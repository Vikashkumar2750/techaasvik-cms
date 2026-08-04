<!-- Single Service Page -->
<div class="container" style="padding-top:var(--space-10);padding-bottom:var(--space-16);">

  <?php \Core\View::partial('breadcrumb', ['crumbs' => [['name'=>'Home','url'=>'/'],['name'=>'Services','url'=>'/services'],['name'=>ucwords(str_replace('-', ' ', $slug ?? 'Service'))]]]) ?>

  <?php
  // Static service content data
  $serviceData = [
    'seo' => [
      'icon' => '🔍',
      'title' => 'SEO Services — Search Engine Optimization',
      'subtitle' => 'Rank Higher, Drive Organic Traffic, and Dominate Google Search Results',
      'features' => [
        ['icon' => '⚙️', 'title' => 'Technical SEO Audit', 'desc' => 'Comprehensive site audit covering crawlability, indexing, Core Web Vitals, schema markup, site architecture, and 200+ technical checkpoints.'],
        ['icon' => '📝', 'title' => 'On-Page SEO', 'desc' => 'Content optimization, keyword research, meta tags, internal linking strategy, heading structure, and E-E-A-T signal enhancement.'],
        ['icon' => '🔗', 'title' => 'Off-Page SEO & Link Building', 'desc' => 'White-hat link acquisition through digital PR, guest posting, broken link building, and HARO outreach campaigns.'],
        ['icon' => '📍', 'title' => 'Local SEO', 'desc' => 'Google Business Profile optimization, local citations, review management, and local pack ranking strategies.'],
        ['icon' => '🛒', 'title' => 'E-Commerce SEO', 'desc' => 'Product page optimization, category structure, faceted navigation, product schema, and marketplace SEO (Amazon, Flipkart).'],
        ['icon' => '📊', 'title' => 'SEO Analytics & Reporting', 'desc' => 'Custom dashboards, keyword tracking, competitor analysis, and monthly performance reports with actionable insights.'],
      ],
      'process' => ['Discovery & Audit', 'Keyword Research & Strategy', 'Technical Optimization', 'Content Creation & Optimization', 'Link Building & Authority', 'Monitoring & Reporting'],
      'stats' => [['value' => '340%', 'label' => 'Avg. Traffic Increase'], ['value' => '85%', 'label' => 'Client Retention Rate'], ['value' => '4.5x', 'label' => 'Average ROI'], ['value' => '200+', 'label' => 'Keywords Ranked']],
    ],
    'google-ads' => [
      'icon' => '📢',
      'title' => 'Google Ads Management — PPC Advertising',
      'subtitle' => 'Maximize ROI with Expertly Managed Google Ads Campaigns',
      'features' => [
        ['icon' => '🔍', 'title' => 'Search Campaigns', 'desc' => 'High-intent keyword targeting on Google Search. Responsive search ads, ad extensions, and Quality Score optimization for maximum visibility.'],
        ['icon' => '🖼️', 'title' => 'Display & Remarketing', 'desc' => 'Visual banner ads across 3M+ websites in the Google Display Network. Smart remarketing to re-engage past visitors and recover abandoned carts.'],
        ['icon' => '🛍️', 'title' => 'Shopping Campaigns', 'desc' => 'Product listing ads with images, prices, and reviews. Google Merchant Center optimization for maximum ROAS on e-commerce products.'],
        ['icon' => '🎬', 'title' => 'YouTube Advertising', 'desc' => 'Video ad campaigns on YouTube reaching 2B+ users. In-stream, bumper, discovery, and Shorts ads for brand awareness and conversions.'],
        ['icon' => '🤖', 'title' => 'Performance Max', 'desc' => 'AI-powered campaigns across all Google channels. Machine learning optimization for bids, audiences, and creative combinations.'],
        ['icon' => '📈', 'title' => 'Conversion Tracking & Analytics', 'desc' => 'Full funnel tracking setup with Google Ads + GA4 integration. Attribution modeling, A/B testing, and ROI analysis.'],
      ],
      'process' => ['Account Audit & Strategy', 'Keyword Research & Planning', 'Campaign Setup & Structuring', 'Ad Copy & Creative Development', 'Bid & Budget Optimization', 'A/B Testing & Scaling'],
      'stats' => [['value' => '5.2x', 'label' => 'Average ROAS'], ['value' => '42%', 'label' => 'Lower CPA vs Industry'], ['value' => '₹10Cr+', 'label' => 'Ad Spend Managed'], ['value' => '150+', 'label' => 'Campaigns Optimized']],
    ],
    'social-media' => [
      'icon' => '📱',
      'title' => 'Social Media Marketing',
      'subtitle' => 'Build Brand Awareness, Engage Your Audience, and Drive Revenue from Social',
      'features' => [
        ['icon' => '📋', 'title' => 'Strategy Development', 'desc' => 'Platform-specific strategies for Facebook, Instagram, LinkedIn, Twitter/X, and emerging platforms. Audience research and competitive analysis.'],
        ['icon' => '🎨', 'title' => 'Content Creation', 'desc' => 'Scroll-stopping creatives — graphic design, short-form video (Reels, Shorts), carousels, stories, and branded templates.'],
        ['icon' => '👥', 'title' => 'Community Management', 'desc' => 'Daily engagement, comment moderation, DM handling, and community building. Proactive audience interaction and brand voice management.'],
        ['icon' => '💰', 'title' => 'Paid Social Advertising', 'desc' => 'Meta Ads, LinkedIn Ads, and Twitter Ads management. Audience targeting, lookalike audiences, and retargeting campaigns.'],
        ['icon' => '🤝', 'title' => 'Influencer Marketing', 'desc' => 'Influencer identification, outreach, campaign management, and performance tracking. Micro and macro influencer collaborations.'],
        ['icon' => '📊', 'title' => 'Analytics & Reporting', 'desc' => 'Monthly social media reports with engagement metrics, growth analysis, content performance, and competitor benchmarking.'],
      ],
      'process' => ['Social Audit & Research', 'Strategy & Content Calendar', 'Content Creation & Scheduling', 'Community Engagement', 'Paid Campaign Management', 'Monthly Performance Review'],
      'stats' => [['value' => '3.5x', 'label' => 'Engagement Increase'], ['value' => '250%', 'label' => 'Follower Growth'], ['value' => '45%', 'label' => 'Higher Reach'], ['value' => '50+', 'label' => 'Brands Managed']],
    ],
    'content-marketing' => [
      'icon' => '✍️',
      'title' => 'Content Marketing Services',
      'subtitle' => 'Strategic Content That Attracts, Engages, and Converts Your Ideal Customers',
      'features' => [
        ['icon' => '🎯', 'title' => 'Content Strategy', 'desc' => 'Data-driven content planning aligned with your business goals. Buyer persona research, content gap analysis, and editorial calendar development.'],
        ['icon' => '📝', 'title' => 'Blog & Article Writing', 'desc' => 'SEO-optimized, expert-written articles by subject matter specialists. Long-form guides, listicles, how-tos, and thought leadership pieces.'],
        ['icon' => '📊', 'title' => 'Infographics & Visual Content', 'desc' => 'Data visualization, infographic design, social media graphics, and interactive content that earns backlinks and social shares.'],
        ['icon' => '🎬', 'title' => 'Video Content Production', 'desc' => 'Explainer videos, tutorials, product demos, customer testimonials, and short-form social content optimized for each platform.'],
        ['icon' => '📧', 'title' => 'Email Content & Newsletters', 'desc' => 'Nurture sequences, drip campaigns, newsletter content, and promotional emails that drive opens, clicks, and conversions.'],
        ['icon' => '📈', 'title' => 'Content Performance Analytics', 'desc' => 'Traffic analysis, engagement metrics, conversion tracking, content attribution, and ROI reporting for every piece of content.'],
      ],
      'process' => ['Audience Research & Strategy', 'Topic Research & Planning', 'Content Creation & Review', 'SEO Optimization & Publishing', 'Distribution & Promotion', 'Performance Analysis & Iteration'],
      'stats' => [['value' => '3x', 'label' => 'More Leads Generated'], ['value' => '62%', 'label' => 'Lower Cost vs Outbound'], ['value' => '500+', 'label' => 'Articles Published'], ['value' => '10M+', 'label' => 'Content Views']],
    ],
    'email-marketing' => [
      'icon' => '📧',
      'title' => 'Email Marketing & Automation',
      'subtitle' => 'Nurture Leads, Retain Customers, and Drive Revenue with Personalized Email Campaigns',
      'features' => [
        ['icon' => '🎯', 'title' => 'Email Strategy & Planning', 'desc' => 'Full email marketing strategy including segmentation, personalization frameworks, sending frequency optimization, and deliverability best practices.'],
        ['icon' => '🔄', 'title' => 'Marketing Automation', 'desc' => 'Automated workflows for welcome series, abandoned cart recovery, post-purchase follow-ups, re-engagement campaigns, and lead scoring.'],
        ['icon' => '✍️', 'title' => 'Email Design & Copywriting', 'desc' => 'Mobile-responsive email templates, persuasive copywriting, A/B subject line testing, and dynamic content personalization.'],
        ['icon' => '📋', 'title' => 'List Management & Segmentation', 'desc' => 'List hygiene, subscriber segmentation by behavior and demographics, preference centers, and GDPR-compliant list management.'],
        ['icon' => '🧪', 'title' => 'A/B Testing & Optimization', 'desc' => 'Subject line testing, send time optimization, content variations, CTA testing, and continuous improvement based on data.'],
        ['icon' => '📊', 'title' => 'Performance Analytics', 'desc' => 'Open rates, click-through rates, conversion tracking, revenue attribution, and actionable monthly reports.'],
      ],
      'process' => ['Email Audit & Strategy', 'List Segmentation & Cleanup', 'Template Design & Content', 'Automation Workflow Setup', 'A/B Testing & Optimization', 'Monthly Reporting & Analysis'],
      'stats' => [['value' => '₹42', 'label' => 'ROI Per ₹1 Spent'], ['value' => '35%', 'label' => 'Avg. Open Rate'], ['value' => '4.5%', 'label' => 'Click-Through Rate'], ['value' => '25%', 'label' => 'Revenue Increase']],
    ],
    'analytics' => [
      'icon' => '📊',
      'title' => 'Analytics & Reporting Services',
      'subtitle' => 'Track, Measure, and Optimize Every Aspect of Your Digital Marketing Performance',
      'features' => [
        ['icon' => '⚙️', 'title' => 'GA4 Setup & Configuration', 'desc' => 'Complete Google Analytics 4 implementation — data streams, enhanced measurement, custom events, conversion tracking, and cross-domain setup.'],
        ['icon' => '🏷️', 'title' => 'Tag Management', 'desc' => 'Google Tag Manager setup, custom triggers, marketing pixel implementation (Meta, LinkedIn, Twitter), and server-side tagging for accuracy.'],
        ['icon' => '📊', 'title' => 'Custom Dashboards', 'desc' => 'Looker Studio (Data Studio) dashboards connecting GA4, Search Console, Google Ads, and social media data for unified reporting.'],
        ['icon' => '🎯', 'title' => 'Conversion Tracking', 'desc' => 'End-to-end conversion funnel setup, micro and macro conversion tracking, attribution modeling, and lead source analysis.'],
        ['icon' => '🔍', 'title' => 'Data Analysis & Insights', 'desc' => 'Monthly deep-dive analysis, trend identification, anomaly detection, competitor benchmarking, and actionable recommendations.'],
        ['icon' => '📈', 'title' => 'CRO & A/B Testing', 'desc' => 'Conversion rate optimization through data-backed hypotheses, A/B testing (Google Optimize, VWO), heatmaps, and user behavior analysis.'],
      ],
      'process' => ['Analytics Audit', 'Tracking Implementation', 'Dashboard Creation', 'Data Collection & QA', 'Analysis & Insights', 'Optimization Recommendations'],
      'stats' => [['value' => '100%', 'label' => 'Tracking Accuracy'], ['value' => '35%', 'label' => 'Better Decision Making'], ['value' => '28%', 'label' => 'Conversion Uplift'], ['value' => '50+', 'label' => 'Dashboards Built']],
    ],
  ];

  $svc = $serviceData[$slug ?? ''] ?? null;
  ?>

  <?php if ($svc): ?>

  <!-- Hero Section -->
  <div style="margin-top:var(--space-4);margin-bottom:var(--space-12);">
    <div style="display:flex;align-items:center;gap:var(--space-4);margin-bottom:var(--space-4);">
      <span style="font-size:48px;"><?= $svc['icon'] ?></span>
      <div>
        <span class="badge badge-brand" style="margin-bottom:var(--space-2);display:inline-block;">🎯 Service</span>
        <h1 style="margin:0;font-size:var(--text-3xl);"><?= e($svc['title']) ?></h1>
      </div>
    </div>
    <p style="font-size:var(--text-lg);color:var(--text-secondary);max-width:700px;line-height:1.7;">
      <?= e($svc['subtitle']) ?>
    </p>
  </div>

  <!-- Stats Bar -->
  <?php if (!empty($svc['stats'])): ?>
  <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:var(--space-4);margin-bottom:var(--space-12);">
    <?php foreach ($svc['stats'] as $stat): ?>
    <div class="card" style="padding:var(--space-5);text-align:center;border-color:rgba(99,102,241,0.15);background:linear-gradient(135deg,rgba(99,102,241,0.04),rgba(139,92,246,0.02));">
      <div style="font-size:var(--text-2xl);font-weight:var(--fw-bold);color:var(--brand-primary);margin-bottom:var(--space-1);"><?= $stat['value'] ?></div>
      <div style="font-size:var(--text-xs);color:var(--text-muted);text-transform:uppercase;letter-spacing:0.5px;"><?= e($stat['label']) ?></div>
    </div>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>

  <!-- What We Offer -->
  <h2 style="font-size:var(--text-2xl);margin-bottom:var(--space-8);text-align:center;">What We Offer</h2>
  <div class="grid grid-3 gap-6" style="margin-bottom:var(--space-14);">
    <?php foreach ($svc['features'] as $f): ?>
    <div class="card card-interactive" style="padding:var(--space-6);display:flex;flex-direction:column;">
      <div style="font-size:32px;margin-bottom:var(--space-3);"><?= $f['icon'] ?></div>
      <h3 style="font-size:var(--text-base);font-weight:var(--fw-semibold);margin-bottom:var(--space-2);"><?= e($f['title']) ?></h3>
      <p style="font-size:var(--text-sm);color:var(--text-secondary);line-height:1.65;flex:1;"><?= e($f['desc']) ?></p>
    </div>
    <?php endforeach; ?>
  </div>

  <!-- Our Process -->
  <?php if (!empty($svc['process'])): ?>
  <div style="margin-bottom:var(--space-14);">
    <h2 style="font-size:var(--text-2xl);margin-bottom:var(--space-8);text-align:center;">Our Process</h2>
    <div style="display:flex;gap:var(--space-3);flex-wrap:wrap;justify-content:center;">
      <?php foreach ($svc['process'] as $i => $step): ?>
      <div style="display:flex;align-items:center;gap:var(--space-3);padding:var(--space-3) var(--space-5);background:var(--bg-surface);border:1px solid var(--border-subtle);border-radius:var(--radius-lg);">
        <span style="width:28px;height:28px;border-radius:50%;background:var(--brand-primary);color:white;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;flex-shrink:0;"><?= $i + 1 ?></span>
        <span style="font-size:var(--text-sm);font-weight:var(--fw-medium);"><?= e($step) ?></span>
      </div>
      <?php if ($i < count($svc['process']) - 1): ?>
      <span style="color:var(--text-muted);font-size:18px;display:flex;align-items:center;">→</span>
      <?php endif; ?>
      <?php endforeach; ?>
    </div>
  </div>
  <?php endif; ?>

  <?php elseif (!empty($page) && !empty($page['content'])): ?>
  <!-- CMS Content -->
  <div style="margin-top:var(--space-4);margin-bottom:var(--space-10);">
    <span class="badge badge-brand" style="margin-bottom:var(--space-3);">🎯 Service</span>
    <h1 style="margin-bottom:var(--space-4);"><?= e($page['title'] ?? ucwords(str_replace('-', ' ', $slug ?? ''))) ?></h1>
  </div>
  <div class="prose"><?= $page['content'] ?></div>
  <?php else: ?>
  <!-- Fallback -->
  <div style="margin-top:var(--space-4);margin-bottom:var(--space-10);">
    <span class="badge badge-brand">🎯 Service</span>
    <h1><?= e(ucwords(str_replace('-', ' ', $slug ?? 'Digital Marketing Service'))) ?></h1>
  </div>
  <?php endif; ?>

  <!-- CTA Section -->
  <div style="display:grid;grid-template-columns:1fr 300px;gap:var(--space-10);align-items:start;margin-top:var(--space-8);">
    <div class="card" style="padding:var(--space-8);text-align:center;background:linear-gradient(135deg,rgba(99,102,241,0.08),rgba(139,92,246,0.04));border-color:rgba(99,102,241,0.15);">
      <h2 style="font-size:var(--text-2xl);margin-bottom:var(--space-3);">Ready to Get Started?</h2>
      <p style="color:var(--text-secondary);margin-bottom:var(--space-6);max-width:500px;margin-left:auto;margin-right:auto;">
        Get a free consultation and custom strategy tailored to your business goals. No commitment required.
      </p>
      <div style="display:flex;gap:var(--space-3);justify-content:center;flex-wrap:wrap;">
        <a href="/contact" class="btn btn-primary btn-lg">Get Free Consultation →</a>
        <a href="/services" class="btn btn-secondary btn-lg">← All Services</a>
      </div>
    </div>

    <aside style="position:sticky;top:100px;">
      <div class="card" style="padding:var(--space-5);">
        <h3 style="font-size:var(--text-base);font-weight:var(--fw-semibold);margin-bottom:var(--space-4);">Related Resources</h3>
        <ul style="list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:var(--space-2);">
          <li><a href="/learn" style="font-size:var(--text-sm);color:var(--brand-primary);">📚 Knowledge Center</a></li>
          <li><a href="/blog" style="font-size:var(--text-sm);color:var(--brand-primary);">📝 Latest Blog Posts</a></li>
          <li><a href="/tools" style="font-size:var(--text-sm);color:var(--brand-primary);">🛠️ Free Marketing Tools</a></li>
          <li><a href="/case-studies" style="font-size:var(--text-sm);color:var(--brand-primary);">📊 Case Studies</a></li>
          <li><a href="/glossary" style="font-size:var(--text-sm);color:var(--brand-primary);">📖 Marketing Glossary</a></li>
        </ul>
      </div>
    </aside>
  </div>

</div>
