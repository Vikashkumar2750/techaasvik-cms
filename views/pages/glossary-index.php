<!-- Glossary Index -->
<div class="container" style="padding-top:var(--space-10);padding-bottom:var(--space-16);">

  <?php \Core\View::partial('breadcrumb', ['crumbs' => [['name'=>'Home','url'=>'/'],['name'=>'Glossary']]]) ?>

  <div style="margin-top:var(--space-4);margin-bottom:var(--space-10);">
    <h1 style="margin-bottom:var(--space-4);">Digital Marketing Glossary</h1>
    <p style="font-size:var(--text-lg);color:var(--text-secondary);max-width:680px;">
      2,000+ digital marketing terms defined in plain English. From A/B Testing to Zero-Click Searches — the most comprehensive marketing glossary in India.
    </p>

    <!-- Search -->
    <div class="search-bar" style="max-width:480px;margin-top:var(--space-6);">
      <svg class="search-bar-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
      <input type="search" id="glossarySearch" placeholder="Search 2000+ terms…" oninput="filterGlossary(this.value)" aria-label="Search glossary">
    </div>
  </div>

  <!-- A-Z Letter Nav -->
  <div style="display:flex;flex-wrap:wrap;gap:6px;margin-bottom:var(--space-8);" role="navigation" aria-label="Alphabet navigation">
    <?php
    $existingLetters = array_column($letters ?? [], 'letter');
    foreach (range('A', 'Z') as $letter):
      $has = in_array($letter, $existingLetters);
    ?>
    <a href="<?= $has ? '/glossary/' . strtolower($letter) : '#' ?>"
       class="<?= $has ? 'btn btn-secondary btn-sm' : 'btn btn-ghost btn-sm' ?>"
       style="width:40px;height:40px;padding:0;justify-content:center;font-weight:700;<?= !$has ? 'opacity:0.3;cursor:default;' : '' ?>"
       <?= !$has ? 'aria-disabled="true"' : '' ?>>
      <?= $letter ?>
    </a>
    <?php endforeach; ?>
  </div>

  <!-- Stats Bar -->
  <?php if (!empty($letters)): ?>
  <div style="display:flex;gap:var(--space-8);padding:var(--space-4) 0;border-top:1px solid var(--border-subtle);border-bottom:1px solid var(--border-subtle);margin-bottom:var(--space-8);flex-wrap:wrap;">
    <div><strong style="color:var(--text-primary);"><?= array_sum(array_column($letters, 'cnt')) ?></strong> <span style="color:var(--text-muted);font-size:var(--text-sm);">Total Terms</span></div>
    <div><strong style="color:var(--text-primary);"><?= count($letters) ?></strong> <span style="color:var(--text-muted);font-size:var(--text-sm);">Letters Covered</span></div>
    <div><strong style="color:var(--accent-400);">New daily</strong> <span style="color:var(--text-muted);font-size:var(--text-sm);">Terms being added</span></div>
  </div>
  <?php endif; ?>

  <!-- Letter Sections -->
  <?php foreach (($letters ?? []) as $letterData): ?>
  <div class="glossary-letter-section" id="letter-<?= strtolower($letterData['letter']) ?>" style="margin-bottom:var(--space-10);">
    <div style="display:flex;align-items:center;gap:var(--space-4);margin-bottom:var(--space-5);">
      <div style="width:48px;height:48px;background:var(--brand-600);border-radius:var(--radius-lg);display:flex;align-items:center;justify-content:center;font-family:var(--font-display);font-size:var(--text-xl);font-weight:var(--fw-black);color:#fff;flex-shrink:0;">
        <?= $letterData['letter'] ?>
      </div>
      <div>
        <span style="font-size:var(--text-xs);color:var(--text-muted);"><?= $letterData['cnt'] ?> term<?= $letterData['cnt'] > 1 ? 's' : '' ?></span>
        <a href="/glossary/<?= strtolower($letterData['letter']) ?>" class="btn btn-ghost btn-sm" style="margin-left:8px;">View All →</a>
      </div>
    </div>
  </div>
  <?php endforeach; ?>

  <!-- Recent Terms -->
  <?php if (!empty($recentTerms)): ?>
  <div style="margin-top:var(--space-8);">
    <h2 style="font-size:var(--text-xl);margin-bottom:var(--space-5);">Recently Added Terms</h2>
    <div class="grid grid-3 gap-4">
      <?php foreach ($recentTerms as $term): ?>
      <a href="/glossary/term/<?= e($term['slug']) ?>" style="text-decoration:none;display:flex;align-items:center;gap:12px;padding:var(--space-4);background:var(--bg-surface);border:1px solid var(--border-subtle);border-radius:var(--radius-lg);transition:all 0.15s;" onmouseover="this.style.borderColor='rgba(99,102,241,0.3)';this.style.color='var(--brand-400)'" onmouseout="this.style.borderColor='rgba(255,255,255,0.06)';this.style.color='inherit'">
        <div style="width:32px;height:32px;border-radius:6px;background:var(--bg-elevated);display:flex;align-items:center;justify-content:center;font-weight:800;color:var(--brand-400);font-size:14px;flex-shrink:0;">
          <?= strtoupper(substr($term['title'], 0, 1)) ?>
        </div>
        <span style="font-size:var(--text-sm);font-weight:var(--fw-medium);color:var(--text-primary);"><?= e($term['title']) ?></span>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
  <?php endif; ?>

</div>

<script>
function filterGlossary(q) {
  const items = document.querySelectorAll('.glossary-letter-section');
  const query = q.toLowerCase();
  if (!query) { items.forEach(i => i.style.display = ''); return; }
  // For now redirect to search
  if (q.length >= 2) {
    clearTimeout(window._gst);
    window._gst = setTimeout(() => {
      window.location.href = '/search?q=' + encodeURIComponent(q);
    }, 800);
  }
}
</script>
