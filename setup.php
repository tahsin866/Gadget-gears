<?php
// Laravel Hosting Setup (PHP version)
echo "<h1>Laravel Hosting Setup</h1>";
echo "<pre>";

// Set proper permissions
echo "Setting permissions...\n";
exec('chmod -R 755 storage 2>&1', $output1);
exec('chmod -R 755 bootstrap/cache 2>&1', $output2);
exec('chmod 644 .env 2>&1', $output3);

if (!empty($output1)) echo "Storage permission output: " . implode("\n", $output1) . "\n";
if (!empty($output2)) echo "Bootstrap/cache permission output: " . implode("\n", $output2) . "\n";

// Clear all caches
echo "Clearing caches...\n";
exec('php artisan config:clear 2>&1', $configClear);
exec('php artisan cache:clear 2>&1', $cacheClear);
exec('php artisan route:clear 2>&1', $routeClear);
exec('php artisan view:clear 2>&1', $viewClear);

echo "Config clear: " . implode("\n", $configClear) . "\n";
echo "Cache clear: " . implode("\n", $cacheClear) . "\n";
echo "Route clear: " . implode("\n", $routeClear) . "\n";
echo "View clear: " . implode("\n", $viewClear) . "\n";

// Create storage symlink
echo "Creating storage symlink...\n";
exec('php artisan storage:link 2>&1', $storageLink);
echo "Storage link: " . implode("\n", $storageLink) . "\n";

// Optimize for production
echo "Optimizing for production...\n";
exec('php artisan config:cache 2>&1', $configCache);
exec('php artisan route:cache 2>&1', $routeCache);
exec('php artisan view:cache 2>&1', $viewCache);

echo "Config cache: " . implode("\n", $configCache) . "\n";
echo "Route cache: " . implode("\n", $routeCache) . "\n";
echo "View cache: " . implode("\n", $viewCache) . "\n";

echo "\nSetup complete! Check for any errors above.\n";
echo "If everything looks good, delete this file for security.\n";
echo "</pre>";
?>
