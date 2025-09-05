# cPanel Hosting Setup & Troubleshooting Guide

## � GitHub Actions Deployment Issue সমাধান

### সমস্যা: Script Exit Code 1
আপনার deployment PHP 8.4 পেয়েছে কিন্তু কোথাও একটা command fail হয়েছে।

### সম্ভাব্য কারণসমূহ:
1. **Git clone/pull fail** - Repository access issue
2. **Composer install fail** - Package conflicts  
3. **NPM install/build fail** - Node modules issue
4. **Permission denied** - File/directory permissions
5. **Path not found** - Directory structure issue

### সমাধান করা হয়েছে:
1. ✅ **Detailed error logging** যোগ করেছি
2. ✅ **Fallback strategies** implement করেছি
3. ✅ **Better error handling** with exit codes
4. ✅ **Platform requirements ignore** করেছি Composer এর জন্য
5. ✅ **Step-by-step verification** যোগ করেছি

## 🔧 Manual Deployment (Backup Option)

যদি GitHub Actions কাজ না করে, manual deployment করুন:

### SSH দিয়ে:
```bash
# Server এ connect করুন
ssh username@yourdomain.com

# public_html এ যান  
cd public_html

# Repository clone/update করুন
git clone https://github.com/tahsin866/Gadget-gears.git .
# অথবা যদি আগে থেকে আছে:
git pull origin main

# Dependencies install করুন
php84 composer.phar install --no-dev --optimize-autoloader --ignore-platform-reqs

# Frontend build করুন
npm ci --production
npm run build

# Permissions set করুন
chmod -R 755 storage bootstrap/cache

# Storage link তৈরি করুন
php84 artisan storage:link

# Laravel cache করুন
php84 artisan config:cache
php84 artisan route:cache  
php84 artisan view:cache
```

### cPanel File Manager দিয়ে:
1. **Zip file upload** করুন
2. **Extract** করুন public_html এ
3. **File permissions** ঠিক করুন:
   - storage: 755
   - bootstrap/cache: 755
   - .env: 644

## 🎯 Environment Setup Requirements

### 1. **GitHub Secrets যাচাই করুন:**
- `HOST` - সঠিক domain/IP
- `USERNAME` - cPanel username
- `PORT` - SSH port (22)
- `SSHKEY` - Private SSH key
- `PASSPHRASE` - Key passphrase (যদি থাকে)

### 2. **cPanel Settings:**
- SSH access enabled
- Git installed
- PHP 8.2+ available
- Composer accessible

### 3. **Database Configuration:**
```env
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=your_db_name
DB_USERNAME=your_db_user
DB_PASSWORD=your_db_password
```

## 🔍 Debugging Steps

### 1. **SSH Test:**
```bash
ssh username@yourdomain.com
php -v
composer --version
git --version
npm --version
```

### 2. **Manual Commands Test:**
```bash
cd /home/username/public_html
git clone https://github.com/tahsin866/Gadget-gears.git test-deploy
cd test-deploy
php composer.phar install --no-dev --ignore-platform-reqs
```

### 3. **Check Logs:**
- GitHub Actions logs  
- cPanel Error Logs
- Laravel logs: `storage/logs/laravel.log`

## 🛠️ Browser-Based Deployment

**Alternative: deploy.php ব্যবহার করুন**

1. `deploy.php` file আপলোড করুন domain root এ
2. Browser এ visit করুন: `https://yourdomain.com/deploy.php`
3. Instructions follow করুন
4. Complete হওয়ার পর file delete করুন

## 📋 Post-Deployment Checklist

- [ ] Site loads without errors
- [ ] Images display correctly (/storage/products/)
- [ ] Database connected
- [ ] Admin panel accessible
- [ ] Forms working
- [ ] SSL certificate active

## 🔐 Security Checklist

- [ ] APP_DEBUG=false
- [ ] Remove deploy.php
- [ ] Secure file permissions
- [ ] Database credentials secured
- [ ] Error logging enabled

## 📞 Emergency Contacts

### যদি সব fail হয়:
1. **Hosting provider support** contact করুন
2. **Manual FTP upload** করুন
3. **Database manually import** করুন
4. **Basic .htaccess** setup করুন

---

**Next Deployment**: Push to main branch আবার test করুন improved workflow দিয়ে!
