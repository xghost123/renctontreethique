release: mkdir -p storage/logs storage/framework/cache storage/framework/sessions storage/framework/views && php artisan migrate --force && php artisan cache:clear && php artisan config:clear
web: caddy run --config /app/Caddyfile --adapter caddyfile
