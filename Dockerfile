FROM php:8.4-cli

RUN apt-get update && apt-get install -y --no-install-recommends \
        git unzip libsqlite3-dev libzip-dev \
    && docker-php-ext-install pdo_sqlite zip bcmath \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Pasang dependensi dulu supaya layer ini ter-cache
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --prefer-dist --no-scripts --no-autoloader

# Salin kode proyek
COPY . .
RUN composer dump-autoload --no-dev --optimize \
    && mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views bootstrap/cache \
    && chmod -R 777 storage bootstrap/cache

EXPOSE 8000

# Saat start: siapkan .env, kunci aplikasi, database SQLite, migrasi, lalu jalankan server
CMD ["sh", "-c", "[ -f .env ] || cp .env.example .env; php artisan key:generate --force; touch database/database.sqlite; php artisan migrate --force; php artisan serve --host=0.0.0.0 --port=8000"]
