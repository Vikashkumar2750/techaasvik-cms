<?php
/**
 * Date Helper Functions
 */

if (!function_exists('format_date')) {
    function format_date(?string $date, string $format = 'd M Y'): string {
        if (!$date) return '';
        return date($format, strtotime($date));
    }
}

if (!function_exists('time_ago')) {
    function time_ago(string $date): string {
        $diff = time() - strtotime($date);
        if ($diff < 60)     return 'just now';
        if ($diff < 3600)   return floor($diff/60) . ' min ago';
        if ($diff < 86400)  return floor($diff/3600) . ' hours ago';
        if ($diff < 604800) return floor($diff/86400) . ' days ago';
        return format_date($date, 'd M Y');
    }
}

if (!function_exists('schema_date')) {
    function schema_date(?string $date): string {
        if (!$date) return date('c');
        return date('c', strtotime($date));
    }
}

if (!function_exists('is_fresh')) {
    function is_fresh(string $date, int $days = 90): bool {
        return (time() - strtotime($date)) < ($days * 86400);
    }
}
