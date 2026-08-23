<?php
namespace Controllers;

use Core\Controller;
use Core\Auth;
use Models\CourseEnrollment;
use Models\CourseProgress;
use Models\CourseCertificate;
use Models\CourseCoupon;
use Models\CourseSetting;
use Services\RazorpayService;
use Services\CertificateService;

/**
 * CourseController — handles all course routes:
 * listing, landing, registration, player, quiz, payment, certificate
 */
class CourseController extends Controller
{
    private CourseEnrollment  $enrollments;
    private CourseProgress    $progress;
    private CourseCertificate $certs;
    private CourseCoupon      $coupons;
    private CourseSetting     $settings;
    private const COURSE_SLUG  = 'ai-marketing-course';
    private const TOTAL_MODULES = 10;
    private const COOKIE_NAME   = 'ta_course_token';

    public function __construct()
    {
        parent::__construct();
        $this->enrollments = new CourseEnrollment();
        $this->progress    = new CourseProgress();
        $this->certs       = new CourseCertificate();
        $this->coupons     = new CourseCoupon();
        $this->settings    = new CourseSetting();
    }

    // ── Courses Index ─────────────────────────────────────────
    public function index(array $params = []): void
    {
        $this->view('courses-index', [
            'seo' => [
                'title'       => 'Free Digital Marketing Courses — TechAasvik',
                'description' => 'Expert-designed courses on SEO, AI Marketing, Google Ads, Meta Ads, GA4 and more. Free to start, industry-certified.',
                'canonical'   => 'https://techaasvik.com/courses',
            ],
        ]);
    }

    // ── Course Landing Page ───────────────────────────────────
    public function landing(array $params = []): void
    {
        $slug = $params['slug'] ?? '';

        // Only the AI Marketing course is fully built
        if ($slug !== self::COURSE_SLUG) {
            // Fallback to old static course page
            $this->legacyCoursePage($slug);
            return;
        }

        $enrollment = $this->getCurrentEnrollment();
        $freeCount  = $this->settings->freeModulesCount();
        $priceOrig  = $this->settings->priceOriginal();
        $priceSale  = $this->settings->priceSale();

        $this->view('course-landing', [
            'seo' => [
                'title'       => 'AI Marketing & ChatGPT SEO Course — Free + Certified | TechAasvik',
                'description' => 'Master AI-powered marketing: prompt engineering, AI SEO, content systems, GEO, paid ads, automation and analytics. Free first 5 modules. Certificate on completion.',
                'canonical'   => 'https://techaasvik.com/courses/' . self::COURSE_SLUG,
            ],
            'enrollment'  => $enrollment,
            'freeCount'   => $freeCount,
            'priceOrig'   => $priceOrig,
            'priceSale'   => $priceSale,
            'modules'     => $this->getCourseModules(),
        ]);
    }

    // ── Course Player ─────────────────────────────────────────
    public function player(array $params = []): void
    {
        Auth::startSession();
        $slug         = $params['slug']      ?? self::COURSE_SLUG;
        $moduleNum    = max(1, min(self::TOTAL_MODULES, (int)($params['module'] ?? 1)));
        $submoduleNum = max(1, (int)($params['submodule'] ?? 1));

        // Get or prompt enrollment
        $enrollment = $this->getCurrentEnrollment();
        if (!$enrollment) {
            $this->redirect('/courses/' . self::COURSE_SLUG . '?enroll=1');
            return;
        }

        $freeCount = $this->settings->freeModulesCount();
        $isPaid    = $enrollment['payment_status'] === 'paid';

        // Paywall check
        if ($moduleNum > $freeCount && !$isPaid) {
            $this->redirect('/courses/' . self::COURSE_SLUG . '/enroll');
            return;
        }

        $allModules  = $this->getCourseModules();
        $moduleData  = $allModules[$moduleNum - 1] ?? null;
        if (!$moduleData) { $this->notFound(); return; }

        // Build submodule list for this module
        $submodules  = $this->getSubmodules($moduleNum, $moduleData);
        $submoduleNum = min($submoduleNum, count($submodules));
        $currentSub  = $submodules[$submoduleNum - 1] ?? $submodules[0];

        $progress           = $this->progress->getForEnrollment($enrollment['id']);
        $completedSubKeys   = $this->progress->getCompletedSubmodules($enrollment['id']);
        $overallScore       = $this->progress->calculateOverallScore($enrollment['id']);
        $grade              = \Models\CourseProgress::scoreToGrade($overallScore);

        $videoEnabled = $this->settings->videoEnabled();
        $priceOrig    = $this->settings->priceOriginal();
        $priceSale    = $this->settings->priceSale();
        $processingFeePct = (float)$this->settings->get('processing_fee_pct', 1.5);

        // Check certificate eligibility
        $completedCount = $this->progress->countCompleted($enrollment['id']);
        $cert           = null;
        if ($completedCount >= self::TOTAL_MODULES) {
            $certSvc = new CertificateService();
            $uid     = $certSvc->issue($enrollment['id']);
            $cert    = $this->certs->findByEnrollment($enrollment['id']);
        }

        $this->view('course-player', [
            'seo' => [
                'title'   => "Module {$moduleNum}: {$moduleData['title']} — AI Marketing Course | TechAasvik",
                'noindex' => true,
            ],
            'enrollment'       => $enrollment,
            'moduleNum'        => $moduleNum,
            'submoduleNum'     => $submoduleNum,
            'totalModules'     => self::TOTAL_MODULES,
            'freeCount'        => $freeCount,
            'module'           => $moduleData,
            'allModules'       => $allModules,
            'submodules'       => $submodules,
            'currentSub'       => $currentSub,
            'progress'         => $progress,
            'completedSubKeys' => $completedSubKeys,
            'isPaid'           => $isPaid,
            'videoEnabled'     => $videoEnabled,
            'priceOrig'        => $priceOrig,
            'priceSale'        => $priceSale,
            'processingFeePct' => $processingFeePct,
            'cert'             => $cert,
            'overallScore'     => $overallScore,
            'grade'            => $grade,
        ]);
    }

    // ── Mark Submodule Complete (AJAX POST) ───────────────────
    public function markSubmoduleComplete(array $params = []): void
    {
        $this->verifyCsrf();
        $enrollment = $this->getCurrentEnrollment();
        if (!$enrollment) {
            $this->json(['success' => false], 401);
            return;
        }
        $subKey = preg_replace('/[^0-9\-quiz]/', '', $this->request->post('sub_key', ''));
        if (!$subKey) {
            $this->json(['success' => false, 'error' => 'Invalid key']);
            return;
        }
        $this->progress->markSubmoduleComplete($enrollment['id'], $subKey);

        // If all submodules of module done → mark module complete
        $moduleNum  = (int)explode('-', $subKey)[0];
        $submodules = $this->getSubmodules($moduleNum, $this->getCourseModules()[$moduleNum - 1] ?? []);
        $doneKeys   = $this->progress->getCompletedSubmodules($enrollment['id']);
        $allDone    = true;
        foreach ($submodules as $s) {
            if (!in_array($s['key'], $doneKeys)) { $allDone = false; break; }
        }
        if ($allDone) {
            $this->progress->markComplete($enrollment['id'], $moduleNum, 0, true);
        }

        $overallScore = $this->progress->calculateOverallScore($enrollment['id']);
        $grade        = \Models\CourseProgress::scoreToGrade($overallScore);
        $this->json(['success' => true, 'module_done' => $allDone, 'grade' => $grade, 'score' => $overallScore]);
    }


    // ── Registration (POST) ───────────────────────────────────
    public function register(array $params = []): void
    {
        Auth::startSession();
        $this->verifyCsrf();

        $name  = trim($this->request->post('name', ''));
        $email = strtolower(trim($this->request->post('email', '')));
        $phone = preg_replace('/[^0-9+]/', '', $this->request->post('phone', ''));

        if (!$name || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($phone) < 10) {
            $this->json(['success' => false, 'error' => 'Please fill all fields correctly.']);
            return;
        }

        // Check if already enrolled
        $existing = $this->enrollments->findByEmail($email, self::COURSE_SLUG);
        if ($existing) {
            $this->setEnrollmentCookie($existing['token']);
            $this->json(['success' => true, 'redirect' => '/courses/' . self::COURSE_SLUG . '/learn/1']);
            return;
        }

        // Create enrollment
        $token = bin2hex(random_bytes(32));
        $id    = $this->enrollments->create([
            'course_slug'    => self::COURSE_SLUG,
            'user_name'      => $name,
            'user_email'     => $email,
            'user_phone'     => $phone,
            'payment_status' => 'free',
            'token'          => $token,
            'ip_address'     => $_SERVER['REMOTE_ADDR'] ?? null,
        ]);

        $this->setEnrollmentCookie($token);
        $this->json(['success' => true, 'redirect' => '/courses/' . self::COURSE_SLUG . '/learn/1']);
    }

    // ── Enroll Page (Razorpay payment) ────────────────────────
    public function enroll(array $params = []): void
    {
        Auth::startSession();
        $enrollment = $this->getCurrentEnrollment();
        if (!$enrollment) {
            $this->redirect('/courses/' . self::COURSE_SLUG . '?enroll=1');
            return;
        }
        if ($enrollment['payment_status'] === 'paid') {
            $this->redirect('/courses/' . self::COURSE_SLUG . '/learn/6');
            return;
        }

        $priceOrig  = $this->settings->priceOriginal();
        $priceSale  = $this->settings->priceSale();
        $keyId      = $this->settings->razorpayKeyId();

        $this->view('course-enroll', [
            'seo'        => ['title' => 'Unlock Full AI Marketing Course — TechAasvik', 'noindex' => true],
            'enrollment' => $enrollment,
            'priceOrig'  => $priceOrig,
            'priceSale'  => $priceSale,
            'razorpayKeyId' => $keyId,
        ]);
    }

    // ── Apply Coupon (JSON) ───────────────────────────────────
    public function applyCoupon(array $params = []): void
    {
        $this->verifyCsrf();
        $code       = strtoupper(trim($this->request->post('coupon', '')));
        $enrollment = $this->getCurrentEnrollment();

        if (!$enrollment) {
            $this->json(['success' => false, 'error' => 'Not enrolled.']);
            return;
        }

        $coupon = $this->coupons->findActive($code);
        if (!$coupon) {
            $this->json(['success' => false, 'error' => 'Invalid or expired coupon code.']);
            return;
        }

        $base      = $this->settings->priceSale();
        $final     = $this->coupons->applyDiscount($coupon, $base);
        $discounted = $this->coupons->getDiscountAmount($coupon, $base);

        $this->json([
            'success'          => true,
            'original'         => $base,
            'discount'         => $discounted,
            'final'            => $final,
            'coupon_id'        => $coupon['id'],
            'discount_display' => $coupon['discount_type'] === 'percent'
                ? $coupon['discount_value'] . '% off'
                : '₹' . $coupon['discount_value'] . ' off',
        ]);
    }

    // ── Create Razorpay Order (JSON) ──────────────────────────
    public function createOrder(array $params = []): void
    {
        $this->verifyCsrf();
        $enrollment = $this->getCurrentEnrollment();
        if (!$enrollment) {
            $this->json(['success' => false, 'error' => 'Not enrolled.'], 401);
            return;
        }

        $baseAmount = $this->settings->priceSale();
        $couponId   = (int)$this->request->post('coupon_id', 0);

        // Apply coupon if provided
        $couponCode     = null;
        $discountAmount = 0;
        if ($couponId) {
            // Fixed: use Database::getInstance() instead of $this->db
            $db     = \Core\Database::getInstance();
            $coupon = $db->fetchOne("SELECT * FROM course_coupons WHERE id=?", [$couponId]);
            if ($coupon && $this->coupons->findActive($coupon['code'])) {
                $discountAmount = $this->coupons->getDiscountAmount($coupon, $baseAmount);
                $baseAmount     = $this->coupons->applyDiscount($coupon, $baseAmount);
                $couponCode     = $coupon['code'];
            }
        }

        // 1.5% processing fee (visible to user)
        $processingFeePct = (float)($this->settings->get('processing_fee_pct', 1.5));
        $processingFee    = round($baseAmount * ($processingFeePct / 100), 2);
        $finalAmount      = round($baseAmount + $processingFee, 2);

        // Minimum amount guard
        if ($baseAmount < 1) {
            // 100% discount → free access
            $this->enrollments->updatePayment($enrollment['id'], [
                'payment_status'     => 'paid',
                'razorpay_order_id'  => 'COUPON_FREE',
                'razorpay_payment_id'=> 'COUPON_FREE',
                'razorpay_signature' => 'COUPON_FREE',
                'amount_paid'        => 0,
                'coupon_code'        => $couponCode,
                'discount_amount'    => $discountAmount,
            ]);
            if ($couponCode) {
                $c = $this->coupons->findActive($couponCode);
                if ($c) $this->coupons->incrementUse($c['id']);
            }
            $this->json(['success' => true, 'free' => true, 'redirect' => '/courses/' . self::COURSE_SLUG . '/learn/1/1']);
            return;
        }

        try {
            $rzp   = new RazorpayService();
            $order = $rzp->createOrder($finalAmount, 'enr_' . $enrollment['id'], [
                'enrollment_id' => $enrollment['id'],
                'email'         => $enrollment['user_email'],
                'base_amount'   => $baseAmount,
                'processing_fee'=> $processingFee,
            ]);

            // Store order ID
            $this->enrollments->setOrderId($enrollment['id'], $order['id']);

            // Store coupon info in session for verification step
            Auth::startSession();
            $_SESSION['pending_coupon']        = $couponCode;
            $_SESSION['pending_discount']      = $discountAmount;
            $_SESSION['pending_amount']        = $baseAmount;
            $_SESSION['pending_processing_fee']= $processingFee;
            $_SESSION['pending_final']         = $finalAmount;

            $this->json([
                'success'        => true,
                'order_id'       => $order['id'],
                'amount'         => (int)round($finalAmount * 100), // paise
                'currency'       => 'INR',
                'key_id'         => $rzp->getKeyId(),
                'name'           => $enrollment['user_name'],
                'email'          => $enrollment['user_email'],
                'phone'          => $enrollment['user_phone'],
                'base_amount'    => $baseAmount,
                'processing_fee' => $processingFee,
                'final_amount'   => $finalAmount,
                'fee_pct'        => $processingFeePct,
            ]);
        } catch (\Exception $e) {
            error_log("CourseController::createOrder error: " . $e->getMessage());
            $this->json(['success' => false, 'error' => 'Payment initialization failed. Please try again. (' . $e->getMessage() . ')'], 500);
        }
    }


    // ── Verify Payment (POST from Razorpay Checkout) ──────────
    public function verifyPayment(array $params = []): void
    {
        $this->verifyCsrf();
        $enrollment = $this->getCurrentEnrollment();
        if (!$enrollment) {
            $this->json(['success' => false, 'error' => 'Session expired.'], 401);
            return;
        }

        $orderId   = $this->request->post('razorpay_order_id', '');
        $paymentId = $this->request->post('razorpay_payment_id', '');
        $signature = $this->request->post('razorpay_signature', '');

        if (!$orderId || !$paymentId || !$signature) {
            $this->json(['success' => false, 'error' => 'Invalid payment data.'], 400);
            return;
        }

        try {
            $rzp = new RazorpayService();
            if (!$rzp->verifyPaymentSignature($orderId, $paymentId, $signature)) {
                error_log("Payment signature verification failed for enrollment #{$enrollment['id']}");
                $this->json(['success' => false, 'error' => 'Payment verification failed.'], 400);
                return;
            }

            Auth::startSession();
            $couponCode     = $_SESSION['pending_coupon']        ?? null;
            $discountAmount = $_SESSION['pending_discount']      ?? 0;
            $amountPaid     = $_SESSION['pending_final']         ?? ($_SESSION['pending_amount'] ?? $this->settings->priceSale());
            unset($_SESSION['pending_coupon'], $_SESSION['pending_discount'], $_SESSION['pending_amount'],
                  $_SESSION['pending_processing_fee'], $_SESSION['pending_final']);

            $this->enrollments->updatePayment($enrollment['id'], [
                'payment_status'      => 'paid',
                'razorpay_order_id'   => $orderId,
                'razorpay_payment_id' => $paymentId,
                'razorpay_signature'  => $signature,
                'amount_paid'         => $amountPaid,
                'coupon_code'         => $couponCode,
                'discount_amount'     => $discountAmount,
            ]);

            // Increment coupon use
            if ($couponCode) {
                $coupon = $this->coupons->findActive($couponCode);
                if ($coupon) $this->coupons->incrementUse($coupon['id']);
            }

            $this->json([
                'success'  => true,
                'redirect' => '/courses/' . self::COURSE_SLUG . '/learn/6/1',
            ]);
        } catch (\Exception $e) {
            error_log("verifyPayment error: " . $e->getMessage());
            $this->json(['success' => false, 'error' => 'Verification error.'], 500);
        }
    }

    // ── Razorpay Webhook (server-to-server) ───────────────────
    public function webhook(array $params = []): void
    {
        $rawBody  = file_get_contents('php://input');
        $headerSig = $_SERVER['HTTP_X_RAZORPAY_SIGNATURE'] ?? '';

        try {
            $rzp = new RazorpayService();
            if (!$rzp->verifyWebhookSignature($rawBody, $headerSig)) {
                http_response_code(400);
                echo 'Invalid signature';
                return;
            }
        } catch (\Exception) {
            http_response_code(500);
            return;
        }

        $payload = json_decode($rawBody, true);
        $event   = $payload['event'] ?? '';

        if ($event === 'payment.captured') {
            $paymentId = $payload['payload']['payment']['entity']['id'] ?? '';
            $orderId   = $payload['payload']['payment']['entity']['order_id'] ?? '';
            $amount    = ($payload['payload']['payment']['entity']['amount'] ?? 0) / 100;

            // Find and update enrollment
            $enrollment = $this->db->fetchOne(
                "SELECT * FROM course_enrollments WHERE razorpay_order_id=? LIMIT 1",
                [$orderId]
            );
            if ($enrollment && $enrollment['payment_status'] !== 'paid') {
                $this->enrollments->updatePayment($enrollment['id'], [
                    'payment_status'      => 'paid',
                    'razorpay_order_id'   => $orderId,
                    'razorpay_payment_id' => $paymentId,
                    'razorpay_signature'  => 'webhook',
                    'amount_paid'         => $amount,
                    'coupon_code'         => $enrollment['coupon_code'],
                    'discount_amount'     => $enrollment['discount_amount'],
                ]);
            }
        }

        http_response_code(200);
        echo 'OK';
    }

    // ── Submit Quiz (JSON) ────────────────────────────────────
    public function submitQuiz(array $params = []): void
    {
        $this->verifyCsrf();
        $enrollment = $this->getCurrentEnrollment();
        if (!$enrollment) {
            $this->json(['success' => false, 'error' => 'Not enrolled.'], 401);
            return;
        }

        $moduleNum = (int)$this->request->post('module', 0);
        if ($moduleNum < 1 || $moduleNum > self::TOTAL_MODULES) {
            $this->json(['success' => false, 'error' => 'Invalid module.'], 400);
            return;
        }

        // Accept score from client (quiz graded in browser) or recompute from module quiz
        $score  = (int)$this->request->post('score', 0);
        $passed = (bool)(int)$this->request->post('passed', 0);
        $answers = $this->request->post('answers', []);
        if (is_string($answers)) $answers = json_decode($answers, true) ?? [];

        // Clamp score
        $score = max(0, min(100, $score));

        // Save quiz result and attempt
        $this->progress->saveQuizResult($enrollment['id'], $moduleNum, $score, $passed);
        $this->progress->saveQuizAttempt($enrollment['id'], $moduleNum, $answers, $score, $passed);

        // Mark module complete if passed
        if ($passed) {
            $this->progress->markComplete($enrollment['id'], $moduleNum, $score, $passed);
        }

        // Compute overall grade
        $overallScore = $this->progress->calculateOverallScore($enrollment['id']);
        $grade        = \Models\CourseProgress::scoreToGrade($overallScore);

        // Check if all modules done → issue certificate
        $completedCount = $this->progress->countCompleted($enrollment['id']);
        $certUid        = null;
        if ($completedCount >= self::TOTAL_MODULES) {
            $certSvc = new CertificateService();
            $certUid = $certSvc->issue($enrollment['id']);
        }

        $this->json([
            'success'       => true,
            'score'         => $score,
            'passed'        => $passed,
            'grade'         => $grade,
            'overall_score' => $overallScore,
            'cert_uid'      => $certUid,
            'completed'     => $completedCount,
        ]);
    }


    // ── Certificate View ──────────────────────────────────────
    public function certificate(array $params = []): void
    {
        $uid  = $params['uid'] ?? '';
        $cert = $this->certs->findByUid($uid);
        if (!$cert) { $this->notFound(); return; }

        $this->certs->incrementDownload($uid);

        $certSvc  = new CertificateService();
        $html     = $certSvc->buildCertificateHtml($cert);

        // Output certificate as standalone HTML page
        header('Content-Type: text/html; charset=UTF-8');
        echo $html;
        exit;
    }

    // ── Verify Certificate (public) ───────────────────────────
    public function verifyCertificate(array $params = []): void
    {
        // Support both URL param and ?id=... form search
        $uid  = $params['uid'] ?? $this->request->get('id', '');
        $uid  = preg_replace('/[^a-f0-9]/', '', $uid); // only hex
        $cert = $uid ? $this->certs->findByUid($uid) : null;

        $this->view('verify-certificate', [
            'seo' => [
                'title'       => 'Verify Certificate — TechAasvik',
                'description' => 'Verify the authenticity of a TechAasvik course completion certificate.',
                'noindex'     => false,
            ],
            'cert' => $cert,
            'uid'  => $uid,
        ]);
    }

    // ── Helpers ───────────────────────────────────────────────
    private function getCurrentEnrollment(): ?array
    {
        $token = $_COOKIE[self::COOKIE_NAME] ?? '';
        if (!$token || strlen($token) !== 64) return null;
        $token = preg_replace('/[^a-f0-9]/', '', $token);
        return $this->enrollments->findByToken($token) ?: null;
    }

    private function setEnrollmentCookie(string $token): void
    {
        setcookie(self::COOKIE_NAME, $token, [
            'expires'  => time() + (365 * 24 * 3600),
            'path'     => '/',
            'secure'   => true,
            'httponly' => true,
            'samesite' => 'Strict',
        ]);
        $_COOKIE[self::COOKIE_NAME] = $token;
    }

    // Fallback for other courses (not fully built yet)
    private function legacyCoursePage(string $slug): void
    {
        $this->view('course', [
            'seo'     => ['title' => ucwords(str_replace('-', ' ', $slug)) . ' — TechAasvik Courses'],
            'course'  => ['title' => ucwords(str_replace('-', ' ', $slug)), 'excerpt' => '', 'difficulty' => '', 'read_time' => ''],
            'modules' => [],
        ]);
    }

    // ── Course Module Data ────────────────────────────────────
    public function getCourseModules(): array
    {
        return [
            // MODULE 1 — FREE
            [
                'num'     => 1,
                'free'    => true,
                'title'   => 'AI-Native Marketing: What Changed & What Matters',
                'emoji'   => '🧠',
                'tagline' => 'The mindset shift every marketer needs right now',
                'duration'=> '45 min',
                'lessons' => [
                    ['title' => 'How AI Transformed Marketing (Not What You Think)', 'duration' => '8 min'],
                    ['title' => 'Search Has Changed — What It Means for Your Strategy', 'duration' => '6 min'],
                    ['title' => 'AI in Content, Ads, and Automation', 'duration' => '7 min'],
                    ['title' => 'AI Agents: What They Are & Why Marketers Need to Know', 'duration' => '6 min'],
                    ['title' => 'What Humans Still Do (Better Than AI)', 'duration' => '5 min'],
                    ['title' => 'The Skills Gap: What to Build Now', 'duration' => '5 min'],
                    ['title' => 'Template: Your Personal AI Marketing Workflow', 'duration' => '8 min'],
                ],
                'content' => $this->module1Content(),
                'quiz'    => $this->module1Quiz(),
            ],
            // MODULE 2 — FREE
            [
                'num'     => 2,
                'free'    => true,
                'title'   => 'ChatGPT for Marketers: Context Engineering & Reliable Workflows',
                'emoji'   => '💬',
                'tagline' => 'Stop prompting randomly. Build repeatable, reliable AI systems.',
                'duration'=> '50 min',
                'lessons' => [
                    ['title' => 'Why "Prompt Engineering" Is the Wrong Frame', 'duration' => '5 min'],
                    ['title' => 'Context Engineering: What the AI Actually Needs', 'duration' => '8 min'],
                    ['title' => 'Prompt Structure: Role, Context, Examples, Constraints, Output', 'duration' => '10 min'],
                    ['title' => 'Iterative Prompting & Multi-Step Workflows', 'duration' => '8 min'],
                    ['title' => 'Research Workflows with AI', 'duration' => '7 min'],
                    ['title' => 'Fact-Checking & Hallucination Detection', 'duration' => '6 min'],
                    ['title' => 'Build 10 Reusable Marketing Workflows', 'duration' => '6 min'],
                ],
                'content' => $this->module2Content(),
                'quiz'    => $this->module2Quiz(),
            ],
            // MODULE 3 — FREE
            [
                'num'     => 3,
                'free'    => true,
                'title'   => 'AI Market Research & Competitor Intelligence',
                'emoji'   => '🔍',
                'tagline' => 'The biggest edge most marketers are completely missing.',
                'duration'=> '55 min',
                'lessons' => [
                    ['title' => 'Why AI Research Changes Everything', 'duration' => '5 min'],
                    ['title' => 'Customer Research with AI: Pain Points, Language, Jobs-to-Be-Done', 'duration' => '10 min'],
                    ['title' => 'Competitor Analysis: Content, Positioning, Gaps', 'duration' => '10 min'],
                    ['title' => 'Mining Reviews for Insights', 'duration' => '7 min'],
                    ['title' => 'Market Gaps & Positioning with AI', 'duration' => '8 min'],
                    ['title' => 'Building a Messaging Framework', 'duration' => '7 min'],
                    ['title' => 'Project: Full AI Market Intelligence Report', 'duration' => '8 min'],
                ],
                'content' => $this->module3Content(),
                'quiz'    => $this->module3Quiz(),
            ],
            // MODULE 4 — FREE
            [
                'num'     => 4,
                'free'    => true,
                'title'   => 'AI-Powered SEO: From Keywords to Search Visibility',
                'emoji'   => '📈',
                'tagline' => 'Not just keyword research — a complete AI-assisted SEO system.',
                'duration'=> '60 min',
                'lessons' => [
                    ['title' => 'How AI Changed SEO (Entity-First Thinking)', 'duration' => '7 min'],
                    ['title' => 'AI Keyword Research: Search Intent & Semantic Clusters', 'duration' => '10 min'],
                    ['title' => 'Topical Authority: Building Topic Clusters with AI', 'duration' => '8 min'],
                    ['title' => 'AI-Assisted Content Briefs & On-Page SEO', 'duration' => '10 min'],
                    ['title' => 'Schema, Internal Linking & Technical SEO Audits', 'duration' => '8 min'],
                    ['title' => 'Content Refresh Strategy with AI', 'duration' => '7 min'],
                    ['title' => 'Project: Full Topical Map + Content Plan', 'duration' => '10 min'],
                ],
                'content' => $this->module4Content(),
                'quiz'    => $this->module4Quiz(),
            ],
            // MODULE 5 — FREE
            [
                'num'     => 5,
                'free'    => true,
                'title'   => 'AI Content Engine: Research → Create → Repurpose → Distribute',
                'emoji'   => '✍️',
                'tagline' => 'One content strategy. Every channel. Powered by AI.',
                'duration'=> '55 min',
                'lessons' => [
                    ['title' => 'The AI Content System vs. Just Writing with AI', 'duration' => '6 min'],
                    ['title' => 'Brand Voice & Human POV in AI Content', 'duration' => '7 min'],
                    ['title' => 'Long-Form AI Writing + E-E-A-T Preservation', 'duration' => '10 min'],
                    ['title' => 'LinkedIn, Instagram & YouTube with AI', 'duration' => '8 min'],
                    ['title' => 'Email Marketing Copy with AI', 'duration' => '7 min'],
                    ['title' => 'Repurposing & Content QA Checklist', 'duration' => '7 min'],
                    ['title' => 'Project: 30-Day Multi-Channel Content System', 'duration' => '10 min'],
                ],
                'content' => $this->module5Content(),
                'quiz'    => $this->module5Quiz(),
            ],
            // MODULE 6 — PAID
            [
                'num'     => 6,
                'free'    => false,
                'title'   => 'AI Search Visibility: GEO + AEO',
                'emoji'   => '🤖',
                'tagline' => 'Rank where AI answers users. The next frontier of SEO.',
                'duration'=> '60 min',
                'lessons' => [
                    ['title' => 'Google AI Overviews & AI Mode — How They Work', 'duration' => '8 min'],
                    ['title' => 'ChatGPT Search & Perplexity: Getting Cited', 'duration' => '8 min'],
                    ['title' => 'Entity Signals, Brand Mentions & Citations', 'duration' => '8 min'],
                    ['title' => 'Content Structure for AI Retrieval', 'duration' => '7 min'],
                    ['title' => 'Tracking AI Visibility: OAI-SearchBot & Search Console', 'duration' => '7 min'],
                    ['title' => 'Project: 50 AI Queries → Gap Analysis → Improve → Retest', 'duration' => '12 min'],
                    ['title' => 'AI Safety: Hallucination Checking & Source Verification', 'duration' => '10 min'],
                ],
                'content' => $this->module6Content(),
                'quiz'    => $this->module6Quiz(),
            ],
            // MODULE 7 — PAID
            [
                'num'     => 7,
                'free'    => false,
                'title'   => 'AI Paid Media: Google Ads + Meta Ads',
                'emoji'   => '🎯',
                'tagline' => 'AI bidding, creative testing, and scaling — the right way.',
                'duration'=> '65 min',
                'lessons' => [
                    ['title' => 'Campaign Architecture in the AI Era', 'duration' => '8 min'],
                    ['title' => 'Google Performance Max & AI Max', 'duration' => '10 min'],
                    ['title' => 'Meta Advantage+ Campaigns', 'duration' => '8 min'],
                    ['title' => 'AI Creative Generation & Testing', 'duration' => '8 min'],
                    ['title' => 'Conversion Signals & Smart Bidding', 'duration' => '8 min'],
                    ['title' => 'Budget, CAC, ROAS & Scaling Frameworks', 'duration' => '10 min'],
                    ['title' => 'Project: AI Campaign Strategy for a Real Business', 'duration' => '13 min'],
                ],
                'content' => $this->module7Content(),
                'quiz'    => $this->module7Quiz(),
            ],
            // MODULE 8 — PAID
            [
                'num'     => 8,
                'free'    => false,
                'title'   => 'AI Conversion & Marketing Automation',
                'emoji'   => '⚡',
                'tagline' => 'Traffic → Lead → Nurture → Conversion. Automated.',
                'duration'=> '65 min',
                'lessons' => [
                    ['title' => 'CRO Fundamentals: What Actually Moves Conversion', 'duration' => '8 min'],
                    ['title' => 'Landing Page Optimization with AI', 'duration' => '8 min'],
                    ['title' => 'AI Lead Capture & Qualification', 'duration' => '7 min'],
                    ['title' => 'Email & WhatsApp Automation Sequences', 'duration' => '10 min'],
                    ['title' => 'n8n & Zapier AI Workflows', 'duration' => '10 min'],
                    ['title' => 'CRM Integration & AI Follow-ups', 'duration' => '8 min'],
                    ['title' => 'Project: Build End-to-End Lead Funnel', 'duration' => '14 min'],
                ],
                'content' => $this->module8Content(),
                'quiz'    => $this->module8Quiz(),
            ],
            // MODULE 9 — PAID
            [
                'num'     => 9,
                'free'    => false,
                'title'   => 'AI Analytics, Attribution & Marketing Decisions',
                'emoji'   => '📊',
                'tagline' => "Don't just read data. Diagnose it. Prescribe action.",
                'duration'=> '60 min',
                'lessons' => [
                    ['title' => 'GA4 Fundamentals for Decision-Making', 'duration' => '8 min'],
                    ['title' => 'UTM Strategy & Funnel Analysis', 'duration' => '7 min'],
                    ['title' => 'Attribution Models: What They Mean & What to Use', 'duration' => '8 min'],
                    ['title' => 'CAC, CPL, CPA, ROAS & LTV Frameworks', 'duration' => '8 min'],
                    ['title' => 'AI-Powered Dashboard Building', 'duration' => '7 min'],
                    ['title' => 'Marketing Diagnosis: Finding What\'s Broken', 'duration' => '8 min'],
                    ['title' => 'Project: Analyze 90 Days of Data → Tell the Founder What to Do', 'duration' => '14 min'],
                ],
                'content' => $this->module9Content(),
                'quiz'    => $this->module9Quiz(),
            ],
            // MODULE 10 — PAID (CAPSTONE)
            [
                'num'     => 10,
                'free'    => false,
                'title'   => 'Capstone: Build Your AI Marketing Operating System',
                'emoji'   => '🚀',
                'tagline' => 'Take one real business. Build the complete system. This is your final proof.',
                'duration'=> '90 min',
                'lessons' => [
                    ['title' => 'Capstone Overview & Business Selection', 'duration' => '8 min'],
                    ['title' => 'Phase 1: Customer & Market Intelligence', 'duration' => '10 min'],
                    ['title' => 'Phase 2: SEO + AI Search Visibility', 'duration' => '10 min'],
                    ['title' => 'Phase 3: Content System', 'duration' => '10 min'],
                    ['title' => 'Phase 4: Paid Media Strategy', 'duration' => '8 min'],
                    ['title' => 'Phase 5: Conversion + Automation', 'duration' => '10 min'],
                    ['title' => 'Phase 6: Analytics & Reporting', 'duration' => '8 min'],
                    ['title' => 'Your AI Marketing OS — Presentation', 'duration' => '12 min'],
                    ['title' => 'Certificate & What\'s Next', 'duration' => '14 min'],
                ],
                'content' => $this->module10Content(),
                'quiz'    => $this->module10Quiz(),
            ],
        ];
    }

    // ── Module Content Methods ────────────────────────────────
    private function module1Content(): string { return $this->renderModuleContent(1); }
    private function module2Content(): string { return $this->renderModuleContent(2); }
    private function module3Content(): string { return $this->renderModuleContent(3); }
    private function module4Content(): string { return $this->renderModuleContent(4); }
    private function module5Content(): string { return $this->renderModuleContent(5); }
    private function module6Content(): string { return $this->renderModuleContent(6); }
    private function module7Content(): string { return $this->renderModuleContent(7); }
    private function module8Content(): string { return $this->renderModuleContent(8); }
    private function module9Content(): string { return $this->renderModuleContent(9); }
    private function module10Content(): string { return $this->renderModuleContent(10); }

    private function renderModuleContent(int $num): string
    {
        $file = APP_ROOT . "/views/partials/course-module-{$num}.php";
        if (file_exists($file)) {
            ob_start();
            include $file;
            return ob_get_clean();
        }
        return "<p>Module content coming soon.</p>";
    }

    // ── Quiz Definitions ──────────────────────────────────────
    private function module1Quiz(): array {
        return [
            ['q' => 'What is the most important shift AI has caused in search?', 'options' => ['More keywords rank', 'AI answers queries directly, reducing click-through', 'Google now shows more ads', 'SEO is no longer needed'], 'answer' => 1],
            ['q' => 'Which skill is most important for AI-native marketers?', 'options' => ['Graphic design', 'Context engineering and critical thinking', 'Social media posting', 'Video editing'], 'answer' => 1],
            ['q' => 'What do humans still do better than AI in marketing?', 'options' => ['Processing large datasets', 'Building genuine relationships and contextual judgment', 'Writing first drafts', 'Running A/B tests'], 'answer' => 1],
            ['q' => 'AI agents in marketing primarily help with:', 'options' => ['Creating logos', 'Multi-step autonomous tasks across tools', 'Only email writing', 'Printing brochures'], 'answer' => 1],
            ['q' => 'Your personal AI marketing workflow should start with:', 'options' => ['Tool selection', 'Understanding your audience\'s real problems', 'Budget allocation', 'Competitor spying'], 'answer' => 1],
        ];
    }
    private function module2Quiz(): array {
        return [
            ['q' => 'Context engineering differs from prompt engineering in that it focuses on:', 'options' => ['Writing shorter prompts', 'Providing complete context for reliable AI output', 'Using only one prompt', 'Avoiding instructions'], 'answer' => 1],
            ['q' => 'Which CRAFT element sets boundaries for AI output?', 'options' => ['Role', 'Context', 'Constraints', 'Format'], 'answer' => 2],
            ['q' => 'Iterative prompting means:', 'options' => ['Writing one perfect prompt', 'Refining prompts based on output across multiple steps', 'Using the same prompt repeatedly', 'Copying prompts from others'], 'answer' => 1],
            ['q' => 'When fact-checking AI output, you should:', 'options' => ['Trust it if it sounds confident', 'Verify against primary sources', 'Only check statistics', 'Never use AI for factual content'], 'answer' => 1],
            ['q' => 'A reusable marketing workflow is valuable because:', 'options' => ['It looks impressive', 'It produces consistent, predictable outputs at scale', 'It requires less thinking', 'AI prefers routines'], 'answer' => 1],
        ];
    }
    private function module3Quiz(): array {
        return [
            ['q' => 'The most underused AI application in marketing is:', 'options' => ['Blog writing', 'Deep customer & competitor research', 'Image generation', 'Email subject lines'], 'answer' => 1],
            ['q' => 'Jobs-to-Be-Done research helps you understand:', 'options' => ['Which tools competitors use', 'What outcome customers actually want, not just features', 'Competitor ad budgets', 'SEO rankings'], 'answer' => 1],
            ['q' => 'Mining customer reviews is valuable for:', 'options' => ['Finding negative press', 'Discovering real customer language for messaging', 'Tracking competitor revenue', 'Finding their email list'], 'answer' => 1],
            ['q' => 'A content gap analysis reveals:', 'options' => ['How much content to publish', 'Topics your competitors rank for that you don\'t', 'Your website\'s load speed', 'Best posting times'], 'answer' => 1],
            ['q' => 'A positioning framework helps you:', 'options' => ['Copy competitor messaging', 'Differentiate clearly and target the right audience segment', 'Set ad budgets', 'Choose fonts'], 'answer' => 1],
        ];
    }
    private function module4Quiz(): array {
        return [
            ['q' => 'Topical authority in SEO means:', 'options' => ['Having the most backlinks', 'Comprehensively covering a topic so search engines trust your site', 'Posting daily', 'Buying guest posts'], 'answer' => 1],
            ['q' => 'Search intent is most important for:', 'options' => ['Domain authority', 'Matching content to what users actually want at each stage', 'Meta description length', 'Image size'], 'answer' => 1],
            ['q' => 'Entity-based SEO focuses on:', 'options' => ['Exact-match keywords', 'Named things (people, places, concepts) and their relationships', 'URL structure', 'Social signals'], 'answer' => 1],
            ['q' => 'An AI-generated content brief should include:', 'options' => ['Only a word count target', 'Target keywords, intent, structure, entities, and competitor gaps', 'Just a title', 'The writer\'s bio'], 'answer' => 1],
            ['q' => 'Content refresh with AI is most valuable for:', 'options' => ['New articles with no traffic', 'Pages that ranked but have declining traffic', 'Pages that never ranked', 'Homepage only'], 'answer' => 1],
        ];
    }
    private function module5Quiz(): array {
        return [
            ['q' => 'E-E-A-T stands for:', 'options' => ['Engagement, Earnings, Authority, Trust', 'Experience, Expertise, Authoritativeness, Trustworthiness', 'Email, Efficiency, AI, Traffic', 'Engagement, Effort, Analytics, Traffic'], 'answer' => 1],
            ['q' => 'The biggest risk of AI-generated content for SEO is:', 'options' => ['It is too expensive', 'Low-value, mass-produced content that signals spam', 'AI content is always incorrect', 'Google bans all AI content'], 'answer' => 1],
            ['q' => 'Brand voice in AI content ensures:', 'options' => ['Content is always positive', 'Output sounds like your brand, not generic AI', 'AI writes faster', 'SEO scores improve automatically'], 'answer' => 1],
            ['q' => 'Repurposing content means:', 'options' => ['Reposting the same content everywhere', 'Adapting core ideas for each platform\'s format and audience', 'Translating content', 'Deleting old content'], 'answer' => 1],
            ['q' => 'A 30-day content system primarily solves:', 'options' => ['Budget problems', 'Inconsistency and reactive content creation', 'Hiring needs', 'Domain authority'], 'answer' => 1],
        ];
    }
    private function module6Quiz(): array {
        return [
            ['q' => 'GEO (Generative Engine Optimization) focuses on:', 'options' => ['Traditional SEO ranking', 'Getting your content cited by AI answer engines', 'Google Maps ranking', 'Video SEO'], 'answer' => 1],
            ['q' => 'Google\'s AI Overviews pull content that is:', 'options' => ['Most recently published', 'Authoritative, structured, and clearly answers the query', 'Longest article', 'From .edu domains only'], 'answer' => 1],
            ['q' => 'Entity signals for AI visibility include:', 'options' => ['Keyword density', 'Brand mentions, citations, and structured data across the web', 'Image alt text only', 'Meta keywords'], 'answer' => 1],
            ['q' => 'OAI-SearchBot refers to:', 'options' => ['A Google bot', 'OpenAI\'s crawler for ChatGPT Search', 'A Bing crawler', 'Meta\'s AI bot'], 'answer' => 1],
            ['q' => 'The best way to track AI visibility is:', 'options' => ['Monthly Google rankings', 'Systematic testing of AI queries and recording mentions/citations', 'Social media monitoring only', 'Checking Alexa rank'], 'answer' => 1],
        ];
    }
    private function module7Quiz(): array {
        return [
            ['q' => 'Performance Max campaigns work best when:', 'options' => ['You restrict all targeting manually', 'You provide rich conversion data and creative assets', 'Budget is very low', 'You use broad match keywords only'], 'answer' => 1],
            ['q' => 'Meta Advantage+ Shopping is designed for:', 'options' => ['B2B lead generation', 'eCommerce with automated targeting and creative testing', 'App installs only', 'Brand awareness only'], 'answer' => 1],
            ['q' => 'The most important signal for AI bidding is:', 'options' => ['Ad frequency', 'High-quality conversion data from your website', 'Competitor bids', 'Ad copy length'], 'answer' => 1],
            ['q' => 'ROAS stands for:', 'options' => ['Return on Ad Spend', 'Rate of Ad Success', 'Revenue Over All Sources', 'Reach of Ad Sets'], 'answer' => 0],
            ['q' => 'Scaling a profitable campaign primarily requires:', 'options' => ['Duplicating it exactly', 'Incrementally increasing budget while monitoring efficiency metrics', 'Changing the offer', 'Reducing targeting'], 'answer' => 1],
        ];
    }
    private function module8Quiz(): array {
        return [
            ['q' => 'CRO (Conversion Rate Optimization) is about:', 'options' => ['Getting more traffic', 'Improving what percentage of visitors take your desired action', 'Reducing ad spend', 'Improving page speed only'], 'answer' => 1],
            ['q' => 'Lead qualification with AI helps:', 'options' => ['Generate more leads', 'Identify which leads are most likely to convert', 'Reduce email costs', 'Replace the sales team'], 'answer' => 1],
            ['q' => 'n8n is used for:', 'options' => ['Graphic design', 'No-code workflow automation with AI steps', 'SEO analysis', 'Email hosting'], 'answer' => 1],
            ['q' => 'The correct order for a nurture sequence is:', 'options' => ['Sell → Build trust → Welcome', 'Welcome → Build trust → Offer', 'Offer → Sell → Retarget', 'Retarget → Welcome → Offer'], 'answer' => 1],
            ['q' => 'Human approval steps in AI automation are important for:', 'options' => ['Slowing things down', 'Brand safety, accuracy, and preventing automated errors', 'Increasing costs', 'Reducing output'], 'answer' => 1],
        ];
    }
    private function module9Quiz(): array {
        return [
            ['q' => 'The best attribution model for understanding full customer journey is:', 'options' => ['Last-click', 'Data-driven attribution', 'First-click', 'Linear'], 'answer' => 1],
            ['q' => 'CAC stands for:', 'options' => ['Content Acquisition Cost', 'Customer Acquisition Cost', 'Campaign Ad Cost', 'Conversion Average Count'], 'answer' => 1],
            ['q' => 'GA4\'s biggest difference from Universal Analytics is:', 'options' => ['No bounce rate', 'Event-based measurement (not session-based)', 'No conversion tracking', 'Only for apps'], 'answer' => 1],
            ['q' => 'LTV (Lifetime Value) helps you decide:', 'options' => ['Which posts to publish', 'How much you can profitably spend to acquire a customer', 'Which social platform to use', 'Email frequency'], 'answer' => 1],
            ['q' => 'Marketing diagnosis means:', 'options' => ['Finding the highest traffic channel', 'Systematically identifying what\'s broken and prescribing a fix', 'Increasing ad budget', 'A/B testing everything'], 'answer' => 1],
        ];
    }
    private function module10Quiz(): array {
        return [
            ['q' => 'An AI Marketing Operating System primarily consists of:', 'options' => ['Tools only', 'Integrated workflows covering research, content, ads, and analytics', 'Only a content calendar', 'Ad campaigns'], 'answer' => 1],
            ['q' => 'The first step in a capstone marketing project should be:', 'options' => ['Setting ad budgets', 'Deep customer and market research', 'Building a website', 'Creating social profiles'], 'answer' => 1],
            ['q' => 'Responsible AI use in marketing requires:', 'options' => ['Ignoring bias in AI outputs', 'Transparency, accuracy checks, and human oversight', 'Publishing all AI content unedited', 'Using AI for every single task'], 'answer' => 1],
            ['q' => 'A complete AI marketing career roadmap should include:', 'options' => ['Only technical skills', 'Continuous learning, portfolio building, and staying current with tools', 'Certification only', 'Specializing in one tool forever'], 'answer' => 1],
            ['q' => 'The most important outcome of this course is:', 'options' => ['Memorizing AI tool names', 'Building a real, working AI marketing system you can apply immediately', 'Getting a certificate', 'Learning to code'], 'answer' => 1],
        ];
    }

    /**
     * Build submodule list for a given module.
     * Structure: 4 lesson submodules + 1 quiz submodule = 5 per module.
     */
    private function getSubmodules(int $moduleNum, array $moduleData): array
    {
        $titles = [
            1 => ['Overview & Mindset', 'AI\'s Impact on Marketing', 'Search & Content Shift', 'Automation & Personalization', 'Quiz'],
            2 => ['ChatGPT Basics for Marketers', 'Context Engineering', 'The CRAFT Framework', 'Building Reusable Workflows', 'Quiz'],
            3 => ['Research Mindset', 'Customer & Competitor Intel', 'Mining Reviews & Gaps', 'Positioning Framework', 'Quiz'],
            4 => ['Keyword Strategy with AI', 'Topical Authority Clusters', 'AI Content Briefs', 'Content Refresh System', 'Quiz'],
            5 => ['AI Content at Scale', 'E-E-A-T & Brand Voice', 'Multimedia & Repurposing', '30-Day Content System', 'Quiz'],
            6 => ['GEO Fundamentals', 'AI Overview Optimization', 'Entity Signals & Schema', 'AEO Answer Engineering', 'Quiz'],
            7 => ['Google Performance Max', 'Meta Advantage+ Ads', 'AI Bidding & Signals', 'ROAS Scaling System', 'Quiz'],
            8 => ['CRO with AI', 'Lead Scoring & Nurturing', 'n8n Workflow Automation', 'Email Sequences', 'Quiz'],
            9 => ['GA4 Setup & Events', 'Attribution Models', 'CAC, LTV & North Star', 'Marketing Diagnosis', 'Quiz'],
            10 => ['Build Your AI Marketing OS', 'Capstone: Real Business Plan', 'AI Safety & Ethics', 'Career Roadmap & Next Steps', 'Quiz'],
        ];

        $subTitles = $titles[$moduleNum] ?? ['Part 1', 'Part 2', 'Part 3', 'Part 4', 'Quiz'];
        $submodules = [];

        foreach ($subTitles as $i => $title) {
            $isQuiz = ($i === 4);
            $submodules[] = [
                'num'   => $i + 1,
                'key'   => $moduleNum . '-' . ($i + 1),
                'title' => $title,
                'type'  => $isQuiz ? 'quiz' : 'lesson',
                'icon'  => $isQuiz ? '📝' : '📖',
            ];
        }

        return $submodules;
    }
}

