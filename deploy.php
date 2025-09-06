<?php
/**
 * Enhanced Laravel cPanel Deployment Helper
 * Upload this file to your cPanel root directory and run it via browser
 * Example: https://yourdomain.com/deploy.php
 */

// Security check - delete this file after use
if (file_exists('.env') && strpos(file_get_contents('.env'), 'APP_ENV=production') !== false) {
    echo "<div style='background: #f8d7da; border: 1px solid #f5c6cb; padding: 15px; margin: 20px 0; border-radius: 5px;'>";
    echo "<strong>⚠️ Security Warning:</strong> This is a production environment. Please delete this file after deployment for security!";
    echo "</div>";
}

echo "<h2>🚀 Enhanced Laravel cPanel Deployment Helper</h2>";
echo "<style>
    body { font-family: Arial, sans-serif; max-width: 1200px; margin: 0 auto; padding: 20px; }
    .success { background: #d4edda; border: 1px solid #c3e6cb; padding: 10px; margin: 10px 0; border-radius: 5px; }
    .error { background: #f8d7da; border: 1px solid #f5c6cb; padding: 10px; margin: 10px 0; border-radius: 5px; }
    .warning { background: #fff3cd; border: 1px solid #ffeaa7; padding: 10px; margin: 10px 0; border-radius: 5px; }
    .info { background: #d1ecf1; border: 1px solid #bee5eb; padding: 10px; margin: 10px 0; border-radius: 5px; }
    pre { background: #f8f9fa; padding: 15px; border-radius: 5px; overflow-x: auto; }
</style>";

// Function to detect PHP version
function detectPHP() {
    $phpVersions = ['php84', 'php83', 'php82', 'php81', 'php80', 'php'];
    foreach ($phpVersions as $version) {
        if (shell_exec("command -v $version 2>/dev/null")) {
            return $version;
        }
    }
    return 'php';
}

// Function to run commands with proper PHP version
function runCommand($command, $usePHP = false) {
    if ($usePHP) {
        $phpCmd = detectPHP();
        $command = "$phpCmd $command";
    }

    $output = [];
    $return_var = 0;
    exec("$command 2>&1", $output, $return_var);
    return [
        'output' => implode("\n", $output),
        'success' => $return_var === 0,
        'command' => $command
    ];
}

// Function to run artisan commands
function runArtisan($command) {
    return runCommand("artisan $command", true);
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
