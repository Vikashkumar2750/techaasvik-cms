<?php
namespace Models;
use Core\Model;

class Tag extends Model {
    protected string $table = 'tags';

    public function getBySlug(string $slug): ?array {
        return $this->db->fetchOne("SELECT * FROM tags WHERE slug = ? LIMIT 1", [$slug]);
    }

    public function getCloud(int $limit = 50): array {
        return $this->db->fetchAll(
            "SELECT t.*, COUNT(ct.content_id) AS post_count
             FROM tags t
             LEFT JOIN content_tags ct ON ct.tag_id = t.id
             LEFT JOIN content c ON c.id = ct.content_id AND c.status = 'published'
             GROUP BY t.id HAVING post_count > 0
             ORDER BY post_count DESC LIMIT ?",
            [$limit]
        );
    }
}
