FROM php:8.2-fpm

# Update apt and install ALL system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libcurl4-openssl-dev \
    zip \
    unzip \
    sqlite3 \
    libsqlite3-dev \
    libonig-dev \
    nginx \
    supervisor \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
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

# Generate APP_KEY and build assets
RUN if [ ! -f .env ]; then cp .env.example .env; fi
RUN php artisan key:generate 2>/dev/null || true
RUN npm install && npm run build

# Create storage directories
RUN mkdir -p storage/logs database
RUN chmod -R 777 storage bootstrap/cache database

# Create database file
RUN touch database/database.sqlite && chmod 666 database/database.sqlite

# Configure PHP-FPM
RUN mkdir -p /run/php && \
    echo "[global]" > /usr/local/etc/php-fpm.conf && \
    echo "daemonize = no" >> /usr/local/etc/php-fpm.conf && \
    echo "[www]" >> /usr/local/etc/php-fpm.conf && \
    echo "listen = 0.0.0.0:9000" >> /usr/local/etc/php-fpm.conf && \
    echo "pm = dynamic" >> /usr/local/etc/php-fpm.conf && \
    echo "pm.max_children = 20" >> /usr/local/etc/php-fpm.conf && \
    echo "pm.start_servers = 5" >> /usr/local/etc/php-fpm.conf && \
    echo "pm.min_spare_servers = 2" >> /usr/local/etc/php-fpm.conf && \
    echo "pm.max_spare_servers = 10" >> /usr/local/etc/php-fpm.conf

# Configure Nginx
RUN mkdir -p /etc/nginx/sites-available /etc/nginx/sites-enabled && \
    cat > /etc/nginx/nginx.conf << 'NGINX_CONF'
user www-data;
worker_processes auto;
pid /run/nginx.pid;

events {
    worker_connections 1024;
}

http {
    sendfile on;
    tcp_nopush on;
    tcp_nodelay on;
    keepalive_timeout 65;
    types_hash_max_size 2048;
    client_max_body_size 20M;

    include /etc/nginx/mime.types;
    default_type application/octet-stream;

    access_log /dev/stdout;
    error_log /dev/stderr;

    gzip on;

    server {
        listen 0.0.0.0:8000;
        server_name _;
        root /app/public;
        index index.php;

        location / {
            try_files $uri $uri/ /index.php?$query_string;
        }

        location ~ \.php$ {
            fastcgi_pass 127.0.0.1:9000;
            fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
            include fastcgi_params;
        }

        location ~ /\.ht {
            deny all;
        }
    }
}
NGINX_CONF

# Configure Supervisor
RUN mkdir -p /etc/supervisor/conf.d && \
    cat > /etc/supervisor/conf.d/services.conf << 'SUPERVISOR_CONF'
[supervisord]
nodaemon=true
logfile=/dev/null
pidfile=/var/run/supervisord.pid

[program:php-fpm]
command=php-fpm
autostart=true
autorestart=true
stderr_logfile=/dev/stderr
stdout_logfile=/dev/stdout

[program:nginx]
command=nginx -g "daemon off;"
autostart=true
autorestart=true
stderr_logfile=/dev/stderr
stdout_logfile=/dev/stdout
SUPERVISOR_CONF

# Create startup script
RUN cat > /app/start.sh << 'STARTUP'
#!/bin/bash
set -e

echo "Starting Rencontre Éthique application..."

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

echo "Starting services with supervisor..."

# Start supervisor (which manages PHP-FPM and Nginx)
exec /usr/bin/supervisord -c /etc/supervisor/supervisord.conf
STARTUP

RUN chmod +x /app/start.sh

EXPOSE 8000

CMD ["/app/start.sh"]
