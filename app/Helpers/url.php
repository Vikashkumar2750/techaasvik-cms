<?php
/**
 * URL Helper Functions
 */

if (!function_exists('url')) {
    function url(string $path = ''): string {
        $config  = require APP_PATH . '/Config/config.php';
        $base    = rtrim($config['site']['url'], '/');
        return $base . '/' . ltrim($path, '/');
    }
}

if (!function_exists('current_url')) {
    function current_url(): string {
        $scheme = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http';
        return $scheme . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost') . ($_SERVER['REQUEST_URI'] ?? '/');
    }
}

if (!function_exists('asset')) {
    function asset(string $path): string {
        $v = defined('ASSET_VERSION') ? ASSET_VERSION : '1';
        return '/assets/' . ltrim($path, '/') . '?v=' . $v;
    }
}

if (!function_exists('content_url')) {
    function content_url(array $content): string {
        $typeMap = [
            'post'            => '/blog/',
            'pillar'          => '/learn/',
            'glossary_term'   => '/glossary/term/',
            'case_study'      => '/case-studies/',
            'statistics'      => '/statistics/',
            'tool'            => '/tools/',
            'calculator'      => '/calculators/',
            'template'        => '/templates/',
            'course'          => '/courses/',
            'research_report' => '/research/',
            'news_article'    => '/news/',
            'video'           => '/videos/',
            'podcast_episode' => '/podcast/',
            'page'            => '/',
        ];
        $prefix = $typeMap[$content['type']] ?? '/';
        return $prefix . ($content['slug'] ?? '');
    }
}

if (!function_exists('redirect')) {
    function redirect(string $url, int $code = 302): never {
        http_response_code($code);
        header("Location: $url");
        exit;
    }
}

if (!function_exists('is_current_url')) {
    function is_current_url(string $path): bool {
        $uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
        return rtrim($uri, '/') === rtrim($path, '/');
    }
}

if (!function_exists('utm_url')) {
    function utm_url(string $url, string $source, string $medium, string $campaign): string {
        return $url . '?' . http_build_query([
            'utm_source'   => $source,
            'utm_medium'   => $medium,
            'utm_campaign' => $campaign,
        ]);
    }
}
