<!-- News Archive Index -->
<div class="container" style="padding-top:var(--space-10);padding-bottom:var(--space-16);">

  <?php \Core\View::partial('breadcrumb', ['crumbs' => [['name'=>'Home','url'=>'/'],['name'=>'News']]]) ?>

  <div style="margin-top:var(--space-4);margin-bottom:var(--space-10);">
    <div style="font-size:40px;margin-bottom:var(--space-4);">📰</div>
    <h1>Digital Marketing News</h1>
    <p style="font-size:var(--text-lg);color:var(--text-secondary);max-width:640px;margin-top:var(--space-3);">
      Latest news and updates from the digital marketing world. Stay informed about algorithm changes, platform updates, and industry trends.
    </p>
  </div>

  <?php if (!empty($posts)): ?>
  <div class="grid grid-3 gap-6">
    <?php foreach ($posts as $post): ?>
    <article class="post-card">
      <div class="post-card-image" style="background:linear-gradient(135deg,rgba(99,102,241,0.1),rgba(139,92,246,0.05));display:flex;align-items:center;justify-content:center;">
        <span style="font-size:36px;opacity:0.4;">📰</span>
      </div>
      <div class="post-card-body">
        <div class="post-card-meta"><?= format_date($post['published_at']) ?></div>
        <h2 class="post-card-title">
          <a href="/news/<?= e($post['slug']) ?>" style="color:inherit;text-decoration:none;"><?= e($post['title']) ?></a>
        </h2>
        <?php if (!empty($post['excerpt'])): ?>
        <p class="post-card-excerpt"><?= str_truncate($post['excerpt'], 120) ?></p>
        <?php endif; ?>
      </div>
      <div class="post-card-footer">
        <span style="font-size:var(--text-xs);color:var(--text-muted);">By <?= e($post['author_name'] ?? 'TechAasvik') ?></span>
        <a href="/news/<?= e($post['slug']) ?>" class="btn btn-ghost btn-sm">Read →</a>
      </div>
    </article>
    <?php endforeach; ?>
  </div>

  <?php \Core\View::partial('pagination', ['total' => $total ?? count($posts), 'page' => $page ?? 1, 'perPage' => $perPage ?? 20, 'baseUrl' => '/news']) ?>

  <?php else: ?>
  <div style="text-align:center;padding:80px;color:var(--text-muted);">
    <div style="font-size:48px;margin-bottom:16px;">📰</div>
    <h2 style="font-size:var(--text-xl);">Coming Soon</h2>
    <p>We're working on bringing you the latest digital marketing news. Check back soon!</p>
    <a href="/blog" class="btn btn-secondary" style="margin-top:16px;">Read Our Blog →</a>
  </div>
  <?php endif; ?>

</div>
