<!-- Calculators Index -->
<div class="container" style="padding-top:var(--space-10);padding-bottom:var(--space-16);">

  <?php \Core\View::partial('breadcrumb', ['crumbs' => [['name'=>'Home','url'=>'/'],['name'=>'Calculators']]]) ?>

  <div style="margin-top:var(--space-4);margin-bottom:var(--space-10);">
    <div style="font-size:40px;margin-bottom:var(--space-4);">🧮</div>
    <h1>Digital Marketing Calculators</h1>
    <p style="font-size:var(--text-lg);color:var(--text-secondary);max-width:640px;margin-top:var(--space-3);">
      Free calculators to plan budgets, forecast ROI, and measure the effectiveness of your digital marketing campaigns.
    </p>
  </div>

  <?php if (!empty($calculators)): ?>
  <div class="grid grid-3 gap-6">
    <?php foreach ($calculators as $calc): ?>
    <div class="card card-interactive" style="padding:var(--space-6);display:flex;flex-direction:column;">
      <div style="font-size:32px;margin-bottom:var(--space-3);">🧮</div>
      <h2 style="font-size:var(--text-base);font-weight:var(--fw-semibold);color:var(--text-primary);margin-bottom:8px;flex:1;">
        <a href="/calculators/<?= e($calc['slug']) ?>" style="color:inherit;text-decoration:none;"><?= e($calc['title']) ?></a>
      </h2>
      <?php if (!empty($calc['excerpt'])): ?>
      <p style="font-size:var(--text-sm);color:var(--text-secondary);line-height:1.5;"><?= str_truncate($calc['excerpt'], 110) ?></p>
      <?php endif; ?>
      <a href="/calculators/<?= e($calc['slug']) ?>" class="btn btn-ghost btn-sm" style="margin-top:12px;padding-left:0;">Use Calculator →</a>
    </div>
    <?php endforeach; ?>
  </div>
  <?php else: ?>
  <div style="text-align:center;padding:80px;color:var(--text-muted);">
    <div style="font-size:48px;margin-bottom:16px;">🧮</div>
    <h2 style="font-size:var(--text-xl);">Coming Soon</h2>
    <p>We're building powerful marketing calculators. Check back soon!</p>
    <a href="/tools" class="btn btn-secondary" style="margin-top:16px;">Browse Free Tools →</a>
  </div>
  <?php endif; ?>

</div>
