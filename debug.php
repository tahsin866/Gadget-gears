<?php
// Temporary error checker - Remove after fixing issues
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "=== Laravel Error Checker ===\n";
echo "PHP Version: " . phpversion() . "\n";
echo "Current Directory: " . getcwd() . "\n";

// Check if Laravel files exist
$requiredFiles = [
    'vendor/autoload.php',
    'bootstrap/app.php',
    '.env',
    'public/index.php'
];

echo "\n=== File Checks ===\n";
foreach ($requiredFiles as $file) {
    if (file_exists($file)) {
        echo "✓ $file exists\n";
    } else {
        echo "✗ $file MISSING\n";
    }
}

// Check permissions
echo "\n=== Permission Checks ===\n";
$writableDirectories = [
    'storage',
    'storage/app',
    'storage/framework',
    'storage/framework/cache',
    'storage/framework/sessions',
    'storage/framework/views',
    'storage/logs',
    'bootstrap/cache'
];

foreach ($writableDirectories as $dir) {
    if (is_dir($dir)) {
        if (is_writable($dir)) {
            echo "✓ $dir is writable\n";
        } else {
            echo "✗ $dir is NOT writable\n";
        }
    } else {
        echo "✗ $dir does not exist\n";
    }
}

// Check if .env is readable
echo "\n=== Environment Check ===\n";
if (file_exists('.env')) {
    $envContent = file_get_contents('.env');
    if ($envContent !== false) {
        echo "✓ .env file is readable\n";
        echo "APP_ENV: " . (preg_match('/APP_ENV=(.*)/', $envContent, $matches) ? $matches[1] : 'not found') . "\n";
        echo "APP_DEBUG: " . (preg_match('/APP_DEBUG=(.*)/', $envContent, $matches) ? $matches[1] : 'not found') . "\n";
        echo "APP_KEY: " . (preg_match('/APP_KEY=(.*)/', $envContent, $matches) ? 'set' : 'not found') . "\n";
    } else {
        echo "✗ Cannot read .env file\n";
    }
} else {
    echo "✗ .env file does not exist\n";
}

// Try to load Laravel
echo "\n=== Laravel Bootstrap Test ===\n";
try {
    if (file_exists('vendor/autoload.php')) {
        require 'vendor/autoload.php';
        echo "✓ Autoloader loaded successfully\n";

        if (file_exists('bootstrap/app.php')) {
            $app = require_once 'bootstrap/app.php';
            echo "✓ Laravel app bootstrapped successfully\n";
        } else {
            echo "✗ bootstrap/app.php not found\n";
        }
    } else {
        echo "✗ vendor/autoload.php not found - run 'composer install'\n";
    }
} catch (Exception $e) {
    echo "✗ Error loading Laravel: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}

echo "\n=== Server Information ===\n";
echo "Server Software: " . ($_SERVER['SERVER_SOFTWARE'] ?? 'Unknown') . "\n";
echo "Document Root: " . ($_SERVER['DOCUMENT_ROOT'] ?? 'Unknown') . "\n";
echo "Script Name: " . ($_SERVER['SCRIPT_NAME'] ?? 'Unknown') . "\n";
echo "Request URI: " . ($_SERVER['REQUEST_URI'] ?? 'Unknown') . "\n";

echo "\n=== PHP Extensions ===\n";
$requiredExtensions = ['pdo', 'pdo_mysql', 'openssl', 'mbstring', 'tokenizer', 'xml', 'ctype', 'json'];
foreach ($requiredExtensions as $ext) {
    if (extension_loaded($ext)) {
        echo "✓ $ext loaded\n";
    } else {
        echo "✗ $ext NOT loaded\n";
    }
}
?>
