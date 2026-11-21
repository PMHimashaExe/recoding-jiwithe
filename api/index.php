<?php

// Prepare writable /tmp directories required for Vercel Serverless execution
$tmpDirs = [
    '/tmp/views',
    '/tmp/sessions',
    '/tmp/cache',
];

foreach ($tmpDirs as $dir) {
    if (! is_dir($dir)) {
        @mkdir($dir, 0777, true);
    }
}

// Forward serverless request to Laravel's entry point
require __DIR__ . '/../public/index.php';
