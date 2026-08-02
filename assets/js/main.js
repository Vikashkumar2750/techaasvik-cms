/**
 * TECHAASVIK.COM — MAIN JAVASCRIPT
 * Handles: navigation, search overlay, newsletter forms, audit forms,
 *          reading progress, mobile menu, lazy load, FAQ accordion
 */

(function () {
  'use strict';

  // ── Reading Progress Bar ────────────────────────────────────
  const progressBar = document.getElementById('readingProgress');
  if (progressBar) {
    window.addEventListener('scroll', () => {
      const doc    = document.documentElement;
      const total  = doc.scrollHeight - doc.clientHeight;
      const pct    = total > 0 ? (window.scrollY / total) * 100 : 0;
      progressBar.style.width = Math.min(pct, 100) + '%';
      progressBar.setAttribute('aria-valuenow', Math.round(pct));
    }, { passive: true });
  }

  // ── Mobile Menu ─────────────────────────────────────────────
  const menuToggle = document.getElementById('menuToggle');
  const siteNav    = document.getElementById('siteNav');
  if (menuToggle && siteNav) {
    menuToggle.addEventListener('click', () => {
      const isOpen = siteNav.classList.toggle('is-open');
      menuToggle.setAttribute('aria-expanded', String(isOpen));
      // Animate toggle bars
      const bars = menuToggle.querySelectorAll('span');
      if (isOpen) {
        bars[0].style.transform = 'rotate(45deg) translate(5px, 5px)';
        bars[1].style.opacity   = '0';
        bars[2].style.transform = 'rotate(-45deg) translate(5px, -5px)';
      } else {
        bars.forEach(b => { b.style.transform = ''; b.style.opacity = ''; });
      }
    });

    // Close on outside click
    document.addEventListener('click', e => {
      if (!menuToggle.contains(e.target) && !siteNav.contains(e.target)) {
        siteNav.classList.remove('is-open');
        menuToggle.setAttribute('aria-expanded', 'false');
        menuToggle.querySelectorAll('span').forEach(b => { b.style.transform = ''; b.style.opacity = ''; });
      }
    });
  }

  // ── Search Overlay ──────────────────────────────────────────
  const headerSearch  = document.getElementById('headerSearch');
  const searchOverlay = document.getElementById('searchOverlay');
  const searchInput   = document.getElementById('searchInput');

  if (headerSearch && searchOverlay && searchInput) {
    headerSearch.addEventListener('click', e => {
      e.preventDefault();
      searchOverlay.style.display = 'flex';
      searchOverlay.style.alignItems = 'center';
      setTimeout(() => searchInput.focus(), 50);
    });

    // Close on Escape
    document.addEventListener('keydown', e => {
      if (e.key === 'Escape' && searchOverlay.style.display !== 'none') {
        searchOverlay.style.display = 'none';
      }
    });

    // Close on background click
    searchOverlay.addEventListener('click', e => {
      if (e.target === searchOverlay) searchOverlay.style.display = 'none';
    });

    // Live search (debounced)
    let searchTimer;
    searchInput.addEventListener('input', e => {
      clearTimeout(searchTimer);
      const q = e.target.value.trim();
      if (q.length < 2) {
        document.getElementById('searchResults').innerHTML = '';
        return;
      }
      searchTimer = setTimeout(() => {
        const q2 = encodeURIComponent(q);
        window.location.href = '/search?q=' + q2;
      }, 600);
    });

    // Submit search on Enter
    searchInput.addEventListener('keydown', e => {
      if (e.key === 'Enter') {
        e.preventDefault();
        const q = searchInput.value.trim();
        if (q) window.location.href = '/search?q=' + encodeURIComponent(q);
      }
    });
  }

  // ── Newsletter Forms ─────────────────────────────────────────
  function setupNewsletter(formId, emailId) {
    const form = document.getElementById(formId);
    if (!form) return;

    form.addEventListener('submit', async e => {
      e.preventDefault();
      const email = document.getElementById(emailId)?.value?.trim();
      if (!email) return;

      const btn = form.querySelector('button[type="submit"]');
      if (btn) { btn.disabled = true; btn.textContent = 'Subscribing…'; }

      try {
        const res = await fetch('/lead/newsletter', {
          method: 'POST',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-Requested-With': 'XMLHttpRequest' },
          body:    'email=' + encodeURIComponent(email) + '&source=' + encodeURIComponent(window.location.href),
        });
        const data = await res.json();

        if (data.success) {
          form.innerHTML = '<p style="color:#4ade80;font-weight:600;text-align:center;">✅ ' + data.message + '</p>';
        } else {
          if (btn) { btn.disabled = false; btn.textContent = 'Subscribe →'; }
          showFormError(form, data.message);
        }
      } catch {
        if (btn) { btn.disabled = false; btn.textContent = 'Subscribe →'; }
        showFormError(form, 'Network error. Please try again.');
      }
    });
  }

  setupNewsletter('heroNewsletter',   'heroEmail');
  setupNewsletter('footerNewsletter', 'footerEmail');
  setupNewsletter('sidebarNewsletter','sidebarEmail');

  // ── Free Audit Form ──────────────────────────────────────────
  const auditForm = document.getElementById('auditForm');
  const auditMsg  = document.getElementById('auditMsg');
  if (auditForm) {
    auditForm.addEventListener('submit', async e => {
      e.preventDefault();
      const btn = auditForm.querySelector('button[type="submit"]');
      if (btn) { btn.disabled = true; btn.textContent = 'Sending…'; }

      const data = new URLSearchParams(new FormData(auditForm));

      try {
        const res  = await fetch('/lead/audit', {
          method: 'POST',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-Requested-With': 'XMLHttpRequest' },
          body:   data.toString(),
        });
        const json = await res.json();

        if (auditMsg) {
          auditMsg.style.display = 'block';
          auditMsg.textContent   = json.message;
          auditMsg.style.color   = json.success ? '#4ade80' : '#f87171';
        }
        if (json.success) auditForm.reset();
        if (btn) { btn.disabled = false; btn.textContent = 'Get Free Audit 🚀'; }
      } catch {
        if (btn) { btn.disabled = false; btn.textContent = 'Get Free Audit 🚀'; }
      }
    });
  }

  // ── FAQ Accordion Enhancements ───────────────────────────────
  document.querySelectorAll('details').forEach(details => {
    const summary = details.querySelector('summary');
    const arrow   = summary?.querySelector('span:last-child');

    if (details && arrow) {
      details.addEventListener('toggle', () => {
        arrow.style.transform = details.open ? 'rotate(45deg)' : '';
      });
    }
  });

  // ── Lazy Load Images ─────────────────────────────────────────
  if ('IntersectionObserver' in window) {
    const imgObs = new IntersectionObserver(entries => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          const img = entry.target;
          if (img.dataset.src) {
            img.src = img.dataset.src;
            img.removeAttribute('data-src');
          }
          imgObs.unobserve(img);
        }
      });
    }, { rootMargin: '200px' });

    document.querySelectorAll('img[data-src]').forEach(img => imgObs.observe(img));
  }

  // ── Sticky Header Shadow ─────────────────────────────────────
  const header = document.getElementById('siteHeader');
  if (header) {
    window.addEventListener('scroll', () => {
      header.style.boxShadow = window.scrollY > 10
        ? '0 1px 30px rgba(0,0,0,0.4)'
        : 'none';
    }, { passive: true });
  }

  // ── Smooth Scroll for Anchor Links ───────────────────────────
  document.querySelectorAll('a[href^="#"]').forEach(a => {
    a.addEventListener('click', e => {
      const target = document.querySelector(a.getAttribute('href'));
      if (target) {
        e.preventDefault();
        target.scrollIntoView({ behavior: 'smooth', block: 'start' });
      }
    });
  });

  // ── Utility: show form error ─────────────────────────────────
  function showFormError(form, msg) {
    let err = form.querySelector('.form-err');
    if (!err) {
      err = document.createElement('p');
      err.className = 'form-err';
      err.style.cssText = 'color:#f87171;font-size:13px;margin-top:6px;';
      form.appendChild(err);
    }
    err.textContent = msg;
    setTimeout(() => err.remove(), 4000);
  }

  // ── Analytics Helper (GA4 events) ───────────────────────────
  window.trackEvent = function (action, category, label) {
    if (typeof gtag === 'function') {
      gtag('event', action, { event_category: category, event_label: label });
    }
  };

  // Track CTA clicks
  document.querySelectorAll('[id*="Cta"], .btn-gradient, [id="heroStartLearning"]').forEach(el => {
    el.addEventListener('click', () => {
      window.trackEvent('click', 'CTA', el.textContent.trim().substring(0, 50));
    });
  });

})();
