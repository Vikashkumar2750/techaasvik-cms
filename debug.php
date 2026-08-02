<?php
/**
 * TechAasvik CMS — Server Debugger
 * Access via: https://t1.techaasvik.com/debug.php
 */

// Force display all errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<h1>TechAasvik Server Debugger</h1>";

// 1. PHP Version
echo "<h2>1. PHP Version</h2>";
echo "Current PHP Version: " . phpversion() . "<br>";
if (version_compare(phpversion(), '8.0.0', '<')) {
    echo "<strong style='color:red;'>ERROR: PHP 8.0 or higher is required.</strong><br>";
} else {
    echo "<strong style='color:green;'>OK: PHP version is supported.</strong><br>";
}

// 2. Extensions
echo "<h2>2. Required Extensions</h2>";
$required = ['pdo_mysql', 'mbstring', 'json', 'fileinfo', 'gd'];
$allOk = true;
foreach ($required as $ext) {
    if (extension_loaded($ext)) {
        echo "<span style='color:green;'>OK: $ext is loaded.</span><br>";
    } else {
        echo "<strong style='color:red;'>ERROR: $ext is NOT loaded.</strong><br>";
        $allOk = false;
    }
}
if (!$allOk) {
    echo "<p>Please enable the missing extensions in Hostinger PHP Configuration.</p>";
}

// 3. File Permissions
echo "<h2>3. File Permissions / Paths</h2>";
$paths = [
    __DIR__ . '/app/Config/config.php',
    __DIR__ . '/setup.php',
    __DIR__ . '/.htaccess',
];
foreach ($paths as $path) {
    $basename = basename($path);
    if (file_exists($path)) {
        echo "<span style='color:green;'>OK: $basename exists (" . substr(sprintf('%o', fileperms($path)), -4) . ")</span><br>";
    } else {
        echo "<strong style='color:red;'>ERROR: $basename is missing!</strong><br>";
    }
}

// 4. Try loading config.php
echo "<h2>4. Config Syntax Check</h2>";
try {
    $config = require __DIR__ . '/app/Config/config.php';
    echo "<span style='color:green;'>OK: app/Config/config.php parsed successfully.</span><br>";
} catch (\Throwable $e) {
    echo "<strong style='color:red;'>ERROR in config.php: " . $e->getMessage() . "</strong><br>";
    echo "Line: " . $e->getLine() . "<br>";
}

// 5. Database Connection Test
echo "<h2>5. Database Connection</h2>";
if (isset($config['database'])) {
    $db = $config['database'];
    try {
        $pdo = new PDO(
            "mysql:host={$db['host']};port={$db['port']};dbname={$db['name']};charset=utf8mb4",
            $db['user'],
            $db['pass'],
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
        echo "<span style='color:green;'>OK: Database connected successfully!</span><br>";
    } catch (\PDOException $e) {
        echo "<strong style='color:red;'>ERROR connecting to Database: " . $e->getMessage() . "</strong><br>";
    }
} else {
    echo "<strong style='color:red;'>ERROR: Database config missing.</strong><br>";
}

echo "<h2>6. Check Setup.php Execution</h2>";
echo "<p>If the above checks are all green, try running: <a href='/setup.php?token=setup_ta_2026_xK9mP2'>Run Setup Script</a></p>";
echo "<p><em>Make sure to delete debug.php and setup.php when done.</em></p>";
