<!-- Authors Index -->
<div class="container" style="padding-top:var(--space-10);padding-bottom:var(--space-16);">

  <?php \Core\View::partial('breadcrumb', ['crumbs' => [['name'=>'Home','url'=>'/'],['name'=>'Authors']]]) ?>

  <div style="margin-top:var(--space-4);margin-bottom:var(--space-10);text-align:center;">
    <h1>Our Expert Authors</h1>
    <p style="font-size:var(--text-lg);color:var(--text-secondary);max-width:560px;margin:var(--space-4) auto 0;">
      Every guide on TechAasvik is written by certified digital marketing professionals with real-world expertise.
    </p>
  </div>

  <?php if (!empty($authors)): ?>
  <div class="grid grid-3 gap-6">
    <?php foreach ($authors as $author): ?>
    <a href="/authors/<?= e($author['slug']) ?>" class="card card-interactive" style="text-decoration:none;text-align:center;display:flex;flex-direction:column;align-items:center;padding:var(--space-7);">
      <!-- Avatar -->
      <?php if (!empty($author['photo_url'])): ?>
      <img src="<?= e($author['photo_url']) ?>" alt="<?= e($author['name']) ?>" style="width:80px;height:80px;border-radius:50%;object-fit:cover;margin-bottom:16px;border:3px solid rgba(99,102,241,0.3);">
      <?php else: ?>
      <div style="width:80px;height:80px;border-radius:50%;background:var(--brand-600);display:flex;align-items:center;justify-content:center;font-weight:800;font-size:28px;color:#fff;margin-bottom:16px;flex-shrink:0;">
        <?= str_initials($author['name']) ?>
      </div>
      <?php endif; ?>

      <h2 style="font-size:var(--text-lg);font-weight:var(--fw-semibold);color:var(--text-primary);margin-bottom:4px;"><?= e($author['name']) ?></h2>
      <?php if (!empty($author['title'])): ?>
      <p style="font-size:var(--text-sm);color:var(--brand-400);margin-bottom:8px;"><?= e($author['title']) ?></p>
      <?php endif; ?>
      <?php if (!empty($author['short_bio'])): ?>
      <p style="font-size:var(--text-sm);color:var(--text-muted);text-align:center;line-height:1.5;margin-bottom:12px;"><?= str_truncate($author['short_bio'], 100) ?></p>
      <?php endif; ?>

      <div style="display:flex;gap:16px;align-items:center;margin-top:auto;">
        <span style="font-size:var(--text-xs);color:var(--text-muted);"><?= number_format($author['post_count'] ?? 0) ?> articles</span>
        <?php if (!empty($author['linkedin_url'])): ?>
        <a href="<?= e($author['linkedin_url']) ?>" target="_blank" rel="noopener" style="font-size:11px;color:var(--text-muted);" onclick="event.stopPropagation()">LinkedIn ↗</a>
        <?php endif; ?>
      </div>
    </a>
    <?php endforeach; ?>
  </div>

  <?php else: ?>
  <div style="text-align:center;padding:80px;color:var(--text-muted);">
    <div style="font-size:48px;margin-bottom:16px;">👤</div>
    <p>Author profiles coming soon.</p>
  </div>
  <?php endif; ?>

</div>
