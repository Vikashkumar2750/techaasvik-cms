<!-- ══════════════════════════════════════════════════════
     CLUSTER PAGE — Learn Sub-Topic with Sidebar Navigation
     Left: sibling navigation │ Right: detailed content
════════════════════════════════════════════════════════ -->

<?php
$currentSlug = $post['slug'] ?? '';
$pillarTitle = $pillar['title'] ?? 'Learn';
$pillarSlug  = $pillar['slug'] ?? ($topic ?? '');
$difficulty  = $post['difficulty'] ?? 'beginner';
$diffMap     = [
    'beginner'     => ['🟢', '#4ade80', 'Beginner'],
    'intermediate' => ['🟡', '#fbbf24', 'Intermediate'],
    'advanced'     => ['🔴', '#f87171', 'Advanced'],
];
$diff = $diffMap[$difficulty] ?? $diffMap['beginner'];
?>

<div class="container" style="padding-top:var(--space-8);padding-bottom:var(--space-16);">

  <!-- Breadcrumb -->
  <?php \Core\View::partial('breadcrumb', ['crumbs' => [
    ['name'=>'Home','url'=>'/'],
    ['name'=>'Learn','url'=>'/learn'],
    ['name'=> $pillarTitle,'url'=>'/learn/'.$pillarSlug],
    ['name'=> $post['title']],
  ]]) ?>

  <div style="display:grid;grid-template-columns:280px 1fr;gap:var(--space-8);margin-top:var(--space-5);align-items:start;">

    <!-- ═══════ LEFT SIDEBAR — Sub-Topic Navigation ═══════ -->
    <aside class="cluster-sidebar" id="clusterSidebar">
      <div style="position:sticky;top:100px;">
        <!-- Back to pillar -->
        <a href="/learn/<?= e($pillarSlug) ?>" style="display:flex;align-items:center;gap:8px;font-size:var(--text-sm);color:var(--brand-400);text-decoration:none;margin-bottom:var(--space-5);font-weight:var(--fw-semibold);">
          ← <?= e($pillarTitle) ?>
        </a>

        <div style="background:var(--bg-surface);border:1px solid var(--border-subtle);border-radius:var(--radius-xl);overflow:hidden;">
          <div style="padding:var(--space-4) var(--space-5);border-bottom:1px solid var(--border-subtle);background:var(--bg-elevated);">
            <p style="font-size:var(--text-xs);font-weight:var(--fw-bold);text-transform:uppercase;letter-spacing:0.08em;color:var(--text-muted);margin:0;">📑 Sub-Topics</p>
          </div>
          <nav style="padding:var(--space-2) 0;">
            <?php foreach ($siblings as $i => $sib): ?>
            <?php $isActive = ($sib['slug'] === $currentSlug); ?>
            <a href="/learn/<?= e($pillarSlug) ?>/<?= e($sib['slug']) ?>"
               class="cluster-nav-item <?= $isActive ? 'active' : '' ?>"
               style="display:flex;align-items:center;gap:10px;padding:10px var(--space-5);font-size:var(--text-sm);color:<?= $isActive ? 'var(--brand-400)' : 'var(--text-secondary)' ?>;text-decoration:none;transition:all 0.15s;font-weight:<?= $isActive ? 'var(--fw-semibold)' : 'var(--fw-normal)' ?>;background:<?= $isActive ? 'rgba(99,102,241,0.08)' : 'transparent' ?>;border-left:3px solid <?= $isActive ? 'var(--brand-500)' : 'transparent' ?>;"
               onmouseover="if(!this.classList.contains('active')){this.style.background='var(--bg-elevated)';this.style.color='var(--text-primary)'}"
               onmouseout="if(!this.classList.contains('active')){this.style.background='transparent';this.style.color='var(--text-secondary)'}">
              <span style="width:22px;height:22px;border-radius:50%;background:<?= $isActive ? 'var(--brand-600)' : 'var(--bg-elevated)' ?>;display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;color:<?= $isActive ? '#fff' : 'var(--text-muted)' ?>;flex-shrink:0;">
                <?= $i + 1 ?>
              </span>
              <span style="flex:1;line-height:1.4;"><?= e($sib['title']) ?></span>
              <?php if (!empty($sib['read_time'])): ?>
              <span style="font-size:10px;color:var(--text-muted);flex-shrink:0;"><?= $sib['read_time'] ?>m</span>
              <?php endif; ?>
            </a>
            <?php endforeach; ?>
          </nav>
        </div>

        <!-- Difficulty badge -->
        <div style="margin-top:var(--space-4);padding:var(--space-3) var(--space-4);background:var(--bg-surface);border:1px solid var(--border-subtle);border-radius:var(--radius-lg);text-align:center;">
          <span style="font-size:var(--text-xs);color:var(--text-muted);">Level: </span>
          <span style="font-size:var(--text-xs);font-weight:700;color:<?= $diff[1] ?>;"><?= $diff[0] ?> <?= $diff[2] ?></span>
        </div>
      </div>
    </aside>

    <!-- ═══════ RIGHT CONTENT — Detailed Learning ═══════ -->
    <article id="mainArticle">
      <!-- Header -->
      <header style="margin-bottom:var(--space-8);">
        <div style="display:flex;gap:8px;align-items:center;margin-bottom:var(--space-4);flex-wrap:wrap;">
          <span class="badge badge-brand"><?= $diff[0] ?> <?= $diff[2] ?></span>
          <?php if (!empty($post['read_time'])): ?>
          <span style="font-size:var(--text-xs);color:var(--text-muted);">⏱ <?= $post['read_time'] ?> min read</span>
          <?php endif; ?>
          <?php if (!empty($post['updated_at'])): ?>
          <span style="font-size:var(--text-xs);color:var(--text-muted);">Updated <?= format_date($post['updated_at']) ?></span>
          <?php endif; ?>
        </div>
        <h1 style="font-size:clamp(1.5rem,3.5vw,2.25rem);margin-bottom:var(--space-4);"><?= e($post['title']) ?></h1>
        <?php if (!empty($post['excerpt'])): ?>
        <p style="font-size:var(--text-lg);color:var(--text-secondary);line-height:var(--leading-relaxed);"><?= e($post['excerpt']) ?></p>
        <?php endif; ?>
      </header>

      <!-- Table of Contents (in-content) -->
      <nav class="toc" id="articleToc" aria-label="Table of Contents">
        <p class="toc-title">📋 In This Lesson</p>
        <ol class="toc-list" id="tocList">
          <!-- Populated by JS from h2/h3 headings -->
        </ol>
      </nav>

      <!-- Article Body -->
      <div class="prose" id="articleBody">
        <?= $post['content'] ?? '<p>Content coming soon.</p>' ?>
      </div>

      <!-- Navigation: Prev/Next Sub-Topic -->
      <?php
      $prevTopic = null;
      $nextTopic = null;
      foreach ($siblings as $i => $sib) {
          if ($sib['slug'] === $currentSlug) {
              $prevTopic = $siblings[$i - 1] ?? null;
              $nextTopic = $siblings[$i + 1] ?? null;
              break;
          }
      }
      ?>
      <div style="display:flex;justify-content:space-between;gap:var(--space-4);margin-top:var(--space-10);padding-top:var(--space-6);border-top:1px solid var(--border-subtle);flex-wrap:wrap;">
        <?php if ($prevTopic): ?>
        <a href="/learn/<?= e($pillarSlug) ?>/<?= e($prevTopic['slug']) ?>" class="btn btn-secondary" style="flex:1;max-width:48%;justify-content:flex-start;gap:8px;">
          ← <?= e($prevTopic['title']) ?>
        </a>
        <?php else: ?>
        <div style="flex:1;max-width:48%;"></div>
        <?php endif; ?>

        <?php if ($nextTopic): ?>
        <a href="/learn/<?= e($pillarSlug) ?>/<?= e($nextTopic['slug']) ?>" class="btn btn-primary" style="flex:1;max-width:48%;justify-content:flex-end;gap:8px;">
          <?= e($nextTopic['title']) ?> →
        </a>
        <?php else: ?>
        <a href="/learn/<?= e($pillarSlug) ?>" class="btn btn-primary" style="flex:1;max-width:48%;justify-content:center;gap:8px;">
          ✅ Complete — Back to <?= e($pillarTitle) ?>
        </a>
        <?php endif; ?>
      </div>

      <!-- Related -->
      <?php if (!empty($related)): ?>
      <div style="margin-top:var(--space-10);">
        <h2 style="font-size:var(--text-lg);margin-bottom:var(--space-5);">Related Topics</h2>
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:var(--space-4);">
          <?php foreach ($related as $r): ?>
          <a href="/learn/<?= e($pillarSlug) ?>/<?= e($r['slug']) ?>" class="card card-interactive" style="padding:var(--space-4);text-decoration:none;">
            <h3 style="font-size:var(--text-sm);font-weight:var(--fw-semibold);color:var(--text-primary);margin-bottom:6px;"><?= e($r['title']) ?></h3>
            <p style="font-size:var(--text-xs);color:var(--text-muted);margin:0;"><?= e(substr($r['excerpt'] ?? '', 0, 80)) ?></p>
          </a>
          <?php endforeach; ?>
        </div>
      </div>
      <?php endif; ?>
    </article>

  </div>
</div>

<!-- TOC JS (same as toc partial) -->
<script>
(function() {
  const content = document.getElementById('articleBody');
  const list    = document.getElementById('tocList');
  if (!content || !list) return;

  const headings = content.querySelectorAll('h2, h3');
  if (headings.length < 3) {
    document.getElementById('articleToc').style.display = 'none';
    return;
  }

  headings.forEach((h, i) => {
    if (!h.id) h.id = 'lesson-' + i;
    const li  = document.createElement('li');
    li.className = 'toc-item' + (h.tagName === 'H3' ? ' toc-h3' : '');
    const a   = document.createElement('a');
    a.href    = '#' + h.id;
    a.textContent = h.textContent;
    li.appendChild(a);
    list.appendChild(li);
  });

  const observer = new IntersectionObserver(entries => {
    entries.forEach(entry => {
      const id   = entry.target.id;
      const link = list.querySelector(`a[href="#${id}"]`);
      if (link) link.closest('.toc-item').classList.toggle('active', entry.isIntersecting);
    });
  }, { rootMargin: '-80px 0px -70% 0px' });

  headings.forEach(h => observer.observe(h));
})();
</script>

<style>
/* Responsive: collapse sidebar on mobile */
@media (max-width: 768px) {
  .cluster-sidebar { display: none; }
  [style*="grid-template-columns:280px 1fr"] {
    grid-template-columns: 1fr !important;
  }
}
</style>
