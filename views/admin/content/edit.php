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
$featuredImage = $item['featured_image'] ?? '';
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
            <input type="url" id="canonical_url" name="canonical_url" class="admin-form-input" value="<?= e($canonical) ?>" placeholder="https://www.techaasvik.com/…">
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

      <!-- Featured Image -->
      <div style="background:var(--admin-surface);border:1px solid var(--admin-border);border-radius:12px;padding:16px;">
        <div style="font-weight:700;font-size:13px;margin-bottom:12px;">Featured Image</div>
        <input type="hidden" id="featured_image" name="featured_image" value="<?= e($featuredImage) ?>">
        <div id="featuredImagePreview" style="border-radius:8px;overflow:hidden;margin-bottom:10px;background:var(--admin-elevated);min-height:60px;display:flex;align-items:center;justify-content:center;">
          <?php if ($featuredImage): ?>
            <img src="<?= e($featuredImage) ?>" style="width:100%;display:block;border-radius:8px;" alt="Featured">
          <?php else: ?>
            <div style="padding:24px;text-align:center;color:var(--admin-muted);font-size:12px;">No image selected</div>
          <?php endif; ?>
        </div>
        <div style="display:flex;gap:6px;">
          <button type="button" class="admin-btn admin-btn-secondary admin-btn-sm" style="flex:1;justify-content:center;font-size:12px;" onclick="openMediaPicker()">
            📷 Select Image
          </button>
          <button type="button" class="admin-btn admin-btn-ghost admin-btn-sm" style="font-size:12px;" onclick="removeFeaturedImage()" title="Remove">
            ✕
          </button>
        </div>
        <!-- Quick Upload -->
        <div style="margin-top:8px;">
          <label style="display:flex;align-items:center;justify-content:center;gap:6px;padding:8px;border:1px dashed var(--admin-border);border-radius:8px;cursor:pointer;font-size:11px;color:var(--admin-muted);transition:all 0.2s;" onmouseover="this.style.borderColor='var(--admin-brand)'" onmouseout="this.style.borderColor='var(--admin-border)'">
            ⬆️ Upload New
            <input type="file" id="featuredImageUpload" accept="image/*" style="display:none;" onchange="uploadFeaturedImage(this)">
          </label>
        </div>
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

      <!-- SEO Score Panel -->
      <div class="seo-score-panel" id="seoScorePanel">
        <div class="seo-score-header">
          <h3>🔍 SEO Score</h3>
          <div class="seo-score-circle score-poor" id="seoScoreCircle">0</div>
        </div>
        <div class="seo-check-list" id="seoCheckList"></div>
      </div>

      <!-- GEO Score Panel -->
      <div class="seo-score-panel" id="geoScorePanel" style="margin-top:14px;">
        <div class="seo-score-header">
          <h3>🌐 GEO Score</h3>
          <div class="seo-score-circle score-poor" id="geoScoreCircle">0</div>
        </div>
        <div class="seo-check-list" id="geoCheckList"></div>
      </div>

    </div>

  </div>
</form>

<!-- Image Resize Context Menu -->
<div id="imgResizeMenu" style="display:none;position:fixed;background:var(--admin-surface);border:1px solid var(--admin-border);border-radius:8px;box-shadow:0 8px 24px rgba(0,0,0,0.5);z-index:500;padding:6px;min-width:140px;">
  <div onclick="resizeImg('25%')" style="padding:6px 12px;cursor:pointer;font-size:12px;border-radius:4px;color:var(--admin-text);" onmouseover="this.style.background='var(--admin-elevated)'" onmouseout="this.style.background=''">📐 25% width</div>
  <div onclick="resizeImg('50%')" style="padding:6px 12px;cursor:pointer;font-size:12px;border-radius:4px;color:var(--admin-text);" onmouseover="this.style.background='var(--admin-elevated)'" onmouseout="this.style.background=''">📐 50% width</div>
  <div onclick="resizeImg('75%')" style="padding:6px 12px;cursor:pointer;font-size:12px;border-radius:4px;color:var(--admin-text);" onmouseover="this.style.background='var(--admin-elevated)'" onmouseout="this.style.background=''">📐 75% width</div>
  <div onclick="resizeImg('100%')" style="padding:6px 12px;cursor:pointer;font-size:12px;border-radius:4px;color:var(--admin-text);" onmouseover="this.style.background='var(--admin-elevated)'" onmouseout="this.style.background=''">📐 Full width</div>
  <div style="height:1px;background:var(--admin-border);margin:4px 0;"></div>
  <div onclick="resizeImg('left')" style="padding:6px 12px;cursor:pointer;font-size:12px;border-radius:4px;color:var(--admin-text);" onmouseover="this.style.background='var(--admin-elevated)'" onmouseout="this.style.background=''">⬅️ Float left</div>
  <div onclick="resizeImg('right')" style="padding:6px 12px;cursor:pointer;font-size:12px;border-radius:4px;color:var(--admin-text);" onmouseover="this.style.background='var(--admin-elevated)'" onmouseout="this.style.background=''">➡️ Float right</div>
  <div onclick="resizeImg('none')" style="padding:6px 12px;cursor:pointer;font-size:12px;border-radius:4px;color:var(--admin-text);" onmouseover="this.style.background='var(--admin-elevated)'" onmouseout="this.style.background=''">↩️ No float</div>
</div>

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
if (typeof Quill === 'undefined') {
  console.error('Quill.js failed to load. Check CSP headers and CDN access.');
  document.getElementById('tab-editor').innerHTML =
    '<div style="padding:20px;color:#f87171;">❌ Rich text editor failed to load. Please check your browser console for errors.<br>Using HTML Source tab instead.</div>';
  // Fallback: switch to HTML source tab
  document.querySelector('.editor-tab:nth-child(3)')?.click();
}

const quillEditor = typeof Quill !== 'undefined' ? new Quill('#quillEditor', {
  theme: 'snow',
  placeholder: 'Start writing your content…',
  modules: {
    toolbar: [
      [{ 'header': [1, 2, 3, 4, 5, false] }],
      [{ 'font': [] }],
      [{ 'size': ['small', false, 'large', 'huge'] }],
      ['bold', 'italic', 'underline', 'strike'],
      [{ 'color': [] }, { 'background': [] }],
      [{ 'script': 'sub'}, { 'script': 'super' }],
      [{ 'list': 'ordered'}, { 'list': 'bullet' }, { 'indent': '-1'}, { 'indent': '+1' }],
      [{ 'direction': 'rtl' }, { 'align': [] }],
      ['blockquote', 'code-block'],
      ['link', 'image', 'video'],
      ['clean']
    ]
  }
}) : null;

// Load existing content into Quill
const initialHtml = document.getElementById('bodyContent').value;
if (quillEditor && initialHtml) {
  quillEditor.root.innerHTML = initialHtml;
}

// ── Sync Quill → hidden textarea on form submit ──────────────
document.getElementById('contentForm').addEventListener('submit', function() {
  if (quillEditor) {
    const html = quillEditor.root.innerHTML;
    document.getElementById('bodyContent').value = (html === '<p><br></p>') ? '' : html;
  }
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

  if (name === 'editor' && quillEditor) {
    if (lastEditedIn === 'html') {
      quillEditor.root.innerHTML = document.getElementById('htmlSource').value;
    }
    lastEditedIn = 'visual';
  }
  if (name === 'preview') {
    const html = (lastEditedIn === 'html' || !quillEditor)
      ? document.getElementById('htmlSource').value
      : quillEditor.root.innerHTML;
    document.getElementById('previewContent').innerHTML = html;
  }
  if (name === 'html') {
    if (lastEditedIn === 'visual' && quillEditor) {
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
  if (!quillEditor) return;
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
if (quillEditor) {
  quillEditor.on('text-change', updateWordCount);
  updateWordCount();
}

// ── Tag filter ───────────────────────────────────────────────
function filterTags(q) {
  document.querySelectorAll('.tag-item').forEach(el => {
    el.style.display = el.textContent.toLowerCase().includes(q.toLowerCase()) ? '' : 'none';
  });
}

// ── Featured Image ───────────────────────────────────────────
function uploadFeaturedImage(input) {
  const file = input.files[0];
  if (!file) return;

  const fd = new FormData();
  fd.append('file', file);

  const preview = document.getElementById('featuredImagePreview');
  preview.innerHTML = '<div style="padding:16px;text-align:center;color:var(--admin-muted);font-size:12px;">⏳ Uploading...</div>';

  fetch('/techaasvik_admin/media/upload', {
    method: 'POST',
    body: fd
  })
  .then(r => r.json())
  .then(data => {
    if (data.success) {
      document.getElementById('featured_image').value = data.url;
      preview.innerHTML = '<img src="' + data.url + '" style="width:100%;display:block;border-radius:8px;" alt="Featured">';
    } else {
      preview.innerHTML = '<div style="padding:16px;text-align:center;color:#f87171;font-size:12px;">❌ ' + data.message + '</div>';
    }
  })
  .catch(err => {
    preview.innerHTML = '<div style="padding:16px;text-align:center;color:#f87171;font-size:12px;">❌ Upload failed</div>';
  });

  input.value = '';
}

function removeFeaturedImage() {
  document.getElementById('featured_image').value = '';
  document.getElementById('featuredImagePreview').innerHTML =
    '<div style="padding:24px;text-align:center;color:var(--admin-muted);font-size:12px;">No image selected</div>';
}

function openMediaPicker() {
  document.getElementById('mediaPickerModal').style.display = 'flex';
  loadMediaLibrary();
}

function closeMediaPicker() {
  document.getElementById('mediaPickerModal').style.display = 'none';
}

function loadMediaLibrary() {
  fetch('/techaasvik_admin/media/api')
    .then(r => r.json())
    .then(data => {
      const grid = document.getElementById('mediaPickerGrid');
      if (!data.items || data.items.length === 0) {
        grid.innerHTML = '<p style="color:var(--admin-muted);text-align:center;padding:40px;">No images yet. Upload one above.</p>';
        return;
      }
      grid.innerHTML = data.items.map(item =>
        '<div onclick="selectMedia(\'' + item.filepath + '\')" style="cursor:pointer;border-radius:8px;overflow:hidden;aspect-ratio:1;background:var(--admin-elevated);border:2px solid transparent;transition:border 0.15s;" onmouseover="this.style.borderColor=\'var(--admin-brand)\'" onmouseout="this.style.borderColor=\'transparent\'">' +
        '<img src="' + item.filepath + '" style="width:100%;height:100%;object-fit:cover;" alt="' + (item.alt_text||'') + '">' +
        '</div>'
      ).join('');
    })
    .catch(() => {
      document.getElementById('mediaPickerGrid').innerHTML = '<p style="color:#f87171;text-align:center;">Failed to load media</p>';
    });
}

function selectMedia(url) {
  document.getElementById('featured_image').value = url;
  document.getElementById('featuredImagePreview').innerHTML =
    '<img src="' + url + '" style="width:100%;display:block;border-radius:8px;" alt="Featured">';
  closeMediaPicker();
}

// ── Quill Image Handler (insert from URL or upload) ──────────
if (quillEditor) quillEditor.getModule('toolbar').addHandler('image', function() {
  const input = document.createElement('input');
  input.type = 'file';
  input.accept = 'image/*';
  input.onchange = function() {
    const file = input.files[0];
    if (!file) return;
    const fd = new FormData();
    fd.append('file', file);
    fetch('/techaasvik_admin/media/upload', { method: 'POST', body: fd })
      .then(r => r.json())
      .then(data => {
        if (data.success) {
          const range = quillEditor.getSelection(true);
          quillEditor.insertEmbed(range.index, 'image', data.url);
        }
      });
  };
  input.click();
});
</script>

<!-- Media Picker Modal -->
<div id="mediaPickerModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.8);z-index:1000;align-items:center;justify-content:center;padding:24px;">
  <div style="background:var(--admin-bg);border-radius:16px;width:100%;max-width:800px;max-height:80vh;overflow:hidden;display:flex;flex-direction:column;">
    <div style="padding:16px 20px;border-bottom:1px solid var(--admin-border);display:flex;justify-content:space-between;align-items:center;">
      <h3 style="margin:0;font-size:16px;">Select from Media Library</h3>
      <button onclick="closeMediaPicker()" style="background:none;border:none;color:var(--admin-muted);cursor:pointer;font-size:20px;">&times;</button>
    </div>
    <div style="padding:16px 20px;overflow-y:auto;flex:1;">
      <div id="mediaPickerGrid" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(120px,1fr));gap:10px;">
        <p style="color:var(--admin-muted);text-align:center;">Loading...</p>
      </div>
    </div>
  </div>
</div>

<script>
// ── Image Resize (right-click on images in editor) ───────────
let selectedImg = null;
if (quillEditor) {
  quillEditor.root.addEventListener('contextmenu', function(e) {
    if (e.target.tagName === 'IMG') {
      e.preventDefault();
      selectedImg = e.target;
      const menu = document.getElementById('imgResizeMenu');
      menu.style.display = 'block';
      menu.style.left = e.clientX + 'px';
      menu.style.top = e.clientY + 'px';
    }
  });
}
document.addEventListener('click', () => {
  document.getElementById('imgResizeMenu').style.display = 'none';
});

function resizeImg(val) {
  if (!selectedImg) return;
  if (['left','right','none'].includes(val)) {
    selectedImg.style.float = val === 'none' ? '' : val;
    if (val !== 'none') selectedImg.style.margin = val === 'left' ? '0 16px 10px 0' : '0 0 10px 16px';
    else selectedImg.style.margin = '';
  } else {
    selectedImg.style.width = val;
    selectedImg.style.height = 'auto';
  }
  document.getElementById('imgResizeMenu').style.display = 'none';
}

// ── SEO/GEO Real-time Scoring Engine ─────────────────────────
function runSeoGeoAnalysis() {
  const title = document.getElementById('title')?.value || '';
  const slug = document.getElementById('slug')?.value || '';
  const excerpt = document.getElementById('excerpt')?.value || '';
  const metaTitle = document.getElementById('meta_title')?.value || '';
  const metaDesc = document.getElementById('meta_description')?.value || '';
  const body = quillEditor ? quillEditor.root.innerHTML : (document.getElementById('htmlSource')?.value || '');
  const bodyText = quillEditor ? quillEditor.getText() : body.replace(/<[^>]+>/g, '');
  const wordCount = bodyText.trim().split(/\s+/).filter(w => w.length > 0).length;
  const canonical = document.getElementById('canonical_url')?.value || '';
  const ogTitle = document.getElementById('og_title')?.value || '';
  const ogDesc = document.getElementById('og_description')?.value || '';
  const schemaType = document.getElementById('schema_type')?.value || '';
  const featuredImg = document.getElementById('featured_image')?.value || '';
  const authorId = document.getElementById('author_id')?.value || '';

  // Count elements in body
  const imgCount = (body.match(/<img /gi) || []).length;
  const linkCount = (body.match(/<a /gi) || []).length;
  const h2Count = (body.match(/<h2/gi) || []).length;
  const h3Count = (body.match(/<h3/gi) || []).length;
  const hasAltMissing = /<img(?![^>]*alt=["'][^"']+["'])/i.test(body);

  // ── SEO CHECKS ──────────────────────────────────────────
  const seoChecks = [
    {
      name: 'Title length',
      pass: title.length >= 30 && title.length <= 70,
      warn: title.length >= 15 && title.length < 30,
      msg: title.length === 0 ? 'Add a title (30-70 chars)' :
           title.length < 30 ? `Title too short: <strong>${title.length}</strong>/30 min` :
           title.length > 70 ? `Title too long: <strong>${title.length}</strong>/70 max` :
           `Title length: <strong>${title.length}</strong> chars ✓`,
      target: '30-70 characters'
    },
    {
      name: 'Meta description',
      pass: metaDesc.length >= 120 && metaDesc.length <= 160,
      warn: metaDesc.length >= 50 && metaDesc.length < 120,
      msg: metaDesc.length === 0 ? 'Add meta description (120-160 chars)' :
           metaDesc.length < 120 ? `Too short: <strong>${metaDesc.length}</strong>/120 min` :
           metaDesc.length > 160 ? `Too long: <strong>${metaDesc.length}</strong>/160 max` :
           `Length: <strong>${metaDesc.length}</strong> chars ✓`,
      target: '120-160 characters'
    },
    {
      name: 'Content length',
      pass: wordCount >= 1000,
      warn: wordCount >= 300,
      msg: wordCount < 300 ? `Only <strong>${wordCount}</strong> words. Need 1000+ for SEO` :
           wordCount < 1000 ? `<strong>${wordCount}</strong> words. Aim for 1000+` :
           `<strong>${wordCount}</strong> words ✓`,
      target: '1000+ words'
    },
    {
      name: 'URL slug',
      pass: slug.length > 0 && slug.length <= 60 && !slug.includes('_'),
      warn: slug.length > 60,
      msg: slug.length === 0 ? 'Add URL slug' :
           slug.length > 60 ? `Slug too long: <strong>${slug.length}</strong> chars` :
           'Clean URL slug ✓',
      target: 'Under 60 chars, hyphens only'
    },
    {
      name: 'Headings (H2/H3)',
      pass: h2Count >= 2 && h3Count >= 1,
      warn: h2Count >= 1,
      msg: h2Count === 0 ? 'Add H2 subheadings for structure' :
           h3Count === 0 ? `${h2Count} H2 found. Add H3 for deeper structure` :
           `${h2Count} H2 + ${h3Count} H3 headings ✓`,
      target: '2+ H2, 1+ H3'
    },
    {
      name: 'Images',
      pass: imgCount >= 1 && !hasAltMissing,
      warn: imgCount >= 1,
      msg: imgCount === 0 ? 'Add at least 1 image' :
           hasAltMissing ? `${imgCount} image(s) but missing alt text` :
           `${imgCount} image(s) with alt text ✓`,
      target: '1+ images with alt text'
    },
    {
      name: 'Featured image',
      pass: featuredImg.length > 0,
      warn: false,
      msg: featuredImg ? 'Featured image set ✓' : 'Add a featured image',
      target: 'Required for social sharing'
    },
    {
      name: 'Internal links',
      pass: linkCount >= 2,
      warn: linkCount >= 1,
      msg: linkCount === 0 ? 'Add internal links to other pages' :
           linkCount < 2 ? `Only ${linkCount} link. Add 2+ internal links` :
           `${linkCount} links found ✓`,
      target: '2+ internal links'
    },
    {
      name: 'Excerpt / Summary',
      pass: excerpt.length >= 100 && excerpt.length <= 320,
      warn: excerpt.length >= 30,
      msg: excerpt.length === 0 ? 'Add excerpt for listings & AI feeds' :
           excerpt.length < 100 ? `Excerpt short: <strong>${excerpt.length}</strong>/100 min` :
           `Excerpt: <strong>${excerpt.length}</strong> chars ✓`,
      target: '100-320 characters'
    },
    {
      name: 'Canonical URL',
      pass: true, // Auto-generated if empty
      warn: false,
      msg: canonical ? `Set to: ${canonical.substring(0,40)}...` : 'Auto-generated ✓',
      target: 'Auto or custom'
    }
  ];

  // ── GEO CHECKS (Generative Engine Optimization) ─────────
  const geoChecks = [
    {
      name: 'Author attribution',
      pass: authorId.length > 0 && authorId !== '',
      warn: false,
      msg: authorId ? 'Author assigned ✓ (E-E-A-T signal)' : 'Assign an author for E-E-A-T credibility',
      target: 'Required for E-E-A-T'
    },
    {
      name: 'Schema markup',
      pass: schemaType.length > 0,
      warn: false,
      msg: schemaType ? `Schema: <strong>${schemaType}</strong> ✓` : 'Select schema type for AI indexing',
      target: 'Article, BlogPosting, etc.'
    },
    {
      name: 'OG tags for AI sharing',
      pass: (ogTitle.length > 0 || metaTitle.length > 0) && (ogDesc.length > 0 || metaDesc.length > 0),
      warn: metaTitle.length > 0 || metaDesc.length > 0,
      msg: ogTitle || metaTitle ? 'Social/AI sharing tags set ✓' : 'Add OG title & description',
      target: 'Title + Description + Image'
    },
    {
      name: 'Structured content',
      pass: h2Count >= 3 && h3Count >= 2,
      warn: h2Count >= 2,
      msg: h2Count < 2 ? 'Need more headings for AI parsing' :
           `${h2Count}×H2 + ${h3Count}×H3 — ${h2Count >= 3 ? 'Well structured ✓' : 'Add more subheadings'}`,
      target: '3+ H2, 2+ H3 for AI parsing'
    },
    {
      name: 'Content depth',
      pass: wordCount >= 1500,
      warn: wordCount >= 800,
      msg: wordCount < 800 ? `<strong>${wordCount}</strong> words — AI prefers 1500+` :
           wordCount < 1500 ? `<strong>${wordCount}</strong> words — aim for 1500+ for AI citations` :
           `<strong>${wordCount}</strong> words — deep content ✓`,
      target: '1500+ words for AI citation'
    },
    {
      name: 'Listicles & formatting',
      pass: (body.match(/<li/gi) || []).length >= 3,
      warn: (body.match(/<li/gi) || []).length >= 1,
      msg: (body.match(/<li/gi) || []).length === 0 ? 'Add bullet/numbered lists for AI extraction' :
           `${(body.match(/<li/gi) || []).length} list items ✓`,
      target: '3+ list items for AI snippets'
    },
    {
      name: 'Quotable statements',
      pass: (body.match(/<blockquote/gi) || []).length >= 1,
      warn: false,
      msg: (body.match(/<blockquote/gi) || []).length > 0 ? 'Has quotable content ✓' : 'Add blockquotes — AI loves citable statements',
      target: '1+ blockquote for AI citation'
    },
    {
      name: 'Concise intro paragraph',
      pass: bodyText.length > 0 && bodyText.substring(0, 300).includes('.'),
      warn: bodyText.length > 0,
      msg: bodyText.length === 0 ? 'Write content with a clear intro' :
           bodyText.substring(0, 300).includes('.') ? 'Has clear intro paragraph ✓' : 'Start with a concise, direct answer',
      target: 'Answer query in first 200 chars'
    }
  ];

  // Render panels
  renderScorePanel('seoCheckList', 'seoScoreCircle', seoChecks);
  renderScorePanel('geoCheckList', 'geoScoreCircle', geoChecks);
}

function renderScorePanel(listId, circleId, checks) {
  let passed = 0;
  const total = checks.length;
  let html = '';

  checks.forEach(c => {
    const status = c.pass ? 'pass' : (c.warn ? 'warn' : 'fail');
    const icon = c.pass ? '✓' : (c.warn ? '!' : '✕');
    if (c.pass) passed++;
    else if (c.warn) passed += 0.5;

    html += `<div class="seo-check-item">
      <div class="seo-check-icon ${status}">${icon}</div>
      <div class="seo-check-text">
        <strong>${c.name}</strong><br>${c.msg}
        <span class="seo-target-badge ${c.pass ? 'target-met' : 'target-miss'}">Target: ${c.target}</span>
      </div>
    </div>`;
  });

  document.getElementById(listId).innerHTML = html;

  const score = Math.round((passed / total) * 100);
  const circle = document.getElementById(circleId);
  circle.textContent = score;
  circle.className = 'seo-score-circle ' +
    (score >= 70 ? 'score-good' : score >= 40 ? 'score-ok' : 'score-poor');
}

// Run analysis on load and on changes
runSeoGeoAnalysis();
if (quillEditor) quillEditor.on('text-change', runSeoGeoAnalysis);
['title','slug','excerpt','meta_title','meta_description','canonical_url','og_title','og_description','author_id','schema_type','featured_image'].forEach(id => {
  const el = document.getElementById(id);
  if (el) el.addEventListener('input', runSeoGeoAnalysis);
  if (el) el.addEventListener('change', runSeoGeoAnalysis);
});
</script>
