<?php
namespace Controllers\Admin;

use Core\Controller;
use Core\View;
use Models\Lead;

/**
 * Admin Leads Controller — view, filter, export, and manage subscriber/audit leads.
 */
class LeadsController extends Controller
{
    public function index(array $params = []): void
    {
        $this->requireAdmin();
        $lead    = new Lead();
        $page    = $this->page();
        $perPage = 50;
        $type    = $this->request->get('type', '');
        $status  = $this->request->get('status', '');

        // Build query
        $where  = ['1=1'];
        $bind   = [];
        if ($type)   { $where[] = 'lead_type = ?'; $bind[] = $type; }
        if ($status) { $where[] = 'status = ?';    $bind[] = $status; }

        $sql   = 'SELECT * FROM leads WHERE ' . implode(' AND ', $where) . ' ORDER BY created_at DESC LIMIT ? OFFSET ?';
        $items = $this->db->fetchAll($sql, [...$bind, $perPage, ($page - 1) * $perPage]);
        $total = (int)$this->db->fetchColumn('SELECT COUNT(*) FROM leads WHERE ' . implode(' AND ', $where), $bind);
        $stats = $lead->getStats();

        $this->adminView('leads/index', [
            'pageTitle' => 'Leads & Subscribers',
            'items'     => $items,
            'total'     => $total,
            'page'      => $page,
            'perPage'   => $perPage,
            'stats'     => $stats,
            'filter'    => ['type' => $type, 'status' => $status],
            'flash'     => $this->getFlash(),
        ]);
    }

    public function export(array $params = []): void
    {
        $this->requireAdmin();

        $type = $this->request->get('type', '');
        $sql  = $type
            ? "SELECT * FROM leads WHERE lead_type = ? ORDER BY created_at DESC"
            : "SELECT * FROM leads ORDER BY created_at DESC";
        $rows = $type
            ? $this->db->fetchAll($sql, [$type])
            : $this->db->fetchAll($sql);

        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="leads-' . date('Y-m-d') . '.csv"');
        header('Cache-Control: no-cache');

        $out = fopen('php://output', 'w');
        // BOM for Excel UTF-8
        fputs($out, "\xEF\xBB\xBF");

        if (!empty($rows)) {
            fputcsv($out, array_keys($rows[0]));
            foreach ($rows as $row) fputcsv($out, $row);
        }
        fclose($out);
        exit;
    }

    public function updateStatus(array $params = []): void
    {
        $this->requireAdmin();
        $this->verifyCsrf();
        $id     = (int)($params['id'] ?? 0);
        $status = $this->request->post('status', 'contacted');
        $this->db->update('leads', ['status' => $status, 'updated_at' => date('Y-m-d H:i:s')], 'id = ?', [$id]);
        $this->flash('success', 'Lead status updated.');
        View::redirect('/techaasvik_admin/leads');
    }

    public function delete(array $params = []): void
    {
        $this->requireAdmin();
        $this->verifyCsrf();
        $id = (int)($params['id'] ?? 0);
        $this->db->delete('leads', 'id = ?', [$id]);
        $this->flash('success', 'Lead deleted.');
        View::redirect('/techaasvik_admin/leads');
    }

    private function requireAdmin(): void
    {
        \Core\Auth::startSession();
        \Core\Auth::requireAdmin();
    }
}
