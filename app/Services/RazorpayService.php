<?php
namespace Services;

/**
 * RazorpayService
 * Handles order creation and payment verification.
 * Keys come from .env — NEVER from frontend.
 */
class RazorpayService
{
    private string $keyId;
    private string $keySecret;
    private string $webhookSecret;
    private const API_BASE = 'https://api.razorpay.com/v1/';

    public function __construct()
    {
        $this->keyId        = env('RAZORPAY_KEY_ID', '');
        $this->keySecret    = env('RAZORPAY_KEY_SECRET', '');
        $this->webhookSecret = env('RAZORPAY_WEBHOOK_SECRET', '');

        if (empty($this->keyId) || empty($this->keySecret)) {
            throw new \RuntimeException('Razorpay keys not configured. Check .env file.');
        }
    }

    /**
     * Create a Razorpay order.
     * @param float  $amount  Final amount in INR (after discount)
     * @param string $receipt Unique receipt ID (enrollment ID)
     * @param array  $notes   Optional metadata
     * @return array Razorpay order object
     */
    public function createOrder(float $amount, string $receipt, array $notes = []): array
    {
        $payload = [
            'amount'   => (int)round($amount * 100), // Convert to paise
            'currency' => 'INR',
            'receipt'  => $receipt,
            'notes'    => $notes,
        ];

        $response = $this->request('POST', 'orders', $payload);

        if (!isset($response['id'])) {
            throw new \RuntimeException('Failed to create Razorpay order: ' . json_encode($response));
        }

        return $response;
    }

    /**
     * Verify payment signature (HMAC-SHA256).
     * Call BEFORE unlocking course access.
     */
    public function verifyPaymentSignature(
        string $orderId,
        string $paymentId,
        string $signature
    ): bool {
        $payload  = $orderId . '|' . $paymentId;
        $expected = hash_hmac('sha256', $payload, $this->keySecret);
        return hash_equals($expected, $signature);
    }

    /**
     * Verify Razorpay webhook signature.
     * Call from webhook endpoint.
     */
    public function verifyWebhookSignature(string $rawBody, string $headerSignature): bool
    {
        if (empty($this->webhookSecret)) {
            return false;
        }
        $expected = hash_hmac('sha256', $rawBody, $this->webhookSecret);
        return hash_equals($expected, $headerSignature);
    }

    /**
     * Return only the key_id for frontend use (safe to expose).
     */
    public function getKeyId(): string
    {
        return $this->keyId;
    }

    /**
     * Fetch payment details from Razorpay API (for webhook verification fallback).
     */
    public function fetchPayment(string $paymentId): array
    {
        return $this->request('GET', "payments/{$paymentId}");
    }

    // ── Internal HTTP ──────────────────────────────────────────
    private function request(string $method, string $endpoint, array $payload = []): array
    {
        $url = self::API_BASE . $endpoint;
        $ch  = curl_init($url);

        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_HTTPHEADER     => ['Content-Type: application/json', 'Accept: application/json'],
            CURLOPT_USERPWD        => $this->keyId . ':' . $this->keySecret,
            CURLOPT_SSL_VERIFYPEER => true,
        ]);

        if ($method === 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        }

        $body  = curl_exec($ch);
        $errno = curl_errno($ch);
        curl_close($ch);

        if ($errno) {
            throw new \RuntimeException("Razorpay API cURL error #{$errno}");
        }

        return json_decode($body, true) ?? [];
    }
}
