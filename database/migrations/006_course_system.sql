-- ============================================================
-- TECHAASVIK — Migration 006: Course System
-- Run once on live DB via phpMyAdmin or CLI
-- ============================================================

SET FOREIGN_KEY_CHECKS = 0;

-- ── Course Enrollments ───────────────────────────────────────
CREATE TABLE IF NOT EXISTS `course_enrollments` (
  `id`                  INT UNSIGNED    NOT NULL AUTO_INCREMENT,
  `course_slug`         VARCHAR(200)    NOT NULL,
  `user_name`           VARCHAR(255)    NOT NULL,
  `user_email`          VARCHAR(255)    NOT NULL,
  `user_phone`          VARCHAR(20)     NOT NULL,
  `payment_status`      ENUM('free','pending','paid') NOT NULL DEFAULT 'free',
  `razorpay_order_id`   VARCHAR(100)    NULL,
  `razorpay_payment_id` VARCHAR(100)    NULL,
  `razorpay_signature`  VARCHAR(300)    NULL,
  `amount_paid`         DECIMAL(10,2)   NOT NULL DEFAULT 0.00,
  `coupon_code`         VARCHAR(50)     NULL,
  `discount_amount`     DECIMAL(10,2)   NOT NULL DEFAULT 0.00,
  `token`               VARCHAR(64)     NOT NULL COMMENT 'Session token stored in cookie (HttpOnly)',
  `ip_address`          VARCHAR(45)     NULL,
  `enrolled_at`         DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `completed_at`        DATETIME        NULL,
  `updated_at`          DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_token`          (`token`),
  KEY `idx_email`                (`user_email`),
  KEY `idx_course`               (`course_slug`),
  KEY `idx_payment_status`       (`payment_status`),
  KEY `idx_razorpay_order`       (`razorpay_order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ── Course Progress (per-module completion) ──────────────────
CREATE TABLE IF NOT EXISTS `course_progress` (
  `id`              INT UNSIGNED    NOT NULL AUTO_INCREMENT,
  `enrollment_id`   INT UNSIGNED    NOT NULL,
  `module_number`   TINYINT UNSIGNED NOT NULL COMMENT '1-10',
  `completed`       TINYINT(1)      NOT NULL DEFAULT 0,
  `quiz_score`      TINYINT UNSIGNED NULL     COMMENT '0-100',
  `quiz_passed`     TINYINT(1)      NOT NULL DEFAULT 0,
  `completed_at`    DATETIME        NULL,
  `updated_at`      DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_enrollment_module` (`enrollment_id`, `module_number`),
  CONSTRAINT `fk_progress_enrollment`
    FOREIGN KEY (`enrollment_id`) REFERENCES `course_enrollments` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ── Quiz Attempts ────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `course_quiz_attempts` (
  `id`              INT UNSIGNED    NOT NULL AUTO_INCREMENT,
  `enrollment_id`   INT UNSIGNED    NOT NULL,
  `module_number`   TINYINT UNSIGNED NOT NULL,
  `answers`         JSON            NOT NULL COMMENT '{"q1":"b","q2":"a",...}',
  `score`           TINYINT UNSIGNED NOT NULL DEFAULT 0 COMMENT '0-100',
  `passed`          TINYINT(1)      NOT NULL DEFAULT 0 COMMENT 'Score >= 60',
  `attempted_at`    DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_enrollment` (`enrollment_id`),
  CONSTRAINT `fk_quiz_enrollment`
    FOREIGN KEY (`enrollment_id`) REFERENCES `course_enrollments` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ── Certificates ─────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `course_certificates` (
  `id`              INT UNSIGNED    NOT NULL AUTO_INCREMENT,
  `enrollment_id`   INT UNSIGNED    NOT NULL,
  `cert_uid`        CHAR(32)        NOT NULL COMMENT 'MD5 unique ID for public verify URL',
  `email_sent`      TINYINT(1)      NOT NULL DEFAULT 0,
  `email_sent_at`   DATETIME        NULL,
  `download_count`  INT             NOT NULL DEFAULT 0,
  `issued_at`       DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_enrollment`  (`enrollment_id`),
  UNIQUE KEY `uk_cert_uid`    (`cert_uid`),
  CONSTRAINT `fk_cert_enrollment`
    FOREIGN KEY (`enrollment_id`) REFERENCES `course_enrollments` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ── Discount Coupons ─────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `course_coupons` (
  `id`              INT UNSIGNED    NOT NULL AUTO_INCREMENT,
  `code`            VARCHAR(50)     NOT NULL,
  `description`     VARCHAR(255)    NULL,
  `discount_type`   ENUM('percent','flat') NOT NULL DEFAULT 'percent',
  `discount_value`  DECIMAL(10,2)   NOT NULL,
  `max_uses`        INT             NULL     COMMENT 'NULL = unlimited',
  `uses_count`      INT             NOT NULL DEFAULT 0,
  `min_amount`      DECIMAL(10,2)   NOT NULL DEFAULT 0.00,
  `valid_from`      DATETIME        NULL,
  `valid_until`     DATETIME        NULL,
  `is_active`       TINYINT(1)      NOT NULL DEFAULT 1,
  `created_at`      DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`      DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_code` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ── Course Settings (admin-managed key-value) ────────────────
CREATE TABLE IF NOT EXISTS `course_settings` (
  `id`            INT UNSIGNED    NOT NULL AUTO_INCREMENT,
  `setting_key`   VARCHAR(100)    NOT NULL,
  `setting_value` LONGTEXT        NULL,
  `updated_at`    DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_key` (`setting_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ── Default Settings ─────────────────────────────────────────
INSERT IGNORE INTO `course_settings` (`setting_key`, `setting_value`) VALUES
  ('course_price_original',   '999'),
  ('course_price_sale',        '199'),
  ('course_title',             'AI Marketing & ChatGPT SEO'),
  ('course_slug',              'ai-marketing-course'),
  ('free_modules_count',       '5'),
  ('video_enabled',            '0'),
  ('cert_signatory_name',      'TechAasvik Editorial Team'),
  ('cert_logo_path',           ''),
  ('cert_signature_path',      ''),
  ('smtp_provider',            'hostinger'),
  ('smtp_host',                'smtp.hostinger.com'),
  ('smtp_port',                '587'),
  ('smtp_encryption',          'tls'),
  ('smtp_user',                ''),
  ('smtp_pass',                ''),
  ('smtp_from_name',           'TechAasvik'),
  ('smtp_from_email',          ''),
  ('razorpay_key_id',          ''),
  -- razorpay_key_secret is loaded from config.local.php or .env (never stored in DB)
  ('razorpay_key_secret_enc',  '');

SET FOREIGN_KEY_CHECKS = 1;
