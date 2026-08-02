<?php
namespace Controllers;

use Core\Controller;
use Core\View;
use Models\Content;
use Models\Category;
use Services\SeoService;
use Services\SchemaService;

/**
 * Archive Controller — category, tag, and resource archive pages.
 */
class ArchiveController extends Controller
{
    // ── Category archive ────────────────────────────────
    public function category(array $params = []): void
    {
        $slug    = $params['slug'] ?? '';
        $catModel = new Category();
        $cat     = $catModel->getBySlug($slug);
        if (!$cat) { $this->notFound('Category not found.'); return; }

        $page    = $this->page();
        $perPage = POSTS_PER_PAGE;
        $content = new Content();
        $posts   = $content->getByCategory($cat['id'], $perPage, ($page - 1) * $perPage);
        $total   = (int)$this->db->fetchColumn(
            "SELECT COUNT(DISTINCT cc.content_id) FROM content_categories cc
             INNER JOIN content c ON c.id = cc.content_id AND c.status = 'published'
             WHERE cc.category_id = ?",
            [$cat['id']]
        );

        $seoSvc = new SeoService();
        $seo    = $seoSvc->buildStatic(
            ($cat['meta_title'] ?: $cat['name'] . ' — Digital Marketing Guides'),
            ($cat['meta_description'] ?: $cat['description'] ?? ''),
            'https://t1.techaasvik.com/category/' . $cat['slug']
        );

        $schemaSvc = new SchemaService();
        $schemas   = [$schemaSvc->breadcrumbs([['name'=>'Home','url'=>'/'],['name'=>$cat['name']]])];

        $this->view('category', [
            'seo'     => $seo,
            'cat'     => $cat,
            'posts'   => $posts,
            'total'   => $total,
            'page'    => $page,
            'perPage' => $perPage,
            'schemas' => $schemas,
        ]);
    }

    // ── Tag archive ──────────────────────────────────────
    public function tag(array $params = []): void
    {
        $slug    = $params['slug'] ?? '';
        $tag     = $this->db->fetchOne("SELECT * FROM tags WHERE slug = ? LIMIT 1", [$slug]);
        if (!$tag) { $this->notFound('Tag not found.'); return; }

        $page    = $this->page();
        $perPage = POSTS_PER_PAGE;
        $content = new Content();
        $posts   = $content->getByTag($tag['id'], $perPage, ($page - 1) * $perPage);

        $seoSvc = new SeoService();
        $seo = $seoSvc->buildStatic(
            '#' . $tag['name'] . ' Articles — TechAasvik',
            'Browse all digital marketing content tagged with "' . $tag['name'] . '".',
            'https://t1.techaasvik.com/tag/' . $tag['slug']
        );
        $seo['noindex'] = true; // Tag archives are typically noindexed

        $this->view('tag', ['seo' => $seo, 'tag' => $tag, 'posts' => $posts, 'page' => $page, 'perPage' => $perPage]);
    }

    // ── Resource archives ─────────────────────────────────
    public function caseStudies(array $params = []): void
    {
        $content = new Content();
        $page    = $this->page();
        $items   = $content->getPublished('case_study', POSTS_PER_PAGE, ($page - 1) * POSTS_PER_PAGE);
        $total   = $content->countPublished('case_study');

        $seoSvc = new SeoService();
        $seo = $seoSvc->buildStatic(
            'Digital Marketing Case Studies — Real Results & ROI',
            'Real-world digital marketing case studies with measurable results. Learn what works from successful SEO, PPC, content marketing, and social media campaigns.',
            'https://t1.techaasvik.com/case-studies'
        );

        $this->view('resource-index', [
            'seo'   => $seo, 'items' => $items, 'total' => $total,
            'page'  => $page, 'type' => 'case_study',
            'title' => 'Case Studies', 'icon' => '📊',
            'desc'  => 'Real campaigns. Measurable results.',
        ]);
    }

    public function statistics(array $params = []): void
    {
        $content = new Content();
        $items   = $content->getPublished('statistics', 50, 0);
        $seoSvc  = new SeoService();
        $seo = $seoSvc->buildStatic(
            'Digital Marketing Statistics 2025 — Data & Research',
            '3,000+ curated digital marketing statistics covering SEO, social media, email marketing, PPC, content marketing, and more. All stats sourced and verified.',
            'https://t1.techaasvik.com/statistics'
        );
        $this->view('resource-index', [
            'seo'   => $seo, 'items' => $items, 'total' => count($items),
            'page'  => 1, 'type' => 'statistics',
            'title' => 'Statistics & Data', 'icon' => '📈',
            'desc'  => 'Data-backed insights for smarter decisions.',
        ]);
    }

    public function templates(array $params = []): void
    {
        $content = new Content();
        $items   = $content->getPublished('template', 50, 0);
        $seoSvc  = new SeoService();
        $seo = $seoSvc->buildStatic(
            'Free Digital Marketing Templates — Ready to Use',
            'Download free digital marketing templates for content calendars, audit checklists, campaign briefs, report dashboards, and more.',
            'https://t1.techaasvik.com/templates'
        );
        $this->view('resource-index', [
            'seo'   => $seo, 'items' => $items, 'total' => count($items),
            'page'  => 1, 'type' => 'template',
            'title' => 'Free Templates', 'icon' => '📋',
            'desc'  => 'Download and use immediately.',
        ]);
    }

    public function research(array $params = []): void
    {
        $content = new Content();
        $items   = $content->getPublished('research_report', 30, 0);
        $seoSvc  = new SeoService();
        $seo = $seoSvc->buildStatic(
            'Digital Marketing Research & Reports — TechAasvik',
            'Original digital marketing research, industry reports, and data studies published by the TechAasvik research team.',
            'https://t1.techaasvik.com/research'
        );
        $this->view('resource-index', [
            'seo'   => $seo, 'items' => $items, 'total' => count($items),
            'page'  => 1, 'type' => 'research_report',
            'title' => 'Research & Reports', 'icon' => '🔬',
            'desc'  => 'Original research and data insights.',
        ]);
    }

    // ── Course archive ───────────────────────────────────
    public function courses(array $params = []): void
    {
        $content = new Content();
        $items   = $content->getPublished('course', 30, 0);
        $seoSvc  = new SeoService();
        $seo = $seoSvc->buildStatic(
            'Free Digital Marketing Courses — Learn Online',
            'Free online digital marketing courses covering SEO, Google Ads, Meta Ads, content marketing, analytics, and AI marketing tools. Certificates available.',
            'https://t1.techaasvik.com/courses'
        );
        $schemaSvc = new SchemaService();
        $this->view('courses-index', [
            'seo'   => $seo, 'courses' => $items,
            'schemas' => [$schemaSvc->breadcrumbs([['name'=>'Home','url'=>'/'],['name'=>'Courses']])],
        ]);
    }

    // ── Single course ────────────────────────────────────
    public function course(array $params = []): void
    {
        $slug   = $params['slug'] ?? '';
        $course = (new Content())->getBySlug($slug, 'course');
        if (!$course) { $this->notFound('Course not found.'); return; }

        // Modules
        $modules = $this->db->fetchAll(
            "SELECT * FROM content WHERE parent_id = ? AND type = 'course_module' AND status = 'published' ORDER BY menu_order",
            [$course['id']]
        );

        $seoSvc    = new SeoService();
        $schemaSvc = new SchemaService();
        $seo       = $seoSvc->buildForContent($course);
        $schemas   = [
            $schemaSvc->course($course),
            $schemaSvc->breadcrumbs([['name'=>'Home','url'=>'/'],['name'=>'Courses','url'=>'/courses'],['name'=>$course['title']]]),
        ];

        $this->view('course', ['seo' => $seo, 'course' => $course, 'modules' => $modules, 'schemas' => $schemas]);
    }

    // ── Generic resource show ─────────────────────────────
    public function resourceShow(array $params = []): void
    {
        $type = $params['type'] ?? '';
        $slug = $params['slug'] ?? '';
        $item = (new Content())->getBySlug($slug, $type);
        if (!$item) { $this->notFound(); return; }

        $seoSvc    = new SeoService();
        $seo       = $seoSvc->buildForContent($item);
        $this->view('post', ['seo' => $seo, 'post' => $item, 'related' => [], 'faqs' => [], 'schemas' => []]);
    }

    // ── Search ────────────────────────────────────────────
    public function search(array $params = []): void
    {
        $query   = trim($this->request->get('q', ''));
        $page    = $this->page();
        $perPage = SEARCH_PER_PAGE;
        $results = [];
        $total   = 0;

        if ($query) {
            // Use FULLTEXT for 3+ char queries (MySQL ft_min_word_len default = 3)
            if (mb_strlen($query) >= 3) {
                $ftQuery = '+' . implode('* +', preg_split('/\s+/', $query)) . '*';
                $sql     = "SELECT c.*, a.name as author_name,
                            MATCH(c.title, c.excerpt, c.content) AGAINST(? IN BOOLEAN MODE) AS relevance
                            FROM content c
                            LEFT JOIN authors a ON a.id = c.author_id
                            WHERE c.status = 'published'
                            AND MATCH(c.title, c.excerpt, c.content) AGAINST(? IN BOOLEAN MODE)
                            ORDER BY relevance DESC";
                $all = $this->db->fetchAll($sql, [$ftQuery, $ftQuery]);
            } else {
                $like = '%' . $query . '%';
                $sql  = "SELECT c.*, a.name as author_name FROM content c
                         LEFT JOIN authors a ON a.id = c.author_id
                         WHERE c.status = 'published' AND (c.title LIKE ? OR c.excerpt LIKE ?)
                         ORDER BY c.published_at DESC";
                $all  = $this->db->fetchAll($sql, [$like, $like]);
            }
            $total   = count($all);
            $results = array_slice($all, ($page - 1) * $perPage, $perPage);
        }

        $seoSvc = new SeoService();
        $seo    = $seoSvc->buildStatic(
            $query ? "Search: \"{$query}\" — TechAasvik" : 'Search — TechAasvik',
            '',
            'https://t1.techaasvik.com/search'
        );
        $seo['noindex'] = true;

        $this->view('search', [
            'seo'     => $seo,
            'schemas' => [],
            'query'   => $query,
            'results' => $results,
            'total'   => $total,
            'page'    => $page,
            'perPage' => $perPage,
        ]);
    }

    // ── Resource Archive Helper ───────────────────────────
    private function resourceIndex(string $type, string $title, string $desc, string $icon, string $path): void
    {
        $page    = $this->page();
        $perPage = POSTS_PER_PAGE;
        $items   = $this->db->fetchAll(
            "SELECT * FROM content WHERE type = ? AND status = 'published' ORDER BY published_at DESC LIMIT ? OFFSET ?",
            [$type, $perPage, ($page - 1) * $perPage]
        );
        $total = (int)$this->db->fetchColumn("SELECT COUNT(*) FROM content WHERE type = ? AND status = 'published'", [$type]);

        $seoSvc = new SeoService();
        $seo    = $seoSvc->buildStatic($title . ' — TechAasvik', $desc, 'https://t1.techaasvik.com/' . $path);

        $this->view('resource-index', [
            'seo'     => $seo,
            'schemas' => [],
            'items'   => $items,
            'total'   => $total,
            'page'    => $page,
            'perPage' => $perPage,
            'type'    => $type,
            'title'   => $title,
            'desc'    => $desc,
            'icon'    => $icon,
        ]);
    }

    public function templates(array $params = []): void   { $this->resourceIndex('template',        'Free Marketing Templates',  'Download professionally designed marketing templates.',  '📋', 'templates'); }
    public function caseStudies(array $params = []): void { $this->resourceIndex('case_study',      'Digital Marketing Case Studies','Real campaign results from Indian businesses.',       '📊', 'case-studies'); }
    public function statistics(array $params = []): void  { $this->resourceIndex('statistics',      'Marketing Statistics',       'Latest digital marketing statistics from India and globally.','📈', 'statistics'); }
    public function research(array $params = []): void    { $this->resourceIndex('research_report', 'Research Reports',           'In-depth digital marketing research and industry reports.','🔬', 'research'); }
    public function videos(array $params = []): void      { $this->resourceIndex('video',           'Video Library',              'Digital marketing tutorials and explainer videos.',      '🎬', 'videos'); }
    public function news(array $params = []): void        { $this->resourceIndex('news_article',    'Digital Marketing News',     'Latest news and updates from the digital marketing world.','📰', 'news'); }
}

