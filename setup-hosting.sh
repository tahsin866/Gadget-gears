#!/bin/bash

# cPanel Laravel Setup Script
# Run this script on your cPanel hosting to setup Laravel

echo "🚀 Starting cPanel Laravel Setup..."

# Get current directory
CURRENT_DIR=$(pwd)
echo "📍 Current directory: $CURRENT_DIR"

# Check if we're in the right directory
if [ ! -f "composer.json" ]; then
  echo "❌ composer.json not found. Please navigate to your Laravel project directory."
  exit 1
fi

# Detect PHP version
PHP_CMD="php"
if command -v php84 >/dev/null 2>&1; then
  PHP_CMD="php84"
elif command -v php83 >/dev/null 2>&1; then
  PHP_CMD="php83"
elif command -v php82 >/dev/null 2>&1; then
  PHP_CMD="php82"
elif command -v php81 >/dev/null 2>&1; then
  PHP_CMD="php81"
elif command -v php80 >/dev/null 2>&1; then
  PHP_CMD="php80"
fi

echo "🐘 Using PHP: $PHP_CMD"
$PHP_CMD -v

# Find Composer
COMPOSER_CMD=""
if command -v composer >/dev/null 2>&1; then
  COMPOSER_CMD="composer"
elif [ -f "/opt/cpanel/composer/bin/composer" ]; then
  COMPOSER_CMD="/opt/cpanel/composer/bin/composer"
elif [ -f "/usr/bin/composer" ]; then
  COMPOSER_CMD="/usr/bin/composer"
elif [ -f "/usr/local/bin/composer" ]; then
  COMPOSER_CMD="/usr/local/bin/composer"
else
  echo "📦 Downloading Composer..."
  curl -sS https://getcomposer.org/installer | $PHP_CMD -- --install-dir=. --filename=composer
  COMPOSER_CMD="$PHP_CMD ./composer"
fi

echo "📦 Using Composer: $COMPOSER_CMD"

# Install PHP dependencies
echo "📦 Installing Composer dependencies..."
$PHP_CMD $COMPOSER_CMD install --no-dev --optimize-autoloader --no-interaction --ignore-platform-reqs || {
  echo "⚠️ Composer install failed, trying update..."
  $PHP_CMD $COMPOSER_CMD update --no-dev --optimize-autoloader --no-interaction --ignore-platform-reqs || {
    echo "⚠️ Removing vendor and lock file..."
    rm -rf vendor composer.lock
    $PHP_CMD $COMPOSER_CMD install --no-dev --optimize-autoloader --no-interaction --ignore-platform-reqs || {
      echo "❌ Composer failed completely"
      exit 1
    }
  }
}

# NPM operations (if available)
if command -v npm >/dev/null 2>&1; then
  echo "📦 Installing NPM dependencies..."
  npm ci --production || {
    echo "⚠️ NPM ci failed, trying npm install..."
    rm -rf node_modules package-lock.json
    npm install --production || {
      echo "⚠️ NPM install failed, skipping frontend build"
    }
  }

  if [ -f "package.json" ]; then
    echo "🏗️ Building frontend assets..."
    npm run build || {
      echo "⚠️ Frontend build failed"
    }
  fi
else
  echo "⚠️ NPM not found, skipping frontend build"
fi

# Set file permissions
echo "🔐 Setting file permissions..."
chmod -R 755 storage bootstrap/cache 2>/dev/null || {
  find storage -type d -exec chmod 755 {} \; 2>/dev/null || true
  find storage -type f -exec chmod 644 {} \; 2>/dev/null || true
  find bootstrap/cache -type d -exec chmod 755 {} \; 2>/dev/null || true
  find bootstrap/cache -type f -exec chmod 644 {} \; 2>/dev/null || true
}

# Create .env if it doesn't exist
if [ ! -f ".env" ]; then
  echo "📝 Creating .env file..."
  cp .env.example .env || {
    echo "❌ Failed to create .env file"
    exit 1
  }
  echo "⚠️ Please edit .env file with your database credentials"
fi

# Generate app key if needed
if ! grep -q "APP_KEY=" .env || [ "$(grep 'APP_KEY=' .env | cut -d'=' -f2)" = "" ]; then
  echo "🔑 Generating application key..."
  $PHP_CMD artisan key:generate || {
    echo "❌ Failed to generate app key"
  }
fi

# Create storage symbolic link
if [ ! -L "public/storage" ]; then
  echo "🔗 Creating storage symbolic link..."
  $PHP_CMD artisan storage:link || {
    echo "⚠️ Failed to create storage link with artisan, trying manual..."
    ln -sf ../storage/app/public public/storage || {
      echo "❌ Failed to create storage symbolic link"
    }
  }
fi

# Clear and cache Laravel
echo "🧹 Clearing Laravel caches..."
$PHP_CMD artisan config:clear 2>/dev/null || echo "⚠️ Config clear failed"
$PHP_CMD artisan cache:clear 2>/dev/null || echo "⚠️ Cache clear failed"
$PHP_CMD artisan route:clear 2>/dev/null || echo "⚠️ Route clear failed"
$PHP_CMD artisan view:clear 2>/dev/null || echo "⚠️ View clear failed"

echo "💾 Caching for production..."
$PHP_CMD artisan config:cache 2>/dev/null || echo "⚠️ Config cache failed"
$PHP_CMD artisan route:cache 2>/dev/null || echo "⚠️ Route cache failed"
$PHP_CMD artisan view:cache 2>/dev/null || echo "⚠️ View cache failed"

echo "⚡ Optimizing application..."
$PHP_CMD artisan optimize 2>/dev/null || echo "⚠️ Optimize failed"

# Ask about database migration
echo ""
read -p "Do you want to run database migrations? (y/N): " -n 1 -r
echo
if [[ $REPLY =~ ^[Yy]$ ]]; then
  echo "🗃️ Running database migrations..."
  $PHP_CMD artisan migrate --force || {
    echo "⚠️ Migration failed. Please check your database credentials in .env"
  }
fi

echo ""
echo "✅ Laravel setup completed successfully!"
echo ""
echo "📋 Next steps:"
echo "1. Edit .env file with your database credentials"
echo "2. Set APP_ENV=production and APP_DEBUG=false in .env"
echo "3. Test your website"
echo ""
echo "🔒 Security reminder:"
echo "- Set proper file permissions"
echo "- Remove setup scripts after deployment"
echo "- Keep .env file secure"
