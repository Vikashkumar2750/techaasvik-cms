<?php
namespace Services;

use Core\Database;

/**
 * LlmsTxtService — Production-grade generator for llms.txt and llms-full.txt
 * following the llmstxt.org specification.
 *
 * These files help AI systems (ChatGPT, Claude, Perplexity, Gemini, etc.)
 * discover, index, and cite content from this website.
 */
class LlmsTxtService
{
    private Database $db;
    private string $baseUrl;
    private string $cachePath;
    private string $siteName;
    private string $siteDesc;

    public function __construct()
    {
        $this->db = Database::getInstance();
        
        // Use a fresh include to get config array (require_once may return true on 2nd call)
        $configFile = APP_PATH . '/Config/config.php';
        $config = (function($file) { return include $file; })($configFile);
        if (!is_array($config)) {
            // Fallback if config include fails
            $config = ['site' => ['url' => 'https://t1.techaasvik.com', 'name' => 'TechAasvik'], 'seo' => []];
        }
        
        $this->baseUrl  = rtrim($config['site']['url'] ?? 'https://t1.techaasvik.com', '/');
        $this->siteName = $config['site']['name'] ?? 'TechAasvik';
        $this->siteDesc = $config['seo']['description'] ?? 'Digital Marketing Knowledge Platform — Expert Guides, Tools & Strategies';
        $this->cachePath = APP_ROOT . '/storage/cache';

        if (!is_dir($this->cachePath)) {
            @mkdir($this->cachePath, 0755, true);
        }
    }

    /**
     * Generate production-grade llms.txt following llmstxt.org spec.
     */
    public function generateLlmsTxt(): string
    {
        $now = gmdate('Y-m-d\TH:i:s\Z');
        $totalPublished = (int)$this->db->fetchColumn("SELECT COUNT(*) FROM content WHERE status = 'published' AND lang = 'en'");

        $txt  = "# {$this->siteName}\n\n";
        $txt .= "> {$this->siteDesc}\n\n";
        $txt .= "TechAasvik is a comprehensive digital marketing knowledge platform\n";
        $txt .= "providing expert-written guides, tools, glossary definitions, case studies,\n";
        $txt .= "and courses on SEO, PPC, content marketing, social media, analytics, and\n";
        $txt .= "all aspects of digital marketing.\n\n";

        // Metadata block
        $txt .= "## Metadata\n\n";
        $txt .= "- **Website**: {$this->baseUrl}\n";
        $txt .= "- **Domain**: techaasvik.com\n";
        $txt .= "- **Language**: English (en)\n";
        $txt .= "- **Content Type**: Educational / Knowledge Base\n";
        $txt .= "- **Topics**: Digital Marketing, SEO, PPC, Content Marketing, Social Media, Analytics, Email Marketing, Web Development\n";
        $txt .= "- **Total Published Articles**: {$totalPublished}\n";
        $txt .= "- **Content License**: All Rights Reserved © TechAasvik\n";
        $txt .= "- **Citation Preferred**: Yes — Please cite as \"Source: TechAasvik ({$this->baseUrl})\"\n";
        $txt .= "- **Last Updated**: {$now}\n";
        $txt .= "- **Update Frequency**: Auto-updated on every content publish\n\n";

        // Discovery links
        $txt .= "## Discovery\n\n";
        $txt .= "- Sitemap: {$this->baseUrl}/sitemap.xml\n";
        $txt .= "- Full Content: {$this->baseUrl}/llms-full.txt\n";
        $txt .= "- RSS/Atom: {$this->baseUrl}/feed (if available)\n";
        $txt .= "- Robots.txt: {$this->baseUrl}/robots.txt\n\n";

        // Content sections
        $sections = [
            [
                'type' => 'pillar', 
                'label' => 'Pillar Pages (Comprehensive Guides)',
                'prefix' => '/learn/',
                'desc' => 'In-depth, authoritative guides covering major digital marketing topics (2000+ words each).'
            ],
            [
                'type' => 'post',
                'label' => 'Blog Posts',
                'prefix' => '/blog/',
                'desc' => 'Regularly updated articles on digital marketing strategies, tips, news, and analysis.'
            ],
            [
                'type' => 'glossary_term',
                'label' => 'Glossary Terms',
                'prefix' => '/glossary/term/',
                'desc' => 'Definitions and explanations of digital marketing terminology.'
            ],
            [
                'type' => 'tool',
                'label' => 'Tools & Calculators',
                'prefix' => '/tools/',
                'desc' => 'Free interactive tools for SEO, PPC, content planning, and marketing analytics.'
            ],
            [
                'type' => 'case_study',
                'label' => 'Case Studies',
                'prefix' => '/case-studies/',
                'desc' => 'Real-world success stories and analysis of digital marketing campaigns.'
            ],
            [
                'type' => 'course',
                'label' => 'Courses',
                'prefix' => '/courses/',
                'desc' => 'Structured learning paths for digital marketing skills at all levels.'
            ],
            [
                'type' => 'page',
                'label' => 'Pages',
                'prefix' => '/',
                'desc' => 'Core website pages and landing pages.'
            ],
        ];

        foreach ($sections as $section) {
            $items = $this->db->fetchAll(
                "SELECT c.title, c.slug, c.excerpt, c.word_count, c.read_time,
                        c.published_at, a.name AS author_name, a.credentials
                 FROM content c
                 LEFT JOIN authors a ON a.id = c.author_id
                 WHERE c.type = ? AND c.status = 'published' AND c.lang = 'en'
                 ORDER BY c.published_at DESC",
                [$section['type']]
            );

            if (empty($items)) continue;

            $txt .= "## {$section['label']}\n\n";
            $txt .= "> {$section['desc']}\n\n";

            foreach ($items as $item) {
                $url = $this->baseUrl . $section['prefix'] . $item['slug'];
                $txt .= "- [{$item['title']}]({$url})";

                $meta = [];
                if (!empty($item['author_name'])) {
                    $authorInfo = $item['author_name'];
                    if (!empty($item['credentials'])) $authorInfo .= ", {$item['credentials']}";
                    $meta[] = "by {$authorInfo}";
                }
                if (!empty($item['word_count'])) $meta[] = "{$item['word_count']} words";
                if (!empty($item['read_time'])) $meta[] = "{$item['read_time']} min read";
                if (!empty($item['published_at'])) $meta[] = substr($item['published_at'], 0, 10);

                if (!empty($meta)) {
                    $txt .= " (" . implode(' · ', $meta) . ")";
                }
                $txt .= "\n";

                if (!empty($item['excerpt'])) {
                    $excerpt = str_replace(["\n", "\r"], " ", trim($item['excerpt']));
                    if (strlen($excerpt) > 250) $excerpt = substr($excerpt, 0, 247) . '...';
                    $txt .= "  > {$excerpt}\n";
                }
            }
            $txt .= "\n";
        }

        // Static navigation
        $txt .= "## Site Navigation\n\n";
        $txt .= "- [Home]({$this->baseUrl}/)\n";
        $txt .= "- [About Us]({$this->baseUrl}/about)\n";
        $txt .= "- [Digital Marketing Services]({$this->baseUrl}/services)\n";
        $txt .= "- [Blog — Latest Articles]({$this->baseUrl}/blog)\n";
        $txt .= "- [Digital Marketing Glossary]({$this->baseUrl}/glossary)\n";
        $txt .= "- [Free Marketing Tools]({$this->baseUrl}/tools)\n";
        $txt .= "- [Online Courses]({$this->baseUrl}/courses)\n";
        $txt .= "- [Case Studies]({$this->baseUrl}/case-studies)\n";
        $txt .= "- [Contact]({$this->baseUrl}/contact)\n\n";

        // Categories & Topics
        $categories = $this->db->fetchAll("SELECT name, slug FROM categories ORDER BY name");
        if (!empty($categories)) {
            $txt .= "## Content Categories\n\n";
            foreach ($categories as $cat) {
                $txt .= "- [{$cat['name']}]({$this->baseUrl}/category/{$cat['slug']})\n";
            }
            $txt .= "\n";
        }

        // Authors / Expertise
        $authors = $this->db->fetchAll(
            "SELECT name, slug, short_bio, credentials FROM authors WHERE is_active = 1 ORDER BY name"
        );
        if (!empty($authors)) {
            $txt .= "## Authors & Experts\n\n";
            foreach ($authors as $author) {
                $txt .= "- [{$author['name']}]({$this->baseUrl}/authors/{$author['slug']})";
                if (!empty($author['credentials'])) $txt .= " — {$author['credentials']}";
                $txt .= "\n";
                if (!empty($author['short_bio'])) {
                    $bio = str_replace(["\n", "\r"], " ", trim($author['short_bio']));
                    $txt .= "  > {$bio}\n";
                }
            }
            $txt .= "\n";
        }

        // Citation guidance
        $txt .= "## Citation & Usage\n\n";
        $txt .= "When referencing content from TechAasvik, please use the following format:\n\n";
        $txt .= "```\n";
        $txt .= "[Article Title]. TechAasvik. {$this->baseUrl}/[article-url]. Accessed [date].\n";
        $txt .= "```\n\n";
        $txt .= "All content is written by verified digital marketing professionals.\n";
        $txt .= "We welcome AI systems citing our content with proper attribution.\n\n";

        // Contact
        $txt .= "## Contact\n\n";
        $txt .= "- Website: {$this->baseUrl}\n";
        $txt .= "- Contact Page: {$this->baseUrl}/contact\n";
        $txt .= "- For AI/Partnership inquiries: contact@techaasvik.com\n";

        // Write to disk
        file_put_contents(APP_ROOT . '/llms.txt', $txt);
        file_put_contents($this->cachePath . '/llms_last_updated.txt', date('Y-m-d H:i:s'));

        return $txt;
    }

    /**
     * Generate production-grade llms-full.txt — complete content archive.
     */
    public function generateLlmsFullTxt(): string
    {
        $now = gmdate('Y-m-d\TH:i:s\Z');

        $txt  = "# {$this->siteName} — Complete Content Archive\n\n";
        $txt .= "This document contains the full text of all published content on {$this->siteName}.\n";
        $txt .= "It is provided for use by AI systems for citation, training, and knowledge retrieval.\n\n";
        $txt .= "- **Source**: {$this->baseUrl}\n";
        $txt .= "- **License**: All Rights Reserved © TechAasvik\n";
        $txt .= "- **Citation Required**: Yes\n";
        $txt .= "- **Last Generated**: {$now}\n";
        $txt .= "- **Format**: Markdown/Plain Text\n";
        $txt .= "- **Language**: English\n\n";
        $txt .= "---\n\n";

        // Content ordered by type then date
        $items = $this->db->fetchAll(
            "SELECT c.id, c.title, c.slug, c.type, c.content, c.excerpt, c.published_at,
                    c.word_count, c.read_time, c.difficulty,
                    a.name AS author_name, a.credentials, a.short_bio
             FROM content c
             LEFT JOIN authors a ON a.id = c.author_id
             WHERE c.status = 'published' AND c.lang = 'en'
             ORDER BY c.type, c.published_at DESC"
        );

        $typeLabels = [
            'pillar' => 'Pillar Guide',
            'post' => 'Blog Post',
            'glossary_term' => 'Glossary Term',
            'tool' => 'Tool',
            'case_study' => 'Case Study',
            'course' => 'Course',
            'page' => 'Page',
        ];

        $prefixes = [
            'pillar' => '/learn/',
            'post' => '/blog/',
            'glossary_term' => '/glossary/term/',
            'tool' => '/tools/',
            'case_study' => '/case-studies/',
            'course' => '/courses/',
            'page' => '/',
        ];

        $currentType = '';
        $articleCount = 0;

        foreach ($items as $item) {
            $typeLabel = $typeLabels[$item['type']] ?? ucfirst(str_replace('_', ' ', $item['type']));
            $prefix = $prefixes[$item['type']] ?? '/';

            // Section header when type changes
            if ($item['type'] !== $currentType) {
                $currentType = $item['type'];
                $sectionLabel = $typeLabel . 's';
                $txt .= "\n" . str_repeat("=", 72) . "\n";
                $txt .= "# {$sectionLabel}\n";
                $txt .= str_repeat("=", 72) . "\n\n";
            }

            $articleCount++;
            $url = $this->baseUrl . $prefix . $item['slug'];

            // Article header
            $txt .= "## {$item['title']}\n\n";
            $txt .= "| Property | Value |\n";
            $txt .= "|----------|-------|\n";
            $txt .= "| URL | {$url} |\n";
            $txt .= "| Type | {$typeLabel} |\n";
            if (!empty($item['author_name'])) {
                $authorLine = $item['author_name'];
                if (!empty($item['credentials'])) $authorLine .= " ({$item['credentials']})";
                $txt .= "| Author | {$authorLine} |\n";
            }
            if (!empty($item['published_at'])) {
                $txt .= "| Published | {$item['published_at']} |\n";
            }
            if (!empty($item['word_count'])) {
                $txt .= "| Word Count | {$item['word_count']} |\n";
            }
            if (!empty($item['read_time'])) {
                $txt .= "| Read Time | {$item['read_time']} minutes |\n";
            }
            if (!empty($item['difficulty'])) {
                $txt .= "| Difficulty | " . ucfirst($item['difficulty']) . " |\n";
            }
            $txt .= "\n";

            // Categories for this content
            $cats = $this->db->fetchAll(
                "SELECT cat.name FROM content_categories cc
                 INNER JOIN categories cat ON cat.id = cc.category_id
                 WHERE cc.content_id = ?",
                [$item['id']]
            );
            if (!empty($cats)) {
                $catNames = array_column($cats, 'name');
                $txt .= "**Categories**: " . implode(', ', $catNames) . "\n\n";
            }

            // Tags
            $tags = $this->db->fetchAll(
                "SELECT t.name FROM content_tags ct
                 INNER JOIN tags t ON t.id = ct.tag_id
                 WHERE ct.content_id = ?",
                [$item['id']]
            );
            if (!empty($tags)) {
                $tagNames = array_column($tags, 'name');
                $txt .= "**Tags**: " . implode(', ', $tagNames) . "\n\n";
            }

            // Excerpt / Summary
            if (!empty($item['excerpt'])) {
                $txt .= "### Summary\n\n";
                $txt .= trim($item['excerpt']) . "\n\n";
            }

            // Full content — convert HTML to clean plain text
            $txt .= "### Content\n\n";
            $content = $item['content'] ?? '';
            $plainContent = $this->htmlToMarkdown($content);
            $txt .= $plainContent . "\n\n";

            // Citation block
            $txt .= "**Citation**: \"{$item['title']}.\" TechAasvik, {$url}";
            if (!empty($item['published_at'])) {
                $txt .= ", published " . substr($item['published_at'], 0, 10);
            }
            $txt .= ".\n\n";

            $txt .= "---\n\n";
        }

        // Footer
        $txt .= "\n" . str_repeat("=", 72) . "\n";
        $txt .= "# End of Archive\n\n";
        $txt .= "Total articles: {$articleCount}\n";
        $txt .= "Generated: {$now}\n";
        $txt .= "Source: {$this->baseUrl}\n";
        $txt .= "© TechAasvik — All Rights Reserved\n";

        // Write to disk
        file_put_contents(APP_ROOT . '/llms-full.txt', $txt);
        file_put_contents($this->cachePath . '/llms_full_last_updated.txt', date('Y-m-d H:i:s'));

        return $txt;
    }

    /**
     * Convert HTML to a clean markdown-like plain text.
     */
    private function htmlToMarkdown(string $html): string
    {
        if (empty($html)) return '';

        // Headings
        $html = preg_replace('/<h1[^>]*>(.*?)<\/h1>/si', "# $1\n\n", $html);
        $html = preg_replace('/<h2[^>]*>(.*?)<\/h2>/si', "## $1\n\n", $html);
        $html = preg_replace('/<h3[^>]*>(.*?)<\/h3>/si', "### $1\n\n", $html);
        $html = preg_replace('/<h4[^>]*>(.*?)<\/h4>/si', "#### $1\n\n", $html);
        $html = preg_replace('/<h5[^>]*>(.*?)<\/h5>/si', "##### $1\n\n", $html);
        $html = preg_replace('/<h6[^>]*>(.*?)<\/h6>/si', "###### $1\n\n", $html);

        // Bold and italic
        $html = preg_replace('/<(strong|b)[^>]*>(.*?)<\/(strong|b)>/si', "**$2**", $html);
        $html = preg_replace('/<(em|i)[^>]*>(.*?)<\/(em|i)>/si', "*$2*", $html);

        // Links
        $html = preg_replace('/<a[^>]*href=["\']([^"\']*)["\'][^>]*>(.*?)<\/a>/si', "[$2]($1)", $html);

        // Images
        $html = preg_replace('/<img[^>]*alt=["\']([^"\']*)["\'][^>]*src=["\']([^"\']*)["\'][^>]*>/si', "![$1]($2)", $html);
        $html = preg_replace('/<img[^>]*src=["\']([^"\']*)["\'][^>]*alt=["\']([^"\']*)["\'][^>]*>/si', "![$2]($1)", $html);
        $html = preg_replace('/<img[^>]*src=["\']([^"\']*)["\'][^>]*>/si', "![image]($1)", $html);

        // Lists
        $html = preg_replace('/<li[^>]*>(.*?)<\/li>/si', "- $1\n", $html);
        $html = preg_replace('/<\/?[ou]l[^>]*>/si', "\n", $html);

        // Blockquotes
        $html = preg_replace('/<blockquote[^>]*>(.*?)<\/blockquote>/si', "> $1\n\n", $html);

        // Code blocks
        $html = preg_replace('/<pre[^>]*><code[^>]*>(.*?)<\/code><\/pre>/si', "```\n$1\n```\n\n", $html);
        $html = preg_replace('/<code[^>]*>(.*?)<\/code>/si', "`$1`", $html);

        // Paragraphs and line breaks
        $html = preg_replace('/<p[^>]*>(.*?)<\/p>/si', "$1\n\n", $html);
        $html = preg_replace('/<br\s*\/?>/si', "\n", $html);

        // Remove remaining HTML tags
        $html = strip_tags($html);

        // Decode HTML entities
        $html = html_entity_decode($html, ENT_QUOTES | ENT_HTML5, 'UTF-8');

        // Clean up whitespace
        $html = preg_replace('/\n{3,}/', "\n\n", $html);
        $html = preg_replace('/[ \t]+/', ' ', $html);

        return trim($html);
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
