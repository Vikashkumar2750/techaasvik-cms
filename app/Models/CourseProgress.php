<?php
namespace Models;
use Core\Model;

class CourseProgress extends Model
{
    protected string $table = 'course_progress';

    public function getForEnrollment(int $enrollmentId): array
    {
        $rows = $this->db->fetchAll(
            "SELECT * FROM course_progress WHERE enrollment_id=? ORDER BY module_number ASC",
            [$enrollmentId]
        );
        // Key by module_number
        $indexed = [];
        foreach ($rows as $r) {
            $indexed[(int)$r['module_number']] = $r;
        }
        return $indexed;
    }

    public function markComplete(int $enrollmentId, int $moduleNum): void
    {
        $this->db->execute(
            "INSERT INTO course_progress (enrollment_id, module_number, completed, completed_at)
             VALUES (?,?,1,NOW())
             ON DUPLICATE KEY UPDATE completed=1, completed_at=COALESCE(completed_at, NOW())",
            [$enrollmentId, $moduleNum]
        );
    }

    public function saveQuizResult(int $enrollmentId, int $moduleNum, int $score, bool $passed): void
    {
        $this->db->execute(
            "INSERT INTO course_progress (enrollment_id, module_number, quiz_score, quiz_passed, completed, completed_at)
             VALUES (?,?,?,?,?,NOW())
             ON DUPLICATE KEY UPDATE quiz_score=?, quiz_passed=?,
               completed = IF(? >= 60, 1, completed),
               completed_at = COALESCE(completed_at, IF(? >= 60, NOW(), NULL))",
            [
                $enrollmentId, $moduleNum, $score, $passed ? 1 : 0, $passed ? 1 : 0, $score,
                $passed ? 1 : 0, $score, $score,
            ]
        );
    }

    public function countCompleted(int $enrollmentId): int
    {
        return (int)$this->db->fetchColumn(
            "SELECT COUNT(*) FROM course_progress WHERE enrollment_id=? AND completed=1",
            [$enrollmentId]
        );
    }

    public function saveQuizAttempt(int $enrollmentId, int $moduleNum, array $answers, int $score, bool $passed): void
    {
        $this->db->execute(
            "INSERT INTO course_quiz_attempts (enrollment_id, module_number, answers, score, passed, attempted_at)
             VALUES (?,?,?,?,?,NOW())",
            [$enrollmentId, $moduleNum, json_encode($answers), $score, $passed ? 1 : 0]
        );
    }
}
