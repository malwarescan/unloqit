FROM php:8.2-cli

# Install system dependencies and MySQL PDO extensions
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) pdo pdo_mysql gd \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /app

# Copy composer files
COPY composer.json composer.lock ./

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

# Copy application files
COPY . .

# Run composer scripts (post-install hooks)
RUN composer dump-autoload --optimize

# Verify MySQL extensions are installed
RUN php -m | grep -i mysql && php -m | grep -i pdo || (echo "ERROR: MySQL extensions not found!" && exit 1)

# Copy start script
COPY start.sh /app/start.sh
RUN chmod +x /app/start.sh

# Expose port
EXPOSE 8080

# Start PHP built-in server via script
CMD ["/app/start.sh"]
