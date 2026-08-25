<?php
/* course-reset-password.php */
use Core\Auth;
Auth::startSession();
$csrfToken = Auth::csrfToken();
$token = $token ?? '';
$error = $error ?? null;
?>
<style>
.auth-wrap { min-height:80vh; display:flex; align-items:center; justify-content:center; padding:40px 20px; }
.auth-card { background:var(--bg-surface); border:1px solid var(--border-subtle); border-radius:20px; max-width:440px; width:100%; padding:48px 40px; }
.auth-icon { font-size:48px; margin-bottom:12px; display:block; text-align:center; }
.auth-title { font-size:22px; font-weight:800; text-align:center; margin-bottom:6px; }
.auth-sub { font-size:14px; color:var(--text-muted); text-align:center; margin-bottom:32px; }
.auth-field { margin-bottom:18px; }
.auth-label { display:block; font-size:13px; font-weight:600; margin-bottom:6px; color:var(--text-secondary); }
.auth-input { width:100%; padding:12px 14px; background:var(--bg-elevated); border:1px solid var(--border-subtle); border-radius:10px; color:var(--text-primary); font-size:14px; transition:border-color 0.15s; }
.auth-input:focus { outline:none; border-color:var(--brand-400); box-shadow:0 0 0 3px rgba(99,102,241,0.12); }
.auth-error { background:rgba(248,113,113,0.08); border:1px solid rgba(248,113,113,0.25); border-radius:8px; padding:12px 16px; color:#f87171; font-size:13px; margin-bottom:16px; }
.pw-strength { height:4px; border-radius:2px; margin-top:6px; transition:all 0.3s; background:#374151; }
</style>

<div class="auth-wrap">
  <div class="auth-card">
    <span class="auth-icon">🔐</span>
    <h1 class="auth-title">Reset Password</h1>
    <p class="auth-sub">Choose a new password for your TechAasvik account.</p>

    <?php if ($error): ?>
    <div class="auth-error">⚠️ <?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" action="/courses/reset-password" id="resetForm">
      <input type="hidden" name="_csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
      <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">

      <div class="auth-field">
        <label class="auth-label" for="rp-password">New Password</label>
        <input type="password" id="rp-password" name="password" class="auth-input" placeholder="Minimum 8 characters" required minlength="8" oninput="checkStrength(this.value)">
        <div class="pw-strength" id="pwStrength"></div>
        <div id="pwStrengthLabel" style="font-size:11px;color:var(--text-muted);margin-top:4px;"></div>
      </div>
      <div class="auth-field">
        <label class="auth-label" for="rp-confirm">Confirm Password</label>
        <input type="password" id="rp-confirm" name="confirm_password" class="auth-input" placeholder="Re-enter your password" required>
      </div>

      <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;padding:14px;font-size:15px;margin-top:8px;" id="resetBtn">
        Reset &amp; Login →
      </button>
    </form>
  </div>
</div>

<script>
function checkStrength(val) {
  const bar = document.getElementById('pwStrength');
  const lbl = document.getElementById('pwStrengthLabel');
  let score = 0;
  if (val.length >= 8) score++;
  if (/[A-Z]/.test(val)) score++;
  if (/[0-9]/.test(val)) score++;
  if (/[^A-Za-z0-9]/.test(val)) score++;
  const colors = ['#ef4444','#f59e0b','#3b82f6','#10b981'];
  const labels = ['Weak','Fair','Good','Strong'];
  bar.style.background = colors[score-1] || '#374151';
  bar.style.width = (score * 25) + '%';
  lbl.textContent = score ? labels[score-1] : '';
}

document.getElementById('resetForm').onsubmit = function(e) {
  const p = document.getElementById('rp-password').value;
  const c = document.getElementById('rp-confirm').value;
  if (p !== c) { e.preventDefault(); alert('Passwords do not match.'); return; }
  document.getElementById('resetBtn').textContent = 'Resetting...';
  document.getElementById('resetBtn').disabled = true;
};
</script>
