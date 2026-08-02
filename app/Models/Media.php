<?php
namespace Models;
use Core\Model;

class Media extends Model {
    protected string $table = 'media';

    public function getImageUrl(int $id, string $size = 'original'): string {
        $media = $this->find($id);
        if (!$media) return '/assets/images/static/placeholder.jpg';

        $path = $media['filepath'];
        if ($size !== 'original') {
            $ext     = pathinfo($path, PATHINFO_EXTENSION);
            $base    = pathinfo($path, PATHINFO_FILENAME);
            $dir     = pathinfo($path, PATHINFO_DIRNAME);
            $sized   = $dir . '/' . $base . '-' . $size . '.' . $ext;
            if (file_exists(APP_ROOT . $sized)) return $sized;
        }
        return $path;
    }

    public function getUrl(?int $id, string $size = 'original'): string {
        if (!$id) return '/assets/images/static/placeholder.jpg';
        return $this->getImageUrl($id, $size);
    }

    public function getRecent(int $limit = 30): array {
        return $this->db->fetchAll(
            "SELECT * FROM media ORDER BY created_at DESC LIMIT ?", [$limit]
        );
    }
}
