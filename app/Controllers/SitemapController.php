<?php
namespace Controllers;

use Core\Controller;
use Core\View;
use Services\SitemapService;

/**
 * Sitemap Controller — serves XML sitemaps dynamically.
 */
class SitemapController extends Controller
{
    private SitemapService $svc;

    public function __construct()
    {
        parent::__construct();
        $this->svc = new SitemapService();
    }

    private function serve(string $xml): void
    {
        header('Content-Type: application/xml; charset=UTF-8');
        header('Cache-Control: public, max-age=86400');
        echo $xml;
        exit;
    }

    public function index(array $params = []): void
    {
        $this->serve($this->svc->generateIndex());
    }

    public function pages(array $params = []): void
    {
        $this->serve($this->svc->generatePages());
    }

    public function posts(array $params = []): void
    {
        $this->serve($this->svc->generateForType('post', '/blog/', 'weekly', 0.8));
    }

    public function glossary(array $params = []): void
    {
        $this->serve($this->svc->generateForType('glossary_term', '/glossary/term/', 'monthly', 0.6));
    }

    public function tools(array $params = []): void
    {
        $this->serve($this->svc->generateForType('tool', '/tools/', 'monthly', 0.7));
    }

    public function courses(array $params = []): void
    {
        $this->serve($this->svc->generateForType('course', '/courses/', 'monthly', 0.8));
    }
}
