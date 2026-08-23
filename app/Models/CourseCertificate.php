<?php
namespace Models;
use Core\Model;

class CourseCertificate extends Model
{
    protected string $table = 'course_certificates';

    public function findByEnrollment(int $enrollmentId): ?array
    {
        return $this->db->fetchOne(
            "SELECT * FROM course_certificates WHERE enrollment_id=? LIMIT 1",
            [$enrollmentId]
        );
    }

    public function findByUid(string $uid): ?array
    {
        return $this->db->fetchOne(
            "SELECT cc.*, ce.user_name, ce.user_email, ce.course_slug, ce.completed_at
             FROM course_certificates cc
             JOIN course_enrollments ce ON ce.id = cc.enrollment_id
             WHERE cc.cert_uid=? LIMIT 1",
            [$uid]
        );
    }

    public function create(int $enrollmentId): string
    {
        $uid = md5(uniqid($enrollmentId . time(), true));
        $this->db->execute(
            "INSERT IGNORE INTO course_certificates (enrollment_id, cert_uid) VALUES (?,?)",
            [$enrollmentId, $uid]
        );
        return $uid;
    }

    public function markEmailSent(int $enrollmentId): void
    {
        $this->db->execute(
            "UPDATE course_certificates SET email_sent=1, email_sent_at=NOW() WHERE enrollment_id=?",
            [$enrollmentId]
        );
    }

    public function incrementDownload(string $uid): void
    {
        $this->db->execute(
            "UPDATE course_certificates SET download_count=download_count+1 WHERE cert_uid=?",
            [$uid]
        );
    }
}
