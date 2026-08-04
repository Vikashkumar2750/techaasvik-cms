<!-- Learn Index / Knowledge Center -->
<div class="container" style="padding-top:var(--space-10);padding-bottom:var(--space-16);">

  <?php \Core\View::partial('breadcrumb', ['crumbs' => [['name'=>'Home','url'=>'/'],['name'=>'Knowledge Center']]]) ?>

  <!-- Hero -->
  <div style="text-align:center;margin:var(--space-6) auto var(--space-12);max-width:720px;">
    <h1 style="margin-bottom:var(--space-4);">Digital Marketing Knowledge Center</h1>
    <p style="font-size:var(--text-xl);color:var(--text-secondary);line-height:var(--leading-relaxed);">
      India's most comprehensive digital marketing curriculum — from beginner to expert. Every guide is written by certified professionals, backed by research, and updated regularly.
    </p>
    <div style="display:flex;justify-content:center;gap:16px;margin-top:var(--space-6);flex-wrap:wrap;">
      <div><strong style="color:var(--text-primary);">2,000+</strong> <span style="color:var(--text-muted);font-size:var(--text-sm);">Expert Guides</span></div>
      <div>·</div>
      <div><strong style="color:var(--text-primary);">15</strong> <span style="color:var(--text-muted);font-size:var(--text-sm);">Topic Pillars</span></div>
      <div>·</div>
      <div><strong style="color:var(--text-primary);">Free</strong> <span style="color:var(--text-muted);font-size:var(--text-sm);">Forever</span></div>
    </div>
  </div>

  <!-- Pillar Page Grid -->
  <?php
  // Hardcoded pillar topics for visual richness — slugs MUST match DB
  $defaultPillars = [
    ['seo',                      '🔍', 'Complete SEO Guide 2026',        'Technical SEO, on-page, off-page, link building, Core Web Vitals, AI Overviews — everything from zero to hero.',  'beginner'],
    ['aeo-complete-guide',       '💬', 'Answer Engine Optimization (AEO)','Win featured snippets, People Also Ask, and AI answer boxes. The future of search visibility.',                   'intermediate'],
    ['geo-complete-guide',       '🤖', 'Generative Engine Optimization', 'Get cited by ChatGPT, Gemini, Claude, and Perplexity. The definitive GEO strategy guide for 2026.',                'advanced'],
    ['google-ads-complete-guide','📢', 'Google Ads Mastery Guide 2026',  'Search, Shopping, Display, YouTube, and Performance Max campaigns. From setup to scaling.',                         'intermediate'],
    ['meta-ads-complete-guide',  '📱', 'Meta Ads Complete Guide 2026',   'Facebook and Instagram advertising — audience research, creative strategy, campaign structure, and scaling.',        'intermediate'],
    ['content-marketing',        '✍️', 'Content Marketing Strategy 2026','Content strategy, creation frameworks, distribution channels, repurposing, and performance measurement.',            'beginner'],
    ['analytics',                '📊', 'Google Analytics 4 & GTM 2026', 'GA4 setup, reports, explorations, conversions, attribution modeling, and Looker Studio dashboards.',                  'intermediate'],
    ['ai-marketing',             '⚡', 'AI Marketing & Automation 2026', 'Prompt engineering for marketing, AI tools, ChatGPT SEO, content automation, and AI ad creative.',                   'advanced'],
    ['email-marketing',          '📧', 'Email Marketing Mastery 2026',   'List building, segmentation, automation sequences, deliverability, and revenue-driven campaigns.',                   'beginner'],
    ['local-seo',                '📍', 'Local SEO Guide 2026',           'Google Business Profile, local citations, review strategy, and local link building for Indian businesses.',           'beginner'],
    ['ecommerce-marketing',      '🛒', 'E-commerce Marketing 2026',     'Google Shopping, Meta Catalog, conversion rate optimization, retention marketing, and ROAS scaling.',                 'intermediate'],
    ['social-media-marketing',   '💙', 'Social Media Marketing 2026',   'Platform strategy for Instagram, YouTube, LinkedIn, and Twitter/X. Content calendars, growth, and analytics.',        'beginner'],
    ['cro-guide',                '🎯', 'Conversion Rate Optimization',  'CRO frameworks, A/B testing, landing page optimization, heatmaps, and user behavior analysis.',                      'intermediate'],
    ['link-building',            '🔗', 'Link Building & Digital PR',    'White-hat link acquisition, outreach templates, HARO, digital PR campaigns, and authority building.',                 'advanced'],
    ['video-marketing',          '🎬', 'Video Marketing & YouTube SEO', 'YouTube channel strategy, video SEO, shorts strategy, YouTube Ads, and video content repurposing.',                   'beginner'],
  ];

  $pillarData = !empty($pillars) ? $pillars : [];
  $pillarsToShow = $defaultPillars;
  ?>

  <div class="grid grid-3 gap-6">
    <?php foreach ($pillarsToShow as [$slug, $icon, $title, $desc, $level]): ?>
    <?php
    // Check if DB has this pillar
    $dbPillar = null;
    foreach ($pillarData as $p) {
        if ($p['slug'] === $slug) { $dbPillar = $p; break; }
    }
    $url     = '/learn/' . $slug;
    $levelColors = ['beginner' => 'var(--accent-400)', 'intermediate' => '#fbbf24', 'advanced' => '#f87171'];
    $levelColor  = $levelColors[$level] ?? 'var(--text-muted)';
    ?>
    <a href="<?= $url ?>" class="card card-interactive" style="text-decoration:none;display:flex;flex-direction:column;" id="pillar-<?= $slug ?>">
      <div style="display:flex;align-items:center;gap:12px;margin-bottom:14px;">
        <div style="width:52px;height:52px;border-radius:14px;background:linear-gradient(135deg,rgba(99,102,241,0.2),rgba(139,92,246,0.1));display:flex;align-items:center;justify-content:center;font-size:24px;flex-shrink:0;">
          <?= $icon ?>
        </div>
        <div>
          <span style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.06em;color:<?= $levelColor ?>;"><?= ucfirst($level) ?></span>
          <h2 style="font-size:var(--text-base);font-weight:var(--fw-semibold);color:var(--text-primary);line-height:1.3;margin:2px 0 0;"><?= $title ?></h2>
        </div>
      </div>
      <p style="font-size:var(--text-sm);color:var(--text-secondary);flex:1;line-height:1.55;"><?= $desc ?></p>
      <div style="margin-top:14px;padding-top:12px;border-top:1px solid var(--border-subtle);display:flex;align-items:center;justify-content:space-between;">
        <?php if ($dbPillar): ?>
        <span style="font-size:var(--text-xs);color:var(--text-muted);"><?= $dbPillar['read_time'] ?? '30' ?> min read</span>
        <?php else: ?>
        <span style="font-size:var(--text-xs);color:var(--accent-400);">Deep-Dive Guide</span>
        <?php endif; ?>
        <span style="font-size:var(--text-xs);color:var(--brand-400);font-weight:600;">Start Learning →</span>
      </div>
    </a>
    <?php endforeach; ?>
  </div>

  <!-- Newsletter CTA -->
  <div class="newsletter-box" style="margin-top:var(--space-16);">
    <h2 style="font-size:var(--text-2xl);margin-bottom:var(--space-3);">📚 Get Our Free Digital Marketing Curriculum</h2>
    <p>Subscribe and receive our complete digital marketing learning path — curated guides, study plan, and weekly tips.</p>
    <form class="newsletter-form" id="learnNewsletter" novalidate>
      <input type="email" name="email" placeholder="your@email.com" class="form-input" required id="learnEmail" aria-label="Email address">
      <button type="submit" class="btn btn-primary">Get Free Curriculum →</button>
    </form>
  </div>

</div>

<script>
// Hook newsletter
(function(){
  const form  = document.getElementById('learnNewsletter');
  const email = document.getElementById('learnEmail');
  if (!form) return;
  form.addEventListener('submit', async e => {
    e.preventDefault();
    const btn = form.querySelector('button');
    btn.disabled = true; btn.textContent = 'Sending…';
    try {
      const res  = await fetch('/lead/newsletter', { method:'POST', headers:{'Content-Type':'application/x-www-form-urlencoded','X-Requested-With':'XMLHttpRequest'}, body:'email='+encodeURIComponent(email.value)+'&source=learn-index' });
      const data = await res.json();
      form.innerHTML = '<p style="color:#4ade80;font-weight:600;text-align:center;">✅ '+data.message+'</p>';
    } catch { btn.disabled=false; btn.textContent='Get Free Curriculum →'; }
  });
})();
</script>
