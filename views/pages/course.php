<!-- Single Course Page -->
<div class="container" style="padding-top:var(--space-10);padding-bottom:var(--space-16);">

  <?php \Core\View::partial('breadcrumb', ['crumbs' => [['name'=>'Home','url'=>'/'],['name'=>'Courses','url'=>'/courses'],['name'=>$course['title']]]]) ?>

  <div style="margin-top:var(--space-4);margin-bottom:var(--space-10);">
    <span class="badge badge-brand" style="margin-bottom:var(--space-3);">🎓 Free Course</span>
    <h1 style="margin-bottom:var(--space-4);"><?= e($course['title']) ?></h1>
    <?php if (!empty($course['excerpt'])): ?>
    <p style="font-size:var(--text-lg);color:var(--text-secondary);max-width:700px;"><?= e($course['excerpt']) ?></p>
    <?php endif; ?>
    <div style="display:flex;gap:var(--space-6);margin-top:var(--space-4);flex-wrap:wrap;">
      <?php if ($course['difficulty'] ?? ''): ?>
      <span style="font-size:var(--text-sm);color:var(--text-muted);">📊 <?= ucfirst($course['difficulty']) ?></span>
      <?php endif; ?>
      <?php if ($course['read_time'] ?? ''): ?>
      <span style="font-size:var(--text-sm);color:var(--text-muted);">⏱ <?= $course['read_time'] ?> min</span>
      <?php endif; ?>
      <?php if (!empty($modules)): ?>
      <span style="font-size:var(--text-sm);color:var(--text-muted);">📦 <?= count($modules) ?> modules</span>
      <?php endif; ?>
    </div>
  </div>

  <div style="display:grid;grid-template-columns:1fr 340px;gap:var(--space-10);align-items:start;">

    <!-- Course Content -->
    <div>
      <?php if (!empty($course['content'])): ?>
      <div class="prose">
        <?= $course['content'] ?>
      </div>
      <?php endif; ?>
    </div>

    <!-- Sidebar: Course Modules -->
    <aside style="position:sticky;top:100px;">
      <?php if (!empty($modules)): ?>
      <div class="card" style="padding:var(--space-5);">
        <h3 style="font-size:var(--text-sm);font-weight:var(--fw-semibold);color:var(--text-muted);text-transform:uppercase;letter-spacing:0.05em;margin-bottom:var(--space-5);">Course Modules</h3>
        <div style="display:flex;flex-direction:column;gap:var(--space-3);">
          <?php foreach ($modules as $i => $mod): ?>
          <a href="<?= e(content_url($mod)) ?>" style="display:flex;align-items:flex-start;gap:var(--space-3);text-decoration:none;padding:var(--space-3);border-radius:var(--radius-md);transition:background 0.15s;" onmouseover="this.style.background='var(--bg-elevated)'" onmouseout="this.style.background=''">
            <span style="width:28px;height:28px;border-radius:50%;background:var(--brand-600);color:#fff;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;flex-shrink:0;"><?= $i + 1 ?></span>
            <div>
              <div style="font-size:var(--text-sm);font-weight:var(--fw-medium);color:var(--text-primary);"><?= e($mod['title']) ?></div>
              <?php if ($mod['read_time'] ?? ''): ?>
              <div style="font-size:var(--text-xs);color:var(--text-muted);margin-top:2px;"><?= $mod['read_time'] ?> min</div>
              <?php endif; ?>
            </div>
          </a>
          <?php endforeach; ?>
        </div>
      </div>
      <?php else: ?>
      <div class="card" style="padding:var(--space-6);text-align:center;">
        <div style="font-size:36px;margin-bottom:var(--space-3);">🎓</div>
        <p style="font-size:var(--text-sm);color:var(--text-muted);">Course modules coming soon. Stay tuned!</p>
      </div>
      <?php endif; ?>
    </aside>

  </div>

</div>
