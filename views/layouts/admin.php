<?php
/**
 * Admin Layout — wraps all admin views.
 * Variables available: $pageTitle, $admin, $flash
 */

use Core\Auth;
use Core\View;

Auth::startSession();
if (!Auth::check()) {
    View::redirect('/techaasvik_admin');
}

$admin = Auth::admin();
$currentUri = $_SERVER['REQUEST_URI'] ?? '';

function adminIsActive(string $path): string {
    global $currentUri;
    return str_starts_with($currentUri, $path) ? 'active' : '';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="robots" content="noindex, nofollow">
<title><?= htmlspecialchars($pageTitle ?? 'Admin') ?> — TechAasvik CMS</title>
<link rel="stylesheet" href="/assets/css/admin.css?v=<?= ASSET_VERSION ?>">
<link rel="icon" href="/assets/images/static/favicon.ico">
<!-- Quill.js WYSIWYG Editor -->
<link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
</head>
<body>
<div class="admin-layout">

  <!-- ── SIDEBAR ── -->
  <aside class="admin-sidebar" id="adminSidebar">
    <div class="sidebar-logo">
      <span class="sidebar-logo-text">TechAasvik</span>
      <span class="sidebar-logo-badge">CMS</span>
    </div>

    <nav class="sidebar-nav">
      <!-- Dashboard -->
      <a href="/techaasvik_admin/dashboard" class="sidebar-link <?= adminIsActive('/techaasvik_admin/dashboard') ?>">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h7v7H3V7zm11 0h7v3h-7V7zm0 7h7v3h-7v-3zM3 18h7v3H3v-3z"/></svg>
        Dashboard
      </a>

      <div class="sidebar-section">Content</div>

      <a href="/techaasvik_admin/content?type=post" class="sidebar-link <?= adminIsActive('/techaasvik_admin/content') && str_contains($currentUri, 'type=post') ? 'active' : '' ?>">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        Blog Posts
      </a>
      <a href="/techaasvik_admin/content?type=pillar" class="sidebar-link">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
        Pillar Pages
      </a>
      <a href="/techaasvik_admin/content?type=glossary_term" class="sidebar-link">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/></svg>
        Glossary
      </a>
      <a href="/techaasvik_admin/content?type=tool" class="sidebar-link">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
        Tools
      </a>
      <a href="/techaasvik_admin/content?type=case_study" class="sidebar-link">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        Case Studies
      </a>
      <a href="/techaasvik_admin/content?type=course" class="sidebar-link">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M12 14l9-5-9-5-9 5 9 5z"/><path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"/></svg>
        Courses
      </a>
      <a href="/techaasvik_admin/content?type=page" class="sidebar-link">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        Pages
      </a>

      <div class="sidebar-section">Taxonomy</div>
      <a href="/techaasvik_admin/categories" class="sidebar-link <?= adminIsActive('/techaasvik_admin/categories') ?>">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
        Categories
      </a>
      <a href="/techaasvik_admin/tags" class="sidebar-link <?= adminIsActive('/techaasvik_admin/tags') ?>">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/></svg>
        Tags
      </a>

      <div class="sidebar-section">People</div>
      <a href="/techaasvik_admin/authors" class="sidebar-link <?= adminIsActive('/techaasvik_admin/authors') ?>">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
        Authors
      </a>
      <a href="/techaasvik_admin/leads" class="sidebar-link <?= adminIsActive('/techaasvik_admin/leads') ?>">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
        Leads
      </a>

      <div class="sidebar-section">Media & Site</div>
      <a href="/techaasvik_admin/media" class="sidebar-link <?= adminIsActive('/techaasvik_admin/media') ?>">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        Media Library
      </a>
      <a href="/techaasvik_admin/menus" class="sidebar-link <?= adminIsActive('/techaasvik_admin/menus') ?>">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
        Menus
      </a>
      <a href="/techaasvik_admin/seo" class="sidebar-link <?= adminIsActive('/techaasvik_admin/seo') ?>">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        SEO Tools
      </a>
      <a href="/techaasvik_admin/settings" class="sidebar-link <?= adminIsActive('/techaasvik_admin/settings') ?>">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
        Settings
      </a>

      <div class="sidebar-section">Site</div>
      <a href="/" target="_blank" class="sidebar-link">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
        View Site
      </a>
    </nav>

    <div class="sidebar-footer">
      <div class="sidebar-user">
        <div class="sidebar-avatar"><?= strtoupper(substr($admin['username'] ?? 'A', 0, 1)) ?></div>
        <div>
          <div class="sidebar-username"><?= htmlspecialchars($admin['username'] ?? '') ?></div>
          <div class="sidebar-role"><?= htmlspecialchars(ucfirst(str_replace('_',' ',$admin['role'] ?? ''))) ?></div>
        </div>
        <a href="/techaasvik_admin/logout" title="Logout" style="margin-left:auto;color:var(--admin-muted)">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
        </a>
      </div>
    </div>
  </aside>

  <!-- ── MAIN CONTENT ── -->
  <div class="admin-main">
    <!-- Top Header -->
    <header class="admin-header">
      <div style="display:flex;align-items:center;gap:12px;">
        <button class="admin-btn admin-btn-ghost admin-btn-sm" id="sidebarToggle" style="display:none" aria-label="Toggle sidebar">
          <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>
        <span class="admin-header-title"><?= htmlspecialchars($pageTitle ?? 'Dashboard') ?></span>
      </div>
      <div class="admin-header-actions">
        <a href="/techaasvik_admin/content/new" class="admin-btn admin-btn-primary admin-btn-sm">
          <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
          New Content
        </a>
        <a href="/" target="_blank" class="admin-btn admin-btn-ghost admin-btn-sm">View Site ↗</a>
      </div>
    </header>

    <!-- Flash Message -->
    <?php if (!empty($flash)): ?>
      <div style="padding: 0 24px 0;">
        <div class="admin-flash admin-flash-<?= $flash['type'] === 'error' ? 'error' : ($flash['type'] === 'success' ? 'success' : 'info') ?>">
          <?= htmlspecialchars($flash['message']) ?>
        </div>
      </div>
    <?php endif; ?>

    <!-- Page Content -->
    <main class="admin-content">
      <?= $content ?? '' ?>
    </main>
  </div>

</div>

<script>
// Sidebar toggle for mobile
const sidebarToggle = document.getElementById('sidebarToggle');
const sidebar       = document.getElementById('adminSidebar');
if (sidebarToggle) {
  sidebarToggle.style.display = 'flex';
  sidebarToggle.addEventListener('click', () => {
    sidebar.classList.toggle('is-open');
  });
}

// Auto-dismiss flash messages after 4s
const flash = document.querySelector('.admin-flash');
if (flash) setTimeout(() => flash.style.display = 'none', 4000);
</script>

<script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>
<script src="/assets/js/admin.js?v=<?= ASSET_VERSION ?>"></script>
</body>
</html>
