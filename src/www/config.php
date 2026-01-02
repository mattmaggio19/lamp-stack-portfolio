<?php
// Load environment variables from .env file
function loadEnv($path) {
    if (!file_exists($path)) {
        return;
    }
    
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        // Skip comments
        if (strpos(trim($line), '#') === 0) {
            continue;
        }
        
        // Parse KEY=VALUE
        list($name, $value) = explode('=', $line, 2);
        $name = trim($name);
        $value = trim($value);
        
        // Set as environment variable if not already set
        if (!getenv($name)) {
            putenv("$name=$value");
        }
    }
}

// Load from parent directory (where .env is located)
loadEnv(__DIR__ . '/../.env');

// Database configuration
define('DB_HOST', getenv('DB_HOST') ?: 'mysql');
define('DB_USER', getenv('DB_USER') ?: 'webuser');
define('DB_PASS', getenv('DB_PASS') ?: 'webpassword');
define('DB_NAME', getenv('DB_NAME') ?: 'portfolio_db');