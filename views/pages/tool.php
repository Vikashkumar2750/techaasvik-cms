<!-- Single Tool Page -->
<div class="container" style="padding-top:var(--space-10);padding-bottom:var(--space-16);">

  <?php \Core\View::partial('breadcrumb', ['crumbs' => [['name'=>'Home','url'=>'/'],['name'=>'Tools','url'=>'/tools'],['name'=>$tool['title']]]]) ?>

  <div style="margin-top:var(--space-4);margin-bottom:var(--space-10);">
    <span class="badge badge-brand" style="margin-bottom:var(--space-3);">⚙️ Free Tool</span>
    <h1 style="margin-bottom:var(--space-4);"><?= e($tool['title']) ?></h1>
    <?php if (!empty($tool['excerpt'])): ?>
    <p style="font-size:var(--text-lg);color:var(--text-secondary);max-width:700px;"><?= e($tool['excerpt']) ?></p>
    <?php endif; ?>
  </div>

  <!-- Tool Content / Interface -->
  <div style="display:grid;grid-template-columns:1fr 300px;gap:var(--space-10);align-items:start;">

    <div>
      <?php if (!empty($tool['content'])): ?>
      <div class="prose">
        <?= $tool['content'] ?>
      </div>
      <?php endif; ?>
    </div>

    <!-- Sidebar -->
    <aside style="position:sticky;top:100px;">
      <div class="card" style="padding:var(--space-5);">
        <h3 style="font-size:var(--text-sm);font-weight:var(--fw-semibold);color:var(--text-muted);text-transform:uppercase;letter-spacing:0.05em;margin-bottom:var(--space-4);">Tool Info</h3>
        <div style="display:flex;flex-direction:column;gap:var(--space-3);">
          <?php if ($tool['difficulty'] ?? ''): ?>
          <div style="display:flex;justify-content:space-between;font-size:var(--text-sm);">
            <span style="color:var(--text-muted);">Difficulty</span>
            <span style="color:var(--text-primary);font-weight:var(--fw-medium);"><?= ucfirst($tool['difficulty']) ?></span>
          </div>
          <?php endif; ?>
          <div style="display:flex;justify-content:space-between;font-size:var(--text-sm);">
            <span style="color:var(--text-muted);">Price</span>
            <span style="color:var(--accent-400);font-weight:var(--fw-semibold);">Free</span>
          </div>
          <div style="display:flex;justify-content:space-between;font-size:var(--text-sm);">
            <span style="color:var(--text-muted);">Category</span>
            <span style="color:var(--text-primary);">Digital Marketing</span>
          </div>
        </div>
      </div>

      <div class="card" style="padding:var(--space-5);margin-top:var(--space-4);">
        <a href="/tools" class="btn btn-secondary" style="width:100%;">← Browse All Tools</a>
      </div>
    </aside>

  </div>

</div>
