<?php
use Core\Auth;
Auth::startSession();
$csrfToken = Auth::csrfToken();
$completions    = $completions    ?? [];
$moduleSettings = $moduleSettings ?? [];
$totalPaid      = $totalPaid      ?? 0;

$moduleNames = [
    1  => 'AI-Native Marketing: What Changed & What Matters',
    2  => 'ChatGPT for Marketers: From Prompting to Context Engineering',
    3  => 'AI-Powered Market Research & Competitive Intelligence',
    4  => 'SEO in the AI Era: Keyword Strategy & Topical Authority',
    5  => 'AI Content Creation at Scale: Quality, Voice & Systems',
    6  => 'GEO & AEO: Getting Found in AI Answers',
    7  => 'AI-Powered Paid Advertising (Google & Meta)',
    8  => 'Marketing Automation, CRO & Lead Generation with AI',
    9  => 'Analytics, Attribution & the Data-Driven Marketing Mindset',
    10 => 'Capstone: Build Your AI Marketing Operating System',
];
?>

<div class="admin-page-header">
  <div>
    <h1 class="admin-page-title">📚 Module Management</h1>
    <p class="admin-page-subtitle">Manage course modules — add images, videos, descriptions, set free/paid</p>
  </div>
  <div style="display:flex;gap:10px;">
    <a href="/techaasvik_admin/course" class="admin-btn admin-btn-ghost">← Course Dashboard</a>
    <a href="/techaasvik_admin/course/certificates" class="admin-btn admin-btn-ghost">🎓 Certificates</a>
  </div>
</div>

<?php if ($flash = ($flash ?? null)): ?>
<div style="padding:12px 16px;border-radius:8px;margin-bottom:20px;background:<?= $flash['type']==='success'?'rgba(52,211,153,0.1)':'rgba(248,113,113,0.1)' ?>;border:1px solid <?= $flash['type']==='success'?'rgba(52,211,153,0.3)':'rgba(248,113,113,0.3)' ?>;color:<?= $flash['type']==='success'?'#34d399':'#f87171' ?>;">
  <?= htmlspecialchars($flash['message']) ?>
</div>
<?php endif; ?>

<!-- Modules Grid -->
<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(340px,1fr));gap:16px;">
  <?php for ($i = 1; $i <= 10; $i++): ?>
  <?php
    $imgUrl    = $moduleSettings["module_{$i}_image_url"]   ?? '';
    $videoUrl  = $moduleSettings["module_{$i}_video_url"]   ?? '';
    $desc      = $moduleSettings["module_{$i}_description"] ?? '';
    $duration  = $moduleSettings["module_{$i}_duration"]    ?? '';
    $isFree    = isset($moduleSettings["module_{$i}_is_free"])
                 ? (bool)(int)$moduleSettings["module_{$i}_is_free"]
                 : ($i <= 5);
    $completed = $completions[$i] ?? 0;
    $pct       = $totalPaid > 0 ? round($completed / $totalPaid * 100) : 0;
  ?>
  <div class="admin-table-wrapper" style="padding:0;overflow:hidden;">
    <div style="height:120px;background:<?= $imgUrl ? "url('{$imgUrl}') center/cover" : 'linear-gradient(135deg,rgba(99,102,241,0.15),rgba(139,92,246,0.08))' ?>;position:relative;">
      <div style="position:absolute;inset:0;background:rgba(0,0,0,0.35);display:flex;align-items:flex-end;padding:10px 14px;">
        <span style="font-size:11px;font-weight:700;background:<?= $isFree ? '#059669' : '#6366f1' ?>;color:#fff;padding:2px 10px;border-radius:100px;"><?= $isFree ? 'FREE' : 'PAID' ?></span>
        <?php if ($videoUrl): ?><span style="font-size:11px;font-weight:700;background:rgba(234,179,8,0.9);color:#000;padding:2px 10px;border-radius:100px;margin-left:4px;">🎬 Video</span><?php endif; ?>
      </div>
      <div style="position:absolute;top:10px;right:12px;font-size:28px;font-weight:900;color:rgba(255,255,255,0.4);"><?= $i ?></div>
    </div>

    <div style="padding:14px 16px;">
      <div style="font-size:13px;font-weight:700;margin-bottom:6px;line-height:1.35;">
        Module <?= $i ?>: <?= htmlspecialchars(mb_substr($moduleNames[$i], 0, 50)) ?>...
      </div>
      <?php if ($desc): ?>
      <div style="font-size:12px;color:var(--admin-text-muted);margin-bottom:8px;"><?= htmlspecialchars(mb_substr($desc, 0, 80)) ?>...</div>
      <?php endif; ?>
      <div style="margin-bottom:10px;">
        <div style="display:flex;justify-content:space-between;font-size:11px;color:var(--admin-text-muted);margin-bottom:4px;">
          <span><?= $completed ?>/<?= $totalPaid ?> completed</span><span><?= $pct ?>%</span>
        </div>
        <div style="height:4px;background:var(--admin-border);border-radius:4px;">
          <div style="height:4px;width:<?= $pct ?>%;background:linear-gradient(90deg,#6366f1,#8b5cf6);border-radius:4px;"></div>
        </div>
      </div>
      <div style="display:flex;gap:8px;align-items:center;">
        <?php if ($duration): ?><span style="font-size:11px;color:var(--admin-text-muted);">⏱ <?= htmlspecialchars($duration) ?></span><?php endif; ?>
        <a href="/techaasvik_admin/course/modules/<?= $i ?>/edit" class="admin-btn admin-btn-primary admin-btn-sm" style="margin-left:auto;">✏️ Edit</a>
      </div>
    </div>
  </div>
  <?php endfor; ?>
</div>
