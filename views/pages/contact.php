<!-- Contact Page -->
<div class="container container-md" style="padding-top:var(--space-12);padding-bottom:var(--space-16);">

  <div style="text-align:center;margin-bottom:var(--space-10);">
    <h1>Get in Touch</h1>
    <p style="font-size:var(--text-lg);color:var(--text-secondary);max-width:520px;margin:var(--space-4) auto 0;">
      Questions, partnerships, sponsorships, guest posts, or just want to say hi?
    </p>
  </div>

  <?php if (isset($_GET['sent'])): ?>
  <div class="alert" style="background:rgba(52,211,153,0.1);border:1px solid rgba(52,211,153,0.3);border-radius:var(--radius-xl);padding:var(--space-5);margin-bottom:var(--space-8);text-align:center;">
    ✅ <strong>Message received!</strong> We'll get back to you within 24 hours.
  </div>
  <?php endif; ?>

  <div style="display:grid;grid-template-columns:1fr 1fr;gap:var(--space-12);align-items:start;">

    <!-- Contact Form -->
    <div>
      <div class="card" style="padding:var(--space-8);">
        <h2 style="font-size:var(--text-xl);margin-bottom:var(--space-6);">Send a Message</h2>
        <form method="post" action="/contact" novalidate>

          <?php if (!empty($errors)): ?>
          <div style="background:rgba(248,113,113,0.1);border:1px solid rgba(248,113,113,0.3);border-radius:var(--radius-lg);padding:var(--space-4);margin-bottom:var(--space-5);">
            <?php foreach ($errors as $err): ?>
            <p style="color:#f87171;font-size:var(--text-sm);margin:2px 0;">• <?= e($err) ?></p>
            <?php endforeach; ?>
          </div>
          <?php endif; ?>

          <div style="display:grid;grid-template-columns:1fr 1fr;gap:var(--space-4);margin-bottom:var(--space-4);">
            <div style="min-width:0;">
              <label style="font-size:var(--text-sm);font-weight:var(--fw-semibold);color:var(--text-secondary);display:block;margin-bottom:6px;" for="name">Full Name *</label>
              <input type="text" id="name" name="name" class="form-input" placeholder="Your name" required value="<?= e($_POST['name'] ?? '') ?>">
            </div>
            <div style="min-width:0;">
              <label style="font-size:var(--text-sm);font-weight:var(--fw-semibold);color:var(--text-secondary);display:block;margin-bottom:6px;" for="email">Email Address *</label>
              <input type="email" id="email" name="email" class="form-input" placeholder="you@example.com" required value="<?= e($_POST['email'] ?? '') ?>">
            </div>
          </div>

          <div style="display:grid;grid-template-columns:1fr 1fr;gap:var(--space-4);margin-bottom:var(--space-4);">
            <div style="min-width:0;">
              <label style="font-size:var(--text-sm);font-weight:var(--fw-semibold);color:var(--text-secondary);display:block;margin-bottom:6px;" for="phone">Phone (optional)</label>
              <input type="tel" id="phone" name="phone" class="form-input" placeholder="+91 98765 43210" value="<?= e($_POST['phone'] ?? '') ?>">
            </div>
            <div style="min-width:0;">
              <label style="font-size:var(--text-sm);font-weight:var(--fw-semibold);color:var(--text-secondary);display:block;margin-bottom:6px;" for="company">Company (optional)</label>
              <input type="text" id="company" name="company" class="form-input" placeholder="Your company" value="<?= e($_POST['company'] ?? '') ?>">
            </div>
          </div>

          <div style="margin-bottom:var(--space-5);">
            <label style="font-size:var(--text-sm);font-weight:var(--fw-semibold);color:var(--text-secondary);display:block;margin-bottom:6px;" for="message">Message *</label>
            <textarea id="message" name="message" class="form-input" rows="5" placeholder="Tell us what you're looking for…" required style="resize:vertical;"><?= e($_POST['message'] ?? '') ?></textarea>
          </div>

          <button type="submit" class="btn btn-primary w-full" style="justify-content:center;padding:12px;">Send Message →</button>

        </form>
      </div>
    </div>

    <!-- Contact Info -->
    <div>
      <h2 style="font-size:var(--text-xl);margin-bottom:var(--space-6);">How We Can Help</h2>

      <?php foreach ([
        ['🤝', 'Partnerships', 'Want to partner with India\'s top digital marketing platform? Let\'s collaborate.'],
        ['✍️', 'Guest Posts', 'We accept expert guest contributions from certified marketing professionals.'],
        ['📢', 'Advertising', 'Reach 100K+ marketers and businesses through TechAasvik content sponsorship.'],
        ['🎓', 'Training', 'Custom corporate training on SEO, Google Ads, Meta Ads, and analytics.'],
        ['🔍', 'Free Audit', 'Get a comprehensive SEO and digital marketing audit for your business.'],
      ] as [$icon, $title, $desc]): ?>
      <div style="display:flex;gap:14px;align-items:flex-start;padding:var(--space-4);border-bottom:1px solid var(--border-subtle);">
        <div style="font-size:22px;flex-shrink:0;"><?= $icon ?></div>
        <div>
          <h3 style="font-size:var(--text-sm);font-weight:var(--fw-semibold);color:var(--text-primary);margin-bottom:4px;"><?= $title ?></h3>
          <p style="font-size:var(--text-xs);color:var(--text-muted);margin:0;"><?= $desc ?></p>
        </div>
      </div>
      <?php endforeach; ?>

      <!-- Social -->
      <div style="margin-top:var(--space-6);">
        <p style="font-size:var(--text-xs);font-weight:var(--fw-semibold);text-transform:uppercase;letter-spacing:0.06em;color:var(--text-muted);margin-bottom:var(--space-4);">Connect with us</p>
        <div class="social-links">
          <a href="https://linkedin.com/company/techaasvik" class="social-link" target="_blank" rel="noopener" aria-label="LinkedIn">in</a>
          <a href="https://twitter.com/techaasvik" class="social-link" target="_blank" rel="noopener" aria-label="Twitter">𝕏</a>
          <a href="https://youtube.com/@techaasvik" class="social-link" target="_blank" rel="noopener" aria-label="YouTube">▶</a>
          <a href="https://instagram.com/techaasvik" class="social-link" target="_blank" rel="noopener" aria-label="Instagram">📷</a>
        </div>
      </div>

      <!-- Response time notice -->
      <div style="margin-top:var(--space-6);padding:var(--space-4);background:rgba(99,102,241,0.08);border-radius:var(--radius-lg);border:1px solid rgba(99,102,241,0.15);">
        <p style="font-size:var(--text-sm);color:var(--text-secondary);margin:0;">⏱ We typically respond within <strong style="color:var(--text-primary);">24 hours</strong> on business days (Mon–Sat, 9am–7pm IST).</p>
      </div>
    </div>

  </div>
</div>
