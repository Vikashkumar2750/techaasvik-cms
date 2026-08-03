<!-- Tag Archive -->
<div class="container" style="padding-top:var(--space-10);padding-bottom:var(--space-16);">

  <?php \Core\View::partial('breadcrumb', ['crumbs' => [['name'=>'Home','url'=>'/'],['name'=>'Tag: '.$tag['name']]]]) ?>

  <div style="margin-top:var(--space-4);margin-bottom:var(--space-10);">
    <div style="display:flex;align-items:center;gap:var(--space-3);margin-bottom:var(--space-3);">
      <span class="badge badge-brand" style="font-size:var(--text-sm);">#<?= e($tag['name']) ?></span>
    </div>
    <h1>Articles tagged "<?= e($tag['name']) ?>"</h1>
    <?php if (!empty($tag['description'])): ?>
    <p style="font-size:var(--text-lg);color:var(--text-secondary);max-width:640px;margin-top:var(--space-3);"><?= e($tag['description']) ?></p>
    <?php endif; ?>
  </div>

  <?php if (!empty($posts)): ?>
  <div class="grid grid-3 gap-6">
    <?php foreach ($posts as $post): ?>
    <article class="post-card">
      <div class="post-card-image" style="background:linear-gradient(135deg,rgba(99,102,241,0.1),rgba(139,92,246,0.05));display:flex;align-items:center;justify-content:center;">
        <span style="font-size:36px;opacity:0.4;">📝</span>
      </div>
      <div class="post-card-body">
        <div class="post-card-meta"><?= format_date($post['published_at']) ?><?php if ($post['read_time'] ?? ''): ?> · <?= $post['read_time'] ?> min<?php endif; ?></div>
        <h2 class="post-card-title">
          <a href="<?= e(content_url($post)) ?>" style="color:inherit;text-decoration:none;"><?= e($post['title']) ?></a>
        </h2>
        <?php if (!empty($post['excerpt'])): ?>
        <p class="post-card-excerpt"><?= str_truncate($post['excerpt'], 120) ?></p>
        <?php endif; ?>
      </div>
      <div class="post-card-footer">
        <span style="font-size:var(--text-xs);color:var(--text-muted);">By <?= e($post['author_name'] ?? 'TechAasvik') ?></span>
        <a href="<?= e(content_url($post)) ?>" class="btn btn-ghost btn-sm">Read →</a>
      </div>
    </article>
    <?php endforeach; ?>
  </div>

  <?php \Core\View::partial('pagination', ['total' => count($posts), 'page' => $page, 'perPage' => $perPage, 'baseUrl' => '/tag/' . $tag['slug']]) ?>

  <?php else: ?>
  <div style="text-align:center;padding:80px;color:var(--text-muted);">
    <p>No published articles with this tag yet.</p>
    <a href="/blog" class="btn btn-secondary" style="margin-top:16px;">Browse All Articles</a>
  </div>
  <?php endif; ?>

</div>
