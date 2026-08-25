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
            'courses_enabled',
            'course_price_original', 'course_price_sale', 'free_modules_count',
            'processing_fee_pct', 'course_grade_a_min', 'course_grade_b_min', 'course_grade_c_min',
            'video_enabled', 'cert_signatory_name',
            'smtp_provider', 'smtp_host', 'smtp_port', 'smtp_encryption',
            'smtp_user', 'smtp_from_name', 'smtp_from_email',
        ];
        foreach ($fields as $f) {
            $pairs[$f] = $this->request->post($f, '');
        }

        // SMTP pass — only update if provided
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

    // ── Module Management (GET list) ─────────────────────────
    public function modules(array $params = []): void
    {
        $db = \Core\Database::getInstance();

        // Count completions per module
        $completions = [];
        $rows = $db->fetchAll(
            "SELECT module_number, COUNT(DISTINCT enrollment_id) as cnt
             FROM course_progress WHERE completed=1 GROUP BY module_number"
        );
        foreach ($rows as $r) {
            $completions[(int)$r['module_number']] = (int)$r['cnt'];
        }

        // Module metadata from DB overrides
        $moduleSettings = [];
        $rows2 = $db->fetchAll("SELECT setting_key, setting_value FROM course_settings WHERE setting_key LIKE 'module_%'");
        foreach ($rows2 as $r) {
            $moduleSettings[$r['setting_key']] = $r['setting_value'];
        }

        $this->adminView('course/modules', [
            'title'          => 'Module Management',
            'completions'    => $completions,
            'moduleSettings' => $moduleSettings,
            'totalPaid'      => $this->enrollments->countPaid(),
            'flash'          => $this->getFlash(),
        ]);
    }

    // ── Module Edit (GET) ─────────────────────────────────────
    public function editModule(array $params = []): void
    {
        $num = (int)($params['num'] ?? 0);
        if ($num < 1 || $num > 10) { $this->notFound(); return; }

        $db = \Core\Database::getInstance();

        // Fetch module-specific settings
        $rows = $db->fetchAll(
            "SELECT setting_key, setting_value FROM course_settings WHERE setting_key LIKE 'module_{$num}_%'"
        );
        $moduleData = [];
        foreach ($rows as $r) {
            // strip module_N_ prefix
            $key = preg_replace("/^module_{$num}_/", '', $r['setting_key']);
            $moduleData[$key] = $r['setting_value'];
        }

        $this->adminView('course/module-edit', [
            'title'      => "Edit Module {$num}",
            'num'        => $num,
            'moduleData' => $moduleData,
            'flash'      => $this->getFlash(),
        ]);
    }

    // ── Module Save (POST) — supports both redirect and AJAX ────
    public function saveModule(array $params = []): void
    {
        $this->verifyCsrf();
        $num = (int)($params['num'] ?? 0);
        if ($num < 1 || $num > 10) { $this->notFound(); return; }

        $fields = ['title', 'description', 'image_url', 'video_url', 'video_embed',
                   'duration', 'is_free', 'objectives', 'resources'];

        $db = \Core\Database::getInstance();
        foreach ($fields as $f) {
            $val = $this->request->post($f, '');
            $key = "module_{$num}_{$f}";
            $db->execute(
                "INSERT INTO course_settings (setting_key, setting_value) VALUES (?,?)
                 ON DUPLICATE KEY UPDATE setting_value=?",
                [$key, $val, $val]
            );
        }

        \Models\CourseSetting::clearCache();

        // Support AJAX response (from unified content editor)
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH'])) {
            $this->json(['success' => true, 'message' => "Module {$num} saved."]);
            return;
        }

        $this->flash('success', "Module {$num} saved successfully.");
        $this->redirect("/techaasvik_admin/course/modules/{$num}/edit");
    }

    // ── Enrollment Detail ─────────────────────────────────────
    public function enrollmentDetail(array $params = []): void
    {
        $id = (int)($params['id'] ?? 0);
        $db = \Core\Database::getInstance();

        $enrollment = $db->fetchOne("SELECT * FROM course_enrollments WHERE id=? LIMIT 1", [$id]);
        if (!$enrollment) { $this->notFound(); return; }

        $progress = $db->fetchAll(
            "SELECT * FROM course_progress WHERE enrollment_id=? ORDER BY module_number ASC",
            [$id]
        );
        $subProgress = $db->fetchAll(
            "SELECT * FROM course_submodule_progress WHERE enrollment_id=? ORDER BY module_number, submodule_key ASC",
            [$id]
        );
        $attempts = $db->fetchAll(
            "SELECT * FROM course_quiz_attempts WHERE enrollment_id=? ORDER BY attempted_at DESC LIMIT 20",
            [$id]
        );
        $cert = $db->fetchOne(
            "SELECT * FROM course_certificates WHERE enrollment_id=? LIMIT 1", [$id]
        );

        $progressModel = new \Models\CourseProgress();
        $overallScore = $progressModel->calculateOverallScore($id);

        $this->adminView('course/enrollment-detail', [
            'title'       => "Enrollment #{$id}",
            'enrollment'  => $enrollment,
            'progress'    => $progress,
            'subProgress' => $subProgress,
            'attempts'    => $attempts,
            'cert'        => $cert,
            'overallScore'=> $overallScore,
            'grade'       => \Models\CourseProgress::scoreToGrade($overallScore),
            'flash'       => $this->getFlash(),
        ]);
    }

    // ── Grant Full Access (manual) ────────────────────────────
    public function grantAccess(array $params = []): void
    {
        $this->verifyCsrf();
        $id = (int)($params['id'] ?? 0);
        $db = \Core\Database::getInstance();

        $db->execute(
            "UPDATE course_enrollments SET payment_status='paid', amount_paid=0,
             razorpay_payment_id='MANUAL_GRANT', updated_at=NOW() WHERE id=?",
            [$id]
        );

        $this->flash('success', "Full access granted to enrollment #{$id}.");
        $this->redirect("/techaasvik_admin/course/enrollments/{$id}");
    }

    // ── Revoke Access ─────────────────────────────────────────
    public function revokeAccess(array $params = []): void
    {
        $this->verifyCsrf();
        $id = (int)($params['id'] ?? 0);
        $db = \Core\Database::getInstance();

        $db->execute(
            "UPDATE course_enrollments SET payment_status='revoked', updated_at=NOW() WHERE id=?",
            [$id]
        );

        $this->flash('success', "Access revoked for enrollment #{$id}.");
        $this->redirect("/techaasvik_admin/course/enrollments/{$id}");
    }

    // ── Delete Enrollment ─────────────────────────────────────
    public function deleteEnrollment(array $params = []): void
    {
        $this->verifyCsrf();
        $id = (int)($params['id'] ?? 0);
        $db = \Core\Database::getInstance();
        $db->execute("DELETE FROM course_enrollments WHERE id=?", [$id]);
        $this->flash('success', "Enrollment #{$id} deleted.");
        $this->redirect('/techaasvik_admin/course/enrollments');
    }

    // ── Certificates List ─────────────────────────────────────
    public function certificates(array $params = []): void
    {
        $db = \Core\Database::getInstance();
        $certs = $db->fetchAll(
            "SELECT cc.*, ce.user_name, ce.user_email, ce.amount_paid
             FROM course_certificates cc
             JOIN course_enrollments ce ON cc.enrollment_id = ce.id
             ORDER BY cc.issued_at DESC LIMIT 100"
        );
        $this->adminView('course/certificates', [
            'title' => 'Certificates',
            'certs' => $certs,
            'flash' => $this->getFlash(),
        ]);
    }

    // ── Revoke Certificate ────────────────────────────────────
    public function revokeCert(array $params = []): void
    {
        $this->verifyCsrf();
        $id = (int)($params['id'] ?? 0);
        $db = \Core\Database::getInstance();
        $db->execute("DELETE FROM course_certificates WHERE id=?", [$id]);
        $this->flash('success', "Certificate revoked.");
        $this->redirect('/techaasvik_admin/course/certificates');
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

    // ─────────────────────────────────────────────────────────────
    // CONTENT EDITOR — submodule content CMS
    // ─────────────────────────────────────────────────────────────

    // Submodule titles (mirrors CourseController::getSubmodules)
    private static function subTitles(): array
    {
        return [
            1  => ['Overview & Mindset', "AI's Impact on Marketing", 'Search & Content Shift', 'Automation & Personalization', 'Quiz'],
            2  => ['ChatGPT Basics for Marketers', 'Context Engineering', 'The CRAFT Framework', 'Building Reusable Workflows', 'Quiz'],
            3  => ['Research Mindset', 'Customer & Competitor Intel', 'Mining Reviews & Gaps', 'Positioning Framework', 'Quiz'],
            4  => ['Keyword Strategy with AI', 'Topical Authority Clusters', 'AI Content Briefs', 'Content Refresh System', 'Quiz'],
            5  => ['AI Content at Scale', 'E-E-A-T & Brand Voice', 'Multimedia & Repurposing', '30-Day Content System', 'Quiz'],
            6  => ['GEO Fundamentals', 'AI Overview Optimization', 'Entity Signals & Schema', 'AEO Answer Engineering', 'Quiz'],
            7  => ['Google Performance Max', 'Meta Advantage+ Ads', 'AI Bidding & Signals', 'ROAS Scaling System', 'Quiz'],
            8  => ['CRO with AI', 'Lead Scoring & Nurturing', 'n8n Workflow Automation', 'Email Sequences', 'Quiz'],
            9  => ['GA4 Setup & Events', 'Attribution Models', 'CAC, LTV & North Star', 'Marketing Diagnosis', 'Quiz'],
            10 => ['Build Your AI Marketing OS', 'Capstone: Real Business Plan', 'AI Safety & Ethics', 'Career Roadmap & Next Steps', 'Quiz'],
        ];
    }

    private static function moduleNames(): array
    {
        return [
            1  => 'AI-Native Marketing: What Changed & What Matters',
            2  => 'ChatGPT for Marketers: From Prompting to Context Engineering',
            3  => 'AI-Powered Market Research & Competitive Intelligence',
            4  => 'SEO in the AI Era: Keyword Strategy & Topical Authority',
            5  => 'AI Content Creation at Scale: Quality, Voice & Systems',
            6  => 'GEO & AEO: Getting Found in AI Answers',
            7  => 'AI-Powered Paid Advertising (Google & Meta)',
            8  => 'Marketing Automation, CRO & Lead Generation with AI',
            9  => 'Analytics, Attribution & the Data-Driven Marketing Mindset',
            10 => 'Capstone: Build Your AI Marketing Operating System',
        ];
    }

    /** GET /techaasvik_admin/course/content-editor */
    public function contentEditor(array $params = []): void
    {
        $db    = \Core\Database::getInstance();
        $slug  = 'ai-marketing-course';

        // Which submodule keys have DB content?
        $rows = $db->fetchAll(
            "SELECT module_num, submodule_key FROM course_submodule_content WHERE course_slug=?",
            [$slug]
        );
        $hasContent = [];
        foreach ($rows as $r) {
            $hasContent[$r['module_num'] . '-' . $r['submodule_key']] = true;
        }

        // Module settings (for module editor panel)
        $moduleSettings = [];
        $mRows = $db->fetchAll("SELECT setting_key, setting_value FROM course_settings WHERE setting_key LIKE 'module_%'");
        foreach ($mRows as $r) {
            $moduleSettings[$r['setting_key']] = $r['setting_value'];
        }

        $this->adminView('course/content-editor', [
            'title'          => 'Course Editor',
            'slug'           => $slug,
            'subTitles'      => self::subTitles(),
            'moduleNames'    => self::moduleNames(),
            'hasContent'     => $hasContent,
            'moduleSettings' => $moduleSettings,
            'flash'          => $this->getFlash(),
        ]);
    }

    /** GET /techaasvik_admin/course/content-editor/{mod}/{sub}/load — AJAX JSON */
    public function contentEditorLoad(array $params = []): void
    {
        $mod  = (int)($params['mod'] ?? 0);
        $sub  = preg_replace('/[^0-9\-]/', '', $params['sub'] ?? '');
        $slug = 'ai-marketing-course';

        if ($mod < 1 || $mod > 10 || !$sub) {
            $this->json(['error' => 'Invalid params'], 400);
            return;
        }

        $db  = \Core\Database::getInstance();
        $row = $db->fetchOne(
            "SELECT * FROM course_submodule_content WHERE course_slug=? AND module_num=? AND submodule_key=? LIMIT 1",
            [$slug, $mod, $sub]
        );

        $subs  = self::subTitles();
        $names = self::moduleNames();
        $parts = explode('-', $sub);
        $sNum  = (int)($parts[1] ?? 1);
        $defaultTitle = $subs[$mod][$sNum - 1] ?? "Module {$mod} Lesson {$sNum}";

        $this->json([
            'exists'           => (bool)$row,
            'module_num'       => $mod,
            'submodule_key'    => $sub,
            'module_name'      => $names[$mod] ?? '',
            'default_title'    => $defaultTitle,
            'content_title'    => $row['content_title']    ?? '',
            'content_html'     => $row['content_html']     ?? '',
            'image_url'        => $row['image_url']        ?? '',
            'video_url'        => $row['video_url']        ?? '',
            'video_embed'      => $row['video_embed']      ?? '',
            'key_points'       => $row ? (json_decode($row['key_points'] ?? '[]', true) ?: []) : [],
            'infographic_title'=> $row['infographic_title']?? '',
            'resources'        => $row ? (json_decode($row['resources']  ?? '[]', true) ?: []) : [],
            'duration_text'    => $row['duration_text']    ?? '',
        ]);
    }

    /** POST /techaasvik_admin/course/content-editor/{mod}/{sub}/save */
    public function contentEditorSave(array $params = []): void
    {
        $this->verifyCsrf();
        $mod  = (int)($params['mod'] ?? 0);
        $sub  = preg_replace('/[^0-9\-]/', '', $params['sub'] ?? '');
        $slug = 'ai-marketing-course';

        if ($mod < 1 || $mod > 10 || !$sub) {
            $this->json(['success' => false, 'error' => 'Invalid params'], 400);
            return;
        }

        $db = \Core\Database::getInstance();

        // Parse key_points and resources from JSON strings sent in POST
        $keyPointsRaw = $this->request->post('key_points', '[]');
        $resourcesRaw = $this->request->post('resources', '[]');
        $keyPoints    = json_decode($keyPointsRaw, true);
        $resources    = json_decode($resourcesRaw, true);

        $data = [
            'content_title'    => $this->request->post('content_title', ''),
            'content_html'     => $this->request->post('content_html', ''),
            'image_url'        => $this->request->post('image_url', ''),
            'video_url'        => $this->request->post('video_url', ''),
            'video_embed'      => $this->request->post('video_embed', ''),
            'infographic_title'=> $this->request->post('infographic_title', ''),
            'key_points'       => json_encode(is_array($keyPoints) ? array_values(array_filter($keyPoints)) : []),
            'resources'        => json_encode(is_array($resources) ? array_values(array_filter($resources, fn($r) => !empty($r['name']))) : []),
            'duration_text'    => $this->request->post('duration_text', ''),
        ];

        $db->execute(
            "INSERT INTO course_submodule_content
             (course_slug, module_num, submodule_key, content_title, content_html, image_url, video_url, video_embed, infographic_title, key_points, resources, duration_text)
             VALUES (?,?,?,?,?,?,?,?,?,?,?,?)
             ON DUPLICATE KEY UPDATE
               content_title=VALUES(content_title), content_html=VALUES(content_html),
               image_url=VALUES(image_url), video_url=VALUES(video_url),
               video_embed=VALUES(video_embed), infographic_title=VALUES(infographic_title),
               key_points=VALUES(key_points), resources=VALUES(resources),
               duration_text=VALUES(duration_text), updated_at=NOW()",
            [
                $slug, $mod, $sub,
                $data['content_title'], $data['content_html'],
                $data['image_url'], $data['video_url'], $data['video_embed'],
                $data['infographic_title'],
                $data['key_points'], $data['resources'],
                $data['duration_text'],
            ]
        );

        $this->json(['success' => true, 'message' => "Module {$mod} · Lesson {$sub} saved!"]);
    }

    /** POST /techaasvik_admin/course/content-editor/{mod}/{sub}/delete — reset to hardcoded */
    public function contentEditorDelete(array $params = []): void
    {
        $this->verifyCsrf();
        $mod  = (int)($params['mod'] ?? 0);
        $sub  = preg_replace('/[^0-9\-]/', '', $params['sub'] ?? '');
        $slug = 'ai-marketing-course';

        $db = \Core\Database::getInstance();
        $db->execute(
            "DELETE FROM course_submodule_content WHERE course_slug=? AND module_num=? AND submodule_key=?",
            [$slug, $mod, $sub]
        );
        $this->json(['success' => true, 'message' => "Content reset to default for {$mod}-{$sub}."]);
    }
}
