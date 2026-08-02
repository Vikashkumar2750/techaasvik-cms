<?php
namespace Core;

/**
 * Database — PDO Singleton with query builder helpers.
 * Uses prepared statements exclusively to prevent SQL injection.
 */
class Database
{
    private static ?Database $instance = null;
    private \PDO $pdo;

    private function __construct(array $config)
    {
        $dsn = sprintf(
            'mysql:host=%s;port=%d;dbname=%s;charset=%s',
            $config['host'],
            $config['port'],
            $config['name'],
            $config['charset']
        );

        $options = [
            \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            \PDO::ATTR_EMULATE_PREPARES   => false,
            \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci",
        ];

        $this->pdo = new \PDO($dsn, $config['user'], $config['pass'], $options);
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            $config = require APP_PATH . '/Config/config.php';
            self::$instance = new self($config['database']);
        }
        return self::$instance;
    }

    // ── Raw query ──────────────────────────────────────────
    public function query(string $sql, array $params = []): \PDOStatement
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    // ── Fetch single row ───────────────────────────────────
    public function fetchOne(string $sql, array $params = []): ?array
    {
        $result = $this->query($sql, $params)->fetch();
        return $result ?: null;
    }

    // ── Fetch all rows ─────────────────────────────────────
    public function fetchAll(string $sql, array $params = []): array
    {
        return $this->query($sql, $params)->fetchAll();
    }

    // ── Fetch single column value ──────────────────────────
    public function fetchColumn(string $sql, array $params = []): mixed
    {
        return $this->query($sql, $params)->fetchColumn();
    }

    // ── Insert and return last insert ID ──────────────────
    public function insert(string $table, array $data): int
    {
        $columns = implode(', ', array_map(fn($k) => "`$k`", array_keys($data)));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));

        $sql = "INSERT INTO `$table` ($columns) VALUES ($placeholders)";
        $this->query($sql, array_values($data));

        return (int) $this->pdo->lastInsertId();
    }

    // ── Update rows ───────────────────────────────────────
    public function update(string $table, array $data, string $where, array $whereParams = []): int
    {
        $set = implode(', ', array_map(fn($k) => "`$k` = ?", array_keys($data)));
        $sql = "UPDATE `$table` SET $set WHERE $where";

        $stmt = $this->query($sql, [...array_values($data), ...$whereParams]);
        return $stmt->rowCount();
    }

    // ── Delete rows ───────────────────────────────────────
    public function delete(string $table, string $where, array $params = []): int
    {
        $sql  = "DELETE FROM `$table` WHERE $where";
        $stmt = $this->query($sql, $params);
        return $stmt->rowCount();
    }

    // ── Paginate ──────────────────────────────────────────
    public function paginate(string $sql, array $params, int $page, int $perPage): array
    {
        $countSql = "SELECT COUNT(*) FROM ($sql) AS _count";
        $total    = (int) $this->fetchColumn($countSql, $params);

        $offset = ($page - 1) * $perPage;
        $items  = $this->fetchAll("$sql LIMIT $perPage OFFSET $offset", $params);

        return [
            'items'        => $items,
            'total'        => $total,
            'per_page'     => $perPage,
            'current_page' => $page,
            'last_page'    => (int) ceil($total / $perPage),
            'has_more'     => ($page * $perPage) < $total,
        ];
    }

    // ── Transaction helper ────────────────────────────────
    public function transaction(callable $callback): mixed
    {
        $this->pdo->beginTransaction();
        try {
            $result = $callback($this);
            $this->pdo->commit();
            return $result;
        } catch (\Throwable $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    // ── Prevent cloning ──────────────────────────────────
    private function __clone() {}
}
