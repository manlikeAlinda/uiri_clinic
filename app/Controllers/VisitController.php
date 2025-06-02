<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\VisitModel;
use App\Models\PatientModel;
use App\Models\DoctorModel;
use App\Models\DrugModel;
use App\Models\EquipmentModel;
use App\Models\SupplyModel;

class VisitController extends BaseController
{
    protected $visitModel;
    protected $patientModel;
    protected $doctorModel;

    public function __construct()
    {
        $this->visitModel   = new VisitModel();
        $this->patientModel = new PatientModel();
        $this->doctorModel  = new DoctorModel();
    }

    // public function index()
    // {
    //     $visitModel = new VisitModel();
    //     $patientModel = new PatientModel();
    //     $doctorModel = new DoctorModel();
    //     $drugModel = new DrugModel();
    //     $equipmentModel = new EquipmentModel();
    //     $supplyModel = new SupplyModel();

    //     $data = [
    //         'visits'     => $visitModel->getVisitsWithRelations(),
    //         'patients'   => $patientModel->findAll(),
    //         'doctors'    => $doctorModel->findAll(),
    //         'drugs'      => $drugModel->findAll(),        // ✅ include this
    //         'equipment'  => $equipmentModel->findAll(),   // ✅ and this
    //         'supplies'   => $supplyModel->findAll(),      // ✅ and this
    //     ];

    //     return view('manage_visits', $data);
    // }

    public function index()
    {
        // Role-based access control
        // if (!session()->get('user_role') || session()->get('user_role') !== 'Administration') {
        //     return redirect()->to('/dashboard')->with('error', 'Access denied.');
        // }

        try {
            // Load data for the view
            $data = [
                'visits'     => $this->visitModel->getVisitsWithRelations(),
                'patients'   => $this->patientModel->findAll(),
                'doctors'    => $this->doctorModel->findAll(),
                'drugs'      => (new \App\Models\DrugModel())->findAll(),
                'equipment'  => (new \App\Models\EquipmentModel())->findAll(),
                'supplies'   => (new \App\Models\SupplyModel())->findAll()
            ];

            // TODO: Implement pagination on visits if data grows large

            return view('manage_visits', array_merge($data, [
                'getStatusColor' => 'getStatusColor'
            ]));
        } catch (\Exception $e) {
            // Log the exception for auditing/debugging
            log_message('error', 'VisitController::index - Error loading visit data: ' . $e->getMessage());

            // Redirect with error message
            return redirect()->to('/dashboard')->with('error', 'Failed to load visit management data.');
        }
    }



    // public function store()
    // {
    //     $data = [
    //         'patient_id'      => $this->request->getPost('patient_id'),
    //         'doctor_id'       => $this->request->getPost('doctor_id'),
    //         'visit_date'      => date('Y-m-d'), // You can make this dynamic if needed
    //         'weight'          => $this->request->getPost('weight'),
    //         'observations'    => $this->request->getPost('observations'),
    //         'diagnosis'       => $this->request->getPost('diagnosis'),
    //         'treatment_notes' => $this->request->getPost('treatment_notes'),
    //         'initial_condition' => $this->request->getPost('initial_condition'),
    //         'admission_time'    => $this->request->getPost('admission_time'),
    //         'discharge_time'    => $this->request->getPost('discharge_time'),
    //         'referral_notes'    => $this->request->getPost('referral_notes'),

    //     ];

    //     $this->visitModel->save($data);

    //     return redirect()->to('/visits')->with('success', 'Visit added successfully.');
    // }

    public function store()
    {
        // Role-based access control
        // $allowedRoles = ['Administration', 'Staff'];
        // if (!in_array(session()->get('user_role'), $allowedRoles)) {
        //     return redirect()->to('/dashboard')->with('error', 'Unauthorized access.');
        // }

        // Validate user input
        $validationRules = [
            'patient_id'        => 'required|is_natural_no_zero',
            'doctor_id'         => 'required|is_natural_no_zero',
            'visit_date'        => 'required|valid_date',
            'weight'            => 'required|decimal',
            'observations'      => 'permit_empty|string',
            'diagnosis'         => 'permit_empty|string',
            'treatment_notes'   => 'permit_empty|string',
            'admission_time'    => 'permit_empty|valid_date',
            'discharge_time'    => 'permit_empty|valid_date',
            'visit_status' => 'required|in_list[Scheduled,In Progress,Completed,Cancelled]',
            'blood_pressure' => 'permit_empty|string',
            'heart_rate'     => 'permit_empty|integer',
            'temperature'    => 'permit_empty|decimal',

        ];

        if (!$this->validate($validationRules)) {
            return redirect()->back()->withInput()
                ->with('error', 'Please correct the errors below.')
                ->with('validation', $this->validator);
        }

        // Prepare sanitized data
        $data = [
            'patient_id'        => $this->request->getPost('patient_id'),
            'doctor_id'         => $this->request->getPost('doctor_id'),
            'visit_date'        => $this->request->getPost('visit_date'),
            'weight'            => $this->request->getPost('weight'),
            'observations'      => $this->request->getPost('observations'),
            'diagnosis'         => $this->request->getPost('diagnosis'),
            'treatment_notes'   => $this->request->getPost('treatment_notes'),
            'admission_time'    => $this->request->getPost('admission_time'),
            'discharge_time'    => $this->request->getPost('discharge_time'),
            'visit_status'      => $this->request->getPost('visit_status'),
            'blood_pressure' => $this->request->getPost('blood_pressure'),
            'heart_rate'     => $this->request->getPost('heart_rate'),
            'temperature'    => $this->request->getPost('temperature'),
        ];


        try {
            $this->visitModel->insert($data);
            return redirect()->to('/visits')->with('success', 'Visit recorded successfully.');
        } catch (\Exception $e) {
            log_message('error', 'VisitController::store - Insert failed: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Failed to save visit record.');
        }
    }

    public function update($id)
    {
        // Access control
        // $allowedRoles = ['Administration', 'Staff'];
        // if (!in_array(session()->get('user_role'), $allowedRoles)) {
        //     return redirect()->to('/dashboard')->with('error', 'Unauthorized access.');
        // }

        // Ensure visit exists
        $existing = $this->visitModel->find($id);
        if (!$existing) {
            return redirect()->to('/visits')->with('error', 'Visit not found.');
        }

        // Determine if the visit date is in the past or today
        $visitDate = $existing['visit_date'] ?? null;
        $visitIsPast = $visitDate && strtotime($visitDate) <= strtotime(date('Y-m-d'));

        // Define dynamic rule
        $patientConditionRule = $visitIsPast ? 'required|in_list[Stable,Critical,Recovering,Discharged,Referred,Under Observation]' : 'permit_empty';

        // Validation rules
        $validationRules = [
            'weight'            => 'required|decimal',
            'observations'      => 'permit_empty|string',
            'diagnosis'         => 'permit_empty|string',
            'treatment_notes'   => 'permit_empty|string',
            'patient_condition' => $patientConditionRule,
            'admission_time'    => 'permit_empty|valid_date',
            'discharge_time'    => 'permit_empty|valid_date',
            'referral_notes'    => 'permit_empty|string',
            'visit_status' => 'required|in_list[Scheduled,In Progress,Completed,Cancelled]',
            'blood_pressure' => 'permit_empty|string',
            'heart_rate'     => 'permit_empty|integer',
            'temperature'    => 'permit_empty|decimal',
        ];

        if (!$this->validate($validationRules)) {
            return redirect()->back()->withInput()
                ->with('error', 'Please fix validation errors.')
                ->with('validation', $this->validator);
        }

        // Prepare data
        $data = [
            'weight'            => $this->request->getPost('weight'),
            'observations'      => $this->request->getPost('observations'),
            'diagnosis'         => $this->request->getPost('diagnosis'),
            'treatment_notes'   => $this->request->getPost('treatment_notes'),
            'patient_condition' => $this->request->getPost('patient_condition'),
            'admission_time'    => $this->request->getPost('admission_time'),
            'discharge_time'    => $this->request->getPost('discharge_time'),
            'referral_notes'    => $this->request->getPost('referral_notes'),
            'visit_status' => $this->request->getPost('visit_status'),
            'blood_pressure' => $this->request->getPost('blood_pressure'),
            'heart_rate'     => $this->request->getPost('heart_rate'),
            'temperature'    => $this->request->getPost('temperature'),
        ];

        try {
            $this->visitModel->update($id, $data);
            return redirect()->to('/visits')->with('success', 'Visit updated successfully.');
        } catch (\Exception $e) {
            log_message('error', 'VisitController::update - Update failed: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Failed to update visit.');
        }
    }


    public function delete()
    {
        $visitId = $this->request->getPost('visit_id');

        $success = $this->visitModel->deleteVisitAndRollbackStock($visitId);

        if (!$success) {
            return redirect()->to('/visits')->with('error', 'Visit deletion failed. Please try again.');
        }

        return redirect()->to('/visits')->with('success', 'Visit and associated data deleted and restored successfully.');
    }

    // public function addDetails()
    // {
    //     $type = $this->request->getPost('type');
    //     $visitId = $this->request->getPost('visit_id');
    //     $visitModel = new VisitModel();

    //     $success = true;

    //     if ($type === 'prescription') {
    //         $data = [
    //             'visit_id'     => $visitId,
    //             'drug_id'      => $this->request->getPost('drug_id'),
    //             'dosage'       => $this->request->getPost('dosage'),
    //             'duration'     => $this->request->getPost('duration'),
    //             'instructions' => $this->request->getPost('instructions'),
    //             'quantity'     => $this->request->getPost('quantity'),
    //         ];
    //         $success = $visitModel->addPrescription($data);
    //     }


    //     if ($type === 'equipment') {
    //         $data = [
    //             'visit_id'      => $visitId,
    //             'equipment_id'  => $this->request->getPost('equipment_id'),
    //             'usage_notes'   => $this->request->getPost('usage_notes'),
    //             'quantity_used' => $this->request->getPost('quantity_used') // ← must be saved
    //         ];
    //         $visitModel->addEquipmentUsage($data);
    //     }


    //     if ($type === 'supply') {
    //         $data = [
    //             'visit_id'      => $visitId,
    //             'supply_id'     => $this->request->getPost('supply_id'),
    //             'quantity_used' => $this->request->getPost('quantity_used'),
    //             'usage_type'    => $this->request->getPost('usage_type'),
    //         ];
    //         $success = $visitModel->addSupplyUsage($data);
    //     }


    //     if (!$success) {
    //         return redirect()->back()->with('error', 'Insufficient stock available.');
    //     }

    //     return redirect()->back()->with('success', 'Visit details updated successfully.');
    // }

    public function addDetails()
    {
        // if (!in_array(session()->get('user_role'), ['Administration', 'Staff'])) {
        //     return redirect()->to('/dashboard')->with('error', 'Unauthorized access.');
        // }

        $type = $this->request->getPost('type');
        $visitId = $this->request->getPost('visit_id');

        // Check that the visit exists
        $visit = $this->visitModel->find($visitId);
        if (!$visit) {
            return redirect()->back()->with('error', 'Invalid visit ID.');
        }

        $success = false;

        try {
            switch ($type) {
                case 'prescription':
                    $this->validateOrThrow([
                        'drug_id'    => 'required|is_natural_no_zero',
                        'dosage'     => 'required|string',
                        'duration'   => 'permit_empty|string',
                        'instructions' => 'permit_empty|string',
                        'quantity'   => 'required|integer'
                    ]);

                    $data = [
                        'visit_id'     => $visitId,
                        'drug_id'      => $this->request->getPost('drug_id'),
                        'dosage'       => $this->request->getPost('dosage'),
                        'duration'     => $this->request->getPost('duration'),
                        'instructions' => $this->request->getPost('instructions'),
                        'quantity'     => $this->request->getPost('quantity')
                    ];

                    $success = $this->visitModel->addPrescription($data);
                    break;

                case 'equipment':
                    $this->validateOrThrow([
                        'equipment_id'  => 'required|is_natural_no_zero',
                        'usage_notes'   => 'permit_empty|string',
                        'quantity_used' => 'required|integer'
                    ]);

                    $data = [
                        'visit_id'      => $visitId,
                        'equipment_id'  => $this->request->getPost('equipment_id'),
                        'usage_notes'   => $this->request->getPost('usage_notes'),
                        'quantity_used' => $this->request->getPost('quantity_used')
                    ];

                    $success = $this->visitModel->addEquipmentUsage($data);
                    break;

                case 'supply':
                    $this->validateOrThrow([
                        'supply_id'     => 'required|is_natural_no_zero',
                        'quantity_used' => 'required|integer',
                        'usage_type'    => 'permit_empty|string'
                    ]);

                    $data = [
                        'visit_id'      => $visitId,
                        'supply_id'     => $this->request->getPost('supply_id'),
                        'quantity_used' => $this->request->getPost('quantity_used'),
                        'usage_type'    => $this->request->getPost('usage_type')
                    ];

                    $success = $this->visitModel->addSupplyUsage($data);
                    break;

                default:
                    return redirect()->back()->with('error', 'Invalid detail type.');
            }
        } catch (\Exception $e) {
            return redirect()->back()->withInput()
                ->with('error', 'Invalid or missing input fields.')
                ->with('validation', $this->validator);
        } catch (\Exception $e) {
            log_message('error', 'VisitController::addDetails - ' . $e->getMessage());
            return redirect()->back()->with('error', 'Unexpected error while adding visit details.');
        }

        if (!$success) {
            return redirect()->back()->with('error', 'Insufficient stock or system validation failed.');
        }

        return redirect()->back()->with('success', ucfirst($type) . ' added successfully.');
    }


    // public function getDetails($visitId)
    // {
    //     $db = \Config\Database::connect();

    //     $prescription = $db->table('visit_prescriptions')->where('visit_id', $visitId)->get()->getRow();
    //     $equipment    = $db->table('visit_equipment')->where('visit_id', $visitId)->get()->getRow();
    //     $supply       = $db->table('visit_supplies')->where('visit_id', $visitId)->get()->getRow();

    //     return $this->response->setJSON([
    //         'prescription' => $prescription,
    //         'equipment'    => $equipment,
    //         'supply'       => $supply
    //     ]);
    // }

    public function getDetails($visitId)
    {
        // Basic input validation
        if (!ctype_digit($visitId)) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Invalid visit ID.']);
        }

        // // Role check
        // if (!in_array(session()->get('user_role'), ['Administration', 'Staff'])) {
        //     return $this->response->setStatusCode(403)->setJSON(['error' => 'Unauthorized access.']);
        // }

        try {
            $db = \Config\Database::connect();

            $prescriptions = $db->table('visit_prescriptions')
                ->where('visit_id', $visitId)
                ->get()
                ->getResult();

            $equipment = $db->table('visit_equipment')
                ->where('visit_id', $visitId)
                ->get()
                ->getResult();

            $supplies = $db->table('visit_supplies')
                ->where('visit_id', $visitId)
                ->get()
                ->getResult();

            return $this->response->setJSON([
                'prescriptions' => $prescriptions,
                'equipment'     => $equipment,
                'supplies'      => $supplies
            ]);
        } catch (\Exception $e) {
            log_message('error', 'VisitController::getDetails - ' . $e->getMessage());
            return $this->response->setStatusCode(500)->setJSON([
                'error' => 'Failed to fetch visit details. Please try again.'
            ]);
        }
    }

    protected function validateOrThrow(array $rules)
    {
        if (!$this->validate($rules)) {
            throw new \Exception('Validation failed.');
        }
    }
}
