<!-- ══════════════════════════════════════════════════════
     HOMEPAGE — TECHAASVIK.COM
     India's Digital Marketing Authority Platform
════════════════════════════════════════════════════════ -->

<!-- ── HERO ── -->
<section class="hero" id="home-hero">
  <div class="container">
    <div class="hero-badge">
      <span class="hero-badge-dot"></span>
      India's #1 Digital Marketing Knowledge Platform
    </div>

    <h1 class="hero-title">
      Master Digital Marketing.<br>
      <span class="gradient-text">Rank. Convert. Grow.</span>
    </h1>

    <p class="hero-subtitle">
      India's most authoritative platform for SEO, AEO, GEO, Google Ads, Meta Ads, Content Marketing, Analytics — everything you need to dominate digital marketing in 2025.
    </p>

    <div class="hero-actions">
      <a href="/learn" class="btn btn-gradient btn-lg" id="heroStartLearning">
        🚀 Start Learning Free
      </a>
      <a href="/glossary" class="btn btn-secondary btn-lg">
        📖 Browse Glossary →
      </a>
    </div>

    <!-- Trust Badges -->
    <div class="hero-stats">
      <div>
        <div class="hero-stat-value">2,000+</div>
        <div class="hero-stat-label">Expert Guides Published</div>
      </div>
      <div>
        <div class="hero-stat-value">500+</div>
        <div class="hero-stat-label">Marketing Terms Defined</div>
      </div>
      <div>
        <div class="hero-stat-value">50+</div>
        <div class="hero-stat-label">Free Tools & Calculators</div>
      </div>
      <div>
        <div class="hero-stat-value">100%</div>
        <div class="hero-stat-label">Free, Always</div>
      </div>
    </div>
  </div>
</section>

<!-- ── TOPIC PILLARS ── -->
<section class="section-sm" style="background:var(--bg-surface);border-top:1px solid var(--border-subtle);border-bottom:1px solid var(--border-subtle);">
  <div class="container">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:var(--space-8);">
      <div>
        <h2 style="font-size:var(--text-2xl);">Master Every Channel</h2>
        <p style="color:var(--text-muted);margin:0;font-size:var(--text-sm);">Deep-dive knowledge centers for every digital marketing discipline</p>
      </div>
      <a href="/learn" class="btn btn-secondary btn-sm">View All Topics →</a>
    </div>

    <?php
    $topics = [
      ['SEO', '/learn/seo-complete-guide', '🔍', 'From technical to off-page. Rank higher on Google.', 'bg: rgba(99,102,241,0.1)'],
      ['AEO', '/learn/aeo-complete-guide', '💬', 'Answer Engine Optimization. Win featured snippets & AI answers.', ''],
      ['GEO', '/learn/geo-complete-guide', '🤖', 'Get cited by ChatGPT, Gemini & Perplexity.', ''],
      ['Google Ads', '/learn/google-ads-complete-guide', '📢', 'Search, Shopping, Display & YouTube campaigns.', ''],
      ['Meta Ads', '/learn/meta-ads-complete-guide', '📱', 'Facebook & Instagram advertising mastery.', ''],
      ['Content Marketing', '/learn/content-marketing', '✍️', 'Strategy, creation, distribution & measurement.', ''],
      ['Analytics & GA4', '/learn/analytics', '📊', 'GA4, GTM, Looker Studio & attribution modeling.', ''],
      ['AI Marketing', '/learn/ai-marketing', '⚡', 'Prompt engineering, AI tools & automation.', ''],
    ];
    ?>

    <div class="grid grid-4 gap-4">
      <?php foreach ($topics as [$name, $url, $icon, $desc, $bg]): ?>
      <a href="<?= $url ?>" class="card card-interactive" style="text-decoration:none;" id="topic-<?= str_slug($name) ?>">
        <div style="font-size:28px;margin-bottom:12px;"><?= $icon ?></div>
        <h3 style="font-size:var(--text-base);font-weight:var(--fw-semibold);color:var(--text-primary);margin-bottom:6px;"><?= $name ?></h3>
        <p style="font-size:var(--text-xs);color:var(--text-muted);margin:0;line-height:1.5;"><?= $desc ?></p>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ── LATEST BLOG POSTS ── -->
<section class="section">
  <div class="container">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:var(--space-8);">
      <div>
        <h2>Latest Articles</h2>
        <p style="color:var(--text-muted);margin:0;font-size:var(--text-sm);">Expert-authored, research-backed digital marketing guides</p>
      </div>
      <a href="/blog" class="btn btn-secondary btn-sm">All Articles →</a>
    </div>

    <?php if (!empty($latestPosts)): ?>
    <div class="grid grid-3 gap-6">
      <?php foreach ($latestPosts as $post): ?>
      <article class="post-card">
        <?php if (!empty($post['featured_image_id'])): ?>
        <div class="post-card-image">
          <img src="/assets/images/static/placeholder.jpg" alt="<?= e($post['title']) ?>" loading="lazy">
        </div>
        <?php else: ?>
        <div class="post-card-image" style="background:linear-gradient(135deg,rgba(99,102,241,0.15),rgba(139,92,246,0.1));display:flex;align-items:center;justify-content:center;">
          <span style="font-size:40px;opacity:0.5;">📝</span>
        </div>
        <?php endif; ?>

        <div class="post-card-body">
          <div class="post-card-meta">
            <span><?= format_date($post['published_at']) ?></span>
            <?php if (!empty($post['read_time'])): ?>
            <span>·</span>
            <span><?= $post['read_time'] ?> min read</span>
            <?php endif; ?>
          </div>
          <h3 class="post-card-title">
            <a href="/blog/<?= e($post['slug']) ?>" style="color:inherit;text-decoration:none;"><?= e($post['title']) ?></a>
          </h3>
          <?php if (!empty($post['excerpt'])): ?>
          <p class="post-card-excerpt"><?= str_truncate($post['excerpt'], 120) ?></p>
          <?php endif; ?>
        </div>

        <div class="post-card-footer">
          <span style="font-size:var(--text-xs);color:var(--text-muted);">By <?= e($post['author_name'] ?? 'TechAasvik') ?></span>
          <a href="/blog/<?= e($post['slug']) ?>" class="btn btn-ghost btn-sm">Read →</a>
        </div>
      </article>
      <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div style="text-align:center;padding:60px;color:var(--text-muted);">
      <p>Content is being published. Check back soon! 🚀</p>
    </div>
    <?php endif; ?>
  </div>
</section>

<!-- ── FREE TOOLS ── -->
<?php if (!empty($tools)): ?>
<section class="section-sm" style="background:var(--bg-surface);border-top:1px solid var(--border-subtle);border-bottom:1px solid var(--border-subtle);">
  <div class="container">
    <div style="text-align:center;margin-bottom:var(--space-10);">
      <h2>Free Digital Marketing Tools</h2>
      <p style="color:var(--text-secondary);max-width:560px;margin:var(--space-3) auto 0;">No signup required. Use 50+ professional marketing tools instantly.</p>
    </div>
    <div class="grid grid-3 gap-4">
      <?php foreach (array_slice($tools, 0, 6) as $tool): ?>
      <a href="/tools/<?= e($tool['slug']) ?>" class="card" style="text-decoration:none;display:flex;gap:12px;align-items:flex-start;">
        <div style="width:36px;height:36px;border-radius:8px;background:rgba(99,102,241,0.15);display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:16px;">⚙️</div>
        <div>
          <h3 style="font-size:var(--text-sm);font-weight:var(--fw-semibold);color:var(--text-primary);margin-bottom:4px;"><?= e($tool['title']) ?></h3>
          <p style="font-size:var(--text-xs);color:var(--text-muted);margin:0;"><?= str_truncate($tool['excerpt'] ?? '', 80) ?></p>
        </div>
      </a>
      <?php endforeach; ?>
    </div>
    <div style="text-align:center;margin-top:var(--space-8);">
      <a href="/tools" class="btn btn-secondary">View All 50+ Tools →</a>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- ── CASE STUDIES ── -->
<?php if (!empty($caseStudies)): ?>
<section class="section">
  <div class="container">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:var(--space-8);">
      <div>
        <h2>Real Results. Proven Strategies.</h2>
        <p style="color:var(--text-muted);margin:0;font-size:var(--text-sm);">Learn from real digital marketing campaigns with measurable ROI</p>
      </div>
      <a href="/case-studies" class="btn btn-secondary btn-sm">All Case Studies →</a>
    </div>
    <div class="grid grid-3 gap-6">
      <?php foreach ($caseStudies as $cs): ?>
      <article class="card card-interactive">
        <div style="display:flex;gap:10px;align-items:center;margin-bottom:12px;">
          <span class="badge badge-success">📊 Case Study</span>
        </div>
        <h3 style="font-size:var(--text-base);font-weight:var(--fw-semibold);color:var(--text-primary);margin-bottom:8px;line-height:1.4;">
          <a href="/case-studies/<?= e($cs['slug']) ?>" style="color:inherit;text-decoration:none;"><?= e($cs['title']) ?></a>
        </h3>
        <p style="font-size:var(--text-sm);color:var(--text-secondary);margin:0;"><?= str_truncate($cs['excerpt'] ?? '', 100) ?></p>
        <a href="/case-studies/<?= e($cs['slug']) ?>" class="btn btn-ghost btn-sm" style="margin-top:12px;padding-left:0;">Read Case Study →</a>
      </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- ── NEWSLETTER / LEAD CTA ── -->
<section class="section-sm">
  <div class="container container-md">
    <div class="newsletter-box">
      <div style="font-size:32px;margin-bottom:12px;">📧</div>
      <h2 style="font-size:var(--text-2xl);margin-bottom:var(--space-3);">Weekly Digital Marketing Digest</h2>
      <p>Join 10,000+ Indian marketers who get our curated weekly digest of the best SEO strategies, algorithm updates, ad tips, and tools. No spam.</p>
      <form class="newsletter-form" id="heroNewsletter" novalidate>
        <input type="email" name="email" placeholder="your@email.com" class="form-input" required id="heroEmail" aria-label="Email address">
        <button type="submit" class="btn btn-primary">Subscribe Free →</button>
      </form>
      <p style="font-size:var(--text-xs);color:var(--text-muted);margin-top:12px;">✅ Free forever &nbsp;·&nbsp; 📌 Unsubscribe anytime &nbsp;·&nbsp; 🔒 No spam ever</p>
    </div>
  </div>
</section>

<!-- ── FREE AUDIT CTA ── -->
<section class="section" style="background:var(--bg-surface);border-top:1px solid var(--border-subtle);">
  <div class="container">
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:var(--space-12);align-items:center;">
      <div>
        <span class="badge badge-brand" style="margin-bottom:16px;">🎁 Limited Time Offer</span>
        <h2 style="margin-bottom:var(--space-4);">Get Your Free Digital Marketing Audit</h2>
        <p style="color:var(--text-secondary);margin-bottom:var(--space-6);">Our experts will analyze your website's SEO, ad performance, content strategy, and analytics — then give you a personalized growth roadmap. Completely free.</p>
        <ul style="display:flex;flex-direction:column;gap:10px;margin-bottom:var(--space-8);color:var(--text-secondary);font-size:var(--text-sm);">
          <li>✅ Complete SEO technical audit</li>
          <li>✅ Competitor gap analysis</li>
          <li>✅ Keyword opportunity report</li>
          <li>✅ Content strategy recommendations</li>
          <li>✅ Delivered within 24 hours</li>
        </ul>
        <form id="auditForm" novalidate style="display:flex;gap:12px;flex-wrap:wrap;">
          <input type="text"  name="name"    placeholder="Your Name"    class="form-input" style="flex:1;min-width:160px;" id="auditName" required>
          <input type="email" name="email"   placeholder="Your Email"   class="form-input" style="flex:1;min-width:160px;" id="auditEmail" required>
          <input type="url"   name="website" placeholder="Your Website" class="form-input" style="flex:1;min-width:200px;" id="auditWebsite">
          <button type="submit" class="btn btn-gradient btn-lg" style="white-space:nowrap;">Get Free Audit 🚀</button>
        </form>
        <p id="auditMsg" style="font-size:var(--text-sm);color:var(--accent-400);margin-top:10px;display:none;"></p>
      </div>
      <div style="display:flex;flex-direction:column;gap:16px;">
        <?php foreach ([
          ['🔍', 'SEO Score', 'Technical health & keyword rankings'],
          ['📊', 'Analytics Review', 'GA4 setup, tracking gaps, attribution'],
          ['🎯', 'Competitor Analysis', 'Gap identification & opportunity mapping'],
          ['✍️', 'Content Audit', 'Existing content quality & topical gaps'],
        ] as [$icon, $title, $desc]): ?>
        <div class="card" style="display:flex;gap:16px;align-items:flex-start;">
          <div style="font-size:24px;flex-shrink:0;"><?= $icon ?></div>
          <div>
            <h3 style="font-size:var(--text-sm);font-weight:var(--fw-semibold);color:var(--text-primary);"><?= $title ?></h3>
            <p style="font-size:var(--text-xs);color:var(--text-muted);margin:0;"><?= $desc ?></p>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>
