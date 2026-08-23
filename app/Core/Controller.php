<?php
namespace Core;

/**
 * Base Controller — shared utilities for all controllers.
 */
abstract class Controller
{
    protected Database $db;
    protected Request  $request;

    public function __construct()
    {
        $this->db      = Database::getInstance();
        $this->request = new Request();
    }

    // ── Render a view ─────────────────────────────────────
    protected function view(string $view, array $data = [], string $layout = 'main'): void
    {
        View::render($view, $data, $layout);
    }

    // ── Render admin view ─────────────────────────────────
    protected function adminView(string $view, array $data = []): void
    {
        Auth::startSession();
        Auth::requireAdmin();
        View::admin($view, $data);
    }

    // ── Redirect ─────────────────────────────────────────
    protected function redirect(string $url, int $status = 302): void
    {
        View::redirect($url, $status);
    }

    // ── JSON response ─────────────────────────────────────
    protected function json(mixed $data, int $status = 200): void
    {
        View::json($data, $status);
    }

    // ── 404 ──────────────────────────────────────────────
    protected function notFound(string $message = 'Page not found'): void
    {
        View::status(404);
        $this->view('404', ['message' => $message, 'title' => '404 — Page Not Found']);
        exit;
    }

    // ── Get setting ──────────────────────────────────────
    protected function setting(string $key, mixed $default = null): mixed
    {
        $cache = Cache::getInstance();
        $settings = $cache->remember(CACHE_SETTINGS, 3600, function () {
            $rows = $this->db->fetchAll("SELECT setting_key, setting_value FROM settings");
            $map  = [];
            foreach ($rows as $row) {
                $map[$row['setting_key']] = $row['setting_value'];
            }
            return $map;
        });

        return $settings[$key] ?? $default;
    }

    // ── Flash message ─────────────────────────────────────
    protected function flash(string $type, string $message): void
    {
        Auth::startSession();
        $_SESSION['flash'] = ['type' => $type, 'message' => $message];
    }

    protected function getFlash(): ?array
    {
        if (!isset($_SESSION['flash'])) return null;
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }

    // ── Validate POST fields ──────────────────────────────
    protected function validate(array $rules): array
    {
        $errors = [];
        foreach ($rules as $field => $rule) {
            $value = $this->request->post($field, '');

            if (str_contains($rule, 'required') && empty($value)) {
                $errors[$field] = ucfirst($field) . ' is required.';
                continue;
            }
            if (str_contains($rule, 'email') && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                $errors[$field] = 'Please enter a valid email address.';
            }
            if (preg_match('/min:(\d+)/', $rule, $m) && strlen($value) < (int)$m[1]) {
                $errors[$field] = ucfirst($field) . " must be at least {$m[1]} characters.";
            }
            if (preg_match('/max:(\d+)/', $rule, $m) && strlen($value) > (int)$m[1]) {
                $errors[$field] = ucfirst($field) . " must not exceed {$m[1]} characters.";
            }
        }
        return $errors;
    }

    // ── CSRF check ────────────────────────────────────────
    protected function verifyCsrf(): void
    {
        Auth::startSession();
        // Accept both _csrf_token (form default) and csrf_token (AJAX)
        $token = $this->request->post('_csrf_token', '')
               ?: $this->request->post('csrf_token', '');
        if (!Auth::verifyCsrf($token)) {
            $this->json(['error' => 'Invalid CSRF token.'], 403);
        }
    }

    // ── Pagination helper ─────────────────────────────────
    protected function page(): int
    {
        return max(1, (int)$this->request->get('page', 1));
    }
}
