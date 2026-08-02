<!-- Admin Tags -->
<div class="admin-page-header">
  <h1 class="admin-page-title">Tags</h1>
</div>
<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
  <!-- Add New Tag -->
  <div class="admin-table-wrapper">
    <div style="padding:16px 20px;border-bottom:1px solid var(--admin-border);font-weight:700;font-size:14px;">Add New Tag</div>
    <div style="padding:20px;">
      <form method="post" action="/techaasvik_admin/tags/store" novalidate>
        <input type="hidden" name="_csrf_token" value="<?= \Core\Auth::csrfToken() ?>">
        <div class="admin-form-group">
          <label class="admin-form-label">Name *</label>
          <input type="text" name="name" class="admin-form-input" placeholder="e.g. Keyword Research" required oninput="document.getElementById('tagSlugInput').value=this.value.toLowerCase().replace(/[^a-z0-9]+/g,'-').replace(/-+/g,'-').replace(/^-|-$/g,'')">
        </div>
        <div class="admin-form-group">
          <label class="admin-form-label">Slug</label>
          <input type="text" id="tagSlugInput" name="slug" class="admin-form-input" placeholder="keyword-research">
        </div>
        <button type="submit" class="admin-btn admin-btn-primary">Add Tag</button>
      </form>
    </div>
  </div>
  <!-- Tag Cloud -->
  <div class="admin-table-wrapper">
    <div style="padding:16px 20px;border-bottom:1px solid var(--admin-border);font-weight:700;font-size:14px;">All Tags (<?= count($items) ?>)</div>
    <div style="padding:16px;display:flex;flex-wrap:wrap;gap:8px;">
      <?php foreach ($items as $tag): ?>
      <div style="display:flex;align-items:center;gap:6px;background:var(--admin-elevated);border-radius:6px;padding:4px 10px;">
        <span style="font-size:13px;"><?= htmlspecialchars($tag['name']) ?></span>
        <span style="font-size:11px;color:var(--admin-muted);">(<?= $tag['post_count'] ?? 0 ?>)</span>
        <form method="post" action="/techaasvik_admin/tags/<?= $tag['id'] ?>/delete" style="display:inline;" onsubmit="return confirm('Delete tag?')">
          <input type="hidden" name="_csrf_token" value="<?= \Core\Auth::csrfToken() ?>">
          <button type="submit" style="background:none;border:none;cursor:pointer;color:var(--admin-muted);font-size:14px;padding:0 0 0 4px;" title="Delete">×</button>
        </form>
      </div>
      <?php endforeach; ?>
      <?php if (empty($items)): ?><p style="color:var(--admin-muted);font-size:13px;">No tags yet.</p><?php endif; ?>
    </div>
  </div>
</div>
