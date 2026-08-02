<?php
namespace Controllers\Admin;

use Core\Controller;
use Core\Auth;
use Core\View;
use Models\Content;

/**
 * Admin Dashboard Controller
 */
class DashboardController extends Controller
{
    public function index(array $params = []): void
    {
        Auth::startSession();
        Auth::requireAdmin();

        $content = new Content();
        $stats   = $content->stats();

        // Recent content
        $recent = $this->db->fetchAll(
            "SELECT c.id, c.type, c.title, c.status, c.updated_at, a.name AS author_name
             FROM content c
             LEFT JOIN authors a ON c.author_id = a.id
             ORDER BY c.updated_at DESC LIMIT 10"
        );

        // Lead counts
        $leads = [
            'total'   => $this->db->fetchColumn("SELECT COUNT(*) FROM leads"),
            'new'     => $this->db->fetchColumn("SELECT COUNT(*) FROM leads WHERE status = 'new'"),
            'today'   => $this->db->fetchColumn("SELECT COUNT(*) FROM leads WHERE DATE(created_at) = CURDATE()"),
            'week'    => $this->db->fetchColumn("SELECT COUNT(*) FROM leads WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)"),
        ];

        // Content by status
        $byStatus = $this->db->fetchAll(
            "SELECT status, COUNT(*) AS cnt FROM content GROUP BY status"
        );
        $statusMap = [];
        foreach ($byStatus as $row) {
            $statusMap[$row['status']] = $row['cnt'];
        }

        View::admin('dashboard', [
            'pageTitle'  => 'Dashboard',
            'stats'      => $stats,
            'recent'     => $recent,
            'leads'      => $leads,
            'statusMap'  => $statusMap,
            'admin'      => Auth::admin(),
            'flash'      => $this->getFlash(),
        ]);
    }
}
