<?php
namespace Models;
use Core\Model;

class Category extends Model {
    protected string $table = 'categories';

    public function getAll(): array {
        return $this->db->fetchAll("SELECT * FROM categories ORDER BY menu_order, name");
    }

    public function getTree(): array {
        $all  = $this->getAll();
        $tree = [];
        $map  = [];
        foreach ($all as $cat) {
            $cat['children'] = [];
            $map[$cat['id']] = $cat;
        }
        foreach ($map as &$cat) {
            if ($cat['parent_id'] && isset($map[$cat['parent_id']])) {
                $map[$cat['parent_id']]['children'][] = &$cat;
            } else {
                $tree[] = &$cat;
            }
        }
        return $tree;
    }

    public function getBySlug(string $slug): ?array {
        return $this->db->fetchOne("SELECT * FROM categories WHERE slug = ? LIMIT 1", [$slug]);
    }

    public function getWithCount(): array {
        return $this->db->fetchAll(
            "SELECT c.*, COUNT(cc.content_id) AS post_count
             FROM categories c
             LEFT JOIN content_categories cc ON cc.category_id = c.id
             LEFT JOIN content ct ON ct.id = cc.content_id AND ct.status = 'published'
             GROUP BY c.id
             ORDER BY post_count DESC"
        );
    }
}
