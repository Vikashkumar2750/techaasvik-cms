<!-- Certificate Verification Page -->
<div class="container" style="padding-top:var(--space-14);padding-bottom:var(--space-16);max-width:640px;">

  <div style="text-align:center;margin-bottom:var(--space-8);">
    <div style="font-size:48px;margin-bottom:12px;">🔍</div>
    <h1 style="font-size:var(--text-3xl);margin-bottom:var(--space-3);">Certificate Verification</h1>
    <p style="color:var(--text-secondary);">Verify the authenticity of a TechAasvik course completion certificate.</p>
  </div>

  <?php if (!empty($cert)): ?>
  <!-- Valid Certificate -->
  <div style="background:rgba(52,211,153,0.06);border:1px solid rgba(52,211,153,0.25);border-radius:var(--radius-xl);padding:var(--space-6);text-align:center;margin-bottom:var(--space-6);">
    <div style="font-size:32px;margin-bottom:12px;">✅</div>
    <div style="font-size:18px;font-weight:700;color:#34d399;margin-bottom:var(--space-4);">Valid Certificate</div>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:var(--space-4);text-align:left;background:var(--bg-surface);border-radius:var(--radius-lg);padding:var(--space-5);">
      <div>
        <div style="font-size:11px;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.08em;margin-bottom:4px;">Student Name</div>
        <div style="font-weight:700;font-size:var(--text-base);"><?= htmlspecialchars($cert['user_name']) ?></div>
      </div>
      <div>
        <div style="font-size:11px;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.08em;margin-bottom:4px;">Course</div>
        <div style="font-weight:700;font-size:var(--text-base);">AI Marketing &amp; ChatGPT SEO</div>
      </div>
      <div>
        <div style="font-size:11px;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.08em;margin-bottom:4px;">Issued By</div>
        <div style="font-weight:600;font-size:var(--text-sm);">TechAasvik Learning</div>
      </div>
      <div>
        <div style="font-size:11px;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.08em;margin-bottom:4px;">Completion Date</div>
        <div style="font-weight:600;font-size:var(--text-sm);"><?= date('d F Y', strtotime($cert['issued_at'])) ?></div>
      </div>
      <div style="grid-column:1/-1;">
        <div style="font-size:11px;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.08em;margin-bottom:4px;">Certificate ID</div>
        <div style="font-family:monospace;font-size:var(--text-sm);color:var(--text-secondary);"><?= htmlspecialchars($cert['cert_uid']) ?></div>
      </div>
    </div>

    <div style="margin-top:var(--space-5);">
      <a href="/certificate/<?= htmlspecialchars($cert['cert_uid']) ?>" target="_blank" class="btn btn-primary" style="padding:12px 24px;" id="btn-view-cert-verify">View Certificate →</a>
    </div>
  </div>

  <?php elseif (!empty($uid)): ?>
  <!-- Invalid -->
  <div style="background:rgba(248,113,113,0.06);border:1px solid rgba(248,113,113,0.25);border-radius:var(--radius-xl);padding:var(--space-6);text-align:center;">
    <div style="font-size:32px;margin-bottom:12px;">❌</div>
    <div style="font-size:18px;font-weight:700;color:#f87171;margin-bottom:8px;">Certificate Not Found</div>
    <p style="color:var(--text-muted);font-size:14px;">No certificate found for ID: <code><?= htmlspecialchars($uid) ?></code>. Please check the ID and try again.</p>
  </div>
  <?php endif; ?>

  <!-- Search by ID -->
  <div style="margin-top:var(--space-8);padding:var(--space-5);background:var(--bg-surface);border:1px solid var(--border-subtle);border-radius:var(--radius-xl);">
    <h3 style="font-size:var(--text-base);font-weight:700;margin-bottom:var(--space-3);">Verify by Certificate ID</h3>
    <form action="" method="GET" style="display:flex;gap:8px;">
      <input type="text" name="id" class="form-input" placeholder="Enter certificate ID..." style="flex:1;" value="<?= htmlspecialchars($uid ?? '') ?>">
      <button type="submit" class="btn btn-primary" id="btn-verify-search">Verify →</button>
    </form>
  </div>

  <?php if (empty($cert) && empty($uid)): ?>
  <p style="text-align:center;color:var(--text-muted);font-size:14px;margin-top:var(--space-6);">Enter a certificate ID above to verify its authenticity.</p>
  <?php endif; ?>

</div>
