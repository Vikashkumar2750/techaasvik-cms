<?php
/**
 * TECHAASVIK.COM — ONE-TIME SETUP SCRIPT
 * ─────────────────────────────────────────────────────────────
 * Run this ONCE after uploading files to Hostinger.
 * Access via: https://t1.techaasvik.com/setup.php
 * DELETE THIS FILE immediately after running.
 * ─────────────────────────────────────────────────────────────
 */

// Security: only allow running once via a secret token
$SECRET_TOKEN = 'setup_ta_2026_xK9mP2';
$provided     = $_GET['token'] ?? '';

if ($provided !== $SECRET_TOKEN) {
    http_response_code(403);
    die('<h1>403 Forbidden</h1><p>Invalid or missing token.</p>');
}

define('APP_ROOT',   __DIR__);
define('APP_PATH',   APP_ROOT . '/app');
define('STORAGE_PATH', APP_ROOT . '/storage');
define('APP_ENV',    'production');

require_once APP_PATH . '/Config/constants.php';

$config = require APP_PATH . '/Config/config.php';
$dbCfg  = $config['database'];

$errors  = [];
$success = [];

// ── Step 1: Connect to database ───────────────────────────────
try {
    $pdo = new PDO(
        sprintf('mysql:host=%s;port=%d;dbname=%s;charset=utf8mb4',
            $dbCfg['host'], $dbCfg['port'], $dbCfg['name']),
        $dbCfg['user'],
        $dbCfg['pass'],
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    $success[] = '✅ Database connection successful.';
} catch (\PDOException $e) {
    die('<h2>❌ Database connection failed:</h2><pre>' . $e->getMessage() . '</pre>
         <p>Please update your database credentials in <code>app/Config/config.php</code> and try again.</p>');
}

// ── Step 2: Run schema.sql ─────────────────────────────────────
$schemaFile = APP_ROOT . '/database/schema.sql';
if (file_exists($schemaFile)) {
    $sql = file_get_contents($schemaFile);
    try {
        // Execute the entire schema file at once
        $pdo->exec($sql);
        $success[] = '✅ Database schema created.';
    } catch (\PDOException $e) {
        $errors[] = '⚠️ Schema: ' . $e->getMessage();
    }
} else {
    $errors[] = '❌ schema.sql not found.';
}

// ── Step 3: Create admin user with hashed password ────────────
$adminPass = 'techaasvik@27';
$hash      = password_hash($adminPass, PASSWORD_BCRYPT, ['cost' => 12]);

try {
    $stmt = $pdo->prepare(
        "INSERT IGNORE INTO admin_users (username, password_hash, email, role)
         VALUES (?, ?, ?, ?)"
    );
    $stmt->execute(['techaasvik', $hash, 'admin@techaasvik.com', 'super_admin']);
    $success[] = '✅ Admin user created (username: techaasvik).';
} catch (\PDOException $e) {
    $errors[] = '⚠️ Admin user: ' . $e->getMessage();
}

// ── Step 4: Run seed data ──────────────────────────────────────
$seedFile = APP_ROOT . '/database/seeds/seed_data.sql';
if (file_exists($seedFile)) {
    $sql = file_get_contents($seedFile);
    // Remove the placeholder admin user line (we already created it correctly above)
    $sql = preg_replace('/^INSERT INTO `admin_users`.*?;/m', '', $sql);
    
    try {
        $pdo->exec($sql);
        $success[] = '✅ Seed data inserted.';
    } catch (\PDOException $e) {
        $errors[] = '⚠️ Seed: ' . $e->getMessage();
    }
}

// ── Step 5: Create storage directories ────────────────────────
$dirs = [
    STORAGE_PATH . '/cache/pages',
    STORAGE_PATH . '/cache/fragments',
    STORAGE_PATH . '/logs',
    STORAGE_PATH . '/exports',
    APP_ROOT . '/assets/images/uploads',
];

foreach ($dirs as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
    // Create .htaccess to block direct access to storage
    if (str_contains($dir, 'storage')) {
        file_put_contents($dir . '/../.htaccess', "Deny from all\n");
    }
}
$success[] = '✅ Storage directories created.';

// ── Step 6: Verify PHP extensions ────────────────────────────
$required = ['pdo_mysql', 'mbstring', 'json', 'fileinfo', 'gd'];
foreach ($required as $ext) {
    if (!extension_loaded($ext)) {
        $errors[] = "❌ Missing PHP extension: $ext";
    }
}
if (empty(array_filter($errors, fn($e) => str_contains($e, 'extension')))) {
    $success[] = '✅ All required PHP extensions present.';
}

// ── Output Results ────────────────────────────────────────────
?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>TechAasvik — Setup</title>
<style>
  body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; max-width: 700px; margin: 40px auto; padding: 20px; background: #0f172a; color: #e2e8f0; }
  h1 { color: #6366f1; } h2 { color: #94a3b8; }
  .success { color: #4ade80; margin: 6px 0; }
  .error   { color: #f87171; margin: 6px 0; }
  .box { background: #1e293b; border-radius: 12px; padding: 24px; margin: 20px 0; }
  .warn { background: #7c2d12; border-radius: 8px; padding: 16px; margin-top: 20px; color: #fca5a5; font-weight: bold; }
  code { background: #334155; padding: 2px 6px; border-radius: 4px; }
</style>
</head>
<body>
<h1>🚀 TechAasvik Setup</h1>

<div class="box">
  <h2>Results</h2>
  <?php foreach ($success as $msg): ?>
    <p class="success"><?= htmlspecialchars($msg) ?></p>
  <?php endforeach; ?>
  <?php foreach ($errors as $msg): ?>
    <p class="error"><?= htmlspecialchars($msg) ?></p>
  <?php endforeach; ?>
</div>

<?php if (empty($errors)): ?>
<div class="box">
  <h2>✅ Setup Complete!</h2>
  <p><strong>Admin URL:</strong> <a href="/techaasvik_admin" style="color:#6366f1">/techaasvik_admin</a></p>
  <p><strong>Username:</strong> <code>techaasvik</code></p>
  <p><strong>Password:</strong> <code>techaasvik@27</code></p>
</div>
<?php endif; ?>

<div class="warn">
  ⚠️ SECURITY: DELETE <code>setup.php</code> from your server immediately after setup!<br>
  SSH: <code>rm public_html/setup.php</code> &nbsp;|&nbsp; FTP: Delete the file manually.
</div>
</body>
</html>
