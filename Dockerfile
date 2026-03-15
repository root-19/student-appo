# Use Node.js 22 as base image
FROM node:22-alpine AS node

# Install PHP and Composer with all required extensions
RUN apk add --no-cache php php-dom php-mbstring php-xml php-pdo php-pdo_mysql composer php-session php-fileinfo php-tokenizer php-curl php-zip php-openssl php-iconv

# Set working directory
WORKDIR /app

# Copy package files
COPY package*.json ./

# Install Node dependencies (fallback to npm install if lock file doesn't exist)
RUN npm ci || npm install

# Copy Laravel files
COPY . .

# Create .env file from .env.example if it doesn't exist
RUN if [ ! -f .env ]; then cp .env.example .env; fi

# Set environment variables for Railway database
ENV DB_CONNECTION=mysql
ENV DB_HOST=mysql.railway.internal
ENV DB_PORT=3306
ENV DB_DATABASE=railway
ENV DB_USERNAME=root
ENV DB_PASSWORD=ZsjIxTHCLMYdzkLFEBacqtfHRsDYXCaM
ENV APP_ENV=production
ENV APP_DEBUG=false

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --ignore-platform-reqs

# Generate application key
RUN php artisan key:generate

# Build frontend assets
RUN npm run build

# Expose port
EXPOSE 8000

# Start the application
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
