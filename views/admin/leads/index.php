<!-- Admin Leads -->
<div class="admin-page-header">
  <div>
    <h1 class="admin-page-title">Leads & Subscribers</h1>
    <p class="admin-page-subtitle"><?= number_format($total) ?> total leads</p>
  </div>
  <div style="display:flex;gap:8px;">
    <a href="/techaasvik_admin/leads/export<?= !empty($filter['type']) ? '?type='.$filter['type'] : '' ?>" class="admin-btn admin-btn-secondary">📥 Export CSV</a>
  </div>
</div>

<!-- Stats Row -->
<div class="admin-stats" style="margin-bottom:20px;">
  <div class="admin-stat-card">
    <div class="admin-stat-label">Total Leads</div>
    <div class="admin-stat-value"><?= number_format($stats['total'] ?? 0) ?></div>
  </div>
  <div class="admin-stat-card">
    <div class="admin-stat-label">New (Uncontacted)</div>
    <div class="admin-stat-value"><?= number_format($stats['new'] ?? 0) ?></div>
  </div>
  <div class="admin-stat-card">
    <div class="admin-stat-label">Today</div>
    <div class="admin-stat-value"><?= number_format($stats['today'] ?? 0) ?></div>
  </div>
  <div class="admin-stat-card">
    <div class="admin-stat-label">This Week</div>
    <div class="admin-stat-value"><?= number_format($stats['week'] ?? 0) ?></div>
  </div>
</div>

<!-- Filters -->
<div style="display:flex;gap:10px;margin-bottom:16px;flex-wrap:wrap;">
  <?php foreach (['','newsletter','contact','audit','download'] as $t): ?>
  <a href="/techaasvik_admin/leads<?= $t ? '?type='.$t : '' ?>"
     class="admin-btn admin-btn-<?= ($filter['type'] ?? '') === $t ? 'primary' : 'secondary' ?> admin-btn-sm">
    <?= $t ? ucfirst($t) : 'All' ?>
  </a>
  <?php endforeach; ?>
</div>

<!-- Table -->
<div class="admin-table-wrapper">
  <table class="admin-table">
    <thead>
      <tr>
        <th>Name / Email</th>
        <th>Type</th>
        <th>Source</th>
        <th>UTM Source</th>
        <th>Status</th>
        <th>Received</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php if (!empty($items)): ?>
      <?php foreach ($items as $lead): ?>
      <tr>
        <td>
          <div style="font-weight:600;color:var(--admin-text);"><?= htmlspecialchars($lead['name'] ?? '—') ?></div>
          <div style="font-size:12px;color:var(--admin-brand);"><?= htmlspecialchars($lead['email']) ?></div>
          <?php if (!empty($lead['phone'])): ?>
          <div style="font-size:11px;color:var(--admin-muted);"><?= htmlspecialchars($lead['phone']) ?></div>
          <?php endif; ?>
        </td>
        <td><span class="type-badge type-<?= $lead['lead_type'] ?>"><?= ucfirst($lead['lead_type']) ?></span></td>
        <td style="font-size:12px;color:var(--admin-muted);max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"><?= htmlspecialchars($lead['source_page'] ?? '—') ?></td>
        <td style="font-size:12px;color:var(--admin-muted);"><?= htmlspecialchars($lead['utm_source'] ?? '—') ?></td>
        <td>
          <form method="post" action="/techaasvik_admin/leads/<?= $lead['id'] ?>/status" style="display:inline;">
            <input type="hidden" name="_csrf_token" value="<?= \Core\Auth::csrfToken() ?>">
            <select name="status" class="admin-form-select" style="padding:3px 8px;font-size:11px;height:auto;" onchange="this.form.submit()">
              <?php foreach (['new','contacted','converted','unsubscribed'] as $s): ?>
              <option value="<?= $s ?>" <?= ($lead['status'] ?? 'new') === $s ? 'selected' : '' ?>><?= ucfirst($s) ?></option>
              <?php endforeach; ?>
            </select>
          </form>
        </td>
        <td style="font-size:12px;color:var(--admin-muted);white-space:nowrap;"><?= date('d M Y H:i', strtotime($lead['created_at'])) ?></td>
        <td>
          <form method="post" action="/techaasvik_admin/leads/<?= $lead['id'] ?>/delete" style="display:inline;" onsubmit="return confirm('Delete this lead?')">
            <input type="hidden" name="_csrf_token" value="<?= \Core\Auth::csrfToken() ?>">
            <button type="submit" class="admin-btn admin-btn-danger admin-btn-sm">Del</button>
          </form>
          <?php if (!empty($lead['email'])): ?>
          <a href="mailto:<?= htmlspecialchars($lead['email']) ?>" class="admin-btn admin-btn-ghost admin-btn-sm">Email</a>
          <?php endif; ?>
        </td>
      </tr>
      <?php endforeach; ?>
      <?php else: ?>
      <tr><td colspan="7" style="text-align:center;padding:40px;color:var(--admin-muted);">No leads yet. They'll appear here once visitors fill in forms.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<!-- Pagination -->
<?php if ($total > $perPage): ?>
<div style="display:flex;justify-content:center;gap:8px;margin-top:16px;">
  <?php for ($p = 1; $p <= ceil($total/$perPage); $p++): ?>
  <a href="?page=<?= $p ?>&type=<?= urlencode($filter['type'] ?? '') ?>"
     class="admin-btn <?= $p === $page ? 'admin-btn-primary' : 'admin-btn-secondary' ?> admin-btn-sm"><?= $p ?></a>
  <?php endfor; ?>
</div>
<?php endif; ?>
