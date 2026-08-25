<?php
/* course-login.php — Student login page */
use Core\Auth;
Auth::startSession();
$csrfToken = Auth::csrfToken();
$email  = $email ?? '';
$error  = $error ?? null;
$exists = $exists ?? false;
$flash  = $flash ?? null;
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
.auth-success { background:rgba(52,211,153,0.08); border:1px solid rgba(52,211,153,0.25); border-radius:8px; padding:12px 16px; color:#34d399; font-size:13px; margin-bottom:16px; }
.auth-links { display:flex; justify-content:space-between; align-items:center; font-size:13px; margin-top:20px; color:var(--text-muted); }
</style>

<div class="auth-wrap">
  <div class="auth-card">
    <span class="auth-icon">🎓</span>
    <h1 class="auth-title">Student Login</h1>
    <p class="auth-sub">AI Marketing &amp; ChatGPT SEO Course</p>

    <?php if ($exists): ?>
    <div class="auth-success">✅ You already have an account. Please log in below.</div>
    <?php endif; ?>

    <?php if ($flash && $flash['type'] === 'success'): ?>
    <div class="auth-success">✅ <?= htmlspecialchars($flash['message']) ?></div>
    <?php endif; ?>

    <?php if ($error): ?>
    <div class="auth-error">⚠️ <?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" action="/courses/login" id="loginForm">
      <input type="hidden" name="_csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">

      <div class="auth-field">
        <label class="auth-label" for="login-email">Email Address</label>
        <input type="email" id="login-email" name="email" class="auth-input" placeholder="you@email.com" required value="<?= htmlspecialchars($email) ?>">
      </div>
      <div class="auth-field">
        <label class="auth-label" for="login-password" style="display:flex;justify-content:space-between;">
          <span>Password</span>
          <a href="/courses/forgot-password" style="font-weight:400;color:var(--brand-400);font-size:12px;">Forgot password?</a>
        </label>
        <input type="password" id="login-password" name="password" class="auth-input" placeholder="Your password" required>
      </div>

      <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;padding:14px;font-size:15px;margin-top:8px;" id="loginBtn">
        Login to Course →
      </button>
    </form>

    <div class="auth-links">
      <span>Don't have an account?</span>
      <a href="/courses/ai-marketing-course?enroll=1" style="color:var(--brand-400);font-weight:600;">Sign up free →</a>
    </div>
  </div>
</div>

<script>
document.getElementById('loginForm').onsubmit = function() {
  document.getElementById('loginBtn').textContent = 'Logging in...';
  document.getElementById('loginBtn').disabled = true;
};
</script>
