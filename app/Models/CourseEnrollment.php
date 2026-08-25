<?php
namespace Models;
use Core\Model;

class CourseEnrollment extends Model
{
    protected string $table = 'course_enrollments';

    public function findByToken(string $token): ?array
    {
        return $this->db->fetchOne(
            "SELECT * FROM course_enrollments WHERE token = ? LIMIT 1", [$token]
        );
    }

    public function findByEmail(string $email, string $courseSlug): ?array
    {
        return $this->db->fetchOne(
            "SELECT * FROM course_enrollments WHERE user_email = ? AND course_slug = ? LIMIT 1",
            [$email, $courseSlug]
        );
    }

    public function findById(int $id): ?array
    {
        return $this->db->fetchOne(
            "SELECT * FROM course_enrollments WHERE id = ? LIMIT 1", [$id]
        );
    }

    public function create(array $data): int
    {
        $this->db->execute(
            "INSERT INTO course_enrollments
             (course_slug, user_name, user_email, user_phone, payment_status,
              token, email_verified, verify_token, verify_expires, ip_address, enrolled_at)
             VALUES (?,?,?,?,?,?,?,?,?,?,NOW())",
            [
                $data['course_slug'], $data['user_name'], $data['user_email'],
                $data['user_phone'], $data['payment_status'] ?? 'free',
                $data['token'],
                $data['email_verified'] ?? 1,
                $data['verify_token'] ?? null,
                $data['verify_expires'] ?? null,
                $data['ip_address'] ?? null,
            ]
        );
        return (int)$this->db->lastInsertId();
    }

    public function updatePayment(int $id, array $data): void
    {
        $this->db->execute(
            "UPDATE course_enrollments
             SET payment_status=?, razorpay_order_id=?, razorpay_payment_id=?,
                 razorpay_signature=?, amount_paid=?, coupon_code=?, discount_amount=?
             WHERE id=?",
            [
                $data['payment_status'], $data['razorpay_order_id'],
                $data['razorpay_payment_id'], $data['razorpay_signature'],
                $data['amount_paid'], $data['coupon_code'] ?? null,
                $data['discount_amount'] ?? 0, $id,
            ]
        );
    }

    public function markCompleted(int $id): void
    {
        $this->db->execute(
            "UPDATE course_enrollments SET completed_at=NOW() WHERE id=? AND completed_at IS NULL",
            [$id]
        );
    }

    public function countAll(): int
    {
        return (int)$this->db->fetchColumn("SELECT COUNT(*) FROM course_enrollments");
    }

    public function countPaid(): int
    {
        return (int)$this->db->fetchColumn(
            "SELECT COUNT(*) FROM course_enrollments WHERE payment_status='paid'"
        );
    }

    public function totalRevenue(): float
    {
        return (float)$this->db->fetchColumn(
            "SELECT COALESCE(SUM(amount_paid),0) FROM course_enrollments WHERE payment_status='paid'"
        );
    }

    public function listRecent(int $limit = 50, int $offset = 0): array
    {
        return $this->db->fetchAll(
            "SELECT * FROM course_enrollments ORDER BY enrolled_at DESC LIMIT ? OFFSET ?",
            [$limit, $offset]
        );
    }

    /** Store Razorpay order ID before payment */
    public function setOrderId(int $id, string $orderId): void
    {
        $this->db->execute(
            "UPDATE course_enrollments SET razorpay_order_id=?, payment_status='pending' WHERE id=?",
            [$orderId, $id]
        );
    }
}
