<?php
namespace Models;
use Core\Model;

class Author extends Model {
    protected string $table = 'authors';

    public function getActive(): array {
        return $this->db->fetchAll("SELECT * FROM authors WHERE is_active = 1 ORDER BY name");
    }

    public function getBySlug(string $slug): ?array {
        return $this->db->fetchOne("SELECT * FROM authors WHERE slug = ? AND is_active = 1 LIMIT 1", [$slug]);
    }

    public function getWithPostCount(): array {
        return $this->db->fetchAll(
            "SELECT a.*, COUNT(c.id) AS post_count
             FROM authors a
             LEFT JOIN content c ON c.author_id = a.id AND c.status = 'published'
             WHERE a.is_active = 1
             GROUP BY a.id
             ORDER BY post_count DESC"
        );
    }

    public function getContentByAuthor(int $authorId, int $limit = 20, int $offset = 0): array {
        return $this->db->fetchAll(
            "SELECT id, type, title, slug, excerpt, published_at, featured_image_id
             FROM content WHERE author_id = ? AND status = 'published'
             ORDER BY published_at DESC LIMIT ? OFFSET ?",
            [$authorId, $limit, $offset]
        );
    }
}
