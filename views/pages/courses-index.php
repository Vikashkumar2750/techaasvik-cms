<!-- Courses Index -->
<div class="container" style="padding-top:var(--space-10);padding-bottom:var(--space-16);">

  <?php \Core\View::partial('breadcrumb', ['crumbs' => [['name'=>'Home','url'=>'/'],['name'=>'Courses']]]) ?>

  <div style="text-align:center;margin:var(--space-5) auto var(--space-10);max-width:640px;">
    <h1>Free Digital Marketing Courses</h1>
    <p style="font-size:var(--text-lg);color:var(--text-secondary);margin-top:var(--space-4);">
      Structured, expert-designed courses on every digital marketing channel. Free forever. Learn at your own pace.
    </p>
  </div>

  <?php
  $defaultCourses = [
    ['seo-fundamentals',           '🔍', 'SEO Fundamentals',              '8 modules', 'beginner',     '4.8', 'Build solid SEO foundations from scratch.'],
    ['google-ads-fundamentals',    '📢', 'Google Ads Fundamentals',       '10 modules','beginner',     '4.9', 'Setup and run profitable Google Ads campaigns.'],
    ['meta-ads-fundamentals',      '📱', 'Meta Ads Mastery',              '12 modules','intermediate', '4.7', 'Master Facebook & Instagram advertising.'],
    ['content-strategy',           '✍️', 'Content Marketing Strategy',    '7 modules', 'beginner',     '4.8', 'Build a content machine that drives organic growth.'],
    ['ga4-complete-course',        '📊', 'Google Analytics 4 (GA4)',      '8 modules', 'intermediate', '4.9', 'Master GA4 reports, explorations, and attribution.'],
    ['aeo-mastery',                '💬', 'AEO & Featured Snippets',       '6 modules', 'advanced',     '4.8', 'Win featured snippets and AI answer boxes.'],
    ['email-marketing-course',     '📧', 'Email Marketing',               '8 modules', 'beginner',     '4.7', 'Build lists, write campaigns, and automate sequences.'],
    ['local-seo-course',           '📍', 'Local SEO for India',           '6 modules', 'beginner',     '4.8', 'Rank on Google Maps and local searches in India.'],
    ['ai-marketing-course',        '⚡', 'AI Marketing & ChatGPT SEO',    '10 modules','advanced',     '4.9', 'Leverage AI tools and prompt engineering for marketing.'],
    ['cro-course',                 '🎯', 'Conversion Rate Optimization',  '7 modules', 'intermediate', '4.7', 'Turn more visitors into customers with CRO frameworks.'],
  ];
  $levelColors = ['beginner' => 'var(--accent-400)', 'intermediate' => '#fbbf24', 'advanced' => '#f87171'];
  ?>

  <div class="grid grid-3 gap-6">
    <?php foreach ($defaultCourses as [$slug, $icon, $title, $modules, $level, $rating, $desc]): ?>
    <a href="/courses/<?= $slug ?>" class="card card-interactive" style="text-decoration:none;display:flex;flex-direction:column;" id="course-<?= $slug ?>">
      <div style="display:flex;gap:12px;align-items:flex-start;margin-bottom:12px;">
        <div style="width:52px;height:52px;border-radius:14px;background:linear-gradient(135deg,rgba(99,102,241,0.2),rgba(139,92,246,0.1));display:flex;align-items:center;justify-content:center;font-size:24px;flex-shrink:0;">
          <?= $icon ?>
        </div>
        <div>
          <div style="display:flex;gap:6px;align-items:center;margin-bottom:4px;">
            <span style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:0.06em;color:<?= $levelColors[$level] ?? 'var(--text-muted)' ?>;"><?= ucfirst($level) ?></span>
            <span style="color:var(--text-muted);font-size:10px;">·</span>
            <span style="font-size:10px;color:var(--text-muted);"><?= $modules ?></span>
          </div>
          <h2 style="font-size:var(--text-base);font-weight:var(--fw-semibold);color:var(--text-primary);line-height:1.3;"><?= $title ?></h2>
        </div>
      </div>
      <p style="font-size:var(--text-sm);color:var(--text-secondary);flex:1;line-height:1.5;"><?= $desc ?></p>
      <div style="margin-top:14px;padding-top:12px;border-top:1px solid var(--border-subtle);display:flex;justify-content:space-between;align-items:center;">
        <span style="font-size:var(--text-xs);color:var(--accent-400);font-weight:700;">Free</span>
        <div style="display:flex;align-items:center;gap:4px;">
          <span style="color:#fbbf24;font-size:12px;">★</span>
          <span style="font-size:var(--text-xs);color:var(--text-muted);"><?= $rating ?></span>
        </div>
        <span style="font-size:var(--text-xs);color:var(--brand-400);font-weight:600;">Start Course →</span>
      </div>
    </a>
    <?php endforeach; ?>
  </div>

  <!-- Newsletter -->
  <div class="newsletter-box" style="margin-top:var(--space-14);">
    <h2 style="font-size:var(--text-2xl);margin-bottom:var(--space-3);">🎓 Get Course Completion Certificates</h2>
    <p>Subscribe to unlock shareable LinkedIn certificates for all completed TechAasvik courses.</p>
    <form class="newsletter-form" id="courseNewsletter" novalidate>
      <input type="email" name="email" placeholder="your@email.com" class="form-input" required id="courseEmail" aria-label="Email">
      <button type="submit" class="btn btn-primary">Get Certificates →</button>
    </form>
  </div>

</div>
