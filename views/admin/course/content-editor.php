<?php
/**
 * Course Content Editor — split-pane AJAX editor
 * Left: Module→Submodule tree | Right: Edit form
 */
use Core\Auth;
Auth::startSession();
$csrfToken  = Auth::csrfToken();
$subTitles  = $subTitles  ?? [];
$moduleNames= $moduleNames?? [];
$hasContent = $hasContent ?? [];
$slug       = $slug       ?? 'ai-marketing-course';
?>

<style>
.ce-wrap { display:grid; grid-template-columns:260px 1fr; gap:0; min-height:calc(100vh - 130px); }
.ce-tree { background:var(--admin-bg-elevated); border-right:1px solid var(--admin-border); overflow-y:auto; height:calc(100vh - 130px); position:sticky; top:0; }
.ce-panel { overflow-y:auto; padding:0; }

/* Tree */
.ce-mod-header { display:flex; align-items:center; gap:8px; padding:10px 14px; cursor:pointer; border-bottom:1px solid var(--admin-border); font-size:12px; font-weight:700; user-select:none; transition:background 0.15s; }
.ce-mod-header:hover { background:rgba(99,102,241,0.06); }
.ce-mod-header.active-mod { background:rgba(99,102,241,0.1); color:var(--admin-primary); }
.ce-mod-num { width:22px; height:22px; border-radius:50%; background:var(--admin-border); display:flex; align-items:center; justify-content:center; font-size:10px; font-weight:800; flex-shrink:0; }
.ce-mod-num.has { background:linear-gradient(135deg,#6366f1,#8b5cf6); color:#fff; }
.ce-subs { display:none; }
.ce-mod-header.open ~ .ce-subs { display:block; }
.ce-sub-item { display:flex; align-items:center; gap:8px; padding:8px 14px 8px 36px; font-size:11px; cursor:pointer; transition:background 0.15s; border-bottom:1px solid rgba(255,255,255,0.03); }
.ce-sub-item:hover { background:rgba(99,102,241,0.06); }
.ce-sub-item.active { background:rgba(99,102,241,0.12); color:var(--admin-primary); font-weight:700; }
.ce-dot { width:8px; height:8px; border-radius:50%; flex-shrink:0; }
.ce-dot.db { background:#34d399; }
.ce-dot.default { background:var(--admin-border); }
.ce-dot.quiz { background:#f59e0b; }
.ce-chevron { font-size:9px; color:var(--admin-text-muted); margin-left:auto; transition:transform 0.2s; }
.ce-mod-header.open .ce-chevron { transform:rotate(90deg); }

/* Edit form */
.ce-edit { padding:24px; }
.ce-placeholder { display:flex; flex-direction:column; align-items:center; justify-content:center; height:400px; color:var(--admin-text-muted); gap:12px; }
.ce-section-title { font-size:12px; font-weight:800; color:var(--admin-text-muted); text-transform:uppercase; letter-spacing:0.5px; margin:20px 0 10px; }
.ce-field { margin-bottom:14px; }
.ce-field label { display:block; font-size:12px; font-weight:700; margin-bottom:5px; }
.ce-field .hint { font-size:11px; color:var(--admin-text-muted); margin-top:3px; }

/* HTML toolbar */
.html-toolbar { display:flex; gap:4px; flex-wrap:wrap; margin-bottom:6px; }
.html-btn { padding:4px 8px; background:var(--admin-bg-elevated); border:1px solid var(--admin-border); border-radius:4px; font-size:11px; font-weight:700; cursor:pointer; color:var(--admin-text); transition:background 0.1s; }
.html-btn:hover { background:rgba(99,102,241,0.1); }
.ce-html-area { font-family:monospace; font-size:12px; resize:vertical; min-height:180px; }

/* Key points + resources */
.ce-list-item { display:flex; align-items:center; gap:8px; margin-bottom:6px; }
.ce-list-item input { flex:1; }
.ce-remove-btn { width:28px; height:28px; background:#ef4444; color:#fff; border:none; border-radius:6px; cursor:pointer; font-size:14px; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
.ce-add-btn { background:transparent; border:1px dashed var(--admin-border); color:var(--admin-text-muted); padding:6px 12px; border-radius:6px; font-size:12px; cursor:pointer; width:100%; margin-top:4px; transition:all 0.2s; }
.ce-add-btn:hover { border-color:var(--admin-primary); color:var(--admin-primary); }

/* Preview image */
.img-preview { width:100%; height:100px; object-fit:cover; border-radius:8px; margin-top:8px; display:none; border:1px solid var(--admin-border); }

/* Status bar */
.ce-status { position:sticky; bottom:0; background:var(--admin-bg); border-top:1px solid var(--admin-border); padding:12px 24px; display:flex; align-items:center; gap:12px; z-index:10; }
.ce-save-indicator { font-size:12px; color:var(--admin-text-muted); }
.ce-save-indicator.unsaved { color:#f59e0b; }
.ce-save-indicator.saved { color:#34d399; }
</style>

<div class="admin-page-header">
  <div>
    <h1 class="admin-page-title">✏️ Course Content Editor</h1>
    <p class="admin-page-subtitle">Select a lesson from the sidebar to edit its content, images, video, and more</p>
  </div>
  <div style="display:flex;gap:10px;">
    <a href="/techaasvik_admin/course/modules" class="admin-btn admin-btn-ghost">📚 Modules</a>
    <a href="/techaasvik_admin/course" class="admin-btn admin-btn-ghost">Dashboard →</a>
  </div>
</div>

<!-- Legend -->
<div style="display:flex;gap:16px;align-items:center;margin-bottom:16px;font-size:12px;color:var(--admin-text-muted);">
  <span><span style="display:inline-block;width:8px;height:8px;border-radius:50%;background:#34d399;margin-right:4px;"></span>Content saved in DB</span>
  <span><span style="display:inline-block;width:8px;height:8px;border-radius:50%;background:var(--admin-border);margin-right:4px;"></span>Using hardcoded default</span>
  <span><span style="display:inline-block;width:8px;height:8px;border-radius:50%;background:#f59e0b;margin-right:4px;"></span>Quiz (content auto-rendered)</span>
</div>

<div class="admin-table-wrapper" style="padding:0;overflow:hidden;">
<div class="ce-wrap" id="ceWrap">

  <!-- LEFT TREE -->
  <nav class="ce-tree" id="ceTree">
    <?php foreach ($moduleNames as $mNum => $mName): ?>
    <?php
      $allSubs = $subTitles[$mNum] ?? [];
      $dbCount = 0;
      foreach ($allSubs as $sIdx => $sTitle) {
          $sNum = $sIdx + 1;
          $key  = $mNum . '-' . $mNum . '-' . $sNum; // hasContent key: "module_num-submodule_key"
          // hasContent format: "modulenum-submodule_key" where submodule_key = "N-S"
          $contentKey = $mNum . '-' . $mNum . '-' . $sNum;
          if (isset($hasContent[$mNum . '-' . $mNum . '-' . $sNum])) $dbCount++;
      }
      // Fix: hasContent stores as "module_num-submodule_key" = "1-1-1" but key is "module_num" . "-" . "submodule_key" where submodule_key is "1-1"
      // Actually in controller: $hasContent[$r['module_num'] . '-' . $r['submodule_key']] = true;
      // submodule_key = "1-1" so full key = "1-1-1" for module 1 lesson 1
      // Let me recheck - module_num=1, submodule_key="1-1" → hasContent["1-1-1"]
      // That's wrong. Let me fix: it should be hasContent["1"]["1-1"]... No wait
      // hasContent[$r['module_num'] . '-' . $r['submodule_key']] → "1" . "-" . "1-1" = "1-1-1"
      // Clicking sub item: submodule_key = moduleNum . '-' . sNum = "1-1"
      // So to check: $mNum . '-' . $subKey where $subKey = $mNum . '-' . $sNum
      $dbCount = 0;
      foreach ($allSubs as $sIdx => $sTitle) {
          $sNum   = $sIdx + 1;
          $subKey = $mNum . '-' . $sNum; // "1-1", "1-2" etc
          $hKey   = $mNum . '-' . $subKey; // "1-1-1" matches controller output
          if (isset($hasContent[$hKey])) $dbCount++;
      }
    ?>
    <div class="ce-mod-header <?= $mNum === 1 ? 'open' : '' ?>" onclick="toggleMod(this)" data-mod="<?= $mNum ?>">
      <div class="ce-mod-num <?= $dbCount > 0 ? 'has' : '' ?>"><?= $mNum ?></div>
      <div style="flex:1;line-height:1.3;"><?= htmlspecialchars(mb_substr($mName, 0, 40)) ?></div>
      <div style="font-size:10px;color:var(--admin-text-muted);"><?= $dbCount ?>/4</div>
      <span class="ce-chevron">▶</span>
    </div>
    <div class="ce-subs">
      <?php foreach ($allSubs as $sIdx => $sTitle): ?>
      <?php
        $sNum   = $sIdx + 1;
        $subKey = $mNum . '-' . $sNum;
        $isQuiz = ($sIdx === 4);
        $hKey   = $mNum . '-' . $subKey;
        $inDb   = isset($hasContent[$hKey]);
      ?>
      <div class="ce-sub-item <?= $isQuiz ? 'quiz-sub' : '' ?>"
           onclick="loadSubmodule(<?= $mNum ?>, '<?= $subKey ?>')"
           id="sub-<?= $mNum ?>-<?= $sNum ?>"
           data-mod="<?= $mNum ?>" data-sub="<?= $subKey ?>">
        <div class="ce-dot <?= $isQuiz ? 'quiz' : ($inDb ? 'db' : 'default') ?>"></div>
        <span style="flex:1;">
          <?= $isQuiz ? '📝 ' : '📖 ' ?><?= htmlspecialchars($sTitle) ?>
        </span>
      </div>
      <?php endforeach; ?>
    </div>
    <?php endforeach; ?>
  </nav>

  <!-- RIGHT PANEL -->
  <div class="ce-panel" id="cePanel">
    <div class="ce-placeholder" id="cePlaceholder">
      <div style="font-size:48px;">✏️</div>
      <div style="font-size:15px;font-weight:700;">Select a lesson from the sidebar</div>
      <div style="font-size:13px;">Click any lesson to edit its content</div>
    </div>

    <!-- EDIT FORM (hidden until submodule selected) -->
    <form id="ceForm" style="display:none;" onsubmit="return false;">
      <input type="hidden" id="ceHiddenMod" name="_mod">
      <input type="hidden" id="ceHiddenSub" name="_sub">
      <input type="hidden" id="csrfInput" name="_csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">

      <div class="ce-edit">

        <!-- Header -->
        <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:20px;gap:12px;">
          <div>
            <div id="ceModLabel" style="font-size:11px;color:var(--admin-text-muted);margin-bottom:4px;"></div>
            <h2 id="ceSubLabel" style="font-size:18px;font-weight:800;margin:0;color:var(--admin-text);"></h2>
          </div>
          <div style="display:flex;gap:8px;flex-shrink:0;">
            <a id="cePreviewLink" href="#" target="_blank" class="admin-btn admin-btn-ghost admin-btn-sm">👁 Preview →</a>
            <button type="button" onclick="resetContent()" class="admin-btn admin-btn-ghost admin-btn-sm" style="color:#ef4444;" id="ceResetBtn" style="display:none;">🗑 Reset to Default</button>
          </div>
        </div>

        <!-- DB status badge -->
        <div id="ceDbStatus" style="margin-bottom:20px;"></div>

        <!-- ── BASIC INFO ─────────────────────────────────── -->
        <div class="ce-section-title">📋 Basic Info</div>
        <div style="display:grid;grid-template-columns:1fr 180px;gap:12px;">
          <div class="ce-field">
            <label>Title Override <span style="color:var(--admin-text-muted);font-weight:400;">— leave blank to use default</span></label>
            <input type="text" id="f_content_title" name="content_title" class="admin-input" placeholder="Leave blank = use default title">
          </div>
          <div class="ce-field">
            <label>Duration</label>
            <input type="text" id="f_duration_text" name="duration_text" class="admin-input" placeholder="12 min">
          </div>
        </div>

        <!-- ── IMAGE ─────────────────────────────────────── -->
        <div class="ce-section-title">🖼️ Image / Banner</div>
        <div class="ce-field">
          <label>Image URL <span style="color:var(--admin-text-muted);font-weight:400;">— paste URL from Media Library or external</span></label>
          <div style="display:flex;gap:8px;">
            <input type="url" id="f_image_url" name="image_url" class="admin-input" placeholder="https://..." style="flex:1;" oninput="previewImg()">
            <a href="/techaasvik_admin/media" target="_blank" class="admin-btn admin-btn-ghost admin-btn-sm" style="white-space:nowrap;">📁 Media</a>
          </div>
          <img id="imgPreview" src="" alt="Preview" class="img-preview">
        </div>

        <!-- ── VIDEO ─────────────────────────────────────── -->
        <div class="ce-section-title">🎬 Video</div>
        <div class="ce-field">
          <label>Video URL <span style="color:var(--admin-text-muted);font-weight:400;">— YouTube, Vimeo, or direct MP4</span></label>
          <input type="url" id="f_video_url" name="video_url" class="admin-input" placeholder="https://www.youtube.com/watch?v=...">
        </div>
        <div class="ce-field">
          <label>Custom Embed Code <span style="color:var(--admin-text-muted);font-weight:400;">— paste full &lt;iframe&gt; to override URL player</span></label>
          <textarea id="f_video_embed" name="video_embed" class="admin-input" rows="2" style="font-family:monospace;font-size:11px;resize:vertical;" placeholder='&lt;iframe src="..."&gt;&lt;/iframe&gt;'></textarea>
        </div>

        <!-- ── INFOGRAPHIC / KEY POINTS ───────────────────── -->
        <div class="ce-section-title">⚡ Visual Card (Key Points Infographic)</div>
        <div class="ce-field">
          <label>Card Title</label>
          <input type="text" id="f_infographic_title" name="infographic_title" class="admin-input" placeholder="The AI Marketing Shift">
        </div>
        <div class="ce-field">
          <label>Key Points <span style="color:var(--admin-text-muted);font-weight:400;">— shown as numbered visual steps (4-6 recommended)</span></label>
          <div id="keyPointsList"></div>
          <button type="button" class="ce-add-btn" onclick="addKeyPoint('')">+ Add Key Point</button>
        </div>

        <!-- ── CONTENT BODY ──────────────────────────────── -->
        <div class="ce-section-title">📝 Content Body (HTML)</div>
        <div class="ce-field">
          <label>Main Content <span style="color:var(--admin-text-muted);font-weight:400;">— supports full HTML markup</span></label>
          <div class="html-toolbar">
            <button type="button" class="html-btn" onclick="wrapTag('b')"><b>B</b></button>
            <button type="button" class="html-btn" onclick="wrapTag('i')"><i>I</i></button>
            <button type="button" class="html-btn" onclick="wrapTag('strong')">Strong</button>
            <button type="button" class="html-btn" onclick="insertHtml('&lt;h2&gt;Heading&lt;/h2&gt;\n')">H2</button>
            <button type="button" class="html-btn" onclick="insertHtml('&lt;h3&gt;Subheading&lt;/h3&gt;\n')">H3</button>
            <button type="button" class="html-btn" onclick="insertHtml('&lt;p&gt;Paragraph&lt;/p&gt;\n')">P</button>
            <button type="button" class="html-btn" onclick="insertHtml('&lt;ul&gt;\n  &lt;li&gt;Item 1&lt;/li&gt;\n  &lt;li&gt;Item 2&lt;/li&gt;\n&lt;/ul&gt;\n')">UL</button>
            <button type="button" class="html-btn" onclick="insertHtml('&lt;ol&gt;\n  &lt;li&gt;Step 1&lt;/li&gt;\n  &lt;li&gt;Step 2&lt;/li&gt;\n&lt;/ol&gt;\n')">OL</button>
            <button type="button" class="html-btn" onclick="insertHtml('&lt;blockquote&gt;Quote&lt;/blockquote&gt;\n')">&rdquo;</button>
            <button type="button" class="html-btn" onclick="insertHtml('&lt;a href=&quot;URL&quot;&gt;Link text&lt;/a&gt;')">Link</button>
            <button type="button" class="html-btn" onclick="insertHtml('&lt;img src=&quot;URL&quot; alt=&quot;desc&quot; style=&quot;max-width:100%;&quot;&gt;\n')">IMG</button>
            <button type="button" class="html-btn" onclick="insertHtml('&lt;code&gt;code&lt;/code&gt;')">Code</button>
            <button type="button" class="html-btn" onclick="insertHtml('&lt;hr&gt;\n')">HR</button>
          </div>
          <textarea id="f_content_html" name="content_html" class="admin-input ce-html-area" rows="12" placeholder="Enter lesson content as HTML...&#10;&#10;Example:&#10;&lt;p&gt;Welcome to Module 1...&lt;/p&gt;&#10;&lt;h2&gt;What You'll Learn&lt;/h2&gt;&#10;&lt;ul&gt;&#10;  &lt;li&gt;Key point 1&lt;/li&gt;&#10;&lt;/ul&gt;" oninput="setUnsaved()"></textarea>
          <div class="hint">HTML is rendered directly in the course player — use standard HTML tags. Tailwind classes are not available.</div>
        </div>

        <!-- ── RESOURCES ─────────────────────────────────── -->
        <div class="ce-section-title">📎 Resources & Downloads</div>
        <div class="ce-field">
          <label>Downloadable Resources <span style="color:var(--admin-text-muted);font-weight:400;">— links shown below lesson content</span></label>
          <div id="resourcesList"></div>
          <button type="button" class="ce-add-btn" onclick="addResource('','')">+ Add Resource</button>
        </div>

      </div>

      <!-- STICKY BOTTOM BAR -->
      <div class="ce-status">
        <button type="button" onclick="saveContent()" class="admin-btn admin-btn-primary" id="ceSaveBtn">
          💾 Save Changes
        </button>
        <span class="ce-save-indicator" id="ceSaveIndicator">Select a lesson to start editing</span>
        <span style="flex:1;"></span>
        <button type="button" onclick="resetContent()" id="ceResetBtn2" class="admin-btn admin-btn-ghost admin-btn-sm" style="color:#ef4444;display:none;">🗑 Reset to Default</button>
      </div>
    </form>

  </div><!-- /ce-panel -->
</div>
</div><!-- /admin-table-wrapper -->

<script>
const CSRF = '<?= htmlspecialchars($csrfToken) ?>';
const SLUG = '<?= $slug ?>';
let currentMod = null, currentSub = null, hasDbContent = false, isDirty = false;

// ── Tree ───────────────────────────────────────────
function toggleMod(el) {
  el.classList.toggle('open');
}

function loadSubmodule(mod, sub) {
  if (isDirty && !confirm('You have unsaved changes. Discard and load new lesson?')) return;

  // Update active state
  document.querySelectorAll('.ce-sub-item').forEach(el => el.classList.remove('active'));
  const el = document.getElementById('sub-' + mod + '-' + sub.split('-')[1]);
  if (el) el.classList.add('active');

  currentMod = mod;
  currentSub = sub;
  isDirty = false;

  // Show loading state
  document.getElementById('cePlaceholder').style.display = 'none';
  document.getElementById('ceForm').style.display = 'block';
  document.getElementById('ceSaveIndicator').textContent = 'Loading...';
  document.getElementById('ceSaveIndicator').className = 'ce-save-indicator';

  fetch(`/techaasvik_admin/course/content-editor/${mod}/${sub}/load`, {
    headers: { 'X-Requested-With': 'XMLHttpRequest' }
  })
  .then(r => r.json())
  .then(data => populateForm(data))
  .catch(e => {
    document.getElementById('ceSaveIndicator').textContent = 'Error loading data';
    console.error(e);
  });
}

function populateForm(d) {
  document.getElementById('ceHiddenMod').value = d.module_num;
  document.getElementById('ceHiddenSub').value = d.submodule_key;
  document.getElementById('ceModLabel').textContent = 'Module ' + d.module_num + ' · ' + (d.module_name || '');
  document.getElementById('ceSubLabel').textContent = d.content_title || d.default_title;
  document.getElementById('cePreviewLink').href = '/courses/' + SLUG + '/learn/' + d.module_num + '/' + d.submodule_key.split('-')[1];

  // Fields
  document.getElementById('f_content_title').value    = d.content_title    || '';
  document.getElementById('f_duration_text').value    = d.duration_text    || '';
  document.getElementById('f_image_url').value         = d.image_url        || '';
  document.getElementById('f_video_url').value         = d.video_url        || '';
  document.getElementById('f_video_embed').value       = d.video_embed      || '';
  document.getElementById('f_infographic_title').value = d.infographic_title || '';
  document.getElementById('f_content_html').value      = d.content_html     || '';
  previewImg();

  // Key points
  const kpList = document.getElementById('keyPointsList');
  kpList.innerHTML = '';
  (d.key_points || []).forEach(p => addKeyPoint(p));

  // Resources
  const resList = document.getElementById('resourcesList');
  resList.innerHTML = '';
  (d.resources || []).forEach(r => addResource(r.name || '', r.url || ''));

  // Status
  hasDbContent = d.exists;
  updateStatus(d.exists);
  isDirty = false;

  // Reset button visibility
  document.getElementById('ceResetBtn').style.display  = d.exists ? '' : 'none';
  document.getElementById('ceResetBtn2').style.display = d.exists ? '' : 'none';
}

function updateStatus(inDb) {
  const el = document.getElementById('ceDbStatus');
  if (inDb) {
    el.innerHTML = '<div style="display:inline-flex;align-items:center;gap:8px;padding:6px 12px;background:rgba(52,211,153,0.08);border:1px solid rgba(52,211,153,0.2);border-radius:6px;font-size:12px;color:#34d399;"><span>✅</span>Custom content saved in DB — overrides hardcoded default</div>';
  } else {
    el.innerHTML = '<div style="display:inline-flex;align-items:center;gap:8px;padding:6px 12px;background:var(--admin-bg-elevated);border:1px solid var(--admin-border);border-radius:6px;font-size:12px;color:var(--admin-text-muted);"><span>○</span>No custom content — using hardcoded default. Save to override.</div>';
  }
}

function setUnsaved() {
  isDirty = true;
  const el = document.getElementById('ceSaveIndicator');
  el.textContent = '● Unsaved changes';
  el.className = 'ce-save-indicator unsaved';
}

// ── Save ───────────────────────────────────────────
function saveContent() {
  if (!currentMod || !currentSub) return;

  const btn = document.getElementById('ceSaveBtn');
  btn.textContent = '⏳ Saving...';
  btn.disabled = true;

  const formData = new FormData();
  formData.append('_csrf_token',      CSRF);
  formData.append('content_title',    document.getElementById('f_content_title').value);
  formData.append('duration_text',    document.getElementById('f_duration_text').value);
  formData.append('image_url',         document.getElementById('f_image_url').value);
  formData.append('video_url',         document.getElementById('f_video_url').value);
  formData.append('video_embed',       document.getElementById('f_video_embed').value);
  formData.append('infographic_title', document.getElementById('f_infographic_title').value);
  formData.append('content_html',      document.getElementById('f_content_html').value);

  // Key points as JSON
  const kps = [...document.querySelectorAll('.kp-input')].map(i => i.value.trim()).filter(Boolean);
  formData.append('key_points', JSON.stringify(kps));

  // Resources as JSON
  const resources = [];
  document.querySelectorAll('.res-row').forEach(row => {
    const name = row.querySelector('.res-name').value.trim();
    const url  = row.querySelector('.res-url').value.trim();
    if (name) resources.push({name, url});
  });
  formData.append('resources', JSON.stringify(resources));

  fetch(`/techaasvik_admin/course/content-editor/${currentMod}/${currentSub}/save`, {
    method: 'POST', body: formData, headers: { 'X-Requested-With': 'XMLHttpRequest' }
  })
  .then(r => r.json())
  .then(data => {
    btn.textContent = '💾 Save Changes';
    btn.disabled = false;
    if (data.success) {
      isDirty = false;
      hasDbContent = true;
      const ind = document.getElementById('ceSaveIndicator');
      ind.textContent = '✓ ' + (data.message || 'Saved!');
      ind.className = 'ce-save-indicator saved';
      // Update tree dot to green
      const dotEl = document.querySelector(`#sub-${currentMod}-${currentSub.split('-')[1]} .ce-dot`);
      if (dotEl) { dotEl.className = 'ce-dot db'; }
      updateStatus(true);
      document.getElementById('ceResetBtn').style.display  = '';
      document.getElementById('ceResetBtn2').style.display = '';
      setTimeout(() => { ind.textContent = 'All changes saved'; }, 3000);
    } else {
      alert('Error: ' + (data.error || 'Save failed'));
    }
  })
  .catch(e => {
    btn.textContent = '💾 Save Changes';
    btn.disabled = false;
    alert('Network error: ' + e.message);
  });
}

function resetContent() {
  if (!currentMod || !currentSub) return;
  if (!confirm('Reset this lesson to its hardcoded default content? This will delete your custom content.')) return;

  fetch(`/techaasvik_admin/course/content-editor/${currentMod}/${currentSub}/delete`, {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-Requested-With': 'XMLHttpRequest' },
    body: '_csrf_token=' + encodeURIComponent(CSRF)
  })
  .then(r => r.json())
  .then(data => {
    if (data.success) {
      hasDbContent = false;
      isDirty = false;
      // Update tree dot
      const dotEl = document.querySelector(`#sub-${currentMod}-${currentSub.split('-')[1]} .ce-dot`);
      if (dotEl) { dotEl.className = 'ce-dot default'; }
      // Reload form
      loadSubmodule(currentMod, currentSub);
    }
  });
}

// ── Key Points ─────────────────────────────────────
function addKeyPoint(value) {
  const list = document.getElementById('keyPointsList');
  const div  = document.createElement('div');
  div.className = 'ce-list-item';
  div.innerHTML = `<input type="text" class="admin-input kp-input" value="${escHtml(value)}" placeholder="Key point..." oninput="setUnsaved()">
                   <button type="button" class="ce-remove-btn" onclick="this.parentNode.remove();setUnsaved()">×</button>`;
  list.appendChild(div);
  setUnsaved();
}

// ── Resources ──────────────────────────────────────
function addResource(name, url) {
  const list = document.getElementById('resourcesList');
  const div  = document.createElement('div');
  div.className = 'ce-list-item res-row';
  div.innerHTML = `<input type="text" class="admin-input res-name" value="${escHtml(name)}" placeholder="Resource name..." style="flex:2;" oninput="setUnsaved()">
                   <input type="url"  class="admin-input res-url"  value="${escHtml(url)}"  placeholder="https://..." style="flex:3;" oninput="setUnsaved()">
                   <button type="button" class="ce-remove-btn" onclick="this.parentNode.remove();setUnsaved()">×</button>`;
  list.appendChild(div);
  setUnsaved();
}

// ── HTML Editor helpers ─────────────────────────────
function wrapTag(tag) {
  const ta = document.getElementById('f_content_html');
  const start = ta.selectionStart, end = ta.selectionEnd;
  const sel   = ta.value.substring(start, end);
  const wrap  = `<${tag}>${sel || 'text'}</${tag}>`;
  ta.value    = ta.value.substring(0, start) + wrap + ta.value.substring(end);
  setUnsaved();
}
function insertHtml(html) {
  const ta  = document.getElementById('f_content_html');
  const pos = ta.selectionStart;
  const decoded = html.replace(/&lt;/g,'<').replace(/&gt;/g,'>').replace(/&amp;/g,'&').replace(/&quot;/g,'"');
  ta.value  = ta.value.substring(0, pos) + decoded + ta.value.substring(pos);
  setUnsaved();
}

// ── Image preview ───────────────────────────────────
function previewImg() {
  const url = document.getElementById('f_image_url').value.trim();
  const img = document.getElementById('imgPreview');
  if (url) { img.src = url; img.style.display = 'block'; }
  else      { img.style.display = 'none'; }
  setUnsaved();
}

// ── Utility ────────────────────────────────────────
function escHtml(s) {
  return (s||'').replace(/&/g,'&amp;').replace(/"/g,'&quot;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
}

// Warn on page leave if dirty
window.addEventListener('beforeunload', e => {
  if (isDirty) { e.preventDefault(); e.returnValue = ''; }
});

// Auto-open first module
document.addEventListener('DOMContentLoaded', () => {
  const first = document.querySelector('.ce-mod-header');
  if (first && !first.classList.contains('open')) first.classList.add('open');
});
</script>
