#!/bin/bash

# Install PHP dependencies
composer install --no-dev --optimize-autoloader

# Install Node dependencies and build
npm ci
npm run build

# Generate application key if not exists
php artisan key:generate --force

# Run migrations
php artisan migrate --force

# Start the server
php artisan serve --host=0.0.0.0 --port=$PORT
