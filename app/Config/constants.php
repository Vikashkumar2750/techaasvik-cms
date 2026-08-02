<?php
/**
 * TECHAASVIK.COM — Application Constants
 */

// ── Content Type Constants ─────────────────────────────────
define('TYPE_POST',          'post');
define('TYPE_PAGE',          'page');
define('TYPE_PILLAR',        'pillar');
define('TYPE_GLOSSARY',      'glossary_term');
define('TYPE_CASE_STUDY',    'case_study');
define('TYPE_STATISTICS',    'statistics');
define('TYPE_TOOL',          'tool');
define('TYPE_CALCULATOR',    'calculator');
define('TYPE_TEMPLATE',      'template');
define('TYPE_COURSE',        'course');
define('TYPE_MODULE',        'course_module');
define('TYPE_LESSON',        'course_lesson');
define('TYPE_RESEARCH',      'research_report');
define('TYPE_NEWS',          'news_article');
define('TYPE_VIDEO',         'video');
define('TYPE_PODCAST',       'podcast_episode');

// ── Content Status ─────────────────────────────────────────
define('STATUS_DRAFT',       'draft');
define('STATUS_PUBLISHED',   'published');
define('STATUS_SCHEDULED',   'scheduled');
define('STATUS_ARCHIVED',    'archived');
define('STATUS_PRIVATE',     'private');

// ── Language Codes ─────────────────────────────────────────
define('LANG_EN', 'en');
define('LANG_HI', 'hi');

// ── Schema Types ───────────────────────────────────────────
define('SCHEMA_ARTICLE',           'Article');
define('SCHEMA_BLOG_POSTING',      'BlogPosting');
define('SCHEMA_NEWS_ARTICLE',      'NewsArticle');
define('SCHEMA_WEBPAGE',           'WebPage');
define('SCHEMA_HOWTO',             'HowTo');
define('SCHEMA_FAQPAGE',           'FAQPage');
define('SCHEMA_DEFINED_TERM',      'DefinedTerm');
define('SCHEMA_COURSE',            'Course');
define('SCHEMA_SOFTWARE_APP',      'SoftwareApplication');
define('SCHEMA_PERSON',            'Person');
define('SCHEMA_ORGANIZATION',      'Organization');
define('SCHEMA_DATASET',           'Dataset');
define('SCHEMA_REPORT',            'Report');
define('SCHEMA_VIDEO_OBJECT',      'VideoObject');
define('SCHEMA_PODCAST_EPISODE',   'PodcastEpisode');
define('SCHEMA_LOCAL_BUSINESS',    'LocalBusiness');
define('SCHEMA_BREADCRUMB',        'BreadcrumbList');

// ── Roles ──────────────────────────────────────────────────
define('ROLE_SUPER_ADMIN', 'super_admin');
define('ROLE_ADMIN',       'admin');
define('ROLE_EDITOR',      'editor');
define('ROLE_AUTHOR',      'author');
define('ROLE_REVIEWER',    'reviewer');

// ── Cache Keys ─────────────────────────────────────────────
define('CACHE_SETTINGS',       'app_settings');
define('CACHE_MENUS',          'app_menus');
define('CACHE_CATEGORIES',     'app_categories');
define('CACHE_POPULAR_POSTS',  'popular_posts');
define('CACHE_RECENT_POSTS',   'recent_posts');

// ── Pagination ─────────────────────────────────────────────
define('POSTS_PER_PAGE',    20);
define('GLOSSARY_PER_PAGE', 50);
define('SEARCH_PER_PAGE',   20);

// ── Version (for cache busting CSS/JS) ────────────────────
define('ASSET_VERSION', '1.0.0');

// ── Date/Time Formats ──────────────────────────────────────
define('DATE_FORMAT',          'd M Y');
define('DATE_FORMAT_FULL',     'd M Y, h:i A');
define('DATE_FORMAT_ISO',      'Y-m-d');
define('DATE_FORMAT_SCHEMA',   'Y-m-d\TH:i:sP');

// ── Content Types Map (value => label) for admin UI ───────
if (!defined('CONTENT_TYPES')) {
    define('CONTENT_TYPES', [
        'post'            => '📝 Blog Post',
        'pillar'          => '📚 Pillar Page',
        'glossary_term'   => '📖 Glossary Term',
        'case_study'      => '📊 Case Study',
        'statistics'      => '📈 Statistics Page',
        'tool'            => '⚙️ Tool',
        'calculator'      => '🧮 Calculator',
        'template'        => '📋 Template',
        'course'          => '🎓 Course',
        'course_module'   => '📦 Course Module',
        'research_report' => '🔬 Research Report',
        'news_article'    => '📰 News Article',
        'video'           => '🎬 Video',
        'podcast_episode' => '🎙 Podcast Episode',
        'page'            => '🗒 Static Page',
    ]);
}
