-- ============================================================
-- Migration 009: Course Student Auth + Courses Enable Toggle
-- Run ONCE on live database
-- ============================================================

-- 1. Add auth columns to course_enrollments
ALTER TABLE `course_enrollments`
  ADD COLUMN IF NOT EXISTS `email_verified`          TINYINT(1)   NOT NULL DEFAULT 0            AFTER `token`,
  ADD COLUMN IF NOT EXISTS `verify_token`            VARCHAR(128) NULL DEFAULT NULL             AFTER `email_verified`,
  ADD COLUMN IF NOT EXISTS `verify_expires`          DATETIME     NULL DEFAULT NULL             AFTER `verify_token`,
  ADD COLUMN IF NOT EXISTS `password_hash`           VARCHAR(255) NULL DEFAULT NULL             AFTER `verify_expires`,
  ADD COLUMN IF NOT EXISTS `password_reset_token`    VARCHAR(128) NULL DEFAULT NULL             AFTER `password_hash`,
  ADD COLUMN IF NOT EXISTS `password_reset_expires`  DATETIME     NULL DEFAULT NULL             AFTER `password_reset_token`;

-- Index for fast token lookup
ALTER TABLE `course_enrollments`
  ADD INDEX IF NOT EXISTS `idx_verify_token`   (`verify_token`(64)),
  ADD INDEX IF NOT EXISTS `idx_reset_token`    (`password_reset_token`(64));

-- 2. Add courses_enabled setting (default ON = 1)
INSERT INTO `course_settings` (`setting_key`, `setting_value`)
VALUES ('courses_enabled', '1')
ON DUPLICATE KEY UPDATE `setting_value` = `setting_value`;

-- 3. Mark existing enrollments as verified (they enrolled before auth existed)
UPDATE `course_enrollments`
SET `email_verified` = 1
WHERE `email_verified` = 0;
