<?php
namespace Controllers;

use Core\Controller;
use Core\View;
use Models\Content;
use Services\SeoService;
use Services\SchemaService;

/**
 * Glossary Controller — handles the /glossary/* routes.
 * 2,000+ digital marketing term definitions.
 */
class GlossaryController extends Controller
{
    public function index(array $params = []): void
    {
        // A-Z index with counts
        $letters = $this->db->fetchAll(
            "SELECT UPPER(LEFT(title,1)) AS letter, COUNT(*) AS cnt
             FROM content WHERE type = 'glossary_term' AND status = 'published'
             GROUP BY letter ORDER BY letter"
        );

        $recentTerms = $this->db->fetchAll(
            "SELECT title, slug FROM content WHERE type = 'glossary_term' AND status = 'published'
             ORDER BY published_at DESC LIMIT 12"
        );

        $seoSvc = new SeoService();
        $seo    = $seoSvc->buildStatic(
            'Digital Marketing Glossary — 2000+ Terms Explained',
            'Complete digital marketing glossary with 2000+ terms explained in plain English. From A/B testing to Zero-click searches — every marketing term defined.',
            'https://t1.techaasvik.com/glossary'
        );

        $schemaSvc = new SchemaService();
        $schemas   = [$schemaSvc->breadcrumbs([
            ['name' => 'Home', 'url' => '/'],
            ['name' => 'Glossary'],
        ])];

        $this->view('glossary-index', [
            'seo'         => $seo,
            'letters'     => $letters,
            'recentTerms' => $recentTerms,
            'schemas'     => $schemas,
        ]);
    }

    public function letter(array $params = []): void
    {
        $letter = strtoupper(substr($params['letter'] ?? 'A', 0, 1));

        $terms = $this->db->fetchAll(
            "SELECT title, slug, excerpt FROM content
             WHERE type = 'glossary_term' AND status = 'published'
             AND UPPER(LEFT(title,1)) = ?
             ORDER BY title",
            [$letter]
        );

        $seoSvc = new SeoService();
        $seo    = $seoSvc->buildStatic(
            "Digital Marketing Terms Starting with '$letter' | Glossary",
            "All digital marketing terms starting with the letter $letter. Definitions and explanations for every $letter-category marketing term.",
            'https://t1.techaasvik.com/glossary/' . strtolower($letter)
        );

        $this->view('glossary-letter', [
            'seo'    => $seo,
            'letter' => $letter,
            'terms'  => $terms,
        ]);
    }

    public function show(array $params = []): void
    {
        $slug = $params['slug'] ?? '';
        $term = (new Content())->getBySlug($slug, 'glossary_term');

        if (!$term) {
            $this->notFound('Glossary term not found.');
            return;
        }

        $seoSvc    = new SeoService();
        $schemaSvc = new SchemaService();
        $seo       = $seoSvc->buildForContent($term);
        $termUrl   = 'https://t1.techaasvik.com/glossary/term/' . $term['slug'];

        $schemas = [
            $schemaSvc->definedTerm($term, $termUrl),
            $schemaSvc->breadcrumbs([
                ['name' => 'Home',     'url' => '/'],
                ['name' => 'Glossary', 'url' => '/glossary'],
                ['name' => $term['title']],
            ]),
        ];

        // Related terms (same first letter)
        $letter  = strtoupper(substr($term['title'], 0, 1));
        $related = $this->db->fetchAll(
            "SELECT title, slug FROM content
             WHERE type = 'glossary_term' AND status = 'published'
             AND UPPER(LEFT(title,1)) = ? AND id != ?
             ORDER BY RAND() LIMIT 8",
            [$letter, $term['id']]
        );

        $this->view('glossary-term', [
            'seo'     => $seo,
            'term'    => $term,
            'related' => $related,
            'schemas' => $schemas,
            'schemaSvc' => $schemaSvc,
        ]);
    }
}
