<?php
namespace Services;

use Core\Database;

/**
 * LlmsTxtService — Generates llms.txt and llms-full.txt
 * for LLM/AI agent discoverability.
 *
 * llms.txt: Brief site index with titles, URLs, and excerpts
 * llms-full.txt: Full content dump in plain text for AI training/citation
 */
class LlmsTxtService
{
    private Database $db;
    private string $baseUrl;
    private string $cachePath;

    public function __construct()
    {
        $this->db = Database::getInstance();
        $config = require APP_PATH . '/Config/config.php';
        $this->baseUrl = rtrim($config['app']['url'] ?? 'https://t1.techaasvik.com', '/');
        $this->cachePath = APP_ROOT . '/storage/cache';

        if (!is_dir($this->cachePath)) {
            mkdir($this->cachePath, 0755, true);
        }
    }

    /**
     * Generate llms.txt — concise site index for LLMs.
     */
    public function generateLlmsTxt(): string
    {
        $config = require APP_PATH . '/Config/config.php';
        $siteName = $config['app']['name'] ?? 'TechAasvik';
        $siteDesc = $config['seo']['description'] ?? 'Digital Marketing Knowledge Platform';

        $txt  = "# {$siteName}\n\n";
        $txt .= "> {$siteDesc}\n\n";
        $txt .= "This file provides an index of all published content on {$siteName}\n";
        $txt .= "for use by large language models and AI assistants.\n\n";
        $txt .= "## Site Information\n\n";
        $txt .= "- Website: {$this->baseUrl}\n";
        $txt .= "- Sitemap: {$this->baseUrl}/sitemap.xml\n";
        $txt .= "- Full Content: {$this->baseUrl}/llms-full.txt\n";
        $txt .= "- Last Updated: " . date('Y-m-d H:i:s T') . "\n\n";

        // Content types to include
        $sections = [
            ['type' => 'pillar',        'label' => 'Pillar Pages (Comprehensive Guides)',  'prefix' => '/learn/'],
            ['type' => 'post',          'label' => 'Blog Posts',                            'prefix' => '/blog/'],
            ['type' => 'glossary_term', 'label' => 'Glossary Terms',                        'prefix' => '/glossary/term/'],
            ['type' => 'tool',          'label' => 'Tools & Calculators',                   'prefix' => '/tools/'],
            ['type' => 'case_study',    'label' => 'Case Studies',                          'prefix' => '/case-studies/'],
            ['type' => 'course',        'label' => 'Courses',                               'prefix' => '/courses/'],
            ['type' => 'page',          'label' => 'Pages',                                 'prefix' => '/'],
        ];

        foreach ($sections as $section) {
            $items = $this->db->fetchAll(
                "SELECT c.title, c.slug, c.excerpt, c.word_count, a.name AS author_name
                 FROM content c
                 LEFT JOIN authors a ON a.id = c.author_id
                 WHERE c.type = ? AND c.status = 'published' AND c.lang = 'en'
                 ORDER BY c.published_at DESC",
                [$section['type']]
            );

            if (empty($items)) continue;

            $txt .= "## {$section['label']}\n\n";
            foreach ($items as $item) {
                $url = $this->baseUrl . $section['prefix'] . $item['slug'];
                $txt .= "- [{$item['title']}]({$url})";
                if (!empty($item['author_name'])) {
                    $txt .= " — by {$item['author_name']}";
                }
                if (!empty($item['word_count'])) {
                    $txt .= " ({$item['word_count']} words)";
                }
                $txt .= "\n";
                if (!empty($item['excerpt'])) {
                    $excerpt = str_replace("\n", " ", trim($item['excerpt']));
                    if (strlen($excerpt) > 200) $excerpt = substr($excerpt, 0, 197) . '...';
                    $txt .= "  > {$excerpt}\n";
                }
            }
            $txt .= "\n";
        }

        // Static pages
        $txt .= "## Quick Links\n\n";
        $txt .= "- [Home]({$this->baseUrl}/)\n";
        $txt .= "- [Services]({$this->baseUrl}/services)\n";
        $txt .= "- [About]({$this->baseUrl}/about)\n";
        $txt .= "- [Contact]({$this->baseUrl}/contact)\n";
        $txt .= "- [Blog]({$this->baseUrl}/blog)\n";
        $txt .= "- [Glossary]({$this->baseUrl}/glossary)\n";
        $txt .= "- [Tools]({$this->baseUrl}/tools)\n";
        $txt .= "- [Courses]({$this->baseUrl}/courses)\n\n";

        // Write to disk
        file_put_contents(APP_ROOT . '/llms.txt', $txt);
        file_put_contents($this->cachePath . '/llms_last_updated.txt', date('Y-m-d H:i:s'));

        return $txt;
    }

    /**
     * Generate llms-full.txt — full content dump for AI.
     */
    public function generateLlmsFullTxt(): string
    {
        $config = require APP_PATH . '/Config/config.php';
        $siteName = $config['app']['name'] ?? 'TechAasvik';

        $txt  = "# {$siteName} — Full Content Archive\n\n";
        $txt .= "This file contains the full text of all published content on {$siteName}.\n";
        $txt .= "It is intended for use by large language models for citation and training.\n";
        $txt .= "Last Updated: " . date('Y-m-d H:i:s T') . "\n\n";
        $txt .= str_repeat("=", 72) . "\n\n";

        $items = $this->db->fetchAll(
            "SELECT c.title, c.slug, c.type, c.content, c.excerpt, c.published_at,
                    c.word_count, c.read_time, a.name AS author_name, a.credentials
             FROM content c
             LEFT JOIN authors a ON a.id = c.author_id
             WHERE c.status = 'published' AND c.lang = 'en'
             ORDER BY c.type, c.published_at DESC"
        );

        $currentType = '';
        foreach ($items as $item) {
            $typeLabel = ucfirst(str_replace('_', ' ', $item['type']));
            if ($item['type'] !== $currentType) {
                $currentType = $item['type'];
                $txt .= str_repeat("#", 2) . " {$typeLabel}s\n\n";
                $txt .= str_repeat("-", 60) . "\n\n";
            }

            $txt .= "### {$item['title']}\n\n";
            $txt .= "- Type: {$typeLabel}\n";
            $txt .= "- URL: {$this->baseUrl}/{$item['slug']}\n";
            if (!empty($item['author_name'])) {
                $txt .= "- Author: {$item['author_name']}";
                if (!empty($item['credentials'])) $txt .= " ({$item['credentials']})";
                $txt .= "\n";
            }
            if (!empty($item['published_at'])) {
                $txt .= "- Published: {$item['published_at']}\n";
            }
            if (!empty($item['word_count'])) {
                $txt .= "- Word Count: {$item['word_count']}\n";
            }
            $txt .= "\n";

            if (!empty($item['excerpt'])) {
                $txt .= "**Summary:** " . trim($item['excerpt']) . "\n\n";
            }

            // Strip HTML to plain text
            $plainContent = strip_tags($item['content'] ?? '');
            $plainContent = html_entity_decode($plainContent, ENT_QUOTES, 'UTF-8');
            $plainContent = preg_replace('/\s+/', ' ', $plainContent);
            $plainContent = wordwrap(trim($plainContent), 100, "\n");

            $txt .= $plainContent . "\n\n";
            $txt .= str_repeat("=", 72) . "\n\n";
        }

        // Write to disk
        file_put_contents(APP_ROOT . '/llms-full.txt', $txt);
        file_put_contents($this->cachePath . '/llms_full_last_updated.txt', date('Y-m-d H:i:s'));

        return $txt;
    }

    /**
     * Get last update timestamps.
     */
    public function getLastUpdated(): array
    {
        return [
            'llms'      => @file_get_contents($this->cachePath . '/llms_last_updated.txt') ?: 'Never',
            'llms_full' => @file_get_contents($this->cachePath . '/llms_full_last_updated.txt') ?: 'Never',
            'sitemap'   => @file_get_contents($this->cachePath . '/sitemap_last_updated.txt') ?: 'Never',
        ];
    }

    /**
     * Mark sitemap as regenerated.
     */
    public function markSitemapUpdated(): void
    {
        file_put_contents($this->cachePath . '/sitemap_last_updated.txt', date('Y-m-d H:i:s'));
    }
}
