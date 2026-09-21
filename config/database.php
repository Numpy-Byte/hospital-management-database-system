<?php
// Optional local override. This file is ignored by Git, so passwords stay private.
$local_config = __DIR__ . '/database.local.php';
if (file_exists($local_config)) {
    require $local_config;
} else {
    // Default XAMPP settings. Change these only if your MySQL setup differs.
    define('DB_HOST', '127.0.0.1');
    define('DB_PORT', '3306');
    define('DB_NAME', 'hospital_management');
    define('DB_USER', 'root');
    define('DB_PASS', '');
}

function db(): PDO
{
    static $connection = null;
    if ($connection === null) {
        $connection = new PDO(
            'mysql:host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_NAME . ';charset=utf8mb4',
            DB_USER,
            DB_PASS,
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]
        );
    }
    return $connection;
}
