# Laravel Hosting Migration Checklist

## 🚨 500 Internal Server Error সমাধানের ধাপসমূহ:

### ১. ফাইল আপলোড চেক করুন:
- ✅ সব Laravel ফাইল আপলোড হয়েছে কিনা
- ✅ .env ফাইল আপলোড হয়েছে কিনা
- ✅ vendor/ ফোল্ডার আপলোড হয়েছে কিনা

### ২. Hosting Environment Setup:
```bash
# cPanel File Manager বা SSH-এ এই commands চালান:
chmod -R 755 storage
chmod -R 755 bootstrap/cache
chmod 644 .env
```

### ৩. Database Configuration:
আপনার `.env` ফাইলে এই settings update করুন:
```
DB_HOST=localhost (usually for shared hosting)
DB_PORT=3306
DB_DATABASE=your_cpanel_database_name
DB_USERNAME=your_cpanel_database_user
DB_PASSWORD=your_cpanel_database_password
```

### ৪. URL Configuration:
```
APP_URL=https://fyohub.com
APP_ENV=production
APP_DEBUG=false
```

### ৫. Laravel Commands (SSH access থাকলে):
```bash
php artisan config:clear
php artisan cache:clear
php artisan storage:link
php artisan migrate --force
```

### ৬. File Permission চেক:
- storage/ ফোল্ডার writable হতে হবে (755)
- bootstrap/cache/ ফোল্ডার writable হতে হবে (755)
- .env ফাইল readable হতে হবে (644)

### ৭. Error Debugging:
১. আপনার ডোমেইনে `/debug.php` visit করুন
২. সেখানে কি error দেখাচ্ছে check করুন
৩. `/setup.php` file run করুন Laravel commands execute করার জন্য

### ৮. Common Issues:
- **Composer না থাকলে:** vendor/ ফোল্ডার manually আপলোড করুন
- **PHP Version:** minimum PHP 8.1 required
- **Database:** MySQL database তৈরি করুন cPanel থেকে
- **Document Root:** public/ ফোল্ডার point করুন

### ৯. Security (Setup complete হওয়ার পর):
- debug.php ফাইল delete করুন
- setup.php ফাইল delete করুন
- APP_DEBUG=false করুন .env ফাইলে

## 📞 Support:
যদি এখনও error থাকে, error log check করুন:
- cPanel Error Logs দেখুন
- storage/logs/laravel.log দেখুন
