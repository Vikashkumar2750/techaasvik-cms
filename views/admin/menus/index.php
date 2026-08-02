<!-- Admin Menu Manager -->
<?php $menus = $menus ?? []; ?>

<div class="admin-page-header">
  <div>
    <h1 class="admin-page-title">Menu Manager</h1>
    <p class="admin-page-subtitle">Manage site navigation menus</p>
  </div>
</div>

<!-- Create New Menu -->
<div style="background:var(--admin-surface);border:1px solid var(--admin-border);border-radius:12px;padding:20px;margin-bottom:24px;">
  <h3 style="font-size:14px;margin-bottom:12px;">➕ Create New Menu</h3>
  <form method="post" action="/techaasvik_admin/menus/create" style="display:flex;gap:10px;flex-wrap:wrap;align-items:end;">
    <input type="hidden" name="_csrf_token" value="<?= \Core\Auth::csrfToken() ?>">
    <div class="admin-form-group" style="margin:0;flex:1;min-width:180px;">
      <label class="admin-form-label" for="menuName">Menu Name</label>
      <input type="text" id="menuName" name="name" class="admin-form-input" placeholder="e.g. Main Navigation" required>
    </div>
    <div class="admin-form-group" style="margin:0;min-width:160px;">
      <label class="admin-form-label" for="menuLocation">Location</label>
      <select id="menuLocation" name="location" class="admin-form-input">
        <option value="primary">Primary (Header)</option>
        <option value="footer">Footer</option>
        <option value="sidebar">Sidebar</option>
        <option value="mobile">Mobile</option>
      </select>
    </div>
    <button type="submit" class="admin-btn admin-btn-primary admin-btn-sm">Create Menu</button>
  </form>
</div>

<!-- Existing Menus -->
<?php if (empty($menus)): ?>
<div style="text-align:center;padding:48px;color:var(--admin-muted);">
  <p style="font-size:20px;">📋</p>
  <p>No menus yet. Create your first menu above.</p>
</div>
<?php else: ?>
  <?php foreach ($menus as $menu): ?>
  <div style="background:var(--admin-surface);border:1px solid var(--admin-border);border-radius:12px;margin-bottom:20px;overflow:hidden;">
    <!-- Menu Header -->
    <div style="padding:16px 20px;border-bottom:1px solid var(--admin-border);display:flex;justify-content:space-between;align-items:center;">
      <div>
        <strong style="font-size:15px;"><?= htmlspecialchars($menu['name']) ?></strong>
        <span class="admin-badge admin-badge-info" style="margin-left:8px;"><?= htmlspecialchars($menu['location']) ?></span>
        <span style="font-size:12px;color:var(--admin-muted);margin-left:8px;"><?= count($menu['items']) ?> items</span>
      </div>
      <form method="post" action="/techaasvik_admin/menus/<?= $menu['id'] ?>/delete" style="display:inline;"
            onsubmit="return confirm('Delete this entire menu and all its items?');">
        <input type="hidden" name="_csrf_token" value="<?= \Core\Auth::csrfToken() ?>">
        <button type="submit" class="admin-btn admin-btn-ghost admin-btn-sm" style="color:var(--admin-error);">Delete Menu</button>
      </form>
    </div>

    <!-- Menu Items -->
    <div style="padding:16px 20px;">
      <?php if (empty($menu['items'])): ?>
        <p style="font-size:13px;color:var(--admin-muted);padding:12px 0;">No items in this menu yet.</p>
      <?php else: ?>
        <div style="display:flex;flex-direction:column;gap:8px;margin-bottom:16px;" id="menuItems-<?= $menu['id'] ?>">
          <?php foreach ($menu['items'] as $item): ?>
          <div style="display:flex;align-items:center;gap:10px;padding:10px 12px;background:var(--admin-elevated);border-radius:8px;border:1px solid var(--admin-border);" data-item-id="<?= $item['id'] ?>">
            <span style="cursor:grab;color:var(--admin-muted);">☰</span>
            <?php if ($item['icon']): ?>
              <span><?= htmlspecialchars($item['icon']) ?></span>
            <?php endif; ?>
            <strong style="font-size:13px;flex:1;"><?= htmlspecialchars($item['title']) ?></strong>
            <span style="font-size:12px;color:var(--admin-muted);font-family:monospace;"><?= htmlspecialchars($item['url'] ?? '') ?></span>
            <?php if ($item['badge']): ?>
              <span class="admin-badge admin-badge-warning" style="font-size:10px;"><?= htmlspecialchars($item['badge']) ?></span>
            <?php endif; ?>
            <span style="font-size:11px;color:var(--admin-muted);"><?= $item['target'] === '_blank' ? '↗' : '' ?></span>
            <form method="post" action="/techaasvik_admin/menus/item/<?= $item['id'] ?>/delete" style="display:inline;">
              <input type="hidden" name="_csrf_token" value="<?= \Core\Auth::csrfToken() ?>">
              <button type="submit" class="admin-btn admin-btn-ghost admin-btn-sm" style="color:var(--admin-error);padding:2px 6px;font-size:11px;">✕</button>
            </form>
          </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>

      <!-- Add Item Form -->
      <form method="post" action="/techaasvik_admin/menus/<?= $menu['id'] ?>/add-item" style="display:flex;gap:8px;flex-wrap:wrap;align-items:end;padding-top:12px;border-top:1px solid var(--admin-border);">
        <input type="hidden" name="_csrf_token" value="<?= \Core\Auth::csrfToken() ?>">
        <div style="flex:1;min-width:140px;">
          <input type="text" name="title" class="admin-form-input" placeholder="Link Title" required style="font-size:13px;">
        </div>
        <div style="flex:1;min-width:140px;">
          <input type="text" name="url" class="admin-form-input" placeholder="/path or https://…" style="font-size:13px;font-family:monospace;">
        </div>
        <div style="min-width:80px;">
          <select name="target" class="admin-form-input" style="font-size:13px;">
            <option value="_self">Same Tab</option>
            <option value="_blank">New Tab</option>
          </select>
        </div>
        <button type="submit" class="admin-btn admin-btn-primary admin-btn-sm">Add Item</button>
      </form>
    </div>
  </div>
  <?php endforeach; ?>
<?php endif; ?>
