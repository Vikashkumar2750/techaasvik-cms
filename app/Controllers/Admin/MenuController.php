<?php
namespace Controllers\Admin;

use Core\Controller;
use Core\View;
use Core\Auth;

/**
 * Admin Menu Controller — manages navigation menus (header, footer, mobile).
 */
class MenuController extends Controller
{
    public function index(array $params = []): void
    {
        $this->requireAdmin();
        $menus = $this->db->fetchAll("SELECT * FROM menus ORDER BY name");
        foreach ($menus as &$menu) {
            $menu['items'] = $this->db->fetchAll(
                "SELECT * FROM menu_items WHERE menu_id = ? ORDER BY menu_order",
                [$menu['id']]
            );
        }
        unset($menu);

        $this->adminView('menus/index', [
            'pageTitle' => 'Menu Manager',
            'menus'     => $menus,
            'flash'     => $this->getFlash(),
        ]);
    }

    public function createMenu(array $params = []): void
    {
        $this->requireAdmin();
        $this->verifyCsrf();

        $name     = trim($this->request->post('name', ''));
        $location = trim($this->request->post('location', ''));
        if (empty($name) || empty($location)) {
            $this->flash('error', 'Menu name and location are required.');
            View::redirect('/techaasvik_admin/menus');
            return;
        }

        $this->db->insert('menus', [
            'name'       => $name,
            'location'   => $location,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        $this->flash('success', "Menu \"{$name}\" created.");
        View::redirect('/techaasvik_admin/menus');
    }

    public function addItem(array $params = []): void
    {
        $this->requireAdmin();
        $this->verifyCsrf();

        $menuId = (int)($params['id'] ?? $this->request->post('menu_id', 0));
        $title  = trim($this->request->post('title', ''));
        $url    = trim($this->request->post('url', ''));

        if (!$menuId || empty($title)) {
            $this->flash('error', 'Menu item title is required.');
            View::redirect('/techaasvik_admin/menus');
            return;
        }

        // Get next order position
        $maxOrder = (int)$this->db->fetchColumn(
            "SELECT COALESCE(MAX(menu_order), 0) FROM menu_items WHERE menu_id = ?",
            [$menuId]
        );

        $this->db->insert('menu_items', [
            'menu_id'    => $menuId,
            'parent_id'  => (int)$this->request->post('parent_id', 0) ?: null,
            'title'      => $title,
            'url'        => $url ?: null,
            'content_id' => (int)$this->request->post('content_id', 0) ?: null,
            'target'     => $this->request->post('target', '_self'),
            'icon'       => $this->request->post('icon', '') ?: null,
            'badge'      => $this->request->post('badge', '') ?: null,
            'menu_order' => $maxOrder + 1,
        ]);

        $this->flash('success', "Menu item \"{$title}\" added.");
        View::redirect('/techaasvik_admin/menus');
    }

    public function deleteItem(array $params = []): void
    {
        $this->requireAdmin();
        $this->verifyCsrf();

        $itemId = (int)($params['id'] ?? 0);
        if ($itemId) {
            $this->db->delete('menu_items', 'id = ?', [$itemId]);
            $this->flash('success', 'Menu item removed.');
        }
        View::redirect('/techaasvik_admin/menus');
    }

    public function reorder(array $params = []): void
    {
        $this->requireAdmin();
        $this->verifyCsrf();

        $items = json_decode($this->request->post('items', '[]'), true);
        if (is_array($items)) {
            foreach ($items as $index => $itemId) {
                $this->db->update('menu_items', ['menu_order' => $index], 'id = ?', [(int)$itemId]);
            }
        }
        View::json(['success' => true]);
    }

    public function deleteMenu(array $params = []): void
    {
        $this->requireAdmin();
        $this->verifyCsrf();

        $menuId = (int)($params['id'] ?? 0);
        if ($menuId) {
            $this->db->delete('menu_items', 'menu_id = ?', [$menuId]);
            $this->db->delete('menus', 'id = ?', [$menuId]);
            $this->flash('success', 'Menu deleted.');
        }
        View::redirect('/techaasvik_admin/menus');
    }

    private function requireAdmin(): void
    {
        Auth::startSession();
        Auth::requireAdmin();
    }
}
