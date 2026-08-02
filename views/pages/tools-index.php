<!-- Tools Index -->
<div class="container" style="padding-top:var(--space-10);padding-bottom:var(--space-16);">

  <?php \Core\View::partial('breadcrumb', ['crumbs' => [['name'=>'Home','url'=>'/'],['name'=>'Free Tools']]]) ?>

  <div style="margin-top:var(--space-4);margin-bottom:var(--space-10);text-align:center;">
    <h1>Free Digital Marketing Tools</h1>
    <p style="font-size:var(--text-lg);color:var(--text-secondary);max-width:600px;margin:var(--space-4) auto 0;">
      50+ professional marketing tools. No signup. No cost. Use instantly.
    </p>
    <!-- Tool Search -->
    <div class="search-bar" style="max-width:480px;margin:var(--space-6) auto 0;">
      <svg class="search-bar-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
      <input type="search" id="toolSearch" placeholder="Search tools…" oninput="filterTools(this.value)" aria-label="Search tools">
    </div>
  </div>

  <!-- Tool Categories Quick Filter -->
  <div style="display:flex;flex-wrap:wrap;gap:8px;margin-bottom:var(--space-8);justify-content:center;">
    <?php foreach(['All','SEO','Google Ads','Meta Ads','Content','Analytics','Calculator','Schema'] as $cat): ?>
    <button class="tool-filter-btn tag <?= $cat === 'All' ? 'active' : '' ?>"
            onclick="filterByCategory('<?= $cat ?>',this)" style="cursor:pointer;border:none;">
      <?= $cat ?>
    </button>
    <?php endforeach; ?>
  </div>

  <!-- Tools Grid -->
  <?php if (!empty($tools)): ?>
  <div class="grid grid-3 gap-6" id="toolsGrid">
    <?php
    $toolIcons = ['🔍','⚙️','📊','✍️','🔗','🖼','📧','🎯','🧮','📋','📱','💡','🔢','📌','🚀'];
    foreach ($tools as $i => $tool):
    $icon = $toolIcons[$i % count($toolIcons)];
    $cat  = $tool['category_name'] ?? 'SEO';
    ?>
    <a href="/tools/<?= e($tool['slug']) ?>"
       class="card card-interactive tool-card"
       data-category="<?= e($cat) ?>"
       style="text-decoration:none;display:flex;flex-direction:column;"
       id="tool-<?= e($tool['slug']) ?>">
      <div style="display:flex;align-items:flex-start;gap:14px;margin-bottom:12px;">
        <div style="width:48px;height:48px;border-radius:12px;background:linear-gradient(135deg,rgba(99,102,241,0.2),rgba(139,92,246,0.1));display:flex;align-items:center;justify-content:center;font-size:22px;flex-shrink:0;">
          <?= $icon ?>
        </div>
        <div>
          <span class="badge badge-gray" style="font-size:10px;margin-bottom:4px;"><?= e($cat) ?></span>
          <h2 style="font-size:var(--text-base);font-weight:var(--fw-semibold);color:var(--text-primary);line-height:1.3;"><?= e($tool['title']) ?></h2>
        </div>
      </div>
      <?php if (!empty($tool['excerpt'])): ?>
      <p style="font-size:var(--text-sm);color:var(--text-secondary);flex:1;line-height:1.5;"><?= str_truncate($tool['excerpt'], 100) ?></p>
      <?php endif; ?>
      <div style="display:flex;align-items:center;justify-content:space-between;margin-top:14px;padding-top:12px;border-top:1px solid var(--border-subtle);">
        <span style="font-size:var(--text-xs);color:var(--accent-400);font-weight:var(--fw-semibold);">Free Tool</span>
        <span style="font-size:var(--text-xs);color:var(--text-muted);">Use Now →</span>
      </div>
    </a>
    <?php endforeach; ?>
  </div>
  <?php else: ?>
  <div style="text-align:center;padding:80px;color:var(--text-muted);">
    <div style="font-size:48px;margin-bottom:16px;">⚙️</div>
    <p>Tools being added. <a href="/blog">Read our guides</a> in the meantime.</p>
  </div>
  <?php endif; ?>

  <!-- Calculators CTA -->
  <div style="margin-top:var(--space-12);padding:var(--space-8);background:var(--bg-surface);border:1px solid var(--border-subtle);border-radius:var(--radius-2xl);text-align:center;">
    <h2 style="font-size:var(--text-xl);margin-bottom:var(--space-3);">🧮 Marketing Calculators</h2>
    <p style="color:var(--text-secondary);margin-bottom:var(--space-6);">ROI, ROAS, CAC, LTV, CPL, CPC — calculate every marketing metric instantly.</p>
    <a href="/calculators" class="btn btn-primary">View All Calculators →</a>
  </div>

</div>

<script>
function filterTools(q) {
  document.querySelectorAll('.tool-card').forEach(card => {
    const text = card.textContent.toLowerCase();
    card.style.display = text.includes(q.toLowerCase()) ? '' : 'none';
  });
}

function filterByCategory(cat, btn) {
  document.querySelectorAll('.tool-filter-btn').forEach(b => b.classList.remove('active'));
  btn.classList.add('active');
  document.querySelectorAll('.tool-card').forEach(card => {
    card.style.display = (cat === 'All' || card.dataset.category === cat) ? '' : 'none';
  });
}
</script>
