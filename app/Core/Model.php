<?php
namespace Core;

/**
 * Base Model — thin PDO wrapper for all data models.
 */
abstract class Model
{
    protected Database $db;
    protected string $table   = '';
    protected string $primary = 'id';

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    // ── Find by primary key ────────────────────────────────
    public function find(int $id): ?array
    {
        return $this->db->fetchOne(
            "SELECT * FROM `{$this->table}` WHERE `{$this->primary}` = ? LIMIT 1",
            [$id]
        );
    }

    // ── Find by column ────────────────────────────────────
    public function findBy(string $column, mixed $value): ?array
    {
        return $this->db->fetchOne(
            "SELECT * FROM `{$this->table}` WHERE `$column` = ? LIMIT 1",
            [$value]
        );
    }

    // ── Find all ──────────────────────────────────────────
    public function all(string $orderBy = 'id', string $dir = 'DESC'): array
    {
        return $this->db->fetchAll(
            "SELECT * FROM `{$this->table}` ORDER BY `$orderBy` $dir"
        );
    }

    // ── Create ────────────────────────────────────────────
    public function create(array $data): int
    {
        $data['created_at'] = $data['created_at'] ?? date('Y-m-d H:i:s');
        $data['updated_at'] = $data['updated_at'] ?? date('Y-m-d H:i:s');
        return $this->db->insert($this->table, $data);
    }

    // ── Update ────────────────────────────────────────────
    public function update(int $id, array $data): int
    {
        $data['updated_at'] = date('Y-m-d H:i:s');
        return $this->db->update(
            $this->table,
            $data,
            "`{$this->primary}` = ?",
            [$id]
        );
    }

    // ── Delete ────────────────────────────────────────────
    public function delete(int $id): int
    {
        return $this->db->delete(
            $this->table,
            "`{$this->primary}` = ?",
            [$id]
        );
    }

    // ── Count ─────────────────────────────────────────────
    public function count(string $where = '1', array $params = []): int
    {
        return (int)$this->db->fetchColumn(
            "SELECT COUNT(*) FROM `{$this->table}` WHERE $where",
            $params
        );
    }

    // ── Exists ────────────────────────────────────────────
    public function exists(string $column, mixed $value, int $excludeId = 0): bool
    {
        $result = $this->db->fetchColumn(
            "SELECT COUNT(*) FROM `{$this->table}` WHERE `$column` = ? AND `{$this->primary}` != ?",
            [$value, $excludeId]
        );
        return (int)$result > 0;
    }
}
