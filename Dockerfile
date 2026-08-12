FROM php:8.2-fpm

# Update apt and install system dependencies
RUN apt-get update && apt-get install -y \
    git curl zip unzip sqlite3 \
    libcurl4-openssl-dev libsqlite3-dev libonig-dev \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_sqlite pdo_mysql mbstring curl bcmath ctype fileinfo

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install Node.js
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - && apt-get install -y nodejs && rm -rf /var/lib/apt/lists/*

# Copy app and setup
COPY . /app
WORKDIR /app

# Install composer dependencies
RUN composer install --no-dev --optimize-autoloader 2>&1 | grep -v "^$"

# Create .env if missing
RUN if [ ! -f .env ]; then cp .env.example .env; fi

# Generate APP_KEY
RUN php artisan key:generate 2>/dev/null || true

# Build frontend assets
RUN npm install --no-audit && npm run build 2>&1 | tail -20

# Create storage directories with proper permissions
RUN mkdir -p storage/logs database && \
    chmod -R 777 storage bootstrap/cache database && \
    touch database/database.sqlite && \
    chmod 666 database/database.sqlite

# Expose port
EXPOSE 8000

# Simple startup script
RUN cat > /start.sh << 'EOF'
#!/bin/bash
set -e

echo "=== Starting Rencontre Éthique ==="
echo "Current time: $(date)"
echo "Working directory: $(pwd)"

# Permissions
echo "Setting permissions..."
chmod -R 777 /app/storage /app/bootstrap/cache /app/database

# Clear caches
echo "Clearing caches..."
php artisan config:clear || true
php artisan cache:clear || true

# Migrations
echo "Running migrations..."
php artisan migrate --force || true

# Seeding
echo "Seeding database..."
php artisan db:seed --class=AdminSeeder --force || true

echo "=== Starting Laravel development server ==="
echo "Listening on 0.0.0.0:8000"
php artisan serve --host=0.0.0.0 --port=8000
EOF

RUN chmod +x /start.sh

# Run startup script
CMD ["/start.sh"]
