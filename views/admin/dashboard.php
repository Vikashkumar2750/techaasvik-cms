<!-- Dashboard -->
<div class="admin-page-header">
  <div>
    <h1 class="admin-page-title">Dashboard</h1>
    <p class="admin-page-subtitle">Welcome back, <?= htmlspecialchars($admin['username'] ?? 'Admin') ?> 👋 &nbsp;Here's your platform overview.</p>
  </div>
  <a href="/techaasvik_admin/content/new" class="admin-btn admin-btn-primary">
    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
    New Content
  </a>
</div>

<!-- Stats Grid -->
<div class="admin-stats">
  <div class="admin-stat-card">
    <div class="admin-stat-label">Total Content</div>
    <div class="admin-stat-value"><?= number_format($stats['total'] ?? 0) ?></div>
    <div class="admin-stat-delta">All content types</div>
  </div>
  <div class="admin-stat-card">
    <div class="admin-stat-label">Published</div>
    <div class="admin-stat-value"><?= number_format($stats['published'] ?? 0) ?></div>
    <div class="admin-stat-delta" style="color:#fbbf24"><?= number_format($stats['draft'] ?? 0) ?> drafts pending</div>
  </div>
  <div class="admin-stat-card">
    <div class="admin-stat-label">Total Leads</div>
    <div class="admin-stat-value"><?= number_format($leads['total'] ?? 0) ?></div>
    <div class="admin-stat-delta"><?= number_format($leads['new'] ?? 0) ?> new</div>
  </div>
  <div class="admin-stat-card">
    <div class="admin-stat-label">Leads Today</div>
    <div class="admin-stat-value"><?= number_format($leads['today'] ?? 0) ?></div>
    <div class="admin-stat-delta"><?= number_format($leads['week'] ?? 0) ?> this week</div>
  </div>
</div>

<!-- Content by Type -->
<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:24px;">

  <!-- Recent Activity -->
  <div class="admin-table-wrapper">
    <div style="padding:16px 20px;border-bottom:1px solid var(--admin-border);display:flex;justify-content:space-between;align-items:center;">
      <strong style="font-size:14px;">Recent Activity</strong>
      <a href="/techaasvik_admin/content" class="admin-btn admin-btn-ghost admin-btn-sm">View All</a>
    </div>
    <table class="admin-table">
      <thead>
        <tr>
          <th>Title</th>
          <th>Type</th>
          <th>Status</th>
          <th>Updated</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach (($recent ?? []) as $item): ?>
        <tr>
          <td>
            <a href="/techaasvik_admin/content/<?= $item['id'] ?>/edit" style="color:var(--admin-text);font-weight:500;max-width:200px;display:block;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;" title="<?= htmlspecialchars($item['title']) ?>">
              <?= htmlspecialchars(mb_strimwidth($item['title'], 0, 40, '…')) ?>
            </a>
            <small style="color:var(--admin-muted);"><?= htmlspecialchars($item['author_name'] ?? '') ?></small>
          </td>
          <td><span class="type-badge type-<?= $item['type'] ?>"><?= htmlspecialchars(str_replace('_',' ',$item['type'])) ?></span></td>
          <td><span class="status-badge status-<?= $item['status'] ?>"><span class="status-dot"></span><?= $item['status'] ?></span></td>
          <td style="color:var(--admin-muted);font-size:12px;"><?= date('d M', strtotime($item['updated_at'])) ?></td>
        </tr>
        <?php endforeach; ?>
        <?php if (empty($recent)): ?>
        <tr><td colspan="4" style="text-align:center;color:var(--admin-muted);padding:24px;">No content yet. <a href="/techaasvik_admin/content/new">Create your first post →</a></td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <!-- Content by Type breakdown -->
  <div class="admin-table-wrapper">
    <div style="padding:16px 20px;border-bottom:1px solid var(--admin-border);">
      <strong style="font-size:14px;">Content by Type</strong>
    </div>
    <table class="admin-table">
      <thead>
        <tr><th>Content Type</th><th>Count</th><th>Action</th></tr>
      </thead>
      <tbody>
        <?php foreach (($stats['by_type'] ?? []) as $row): ?>
        <tr>
          <td><span class="type-badge type-<?= $row['type'] ?>"><?= htmlspecialchars(str_replace('_',' ', ucfirst($row['type']))) ?></span></td>
          <td><strong><?= number_format($row['cnt']) ?></strong></td>
          <td><a href="/techaasvik_admin/content?type=<?= urlencode($row['type']) ?>" class="admin-btn admin-btn-ghost admin-btn-sm">View</a></td>
        </tr>
        <?php endforeach; ?>
        <?php if (empty($stats['by_type'])): ?>
        <tr><td colspan="3" style="text-align:center;color:var(--admin-muted);padding:24px;">No content yet.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

</div>

<!-- Quick Actions -->
<div style="background:var(--admin-surface);border:1px solid var(--admin-border);border-radius:12px;padding:20px;">
  <p style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:var(--admin-muted);margin-bottom:14px;">Quick Actions</p>
  <div style="display:flex;flex-wrap:wrap;gap:10px;">
    <?php
    $quickTypes = [
      ['post','Blog Post'],['pillar','Pillar Page'],['glossary_term','Glossary Term'],
      ['tool','Tool Page'],['case_study','Case Study'],['statistics','Statistics Page'],
      ['course','Course'],['page','Static Page'],['news_article','News Article'],
    ];
    foreach ($quickTypes as [$t, $label]): ?>
    <a href="/techaasvik_admin/content/new?type=<?= $t ?>" class="admin-btn admin-btn-secondary admin-btn-sm">
      + <?= $label ?>
    </a>
    <?php endforeach; ?>
  </div>
</div>
