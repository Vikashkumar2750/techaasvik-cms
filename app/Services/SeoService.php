<?php
namespace Services;

use Core\Database;

/**
 * SEO Service — generates all meta tags, Open Graph, Twitter Cards,
 * and canonical URLs for every page type automatically.
 */
class SeoService
{
    private Database $db;
    private array $config;

    public function __construct()
    {
        $this->db     = Database::getInstance();
        $this->config = require APP_PATH . '/Config/config.php';
    }

    // ── Build full SEO data for a content item ────────────
    public function buildForContent(array $content): array
    {
        $seo = $this->db->fetchOne(
            "SELECT * FROM content_seo WHERE content_id = ?",
            [$content['id']]
        );

        $title       = $seo['meta_title']       ?? $content['title'];
        $description = $seo['meta_description'] ?? ($content['excerpt'] ?? '');
        $canonical   = $seo['canonical_url']    ?? $this->buildCanonical($content);
        $ogImage     = $seo['og_image']         ?? $this->config['site']['og_image'];

        return [
            'meta_title'       => $this->buildTitle($title),
            'meta_description' => $this->truncateDescription($description),
            'canonical'        => $canonical,
            'noindex'          => (bool)($seo['noindex'] ?? false),
            'nofollow'         => (bool)($seo['nofollow'] ?? false),
            'og_title'         => $seo['og_title']       ?? $title,
            'og_description'   => $seo['og_description'] ?? $description,
            'og_image'         => $ogImage,
            'og_type'          => $this->getOgType($content['type']),
            'og_url'           => $canonical,
            'twitter_card'     => 'summary_large_image',
            'twitter_title'    => $seo['og_title']       ?? $title,
            'twitter_image'    => $ogImage,
            'schema_type'      => $seo['schema_type']    ?? $this->defaultSchemaType($content['type']),
            'schema_json'      => $seo['schema_json']    ?? null,
            'published_at'     => $content['published_at'] ?? null,
            'modified_at'      => $content['updated_at']  ?? null,
            'author'           => $content['author_name'] ?? null,
        ];
    }

    // ── Build SEO data for static/non-content pages ───────
    public function buildStatic(
        string $title,
        string $description = '',
        string $canonical   = '',
        string $ogImage     = ''
    ): array {
        return [
            'meta_title'       => $this->buildTitle($title),
            'meta_description' => $this->truncateDescription($description ?: $this->config['seo']['default_meta_desc']),
            'canonical'        => $canonical ?: $this->config['site']['url'] . $_SERVER['REQUEST_URI'],
            'noindex'          => false,
            'nofollow'         => false,
            'og_title'         => $title,
            'og_description'   => $description,
            'og_image'         => $ogImage ?: $this->config['site']['og_image'],
            'og_type'          => 'website',
            'og_url'           => $canonical,
            'twitter_card'     => 'summary_large_image',
            'twitter_title'    => $title,
            'twitter_image'    => $ogImage ?: $this->config['site']['og_image'],
            'schema_type'      => 'WebPage',
            'schema_json'      => null,
        ];
    }

    // ── Build <title> tag ─────────────────────────────────
    public function buildTitle(string $title): string
    {
        $suffix = $this->config['seo']['title_suffix']    ?? 'TechAasvik';
        $sep    = $this->config['seo']['title_separator'] ?? ' | ';
        $title  = trim($title);

        // Don't double-append suffix
        if (str_ends_with($title, $suffix)) {
            return $title;
        }

        return $title . $sep . $suffix;
    }

    // ── Canonical URL ─────────────────────────────────────
    private function buildCanonical(array $content): string
    {
        $base = rtrim($this->config['site']['url'], '/');
        $slug = $content['slug'];
        $type = $content['type'];

        // Cluster pages need parent pillar slug
        if ($type === 'cluster' && !empty($content['parent_id'])) {
            $parentSlug = $this->db->fetchColumn(
                "SELECT slug FROM content WHERE id = ? LIMIT 1",
                [$content['parent_id']]
            );
            if ($parentSlug) {
                return $base . '/learn/' . $parentSlug . '/' . $slug;
            }
        }

        $typeToPath = [
            'post'            => '/blog/',
            'pillar'          => '/learn/',
            'cluster'         => '/learn/',
            'glossary_term'   => '/glossary/term/',
            'case_study'      => '/case-studies/',
            'statistics'      => '/statistics/',
            'tool'            => '/tools/',
            'calculator'      => '/calculators/',
            'template'        => '/templates/',
            'course'          => '/courses/',
            'research_report' => '/research/',
            'news_article'    => '/news/',
            'video'           => '/videos/',
            'podcast_episode' => '/podcast/',
        ];

        $path = $typeToPath[$type] ?? '/';
        return $base . $path . $slug;
    }

    // ── OG type by content type ───────────────────────────
    private function getOgType(string $type): string
    {
        return match($type) {
            'post', 'pillar', 'cluster', 'news_article' => 'article',
            'course'                         => 'website',
            'video'                          => 'video.other',
            default                          => 'website',
        };
    }

    // ── Default schema type ───────────────────────────────
    private function defaultSchemaType(string $contentType): string
    {
        return match($contentType) {
            'post'            => 'BlogPosting',
            'pillar'          => 'Article',
            'cluster'         => 'Article',
            'news_article'    => 'NewsArticle',
            'glossary_term'   => 'DefinedTerm',
            'course'          => 'Course',
            'tool'            => 'SoftwareApplication',
            'calculator'      => 'SoftwareApplication',
            'case_study'      => 'Article',
            'research_report' => 'Report',
            'statistics'      => 'Dataset',
            'video'           => 'VideoObject',
            'podcast_episode' => 'PodcastEpisode',
            default           => 'WebPage',
        };
    }

    // ── Truncate description to 160 chars ──────────────────
    private function truncateDescription(string $text): string
    {
        $text = strip_tags($text);
        $text = preg_replace('/\s+/', ' ', $text);
        $text = trim($text);

        if (strlen($text) <= 160) return $text;

        return rtrim(substr($text, 0, 157)) . '...';
    }

    // ── Save SEO data from admin ──────────────────────────
    public function saveSeo(int $contentId, array $seoData): void
    {
        $existing = $this->db->fetchOne(
            "SELECT id FROM content_seo WHERE content_id = ?",
            [$contentId]
        );

        $data = [
            'meta_title'       => $seoData['meta_title']       ?? null,
            'meta_description' => $seoData['meta_description'] ?? null,
            'canonical_url'    => $seoData['canonical_url']    ?? null,
            'og_title'         => $seoData['og_title']         ?? null,
            'og_description'   => $seoData['og_description']   ?? null,
            'og_image'         => $seoData['og_image']         ?? null,
            'schema_type'      => $seoData['schema_type']      ?? null,
            'schema_json'      => $seoData['schema_json']      ?? null,
            'noindex'          => isset($seoData['noindex']) ? 1 : 0,
            'nofollow'         => isset($seoData['nofollow']) ? 1 : 0,
        ];

        if ($existing) {
            $this->db->update('content_seo', $data, 'id = ?', [$existing['id']]);
        } else {
            $data['content_id'] = $contentId;
            $this->db->insert('content_seo', $data);
        }
    }
}
