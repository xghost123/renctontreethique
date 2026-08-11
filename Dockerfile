FROM php:8.2-fpm

# Update apt and install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    zip \
    unzip \
    sqlite3 \
    libsqlite3-dev \
    libonig-dev

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
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - && apt-get install -y nodejs

# Copy application
COPY . /app
WORKDIR /app

# Install dependencies
RUN composer install --no-dev --optimize-autoloader
RUN npm install && npm run build

# Create storage directories
RUN mkdir -p storage/logs database
RUN chmod -R 777 storage bootstrap/cache database

# Expose port
EXPOSE 8000

# Health check
HEALTHCHECK --interval=30s --timeout=3s --start-period=40s --retries=3 \
    CMD curl -f http://localhost:8000/ || exit 1

# Start Laravel
CMD ["php", "artisan", "serve", "--host", "0.0.0.0", "--port", "8000"]
