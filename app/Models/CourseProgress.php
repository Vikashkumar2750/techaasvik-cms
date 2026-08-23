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

    public function markComplete(int $enrollmentId, int $moduleNum, int $score = 0, bool $passed = true): void
    {
        $this->db->execute(
            "INSERT INTO course_progress (enrollment_id, module_number, quiz_score, quiz_passed, completed, completed_at)
             VALUES (?,?,?,?,1,NOW())
             ON DUPLICATE KEY UPDATE completed=1, completed_at=COALESCE(completed_at,NOW()),
               quiz_score=IF(?=0, quiz_score, ?), quiz_passed=IF(?=0, quiz_passed, ?)",
            [$enrollmentId, $moduleNum, $score ?: null, $passed ? 1 : 0,
             $score, $score, $score, $passed ? 1 : 0]
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

    /** Mark a submodule complete */
    public function markSubmoduleComplete(int $enrollmentId, string $subKey): void
    {
        $this->db->execute(
            "INSERT INTO course_submodule_progress (enrollment_id, module_number, submodule_key, completed, completed_at)
             VALUES (?,?,?,1,NOW())
             ON DUPLICATE KEY UPDATE completed=1, completed_at=COALESCE(completed_at,NOW())",
            [$enrollmentId, (int)explode('-', $subKey)[0], $subKey]
        );
    }

    /** Get completed submodule keys for an enrollment */
    public function getCompletedSubmodules(int $enrollmentId): array
    {
        $rows = $this->db->fetchAll(
            "SELECT submodule_key FROM course_submodule_progress WHERE enrollment_id=? AND completed=1",
            [$enrollmentId]
        );
        return array_column($rows, 'submodule_key');
    }

    /** Calculate overall grade (avg of quiz scores, 0-100) */
    public function calculateOverallScore(int $enrollmentId): float
    {
        $score = $this->db->fetchColumn(
            "SELECT AVG(quiz_score) FROM course_progress
             WHERE enrollment_id=? AND quiz_passed=1 AND quiz_score IS NOT NULL",
            [$enrollmentId]
        );
        return round((float)$score, 1);
    }

    /** Convert score to letter grade */
    public static function scoreToGrade(float $score): string
    {
        if ($score >= 85) return 'A';
        if ($score >= 70) return 'B';
        if ($score >= 60) return 'C';
        return 'Pass';
    }

    /** Grade label with distinction */
    public static function gradeLabel(string $grade): string
    {
        return match($grade) {
            'A'    => 'A — Distinction',
            'B'    => 'B — Merit',
            'C'    => 'C — Pass',
            default=> 'Pass',
        };
    }
}
