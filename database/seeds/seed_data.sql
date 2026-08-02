-- ============================================================
-- TECHAASVIK.COM — SEED DATA
-- Run AFTER schema.sql
-- ============================================================

-- ── Admin User ────────────────────────────────────────────────
-- Password: techaasvik@27 (bcrypt hash — DO NOT change)
-- Hash generated with: password_hash('techaasvik@27', PASSWORD_BCRYPT, ['cost'=>12])
INSERT INTO `admin_users` (`username`, `password_hash`, `email`, `role`) VALUES
('techaasvik', '$2y$12$placeholder_run_setup_php_to_set_real_hash', 'admin@techaasvik.com', 'super_admin');

-- ── Default Author ────────────────────────────────────────────
INSERT INTO `authors` (`name`, `slug`, `bio`, `short_bio`, `credentials`, `social_links`) VALUES
(
  'TechAasvik Team',
  'techaasvik-team',
  'TechAasvik is India\'s most authoritative digital marketing knowledge platform. Our team of certified digital marketing experts, SEO specialists, and content strategists brings you research-backed, actionable insights to help you grow online.',
  'India\'s leading digital marketing authority and knowledge platform.',
  'Google Certified, Meta Blueprint Certified, Digital Marketing Experts',
  '{"linkedin":"https://linkedin.com/company/techaasvik","youtube":"https://youtube.com/@techaasvik","instagram":"https://instagram.com/techaasvik","twitter":"https://twitter.com/techaasvik"}'
);

-- ── Default Categories ─────────────────────────────────────────
INSERT INTO `categories` (`name`, `slug`, `description`, `meta_title`, `meta_description`, `menu_order`) VALUES
('SEO',                 'seo',                 'Search Engine Optimization guides, tips, strategies, and tutorials.',  'SEO Guides & Tutorials | TechAasvik',              'Learn SEO from India\'s top digital marketing experts. Covers technical SEO, on-page SEO, link building, local SEO, and more.',  1),
('Google Ads',          'google-ads',          'Google Ads tutorials, strategies, and optimization guides.',           'Google Ads Tutorials | TechAasvik',                'Master Google Ads with expert tutorials, bid strategies, campaign optimization, and performance marketing insights.',             2),
('Meta Ads',            'meta-ads',            'Facebook and Instagram advertising guides and strategies.',            'Meta Ads & Facebook Advertising | TechAasvik',     'Complete Facebook and Instagram advertising guides. Learn Meta Ads, campaign setup, targeting, and optimization.',               3),
('Content Marketing',   'content-marketing',   'Content strategy, writing, and marketing guides.',                    'Content Marketing Guides | TechAasvik',            'Build authority with content marketing. Learn content strategy, writing, distribution, and measurement.',                       4),
('AEO',                 'aeo',                 'Answer Engine Optimization for featured snippets and AI answers.',    'AEO — Answer Engine Optimization | TechAasvik',   'Master Answer Engine Optimization. Learn how to rank in featured snippets, Google AI Overviews, and AI-powered search.',       5),
('GEO',                 'geo',                 'Generative Engine Optimization for AI-powered search.',               'GEO — Generative Engine Optimization | TechAasvik','Get cited by ChatGPT, Gemini, Perplexity, and other AI systems. Learn Generative Engine Optimization strategies.',               6),
('Analytics',           'analytics',           'GA4, Looker Studio, GTM, and marketing analytics guides.',            'Marketing Analytics — GA4 & GTM | TechAasvik',    'Master marketing analytics with GA4, Google Tag Manager, Looker Studio, and data-driven decision making.',                    7),
('Email Marketing',     'email-marketing',     'Email marketing strategy, automation, and deliverability guides.',    'Email Marketing Guides | TechAasvik',              'Build and grow your email marketing. Learn email strategy, automation, segmentation, and deliverability.',                     8),
('Social Media',        'social-media',        'Social media marketing guides for all major platforms.',              'Social Media Marketing Guides | TechAasvik',       'Grow your brand on social media. LinkedIn, Instagram, YouTube, and Facebook marketing strategies.',                           9),
('AI & Prompt Engineering','ai-prompts',       'AI tools, prompt engineering, and AI marketing strategies.',         'AI Marketing & Prompt Engineering | TechAasvik',  'Stay ahead with AI marketing tools, ChatGPT prompts, and AI-powered marketing strategies.',                                  10),
('Digital Marketing',   'digital-marketing',   'Comprehensive digital marketing guides and strategies.',             'Digital Marketing Guides | TechAasvik',            'Complete digital marketing resources covering SEO, ads, social media, email, and analytics.',                                  11),
('CRO',                 'cro',                 'Conversion Rate Optimization guides and landing page strategies.',   'CRO & Landing Page Optimization | TechAasvik',    'Improve your conversion rates. Learn CRO strategy, A/B testing, landing page design, and user behavior analysis.',             12);

-- ── Default Menus ──────────────────────────────────────────────
INSERT INTO `menus` (`name`, `location`) VALUES
('Primary Navigation', 'primary'),
('Footer Navigation',  'footer'),
('Mobile Navigation',  'mobile');

-- Primary menu items
INSERT INTO `menu_items` (`menu_id`, `parent_id`, `title`, `url`, `menu_order`) VALUES
(1, NULL, 'Learn',        '/learn',        1),
(1, NULL, 'Blog',         '/blog',         2),
(1, NULL, 'Tools',        '/tools',        3),
(1, NULL, 'Glossary',     '/glossary',     4),
(1, NULL, 'Case Studies', '/case-studies', 5),
(1, NULL, 'Courses',      '/courses',      6),
(1, NULL, 'Services',     '/services',     7);

-- ── Default Settings ───────────────────────────────────────────
INSERT INTO `settings` (`setting_key`, `setting_value`, `autoload`) VALUES
('site_name',           'TechAasvik',                             1),
('site_tagline',        'India\'s Digital Marketing Authority',   1),
('site_url',            'https://t1.techaasvik.com',                 1),
('site_email',          'hello@techaasvik.com',                   1),
('ga4_id',              '',                                       1),
('gtm_id',              '',                                       1),
('gsc_verify',          '',                                       1),
('recaptcha_site_key',  '',                                       1),
('posts_per_page',      '20',                                     1),
('social_linkedin',     'https://linkedin.com/company/techaasvik',1),
('social_youtube',      'https://youtube.com/@techaasvik',        1),
('social_instagram',    'https://instagram.com/techaasvik',       1),
('social_twitter',      'https://twitter.com/techaasvik',         1),
('maintenance_mode',    '0',                                      1),
('show_hindi',          '1',                                      1);
