<?php
namespace Models;
use Core\Model;

class Setting extends Model {
    protected string $table = 'settings';
    private static array $cache = [];

    public function get(string $key, mixed $default = null): mixed {
        if (empty(self::$cache)) {
            $rows = $this->db->fetchAll("SELECT setting_key, setting_value FROM settings WHERE autoload = 1");
            foreach ($rows as $row) self::$cache[$row['setting_key']] = $row['setting_value'];
        }
        return self::$cache[$key] ?? $default;
    }

    public function set(string $key, mixed $value): void {
        $existing = $this->db->fetchOne("SELECT id FROM settings WHERE setting_key = ?", [$key]);
        if ($existing) {
            $this->db->update('settings', ['setting_value' => $value], 'setting_key = ?', [$key]);
        } else {
            $this->db->insert('settings', ['setting_key' => $key, 'setting_value' => $value]);
        }
        self::$cache[$key] = $value;
    }

    public function setMany(array $data): void {
        foreach ($data as $key => $value) $this->set($key, $value);
    }

    public function getAll(): array {
        return $this->db->fetchAll("SELECT * FROM settings ORDER BY setting_key");
    }
}
