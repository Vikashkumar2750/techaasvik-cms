<?php
namespace Controllers\Admin;

use Core\Controller;
use Core\View;

/**
 * Admin Taxonomy Controller — Categories and Tags management.
 */
class TaxonomyController extends Controller
{
    // ── Categories ───────────────────────────────────────────

    public function categories(array $params = []): void
    {
        $this->requireAdmin();
        $items = $this->db->fetchAll(
            "SELECT c.*, COUNT(cc.content_id) AS post_count
             FROM categories c
             LEFT JOIN content_categories cc ON cc.category_id = c.id
             GROUP BY c.id ORDER BY c.menu_order, c.name"
        );
        $this->adminView('taxonomy/categories', [
            'pageTitle' => 'Categories',
            'items'     => $items,
            'flash'     => $this->getFlash(),
        ]);
    }

    public function storeCategory(array $params = []): void
    {
        $this->requireAdmin();
        $this->verifyCsrf();

        $name   = trim($this->request->post('name', ''));
        $slug   = trim($this->request->post('slug', '')) ?: str_slug($name);
        $parent = (int)$this->request->post('parent_id', 0) ?: null;
        $desc   = trim($this->request->post('description', ''));

        if (!$name) { $this->flash('error', 'Category name is required.'); View::redirect('/techaasvik_admin/categories'); return; }

        $exists = $this->db->fetchOne("SELECT id FROM categories WHERE slug = ?", [$slug]);
        if ($exists) { $this->flash('error', 'Category slug already exists.'); View::redirect('/techaasvik_admin/categories'); return; }

        $this->db->insert('categories', [
            'name'        => $name,
            'slug'        => $slug,
            'parent_id'   => $parent,
            'description' => $desc,
            'created_at'  => date('Y-m-d H:i:s'),
            'updated_at'  => date('Y-m-d H:i:s'),
        ]);

        $this->flash('success', "Category \"{$name}\" created.");
        View::redirect('/techaasvik_admin/categories');
    }

    public function deleteCategory(array $params = []): void
    {
        $this->requireAdmin();
        $this->verifyCsrf();
        $id = (int)($params['id'] ?? 0);
        $this->db->delete('content_categories', 'category_id = ?', [$id]);
        $this->db->delete('categories', 'id = ?', [$id]);
        $this->flash('success', 'Category deleted.');
        View::redirect('/techaasvik_admin/categories');
    }

    // ── Tags ─────────────────────────────────────────────────

    public function tags(array $params = []): void
    {
        $this->requireAdmin();
        $items = $this->db->fetchAll(
            "SELECT t.*, COUNT(ct.content_id) AS post_count
             FROM tags t
             LEFT JOIN content_tags ct ON ct.tag_id = t.id
             GROUP BY t.id ORDER BY post_count DESC, t.name"
        );
        $this->adminView('taxonomy/tags', [
            'pageTitle' => 'Tags',
            'items'     => $items,
            'flash'     => $this->getFlash(),
        ]);
    }

    public function storeTag(array $params = []): void
    {
        $this->requireAdmin();
        $this->verifyCsrf();

        $name = trim($this->request->post('name', ''));
        $slug = trim($this->request->post('slug', '')) ?: str_slug($name);

        if (!$name) { $this->flash('error', 'Tag name is required.'); View::redirect('/techaasvik_admin/tags'); return; }

        $exists = $this->db->fetchOne("SELECT id FROM tags WHERE slug = ?", [$slug]);
        if ($exists) { $this->flash('error', 'Tag slug already exists.'); View::redirect('/techaasvik_admin/tags'); return; }

        $this->db->insert('tags', [
            'name'       => $name,
            'slug'       => $slug,
            'created_at' => date('Y-m-d H:i:s'),
        ]);
        $this->flash('success', "Tag \"{$name}\" created.");
        View::redirect('/techaasvik_admin/tags');
    }

    public function deleteTag(array $params = []): void
    {
        $this->requireAdmin();
        $this->verifyCsrf();
        $id = (int)($params['id'] ?? 0);
        $this->db->delete('content_tags', 'tag_id = ?', [$id]);
        $this->db->delete('tags', 'id = ?', [$id]);
        $this->flash('success', 'Tag deleted.');
        View::redirect('/techaasvik_admin/tags');
    }

    private function requireAdmin(): void
    {
        \Core\Auth::startSession();
        \Core\Auth::requireAdmin();
    }
}
