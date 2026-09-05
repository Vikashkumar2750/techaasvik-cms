<?php
namespace Controllers;

use Core\Controller;
use Core\View;
use Models\Content;
use Services\SeoService;
use Services\SchemaService;

/**
 * Tool Controller — /tools/* and /calculators/* routes.
 * Powers all free SEO, content, and marketing tools.
 */
class ToolController extends Controller
{
    private Content $content;

    public function __construct()
    {
        parent::__construct();
        $this->content = new Content();
    }

    public function index(array $params = []): void
    {
        $tools = $this->content->getPublished('tool', 50, 0, 'en');
        $seoSvc = new SeoService();
        $schemaSvc = new SchemaService();

        $seo = $seoSvc->buildStatic(
            'Free Digital Marketing Tools — SEO, Ads, Content Tools',
            '50+ free digital marketing tools for SEO analysis, keyword research, ad copy generation, content optimization, schema markup, and more. No signup required.',
            'https://techaasvik.com/tools'
        );

        $schemas = [$schemaSvc->breadcrumbs([['name'=>'Home','url'=>'/'],['name'=>'Tools']])];

        $this->view('tools-index', [
            'seo'      => $seo,
            'tools'    => $tools,
            'schemas'  => $schemas,
        ]);
    }

    public function show(array $params = []): void
    {
        $slug = $params['slug'] ?? '';
        $tool = $this->content->getBySlug($slug, 'tool');
        if (!$tool) { $this->notFound('Tool not found.'); return; }

        $seoSvc    = new SeoService();
        $schemaSvc = new SchemaService();
        $seo       = $seoSvc->buildForContent($tool);

        $schemas = [
            $schemaSvc->softwareApp($tool),
            $schemaSvc->breadcrumbs([
                ['name'=>'Home','url'=>'/'],
                ['name'=>'Tools','url'=>'/tools'],
                ['name'=>$tool['title']],
            ]),
        ];

        $this->view('tool', [
            'seo'    => $seo,
            'tool'   => $tool,
            'schemas'=> $schemas,
        ]);
    }

    public function process(array $params = []): void
    {
        // AJAX endpoint — each tool has its own JS; this handles server-side processing if needed
        $slug = $params['slug'] ?? '';
        View::json(['error' => 'Tool processing not configured for: ' . $slug], 200);
    }

    public function calculators(array $params = []): void
    {
        $calcs = $this->content->getPublished('calculator', 30, 0, 'en');
        $seoSvc = new SeoService();
        $seo = $seoSvc->buildStatic(
            'Free Marketing Calculators — ROI, ROAS, CAC, LTV & More',
            'Free marketing calculators for ROI, ROAS, CAC, LTV, PPC budget, conversion rate, email revenue, and more. Make data-driven marketing decisions.',
            'https://techaasvik.com/calculators'
        );

        $this->view('calculators-index', [
            'seo'   => $seo,
            'calcs' => $calcs,
        ]);
    }

    public function calculator(array $params = []): void
    {
        $slug = $params['slug'] ?? '';
        $calc = $this->content->getBySlug($slug, 'calculator');
        if (!$calc) { $this->notFound(); return; }

        $seoSvc = new SeoService();
        $seo = $seoSvc->buildForContent($calc);
        $this->view('tool', ['seo' => $seo, 'tool' => $calc, 'schemas' => []]);
    }
}
