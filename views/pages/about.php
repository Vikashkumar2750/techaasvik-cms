<!-- About Page -->
<div class="container" style="padding-top:var(--space-12);padding-bottom:var(--space-16);">

  <!-- Hero -->
  <div style="text-align:center;max-width:720px;margin:0 auto var(--space-14);">
    <span class="badge badge-brand" style="margin-bottom:var(--space-4);">🇮🇳 Made in India</span>
    <h1 style="margin-bottom:var(--space-5);">India's Digital Marketing Authority Platform</h1>
    <p style="font-size:var(--text-xl);color:var(--text-secondary);line-height:var(--leading-relaxed);">
      TechAasvik was built with a single mission: make world-class digital marketing knowledge accessible to every marketer, agency, and business in India — completely free.
    </p>
  </div>

  <!-- Mission Stats -->
  <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:20px;margin-bottom:var(--space-14);">
    <?php foreach([
      ['2,000+', 'Expert Guides Published', '#6366f1'],
      ['500+',   'Glossary Terms Defined',  '#34d399'],
      ['50+',    'Free Tools Available',    '#fbbf24'],
      ['10K+',   'Marketers Helped',        '#f472b6'],
    ] as [$num, $label, $color]): ?>
    <div style="text-align:center;padding:var(--space-6);background:var(--bg-surface);border:1px solid var(--border-subtle);border-radius:var(--radius-xl);">
      <div style="font-size:clamp(2rem,4vw,3rem);font-weight:var(--fw-black);color:<?= $color ?>;font-family:var(--font-display);"><?= $num ?></div>
      <div style="font-size:var(--text-sm);color:var(--text-muted);margin-top:4px;"><?= $label ?></div>
    </div>
    <?php endforeach; ?>
  </div>

  <!-- Story Section -->
  <div style="display:grid;grid-template-columns:1fr 1fr;gap:var(--space-12);align-items:center;margin-bottom:var(--space-14);">
    <div>
      <h2 style="margin-bottom:var(--space-5);">Our Story</h2>
      <p style="color:var(--text-secondary);margin-bottom:var(--space-4);line-height:var(--leading-relaxed);">
        TechAasvik started from a simple frustration: India's marketers were learning digital marketing from outdated blogs, expensive courses, and fragmented resources — none of which reflected the realities of the Indian market.
      </p>
      <p style="color:var(--text-secondary);margin-bottom:var(--space-4);line-height:var(--leading-relaxed);">
        We built the platform we wished existed. Every guide is written with India's unique digital landscape in mind — local SEO for Indian businesses, Google Ads strategies for Indian audiences, Meta Ads frameworks for Indian e-commerce.
      </p>
      <p style="color:var(--text-secondary);line-height:var(--leading-relaxed);">
        Today, TechAasvik is India's most comprehensive digital marketing knowledge platform — and we're just getting started.
      </p>
    </div>
    <div style="display:flex;flex-direction:column;gap:16px;">
      <?php foreach([
        ['🎯', 'Research-Backed', 'Every guide is backed by data, real campaigns, and expert experience.'],
        ['🔄', 'Always Updated',  'We update content as algorithms change, platforms evolve, and new strategies emerge.'],
        ['🇮🇳', 'India-First',    'Strategies, examples, and tools calibrated for the Indian market.'],
        ['💸', 'Always Free',    'We believe great marketing education should be accessible to everyone.'],
      ] as [$icon, $title, $desc]): ?>
      <div style="display:flex;gap:14px;align-items:flex-start;padding:var(--space-4);background:var(--bg-surface);border:1px solid var(--border-subtle);border-radius:var(--radius-lg);">
        <div style="font-size:20px;flex-shrink:0;"><?= $icon ?></div>
        <div>
          <h3 style="font-size:var(--text-sm);font-weight:var(--fw-semibold);color:var(--text-primary);margin-bottom:2px;"><?= $title ?></h3>
          <p style="font-size:var(--text-xs);color:var(--text-muted);margin:0;"><?= $desc ?></p>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>

  <!-- What We Cover -->
  <div style="margin-bottom:var(--space-14);">
    <h2 style="text-align:center;margin-bottom:var(--space-8);">What TechAasvik Covers</h2>
    <div class="grid grid-4 gap-4">
      <?php foreach([
        ['🔍','SEO'],['💬','AEO'],['🤖','GEO'],['📢','Google Ads'],
        ['📱','Meta Ads'],['✍️','Content Marketing'],['📊','Analytics'],['⚡','AI Marketing'],
        ['📧','Email Marketing'],['📍','Local SEO'],['🛒','E-commerce'],['🔗','Link Building'],
        ['🎯','CRO'],['📹','Video Marketing'],['💙','Social Media'],['📈','Data & Statistics'],
      ] as [$icon, $name]): ?>
      <div style="padding:var(--space-3) var(--space-4);background:var(--bg-surface);border:1px solid var(--border-subtle);border-radius:var(--radius-lg);display:flex;align-items:center;gap:10px;">
        <span><?= $icon ?></span>
        <span style="font-size:var(--text-sm);font-weight:var(--fw-medium);color:var(--text-secondary);"><?= $name ?></span>
      </div>
      <?php endforeach; ?>
    </div>
  </div>

  <!-- Contact CTA -->
  <div style="text-align:center;padding:var(--space-10);background:var(--bg-surface);border:1px solid var(--border-subtle);border-radius:var(--radius-2xl);">
    <h2 style="margin-bottom:var(--space-4);">Work With TechAasvik</h2>
    <p style="color:var(--text-secondary);margin-bottom:var(--space-6);">Partnerships, guest contributions, advertising, or just to say hi.</p>
    <div style="display:flex;justify-content:center;gap:12px;flex-wrap:wrap;">
      <a href="/contact" class="btn btn-primary">Get in Touch →</a>
      <a href="/authors" class="btn btn-secondary">Meet Our Authors</a>
      <a href="/editorial-policy" class="btn btn-secondary">Editorial Standards</a>
    </div>
  </div>

</div>
