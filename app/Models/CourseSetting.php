<?php
namespace Models;
use Core\Model;

class CourseSetting extends Model
{
    protected string $table = 'course_settings';
    private static array $cache = [];

    public static function clearCache(): void
    {
        self::$cache = [];
    }

    public function get(string $key, mixed $default = null): mixed
    {
        if (isset(self::$cache[$key])) {
            return self::$cache[$key];
        }
        $row = $this->db->fetchOne(
            "SELECT setting_value FROM course_settings WHERE setting_key=? LIMIT 1",
            [$key]
        );
        $value = $row ? $row['setting_value'] : $default;
        self::$cache[$key] = $value;
        return $value;
    }

    public function set(string $key, mixed $value): void
    {
        $this->db->execute(
            "INSERT INTO course_settings (setting_key, setting_value)
             VALUES (?,?)
             ON DUPLICATE KEY UPDATE setting_value=?",
            [$key, $value, $value]
        );
        self::$cache[$key] = $value;
    }

    public function setMany(array $pairs): void
    {
        foreach ($pairs as $key => $value) {
            $this->set($key, $value);
        }
    }

    public function all(string $orderBy = 'id', string $dir = 'DESC'): array
    {
        $rows = $this->db->fetchAll("SELECT setting_key, setting_value FROM course_settings");
        $result = [];
        foreach ($rows as $r) {
            $result[$r['setting_key']] = $r['setting_value'];
            self::$cache[$r['setting_key']] = $r['setting_value'];
        }
        return $result;
    }

    /** Get Razorpay key_id — prefer .env over DB */
    public function razorpayKeyId(): string
    {
        return env('RAZORPAY_KEY_ID', $this->get('razorpay_key_id', ''));
    }

    /** Get Razorpay key_secret — NEVER expose to frontend. .env only. */
    public function razorpayKeySecret(): string
    {
        return env('RAZORPAY_KEY_SECRET', '');
    }

    /** Get Razorpay webhook secret */
    public function razorpayWebhookSecret(): string
    {
        return env('RAZORPAY_WEBHOOK_SECRET', '');
    }

    public function priceOriginal(): float
    {
        return (float)$this->get('course_price_original', 999);
    }

    public function priceSale(): float
    {
        return (float)$this->get('course_price_sale', 199);
    }

    public function freeModulesCount(): int
    {
        return (int)$this->get('free_modules_count', 5);
    }

    public function videoEnabled(): bool
    {
        return (bool)(int)$this->get('video_enabled', 0);
    }

    /** Is the Courses section enabled site-wide? (Super admin toggle) */
    public function coursesEnabled(): bool
    {
        return (bool)(int)$this->get('courses_enabled', 1); // default ON
    }
}
