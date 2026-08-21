<?php
/**
 * Site Header Partial
 * Navigation items & search
 * NOTE: Mobile nav (#mobileNav) is OUTSIDE <header> to fix
 *       backdrop-filter containing block issue with position:fixed
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

    <!-- Desktop Nav (visible on ≥768px, hidden on mobile via CSS) -->
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

      <!-- Theme Toggle -->
      <button class="theme-toggle" id="themeToggle" aria-label="Toggle light/dark mode" title="Toggle theme">
        <svg class="theme-icon theme-icon-sun" xmlns="http://www.w3.org/2000/svg" width="17" height="17" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
          <circle cx="12" cy="12" r="5"/>
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"/>
        </svg>
        <svg class="theme-icon theme-icon-moon" xmlns="http://www.w3.org/2000/svg" width="17" height="17" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
          <path stroke-linecap="round" stroke-linejoin="round" d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/>
        </svg>
      </button>

      <!-- CTA (hidden on very small screens via .header-cta-btn CSS) -->
      <a href="/free-audit" class="btn btn-primary btn-sm header-cta-btn" id="headerCta">
        Free Audit ↗
      </a>

      <!-- Mobile Menu Toggle -->
      <button class="menu-toggle" id="menuToggle" aria-label="Toggle menu" aria-expanded="false" aria-controls="mobileNav">
        <span></span>
        <span></span>
        <span></span>
      </button>
    </div>

  </div>
</header>

<!-- ══════════════════════════════════════════════════════
     MOBILE NAV — MUST be OUTSIDE <header>
     Reason: header has backdrop-filter which creates a new
     CSS containing block, making position:fixed children
     position relative to the 64px header instead of viewport.
     Being a sibling of <header> fixes this.
     ══════════════════════════════════════════════════════ -->
<nav class="mobile-nav" id="mobileNav" role="navigation" aria-label="Mobile navigation" aria-hidden="true">
  <div class="mobile-nav-inner">
    <?php foreach ($navItems as [$label, $path]): ?>
    <?php $isActive = str_starts_with(rtrim($currentPath, '/'), $path); ?>
    <a href="<?= $path ?>" class="mobile-nav-link<?= $isActive ? ' active' : '' ?>"><?= $label ?></a>
    <?php endforeach; ?>
    <div class="mobile-nav-footer">
      <a href="/free-audit" class="btn btn-gradient" style="width:100%;justify-content:center;margin-top:var(--space-2);">🚀 Get Free Audit</a>
    </div>
  </div>
</nav>
<!-- Mobile nav backdrop -->
<div class="mobile-nav-backdrop" id="mobileNavBackdrop" aria-hidden="true"></div>

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
