/**
 * TECHAASVIK ADMIN — JavaScript
 * Handles: AJAX operations, image uploads, flash auto-dismiss
 */

(function () {
  'use strict';

  // ── Auto-dismiss flash messages ──────────────────────────────
  document.querySelectorAll('.admin-flash').forEach(el => {
    setTimeout(() => {
      el.style.transition = 'opacity 0.4s ease';
      el.style.opacity    = '0';
      setTimeout(() => el.remove(), 400);
    }, 4000);
  });

  // ── Sidebar toggle (mobile) ──────────────────────────────────
  const sidebar = document.getElementById('adminSidebar');
  const toggle  = document.getElementById('sidebarToggle');
  if (toggle && sidebar) {
    toggle.addEventListener('click', () => sidebar.classList.toggle('is-open'));
    document.addEventListener('click', e => {
      if (!sidebar.contains(e.target) && !toggle.contains(e.target)) {
        sidebar.classList.remove('is-open');
      }
    });
  }

  // ── Mark mobile sidebar as needed ────────────────────────────
  function updateSidebarVisibility() {
    if (toggle) toggle.style.display = window.innerWidth < 768 ? 'flex' : 'none';
  }
  updateSidebarVisibility();
  window.addEventListener('resize', updateSidebarVisibility);

  // ── Image Upload Zone (drag and drop) ─────────────────────────
  const uploadZones = document.querySelectorAll('.upload-zone');
  uploadZones.forEach(zone => {
    zone.addEventListener('dragover',   e => { e.preventDefault(); zone.classList.add('drag-over'); });
    zone.addEventListener('dragleave',  ()  => zone.classList.remove('drag-over'));
    zone.addEventListener('drop', e => {
      e.preventDefault();
      zone.classList.remove('drag-over');
      const files = e.dataTransfer.files;
      if (files.length) handleFileUpload(files, zone);
    });
    zone.addEventListener('click', () => {
      const input = zone.querySelector('input[type="file"]') || document.createElement('input');
      input.type = 'file';
      input.accept = 'image/*';
      input.multiple = true;
      input.click();
      input.onchange = () => handleFileUpload(input.files, zone);
    });
  });

  function handleFileUpload(files, zone) {
    Array.from(files).forEach(file => {
      const formData = new FormData();
      formData.append('file', file);
      formData.append('_csrf_token', document.querySelector('[name="_csrf_token"]')?.value || '');

      fetch('/techaasvik_admin/media/upload', { method: 'POST', body: formData })
        .then(r => r.json())
        .then(data => {
          if (data.success) {
            showFlash('success', 'Image uploaded: ' + data.filename);
            // Optionally refresh media grid
            if (typeof refreshMediaGrid === 'function') refreshMediaGrid();
          } else {
            showFlash('error', data.message || 'Upload failed.');
          }
        })
        .catch(() => showFlash('error', 'Upload failed. Check file size/type.'));
    });
  }

  // ── Admin Flash Message ───────────────────────────────────────
  function showFlash(type, message) {
    const main = document.querySelector('.admin-content');
    if (!main) return;
    const el = document.createElement('div');
    el.className = 'admin-flash admin-flash-' + (type === 'success' ? 'success' : 'error');
    el.textContent = message;
    main.prepend(el);
    setTimeout(() => { el.style.opacity = '0'; setTimeout(() => el.remove(), 400); }, 3500);
  }
  window.showFlash = showFlash;

  // ── Copy to Clipboard ─────────────────────────────────────────
  document.querySelectorAll('[data-copy]').forEach(btn => {
    btn.addEventListener('click', () => {
      const text = btn.dataset.copy;
      navigator.clipboard.writeText(text).then(() => {
        const orig = btn.textContent;
        btn.textContent = '✅ Copied!';
        setTimeout(() => btn.textContent = orig, 2000);
      });
    });
  });

  // ── SEO char counters (initial) ───────────────────────────────
  document.querySelectorAll('[oninput*="updateCharCount"]').forEach(el => {
    el.dispatchEvent(new Event('input'));
  });

})();
