#!/bin/bash

set -e

echo "Step 1: php artisan down --retry=60"
echo "Step 2: git pull origin main"
echo "Step 3: composer install --no-dev --optimize-autoloader"
echo "Step 4: php artisan migrate --force"
echo "Step 5: php artisan config:cache"
echo "       php artisan route:cache"
echo "       php artisan view:cache"
echo "Step 6: php artisan queue:restart"
echo "Step 7: php artisan up"