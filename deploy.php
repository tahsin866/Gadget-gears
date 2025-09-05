<?php
/**
 * Laravel cPanel Deployment Helper
 * Upload this file to your cPanel root directory and run it via browser
 * Example: https://yourdomain.com/deploy.php
 */

echo "<h2>Laravel cPanel Deployment Helper</h2>";

// Function to run artisan commands
function runArtisan($command) {
    $output = [];
    $return_var = 0;
    exec("php artisan $command 2>&1", $output, $return_var);
    return [
        'output' => implode("\n", $output),
        'success' => $return_var === 0
    ];
}

// Function to create symbolic link
function createStorageLink() {
    $linkPath = __DIR__ . '/public/storage';
    $targetPath = __DIR__ . '/storage/app/public';

    if (is_link($linkPath)) {
        return "Storage link already exists.";
    }

    if (symlink($targetPath, $linkPath)) {
        return "Storage link created successfully!";
    } else {
        return "Failed to create storage link. Please create it manually.";
    }
}

// Function to set permissions
function setPermissions() {
    $directories = [
        'storage',
        'bootstrap/cache',
        'public/storage'
    ];

    $results = [];
    foreach ($directories as $dir) {
        if (is_dir($dir)) {
            chmod($dir, 0755);
            $results[] = "Set permissions for $dir";
        }
    }

    return implode("\n", $results);
}

echo "<h3>Step 1: Creating Storage Link</h3>";
echo "<pre>" . createStorageLink() . "</pre>";

echo "<h3>Step 2: Setting Permissions</h3>";
echo "<pre>" . setPermissions() . "</pre>";

echo "<h3>Step 3: Clearing Caches</h3>";
$commands = ['config:clear', 'cache:clear', 'route:clear', 'view:clear'];
foreach ($commands as $command) {
    $result = runArtisan($command);
    echo "<pre>php artisan $command: " . ($result['success'] ? 'SUCCESS' : 'FAILED') . "</pre>";
    if (!$result['success']) {
        echo "<pre>Error: " . $result['output'] . "</pre>";
    }
}

echo "<h3>Step 4: Caching for Production</h3>";
$cacheCommands = ['config:cache', 'route:cache', 'view:cache'];
foreach ($cacheCommands as $command) {
    $result = runArtisan($command);
    echo "<pre>php artisan $command: " . ($result['success'] ? 'SUCCESS' : 'FAILED') . "</pre>";
    if (!$result['success']) {
        echo "<pre>Error: " . $result['output'] . "</pre>";
    }
}

echo "<h3>Step 5: Optimizing</h3>";
$result = runArtisan('optimize');
echo "<pre>php artisan optimize: " . ($result['success'] ? 'SUCCESS' : 'FAILED') . "</pre>";

echo "<hr>";
echo "<h3>Manual Steps Required:</h3>";
echo "<ol>";
echo "<li>Update your .env file with production database credentials</li>";
echo "<li>Set APP_ENV=production in your .env file</li>";
echo "<li>Set APP_DEBUG=false in your .env file</li>";
echo "<li>Update APP_URL to your domain in .env file</li>";
echo "<li>Run database migrations if needed</li>";
echo "<li>Delete this deploy.php file for security</li>";
echo "</ol>";

echo "<p><strong>Remember to delete this file after deployment for security!</strong></p>";
?>
