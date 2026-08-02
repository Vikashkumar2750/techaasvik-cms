<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="robots" content="noindex, nofollow">
<title>Admin Login — TechAasvik CMS</title>
<link rel="stylesheet" href="/assets/css/admin.css?v=<?= defined('ASSET_VERSION') ? ASSET_VERSION : '1' ?>">
<link rel="icon" href="/assets/images/static/favicon.ico">
</head>
<body>

<div class="admin-login-page">
  <div class="admin-login-box">

    <div class="admin-login-logo">
      <div class="admin-login-title">TechAasvik</div>
      <div class="admin-login-subtitle">Content Management System</div>
    </div>

    <?php if (!empty($error)): ?>
    <div class="admin-flash admin-flash-error" style="margin-bottom:20px;">
      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="flex-shrink:0"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
      <?= htmlspecialchars($error) ?>
    </div>
    <?php endif; ?>

    <?php if (isset($_GET['msg']) && $_GET['msg'] === 'logged_out'): ?>
    <div class="admin-flash admin-flash-info" style="margin-bottom:20px;">
      You have been logged out successfully.
    </div>
    <?php endif; ?>

    <form method="POST" action="/techaasvik_admin/login" autocomplete="on" novalidate>
      <input type="hidden" name="_csrf_token" value="<?= htmlspecialchars($csrf ?? '') ?>">
      <input type="hidden" name="redirect" value="<?= htmlspecialchars($redirect ?? '/techaasvik_admin/dashboard') ?>">

      <div class="admin-form-group">
        <label class="admin-form-label" for="username">Username</label>
        <input
          type="text"
          id="username"
          name="username"
          class="admin-form-input"
          placeholder="Enter your username"
          autocomplete="username"
          autofocus
          required
        >
      </div>

      <div class="admin-form-group">
        <label class="admin-form-label" for="password">
          Password
          <span id="capsLockWarn" style="color:#fbbf24;font-weight:400;display:none;"> · CAPS LOCK is ON</span>
        </label>
        <div style="position:relative;">
          <input
            type="password"
            id="password"
            name="password"
            class="admin-form-input"
            placeholder="Enter your password"
            autocomplete="current-password"
            required
          >
          <button type="button" id="togglePwd" style="position:absolute;right:10px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:var(--admin-muted);padding:4px;" aria-label="Toggle password visibility">
            <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
          </button>
        </div>
      </div>

      <button type="submit" class="admin-btn admin-btn-primary" style="width:100%;justify-content:center;padding:10px;font-size:14px;margin-top:8px;">
        Sign In to CMS
      </button>
    </form>

    <div class="admin-login-footer">
      <p>🔒 Secure, private admin access.</p>
      <p style="margin-top:6px;">techaasvik.com &copy; <?= date('Y') ?></p>
    </div>

  </div>
</div>

<script>
// Show/hide password
const pwd       = document.getElementById('password');
const toggleBtn = document.getElementById('togglePwd');
const eyeIcon   = document.getElementById('eyeIcon');

if (toggleBtn && pwd) {
  toggleBtn.addEventListener('click', () => {
    const isHidden = pwd.type === 'password';
    pwd.type = isHidden ? 'text' : 'password';
    eyeIcon.innerHTML = isHidden
      ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>'
      : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>';
  });
}

// Caps Lock detection
if (pwd) {
  pwd.addEventListener('keyup', e => {
    const warn = document.getElementById('capsLockWarn');
    if (warn) warn.style.display = e.getModifierState('CapsLock') ? 'inline' : 'none';
  });
}
</script>
</body>
</html>
