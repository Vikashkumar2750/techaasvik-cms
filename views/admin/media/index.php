<!-- Admin Media Library -->
<div class="admin-page-header">
  <div>
    <h1 class="admin-page-title">Media Library</h1>
    <p class="admin-page-subtitle"><?= number_format($total) ?> files uploaded</p>
  </div>
  <label class="admin-btn admin-btn-primary" style="cursor:pointer;">
    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
    Upload Files
    <input type="file" id="mediaUploadInput" accept="image/*,application/pdf" multiple style="display:none;">
  </label>
</div>

<!-- Drag & Drop Upload Zone -->
<div id="uploadZone" class="upload-zone" style="border:2px dashed var(--admin-border);border-radius:12px;padding:40px;text-align:center;margin-bottom:20px;cursor:pointer;transition:all 0.2s;" onmouseover="this.style.borderColor='var(--admin-brand)'" onmouseout="this.style.borderColor='var(--admin-border)'">
  <div style="font-size:36px;margin-bottom:8px;">🖼</div>
  <p style="color:var(--admin-muted);font-size:14px;">Drag and drop images here, or click Upload Files above.</p>
  <p style="color:var(--admin-muted);font-size:12px;margin-top:4px;">Max 5 MB · JPEG, PNG, WebP, GIF, SVG, PDF</p>
  <div id="uploadProgress" style="display:none;margin-top:12px;">
    <div style="height:4px;background:var(--admin-elevated);border-radius:2px;overflow:hidden;">
      <div id="uploadProgressBar" style="height:100%;width:0%;background:var(--admin-brand);transition:width 0.3s;"></div>
    </div>
    <p id="uploadStatus" style="font-size:12px;color:var(--admin-muted);margin-top:6px;"></p>
  </div>
</div>

<!-- Media Grid -->
<?php if (!empty($items)): ?>
<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(150px,1fr));gap:12px;" id="mediaGrid">
  <?php foreach ($items as $file): ?>
  <div class="media-item" data-id="<?= $file['id'] ?>" style="background:var(--admin-surface);border:1px solid var(--admin-border);border-radius:10px;overflow:hidden;position:relative;group;">
    <!-- Preview -->
    <?php if (str_starts_with($file['mime_type'] ?? '', 'image/')): ?>
    <div style="aspect-ratio:1;overflow:hidden;background:var(--admin-elevated);">
      <img src="<?= htmlspecialchars($file['filepath']) ?>" alt="<?= htmlspecialchars($file['alt_text'] ?? '') ?>" style="width:100%;height:100%;object-fit:cover;" loading="lazy">
    </div>
    <?php else: ?>
    <div style="aspect-ratio:1;display:flex;align-items:center;justify-content:center;background:var(--admin-elevated);font-size:32px;">📄</div>
    <?php endif; ?>

    <!-- Info -->
    <div style="padding:8px;">
      <p style="font-size:11px;color:var(--admin-text);overflow:hidden;text-overflow:ellipsis;white-space:nowrap;margin-bottom:2px;" title="<?= htmlspecialchars($file['filename']) ?>">
        <?= htmlspecialchars(mb_strimwidth($file['filename'], 0, 20, '…')) ?>
      </p>
      <p style="font-size:10px;color:var(--admin-muted);"><?= round(($file['filesize'] ?? 0) / 1024, 1) ?> KB</p>
    </div>

    <!-- Actions overlay -->
    <div style="position:absolute;inset:0;background:rgba(8,13,22,0.75);display:none;flex-direction:column;align-items:center;justify-content:center;gap:6px;border-radius:10px;" class="media-overlay">
      <button onclick="copyUrl('<?= htmlspecialchars($file['filepath']) ?>')" class="admin-btn admin-btn-secondary admin-btn-sm" style="width:110px;">🔗 Copy URL</button>
      <form method="post" action="/techaasvik_admin/media/<?= $file['id'] ?>/delete" onsubmit="return confirm('Delete this file permanently?')">
        <input type="hidden" name="_csrf_token" value="<?= \Core\Auth::csrfToken() ?>">
        <button type="submit" class="admin-btn admin-btn-danger admin-btn-sm" style="width:110px;">🗑 Delete</button>
      </form>
    </div>
  </div>
  <?php endforeach; ?>
</div>

<!-- Pagination -->
<?php if ($total > $perPage): ?>
<div style="display:flex;justify-content:center;gap:8px;margin-top:20px;">
  <?php for ($p = 1; $p <= ceil($total/$perPage); $p++): ?>
  <a href="?page=<?= $p ?>" class="admin-btn <?= $p === $page ? 'admin-btn-primary' : 'admin-btn-secondary' ?> admin-btn-sm"><?= $p ?></a>
  <?php endfor; ?>
</div>
<?php endif; ?>

<?php else: ?>
<div style="text-align:center;padding:60px;color:var(--admin-muted);">
  <div style="font-size:48px;margin-bottom:12px;">🖼</div>
  <p>No media uploaded yet. Upload your first image above!</p>
</div>
<?php endif; ?>

<script>
// Hover overlay for media items
document.querySelectorAll('.media-item').forEach(item => {
  const overlay = item.querySelector('.media-overlay');
  item.addEventListener('mouseenter', () => overlay.style.display = 'flex');
  item.addEventListener('mouseleave', () => overlay.style.display = 'none');
});

// Copy URL helper
function copyUrl(url) {
  navigator.clipboard.writeText(window.location.origin + url)
    .then(() => window.showFlash && showFlash('success', 'URL copied: ' + url));
}

// File input upload
const mediaInput = document.getElementById('mediaUploadInput');
if (mediaInput) {
  mediaInput.addEventListener('change', e => handleUpload(e.target.files));
}

// Drag & drop upload
const zone = document.getElementById('uploadZone');
if (zone) {
  zone.addEventListener('dragover',  e => { e.preventDefault(); zone.style.borderColor = 'var(--admin-brand)'; zone.style.background = 'rgba(99,102,241,0.05)'; });
  zone.addEventListener('dragleave', ()  => { zone.style.borderColor = 'var(--admin-border)'; zone.style.background = ''; });
  zone.addEventListener('drop', e => {
    e.preventDefault();
    zone.style.borderColor = 'var(--admin-border)';
    zone.style.background  = '';
    handleUpload(e.dataTransfer.files);
  });
}

function handleUpload(files) {
  const prog   = document.getElementById('uploadProgress');
  const bar    = document.getElementById('uploadProgressBar');
  const status = document.getElementById('uploadStatus');
  const csrf   = document.querySelector('[name="_csrf_token"]')?.value || '';

  prog.style.display = '';
  let done = 0;

  Array.from(files).forEach(file => {
    status.textContent = 'Uploading ' + file.name + '…';
    const fd = new FormData();
    fd.append('file', file);
    fd.append('_csrf_token', csrf);

    fetch('/techaasvik_admin/media/upload', { method: 'POST', body: fd })
      .then(r => r.json())
      .then(data => {
        done++;
        bar.style.width = Math.round((done / files.length) * 100) + '%';
        if (done === files.length) {
          status.textContent = files.length + ' file(s) uploaded!';
          setTimeout(() => location.reload(), 1200);
        }
      })
      .catch(() => { status.textContent = 'Upload error. Check file type/size.'; });
  });
}
</script>
