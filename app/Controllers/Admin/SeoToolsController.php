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

    // ── Seed Clusters (temp) ─────────────────────
    public function seedClusters(array $params = []): void
    {
        Auth::requireAdmin();
        header('Content-Type: text/plain; charset=utf-8');

        try {
            $db = \Core\Database::getInstance();
            $file = APP_ROOT . '/seed-clusters.php';
            if (!file_exists($file)) { echo "ERROR: seed-clusters.php not found\n"; exit; }

            $data = require $file;
            $count = 0;

            // Step 1: ALTER TABLE to add 'cluster' to ENUM if not present
            echo "=== Step 1: ALTER TABLE ===\n";
            try {
                $db->query("ALTER TABLE `content` MODIFY `type` ENUM('post','page','pillar','cluster','glossary_term','case_study','statistics','tool','calculator','template','course','course_module','course_lesson','research_report','news_article','video','podcast_episode') NOT NULL DEFAULT 'post'");
                echo "OK: Added 'cluster' to ENUM\n\n";
            } catch (\Throwable $e) {
                echo "ALTER note: " . $e->getMessage() . "\n\n";
            }

            // Step 2: Delete all broken rows (empty type) that match our slugs
            echo "=== Step 2: Clean broken rows ===\n";
            $brokenSlugs = array_column($data['clusters'], 'slug');
            foreach ($brokenSlugs as $bs) {
                $broken = $db->fetchAll("SELECT id, type, slug FROM content WHERE slug = ? AND (type = '' OR type = 'post')", [$bs]);
                foreach ($broken as $b) {
                    $db->query("DELETE FROM content WHERE id = ?", [$b['id']]);
                    echo "DELETED broken row ID:{$b['id']} slug:'{$b['slug']}' type:'{$b['type']}'\n";
                }
            }
            echo "\n";

            // Find SEO pillar ID
            $seoId = $db->fetchColumn("SELECT id FROM content WHERE slug = 'seo' AND type = 'pillar' LIMIT 1");
            if (!$seoId) { echo "ERROR: SEO pillar not found\n"; exit; }
            echo "SEO Pillar ID: $seoId\n\n";

            // Insert clusters
            foreach ($data['clusters'] as $cluster) {
                // Delete any existing row with same slug and empty type
                $oldId = $db->fetchColumn("SELECT id FROM content WHERE slug = ? AND (type IS NULL OR type = '') LIMIT 1", [$cluster['slug']]);
                if ($oldId) {
                    $db->query("DELETE FROM content WHERE id = ?", [$oldId]);
                    echo "DELETED old empty-type row ID: $oldId for '{$cluster['slug']}'\n";
                }

                // Check if cluster type already exists
                $existing = $db->fetchColumn("SELECT id FROM content WHERE slug = ? AND type = 'cluster' LIMIT 1", [$cluster['slug']]);
                if ($existing) {
                    // Update existing
                    $db->query(
                        "UPDATE content SET title = ?, excerpt = ?, content = ?, parent_id = ?, difficulty = ?, menu_order = ?, word_count = ?, read_time = ?, status = 'published', updated_at = NOW() WHERE id = ?",
                        [$cluster['title'], $cluster['excerpt'], $cluster['content'], $seoId, $cluster['difficulty'], $cluster['menu_order'], str_word_count(strip_tags($cluster['content'])), max(1, (int)ceil(str_word_count(strip_tags($cluster['content']))/200)), $existing]
                    );
                    echo "UPDATED cluster: '{$cluster['slug']}' (ID: $existing)\n";
                    $count++;
                    continue;
                }

                $wordCount = str_word_count(strip_tags($cluster['content']));
                $readTime  = max(1, (int) ceil($wordCount / 200));

                $db->insert('content', [
                    'type'         => 'cluster',
                    'title'        => $cluster['title'],
                    'slug'         => $cluster['slug'],
                    'excerpt'      => $cluster['excerpt'],
                    'content'      => $cluster['content'],
                    'parent_id'    => $seoId,
                    'difficulty'   => $cluster['difficulty'],
                    'menu_order'   => $cluster['menu_order'],
                    'word_count'   => $wordCount,
                    'read_time'    => $readTime,
                    'status'       => 'published',
                    'lang'         => 'en',
                    'author_id'    => 1,
                    'published_at' => date('Y-m-d H:i:s'),
                    'created_at'   => date('Y-m-d H:i:s'),
                    'updated_at'   => date('Y-m-d H:i:s'),
                ]);
                echo "OK cluster: '{$cluster['slug']}' inserted (words: $wordCount)\n";
                $count++;
            }

            // Insert site pages
            foreach ($data['pages'] as $page) {
                $existing = $db->fetchColumn("SELECT id FROM content WHERE slug = ? AND type = 'page' LIMIT 1", [$page['slug']]);
                if ($existing) {
                    echo "SKIP page: '{$page['slug']}' already exists (ID: $existing)\n";
                    continue;
                }

                $db->insert('content', [
                    'type'         => 'page',
                    'title'        => $page['title'],
                    'slug'         => $page['slug'],
                    'content'      => $page['content'],
                    'status'       => 'published',
                    'lang'         => 'en',
                    'published_at' => date('Y-m-d H:i:s'),
                    'created_at'   => date('Y-m-d H:i:s'),
                    'updated_at'   => date('Y-m-d H:i:s'),
                ]);
                echo "OK page: '{$page['slug']}' inserted\n";
                $count++;
            }

            echo "\nDone! Processed: $count items\n";
        } catch (\Throwable $e) {
            echo "ERROR: " . $e->getMessage() . "\n";
            echo "Trace: " . $e->getTraceAsString() . "\n";
        }
        exit;
    }
}
