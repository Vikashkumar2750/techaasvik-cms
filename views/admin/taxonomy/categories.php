<!-- Admin Categories -->
<div class="admin-page-header">
  <h1 class="admin-page-title">Categories</h1>
</div>
<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
  <!-- Add New -->
  <div class="admin-table-wrapper">
    <div style="padding:16px 20px;border-bottom:1px solid var(--admin-border);font-weight:700;font-size:14px;">Add New Category</div>
    <div style="padding:20px;">
      <form method="post" action="/techaasvik_admin/categories/store" novalidate>
        <input type="hidden" name="_csrf_token" value="<?= \Core\Auth::csrfToken() ?>">
        <div class="admin-form-group">
          <label class="admin-form-label" for="catName">Name *</label>
          <input type="text" id="catName" name="name" class="admin-form-input" placeholder="e.g. SEO" required oninput="this.form.slug.value=this.value.toLowerCase().replace(/[^a-z0-9]+/g,'-').replace(/-+/g,'-').replace(/^-|-$/g,'')">
        </div>
        <div class="admin-form-group">
          <label class="admin-form-label" for="catSlug">Slug</label>
          <input type="text" id="catSlug" name="slug" class="admin-form-input" placeholder="seo">
        </div>
        <div class="admin-form-group">
          <label class="admin-form-label" for="catParent">Parent Category</label>
          <select id="catParent" name="parent_id" class="admin-form-select">
            <option value="">— None —</option>
            <?php foreach ($items as $cat): ?>
            <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="admin-form-group">
          <label class="admin-form-label" for="catDesc">Description</label>
          <textarea id="catDesc" name="description" class="admin-form-textarea" rows="3" placeholder="Optional category description"></textarea>
        </div>
        <button type="submit" class="admin-btn admin-btn-primary">Add Category</button>
      </form>
    </div>
  </div>
  <!-- List -->
  <div class="admin-table-wrapper">
    <div style="padding:16px 20px;border-bottom:1px solid var(--admin-border);font-weight:700;font-size:14px;">All Categories</div>
    <table class="admin-table">
      <thead><tr><th>Name</th><th>Slug</th><th>Posts</th><th>Actions</th></tr></thead>
      <tbody>
        <?php foreach ($items as $cat): ?>
        <tr>
          <td style="font-weight:600;"><?= htmlspecialchars($cat['name']) ?></td>
          <td style="font-family:monospace;font-size:12px;color:var(--admin-muted);"><?= htmlspecialchars($cat['slug']) ?></td>
          <td><?= $cat['post_count'] ?? 0 ?></td>
          <td>
            <form method="post" action="/techaasvik_admin/categories/<?= $cat['id'] ?>/delete" style="display:inline;" onsubmit="return confirm('Delete this category?')">
              <input type="hidden" name="_csrf_token" value="<?= \Core\Auth::csrfToken() ?>">
              <button type="submit" class="admin-btn admin-btn-danger admin-btn-sm">Del</button>
            </form>
          </td>
        </tr>
        <?php endforeach; ?>
        <?php if (empty($items)): ?><tr><td colspan="4" style="text-align:center;color:var(--admin-muted);padding:24px;">No categories yet.</td></tr><?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
