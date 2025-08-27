<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\VisitModel;
use App\Models\PatientModel;
use App\Models\DoctorModel;
use App\Models\DrugModel;
use App\Models\SupplyModel;
use App\Models\VisitPrescriptionModel;
use App\Models\VisitSupplyModel;
use App\Models\VisitOutcomeModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * VisitController (RBAC-aware, doctor-scoped with optional unassigned)
 *
 * Key assumptions:
 * - Session contains: user_id, user_name, role (e.g., 'Admin'|'Doctor'), doctor_id (nullable for non-doctors).
 * - Admin: can view/update/delete all visits.
 * - Doctor: can view/update/delete only visits assigned to them. (Optionally can view unassigned if enabled.)
 * - Unassigned visibility for Doctors is controlled by ALLOW_UNASSIGNED_FOR_DOCTORS (below).
 */
class VisitController extends BaseController
{
    use ResponseTrait;

    // ---- CONFIG TOGGLE -------------------------------------------------------
    // Allow doctors to see visits with NULL doctor_id (unassigned)
    private const ALLOW_UNASSIGNED_FOR_DOCTORS = true;

    protected VisitModel   $visitModel;
    protected PatientModel $patientModel;
    protected DoctorModel  $doctorModel;
    protected DrugModel    $drugModel;
    protected SupplyModel  $supplyModel;

    /** Cache the resolved doctor row for the current request */
    private ?array $cachedDoctor = null;

    public function __construct()
    {
        $this->visitModel   = new VisitModel();
        $this->patientModel = new PatientModel();
        $this->doctorModel  = new DoctorModel();
        $this->drugModel    = new DrugModel();
        $this->supplyModel  = new SupplyModel();
    }

    /* ========================================================================
     * Auth & RBAC helpers
     * ====================================================================== */

    private function currentUserId(): ?int
    {
        // Prefer values set by your Login controller
        $s = session();
        foreach (['user_id', 'id', 'uid'] as $k) {
            $v = $s->get($k);
            if ($v !== null && ctype_digit((string) $v)) {
                return (int) $v;
            }
        }
        $user = $s->get('user'); // if you store a user array
        if (is_array($user) && isset($user['user_id']) && ctype_digit((string) $user['user_id'])) {
            return (int) $user['user_id'];
        }
        return null;
    }

    private function currentRole(): ?string
    {
        $role = session()->get('role');
        return is_string($role) ? $role : null;
    }

    private function currentDoctorId(): ?int
    {
        $docId = session()->get('doctor_id');
        return $docId !== null && ctype_digit((string) $docId) ? (int) $docId : null;
    }

    /**
     * Resolve the *doctor* row for the current user or return RedirectResponse.
     * - Admins do NOT need a doctor row; return a synthetic minimal array.
     * - Doctors MUST have a doctor row mapped; otherwise redirect with message.
     */
    private function currentDoctorOrRedirect(): array|RedirectResponse
    {
        if ($this->cachedDoctor !== null) {
            return $this->cachedDoctor;
        }

        $uid  = $this->currentUserId();
        $role = $this->currentRole();

        if (!$uid) {
            return redirect()->to('/login')->with('error', 'Please sign in first.');
        }

        if ($role === 'Admin') {
            // Admins may not have a doctors row; return a lightweight "virtual" doc
            return $this->cachedDoctor = [
                'doctor_id'  => null,
                'user_id'    => $uid,
                'first_name' => 'Admin',
                'last_name'  => '',
            ];
        }

        if ($role === 'Doctor') {
            // Expect a doctors row mapped to this user
            $doctor = $this->doctorModel
                ->select('doctor_id, user_id, first_name, last_name')
                ->where('user_id', $uid)
                ->first();

            if (!$doctor) {
                return redirect()
                    ->to('/login')
                    ->with('error', 'Your account is not linked to a doctor profile. Contact admin.');
            }
            return $this->cachedDoctor = $doctor;
        }

        // Unknown/unsupported role
        return redirect()->to('/login')->with('error', 'Unauthorized role.');
    }

    private function isAdmin(): bool
    {
        return $this->currentRole() === 'Admin';
    }

    private function isDoctor(): bool
    {
        return $this->currentRole() === 'Doctor';
    }

    private function isAjax(): bool
    {
        return $this->request->isAJAX() || $this->request->getHeaderLine('Accept') === 'application/json';
    }

    /**
     * Authorization: can the current user view this visit?
     */
    private function canViewVisit(array $visit): bool
    {
        if ($this->isAdmin()) {
            return true;
        }
        if ($this->isDoctor()) {
            $doctorId = $this->currentDoctorId();
            if ($doctorId !== null && (int) $visit['doctor_id'] === $doctorId) {
                return true;
            }
            if (self::ALLOW_UNASSIGNED_FOR_DOCTORS && $visit['doctor_id'] === null) {
                return true;
            }
        }
        return false;
    }

    /**
     * Authorization: can the current user modify (update/delete) this visit?
     * - Admin: yes
     * - Doctor: only if assigned owner
     */
    private function canModifyVisit(array $visit): bool
    {
        if ($this->isAdmin()) {
            return true;
        }
        if ($this->isDoctor()) {
            $doctorId = $this->currentDoctorId();
            return $doctorId !== null && (int) $visit['doctor_id'] === $doctorId;
        }
        return false;
    }

    /* ========================================================================
     * Page: index (list + optional report)
     * ====================================================================== */

    public function index()
    {
        $who = $this->currentDoctorOrRedirect();
        if ($who instanceof RedirectResponse) {
            return $who;
        }

        $role     = $this->currentRole();
        $doctorId = $this->currentDoctorId(); // may be null for Admin

        // Filters (GET)
        $filters = [
            'patient_id' => $this->request->getGet('patient_id'),
            'date_from'  => $this->request->getGet('date_from'),
            'date_to'    => $this->request->getGet('date_to'),
            'category'   => $this->request->getGet('category'),
        ];

        // Optional report printer (by ?report=<id>)
        $reportId       = $this->request->getGet('report');
        $reportData     = null;
        $visitReport    = null;
        $patientReport  = null;
        $vitals         = [];
        $complaints     = [];
        $investigations = [];
        $diagnoses      = [];
        $prescriptionsR = [];
        $suppliesUsedR  = [];
        $outcomeR       = null;

        if (ctype_digit((string) $reportId)) {
            $reportBuilder = $this->visitModel
                ->select("
                    visits.*,
                    CONCAT(p.first_name,' ',p.last_name) AS patient_name,
                    CONCAT(d.first_name,' ',d.last_name) AS doctor_name
                ")
                ->join('patients p', 'p.patient_id = visits.patient_id')
                ->join('doctors  d', 'd.doctor_id = visits.doctor_id', 'left') // left to allow unassigned
                ->where('visits.visit_id', (int) $reportId);

            // Scope by role
            if ($this->isAdmin()) {
                // no extra where
            } elseif ($this->isDoctor()) {
                if ($doctorId !== null) {
                    $reportBuilder->groupStart()
                        ->where('visits.doctor_id', $doctorId);
                    if (self::ALLOW_UNASSIGNED_FOR_DOCTORS) {
                        $reportBuilder->orWhere('visits.doctor_id IS NULL', null, false);
                    }
                    $reportBuilder->groupEnd();
                } else {
                    // should never happen if login sets doctor_id correctly
                    $reportBuilder->where('1 = 0'); // force none
                }
            } else {
                $reportBuilder->where('1 = 0');
            }

            $visitReport = $reportBuilder->first();

            if ($visitReport && $this->canViewVisit($visitReport)) {
                $patientReport = $this->patientModel->find((int) ($visitReport['patient_id'] ?? 0));

                $vitals = $this->visitModel
                    ->select('weight, blood_pressure AS bp, pulse, temperature AS temp, sp02, respiration_rate AS resp_rate')
                    ->where('visit_id', (int) $reportId)
                    ->first() ?? [];

                $complaints     = !empty($visitReport['patient_complaints']) ? preg_split('/\r\n|\r|\n/', (string) $visitReport['patient_complaints']) : [];
                $investigations = !empty($visitReport['investigations'])     ? preg_split('/\r\n|\r|\n/', (string) $visitReport['investigations'])     : [];
                $diagnoses      = !empty($visitReport['diagnosis'])          ? preg_split('/\r\n|\r|\n/', (string) $visitReport['diagnosis'])          : [];

                $prescriptionsR = (new VisitPrescriptionModel())
                    ->select("
                        drugs.name                   AS drug,
                        visit_prescriptions.dosage,
                        visit_prescriptions.quantity,
                        visit_prescriptions.duration,
                        visit_prescriptions.route,
                        visit_prescriptions.instructions
                    ")
                    ->join('drugs', 'drugs.drug_id = visit_prescriptions.drug_id')
                    ->where('visit_prescriptions.visit_id', (int) $reportId)
                    ->findAll();

                $suppliesUsedR = (new VisitSupplyModel())
                    ->select("
                        supplies.name              AS supply,
                        visit_supplies.quantity_used,
                        visit_supplies.usage_type
                    ")
                    ->join('supplies', 'supplies.supply_id = visit_supplies.supply_id')
                    ->where('visit_supplies.visit_id', (int) $reportId)
                    ->findAll();

                $outcomeR = (new VisitOutcomeModel())
                    ->where('visit_id', (int) $reportId)
                    ->first();

                $reportData = compact(
                    'visitReport',
                    'patientReport',
                    'vitals',
                    'complaints',
                    'investigations',
                    'diagnoses',
                    'prescriptionsR',
                    'suppliesUsedR',
                    'outcomeR'
                );
            }
        }

        // Listing builder
        $builder = $this->visitModel
            ->select("
                visits.*,
                CONCAT(p.first_name,' ',p.last_name) AS patient_name,
                CONCAT(d.first_name,' ',d.last_name) AS doctor_name
            ")
            ->join('patients p', 'p.patient_id = visits.patient_id')
            ->join('doctors  d', 'd.doctor_id  = visits.doctor_id', 'left')
            ->orderBy('visits.visit_date', 'DESC');

        // Scope by role
        if ($this->isAdmin()) {
            // Admin sees all
        } elseif ($this->isDoctor()) {
            if ($doctorId !== null) {
                $builder->groupStart()
                    ->where('visits.doctor_id', $doctorId);
                if (self::ALLOW_UNASSIGNED_FOR_DOCTORS) {
                    $builder->orWhere('visits.doctor_id IS NULL', null, false);
                }
                $builder->groupEnd();
            } else {
                // no mapped doctor id -> no data, but show empty list gracefully
                $builder->where('1 = 0');
            }
        } else {
            // unknown/unsupported role
            $builder->where('1 = 0');
        }

        // Apply filters
        if ($filters['patient_id']) {
            $builder->where('visits.patient_id', (int) $filters['patient_id']);
        }
        if ($filters['date_from']) {
            $builder->where('visits.visit_date >=', $filters['date_from']);
        }
        if ($filters['date_to']) {
            $builder->where('visits.visit_date <=', $filters['date_to']);
        }
        if ($filters['category']) {
            $builder->where('visits.visit_category', $filters['category']);
        }

        // Per-page from query string (whitelist)
        $perPage = (int)($this->request->getGet('per_page') ?? 10);
        if (! in_array($perPage, [10, 25, 50, 100], true)) {
            $perPage = 10;
        }

        // IMPORTANT: use paginate() on the MODEL builder
        $visits = $builder
            ->paginate($perPage, 'visits'); // group name 'visits'

        $pager = $this->visitModel->pager;

        return view('manage_visits', [
            'filters'        => $filters,
            'patients'       => $this->patientModel->orderBy('last_name', 'asc')->findAll(),
            'doctors'        => [],
            'visits'         => $visits,      // paginated slice
            'pager'          => $pager,       // <-- add
            'perPage'        => $perPage,     // <-- add
            'drugs'          => $this->drugModel->findAll(),
            'supplies'       => $this->supplyModel->findAll(),

            // placeholders for edit details modal
            'prescriptions'  => [],
            'supplies_used'  => [],
            'outcome'        => null,

            // report payload (unchanged)
            'reportId'       => $reportId,
            'reportData'     => $reportData,
            'visit'          => $visitReport,
            'patient'        => $patientReport,
            'vitals'         => $vitals,
            'complaints'     => $complaints,
            'investigations' => $investigations,
            'diagnoses'      => $diagnoses,
            'prescriptions'  => $prescriptionsR,
            'supplies_used'  => $suppliesUsedR,
            'outcome'        => $outcomeR,

            'currentDoctor'  => $who,
            'currentRole'    => $role,
        ]);
    }

    /* ========================================================================
     * JSON: visit details (role-aware)
     * ====================================================================== */

    public function getVisitDetails($visit_id)
    {
        $who = $this->currentDoctorOrRedirect();
        if ($who instanceof RedirectResponse) {
            // For XHR, return JSON error
            return $this->response->setJSON(['error' => 'Not authenticated'])->setStatusCode(401);
        }

        if (!ctype_digit((string) $visit_id)) {
            return $this->response->setJSON(['error' => 'Invalid visit ID'])->setStatusCode(400);
        }

        $visit = $this->visitModel
            ->select("
                visits.*,
                CONCAT(patients.first_name,' ',patients.last_name) AS patient_name,
                CONCAT(doctors.first_name,' ',doctors.last_name)   AS doctor_name
            ")
            ->join('patients', 'patients.patient_id = visits.patient_id')
            ->join('doctors',  'doctors.doctor_id   = visits.doctor_id', 'left')
            ->where('visits.visit_id', (int) $visit_id)
            ->first();

        if (!$visit || !$this->canViewVisit($visit)) {
            return $this->response->setJSON(['error' => 'Visit not found or not authorized'])->setStatusCode(404);
        }

        $prescriptions = (new VisitPrescriptionModel())
            ->select("
                visit_prescriptions.prescription_id,
                drugs.name                  AS drug_name,
                visit_prescriptions.dosage,
                visit_prescriptions.quantity,
                visit_prescriptions.duration,
                visit_prescriptions.route,
                visit_prescriptions.instructions
            ")
            ->join('drugs', 'drugs.drug_id = visit_prescriptions.drug_id')
            ->where('visit_id', (int) $visit_id)
            ->findAll();

        $supplies = (new VisitSupplyModel())
            ->select("
                visit_supplies.visit_supplies_id AS supply_usage_id,
                supplies.name                    AS supply_name,
                visit_supplies.quantity_used,
                visit_supplies.usage_type
            ")
            ->join('supplies', 'supplies.supply_id = visit_supplies.supply_id')
            ->where('visit_id', (int) $visit_id)
            ->findAll();

        $outcome = (new VisitOutcomeModel())
            ->where('visit_id', (int) $visit_id)
            ->first();

        return $this->response->setJSON([
            'visit'         => $visit,
            'prescriptions' => $prescriptions,
            'supplies'      => $supplies,
            'outcome'       => $outcome,
        ]);
    }

    /* ========================================================================
     * Create / Update / Delete (role-aware)
     * ====================================================================== */

    public function store()
    {
        $who = $this->currentDoctorOrRedirect();
        if ($who instanceof RedirectResponse) {
            return $who;
        }

        // Only Admin or Doctor may create; for Doctor, force ownership.
        if (!$this->isAdmin() && !$this->isDoctor()) {
            return redirect()->to('/visits')->with('error', 'Unauthorized.');
        }

        if (!$this->validate($this->validationRules())) {
            return redirect()->back()
                ->withInput()
                ->with('validation', $this->validator)
                ->with('error', 'Please correct the errors below.');
        }

        $payload = $this->collectVisitData();

        if ($this->isAdmin()) {
            // Admin can decide to create as unassigned or assign later.
            // If you want Admin to always assign someone, set $payload['doctor_id'] here.
            $payload['doctor_id'] = $payload['doctor_id'] ?? null;
        } else {
            // Doctor creates visit -> owns it
            $payload['doctor_id'] = $this->currentDoctorId();
        }

        $db = \Config\Database::connect();
        $db->transStart();
        try {
            $this->visitModel->insert($payload, true);
            $db->transComplete();

            if (!$db->transStatus()) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Could not save the visit. Please try again.');
            }

            return redirect()->to('/visits')->with('success', 'Visit recorded successfully.');
        } catch (\Throwable $e) {
            $db->transRollback();
            log_message('error', 'Visit create failed: {msg}', ['msg' => $e->getMessage()]);
            return redirect()->back()
                ->withInput()
                ->with('error', 'An unexpected error occurred while saving the visit.');
        }
    }

    public function update($id = null)
    {
        $who = $this->currentDoctorOrRedirect();
        if ($who instanceof RedirectResponse) {
            return $who;
        }

        if (!ctype_digit((string) $id)) {
            return redirect()->to('/visits')->with('error', 'Invalid Visit ID.');
        }

        $existing = $this->visitModel->find((int) $id);
        if (!$existing) {
            return redirect()->to('/visits')->with('error', 'Visit not found.');
        }

        if (!$this->canModifyVisit($existing)) {
            return redirect()->to('/visits')->with('error', 'You cannot modify this visit.');
        }

        if (!$this->validate($this->validationRules(true))) {
            return redirect()->back()
                ->withInput()
                ->with('validation', $this->validator)
                ->with('error', 'Please correct the errors below.');
        }

        $payload = $this->collectVisitData();

        // Preserve/force doctor ownership on modify:
        if ($this->isAdmin()) {
            // Admin can reassign or keep; to allow reassignment you'd accept a doctor_id in POST,
            // else keep original owner.
            $payload['doctor_id'] = $existing['doctor_id']; // keep owner unless you build a dedicated "assign" action
        } else {
            // Doctor: keep ownership to self
            $payload['doctor_id'] = $this->currentDoctorId();
        }

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            $this->visitModel->update((int) $id, $payload);
            $db->transComplete();

            if (!$db->transStatus()) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Could not update the visit. Please try again.');
            }

            return redirect()->to('/visits')->with('success', 'Visit updated successfully.');
        } catch (\Throwable $e) {
            $db->transRollback();
            log_message('error', 'Visit update failed: {msg}', ['msg' => $e->getMessage()]);
            return redirect()->back()
                ->withInput()
                ->with('error', 'An unexpected error occurred while updating the visit.');
        }
    }

    // public function delete()
    // {
    //     $who = $this->currentDoctorOrRedirect();
    //     if ($who instanceof RedirectResponse) {
    //         return $who;
    //     }

    //     $id = $this->request->getPost('visit_id');
    //     if (!ctype_digit((string) $id)) {
    //         return redirect()->to('/visits')->with('error', 'No valid Visit ID provided.');
    //     }

    //     $existing = $this->visitModel->find((int) $id);
    //     if (!$existing) {
    //         return redirect()->to('/visits')->with('error', 'Visit not found.');
    //     }

    //     if (!$this->canModifyVisit($existing)) {
    //         return redirect()->to('/visits')->with('error', 'You cannot delete this visit.');
    //     }

    //     $db = \Config\Database::connect();
    //     $db->transStart();

    //     try {
    //         $this->visitModel->delete((int) $id);
    //         $db->transComplete();

    //         if (!$db->transStatus()) {
    //             return redirect()->to('/visits')->with('error', 'Could not delete the visit. Please try again.');
    //         }

    //         return redirect()->to('/visits')->with('success', 'Visit deleted successfully.');
    //     } catch (\Throwable $e) {
    //         $db->transRollback();
    //         log_message('error', 'Visit delete failed: {msg}', ['msg' => $e->getMessage()]);
    //         return redirect()->to('/visits')->with('error', 'An unexpected error occurred while deleting the visit.');
    //     }
    // }

    public function delete()
    {
        // Auth & role checks reused from your controller
        $who = $this->currentDoctorOrRedirect();
        if ($who instanceof \CodeIgniter\HTTP\RedirectResponse) {
            return $who;
        }

        $id = $this->request->getPost('visit_id');
        if (!ctype_digit((string) $id)) {
            $msg = 'Invalid visit.';
            return $this->isAjax() ? $this->failValidationError($msg) : redirect()->to('/visits')->with('error', $msg);
        }
        $visitId = (int) $id;

        // Ensure visit exists and user can modify
        $existing = $this->visitModel->find($visitId);
        if (!$existing) {
            $msg = 'Visit not found.';
            return $this->isAjax() ? $this->failNotFound($msg) : redirect()->to('/visits')->with('error', $msg);
        }
        if (!$this->canModifyVisit($existing)) {
            $msg = 'You cannot delete this visit.';
            return $this->isAjax() ? $this->failForbidden($msg) : redirect()->to('/visits')->with('error', $msg);
        }

        $db = \Config\Database::connect();
        $db->transException(true); // throw on failure

        try {
            // 1) Aggregate child usage for this visit (use your actual table names)
            $presc = $db->table('visit_prescriptions')
                ->select('drug_id, SUM(quantity) AS qty', false)
                ->where('visit_id', $visitId)
                ->groupBy('drug_id')
                ->get()->getResultArray();

            $supps = $db->table('visit_supplies')
                ->select('supply_id, SUM(quantity_used) AS qty', false)
                ->where('visit_id', $visitId)
                ->groupBy('supply_id')
                ->get()->getResultArray();

            // 2) Reinstate drugs (atomic += to avoid race conditions)
            foreach ($presc as $row) {
                $drugId = (int) ($row['drug_id'] ?? 0);
                $qty    = (int) ($row['qty'] ?? 0);
                if ($drugId && $qty > 0) {
                    $db->table('drugs')
                        ->set('quantity_in_stock', 'quantity_in_stock + ' . $qty, false)
                        ->where('drug_id', $drugId)
                        ->update();
                }
            }

            // 3) Reinstate supplies (atomic +=)
            foreach ($supps as $row) {
                $supplyId = (int) ($row['supply_id'] ?? 0);
                $qty      = (int) ($row['qty'] ?? 0);
                if ($supplyId && $qty > 0) {
                    $db->table('supplies')
                        ->set('quantity_in_stock', 'quantity_in_stock + ' . $qty, false)
                        ->where('supply_id', $supplyId)
                        ->update();
                }
            }

            // 4) Delete children (skip if you have FK ON DELETE CASCADE on these)
            $db->table('visit_prescriptions')->where('visit_id', $visitId)->delete();
            $db->table('visit_supplies')->where('visit_id', $visitId)->delete();
            $db->table('visit_outcomes')->where('visit_id', $visitId)->delete();

            // 5) Delete the visit
            $db->table('visits')->where('visit_id', $visitId)->delete();

            $db->transCommit();

            $ok = 'Visit deleted and inventory reinstated.';
            return $this->isAjax()
                ? $this->respond(['status' => 'ok', 'message' => $ok])
                : redirect()->to('/visits')->with('success', $ok);
        } catch (\Throwable $e) {
            $db->transRollback();
            log_message('error', 'Visit delete failed: {err}', ['err' => $e->getMessage()]);
            $msg = 'Failed to delete visit. No changes were made.';
            return $this->isAjax() ? $this->failServerError($msg) : redirect()->to('/visits')->with('error', $msg);
        }
    }


    /* ========================================================================
     * Validation / payload
     * ====================================================================== */

    private function validationRules(bool $update = false): array
    {
        // Align these to your DB (nullable/lengths)
        return [
            'patient_id'         => 'required|is_natural_no_zero',
            'visit_date'         => 'required|valid_date',
            'visit_category'     => 'required|in_list[in-patient,out-patient]',
            'admission_time'     => 'permit_empty|valid_date',
            'blood_pressure'     => 'permit_empty|string',
            'weight'             => 'required|decimal',
            'pulse'              => 'permit_empty|integer',
            'temperature'        => 'permit_empty|decimal',
            'sp02'               => 'permit_empty|decimal',
            'respiration_rate'   => 'permit_empty|integer',
            'patient_complaints' => 'required|string',
            'examination_notes'  => 'permit_empty|string',
            'investigations'     => 'permit_empty|string',
            'diagnosis'          => 'required|string',
        ];
    }

    /**
     * Only extract the allowed visit fields from the request.
     * doctor_id is decided by role logic (not taken from client).
     */
    private function collectVisitData(): array
    {
        $post = static fn(string $key, $default = null) => $this->request->getPost($key) ?? $default;

        return [
            'patient_id'         => (int) $post('patient_id'),
            'visit_date'         => trim((string) $post('visit_date')),
            'visit_category'     => trim((string) $post('visit_category')),
            'admission_time'     => $post('admission_time') ? trim((string) $post('admission_time')) : null,
            'blood_pressure'     => $post('blood_pressure') ? trim((string) $post('blood_pressure')) : null,
            'weight'             => $post('weight') !== null ? (string) $post('weight') : null,
            'pulse'              => $post('pulse') !== null ? (int) $post('pulse') : null,
            'temperature'        => $post('temperature') !== null ? (string) $post('temperature') : null,
            'sp02'               => $post('sp02') !== null ? (string) $post('sp02') : null,
            'respiration_rate'   => $post('respiration_rate') !== null ? (int) $post('respiration_rate') : null,
            'patient_complaints' => trim((string) $post('patient_complaints')),
            'examination_notes'  => $post('examination_notes') ? trim((string) $post('examination_notes')) : null,
            'investigations'     => $post('investigations') ? trim((string) $post('investigations')) : null,
            'diagnosis'          => trim((string) $post('diagnosis')),
        ];
    }
}
