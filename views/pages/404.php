<!-- 404 Error Page -->
<div style="min-height:70vh;display:flex;align-items:center;justify-content:center;padding:var(--space-16) var(--space-6);">
  <div style="text-align:center;max-width:520px;">

    <!-- Animated 404 -->
    <div style="font-size:clamp(80px,15vw,140px);font-family:var(--font-display);font-weight:var(--fw-black);background:linear-gradient(135deg,var(--brand-400),#a78bfa);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;line-height:1;margin-bottom:var(--space-6);">
      404
    </div>

    <h1 style="font-size:var(--text-2xl);margin-bottom:var(--space-4);">Page Not Found</h1>
    <p style="color:var(--text-secondary);margin-bottom:var(--space-8);font-size:var(--text-lg);">
      The page you're looking for doesn't exist or has been moved. Let's get you back on track.
    </p>

    <!-- Helpful actions -->
    <div style="display:flex;flex-wrap:wrap;justify-content:center;gap:12px;margin-bottom:var(--space-10);">
      <a href="/" class="btn btn-primary btn-lg">Go Home</a>
      <a href="/blog" class="btn btn-secondary btn-lg">Browse Blog</a>
      <a href="/search" class="btn btn-secondary btn-lg">Search →</a>
    </div>

    <!-- Popular Pages -->
    <div style="background:var(--bg-surface);border:1px solid var(--border-subtle);border-radius:var(--radius-xl);padding:var(--space-6);text-align:left;">
      <p style="font-size:var(--text-xs);font-weight:var(--fw-semibold);text-transform:uppercase;letter-spacing:0.06em;color:var(--text-muted);margin-bottom:var(--space-4);">Popular Pages</p>
      <div style="display:flex;flex-direction:column;gap:8px;">
        <?php foreach ([
          ['/learn',        '📚 Knowledge Center'],
          ['/glossary',     '📖 Marketing Glossary'],
          ['/tools',        '⚙️ Free Tools'],
          ['/case-studies', '📊 Case Studies'],
          ['/courses',      '🎓 Free Courses'],
        ] as [$url, $label]): ?>
        <a href="<?= $url ?>" style="font-size:var(--text-sm);color:var(--text-secondary);text-decoration:none;padding:6px 8px;border-radius:var(--radius-md);transition:all 0.15s;" onmouseover="this.style.background='var(--bg-elevated)';this.style.color='var(--text-primary)'" onmouseout="this.style.background='';this.style.color='var(--text-secondary)'"><?= $label ?></a>
        <?php endforeach; ?>
      </div>
    </div>

  </div>
</div>
