<?php
namespace Services;

use Core\Database;

/**
 * Sitemap Service — generates XML sitemaps for all content types.
 * Auto-generated on publish and via cron.
 */
class SitemapService
{
    private Database $db;
    private string   $siteUrl;
    private string   $sitemapPath;

    public function __construct()
    {
        $this->db          = Database::getInstance();
        $config            = require APP_PATH . '/Config/config.php';
        $this->siteUrl     = rtrim($config['site']['url'], '/');
        $this->sitemapPath = APP_ROOT;
    }

    // ── Master sitemap index ─────────────────────────────
    public function generateIndex(): string
    {
        $sitemaps = [
            ['loc' => $this->siteUrl . '/sitemap-pages.xml',   'lastmod' => date('Y-m-d')],
            ['loc' => $this->siteUrl . '/sitemap-posts.xml',   'lastmod' => date('Y-m-d')],
            ['loc' => $this->siteUrl . '/sitemap-glossary.xml','lastmod' => date('Y-m-d')],
            ['loc' => $this->siteUrl . '/sitemap-tools.xml',   'lastmod' => date('Y-m-d')],
            ['loc' => $this->siteUrl . '/sitemap-courses.xml', 'lastmod' => date('Y-m-d')],
        ];

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        foreach ($sitemaps as $s) {
            $xml .= "  <sitemap>\n";
            $xml .= "    <loc>{$s['loc']}</loc>\n";
            $xml .= "    <lastmod>{$s['lastmod']}</lastmod>\n";
            $xml .= "  </sitemap>\n";
        }
        $xml .= '</sitemapindex>';
        return $xml;
    }

    // ── Generate sitemap for a content type ─────────────
    public function generateForType(string $type, string $urlPrefix, string $changefreq = 'weekly', float $priority = 0.7): string
    {
        $rows = $this->db->fetchAll(
            "SELECT slug, updated_at FROM content WHERE type = ? AND status = 'published' ORDER BY updated_at DESC",
            [$type]
        );

        $xml  = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
         xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">' . "\n";

        // Homepage (for pages sitemap)
        if ($type === 'page') {
            $xml .= $this->urlEntry($this->siteUrl . '/', date('Y-m-d'), 'daily', 1.0);
        }

        foreach ($rows as $row) {
            $loc = $this->siteUrl . $urlPrefix . $row['slug'];
            $mod = date('Y-m-d', strtotime($row['updated_at']));
            $xml .= $this->urlEntry($loc, $mod, $changefreq, $priority);
        }

        $xml .= '</urlset>';
        return $xml;
    }

    // ── Pages sitemap (static + dynamic pages) ──────────
    public function generatePages(): string
    {
        $staticPages = [
            ['/', 1.0, 'daily'],
            ['/about', 0.7, 'monthly'],
            ['/contact', 0.6, 'monthly'],
            ['/services', 0.8, 'weekly'],
            ['/blog', 0.9, 'daily'],
            ['/learn', 0.9, 'weekly'],
            ['/glossary', 0.8, 'weekly'],
            ['/tools', 0.8, 'weekly'],
            ['/calculators', 0.7, 'weekly'],
            ['/templates', 0.7, 'weekly'],
            ['/case-studies', 0.8, 'weekly'],
            ['/statistics', 0.8, 'weekly'],
            ['/research', 0.7, 'weekly'],
            ['/courses', 0.8, 'weekly'],
            ['/authors', 0.6, 'monthly'],
        ];

        $xml  = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        foreach ($staticPages as [$path, $prio, $freq]) {
            $xml .= $this->urlEntry($this->siteUrl . $path, date('Y-m-d'), $freq, $prio);
        }

        // Dynamic page content
        $pages = $this->db->fetchAll(
            "SELECT slug, updated_at FROM content WHERE type = 'page' AND status = 'published'"
        );
        foreach ($pages as $p) {
            $xml .= $this->urlEntry($this->siteUrl . '/' . $p['slug'], date('Y-m-d', strtotime($p['updated_at'])), 'monthly', 0.6);
        }

        $xml .= '</urlset>';
        return $xml;
    }

    // ── Build a <url> entry ─────────────────────────────
    private function urlEntry(string $loc, string $lastmod, string $changefreq, float $priority): string
    {
        return "  <url>\n"
            . "    <loc>" . htmlspecialchars($loc) . "</loc>\n"
            . "    <lastmod>$lastmod</lastmod>\n"
            . "    <changefreq>$changefreq</changefreq>\n"
            . "    <priority>$priority</priority>\n"
            . "  </url>\n";
    }

    // ── Save sitemap to disk (for cron) ─────────────────
    public function saveAll(): void
    {
        file_put_contents($this->sitemapPath . '/sitemap.xml',          $this->generateIndex());
        file_put_contents($this->sitemapPath . '/sitemap-pages.xml',    $this->generatePages());
        file_put_contents($this->sitemapPath . '/sitemap-posts.xml',    $this->generateForType('post', '/blog/', 'weekly', 0.8));
        file_put_contents($this->sitemapPath . '/sitemap-glossary.xml', $this->generateForType('glossary_term', '/glossary/term/', 'monthly', 0.6));
        file_put_contents($this->sitemapPath . '/sitemap-tools.xml',    $this->generateForType('tool', '/tools/', 'monthly', 0.7));
        file_put_contents($this->sitemapPath . '/sitemap-courses.xml',  $this->generateForType('course', '/courses/', 'monthly', 0.8));
    }
}
