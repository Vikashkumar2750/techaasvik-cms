<?php
namespace Services;

/**
 * Email Service — sends transactional emails via SMTP (Brevo/Hostinger) or PHP mail().
 * Uses PHP's native sockets for SMTP — no Composer dependency needed.
 */
class EmailService
{
    private array $config;

    public function __construct()
    {
        $appConfig    = require APP_PATH . '/Config/config.php';
        $this->config = $appConfig['mail'] ?? [];
    }

    /**
     * Send an email. Returns true on success, false on failure.
     */
    public function send(string $to, string $subject, string $htmlBody, string $fromName = '', string $fromEmail = ''): bool
    {
        $fromName  = $fromName  ?: ($this->config['from_name']  ?? 'TechAasvik');
        $fromEmail = $fromEmail ?: ($this->config['from_email'] ?? 'noreply@techaasvik.com');

        // Try SMTP first, fall back to mail()
        if (!empty($this->config['host']) && $this->config['host'] !== 'BREVO_USERNAME') {
            return $this->sendSmtp($to, $subject, $htmlBody, $fromName, $fromEmail);
        }

        return $this->sendMail($to, $subject, $htmlBody, $fromName, $fromEmail);
    }

    /**
     * Notify admin of a new lead submission.
     */
    public function notifyLead(array $lead): bool
    {
        $adminEmail = $this->config['from_email'] ?? 'hello@techaasvik.com';
        $type       = ucfirst($lead['lead_type'] ?? 'unknown');
        $subject    = "New {$type} Lead — TechAasvik";

        $html = "<div style='font-family:sans-serif;max-width:600px;margin:0 auto;'>";
        $html .= "<h2 style='color:#6366f1;'>New {$type} Lead 🚀</h2>";
        $html .= "<table style='width:100%;border-collapse:collapse;'>";
        foreach (['name', 'email', 'phone', 'website', 'message', 'source_page', 'lead_type'] as $key) {
            if (!empty($lead[$key])) {
                $label = ucfirst(str_replace('_', ' ', $key));
                $val   = htmlspecialchars($lead[$key]);
                $html .= "<tr><td style='padding:8px;border-bottom:1px solid #eee;font-weight:600;width:120px;'>{$label}</td>";
                $html .= "<td style='padding:8px;border-bottom:1px solid #eee;'>{$val}</td></tr>";
            }
        }
        $html .= "</table>";
        $html .= "<p style='margin-top:16px;font-size:12px;color:#999;'>Sent by TechAasvik CMS · " . date('Y-m-d H:i:s') . "</p>";
        $html .= "</div>";

        return $this->send($adminEmail, $subject, $html);
    }

    /**
     * Send via PHP mail() — fallback.
     */
    private function sendMail(string $to, string $subject, string $body, string $fromName, string $fromEmail): bool
    {
        $boundary = md5(time());
        $headers  = [];
        $headers[] = "From: {$fromName} <{$fromEmail}>";
        $headers[] = "Reply-To: {$fromEmail}";
        $headers[] = "MIME-Version: 1.0";
        $headers[] = "Content-Type: text/html; charset=UTF-8";
        $headers[] = "X-Mailer: TechAasvik-CMS/1.0";

        return @mail($to, $subject, $body, implode("\r\n", $headers));
    }

    /**
     * Send via raw SMTP socket — works on shared hosting without Composer.
     */
    private function sendSmtp(string $to, string $subject, string $body, string $fromName, string $fromEmail): bool
    {
        $host     = $this->config['host'];
        $port     = (int)($this->config['port'] ?? 587);
        $user     = $this->config['username'] ?? '';
        $pass     = $this->config['password'] ?? '';
        $encrypt  = $this->config['encryption'] ?? 'tls';

        try {
            $socket = @fsockopen(
                $encrypt === 'ssl' ? "ssl://{$host}" : $host,
                $port,
                $errno,
                $errstr,
                10
            );

            if (!$socket) {
                error_log("SMTP connection failed: {$errstr} ({$errno})");
                return $this->sendMail($to, $subject, $body, $fromName, $fromEmail);
            }

            $this->smtpRead($socket);
            $this->smtpCommand($socket, "EHLO " . gethostname());

            if ($encrypt === 'tls') {
                $this->smtpCommand($socket, "STARTTLS");
                stream_socket_enable_crypto($socket, true, STREAM_CRYPTO_METHOD_TLS_CLIENT);
                $this->smtpCommand($socket, "EHLO " . gethostname());
            }

            // AUTH LOGIN
            $this->smtpCommand($socket, "AUTH LOGIN");
            $this->smtpCommand($socket, base64_encode($user));
            $this->smtpCommand($socket, base64_encode($pass));

            $this->smtpCommand($socket, "MAIL FROM:<{$fromEmail}>");
            $this->smtpCommand($socket, "RCPT TO:<{$to}>");
            $this->smtpCommand($socket, "DATA");

            $message  = "From: {$fromName} <{$fromEmail}>\r\n";
            $message .= "To: {$to}\r\n";
            $message .= "Subject: {$subject}\r\n";
            $message .= "MIME-Version: 1.0\r\n";
            $message .= "Content-Type: text/html; charset=UTF-8\r\n";
            $message .= "X-Mailer: TechAasvik-CMS/1.0\r\n";
            $message .= "\r\n";
            $message .= $body;
            $message .= "\r\n.\r\n";

            fwrite($socket, $message);
            $this->smtpRead($socket);

            $this->smtpCommand($socket, "QUIT");
            fclose($socket);

            return true;
        } catch (\Throwable $e) {
            error_log("SMTP error: " . $e->getMessage());
            return $this->sendMail($to, $subject, $body, $fromName, $fromEmail);
        }
    }

    private function smtpCommand($socket, string $command): string
    {
        fwrite($socket, $command . "\r\n");
        return $this->smtpRead($socket);
    }

    private function smtpRead($socket): string
    {
        $response = '';
        while ($line = fgets($socket, 512)) {
            $response .= $line;
            if (substr($line, 3, 1) === ' ') break;
        }
        return $response;
    }
}
