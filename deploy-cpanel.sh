#!/bin/bash

# Laravel cPanel Deployment Script
# Run this script after uploading your Laravel project to cPanel

echo "Starting Laravel cPanel deployment setup..."

# Create symbolic link for storage (if it doesn't exist)
if [ ! -L "public/storage" ]; then
    echo "Creating storage symbolic link..."
    ln -sf ../storage/app/public public/storage
    echo "Storage link created successfully!"
else
    echo "Storage link already exists."
fi

# Set proper permissions for storage and cache directories
echo "Setting proper file permissions..."
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
chmod -R 755 public/storage/

# Clear and cache configuration
echo "Clearing and caching Laravel configurations..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Cache configurations for production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Optimize for production
php artisan optimize

echo "Deployment setup completed!"
echo ""
echo "Please make sure to:"
echo "1. Update your .env file with production database credentials"
echo "2. Set APP_ENV=production in your .env file"
echo "3. Set APP_DEBUG=false in your .env file"
echo "4. Update APP_URL to your domain in .env file"
echo "5. Run 'php artisan migrate' if needed"
