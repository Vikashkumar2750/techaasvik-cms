<!-- Admin Author Edit/Create -->
<?php
$isNew = empty($author);
$a     = $author ?? [];
$social = json_decode($a['social_links'] ?? '{}', true) ?: [];
$actionUrl = $isNew ? '/techaasvik_admin/authors/store' : "/techaasvik_admin/authors/{$a['id']}/update";
?>

<div class="admin-page-header">
  <div>
    <h1 class="admin-page-title"><?= $isNew ? 'New Author' : 'Edit Author' ?></h1>
  </div>
  <a href="/techaasvik_admin/authors" class="admin-btn admin-btn-secondary">← Back to Authors</a>
</div>

<form method="post" action="<?= $actionUrl ?>" style="max-width:720px;">
  <input type="hidden" name="_csrf_token" value="<?= \Core\Auth::csrfToken() ?>">

  <!-- Name -->
  <div class="admin-form-group">
    <label class="admin-form-label" for="name">Full Name *</label>
    <input type="text" id="name" name="name" class="admin-form-input" required
           value="<?= htmlspecialchars($a['name'] ?? '') ?>" placeholder="e.g. Vikas Dhiman">
  </div>

  <!-- Slug -->
  <div class="admin-form-group">
    <label class="admin-form-label" for="slug">URL Slug</label>
    <input type="text" id="slug" name="slug" class="admin-form-input"
           value="<?= htmlspecialchars($a['slug'] ?? '') ?>" placeholder="auto-generated from name"
           style="font-family:monospace;font-size:13px;">
  </div>

  <!-- Credentials -->
  <div class="admin-form-group">
    <label class="admin-form-label" for="credentials">Credentials / Title</label>
    <input type="text" id="credentials" name="credentials" class="admin-form-input"
           value="<?= htmlspecialchars($a['credentials'] ?? '') ?>" placeholder="e.g. Google Ads Certified, 8+ years in Digital Marketing">
  </div>

  <!-- Short Bio -->
  <div class="admin-form-group">
    <label class="admin-form-label" for="short_bio">Short Bio <span class="admin-form-hint">(used in author cards)</span></label>
    <textarea id="short_bio" name="short_bio" class="admin-form-textarea" rows="2"
              placeholder="1-2 sentence bio…"><?= htmlspecialchars($a['short_bio'] ?? '') ?></textarea>
  </div>

  <!-- Full Bio -->
  <div class="admin-form-group">
    <label class="admin-form-label" for="bio">Full Bio <span class="admin-form-hint">(HTML allowed)</span></label>
    <textarea id="bio" name="bio" class="admin-form-textarea" rows="6"
              placeholder="Detailed author biography…"><?= htmlspecialchars($a['bio'] ?? '') ?></textarea>
  </div>

  <!-- Email -->
  <div class="admin-form-group">
    <label class="admin-form-label" for="email">Email</label>
    <input type="email" id="email" name="email" class="admin-form-input"
           value="<?= htmlspecialchars($a['email'] ?? '') ?>" placeholder="author@example.com">
  </div>

  <!-- Social Links -->
  <div style="border:1px solid var(--admin-border);border-radius:10px;padding:16px;margin-bottom:16px;">
    <h3 style="font-size:14px;margin-bottom:12px;">🔗 Social Links</h3>
    <div class="admin-form-group">
      <label class="admin-form-label" for="twitter">Twitter / X</label>
      <input type="url" id="twitter" name="twitter" class="admin-form-input"
             value="<?= htmlspecialchars($social['twitter'] ?? '') ?>" placeholder="https://twitter.com/handle">
    </div>
    <div class="admin-form-group">
      <label class="admin-form-label" for="linkedin">LinkedIn</label>
      <input type="url" id="linkedin" name="linkedin" class="admin-form-input"
             value="<?= htmlspecialchars($social['linkedin'] ?? '') ?>" placeholder="https://linkedin.com/in/profile">
    </div>
    <div class="admin-form-group">
      <label class="admin-form-label" for="website">Personal Website</label>
      <input type="url" id="website" name="website" class="admin-form-input"
             value="<?= htmlspecialchars($social['website'] ?? '') ?>" placeholder="https://example.com">
    </div>
  </div>

  <!-- Photo ID (hidden, set via media picker) -->
  <div class="admin-form-group">
    <label class="admin-form-label">Profile Photo ID</label>
    <input type="number" name="photo_id" class="admin-form-input" style="max-width:120px;"
           value="<?= (int)($a['photo_id'] ?? 0) ?>" placeholder="Media ID">
    <span class="admin-form-hint">Enter a media library ID, or 0 for default avatar.</span>
  </div>

  <!-- Active -->
  <div class="admin-form-group" style="display:flex;align-items:center;gap:8px;">
    <input type="checkbox" id="is_active" name="is_active" value="1"
           <?= ($a['is_active'] ?? 1) ? 'checked' : '' ?>>
    <label for="is_active" style="cursor:pointer;">Author is active (visible on site)</label>
  </div>

  <div style="display:flex;gap:12px;margin-top:24px;">
    <button type="submit" class="admin-btn admin-btn-primary"><?= $isNew ? 'Create Author' : 'Save Changes' ?></button>
    <a href="/techaasvik_admin/authors" class="admin-btn admin-btn-secondary">Cancel</a>
  </div>
</form>
