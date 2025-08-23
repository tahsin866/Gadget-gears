#!/bin/bash
# Laravel Hosting Setup Script
# Run this script after uploading files to hosting

echo "=== Laravel Hosting Setup ==="

# Set proper permissions
echo "Setting permissions..."
chmod -R 755 storage
chmod -R 755 bootstrap/cache
chmod 644 .env

# Create symlink for storage
echo "Creating storage symlink..."
php artisan storage:link

# Clear all caches
echo "Clearing caches..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Optimize for production
echo "Optimizing for production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run migrations
echo "Running migrations..."
php artisan migrate --force

echo "Setup complete!"
echo "If you see errors, check the debug.php file in your browser"
