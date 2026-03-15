#!/bin/bash

# Install dependencies
composer install --no-dev --optimize-autoloader

# Generate application key if not exists
php artisan key:generate --force

# Run migrations
php artisan migrate --force

# Start the server
php artisan serve --host=0.0.0.0 --port=$PORT
