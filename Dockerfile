# syntax=docker/dockerfile:1

# Versi PHP dikunci di satu tempat (bukan "latest" / "8.4-cli" yang bisa bergeser)
ARG PHP_VERSION=8.4.28

# =====================================================================
# TAHAP 1 - BUILD: boleh besar (git, unzip, Composer, cache Composer).
# Tahap ini hanya dipakai untuk menyiapkan /app, lalu dibuang.
# =====================================================================
FROM php:${PHP_VERSION}-cli-alpine AS build

RUN apk add --no-cache git unzip
COPY --from=composer:2.9 /usr/bin/composer /usr/bin/composer
ENV COMPOSER_ALLOW_SUPERUSER=1

WORKDIR /app

# Pasang dependensi dulu supaya layer ini ter-cache (urutan dari Tugas 4)
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --prefer-dist --no-scripts --no-autoloader

# Salin kode proyek, lalu buat autoloader final
COPY . .
RUN composer dump-autoload --no-dev --optimize \
    && mkdir -p storage/framework/cache storage/framework/sessions \
                storage/framework/views storage/logs bootstrap/cache

# =====================================================================
# TAHAP 2 - RUNTIME: hanya yang dibutuhkan saat melayani request.
# Tanpa Composer, git, unzip, atau cache build.
# =====================================================================
FROM php:${PHP_VERSION}-cli-alpine AS runtime

# Pengguna non-root
RUN addgroup -g 1000 -S app \
    && adduser -u 1000 -S -G app app \
    && mkdir -p /var/www/html \
    && chown app:app /var/www/html

WORKDIR /var/www/html

# Ambil hasil build saja, langsung dimiliki pengguna "app"
COPY --from=build --chown=app:app /app ./

USER app

EXPOSE 8000

# /up adalah route health bawaan Laravel (bootstrap/app.php: health: '/up')
HEALTHCHECK --interval=10s --timeout=5s --start-period=20s --retries=3 \
    CMD wget -qO /dev/null http://127.0.0.1:8000/up || exit 1

# Saat start: siapkan .env, kunci aplikasi, database SQLite, migrasi, lalu jalankan server
CMD ["sh", "-c", "[ -f .env ] || cp .env.example .env; php artisan key:generate --force; touch database/database.sqlite; php artisan migrate --force; php artisan serve --host=0.0.0.0 --port=8000"]
