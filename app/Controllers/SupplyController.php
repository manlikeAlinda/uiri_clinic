<?php
namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\SupplyModel;
use Config\Database;

class SupplyController extends BaseController
{
    protected SupplyModel $supplyModel;
    protected $db;

    public function __construct()
    {
        $this->supplyModel = new SupplyModel();
        $this->db          = Database::connect();
    }

    /** LIST + FILTER */
public function index()
{
    $filters = [
        'q'        => trim((string)($this->request->getGet('q') ?? '')),
        'exp_from' => trim((string)($this->request->getGet('exp_from') ?? '')),
        'exp_to'   => trim((string)($this->request->getGet('exp_to') ?? '')),
        'status'   => trim((string)($this->request->getGet('status') ?? '')),
    ];

    // Pagination inputs
    $page    = max(1, (int)($this->request->getGet('page') ?? 1));
    $perPage = (int)($this->request->getGet('per_page') ?? 10);
    $perPage = in_array($perPage, [10, 25, 50, 100], true) ? $perPage : 10;

    // Base query (apply search & date filters)
    $builder = $this->db->table('supplies');

    if ($filters['q'] !== '') {
        $builder->groupStart()
            ->like('name', $filters['q'])
            ->orLike('batch_no', $filters['q'])
            ->groupEnd();
    }
    if ($filters['exp_from'] !== '') {
        $builder->where('expiration_date >=', $filters['exp_from']);
    }
    if ($filters['exp_to'] !== '') {
        $builder->where('expiration_date <=', $filters['exp_to']);
    }

    $rows = $builder->orderBy('name', 'ASC')->get()->getResultArray();

    // Compute effective status & low-stock
    $today      = date('Y-m-d');
    $lowStock   = [];
    $suppliesAll = [];

    foreach ($rows as $r) {
        $qty     = (int)($r['quantity_in_stock'] ?? 0);
        $reorder = (int)($r['reorder_level'] ?? 0);
        $expired = !empty($r['expiration_date']) && $r['expiration_date'] < $today;

        if ($expired) {
            $status = 'Expired';
        } elseif ($qty <= 0) {
            $status = 'Out of Stock';
        } elseif ($reorder > 0 && $qty <= $reorder) {
            $status = 'Low Stock';
        } else {
            $status = 'Available';
        }

        $r['effective_status'] = $status;

        if ($status === 'Low Stock') {
            $lowStock[] = $r; // keep full-scan low-stock alert
        }

        // Apply optional status filter after deriving it
        $statusFilter = strtolower($filters['status']);
        $match = ($statusFilter === '')
            || ($statusFilter === strtolower($status))
            || ($statusFilter === 'in_stock'   && $status === 'Available')
            || ($statusFilter === 'low_stock'  && $status === 'Low Stock')
            || ($statusFilter === 'expired'    && $status === 'Expired')
            || ($statusFilter === 'out_of_stock' && $status === 'Out of Stock');

        if ($match) {
            $suppliesAll[] = $r;
        }
    }

    // ---- Pagination (array slice after derived filter) ----
    $total  = count($suppliesAll);
    $pages  = max(1, (int)ceil($total / max(1, $perPage)));
    if ($page > $pages) { $page = $pages; }
    $offset = ($page - 1) * $perPage;

    $supplies = array_slice($suppliesAll, $offset, $perPage);

    $data = [
        'filters'   => $filters,
        'supplies'  => $supplies,
        'lowStock'  => $lowStock,

        // Pagination vars used by the view
        'total'     => $total,
        'page'      => $page,
        'perPage'   => $perPage,

        // report-related vars default empty on /supplies
        'reportId'   => null,
        'reportData' => [],
        'totals'     => ['used' => 0, 'remaining' => 0],
        'period'     => ['from' => '', 'to' => ''],
    ];

    return view('manage_supplies', $data);
}


    /** ADD */
    public function store()
    {
        $this->supplyModel->save([
            'name'              => $this->request->getPost('name'),
            'quantity_in_stock' => $this->request->getPost('quantity_in_stock'),
            'batch_no'          => $this->request->getPost('batch_no'),
            'manufacture_date'  => $this->request->getPost('manufacture_date'),
            'expiration_date'   => $this->request->getPost('expiration_date'),
            'reorder_level'     => (int)$this->request->getPost('reorder_level'),
            'reorder_quantity'  => $this->request->getPost('reorder_quantity') ?: null,
        ]);

        return redirect()->to(base_url('supplies'))->with('success', 'Supply added!');
    }

    /** UPDATE */
    public function update()
    {
        $id = $this->request->getPost('id');
        $this->supplyModel->update($id, [
            'name'              => $this->request->getPost('name'),
            'quantity_in_stock' => $this->request->getPost('quantity_in_stock'),
            'batch_no'          => $this->request->getPost('batch_no'),
            'manufacture_date'  => $this->request->getPost('manufacture_date'),
            'expiration_date'   => $this->request->getPost('expiration_date'),
            'reorder_level'     => (int)$this->request->getPost('reorder_level'),
            'reorder_quantity'  => $this->request->getPost('reorder_quantity') ?: null,
        ]);

        return redirect()->to(base_url('supplies'))->with('success', 'Supply updated!');
    }

    /** DELETE */
    public function delete()
    {
        $id = $this->request->getPost('id');
        $this->supplyModel->delete($id);
        return redirect()->to(base_url('supplies'))->with('success', 'Supply deleted!');
    }

    /** GENERAL REPORT (Qty used in period + current remaining) */
    public function generalReport()
    {
        $from = trim((string)($this->request->getGet('from') ?? ''));
        $to   = trim((string)($this->request->getGet('to')   ?? ''));

        // Put the date constraints in the JOIN to keep supplies with zero usage
        $join = 'vs.supply_id = s.supply_id';
        if ($from !== '') $join .= " AND vs.created_at >= '{$from} 00:00:00'";
        if ($to   !== '') $join .= " AND vs.created_at <= '{$to} 23:59:59'";

        $report = $this->db->table('supplies s')
            ->select('s.supply_id, s.name, s.batch_no, s.quantity_in_stock,
                      s.reorder_level, s.reorder_quantity, s.manufacture_date, s.expiration_date,
                      COALESCE(SUM(vs.quantity_used),0) AS qty_used')
            ->join('visit_supplies vs', $join, 'left')
            ->groupBy('s.supply_id')
            ->orderBy('s.name', 'ASC')
            ->get()->getResultArray();

        // compute status & totals
        $today = date('Y-m-d');
        $totals = ['used' => 0, 'remaining' => 0];
        foreach ($report as &$r) {
            $qty     = (int)$r['quantity_in_stock'];
            $reorder = (int)($r['reorder_level'] ?? 0);
            $expired = !empty($r['expiration_date']) && $r['expiration_date'] < $today;

            if     ($expired)               $status = 'Expired';
            elseif ($qty <= 0)              $status = 'Out of Stock';
            elseif ($reorder > 0 && $qty <= $reorder) $status = 'Low Stock';
            else                             $status = 'Available';

            $r['effective_status'] = $status;

            $totals['used']      += (int)$r['qty_used'];
            $totals['remaining'] += $qty;
        }

        $data = [
            'filters'    => ['q'=>'','exp_from'=>'','exp_to'=>'','status'=>''],
            'supplies'   => [], // hide the big table in report-mode
            'lowStock'   => [],
            'reportId'   => 'SUPPLIES-' . date('Ymd-His'),
            'reportData' => $report,
            'totals'     => $totals,
            'period'     => ['from' => $from, 'to' => $to],
        ];

        return view('manage_supplies', $data);
    }
}
