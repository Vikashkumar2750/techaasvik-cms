<?php
namespace Controllers;

use Core\Controller;
use Core\View;
use Core\Cache;
use Models\Content;
use Models\Category;
use Services\SeoService;
use Services\SchemaService;

/**
 * Home Controller — renders the platform homepage.
 */
class HomeController extends Controller
{
    public function index(array $params = []): void
    {
        $cache      = Cache::getInstance();
        $content    = new Content();
        $categories = new Category();
        $seoSvc     = new SeoService();
        $schemaSvc  = new SchemaService();

        // Latest blog posts
        $latestPosts = $cache->remember('home_posts', 900, fn() =>
            $content->getPublished('post', 6, 0, 'en')
        );

        // Pillar pages
        $pillars = $cache->remember('home_pillars', 3600, fn() =>
            $content->getPublished('pillar', 8, 0, 'en')
        );

        // Top categories with count
        $topCategories = $cache->remember('home_categories', 3600, fn() =>
            $categories->getWithCount()
        );

        // Latest case studies
        $caseStudies = $cache->remember('home_cases', 3600, fn() =>
            $content->getPublished('case_study', 3, 0, 'en')
        );

        // Latest tools
        $tools = $cache->remember('home_tools', 3600, fn() =>
            $content->getPublished('tool', 6, 0, 'en')
        );

        // Build SEO data
        $seo = $seoSvc->buildStatic(
            'TechAasvik — India\'s Digital Marketing Authority Platform',
            'India\'s most authoritative digital marketing knowledge platform. Expert guides on SEO, AEO, GEO, Google Ads, Meta Ads, Content Marketing, Analytics, and every aspect of modern digital marketing.',
            'https://t1.techaasvik.com'
        );

        // Build schema
        $schemas = [
            $schemaSvc->website(),
            $schemaSvc->organization(),
        ];

        $this->view('home', [
            'seo'           => $seo,
            'schemas'       => $schemas,
            'latestPosts'   => $latestPosts,
            'pillars'       => $pillars,
            'topCategories' => $topCategories,
            'caseStudies'   => $caseStudies,
            'tools'         => $tools,
            'schemaSvc'     => $schemaSvc,
        ]);
    }

    public function indexHi(array $params = []): void
    {
        // Hindi homepage — redirect to blog filtered by hi lang
        View::redirect('/blog?lang=hi');
    }
}
