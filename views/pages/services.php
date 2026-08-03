<!-- Services Listing Page -->
<div class="container" style="padding-top:var(--space-10);padding-bottom:var(--space-16);">

  <?php \Core\View::partial('breadcrumb', ['crumbs' => [['name'=>'Home','url'=>'/'],['name'=>'Services']]]) ?>

  <div style="margin-top:var(--space-4);margin-bottom:var(--space-12);text-align:center;">
    <h1 style="margin-bottom:var(--space-4);">Digital Marketing Services</h1>
    <p style="font-size:var(--text-lg);color:var(--text-secondary);max-width:700px;margin:0 auto;">
      Comprehensive digital marketing solutions to help your business grow. From SEO to paid advertising, we deliver measurable results.
    </p>
  </div>

  <div class="grid grid-3 gap-6">
    <?php
    $services = [
      ['slug' => 'seo', 'icon' => '🔍', 'title' => 'SEO Services', 'desc' => 'Rank higher on Google with our data-driven SEO strategies. Technical SEO, content optimization, and link building.'],
      ['slug' => 'google-ads', 'icon' => '📢', 'title' => 'Google Ads (PPC)', 'desc' => 'Maximize ROI with expertly managed Google Ads campaigns. Search, Display, Shopping, and YouTube advertising.'],
      ['slug' => 'social-media', 'icon' => '📱', 'title' => 'Social Media Marketing', 'desc' => 'Build brand awareness and engage your audience on Facebook, Instagram, LinkedIn, and Twitter.'],
      ['slug' => 'content-marketing', 'icon' => '✍️', 'title' => 'Content Marketing', 'desc' => 'Strategic content creation that attracts, engages, and converts. Blog posts, infographics, and video content.'],
      ['slug' => 'email-marketing', 'icon' => '📧', 'title' => 'Email Marketing', 'desc' => 'Nurture leads and retain customers with personalized email campaigns and marketing automation.'],
      ['slug' => 'analytics', 'icon' => '📊', 'title' => 'Analytics & Reporting', 'desc' => 'Track, measure, and optimize your digital marketing performance with comprehensive analytics setup.'],
    ];
    foreach ($services as $svc): ?>
    <div class="card card-interactive" style="padding:var(--space-6);display:flex;flex-direction:column;">
      <div style="font-size:40px;margin-bottom:var(--space-4);"><?= $svc['icon'] ?></div>
      <h2 style="font-size:var(--text-lg);font-weight:var(--fw-semibold);margin-bottom:var(--space-3);">
        <a href="/services/<?= $svc['slug'] ?>" style="color:inherit;text-decoration:none;"><?= $svc['title'] ?></a>
      </h2>
      <p style="font-size:var(--text-sm);color:var(--text-secondary);line-height:1.6;flex:1;"><?= $svc['desc'] ?></p>
      <a href="/services/<?= $svc['slug'] ?>" class="btn btn-ghost btn-sm" style="margin-top:var(--space-4);padding-left:0;">Learn More →</a>
    </div>
    <?php endforeach; ?>
  </div>

  <!-- CTA -->
  <div style="text-align:center;margin-top:var(--space-16);padding:var(--space-10);background:var(--bg-surface);border:1px solid var(--border-subtle);border-radius:var(--radius-xl);">
    <h2 style="font-size:var(--text-2xl);margin-bottom:var(--space-4);">Ready to Grow Your Business?</h2>
    <p style="color:var(--text-secondary);margin-bottom:var(--space-6);max-width:500px;margin-left:auto;margin-right:auto;">Get a free digital marketing audit and discover opportunities to improve your online presence.</p>
    <a href="/contact" class="btn btn-primary btn-lg">Get Free Audit →</a>
  </div>

</div>
