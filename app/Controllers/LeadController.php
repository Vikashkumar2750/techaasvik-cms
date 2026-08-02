<?php
namespace Controllers;

use Core\Controller;
use Core\View;
use Models\Lead;
use Services\EmailService;

/**
 * Lead Controller — handles all form submissions via AJAX or POST.
 */
class LeadController extends Controller
{
    /**
     * Verify reCAPTCHA v3 token. Returns true if valid or if reCAPTCHA is not configured.
     */
    private function verifyRecaptcha(): bool
    {
        $config = require APP_PATH . '/Config/config.php';
        $secret = $config['google']['recaptcha_secret_key'] ?? '';
        if (empty($secret) || $secret === 'RECAPTCHA_SECRET_KEY') {
            return true; // reCAPTCHA not configured, skip
        }

        $token = $this->request->post('g_recaptcha_token', '');
        if (empty($token)) return false;

        $response = @file_get_contents('https://www.google.com/recaptcha/api/siteverify?' . http_build_query([
            'secret'   => $secret,
            'response' => $token,
            'remoteip' => $_SERVER['REMOTE_ADDR'] ?? '',
        ]));

        if (!$response) return true; // Can't reach Google, allow through

        $result = json_decode($response, true);
        return ($result['success'] ?? false) && ($result['score'] ?? 0) >= 0.3;
    }
    public function newsletter(array $params = []): void
    {
        if (!$this->verifyRecaptcha()) {
            $this->respondError('Security verification failed. Please try again.');
            return;
        }

        $email = filter_var(
            $this->request->post('email', ''),
            FILTER_SANITIZE_EMAIL
        );

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->respondError('Please enter a valid email address.');
            return;
        }

        // Check duplicate
        $exists = $this->db->fetchOne("SELECT id FROM leads WHERE email = ? AND lead_type = 'newsletter'", [$email]);
        if ($exists) {
            $this->respondSuccess('You\'re already subscribed! 🎉');
            return;
        }

        $lead = new Lead();
        $data = [
            'email'       => $email,
            'name'        => $this->request->post('name', ''),
            'lead_type'   => 'newsletter',
            'source_page' => $this->request->post('source', $_SERVER['HTTP_REFERER'] ?? ''),
        ];
        $lead->capture($data);

        // Notify admin
        (new EmailService())->notifyLead($data);

        $this->respondSuccess('Welcome! You\'re subscribed to our weekly digest. 📧');
    }

    public function audit(array $params = []): void
    {
        if (!$this->verifyRecaptcha()) {
            $this->respondError('Security verification failed. Please try again.');
            return;
        }

        $email   = filter_var($this->request->post('email', ''), FILTER_SANITIZE_EMAIL);
        $website = $this->request->post('website', '');

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->respondError('Please enter a valid email address.');
            return;
        }

        $lead = new Lead();
        $data = [
            'email'       => $email,
            'name'        => $this->request->post('name', ''),
            'website'     => $website,
            'lead_type'   => 'audit',
            'source_page' => $_SERVER['HTTP_REFERER'] ?? '',
        ];
        $lead->capture($data);

        (new EmailService())->notifyLead($data);

        $this->respondSuccess('Your free audit request is received! We\'ll contact you within 24 hours. 🚀');
    }

    public function download(array $params = []): void
    {
        if (!$this->verifyRecaptcha()) {
            $this->respondError('Security verification failed.');
            return;
        }

        $email    = filter_var($this->request->post('email', ''), FILTER_SANITIZE_EMAIL);
        $resource = $this->request->post('resource', '');

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->respondError('Please enter a valid email address.');
            return;
        }

        $lead = new Lead();
        $data = [
            'email'       => $email,
            'name'        => $this->request->post('name', ''),
            'lead_type'   => 'download',
            'message'     => 'Resource: ' . $resource,
            'source_page' => $_SERVER['HTTP_REFERER'] ?? '',
        ];
        $lead->capture($data);
        (new EmailService())->notifyLead($data);

        $this->respondSuccess('Check your email for the download link!', [
            'download_url' => '/assets/downloads/' . basename($resource),
        ]);
    }

    private function respondSuccess(string $message, array $extra = []): void
    {
        View::json(array_merge(['success' => true, 'message' => $message], $extra));
    }

    private function respondError(string $message): void
    {
        View::json(['success' => false, 'message' => $message], 422);
    }

    /** Alias for audit() — used by route /lead/audit */
    public function auditRequest(array $params = []): void
    {
        $this->audit($params);
    }

    /** Contact form submission via AJAX */
    public function contact(array $params = []): void
    {
        if (!$this->verifyRecaptcha()) {
            $this->respondError('Security verification failed.');
            return;
        }

        $name    = trim($this->request->post('name', ''));
        $email   = filter_var($this->request->post('email', ''), FILTER_SANITIZE_EMAIL);
        $message = trim($this->request->post('message', ''));

        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($message) < 10) {
            $this->respondError('Please fill all required fields correctly.');
            return;
        }

        $lead = new Lead();
        $data = [
            'name'        => $name,
            'email'       => $email,
            'message'     => $message,
            'lead_type'   => 'contact',
            'source_page' => $_SERVER['HTTP_REFERER'] ?? '/contact',
        ];
        $lead->capture($data);
        (new EmailService())->notifyLead($data);

        $this->respondSuccess('Message received! We\'ll get back to you within 24 hours. ✅');
    }
}

