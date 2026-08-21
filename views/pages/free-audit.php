<?php
/**
 * Free Digital Marketing Audit — Landing Page
 * Captures audit leads via /lead/audit endpoint
 */
?>

<!-- ─────────────────────────────────────────────────────────
     FREE AUDIT PAGE
───────────────────────────────────────────────────────── -->

<!-- Hero -->
<section class="audit-hero" aria-labelledby="audit-hero-heading">
  <div class="container">
    <div class="audit-hero-inner">

      <!-- Left: Copy -->
      <div class="audit-hero-copy">
        <div class="audit-badge">
          <span class="audit-badge-dot"></span>
          Free Digital Marketing Audit — Limited Slots
        </div>

        <h1 id="audit-hero-heading" class="audit-hero-title">
          Get Your Free<br>
          <span class="gradient-text">Digital Marketing Audit</span>
        </h1>

        <p class="audit-hero-desc">
          Our certified experts will analyse your website, SEO health, ad performance, and social presence — then deliver a personalised roadmap to grow your business faster in 2026.
        </p>

        <ul class="audit-includes" aria-label="What's included in the audit">
          <li>
            <span class="audit-check">✓</span>
            <div>
              <strong>SEO & Technical Health Check</strong>
              <span>Core Web Vitals, indexation, backlinks, keyword gaps</span>
            </div>
          </li>
          <li>
            <span class="audit-check">✓</span>
            <div>
              <strong>GEO / AI Visibility Audit</strong>
              <span>How your brand appears in ChatGPT, Gemini & AI Overviews</span>
            </div>
          </li>
          <li>
            <span class="audit-check">✓</span>
            <div>
              <strong>Paid Ads Performance Review</strong>
              <span>Google Ads, Meta Ads — wasted spend & missed opportunities</span>
            </div>
          </li>
          <li>
            <span class="audit-check">✓</span>
            <div>
              <strong>Competitor Gap Analysis</strong>
              <span>Where your top competitors are beating you and how to close the gap</span>
            </div>
          </li>
          <li>
            <span class="audit-check">✓</span>
            <div>
              <strong>Action Plan & Recommendations</strong>
              <span>Prioritised 90-day roadmap delivered as a PDF report</span>
            </div>
          </li>
        </ul>

        <!-- Social Proof -->
        <div class="audit-social-proof">
          <div class="audit-avatars" aria-label="Recent clients">
            <?php
            $initials = ['AK','RM','PS','VT','SM'];
            $colors   = ['#6366f1','#10b981','#f59e0b','#ec4899','#8b5cf6'];
            foreach ($initials as $i => $init):
            ?>
            <span class="audit-avatar" style="background:<?= $colors[$i] ?>;" title="Client <?= ($i+1) ?>"><?= $init ?></span>
            <?php endforeach; ?>
          </div>
          <div>
            <div class="audit-stars" aria-label="5 star rating">★★★★★</div>
            <p>Trusted by <strong>500+</strong> businesses across India</p>
          </div>
        </div>
      </div>

      <!-- Right: Form -->
      <div class="audit-form-wrap" id="audit-form-section">
        <div class="audit-form-card">
          <div class="audit-form-header">
            <h2>Request Your Free Audit</h2>
            <p>Fill in your details and we'll get back to you within 24 hours with a personalised analysis.</p>
          </div>

          <p id="auditMsg" style="display:none;padding:12px 16px;border-radius:8px;font-weight:600;margin-bottom:16px;"></p>

          <form id="auditForm" action="/lead/audit" method="POST" novalidate data-recaptcha>
            <div class="audit-form-row">
              <div class="form-group">
                <label for="auditName" class="form-label">Your Name <span aria-hidden="true">*</span></label>
                <input type="text" id="auditName" name="name" class="form-input" placeholder="Vikash Kumar" required autocomplete="name">
              </div>
              <div class="form-group">
                <label for="auditEmail" class="form-label">Business Email <span aria-hidden="true">*</span></label>
                <input type="email" id="auditEmail" name="email" class="form-input" placeholder="you@company.com" required autocomplete="email">
              </div>
            </div>

            <div class="audit-form-row">
              <div class="form-group">
                <label for="auditPhone" class="form-label">Phone Number</label>
                <input type="tel" id="auditPhone" name="phone" class="form-input" placeholder="+91 98765 43210" autocomplete="tel">
              </div>
              <div class="form-group">
                <label for="auditCompany" class="form-label">Company / Brand</label>
                <input type="text" id="auditCompany" name="company" class="form-input" placeholder="Acme Pvt Ltd" autocomplete="organization">
              </div>
            </div>

            <div class="form-group">
              <label for="auditWebsite" class="form-label">Your Website <span aria-hidden="true">*</span></label>
              <input type="url" id="auditWebsite" name="website" class="form-input" placeholder="https://yourwebsite.com" required autocomplete="url">
            </div>

            <div class="form-group">
              <label for="auditService" class="form-label">Primary Service Interest</label>
              <select id="auditService" name="service" class="form-select">
                <option value="">— Select a service —</option>
                <option value="seo">SEO Services</option>
                <option value="google-ads">Google Ads (PPC)</option>
                <option value="meta-ads">Meta Ads (Facebook/Instagram)</option>
                <option value="social-media">Social Media Marketing</option>
                <option value="content-marketing">Content Marketing</option>
                <option value="geo">GEO — Generative Engine Optimization</option>
                <option value="aeo">AEO — Answer Engine Optimization</option>
                <option value="ai-marketing">AI Marketing & Automation</option>
                <option value="video-marketing">Video & Reels Marketing</option>
                <option value="cro">CRO — Conversion Rate Optimization</option>
                <option value="analytics">Analytics & Reporting</option>
                <option value="full-stack">Full-Stack Digital Marketing</option>
              </select>
            </div>

            <div class="form-group">
              <label for="auditBudget" class="form-label">Monthly Marketing Budget</label>
              <select id="auditBudget" name="budget" class="form-select">
                <option value="">— Select budget range —</option>
                <option value="under-25k">Under ₹25,000 / month</option>
                <option value="25k-50k">₹25,000 – ₹50,000 / month</option>
                <option value="50k-1l">₹50,000 – ₹1,00,000 / month</option>
                <option value="1l-5l">₹1,00,000 – ₹5,00,000 / month</option>
                <option value="5l-plus">₹5,00,000+ / month</option>
              </select>
            </div>

            <div class="form-group">
              <label for="auditGoals" class="form-label">Tell us about your main marketing challenge</label>
              <textarea id="auditGoals" name="message" class="form-textarea" rows="3" placeholder="E.g. Our organic traffic dropped 40% after a Google update, and our Google Ads ROAS is only 1.2x. We need help getting back on track..."></textarea>
            </div>

            <input type="hidden" name="lead_type" value="audit">
            <input type="hidden" name="source_page" value="/free-audit">
            <?php if (!empty($_GET['service'])): ?>
            <input type="hidden" name="service_interest" value="<?= htmlspecialchars($_GET['service']) ?>">
            <?php endif; ?>

            <button type="submit" class="btn btn-gradient btn-lg w-full" id="auditSubmit">
              Get My Free Audit 🚀
            </button>

            <p style="font-size:12px;color:var(--text-muted);text-align:center;margin-top:12px;">
              By submitting, you agree to our <a href="/privacy-policy" style="color:inherit;text-decoration:underline;">Privacy Policy</a>. We never share your data.
            </p>
          </form>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- Brands / Logos (placeholder) -->
<section class="audit-trust" aria-label="Trusted by">
  <div class="container">
    <p class="audit-trust-label">Trusted by businesses in every industry</p>
    <div class="audit-trust-badges">
      <span class="audit-trust-badge">🛒 E-commerce</span>
      <span class="audit-trust-badge">🏥 Healthcare</span>
      <span class="audit-trust-badge">🏗️ Real Estate</span>
      <span class="audit-trust-badge">🎓 EdTech</span>
      <span class="audit-trust-badge">⚙️ SaaS</span>
      <span class="audit-trust-badge">🍽️ F&B</span>
      <span class="audit-trust-badge">💼 B2B Services</span>
      <span class="audit-trust-badge">🌏 D2C Brands</span>
    </div>
  </div>
</section>

<style>
/* ─────────────────────────────────────────────────────────────
   FREE AUDIT PAGE STYLES
───────────────────────────────────────────────────────── */
.audit-hero {
  padding: var(--space-16) 0 var(--space-12);
  position: relative;
  overflow: hidden;
}
.audit-hero::before {
  content: '';
  position: absolute;
  top: -200px;
  right: -200px;
  width: 700px;
  height: 700px;
  background: radial-gradient(circle, rgba(99,102,241,0.1) 0%, transparent 65%);
  pointer-events: none;
}

.audit-hero-inner {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: var(--space-12);
  align-items: start;
}

/* Badge */
.audit-badge {
  display: inline-flex;
  align-items: center;
  gap: var(--space-2);
  background: rgba(16,185,129,0.12);
  border: 1px solid rgba(16,185,129,0.25);
  border-radius: var(--radius-full);
  padding: var(--space-2) var(--space-4);
  font-size: var(--text-xs);
  font-weight: var(--fw-semibold);
  color: var(--accent-400);
  letter-spacing: 0.03em;
  margin-bottom: var(--space-6);
}
.audit-badge-dot {
  width: 7px;
  height: 7px;
  border-radius: 50%;
  background: var(--accent-400);
  animation: pulse 2s infinite;
  flex-shrink: 0;
}

.audit-hero-title {
  font-size: clamp(2rem, 4vw, 3.5rem);
  font-family: var(--font-display);
  font-weight: var(--fw-extrabold);
  letter-spacing: -0.03em;
  line-height: 1.1;
  color: var(--text-primary);
  margin-bottom: var(--space-6);
}
.audit-hero-desc {
  font-size: var(--text-lg);
  color: var(--text-secondary);
  line-height: var(--leading-relaxed);
  margin-bottom: var(--space-8);
}

/* Includes List */
.audit-includes {
  list-style: none;
  padding: 0;
  margin: 0 0 var(--space-8);
  display: flex;
  flex-direction: column;
  gap: var(--space-4);
}
.audit-includes li {
  display: flex;
  gap: var(--space-3);
  align-items: flex-start;
}
.audit-check {
  width: 22px;
  height: 22px;
  background: rgba(16,185,129,0.15);
  color: var(--accent-400);
  border: 1px solid rgba(16,185,129,0.3);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 12px;
  font-weight: var(--fw-bold);
  flex-shrink: 0;
  margin-top: 2px;
}
.audit-includes strong {
  display: block;
  font-size: var(--text-sm);
  font-weight: var(--fw-semibold);
  color: var(--text-primary);
}
.audit-includes span {
  font-size: var(--text-xs);
  color: var(--text-muted);
  line-height: 1.5;
}

/* Social Proof */
.audit-social-proof {
  display: flex;
  align-items: center;
  gap: var(--space-4);
  padding: var(--space-4) var(--space-5);
  background: var(--bg-surface);
  border: 1px solid var(--border-subtle);
  border-radius: var(--radius-xl);
}
.audit-avatars {
  display: flex;
}
.audit-avatar {
  width: 34px;
  height: 34px;
  border-radius: 50%;
  color: #fff;
  font-size: 11px;
  font-weight: var(--fw-bold);
  display: flex;
  align-items: center;
  justify-content: center;
  border: 2px solid var(--bg-base);
  margin-left: -8px;
  flex-shrink: 0;
}
.audit-avatars .audit-avatar:first-child { margin-left: 0; }
.audit-stars {
  font-size: var(--text-sm);
  color: var(--gold-400);
  letter-spacing: 2px;
  margin-bottom: 2px;
}
.audit-social-proof p {
  font-size: var(--text-xs);
  color: var(--text-secondary);
  margin: 0;
}
.audit-social-proof strong { color: var(--text-primary); }

/* Form */
.audit-form-wrap {
  position: sticky;
  top: calc(var(--header-height) + var(--space-6));
}
.audit-form-card {
  background: var(--bg-surface);
  border: 1px solid var(--border-default);
  border-radius: var(--radius-2xl);
  padding: var(--space-8);
  box-shadow: var(--shadow-xl);
}
.audit-form-header {
  margin-bottom: var(--space-6);
  padding-bottom: var(--space-6);
  border-bottom: 1px solid var(--border-subtle);
}
.audit-form-header h2 {
  font-size: var(--text-2xl);
  margin-bottom: var(--space-2);
}
.audit-form-header p {
  font-size: var(--text-sm);
  color: var(--text-secondary);
  margin: 0;
}

.audit-form-row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: var(--space-4);
}

/* Trust section */
.audit-trust {
  border-top: 1px solid var(--border-subtle);
  padding: var(--space-10) 0;
  text-align: center;
}
.audit-trust-label {
  font-size: var(--text-sm);
  color: var(--text-muted);
  text-transform: uppercase;
  letter-spacing: 0.08em;
  font-weight: var(--fw-semibold);
  margin-bottom: var(--space-6);
}
.audit-trust-badges {
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
  gap: var(--space-3);
}
.audit-trust-badge {
  display: inline-flex;
  align-items: center;
  gap: var(--space-2);
  font-size: var(--text-sm);
  font-weight: var(--fw-medium);
  padding: var(--space-2) var(--space-4);
  background: var(--bg-elevated);
  border: 1px solid var(--border-subtle);
  border-radius: var(--radius-full);
  color: var(--text-secondary);
  transition: all 0.2s ease;
}
.audit-trust-badge:hover {
  border-color: var(--border-brand);
  color: var(--text-primary);
  background: rgba(99,102,241,0.06);
}

/* Responsive */
@media (max-width: 1024px) {
  .audit-hero-inner { grid-template-columns: 1fr; }
  .audit-form-wrap { position: static; }
}
@media (max-width: 640px) {
  .audit-form-row { grid-template-columns: 1fr; gap: 0; }
  .audit-form-card { padding: var(--space-6); }
  .audit-hero-title { font-size: 1.875rem; }
  .audit-hero-desc { font-size: var(--text-base); }
}

/* Light mode */
[data-theme="light"] .audit-form-card {
  background: #ffffff;
  box-shadow: 0 4px 30px rgba(0,0,0,0.08);
}
[data-theme="light"] .audit-social-proof {
  background: #ffffff;
}
[data-theme="light"] .audit-trust-badge {
  background: #ffffff;
}
</style>
