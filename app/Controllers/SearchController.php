<?php
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
