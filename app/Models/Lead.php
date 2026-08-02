<?php
namespace Models;
use Core\Model;

class Lead extends Model {
    protected string $table = 'leads';

    public function capture(array $data): int {
        $data['ip_address'] = $_SERVER['REMOTE_ADDR'] ?? null;
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        // Extract UTM params from referrer
        $data['utm_source']   = $data['utm_source']   ?? ($_GET['utm_source']   ?? null);
        $data['utm_medium']   = $data['utm_medium']   ?? ($_GET['utm_medium']   ?? null);
        $data['utm_campaign'] = $data['utm_campaign'] ?? ($_GET['utm_campaign'] ?? null);
        $data['utm_content']  = $data['utm_content']  ?? ($_GET['utm_content']  ?? null);
        $data['utm_term']     = $data['utm_term']     ?? ($_GET['utm_term']     ?? null);
        return $this->db->insert($this->table, $data);
    }

    public function getRecent(int $limit = 50, int $offset = 0): array {
        return $this->db->fetchAll(
            "SELECT * FROM leads ORDER BY created_at DESC LIMIT ? OFFSET ?",
            [$limit, $offset]
        );
    }

    public function getStats(): array {
        return [
            'total'       => $this->db->fetchColumn("SELECT COUNT(*) FROM leads"),
            'new'         => $this->db->fetchColumn("SELECT COUNT(*) FROM leads WHERE status = 'new'"),
            'today'       => $this->db->fetchColumn("SELECT COUNT(*) FROM leads WHERE DATE(created_at) = CURDATE()"),
            'week'        => $this->db->fetchColumn("SELECT COUNT(*) FROM leads WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)"),
            'by_type'     => $this->db->fetchAll("SELECT lead_type, COUNT(*) AS cnt FROM leads GROUP BY lead_type ORDER BY cnt DESC"),
            'by_source'   => $this->db->fetchAll("SELECT utm_source, COUNT(*) AS cnt FROM leads WHERE utm_source IS NOT NULL GROUP BY utm_source ORDER BY cnt DESC LIMIT 10"),
        ];
    }
}
