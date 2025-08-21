<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use Config\Database;
use App\Models\DrugModel;
use DateTime;

class DrugController extends BaseController
{
    protected DrugModel $drugModel;
    protected $db;

    public function __construct()
    {
        $this->drugModel = new DrugModel();
        $this->db        = Database::connect();
    }

    /** ======================================
     *  1) List & filter drugs
     *  ====================================== */
    public function index()
    {
        // 1) Read filters + per_page
        $filters = [
            'q'        => trim((string)($this->request->getGet('q')        ?? '')),
            'exp_from' => trim((string)($this->request->getGet('exp_from') ?? '')),
            'exp_to'   => trim((string)($this->request->getGet('exp_to')   ?? '')),
            'status'   => trim((string)($this->request->getGet('status')   ?? '')),
            'usable'   => trim((string)($this->request->getGet('usable')   ?? '')), // "Usable" | "Non-usable"
        ];

        $perPage = (int)($this->request->getGet('per_page') ?? 10);
        if (!in_array($perPage, [10, 25, 50, 100], true)) {
            $perPage = 10;
        }

        // 2) Build query on the MODEL so paginate() works
        $builder = $this->drugModel
            ->select([
                'drugs.*',
                // Effective status
                "CASE
               WHEN expiration_date IS NOT NULL AND expiration_date < CURDATE() THEN 'Expired'
               WHEN quantity_in_stock <= 0 THEN 'Out of Stock'
               WHEN quantity_in_stock <= COALESCE(reorder_level,0) THEN 'Low Stock'
               ELSE COALESCE(status,'Available')
             END AS effective_status",
                // Effective usability (expired => non-usable)
                "CASE
               WHEN expiration_date IS NOT NULL AND expiration_date < CURDATE() THEN 0
               ELSE COALESCE(is_usable,1)
             END AS effective_usable",
            ], false);

        // Filters
        if ($filters['q'] !== '') {
            $builder->groupStart()
                ->like('name', $filters['q'])
                ->orLike('dosage', $filters['q'])
                ->orLike('batch_no', $filters['q'])
                ->groupEnd();
        }
        if ($filters['exp_from'] !== '') $builder->where('expiration_date >=', $filters['exp_from']);
        if ($filters['exp_to']   !== '') $builder->where('expiration_date <=', $filters['exp_to']);

        // Filters that depend on computed columns — use HAVING (MySQL supports alias in HAVING)
        if ($filters['status'] !== '') {
            $builder->having('effective_status', $filters['status']);
        }
        if ($filters['usable'] !== '') {
            $want = $filters['usable'] === 'Usable' ? 1 : 0;
            $builder->having('effective_usable', $want);
        }

        // 3) Order + paginate (group name 'drugs' to match your view snippet)
        $drugs = $builder
            ->orderBy('name', 'ASC')
            ->paginate($perPage, 'drugs');

        $pager = $this->drugModel->pager;

        // 4) (Optional) Rebuild a lightweight Low Stock list (not paged)
        //    so the warning badges remain meaningful.
        $lowStockRows = $this->db->table('drugs')
            ->select('name, quantity_in_stock, reorder_level, expiration_date', false)
            ->orderBy('name', 'ASC')
            ->get()->getResultArray();

        $today    = date('Y-m-d');
        $lowStock = [];
        foreach ($lowStockRows as $r) {
            $qty     = (int)($r['quantity_in_stock'] ?? 0);
            $reorder = (int)($r['reorder_level'] ?? 0);
            $expired = !empty($r['expiration_date']) && $r['expiration_date'] < $today;
            if (!$expired && $reorder > 0 && $qty <= $reorder) {
                $lowStock[] = $r;
            }
        }

        // 5) Render
        return view('manage_drugs', [
            'filters'  => $filters,
            'drugs'    => $drugs,            // paginated slice
            'pager'    => $pager,            // <-- needed by the pager UI
            'perPage'  => $perPage,          // <-- for the "Rows" dropdown
            'lowStock' => $lowStock,
            // report vars are not used on index() (list) page
            'reportId'   => null,
            'reportData' => [],
            'totals'     => ['used' => 0, 'remaining' => 0],
            'period'     => ['from' => '', 'to' => ''],
        ]);
    }


    /** ======================================
     *  2) Create
     *  ====================================== */
    public function store()
    {
        $rules = [
            'name'              => 'required|string|min_length[2]',
            'dosage'            => 'required|string',
            'quantity_in_stock' => 'required|integer|greater_than_equal_to[0]',
            'reorder_level'     => 'permit_empty|integer|greater_than_equal_to[0]',
            'reorder_quantity'  => 'permit_empty|integer|greater_than[0]',
            'batch_no'          => 'required|string',
            'manufacture_date'  => 'required|valid_date',
            'expiration_date'   => 'required|valid_date',
            'status'            => 'required|in_list[Available,Out of Stock,Expired]',
            'is_usable'         => 'permit_empty|in_list[0,1]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->with('error', implode('<br>', $this->validator->getErrors()))
                ->withInput();
        }

        $expiry = new DateTime($this->request->getPost('expiration_date'));
        $today  = new DateTime('today');

        $status    = $this->request->getPost('status');
        $isUsable  = (int)($this->request->getPost('is_usable') ?? 1);

        if ($expiry < $today) { // force on create
            $status   = 'Expired';
            $isUsable = 0;
        }

        $this->drugModel->save([
            'name'              => $this->request->getPost('name'),
            'dosage'            => $this->request->getPost('dosage'),
            'quantity_in_stock' => $this->request->getPost('quantity_in_stock'),
            'reorder_level'     => $this->request->getPost('reorder_level') ?: 0,
            'reorder_quantity'  => $this->request->getPost('reorder_quantity') ?: null,
            'batch_no'          => $this->request->getPost('batch_no'),
            'manufacture_date'  => $this->request->getPost('manufacture_date'),
            'expiration_date'   => $this->request->getPost('expiration_date'),
            'status'            => $status,
            'is_usable'         => $isUsable,
        ]);

        return redirect()->to(base_url('drugs'))->with('success', 'Drug added successfully!');
    }

    /** ======================================
     *  3) Update
     *  ====================================== */
    public function update()
    {
        $rules = [
            'id'                => 'required|integer',
            'name'              => 'required|string|min_length[2]',
            'dosage'            => 'required|string',
            'quantity_in_stock' => 'required|integer|greater_than_equal_to[0]',
            'reorder_level'     => 'permit_empty|integer|greater_than_equal_to[0]',
            'reorder_quantity'  => 'permit_empty|integer|greater_than[0]',
            'batch_no'          => 'required|string',
            'manufacture_date'  => 'required|valid_date',
            'expiration_date'   => 'required|valid_date',
            'status'            => 'required|in_list[Available,Out of Stock,Expired]',
            'is_usable'         => 'permit_empty|in_list[0,1]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->with('error', implode('<br>', $this->validator->getErrors()))
                ->withInput();
        }

        $id     = (int)$this->request->getPost('id');
        $expiry = new DateTime($this->request->getPost('expiration_date'));
        $today  = new DateTime('today');

        $status   = $this->request->getPost('status');
        $isUsable = (int)($this->request->getPost('is_usable') ?? 1);

        if ($expiry < $today) { // force on update
            $status   = 'Expired';
            $isUsable = 0;
        }

        $this->drugModel->update($id, [
            'name'              => $this->request->getPost('name'),
            'dosage'            => $this->request->getPost('dosage'),
            'quantity_in_stock' => $this->request->getPost('quantity_in_stock'),
            'reorder_level'     => $this->request->getPost('reorder_level') ?: 0,
            'reorder_quantity'  => $this->request->getPost('reorder_quantity') ?: null,
            'batch_no'          => $this->request->getPost('batch_no'),
            'manufacture_date'  => $this->request->getPost('manufacture_date'),
            'expiration_date'   => $this->request->getPost('expiration_date'),
            'status'            => $status,
            'is_usable'         => $isUsable,
        ]);

        return redirect()->to(base_url('drugs'))->with('success', 'Drug updated successfully!');
    }

    /** ======================================
     *  4) General (period) report
     *  ====================================== */
    public function generalReport()
    {
        $from = trim((string)($this->request->getGet('from') ?? ''));
        $to   = trim((string)($this->request->getGet('to')   ?? ''));

        // Usage subquery
        $vp = $this->db->table('visit_prescriptions')
            ->select('drug_id, COALESCE(SUM(quantity),0) AS qty_used', false);

        if ($from !== '') $vp->where('created_at >=', $from . ' 00:00:00');
        if ($to   !== '') $vp->where('created_at <=', $to  . ' 23:59:59');

        $vp->groupBy('drug_id');
        $usedSub = $vp->getCompiledSelect();

        // Main dataset
        $reportRows = $this->db->table('drugs d')
            ->select("
                d.drug_id, d.name, d.dosage, d.batch_no,
                d.quantity_in_stock, d.reorder_level, d.status, d.is_usable, d.expiration_date,
                COALESCE(u.qty_used, 0) AS qty_used,
                CASE
                  WHEN d.expiration_date IS NOT NULL AND d.expiration_date < CURDATE() THEN 'Expired'
                  WHEN d.quantity_in_stock <= 0 THEN 'Out of Stock'
                  WHEN d.quantity_in_stock <= COALESCE(d.reorder_level, 0) THEN 'Low Stock'
                  ELSE COALESCE(d.status,'Available')
                END AS effective_status,
                CASE
                  WHEN d.expiration_date < CURDATE() THEN 0
                  ELSE COALESCE(d.is_usable,1)
                END AS effective_usable
            ", false)
            ->join("($usedSub) u", 'u.drug_id = d.drug_id', 'left')
            ->orderBy('d.name', 'ASC')
            ->get()->getResultArray();

        $totals = ['used' => 0, 'remaining' => 0];
        foreach ($reportRows as $r) {
            $totals['used']      += (int)($r['qty_used'] ?? 0);
            $totals['remaining'] += (int)($r['quantity_in_stock'] ?? 0);
        }

        // Reuse index() filter experience (without re-querying effective fields here)
        return view('manage_drugs', [
            'filters'    => [
                'q' => $this->request->getGet('q') ?? '',
                'exp_from' => $this->request->getGet('exp_from') ?? '',
                'exp_to'   => $this->request->getGet('exp_to')   ?? '',
                'status'   => $this->request->getGet('status')   ?? '',
                'usable'   => $this->request->getGet('usable')   ?? '',
            ],
            'drugs'      => $reportRows, // show same table with computed fields
            'reportId'   => 'DRUGS-' . date('Ymd-His'),
            'reportData' => $reportRows,
            'period'     => ['from' => $from ?: null, 'to' => $to ?: null],
            'totals'     => $totals,
            'lowStock'   => array_filter($reportRows, fn($d) => ($d['effective_status'] ?? 'Available') === 'Low Stock'),
        ]);
    }

    /** ======================================
     *  5) JSON batches for prescription picker
     *     /drugs/batches?drug_id=123  or  ?name=Amoxicillin%20500mg
     *  ====================================== */
    public function batches()
    {
        $name   = trim((string)$this->request->getGet('name'));
        $drugId = (int)($this->request->getGet('drug_id') ?? 0);

        $b = $this->db->table('drugs')
            ->select("
                drug_id, name, dosage, batch_no, quantity_in_stock, expiration_date,
                CASE WHEN expiration_date < CURDATE() THEN 0 ELSE COALESCE(is_usable,1) END AS effective_usable
            ", false)
            ->where('quantity_in_stock >', 0);

        if ($drugId) $b->where('drug_id', $drugId);
        if ($name   !== '') $b->where('name', $name);

        $rows = $b->orderBy('expiration_date', 'ASC')->get()->getResultArray();

        return $this->response->setJSON($rows);
    }

    /** ======================================
     *  6) Delete
     *  ====================================== */
    public function delete()
    {
        $id = $this->request->getPost('id');
        $this->drugModel->delete($id);

        return redirect()->to(base_url('drugs'))->with('success', 'Drug deleted successfully!');
    }
}
