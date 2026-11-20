#!/bin/sh
set -e

PORT="${PORT:-10000}"

if [ -z "$APP_KEY" ]; then
  echo "APP_KEY is missing. Add it under Environment in your Render service."
  exit 1
fi

if [ -z "$DB_URL" ]; then
  echo "DB_URL is missing. Without an external database every account is lost when the server restarts."
  exit 1
fi

export DB_CONNECTION="${DB_CONNECTION:-pgsql}"

sed -ri "s/^Listen 80$/Listen ${PORT}/" /etc/apache2/ports.conf
sed -ri "s/<VirtualHost \*:[0-9]+>/<VirtualHost *:${PORT}>/" /etc/apache2/sites-available/000-default.conf

mkdir -p storage/framework/cache/data storage/framework/sessions storage/framework/views storage/logs bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

php artisan migrate --force

exec apache2-foreground
