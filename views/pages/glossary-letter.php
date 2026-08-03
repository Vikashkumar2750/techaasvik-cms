<!-- Glossary Letter Page -->
<div class="container" style="padding-top:var(--space-10);padding-bottom:var(--space-16);">

  <?php \Core\View::partial('breadcrumb', ['crumbs' => [['name'=>'Home','url'=>'/'],['name'=>'Glossary','url'=>'/glossary'],['name'=>'Letter ' . strtoupper($letter ?? 'A')]]]) ?>

  <div style="margin-top:var(--space-4);margin-bottom:var(--space-8);">
    <div style="display:flex;align-items:center;gap:var(--space-4);margin-bottom:var(--space-4);">
      <div style="width:56px;height:56px;background:var(--brand-600);border-radius:var(--radius-lg);display:flex;align-items:center;justify-content:center;font-family:var(--font-display);font-size:var(--text-2xl);font-weight:var(--fw-black);color:#fff;">
        <?= strtoupper($letter ?? 'A') ?>
      </div>
      <div>
        <h1 style="margin:0;">Glossary: <?= strtoupper($letter ?? 'A') ?></h1>
        <p style="color:var(--text-muted);font-size:var(--text-sm);margin-top:4px;"><?= count($terms ?? []) ?> terms starting with "<?= strtoupper($letter ?? 'A') ?>"</p>
      </div>
    </div>
  </div>

  <!-- A-Z Letter Nav -->
  <div style="display:flex;flex-wrap:wrap;gap:6px;margin-bottom:var(--space-8);" role="navigation" aria-label="Alphabet navigation">
    <?php foreach (range('A', 'Z') as $l): ?>
    <a href="/glossary/<?= strtolower($l) ?>"
       class="btn <?= strtolower($l) === strtolower($letter ?? '') ? 'btn-primary' : 'btn-secondary' ?> btn-sm"
       style="width:40px;height:40px;padding:0;justify-content:center;font-weight:700;">
      <?= $l ?>
    </a>
    <?php endforeach; ?>
  </div>

  <!-- Terms List -->
  <?php if (!empty($terms)): ?>
  <div class="grid grid-3 gap-4">
    <?php foreach ($terms as $term): ?>
    <a href="/glossary/term/<?= e($term['slug']) ?>" style="text-decoration:none;display:flex;align-items:center;gap:12px;padding:var(--space-4);background:var(--bg-surface);border:1px solid var(--border-subtle);border-radius:var(--radius-lg);transition:all 0.15s;" onmouseover="this.style.borderColor='rgba(99,102,241,0.3)'" onmouseout="this.style.borderColor='rgba(255,255,255,0.06)'">
      <div style="width:36px;height:36px;border-radius:var(--radius-md);background:var(--bg-elevated);display:flex;align-items:center;justify-content:center;font-weight:800;color:var(--brand-400);font-size:14px;flex-shrink:0;">
        <?= strtoupper(substr($term['title'], 0, 1)) ?>
      </div>
      <div style="flex:1;">
        <div style="font-size:var(--text-sm);font-weight:var(--fw-semibold);color:var(--text-primary);"><?= e($term['title']) ?></div>
        <?php if (!empty($term['excerpt'])): ?>
        <div style="font-size:var(--text-xs);color:var(--text-muted);margin-top:2px;"><?= str_truncate($term['excerpt'], 80) ?></div>
        <?php endif; ?>
      </div>
      <span style="color:var(--text-muted);">→</span>
    </a>
    <?php endforeach; ?>
  </div>
  <?php else: ?>
  <div style="text-align:center;padding:80px;color:var(--text-muted);">
    <p>No glossary terms starting with "<?= strtoupper($letter ?? 'A') ?>" yet.</p>
    <a href="/glossary" class="btn btn-secondary" style="margin-top:16px;">← Back to Glossary</a>
  </div>
  <?php endif; ?>

</div>
