<!-- ══════════════════════════════════════════════════════
     PILLAR PAGE — Premium Guide Template
     Distinct from blog posts: TOC, Key Takeaways, 
     Author E-E-A-T, Progress Sidebar, Related Guides
════════════════════════════════════════════════════════ -->

<?php
// Extract H2 headings from content for TOC
$tocItems = [];
if (!empty($pillar['content'])) {
    preg_match_all('/<h2[^>]*(?:id=["\']([^"\']*)["\'])?[^>]*>(.*?)<\/h2>/is', $pillar['content'], $matches, PREG_SET_ORDER);
    foreach ($matches as $i => $m) {
        $id   = !empty($m[1]) ? $m[1] : 'section-' . ($i + 1);
        $text = strip_tags($m[2]);
        $tocItems[] = ['id' => $id, 'text' => $text];
    }
    // Inject IDs into H2 tags if missing
    $contentHtml = $pillar['content'];
    $counter = 0;
    $contentHtml = preg_replace_callback('/<h2([^>]*)>(.*?)<\/h2>/is', function($m) use (&$counter, $tocItems) {
        $id = $tocItems[$counter]['id'] ?? 'section-' . ($counter + 1);
        $counter++;
        // Check if id already exists
        if (str_contains($m[1], 'id=')) return $m[0];
        return '<h2 id="' . e($id) . '"' . $m[1] . '>' . $m[2] . '</h2>';
    }, $contentHtml);
}

// Extract key takeaways from content_meta or generate from excerpt
$keyTakeaways = [];
if (!empty($pillar['excerpt'])) {
    // Split excerpt into bullet-friendly sentences
    $sentences = preg_split('/(?<=[.!?])\s+/', $pillar['excerpt']);
    foreach ($sentences as $s) {
        $s = trim($s);
        if (strlen($s) > 20) $keyTakeaways[] = $s;
    }
}

// Author info
$authorName = $pillar['author_name'] ?? 'TechAasvik Editorial Team';
$authorBio = $pillar['author_bio'] ?? 'Our editorial team consists of certified digital marketing professionals with 10+ years of combined experience across SEO, PPC, content marketing, and analytics.';
$authorCredentials = $pillar['author_credentials'] ?? 'Google Ads Certified • GA4 Certified • HubSpot Certified';

// Date formatting
$publishDate = !empty($pillar['published_at']) ? date('M j, Y', strtotime($pillar['published_at'])) : '';
$updateDate = !empty($pillar['updated_at']) ? date('M Y', strtotime($pillar['updated_at'])) : date('M Y');

// Difficulty
$difficultyMap = [
    'beginner'     => ['label' => 'Beginner', 'color' => '#4ade80', 'icon' => '🟢'],
    'intermediate' => ['label' => 'Intermediate', 'color' => '#fbbf24', 'icon' => '🟡'],
    'advanced'     => ['label' => 'Advanced', 'color' => '#f87171', 'icon' => '🔴'],
];
$diff = $difficultyMap[$pillar['difficulty'] ?? 'beginner'] ?? $difficultyMap['beginner'];

// Get related pillars
$relatedPillars = $relatedPillars ?? [];
?>

<div class="container" style="padding-top:var(--space-8);padding-bottom:var(--space-16);">

  <?php \Core\View::partial('breadcrumb', ['crumbs' => [['name'=>'Home','url'=>'/'],['name'=>'Knowledge Center','url'=>'/learn'],['name'=>$pillar['title']]]]) ?>

  <!-- ── Guide Header ── -->
  <header style="margin-top:var(--space-4);margin-bottom:var(--space-8);">
    <div style="display:flex;align-items:center;gap:var(--space-3);margin-bottom:var(--space-4);flex-wrap:wrap;">
      <span class="badge badge-brand" style="font-size:12px;">📚 Complete Guide</span>
      <span style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.06em;color:<?= $diff['color'] ?>;background:<?= $diff['color'] ?>18;padding:3px 10px;border-radius:var(--radius-full);"><?= $diff['icon'] ?> <?= $diff['label'] ?></span>
    </div>

    <h1 style="font-size:var(--text-3xl);line-height:1.2;margin-bottom:var(--space-4);max-width:800px;"><?= e($pillar['title']) ?></h1>

    <?php if (!empty($pillar['excerpt'])): ?>
    <p style="font-size:var(--text-lg);color:var(--text-secondary);line-height:var(--leading-relaxed);max-width:750px;"><?= e($pillar['excerpt']) ?></p>
    <?php endif; ?>

    <!-- Meta Bar -->
    <div style="display:flex;align-items:center;gap:var(--space-5);margin-top:var(--space-5);padding:var(--space-4);background:var(--bg-surface);border:1px solid var(--border-subtle);border-radius:var(--radius-lg);flex-wrap:wrap;">
      <?php if ($pillar['read_time'] ?? 0): ?>
      <span style="font-size:var(--text-sm);color:var(--text-muted);display:flex;align-items:center;gap:6px;">
        <span>⏱</span> <?= $pillar['read_time'] ?> min read
      </span>
      <?php endif; ?>
      <span style="color:var(--border-subtle);">|</span>
      <span style="font-size:var(--text-sm);color:var(--text-muted);display:flex;align-items:center;gap:6px;">
        <span>📅</span> Updated <?= $updateDate ?>
      </span>
      <span style="color:var(--border-subtle);">|</span>
      <span style="font-size:var(--text-sm);color:var(--text-muted);display:flex;align-items:center;gap:6px;">
        <span>👤</span> <?= e($authorName) ?>
      </span>
      <?php if (!empty($pillar['word_count'])): ?>
      <span style="color:var(--border-subtle);">|</span>
      <span style="font-size:var(--text-sm);color:var(--text-muted);display:flex;align-items:center;gap:6px;">
        <span>📄</span> <?= number_format($pillar['word_count']) ?> words
      </span>
      <?php endif; ?>
    </div>
  </header>

  <!-- ── Key Takeaways Box ── -->
  <?php if (!empty($keyTakeaways)): ?>
  <div style="background:linear-gradient(135deg,rgba(99,102,241,0.06),rgba(52,211,153,0.04));border:1px solid rgba(99,102,241,0.15);border-radius:var(--radius-xl);padding:var(--space-6) var(--space-7);margin-bottom:var(--space-8);" id="key-takeaways">
    <h2 style="font-size:var(--text-base);font-weight:var(--fw-bold);color:var(--brand-primary);margin-bottom:var(--space-4);display:flex;align-items:center;gap:8px;">
      <span style="font-size:20px;">💡</span> Key Takeaways
    </h2>
    <ul style="list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:var(--space-3);">
      <?php foreach ($keyTakeaways as $takeaway): ?>
      <li style="display:flex;gap:10px;font-size:var(--text-sm);line-height:1.6;color:var(--text-secondary);">
        <span style="color:var(--accent-400);font-size:14px;flex-shrink:0;margin-top:2px;">✓</span>
        <span><?= e($takeaway) ?></span>
      </li>
      <?php endforeach; ?>
    </ul>
  </div>
  <?php endif; ?>

  <!-- ── Table of Contents ── -->
  <?php if (count($tocItems) >= 3): ?>
  <div style="background:var(--bg-surface);border:1px solid var(--border-subtle);border-radius:var(--radius-xl);padding:var(--space-5) var(--space-6);margin-bottom:var(--space-10);" id="toc-box">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:var(--space-4);">
      <h2 style="font-size:var(--text-sm);font-weight:var(--fw-bold);color:var(--text-muted);text-transform:uppercase;letter-spacing:0.06em;margin:0;display:flex;align-items:center;gap:8px;">
        <span>📋</span> Table of Contents
      </h2>
      <span style="font-size:var(--text-xs);color:var(--text-muted);"><?= count($tocItems) ?> chapters</span>
    </div>
    <nav style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:var(--space-1);">
      <?php foreach ($tocItems as $i => $item): ?>
      <a href="#<?= e($item['id']) ?>" style="display:flex;align-items:center;gap:10px;padding:8px 12px;font-size:var(--text-sm);color:var(--text-secondary);text-decoration:none;border-radius:var(--radius-md);transition:all 0.15s;" class="toc-link"
         onmouseover="this.style.background='rgba(99,102,241,0.06)';this.style.color='var(--brand-400)'"
         onmouseout="this.style.background='transparent';this.style.color='var(--text-secondary)'">
        <span style="width:22px;height:22px;border-radius:50%;background:rgba(99,102,241,0.1);color:var(--brand-primary);display:flex;align-items:center;justify-content:center;font-size:10px;font-weight:700;flex-shrink:0;"><?= $i + 1 ?></span>
        <span style="line-height:1.4;"><?= e($item['text']) ?></span>
      </a>
      <?php endforeach; ?>
    </nav>
  </div>
  <?php endif; ?>

  <!-- ── Main Content + Sidebar Grid ── -->
  <div style="display:grid;grid-template-columns:1fr 280px;gap:var(--space-10);align-items:start;">

    <!-- Article Body -->
    <article>
      <?php if (!empty($contentHtml)): ?>
      <div class="prose pillar-prose" id="pillar-content">
        <?= $contentHtml ?>
      </div>
      <?php endif; ?>

      <!-- ── Cluster Articles (In-Depth Articles) ── -->
      <?php if (!empty($clusters)): ?>
      <div style="margin-top:var(--space-12);padding-top:var(--space-8);border-top:2px solid var(--border-subtle);">
        <h2 style="font-size:var(--text-xl);margin-bottom:var(--space-2);">📖 Deep-Dive Articles</h2>
        <p style="font-size:var(--text-sm);color:var(--text-muted);margin-bottom:var(--space-6);">Continue learning with our in-depth articles on specific subtopics.</p>
        <div style="display:grid;gap:var(--space-4);">
          <?php foreach ($clusters as $ci => $cluster): ?>
          <a href="/learn/<?= e($pillar['slug']) ?>/<?= e($cluster['slug']) ?>" class="card card-interactive" style="text-decoration:none;padding:var(--space-5);display:flex;align-items:center;gap:var(--space-4);">
            <div style="width:44px;height:44px;border-radius:var(--radius-lg);background:linear-gradient(135deg,rgba(99,102,241,0.15),rgba(139,92,246,0.08));display:flex;align-items:center;justify-content:center;flex-shrink:0;">
              <span style="font-size:16px;font-weight:700;color:var(--brand-primary);"><?= $ci + 1 ?></span>
            </div>
            <div style="flex:1;">
              <div style="font-size:var(--text-sm);font-weight:var(--fw-semibold);color:var(--text-primary);margin-bottom:2px;"><?= e($cluster['title']) ?></div>
              <?php if (!empty($cluster['excerpt'])): ?>
              <div style="font-size:var(--text-xs);color:var(--text-muted);line-height:1.5;"><?= str_truncate($cluster['excerpt'], 120) ?></div>
              <?php endif; ?>
            </div>
            <div style="display:flex;flex-direction:column;align-items:flex-end;gap:4px;flex-shrink:0;">
              <?php if (!empty($cluster['read_time'])): ?>
              <span style="font-size:10px;color:var(--text-muted);"><?= $cluster['read_time'] ?> min</span>
              <?php endif; ?>
              <span style="color:var(--brand-400);font-size:var(--text-sm);">→</span>
            </div>
          </a>
          <?php endforeach; ?>
        </div>
      </div>
      <?php endif; ?>

      <!-- ── Author E-E-A-T Box ── -->
      <div style="margin-top:var(--space-12);padding:var(--space-7);background:var(--bg-surface);border:1px solid var(--border-subtle);border-radius:var(--radius-xl);" id="author-box">
        <div style="display:flex;gap:var(--space-5);align-items:flex-start;">
          <div style="width:64px;height:64px;border-radius:50%;background:linear-gradient(135deg,var(--brand-primary),var(--brand-secondary,#8b5cf6));display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <span style="font-size:28px;color:white;">✍️</span>
          </div>
          <div style="flex:1;">
            <p style="font-size:var(--text-xs);text-transform:uppercase;letter-spacing:0.06em;color:var(--text-muted);font-weight:600;margin-bottom:4px;">Written & Reviewed By</p>
            <h3 style="font-size:var(--text-lg);font-weight:var(--fw-bold);margin-bottom:var(--space-2);"><?= e($authorName) ?></h3>
            <p style="font-size:var(--text-sm);color:var(--text-secondary);line-height:1.6;margin-bottom:var(--space-3);"><?= e($authorBio) ?></p>
            <p style="font-size:var(--text-xs);color:var(--brand-400);font-weight:var(--fw-medium);"><?= e($authorCredentials) ?></p>
            <?php if ($publishDate): ?>
            <p style="font-size:var(--text-xs);color:var(--text-muted);margin-top:var(--space-2);">
              Originally published <?= $publishDate ?> · Last updated <?= $updateDate ?>
            </p>
            <?php endif; ?>
          </div>
        </div>
      </div>

      <!-- ── Related Pillars ── -->
      <?php if (!empty($relatedPillars)): ?>
      <div style="margin-top:var(--space-10);">
        <h2 style="font-size:var(--text-lg);margin-bottom:var(--space-5);">📚 Continue Learning</h2>
        <div class="grid grid-3 gap-4">
          <?php foreach ($relatedPillars as $rp): ?>
          <a href="/learn/<?= e($rp['slug']) ?>" class="card card-interactive" style="text-decoration:none;padding:var(--space-5);">
            <h3 style="font-size:var(--text-sm);font-weight:var(--fw-semibold);color:var(--text-primary);margin-bottom:4px;"><?= e($rp['title']) ?></h3>
            <span style="font-size:var(--text-xs);color:var(--brand-400);font-weight:600;">Read Guide →</span>
          </a>
          <?php endforeach; ?>
        </div>
      </div>
      <?php endif; ?>
    </article>

    <!-- ── Sticky Sidebar ── -->
    <aside style="position:sticky;top:90px;">

      <!-- TOC Sidebar -->
      <?php if (count($tocItems) >= 3): ?>
      <div class="card" style="padding:var(--space-5);margin-bottom:var(--space-4);">
        <h3 style="font-size:12px;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.06em;margin-bottom:var(--space-3);">📋 Chapters</h3>
        <nav style="display:flex;flex-direction:column;gap:2px;" id="sidebar-toc">
          <?php foreach ($tocItems as $i => $item): ?>
          <a href="#<?= e($item['id']) ?>" style="font-size:12px;color:var(--text-secondary);text-decoration:none;padding:5px 8px;border-radius:var(--radius-sm);border-left:2px solid transparent;transition:all 0.15s;line-height:1.4;" class="sidebar-toc-link"
             data-section="<?= e($item['id']) ?>">
            <?= e($item['text']) ?>
          </a>
          <?php endforeach; ?>
        </nav>
      </div>
      <?php endif; ?>

      <!-- Author Card -->
      <div class="card" style="padding:var(--space-5);margin-bottom:var(--space-4);">
        <div style="display:flex;align-items:center;gap:10px;margin-bottom:var(--space-3);">
          <div style="width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,var(--brand-primary),#8b5cf6);display:flex;align-items:center;justify-content:center;">
            <span style="font-size:16px;color:white;">✍️</span>
          </div>
          <div>
            <div style="font-size:var(--text-sm);font-weight:var(--fw-semibold);"><?= e($authorName) ?></div>
            <div style="font-size:10px;color:var(--text-muted);">Digital Marketing Expert</div>
          </div>
        </div>
        <p style="font-size:11px;color:var(--text-muted);line-height:1.5;"><?= e($authorCredentials) ?></p>
      </div>

      <!-- Quick Links -->
      <div class="card" style="padding:var(--space-5);">
        <h3 style="font-size:12px;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.06em;margin-bottom:var(--space-3);">🔗 Quick Links</h3>
        <nav style="display:flex;flex-direction:column;gap:var(--space-2);">
          <a href="/learn" style="font-size:var(--text-sm);color:var(--text-secondary);text-decoration:none;">← All Guides</a>
          <a href="/glossary" style="font-size:var(--text-sm);color:var(--text-secondary);text-decoration:none;">📖 Glossary</a>
          <a href="/tools" style="font-size:var(--text-sm);color:var(--text-secondary);text-decoration:none;">🛠️ Free Tools</a>
          <a href="/blog" style="font-size:var(--text-sm);color:var(--text-secondary);text-decoration:none;">📝 Latest Articles</a>
        </nav>
      </div>
    </aside>

  </div>
</div>

<!-- ── Sidebar TOC Active State JS ── -->
<script>
(function() {
  const links = document.querySelectorAll('.sidebar-toc-link');
  if (!links.length) return;

  const observer = new IntersectionObserver(entries => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        links.forEach(l => {
          l.style.borderLeftColor = 'transparent';
          l.style.color = 'var(--text-secondary)';
          l.style.fontWeight = '400';
        });
        const active = document.querySelector('.sidebar-toc-link[data-section="' + entry.target.id + '"]');
        if (active) {
          active.style.borderLeftColor = 'var(--brand-primary)';
          active.style.color = 'var(--brand-400)';
          active.style.fontWeight = '600';
        }
      }
    });
  }, { rootMargin: '-80px 0px -70% 0px' });

  document.querySelectorAll('#pillar-content h2[id]').forEach(h2 => observer.observe(h2));
})();
</script>
