<?php
namespace Controllers\Admin;

use Core\Controller;
use Core\View;
use Core\Auth;

/**
 * Admin Author Controller — CRUD for content authors/experts.
 */
class AuthorController extends Controller
{
    public function index(array $params = []): void
    {
        $this->requireAdmin();
        $authors = $this->db->fetchAll(
            "SELECT a.*, m.filepath as photo_url,
             (SELECT COUNT(*) FROM content c WHERE c.author_id = a.id AND c.status = 'published') as post_count
             FROM authors a
             LEFT JOIN media m ON m.id = a.photo_id
             ORDER BY a.name"
        );

        $this->adminView('authors/index', [
            'pageTitle' => 'Authors',
            'authors'   => $authors,
            'flash'     => $this->getFlash(),
        ]);
    }

    public function edit(array $params = []): void
    {
        $this->requireAdmin();
        $id     = (int)($params['id'] ?? 0);
        $author = $id ? $this->db->fetchOne("SELECT * FROM authors WHERE id = ?", [$id]) : null;

        $this->adminView('authors/edit', [
            'pageTitle' => $author ? 'Edit Author' : 'New Author',
            'author'    => $author,
            'flash'     => $this->getFlash(),
        ]);
    }

    public function store(array $params = []): void
    {
        $this->requireAdmin();
        $this->verifyCsrf();

        $name = trim($this->request->post('name', ''));
        if (empty($name)) {
            $this->flash('error', 'Author name is required.');
            View::redirect('/techaasvik_admin/authors/new');
        }

        $slug = $this->request->post('slug', '') ?: $this->slugify($name);

        $data = [
            'name'        => $name,
            'slug'        => $slug,
            'bio'         => $this->request->post('bio', ''),
            'short_bio'   => $this->request->post('short_bio', ''),
            'credentials' => $this->request->post('credentials', ''),
            'email'       => $this->request->post('email', ''),
            'social_links'=> json_encode([
                'twitter'  => $this->request->post('twitter', ''),
                'linkedin' => $this->request->post('linkedin', ''),
                'website'  => $this->request->post('website', ''),
            ]),
            'is_active'   => $this->request->post('is_active') ? 1 : 0,
        ];

        $photoId = (int)$this->request->post('photo_id', 0);
        if ($photoId) $data['photo_id'] = $photoId;

        $this->db->insert('authors', $data);
        $this->flash('success', "Author \"{$name}\" created.");
        View::redirect('/techaasvik_admin/authors');
    }

    public function update(array $params = []): void
    {
        $this->requireAdmin();
        $this->verifyCsrf();

        $id = (int)($params['id'] ?? 0);
        if (!$id) { View::redirect('/techaasvik_admin/authors'); return; }

        $name = trim($this->request->post('name', ''));
        $slug = $this->request->post('slug', '') ?: $this->slugify($name);

        $data = [
            'name'        => $name,
            'slug'        => $slug,
            'bio'         => $this->request->post('bio', ''),
            'short_bio'   => $this->request->post('short_bio', ''),
            'credentials' => $this->request->post('credentials', ''),
            'email'       => $this->request->post('email', ''),
            'social_links'=> json_encode([
                'twitter'  => $this->request->post('twitter', ''),
                'linkedin' => $this->request->post('linkedin', ''),
                'website'  => $this->request->post('website', ''),
            ]),
            'is_active'   => $this->request->post('is_active') ? 1 : 0,
        ];

        $photoId = (int)$this->request->post('photo_id', 0);
        if ($photoId) $data['photo_id'] = $photoId;

        $this->db->update('authors', $data, 'id = ?', [$id]);
        $this->flash('success', "Author \"{$name}\" updated.");
        View::redirect('/techaasvik_admin/authors');
    }

    public function delete(array $params = []): void
    {
        $this->requireAdmin();
        $this->verifyCsrf();
        $id = (int)($params['id'] ?? 0);
        if ($id) {
            $this->db->update('content', ['author_id' => null], 'author_id = ?', [$id]);
            $this->db->delete('authors', 'id = ?', [$id]);
            $this->flash('success', 'Author deleted.');
        }
        View::redirect('/techaasvik_admin/authors');
    }

    private function requireAdmin(): void
    {
        Auth::startSession();
        Auth::requireAdmin();
    }

    private function slugify(string $text): string
    {
        $text = strtolower(trim($text));
        $text = preg_replace('/[^a-z0-9\s-]/', '', $text);
        $text = preg_replace('/[\s-]+/', '-', $text);
        return trim($text, '-');
    }
}
