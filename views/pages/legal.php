<!-- Legal Pages (Privacy, Terms, Editorial, Disclaimer) -->
<?php
$pageTitles = [
    'privacy'   => 'Privacy Policy',
    'terms'     => 'Terms of Service',
    'editorial' => 'Editorial Policy',
    'disclaimer'=> 'Disclaimer',
];
$pageType  = $pageType ?? 'privacy';
$pageTitle = $pageTitles[$pageType] ?? 'Legal';
?>
<div class="container container-sm" style="padding-top:var(--space-10);padding-bottom:var(--space-16);">

  <h1 style="margin-bottom:var(--space-4);"><?= $pageTitle ?></h1>
  <p style="font-size:var(--text-sm);color:var(--text-muted);margin-bottom:var(--space-8);">
    Last updated: <?= date('d F Y') ?> &nbsp;·&nbsp; TechAasvik, India
  </p>

  <div class="prose">
    <?php if ($pageType === 'editorial'): ?>
    <h2>Our Editorial Standards</h2>
    <p>TechAasvik is committed to publishing accurate, unbiased, and helpful digital marketing content. Our editorial process includes expert authorship, factual verification, and regular updates.</p>
    <h2>Author Requirements</h2>
    <p>All TechAasvik authors must have demonstrable expertise in their subject area, typically through professional experience, certifications (Google Ads, Meta Blueprint, HubSpot, etc.), or verifiable client results.</p>
    <h2>Fact-Checking Process</h2>
    <p>Claims, statistics, and data cited in our content are verified against original sources. All statistics pages link directly to primary sources.</p>
    <h2>Content Updates</h2>
    <p>We review and update published content on a regular basis to reflect algorithm changes, platform updates, and new best practices.</p>
    <h2>Affiliate Disclosure</h2>
    <p>Some content may include affiliate links. We only recommend tools and products we have personally evaluated. Affiliate relationships never influence our editorial judgement.</p>
    <h2>Corrections Policy</h2>
    <p>If you spot an error, please <a href="/contact">contact us</a>. We correct factual errors promptly and transparently.</p>

    <?php elseif ($pageType === 'privacy'): ?>
    <h2>Information We Collect</h2>
    <p>When you subscribe to our newsletter, request a free audit, or contact us, we collect your name, email address, and relevant contact details. We also collect analytics data through Google Analytics 4 to improve our content.</p>
    <h2>How We Use Your Information</h2>
    <p>We use your information to send you the content you subscribed to, respond to inquiries, and improve our platform. We never sell your data.</p>
    <h2>Cookies</h2>
    <p>We use cookies for analytics (GA4) and to remember your preferences. You can disable cookies in your browser settings.</p>
    <h2>Third-Party Services</h2>
    <p>We use Google Analytics, Google Tag Manager, and email service providers. Their privacy policies apply to data processed by those services.</p>
    <h2>Your Rights</h2>
    <p>You can unsubscribe from our emails at any time using the link in every email. To request deletion of your data, contact us at privacy@techaasvik.com.</p>
    <h2>Contact</h2>
    <p>Questions about privacy? Email us at <a href="mailto:privacy@techaasvik.com">privacy@techaasvik.com</a>.</p>

    <?php elseif ($pageType === 'terms'): ?>
    <h2>Acceptance of Terms</h2>
    <p>By accessing TechAasvik, you agree to these Terms of Service. If you do not agree, please do not use our platform.</p>
    <h2>Content License</h2>
    <p>All content on TechAasvik is protected by copyright. You may share articles with attribution and a link back. Reproducing entire articles without permission is prohibited.</p>
    <h2>Free Tools</h2>
    <p>Our tools are provided "as is" without warranty. We are not responsible for decisions made based on tool outputs.</p>
    <h2>Limitation of Liability</h2>
    <p>TechAasvik is not liable for any direct, indirect, or consequential damages arising from use of this platform or the information provided.</p>
    <h2>Changes to Terms</h2>
    <p>We may update these terms. Continued use of the platform constitutes acceptance of updated terms.</p>

    <?php else: ?>
    <h2>General Disclaimer</h2>
    <p>The information provided on TechAasvik is for educational and informational purposes only. Digital marketing results vary based on many factors including industry, budget, and execution.</p>
    <h2>No Professional Advice</h2>
    <p>Nothing on this platform constitutes professional marketing, legal, or financial advice. Always consult qualified professionals for your specific situation.</p>
    <h2>Accuracy</h2>
    <p>While we strive for accuracy, we cannot guarantee that all information is current or complete. The digital marketing landscape changes rapidly.</p>
    <h2>External Links</h2>
    <p>We link to external resources for reference. We are not responsible for the content, accuracy, or practices of external websites.</p>
    <?php endif; ?>
  </div>

  <div style="margin-top:var(--space-10);padding-top:var(--space-6);border-top:1px solid var(--border-subtle);">
    <div style="display:flex;gap:12px;flex-wrap:wrap;">
      <a href="/privacy-policy"    class="btn btn-ghost btn-sm">Privacy Policy</a>
      <a href="/terms-of-service"  class="btn btn-ghost btn-sm">Terms of Service</a>
      <a href="/editorial-policy"  class="btn btn-ghost btn-sm">Editorial Policy</a>
      <a href="/disclaimer"        class="btn btn-ghost btn-sm">Disclaimer</a>
    </div>
  </div>

</div>
