<!-- Admin Settings -->
<div class="admin-page-header">
  <div>
    <h1 class="admin-page-title">Site Settings</h1>
    <p class="admin-page-subtitle">Configure your platform — all changes are saved immediately.</p>
  </div>
  <button form="settingsForm" type="submit" class="admin-btn admin-btn-primary">💾 Save All Settings</button>
</div>

<form method="post" action="/techaasvik_admin/settings/update" id="settingsForm" novalidate>
  <input type="hidden" name="_csrf_token" value="<?= \Core\Auth::csrfToken() ?>">

  <!-- Tab navigation -->
  <div style="display:flex;gap:4px;margin-bottom:20px;flex-wrap:wrap;border-bottom:1px solid var(--admin-border);padding-bottom:12px;">
    <?php foreach (array_keys($groups) as $i => $group): ?>
    <button type="button" onclick="switchSettingsTab('tab-<?= strtolower($group) ?>',this)"
            class="admin-btn admin-btn-ghost admin-btn-sm settings-tab <?= $i === 0 ? 'active' : '' ?>">
      <?= $group ?>
    </button>
    <?php endforeach; ?>
  </div>

  <?php foreach ($groups as $groupName => $fields): ?>
  <div id="tab-<?= strtolower($groupName) ?>" class="settings-panel" style="<?= array_key_first($groups) === $groupName ? '' : 'display:none' ?>">
    <div style="background:var(--admin-surface);border:1px solid var(--admin-border);border-radius:12px;padding:24px;">
      <h2 style="font-size:15px;font-weight:700;margin-bottom:20px;color:var(--admin-text);"><?= $groupName ?> Settings</h2>
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
        <?php foreach ($fields as $field): ?>
        <div class="admin-form-group">
          <label class="admin-form-label" for="<?= $field['key'] ?>"><?= ucwords(str_replace('_', ' ', $field['key'])) ?></label>
          <?php if (in_array($field['key'], ['about_text','footer_text'])): ?>
          <textarea id="<?= $field['key'] ?>" name="<?= $field['key'] ?>" class="admin-form-textarea" rows="3"><?= htmlspecialchars($field['value']) ?></textarea>
          <?php elseif (str_contains($field['key'], 'pass')): ?>
          <input type="password" id="<?= $field['key'] ?>" name="<?= $field['key'] ?>" class="admin-form-input" value="<?= htmlspecialchars($field['value']) ?>" autocomplete="new-password">
          <?php elseif (in_array($field['key'], ['enable_comments','enable_hindi'])): ?>
          <select id="<?= $field['key'] ?>" name="<?= $field['key'] ?>" class="admin-form-select">
            <option value="1" <?= $field['value'] === '1' ? 'selected' : '' ?>>Enabled</option>
            <option value="0" <?= $field['value'] === '0' ? 'selected' : '' ?>>Disabled</option>
          </select>
          <?php else: ?>
          <input type="text" id="<?= $field['key'] ?>" name="<?= $field['key'] ?>" class="admin-form-input" value="<?= htmlspecialchars($field['value']) ?>">
          <?php endif; ?>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
  <?php endforeach; ?>

  <div style="margin-top:20px;display:flex;justify-content:flex-end;">
    <button type="submit" class="admin-btn admin-btn-primary">💾 Save All Settings</button>
  </div>
</form>

<script>
function switchSettingsTab(tabId, btn) {
  document.querySelectorAll('.settings-panel').forEach(p => p.style.display = 'none');
  document.querySelectorAll('.settings-tab').forEach(b => b.classList.remove('active'));
  document.getElementById(tabId).style.display = '';
  btn.classList.add('active');
}
</script>
