<?php
/**
 * Main Site Layout — wraps all public-facing views.
 * Variables available: $seo, $schemas, $content (page body)
 */
$config = require APP_PATH . '/Config/config.php';
$gaId   = $config['analytics']['ga4_id'] ?? '';
$gtmId  = $config['analytics']['gtm_id'] ?? '';
?>
<!DOCTYPE html>
<html lang="<?= !empty($seo['lang']) ? $seo['lang'] : 'en' ?>" prefix="og: https://ogp.me/ns#">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="IE=edge">

<!-- ── SEO ── -->
<?= !empty($seo) ? render_meta($seo) : '<title>TechAasvik</title>' ?>

<!-- ── Verification ── -->
<?php if (!empty($config['seo']['gsc_verify'])): ?>
<meta name="google-site-verification" content="<?= e($config['seo']['gsc_verify']) ?>">
<?php endif; ?>

<!-- ── Preconnects ── -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

<!-- ── Fonts ── -->
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Sora:wght@400;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap">

<!-- ── Stylesheets ── -->
<link rel="stylesheet" href="/assets/css/main.css?v=<?= ASSET_VERSION ?>">

<!-- ── Favicon ── -->
<link rel="icon"             type="image/x-icon" href="/assets/images/static/favicon.ico">
<link rel="apple-touch-icon" sizes="180x180"     href="/assets/images/static/apple-touch-icon.png">
<link rel="icon"             type="image/png"    sizes="32x32" href="/assets/images/static/favicon-32x32.png">
<link rel="icon"             type="image/png"    sizes="16x16" href="/assets/images/static/favicon-16x16.png">
<link rel="manifest"         href="/site.webmanifest">

<!-- ── AI / LLM Discovery ── -->
<link rel="llms-txt"      href="/llms.txt"      type="text/plain">
<link rel="llms-full-txt" href="/llms-full.txt"  type="text/plain">
<link rel="sitemap"       href="/sitemap.xml"    type="application/xml">

<!-- ── Hreflang for multilingual ── -->
<?php if (!empty($seo['hreflang'])): ?>
  <?php foreach ($seo['hreflang'] as $lang => $href): ?>
<link rel="alternate" hreflang="<?= e($lang) ?>" href="<?= e($href) ?>">
  <?php endforeach; ?>
<?php endif; ?>

<!-- ── Schema.org JSON-LD ── -->
<?php if (!empty($schemas)): ?>
  <?php foreach ($schemas as $schema): ?>
<script type="application/ld+json"><?= json_encode($schema, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) ?></script>
  <?php endforeach; ?>
<?php endif; ?>

<!-- ── GTM ── -->
<?php if ($gtmId): ?>
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src='https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);})(window,document,'script','dataLayer','<?= $gtmId ?>');</script>
<?php endif; ?>
</head>
<body>

<?php if ($gtmId): ?>
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=<?= $gtmId ?>" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<?php endif; ?>

<!-- Reading progress bar -->
<div class="reading-progress" id="readingProgress" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>

<!-- Skip to content -->
<a href="#main-content" class="sr-only" style="position:fixed;top:0;left:0;z-index:9999;background:var(--brand-500);color:#fff;padding:8px 16px;border-radius:0 0 8px 0;">Skip to main content</a>

<!-- ══════════════════════════════
     HEADER
════════════════════════════════ -->
<?php \Core\View::partial('header') ?>

<!-- ══════════════════════════════
     MAIN CONTENT
════════════════════════════════ -->
<main id="main-content">
  <?= $content ?? '' ?>
</main>

<!-- ══════════════════════════════
     FOOTER
════════════════════════════════ -->
<?php \Core\View::partial('footer') ?>

<!-- ══════════════════════════════
     SCRIPTS
════════════════════════════════ -->
<script src="/assets/js/main.js?v=<?= ASSET_VERSION ?>" defer></script>

<?php if ($gaId): ?>
<script async src="https://www.googletagmanager.com/gtag/js?id=<?= $gaId ?>"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', '<?= $gaId ?>', { anonymize_ip: true });
</script>
<?php endif; ?>

<?php
  $recaptchaKey = $config['google']['recaptcha_site_key'] ?? '';
  if ($recaptchaKey && $recaptchaKey !== 'RECAPTCHA_SITE_KEY'):
?>
<!-- reCAPTCHA v3 — auto-inject token into all forms -->
<script src="https://www.google.com/recaptcha/api.js?render=<?= $recaptchaKey ?>"></script>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('form[data-recaptcha]').forEach(function(form) {
      form.addEventListener('submit', function(e) {
        e.preventDefault();
        grecaptcha.ready(function() {
          grecaptcha.execute('<?= $recaptchaKey ?>', {action: 'submit'}).then(function(token) {
            let input = form.querySelector('input[name="g_recaptcha_token"]');
            if (!input) {
              input = document.createElement('input');
              input.type = 'hidden';
              input.name = 'g_recaptcha_token';
              form.appendChild(input);
            }
            input.value = token;
            form.submit();
          });
        });
      });
    });
  });
</script>
<?php endif; ?>

</body>
</html>

