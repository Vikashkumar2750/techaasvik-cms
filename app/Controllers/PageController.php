<?php
namespace Controllers;

use Core\Controller;
use Core\View;
use Models\Content;
use Models\Lead;
use Services\SeoService;
use Services\SchemaService;

/**
 * PageController — handles all static pages: About, Contact, Services, Legal, etc.
 */
class PageController extends Controller
{
    public function about(array $params = []): void
    {
        $seoSvc = new SeoService();
        $seo = $seoSvc->buildStatic(
            'About TechAasvik — India\'s Digital Marketing Authority',
            'TechAasvik is India\'s most authoritative digital marketing platform. We create research-backed guides, tools, and resources to help marketers, businesses, and agencies grow online.',
            'https://t1.techaasvik.com/about'
        );
        $this->view('about', ['seo' => $seo]);
    }

    public function contact(array $params = []): void
    {
        $seoSvc = new SeoService();
        $seo = $seoSvc->buildStatic(
            'Contact TechAasvik — Get in Touch',
            'Have a question, partnership proposal, or want a free digital marketing audit? Get in touch with the TechAasvik team.',
            'https://t1.techaasvik.com/contact'
        );
        $this->view('contact', [
            'seo'    => $seo,
            'flash'  => null,
            'errors' => [],
        ]);
    }

    public function contactPost(array $params = []): void
    {
        $errors = $this->validate([
            'name'    => 'required|max:255',
            'email'   => 'required|email',
            'message' => 'required|min:20|max:2000',
        ]);

        if ($errors) {
            $seoSvc = new SeoService();
            $seo = $seoSvc->buildStatic('Contact TechAasvik', '', 'https://t1.techaasvik.com/contact');
            $this->view('contact', ['seo' => $seo, 'flash' => null, 'errors' => $errors]);
            return;
        }

        $lead = new Lead();
        $lead->capture([
            'email'       => $this->request->post('email'),
            'name'        => $this->request->post('name'),
            'phone'       => $this->request->post('phone', ''),
            'company'     => $this->request->post('company', ''),
            'message'     => $this->request->post('message'),
            'lead_type'   => 'contact',
            'source_page' => '/contact',
        ]);

        View::redirect('/contact?sent=1');
    }

    public function services(array $params = []): void
    {
        $seoSvc = new SeoService();
        $seo = $seoSvc->buildStatic(
            'Digital Marketing Services — TechAasvik',
            'Expert digital marketing services including SEO, Google Ads, Meta Ads, content marketing, analytics, and full-funnel performance marketing for businesses in India and globally.',
            'https://t1.techaasvik.com/services'
        );
        $this->view('services', ['seo' => $seo]);
    }

    public function service(array $params = []): void
    {
        $slug = $params['slug'] ?? '';
        $page = (new Content())->getBySlug($slug, 'page');

        if (!$page) {
            // Try a static fallback
            $staticServices = ['seo', 'google-ads', 'meta-ads', 'content-marketing', 'analytics'];
            if (!in_array($slug, $staticServices)) {
                $this->notFound();
                return;
            }
        }

        $seoSvc = new SeoService();
        $seo    = $page
            ? $seoSvc->buildForContent($page)
            : $seoSvc->buildStatic(ucwords(str_replace('-', ' ', $slug)) . ' Services | TechAasvik', '', 'https://t1.techaasvik.com/services/' . $slug);

        $this->view('service', ['seo' => $seo, 'page' => $page, 'slug' => $slug]);
    }

    public function htmlSitemap(array $params = []): void
    {
        $seoSvc = new SeoService();
        $seo = $seoSvc->buildStatic('Site Map — TechAasvik', 'Complete site map of TechAasvik digital marketing platform.');
        $allContent = $this->db->fetchAll(
            "SELECT type, title, slug FROM content WHERE status = 'published' ORDER BY type, title"
        );
        $this->view('sitemap-html', ['seo' => $seo, 'allContent' => $allContent]);
    }

    public function privacy(array $params = []): void {
        $seoSvc = new SeoService();
        $seo = $seoSvc->buildStatic('Privacy Policy — TechAasvik', 'How TechAasvik collects, uses, and protects your personal information.');
        $seo['noindex'] = true;
        $this->view('legal', ['seo' => $seo, 'pageType' => 'privacy', 'schemas' => []]);
    }

    public function terms(array $params = []): void {
        $seoSvc = new SeoService();
        $seo = $seoSvc->buildStatic('Terms of Service — TechAasvik', 'Terms of service for using TechAasvik platform, tools, and services.');
        $seo['noindex'] = true;
        $this->view('legal', ['seo' => $seo, 'pageType' => 'terms', 'schemas' => []]);
    }

    public function editorial(array $params = []): void {
        $seoSvc = new SeoService();
        $seo = $seoSvc->buildStatic('Editorial Policy — TechAasvik', 'Our editorial standards, fact-checking process, author guidelines, and content integrity policy.');
        $this->view('legal', ['seo' => $seo, 'pageType' => 'editorial', 'schemas' => []]);
    }

    public function disclaimer(array $params = []): void {
        $seoSvc = new SeoService();
        $seo = $seoSvc->buildStatic('Disclaimer — TechAasvik', 'Important disclaimer about the information provided on TechAasvik.');
        $seo['noindex'] = true;
        $this->view('legal', ['seo' => $seo, 'pageType' => 'disclaimer', 'schemas' => []]);
    }

    /**
     * Unified legal() method — detects page type from request URI.
     */
    public function legal(array $params = []): void
    {
        $uri = ltrim(parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH), '/');
        $typeMap = [
            'privacy-policy'   => 'privacy',
            'terms-of-service' => 'terms',
            'editorial-policy' => 'editorial',
            'disclaimer'       => 'disclaimer',
        ];
        $pageType = $typeMap[$uri] ?? 'privacy';
        $method   = $pageType === 'editorial' ? 'editorial' : $pageType;
        $this->$method($params);
    }
}

