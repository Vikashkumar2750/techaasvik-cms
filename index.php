<?php
/**
 * TECHAASVIK.COM — Front Controller
 * All HTTP requests are routed through this single entry point.
 */

// ── Timezone ──────────────────────────────────────────────
date_default_timezone_set('Asia/Kolkata');

// ── Environment detection ──────────────────────────────────
define('APP_ENV', getenv('APP_ENV') ?: 'production');
define('APP_ROOT', __DIR__);
define('APP_PATH', APP_ROOT . '/app');
define('VIEWS_PATH', APP_ROOT . '/views');
define('STORAGE_PATH', APP_ROOT . '/storage');
define('ASSETS_PATH', APP_ROOT . '/assets');

// ── Error reporting ────────────────────────────────────────
if (APP_ENV === 'development') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    ini_set('log_errors', 1);
    ini_set('error_log', STORAGE_PATH . '/logs/php_errors.log');
}

// ── Autoloader ─────────────────────────────────────────────
spl_autoload_register(function (string $class): void {
    $map = [
        'Core\\'        => APP_PATH . '/Core/',
        'Models\\'      => APP_PATH . '/Models/',
        'Controllers\\' => APP_PATH . '/Controllers/',
        'Services\\'    => APP_PATH . '/Services/',
    ];

    foreach ($map as $namespace => $basePath) {
        if (str_starts_with($class, $namespace)) {
            $file = $basePath . str_replace(
                [$namespace, '\\'],
                ['', '/'],
                $class
            ) . '.php';
            if (file_exists($file)) {
                require_once $file;
                return;
            }
        }
    }
});

// ── Helpers ────────────────────────────────────────────────
require_once APP_PATH . '/Helpers/string.php';
require_once APP_PATH . '/Helpers/url.php';
require_once APP_PATH . '/Helpers/date.php';
require_once APP_PATH . '/Helpers/seo.php';

// ── Config ─────────────────────────────────────────────────
require_once APP_PATH . '/Config/constants.php';
require_once APP_PATH . '/Config/config.php';

// ── Bootstrap & Route ──────────────────────────────────────
use Core\Router;
use Core\Request;

$request = new Request();
$router  = new Router($request);

require_once APP_PATH . '/Config/routes.php';

$router->dispatch();
