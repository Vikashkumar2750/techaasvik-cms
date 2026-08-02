<?php
namespace Core;

/**
 * View — PHP template renderer with layout support.
 */
class View
{
    private static array $sections  = [];
    private static array $stack     = [];
    private static ?string $layout  = null;
    private static array $data      = [];

    // ── Render a view with optional layout ───────────────
    public static function render(string $view, array $data = [], string $layout = 'main'): void
    {
        self::$layout = $layout;
        self::$data   = $data;

        // Make data available as variables
        extract($data, EXTR_SKIP);

        // Buffer the view content
        ob_start();
        $viewFile = VIEWS_PATH . '/pages/' . $view . '.php';

        if (!file_exists($viewFile)) {
            throw new \RuntimeException("View not found: $viewFile");
        }

        require $viewFile;
        $content = ob_get_clean();

        // Render within layout
        if ($layout && file_exists(VIEWS_PATH . '/layouts/' . $layout . '.php')) {
            extract($data, EXTR_SKIP);
            require VIEWS_PATH . '/layouts/' . $layout . '.php';
        } else {
            echo $content;
        }
    }

    // ── Render admin views ────────────────────────────────
    public static function admin(string $view, array $data = []): void
    {
        self::$data = $data;
        extract($data, EXTR_SKIP);

        ob_start();
        $viewFile = VIEWS_PATH . '/admin/' . $view . '.php';

        if (!file_exists($viewFile)) {
            throw new \RuntimeException("Admin view not found: $viewFile");
        }

        require $viewFile;
        $content = ob_get_clean();

        require VIEWS_PATH . '/layouts/admin.php';
    }

    // ── Render a partial ─────────────────────────────────
    public static function partial(string $partial, array $data = []): void
    {
        extract(array_merge(self::$data, $data), EXTR_SKIP);
        $file = VIEWS_PATH . '/partials/' . $partial . '.php';

        if (file_exists($file)) {
            require $file;
        }
    }

    // ── Section system (yield/section) ───────────────────
    public static function section(string $name): void
    {
        self::$stack[] = $name;
        ob_start();
    }

    public static function endSection(): void
    {
        $name = array_pop(self::$stack);
        self::$sections[$name] = ob_get_clean();
    }

    public static function yield(string $name, string $default = ''): string
    {
        return self::$sections[$name] ?? $default;
    }

    // ── Output escaped variable ───────────────────────────
    public static function e(mixed $value): string
    {
        return htmlspecialchars((string)$value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }

    // ── JSON response ────────────────────────────────────
    public static function json(mixed $data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit;
    }

    // ── Redirect ─────────────────────────────────────────
    public static function redirect(string $url, int $status = 302): void
    {
        http_response_code($status);
        header("Location: $url");
        exit;
    }

    // ── Set HTTP status ──────────────────────────────────
    public static function status(int $code): void
    {
        http_response_code($code);
    }

    // ── Asset URL with cache busting ─────────────────────
    public static function asset(string $path): string
    {
        $v = defined('ASSET_VERSION') ? ASSET_VERSION : '1';
        return '/assets/' . ltrim($path, '/') . '?v=' . $v;
    }
}
