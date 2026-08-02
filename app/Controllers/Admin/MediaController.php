<?php
namespace Controllers\Admin;

use Core\Controller;
use Core\View;

/**
 * Admin Media Controller — file upload, listing, and deletion.
 */
class MediaController extends Controller
{
    private string $uploadDir;

    public function __construct()
    {
        parent::__construct();
        $this->uploadDir = STORAGE_PATH . '/uploads';
        if (!is_dir($this->uploadDir)) {
            mkdir($this->uploadDir, 0755, true);
        }
    }

    public function index(array $params = []): void
    {
        $this->requireAdmin();
        $page    = $this->page();
        $perPage = 30;
        $items   = $this->db->fetchAll(
            "SELECT * FROM media ORDER BY created_at DESC LIMIT ? OFFSET ?",
            [$perPage, ($page - 1) * $perPage]
        );
        $total = (int)$this->db->fetchColumn("SELECT COUNT(*) FROM media");

        $this->adminView('media/index', [
            'pageTitle' => 'Media Library',
            'items'     => $items,
            'total'     => $total,
            'page'      => $page,
            'perPage'   => $perPage,
            'flash'     => $this->getFlash(),
        ]);
    }

    public function upload(array $params = []): void
    {
        $this->requireAdmin();

        if (empty($_FILES['file'])) {
            View::json(['success' => false, 'message' => 'No file provided.'], 400);
        }

        $file    = $_FILES['file'];
        $allowed = ['image/jpeg','image/png','image/webp','image/gif','image/svg+xml','application/pdf'];

        if (!in_array($file['type'], $allowed)) {
            View::json(['success' => false, 'message' => 'File type not allowed.'], 422);
        }

        $maxSize = 5 * 1024 * 1024; // 5 MB
        if ($file['size'] > $maxSize) {
            View::json(['success' => false, 'message' => 'File exceeds 5 MB limit.'], 422);
        }

        // Build unique filename
        $ext      = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $safeName = preg_replace('/[^a-z0-9\-]/', '', strtolower(pathinfo($file['name'], PATHINFO_FILENAME)));
        $safeName = substr($safeName, 0, 40) ?: 'upload';
        $filename = $safeName . '-' . time() . '.' . $ext;
        $yearMonth = date('Y/m');
        $destDir  = $this->uploadDir . '/' . $yearMonth;
        if (!is_dir($destDir)) mkdir($destDir, 0755, true);
        $destPath = $destDir . '/' . $filename;

        if (!move_uploaded_file($file['tmp_name'], $destPath)) {
            View::json(['success' => false, 'message' => 'Upload failed. Check server permissions.'], 500);
        }

        // Get image dimensions
        $width = $height = null;
        if (str_starts_with($file['type'], 'image/') && $file['type'] !== 'image/svg+xml') {
            [$width, $height] = @getimagesize($destPath) ?: [null, null];
        }

        $webPath = '/storage/uploads/' . $yearMonth . '/' . $filename;

        // Insert into DB
        $mediaId = $this->db->insert('media', [
            'filename'    => $filename,
            'filepath'    => $webPath,
            'mime_type'   => $file['type'],
            'filesize'    => $file['size'],
            'width'       => $width,
            'height'      => $height,
            'alt_text'    => $safeName,
            'uploaded_by' => $_SESSION['admin']['id'] ?? null,
            'created_at'  => date('Y-m-d H:i:s'),
        ]);

        View::json([
            'success'  => true,
            'id'       => $mediaId,
            'url'      => $webPath,
            'filename' => $filename,
        ]);
    }

    public function delete(array $params = []): void
    {
        $this->requireAdmin();
        $this->verifyCsrf();
        $id    = (int)($params['id'] ?? 0);
        $media = $this->db->fetchOne("SELECT * FROM media WHERE id = ?", [$id]);

        if ($media) {
            $path = STORAGE_PATH . '/uploads' . str_replace('/storage/uploads', '', $media['filepath']);
            if (file_exists($path)) unlink($path);
            $this->db->delete('media', 'id = ?', [$id]);
        }

        if ($this->request->isAjax()) {
            View::json(['success' => true]);
        } else {
            $this->flash('success', 'File deleted.');
            View::redirect('/techaasvik_admin/media');
        }
    }

    private function requireAdmin(): void
    {
        \Core\Auth::startSession();
        \Core\Auth::requireAdmin();
    }
}
