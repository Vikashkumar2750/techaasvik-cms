<!-- Admin Content List -->
<div class="admin-page-header">
  <div>
    <h1 class="admin-page-title">Content Manager</h1>
    <p class="admin-page-subtitle"><?= number_format($total) ?> total items</p>
  </div>
  <a href="/techaasvik_admin/content/new" class="admin-btn admin-btn-primary">
    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
    New Content
  </a>
</div>

<!-- Filters -->
<form method="get" action="/techaasvik_admin/content" class="admin-filters">
  <input type="text" name="search" placeholder="Search titles…" class="admin-form-input" value="<?= e($_GET['search'] ?? '') ?>">
  <select name="type" class="admin-form-select" onchange="this.form.submit()">
    <option value="">All Types</option>
    <?php foreach (CONTENT_TYPES as $value => $label): ?>
    <option value="<?= $value ?>" <?= ($type ?? '') === $value ? 'selected' : '' ?>><?= $label ?></option>
    <?php endforeach; ?>
  </select>
  <select name="status" class="admin-form-select" onchange="this.form.submit()">
    <option value="">All Statuses</option>
    <option value="published" <?= ($status ?? '') === 'published' ? 'selected' : '' ?>>Published</option>
    <option value="draft"     <?= ($status ?? '') === 'draft'     ? 'selected' : '' ?>>Draft</option>
    <option value="archived"  <?= ($status ?? '') === 'archived'  ? 'selected' : '' ?>>Archived</option>
  </select>
  <button type="submit" class="admin-btn admin-btn-secondary">Filter</button>
  <a href="/techaasvik_admin/content" class="admin-btn admin-btn-ghost">Clear</a>
</form>

<!-- Table -->
<div class="admin-table-wrapper">
  <table class="admin-table">
    <thead>
      <tr>
        <th style="width:40%">Title</th>
        <th>Type</th>
        <th>Status</th>
        <th>Author</th>
        <th>Updated</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php if (!empty($items)): ?>
      <?php foreach ($items as $item): ?>
      <tr>
        <td>
          <a href="/techaasvik_admin/content/<?= $item['id'] ?>/edit" style="font-weight:600;color:var(--admin-text);max-width:400px;display:block;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;" title="<?= e($item['title']) ?>">
            <?= e(mb_strimwidth($item['title'], 0, 60, '…')) ?>
          </a>
          <span style="font-size:11px;color:var(--admin-muted);">/<?= e($item['slug'] ?? '') ?></span>
        </td>
        <td><span class="type-badge type-<?= $item['type'] ?>"><?= str_replace('_',' ',ucfirst($item['type'])) ?></span></td>
        <td><span class="status-badge status-<?= $item['status'] ?>"><span class="status-dot"></span><?= $item['status'] ?></span></td>
        <td style="color:var(--admin-muted);font-size:12px;"><?= e($item['author_name'] ?? '—') ?></td>
        <td style="color:var(--admin-muted);font-size:12px;white-space:nowrap;"><?= date('d M Y', strtotime($item['updated_at'])) ?></td>
        <td>
          <div style="display:flex;gap:6px;align-items:center;">
            <a href="/techaasvik_admin/content/<?= $item['id'] ?>/edit" class="admin-btn admin-btn-ghost admin-btn-sm">Edit</a>
            <?php if ($item['status'] !== 'published'): ?>
            <a href="/techaasvik_admin/content/<?= $item['id'] ?>/publish" class="admin-btn admin-btn-success admin-btn-sm">Publish</a>
            <?php else: ?>
            <a href="<?= content_url($item) ?>" target="_blank" rel="noopener" class="admin-btn admin-btn-ghost admin-btn-sm">View ↗</a>
            <?php endif; ?>
            <button class="admin-btn admin-btn-danger admin-btn-sm" onclick="confirmDelete(<?= $item['id'] ?>, '<?= e(addslashes($item['title'])) ?>')">Del</button>
          </div>
        </td>
      </tr>
      <?php endforeach; ?>
      <?php else: ?>
      <tr>
        <td colspan="6" style="text-align:center;padding:40px;color:var(--admin-muted);">
          No content found. <a href="/techaasvik_admin/content/new">Create your first piece →</a>
        </td>
      </tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<!-- Pagination -->
<?php if ($total > $limit): ?>
<div style="display:flex;justify-content:center;gap:8px;margin-top:20px;">
  <?php for ($p = 1; $p <= ceil($total/$limit); $p++): ?>
  <a href="?page=<?= $p ?>&type=<?= urlencode($type ?? '') ?>&status=<?= urlencode($status ?? '') ?>"
     class="admin-btn <?= $p === $page ? 'admin-btn-primary' : 'admin-btn-secondary' ?> admin-btn-sm">
    <?= $p ?>
  </a>
  <?php endfor; ?>
</div>
<?php endif; ?>

<!-- Delete Confirm -->
<form method="post" id="deleteForm" style="display:none;">
  <input type="hidden" name="_csrf_token" value="<?= \Core\Auth::csrfToken() ?>">
</form>

<script>
function confirmDelete(id, title) {
  if (!confirm('Delete "' + title + '"?\n\nThis action cannot be undone.')) return;
  const form = document.getElementById('deleteForm');
  form.action = '/techaasvik_admin/content/' + id + '/delete';
  form.submit();
}
</script>
