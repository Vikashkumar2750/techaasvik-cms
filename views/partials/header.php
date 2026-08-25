<?php
/**
 * Site Header Partial
 * Navigation items & search
 * NOTE: Mobile nav (#mobileNav) is OUTSIDE <header> to fix
 *       backdrop-filter containing block issue with position:fixed
 */

$currentPath = (string)(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/');

// Courses visibility toggle
$_coursesEnabled = true;
$_studentLoggedIn = false;
try {
    $__cs = new \Models\CourseSetting();
    $_coursesEnabled = $__cs->coursesEnabled();
} catch (\Throwable $e) { /* DB not ready */ }

// Check if student is logged in (session-based)
if (session_status() === PHP_SESSION_NONE) @session_start();
$_studentLoggedIn = !empty($_SESSION['course_student_id']);

$navItems = [
    ['Learn',        '/learn'],
    ['Blog',         '/blog'],
    ['Services',     '/services'],
    ['Tools',        '/tools'],
    ['Glossary',     '/glossary'],
    ['Case Studies', '/case-studies'],
];
if ($_coursesEnabled) {
    $navItems[] = ['Courses', '/courses'];
}

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
      <img src="/assets/images/logo.png" alt="TechAasvik" width="36" height="36" style="object-fit:contain;" loading="eager">
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

      <?php if ($_coursesEnabled): ?>
      <!-- Course Login / My Course -->
      <?php if ($_studentLoggedIn): ?>
      <a href="/courses/ai-marketing-course/learn/1" class="btn btn-ghost btn-sm" title="My Course" style="gap:6px;display:flex;align-items:center;" id="headerCourseLogin">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="4"/><path stroke-linecap="round" d="M4 20c0-4 3.582-7 8-7s8 3 8 7"/></svg>
        <span style="font-size:12px;font-weight:600;">My Course</span>
      </a>
      <?php else: ?>
      <a href="/courses/login" class="btn btn-ghost btn-sm" title="Student Login" style="gap:6px;display:flex;align-items:center;" id="headerCourseLogin">
        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
        <span style="font-size:12px;font-weight:600;">Login</span>
      </a>
      <?php endif; ?>
      <?php endif; ?>

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
    <?php if ($_coursesEnabled): ?>
    <a href="<?= $_studentLoggedIn ? '/courses/ai-marketing-course/learn/1' : '/courses/login' ?>" class="mobile-nav-link" style="color:var(--brand-400);font-weight:600;">
      <?= $_studentLoggedIn ? '🎓 My Course' : '🔑 Student Login' ?>
    </a>
    <?php endif; ?>
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
