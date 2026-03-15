# Use Node.js 22 as base image
FROM node:22-alpine AS node

# Install PHP and Composer
RUN apk add --no-cache php php-dom php-mbstring php-xml php-pdo php-pdo_mysql composer

# Set working directory
WORKDIR /app

# Copy package files
COPY package*.json ./

# Install Node dependencies (fallback to npm install if lock file doesn't exist)
RUN npm ci || npm install

# Copy Laravel files
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Generate application key
RUN php artisan key:generate

# Build frontend assets
RUN npm run build

# Expose port
EXPOSE 8000

# Start the application
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
