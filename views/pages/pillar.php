<!-- Pillar / Topic Page -->
<div class="container" style="padding-top:var(--space-10);padding-bottom:var(--space-16);">

  <?php \Core\View::partial('breadcrumb', ['crumbs' => [['name'=>'Home','url'=>'/'],['name'=>'Learn','url'=>'/learn'],['name'=>$pillar['title']]]]) ?>

  <div style="margin-top:var(--space-4);margin-bottom:var(--space-10);">
    <span class="badge badge-brand" style="margin-bottom:var(--space-3);">📚 Pillar Guide</span>
    <h1 style="margin-bottom:var(--space-4);"><?= e($pillar['title']) ?></h1>
    <?php if (!empty($pillar['excerpt'])): ?>
    <p style="font-size:var(--text-lg);color:var(--text-secondary);max-width:700px;"><?= e($pillar['excerpt']) ?></p>
    <?php endif; ?>
    <?php if ($pillar['read_time'] ?? ''): ?>
    <p style="color:var(--text-muted);font-size:var(--text-sm);margin-top:var(--space-3);">⏱ <?= $pillar['read_time'] ?> min read</p>
    <?php endif; ?>
  </div>

  <!-- Main Content -->
  <div style="display:grid;grid-template-columns:1fr 300px;gap:var(--space-10);align-items:start;">

    <!-- Article Body -->
    <div>
      <?php if (!empty($pillar['content'])): ?>
      <div class="prose">
        <?= $pillar['content'] ?>
      </div>
      <?php endif; ?>

      <!-- Cluster Articles -->
      <?php if (!empty($clusters)): ?>
      <div style="margin-top:var(--space-12);padding-top:var(--space-8);border-top:1px solid var(--border-subtle);">
        <h2 style="font-size:var(--text-xl);margin-bottom:var(--space-6);">📖 In-Depth Articles</h2>
        <div style="display:flex;flex-direction:column;gap:var(--space-4);">
          <?php foreach ($clusters as $cluster): ?>
          <a href="/learn/<?= e($pillar['slug']) ?>/<?= e($cluster['slug']) ?>" style="display:flex;align-items:center;gap:var(--space-4);padding:var(--space-4);background:var(--bg-surface);border:1px solid var(--border-subtle);border-radius:var(--radius-lg);text-decoration:none;transition:all 0.15s;" onmouseover="this.style.borderColor='rgba(99,102,241,0.3)'" onmouseout="this.style.borderColor='rgba(255,255,255,0.06)'">
            <div style="width:40px;height:40px;border-radius:var(--radius-md);background:var(--bg-elevated);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
              <span style="font-size:18px;">📄</span>
            </div>
            <div style="flex:1;">
              <div style="font-size:var(--text-sm);font-weight:var(--fw-semibold);color:var(--text-primary);"><?= e($cluster['title']) ?></div>
              <?php if (!empty($cluster['excerpt'])): ?>
              <div style="font-size:var(--text-xs);color:var(--text-muted);margin-top:2px;"><?= str_truncate($cluster['excerpt'], 100) ?></div>
              <?php endif; ?>
            </div>
            <span style="color:var(--text-muted);font-size:var(--text-sm);">→</span>
          </a>
          <?php endforeach; ?>
        </div>
      </div>
      <?php endif; ?>
    </div>

    <!-- Sidebar -->
    <aside style="position:sticky;top:100px;">
      <?php if (!empty($clusters)): ?>
      <div class="card" style="padding:var(--space-5);">
        <h3 style="font-size:var(--text-sm);font-weight:var(--fw-semibold);color:var(--text-muted);text-transform:uppercase;letter-spacing:0.05em;margin-bottom:var(--space-4);">On this Topic</h3>
        <nav style="display:flex;flex-direction:column;gap:6px;">
          <?php foreach ($clusters as $cluster): ?>
          <a href="/learn/<?= e($pillar['slug']) ?>/<?= e($cluster['slug']) ?>" style="font-size:var(--text-sm);color:var(--text-secondary);text-decoration:none;padding:4px 0;transition:color 0.15s;" onmouseover="this.style.color='var(--brand-400)'" onmouseout="this.style.color='var(--text-secondary)'"><?= e($cluster['title']) ?></a>
          <?php endforeach; ?>
        </nav>
      </div>
      <?php endif; ?>
    </aside>

  </div>

</div>
