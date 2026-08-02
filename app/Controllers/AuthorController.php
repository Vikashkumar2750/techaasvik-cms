<?php
namespace Controllers;

use Core\Controller;
use Models\Content;
use Models\Lead;
use Services\SeoService;
use Services\SchemaService;

class AuthorController extends Controller
{
    public function index(array $params = []): void
    {
        $authors = $this->db->fetchAll(
            "SELECT a.*, COUNT(c.id) AS post_count FROM authors a
             LEFT JOIN content c ON c.author_id = a.id AND c.status = 'published'
             WHERE a.is_active = 1 GROUP BY a.id ORDER BY post_count DESC"
        );
        $seoSvc = new SeoService();
        $seo = $seoSvc->buildStatic(
            'Our Expert Authors — Digital Marketing Specialists',
            'Meet TechAasvik\'s team of certified digital marketing experts, SEO specialists, and content strategists who create authoritative guides and research.',
            'https://t1.techaasvik.com/authors'
        );
        $this->view('authors-index', ['seo' => $seo, 'authors' => $authors]);
    }

    public function show(array $params = []): void
    {
        $slug   = $params['slug'] ?? '';
        $author = $this->db->fetchOne("SELECT * FROM authors WHERE slug = ? AND is_active = 1 LIMIT 1", [$slug]);
        if (!$author) { $this->notFound('Author not found.'); return; }

        $page    = $this->page();
        $perPage = 12;
        $posts   = $this->db->fetchAll(
            "SELECT id, type, title, slug, excerpt, published_at, featured_image_id
             FROM content WHERE author_id = ? AND status = 'published'
             ORDER BY published_at DESC LIMIT ? OFFSET ?",
            [$author['id'], $perPage, ($page - 1) * $perPage]
        );
        $total = (int)$this->db->fetchColumn("SELECT COUNT(*) FROM content WHERE author_id = ? AND status = 'published'", [$author['id']]);

        $seoSvc    = new SeoService();
        $schemaSvc = new SchemaService();
        $seo = $seoSvc->buildStatic(
            $author['name'] . ' — Digital Marketing Expert at TechAasvik',
            $author['short_bio'] ?? ('Articles and guides by ' . $author['name'] . ', digital marketing expert at TechAasvik.'),
            'https://t1.techaasvik.com/authors/' . $author['slug']
        );
        $schemas = [
            $schemaSvc->person($author),
            $schemaSvc->breadcrumbs([['name'=>'Home','url'=>'/'],['name'=>'Authors','url'=>'/authors'],['name'=>$author['name']]]),
        ];

        $this->view('author', ['seo' => $seo, 'author' => $author, 'posts' => $posts, 'total' => $total, 'page' => $page, 'perPage' => $perPage, 'schemas' => $schemas]);
    }
}

// ─────────────────────────────────────────────────────────────
// File: app/Controllers/SearchController.php
// ─────────────────────────────────────────────────────────────
namespace Controllers;

use Core\Controller;
use Models\Content;
use Services\SeoService;

class SearchController extends Controller
{
    public function index(array $params = []): void
    {
        $query   = trim($this->request->get('q', ''));
        $page    = $this->page();
        $perPage = SEARCH_PER_PAGE;
        $results = [];
        $total   = 0;

        if (strlen($query) >= 2) {
            $content = new Content();
            $results = $content->search($query, $perPage, ($page - 1) * $perPage);
            $total   = (int)$this->db->fetchColumn(
                "SELECT COUNT(*) FROM content WHERE status = 'published'
                 AND (title LIKE ? OR excerpt LIKE ?)",
                ['%'.$query.'%', '%'.$query.'%']
            );
        }

        $seoSvc = new SeoService();
        $seo = $seoSvc->buildStatic(
            $query ? "Search: \"$query\" — TechAasvik" : 'Search — TechAasvik',
            'Search across thousands of digital marketing guides, tutorials, glossary terms, tools, and resources on TechAasvik.',
            'https://t1.techaasvik.com/search'
        );
        $seo['noindex'] = true; // Search pages should not be indexed

        $this->view('search', ['seo' => $seo, 'query' => $query, 'results' => $results, 'total' => $total, 'page' => $page, 'perPage' => $perPage]);
    }
}
