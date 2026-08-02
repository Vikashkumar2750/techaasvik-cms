<!-- Resource Index (Case Studies / Statistics / Templates / Research) -->
<div class="container" style="padding-top:var(--space-10);padding-bottom:var(--space-16);">

  <?php \Core\View::partial('breadcrumb', ['crumbs' => [['name'=>'Home','url'=>'/'],['name'=>$title ?? 'Resources']]]) ?>

  <div style="margin-top:var(--space-4);margin-bottom:var(--space-10);">
    <div style="font-size:40px;margin-bottom:var(--space-4);"><?= $icon ?? '📄' ?></div>
    <h1><?= e($title ?? 'Resources') ?></h1>
    <?php if (!empty($desc)): ?>
    <p style="font-size:var(--text-lg);color:var(--text-secondary);max-width:640px;margin-top:var(--space-3);"><?= e($desc) ?></p>
    <?php endif; ?>
    <p style="color:var(--text-muted);font-size:var(--text-sm);margin-top:var(--space-2);"><?= number_format($total) ?> resources published</p>
  </div>

  <?php if (!empty($items)): ?>
  <div class="grid grid-3 gap-6">
    <?php foreach ($items as $item): ?>
    <article class="card card-interactive" style="display:flex;flex-direction:column;">
      <div style="display:flex;gap:8px;align-items:center;margin-bottom:10px;">
        <span class="badge badge-brand"><?= str_replace('_',' ',ucfirst($type ?? $item['type'] ?? '')) ?></span>
        <?php if (!empty($item['published_at'])): ?>
        <span style="font-size:var(--text-xs);color:var(--text-muted);"><?= format_date($item['published_at']) ?></span>
        <?php endif; ?>
      </div>
      <h2 style="font-size:var(--text-base);font-weight:var(--fw-semibold);color:var(--text-primary);margin-bottom:8px;line-height:1.4;flex:1;">
        <a href="<?= e(content_url($item)) ?>" style="color:inherit;text-decoration:none;"><?= e($item['title']) ?></a>
      </h2>
      <?php if (!empty($item['excerpt'])): ?>
      <p style="font-size:var(--text-sm);color:var(--text-secondary);line-height:1.5;"><?= str_truncate($item['excerpt'], 110) ?></p>
      <?php endif; ?>
      <a href="<?= e(content_url($item)) ?>" class="btn btn-ghost btn-sm" style="margin-top:12px;padding-left:0;">View <?= $title ?? 'Resource' ?> →</a>
    </article>
    <?php endforeach; ?>
  </div>

  <?php \Core\View::partial('pagination', ['total' => $total, 'page' => $page ?? 1, 'perPage' => 20, 'baseUrl' => '/' . ($type ?? '')]) ?>

  <?php else: ?>
  <div style="text-align:center;padding:80px;color:var(--text-muted);">
    <div style="font-size:48px;margin-bottom:16px;"><?= $icon ?? '📄' ?></div>
    <h2 style="font-size:var(--text-xl);">Coming Soon</h2>
    <p>We're publishing new <?= strtolower($title ?? 'resources') ?> regularly. Check back soon!</p>
    <a href="/blog" class="btn btn-secondary" style="margin-top:16px;">Read Our Blog →</a>
  </div>
  <?php endif; ?>

</div>
