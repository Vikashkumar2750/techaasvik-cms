<!-- ═══════════════════════════════════════════════════
     SERVICES PAGE — 2026 Edition
     Premium redesign with hero, categories & CTA
════════════════════════════════════════════════════ -->

<!-- ── Hero ──────────────────────────────────────────────── -->
<section class="svc-hero" aria-label="Services hero">
  <div class="container">

    <div class="svc-hero-badge">
      <span class="svc-hero-badge-dot"></span>
      2026 & Beyond — AI-Native Digital Marketing
    </div>

    <h1 class="svc-hero-title">
      Digital Marketing Services<br>
      <span class="gradient-text">That Actually Deliver Results</span>
    </h1>

    <p class="svc-hero-sub">
      From foundational SEO to cutting-edge GEO & AI Marketing — we offer
      every service your brand needs to dominate search, social, and AI-powered
      discovery in 2026 and beyond.
    </p>

    <div class="svc-hero-actions">
      <a href="/free-audit" class="btn btn-gradient btn-lg" id="servicesHeroCta">
        Get Free Audit ↗
      </a>
      <a href="#all-services" class="btn btn-secondary btn-lg">
        Explore Services ↓
      </a>
    </div>

    <!-- Stats Bar -->
    <div class="svc-stats" role="list">
      <div class="svc-stat" role="listitem">
        <span class="svc-stat-value">500+</span>
        <span class="svc-stat-label">Businesses Helped</span>
      </div>
      <div class="svc-stat-divider" aria-hidden="true"></div>
      <div class="svc-stat" role="listitem">
        <span class="svc-stat-value">12</span>
        <span class="svc-stat-label">Core Services</span>
      </div>
      <div class="svc-stat-divider" aria-hidden="true"></div>
      <div class="svc-stat" role="listitem">
        <span class="svc-stat-value">4.9★</span>
        <span class="svc-stat-label">Client Rating</span>
      </div>
      <div class="svc-stat-divider" aria-hidden="true"></div>
      <div class="svc-stat" role="listitem">
        <span class="svc-stat-value">3× ROI</span>
        <span class="svc-stat-label">Average Return</span>
      </div>
    </div>

  </div>
</section>

<!-- ── All Services ───────────────────────────────────────── -->
<section class="container svc-section" id="all-services">

  <?php
  \Core\View::partial('breadcrumb', ['crumbs' => [
    ['name' => 'Home', 'url' => '/'],
    ['name' => 'Services'],
  ]]);
  ?>

  <!-- ── Category 1: Proven Foundations ─────────────────── -->
  <div class="svc-category-header">
    <div class="svc-category-label">Proven Foundations</div>
    <h2 class="svc-category-title">Core Digital Marketing Services</h2>
    <p class="svc-category-desc">
      Battle-tested strategies that drive predictable, measurable growth across all major digital channels.
    </p>
  </div>

  <div class="svc-grid" id="core-services">
    <?php
    $coreServices = [
      [
        'slug'    => 'seo',
        'icon'    => '<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6"><circle cx="11" cy="11" r="8"/><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35"/></svg>',
        'color'   => '#6366f1',
        'badge'   => 'Most Popular',
        'title'   => 'SEO Services',
        'tagline' => 'Rank Higher. Convert More.',
        'desc'    => 'Data-driven SEO that goes beyond rankings. Technical SEO, Core Web Vitals, AI-optimized content, E-E-A-T signals, and authoritative link building to dominate Google in 2026.',
        'features'=> ['Technical SEO Audit', 'Core Web Vitals', 'E-E-A-T Optimization', 'Link Building'],
        'learn'   => '/learn/seo',
      ],
      [
        'slug'    => 'google-ads',
        'icon'    => '<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>',
        'color'   => '#f59e0b',
        'badge'   => 'High ROI',
        'title'   => 'Google Ads (PPC)',
        'tagline' => 'Every Rupee Counts.',
        'desc'    => 'Performance Max, Smart Shopping, Demand Gen & YouTube campaigns managed by certified experts. AI-powered bid strategies that maximize ROAS across every Google surface.',
        'features'=> ['Search & Shopping', 'Performance Max', 'YouTube Ads', 'Smart Bidding'],
        'learn'   => '/learn/google-ads-complete-guide',
      ],
      [
        'slug'    => 'meta-ads',
        'icon'    => '<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6"><rect x="2" y="2" width="20" height="20" rx="5"/><path stroke-linecap="round" stroke-linejoin="round" d="M8 11.857C8 9.724 9.343 8 11 8s3 1.724 3 3.857v4.286M11 12v4.143"/></svg>',
        'color'   => '#3b82f6',
        'badge'   => 'Top Converting',
        'title'   => 'Meta Ads',
        'tagline' => 'Find Your Ideal Customer.',
        'desc'    => 'Hyper-targeted Facebook & Instagram campaigns using Advantage+ AI, Lookalike Audiences, and Reels Ads. Full-funnel strategy from awareness to conversion.',
        'features'=> ['Facebook Ads', 'Instagram Reels', 'Advantage+ AI', 'Retargeting'],
        'learn'   => '/learn/meta-ads-complete-guide',
      ],
      [
        'slug'    => 'social-media',
        'icon'    => '<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>',
        'color'   => '#ec4899',
        'badge'   => '',
        'title'   => 'Social Media Marketing',
        'tagline' => 'Build a Loyal Community.',
        'desc'    => 'Organic social strategy across LinkedIn, Instagram, YouTube, and X. Content calendars, community management, influencer partnerships, and short-form video creation.',
        'features'=> ['LinkedIn Strategy', 'Instagram Growth', 'Short-form Video', 'Community Building'],
        'learn'   => '/learn/social-media-marketing',
      ],
      [
        'slug'    => 'content-marketing',
        'icon'    => '<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>',
        'color'   => '#10b981',
        'badge'   => '',
        'title'   => 'Content Marketing',
        'tagline' => 'Content That Converts.',
        'desc'    => 'Strategic content that ranks, answers, and converts. Pillar pages, cluster articles, infographics, and multimedia content built around your audience\'s search intent.',
        'features'=> ['Pillar Pages', 'Cluster Content', 'Infographics', 'Video Scripts'],
        'learn'   => '/learn/content-marketing',
      ],
      [
        'slug'    => 'analytics',
        'icon'    => '<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>',
        'color'   => '#8b5cf6',
        'badge'   => '',
        'title'   => 'Analytics & Reporting',
        'tagline' => 'Data-Driven Decisions.',
        'desc'    => 'GA4 setup, GTM configuration, custom dashboards, attribution modelling, and monthly performance reports that show the metrics that actually matter for your business.',
        'features'=> ['GA4 Setup', 'GTM Configuration', 'Custom Dashboards', 'Attribution'],
        'learn'   => '/learn/analytics',
      ],
    ];
    foreach ($coreServices as $svc):
    ?>
    <article class="svc-card" id="service-<?= $svc['slug'] ?>">
      <div class="svc-card-header">
        <div class="svc-card-icon" style="background:<?= $svc['color'] ?>20;color:<?= $svc['color'] ?>;">
          <?= $svc['icon'] ?>
        </div>
        <?php if ($svc['badge']): ?>
        <span class="svc-card-badge"><?= $svc['badge'] ?></span>
        <?php endif; ?>
      </div>
      <div class="svc-card-tagline"><?= $svc['tagline'] ?></div>
      <h3 class="svc-card-title">
        <a href="/services/<?= $svc['slug'] ?>"><?= $svc['title'] ?></a>
      </h3>
      <p class="svc-card-desc"><?= $svc['desc'] ?></p>
      <ul class="svc-card-features" aria-label="Key features">
        <?php foreach ($svc['features'] as $feat): ?>
        <li>
          <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
          <?= $feat ?>
        </li>
        <?php endforeach; ?>
      </ul>
      <div class="svc-card-footer">
        <a href="/services/<?= $svc['slug'] ?>" class="svc-card-cta">Explore Service →</a>
        <?php if ($svc['learn']): ?>
        <a href="<?= $svc['learn'] ?>" class="svc-card-learn">Free Guide ↗</a>
        <?php endif; ?>
      </div>
    </article>
    <?php endforeach; ?>
  </div>

  <!-- ── Divider ──────────────────────────────────────────── -->
  <div class="svc-category-divider" aria-hidden="true">
    <div class="svc-category-divider-line"></div>
    <div class="svc-category-divider-badge">
      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
      New in 2026
    </div>
    <div class="svc-category-divider-line"></div>
  </div>

  <!-- ── Category 2: 2026 Emerging Services ─────────────── -->
  <div class="svc-category-header">
    <div class="svc-category-label svc-category-label--new">🚀 2026 Emerging</div>
    <h2 class="svc-category-title">Next-Gen Marketing for the AI Era</h2>
    <p class="svc-category-desc">
      Future-proof your brand with AI-native strategies that dominate the new landscape of search, discovery, and digital engagement.
    </p>
  </div>

  <div class="svc-grid svc-grid--emerging" id="emerging-services">
    <?php
    $emergingServices = [
      [
        'slug'    => 'geo',
        'icon'    => '<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>',
        'color'   => '#6366f1',
        'isNew'   => true,
        'badge'   => '🔥 Hottest in 2026',
        'title'   => 'GEO — Generative Engine Optimization',
        'tagline' => 'Rank in AI Answers.',
        'desc'    => 'Optimize your brand to appear in ChatGPT, Perplexity, Gemini, and AI Overviews. GEO is the new SEO — get cited by AI systems when your audience asks questions about your industry.',
        'features'=> ['AI Overview Visibility', 'ChatGPT Citations', 'Perplexity Optimization', 'LLM Brand Mentions'],
        'learn'   => '/learn/geo-complete-guide',
      ],
      [
        'slug'    => 'aeo',
        'icon'    => '<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6"><path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 01-2.555-.337A5.972 5.972 0 015.41 20.97a5.969 5.969 0 01-.474-.065 4.48 4.48 0 00.978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25z"/></svg>',
        'color'   => '#a78bfa',
        'isNew'   => true,
        'badge'   => 'AI-First Strategy',
        'title'   => 'AEO — Answer Engine Optimization',
        'tagline' => 'Win Featured Snippets & Voice.',
        'desc'    => 'Structured to answer. Optimized for voice search, featured snippets, People Also Ask, and AI chatbot responses. Make your content the definitive answer for every question in your niche.',
        'features'=> ['Featured Snippets', 'Voice Search', 'FAQ Schema', 'Conversational SEO'],
        'learn'   => '/learn/aeo-complete-guide',
      ],
      [
        'slug'    => 'ai-marketing',
        'icon'    => '<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6"><path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>',
        'color'   => '#f59e0b',
        'isNew'   => true,
        'badge'   => 'Future of Marketing',
        'title'   => 'AI Marketing & Automation',
        'tagline' => 'Work Smarter, Scale Faster.',
        'desc'    => 'Leverage AI for hyper-personalized campaigns, predictive audience targeting, automated content creation workflows, and intelligent chatbot funnels that convert 24/7.',
        'features'=> ['AI Personalization', 'Predictive Analytics', 'Chatbot Funnels', 'Automated Workflows'],
        'learn'   => '/learn/ai-marketing',
      ],
      [
        'slug'    => 'video-marketing',
        'icon'    => '<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.276A1 1 0 0121 8.723v6.554a1 1 0 01-1.447.894L15 14M3 8a2 2 0 012-2h8a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z"/></svg>',
        'color'   => '#ef4444',
        'isNew'   => true,
        'badge'   => 'Highest Engagement',
        'title'   => 'Video & Reels Marketing',
        'tagline' => 'Short-form. Long-impact.',
        'desc'    => 'YouTube SEO, Instagram Reels, LinkedIn Videos, and YouTube Shorts strategy. From scripting and production to optimization — get the massive engagement that video delivers in 2026.',
        'features'=> ['YouTube SEO', 'Reels Strategy', 'Video Production', 'Shorts Optimization'],
        'learn'   => '/contact',
      ],
      [
        'slug'    => 'cro',
        'icon'    => '<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6"><path stroke-linecap="round" stroke-linejoin="round" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>',
        'color'   => '#10b981',
        'isNew'   => true,
        'badge'   => 'Max Your Revenue',
        'title'   => 'CRO — Conversion Rate Optimization',
        'tagline' => 'Turn Visitors into Buyers.',
        'desc'    => 'Scientific A/B testing, heatmap analysis, user session recordings, landing page optimization, and psychological copywriting that turns your existing traffic into more revenue.',
        'features'=> ['A/B Testing', 'Heatmap Analysis', 'Landing Pages', 'UX Audits'],
        'learn'   => '/contact',
      ],
      [
        'slug'    => 'programmatic',
        'icon'    => '<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6"><path stroke-linecap="round" stroke-linejoin="round" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"/></svg>',
        'color'   => '#06b6d4',
        'isNew'   => true,
        'badge'   => 'Advanced',
        'title'   => 'Programmatic & Display Advertising',
        'tagline' => 'Reach Everywhere Digitally.',
        'desc'    => 'Automated real-time bidding on premium ad inventory across 50,000+ publisher websites. Advanced audience targeting using first-party data, contextual signals, and AI-powered optimization.',
        'features'=> ['Real-time Bidding', 'DMP Integration', 'Contextual Targeting', 'Native Ads'],
        'learn'   => '/contact',
      ],
    ];
    foreach ($emergingServices as $svc):
    ?>
    <article class="svc-card svc-card--emerging" id="service-<?= $svc['slug'] ?>">
      <div class="svc-card-header">
        <div class="svc-card-icon svc-card-icon--glow" style="background:<?= $svc['color'] ?>20;color:<?= $svc['color'] ?>;--icon-glow:<?= $svc['color'] ?>;">
          <?= $svc['icon'] ?>
        </div>
        <?php if ($svc['badge']): ?>
        <span class="svc-card-badge svc-card-badge--new"><?= $svc['badge'] ?></span>
        <?php endif; ?>
      </div>
      <div class="svc-card-tagline"><?= $svc['tagline'] ?></div>
      <h3 class="svc-card-title">
        <a href="/services/<?= $svc['slug'] ?>"><?= $svc['title'] ?></a>
      </h3>
      <p class="svc-card-desc"><?= $svc['desc'] ?></p>
      <ul class="svc-card-features" aria-label="Key features">
        <?php foreach ($svc['features'] as $feat): ?>
        <li>
          <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
          <?= $feat ?>
        </li>
        <?php endforeach; ?>
      </ul>
      <div class="svc-card-footer">
        <a href="/services/<?= $svc['slug'] ?>" class="svc-card-cta">Explore Service →</a>
        <?php if (!empty($svc['learn']) && $svc['learn'] !== '/contact'): ?>
        <a href="<?= $svc['learn'] ?>" class="svc-card-learn">Free Guide ↗</a>
        <?php endif; ?>
      </div>
    </article>
    <?php endforeach; ?>
  </div>

</section>

<!-- ── Why TechAasvik ─────────────────────────────────────── -->
<section class="svc-why" aria-labelledby="why-heading">
  <div class="container">
    <div class="svc-why-inner">
      <div class="svc-why-text">
        <div class="svc-category-label">Our Approach</div>
        <h2 id="why-heading">Why Choose TechAasvik for Your Digital Marketing?</h2>
        <p>We're not just another agency. We're India's most authoritative digital marketing platform, combining research-backed strategies with hands-on execution to deliver real, measurable results.</p>

        <ul class="svc-why-list" role="list">
          <li>
            <span class="svc-why-icon">🎯</span>
            <div>
              <strong>Strategy-First Approach</strong>
              <p>Every campaign starts with deep research, audience analysis, and a custom roadmap — not a one-size-fits-all template.</p>
            </div>
          </li>
          <li>
            <span class="svc-why-icon">🤖</span>
            <div>
              <strong>AI-Powered Execution</strong>
              <p>We leverage the latest AI tools for content, analytics, and targeting — so your campaigns move faster and cost less.</p>
            </div>
          </li>
          <li>
            <span class="svc-why-icon">📊</span>
            <div>
              <strong>Radical Transparency</strong>
              <p>Monthly reports with real numbers. No vanity metrics. You always know exactly where your budget is going and what it's producing.</p>
            </div>
          </li>
          <li>
            <span class="svc-why-icon">🇮🇳</span>
            <div>
              <strong>Made in India, Globally Effective</strong>
              <p>We understand the Indian digital landscape deeply — regional audiences, vernacular content, platform nuances — while executing to global standards.</p>
            </div>
          </li>
        </ul>
      </div>

      <div class="svc-why-cards" aria-label="Service highlights">
        <div class="svc-why-card svc-why-card--glow">
          <div class="svc-why-card-icon">⚡</div>
          <div class="svc-why-card-num">2–4 weeks</div>
          <div class="svc-why-card-label">Average onboarding time</div>
        </div>
        <div class="svc-why-card">
          <div class="svc-why-card-icon">📈</div>
          <div class="svc-why-card-num">90-day</div>
          <div class="svc-why-card-label">Results guarantee window</div>
        </div>
        <div class="svc-why-card">
          <div class="svc-why-card-icon">🔒</div>
          <div class="svc-why-card-num">No Lock-in</div>
          <div class="svc-why-card-label">Month-to-month contracts</div>
        </div>
        <div class="svc-why-card svc-why-card--glow">
          <div class="svc-why-card-icon">🏆</div>
          <div class="svc-why-card-num">Top 1%</div>
          <div class="svc-why-card-label">Google Partner expertise</div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ── CTA ────────────────────────────────────────────────── -->
<section class="svc-cta" aria-labelledby="cta-heading">
  <div class="container">
    <div class="svc-cta-inner">
      <div class="svc-cta-glow" aria-hidden="true"></div>
      <div class="svc-cta-content">
        <div class="svc-hero-badge" style="margin:0 auto var(--space-6);">
          <span class="svc-hero-badge-dot"></span>
          Limited slots available this month
        </div>
        <h2 id="cta-heading">Ready to Transform Your Digital Presence?</h2>
        <p>Get a free, no-commitment digital marketing audit. Our experts will analyse your current setup and show you exactly where you're leaving money on the table.</p>
        <div class="svc-cta-actions">
          <a href="/free-audit" class="btn btn-gradient btn-lg" id="servicesCta">
            Get Your Free Audit →
          </a>
          <a href="/contact" class="btn btn-secondary btn-lg">
            Talk to an Expert
          </a>
        </div>

      </div>
    </div>
  </div>
</section>

<!-- ── Services Page Styles ───────────────────────────────── -->
<style>
/* ── Hero ────────────────────────────────────────────────── */
.svc-hero {
  position: relative;
  padding: var(--space-20) 0 var(--space-12);
  text-align: center;
  overflow: hidden;
}
.svc-hero::before {
  content: '';
  position: absolute;
  top: -150px;
  left: 50%;
  transform: translateX(-50%);
  width: 900px;
  height: 700px;
  background: radial-gradient(ellipse, rgba(99,102,241,0.14) 0%, transparent 65%);
  pointer-events: none;
  z-index: 0;
}
.svc-hero .container { position: relative; z-index: 1; }

.svc-hero-badge {
  display: inline-flex;
  align-items: center;
  gap: var(--space-2);
  background: rgba(99,102,241,0.12);
  border: 1px solid rgba(99,102,241,0.28);
  border-radius: var(--radius-full);
  padding: var(--space-2) var(--space-5);
  font-size: var(--text-xs);
  font-weight: var(--fw-semibold);
  color: var(--brand-300);
  letter-spacing: 0.02em;
  margin-bottom: var(--space-6);
}
.svc-hero-badge-dot {
  width: 7px;
  height: 7px;
  border-radius: 50%;
  background: var(--brand-400);
  animation: pulse 2s infinite;
  flex-shrink: 0;
}

.svc-hero-title {
  font-size: clamp(2.2rem, 5.5vw, 4rem);
  font-family: var(--font-display);
  font-weight: var(--fw-extrabold);
  letter-spacing: -0.03em;
  line-height: 1.1;
  color: var(--text-primary);
  margin-bottom: var(--space-6);
}
.svc-hero-sub {
  font-size: clamp(var(--text-base), 2vw, var(--text-xl));
  color: var(--text-secondary);
  max-width: 680px;
  margin: 0 auto var(--space-8);
  line-height: var(--leading-relaxed);
}
.svc-hero-actions {
  display: flex;
  justify-content: center;
  flex-wrap: wrap;
  gap: var(--space-4);
  margin-bottom: var(--space-16);
}

/* Stats Bar */
.svc-stats {
  display: flex;
  align-items: center;
  justify-content: center;
  flex-wrap: wrap;
  gap: var(--space-6);
  padding: var(--space-8) var(--space-10);
  background: var(--bg-surface);
  border: 1px solid var(--border-subtle);
  border-radius: var(--radius-2xl);
  max-width: 700px;
  margin: 0 auto;
}
.svc-stat { text-align: center; }
.svc-stat-value {
  display: block;
  font-family: var(--font-display);
  font-size: var(--text-2xl);
  font-weight: var(--fw-black);
  background: linear-gradient(135deg, var(--text-primary), var(--brand-400));
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
  line-height: 1.2;
}
.svc-stat-label {
  font-size: var(--text-xs);
  color: var(--text-muted);
  margin-top: 2px;
}
.svc-stat-divider {
  width: 1px;
  height: 36px;
  background: var(--border-default);
  flex-shrink: 0;
}

/* ── Section ─────────────────────────────────────────────── */
.svc-section {
  padding-top: var(--space-16);
  padding-bottom: var(--space-8);
}

/* ── Category Header ─────────────────────────────────────── */
.svc-category-header {
  text-align: center;
  margin-bottom: var(--space-10);
}
.svc-category-label {
  display: inline-flex;
  align-items: center;
  gap: var(--space-2);
  font-size: var(--text-xs);
  font-weight: var(--fw-semibold);
  text-transform: uppercase;
  letter-spacing: 0.1em;
  color: var(--brand-400);
  background: rgba(99,102,241,0.08);
  border: 1px solid rgba(99,102,241,0.2);
  border-radius: var(--radius-full);
  padding: 3px var(--space-4);
  margin-bottom: var(--space-4);
}
.svc-category-label--new {
  color: var(--accent-400);
  background: rgba(16,185,129,0.08);
  border-color: rgba(16,185,129,0.2);
}
.svc-category-title {
  font-size: clamp(var(--text-2xl), 4vw, var(--text-4xl));
  font-weight: var(--fw-bold);
  color: var(--text-primary);
  margin-bottom: var(--space-4);
}
.svc-category-desc {
  color: var(--text-secondary);
  font-size: var(--text-lg);
  max-width: 600px;
  margin: 0 auto;
  line-height: var(--leading-relaxed);
}

/* Divider */
.svc-category-divider {
  display: flex;
  align-items: center;
  gap: var(--space-6);
  margin: var(--space-16) 0;
}
.svc-category-divider-line { flex: 1; height: 1px; background: var(--border-subtle); }
.svc-category-divider-badge {
  display: inline-flex;
  align-items: center;
  gap: var(--space-2);
  font-size: var(--text-xs);
  font-weight: var(--fw-semibold);
  text-transform: uppercase;
  letter-spacing: 0.08em;
  color: var(--accent-400);
  white-space: nowrap;
  border: 1px solid rgba(16,185,129,0.25);
  background: rgba(16,185,129,0.08);
  padding: var(--space-2) var(--space-4);
  border-radius: var(--radius-full);
}
.svc-category-divider-badge svg { color: var(--accent-400); }

/* ── Service Grid ────────────────────────────────────────── */
.svc-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: var(--space-6);
  margin-bottom: var(--space-6);
}

/* ── Service Card ────────────────────────────────────────── */
.svc-card {
  display: flex;
  flex-direction: column;
  background: var(--bg-surface);
  border: 1px solid var(--border-subtle);
  border-radius: var(--radius-xl);
  padding: var(--space-6);
  transition: all 0.3s cubic-bezier(0.4,0,0.2,1);
  position: relative;
  overflow: hidden;
}
.svc-card::before {
  content: '';
  position: absolute;
  inset: 0;
  background: radial-gradient(circle at top right, rgba(99,102,241,0.04), transparent 60%);
  pointer-events: none;
  opacity: 0;
  transition: opacity 0.3s ease;
}
.svc-card:hover {
  border-color: var(--border-brand);
  transform: translateY(-4px);
  box-shadow: 0 16px 48px rgba(0,0,0,0.35), 0 0 0 1px rgba(99,102,241,0.15);
}
.svc-card:hover::before { opacity: 1; }

.svc-card--emerging {
  background: linear-gradient(135deg, var(--bg-surface), rgba(99,102,241,0.03));
}
.svc-card--emerging::after {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 2px;
  background: linear-gradient(90deg, var(--brand-500), #a78bfa, var(--accent-500));
  opacity: 0;
  transition: opacity 0.3s ease;
}
.svc-card--emerging:hover::after { opacity: 1; }

.svc-card-header {
  display: flex;
  align-items: flex-start;
  justify-content: space-between;
  gap: var(--space-3);
  margin-bottom: var(--space-4);
}
.svc-card-icon {
  width: 52px;
  height: 52px;
  border-radius: var(--radius-lg);
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
  transition: box-shadow 0.3s ease;
}
.svc-card-icon--glow:hover,
.svc-card:hover .svc-card-icon--glow {
  box-shadow: 0 0 20px var(--icon-glow, rgba(99,102,241,0.4));
}
.svc-card-badge {
  font-size: 11px;
  font-weight: var(--fw-semibold);
  padding: 3px 10px;
  border-radius: var(--radius-full);
  background: rgba(99,102,241,0.12);
  color: var(--brand-300);
  border: 1px solid rgba(99,102,241,0.2);
  white-space: nowrap;
  flex-shrink: 0;
}
.svc-card-badge--new {
  background: rgba(16,185,129,0.12);
  color: var(--accent-400);
  border-color: rgba(16,185,129,0.25);
}

.svc-card-tagline {
  font-size: var(--text-xs);
  font-weight: var(--fw-semibold);
  text-transform: uppercase;
  letter-spacing: 0.07em;
  color: var(--text-muted);
  margin-bottom: var(--space-2);
}
.svc-card-title {
  font-size: var(--text-xl);
  font-weight: var(--fw-bold);
  margin-bottom: var(--space-3);
  line-height: 1.3;
}
.svc-card-title a {
  color: var(--text-primary);
  text-decoration: none;
  transition: color 0.2s ease;
}
.svc-card:hover .svc-card-title a { color: var(--brand-400); }

.svc-card-desc {
  font-size: var(--text-sm);
  color: var(--text-secondary);
  line-height: var(--leading-relaxed);
  flex: 1;
  margin-bottom: var(--space-4);
}

.svc-card-features {
  list-style: none;
  padding: 0;
  margin: 0 0 var(--space-5);
  display: flex;
  flex-direction: column;
  gap: var(--space-2);
}
.svc-card-features li {
  display: flex;
  align-items: center;
  gap: var(--space-2);
  font-size: var(--text-xs);
  color: var(--text-secondary);
  font-weight: var(--fw-medium);
}
.svc-card-features svg { color: var(--accent-400); flex-shrink: 0; }

.svc-card-footer {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: var(--space-3);
  padding-top: var(--space-4);
  border-top: 1px solid var(--border-subtle);
  margin-top: auto;
}
.svc-card-cta {
  font-size: var(--text-sm);
  font-weight: var(--fw-semibold);
  color: var(--brand-400);
  text-decoration: none;
  transition: color 0.2s ease;
}
.svc-card-cta:hover { color: var(--brand-300); }
.svc-card-learn {
  font-size: var(--text-xs);
  color: var(--text-muted);
  text-decoration: none;
  transition: color 0.2s ease;
}
.svc-card-learn:hover { color: var(--text-secondary); }

/* ── Why Section ─────────────────────────────────────────── */
.svc-why {
  background: var(--bg-surface);
  border-top: 1px solid var(--border-subtle);
  border-bottom: 1px solid var(--border-subtle);
  padding: var(--space-20) 0;
  margin: var(--space-12) 0;
}
.svc-why-inner {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: var(--space-16);
  align-items: center;
}
.svc-why-text h2 {
  font-size: clamp(var(--text-2xl), 3.5vw, var(--text-4xl));
  margin-bottom: var(--space-4);
}
.svc-why-text > p {
  color: var(--text-secondary);
  margin-bottom: var(--space-8);
  line-height: var(--leading-relaxed);
}
.svc-why-list {
  list-style: none;
  padding: 0;
  margin: 0;
  display: flex;
  flex-direction: column;
  gap: var(--space-6);
}
.svc-why-list li {
  display: flex;
  gap: var(--space-4);
  align-items: flex-start;
}
.svc-why-icon {
  font-size: 24px;
  line-height: 1;
  flex-shrink: 0;
  margin-top: 2px;
}
.svc-why-list strong {
  display: block;
  font-size: var(--text-base);
  font-weight: var(--fw-semibold);
  color: var(--text-primary);
  margin-bottom: var(--space-1);
}
.svc-why-list p {
  font-size: var(--text-sm);
  color: var(--text-secondary);
  margin: 0;
  line-height: var(--leading-relaxed);
}

.svc-why-cards {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: var(--space-4);
}
.svc-why-card {
  background: var(--bg-elevated);
  border: 1px solid var(--border-default);
  border-radius: var(--radius-xl);
  padding: var(--space-6);
  text-align: center;
  transition: all 0.25s ease;
}
.svc-why-card:hover {
  border-color: var(--border-brand);
  transform: translateY(-2px);
}
.svc-why-card--glow {
  background: linear-gradient(135deg, rgba(99,102,241,0.08), rgba(139,92,246,0.05));
  border-color: rgba(99,102,241,0.2);
}
.svc-why-card-icon { font-size: 28px; margin-bottom: var(--space-3); }
.svc-why-card-num {
  font-family: var(--font-display);
  font-size: var(--text-xl);
  font-weight: var(--fw-extrabold);
  color: var(--text-primary);
  margin-bottom: var(--space-1);
}
.svc-why-card-label {
  font-size: var(--text-xs);
  color: var(--text-muted);
  line-height: 1.4;
}

/* ── CTA Section ─────────────────────────────────────────── */
.svc-cta {
  padding: var(--space-20) 0;
}
.svc-cta-inner {
  position: relative;
  text-align: center;
  background: linear-gradient(135deg, rgba(99,102,241,0.08), rgba(139,92,246,0.05));
  border: 1px solid rgba(99,102,241,0.2);
  border-radius: var(--radius-2xl);
  padding: var(--space-16) var(--space-10);
  overflow: hidden;
}
.svc-cta-glow {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  width: 600px;
  height: 400px;
  background: radial-gradient(ellipse, rgba(99,102,241,0.12) 0%, transparent 70%);
  pointer-events: none;
  z-index: 0;
}
.svc-cta-content { position: relative; z-index: 1; }
.svc-cta-inner h2 {
  font-size: clamp(var(--text-2xl), 4vw, var(--text-4xl));
  margin-bottom: var(--space-4);
}
.svc-cta-inner p {
  color: var(--text-secondary);
  max-width: 560px;
  margin: 0 auto var(--space-8);
  font-size: var(--text-lg);
}
.svc-cta-actions {
  display: flex;
  justify-content: center;
  flex-wrap: wrap;
  gap: var(--space-4);
  margin-bottom: var(--space-5);
}
.svc-cta-note {
  font-size: var(--text-sm);
  color: var(--text-muted);
  margin: 0 !important;
}

/* ── Responsive ──────────────────────────────────────────── */
@media (max-width: 1024px) {
  .svc-grid { grid-template-columns: repeat(2, 1fr); }
  .svc-why-inner { grid-template-columns: 1fr; gap: var(--space-10); }
  .svc-why-cards { grid-template-columns: repeat(4, 1fr); }
}
@media (max-width: 768px) {
  .svc-hero { padding: var(--space-16) 0 var(--space-10); }
  .svc-hero-title { font-size: 2rem; }
  .svc-grid { grid-template-columns: 1fr; }
  .svc-stats { gap: var(--space-4); padding: var(--space-6); }
  .svc-stat-divider { width: 36px; height: 1px; }
  .svc-why-cards { grid-template-columns: repeat(2, 1fr); }
  .svc-cta-inner { padding: var(--space-10) var(--space-6); }
  .svc-hero-actions { gap: var(--space-3); }
  .svc-hero-actions .btn-lg { width: 100%; }
  .svc-cta-actions .btn-lg { width: 100%; }
}
@media (max-width: 480px) {
  .svc-stats { flex-direction: column; align-items: center; }
  .svc-stat-divider { display: none; }
  .svc-why-cards { grid-template-columns: 1fr 1fr; }
}

/* ── Light Mode Overrides ────────────────────────────────── */
[data-theme="light"] .svc-card {
  background: #ffffff;
  box-shadow: 0 1px 3px rgba(0,0,0,0.06);
}
[data-theme="light"] .svc-card:hover {
  box-shadow: 0 12px 40px rgba(99,102,241,0.15);
}
[data-theme="light"] .svc-stats {
  background: #ffffff;
  box-shadow: 0 1px 3px rgba(0,0,0,0.06);
}
[data-theme="light"] .svc-why { background: #f8fafc; }
[data-theme="light"] .svc-why-card { background: #ffffff; }
[data-theme="light"] .svc-cta-inner {
  background: linear-gradient(135deg, rgba(99,102,241,0.06), rgba(139,92,246,0.04));
}
</style>
