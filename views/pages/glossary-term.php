<!-- Glossary Term Page -->
<div class="container" style="padding-top:var(--space-8);padding-bottom:var(--space-16);">

  <?php \Core\View::partial('breadcrumb', ['crumbs' => [
    ['name'=>'Home','url'=>'/'],
    ['name'=>'Glossary','url'=>'/glossary'],
    ['name'=>$term['title']],
  ]]) ?>

  <div style="display:grid;grid-template-columns:1fr 260px;gap:var(--space-10);margin-top:var(--space-5);align-items:start;">

    <!-- Main Content -->
    <article>
      <div style="display:flex;align-items:flex-start;gap:var(--space-4);margin-bottom:var(--space-6);">
        <div style="width:64px;height:64px;border-radius:var(--radius-xl);background:var(--brand-600);display:flex;align-items:center;justify-content:center;font-family:var(--font-display);font-size:var(--text-2xl);font-weight:var(--fw-black);color:#fff;flex-shrink:0;">
          <?= strtoupper(substr($term['title'], 0, 1)) ?>
        </div>
        <div>
          <div style="display:flex;gap:8px;align-items:center;margin-bottom:6px;flex-wrap:wrap;">
            <span class="badge badge-brand">Glossary Term</span>
            <?php if (!empty($term['updated_at'])): ?>
            <span style="font-size:var(--text-xs);color:var(--text-muted);">Updated <?= format_date($term['updated_at']) ?></span>
            <?php endif; ?>
          </div>
          <h1 style="font-size:clamp(1.75rem,4vw,2.5rem);"><?= e($term['title']) ?></h1>
        </div>
      </div>

      <!-- Definition highlight box -->
      <?php if (!empty($term['excerpt'])): ?>
      <div style="background:rgba(99,102,241,0.08);border-left:4px solid var(--brand-500);border-radius:0 var(--radius-lg) var(--radius-lg) 0;padding:var(--space-5) var(--space-6);margin-bottom:var(--space-8);">
        <p style="font-size:var(--text-lg);font-weight:var(--fw-medium);color:var(--text-primary);margin:0;line-height:var(--leading-relaxed);">
          <strong><?= e($term['title']) ?></strong> — <?= e($term['excerpt']) ?>
        </p>
      </div>
      <?php endif; ?>

      <!-- Full content -->
      <div class="prose">
        <?= $term['content'] ?? '<p>Definition coming soon.</p>' ?>
      </div>

      <!-- Share -->
      <div style="margin-top:var(--space-8);padding:var(--space-4);background:var(--bg-surface);border-radius:var(--radius-lg);border:1px solid var(--border-subtle);display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px;">
        <span style="font-size:var(--text-sm);font-weight:var(--fw-medium);">Share this definition →</span>
        <div style="display:flex;gap:8px;">
          <a href="https://twitter.com/intent/tweet?url=<?= urlencode(current_url()) ?>&text=<?= urlencode($term['title'] . ' definition: ' . ($term['excerpt'] ?? '')) ?>" target="_blank" rel="noopener" class="btn btn-secondary btn-sm">𝕏 Twitter</a>
          <button onclick="navigator.clipboard.writeText(window.location.href);this.textContent='✅ Copied!'" class="btn btn-secondary btn-sm">🔗 Copy</button>
        </div>
      </div>
    </article>

    <!-- Sidebar -->
    <aside>
      <!-- Back to Glossary -->
      <div style="margin-bottom:var(--space-5);">
        <a href="/glossary" class="btn btn-secondary btn-sm w-full" style="justify-content:center;">← Back to Glossary</a>
      </div>

      <!-- Browse A-Z -->
      <div style="background:var(--bg-surface);border:1px solid var(--border-subtle);border-radius:var(--radius-xl);padding:var(--space-5);margin-bottom:var(--space-5);">
        <p style="font-size:var(--text-xs);font-weight:var(--fw-semibold);text-transform:uppercase;letter-spacing:0.06em;color:var(--text-muted);margin-bottom:var(--space-3);">Browse A-Z</p>
        <div style="display:flex;flex-wrap:wrap;gap:5px;">
          <?php foreach (range('A','Z') as $l): ?>
          <a href="/glossary/<?= strtolower($l) ?>" style="width:30px;height:30px;display:flex;align-items:center;justify-content:center;border-radius:6px;font-size:12px;font-weight:700;background:var(--bg-elevated);color:var(--text-secondary);text-decoration:none;transition:all 0.15s;" onmouseover="this.style.background='var(--brand-600)';this.style.color='#fff'" onmouseout="this.style.background='var(--bg-elevated)';this.style.color='var(--text-secondary)'"><?= $l ?></a>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- Related Terms -->
      <?php if (!empty($related)): ?>
      <div style="background:var(--bg-surface);border:1px solid var(--border-subtle);border-radius:var(--radius-xl);padding:var(--space-5);">
        <p style="font-size:var(--text-xs);font-weight:var(--fw-semibold);text-transform:uppercase;letter-spacing:0.06em;color:var(--text-muted);margin-bottom:var(--space-3);">Related Terms</p>
        <div style="display:flex;flex-direction:column;gap:8px;">
          <?php foreach ($related as $r): ?>
          <a href="/glossary/term/<?= e($r['slug']) ?>" style="font-size:var(--text-sm);color:var(--text-secondary);text-decoration:none;padding:6px 8px;border-radius:6px;transition:all 0.15s;" onmouseover="this.style.background='var(--bg-elevated)';this.style.color='var(--brand-400)'" onmouseout="this.style.background='';this.style.color='var(--text-secondary)'">
            → <?= e($r['title']) ?>
          </a>
          <?php endforeach; ?>
        </div>
      </div>
      <?php endif; ?>
    </aside>

  </div>
</div>
