<?php
namespace Controllers\Admin;

use Core\Controller;
use Core\Auth;
use Models\CourseEnrollment;
use Models\CourseSetting;
use Models\CourseCoupon;
use Services\MailService;

/**
 * CourseAdminController — super admin course management
 */
class CourseAdminController extends Controller
{
    private CourseEnrollment $enrollments;
    private CourseSetting    $settings;
    private CourseCoupon     $coupons;

    public function __construct()
    {
        parent::__construct();
        $this->enrollments = new CourseEnrollment();
        $this->settings    = new CourseSetting();
        $this->coupons     = new CourseCoupon();
    }

    // ── Dashboard / Stats ─────────────────────────────────────
    public function index(array $params = []): void
    {
        $this->adminView('course-dashboard', [
            'title'          => 'Course Management',
            'total'          => $this->enrollments->countAll(),
            'paid'           => $this->enrollments->countPaid(),
            'revenue'        => $this->enrollments->totalRevenue(),
            'recent'         => $this->enrollments->listRecent(10),
            'coupons'        => $this->coupons->listAll(),
        ]);
    }

    // ── Settings (GET) ────────────────────────────────────────
    public function settings(array $params = []): void
    {
        $this->adminView('course-settings', [
            'title'    => 'Course Settings',
            'settings' => $this->settings->all(),
            'providers'=> MailService::providerPresets(),
            'flash'    => $this->getFlash(),
        ]);
    }

    // ── Settings (POST) ───────────────────────────────────────
    public function saveSettings(array $params = []): void
    {
        Auth::startSession();
        Auth::requireAdmin();
        $this->verifyCsrf();

        $pairs = [];
        $fields = [
            'course_price_original', 'course_price_sale', 'free_modules_count',
            'processing_fee_pct', 'course_grade_a_min', 'course_grade_b_min', 'course_grade_c_min',
            'video_enabled', 'cert_signatory_name',
            'smtp_provider', 'smtp_host', 'smtp_port', 'smtp_encryption',
            'smtp_user', 'smtp_from_name', 'smtp_from_email',
        ];
        foreach ($fields as $f) {
            $pairs[$f] = $this->request->post($f, '');
        }

        // SMTP pass — only update if provided (don't overwrite with blank)
        $smtpPass = $this->request->post('smtp_pass', '');
        if ($smtpPass !== '') {
            $pairs['smtp_pass'] = $smtpPass;
        }

        // Handle cert logo upload
        if (!empty($_FILES['cert_logo']['tmp_name'])) {
            $path = $this->uploadFile($_FILES['cert_logo'], 'cert_logo');
            if ($path) $pairs['cert_logo_path'] = $path;
        }
        // Handle cert signature upload
        if (!empty($_FILES['cert_signature']['tmp_name'])) {
            $path = $this->uploadFile($_FILES['cert_signature'], 'cert_signature');
            if ($path) $pairs['cert_signature_path'] = $path;
        }

        $this->settings->setMany($pairs);
        $this->flash('success', 'Settings saved successfully.');
        $this->redirect('/techaasvik_admin/course/settings');
    }

    // ── SMTP Test ─────────────────────────────────────────────
    public function testSmtp(array $params = []): void
    {
        $this->verifyCsrf();
        $to = $this->request->post('test_email', '');

        if (!filter_var($to, FILTER_VALIDATE_EMAIL)) {
            $this->json(['success' => false, 'error' => 'Invalid email address.']);
            return;
        }

        $mailer = new MailService();
        $result = $mailer->sendTest($to);

        $this->json([
            'success' => $result,
            'message' => $result
                ? "Test email sent to {$to}. Check your inbox."
                : "Failed to send. Check SMTP credentials and try again.",
        ]);
    }

    // ── Enrollments ───────────────────────────────────────────
    public function enrollments(array $params = []): void
    {
        $page   = max(1, (int)$this->request->get('page', 1));
        $offset = ($page - 1) * 50;
        $this->adminView('course-enrollments', [
            'title'       => 'Course Enrollments',
            'enrollments' => $this->enrollments->listRecent(50, $offset),
            'total'       => $this->enrollments->countAll(),
            'paid'        => $this->enrollments->countPaid(),
            'revenue'     => $this->enrollments->totalRevenue(),
            'page'        => $page,
            'flash'       => $this->getFlash(),
        ]);
    }

    // ── Coupons ───────────────────────────────────────────────
    public function createCoupon(array $params = []): void
    {
        $this->verifyCsrf();
        $code = strtoupper(trim($this->request->post('code', '')));
        if (!$code || strlen($code) < 3) {
            $this->flash('error', 'Invalid coupon code.');
            $this->redirect('/techaasvik_admin/course');
            return;
        }

        try {
            $this->coupons->create([
                'code'           => $code,
                'description'    => $this->request->post('description', ''),
                'discount_type'  => $this->request->post('discount_type', 'percent'),
                'discount_value' => (float)$this->request->post('discount_value', 10),
                'max_uses'       => $this->request->post('max_uses', ''),
                'min_amount'     => (float)$this->request->post('min_amount', 0),
                'valid_from'     => $this->request->post('valid_from', ''),
                'valid_until'    => $this->request->post('valid_until', ''),
            ]);
            $this->flash('success', "Coupon {$code} created.");
        } catch (\Exception $e) {
            $this->flash('error', 'Coupon code may already exist. Try a different code.');
        }
        $this->redirect('/techaasvik_admin/course');
    }

    public function deactivateCoupon(array $params = []): void
    {
        $this->verifyCsrf();
        $id = (int)($params['id'] ?? 0);
        if ($id) $this->coupons->deactivate($id);
        $this->flash('success', 'Coupon deactivated.');
        $this->redirect('/techaasvik_admin/course');
    }

    // ── Helpers ───────────────────────────────────────────────
    private function uploadFile(array $file, string $prefix): ?string
    {
        $allowed   = ['image/jpeg', 'image/png', 'image/svg+xml', 'image/webp'];
        $maxSize   = 2 * 1024 * 1024; // 2MB

        if ($file['error'] !== UPLOAD_ERR_OK) return null;
        if ($file['size'] > $maxSize) return null;

        $mime = mime_content_type($file['tmp_name']);
        if (!in_array($mime, $allowed)) return null;

        $ext  = pathinfo($file['name'], PATHINFO_EXTENSION);
        $name = $prefix . '_' . time() . '.' . $ext;
        $dir  = APP_ROOT . '/storage/course/';

        if (!is_dir($dir)) mkdir($dir, 0755, true);

        $dest = $dir . $name;
        if (move_uploaded_file($file['tmp_name'], $dest)) {
            return '/storage/course/' . $name;
        }
        return null;
    }
}
