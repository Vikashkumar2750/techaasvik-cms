<!-- Admin Content Editor -->
<?php
$isNew   = empty($item);
$itemId  = $item['id'] ?? '';
$title   = $item['title'] ?? '';
$slug    = $item['slug'] ?? '';
$status  = $item['status'] ?? 'draft';
$lang    = $item['lang'] ?? 'en';
$excerpt = $item['excerpt'] ?? '';
$body    = $item['content'] ?? '';
$metaTitle = $item['meta_title'] ?? '';
$metaDesc  = $item['meta_description'] ?? '';
$canonical = $item['canonical_url'] ?? '';
$ogTitle   = $item['og_title'] ?? '';
$ogDesc    = $item['og_description'] ?? '';
$schemaType= $item['schema_type'] ?? '';
$schemaJson= $item['schema_json'] ?? '';
$noindex   = $item['noindex'] ?? 0;
$nofollow  = $item['nofollow'] ?? 0;
$contentType = $item['type'] ?? ($type ?? $_GET['type'] ?? 'post');
$selectedCats = $selectedCats ?? [];
$selectedTags = $selectedTags ?? [];
$actionUrl = $isNew ? '/techaasvik_admin/content/store' : "/techaasvik_admin/content/{$itemId}/update";
?>

<div class="admin-page-header">
  <div>
    <h1 class="admin-page-title"><?= $isNew ? 'New Content' : 'Edit Content' ?></h1>
    <?php if (!$isNew): ?>
    <p class="admin-page-subtitle" id="wordCountDisplay">Word count: calculating…</p>
    <?php endif; ?>
  </div>
  <div style="display:flex;gap:8px;">
    <?php if (!$isNew && ($item['status'] ?? '') === 'published'): ?>
    <a href="<?= content_url($item) ?>" target="_blank" class="admin-btn admin-btn-ghost">View Live ↗</a>
    <?php endif; ?>
    <a href="/techaasvik_admin/content" class="admin-btn admin-btn-secondary">← Back to List</a>
  </div>
</div>

<form method="post" action="<?= $actionUrl ?>" id="contentForm" novalidate>
  <input type="hidden" name="_csrf_token" value="<?= \Core\Auth::csrfToken() ?>">

  <div style="display:grid;grid-template-columns:1fr 320px;gap:20px;align-items:start;">

    <!-- ── LEFT COLUMN: Main Content ── -->
    <div style="display:flex;flex-direction:column;gap:16px;">

      <!-- Title -->
      <div class="admin-form-group">
        <label class="admin-form-label" for="title">Title <span class="req">*</span></label>
        <input type="text" id="title" name="title" class="admin-form-input" value="<?= e($title) ?>"
               placeholder="Enter a descriptive title…" required oninput="autoSlug(this.value)" style="font-size:18px;font-weight:600;padding:12px 14px;">
      </div>

      <!-- Slug -->
      <div class="admin-form-group" style="margin-top:-8px;">
        <label class="admin-form-label" for="slug">
          URL Slug
          <button type="button" onclick="regenerateSlug()" style="background:none;border:none;color:var(--admin-brand);cursor:pointer;font-size:11px;margin-left:6px;">↺ Regenerate</button>
        </label>
        <div style="display:flex;align-items:center;gap:8px;">
          <span style="font-size:12px;color:var(--admin-muted);flex-shrink:0;">techaasvik.com/…/</span>
          <input type="text" id="slug" name="slug" class="admin-form-input" value="<?= e($slug) ?>" placeholder="url-slug" style="font-family:var(--font-mono,monospace);font-size:13px;">
        </div>
      </div>

      <!-- Excerpt -->
      <div class="admin-form-group">
        <label class="admin-form-label" for="excerpt">Excerpt / Meta Summary</label>
        <textarea id="excerpt" name="excerpt" class="admin-form-textarea" rows="3"
                  placeholder="150–300 character summary (used in listings, OG tags, and AI feeds)…"
                  oninput="updateCharCount('excerpt','excerptCount',320)"><?= e($excerpt) ?></textarea>
        <div style="display:flex;justify-content:space-between;">
          <span class="admin-form-hint">Used for SEO meta description, OG tags, and AI citations.</span>
          <span class="char-counter" id="excerptCount"><?= strlen($excerpt) ?>/320</span>
        </div>
      </div>

      <!-- Content Editor Tabs -->
      <div class="editor-panel">
        <div class="editor-tabs">
          <div class="editor-tab active" onclick="switchTab('editor','tab-editor',this)">✏️ Visual Editor</div>
          <div class="editor-tab" onclick="switchTab('preview','tab-preview',this)">👁 Preview</div>
          <div class="editor-tab" onclick="switchTab('html','tab-html',this)">{'<>'} HTML Source</div>
        </div>

        <!-- Visual (Quill) Tab -->
        <div id="tab-editor" class="editor-panel-body" style="padding:0;">
          <div id="quillEditor" style="min-height:420px;font-size:15px;line-height:1.7;"></div>
          <!-- Hidden textarea synced with Quill on form submit -->
          <textarea id="bodyContent" name="content" style="display:none;"><?= e($body) ?></textarea>
        </div>

        <!-- Preview Tab -->
        <div id="tab-preview" class="editor-panel-body" style="display:none;min-height:400px;">
          <div id="previewContent" class="prose" style="max-width:none;"></div>
        </div>

        <!-- HTML (raw) Tab -->
        <div id="tab-html" class="editor-panel-body" style="display:none;">
          <textarea id="htmlSource" class="admin-form-textarea"
                    style="min-height:400px;font-family:var(--font-mono,monospace);font-size:13px;padding:16px;resize:vertical;background:var(--admin-elevated);"
                    placeholder="Raw HTML source…"><?= e($body) ?></textarea>
        </div>
      </div>

      <!-- SEO Panel -->
      <div class="editor-panel">
        <div class="editor-tabs">
          <div class="editor-tab active" onclick="switchTab('seo','tab-seo',this)">🔍 SEO</div>
          <div class="editor-tab" onclick="switchTab('og','tab-og',this)">📣 Social</div>
          <div class="editor-tab" onclick="switchTab('schema','tab-schema',this)">⚡ Schema</div>
        </div>

        <!-- SEO Tab -->
        <div id="tab-seo" class="editor-panel-body">
          <div class="admin-form-group">
            <label class="admin-form-label" for="meta_title">SEO Title <span style="color:var(--admin-muted);font-weight:400;">(max 70 chars)</span></label>
            <input type="text" id="meta_title" name="meta_title" class="admin-form-input"
                   value="<?= e($metaTitle) ?>" maxlength="70"
                   oninput="updateCharCount('meta_title','metaTitleCount',70)"
                   placeholder="Page title for Google search results…">
            <div style="display:flex;justify-content:space-between;">
              <span class="admin-form-hint">Shown in Google SERP. Leave empty to auto-generate.</span>
              <span class="char-counter" id="metaTitleCount"><?= strlen($metaTitle) ?>/70</span>
            </div>
          </div>
          <div class="admin-form-group">
            <label class="admin-form-label" for="meta_description">Meta Description <span style="color:var(--admin-muted);font-weight:400;">(max 160 chars)</span></label>
            <textarea id="meta_description" name="meta_description" class="admin-form-textarea" rows="3"
                      maxlength="160" oninput="updateCharCount('meta_description','metaDescCount',160)"
                      placeholder="Compelling description shown under the title in search results…"><?= e($metaDesc) ?></textarea>
            <div style="display:flex;justify-content:space-between;">
              <span class="admin-form-hint">Keep between 120–160 characters for best display.</span>
              <span class="char-counter" id="metaDescCount"><?= strlen($metaDesc) ?>/160</span>
            </div>
          </div>
          <div class="admin-form-group">
            <label class="admin-form-label" for="canonical_url">Canonical URL</label>
            <input type="url" id="canonical_url" name="canonical_url" class="admin-form-input" value="<?= e($canonical) ?>" placeholder="https://t1.techaasvik.com/…">
            <span class="admin-form-hint">Leave empty to auto-generate.</span>
          </div>
          <div style="display:flex;gap:16px;margin-top:8px;">
            <label style="display:flex;align-items:center;gap:8px;cursor:pointer;font-size:13px;">
              <input type="checkbox" name="noindex" value="1" <?= $noindex ? 'checked' : '' ?>> Noindex
            </label>
            <label style="display:flex;align-items:center;gap:8px;cursor:pointer;font-size:13px;">
              <input type="checkbox" name="nofollow" value="1" <?= $nofollow ? 'checked' : '' ?>> Nofollow
            </label>
          </div>
        </div>

        <!-- Social (OG) Tab -->
        <div id="tab-og" class="editor-panel-body" style="display:none;">
          <div class="admin-form-group">
            <label class="admin-form-label" for="og_title">OG Title</label>
            <input type="text" id="og_title" name="og_title" class="admin-form-input" value="<?= e($ogTitle) ?>" placeholder="Same as meta title by default…">
          </div>
          <div class="admin-form-group">
            <label class="admin-form-label" for="og_description">OG Description</label>
            <textarea id="og_description" name="og_description" class="admin-form-textarea" rows="3"
                      placeholder="Facebook/LinkedIn preview description…"><?= e($ogDesc) ?></textarea>
          </div>
          <div class="admin-form-group">
            <label class="admin-form-label" for="og_image">OG Image URL</label>
            <input type="url" id="og_image" name="og_image" class="admin-form-input" value="<?= e($item['og_image'] ?? '') ?>" placeholder="https://… (1200×630 recommended)">
          </div>
        </div>

        <!-- Schema Tab -->
        <div id="tab-schema" class="editor-panel-body" style="display:none;">
          <div class="admin-form-group">
            <label class="admin-form-label" for="schema_type">Schema Type</label>
            <select id="schema_type" name="schema_type" class="admin-form-select">
              <option value="">Auto-detect</option>
              <option value="Article"              <?= $schemaType === 'Article'              ? 'selected' : '' ?>>Article</option>
              <option value="BlogPosting"          <?= $schemaType === 'BlogPosting'          ? 'selected' : '' ?>>BlogPosting</option>
              <option value="NewsArticle"          <?= $schemaType === 'NewsArticle'          ? 'selected' : '' ?>>NewsArticle</option>
              <option value="FAQPage"              <?= $schemaType === 'FAQPage'              ? 'selected' : '' ?>>FAQPage</option>
              <option value="HowTo"                <?= $schemaType === 'HowTo'                ? 'selected' : '' ?>>HowTo</option>
              <option value="DefinedTerm"          <?= $schemaType === 'DefinedTerm'          ? 'selected' : '' ?>>DefinedTerm</option>
              <option value="Course"               <?= $schemaType === 'Course'               ? 'selected' : '' ?>>Course</option>
              <option value="SoftwareApplication"  <?= $schemaType === 'SoftwareApplication'  ? 'selected' : '' ?>>SoftwareApplication</option>
              <option value="Dataset"              <?= $schemaType === 'Dataset'              ? 'selected' : '' ?>>Dataset</option>
              <option value="Report"               <?= $schemaType === 'Report'               ? 'selected' : '' ?>>Report</option>
            </select>
          </div>
          <div class="admin-form-group">
            <label class="admin-form-label" for="schema_json">Custom Schema JSON <span style="color:var(--admin-muted);font-weight:400;">(optional override)</span></label>
            <textarea id="schema_json" name="schema_json" class="admin-form-textarea" rows="8"
                      style="font-family:monospace;font-size:12px;"
                      placeholder='{"@context":"https://schema.org","@type":"Article",…}'><?= e($schemaJson) ?></textarea>
          </div>
        </div>
      </div>

    </div>

    <!-- ── RIGHT COLUMN: Meta Panel ── -->
    <div style="display:flex;flex-direction:column;gap:14px;position:sticky;top:76px;">

      <!-- Publish Box -->
      <div style="background:var(--admin-surface);border:1px solid var(--admin-border);border-radius:12px;overflow:hidden;">
        <div style="padding:14px 16px;border-bottom:1px solid var(--admin-border);font-weight:700;font-size:13px;">Publish Settings</div>
        <div style="padding:16px;">
          <div class="admin-form-group">
            <label class="admin-form-label" for="status">Status</label>
            <select id="status" name="status" class="admin-form-select">
              <option value="draft"     <?= $status === 'draft'     ? 'selected' : '' ?>>📝 Draft</option>
              <option value="published" <?= $status === 'published' ? 'selected' : '' ?>>✅ Published</option>
              <option value="scheduled" <?= $status === 'scheduled' ? 'selected' : '' ?>>🗓 Scheduled</option>
              <option value="archived"  <?= $status === 'archived'  ? 'selected' : '' ?>>📦 Archived</option>
            </select>
          </div>
          <div class="admin-form-group">
            <label class="admin-form-label" for="lang">Language</label>
            <select id="lang" name="lang" class="admin-form-select">
              <option value="en" <?= $lang === 'en' ? 'selected' : '' ?>>🇬🇧 English</option>
              <option value="hi" <?= $lang === 'hi' ? 'selected' : '' ?>>🇮🇳 Hindi</option>
              <option value="ta" <?= $lang === 'ta' ? 'selected' : '' ?>>Tamil</option>
              <option value="te" <?= $lang === 'te' ? 'selected' : '' ?>>Telugu</option>
              <option value="mr" <?= $lang === 'mr' ? 'selected' : '' ?>>Marathi</option>
              <option value="bn" <?= $lang === 'bn' ? 'selected' : '' ?>>Bengali</option>
            </select>
          </div>
          <div class="admin-form-group">
            <label class="admin-form-label" for="content_type">Content Type</label>
            <select id="content_type" name="type" class="admin-form-select">
              <?php foreach (CONTENT_TYPES as $value => $label): ?>
              <option value="<?= $value ?>" <?= $contentType === $value ? 'selected' : '' ?>><?= $label ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="admin-form-group">
            <label class="admin-form-label" for="difficulty">Difficulty Level</label>
            <select id="difficulty" name="difficulty" class="admin-form-select">
              <option value="">— None —</option>
              <option value="beginner"     <?= ($item['difficulty'] ?? '') === 'beginner'     ? 'selected' : '' ?>>🟢 Beginner</option>
              <option value="intermediate" <?= ($item['difficulty'] ?? '') === 'intermediate' ? 'selected' : '' ?>>🟡 Intermediate</option>
              <option value="advanced"     <?= ($item['difficulty'] ?? '') === 'advanced'     ? 'selected' : '' ?>>🔴 Advanced</option>
            </select>
          </div>

          <div style="display:flex;gap:8px;margin-top:4px;">
            <button type="submit" class="admin-btn admin-btn-primary" style="flex:1;justify-content:center;">
              <?= $isNew ? '✨ Create' : '💾 Update' ?>
            </button>
            <?php if (!$isNew && $status !== 'published'): ?>
            <button type="submit" class="admin-btn admin-btn-success" onclick="document.getElementById('status').value='published'" title="Save and publish">🚀</button>
            <?php endif; ?>
          </div>
        </div>
      </div>

      <!-- Author -->
      <div style="background:var(--admin-surface);border:1px solid var(--admin-border);border-radius:12px;padding:16px;">
        <div style="font-weight:700;font-size:13px;margin-bottom:12px;">Author</div>
        <select id="author_id" name="author_id" class="admin-form-select">
          <option value="">— Select Author —</option>
          <?php foreach (($authors ?? []) as $author): ?>
          <option value="<?= $author['id'] ?>" <?= ($item['author_id'] ?? '') == $author['id'] ? 'selected' : '' ?>>
            <?= e($author['name']) ?>
          </option>
          <?php endforeach; ?>
        </select>
      </div>

      <!-- Categories -->
      <div style="background:var(--admin-surface);border:1px solid var(--admin-border);border-radius:12px;padding:16px;">
        <div style="font-weight:700;font-size:13px;margin-bottom:12px;">Categories</div>
        <div style="max-height:200px;overflow-y:auto;display:flex;flex-direction:column;gap:6px;">
          <?php foreach (($categories ?? []) as $cat): ?>
          <label style="display:flex;align-items:center;gap:8px;cursor:pointer;font-size:13px;padding:4px 6px;border-radius:6px;transition:background 0.15s;" onmouseover="this.style.background='rgba(255,255,255,0.04)'" onmouseout="this.style.background=''">
            <input type="checkbox" name="categories[]" value="<?= $cat['id'] ?>"
                   <?= in_array($cat['id'], $selectedCats) ? 'checked' : '' ?>
                   style="accent-color:var(--admin-brand);">
            <?= e($cat['name']) ?>
          </label>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- Tags -->
      <div style="background:var(--admin-surface);border:1px solid var(--admin-border);border-radius:12px;padding:16px;">
        <div style="font-weight:700;font-size:13px;margin-bottom:12px;">Tags</div>
        <input type="text" id="tagSearch" placeholder="Filter tags…" class="admin-form-input admin-form-input-sm" style="margin-bottom:8px;font-size:12px;" oninput="filterTags(this.value)">
        <div style="max-height:180px;overflow-y:auto;display:flex;flex-direction:column;gap:4px;" id="tagList">
          <?php foreach (($tags ?? []) as $tag): ?>
          <label class="tag-item" style="display:flex;align-items:center;gap:8px;cursor:pointer;font-size:13px;padding:3px 6px;border-radius:6px;transition:background 0.15s;" onmouseover="this.style.background='rgba(255,255,255,0.04)'" onmouseout="this.style.background=''">
            <input type="checkbox" name="tags[]" value="<?= $tag['id'] ?>"
                   <?= in_array($tag['id'], $selectedTags) ? 'checked' : '' ?>
                   style="accent-color:var(--admin-brand);">
            <?= e($tag['name']) ?>
          </label>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- Word count quick stats -->
      <div id="contentStats" style="background:var(--admin-surface);border:1px solid var(--admin-border);border-radius:12px;padding:14px;font-size:12px;color:var(--admin-muted);display:none;">
        <div style="display:flex;justify-content:space-between;margin-bottom:4px;"><span>Words</span><strong id="statWords" style="color:var(--admin-text);">0</strong></div>
        <div style="display:flex;justify-content:space-between;margin-bottom:4px;"><span>Read time</span><strong id="statReadTime" style="color:var(--admin-text);">0 min</strong></div>
        <div style="display:flex;justify-content:space-between;"><span>Characters</span><strong id="statChars" style="color:var(--admin-text);">0</strong></div>
      </div>

    </div>

  </div>
</form>

<script>
// ── Slug auto-generation ──────────────────────────────────────
let slugEdited = <?= $isNew ? 'false' : 'true' ?>;
document.getElementById('slug').addEventListener('input', () => slugEdited = true);

function autoSlug(val) {
  if (slugEdited) return;
  document.getElementById('slug').value = val
    .toLowerCase()
    .replace(/[^\w\s-]/g, '')
    .trim()
    .replace(/[\s_]+/g, '-')
    .replace(/-{2,}/g, '-')
    .substring(0, 200);
}

function regenerateSlug() {
  slugEdited = false;
  autoSlug(document.getElementById('title').value);
  slugEdited = false;
}

// ── Char counter ─────────────────────────────────────────────
function updateCharCount(fieldId, counterId, max) {
  const len  = document.getElementById(fieldId).value.length;
  const el   = document.getElementById(counterId);
  el.textContent = len + '/' + max;
  el.className = 'char-counter' + (len > max ? ' over' : len > max * 0.9 ? ' warning' : '');
}

// Init char counters
['meta_title','meta_description','excerpt'].forEach(id => {
  const el = document.getElementById(id);
  if (el) el.dispatchEvent(new Event('input'));
});

// ── Quill.js WYSIWYG Initialization ──────────────────────────
const quillEditor = new Quill('#quillEditor', {
  theme: 'snow',
  placeholder: 'Start writing your content…',
  modules: {
    toolbar: [
      [{ 'header': [2, 3, 4, false] }],
      ['bold', 'italic', 'underline', 'strike'],
      [{ 'list': 'ordered'}, { 'list': 'bullet' }],
      ['blockquote', 'code-block'],
      ['link', 'image', 'video'],
      [{ 'align': [] }],
      ['clean']
    ]
  }
});

// Load existing content into Quill
const initialHtml = document.getElementById('bodyContent').value;
if (initialHtml) {
  quillEditor.root.innerHTML = initialHtml;
}

// ── Sync Quill → hidden textarea on form submit ──────────────
document.getElementById('contentForm').addEventListener('submit', function() {
  const html = quillEditor.root.innerHTML;
  document.getElementById('bodyContent').value = (html === '<p><br></p>') ? '' : html;
});

// Track last active source so we can sync between tabs
let lastEditedIn = 'visual'; // 'visual' or 'html'

// ── Tab switcher (Quill-aware) ───────────────────────────────
function switchTab(name, tabId, btn) {
  const panel = btn.closest('.editor-panel');
  panel.querySelectorAll('.editor-panel-body').forEach(t => t.style.display = 'none');
  panel.querySelectorAll('.editor-tab').forEach(t => t.classList.remove('active'));
  document.getElementById(tabId).style.display = '';
  btn.classList.add('active');

  if (name === 'editor') {
    // If user was editing raw HTML, push it into Quill
    if (lastEditedIn === 'html') {
      quillEditor.root.innerHTML = document.getElementById('htmlSource').value;
    }
    lastEditedIn = 'visual';
  }
  if (name === 'preview') {
    const html = lastEditedIn === 'html'
      ? document.getElementById('htmlSource').value
      : quillEditor.root.innerHTML;
    document.getElementById('previewContent').innerHTML = html;
  }
  if (name === 'html') {
    // Sync Quill → HTML source textarea
    if (lastEditedIn === 'visual') {
      document.getElementById('htmlSource').value = quillEditor.root.innerHTML;
    }
    lastEditedIn = 'html';
  }
}

// Also sync htmlSource back on form submit
document.getElementById('contentForm').addEventListener('submit', function() {
  if (lastEditedIn === 'html') {
    const rawHtml = document.getElementById('htmlSource').value;
    document.getElementById('bodyContent').value = rawHtml;
  }
});

// ── Word count (Quill-aware) ─────────────────────────────────
function updateWordCount() {
  const text  = quillEditor.getText();
  const words = text.trim().split(/\s+/).filter(w => w.length > 0).length;
  const rt    = Math.max(1, Math.ceil(words / 200));
  const chars = text.trim().length;

  const stats = document.getElementById('contentStats');
  if (stats) {
    stats.style.display = '';
    document.getElementById('statWords').textContent   = words.toLocaleString();
    document.getElementById('statReadTime').textContent = rt + ' min';
    document.getElementById('statChars').textContent   = chars.toLocaleString();
  }

  const disp = document.getElementById('wordCountDisplay');
  if (disp) disp.textContent = words.toLocaleString() + ' words · ' + rt + ' min read';
}
quillEditor.on('text-change', updateWordCount);
updateWordCount();

// ── Tag filter ───────────────────────────────────────────────
function filterTags(q) {
  document.querySelectorAll('.tag-item').forEach(el => {
    el.style.display = el.textContent.toLowerCase().includes(q.toLowerCase()) ? '' : 'none';
  });
}
</script>

