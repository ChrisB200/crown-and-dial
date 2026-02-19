#!/bin/sh
set -e

echo "Starting Laravel container..."

# If .env doesn't exist, copy example
if [ ! -f .env ]; then
    echo "Creating .env file..."
    cp .env.example .env
fi

# Generate app key if not set
if ! grep -q "^APP_KEY=base64" .env; then
    echo "Generating APP_KEY..."
    php artisan key:generate --force
fi

# Wait for MySQL
echo "Waiting for database..."
until php -r "
try {
    new PDO('mysql:host=' . getenv('DB_HOST') . ';port=' . getenv('DB_PORT'), getenv('DB_USERNAME'), getenv('DB_PASSWORD'));
    exit(0);
} catch (Exception \$e) {
    exit(1);
}
"; do
    sleep 2
done

echo "Database is ready."

# Run migrations
php artisan migrate --force

php artisan db:seed --force

# Cache config for production
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Starting PHP-FPM..."

exec php-fpm
