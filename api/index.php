<?php

// Ensure serverless /tmp directories exist for Laravel storage & caching
$dirs = [
    '/tmp/storage/app/public',
    '/tmp/storage/framework/cache',
    '/tmp/storage/framework/views',
    '/tmp/storage/framework/sessions',
    '/tmp/storage/logs',
];

foreach ($dirs as $dir) {
    if (! is_dir($dir)) {
        mkdir($dir, 0777, true);
    }
}

// For SQLite on Vercel: copy database if needed or set up /tmp/database.sqlite
if (getenv('DB_CONNECTION') === 'sqlite' || (! getenv('DB_CONNECTION') && ! getenv('DB_HOST'))) {
    $sqlitePath = '/tmp/database.sqlite';
    if (! file_exists($sqlitePath)) {
        $sourceDb = __DIR__ . '/../database/database.sqlite';
        if (file_exists($sourceDb)) {
            copy($sourceDb, $sqlitePath);
        } else {
            touch($sqlitePath);
        }
    }
    putenv("DB_DATABASE={$sqlitePath}");
    $_ENV['DB_DATABASE'] = $sqlitePath;
    $_SERVER['DB_DATABASE'] = $sqlitePath;
}

require __DIR__ . '/../public/index.php';
