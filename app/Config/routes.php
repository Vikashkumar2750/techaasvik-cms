<?php
/**
 * TECHAASVIK.COM — Route Definitions
 */

use Core\Router;

/** @var Router $router */

// ─────────────────────────────────────────────────────────────
// FRONTEND ROUTES
// ─────────────────────────────────────────────────────────────

// Home
$router->get('',        'HomeController@index', 'home');
$router->get('/',       'HomeController@index', 'home.slash');

// Static Pages
$router->get('/about',               'PageController@about',       'about');
$router->get('/contact',             'PageController@contact',     'contact');
$router->post('/contact',            'PageController@contactPost', 'contact.post');
$router->get('/sitemap',             'PageController@htmlSitemap', 'html-sitemap');
$router->get('/privacy-policy',      'PageController@legal',       'privacy');
$router->get('/terms-of-service',    'PageController@legal',       'terms');
$router->get('/editorial-policy',    'PageController@legal',       'editorial');
$router->get('/disclaimer',          'PageController@legal',       'disclaimer');
$router->get('/services',            'PageController@services',    'services');
$router->get('/services/{slug}',     'PageController@service',     'service');

// Knowledge Center
$router->get('/learn',               'PostController@learnIndex',  'learn');
$router->get('/learn/{topic}',       'PostController@pillar',      'pillar');
$router->get('/learn/{topic}/{slug}','PostController@cluster',     'cluster');

// Blog
$router->get('/blog',                'PostController@index',       'blog');
$router->get('/blog/{slug}',         'PostController@show',        'blog.show');

// Glossary
$router->get('/glossary',            'GlossaryController@index',   'glossary');
$router->get('/glossary/term/{slug}','GlossaryController@show',    'glossary.show');
$router->get('/glossary/{letter}',   'GlossaryController@letter',  'glossary.letter');

// Tools
$router->get('/tools',               'ToolController@index',       'tools');
$router->get('/tools/{slug}',        'ToolController@show',        'tool.show');
$router->post('/tools/{slug}/process','ToolController@process',    'tool.process');

// Calculators
$router->get('/calculators',         'ToolController@calculators', 'calculators');
$router->get('/calculators/{slug}',  'ToolController@calculator',  'calculator.show');

// Resource archives (all served via ArchiveController)
$router->get('/templates',           'ArchiveController@templates',  'templates');
$router->get('/templates/{slug}',    'PostController@show',          'template.show');
$router->get('/case-studies',        'ArchiveController@caseStudies','case-studies');
$router->get('/case-studies/{slug}', 'PostController@show',          'case-study.show');
$router->get('/statistics',          'ArchiveController@statistics', 'statistics');
$router->get('/statistics/{slug}',   'PostController@show',          'statistics.show');
$router->get('/research',            'ArchiveController@research',   'research');
$router->get('/research/{slug}',     'PostController@show',          'research.show');
$router->get('/videos',              'ArchiveController@videos',     'videos');

// Courses
$router->get('/courses',             'ArchiveController@courses',    'courses');
$router->get('/courses/{slug}',      'ArchiveController@course',     'course.show');

// Authors
$router->get('/authors',             'AuthorController@index',       'authors');
$router->get('/authors/{slug}',      'AuthorController@show',        'author.show');

// Archives
$router->get('/category/{slug}',     'ArchiveController@category',   'category');
$router->get('/tag/{slug}',          'ArchiveController@tag',        'tag');
$router->get('/topic/{slug}',        'ArchiveController@topic',      'topic');
$router->get('/news',                'ArchiveController@news',       'news');
$router->get('/news/{slug}',         'PostController@show',          'news.show');

// Search
$router->get('/search',              'ArchiveController@search',     'search');

// Lead Capture
$router->post('/lead/newsletter',    'LeadController@newsletter',    'lead.newsletter');
$router->post('/lead/audit',         'LeadController@auditRequest',  'lead.audit');
$router->post('/lead/contact',       'LeadController@contact',       'lead.contact');
$router->post('/lead/download',      'LeadController@download',      'lead.download');

// Hindi
$router->get('/hi',                  'HomeController@index',         'home.hi');
$router->get('/hi/{slug}',           'PostController@show',          'blog.show.hi');

// Sitemaps
$router->get('/sitemap.xml',          'SitemapController@index',    'sitemap.main');
$router->get('/sitemap-posts.xml',    'SitemapController@posts',    'sitemap.posts');
$router->get('/sitemap-pages.xml',    'SitemapController@pages',    'sitemap.pages');
$router->get('/sitemap-glossary.xml', 'SitemapController@glossary', 'sitemap.glossary');
$router->get('/sitemap-tools.xml',    'SitemapController@tools',    'sitemap.tools');
$router->get('/sitemap-courses.xml',  'SitemapController@courses',  'sitemap.courses');

// LLMs.txt — serve via PHP fallback if static file not found
$router->get('/llms.txt',             'SeoController@llmsTxt',      'llms.txt');
$router->get('/llms-full.txt',        'SeoController@llmsFullTxt',  'llms-full.txt');

// ─────────────────────────────────────────────────────────────
// ADMIN ROUTES (/techaasvik_admin/*)
// ─────────────────────────────────────────────────────────────

// Auth
$router->get('/techaasvik_admin',                         'Admin\AuthController@login',    'admin.login');
$router->get('/techaasvik_admin/login',                   'Admin\AuthController@login',    'admin.login.get');
$router->post('/techaasvik_admin/login',                  'Admin\AuthController@doLogin',  'admin.login.post');
$router->get('/techaasvik_admin/logout',                  'Admin\AuthController@logout',   'admin.logout');

// Dashboard
$router->get('/techaasvik_admin/dashboard',               'Admin\DashboardController@index', 'admin.dashboard');

// Content
$router->get('/techaasvik_admin/content',                 'Admin\ContentController@index',   'admin.content');
$router->get('/techaasvik_admin/content/new',             'Admin\ContentController@create',  'admin.content.new');
$router->post('/techaasvik_admin/content/store',          'Admin\ContentController@store',   'admin.content.store');
$router->get('/techaasvik_admin/content/{id}/edit',       'Admin\ContentController@edit',    'admin.content.edit');
$router->post('/techaasvik_admin/content/{id}/update',    'Admin\ContentController@update',  'admin.content.update');
$router->post('/techaasvik_admin/content/{id}/delete',    'Admin\ContentController@delete',  'admin.content.delete');
$router->post('/techaasvik_admin/content/{id}/publish',   'Admin\ContentController@publish', 'admin.content.publish');

// Media
$router->get('/techaasvik_admin/media',                   'Admin\MediaController@index',   'admin.media');
$router->get('/techaasvik_admin/media/api',                'Admin\MediaController@api',     'admin.media.api');
$router->post('/techaasvik_admin/media/upload',           'Admin\MediaController@upload',  'admin.media.upload');
$router->post('/techaasvik_admin/media/{id}/delete',      'Admin\MediaController@delete',  'admin.media.delete');

// Taxonomy
$router->get('/techaasvik_admin/categories',              'Admin\TaxonomyController@categories',     'admin.categories');
$router->post('/techaasvik_admin/categories/store',       'Admin\TaxonomyController@storeCategory',  'admin.categories.store');
$router->post('/techaasvik_admin/categories/{id}/delete', 'Admin\TaxonomyController@deleteCategory', 'admin.categories.delete');
$router->get('/techaasvik_admin/tags',                    'Admin\TaxonomyController@tags',           'admin.tags');
$router->post('/techaasvik_admin/tags/store',             'Admin\TaxonomyController@storeTag',       'admin.tags.store');
$router->post('/techaasvik_admin/tags/{id}/delete',       'Admin\TaxonomyController@deleteTag',      'admin.tags.delete');

// Leads
$router->get('/techaasvik_admin/leads',                   'Admin\LeadsController@index',        'admin.leads');
$router->get('/techaasvik_admin/leads/export',            'Admin\LeadsController@export',       'admin.leads.export');
$router->post('/techaasvik_admin/leads/{id}/status',      'Admin\LeadsController@updateStatus', 'admin.leads.status');
$router->post('/techaasvik_admin/leads/{id}/delete',      'Admin\LeadsController@delete',       'admin.leads.delete');

// Settings
$router->get('/techaasvik_admin/settings',                'Admin\SettingsController@index',  'admin.settings');
$router->post('/techaasvik_admin/settings/update',        'Admin\SettingsController@update', 'admin.settings.update');

// Authors
$router->get('/techaasvik_admin/authors',                 'Admin\AuthorController@index',   'admin.authors');
$router->get('/techaasvik_admin/authors/new',             'Admin\AuthorController@edit',    'admin.authors.new');
$router->post('/techaasvik_admin/authors/store',          'Admin\AuthorController@store',   'admin.authors.store');
$router->get('/techaasvik_admin/authors/{id}/edit',       'Admin\AuthorController@edit',    'admin.authors.edit');
$router->post('/techaasvik_admin/authors/{id}/update',    'Admin\AuthorController@update',  'admin.authors.update');
$router->post('/techaasvik_admin/authors/{id}/delete',    'Admin\AuthorController@delete',  'admin.authors.delete');

// Menus
$router->get('/techaasvik_admin/menus',                   'Admin\MenuController@index',      'admin.menus');
$router->post('/techaasvik_admin/menus/create',           'Admin\MenuController@createMenu', 'admin.menus.create');
$router->post('/techaasvik_admin/menus/{id}/add-item',    'Admin\MenuController@addItem',    'admin.menus.addItem');
$router->post('/techaasvik_admin/menus/item/{id}/delete', 'Admin\MenuController@deleteItem', 'admin.menus.deleteItem');
$router->post('/techaasvik_admin/menus/{id}/reorder',     'Admin\MenuController@reorder',    'admin.menus.reorder');
$router->post('/techaasvik_admin/menus/{id}/delete',      'Admin\MenuController@deleteMenu', 'admin.menus.delete');

// SEO Tools
$router->get('/techaasvik_admin/seo',                        'Admin\SeoToolsController@index',                'admin.seo');
$router->post('/techaasvik_admin/seo/generate-titles',       'Admin\SeoToolsController@generateTitles',       'admin.seo.titles');
$router->post('/techaasvik_admin/seo/generate-descriptions', 'Admin\SeoToolsController@generateDescriptions', 'admin.seo.desc');
$router->post('/techaasvik_admin/seo/regenerate-sitemap',    'Admin\SeoToolsController@regenerateSitemap',    'admin.seo.regen.sitemap');
$router->post('/techaasvik_admin/seo/regenerate-llms',       'Admin\SeoToolsController@regenerateLlms',       'admin.seo.regen.llms');
$router->post('/techaasvik_admin/seo/regenerate-llms-full',  'Admin\SeoToolsController@regenerateLlmsFull',   'admin.seo.regen.llmsfull');
$router->post('/techaasvik_admin/seo/regenerate-all',        'Admin\SeoToolsController@regenerateAll',        'admin.seo.regen.all');

// Temp: fix content (remove after use)
$router->get('/techaasvik_admin/fix-content',                'Admin\SeoToolsController@fixContent',           'admin.fix.content');
