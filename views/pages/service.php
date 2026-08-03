<!-- Single Service Page -->
<div class="container" style="padding-top:var(--space-10);padding-bottom:var(--space-16);">

  <?php \Core\View::partial('breadcrumb', ['crumbs' => [['name'=>'Home','url'=>'/'],['name'=>'Services','url'=>'/services'],['name'=>ucwords(str_replace('-', ' ', $slug ?? 'Service'))]]]) ?>

  <div style="margin-top:var(--space-4);margin-bottom:var(--space-10);">
    <span class="badge badge-brand" style="margin-bottom:var(--space-3);">🎯 Service</span>
    <h1 style="margin-bottom:var(--space-4);"><?= e(ucwords(str_replace('-', ' ', $slug ?? 'Digital Marketing Service'))) ?></h1>
  </div>

  <div style="display:grid;grid-template-columns:1fr 300px;gap:var(--space-10);align-items:start;">

    <!-- Main Content -->
    <div>
      <?php if (!empty($page) && !empty($page['content'])): ?>
      <div class="prose">
        <?= $page['content'] ?>
      </div>
      <?php else: ?>
      <div class="card" style="padding:var(--space-8);text-align:center;">
        <div style="font-size:48px;margin-bottom:var(--space-4);">🚧</div>
        <h2 style="font-size:var(--text-xl);margin-bottom:var(--space-3);">Coming Soon</h2>
        <p style="color:var(--text-secondary);">We're preparing detailed information about this service. Contact us to learn more.</p>
        <a href="/contact" class="btn btn-primary" style="margin-top:var(--space-4);">Contact Us →</a>
      </div>
      <?php endif; ?>
    </div>

    <!-- Sidebar CTA -->
    <aside style="position:sticky;top:100px;">
      <div class="card" style="padding:var(--space-6);background:linear-gradient(135deg,rgba(99,102,241,0.08),rgba(139,92,246,0.04));border-color:rgba(99,102,241,0.15);">
        <h3 style="font-size:var(--text-lg);font-weight:var(--fw-semibold);margin-bottom:var(--space-3);">Get Started Today</h3>
        <p style="font-size:var(--text-sm);color:var(--text-secondary);margin-bottom:var(--space-5);">
          Let our experts help you achieve your digital marketing goals with a customized strategy.
        </p>
        <a href="/contact" class="btn btn-primary" style="width:100%;">Request Free Audit</a>
      </div>

      <div class="card" style="padding:var(--space-5);margin-top:var(--space-4);">
        <a href="/services" class="btn btn-secondary" style="width:100%;">← All Services</a>
      </div>
    </aside>

  </div>

</div>
