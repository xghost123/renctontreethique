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

# Expose port
EXPOSE 8000

# Health check
HEALTHCHECK --interval=30s --timeout=3s --start-period=40s --retries=3 \
    CMD curl -f http://localhost:8000/ || exit 1

# Create entrypoint script for migrations with logging
RUN cat > /app/start.sh << 'EOF'
#!/bin/bash
set -e

echo "[$(date +'%Y-%m-%d %H:%M:%S')] Starting Laravel application..."

# Set permissions
chmod -R 777 /app/storage /app/bootstrap/cache /app/database 2>/dev/null || true

# Clear caches
echo "[$(date +'%Y-%m-%d %H:%M:%S')] Clearing config and cache..."
php artisan config:clear 2>/dev/null || true
php artisan cache:clear 2>/dev/null || true

# Run migrations
echo "[$(date +'%Y-%m-%d %H:%M:%S')] Running migrations..."
php artisan migrate --force 2>&1 || echo "Migration warning (may have already run)"

# Seed admin user
echo "[$(date +'%Y-%m-%d %H:%M:%S')] Seeding admin user..."
php artisan db:seed --class=AdminSeeder --force 2>&1 || echo "Seeding warning (may have already run)"

echo "[$(date +'%Y-%m-%d %H:%M:%S')] Starting Laravel server..."
exec php artisan serve --host 0.0.0.0 --port 8000
EOF

RUN chmod +x /app/start.sh

# Start Laravel with migrations
CMD ["/app/start.sh"]
