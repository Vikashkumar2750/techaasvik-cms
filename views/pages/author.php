<!-- Author Profile Page -->
<div class="container" style="padding-top:var(--space-10);padding-bottom:var(--space-16);">

  <?php \Core\View::partial('breadcrumb', ['crumbs' => [['name'=>'Home','url'=>'/'],['name'=>'Authors','url'=>'/authors'],['name'=>$author['name']]]]) ?>

  <!-- Author Header -->
  <div style="display:flex;gap:var(--space-8);align-items:flex-start;margin-top:var(--space-6);margin-bottom:var(--space-10);flex-wrap:wrap;">

    <?php if (!empty($author['photo_url'])): ?>
    <img src="<?= e($author['photo_url']) ?>" alt="<?= e($author['name']) ?>" style="width:120px;height:120px;border-radius:50%;object-fit:cover;border:4px solid rgba(99,102,241,0.3);flex-shrink:0;">
    <?php else: ?>
    <div style="width:120px;height:120px;border-radius:50%;background:var(--brand-600);display:flex;align-items:center;justify-content:center;font-weight:800;font-size:40px;color:#fff;flex-shrink:0;">
      <?= str_initials($author['name']) ?>
    </div>
    <?php endif; ?>

    <div style="flex:1;min-width:200px;">
      <h1 style="font-size:clamp(1.5rem,3vw,2rem);margin-bottom:var(--space-2);"><?= e($author['name']) ?></h1>
      <?php if (!empty($author['title'])): ?>
      <p style="font-size:var(--text-base);color:var(--brand-400);font-weight:var(--fw-semibold);margin-bottom:var(--space-3);"><?= e($author['title']) ?></p>
      <?php endif; ?>
      <?php if (!empty($author['bio'])): ?>
      <p style="font-size:var(--text-base);color:var(--text-secondary);line-height:var(--leading-relaxed);max-width:680px;margin-bottom:var(--space-5);"><?= e($author['bio']) ?></p>
      <?php endif; ?>

      <div style="display:flex;gap:12px;flex-wrap:wrap;align-items:center;">
        <span style="font-size:var(--text-sm);color:var(--text-muted);">✏️ <?= number_format($total) ?> Articles</span>
        <?php if (!empty($author['linkedin_url'])): ?>
        <a href="<?= e($author['linkedin_url']) ?>" class="btn btn-secondary btn-sm" target="_blank" rel="noopener">LinkedIn ↗</a>
        <?php endif; ?>
        <?php if (!empty($author['twitter_url'])): ?>
        <a href="<?= e($author['twitter_url']) ?>" class="btn btn-secondary btn-sm" target="_blank" rel="noopener">𝕏 Twitter ↗</a>
        <?php endif; ?>
        <?php if (!empty($author['website_url'])): ?>
        <a href="<?= e($author['website_url']) ?>" class="btn btn-secondary btn-sm" target="_blank" rel="noopener">Website ↗</a>
        <?php endif; ?>
      </div>

      <!-- Expertise Tags -->
      <?php if (!empty($author['expertise'])): ?>
      <div style="margin-top:var(--space-4);display:flex;flex-wrap:wrap;gap:8px;">
        <?php foreach (explode(',', $author['expertise']) as $exp): ?>
        <span class="tag"><?= e(trim($exp)) ?></span>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>
    </div>
  </div>

  <!-- Author's Articles -->
  <h2 style="font-size:var(--text-2xl);margin-bottom:var(--space-6);">Articles by <?= e($author['name']) ?></h2>

  <?php if (!empty($posts)): ?>
  <div class="grid grid-3 gap-6">
    <?php foreach ($posts as $post): ?>
    <article class="post-card">
      <div class="post-card-image" style="background:linear-gradient(135deg,rgba(99,102,241,0.1),rgba(139,92,246,0.05));display:flex;align-items:center;justify-content:center;">
        <span style="font-size:36px;opacity:0.4;">📝</span>
      </div>
      <div class="post-card-body">
        <div class="post-card-meta"><?= format_date($post['published_at']) ?><?php if ($post['read_time'] ?? ''): ?> · <?= $post['read_time'] ?> min<?php endif; ?></div>
        <h3 class="post-card-title">
          <a href="<?= e(content_url($post)) ?>" style="color:inherit;text-decoration:none;"><?= e($post['title']) ?></a>
        </h3>
        <?php if (!empty($post['excerpt'])): ?>
        <p class="post-card-excerpt"><?= str_truncate($post['excerpt'], 110) ?></p>
        <?php endif; ?>
      </div>
      <div class="post-card-footer">
        <span class="type-badge type-<?= $post['type'] ?>"><?= str_replace('_',' ',ucfirst($post['type'])) ?></span>
        <a href="<?= e(content_url($post)) ?>" class="btn btn-ghost btn-sm">Read →</a>
      </div>
    </article>
    <?php endforeach; ?>
  </div>
  <?php \Core\View::partial('pagination', ['total' => $total, 'page' => $page, 'perPage' => $perPage, 'baseUrl' => '/authors/' . $author['slug']]) ?>
  <?php else: ?>
  <p style="color:var(--text-muted);">No published articles yet.</p>
  <?php endif; ?>

</div>
