<?php
namespace Services;

/**
 * MailService — Multi-provider SMTP mailer
 * Supports: Hostinger, Gmail, Brevo, Custom SMTP
 * Config comes from DB (course_settings) or .env
 */
class MailService
{
    private array $config;

    public function __construct(array $config = [])
    {
        $this->config = $config ?: $this->loadFromDb();
    }

    private function loadFromDb(): array
    {
        try {
            $setting = new \Models\CourseSetting();
            return [
                'host'       => $setting->get('smtp_host', 'smtp.hostinger.com'),
                'port'       => (int)$setting->get('smtp_port', 587),
                'encryption' => $setting->get('smtp_encryption', 'tls'),
                'user'       => $setting->get('smtp_user', ''),
                'pass'       => $setting->get('smtp_pass', ''),
                'from_name'  => $setting->get('smtp_from_name', 'TechAasvik'),
                'from_email' => $setting->get('smtp_from_email', ''),
            ];
        } catch (\Exception) {
            return [];
        }
    }

    /**
     * Send an email.
     * Returns true on success, false on failure.
     */
    public function send(
        string $toEmail,
        string $toName,
        string $subject,
        string $htmlBody,
        string $textBody = ''
    ): bool {
        if (empty($this->config['user']) || empty($this->config['pass'])) {
            error_log("MailService: SMTP not configured");
            return false;
        }

        $fromEmail = $this->config['from_email'] ?: $this->config['user'];
        $fromName  = $this->config['from_name'] ?: 'TechAasvik';
        $boundary  = bin2hex(random_bytes(16));

        // Build raw MIME message
        $textBody = $textBody ?: strip_tags($htmlBody);

        $headers  = "From: {$fromName} <{$fromEmail}>\r\n";
        $headers .= "To: {$toName} <{$toEmail}>\r\n";
        $headers .= "Subject: =?UTF-8?B?" . base64_encode($subject) . "?=\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: multipart/alternative; boundary=\"{$boundary}\"\r\n";
        $headers .= "X-Mailer: TechAasvik-Mailer/1.0\r\n";

        $body  = "--{$boundary}\r\n";
        $body .= "Content-Type: text/plain; charset=UTF-8\r\n\r\n";
        $body .= $textBody . "\r\n\r\n";
        $body .= "--{$boundary}\r\n";
        $body .= "Content-Type: text/html; charset=UTF-8\r\n\r\n";
        $body .= $htmlBody . "\r\n\r\n";
        $body .= "--{$boundary}--";

        return $this->sendViaSMTP($toEmail, $subject, $headers, $body);
    }

    private function sendViaSMTP(string $to, string $subject, string $headers, string $body): bool
    {
        $host = $this->config['host'];
        $port = $this->config['port'];
        $enc  = $this->config['encryption'];

        $prefix = match($enc) {
            'ssl'   => 'ssl://',
            default => '',
        };

        $socket = @fsockopen($prefix . $host, $port, $errno, $errstr, 10);
        if (!$socket) {
            throw new \RuntimeException("Cannot connect to {$host}:{$port} — {$errstr} (errno:{$errno})");
        }

        try {
            $this->readResponse($socket); // 220

            $this->sendCmd($socket, "EHLO " . ($_SERVER['SERVER_NAME'] ?? 'localhost'));
            $this->readResponse($socket);

            if ($enc === 'tls') {
                $this->sendCmd($socket, "STARTTLS");
                $this->readResponse($socket);
                stream_socket_enable_crypto($socket, true, STREAM_CRYPTO_METHOD_TLS_CLIENT);

                $this->sendCmd($socket, "EHLO " . ($_SERVER['SERVER_NAME'] ?? 'localhost'));
                $this->readResponse($socket);
            }

            // AUTH LOGIN
            $this->sendCmd($socket, "AUTH LOGIN");
            $this->readResponse($socket);
            $this->sendCmd($socket, base64_encode($this->config['user']));
            $this->readResponse($socket);
            $this->sendCmd($socket, base64_encode($this->config['pass']));
            $r = $this->readResponse($socket);

            if (!str_starts_with($r, '235')) {
                throw new \RuntimeException("AUTH failed — " . trim($r));
            }

            $fromEmail = $this->config['from_email'] ?: $this->config['user'];
            $this->sendCmd($socket, "MAIL FROM:<{$fromEmail}>");
            $this->readResponse($socket);

            $this->sendCmd($socket, "RCPT TO:<{$to}>");
            $this->readResponse($socket);

            $this->sendCmd($socket, "DATA");
            $this->readResponse($socket);

            fwrite($socket, $headers . "\r\n" . $body . "\r\n.\r\n");
            $r = $this->readResponse($socket);

            $this->sendCmd($socket, "QUIT");
            fclose($socket);

            return str_starts_with($r, '250');
        } catch (\RuntimeException $e) {
            @fclose($socket);
            throw $e; // Re-throw so caller sees real error
        } catch (\Exception $e) {
            error_log("MailService: " . $e->getMessage());
            @fclose($socket);
            throw new \RuntimeException($e->getMessage());
        }
    }

    private function sendCmd($socket, string $cmd): void
    {
        fwrite($socket, $cmd . "\r\n");
    }

    private function readResponse($socket): string
    {
        $response = '';
        while ($line = fgets($socket, 512)) {
            $response .= $line;
            if (substr($line, 3, 1) === ' ') break; // Last line of multi-line response
        }
        return $response;
    }

    /** Send test email — used by admin to verify SMTP config */
    public function sendTest(string $toEmail): bool
    {
        return $this->send(
            $toEmail,
            'Admin',
            '✅ TechAasvik SMTP Test',
            '<h2>SMTP is working!</h2><p>Your email configuration on TechAasvik is correctly set up.</p>',
        );
    }

    /** SMTP provider presets */
    public static function providerPresets(): array
    {
        return [
            'hostinger' => ['host' => 'smtp.hostinger.com', 'port' => 587, 'encryption' => 'tls'],
            'gmail'     => ['host' => 'smtp.gmail.com',     'port' => 587, 'encryption' => 'tls'],
            'brevo'     => ['host' => 'smtp-relay.brevo.com','port'=> 587, 'encryption' => 'tls'],
            'outlook'   => ['host' => 'smtp.office365.com', 'port' => 587, 'encryption' => 'tls'],
            'custom'    => ['host' => '',                   'port' => 587, 'encryption' => 'tls'],
        ];
    }
}
