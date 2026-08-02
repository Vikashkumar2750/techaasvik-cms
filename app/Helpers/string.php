<?php
/**
 * String Helper Functions
 */

if (!function_exists('str_slug')) {
    function str_slug(string $text, string $separator = '-'): string {
        $text = mb_strtolower(trim($text), 'UTF-8');
        $text = preg_replace('/[^\p{L}\p{N}\s\-]/u', '', $text);
        $text = preg_replace('/[\s\-]+/', $separator, $text);
        return trim($text, $separator);
    }
}

if (!function_exists('str_excerpt')) {
    function str_excerpt(string $html, int $length = 155): string {
        $text = strip_tags($html);
        $text = preg_replace('/\s+/', ' ', trim($text));
        if (mb_strlen($text) <= $length) return $text;
        return rtrim(mb_substr($text, 0, $length)) . '…';
    }
}

if (!function_exists('str_word_count_utf8')) {
    function str_word_count_utf8(string $html): int {
        return str_word_count(strip_tags($html));
    }
}

if (!function_exists('str_read_time')) {
    function str_read_time(string $html, int $wpm = 200): int {
        return max(1, (int)ceil(str_word_count_utf8($html) / $wpm));
    }
}

if (!function_exists('e')) {
    function e(mixed $value): string {
        return htmlspecialchars((string)$value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }
}

if (!function_exists('str_truncate')) {
    function str_truncate(string $text, int $length, string $suffix = '…'): string {
        $text = strip_tags($text);
        if (mb_strlen($text) <= $length) return $text;
        return mb_substr($text, 0, $length) . $suffix;
    }
}

if (!function_exists('str_initials')) {
    function str_initials(string $name): string {
        $parts = explode(' ', trim($name));
        $init  = '';
        foreach (array_slice($parts, 0, 2) as $part) {
            $init .= mb_strtoupper(mb_substr($part, 0, 1));
        }
        return $init;
    }
}
