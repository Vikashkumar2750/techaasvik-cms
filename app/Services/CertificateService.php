<?php
namespace Services;

/**
 * CertificateService — generates HTML certificate and emails it
 */
class CertificateService
{
    private \Models\CourseCertificate $certModel;
    private \Models\CourseEnrollment  $enrollModel;
    private \Models\CourseSetting     $settings;
    private MailService               $mailer;

    public function __construct()
    {
        $this->certModel   = new \Models\CourseCertificate();
        $this->enrollModel = new \Models\CourseEnrollment();
        $this->settings    = new \Models\CourseSetting();
        $this->mailer      = new MailService();
    }

    /**
     * Issue (or retrieve existing) certificate for an enrollment.
     * Returns the cert_uid.
     */
    public function issue(int $enrollmentId): string
    {
        // Check if already issued
        $existing = $this->certModel->findByEnrollment($enrollmentId);
        if ($existing) {
            return $existing['cert_uid'];
        }

        // Mark enrollment as completed
        $this->enrollModel->markCompleted($enrollmentId);

        // Create cert record
        $uid = $this->certModel->create($enrollmentId);

        // Send email
        $this->emailCertificate($enrollmentId, $uid);

        return $uid;
    }

    /**
     * Send certificate email to user.
     */
    public function emailCertificate(int $enrollmentId, string $uid): void
    {
        $enrollment = $this->enrollModel->findByToken('') ?? [];
        // Fetch by ID directly
        $enrollment = $this->fetchEnrollmentById($enrollmentId);
        if (!$enrollment) return;

        $verifyUrl  = "https://techaasvik.com/certificate/{$uid}";
        $courseTitle = $this->settings->get('course_title', 'AI Marketing & ChatGPT SEO');
        $html = $this->buildCertificateEmail($enrollment['user_name'], $courseTitle, $verifyUrl, $uid);

        $sent = $this->mailer->send(
            $enrollment['user_email'],
            $enrollment['user_name'],
            "🎓 Your TechAasvik Certificate — {$courseTitle}",
            $html
        );

        if ($sent) {
            $this->certModel->markEmailSent($enrollmentId);
        }
    }

    private function fetchEnrollmentById(int $id): ?array
    {
        // Direct DB fetch
        $db = \Core\Database::getInstance();
        return $db->fetchOne("SELECT * FROM course_enrollments WHERE id=? LIMIT 1", [$id]);
    }

    /**
     * Build printable certificate HTML.
     * Used by controller to render certificate page.
     */
    public function buildCertificateHtml(array $cert): string
    {
        $name       = htmlspecialchars($cert['user_name']);
        $course     = htmlspecialchars($this->settings->get('course_title', 'AI Marketing & ChatGPT SEO'));
        $date       = date('d F Y', strtotime($cert['issued_at'] ?? $cert['completed_at'] ?? 'now'));
        $uid        = $cert['cert_uid'];
        $signatory  = htmlspecialchars($this->settings->get('cert_signatory_name', 'TechAasvik Editorial Team'));
        $logoPath   = $this->settings->get('cert_logo_path', '');
        $sigPath    = $this->settings->get('cert_signature_path', '');
        $verifyUrl  = "https://techaasvik.com/certificate/{$uid}";

        $logoHtml = $logoPath
            ? "<img src=\"{$logoPath}\" alt=\"TechAasvik\" style=\"height:60px;\">"
            : "<div style=\"font-size:28px;font-weight:900;color:#6366f1;letter-spacing:-1px;\">TechAasvik</div>";

        $sigHtml = $sigPath
            ? "<img src=\"{$sigPath}\" alt=\"Signature\" style=\"height:50px;margin-bottom:4px;\">"
            : "<div style=\"font-size:20px;font-style:italic;color:#1e293b;\">TechAasvik</div>";

        return <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Certificate — {$name}</title>
<style>
  @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Playfair+Display:ital,wght@0,700;1,400&display=swap');
  * { margin:0; padding:0; box-sizing:border-box; }
  body { font-family:'Inter',sans-serif; background:#f8fafc; display:flex; align-items:center; justify-content:center; min-height:100vh; padding:20px; }
  .cert-wrap { max-width:900px; width:100%; }
  .cert {
    background:#fff;
    border:2px solid #e2e8f0;
    border-radius:16px;
    padding:60px 70px;
    position:relative;
    box-shadow: 0 20px 60px rgba(0,0,0,0.08);
    overflow:hidden;
  }
  .cert::before {
    content:'';
    position:absolute; top:0; left:0; right:0; height:8px;
    background:linear-gradient(90deg,#6366f1,#8b5cf6,#06b6d4);
  }
  .cert-corner {
    position:absolute; width:120px; height:120px;
    background:linear-gradient(135deg,rgba(99,102,241,0.06),rgba(139,92,246,0.04));
    border-radius:0 0 120px 0;
    top:0; left:0;
  }
  .cert-header { display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:40px; }
  .cert-badge { background:linear-gradient(135deg,#6366f1,#8b5cf6); color:#fff; padding:6px 16px; border-radius:100px; font-size:12px; font-weight:700; letter-spacing:0.08em; text-transform:uppercase; }
  .cert-title { text-align:center; margin-bottom:32px; }
  .cert-title .label { font-size:13px; text-transform:uppercase; letter-spacing:0.12em; color:#64748b; font-weight:600; margin-bottom:8px; }
  .cert-title .heading { font-family:'Playfair Display',serif; font-size:42px; font-weight:700; color:#0f172a; line-height:1.15; }
  .cert-student { text-align:center; margin:28px 0; padding:28px; background:linear-gradient(135deg,rgba(99,102,241,0.04),rgba(139,92,246,0.02)); border-radius:12px; border:1px solid rgba(99,102,241,0.12); }
  .cert-student .student-label { font-size:12px; text-transform:uppercase; letter-spacing:0.1em; color:#64748b; font-weight:600; }
  .cert-student .student-name { font-family:'Playfair Display',serif; font-size:38px; font-weight:700; color:#1e293b; margin-top:8px; }
  .cert-desc { text-align:center; color:#64748b; font-size:16px; line-height:1.6; margin:24px 0 36px; max-width:600px; margin-left:auto; margin-right:auto; }
  .cert-course { text-align:center; font-size:20px; font-weight:700; color:#6366f1; margin-bottom:8px; }
  .cert-footer { display:flex; justify-content:space-between; align-items:flex-end; margin-top:48px; padding-top:28px; border-top:1px solid #e2e8f0; }
  .cert-signatory { text-align:center; }
  .cert-signatory .sig-name { font-size:13px; font-weight:600; color:#1e293b; margin-top:4px; border-top:1px solid #94a3b8; padding-top:6px; }
  .cert-signatory .sig-title { font-size:11px; color:#94a3b8; }
  .cert-id { text-align:right; }
  .cert-id .uid { font-size:10px; color:#94a3b8; font-family:monospace; margin-bottom:4px; }
  .cert-id .verify { font-size:11px; color:#6366f1; text-decoration:none; }
  .cert-date { text-align:center; color:#64748b; font-size:14px; }
  @media print {
    body { background:white; padding:0; }
    .cert { box-shadow:none; border:1px solid #e2e8f0; }
    .no-print { display:none !important; }
  }
  .print-btn { text-align:center; margin-top:24px; }
  .print-btn button { background:linear-gradient(135deg,#6366f1,#8b5cf6); color:#fff; border:none; padding:12px 28px; border-radius:8px; font-size:15px; font-weight:600; cursor:pointer; }
</style>
</head>
<body>
<div class="cert-wrap">
  <div class="cert">
    <div class="cert-corner"></div>
    <div class="cert-header">
      {$logoHtml}
      <span class="cert-badge">Certificate of Completion</span>
    </div>
    <div class="cert-title">
      <div class="label">This certifies that</div>
    </div>
    <div class="cert-student">
      <div class="student-label">has successfully completed</div>
      <div class="student-name">{$name}</div>
    </div>
    <div class="cert-course">{$course}</div>
    <p class="cert-desc">
      Demonstrating proficiency in AI-powered marketing strategies, prompt engineering, market research,
      SEO, content creation, and the application of AI tools to build a complete marketing system.
    </p>
    <div class="cert-footer">
      <div class="cert-signatory">
        {$sigHtml}
        <div class="sig-name">{$signatory}</div>
        <div class="sig-title">TechAasvik Learning</div>
      </div>
      <div class="cert-date">
        <div style="font-size:12px;color:#94a3b8;margin-bottom:4px;">Date of Completion</div>
        <div style="font-size:16px;font-weight:700;color:#1e293b;">{$date}</div>
      </div>
      <div class="cert-id">
        <div class="uid">ID: {$uid}</div>
        <a href="{$verifyUrl}" class="verify" target="_blank">Verify Certificate ↗</a>
      </div>
    </div>
  </div>
  <div class="print-btn no-print">
    <button onclick="window.print()">🖨️ Download / Print Certificate</button>
    <a href="{$verifyUrl}" style="display:inline-block;margin-left:12px;color:#6366f1;font-size:14px;">Share Verify Link</a>
  </div>
</div>
</body>
</html>
HTML;
    }

    private function buildCertificateEmail(string $name, string $course, string $verifyUrl, string $uid): string
    {
        $nameHtml = htmlspecialchars($name);
        return <<<HTML
<div style="font-family:Inter,Arial,sans-serif;max-width:600px;margin:0 auto;background:#fff;border-radius:12px;overflow:hidden;">
  <div style="background:linear-gradient(135deg,#6366f1,#8b5cf6);padding:32px;text-align:center;">
    <div style="font-size:48px;">🎓</div>
    <h1 style="color:#fff;margin:16px 0 8px;font-size:24px;">Congratulations, {$nameHtml}!</h1>
    <p style="color:rgba(255,255,255,0.85);margin:0;">You've completed the <strong>{$course}</strong> course on TechAasvik.</p>
  </div>
  <div style="padding:32px;">
    <p style="color:#475569;line-height:1.6;">You've demonstrated real proficiency in AI-powered marketing — from prompt engineering to building a complete AI Marketing System. That's a genuinely valuable skill set.</p>
    <div style="text-align:center;margin:28px 0;">
      <a href="{$verifyUrl}" style="display:inline-block;background:linear-gradient(135deg,#6366f1,#8b5cf6);color:#fff;padding:14px 28px;border-radius:8px;text-decoration:none;font-weight:700;font-size:16px;">View &amp; Download Certificate</a>
    </div>
    <p style="color:#94a3b8;font-size:13px;text-align:center;">Certificate ID: <code>{$uid}</code><br>Verify at: {$verifyUrl}</p>
  </div>
  <div style="background:#f8fafc;padding:20px;text-align:center;font-size:12px;color:#94a3b8;">
    TechAasvik · Digital Marketing Excellence · <a href="https://techaasvik.com" style="color:#6366f1;">techaasvik.com</a>
  </div>
</div>
HTML;
    }
}
