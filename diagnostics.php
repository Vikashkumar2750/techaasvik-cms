<?php
/**
 * Quick diagnostic v2 — shows config.php content and opcache status
 */
error_reporting(E_ALL);
ini_set('display_errors', 1);

define('APP_ROOT', __DIR__);
define('APP_PATH', APP_ROOT . '/app');
define('VIEWS_PATH', APP_ROOT . '/views');
define('STORAGE_PATH', APP_ROOT . '/storage');
define('ASSETS_PATH', APP_ROOT . '/assets');

echo "<h2>TechAasvik Diagnostics v2</h2>";

// 1. Show config.php lines 28-38 (around line 33)
echo "<h3>1. config.php lines 28-40</h3><pre>";
$lines = file(APP_PATH . '/Config/config.php');
for ($i = 27; $i < min(40, count($lines)); $i++) {
    $ln = $i + 1;
    $marker = ($ln == 33) ? ' ◄◄◄ LINE 33' : '';
    echo sprintf("%3d: %s%s", $ln, htmlspecialchars($lines[$i]), $marker);
}
echo "</pre>";

// 2. OPcache status
echo "<h3>2. OPcache</h3>";
if (function_exists('opcache_get_status')) {
    $status = opcache_get_status(false);
    echo "OPcache enabled: " . ($status['opcache_enabled'] ? 'YES' : 'NO') . "<br>";
    echo "Cached scripts: " . ($status['opcache_statistics']['num_cached_scripts'] ?? 'N/A') . "<br>";
    echo "Revalidate freq: " . (ini_get('opcache.revalidate_freq') ?: 'N/A') . "s<br>";
    
    // Try to invalidate config.php
    $configFile = APP_PATH . '/Config/config.php';
    if (function_exists('opcache_invalidate')) {
        $result = opcache_invalidate($configFile, true);
        echo "Invalidated config.php from opcache: " . ($result ? 'YES' : 'NO') . "<br>";
    }
} else {
    echo "OPcache not available<br>";
}

// 3. Config load test
echo "<h3>3. Config Load</h3>";
try {
    require_once APP_PATH . '/Config/constants.php';
    $config = require APP_PATH . '/Config/config.php';
    echo "✅ Config loaded OK<br>";
    echo "DB: " . ($config['database']['name'] ?? 'MISSING') . "<br>";
    echo "Cache enabled: " . ($config['cache']['enabled'] ? 'YES' : 'NO') . "<br>";
} catch (Throwable $e) {
    echo "❌ " . $e->getMessage() . "<br>";
}

// 4. DB test
echo "<h3>4. Database</h3>";
try {
    $dsn = sprintf('mysql:host=%s;port=%d;dbname=%s;charset=%s',
        $config['database']['host'], $config['database']['port'],
        $config['database']['name'], $config['database']['charset']
    );
    $pdo = new PDO($dsn, $config['database']['user'], $config['database']['pass'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);
    echo "✅ Connected. Tables: ";
    $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    echo count($tables) . " (" . implode(', ', array_slice($tables, 0, 10)) . "...)<br>";
} catch (Throwable $e) {
    echo "❌ " . $e->getMessage() . "<br>";
}

// 5. Simulate index.php loading
echo "<h3>5. Simulate Blog Load</h3>";
try {
    // Load same way index.php does
    require APP_PATH . '/Helpers/string.php';
    require APP_PATH . '/Helpers/url.php';
    require APP_PATH . '/Helpers/date.php';
    require APP_PATH . '/Helpers/seo.php';
    
    // Load config again (same as index.php line 61)
    require APP_PATH . '/Config/config.php';
    echo "✅ Config re-loaded via require (like index.php)<br>";
    
    // Try autoloading
    spl_autoload_register(function (string $class): void {
        $map = [
            'Core\\'        => APP_PATH . '/Core/',
            'Models\\'      => APP_PATH . '/Models/',
            'Controllers\\' => APP_PATH . '/Controllers/',
            'Services\\'    => APP_PATH . '/Services/',
        ];
        foreach ($map as $namespace => $basePath) {
            if (str_starts_with($class, $namespace)) {
                $file = $basePath . str_replace([$namespace, '\\'], ['', '/'], $class) . '.php';
                if (file_exists($file)) { require_once $file; return; }
            }
        }
    });
    
    $content = new \Models\Content();
    $posts = $content->getPublished('post', 5, 0, 'en');
    echo "✅ Content model works. Posts found: " . count($posts) . "<br>";
} catch (Throwable $e) {
    echo "❌ Error: " . $e->getMessage() . " in " . $e->getFile() . " line " . $e->getLine() . "<br>";
}

// 6. Storage dirs
echo "<h3>6. Storage Directories</h3>";
$dirs = ['storage/logs', 'storage/cache', 'storage/cache/fragments', 'storage/cache/pages'];
foreach ($dirs as $d) {
    $p = APP_ROOT . '/' . $d;
    if (!is_dir($p)) {
        @mkdir($p, 0755, true);
        echo "📁 Created: $d<br>";
    } else {
        echo "✅ $d exists<br>";
    }
}

echo "<hr><small>Delete this file when done</small>";
