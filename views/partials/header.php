<?php
/**
 * Site Header Partial
 * Navigation items & search
 */

$currentPath = (string)(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/');
$navItems = [
    ['Learn',        '/learn'],
    ['Blog',         '/blog'],
    ['Services',     '/services'],
    ['Tools',        '/tools'],
    ['Glossary',     '/glossary'],
    ['Case Studies', '/case-studies'],
    ['Courses',      '/courses'],
];

if (!function_exists('navClass')) {
    function navClass(string $path): string {
        global $currentPath;
        $cp = rtrim($currentPath ?? '/', '/');
        return str_starts_with($cp, $path) ? 'site-nav-link active' : 'site-nav-link';
    }
}
?>

<header class="site-header" role="banner" id="siteHeader">
  <div class="container">

    <!-- Logo -->
    <a href="/" class="site-logo" aria-label="TechAasvik — Home">
      <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
        <rect width="32" height="32" rx="8" fill="#6366f1"/>
        <path d="M8 10h16M8 16h10M8 22h13" stroke="white" stroke-width="2.5" stroke-linecap="round"/>
        <circle cx="24" cy="22" r="3.5" fill="#34d399"/>
      </svg>
      <span class="site-logo-text">TechAasvik</span>
    </a>

    <!-- Desktop Navigation -->
    <nav class="site-nav" id="siteNav" role="navigation" aria-label="Main navigation">
      <?php foreach ($navItems as [$label, $path]): ?>
      <a href="<?= $path ?>" class="<?= navClass($path) ?>"><?= $label ?></a>
      <?php endforeach; ?>
    </nav>

    <!-- Header Actions -->
    <div class="header-actions">
      <!-- Search -->
      <a href="/search" class="btn btn-ghost btn-sm" aria-label="Search" id="headerSearch">
        <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
        </svg>
      </a>

      <!-- Language Toggle -->
      <a href="/hi" class="btn btn-ghost btn-sm" title="हिंदी में पढ़ें" aria-label="Switch to Hindi">
        <span style="font-size:12px;font-weight:600;color:var(--text-muted)">हिंदी</span>
      </a>

      <!-- CTA -->
      <a href="/free-audit" class="btn btn-primary btn-sm" id="headerCta">
        Free Audit ↗
      </a>

      <!-- Mobile Menu Toggle -->
      <button class="menu-toggle" id="menuToggle" aria-label="Toggle menu" aria-expanded="false" aria-controls="siteNav">
        <span></span>
        <span></span>
        <span></span>
      </button>
    </div>

  </div>
</header>

<!-- Global Search Overlay -->
<div id="searchOverlay" style="display:none;position:fixed;inset:0;background:rgba(8,13,22,0.9);z-index:500;padding:80px 20px;" role="dialog" aria-label="Search">
  <div style="max-width:640px;margin:0 auto;">
    <div class="search-bar" style="max-width:100%;">
      <svg class="search-bar-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
      </svg>
      <input type="search" id="searchInput" placeholder="Search articles, glossary, tools…" autocomplete="off">
    </div>
    <div id="searchResults" style="margin-top:16px;"></div>
    <button onclick="document.getElementById('searchOverlay').style.display='none'" style="margin-top:16px;background:none;border:none;color:var(--text-muted);cursor:pointer;font-size:14px;">
      Press Esc or click to close
    </button>
  </div>
</div>
