<?php
use Core\Auth;
Auth::startSession();
$csrfToken  = Auth::csrfToken();
$num        = $num        ?? 1;
$moduleData = $moduleData ?? [];

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

function mval(array $d, string $k, string $def = ''): string {
    return htmlspecialchars($d[$k] ?? $def);
}
?>

<div class="admin-page-header">
  <div>
    <h1 class="admin-page-title">✏️ Edit Module <?= $num ?></h1>
    <p class="admin-page-subtitle"><?= htmlspecialchars($moduleNames[$num] ?? '') ?></p>
  </div>
  <div style="display:flex;gap:10px;">
    <a href="/techaasvik_admin/course/modules" class="admin-btn admin-btn-ghost">← All Modules</a>
    <?php if ($num > 1): ?><a href="/techaasvik_admin/course/modules/<?= $num-1 ?>/edit" class="admin-btn admin-btn-ghost">‹ Prev</a><?php endif; ?>
    <?php if ($num < 10): ?><a href="/techaasvik_admin/course/modules/<?= $num+1 ?>/edit" class="admin-btn admin-btn-ghost">Next ›</a><?php endif; ?>
  </div>
</div>

<?php if ($flash = ($flash ?? null)): ?>
<div style="padding:12px 16px;border-radius:8px;margin-bottom:20px;background:<?= $flash['type']==='success'?'rgba(52,211,153,0.1)':'rgba(248,113,113,0.1)' ?>;border:1px solid <?= $flash['type']==='success'?'rgba(52,211,153,0.3)':'rgba(248,113,113,0.3)' ?>;color:<?= $flash['type']==='success'?'#34d399':'#f87171' ?>;">
  <?= htmlspecialchars($flash['message']) ?>
</div>
<?php endif; ?>

<form method="POST" action="/techaasvik_admin/course/modules/<?= $num ?>/save">
  <input type="hidden" name="_csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">

  <div style="display:grid;grid-template-columns:1fr 360px;gap:20px;align-items:start;">

    <!-- LEFT: Main Content -->
    <div style="display:flex;flex-direction:column;gap:16px;">

      <!-- Title override -->
      <div class="admin-table-wrapper" style="padding:20px;">
        <h3 style="font-size:14px;font-weight:700;margin-bottom:16px;">📝 Module Info</h3>
        <div style="margin-bottom:14px;">
          <label style="display:block;font-size:13px;font-weight:600;margin-bottom:5px;">Custom Title <span style="color:var(--admin-text-muted);font-weight:400;">(leave blank to use default)</span></label>
          <input type="text" name="title" class="admin-input" value="<?= mval($moduleData,'title') ?>" placeholder="<?= htmlspecialchars($moduleNames[$num]) ?>">
        </div>
        <div style="margin-bottom:14px;">
          <label style="display:block;font-size:13px;font-weight:600;margin-bottom:5px;">Description <span style="color:var(--admin-text-muted);font-weight:400;">— shown in module card & course player</span></label>
          <textarea name="description" class="admin-input" rows="4" style="resize:vertical;" placeholder="What will students learn in this module..."><?= mval($moduleData,'description') ?></textarea>
        </div>
        <div style="margin-bottom:14px;">
          <label style="display:block;font-size:13px;font-weight:600;margin-bottom:5px;">Duration <span style="color:var(--admin-text-muted);font-weight:400;">— e.g. "45 min" or "1.5 hrs"</span></label>
          <input type="text" name="duration" class="admin-input" value="<?= mval($moduleData,'duration') ?>" placeholder="45 min">
        </div>
        <div>
          <label style="display:block;font-size:13px;font-weight:600;margin-bottom:5px;">Learning Objectives <span style="color:var(--admin-text-muted);font-weight:400;">— one per line</span></label>
          <textarea name="objectives" class="admin-input" rows="5" style="resize:vertical;" placeholder="Build your AI Marketing workflow&#10;Master context engineering&#10;..."><?= mval($moduleData,'objectives') ?></textarea>
        </div>
      </div>

      <!-- Video -->
      <div class="admin-table-wrapper" style="padding:20px;">
        <h3 style="font-size:14px;font-weight:700;margin-bottom:16px;">🎬 Video</h3>
        <div style="margin-bottom:14px;">
          <label style="display:block;font-size:13px;font-weight:600;margin-bottom:5px;">Video URL <span style="color:var(--admin-text-muted);font-weight:400;">— YouTube / Vimeo / direct MP4</span></label>
          <input type="url" name="video_url" class="admin-input" value="<?= mval($moduleData,'video_url') ?>" placeholder="https://www.youtube.com/watch?v=..." id="videoUrlInput">
        </div>
        <div style="margin-bottom:14px;">
          <label style="display:block;font-size:13px;font-weight:600;margin-bottom:5px;">Video Embed Code <span style="color:var(--admin-text-muted);font-weight:400;">— paste full iframe or leave blank to auto-convert URL</span></label>
          <textarea name="video_embed" class="admin-input" rows="3" style="resize:vertical;font-family:monospace;font-size:12px;" placeholder='&lt;iframe src="..."&gt;&lt;/iframe&gt;'><?= mval($moduleData,'video_embed') ?></textarea>
        </div>
        <?php if (!empty($moduleData['video_url'])): ?>
        <div style="background:var(--admin-bg-elevated);border-radius:8px;padding:12px;font-size:12px;color:var(--admin-text-muted);">
          Current: <a href="<?= htmlspecialchars($moduleData['video_url']) ?>" target="_blank" style="color:var(--admin-primary);"><?= htmlspecialchars($moduleData['video_url']) ?></a>
        </div>
        <?php endif; ?>
      </div>

      <!-- Resources -->
      <div class="admin-table-wrapper" style="padding:20px;">
        <h3 style="font-size:14px;font-weight:700;margin-bottom:16px;">📎 Resources & Downloads</h3>
        <label style="display:block;font-size:13px;font-weight:600;margin-bottom:5px;">Resources <span style="color:var(--admin-text-muted);font-weight:400;">— JSON array or plain text list</span></label>
        <textarea name="resources" class="admin-input" rows="5" style="resize:vertical;" placeholder='[{"name":"Prompt Template","url":"/downloads/m1-prompts.pdf"}]'><?= mval($moduleData,'resources') ?></textarea>
      </div>

    </div>

    <!-- RIGHT: Image + Access -->
    <div style="display:flex;flex-direction:column;gap:16px;">

      <!-- Module Image -->
      <div class="admin-table-wrapper" style="padding:20px;">
        <h3 style="font-size:14px;font-weight:700;margin-bottom:14px;">🖼️ Module Image</h3>
        <?php if (!empty($moduleData['image_url'])): ?>
        <div style="margin-bottom:12px;border-radius:8px;overflow:hidden;border:1px solid var(--admin-border);">
          <img src="<?= htmlspecialchars($moduleData['image_url']) ?>" alt="Module image" style="width:100%;height:160px;object-fit:cover;">
        </div>
        <?php else: ?>
        <div style="height:120px;background:linear-gradient(135deg,rgba(99,102,241,0.1),rgba(139,92,246,0.06));border-radius:8px;display:flex;align-items:center;justify-content:center;margin-bottom:12px;border:2px dashed var(--admin-border);">
          <span style="color:var(--admin-text-muted);font-size:13px;">No image set</span>
        </div>
        <?php endif; ?>
        <div>
          <label style="display:block;font-size:13px;font-weight:600;margin-bottom:5px;">Image URL <span style="color:var(--admin-text-muted);font-weight:400;">— paste full URL from Media Library</span></label>
          <input type="url" name="image_url" class="admin-input" value="<?= mval($moduleData,'image_url') ?>" placeholder="https://...">
        </div>
        <div style="margin-top:10px;">
          <a href="/techaasvik_admin/media" target="_blank" class="admin-btn admin-btn-ghost admin-btn-sm" style="width:100%;justify-content:center;">📁 Open Media Library ↗</a>
        </div>
      </div>

      <!-- Access Control -->
      <div class="admin-table-wrapper" style="padding:20px;">
        <h3 style="font-size:14px;font-weight:700;margin-bottom:14px;">🔐 Access Control</h3>
        <div style="display:flex;flex-direction:column;gap:10px;">
          <label style="display:flex;align-items:center;gap:10px;cursor:pointer;padding:10px;border-radius:8px;border:1.5px solid <?= empty($moduleData['is_free']) || $moduleData['is_free']==='0' ? 'var(--admin-border)' : 'var(--admin-primary)' ?>;background:<?= empty($moduleData['is_free']) || $moduleData['is_free']==='0' ? 'transparent' : 'rgba(99,102,241,0.05)' ?>;">
            <input type="radio" name="is_free" value="1" <?= ($moduleData['is_free'] ?? '1') === '1' ? 'checked' : '' ?>>
            <div>
              <div style="font-size:13px;font-weight:700;color:#059669;">🔓 Free Access</div>
              <div style="font-size:11px;color:var(--admin-text-muted);">Visible without payment</div>
            </div>
          </label>
          <label style="display:flex;align-items:center;gap:10px;cursor:pointer;padding:10px;border-radius:8px;border:1.5px solid <?= ($moduleData['is_free'] ?? '1') === '0' ? 'var(--admin-primary)' : 'var(--admin-border)' ?>;background:<?= ($moduleData['is_free'] ?? '1') === '0' ? 'rgba(99,102,241,0.05)' : 'transparent' ?>;">
            <input type="radio" name="is_free" value="0" <?= ($moduleData['is_free'] ?? '1') === '0' ? 'checked' : '' ?>>
            <div>
              <div style="font-size:13px;font-weight:700;color:#6366f1;">🔒 Paid Only</div>
              <div style="font-size:11px;color:var(--admin-text-muted);">Requires payment to access</div>
            </div>
          </label>
        </div>
      </div>

      <!-- Quick Links -->
      <div class="admin-table-wrapper" style="padding:20px;">
        <h3 style="font-size:14px;font-weight:700;margin-bottom:14px;">🔗 Quick Actions</h3>
        <div style="display:flex;flex-direction:column;gap:8px;">
          <a href="/courses/ai-marketing-course/learn/<?= $num ?>/1" target="_blank" class="admin-btn admin-btn-ghost admin-btn-sm" style="justify-content:center;">👁 Preview Module →</a>
          <a href="/courses/ai-marketing-course" target="_blank" class="admin-btn admin-btn-ghost admin-btn-sm" style="justify-content:center;">📄 Course Page →</a>
        </div>
      </div>

      <!-- Save -->
      <button type="submit" class="admin-btn admin-btn-primary" style="width:100%;justify-content:center;padding:14px;font-size:15px;">
        💾 Save Module <?= $num ?>
      </button>
    </div>
  </div>
</form>

<!-- Navigation -->
<div style="display:flex;justify-content:space-between;margin-top:24px;padding-top:24px;border-top:1px solid var(--admin-border);">
  <?php if ($num > 1): ?>
  <a href="/techaasvik_admin/course/modules/<?= $num-1 ?>/edit" class="admin-btn admin-btn-ghost">‹ Module <?= $num-1 ?></a>
  <?php else: ?><span></span><?php endif; ?>
  <?php if ($num < 10): ?>
  <a href="/techaasvik_admin/course/modules/<?= $num+1 ?>/edit" class="admin-btn admin-btn-ghost">Module <?= $num+1 ?> ›</a>
  <?php endif; ?>
</div>
