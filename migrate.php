<?php
/**
 * Migration: Add featured_image column to content table
 * Run once on the server, then delete this file.
 *
 * Access via: https://t1.techaasvik.com/migrate.php
 */
error_reporting(E_ALL);
ini_set('display_errors', 1);

define('APP_ROOT', __DIR__);
define('APP_PATH', APP_ROOT . '/app');
define('STORAGE_PATH', APP_ROOT . '/storage');

require_once APP_PATH . '/Config/constants.php';
$config = require APP_PATH . '/Config/config.php';

$dsn = sprintf('mysql:host=%s;port=%d;dbname=%s;charset=%s',
    $config['database']['host'],
    $config['database']['port'],
    $config['database']['name'],
    $config['database']['charset']
);
$pdo = new PDO($dsn, $config['database']['user'], $config['database']['pass'], [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
]);

$migrations = [
    "ALTER TABLE content ADD COLUMN featured_image VARCHAR(500) NULL COMMENT 'URL of featured image' AFTER featured_image_id",
];

echo "<h2>Running Migrations</h2>";
foreach ($migrations as $sql) {
    try {
        $pdo->exec($sql);
        echo "✅ " . htmlspecialchars(substr($sql, 0, 80)) . "...<br>";
    } catch (PDOException $e) {
        if (str_contains($e->getMessage(), 'Duplicate column')) {
            echo "⏭ Column already exists, skipping.<br>";
        } else {
            echo "❌ " . $e->getMessage() . "<br>";
        }
    }
}

echo "<hr><strong>Done!</strong> Delete this file now: <code>migrate.php</code>";
