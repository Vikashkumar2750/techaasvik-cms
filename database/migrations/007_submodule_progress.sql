-- ============================================================
-- TECHAASVIK — Migration 007: Submodule Progress + New Settings
-- Run once on live DB via phpMyAdmin or CLI
-- ============================================================

-- ── Submodule Progress tracking ──────────────────────────────
CREATE TABLE IF NOT EXISTS `course_submodule_progress` (
  `id`              INT UNSIGNED    NOT NULL AUTO_INCREMENT,
  `enrollment_id`   INT UNSIGNED    NOT NULL,
  `module_number`   TINYINT UNSIGNED NOT NULL COMMENT '1-10',
  `submodule_key`   VARCHAR(30)     NOT NULL COMMENT 'e.g. 1-1, 1-2, 1-quiz',
  `completed`       TINYINT(1)      NOT NULL DEFAULT 0,
  `completed_at`    DATETIME        NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_enroll_sub`    (`enrollment_id`, `submodule_key`),
  KEY `idx_enrollment`          (`enrollment_id`),
  CONSTRAINT `fk_subprog_enroll`
    FOREIGN KEY (`enrollment_id`) REFERENCES `course_enrollments` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ── Quiz Attempts table — add submodule_key column if missing ─
-- (safe to ignore error if column already exists)
ALTER TABLE `course_quiz_attempts`
  ADD COLUMN IF NOT EXISTS `submodule_key` VARCHAR(30) NULL AFTER `module_number`;

-- ── New course_settings entries ───────────────────────────────
INSERT IGNORE INTO `course_settings` (`setting_key`, `setting_value`) VALUES
  ('processing_fee_pct', '1.5'),
  ('course_grade_a_min',  '85'),
  ('course_grade_b_min',  '70'),
  ('course_grade_c_min',  '60');
