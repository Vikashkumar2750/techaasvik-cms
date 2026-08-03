<?php
/**
 * Quick diagnostic — tests blog and admin routes
 * DELETE THIS FILE after debugging
 */
error_reporting(E_ALL);
ini_set('display_errors', 1);

define('APP_ROOT', __DIR__);
define('APP_PATH', APP_ROOT . '/app');
define('VIEWS_PATH', APP_ROOT . '/views');
define('STORAGE_PATH', APP_ROOT . '/storage');
define('ASSETS_PATH', APP_ROOT . '/assets');

echo "<h2>TechAasvik Diagnostics</h2>";

// 1. Config load test
echo "<h3>1. Config Load</h3>";
try {
    require_once APP_PATH . '/Config/constants.php';
    $config = require APP_PATH . '/Config/config.php';
    echo "✅ Config loaded. DB host: " . ($config['database']['host'] ?? 'MISSING') . "<br>";
    echo "DB name: " . ($config['database']['name'] ?? 'MISSING') . "<br>";
    echo "DB user: " . ($config['database']['user'] ?? 'MISSING') . "<br>";
    echo "DB pass set: " . (!empty($config['database']['pass']) && $config['database']['pass'] !== 'DB_PASSWORD_HERE' ? 'YES' : 'NO (still template!)') . "<br>";
    echo "Cache enabled: " . ($config['cache']['enabled'] ? 'YES' : 'NO') . "<br>";
} catch (Throwable $e) {
    echo "❌ Config Error: " . $e->getMessage() . " on line " . $e->getLine() . "<br>";
}

// 2. Database connection test
echo "<h3>2. Database Connection</h3>";
try {
    $dsn = sprintf('mysql:host=%s;port=%d;dbname=%s;charset=%s',
        $config['database']['host'],
        $config['database']['port'],
        $config['database']['name'],
        $config['database']['charset']
    );
    $pdo = new PDO($dsn, $config['database']['user'], $config['database']['pass'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);
    $count = $pdo->query("SELECT COUNT(*) FROM content")->fetchColumn();
    echo "✅ DB connected. Content rows: $count<br>";
} catch (Throwable $e) {
    echo "❌ DB Error: " . $e->getMessage() . "<br>";
}

// 3. Session test
echo "<h3>3. Session</h3>";
try {
    session_start();
    echo "✅ Session started. ID: " . session_id() . "<br>";
} catch (Throwable $e) {
    echo "❌ Session Error: " . $e->getMessage() . "<br>";
}

// 4. Check critical view files
echo "<h3>4. View Files</h3>";
$views = ['blog-index', 'admin-login', 'home', 'post', 'about'];
foreach ($views as $v) {
    $file = VIEWS_PATH . '/pages/' . $v . '.php';
    echo (file_exists($file) ? "✅" : "❌") . " $v.php " . (file_exists($file) ? "exists" : "MISSING") . "<br>";
}

// 5. Check layouts
echo "<h3>5. Layouts</h3>";
$layouts = ['main', 'minimal', 'admin'];
foreach ($layouts as $l) {
    $file = VIEWS_PATH . '/layouts/' . $l . '.php';
    echo (file_exists($file) ? "✅" : "❌") . " $l.php " . (file_exists($file) ? "exists" : "MISSING") . "<br>";
}

// 6. Check config.local.php presence
echo "<h3>6. Config.local.php</h3>";
$localFile = APP_PATH . '/Config/config.local.php';
echo (file_exists($localFile) ? "✅ config.local.php exists" : "❌ config.local.php MISSING") . "<br>";

// 7. Check storage directories
echo "<h3>7. Storage Dirs</h3>";
$dirs = ['storage/logs', 'storage/cache', 'storage/cache/fragments', 'storage/cache/pages'];
foreach ($dirs as $d) {
    $path = APP_ROOT . '/' . $d;
    echo (is_dir($path) ? "✅" : "❌") . " $d " . (is_dir($path) ? "exists" : "MISSING") . "<br>";
}

echo "<hr><small>Delete this file when done: diagnostics.php</small>";
