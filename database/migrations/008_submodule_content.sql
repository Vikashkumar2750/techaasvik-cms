-- Migration 008: Course Submodule Content CMS
-- Run once on live DB via phpMyAdmin
-- Stores admin-editable content for each lesson/submodule

CREATE TABLE IF NOT EXISTS `course_submodule_content` (
    `id`            INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `course_slug`   VARCHAR(100) NOT NULL DEFAULT 'ai-marketing-course',
    `module_num`    TINYINT UNSIGNED NOT NULL COMMENT '1-10',
    `submodule_key` VARCHAR(30) NOT NULL COMMENT 'e.g. 1-1, 2-3, 10-5',
    `content_title` VARCHAR(255) NULL COMMENT 'Override lesson title shown in player',
    `content_html`  LONGTEXT NULL COMMENT 'Main HTML body — rendered below infographic',
    `image_url`     VARCHAR(1000) NULL COMMENT 'Top image/infographic URL',
    `video_url`     VARCHAR(1000) NULL COMMENT 'YouTube/Vimeo/MP4 URL',
    `video_embed`   TEXT NULL COMMENT 'Custom iframe embed code (overrides video_url player)',
    `key_points`    TEXT NULL COMMENT 'JSON array of strings for visual step cards',
    `infographic_title` VARCHAR(255) NULL COMMENT 'Override the visual card heading',
    `resources`     TEXT NULL COMMENT 'JSON array of {name,url} download links',
    `duration_text` VARCHAR(50) NULL COMMENT 'e.g. "12 min"',
    `updated_at`    TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uk_sub_content` (`course_slug`, `module_num`, `submodule_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
