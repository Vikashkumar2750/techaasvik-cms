<?php
namespace Models;
use Core\Model;

class CourseCoupon extends Model
{
    protected string $table = 'course_coupons';

    public function findActive(string $code): ?array
    {
        return $this->db->fetchOne(
            "SELECT * FROM course_coupons
             WHERE code = ? AND is_active = 1
               AND (valid_from IS NULL OR valid_from <= NOW())
               AND (valid_until IS NULL OR valid_until >= NOW())
               AND (max_uses IS NULL OR uses_count < max_uses)
             LIMIT 1",
            [strtoupper(trim($code))]
        );
    }

    public function incrementUse(int $id): void
    {
        $this->db->execute(
            "UPDATE course_coupons SET uses_count = uses_count + 1 WHERE id = ?",
            [$id]
        );
    }

    /** Calculate discounted amount. Returns final amount (not discount). */
    public function applyDiscount(array $coupon, float $originalAmount): float
    {
        if ($coupon['discount_type'] === 'percent') {
            $discount = $originalAmount * ($coupon['discount_value'] / 100);
        } else {
            $discount = min((float)$coupon['discount_value'], $originalAmount);
        }
        return max(0, round($originalAmount - $discount, 2));
    }

    public function getDiscountAmount(array $coupon, float $originalAmount): float
    {
        return round($originalAmount - $this->applyDiscount($coupon, $originalAmount), 2);
    }

    public function listAll(): array
    {
        return $this->db->fetchAll(
            "SELECT * FROM course_coupons ORDER BY created_at DESC"
        );
    }

    public function create(array $data): int
    {
        $this->db->execute(
            "INSERT INTO course_coupons
             (code, description, discount_type, discount_value, max_uses, min_amount, valid_from, valid_until, is_active)
             VALUES (?,?,?,?,?,?,?,?,1)",
            [
                strtoupper(trim($data['code'])),
                $data['description'] ?? null,
                $data['discount_type'] ?? 'percent',
                (float)$data['discount_value'],
                !empty($data['max_uses']) ? (int)$data['max_uses'] : null,
                (float)($data['min_amount'] ?? 0),
                !empty($data['valid_from']) ? $data['valid_from'] : null,
                !empty($data['valid_until']) ? $data['valid_until'] : null,
            ]
        );
        return (int)$this->db->lastInsertId();
    }

    public function deactivate(int $id): void
    {
        $this->db->execute("UPDATE course_coupons SET is_active=0 WHERE id=?", [$id]);
    }
}
