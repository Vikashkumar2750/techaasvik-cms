<?php
namespace Controllers\Admin;

use Core\Controller;
use Core\Auth;
use Core\View;

/**
 * Admin Auth Controller — Login / Logout
 */
class AuthController extends Controller
{
    public function login(array $params = []): void
    {
        Auth::startSession();

        // Already logged in
        if (Auth::check()) {
            View::redirect('/techaasvik_admin/dashboard');
        }

        $error    = null;
        $lockMsg  = null;
        $redirect = $_GET['redirect'] ?? '/techaasvik_admin/dashboard';

        View::render('admin-login', [
            'title'    => 'Admin Login — TechAasvik',
            'error'    => $error,
            'redirect' => htmlspecialchars($redirect, ENT_QUOTES),
            'csrf'     => Auth::csrfToken(),
        ], 'minimal');
    }

    public function doLogin(array $params = []): void
    {
        Auth::startSession();

        // CSRF check
        $token = $this->request->post('_csrf_token', '');
        if (!Auth::verifyCsrf($token)) {
            View::render('admin-login', [
                'title' => 'Admin Login — TechAasvik',
                'error' => 'Security token expired. Please try again.',
                'csrf'  => Auth::csrfToken(),
            ], 'minimal');
            return;
        }

        $username = $this->request->post('username', '');
        $password = $this->request->post('password', '');
        $redirect = $this->request->post('redirect', '/techaasvik_admin/dashboard');

        if (empty($username) || empty($password)) {
            View::render('admin-login', [
                'title'    => 'Admin Login — TechAasvik',
                'error'    => 'Please enter your username and password.',
                'redirect' => htmlspecialchars($redirect, ENT_QUOTES),
                'csrf'     => Auth::csrfToken(),
            ], 'minimal');
            return;
        }

        // Check for lockout before attempting
        $user = $this->db->fetchOne(
            "SELECT locked_until, login_attempts FROM admin_users WHERE username = ?",
            [$username]
        );

        if ($user && $user['locked_until'] && strtotime($user['locked_until']) > time()) {
            $remaining = ceil((strtotime($user['locked_until']) - time()) / 60);
            View::render('admin-login', [
                'title'    => 'Admin Login — TechAasvik',
                'error'    => "Too many failed attempts. Try again in {$remaining} minute(s).",
                'redirect' => htmlspecialchars($redirect, ENT_QUOTES),
                'csrf'     => Auth::csrfToken(),
            ], 'minimal');
            return;
        }

        if (Auth::attempt($username, $password)) {
            // Sanitize redirect URL
            $redirect = filter_var($redirect, FILTER_SANITIZE_URL);
            if (!str_starts_with($redirect, '/techaasvik_admin')) {
                $redirect = '/techaasvik_admin/dashboard';
            }
            View::redirect($redirect);
        } else {
            View::render('admin-login', [
                'title'    => 'Admin Login — TechAasvik',
                'error'    => 'Invalid username or password.',
                'redirect' => htmlspecialchars($redirect, ENT_QUOTES),
                'csrf'     => Auth::csrfToken(),
            ], 'minimal');
        }
    }

    public function logout(array $params = []): void
    {
        Auth::startSession();
        Auth::logout();
        View::redirect('/techaasvik_admin?msg=logged_out');
    }
}
