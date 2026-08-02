<!-- Blog Index -->
<div class="container" style="padding-top:var(--space-10);padding-bottom:var(--space-16);">

  <!-- Page Header -->
  <div style="margin-bottom:var(--space-10);">
    <?php \Core\View::partial('breadcrumb', ['crumbs' => [['name'=>'Home','url'=>'/'],['name'=>'Blog']]]) ?>
    <h1 style="margin-top:var(--space-4);margin-bottom:var(--space-3);">Digital Marketing Blog</h1>
    <p style="font-size:var(--text-lg);color:var(--text-secondary);max-width:640px;">Expert-authored guides on SEO, Google Ads, Meta Ads, content marketing, analytics, and every aspect of modern digital marketing.</p>

    <!-- Filters -->
    <div style="display:flex;gap:8px;flex-wrap:wrap;margin-top:var(--space-6);">
      <a href="/blog" class="<?= empty($_GET['lang']) ? 'badge badge-brand' : 'tag' ?>" style="text-decoration:none;padding:5px 12px;">All</a>
      <a href="/blog?lang=en" class="<?= ($_GET['lang'] ?? '') === 'en' ? 'badge badge-brand' : 'tag' ?>" style="text-decoration:none;padding:5px 12px;">🇬🇧 English</a>
      <a href="/blog?lang=hi" class="<?= ($_GET['lang'] ?? '') === 'hi' ? 'badge badge-brand' : 'tag' ?>" style="text-decoration:none;padding:5px 12px;">🇮🇳 हिंदी</a>
    </div>
  </div>

  <!-- Post Grid -->
  <?php if (!empty($posts)): ?>
  <div class="grid grid-3 gap-6" id="postGrid">
    <?php foreach ($posts as $post): ?>
    <article class="post-card">
      <div class="post-card-image" style="background:linear-gradient(135deg,rgba(99,102,241,0.1),rgba(139,92,246,0.05));display:flex;align-items:center;justify-content:center;">
        <span style="font-size:36px;opacity:0.4;">📝</span>
      </div>
      <div class="post-card-body">
        <div class="post-card-meta">
          <?= format_date($post['published_at']) ?>
          <?php if (!empty($post['read_time'])): ?>· <?= $post['read_time'] ?> min<?php endif; ?>
          <?php if (!empty($post['lang']) && $post['lang'] !== 'en'): ?>
          · <span class="badge badge-gray"><?= strtoupper($post['lang']) ?></span>
          <?php endif; ?>
        </div>
        <h2 class="post-card-title">
          <a href="/blog/<?= e($post['slug']) ?>" style="color:inherit;text-decoration:none;"><?= e($post['title']) ?></a>
        </h2>
        <?php if (!empty($post['excerpt'])): ?>
        <p class="post-card-excerpt"><?= str_truncate($post['excerpt'], 120) ?></p>
        <?php endif; ?>
      </div>
      <div class="post-card-footer">
        <span style="font-size:var(--text-xs);color:var(--text-muted);">By <?= e($post['author_name'] ?? 'TechAasvik') ?></span>
        <a href="/blog/<?= e($post['slug']) ?>" class="btn btn-ghost btn-sm">Read →</a>
      </div>
    </article>
    <?php endforeach; ?>
  </div>

  <!-- Pagination -->
  <?php \Core\View::partial('pagination', ['total' => $total, 'page' => $page, 'perPage' => $perPage, 'baseUrl' => '/blog']) ?>

  <?php else: ?>
  <div style="text-align:center;padding:80px 20px;color:var(--text-muted);">
    <div style="font-size:48px;margin-bottom:16px;">📝</div>
    <h2 style="font-size:var(--text-xl);">Content coming soon!</h2>
    <p>We're publishing new articles every day. Check back shortly.</p>
    <a href="/learn" class="btn btn-primary" style="margin-top:16px;">Browse Knowledge Center →</a>
  </div>
  <?php endif; ?>

</div>
