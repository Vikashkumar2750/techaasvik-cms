<?php
/**
 * Unified Course Editor — WordPress-style
 * 3 panes: Left nav tree | Center editor | Top tabs
 * Merges Module Settings + Submodule Content in one place
 */
use Core\Auth;
Auth::startSession();
$csrfToken  = Auth::csrfToken();
$subTitles  = $subTitles  ?? [];
$moduleNames= $moduleNames ?? [];
$hasContent = $hasContent ?? [];
$moduleSettings = $moduleSettings ?? [];
$slug       = $slug ?? 'ai-marketing-course';

// Hardcoded default key-points (so editor can show existing content)
$defaultKeyPoints = [
    '1-1' => ['AI answers queries directly', 'Search intent changes', 'Zero-click searches rise', 'Content must be AI-readable', 'Human judgment > rote tasks'],
    '1-2' => ['Content generation speed 10x', 'Personalisation at scale', 'Research in seconds', 'Ad copy testing automated', 'Predictive insights available'],
    '1-3' => ['Featured snippets → AI Overviews', 'Keywords → Entities & topics', 'Links → Citations in AI answers', 'Long-form → Answer-first content', 'SEO + GEO + AEO combined'],
    '1-4' => ['AI agents handle multi-step tasks', 'Hyper-personalised email at scale', 'Predictive lead scoring', 'Dynamic content generation', 'Always-on campaign optimisation'],
    '2-1' => ['Understand model limitations', 'Set role context first', 'Be specific with your ask', 'Iterate on outputs', 'Fact-check everything'],
    '2-2' => ['Goal → what do you need?', 'Role → who is ChatGPT playing?', 'Audience → who reads this?', 'Constraints → what to avoid?', 'Format → how should it look?'],
    '2-3' => ['C — Context', 'R — Role', 'A — Action', 'F — Format', 'T — Tone/Constraints'],
    '2-4' => ['Document your best prompts', 'Create prompt templates', 'Add variables for reuse', 'Test across content types', 'Build a prompt library'],
    '3-1' => ['Research before creating', 'Know your audience deeply', 'Map their journey', 'Find real pain points', 'Use AI to synthesise data'],
    '3-2' => ['Interview customers', 'Mine online reviews', 'Analyse competitor content', 'Map content gaps', 'Find positioning opportunities'],
    '3-3' => ['Amazon & G2 reviews', 'Reddit & Quora threads', 'YouTube comments', 'Trustpilot feedback', 'Extract VOC language'],
    '3-4' => ['Define your differentiation', 'Identify target segment', 'Map key messages', 'Proof points & evidence', 'Create positioning statement'],
    '4-1' => ['Seed keyword brainstorm', 'Intent classification', 'Volume vs. difficulty', 'Long-tail clusters', 'Topical map creation'],
    '4-2' => ['1 pillar page per topic', '5–10 cluster articles each', 'Internal linking structure', 'Cover every subtopic', 'Update quarterly'],
    '4-3' => ['Target keyword + intent', 'Outline with H2/H3s', 'Must-answer questions', 'Entities to include', 'Competitor gaps to cover'],
    '4-4' => ['Identify declining pages', 'Run content audit', 'Update stats & examples', 'Add new sections', 'Improve internal links'],
    '5-1' => ['Create content templates', 'Use AI for first drafts', 'Human editing & review', 'Fact-check all claims', 'Publish + distribute'],
    '5-2' => ['Experience signals', 'Expert author credentials', 'Authoritative sources cited', 'Trustworthy site signals', 'Consistent brand tone'],
    '5-3' => ['Blog → LinkedIn thread', 'Blog → short-form video', 'Podcast → show notes', 'Video → Twitter thread', 'Report → infographic'],
    '5-4' => ['Week 1: Pillar content', 'Week 2: Social repurpose', 'Week 3: Email newsletter', 'Week 4: SEO update + analyse', 'Repeat monthly'],
    '6-1' => ['AI reads structured content', 'Clear, direct answers win', 'Citations matter', 'Brand mentions across web', 'Schema markup essential'],
    '6-2' => ['Answer questions directly', 'Use FAQ structure', 'Add structured data', 'Build topical authority', 'Get cited by others'],
    '6-3' => ['Entity consistency across web', 'Wikipedia + Wikidata presence', 'Schema.org markup', 'Knowledge Panel optimisation', 'Brand search volume'],
    '6-4' => ['Identify "People Also Ask"', 'Write direct answers (40–60 words)', 'Add supporting context', 'Use ordered & unordered lists', 'Monitor AI answer appearances'],
    '7-1' => ['Provide rich conversion data', 'Upload diverse creatives', 'Set smart bidding goals', 'Use audience signals', 'Monitor asset performance'],
    '7-2' => ['Connect product catalogue', 'Let AI find best audiences', 'Test multiple creatives', 'Optimise for purchase events', 'Scale winning ad sets'],
    '7-3' => ['Maximise conversions bidding', 'Target CPA / Target ROAS', 'Feed quality conversion data', 'Avoid over-constraining', 'Allow learning period (2 weeks)'],
    '7-4' => ['Identify winning campaigns', 'Increase budget 15–20%/week', 'Monitor efficiency closely', 'Expand to new audiences', 'Test new creative batches'],
    '8-1' => ['Run heatmap analysis', 'Test landing page variations', 'AI-generated copy variants', 'Form optimisation', 'Mobile UX improvements'],
    '8-2' => ['Define ICP criteria', 'Score by engagement signals', 'AI predicts conversion probability', 'Segment by score', 'Personalise follow-up'],
    '8-3' => ['Trigger: new lead/event', 'Enrich data via APIs', 'AI step: classify/score', 'Route to correct sequence', 'Human approval if needed'],
    '8-4' => ['Welcome email (instant)', 'Value email (day 2)', 'Education email (day 4)', 'Social proof (day 7)', 'Offer email (day 10)'],
    '9-1' => ['GA4 property setup', 'Define key events', 'Enable Enhanced Measurement', 'Connect Search Console', 'Set up conversions'],
    '9-2' => ['Last-click (legacy)', 'First-click (awareness)', 'Linear (equal credit)', 'Time-decay (recent weighted)', 'Data-driven (AI-powered) ✓'],
    '9-3' => ['CAC = Total spend ÷ new customers', 'LTV = Avg order × frequency × lifetime', 'LTV:CAC ratio goal ≥ 3:1', 'North Star = 1 key growth metric', 'Review monthly'],
    '9-4' => ['What is the symptom?', 'Which funnel stage breaks?', 'Root cause analysis', 'Hypothesis & test', 'Measure → iterate'],
    '10-1'=> ['Research system', 'Content system', 'Distribution system', 'Paid media system', 'Analytics system'],
    '10-2'=> ['Pick a real business', 'Run full market research', 'Build content strategy', 'Create ad strategy', 'Set up automations'],
    '10-3'=> ['Verify all AI outputs', 'Check for bias', 'Maintain human oversight', 'Disclose AI use', 'Protect user data'],
    '10-4'=> ['AI Marketing Specialist', 'Growth Marketer', 'Marketing Technologist', 'CMO / Head of Growth', 'AI Marketing Consultant'],
];
?>
<!DOCTYPE html>
<html><!-- This is a full-page view inside admin layout -->
<style>
/* ── Course Editor Layout ────────────────────────── */
.ce-root { display:flex; height:calc(100vh - 56px); overflow:hidden; margin:-24px; }

/* Left Tree Sidebar */
.ce-sidebar { width:280px; flex-shrink:0; background:#141420; border-right:1px solid rgba(255,255,255,0.06); overflow-y:auto; display:flex; flex-direction:column; }
.ce-sidebar-header { padding:14px 16px; border-bottom:1px solid rgba(255,255,255,0.06); }
.ce-sidebar-header h3 { font-size:12px; font-weight:800; letter-spacing:1px; text-transform:uppercase; color:rgba(255,255,255,0.4); margin:0; }
.ce-course-label { font-size:13px; font-weight:700; color:rgba(255,255,255,0.9); margin-top:4px; display:flex; align-items:center; gap:6px; }

/* Module items */
.ce-mod-item { border-bottom:1px solid rgba(255,255,255,0.04); }
.ce-mod-toggle { display:flex; align-items:center; gap:8px; padding:10px 14px; cursor:pointer; transition:background 0.15s; user-select:none; }
.ce-mod-toggle:hover { background:rgba(255,255,255,0.04); }
.ce-mod-toggle.active-mod { background:rgba(99,102,241,0.12); }
.ce-mod-num { width:24px; height:24px; border-radius:6px; display:flex; align-items:center; justify-content:center; font-size:10px; font-weight:800; flex-shrink:0; background:rgba(255,255,255,0.1); color:rgba(255,255,255,0.6); }
.ce-mod-num.edited { background:linear-gradient(135deg,#6366f1,#8b5cf6); color:#fff; }
.ce-mod-title { font-size:11px; font-weight:600; color:rgba(255,255,255,0.7); flex:1; line-height:1.3; }
.ce-mod-count { font-size:10px; color:rgba(255,255,255,0.3); flex-shrink:0; }
.ce-mod-arrow { font-size:9px; color:rgba(255,255,255,0.25); transition:transform 0.2s; }
.ce-mod-toggle.open .ce-mod-arrow { transform:rotate(90deg); }

/* Submodule items */
.ce-sub-list { display:none; background:rgba(0,0,0,0.2); }
.ce-sub-list.open { display:block; }
.ce-sub-link { display:flex; align-items:center; gap:8px; padding:8px 14px 8px 46px; cursor:pointer; transition:background 0.12s; font-size:11px; color:rgba(255,255,255,0.5); }
.ce-sub-link:hover { background:rgba(255,255,255,0.04); color:rgba(255,255,255,0.8); }
.ce-sub-link.active { background:rgba(99,102,241,0.18); color:#a5b4fc; font-weight:700; border-left:2px solid #6366f1; padding-left:44px; }
.ce-sub-dot { width:7px; height:7px; border-radius:50%; flex-shrink:0; }
.ce-sub-dot.db { background:#34d399; }
.ce-sub-dot.default { background:rgba(255,255,255,0.15); }
.ce-sub-dot.quiz { background:#f59e0b; }

/* ── Main Editor Area ─── */
.ce-main { flex:1; overflow:hidden; display:flex; flex-direction:column; }
.ce-topbar { display:flex; align-items:center; gap:12px; padding:0 20px; height:52px; border-bottom:1px solid var(--admin-border); background:var(--admin-bg); flex-shrink:0; }
.ce-breadcrumb { font-size:12px; color:var(--admin-text-muted); flex:1; }
.ce-breadcrumb strong { color:var(--admin-text); }
.ce-topbar-actions { display:flex; align-items:center; gap:8px; }
.ce-save-status { font-size:12px; color:var(--admin-text-muted); padding:0 12px; }
.ce-save-status.unsaved { color:#f59e0b; }
.ce-save-status.saved { color:#34d399; }

/* Editor tabs */
.ce-tabs { display:flex; border-bottom:1px solid var(--admin-border); background:var(--admin-bg); padding:0 20px; flex-shrink:0; }
.ce-tab { padding:10px 16px; font-size:12px; font-weight:700; cursor:pointer; color:var(--admin-text-muted); border-bottom:2px solid transparent; transition:all 0.15s; }
.ce-tab:hover { color:var(--admin-text); }
.ce-tab.active { color:var(--admin-primary); border-bottom-color:var(--admin-primary); }

/* Scrollable content area */
.ce-editor-body { flex:1; overflow-y:auto; padding:24px; }

/* Welcome state */
.ce-welcome { display:flex; flex-direction:column; align-items:center; justify-content:center; height:100%; color:var(--admin-text-muted); gap:16px; text-align:center; }

/* Section cards */
.ce-card { background:var(--admin-bg-elevated); border:1px solid var(--admin-border); border-radius:10px; margin-bottom:16px; overflow:hidden; }
.ce-card-header { padding:14px 16px; border-bottom:1px solid var(--admin-border); display:flex; align-items:center; gap:10px; }
.ce-card-title { font-size:13px; font-weight:800; color:var(--admin-text); flex:1; }
.ce-card-body { padding:16px; }

/* DB content indicator */
.ce-db-badge { display:inline-flex; align-items:center; gap:6px; padding:4px 10px; border-radius:20px; font-size:11px; font-weight:700; }
.ce-db-badge.db { background:rgba(52,211,153,0.1); color:#34d399; border:1px solid rgba(52,211,153,0.2); }
.ce-db-badge.default { background:var(--admin-bg); color:var(--admin-text-muted); border:1px solid var(--admin-border); }

/* Default content preview */
.ce-default-preview { background:rgba(99,102,241,0.04); border:1px solid rgba(99,102,241,0.12); border-radius:8px; padding:12px 16px; margin-bottom:14px; }
.ce-default-preview-label { font-size:10px; font-weight:800; text-transform:uppercase; letter-spacing:0.5px; color:rgba(99,102,241,0.7); margin-bottom:8px; }
.ce-default-step { display:flex; align-items:center; gap:8px; font-size:12px; color:var(--admin-text-muted); padding:3px 0; }
.ce-default-step-num { width:18px; height:18px; border-radius:50%; background:rgba(99,102,241,0.15); color:#6366f1; font-size:10px; font-weight:800; display:flex; align-items:center; justify-content:center; flex-shrink:0; }

/* Inputs */
.ce-label { display:block; font-size:12px; font-weight:700; color:var(--admin-text); margin-bottom:5px; }
.ce-hint { font-size:11px; color:var(--admin-text-muted); margin-top:4px; }
.ce-field { margin-bottom:14px; }

/* HTML toolbar */
.html-toolbar { display:flex; gap:3px; flex-wrap:wrap; margin-bottom:6px; }
.html-btn { padding:3px 8px; background:var(--admin-bg); border:1px solid var(--admin-border); border-radius:4px; font-size:11px; cursor:pointer; color:var(--admin-text); transition:all 0.1s; }
.html-btn:hover { background:rgba(99,102,241,0.1); border-color:#6366f1; }

/* Dynamic lists */
.ce-list-row { display:flex; align-items:center; gap:6px; margin-bottom:6px; }
.ce-list-row input { flex:1; }
.ce-del-btn { width:28px; height:28px; background:rgba(239,68,68,0.1); color:#f87171; border:1px solid rgba(239,68,68,0.2); border-radius:6px; cursor:pointer; font-size:16px; display:flex; align-items:center; justify-content:center; flex-shrink:0; transition:all 0.15s; }
.ce-del-btn:hover { background:#ef4444; color:#fff; }
.ce-add-btn { display:flex; align-items:center; justify-content:center; gap:6px; width:100%; padding:8px; border:1px dashed var(--admin-border); border-radius:6px; background:transparent; color:var(--admin-text-muted); font-size:12px; cursor:pointer; transition:all 0.15s; margin-top:4px; }
.ce-add-btn:hover { border-color:var(--admin-primary); color:var(--admin-primary); background:rgba(99,102,241,0.04); }

/* Image preview */
.ce-img-preview { width:100%; height:140px; object-fit:cover; border-radius:8px; border:1px solid var(--admin-border); display:none; margin-top:8px; }

/* Module settings tab */
.ce-toggle-row { display:flex; align-items:center; gap:12px; padding:14px 0; border-bottom:1px solid var(--admin-border); }
.ce-toggle-row:last-child { border:none; }
.ce-toggle-info { flex:1; }
.ce-toggle-title { font-size:13px; font-weight:700; color:var(--admin-text); }
.ce-toggle-desc { font-size:11px; color:var(--admin-text-muted); margin-top:2px; }
.ce-switch { position:relative; width:44px; height:24px; flex-shrink:0; }
.ce-switch input { opacity:0; width:0; height:0; }
.ce-switch-track { position:absolute; inset:0; background:var(--admin-border); border-radius:12px; cursor:pointer; transition:background 0.2s; }
.ce-switch input:checked + .ce-switch-track { background:#6366f1; }
.ce-switch-track::before { content:''; position:absolute; width:18px; height:18px; left:3px; top:3px; background:#fff; border-radius:50%; transition:transform 0.2s; }
.ce-switch input:checked + .ce-switch-track::before { transform:translateX(20px); }

/* Sticky save bar */
.ce-save-bar { padding:12px 20px; border-top:1px solid var(--admin-border); background:var(--admin-bg); display:flex; align-items:center; gap:12px; flex-shrink:0; }
</style>

<div class="ce-root" id="ceRoot">

  <!-- ── LEFT SIDEBAR ─────────────────────────────── -->
  <nav class="ce-sidebar">
    <div class="ce-sidebar-header">
      <div class="ce-sidebar-header h3">Course Structure</div>
      <div class="ce-course-label">🎓 AI Marketing & ChatGPT SEO</div>
    </div>

    <?php foreach ($moduleNames as $mNum => $mName):
      $allSubs = $subTitles[$mNum] ?? [];
      $dbCount = 0;
      foreach ($allSubs as $si => $st) {
          $sk = $mNum . '-' . ($si+1);
          if (isset($hasContent[$mNum . '-' . $sk])) $dbCount++;
      }
      $hasMeta = !empty($moduleSettings["module_{$mNum}_image_url"]) || !empty($moduleSettings["module_{$mNum}_description"]);
    ?>
    <div class="ce-mod-item">
      <div class="ce-mod-toggle" onclick="toggleMod(this,<?= $mNum ?>)" id="modToggle<?= $mNum ?>">
        <div class="ce-mod-num <?= ($dbCount > 0 || $hasMeta) ? 'edited' : '' ?>"><?= $mNum ?></div>
        <div class="ce-mod-title"><?= htmlspecialchars(mb_substr($mName, 0, 45)) ?></div>
        <div class="ce-mod-count"><?= $dbCount ?>/4</div>
        <span class="ce-mod-arrow">▶</span>
      </div>
      <div class="ce-sub-list" id="subList<?= $mNum ?>">
        <!-- Module-level settings entry -->
        <div class="ce-sub-link" onclick="openEditor(<?= $mNum ?>, 'module')" id="sub-<?= $mNum ?>-module">
          <div class="ce-sub-dot <?= $hasMeta ? 'db' : 'default' ?>"></div>
          <span>⚙️ Module Settings</span>
        </div>
        <?php foreach ($allSubs as $si => $sTitle):
          $sNum = $si+1;
          $subKey = $mNum . '-' . $sNum;
          $isQuiz = ($si === 4);
          $inDb   = isset($hasContent[$mNum . '-' . $subKey]);
        ?>
        <div class="ce-sub-link <?= $isQuiz ? 'quiz-sub' : '' ?>"
             onclick="openEditor(<?= $mNum ?>, '<?= $subKey ?>')"
             id="sub-<?= $mNum ?>-<?= $subKey ?>">
          <div class="ce-sub-dot <?= $isQuiz ? 'quiz' : ($inDb ? 'db' : 'default') ?>"></div>
          <span><?= $isQuiz ? '📝' : '📖' ?> <?= htmlspecialchars($sTitle) ?></span>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
    <?php endforeach; ?>

    <div style="padding:12px 14px;border-top:1px solid rgba(255,255,255,0.06);margin-top:auto;">
      <div style="font-size:10px;color:rgba(255,255,255,0.2);margin-bottom:6px;">LEGEND</div>
      <div style="display:flex;flex-direction:column;gap:4px;font-size:10px;color:rgba(255,255,255,0.35);">
        <span><span style="display:inline-block;width:7px;height:7px;border-radius:50%;background:#34d399;margin-right:5px;"></span>Custom content in DB</span>
        <span><span style="display:inline-block;width:7px;height:7px;border-radius:50%;background:rgba(255,255,255,0.15);margin-right:5px;"></span>Using hardcoded default</span>
        <span><span style="display:inline-block;width:7px;height:7px;border-radius:50%;background:#f59e0b;margin-right:5px;"></span>Quiz (auto-rendered)</span>
      </div>
    </div>
  </nav>

  <!-- ── MAIN EDITOR ─────────────────────────────── -->
  <div class="ce-main">

    <!-- Top bar -->
    <div class="ce-topbar">
      <div class="ce-breadcrumb" id="ceBreadcrumb">
        <strong>Course Editor</strong> — Select a module or lesson from the sidebar
      </div>
      <div class="ce-topbar-actions">
        <span class="ce-save-status" id="ceSaveStatus"></span>
        <a href="/courses/ai-marketing-course/learn/1/1" target="_blank" class="admin-btn admin-btn-ghost admin-btn-sm">👁 Preview Course</a>
        <button type="button" onclick="saveCurrentEditor()" class="admin-btn admin-btn-primary admin-btn-sm" id="ceSaveBtn" style="display:none;">💾 Save</button>
        <button type="button" onclick="resetCurrentEditor()" class="admin-btn admin-btn-ghost admin-btn-sm" style="color:#ef4444;display:none;" id="ceResetBtn">↩ Reset to Default</button>
      </div>
    </div>

    <!-- Welcome state -->
    <div class="ce-welcome" id="ceWelcome">
      <div style="font-size:60px;opacity:0.3;">📝</div>
      <div style="font-size:18px;font-weight:800;color:var(--admin-text);">Select a lesson to edit</div>
      <div style="font-size:13px;">Click any item in the left sidebar to start editing</div>
      <div style="display:flex;gap:8px;margin-top:8px;">
        <span style="font-size:11px;background:rgba(52,211,153,0.1);color:#34d399;padding:4px 10px;border-radius:20px;">● Custom content</span>
        <span style="font-size:11px;background:var(--admin-bg-elevated);color:var(--admin-text-muted);padding:4px 10px;border-radius:20px;">○ Hardcoded default</span>
      </div>
    </div>

    <!-- Module Settings editor (shown when ⚙️ Module Settings clicked) -->
    <div id="ceModuleEditor" style="display:none;flex:1;flex-direction:column;overflow:hidden;">
      <div class="ce-tabs">
        <div class="ce-tab active" onclick="switchTab('modTab','settings')">⚙️ Module Settings</div>
      </div>
      <div class="ce-editor-body" id="modTabSettings">
        <div id="modEditorContent"></div>
      </div>
      <div class="ce-save-bar">
        <button type="button" onclick="saveModuleSettings()" class="admin-btn admin-btn-primary">💾 Save Module Settings</button>
        <span class="ce-save-status" id="modSaveStatus"></span>
      </div>
    </div>

    <!-- Submodule Content editor -->
    <div id="ceSubEditor" style="display:none;flex:1;flex-direction:column;overflow:hidden;">
      <div class="ce-tabs">
        <div class="ce-tab active" onclick="switchSubTab('content')" id="subTabContent">📝 Content</div>
        <div class="ce-tab" onclick="switchSubTab('media')" id="subTabMedia">🖼️ Media</div>
        <div class="ce-tab" onclick="switchSubTab('visual')" id="subTabVisual">⚡ Visual Card</div>
        <div class="ce-tab" onclick="switchSubTab('resources')" id="subTabResources">📎 Resources</div>
      </div>
      <div class="ce-editor-body" id="subEditorBody">
        <input type="hidden" id="ceCurrentMod">
        <input type="hidden" id="ceCurrentSub">

        <!-- Content tab -->
        <div id="subTab-content">
          <div class="ce-card" style="margin-bottom:14px;">
            <div class="ce-card-header">
              <span>📋</span><div class="ce-card-title">Lesson Info</div>
              <div id="subDbBadge"></div>
            </div>
            <div class="ce-card-body" style="display:grid;grid-template-columns:1fr 160px;gap:12px;">
              <div class="ce-field">
                <label class="ce-label">Title Override <span style="color:var(--admin-text-muted);font-weight:400;">— leave blank for default</span></label>
                <input type="text" id="f_content_title" class="admin-input" placeholder="Use default title">
              </div>
              <div class="ce-field">
                <label class="ce-label">Duration</label>
                <input type="text" id="f_duration_text" class="admin-input" placeholder="12 min">
              </div>
            </div>
          </div>

          <div class="ce-card">
            <div class="ce-card-header">
              <span>📝</span><div class="ce-card-title">Content Body (HTML)</div>
              <span style="font-size:11px;color:var(--admin-text-muted);">Full HTML supported</span>
            </div>
            <div class="ce-card-body">
              <div class="html-toolbar">
                <button type="button" class="html-btn" onclick="wrapTag('strong')"><b>B</b></button>
                <button type="button" class="html-btn" onclick="wrapTag('em')"><i>I</i></button>
                <button type="button" class="html-btn" onclick="insertHtml('<h2>Heading</h2>\n')">H2</button>
                <button type="button" class="html-btn" onclick="insertHtml('<h3>Subheading</h3>\n')">H3</button>
                <button type="button" class="html-btn" onclick="insertHtml('<p>Paragraph text here.</p>\n')">P</button>
                <button type="button" class="html-btn" onclick="insertHtml('<ul>\n  <li>Item 1</li>\n  <li>Item 2</li>\n</ul>\n')">UL</button>
                <button type="button" class="html-btn" onclick="insertHtml('<ol>\n  <li>Step 1</li>\n  <li>Step 2</li>\n</ol>\n')">OL</button>
                <button type="button" class="html-btn" onclick="insertHtml('<blockquote>Quote text</blockquote>\n')">"</button>
                <button type="button" class="html-btn" onclick="insertHtml('<a href=\"URL\">Link text</a>')">Link</button>
                <button type="button" class="html-btn" onclick="insertHtml('<code>code</code>')">Code</button>
                <button type="button" class="html-btn" onclick="insertHtml('<hr>\n')">HR</button>
                <button type="button" class="html-btn" onclick="insertHtml('<img src=\"URL\" alt=\"\" style=\"max-width:100%;border-radius:8px;\">\n')">IMG</button>
              </div>
              <textarea id="f_content_html" class="admin-input" rows="14"
                style="font-family:monospace;font-size:12px;resize:vertical;min-height:200px;"
                placeholder="Enter lesson HTML content...&#10;&#10;Example:&#10;<p>In this lesson you'll learn...</p>&#10;<h2>Key Concepts</h2>&#10;<ul>&#10;  <li>Point 1</li>&#10;</ul>"
                oninput="markDirty()"></textarea>
            </div>
          </div>
        </div>

        <!-- Media tab -->
        <div id="subTab-media" style="display:none;">
          <div class="ce-card">
            <div class="ce-card-header"><span>🖼️</span><div class="ce-card-title">Lesson Image / Banner</div></div>
            <div class="ce-card-body">
              <div class="ce-field">
                <label class="ce-label">Image URL</label>
                <div style="display:flex;gap:8px;">
                  <input type="url" id="f_image_url" class="admin-input" placeholder="https://..." style="flex:1;" oninput="previewImg();markDirty()">
                  <a href="/techaasvik_admin/media" target="_blank" class="admin-btn admin-btn-ghost admin-btn-sm">📁 Media Library</a>
                </div>
                <img id="ceImgPreview" src="" alt="Preview" class="ce-img-preview">
                <div class="ce-hint">Shown as banner above the lesson content. Use 16:9 or wide-format images.</div>
              </div>
            </div>
          </div>
          <div class="ce-card">
            <div class="ce-card-header"><span>🎬</span><div class="ce-card-title">Video</div></div>
            <div class="ce-card-body">
              <div class="ce-field">
                <label class="ce-label">Video URL</label>
                <input type="url" id="f_video_url" class="admin-input" placeholder="https://youtube.com/watch?v=... or Vimeo or direct MP4" oninput="markDirty()">
                <div class="ce-hint">YouTube, Vimeo, and direct MP4 URLs are auto-converted to embedded player.</div>
              </div>
              <div class="ce-field">
                <label class="ce-label">Custom Embed Code <span style="color:var(--admin-text-muted);font-weight:400;">— overrides Video URL player</span></label>
                <textarea id="f_video_embed" class="admin-input" rows="3" style="font-family:monospace;font-size:11px;resize:vertical;" placeholder='<iframe src="..." allow="autoplay" ...></iframe>' oninput="markDirty()"></textarea>
              </div>
            </div>
          </div>
        </div>

        <!-- Visual Card tab -->
        <div id="subTab-visual" style="display:none;">
          <div class="ce-card" style="margin-bottom:14px;">
            <div class="ce-card-header"><span>⚡</span><div class="ce-card-title">Visual Infographic Card</div></div>
            <div class="ce-card-body">
              <div class="ce-field">
                <label class="ce-label">Card Heading</label>
                <input type="text" id="f_infographic_title" class="admin-input" placeholder="e.g. The AI Marketing Shift" oninput="markDirty()">
              </div>
              <!-- Default preview -->
              <div class="ce-default-preview" id="defaultKpPreview"></div>
              <div class="ce-field">
                <label class="ce-label">Custom Key Points <span style="color:var(--admin-text-muted);font-weight:400;">— leave empty to use default above</span></label>
                <div id="keyPointsList"></div>
                <button type="button" class="ce-add-btn" onclick="addKeyPoint('')">+ Add Key Point</button>
                <div class="ce-hint">4–6 points recommended. These replace the default visual steps.</div>
              </div>
            </div>
          </div>
        </div>

        <!-- Resources tab -->
        <div id="subTab-resources" style="display:none;">
          <div class="ce-card">
            <div class="ce-card-header"><span>📎</span><div class="ce-card-title">Downloadable Resources</div></div>
            <div class="ce-card-body">
              <div id="resourcesList"></div>
              <button type="button" class="ce-add-btn" onclick="addResource('','')">+ Add Resource Link</button>
              <div class="ce-hint" style="margin-top:8px;">Resources appear below the lesson content as download links.</div>
            </div>
          </div>
        </div>

      </div>
      <div class="ce-save-bar">
        <button type="button" onclick="saveSubEditor()" class="admin-btn admin-btn-primary" id="subSaveBtn">💾 Save Lesson Content</button>
        <span class="ce-save-status" id="subSaveStatus"></span>
        <span style="flex:1;"></span>
        <button type="button" onclick="resetSubEditor()" class="admin-btn admin-btn-ghost admin-btn-sm" style="color:#ef4444;" id="subResetBtn" style="display:none;">↩ Reset to Default</button>
        <a id="subPreviewLink" href="#" target="_blank" class="admin-btn admin-btn-ghost admin-btn-sm">👁 Preview →</a>
      </div>
    </div>

    <!-- Quiz notice -->
    <div id="ceQuizNotice" style="display:none;flex:1;align-items:center;justify-content:center;">
      <div style="text-align:center;color:var(--admin-text-muted);padding:40px;">
        <div style="font-size:48px;margin-bottom:16px;">📝</div>
        <div style="font-size:16px;font-weight:700;color:var(--admin-text);margin-bottom:8px;">Module Quiz</div>
        <div style="font-size:13px;">Quiz questions are managed in the controller code.<br>To edit quiz questions, update <code>CourseController::module<?= '' ?>Nquiz()</code> methods.</div>
      </div>
    </div>

  </div><!-- /ce-main -->

</div><!-- /ce-root -->

<script>
const CSRF = '<?= htmlspecialchars($csrfToken) ?>';
const SLUG = '<?= $slug ?>';
let currentMod = null, currentSub = null, currentType = null, isDirty = false;

// All default key-points for showing in editor
const DEFAULT_KEYPOINTS = <?= json_encode($defaultKeyPoints) ?>;
const MODULE_NAMES = <?= json_encode($moduleNames) ?>;
const SUB_TITLES = <?= json_encode($subTitles) ?>;

// Module settings cache (from PHP)
const MODULE_SETTINGS = <?= json_encode($moduleSettings) ?>;

// ── Sidebar ──────────────────────────────────────────────────
function toggleMod(el, mNum) {
  el.classList.toggle('open');
  const list = document.getElementById('subList' + mNum);
  list.classList.toggle('open');
}

function openEditor(mod, sub) {
  if (isDirty && !confirm('Unsaved changes will be lost. Continue?')) return;
  isDirty = false;

  // Clear active states
  document.querySelectorAll('.ce-sub-link').forEach(e => e.classList.remove('active'));
  const el = document.getElementById('sub-' + mod + '-' + (sub === 'module' ? 'module' : sub));
  if (el) el.classList.add('active');

  currentMod = mod;
  currentSub = sub;

  // Show/hide panels
  document.getElementById('ceWelcome').style.display = 'none';
  document.getElementById('ceModuleEditor').style.display = 'none';
  document.getElementById('ceSubEditor').style.display = 'none';
  document.getElementById('ceQuizNotice').style.display = 'none';

  if (sub === 'module') {
    openModuleEditor(mod);
  } else {
    const parts = sub.split('-');
    const sNum = parseInt(parts[1]);
    const isQuiz = (sNum === 5);
    if (isQuiz) {
      document.getElementById('ceQuizNotice').style.display = 'flex';
      updateBreadcrumb('Module ' + mod, 'Quiz');
    } else {
      loadSubmodule(mod, sub);
    }
  }
}

function updateBreadcrumb(mod, sub) {
  document.getElementById('ceBreadcrumb').innerHTML =
    '<strong>Module ' + mod + '</strong> <span style="color:var(--admin-text-muted);"> › </span> ' + sub;
}

// ── Module Settings ──────────────────────────────────────────
function openModuleEditor(mNum) {
  document.getElementById('ceModuleEditor').style.display = 'flex';
  const name  = MODULE_NAMES[mNum] || ('Module ' + mNum);
  const s     = MODULE_SETTINGS;
  const imgUrl  = s['module_' + mNum + '_image_url']   || '';
  const videoUrl= s['module_' + mNum + '_video_url']   || '';
  const desc    = s['module_' + mNum + '_description'] || '';
  const dur     = s['module_' + mNum + '_duration']    || '';
  const isFree  = s['module_' + mNum + '_is_free'];
  const freeVal = (isFree !== undefined && isFree !== null) ? (isFree == '1' || isFree === true) : (mNum <= 5);

  updateBreadcrumb(mNum, '⚙️ Module Settings');

  document.getElementById('modEditorContent').innerHTML = `
    <div class="ce-card" style="margin-bottom:14px;">
      <div class="ce-card-header"><span>⚙️</span><div class="ce-card-title">Module ${mNum}: ${escHtml(name)}</div></div>
      <div class="ce-card-body">
        <div class="ce-toggle-row">
          <div class="ce-toggle-info">
            <div class="ce-toggle-title">Free / Paid Access</div>
            <div class="ce-toggle-desc">Toggle ON = Free preview. Toggle OFF = requires payment to access.</div>
          </div>
          <label class="ce-switch">
            <input type="checkbox" id="mod_is_free" ${freeVal ? 'checked' : ''}>
            <span class="ce-switch-track"></span>
          </label>
          <span id="modFreeLabel" style="font-size:12px;font-weight:700;width:50px;">${freeVal ? '<span style="color:#34d399;">FREE</span>' : '<span style="color:#f59e0b;">PAID</span>'}</span>
        </div>
      </div>
    </div>

    <div class="ce-card" style="margin-bottom:14px;">
      <div class="ce-card-header"><span>📋</span><div class="ce-card-title">Module Description & Duration</div></div>
      <div class="ce-card-body">
        <div class="ce-field">
          <label class="ce-label">Description <span style="color:var(--admin-text-muted);font-weight:400;">— shown on course landing page</span></label>
          <textarea id="mod_description" class="admin-input" rows="3" placeholder="Brief description of what students learn in this module...">${escHtml(desc)}</textarea>
        </div>
        <div class="ce-field">
          <label class="ce-label">Total Duration</label>
          <input type="text" id="mod_duration" class="admin-input" value="${escHtml(dur)}" placeholder="45 min">
        </div>
      </div>
    </div>

    <div class="ce-card">
      <div class="ce-card-header"><span>🖼️</span><div class="ce-card-title">Module Media</div></div>
      <div class="ce-card-body">
        <div class="ce-field">
          <label class="ce-label">Module Image URL</label>
          <div style="display:flex;gap:8px;">
            <input type="url" id="mod_image_url" class="admin-input" value="${escHtml(imgUrl)}" placeholder="https://..." style="flex:1;" oninput="previewModImg()">
            <a href="/techaasvik_admin/media" target="_blank" class="admin-btn admin-btn-ghost admin-btn-sm">📁 Media</a>
          </div>
          <img id="modImgPreview" src="${escHtml(imgUrl)}" alt="" class="ce-img-preview" style="${imgUrl ? 'display:block' : ''}">
        </div>
        <div class="ce-field">
          <label class="ce-label">Module Intro Video URL</label>
          <input type="url" id="mod_video_url" class="admin-input" value="${escHtml(videoUrl)}" placeholder="https://youtube.com/...">
        </div>
      </div>
    </div>
  `;

  // Toggle handler
  document.getElementById('mod_is_free').addEventListener('change', function() {
    document.getElementById('modFreeLabel').innerHTML = this.checked
      ? '<span style="color:#34d399;">FREE</span>'
      : '<span style="color:#f59e0b;">PAID</span>';
  });
}

function previewModImg() {
  const url = document.getElementById('mod_image_url').value.trim();
  const img = document.getElementById('modImgPreview');
  if (url) { img.src = url; img.style.display = 'block'; }
  else      { img.style.display = 'none'; }
}

async function saveModuleSettings() {
  const btn = document.querySelector('.ce-save-bar .admin-btn-primary');
  const status = document.getElementById('modSaveStatus');
  btn.textContent = '⏳ Saving...'; btn.disabled = true;

  const fd = new FormData();
  fd.append('_csrf_token',  CSRF);
  fd.append('is_free',      document.getElementById('mod_is_free').checked ? '1' : '0');
  fd.append('description',  document.getElementById('mod_description').value);
  fd.append('duration',     document.getElementById('mod_duration').value);
  fd.append('image_url',    document.getElementById('mod_image_url').value);
  fd.append('video_url',    document.getElementById('mod_video_url').value);

  try {
    const r = await fetch(`/techaasvik_admin/course/modules/${currentMod}/save`, { method:'POST', body:fd, headers:{'X-Requested-With':'XMLHttpRequest'} });
    const d = await r.json();
    btn.textContent = '💾 Save Module Settings'; btn.disabled = false;
    if (d.success) {
      status.textContent = '✓ Saved!'; status.className = 'ce-save-status saved';
      // Update sidebar dot
      const numEl = document.querySelector(`#modToggle${currentMod} .ce-mod-num`);
      if (numEl) numEl.classList.add('edited');
      setTimeout(() => { status.textContent = ''; }, 3000);
    } else { alert('Error: ' + d.error); }
  } catch(e) {
    btn.textContent = '💾 Save Module Settings'; btn.disabled = false;
    alert('Network error: ' + e.message);
  }
}

// ── Submodule Content ────────────────────────────────────────
function switchSubTab(tab) {
  ['content','media','visual','resources'].forEach(t => {
    document.getElementById('subTab-' + t).style.display = t === tab ? 'block' : 'none';
    document.getElementById('subTab' + t.charAt(0).toUpperCase() + t.slice(1)).classList.toggle('active', t === tab);
  });
}

async function loadSubmodule(mod, sub) {
  document.getElementById('ceSubEditor').style.display = 'flex';
  document.getElementById('subSaveStatus').textContent = 'Loading...';
  document.getElementById('subSaveStatus').className = 'ce-save-status';
  document.getElementById('subPreviewLink').href = `/courses/${SLUG}/learn/${mod}/${sub.split('-')[1]}`;

  // Open visual tab to first / content tab
  switchSubTab('content');

  // Show default key-points preview
  const defaultKps = DEFAULT_KEYPOINTS[sub] || [];
  const kpPreview = document.getElementById('defaultKpPreview');
  if (defaultKps.length) {
    kpPreview.innerHTML = '<div class="ce-default-preview-label">📌 Current Default Key Points (hardcoded)</div>' +
      defaultKps.map((p,i) => `<div class="ce-default-step"><div class="ce-default-step-num">${i+1}</div>${escHtml(p)}</div>`).join('');
  } else {
    kpPreview.innerHTML = '<div class="ce-default-preview-label" style="color:var(--admin-text-muted);">No default key points for this lesson</div>';
  }

  // Breadcrumb
  const parts = sub.split('-');
  const sNum = parseInt(parts[1]);
  const sTitle = (SUB_TITLES[mod] || [])[sNum-1] || 'Lesson ' + sNum;
  updateBreadcrumb('Module ' + mod, sTitle);

  try {
    const r = await fetch(`/techaasvik_admin/course/content-editor/${mod}/${sub}/load`, { headers:{'X-Requested-With':'XMLHttpRequest'} });
    const d = await r.json();
    populateSubForm(d, sub);
  } catch(e) {
    document.getElementById('subSaveStatus').textContent = 'Error: ' + e.message;
  }
}

function populateSubForm(d, sub) {
  document.getElementById('ceCurrentMod').value = d.module_num;
  document.getElementById('ceCurrentSub').value = d.submodule_key;

  document.getElementById('f_content_title').value     = d.content_title    || '';
  document.getElementById('f_duration_text').value     = d.duration_text    || '';
  document.getElementById('f_image_url').value          = d.image_url        || '';
  document.getElementById('f_video_url').value          = d.video_url        || '';
  document.getElementById('f_video_embed').value        = d.video_embed      || '';
  document.getElementById('f_infographic_title').value  = d.infographic_title|| '';
  document.getElementById('f_content_html').value       = d.content_html     || '';
  previewImg();

  // Key points
  document.getElementById('keyPointsList').innerHTML = '';
  (d.key_points || []).forEach(p => addKeyPoint(p));

  // Resources
  document.getElementById('resourcesList').innerHTML = '';
  (d.resources || []).forEach(r => addResource(r.name||'', r.url||''));

  // DB badge
  const badge = document.getElementById('subDbBadge');
  if (d.exists) {
    badge.innerHTML = '<span class="ce-db-badge db">✅ Custom Content</span>';
    document.getElementById('subResetBtn').style.display = '';
  } else {
    badge.innerHTML = '<span class="ce-db-badge default">○ Default</span>';
    document.getElementById('subResetBtn').style.display = 'none';
  }

  document.getElementById('subSaveStatus').textContent = '';
  isDirty = false;
}

function markDirty() {
  isDirty = true;
  const s = document.getElementById('subSaveStatus');
  s.textContent = '● Unsaved changes';
  s.className = 'ce-save-status unsaved';
}

async function saveSubEditor() {
  const mod = document.getElementById('ceCurrentMod').value;
  const sub = document.getElementById('ceCurrentSub').value;
  if (!mod || !sub) return;

  const btn = document.getElementById('subSaveBtn');
  btn.textContent = '⏳ Saving...'; btn.disabled = true;

  const kps = [...document.querySelectorAll('.kp-input')].map(i => i.value.trim()).filter(Boolean);
  const res = [];
  document.querySelectorAll('.res-row').forEach(row => {
    const name = row.querySelector('.res-name').value.trim();
    const url  = row.querySelector('.res-url').value.trim();
    if (name) res.push({name, url});
  });

  const fd = new FormData();
  fd.append('_csrf_token',      CSRF);
  fd.append('content_title',    document.getElementById('f_content_title').value);
  fd.append('duration_text',    document.getElementById('f_duration_text').value);
  fd.append('image_url',         document.getElementById('f_image_url').value);
  fd.append('video_url',         document.getElementById('f_video_url').value);
  fd.append('video_embed',       document.getElementById('f_video_embed').value);
  fd.append('infographic_title', document.getElementById('f_infographic_title').value);
  fd.append('content_html',      document.getElementById('f_content_html').value);
  fd.append('key_points',        JSON.stringify(kps));
  fd.append('resources',         JSON.stringify(res));

  try {
    const r = await fetch(`/techaasvik_admin/course/content-editor/${mod}/${sub}/save`, { method:'POST', body:fd, headers:{'X-Requested-With':'XMLHttpRequest'} });
    const d = await r.json();
    btn.textContent = '💾 Save Lesson Content'; btn.disabled = false;
    if (d.success) {
      isDirty = false;
      const s = document.getElementById('subSaveStatus');
      s.textContent = '✓ ' + d.message; s.className = 'ce-save-status saved';
      document.getElementById('subDbBadge').innerHTML = '<span class="ce-db-badge db">✅ Custom Content</span>';
      document.getElementById('subResetBtn').style.display = '';
      // Update tree dot
      const dotEl = document.querySelector(`#sub-${mod}-${sub} .ce-sub-dot`);
      if (dotEl) dotEl.className = 'ce-sub-dot db';
      // Update module count in sidebar
      const cnt = document.querySelector(`#modToggle${mod} .ce-mod-count`);
      if (cnt) {
        const cur = parseInt(cnt.textContent) || 0;
        cnt.textContent = (cur + 1) + '/4';
      }
      const numEl = document.querySelector(`#modToggle${mod} .ce-mod-num`);
      if (numEl) numEl.classList.add('edited');
      setTimeout(() => { s.textContent = ''; }, 3000);
    } else { alert('Error: ' + d.error); }
  } catch(e) {
    btn.textContent = '💾 Save Lesson Content'; btn.disabled = false;
    alert('Network error: ' + e.message);
  }
}

async function resetSubEditor() {
  const mod = document.getElementById('ceCurrentMod').value;
  const sub = document.getElementById('ceCurrentSub').value;
  if (!mod || !sub) return;
  if (!confirm('Reset this lesson to hardcoded default? Custom content will be deleted.')) return;

  const fd = new FormData();
  fd.append('_csrf_token', CSRF);

  try {
    const r = await fetch(`/techaasvik_admin/course/content-editor/${mod}/${sub}/delete`, { method:'POST', body:fd, headers:{'X-Requested-With':'XMLHttpRequest'} });
    const d = await r.json();
    if (d.success) {
      isDirty = false;
      const dotEl = document.querySelector(`#sub-${mod}-${sub} .ce-sub-dot`);
      if (dotEl) dotEl.className = 'ce-sub-dot default';
      loadSubmodule(parseInt(mod), sub);
    }
  } catch(e) { alert('Error: ' + e.message); }
}

// ── Dynamic lists ────────────────────────────────────────────
function addKeyPoint(value) {
  const list = document.getElementById('keyPointsList');
  const div  = document.createElement('div');
  div.className = 'ce-list-row';
  div.innerHTML = `<input type="text" class="admin-input kp-input" value="${escHtml(value)}" placeholder="Key point..." oninput="markDirty()">
                   <button type="button" class="ce-del-btn" onclick="this.parentNode.remove();markDirty()">×</button>`;
  list.appendChild(div);
}

function addResource(name, url) {
  const list = document.getElementById('resourcesList');
  const div  = document.createElement('div');
  div.className = 'ce-list-row res-row';
  div.innerHTML = `<input type="text" class="admin-input res-name" value="${escHtml(name)}" placeholder="Resource name..." style="flex:2;" oninput="markDirty()">
                   <input type="url"  class="admin-input res-url"  value="${escHtml(url)}"  placeholder="https://..." style="flex:3;" oninput="markDirty()">
                   <button type="button" class="ce-del-btn" onclick="this.parentNode.remove();markDirty()">×</button>`;
  list.appendChild(div);
}

// ── HTML toolbar ─────────────────────────────────────────────
function wrapTag(tag) {
  const ta = document.getElementById('f_content_html');
  const s = ta.selectionStart, e = ta.selectionEnd;
  const sel = ta.value.substring(s, e) || 'text';
  ta.value = ta.value.substring(0,s) + `<${tag}>${sel}</${tag}>` + ta.value.substring(e);
  markDirty();
}
function insertHtml(html) {
  const ta = document.getElementById('f_content_html');
  const pos = ta.selectionStart;
  ta.value = ta.value.substring(0,pos) + html + ta.value.substring(pos);
  markDirty();
}

// ── Image preview ─────────────────────────────────────────────
function previewImg() {
  const url = document.getElementById('f_image_url').value.trim();
  const img = document.getElementById('ceImgPreview');
  if (url) { img.src = url; img.style.display = 'block'; }
  else      { img.style.display = 'none'; }
}

// ── Tab switcher for module editor ───────────────────────────
function switchTab(group, name) {
  document.querySelectorAll(`[data-tabgroup="${group}"]`).forEach(el => el.style.display = 'none');
  document.querySelectorAll(`[data-tabtrigger="${group}"]`).forEach(el => el.classList.remove('active'));
  const target = document.getElementById(group + '_' + name);
  if (target) { target.style.display = 'block'; }
}

// ── Utility ──────────────────────────────────────────────────
function escHtml(s) {
  return (s||'').replace(/&/g,'&amp;').replace(/"/g,'&quot;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
}

window.addEventListener('beforeunload', e => {
  if (isDirty) { e.preventDefault(); e.returnValue = ''; }
});

// Auto-open Module 1 on load
document.addEventListener('DOMContentLoaded', () => {
  const firstToggle = document.getElementById('modToggle1');
  if (firstToggle) { firstToggle.classList.add('open'); document.getElementById('subList1').classList.add('open'); }
});
</script>
