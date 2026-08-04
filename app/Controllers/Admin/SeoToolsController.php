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

    // ── Seed Pillar Pages (temporary) ────────────────────
    public function seedPillars(array $params = []): void
    {
        Auth::requireAdmin();
        header('Content-Type: text/plain; charset=utf-8');

        try {
            $db = \Core\Database::getInstance();
            $authorId = $db->fetchColumn("SELECT id FROM authors WHERE is_active = 1 ORDER BY id ASC LIMIT 1");
            if (!$authorId) {
                echo "ERROR: No active author found.\n";
                return;
            }

            $seedFile = APP_ROOT . '/seed-pillars-data.php';
            echo "Seed file: $seedFile\n";
            echo "File exists: " . (file_exists($seedFile) ? 'YES' : 'NO') . "\n\n";

            if (!file_exists($seedFile)) {
                echo "ERROR: seed-pillars-data.php not found at APP_ROOT\n";
                return;
            }

            $pillars = require $seedFile;
            if (!is_array($pillars) || empty($pillars)) {
                echo "ERROR: seed-pillars-data.php did not return a valid array\n";
                return;
            }

            echo "Found " . count($pillars) . " pillars to seed\n\n";

            $inserted = 0;
            $skipped  = 0;

            foreach ($pillars as $p) {
                $exists = $db->fetchColumn(
                    "SELECT id FROM content WHERE slug = ? AND type = 'pillar' AND lang = 'en' LIMIT 1",
                    [$p['slug']]
                );
                if ($exists) {
                    echo "SKIP: '{$p['slug']}' already exists (ID: $exists)\n";
                    $skipped++;
                    continue;
                }

                $contentId = $db->insert('content', [
                    'type'         => 'pillar',
                    'lang'         => 'en',
                    'title'        => $p['title'],
                    'slug'         => $p['slug'],
                    'content'      => $p['content'],
                    'excerpt'      => $p['excerpt'],
                    'status'       => 'published',
                    'author_id'    => $authorId,
                    'word_count'   => $p['word_count'],
                    'read_time'    => $p['read_time'],
                    'difficulty'   => $p['difficulty'],
                    'published_at' => date('Y-m-d H:i:s'),
                ]);

                $db->insert('content_seo', [
                    'content_id'       => $contentId,
                    'meta_title'       => $p['seo_title'],
                    'meta_description' => $p['seo_desc'],
                    'canonical_url'    => "https://t1.techaasvik.com/learn/{$p['slug']}",
                    'focus_keyword'    => $p['slug'],
                ]);

                echo "OK: '{$p['slug']}' inserted (ID: $contentId)\n";
                $inserted++;
            }

            echo "\nDone! Inserted: $inserted, Skipped: $skipped\n";
        } catch (\Throwable $e) {
            echo "FATAL ERROR: " . $e->getMessage() . "\n";
            echo "File: " . $e->getFile() . " Line: " . $e->getLine() . "\n";
            echo "Trace: " . $e->getTraceAsString() . "\n";
        }
        exit;
    }

    private function getPillarData(): array
    {
        // Include the seed data file which returns the pillar array
        $seedFile = APP_ROOT . '/seed-pillars-data.php';
        if (file_exists($seedFile)) {
            return require $seedFile;
        }
        // Fallback inline data
        return [];
    }
}
