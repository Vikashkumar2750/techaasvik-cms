<?php
/**
 * TECHAASVIK.COM — Application Configuration
 * DO NOT commit this file to public repositories.
 * Copy from config.example.php and fill in real values.
 */

return [

    // ── Site ───────────────────────────────────────────────
    'site' => [
        'name'        => 'TechAasvik',
        'tagline'     => 'India\'s Most Authoritative Digital Marketing Platform',
        'url'         => 'https://t1.techaasvik.com',
        'admin_url'   => 'https://t1.techaasvik.com/techaasvik_admin',
        'email'       => 'hello@techaasvik.com',
        'language'    => 'en',
        'languages'   => ['en', 'hi'],
        'default_lang'=> 'en',
        'timezone'    => 'Asia/Kolkata',
        'logo'        => '/assets/images/static/logo.svg',
        'favicon'     => '/assets/images/static/favicon.ico',
        'og_image'    => '/assets/images/static/og-default.jpg',
    ],

    // ── Database ───────────────────────────────────────────
    'database' => [
        'host'    => 'localhost',
        'name'    => 'techaasvik_db',
        'user'    => 'techaasvik_user',
        'pass'    => 'DB_PASSWORD_HERE',
        'charset' => 'utf8mb4',
        'port'    => 3306,
    ],

    // ── Admin ──────────────────────────────────────────────
    'admin' => [
        'path'            => 'techaasvik_admin',
        'session_timeout' => 28800,     // 8 hours in seconds
        'max_attempts'    => 5,
        'lockout_minutes' => 30,
    ],

    // ── Cache ──────────────────────────────────────────────
    'cache' => [
        'driver'   => 'file',           // 'file' | 'apcu'
        'ttl'      => 3600,             // 1 hour default
        'path'     => STORAGE_PATH . '/cache',
        'enabled'  => false,            // DISABLED until build is fully tested
    ],

    // ── Email (Brevo SMTP) ─────────────────────────────────
    'mail' => [
        'driver'    => 'smtp',
        'host'      => 'smtp-relay.brevo.com',
        'port'      => 587,
        'username'  => 'BREVO_USERNAME',
        'password'  => 'BREVO_API_KEY',
        'from_name' => 'TechAasvik',
        'from_email'=> 'hello@techaasvik.com',
        'encryption'=> 'tls',
    ],

    // ── Analytics ──────────────────────────────────────────
    'analytics' => [
        'ga4_id'          => 'G-XXXXXXXXXX',
        'gtm_id'          => 'GTM-XXXXXXX',
        'gsc_verify'      => '',
        'fb_pixel_id'     => '',
        'clarity_id'      => '',
        'hotjar_id'       => '',
    ],

    // ── SEO ────────────────────────────────────────────────
    'seo' => [
        'title_separator'     => ' | ',
        'title_suffix'        => 'TechAasvik',
        'default_meta_desc'   => 'India\'s most authoritative digital marketing knowledge platform. Learn SEO, Google Ads, Meta Ads, AEO, GEO, and every aspect of modern digital marketing.',
        'robots'              => 'index,follow',
        'sitemap_auto'        => true,
        'sitemap_freq'        => 'daily',
    ],

    // ── Google APIs ────────────────────────────────────────
    'google' => [
        'recaptcha_site_key'   => 'RECAPTCHA_SITE_KEY',
        'recaptcha_secret_key' => 'RECAPTCHA_SECRET_KEY',
        'maps_api_key'         => '',
        'gsc_api_key'          => '',
    ],

    // ── Media ──────────────────────────────────────────────
    'media' => [
        'upload_path'    => APP_ROOT . '/assets/images/uploads',
        'upload_url'     => '/assets/images/uploads',
        'max_size'       => 10 * 1024 * 1024,   // 10MB
        'allowed_types'  => ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'pdf'],
        'image_quality'  => 85,
        'webp_convert'   => true,
        'thumb_sizes'    => [
            'thumbnail' => [150, 150],
            'medium'    => [400, 300],
            'large'     => [800, 600],
            'og'        => [1200, 630],
        ],
    ],

    // ── Pagination ─────────────────────────────────────────
    'pagination' => [
        'per_page'         => 20,
        'posts_per_page'   => 20,
        'glossary_per_page'=> 50,
    ],

    // ── Cloudflare ─────────────────────────────────────────
    'cloudflare' => [
        'zone_id'  => 'CF_ZONE_ID',
        'api_token'=> 'CF_API_TOKEN',
        'enabled'  => false,    // Enable after CF setup
    ],

    // ── Features (feature flags) ───────────────────────────
    'features' => [
        'courses'     => true,
        'tools'       => true,
        'community'   => false,    // Phase 2
        'podcast'     => false,    // Phase 2
        'comments'    => false,    // Phase 2
        'payments'    => false,    // Phase 2 (Razorpay)
        'whatsapp'    => false,    // Phase 2
        'hindi'       => true,
    ],

];
