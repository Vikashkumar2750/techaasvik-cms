<?php
/**
 * SEO Helper Functions
 */

if (!function_exists('seo_title')) {
    function seo_title(string $title, string $suffix = 'TechAasvik'): string {
        $title = trim($title);
        if (str_ends_with($title, $suffix)) return $title;
        return $title . ' | ' . $suffix;
    }
}

if (!function_exists('seo_description')) {
    function seo_description(string $text, int $max = 160): string {
        $text = strip_tags($text);
        $text = preg_replace('/\s+/', ' ', trim($text));
        if (mb_strlen($text) <= $max) return $text;
        return rtrim(mb_substr($text, 0, $max - 3)) . '...';
    }
}

if (!function_exists('render_meta')) {
    function render_meta(array $seo): string {
        $html = '';
        // Title
        $html .= '<title>' . e($seo['meta_title'] ?? 'TechAasvik') . '</title>' . "\n";
        // Description
        if (!empty($seo['meta_description'])) {
            $html .= '<meta name="description" content="' . e($seo['meta_description']) . '">' . "\n";
        }
        // Robots
        $robots = [];
        if (!empty($seo['noindex']))  $robots[] = 'noindex';
        else                          $robots[] = 'index';
        if (!empty($seo['nofollow'])) $robots[] = 'nofollow';
        else                          $robots[] = 'follow';
        $html .= '<meta name="robots" content="' . implode(', ', $robots) . '">' . "\n";
        // Canonical
        if (!empty($seo['canonical'])) {
            $html .= '<link rel="canonical" href="' . e($seo['canonical']) . '">' . "\n";
        }
        // OG
        $html .= '<meta property="og:title" content="' . e($seo['og_title'] ?? $seo['meta_title'] ?? '') . '">' . "\n";
        $html .= '<meta property="og:description" content="' . e($seo['og_description'] ?? $seo['meta_description'] ?? '') . '">' . "\n";
        $html .= '<meta property="og:type" content="' . e($seo['og_type'] ?? 'website') . '">' . "\n";
        if (!empty($seo['og_url'])) {
            $html .= '<meta property="og:url" content="' . e($seo['og_url']) . '">' . "\n";
        }
        if (!empty($seo['og_image'])) {
            $html .= '<meta property="og:image" content="' . e($seo['og_image']) . '">' . "\n";
            $html .= '<meta property="og:image:width" content="1200">' . "\n";
            $html .= '<meta property="og:image:height" content="630">' . "\n";
        }
        $html .= '<meta property="og:site_name" content="TechAasvik">' . "\n";
        $html .= '<meta property="og:locale" content="en_IN">' . "\n";
        // Twitter Card
        $html .= '<meta name="twitter:card" content="summary_large_image">' . "\n";
        $html .= '<meta name="twitter:title" content="' . e($seo['og_title'] ?? $seo['meta_title'] ?? '') . '">' . "\n";
        if (!empty($seo['meta_description'])) {
            $html .= '<meta name="twitter:description" content="' . e($seo['meta_description']) . '">' . "\n";
        }
        if (!empty($seo['og_image'])) {
            $html .= '<meta name="twitter:image" content="' . e($seo['og_image']) . '">' . "\n";
        }
        $html .= '<meta name="twitter:site" content="@techaasvik">' . "\n";
        // Article dates
        if (!empty($seo['published_at'])) {
            $html .= '<meta property="article:published_time" content="' . schema_date($seo['published_at']) . '">' . "\n";
        }
        if (!empty($seo['modified_at'])) {
            $html .= '<meta property="article:modified_time" content="' . schema_date($seo['modified_at']) . '">' . "\n";
        }
        return $html;
    }
}
