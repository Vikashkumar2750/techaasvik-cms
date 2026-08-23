<?php
use Core\Auth;
Auth::startSession();
$csrfToken = Auth::csrfToken();
$certs     = $certs ?? [];
?>

<div class="admin-page-header">
  <div>
    <h1 class="admin-page-title">🎓 Certificates</h1>
    <p class="admin-page-subtitle">All issued course completion certificates</p>
  </div>
  <a href="/techaasvik_admin/course" class="admin-btn admin-btn-ghost">← Course Dashboard</a>
</div>

<?php if ($flash = ($flash ?? null)): ?>
<div style="padding:12px 16px;border-radius:8px;margin-bottom:20px;background:<?= $flash['type']==='success'?'rgba(52,211,153,0.1)':'rgba(248,113,113,0.1)' ?>;border:1px solid <?= $flash['type']==='success'?'rgba(52,211,153,0.3)':'rgba(248,113,113,0.3)' ?>;color:<?= $flash['type']==='success'?'#34d399':'#f87171' ?>;">
  <?= htmlspecialchars($flash['message']) ?>
</div>
<?php endif; ?>

<div class="admin-table-wrapper">
  <table class="admin-table">
    <thead>
      <tr>
        <th>Student</th>
        <th>Email</th>
        <th>Certificate ID</th>
        <th>Issued</th>
        <th>Downloads</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php if (empty($certs)): ?>
      <tr><td colspan="6" style="text-align:center;color:var(--admin-text-muted);padding:32px;">No certificates issued yet.</td></tr>
      <?php else: ?>
      <?php foreach ($certs as $c): ?>
      <tr>
        <td><strong><?= htmlspecialchars($c['user_name']) ?></strong></td>
        <td style="font-size:12px;"><?= htmlspecialchars($c['user_email']) ?></td>
        <td style="font-family:monospace;font-size:11px;color:var(--admin-text-muted);"><?= htmlspecialchars($c['cert_uid']) ?></td>
        <td style="font-size:12px;"><?= date('d M Y', strtotime($c['issued_at'])) ?></td>
        <td style="text-align:center;"><?= (int)($c['download_count'] ?? 0) ?></td>
        <td>
          <div style="display:flex;gap:6px;">
            <a href="/certificate/<?= htmlspecialchars($c['cert_uid']) ?>" target="_blank" class="admin-btn admin-btn-ghost admin-btn-sm">View</a>
            <form method="POST" action="/techaasvik_admin/course/certificates/<?= $c['id'] ?>/revoke" style="display:inline;">
              <input type="hidden" name="_csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
              <button type="submit" class="admin-btn admin-btn-ghost admin-btn-sm" style="color:#ef4444;"
                      onclick="return confirm('Revoke this certificate?')">Revoke</button>
            </form>
          </div>
        </td>
      </tr>
      <?php endforeach; ?>
      <?php endif; ?>
    </tbody>
  </table>
</div>
