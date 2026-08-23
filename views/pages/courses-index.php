<!-- Courses Index -->
<div class="container" style="padding-top:var(--space-10);padding-bottom:var(--space-16);">

  <?php \Core\View::partial('breadcrumb', ['crumbs' => [['name'=>'Home','url'=>'/'],['name'=>'Courses']]]) ?>

  <div style="text-align:center;margin:var(--space-5) auto var(--space-10);max-width:680px;">
    <span class="badge badge-brand" style="margin-bottom:var(--space-3);">🎓 Learn AI Marketing</span>
    <h1 style="font-size:var(--text-4xl);margin-bottom:var(--space-4);line-height:1.15;">Digital Marketing Courses</h1>
    <p style="font-size:var(--text-lg);color:var(--text-secondary);line-height:var(--leading-relaxed);">
      Expert-designed courses on every digital marketing channel. Start free. Get certified.
    </p>
  </div>

  <?php
  $defaultCourses = [
    ['seo-fundamentals',         '🔍', 'SEO Fundamentals',              '8 modules',  'beginner',     '4.8', 'Build solid SEO foundations from scratch. Keyword research, on-page, technical SEO.', false],
    ['google-ads-fundamentals',  '📢', 'Google Ads Fundamentals',       '10 modules', 'beginner',     '4.9', 'Setup and run profitable Google Ads campaigns from zero to first conversion.', false],
    ['meta-ads-fundamentals',    '📱', 'Meta Ads Mastery',              '12 modules', 'intermediate', '4.7', 'Master Facebook & Instagram advertising — targeting, creative, scaling.', false],
    ['content-strategy',         '✍️', 'Content Marketing Strategy',    '7 modules',  'beginner',     '4.8', 'Build a content machine that drives consistent organic growth.', false],
    ['ga4-complete-course',      '📊', 'Google Analytics 4 (GA4)',      '8 modules',  'intermediate', '4.9', 'Master GA4 reports, explorations, funnels and attribution.', false],
    ['aeo-mastery',              '💬', 'AEO & Featured Snippets',       '6 modules',  'advanced',     '4.8', 'Win featured snippets and AI answer boxes for maximum search visibility.', false],
    ['email-marketing-course',   '📧', 'Email Marketing',               '8 modules',  'beginner',     '4.7', 'Build lists, write campaigns, and automate sequences that convert.', false],
    ['local-seo-course',         '📍', 'Local SEO for India',           '6 modules',  'beginner',     '4.8', 'Rank on Google Maps and local searches across Indian cities.', false],
    ['ai-marketing-course',      '⚡', 'AI Marketing & ChatGPT SEO',    '10 modules', 'advanced',     '4.9', 'Leverage AI tools and prompt engineering to build a complete marketing system.', true],
    ['cro-course',               '🎯', 'Conversion Rate Optimization',  '7 modules',  'intermediate', '4.7', 'Turn more visitors into customers with proven CRO frameworks.', false],
  ];
  $levelColors = ['beginner' => 'var(--accent-400)', 'intermediate' => '#fbbf24', 'advanced' => '#f87171'];
  ?>

  <div class="grid grid-3 gap-6">
    <?php foreach ($defaultCourses as [$slug, $icon, $title, $modules, $level, $rating, $desc, $isFeatured]): ?>
    <div class="card<?= $isFeatured ? ' card-featured' : '' ?>" style="text-decoration:none;display:flex;flex-direction:column;position:relative;<?= $isFeatured ? 'border:1px solid rgba(99,102,241,0.4);box-shadow:0 0 40px rgba(99,102,241,0.15);' : '' ?>" id="course-<?= $slug ?>">
      <?php if ($isFeatured): ?>
      <div style="position:absolute;top:-1px;right:20px;background:linear-gradient(135deg,#6366f1,#8b5cf6);color:#fff;font-size:10px;font-weight:700;padding:4px 12px;border-radius:0 0 8px 8px;letter-spacing:0.06em;text-transform:uppercase;">⭐ Featured</div>
      <?php endif; ?>
      <div style="display:flex;gap:12px;align-items:flex-start;margin-bottom:12px;">
        <div style="width:52px;height:52px;border-radius:14px;background:linear-gradient(135deg,rgba(99,102,241,0.2),rgba(139,92,246,0.1));display:flex;align-items:center;justify-content:center;font-size:24px;flex-shrink:0;"><?= $icon ?></div>
        <div>
          <div style="display:flex;gap:6px;align-items:center;margin-bottom:4px;flex-wrap:wrap;">
            <span style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:0.06em;color:<?= $levelColors[$level] ?? 'var(--text-muted)' ?>;"><?= ucfirst($level) ?></span>
            <span style="color:var(--text-muted);font-size:10px;">·</span>
            <span style="font-size:10px;color:var(--text-muted);"><?= $modules ?></span>
          </div>
          <h2 style="font-size:var(--text-base);font-weight:var(--fw-semibold);color:var(--text-primary);line-height:1.3;"><?= $title ?></h2>
        </div>
      </div>
      <p style="font-size:var(--text-sm);color:var(--text-secondary);flex:1;line-height:1.6;"><?= $desc ?></p>

      <?php if ($isFeatured): ?>
      <!-- AI Marketing — dual buttons (5 free + paid) -->
      <div style="margin-top:16px;padding-top:14px;border-top:1px solid var(--border-subtle);">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:10px;flex-wrap:wrap;gap:6px;">
          <div style="display:flex;align-items:baseline;gap:8px;">
            <span style="font-size:13px;font-weight:800;color:var(--accent-400);">₹199</span>
            <span style="font-size:11px;color:var(--text-muted);text-decoration:line-through;">₹999</span>
            <span style="font-size:10px;font-weight:700;background:rgba(52,211,153,0.15);color:#34d399;padding:2px 7px;border-radius:4px;">SAVE 80%</span>
          </div>
          <div style="display:flex;align-items:center;gap:4px;">
            <span style="color:#fbbf24;font-size:12px;">★</span>
            <span style="font-size:11px;color:var(--text-muted);"><?= $rating ?></span>
          </div>
        </div>
        <div style="display:flex;gap:8px;flex-direction:column;">
          <a href="/courses/<?= $slug ?>" class="btn btn-primary" style="width:100%;justify-content:center;text-align:center;text-decoration:none;font-size:13px;" id="btn-start-<?= $slug ?>">🆓 Start Free — 5 Modules</a>
          <a href="/courses/<?= $slug ?>/enroll" class="btn btn-ghost" style="width:100%;justify-content:center;text-align:center;text-decoration:none;font-size:13px;border-color:rgba(99,102,241,0.4);color:var(--brand-400);" id="btn-unlock-<?= $slug ?>">🔓 Unlock Full Course ₹199</a>
        </div>
      </div>
      <?php else: ?>
      <div style="margin-top:14px;padding-top:12px;border-top:1px solid var(--border-subtle);display:flex;justify-content:space-between;align-items:center;">
        <span style="font-size:var(--text-xs);color:var(--accent-400);font-weight:700;">Free</span>
        <div style="display:flex;align-items:center;gap:4px;">
          <span style="color:#fbbf24;font-size:12px;">★</span>
          <span style="font-size:var(--text-xs);color:var(--text-muted);"><?= $rating ?></span>
        </div>
        <a href="/courses/<?= $slug ?>" style="font-size:var(--text-xs);color:var(--brand-400);font-weight:600;text-decoration:none;" id="btn-course-<?= $slug ?>">Start Course →</a>
      </div>
      <?php endif; ?>
    </div>
    <?php endforeach; ?>
  </div>

  <!-- Newsletter / Certificate Section -->
  <div class="newsletter-box" style="margin-top:var(--space-16);">
    <h2 style="font-size:var(--text-2xl);margin-bottom:var(--space-3);">🎓 Get Course Completion Certificates</h2>
    <p>Complete any TechAasvik course and earn a shareable, verifiable certificate. Free for life.</p>
    <form class="newsletter-form" id="courseNewsletter" novalidate>
      <input type="email" name="email" placeholder="your@email.com" class="form-input" required id="courseEmail" aria-label="Email">
      <button type="submit" class="btn btn-primary" id="btnGetCert">Get Certificates →</button>
    </form>
  </div>

</div>
