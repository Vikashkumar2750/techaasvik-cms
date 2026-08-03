<!-- HTML Sitemap -->
<div class="container" style="padding-top:var(--space-10);padding-bottom:var(--space-16);">

  <?php \Core\View::partial('breadcrumb', ['crumbs' => [['name'=>'Home','url'=>'/'],['name'=>'Sitemap']]]) ?>

  <div style="margin-top:var(--space-4);margin-bottom:var(--space-10);">
    <h1 style="margin-bottom:var(--space-4);">Sitemap</h1>
    <p style="font-size:var(--text-lg);color:var(--text-secondary);max-width:640px;">
      A complete overview of all pages and content on TechAasvik. Find exactly what you're looking for.
    </p>
  </div>

  <!-- Static Pages -->
  <div style="margin-bottom:var(--space-10);">
    <h2 style="font-size:var(--text-xl);margin-bottom:var(--space-5);padding-bottom:var(--space-3);border-bottom:1px solid var(--border-subtle);">📄 Main Pages</h2>
    <div class="grid grid-3 gap-4">
      <?php foreach ([
        ['/', 'Home'],
        ['/about', 'About Us'],
        ['/contact', 'Contact'],
        ['/services', 'Services'],
        ['/blog', 'Blog'],
        ['/learn', 'Knowledge Center'],
        ['/glossary', 'Marketing Glossary'],
        ['/tools', 'Free Tools'],
        ['/calculators', 'Calculators'],
        ['/courses', 'Free Courses'],
        ['/case-studies', 'Case Studies'],
        ['/templates', 'Templates'],
        ['/statistics', 'Statistics'],
        ['/research', 'Research Reports'],
        ['/news', 'News'],
        ['/authors', 'Our Authors'],
        ['/privacy-policy', 'Privacy Policy'],
        ['/terms-of-service', 'Terms of Service'],
        ['/disclaimer', 'Disclaimer'],
      ] as [$url, $label]): ?>
      <a href="<?= $url ?>" style="font-size:var(--text-sm);color:var(--text-secondary);text-decoration:none;padding:var(--space-3);border-radius:var(--radius-md);transition:all 0.15s;" onmouseover="this.style.background='var(--bg-surface)';this.style.color='var(--brand-400)'" onmouseout="this.style.background='';this.style.color='var(--text-secondary)'"><?= $label ?></a>
      <?php endforeach; ?>
    </div>
  </div>

  <!-- Dynamic Content -->
  <?php if (!empty($allContent)): ?>
  <?php
    $grouped = [];
    foreach ($allContent as $item) {
      $type = $item['type'] ?? 'post';
      $grouped[$type][] = $item;
    }
    $typeLabels = [
      'post' => '📝 Blog Posts',
      'pillar' => '📚 Pillar Guides',
      'glossary_term' => '📖 Glossary Terms',
      'case_study' => '📊 Case Studies',
      'tool' => '⚙️ Tools',
      'calculator' => '🧮 Calculators',
      'template' => '📋 Templates',
      'course' => '🎓 Courses',
      'statistics' => '📈 Statistics',
      'research_report' => '🔬 Research',
      'news_article' => '📰 News',
      'video' => '🎬 Videos',
    ];
  ?>
  <?php foreach ($grouped as $type => $items): ?>
  <div style="margin-bottom:var(--space-10);">
    <h2 style="font-size:var(--text-xl);margin-bottom:var(--space-5);padding-bottom:var(--space-3);border-bottom:1px solid var(--border-subtle);">
      <?= $typeLabels[$type] ?? ucfirst(str_replace('_', ' ', $type)) ?> (<?= count($items) ?>)
    </h2>
    <div style="columns:3;column-gap:var(--space-6);">
      <?php foreach ($items as $item): ?>
      <a href="<?= e(content_url($item)) ?>" style="display:block;font-size:var(--text-sm);color:var(--text-secondary);text-decoration:none;padding:4px 0;break-inside:avoid;transition:color 0.15s;" onmouseover="this.style.color='var(--brand-400)'" onmouseout="this.style.color='var(--text-secondary)'"><?= e($item['title']) ?></a>
      <?php endforeach; ?>
    </div>
  </div>
  <?php endforeach; ?>
  <?php endif; ?>

</div>
