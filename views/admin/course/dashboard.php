<!-- Admin: Course Dashboard -->
<?php
use Core\Auth;
Auth::startSession();
$csrfToken = Auth::csrfToken();
?>

<div class="admin-page-header">
  <div>
    <h1 class="admin-page-title">🎓 Course Management</h1>
    <p class="admin-page-subtitle">AI Marketing &amp; ChatGPT SEO — Enrollments, Coupons &amp; Settings</p>
  </div>
  <div style="display:flex;gap:10px;">
    <a href="/techaasvik_admin/course/settings" class="admin-btn admin-btn-ghost">⚙ Settings</a>
    <a href="/techaasvik_admin/course/enrollments" class="admin-btn admin-btn-primary">📋 All Enrollments</a>
  </div>
</div>

<?php if ($flash = ($flash ?? null)): ?>
<div style="padding:12px 16px;border-radius:8px;margin-bottom:20px;background:<?= $flash['type']==='success'?'rgba(52,211,153,0.1)':'rgba(248,113,113,0.1)' ?>;border:1px solid <?= $flash['type']==='success'?'rgba(52,211,153,0.3)':'rgba(248,113,113,0.3)' ?>;color:<?= $flash['type']==='success'?'#34d399':'#f87171' ?>;">
  <?= htmlspecialchars($flash['message']) ?>
</div>
<?php endif; ?>

<!-- Stats -->
<div class="admin-stats" style="margin-bottom:24px;">
  <div class="admin-stat-card">
    <div class="admin-stat-label">Total Enrollments</div>
    <div class="admin-stat-value"><?= number_format($total ?? 0) ?></div>
    <div class="admin-stat-delta">All students</div>
  </div>
  <div class="admin-stat-card">
    <div class="admin-stat-label">Paid Enrollments</div>
    <div class="admin-stat-value"><?= number_format($paid ?? 0) ?></div>
    <div class="admin-stat-delta" style="color:#34d399;">Full course access</div>
  </div>
  <div class="admin-stat-card">
    <div class="admin-stat-label">Revenue</div>
    <div class="admin-stat-value">₹<?= number_format($revenue ?? 0, 0) ?></div>
    <div class="admin-stat-delta">From paid enrollments</div>
  </div>
  <div class="admin-stat-card">
    <div class="admin-stat-label">Free Students</div>
    <div class="admin-stat-value"><?= number_format(($total ?? 0) - ($paid ?? 0)) ?></div>
    <div class="admin-stat-delta">On free tier</div>
  </div>
</div>

<div style="display:grid;grid-template-columns:1fr 380px;gap:24px;">

  <!-- Recent Enrollments -->
  <div class="admin-table-wrapper">
    <div style="padding:16px 20px;border-bottom:1px solid var(--admin-border);display:flex;justify-content:space-between;align-items:center;">
      <strong style="font-size:14px;">Recent Enrollments</strong>
      <a href="/techaasvik_admin/course/enrollments" class="admin-btn admin-btn-ghost admin-btn-sm">View All →</a>
    </div>
    <table class="admin-table">
      <thead>
        <tr>
          <th>Name</th><th>Email</th><th>Status</th><th>Enrolled</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($recent ?? [] as $e): ?>
        <tr>
          <td style="font-weight:600;"><?= htmlspecialchars($e['user_name']) ?></td>
          <td style="color:var(--admin-text-muted);font-size:13px;"><?= htmlspecialchars($e['user_email']) ?></td>
          <td>
            <span style="background:<?= $e['payment_status']==='paid'?'rgba(52,211,153,0.1)':'rgba(251,191,36,0.1)' ?>;color:<?= $e['payment_status']==='paid'?'#34d399':'#fbbf24' ?>;font-size:11px;font-weight:700;padding:2px 10px;border-radius:100px;text-transform:uppercase;">
              <?= $e['payment_status'] ?>
            </span>
          </td>
          <td style="font-size:12px;color:var(--admin-text-muted);"><?= date('d M y', strtotime($e['enrolled_at'])) ?></td>
        </tr>
        <?php endforeach; ?>
        <?php if(empty($recent)): ?>
        <tr><td colspan="4" style="text-align:center;color:var(--admin-text-muted);padding:20px;">No enrollments yet.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <!-- Coupons -->
  <div>
    <div class="admin-table-wrapper" style="margin-bottom:20px;">
      <div style="padding:16px 20px;border-bottom:1px solid var(--admin-border);">
        <strong style="font-size:14px;">🎟 Active Coupons</strong>
      </div>
      <div style="padding:16px;">
        <?php foreach($coupons ?? [] as $c): ?>
        <?php if(!$c['is_active']) continue; ?>
        <div style="display:flex;align-items:center;justify-content:space-between;padding:10px;background:var(--admin-bg-elevated);border-radius:8px;margin-bottom:8px;">
          <div>
            <div style="font-weight:700;font-size:13px;font-family:monospace;"><?= htmlspecialchars($c['code']) ?></div>
            <div style="font-size:11px;color:var(--admin-text-muted);"><?= $c['discount_type']==='percent' ? $c['discount_value'].'% off' : '₹'.$c['discount_value'].' off' ?> · <?= $c['uses_count'] ?>/<?= $c['max_uses']??'∞' ?> uses</div>
          </div>
          <form method="POST" action="/techaasvik_admin/course/coupons/<?= $c['id'] ?>/deactivate" onsubmit="return confirm('Deactivate this coupon?')">
            <input type="hidden" name="_csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
            <button type="submit" class="admin-btn admin-btn-ghost admin-btn-sm" style="color:#f87171;">✕</button>
          </form>
        </div>
        <?php endforeach; ?>
        <?php if(empty(array_filter($coupons??[], fn($c)=>$c['is_active']))): ?>
        <p style="font-size:13px;color:var(--admin-text-muted);text-align:center;padding:10px 0;">No active coupons.</p>
        <?php endif; ?>
      </div>
    </div>

    <!-- Create Coupon -->
    <div class="admin-table-wrapper">
      <div style="padding:16px 20px;border-bottom:1px solid var(--admin-border);">
        <strong style="font-size:14px;">➕ Create Coupon</strong>
      </div>
      <form method="POST" action="/techaasvik_admin/course/coupons/create" style="padding:16px;">
        <input type="hidden" name="_csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
        <div style="margin-bottom:12px;">
          <label style="display:block;font-size:12px;font-weight:600;margin-bottom:4px;">Code *</label>
          <input type="text" name="code" class="admin-input" placeholder="SAVE20" required style="text-transform:uppercase;">
        </div>
        <div style="margin-bottom:12px;">
          <label style="display:block;font-size:12px;font-weight:600;margin-bottom:4px;">Description</label>
          <input type="text" name="description" class="admin-input" placeholder="Summer offer">
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:12px;">
          <div>
            <label style="display:block;font-size:12px;font-weight:600;margin-bottom:4px;">Type</label>
            <select name="discount_type" class="admin-input">
              <option value="percent">Percent (%)</option>
              <option value="flat">Flat (₹)</option>
            </select>
          </div>
          <div>
            <label style="display:block;font-size:12px;font-weight:600;margin-bottom:4px;">Value *</label>
            <input type="number" name="discount_value" class="admin-input" placeholder="20" step="0.01" required>
          </div>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:12px;">
          <div>
            <label style="display:block;font-size:12px;font-weight:600;margin-bottom:4px;">Max Uses</label>
            <input type="number" name="max_uses" class="admin-input" placeholder="Unlimited">
          </div>
          <div>
            <label style="display:block;font-size:12px;font-weight:600;margin-bottom:4px;">Valid Until</label>
            <input type="date" name="valid_until" class="admin-input">
          </div>
        </div>
        <button type="submit" class="admin-btn admin-btn-primary" style="width:100%;justify-content:center;">Create Coupon</button>
      </form>
    </div>
  </div>
</div>
