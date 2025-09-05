# GitHub Actions Deployment Setup Guide

## Required GitHub Secrets

আপনার GitHub repository তে যেতে হবে এবং এই secrets গুলো add করতে হবে:

### Settings > Secrets and variables > Actions > New repository secret

1. **HOST** - আপনার cPanel server এর IP address বা domain
   ```
   Example: yourdomain.com বা 192.168.1.100
   ```

2. **USERNAME** - আপনার cPanel username
   ```
   Example: cpanel_username
   ```

3. **PORT** - SSH port (সাধারণত 22)
   ```
   Example: 22
   ```

4. **SSHKEY** - আপনার private SSH key
   ```
   SSH key generate করার জন্য:
   1. Terminal এ run করুন: ssh-keygen -t rsa -b 4096 -C "your_email@example.com"
   2. Private key copy করুন: cat ~/.ssh/id_rsa
   3. Public key copy করুন: cat ~/.ssh/id_rsa.pub
   4. Public key cPanel এ add করুন (SSH/Shell Access)
   5. Private key GitHub secret এ add করুন
   ```

## cPanel Setup Requirements

### 1. SSH Access Enable করুন
- cPanel > SSH/Shell Access
- SSH access enable করুন
- Public key add করুন

### 2. Git Install করুন (যদি না থাকে)
```bash
# Check if git is installed
git --version

# If not installed, contact your hosting provider
```

### 3. Repository Clone করুন
```bash
cd /home/yourusername/public_html
git clone https://github.com/tahsin866/Gadget-gears.git .
```

### 4. Initial Setup
```bash
# Install composer dependencies
composer install --no-dev --optimize-autoloader

# Install npm dependencies
npm install

# Build assets
npm run build

# Set permissions
chmod -R 755 storage bootstrap/cache

# Create storage link
php artisan storage:link

# Copy environment file
cp .env.example .env

# Edit .env file with your database credentials
nano .env

# Generate app key
php artisan key:generate

# Run migrations
php artisan migrate

# Cache for production
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Testing the Deployment

1. Push code to main branch:
   ```bash
   git add .
   git commit -m "Setup deployment"
   git push origin main
   ```

2. Check GitHub Actions tab for deployment status

3. If deployment fails, check the logs in GitHub Actions

## Troubleshooting

### Common Issues:

1. **Permission Denied**
   - Check SSH key is correctly added
   - Verify SSH access is enabled in cPanel

2. **Git not found**
   - Contact hosting provider to install Git
   - Or use FTP deployment instead

3. **Composer/NPM not found**
   - Check if they are installed on server
   - Update workflow to install them if needed

4. **Path issues**
   - Verify the paths in the workflow match your cPanel structure
   - Update DOMAIN_PATH in the workflow if needed

## Alternative: Manual Deployment

If GitHub Actions doesn't work, you can use the deploy.php file:

1. Upload all files to cPanel
2. Visit: https://yourdomain.com/deploy.php
3. Follow the instructions
4. Delete deploy.php file after deployment
