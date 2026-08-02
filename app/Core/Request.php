<?php
namespace Core;

/**
 * Request — wraps superglobals in a clean, sanitized API.
 */
class Request
{
    private string $uri;
    private string $method;

    public function __construct()
    {
        $this->method = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
        $this->uri    = $this->parseUri();
    }

    private function parseUri(): string
    {
        $uri = $_GET['url'] ?? $_SERVER['REQUEST_URI'] ?? '/';
        $uri = parse_url($uri, PHP_URL_PATH) ?? '/';
        $uri = '/' . trim($uri, '/');
        // Remove double slashes
        $uri = preg_replace('#/+#', '/', $uri);
        return $uri === '/' ? '' : rtrim($uri, '/');
    }

    public function uri(): string    { return $this->uri; }
    public function method(): string { return $this->method; }
    public function isGet(): bool    { return $this->method === 'GET'; }
    public function isPost(): bool   { return $this->method === 'POST'; }
    public function isAjax(): bool   { return ($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') === 'XMLHttpRequest'; }

    // ── Input getters (sanitized) ────────────────────────
    public function get(string $key, mixed $default = null): mixed
    {
        return isset($_GET[$key]) ? $this->sanitize($_GET[$key]) : $default;
    }

    public function post(string $key, mixed $default = null): mixed
    {
        return isset($_POST[$key]) ? $this->sanitize($_POST[$key]) : $default;
    }

    public function input(string $key, mixed $default = null): mixed
    {
        return $this->post($key) ?? $this->get($key) ?? $default;
    }

    public function all(): array
    {
        return array_merge(
            array_map([$this, 'sanitize'], $_GET),
            array_map([$this, 'sanitize'], $_POST)
        );
    }

    public function file(string $key): ?array
    {
        return $_FILES[$key] ?? null;
    }

    public function header(string $key): ?string
    {
        $serverKey = 'HTTP_' . strtoupper(str_replace('-', '_', $key));
        return $_SERVER[$serverKey] ?? null;
    }

    public function ip(): string
    {
        foreach (['HTTP_CF_CONNECTING_IP', 'HTTP_X_FORWARDED_FOR', 'REMOTE_ADDR'] as $key) {
            if (!empty($_SERVER[$key])) {
                return explode(',', $_SERVER[$key])[0];
            }
        }
        return '0.0.0.0';
    }

    private function sanitize(mixed $value): mixed
    {
        if (is_array($value)) {
            return array_map([$this, 'sanitize'], $value);
        }
        return is_string($value) ? trim(strip_tags($value)) : $value;
    }

    // ── Raw POST (for JSON payloads) ─────────────────────
    public function json(): array
    {
        $body = file_get_contents('php://input');
        return json_decode($body, true) ?? [];
    }
}
