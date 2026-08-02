<!-- Admin Authors List -->
<?php $authors = $authors ?? []; ?>

<div class="admin-page-header">
  <div>
    <h1 class="admin-page-title">Authors & Experts</h1>
    <p class="admin-page-subtitle"><?= count($authors) ?> author<?= count($authors) !== 1 ? 's' : '' ?></p>
  </div>
  <a href="/techaasvik_admin/authors/new" class="admin-btn admin-btn-primary">+ New Author</a>
</div>

<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(320px,1fr));gap:16px;">
  <?php if (empty($authors)): ?>
  <div style="grid-column:1/-1;padding:48px;text-align:center;color:var(--admin-muted);">
    <p style="font-size:20px;">👤</p>
    <p>No authors yet. Create your first author to start publishing content.</p>
  </div>
  <?php else: ?>
    <?php foreach ($authors as $a): ?>
    <div style="background:var(--admin-surface);border:1px solid var(--admin-border);border-radius:12px;padding:20px;display:flex;gap:16px;align-items:flex-start;">
      <!-- Avatar -->
      <div style="width:56px;height:56px;border-radius:12px;background:var(--admin-brand);color:#fff;display:flex;align-items:center;justify-content:center;font-size:22px;font-weight:700;flex-shrink:0;overflow:hidden;">
        <?php if (!empty($a['photo_url'])): ?>
          <img src="<?= htmlspecialchars($a['photo_url']) ?>" alt="" style="width:100%;height:100%;object-fit:cover;">
        <?php else: ?>
          <?= strtoupper(mb_substr($a['name'], 0, 1)) ?>
        <?php endif; ?>
      </div>
      <!-- Info -->
      <div style="flex:1;min-width:0;">
        <div style="display:flex;align-items:center;gap:8px;margin-bottom:4px;">
          <strong style="font-size:15px;"><?= htmlspecialchars($a['name']) ?></strong>
          <?php if ($a['is_active']): ?>
            <span class="admin-badge admin-badge-success" style="font-size:10px;">Active</span>
          <?php else: ?>
            <span class="admin-badge admin-badge-muted" style="font-size:10px;">Inactive</span>
          <?php endif; ?>
        </div>
        <?php if (!empty($a['credentials'])): ?>
          <p style="font-size:12px;color:var(--admin-muted);margin-bottom:4px;"><?= htmlspecialchars($a['credentials']) ?></p>
        <?php endif; ?>
        <p style="font-size:12px;color:var(--admin-muted);">
          <?= (int)($a['post_count'] ?? 0) ?> published article<?= ($a['post_count'] ?? 0) != 1 ? 's' : '' ?>
        </p>
        <div style="display:flex;gap:8px;margin-top:8px;">
          <a href="/techaasvik_admin/authors/<?= $a['id'] ?>/edit" class="admin-btn admin-btn-ghost admin-btn-sm">Edit</a>
          <form method="post" action="/techaasvik_admin/authors/<?= $a['id'] ?>/delete" style="display:inline;" onsubmit="return confirm('Delete this author?');">
            <input type="hidden" name="_csrf_token" value="<?= \Core\Auth::csrfToken() ?>">
            <button type="submit" class="admin-btn admin-btn-ghost admin-btn-sm" style="color:var(--admin-error);">Delete</button>
          </form>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  <?php endif; ?>
</div>
