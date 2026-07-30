#!/bin/sh

echo "===================================="
echo "Laravel Starting..."
echo "===================================="

# Generate APP_KEY
php artisan key:generate --force || true

# Clear caches
php artisan optimize:clear || true

# Rebuild autoload
composer dump-autoload --optimize || true

# Generate Passport Keys if missing
if [ ! -f storage/oauth-private.key ]; then
    echo "Generating Passport Keys..."
    php artisan passport:keys --force || true
fi

# Run migrations
php artisan migrate --force || true

# Install Passport Clients
php artisan passport:install --force || true

# Cache configs
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

echo "===================================="
echo "Laravel Ready..."
echo "===================================="

php artisan serve \
    --host=0.0.0.0 \
    --port=${PORT:-10000}