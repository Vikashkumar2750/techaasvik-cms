<?php
/* course-verify-pending.php — shown after registration OR when link is expired */
$error = $error ?? null;
?>
<?php $bodyClass = 'page-auth'; ?>
<style>
.auth-wrap { min-height: 80vh; display:flex; align-items:center; justify-content:center; padding: 40px 20px; }
.auth-card { background: var(--bg-surface); border: 1px solid var(--border-subtle); border-radius: 20px; max-width: 480px; width:100%; padding: 48px 40px; text-align:center; }
.auth-icon { font-size: 56px; margin-bottom: 16px; line-height: 1; }
.auth-title { font-size: 24px; font-weight: 800; margin-bottom: 8px; }
.auth-sub { font-size: 15px; color: var(--text-muted); margin-bottom: 32px; line-height: 1.6; }
.auth-steps { text-align:left; background: var(--bg-elevated); border-radius:12px; padding: 20px 24px; margin-bottom: 28px; }
.auth-step { display:flex; align-items:flex-start; gap: 12px; margin-bottom: 14px; font-size: 14px; color: var(--text-secondary); }
.auth-step:last-child { margin-bottom: 0; }
.auth-step-num { width:22px; height:22px; border-radius:50%; background: var(--brand-500); color:#fff; font-size:11px; font-weight:800; display:flex; align-items:center; justify-content:center; flex-shrink:0; margin-top:1px; }
.auth-alert-error { background: rgba(248,113,113,0.08); border:1px solid rgba(248,113,113,0.25); border-radius:10px; padding:14px 18px; color:#f87171; font-size:14px; margin-bottom:24px; }
</style>

<div class="auth-wrap">
  <div class="auth-card">
    <?php if ($error): ?>
      <div class="auth-icon">⚠️</div>
      <h1 class="auth-title">Link Expired</h1>
      <div class="auth-alert-error"><?= htmlspecialchars($error) ?></div>
      <a href="/courses/ai-marketing-course" class="btn btn-primary" style="display:inline-block;">← Back to Course</a>
    <?php else: ?>
      <div class="auth-icon">📬</div>
      <h1 class="auth-title">Check your email!</h1>
      <p class="auth-sub">We've sent you a verification link. Click it to set your password and start learning.</p>
      <div class="auth-steps">
        <div class="auth-step"><div class="auth-step-num">1</div><div>Open your email inbox (check Spam/Promotions too)</div></div>
        <div class="auth-step"><div class="auth-step-num">2</div><div>Click <strong>"Verify &amp; Set Password"</strong> button in the email</div></div>
        <div class="auth-step"><div class="auth-step-num">3</div><div>Set your password and start learning!</div></div>
      </div>
      <p style="font-size:13px;color:var(--text-muted);">Link valid for 24 hours · <a href="/courses/ai-marketing-course?enroll=1" style="color:var(--brand-400);">Resend verification</a></p>
    <?php endif; ?>
  </div>
</div>
