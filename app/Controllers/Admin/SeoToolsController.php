<?php
namespace Controllers\Admin;

use Core\Controller;
use Core\Auth;
use Core\View;
use Core\Database;
use Services\SitemapService;
use Services\LlmsTxtService;

/**
 * Admin SEO Tools Controller — SEO audit, sitemap, robots.txt, llms.txt management.
 */
class SeoToolsController extends Controller
{
    public function index(array $params = []): void
    {
        Auth::requireAdmin();

        $db = Database::getInstance();

        $missingTitle = $db->fetchAll(
            "SELECT c.id, c.type, c.title, c.slug, c.status
             FROM content c
             LEFT JOIN content_seo s ON s.content_id = c.id
             WHERE c.status = 'published'
             AND (s.meta_title IS NULL OR s.meta_title = '')
             ORDER BY c.updated_at DESC LIMIT 50"
        );

        $missingDesc = $db->fetchAll(
            "SELECT c.id, c.type, c.title, c.slug, c.status
             FROM content c
             LEFT JOIN content_seo s ON s.content_id = c.id
             WHERE c.status = 'published'
             AND (s.meta_description IS NULL OR s.meta_description = '')
             ORDER BY c.updated_at DESC LIMIT 50"
        );

        $noindexed = $db->fetchAll(
            "SELECT c.id, c.type, c.title, c.slug
             FROM content c
             INNER JOIN content_seo s ON s.content_id = c.id
             WHERE s.noindex = 1
             ORDER BY c.title LIMIT 50"
        );

        $missingExcerpt = $db->fetchAll(
            "SELECT c.id, c.type, c.title, c.slug
             FROM content c
             WHERE c.status = 'published'
             AND (c.excerpt IS NULL OR c.excerpt = '')
             ORDER BY c.updated_at DESC LIMIT 50"
        );

        $stats = [
            'total_published'   => (int)$db->fetchColumn("SELECT COUNT(*) FROM content WHERE status = 'published'"),
            'with_meta_title'   => (int)$db->fetchColumn("SELECT COUNT(*) FROM content c INNER JOIN content_seo s ON s.content_id = c.id WHERE c.status = 'published' AND s.meta_title IS NOT NULL AND s.meta_title != ''"),
            'with_meta_desc'    => (int)$db->fetchColumn("SELECT COUNT(*) FROM content c INNER JOIN content_seo s ON s.content_id = c.id WHERE c.status = 'published' AND s.meta_description IS NOT NULL AND s.meta_description != ''"),
            'with_excerpt'      => (int)$db->fetchColumn("SELECT COUNT(*) FROM content WHERE status = 'published' AND excerpt IS NOT NULL AND excerpt != ''"),
            'noindexed'         => count($noindexed),
        ];

        // Last update timestamps
        $llmsService = new LlmsTxtService();
        $lastUpdated = $llmsService->getLastUpdated();
        $fileStatus = [
            'sitemap'   => true,
            'llms'      => file_exists(APP_ROOT . '/llms.txt'),
            'llms_full' => file_exists(APP_ROOT . '/llms-full.txt'),
        ];

        View::admin('seo/index', [
            'pageTitle'      => 'SEO Tools',
            'stats'          => $stats,
            'missingTitle'   => $missingTitle,
            'missingDesc'    => $missingDesc,
            'missingExcerpt' => $missingExcerpt,
            'noindexed'      => $noindexed,
            'lastUpdated'    => $lastUpdated,
            'fileStatus'     => $fileStatus,
            'flash'          => $this->getFlash(),
        ]);
    }

    // ── Regenerate Sitemap ───────────────────────────────
    public function regenerateSitemap(array $params = []): void
    {
        Auth::requireAdmin();
        $this->verifyCsrf();

        $svc = new SitemapService();
        file_put_contents(APP_ROOT . '/sitemap.xml', $svc->generateIndex());
        file_put_contents(APP_ROOT . '/sitemap-posts.xml', $svc->generateForType('post', '/blog/', 'weekly', 0.8));
        file_put_contents(APP_ROOT . '/sitemap-pages.xml', $svc->generatePages());
        file_put_contents(APP_ROOT . '/sitemap-glossary.xml', $svc->generateForType('glossary_term', '/glossary/term/', 'monthly', 0.6));
        file_put_contents(APP_ROOT . '/sitemap-tools.xml', $svc->generateForType('tool', '/tools/', 'monthly', 0.7));
        file_put_contents(APP_ROOT . '/sitemap-courses.xml', $svc->generateForType('course', '/courses/', 'monthly', 0.8));

        $llms = new LlmsTxtService();
        $llms->markSitemapUpdated();

        $this->flash('success', '🗺️ Sitemap regenerated successfully!');
        View::redirect('/techaasvik_admin/seo');
    }

    // ── Regenerate llms.txt ──────────────────────────────
    public function regenerateLlms(array $params = []): void
    {
        Auth::requireAdmin();
        $this->verifyCsrf();

        $svc = new LlmsTxtService();
        $svc->generateLlmsTxt();

        $this->flash('success', '🤖 llms.txt regenerated successfully!');
        View::redirect('/techaasvik_admin/seo');
    }

    // ── Regenerate llms-full.txt ─────────────────────────
    public function regenerateLlmsFull(array $params = []): void
    {
        Auth::requireAdmin();
        $this->verifyCsrf();

        $svc = new LlmsTxtService();
        $svc->generateLlmsFullTxt();

        $this->flash('success', '📄 llms-full.txt regenerated successfully!');
        View::redirect('/techaasvik_admin/seo');
    }

    // ── Regenerate ALL ───────────────────────────────────
    public function regenerateAll(array $params = []): void
    {
        Auth::requireAdmin();
        $this->verifyCsrf();

        $errors = [];

        // Sitemap
        try {
            $svc = new SitemapService();
            @file_put_contents(APP_ROOT . '/sitemap.xml', $svc->generateIndex());
            @file_put_contents(APP_ROOT . '/sitemap-posts.xml', $svc->generateForType('post', '/blog/', 'weekly', 0.8));
            @file_put_contents(APP_ROOT . '/sitemap-pages.xml', $svc->generatePages());
            @file_put_contents(APP_ROOT . '/sitemap-glossary.xml', $svc->generateForType('glossary_term', '/glossary/term/', 'monthly', 0.6));
            @file_put_contents(APP_ROOT . '/sitemap-tools.xml', $svc->generateForType('tool', '/tools/', 'monthly', 0.7));
            @file_put_contents(APP_ROOT . '/sitemap-courses.xml', $svc->generateForType('course', '/courses/', 'monthly', 0.8));
        } catch (\Throwable $e) {
            $errors[] = 'Sitemap: ' . $e->getMessage();
        }

        // LLMs
        try {
            $llms = new LlmsTxtService();
            $llms->generateLlmsTxt();
            $llms->generateLlmsFullTxt();
            $llms->markSitemapUpdated();
        } catch (\Throwable $e) {
            $errors[] = 'LLMs: ' . $e->getMessage();
        }

        if (empty($errors)) {
            $this->flash('success', '🚀 All regenerated: sitemap.xml, llms.txt, llms-full.txt');
        } else {
            $this->flash('error', '⚠️ Partial success. Errors: ' . implode(' | ', $errors));
        }
        View::redirect('/techaasvik_admin/seo');
    }

    // ── Bulk generate meta titles ────────────────────────
    public function generateTitles(array $params = []): void
    {
        Auth::requireAdmin();
        $this->verifyCsrf();

        $db    = Database::getInstance();
        $rows  = $db->fetchAll(
            "SELECT c.id, c.title, c.type
             FROM content c
             LEFT JOIN content_seo s ON s.content_id = c.id
             WHERE c.status = 'published'
             AND (s.meta_title IS NULL OR s.meta_title = '')"
        );

        $config  = require APP_PATH . '/Config/config.php';
        $suffix  = $config['seo']['title_suffix'] ?? 'TechAasvik';
        $sep     = $config['seo']['title_separator'] ?? ' | ';
        $count   = 0;

        foreach ($rows as $row) {
            $metaTitle = $row['title'] . $sep . $suffix;
            if (strlen($metaTitle) > 60) {
                $metaTitle = substr($row['title'], 0, 60 - strlen($sep . $suffix)) . $sep . $suffix;
            }

            $existing = $db->fetchOne("SELECT id FROM content_seo WHERE content_id = ?", [$row['id']]);
            if ($existing) {
                $db->update('content_seo', ['meta_title' => $metaTitle], 'id = ?', [$existing['id']]);
            } else {
                $db->insert('content_seo', ['content_id' => $row['id'], 'meta_title' => $metaTitle]);
            }
            $count++;
        }

        $this->flash('success', "Auto-generated meta titles for {$count} content items.");
        View::redirect('/techaasvik_admin/seo');
    }

    // ── Bulk generate meta descriptions ──────────────────
    public function generateDescriptions(array $params = []): void
    {
        Auth::requireAdmin();
        $this->verifyCsrf();

        $db   = Database::getInstance();
        $rows = $db->fetchAll(
            "SELECT c.id, c.excerpt, c.content
             FROM content c
             LEFT JOIN content_seo s ON s.content_id = c.id
             WHERE c.status = 'published'
             AND (s.meta_description IS NULL OR s.meta_description = '')"
        );

        $count = 0;
        foreach ($rows as $row) {
            $text = $row['excerpt'] ?: strip_tags($row['content'] ?? '');
            $text = preg_replace('/\s+/', ' ', trim($text));
            if (strlen($text) > 155) {
                $text = rtrim(substr($text, 0, 152)) . '...';
            }
            if (empty($text)) continue;

            $existing = $db->fetchOne("SELECT id FROM content_seo WHERE content_id = ?", [$row['id']]);
            if ($existing) {
                $db->update('content_seo', ['meta_description' => $text], 'id = ?', [$existing['id']]);
            } else {
                $db->insert('content_seo', ['content_id' => $row['id'], 'meta_description' => $text]);
            }
            $count++;
        }

        $this->flash('success', "Auto-generated meta descriptions for {$count} content items.");
        View::redirect('/techaasvik_admin/seo');
    }

    // ── Fix Content (one-time migration) ─────────────────
    public function fixContent(array $params = []): void
    {
        Auth::requireAdmin();
        header('Content-Type: text/plain; charset=utf-8');
        $db = \Core\Database::getInstance();
        $fixes = 0;

        // Fix SEO Glossary
        $row = $db->fetchOne("SELECT id FROM content WHERE slug = 'seo-glossary' AND type = 'glossary_term' LIMIT 1");
        if ($row) {
            $html = '<h2 id="section-1">What is SEO?</h2>'
                . '<p>Search Engine Optimization (SEO) is the process of improving a website\'s visibility in organic (non-paid) search engine results. SEO involves optimizing content, HTML source code, and the site\'s authority to help search engines understand and rank pages for relevant queries.</p>'
                . '<p>In 2026, SEO has expanded beyond traditional link-based ranking. Google now evaluates <strong>content quality, user experience, entity relevance, and E-E-A-T signals</strong> (Experience, Expertise, Authoritativeness, Trustworthiness).</p>'
                . '<h2 id="section-2">Types of SEO</h2>'
                . '<h3>On-Page SEO</h3><p>Optimizing content, title tags, meta descriptions, headings, and internal links on individual pages to improve relevance.</p>'
                . '<h3>Off-Page SEO</h3><p>Building backlinks and external signals that increase a site\'s authority — link building, digital PR, brand mentions, social signals.</p>'
                . '<h3>Technical SEO</h3><p>Improving site speed, mobile-friendliness, crawlability, and indexation. Covers Core Web Vitals, structured data, XML sitemaps.</p>'
                . '<h3>Local SEO</h3><p>Optimizing for location-based searches — Google Business Profile, NAP consistency, local citations, review management.</p>'
                . '<h3>Voice Search SEO</h3><p>Optimizing content for voice queries from smart speakers and mobile assistants — conversational keywords, FAQ schema.</p>'
                . '<h2 id="section-3">How Search Engines Work</h2>'
                . '<p>Search engines operate in three phases:</p>'
                . '<ol><li><strong>Crawling:</strong> Bots (like Googlebot) discover pages by following links and sitemaps</li>'
                . '<li><strong>Indexing:</strong> Pages are analyzed, processed, and stored in the search index</li>'
                . '<li><strong>Ranking:</strong> Algorithms determine which pages best match a user\'s query</li></ol>'
                . '<p>Google uses over <strong>200 ranking factors</strong> including content relevance, backlink quality, page experience, E-E-A-T signals.</p>'
                . '<h2 id="section-4">Key SEO Ranking Factors in 2026</h2>'
                . '<ul><li><strong>Content Quality &amp; Depth:</strong> Comprehensive content satisfying search intent</li>'
                . '<li><strong>E-E-A-T:</strong> Demonstrated experience, expertise, authoritativeness, trustworthiness</li>'
                . '<li><strong>Backlinks:</strong> Quality links from authoritative domains</li>'
                . '<li><strong>Core Web Vitals:</strong> LCP, INP, and CLS performance metrics</li>'
                . '<li><strong>Mobile Experience:</strong> Mobile-first indexing</li>'
                . '<li><strong>Structured Data:</strong> Schema markup for rich results</li></ul>'
                . '<h2 id="section-5">SEO Best Practices</h2>'
                . '<ul><li>Create high-quality, original content addressing user search intent</li>'
                . '<li>Optimize title tags and meta descriptions for CTR</li>'
                . '<li>Use proper heading hierarchy (H1, H2, H3)</li>'
                . '<li>Build strong internal linking architecture</li>'
                . '<li>Earn quality backlinks through digital PR</li>'
                . '<li>Ensure fast page load and strong Core Web Vitals</li>'
                . '<li>Implement structured data markup</li>'
                . '<li>Monitor with Google Search Console and analytics</li></ul>'
                . '<h2 id="section-6">SEO vs SEM vs PPC</h2>'
                . '<p><strong>SEO</strong> focuses on organic (unpaid) visibility. <strong>SEM</strong> (Search Engine Marketing) encompasses SEO + paid search. <strong>PPC</strong> (Pay-Per-Click) is paid advertising where you pay per click.</p>'
                . '<p>While PPC provides immediate traffic, SEO builds sustainable long-term visibility. The best strategies combine both.</p>';

            $wc = str_word_count(strip_tags($html));
            $db->query("UPDATE content SET content = ?, word_count = ?, read_time = ?, updated_at = NOW() WHERE id = ?",
                [$html, $wc, max(1, (int)ceil($wc / 200)), $row['id']]);
            echo "FIXED seo-glossary (ID: {$row['id']}, words: $wc)\n";
            $fixes++;
        }

        echo "\nDone! Fixed $fixes items.\n";
        exit;
    }
}
