<?php
namespace Controllers\Admin;

use Core\Controller;
use Core\Auth;
use Core\View;
use Core\Database;

/**
 * Admin SEO Tools Controller — SEO audit, sitemap, robots.txt management.
 */
class SeoToolsController extends Controller
{
    public function index(array $params = []): void
    {
        Auth::requireAdmin();

        $db = Database::getInstance();

        // Content without meta title
        $missingTitle = $db->fetchAll(
            "SELECT c.id, c.type, c.title, c.slug, c.status
             FROM content c
             LEFT JOIN content_seo s ON s.content_id = c.id
             WHERE c.status = 'published'
             AND (s.meta_title IS NULL OR s.meta_title = '')
             ORDER BY c.updated_at DESC LIMIT 50"
        );

        // Content without meta description
        $missingDesc = $db->fetchAll(
            "SELECT c.id, c.type, c.title, c.slug, c.status
             FROM content c
             LEFT JOIN content_seo s ON s.content_id = c.id
             WHERE c.status = 'published'
             AND (s.meta_description IS NULL OR s.meta_description = '')
             ORDER BY c.updated_at DESC LIMIT 50"
        );

        // Content with noindex
        $noindexed = $db->fetchAll(
            "SELECT c.id, c.type, c.title, c.slug
             FROM content c
             INNER JOIN content_seo s ON s.content_id = c.id
             WHERE s.noindex = 1
             ORDER BY c.title LIMIT 50"
        );

        // Content without excerpt
        $missingExcerpt = $db->fetchAll(
            "SELECT c.id, c.type, c.title, c.slug
             FROM content c
             WHERE c.status = 'published'
             AND (c.excerpt IS NULL OR c.excerpt = '')
             ORDER BY c.updated_at DESC LIMIT 50"
        );

        // SEO stats
        $stats = [
            'total_published'   => (int)$db->fetchColumn("SELECT COUNT(*) FROM content WHERE status = 'published'"),
            'with_meta_title'   => (int)$db->fetchColumn("SELECT COUNT(*) FROM content c INNER JOIN content_seo s ON s.content_id = c.id WHERE c.status = 'published' AND s.meta_title IS NOT NULL AND s.meta_title != ''"),
            'with_meta_desc'    => (int)$db->fetchColumn("SELECT COUNT(*) FROM content c INNER JOIN content_seo s ON s.content_id = c.id WHERE c.status = 'published' AND s.meta_description IS NOT NULL AND s.meta_description != ''"),
            'with_excerpt'      => (int)$db->fetchColumn("SELECT COUNT(*) FROM content WHERE status = 'published' AND excerpt IS NOT NULL AND excerpt != ''"),
            'noindexed'         => count($noindexed),
        ];

        View::admin('seo/index', [
            'pageTitle'      => 'SEO Tools',
            'stats'          => $stats,
            'missingTitle'   => $missingTitle,
            'missingDesc'    => $missingDesc,
            'missingExcerpt' => $missingExcerpt,
            'noindexed'      => $noindexed,
            'flash'          => $this->getFlash(),
        ]);
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
            // Truncate to 60 chars
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
}
