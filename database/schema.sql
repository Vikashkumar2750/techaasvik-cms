-- ============================================================
-- TECHAASVIK.COM — MASTER DATABASE SCHEMA
-- Engine: MySQL 8.0 | Charset: utf8mb4_unicode_ci
-- ============================================================

SET FOREIGN_KEY_CHECKS = 0;
SET sql_mode = 'STRICT_TRANS_TABLES,NO_ZERO_DATE,NO_ZERO_IN_DATE,ERROR_FOR_DIVISION_BY_ZERO';

-- ── Admin Users ─────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `admin_users` (
  `id`             INT UNSIGNED    NOT NULL AUTO_INCREMENT,
  `username`       VARCHAR(100)    NOT NULL,
  `password_hash`  VARCHAR(255)    NOT NULL,
  `email`          VARCHAR(255)    NOT NULL,
  `role`           ENUM('super_admin','admin','editor','author','reviewer') NOT NULL DEFAULT 'editor',
  `last_login`     DATETIME        NULL,
  `login_attempts` TINYINT         NOT NULL DEFAULT 0,
  `locked_until`   DATETIME        NULL,
  `created_at`     DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`     DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_username` (`username`),
  UNIQUE KEY `uk_email`    (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ── Authors (Content contributors / experts) ─────────────────
CREATE TABLE IF NOT EXISTS `authors` (
  `id`           INT UNSIGNED    NOT NULL AUTO_INCREMENT,
  `name`         VARCHAR(255)    NOT NULL,
  `slug`         VARCHAR(255)    NOT NULL,
  `bio`          TEXT            NULL,
  `short_bio`    VARCHAR(500)    NULL,
  `photo_id`     INT UNSIGNED    NULL,
  `credentials`  VARCHAR(500)    NULL,
  `social_links` JSON            NULL,
  `schema_json`  JSON            NULL,
  `email`        VARCHAR(255)    NULL,
  `is_active`    TINYINT(1)      NOT NULL DEFAULT 1,
  `created_at`   DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`   DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ── Media ───────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `media` (
  `id`          INT UNSIGNED    NOT NULL AUTO_INCREMENT,
  `filename`    VARCHAR(255)    NOT NULL,
  `filepath`    VARCHAR(500)    NOT NULL,
  `mime_type`   VARCHAR(100)    NOT NULL,
  `alt_text`    VARCHAR(500)    NULL,
  `title`       VARCHAR(255)    NULL,
  `width`       SMALLINT        NULL,
  `height`      SMALLINT        NULL,
  `filesize`    INT UNSIGNED    NOT NULL DEFAULT 0,
  `uploaded_by` INT UNSIGNED    NULL,
  `created_at`  DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_mime`    (`mime_type`),
  KEY `idx_created` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ── Content (universal content table) ───────────────────────
CREATE TABLE IF NOT EXISTS `content` (
  `id`                INT UNSIGNED    NOT NULL AUTO_INCREMENT,
  `type`              ENUM('post','page','pillar','cluster','glossary_term','case_study',
                          'statistics','tool','calculator','template','course',
                          'course_module','course_lesson','research_report',
                          'news_article','video','podcast_episode')
                      NOT NULL DEFAULT 'post',
  `lang`              ENUM('en','hi','ta','te','mr','bn','kn') NOT NULL DEFAULT 'en',
  `title`             VARCHAR(500)    NOT NULL,
  `slug`              VARCHAR(500)    NOT NULL,
  `content`           LONGTEXT        NULL,
  `excerpt`           TEXT            NULL,
  `status`            ENUM('draft','published','scheduled','archived','private')
                      NOT NULL DEFAULT 'draft',
  `author_id`         INT UNSIGNED    NULL,
  `parent_id`         INT UNSIGNED    NULL,
  `featured_image_id` INT UNSIGNED    NULL,
  `featured_image`    VARCHAR(500)    NULL COMMENT 'URL of featured image',
  `menu_order`        SMALLINT        NOT NULL DEFAULT 0,
  `comment_status`    TINYINT(1)      NOT NULL DEFAULT 0,
  `word_count`        INT             NULL,
  `read_time`         TINYINT         NULL COMMENT 'Minutes to read',
  `difficulty`        ENUM('beginner','intermediate','advanced') NULL,
  `published_at`      DATETIME        NULL,
  `scheduled_at`      DATETIME        NULL,
  `created_at`        DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`        DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_slug_type_lang` (`slug`, `type`, `lang`),
  KEY `idx_type_status_lang`    (`type`, `status`, `lang`),
  KEY `idx_author`              (`author_id`),
  KEY `idx_parent`              (`parent_id`),
  KEY `idx_published_at`        (`published_at`),
  KEY `idx_status`              (`status`),
  FULLTEXT KEY `ft_search` (`title`, `excerpt`, `content`),
  CONSTRAINT `fk_content_author` FOREIGN KEY (`author_id`) REFERENCES `authors` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ── Content Meta (flexible key-value metadata) ───────────────
CREATE TABLE IF NOT EXISTS `content_meta` (
  `id`         INT UNSIGNED    NOT NULL AUTO_INCREMENT,
  `content_id` INT UNSIGNED    NOT NULL,
  `meta_key`   VARCHAR(255)    NOT NULL,
  `meta_value` LONGTEXT        NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_content_key` (`content_id`, `meta_key`),
  KEY `idx_key`               (`meta_key`),
  CONSTRAINT `fk_meta_content` FOREIGN KEY (`content_id`) REFERENCES `content` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ── Content SEO ──────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `content_seo` (
  `id`               INT UNSIGNED    NOT NULL AUTO_INCREMENT,
  `content_id`       INT UNSIGNED    NOT NULL,
  `meta_title`       VARCHAR(70)     NULL,
  `meta_description` VARCHAR(160)    NULL,
  `canonical_url`    VARCHAR(500)    NULL,
  `og_title`         VARCHAR(200)    NULL,
  `og_description`   VARCHAR(300)    NULL,
  `og_image`         VARCHAR(500)    NULL,
  `twitter_title`    VARCHAR(200)    NULL,
  `twitter_image`    VARCHAR(500)    NULL,
  `schema_type`      VARCHAR(100)    NULL,
  `schema_json`      JSON            NULL,
  `noindex`          TINYINT(1)      NOT NULL DEFAULT 0,
  `nofollow`         TINYINT(1)      NOT NULL DEFAULT 0,
  `focus_keyword`    VARCHAR(255)    NULL,
  `seo_score`        TINYINT         NULL,
  `created_at`       DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`       DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_content_id` (`content_id`),
  CONSTRAINT `fk_seo_content` FOREIGN KEY (`content_id`) REFERENCES `content` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ── Categories ───────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `categories` (
  `id`               INT UNSIGNED    NOT NULL AUTO_INCREMENT,
  `name`             VARCHAR(255)    NOT NULL,
  `slug`             VARCHAR(255)    NOT NULL,
  `parent_id`        INT UNSIGNED    NULL,
  `description`      TEXT            NULL,
  `meta_title`       VARCHAR(70)     NULL,
  `meta_description` VARCHAR(160)    NULL,
  `schema_json`      JSON            NULL,
  `menu_order`       SMALLINT        NOT NULL DEFAULT 0,
  `content_count`    INT             NOT NULL DEFAULT 0,
  `created_at`       DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`       DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_slug` (`slug`),
  KEY `idx_parent`   (`parent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ── Tags ─────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `tags` (
  `id`          INT UNSIGNED    NOT NULL AUTO_INCREMENT,
  `name`        VARCHAR(255)    NOT NULL,
  `slug`        VARCHAR(255)    NOT NULL,
  `description` TEXT            NULL,
  `created_at`  DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ── Content ↔ Categories (pivot) ────────────────────────────
CREATE TABLE IF NOT EXISTS `content_categories` (
  `content_id`  INT UNSIGNED    NOT NULL,
  `category_id` INT UNSIGNED    NOT NULL,
  PRIMARY KEY (`content_id`, `category_id`),
  CONSTRAINT `fk_cc_content`  FOREIGN KEY (`content_id`)  REFERENCES `content`    (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_cc_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ── Content ↔ Tags (pivot) ───────────────────────────────────
CREATE TABLE IF NOT EXISTS `content_tags` (
  `content_id` INT UNSIGNED    NOT NULL,
  `tag_id`     INT UNSIGNED    NOT NULL,
  PRIMARY KEY (`content_id`, `tag_id`),
  CONSTRAINT `fk_ct_content` FOREIGN KEY (`content_id`) REFERENCES `content` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_ct_tag`     FOREIGN KEY (`tag_id`)     REFERENCES `tags`    (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ── Internal Links (tracked) ─────────────────────────────────
CREATE TABLE IF NOT EXISTS `internal_links` (
  `id`              INT UNSIGNED    NOT NULL AUTO_INCREMENT,
  `from_content_id` INT UNSIGNED    NOT NULL,
  `to_content_id`   INT UNSIGNED    NOT NULL,
  `anchor_text`     VARCHAR(255)    NULL,
  `link_type`       ENUM('contextual','navigation','footer','sidebar','breadcrumb','cta') NOT NULL DEFAULT 'contextual',
  `created_at`      DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_from` (`from_content_id`),
  KEY `idx_to`   (`to_content_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ── Menus ────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `menus` (
  `id`         INT UNSIGNED    NOT NULL AUTO_INCREMENT,
  `name`       VARCHAR(100)    NOT NULL,
  `location`   VARCHAR(100)    NOT NULL COMMENT 'primary, footer, sidebar, mobile',
  `created_at` DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_location` (`location`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `menu_items` (
  `id`          INT UNSIGNED    NOT NULL AUTO_INCREMENT,
  `menu_id`     INT UNSIGNED    NOT NULL,
  `parent_id`   INT UNSIGNED    NULL,
  `title`       VARCHAR(255)    NOT NULL,
  `url`         VARCHAR(500)    NULL,
  `content_id`  INT UNSIGNED    NULL,
  `target`      ENUM('_self','_blank') NOT NULL DEFAULT '_self',
  `icon`        VARCHAR(100)    NULL,
  `badge`       VARCHAR(50)     NULL,
  `menu_order`  SMALLINT        NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `idx_menu`   (`menu_id`),
  KEY `idx_parent` (`parent_id`),
  CONSTRAINT `fk_mi_menu` FOREIGN KEY (`menu_id`) REFERENCES `menus` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ── Leads / Subscribers ───────────────────────────────────────
CREATE TABLE IF NOT EXISTS `leads` (
  `id`              INT UNSIGNED    NOT NULL AUTO_INCREMENT,
  `email`           VARCHAR(255)    NOT NULL,
  `name`            VARCHAR(255)    NULL,
  `phone`           VARCHAR(20)     NULL,
  `company`         VARCHAR(255)    NULL,
  `website`         VARCHAR(500)    NULL,
  `message`         TEXT            NULL,
  `lead_type`       ENUM('newsletter','audit','contact','course','tool','download') NOT NULL DEFAULT 'newsletter',
  `source_page`     VARCHAR(500)    NULL,
  `utm_source`      VARCHAR(100)    NULL,
  `utm_medium`      VARCHAR(100)    NULL,
  `utm_campaign`    VARCHAR(100)    NULL,
  `utm_content`     VARCHAR(100)    NULL,
  `utm_term`        VARCHAR(100)    NULL,
  `ip_address`      VARCHAR(45)     NULL,
  `status`          ENUM('new','contacted','qualified','converted','unsubscribed') NOT NULL DEFAULT 'new',
  `notes`           TEXT            NULL,
  `created_at`      DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`      DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_email`      (`email`),
  KEY `idx_lead_type`  (`lead_type`),
  KEY `idx_status`     (`status`),
  KEY `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ── Site Settings ────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `settings` (
  `id`          INT UNSIGNED    NOT NULL AUTO_INCREMENT,
  `setting_key` VARCHAR(255)    NOT NULL,
  `setting_value` LONGTEXT        NULL,
  `autoload`    TINYINT(1)      NOT NULL DEFAULT 1,
  `created_at`  DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`  DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_key` (`setting_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ── Job / Cron Log ───────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `job_log` (
  `id`       INT UNSIGNED    NOT NULL AUTO_INCREMENT,
  `job_name` VARCHAR(100)    NOT NULL,
  `status`   ENUM('success','error','running') NOT NULL DEFAULT 'running',
  `message`  TEXT            NULL,
  `run_at`   DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_job_name` (`job_name`),
  KEY `idx_run_at`   (`run_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;
