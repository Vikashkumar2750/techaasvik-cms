<?php
use Core\Auth;
Auth::startSession();
$csrfToken  = Auth::csrfToken();
$enrollment = $enrollment  ?? [];
$progress   = $progress    ?? [];
$subProgress= $subProgress ?? [];
$attempts   = $attempts    ?? [];
$cert       = $cert        ?? null;
$overallScore = $overallScore ?? 0;
$grade      = $grade       ?? '';

// Key progress by module_number
$progressByModule = [];
foreach ($progress as $p) { $progressByModule[(int)$p['module_number']] = $p; }
$completedModules = count(array_filter($progress, fn($p) => $p['completed']));
?>

<div class="admin-page-header">
  <div>
    <h1 class="admin-page-title">👤 Enrollment #<?= $enrollment['id'] ?></h1>
    <p class="admin-page-subtitle"><?= htmlspecialchars($enrollment['user_name'] ?? '') ?> · <?= htmlspecialchars($enrollment['user_email'] ?? '') ?></p>
  </div>
  <div style="display:flex;gap:10px;">
    <a href="/techaasvik_admin/course/enrollments" class="admin-btn admin-btn-ghost">← All Enrollments</a>
    <a href="/techaasvik_admin/course" class="admin-btn admin-btn-ghost">Dashboard</a>
  </div>
</div>

<?php if ($flash = ($flash ?? null)): ?>
<div style="padding:12px 16px;border-radius:8px;margin-bottom:20px;background:<?= $flash['type']==='success'?'rgba(52,211,153,0.1)':'rgba(248,113,113,0.1)' ?>;border:1px solid <?= $flash['type']==='success'?'rgba(52,211,153,0.3)':'rgba(248,113,113,0.3)' ?>;color:<?= $flash['type']==='success'?'#34d399':'#f87171' ?>;">
  <?= htmlspecialchars($flash['message']) ?>
</div>
<?php endif; ?>

<div style="display:grid;grid-template-columns:1fr 320px;gap:20px;align-items:start;">

  <!-- LEFT -->
  <div style="display:flex;flex-direction:column;gap:16px;">

    <!-- Enrollment Info Card -->
    <div class="admin-table-wrapper" style="padding:20px;">
      <h3 style="font-size:14px;font-weight:700;margin-bottom:16px;">📋 Enrollment Details</h3>
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
        <?php
        $badge = match($enrollment['payment_status'] ?? '') {
          'paid'     => ['#059669','PAID'],
          'pending'  => ['#d97706','PENDING'],
          'revoked'  => ['#ef4444','REVOKED'],
          'free'     => ['#6366f1','FREE'],
          default    => ['#64748b',$enrollment['payment_status'] ?? 'unknown'],
        };
        ?>
        <div style="background:var(--admin-bg-elevated);padding:12px;border-radius:8px;">
          <div style="font-size:11px;color:var(--admin-text-muted);margin-bottom:4px;">Status</div>
          <span style="background:<?= $badge[0] ?>;color:#fff;padding:2px 10px;border-radius:100px;font-size:12px;font-weight:700;"><?= $badge[1] ?></span>
        </div>
        <div style="background:var(--admin-bg-elevated);padding:12px;border-radius:8px;">
          <div style="font-size:11px;color:var(--admin-text-muted);margin-bottom:4px;">Amount Paid</div>
          <div style="font-weight:700;">₹<?= number_format($enrollment['amount_paid'] ?? 0, 2) ?></div>
        </div>
        <div style="background:var(--admin-bg-elevated);padding:12px;border-radius:8px;">
          <div style="font-size:11px;color:var(--admin-text-muted);margin-bottom:4px;">Enrolled At</div>
          <div style="font-size:13px;"><?= date('d M Y H:i', strtotime($enrollment['created_at'] ?? 'now')) ?></div>
        </div>
        <div style="background:var(--admin-bg-elevated);padding:12px;border-radius:8px;">
          <div style="font-size:11px;color:var(--admin-text-muted);margin-bottom:4px;">Phone</div>
          <div style="font-size:13px;"><?= htmlspecialchars($enrollment['user_phone'] ?? '—') ?></div>
        </div>
        <?php if ($enrollment['razorpay_payment_id']): ?>
        <div style="background:var(--admin-bg-elevated);padding:12px;border-radius:8px;grid-column:span 2;">
          <div style="font-size:11px;color:var(--admin-text-muted);margin-bottom:4px;">Razorpay Payment ID</div>
          <div style="font-size:12px;font-family:monospace;"><?= htmlspecialchars($enrollment['razorpay_payment_id']) ?></div>
        </div>
        <?php endif; ?>
        <?php if ($enrollment['coupon_code']): ?>
        <div style="background:var(--admin-bg-elevated);padding:12px;border-radius:8px;grid-column:span 2;">
          <div style="font-size:11px;color:var(--admin-text-muted);margin-bottom:4px;">Coupon Used</div>
          <div style="font-size:13px;font-weight:600;"><?= htmlspecialchars($enrollment['coupon_code']) ?> — saved ₹<?= number_format($enrollment['discount_amount'] ?? 0) ?></div>
        </div>
        <?php endif; ?>
      </div>
    </div>

    <!-- Module Progress -->
    <div class="admin-table-wrapper" style="padding:20px;">
      <h3 style="font-size:14px;font-weight:700;margin-bottom:16px;">📊 Module Progress (<?= $completedModules ?>/10 completed)</h3>
      <div style="display:grid;grid-template-columns:repeat(5,1fr);gap:8px;">
        <?php for ($m = 1; $m <= 10; $m++): ?>
        <?php $mp = $progressByModule[$m] ?? null; ?>
        <div style="background:var(--admin-bg-elevated);padding:10px;border-radius:8px;text-align:center;border:1.5px solid <?= $mp && $mp['completed'] ? '#059669' : 'var(--admin-border)' ?>;">
          <div style="font-size:20px;"><?= $mp && $mp['completed'] ? '✅' : '⬜' ?></div>
          <div style="font-size:11px;font-weight:700;margin-top:4px;">M<?= $m ?></div>
          <?php if ($mp && isset($mp['quiz_score'])): ?>
          <div style="font-size:10px;color:var(--admin-text-muted);"><?= $mp['quiz_score'] ?>%</div>
          <?php endif; ?>
        </div>
        <?php endfor; ?>
      </div>
      <?php if ($overallScore > 0): ?>
      <div style="margin-top:12px;padding:12px;background:rgba(99,102,241,0.06);border:1px solid rgba(99,102,241,0.15);border-radius:8px;display:flex;gap:16px;align-items:center;">
        <div>
          <div style="font-size:11px;color:var(--admin-text-muted);">Overall Score</div>
          <div style="font-size:22px;font-weight:900;color:#6366f1;"><?= $overallScore ?>%</div>
        </div>
        <div>
          <div style="font-size:11px;color:var(--admin-text-muted);">Grade</div>
          <div style="font-size:22px;font-weight:900;color:#059669;"><?= $grade ?></div>
        </div>
        <?php if ($cert): ?>
        <div style="margin-left:auto;">
          <a href="/certificate/<?= $cert['cert_uid'] ?>" target="_blank" class="admin-btn admin-btn-primary admin-btn-sm">🎓 View Cert</a>
        </div>
        <?php endif; ?>
      </div>
      <?php endif; ?>
    </div>

    <!-- Quiz Attempts -->
    <?php if (!empty($attempts)): ?>
    <div class="admin-table-wrapper" style="padding:20px;">
      <h3 style="font-size:14px;font-weight:700;margin-bottom:14px;">📝 Quiz Attempts</h3>
      <table class="admin-table">
        <thead><tr><th>Module</th><th>Score</th><th>Passed</th><th>Date</th></tr></thead>
        <tbody>
          <?php foreach ($attempts as $a): ?>
          <tr>
            <td>Module <?= $a['module_number'] ?></td>
            <td><strong><?= $a['score'] ?>%</strong></td>
            <td><span style="color:<?= $a['passed'] ? '#059669' : '#ef4444' ?>;font-weight:700;"><?= $a['passed'] ? '✓ Pass' : '✗ Fail' ?></span></td>
            <td style="font-size:12px;color:var(--admin-text-muted);"><?= date('d M H:i', strtotime($a['attempted_at'])) ?></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <?php endif; ?>

  </div>

  <!-- RIGHT: Actions -->
  <div style="display:flex;flex-direction:column;gap:16px;">

    <!-- Access Management -->
    <div class="admin-table-wrapper" style="padding:20px;">
      <h3 style="font-size:14px;font-weight:700;margin-bottom:16px;">🔐 Access Management</h3>
      <?php if (($enrollment['payment_status'] ?? '') !== 'paid'): ?>
      <form method="POST" action="/techaasvik_admin/course/enrollments/<?= $enrollment['id'] ?>/grant">
        <input type="hidden" name="_csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
        <button type="submit" class="admin-btn admin-btn-primary" style="width:100%;justify-content:center;"
                onclick="return confirm('Grant full access to this student?')">
          🔓 Grant Full Access
        </button>
      </form>
      <?php else: ?>
      <div style="font-size:12px;color:#059669;font-weight:600;margin-bottom:12px;">✅ Full Access Active</div>
      <form method="POST" action="/techaasvik_admin/course/enrollments/<?= $enrollment['id'] ?>/revoke">
        <input type="hidden" name="_csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
        <button type="submit" class="admin-btn admin-btn-ghost" style="width:100%;justify-content:center;color:#ef4444;border-color:#ef4444;"
                onclick="return confirm('Revoke access? Student will lose course access.')">
          🔒 Revoke Access
        </button>
      </form>
      <?php endif; ?>
    </div>

    <!-- Certificate -->
    <div class="admin-table-wrapper" style="padding:20px;">
      <h3 style="font-size:14px;font-weight:700;margin-bottom:14px;">🎓 Certificate</h3>
      <?php if ($cert): ?>
      <div style="font-size:12px;color:var(--admin-text-muted);margin-bottom:10px;">Issued: <?= date('d M Y', strtotime($cert['issued_at'])) ?></div>
      <a href="/certificate/<?= $cert['cert_uid'] ?>" target="_blank" class="admin-btn admin-btn-primary admin-btn-sm" style="width:100%;justify-content:center;margin-bottom:8px;">View Certificate</a>
      <form method="POST" action="/techaasvik_admin/course/certificates/<?= $cert['id'] ?>/revoke">
        <input type="hidden" name="_csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
        <button type="submit" class="admin-btn admin-btn-ghost admin-btn-sm" style="width:100%;color:#ef4444;border-color:#ef4444;"
                onclick="return confirm('Revoke this certificate? Cannot be undone.')">🗑 Revoke Certificate</button>
      </form>
      <?php else: ?>
      <div style="color:var(--admin-text-muted);font-size:13px;">No certificate issued yet.</div>
      <?php endif; ?>
    </div>

    <!-- Danger Zone -->
    <div class="admin-table-wrapper" style="padding:20px;border:1px solid rgba(239,68,68,0.2);">
      <h3 style="font-size:14px;font-weight:700;margin-bottom:14px;color:#ef4444;">⚠️ Danger Zone</h3>
      <form method="POST" action="/techaasvik_admin/course/enrollments/<?= $enrollment['id'] ?>/delete">
        <input type="hidden" name="_csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
        <button type="submit" style="width:100%;padding:10px;background:#ef4444;color:#fff;border:none;border-radius:8px;font-weight:700;cursor:pointer;"
                onclick="return confirm('PERMANENTLY DELETE this enrollment? This cannot be undone.')">
          🗑 Delete Enrollment
        </button>
      </form>
    </div>

  </div>
</div>
