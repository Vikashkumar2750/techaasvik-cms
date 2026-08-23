<!-- Admin: Course Enrollments -->
<div class="admin-page-header">
  <div>
    <h1 class="admin-page-title">📋 Course Enrollments</h1>
    <p class="admin-page-subtitle">All students enrolled in AI Marketing &amp; ChatGPT SEO</p>
  </div>
  <div style="display:flex;gap:10px;">
    <a href="/techaasvik_admin/course" class="admin-btn admin-btn-ghost">← Dashboard</a>
  </div>
</div>

<!-- Stats Row -->
<div style="display:flex;gap:12px;margin-bottom:24px;flex-wrap:wrap;">
  <div style="padding:14px 20px;background:var(--admin-surface);border:1px solid var(--admin-border);border-radius:10px;">
    <div style="font-size:11px;color:var(--admin-text-muted);text-transform:uppercase;letter-spacing:0.06em;">Total</div>
    <div style="font-size:24px;font-weight:800;"><?= number_format($total??0) ?></div>
  </div>
  <div style="padding:14px 20px;background:var(--admin-surface);border:1px solid var(--admin-border);border-radius:10px;">
    <div style="font-size:11px;color:var(--admin-text-muted);text-transform:uppercase;letter-spacing:0.06em;">Paid</div>
    <div style="font-size:24px;font-weight:800;color:#34d399;"><?= number_format($paid??0) ?></div>
  </div>
  <div style="padding:14px 20px;background:var(--admin-surface);border:1px solid var(--admin-border);border-radius:10px;">
    <div style="font-size:11px;color:var(--admin-text-muted);text-transform:uppercase;letter-spacing:0.06em;">Revenue</div>
    <div style="font-size:24px;font-weight:800;color:#6366f1;">₹<?= number_format($revenue??0, 0) ?></div>
  </div>
  <div style="padding:14px 20px;background:var(--admin-surface);border:1px solid var(--admin-border);border-radius:10px;">
    <div style="font-size:11px;color:var(--admin-text-muted);text-transform:uppercase;letter-spacing:0.06em;">Free</div>
    <div style="font-size:24px;font-weight:800;color:#fbbf24;"><?= number_format(($total??0)-($paid??0)) ?></div>
  </div>
</div>

<div class="admin-table-wrapper">
  <table class="admin-table">
    <thead>
      <tr>
        <th>#</th>
        <th>Name</th>
        <th>Email</th>
        <th>Phone</th>
        <th>Status</th>
        <th>Amount</th>
        <th>Coupon</th>
        <th>Razorpay ID</th>
        <th>Enrolled</th>
        <th>Completed</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($enrollments??[] as $e): ?>
      <tr>
        <td style="color:var(--admin-text-muted);font-size:12px;"><?= $e['id'] ?></td>
        <td style="font-weight:600;"><?= htmlspecialchars($e['user_name']) ?></td>
        <td style="font-size:13px;color:var(--admin-text-muted);"><?= htmlspecialchars($e['user_email']) ?></td>
        <td style="font-size:13px;color:var(--admin-text-muted);"><?= htmlspecialchars($e['user_phone']) ?></td>
        <td>
          <span style="background:<?= $e['payment_status']==='paid'?'rgba(52,211,153,0.1)':($e['payment_status']==='pending'?'rgba(251,191,36,0.1)':'rgba(99,102,241,0.1)') ?>;color:<?= $e['payment_status']==='paid'?'#34d399':($e['payment_status']==='pending'?'#fbbf24':'var(--admin-primary)') ?>;font-size:11px;font-weight:700;padding:3px 10px;border-radius:100px;text-transform:uppercase;">
            <?= $e['payment_status'] ?>
          </span>
        </td>
        <td style="font-weight:600;"><?= $e['amount_paid']>0 ? '₹'.number_format($e['amount_paid'],0) : '—' ?></td>
        <td style="font-size:12px;font-family:monospace;color:var(--admin-text-muted);"><?= htmlspecialchars($e['coupon_code']??'—') ?></td>
        <td style="font-size:11px;font-family:monospace;color:var(--admin-text-muted);"><?= htmlspecialchars(substr($e['razorpay_payment_id']??'—',0,20)) ?></td>
        <td style="font-size:12px;color:var(--admin-text-muted);"><?= date('d M y, h:i A', strtotime($e['enrolled_at'])) ?></td>
        <td style="font-size:12px;color:var(--admin-text-muted);"><?= $e['completed_at'] ? date('d M y', strtotime($e['completed_at'])) : '—' ?></td>
      </tr>
      <?php endforeach; ?>
      <?php if(empty($enrollments)): ?>
      <tr><td colspan="10" style="text-align:center;color:var(--admin-text-muted);padding:30px;">No enrollments yet.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<?php if(($total??0) > 50): ?>
<div style="text-align:center;margin-top:20px;">
  <?php if(($page??1) > 1): ?>
  <a href="?page=<?= ($page??1)-1 ?>" class="admin-btn admin-btn-ghost">← Previous</a>
  <?php endif; ?>
  <span style="padding:0 16px;font-size:13px;color:var(--admin-text-muted);">Page <?= $page??1 ?></span>
  <?php if(($total??0) > ($page??1)*50): ?>
  <a href="?page=<?= ($page??1)+1 ?>" class="admin-btn admin-btn-ghost">Next →</a>
  <?php endif; ?>
</div>
<?php endif; ?>
