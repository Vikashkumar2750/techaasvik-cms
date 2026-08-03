<!-- SEO Tools Dashboard -->
<?php use Core\Auth; ?>

<!-- SEO Score Overview -->
<div style="display:grid;grid-template-columns:repeat(5,1fr);gap:16px;margin-bottom:32px;">
  <?php
  $total = $stats['total_published'] ?: 1;
  $cards = [
    ['label' => 'Published',     'value' => $stats['total_published'],  'color' => '#6366f1'],
    ['label' => 'Meta Titles',   'value' => $stats['with_meta_title'],  'color' => $stats['with_meta_title'] >= $total ? '#22c55e' : '#f59e0b'],
    ['label' => 'Meta Desc.',    'value' => $stats['with_meta_desc'],   'color' => $stats['with_meta_desc'] >= $total ? '#22c55e' : '#f59e0b'],
    ['label' => 'Excerpts',      'value' => $stats['with_excerpt'],     'color' => $stats['with_excerpt'] >= $total ? '#22c55e' : '#f59e0b'],
    ['label' => 'Noindexed',     'value' => $stats['noindexed'],        'color' => $stats['noindexed'] > 0 ? '#f59e0b' : '#22c55e'],
  ];
  foreach ($cards as $card): ?>
  <div class="admin-card" style="padding:20px;text-align:center;">
    <div style="font-size:28px;font-weight:800;color:<?= $card['color'] ?>;margin-bottom:4px;"><?= $card['value'] ?></div>
    <div style="font-size:12px;color:var(--admin-muted);text-transform:uppercase;letter-spacing:0.05em;"><?= $card['label'] ?></div>
    <?php if ($card['label'] !== 'Published' && $card['label'] !== 'Noindexed'): ?>
    <div style="margin-top:8px;height:4px;background:rgba(255,255,255,0.06);border-radius:2px;overflow:hidden;">
      <div style="height:100%;width:<?= round(($card['value'] / $total) * 100) ?>%;background:<?= $card['color'] ?>;border-radius:2px;"></div>
    </div>
    <div style="font-size:11px;color:var(--admin-muted);margin-top:4px;"><?= round(($card['value'] / $total) * 100) ?>%</div>
    <?php endif; ?>
  </div>
  <?php endforeach; ?>
</div>

<!-- Quick Actions -->
<div class="admin-card" style="padding:20px;margin-bottom:24px;">
  <h3 style="font-size:14px;font-weight:600;margin-bottom:16px;">⚡ Quick Actions</h3>
  <div style="display:flex;gap:12px;flex-wrap:wrap;">
    <form method="POST" action="/techaasvik_admin/seo/generate-titles" style="display:inline;">
      <input type="hidden" name="_csrf_token" value="<?= Auth::csrfToken() ?>">
      <button type="submit" class="admin-btn admin-btn-primary admin-btn-sm" onclick="return confirm('Auto-generate meta titles for all content missing them?')">
        🏷️ Auto-Generate Missing Meta Titles
      </button>
    </form>
    <form method="POST" action="/techaasvik_admin/seo/generate-descriptions" style="display:inline;">
      <input type="hidden" name="_csrf_token" value="<?= Auth::csrfToken() ?>">
      <button type="submit" class="admin-btn admin-btn-primary admin-btn-sm" onclick="return confirm('Auto-generate meta descriptions from excerpts/content?')">
        📝 Auto-Generate Missing Meta Descriptions
      </button>
    </form>
    <a href="/sitemap.xml" target="_blank" class="admin-btn admin-btn-ghost admin-btn-sm">🗺️ View Sitemap.xml</a>
    <a href="/robots.txt" target="_blank" class="admin-btn admin-btn-ghost admin-btn-sm">🤖 View Robots.txt</a>
  </div>
</div>

<!-- Missing Meta Titles -->
<?php if (!empty($missingTitle)): ?>
<div class="admin-card" style="padding:20px;margin-bottom:24px;">
  <h3 style="font-size:14px;font-weight:600;margin-bottom:12px;color:#f59e0b;">
    ⚠️ Missing Meta Titles (<?= count($missingTitle) ?>)
  </h3>
  <div class="admin-table-wrapper">
    <table class="admin-table">
      <thead>
        <tr><th>Title</th><th>Type</th><th>Status</th><th>Action</th></tr>
      </thead>
      <tbody>
        <?php foreach ($missingTitle as $item): ?>
        <tr>
          <td style="max-width:300px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"><?= htmlspecialchars($item['title']) ?></td>
          <td><span class="admin-badge"><?= ucfirst(str_replace('_', ' ', $item['type'])) ?></span></td>
          <td><span class="admin-badge admin-badge-<?= $item['status'] === 'published' ? 'success' : 'warning' ?>"><?= ucfirst($item['status']) ?></span></td>
          <td><a href="/techaasvik_admin/content/<?= $item['id'] ?>/edit" class="admin-btn admin-btn-ghost admin-btn-sm">Edit →</a></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
<?php endif; ?>

<!-- Missing Meta Descriptions -->
<?php if (!empty($missingDesc)): ?>
<div class="admin-card" style="padding:20px;margin-bottom:24px;">
  <h3 style="font-size:14px;font-weight:600;margin-bottom:12px;color:#f59e0b;">
    ⚠️ Missing Meta Descriptions (<?= count($missingDesc) ?>)
  </h3>
  <div class="admin-table-wrapper">
    <table class="admin-table">
      <thead>
        <tr><th>Title</th><th>Type</th><th>Action</th></tr>
      </thead>
      <tbody>
        <?php foreach ($missingDesc as $item): ?>
        <tr>
          <td style="max-width:300px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"><?= htmlspecialchars($item['title']) ?></td>
          <td><span class="admin-badge"><?= ucfirst(str_replace('_', ' ', $item['type'])) ?></span></td>
          <td><a href="/techaasvik_admin/content/<?= $item['id'] ?>/edit" class="admin-btn admin-btn-ghost admin-btn-sm">Edit →</a></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
<?php endif; ?>

<!-- Missing Excerpts -->
<?php if (!empty($missingExcerpt)): ?>
<div class="admin-card" style="padding:20px;margin-bottom:24px;">
  <h3 style="font-size:14px;font-weight:600;margin-bottom:12px;color:#f59e0b;">
    ⚠️ Missing Excerpts (<?= count($missingExcerpt) ?>)
  </h3>
  <div class="admin-table-wrapper">
    <table class="admin-table">
      <thead>
        <tr><th>Title</th><th>Type</th><th>Action</th></tr>
      </thead>
      <tbody>
        <?php foreach ($missingExcerpt as $item): ?>
        <tr>
          <td style="max-width:300px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"><?= htmlspecialchars($item['title']) ?></td>
          <td><span class="admin-badge"><?= ucfirst(str_replace('_', ' ', $item['type'])) ?></span></td>
          <td><a href="/techaasvik_admin/content/<?= $item['id'] ?>/edit" class="admin-btn admin-btn-ghost admin-btn-sm">Edit →</a></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
<?php endif; ?>

<!-- Noindexed Pages -->
<?php if (!empty($noindexed)): ?>
<div class="admin-card" style="padding:20px;margin-bottom:24px;">
  <h3 style="font-size:14px;font-weight:600;margin-bottom:12px;">
    🚫 Noindexed Pages (<?= count($noindexed) ?>)
  </h3>
  <div class="admin-table-wrapper">
    <table class="admin-table">
      <thead>
        <tr><th>Title</th><th>Type</th><th>Action</th></tr>
      </thead>
      <tbody>
        <?php foreach ($noindexed as $item): ?>
        <tr>
          <td><?= htmlspecialchars($item['title']) ?></td>
          <td><span class="admin-badge"><?= ucfirst(str_replace('_', ' ', $item['type'])) ?></span></td>
          <td><a href="/techaasvik_admin/content/<?= $item['id'] ?>/edit" class="admin-btn admin-btn-ghost admin-btn-sm">Edit →</a></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
<?php endif; ?>

<!-- All Green -->
<?php if (empty($missingTitle) && empty($missingDesc) && empty($missingExcerpt)): ?>
<div class="admin-card" style="padding:40px;text-align:center;">
  <div style="font-size:48px;margin-bottom:12px;">🎉</div>
  <h3 style="font-size:18px;color:#22c55e;margin-bottom:8px;">Perfect SEO Score!</h3>
  <p style="color:var(--admin-muted);">All published content has meta titles, descriptions, and excerpts.</p>
</div>
<?php endif; ?>
