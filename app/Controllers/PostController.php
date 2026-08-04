<?php
namespace Controllers;

use Core\Controller;
use Core\View;
use Models\Content;
use Services\SeoService;
use Services\SchemaService;

/**
 * Post Controller — Blog, Pillar Pages, Cluster Pages, News, Learn section.
 */
class PostController extends Controller
{
    private Content      $content;
    private SeoService   $seoSvc;
    private SchemaService $schemaSvc;

    public function __construct()
    {
        parent::__construct();
        $this->content   = new Content();
        $this->seoSvc    = new SeoService();
        $this->schemaSvc = new SchemaService();
    }

    // ── Blog Index ────────────────────────────────────────
    public function index(array $params = []): void
    {
        $lang    = $this->request->get('lang', 'en');
        $page    = $this->page();
        $perPage = POSTS_PER_PAGE;
        $offset  = ($page - 1) * $perPage;

        $posts = $this->content->getPublished('post', $perPage, $offset, $lang);
        $total = $this->content->countPublished('post', $lang);

        $seo = $this->seoSvc->buildStatic(
            'Digital Marketing Blog — Latest Articles & Guides',
            'Expert digital marketing articles covering SEO, Google Ads, Meta Ads, content marketing, analytics, and more. Written by certified marketing professionals.',
            'https://t1.techaasvik.com/blog'
        );

        $this->view('blog-index', [
            'seo'      => $seo,
            'posts'    => $posts,
            'total'    => $total,
            'page'     => $page,
            'perPage'  => $perPage,
            'lang'     => $lang,
            'schemas'  => [$this->schemaSvc->breadcrumbs([
                ['name' => 'Home',  'url' => '/'],
                ['name' => 'Blog'],
            ])],
        ]);
    }

    // ── Single Post ───────────────────────────────────────
    public function show(array $params = []): void
    {
        $slug = $params['slug'] ?? '';
        $post = $this->content->getBySlug($slug, 'post');

        if (!$post) {
            $this->notFound('Article not found.');
            return;
        }

        $seo      = $this->seoSvc->buildForContent($post);
        $related  = $this->content->getRelated($post['id'], 'post', 4);
        $faqs     = $this->extractFaqs($post['content'] ?? '');

        // Build schemas
        $schemas = [
            $this->schemaSvc->article($post, $seo, 'BlogPosting'),
            $this->schemaSvc->breadcrumbs([
                ['name' => 'Home', 'url' => '/'],
                ['name' => 'Blog', 'url' => '/blog'],
                ['name' => $post['title']],
            ]),
        ];

        if ($faqs) {
            $schemas[] = $this->schemaSvc->faqPage($faqs);
        }

        $this->view('post', [
            'seo'     => $seo,
            'post'    => $post,
            'related' => $related,
            'faqs'    => $faqs,
            'schemas' => $schemas,
            'schemaSvc' => $this->schemaSvc,
        ]);
    }

    // ── Learn Index (Knowledge Center) ────────────────────
    public function learnIndex(array $params = []): void
    {
        $pillars = $this->content->getPublished('pillar', 50, 0, 'en');

        $seo = $this->seoSvc->buildStatic(
            'Digital Marketing Knowledge Center — Learn Everything',
            'India\'s most comprehensive digital marketing knowledge center. Deep-dive guides on SEO, GEO, AEO, Google Ads, Meta Ads, analytics, and every digital marketing topic.',
            'https://t1.techaasvik.com/learn'
        );

        $this->view('learn-index', [
            'seo'     => $seo,
            'pillars' => $pillars,
            'schemas' => [$this->schemaSvc->breadcrumbs([
                ['name' => 'Home', 'url' => '/'],
                ['name' => 'Learn'],
            ])],
        ]);
    }

    // ── Pillar Page ───────────────────────────────────────
    public function pillar(array $params = []): void
    {
        $slug   = $params['topic'] ?? '';
        $pillar = $this->content->getBySlug($slug, 'pillar');

        if (!$pillar) {
            $this->notFound('Topic not found.');
            return;
        }

        // Get cluster posts under this pillar
        $clusters = $this->db->fetchAll(
            "SELECT c.id, c.title, c.slug, c.excerpt, c.read_time, c.difficulty, c.published_at
             FROM content c
             WHERE c.parent_id = ? AND c.status = 'published'
             ORDER BY c.menu_order, c.published_at DESC",
            [$pillar['id']]
        );

        // Get related pillar pages (other pillars, excluding current)
        $relatedPillars = $this->db->fetchAll(
            "SELECT title, slug FROM content
             WHERE type = 'pillar' AND status = 'published' AND lang = 'en' AND id != ?
             ORDER BY RAND() LIMIT 3",
            [$pillar['id']]
        );

        // Get author info
        if (!empty($pillar['author_id'])) {
            $author = $this->db->fetchOne("SELECT name, bio, short_bio, credentials FROM authors WHERE id = ?", [$pillar['author_id']]);
            if ($author) {
                $pillar['author_name'] = $author['name'];
                $pillar['author_bio'] = $author['short_bio'] ?: $author['bio'] ?: '';
                $pillar['author_credentials'] = $author['credentials'] ?: '';
            }
        }

        $seo     = $this->seoSvc->buildForContent($pillar);
        $schemas = [
            $this->schemaSvc->article($pillar, $seo, 'Article'),
            $this->schemaSvc->breadcrumbs([
                ['name' => 'Home',  'url' => '/'],
                ['name' => 'Learn', 'url' => '/learn'],
                ['name' => $pillar['title']],
            ]),
        ];

        $this->view('pillar', [
            'seo'            => $seo,
            'pillar'         => $pillar,
            'clusters'       => $clusters,
            'relatedPillars' => $relatedPillars,
            'schemas'        => $schemas,
            'schemaSvc'      => $this->schemaSvc,
        ]);
    }

    // ── Cluster Page ──────────────────────────────────────
    public function cluster(array $params = []): void
    {
        $topic  = $params['topic'] ?? '';
        $slug   = $params['slug']  ?? '';

        $pillar  = $this->content->getBySlug($topic, 'pillar');
        $cluster = $this->content->getBySlug($slug, 'post');

        if (!$cluster) {
            $cluster = $this->content->getBySlug($slug, 'pillar');
        }

        if (!$cluster) {
            $this->notFound();
            return;
        }

        $seo     = $this->seoSvc->buildForContent($cluster);
        $related = $this->content->getRelated($cluster['id'], $cluster['type'], 3);
        $faqs    = $this->extractFaqs($cluster['content'] ?? '');
        $schemas = [
            $this->schemaSvc->article($cluster, $seo),
            $this->schemaSvc->breadcrumbs([
                ['name' => 'Home',              'url' => '/'],
                ['name' => 'Learn',             'url' => '/learn'],
                ['name' => $pillar['title'] ?? ucfirst($topic), 'url' => '/learn/' . $topic],
                ['name' => $cluster['title']],
            ]),
        ];
        if ($faqs) $schemas[] = $this->schemaSvc->faqPage($faqs);

        $this->view('post', [
            'seo'      => $seo,
            'post'     => $cluster,
            'pillar'   => $pillar,
            'related'  => $related,
            'faqs'     => $faqs,
            'schemas'  => $schemas,
            'schemaSvc'=> $this->schemaSvc,
        ]);
    }

    // ── News ──────────────────────────────────────────────
    public function news(array $params = []): void
    {
        $page    = $this->page();
        $perPage = POSTS_PER_PAGE;
        $posts   = $this->content->getPublished('news_article', $perPage, ($page-1)*$perPage);
        $total   = $this->content->countPublished('news_article');

        $seo = $this->seoSvc->buildStatic(
            'Digital Marketing News — Latest Industry Updates',
            'Stay updated with the latest digital marketing news, algorithm updates, platform changes, and industry trends.',
            'https://t1.techaasvik.com/news'
        );

        $this->view('news-index', [
            'seo'    => $seo,
            'posts'  => $posts,
            'total'  => $total,
            'page'   => $page,
            'perPage'=> $perPage,
        ]);
    }

    public function newsShow(array $params = []): void
    {
        $slug = $params['slug'] ?? '';
        $post = $this->content->getBySlug($slug, 'news_article');
        if (!$post) { $this->notFound(); return; }

        $seo     = $this->seoSvc->buildForContent($post);
        $schemas = [
            $this->schemaSvc->article($post, $seo, 'NewsArticle'),
            $this->schemaSvc->breadcrumbs([
                ['name' => 'Home', 'url' => '/'],
                ['name' => 'News', 'url' => '/news'],
                ['name' => $post['title']],
            ]),
        ];

        $this->view('post', ['seo' => $seo, 'post' => $post, 'schemas' => $schemas, 'related' => [], 'faqs' => []]);
    }

    // Hindi post show
    public function showHi(array $params = []): void
    {
        $slug = $params['slug'] ?? '';
        $post = $this->content->getBySlug($slug, 'post');
        if (!$post || $post['lang'] !== 'hi') { $this->notFound(); return; }

        $seo = $this->seoSvc->buildForContent($post);
        $this->view('post', ['seo' => $seo, 'post' => $post, 'related' => [], 'faqs' => [], 'schemas' => []]);
    }

    // ── Extract FAQs from post content ───────────────────
    private function extractFaqs(string $content): array
    {
        $faqs = [];
        preg_match_all('/<h[23][^>]*>(.*?)<\/h[23]>.*?<p>(.*?)<\/p>/si', $content, $matches, PREG_SET_ORDER);
        foreach (array_slice($matches, 0, 10) as $m) {
            $q = strip_tags($m[1]);
            $a = strip_tags($m[2]);
            if (str_ends_with(trim($q), '?') && strlen($a) > 30) {
                $faqs[] = ['question' => $q, 'answer' => $a];
            }
        }
        return $faqs;
    }

    // ── Courses Index ─────────────────────────────────────
    public function coursesIndex(array $params = []): void
    {
        $courses = $this->db->fetchAll(
            "SELECT * FROM content WHERE type = 'course' AND status = 'published' ORDER BY menu_order, published_at DESC"
        );
        $seo = $this->seoSvc->buildStatic(
            'Free Digital Marketing Courses — TechAasvik',
            'Free online courses on SEO, Google Ads, Meta Ads, GA4, content marketing, and AI marketing. Learn at your own pace.',
            'https://t1.techaasvik.com/courses'
        );
        $this->view('courses-index', ['seo' => $seo, 'schemas' => [], 'courses' => $courses]);
    }

    // ── Single Course ─────────────────────────────────────
    public function course(array $params = []): void
    {
        $slug   = $params['slug'] ?? '';
        $course = $this->content->getBySlug($slug, 'course');
        if (!$course) { $this->notFound(); return; }

        $modules = $this->db->fetchAll(
            "SELECT * FROM content WHERE type = 'course_module' AND parent_id = ? AND status = 'published' ORDER BY menu_order",
            [$course['id']]
        );

        $seo     = $this->seoSvc->buildForContent($course);
        $schemas = [
            $this->schemaSvc->course($course),
            $this->schemaSvc->breadcrumbs([
                ['name' => 'Home',    'url' => '/'],
                ['name' => 'Courses', 'url' => '/courses'],
                ['name' => $course['title']],
            ]),
        ];

        $this->view('post', ['seo' => $seo, 'post' => $course, 'related' => $modules, 'faqs' => [], 'schemas' => $schemas]);
    }
}

