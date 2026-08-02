<?php
namespace Core;

/**
 * Auth — Session-based authentication for the admin panel.
 * All admin routes must call Auth::requireAdmin() before doing anything.
 */
class Auth
{
    private static string $sessionKey = 'ta_admin';

    // ── Start session safely ──────────────────────────────
    public static function startSession(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_set_cookie_params([
                'lifetime' => 0,
                'path'     => '/',
                'secure'   => isset($_SERVER['HTTPS']),
                'httponly' => true,
                'samesite' => 'Strict',
            ]);
            session_start();
        }
    }

    // ── Attempt admin login ───────────────────────────────
    public static function attempt(string $username, string $password): bool
    {
        $db = Database::getInstance();

        // Check for lockout
        $user = $db->fetchOne(
            "SELECT * FROM admin_users WHERE username = ?",
            [$username]
        );

        if (!$user) {
            return false;
        }

        // Lockout check
        if ($user['locked_until'] && strtotime($user['locked_until']) > time()) {
            return false;
        }

        // Verify password
        if (!password_verify($password, $user['password_hash'])) {
            // Increment failed attempts
            $attempts = (int)$user['login_attempts'] + 1;
            $config   = require APP_PATH . '/Config/config.php';
            $maxAttempts = $config['admin']['max_attempts'] ?? 5;
            $lockoutMins = $config['admin']['lockout_minutes'] ?? 30;

            $lockedUntil = $attempts >= $maxAttempts
                ? date('Y-m-d H:i:s', time() + ($lockoutMins * 60))
                : null;

            $db->update('admin_users', [
                'login_attempts' => $attempts,
                'locked_until'   => $lockedUntil,
            ], 'id = ?', [$user['id']]);

            return false;
        }

        // Success — reset attempts, record login
        $db->update('admin_users', [
            'login_attempts' => 0,
            'locked_until'   => null,
            'last_login'     => date('Y-m-d H:i:s'),
        ], 'id = ?', [$user['id']]);

        // Regenerate session ID on login (prevent fixation)
        session_regenerate_id(true);

        $_SESSION[self::$sessionKey] = [
            'id'       => $user['id'],
            'username' => $user['username'],
            'role'     => $user['role'],
            'login_at' => time(),
        ];

        return true;
    }

    // ── Check if admin is logged in ───────────────────────
    public static function check(): bool
    {
        if (!isset($_SESSION[self::$sessionKey])) {
            return false;
        }

        $config  = require APP_PATH . '/Config/config.php';
        $timeout = $config['admin']['session_timeout'] ?? 28800;

        $loginAt = $_SESSION[self::$sessionKey]['login_at'] ?? 0;
        if ((time() - $loginAt) > $timeout) {
            self::logout();
            return false;
        }

        return true;
    }

    // ── Require admin auth or redirect to login ───────────
    public static function requireAdmin(): void
    {
        self::startSession();

        if (!self::check()) {
            header('Location: /techaasvik_admin?redirect=' . urlencode($_SERVER['REQUEST_URI']));
            exit;
        }
    }

    // ── Get current admin user ────────────────────────────
    public static function admin(): ?array
    {
        return $_SESSION[self::$sessionKey] ?? null;
    }

    // ── Logout ────────────────────────────────────────────
    public static function logout(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            unset($_SESSION[self::$sessionKey]);
            session_destroy();
        }
    }

    // ── CSRF token ────────────────────────────────────────
    public static function csrfToken(): string
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    public static function verifyCsrf(string $token): bool
    {
        return hash_equals($_SESSION['csrf_token'] ?? '', $token);
    }
}
