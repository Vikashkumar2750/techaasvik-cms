<?php
namespace Controllers\Admin;

use Core\Controller;
use Core\Auth;
use Core\View;
use Models\Content;
use Services\SeoService;

/**
 * Admin Content Controller — CRUD for all content types.
 */
class ContentController extends Controller
{
    private Content    $content;
    private SeoService $seoService;

    public function __construct()
    {
        parent::__construct();
        $this->content    = new Content();
        $this->seoService = new SeoService();
    }

    // ── List all content ──────────────────────────────────
    public function index(array $params = []): void
    {
        Auth::requireAdmin();

        $type   = $this->request->get('type', '');
        $status = $this->request->get('status', '');
        $page   = $this->page();
        $limit  = 30;
        $offset = ($page - 1) * $limit;

        $items = $this->content->adminList($type, $status, $limit, $offset);

        $total = (int)$this->db->fetchColumn(
            "SELECT COUNT(*) FROM content" .
            ($type   ? " WHERE type = ?" : '') .
            ($status ? ($type ? " AND status = ?" : " WHERE status = ?") : ''),
            array_filter([$type, $status])
        );

        $categories = $this->db->fetchAll("SELECT id, name FROM categories ORDER BY name");
        $authors    = $this->db->fetchAll("SELECT id, name FROM authors ORDER BY name");

        View::admin('content/list', [
            'pageTitle'  => 'Content Manager',
            'items'      => $items,
            'total'      => $total,
            'page'       => $page,
            'limit'      => $limit,
            'type'       => $type,
            'status'     => $status,
            'categories' => $categories,
            'authors'    => $authors,
            'flash'      => $this->getFlash(),
        ]);
    }

    // ── New content form ──────────────────────────────────
    public function create(array $params = []): void
    {
        Auth::requireAdmin();

        $type       = $this->request->get('type', 'post');
        $categories = $this->db->fetchAll("SELECT id, name FROM categories ORDER BY name");
        $tags       = $this->db->fetchAll("SELECT id, name FROM tags ORDER BY name");
        $authors    = $this->db->fetchAll("SELECT id, name FROM authors ORDER BY name");

        View::admin('content/edit', [
            'pageTitle'  => 'New Content',
            'item'       => null,
            'seo'        => null,
            'type'       => $type,
            'categories' => $categories,
            'tags'       => $tags,
            'authors'    => $authors,
            'csrf'       => Auth::csrfToken(),
        ]);
    }

    // ── Store new content ─────────────────────────────────
    public function store(array $params = []): void
    {
        Auth::requireAdmin();
        $this->verifyCsrf();

        $errors = $this->validate([
            'title' => 'required|max:500',
            'type'  => 'required',
        ]);

        if ($errors) {
            $this->flash('error', 'Please fix the errors below.');
            View::redirect('/techaasvik_admin/content/new');
            return;
        }

        $title  = $this->request->post('title', '');
        $slug   = $this->generateSlug($this->request->post('slug', ''), $title);
        $type   = $this->request->post('type', 'post');
        $lang   = $this->request->post('lang', 'en');
        $status = $this->request->post('status', 'draft');

        $id = $this->content->create([
            'type'              => $type,
            'lang'              => $lang,
            'title'             => $title,
            'slug'              => $slug,
            'content'           => $this->request->post('content', ''),
            'excerpt'           => $this->request->post('excerpt', ''),
            'status'            => $status,
            'author_id'         => $this->request->post('author_id', null) ?: null,
            'featured_image_id' => $this->request->post('featured_image_id', null) ?: null,
            'featured_image'    => $this->request->post('featured_image', ''),
            'word_count'        => $this->countWords($this->request->post('content', '')),
            'read_time'         => $this->estimateReadTime($this->request->post('content', '')),
            'difficulty'        => $this->request->post('difficulty', null) ?: null,
            'published_at'      => $status === 'published' ? date('Y-m-d H:i:s') : null,
        ]);

        // Save categories
        $catIds = $this->request->post('categories', []);
        if (is_array($catIds)) {
            $this->content->attachCategories($id, array_map('intval', $catIds));
        }

        // Save tags
        $tagIds = $this->request->post('tags', []);
        if (is_array($tagIds)) {
            $this->content->attachTags($id, array_map('intval', $tagIds));
        }

        // Save SEO
        $this->seoService->saveSeo($id, [
            'meta_title'       => $this->request->post('meta_title', ''),
            'meta_description' => $this->request->post('meta_description', ''),
            'canonical_url'    => $this->request->post('canonical_url', ''),
            'og_title'         => $this->request->post('og_title', ''),
            'og_description'   => $this->request->post('og_description', ''),
            'og_image'         => $this->request->post('og_image', ''),
            'schema_type'      => $this->request->post('schema_type', ''),
            'schema_json'      => $this->request->post('schema_json', ''),
            'noindex'          => $this->request->post('noindex'),
            'nofollow'         => $this->request->post('nofollow'),
        ]);

        $this->flash('success', "Content \"$title\" created successfully.");
        View::redirect("/techaasvik_admin/content/{$id}/edit");
    }

    // ── Edit form ─────────────────────────────────────────
    public function edit(array $params = []): void
    {
        Auth::requireAdmin();

        $id   = (int)($params['id'] ?? 0);
        $item = $this->content->getWithSeo($id);

        if (!$item) {
            $this->flash('error', 'Content not found.');
            View::redirect('/techaasvik_admin/content');
            return;
        }

        $categories = $this->db->fetchAll("SELECT id, name FROM categories ORDER BY name");
        $tags       = $this->db->fetchAll("SELECT id, name FROM tags ORDER BY name");
        $authors    = $this->db->fetchAll("SELECT id, name FROM authors ORDER BY name");

        // Current category/tag selections
        $selectedCats = array_column(
            $this->db->fetchAll("SELECT category_id FROM content_categories WHERE content_id = ?", [$id]),
            'category_id'
        );
        $selectedTags = array_column(
            $this->db->fetchAll("SELECT tag_id FROM content_tags WHERE content_id = ?", [$id]),
            'tag_id'
        );

        View::admin('content/edit', [
            'pageTitle'    => 'Edit: ' . $item['title'],
            'item'         => $item,
            'categories'   => $categories,
            'tags'         => $tags,
            'authors'      => $authors,
            'selectedCats' => $selectedCats,
            'selectedTags' => $selectedTags,
            'flash'        => $this->getFlash(),
            'csrf'         => Auth::csrfToken(),
        ]);
    }

    // ── Update content ────────────────────────────────────
    public function update(array $params = []): void
    {
        Auth::requireAdmin();
        $this->verifyCsrf();

        $id   = (int)($params['id'] ?? 0);
        $item = $this->content->find($id);

        if (!$item) {
            $this->flash('error', 'Content not found.');
            View::redirect('/techaasvik_admin/content');
            return;
        }

        $title  = $this->request->post('title', $item['title']);
        $slug   = $this->generateSlug($this->request->post('slug', ''), $title, $id);
        $status = $this->request->post('status', $item['status']);
        $body   = $this->request->post('content', '');

        $this->content->update($id, [
            'title'             => $title,
            'slug'              => $slug,
            'lang'              => $this->request->post('lang', $item['lang']),
            'content'           => $body,
            'excerpt'           => $this->request->post('excerpt', ''),
            'status'            => $status,
            'author_id'         => $this->request->post('author_id', null) ?: null,
            'featured_image_id' => $this->request->post('featured_image_id', null) ?: null,
            'featured_image'    => $this->request->post('featured_image', ''),
            'word_count'        => $this->countWords($body),
            'read_time'         => $this->estimateReadTime($body),
            'difficulty'        => $this->request->post('difficulty', null) ?: null,
            'published_at'      => ($status === 'published' && !$item['published_at'])
                                    ? date('Y-m-d H:i:s')
                                    : $item['published_at'],
        ]);

        // Update categories & tags
        $catIds = $this->request->post('categories', []);
        $this->content->attachCategories($id, is_array($catIds) ? array_map('intval', $catIds) : []);

        $tagIds = $this->request->post('tags', []);
        $this->content->attachTags($id, is_array($tagIds) ? array_map('intval', $tagIds) : []);

        // Update SEO
        $this->seoService->saveSeo($id, [
            'meta_title'       => $this->request->post('meta_title', ''),
            'meta_description' => $this->request->post('meta_description', ''),
            'canonical_url'    => $this->request->post('canonical_url', ''),
            'og_title'         => $this->request->post('og_title', ''),
            'og_description'   => $this->request->post('og_description', ''),
            'og_image'         => $this->request->post('og_image', ''),
            'schema_type'      => $this->request->post('schema_type', ''),
            'schema_json'      => $this->request->post('schema_json', ''),
            'noindex'          => $this->request->post('noindex'),
            'nofollow'         => $this->request->post('nofollow'),
        ]);

        $this->flash('success', "\"$title\" updated successfully.");
        View::redirect("/techaasvik_admin/content/{$id}/edit");
    }

    // ── Delete ────────────────────────────────────────────
    public function delete(array $params = []): void
    {
        Auth::requireAdmin();
        $this->verifyCsrf();

        $id   = (int)($params['id'] ?? 0);
        $item = $this->content->find($id);

        if ($item) {
            $this->content->delete($id);
            $this->flash('success', "\"" . $item['title'] . "\" deleted.");
        }

        View::redirect('/techaasvik_admin/content');
    }

    // ── Quick publish ──────────────────────────────────────
    public function publish(array $params = []): void
    {
        Auth::requireAdmin();
        $id = (int)($params['id'] ?? 0);
        $this->content->update($id, ['status' => 'published', 'published_at' => date('Y-m-d H:i:s')]);
        $this->flash('success', 'Content published.');
        View::redirect('/techaasvik_admin/content');
    }

    public function unpublish(array $params = []): void
    {
        Auth::requireAdmin();
        $id = (int)($params['id'] ?? 0);
        $this->content->update($id, ['status' => 'draft']);
        $this->flash('success', 'Content unpublished.');
        View::redirect('/techaasvik_admin/content');
    }

    // ── Helpers ───────────────────────────────────────────
    private function generateSlug(string $slug, string $fallback, int $excludeId = 0): string
    {
        $base = $slug ?: $fallback;
        $base = strtolower(trim(preg_replace('/[^a-z0-9]+/i', '-', $base), '-'));
        $base = preg_replace('/-{2,}/', '-', $base);
        $base = substr($base, 0, 200);

        $slug    = $base;
        $counter = 1;

        while ($this->content->exists('slug', $slug, $excludeId)) {
            $slug = $base . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    private function countWords(string $html): int
    {
        return str_word_count(strip_tags($html));
    }

    private function estimateReadTime(string $html): int
    {
        $words = $this->countWords($html);
        return max(1, (int)ceil($words / 200));
    }
}
