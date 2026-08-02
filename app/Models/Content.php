<?php
namespace Models;

use Core\Model;

/**
 * Content Model — universal content model for all page types.
 * Mirrors WordPress's wp_posts concept but fully custom.
 */
class Content extends Model
{
    protected string $table = 'content';

    // ── Fetch published content by slug & type ────────────
    public function getBySlug(string $slug, string $type = ''): ?array
    {
        $sql    = "SELECT c.*, a.name AS author_name, a.slug AS author_slug,
                          a.photo_id AS author_photo, a.bio AS author_bio
                   FROM content c
                   LEFT JOIN authors a ON c.author_id = a.id
                   WHERE c.slug = ? AND c.status = 'published'";
        $params = [$slug];

        if ($type) {
            $sql    .= " AND c.type = ?";
            $params[] = $type;
        }

        return $this->db->fetchOne($sql . " LIMIT 1", $params);
    }

    // ── Fetch published list by type ──────────────────────
    public function getPublished(
        string $type,
        int    $limit  = 20,
        int    $offset = 0,
        string $lang   = 'en'
    ): array {
        return $this->db->fetchAll(
            "SELECT c.*, a.name AS author_name, a.slug AS author_slug
             FROM content c
             LEFT JOIN authors a ON c.author_id = a.id
             WHERE c.type = ? AND c.status = 'published' AND c.lang = ?
             ORDER BY c.published_at DESC
             LIMIT ? OFFSET ?",
            [$type, $lang, $limit, $offset]
        );
    }

    // ── Count published by type ───────────────────────────
    public function countPublished(string $type, string $lang = 'en'): int
    {
        return (int)$this->db->fetchColumn(
            "SELECT COUNT(*) FROM content WHERE type = ? AND status = 'published' AND lang = ?",
            [$type, $lang]
        );
    }

    // ── Get with SEO data ─────────────────────────────────
    public function getWithSeo(int $id): ?array
    {
        return $this->db->fetchOne(
            "SELECT c.*, s.meta_title, s.meta_description, s.canonical_url,
                    s.og_title, s.og_description, s.og_image, s.schema_type,
                    s.schema_json, s.noindex, s.nofollow
             FROM content c
             LEFT JOIN content_seo s ON s.content_id = c.id
             WHERE c.id = ? LIMIT 1",
            [$id]
        );
    }

    // ── Get related posts (by shared categories) ──────────
    public function getRelated(int $id, string $type, int $limit = 4): array
    {
        return $this->db->fetchAll(
            "SELECT DISTINCT c.id, c.title, c.slug, c.excerpt, c.featured_image_id,
                    c.published_at, c.type
             FROM content c
             INNER JOIN content_categories cc1 ON cc1.content_id = c.id
             INNER JOIN content_categories cc2 ON cc2.category_id = cc1.category_id
             WHERE cc2.content_id = ? AND c.id != ? AND c.status = 'published' AND c.type = ?
             ORDER BY c.published_at DESC
             LIMIT ?",
            [$id, $id, $type, $limit]
        );
    }

    // ── Get by category ───────────────────────────────────
    public function getByCategory(int $catId, int $limit = 20, int $offset = 0): array
    {
        return $this->db->fetchAll(
            "SELECT c.*, a.name AS author_name
             FROM content c
             LEFT JOIN authors a ON c.author_id = a.id
             INNER JOIN content_categories cc ON cc.content_id = c.id
             WHERE cc.category_id = ? AND c.status = 'published'
             ORDER BY c.published_at DESC LIMIT ? OFFSET ?",
            [$catId, $limit, $offset]
        );
    }

    // ── Get by tag ────────────────────────────────────────
    public function getByTag(int $tagId, int $limit = 20, int $offset = 0): array
    {
        return $this->db->fetchAll(
            "SELECT c.*, a.name AS author_name
             FROM content c
             LEFT JOIN authors a ON c.author_id = a.id
             INNER JOIN content_tags ct ON ct.content_id = c.id
             WHERE ct.tag_id = ? AND c.status = 'published'
             ORDER BY c.published_at DESC LIMIT ? OFFSET ?",
            [$tagId, $limit, $offset]
        );
    }

    // ── Sitemap data ──────────────────────────────────────
    public function getSitemapData(string $type): array
    {
        return $this->db->fetchAll(
            "SELECT slug, type, updated_at, published_at
             FROM content
             WHERE type = ? AND status = 'published'
             ORDER BY updated_at DESC",
            [$type]
        );
    }

    // ── Full text search ──────────────────────────────────
    public function search(string $query, int $limit = 20, int $offset = 0): array
    {
        $like = '%' . $query . '%';
        return $this->db->fetchAll(
            "SELECT id, type, title, slug, excerpt, published_at
             FROM content
             WHERE status = 'published'
             AND (title LIKE ? OR excerpt LIKE ? OR content LIKE ?)
             ORDER BY
               CASE WHEN title LIKE ? THEN 0 ELSE 1 END,
               published_at DESC
             LIMIT ? OFFSET ?",
            [$like, $like, $like, $like, $limit, $offset]
        );
    }

    // ── Admin: all with filters ───────────────────────────
    public function adminList(string $type = '', string $status = '', int $limit = 30, int $offset = 0): array
    {
        $where  = ['1=1'];
        $params = [];

        if ($type) {
            $where[]  = 'type = ?';
            $params[] = $type;
        }
        if ($status) {
            $where[]  = 'status = ?';
            $params[] = $status;
        }

        $whereStr = implode(' AND ', $where);
        $params   = array_merge($params, [$limit, $offset]);

        return $this->db->fetchAll(
            "SELECT c.*, a.name AS author_name
             FROM content c
             LEFT JOIN authors a ON c.author_id = a.id
             WHERE $whereStr
             ORDER BY c.updated_at DESC LIMIT ? OFFSET ?",
            $params
        );
    }

    // ── Attach categories ─────────────────────────────────
    public function attachCategories(int $contentId, array $categoryIds): void
    {
        $this->db->delete('content_categories', 'content_id = ?', [$contentId]);
        foreach (array_filter($categoryIds) as $catId) {
            $this->db->insert('content_categories', [
                'content_id'  => $contentId,
                'category_id' => (int)$catId,
            ]);
        }
    }

    // ── Attach tags ───────────────────────────────────────
    public function attachTags(int $contentId, array $tagIds): void
    {
        $this->db->delete('content_tags', 'content_id = ?', [$contentId]);
        foreach (array_filter($tagIds) as $tagId) {
            $this->db->insert('content_tags', [
                'content_id' => $contentId,
                'tag_id'     => (int)$tagId,
            ]);
        }
    }

    // ── Get meta values ───────────────────────────────────
    public function getMeta(int $contentId, string $key): ?string
    {
        $result = $this->db->fetchOne(
            "SELECT meta_value FROM content_meta WHERE content_id = ? AND meta_key = ?",
            [$contentId, $key]
        );
        return $result ? $result['meta_value'] : null;
    }

    public function setMeta(int $contentId, string $key, string $value): void
    {
        $existing = $this->db->fetchOne(
            "SELECT id FROM content_meta WHERE content_id = ? AND meta_key = ?",
            [$contentId, $key]
        );

        if ($existing) {
            $this->db->update('content_meta', ['meta_value' => $value], 'id = ?', [$existing['id']]);
        } else {
            $this->db->insert('content_meta', [
                'content_id' => $contentId,
                'meta_key'   => $key,
                'meta_value' => $value,
            ]);
        }
    }

    // ── Dashboard stats ───────────────────────────────────
    public function stats(): array
    {
        return [
            'total'     => $this->db->fetchColumn("SELECT COUNT(*) FROM content"),
            'published' => $this->db->fetchColumn("SELECT COUNT(*) FROM content WHERE status = 'published'"),
            'draft'     => $this->db->fetchColumn("SELECT COUNT(*) FROM content WHERE status = 'draft'"),
            'by_type'   => $this->db->fetchAll(
                "SELECT type, COUNT(*) AS cnt FROM content GROUP BY type ORDER BY cnt DESC"
            ),
        ];
    }
}
