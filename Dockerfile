FROM php:8.2-fpm

# Update apt and install ALL system dependencies at once
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libcurl4-openssl-dev \
    zip \
    unzip \
    sqlite3 \
    libsqlite3-dev \
    libonig-dev \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions (one by one to avoid conflicts)
RUN docker-php-ext-install pdo
RUN docker-php-ext-install pdo_sqlite
RUN docker-php-ext-install pdo_mysql
RUN docker-php-ext-install mbstring
RUN docker-php-ext-install curl
RUN docker-php-ext-install bcmath
RUN docker-php-ext-install ctype
RUN docker-php-ext-install fileinfo

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install Node.js
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - && apt-get install -y nodejs && rm -rf /var/lib/apt/lists/*

# Copy application
COPY . /app
WORKDIR /app

# Install dependencies
RUN composer install --no-dev --optimize-autoloader

# Generate APP_KEY if not exists and build assets
RUN if [ ! -f .env ]; then cp .env.example .env; fi
RUN php artisan key:generate 2>/dev/null || true
RUN npm install && npm run build

# Create storage directories
RUN mkdir -p storage/logs database
RUN chmod -R 777 storage bootstrap/cache database

# Create database file if it doesn't exist
RUN touch database/database.sqlite && chmod 666 database/database.sqlite

# Expose port (dynamic)
EXPOSE 8000

# Health check
HEALTHCHECK --interval=30s --timeout=3s --start-period=40s --retries=3 \
    CMD curl -f http://localhost:${PORT:-8000}/ || exit 1

# Create entrypoint script that sets up and runs the app
RUN cat > /app/entrypoint.sh << 'EOF'
#!/bin/bash
set -e

echo "Starting Rencontre Éthique application..."

# Get PORT from environment, default to 8000
PORT=${PORT:-8000}
echo "Listening on port $PORT"

# Set permissions
chmod -R 777 /app/storage /app/bootstrap/cache /app/database 2>/dev/null || true

# Clear caches
php artisan config:clear 2>/dev/null || true
php artisan cache:clear 2>/dev/null || true

# Run migrations
echo "Running migrations..."
php artisan migrate --force 2>&1 || echo "Migrations completed or already run"

# Seed admin user
echo "Seeding admin user..."
php artisan db:seed --class=AdminSeeder --force 2>&1 || echo "Seeding completed or already run"

echo "Starting application server on 0.0.0.0:$PORT..."

# Use PHP built-in server with dynamic PORT
exec php -S 0.0.0.0:$PORT -t public public/index.php
EOF

RUN chmod +x /app/entrypoint.sh

# Start application with entrypoint
CMD ["/app/entrypoint.sh"]
