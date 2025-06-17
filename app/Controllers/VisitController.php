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

class VisitController extends BaseController
{
    use ResponseTrait;

    protected VisitModel   $visitModel;
    protected PatientModel $patientModel;
    protected DoctorModel  $doctorModel;
    protected DrugModel $drugModel;
    protected SupplyModel $supplyModel; // ✅ Add this line


    public function __construct()
    {
        $this->visitModel   = new VisitModel();
        $this->patientModel = new PatientModel();
        $this->doctorModel  = new DoctorModel();
        $this->drugModel    = new DrugModel();     // ✅ Add this
        $this->supplyModel  = new SupplyModel();   // ✅ Add this
    }

    public function index()
    {
        $data = [
            'visits'        => $this->visitModel->getVisitsWithRelations(),
            'patients'      => $this->patientModel->findAll(),
            'doctors'       => $this->doctorModel->findAll(),
            'drugs'         => $this->drugModel->findAll(),
            'supplies'      => $this->supplyModel->findAll(),

            // ✅ Correct these to match your view file
            'prescriptions' => [],
            'supplies_used' => [],
            'outcome'  => null
        ];
        return view('manage_visits', $data);
    }

    public function store()
    {
        if (! $this->validate($this->validationRules())) {
            return redirect()->back()
                ->withInput()
                ->with('validation', $this->validator)
                ->with('error', 'Please correct the errors below.');
        }

        $this->visitModel->insert($this->collectVisitData());

        return redirect()->to('/visits')
            ->with('success', 'Visit recorded successfully.');
    }

    public function update($id = null)
    {
        if (! $id || ! $this->visitModel->find($id)) {
            return redirect()->to('/visits')->with('error', 'Visit not found.');
        }

        if (! $this->validate($this->validationRules(true))) {
            return redirect()->back()
                ->withInput()
                ->with('validation', $this->validator)
                ->with('error', 'Please correct the errors below.');
        }

        $data = $this->collectVisitData();

        // Ensure the ID is not overridden unexpectedly
        $this->visitModel->update($id, $data);

        return redirect()->to('/visits')
            ->with('success', 'Visit updated successfully.');
    }

    public function delete()
    {
        $id = $this->request->getPost('visit_id');

        // 1) Guard against missing or non-numeric IDs
        if (empty($id) || !ctype_digit($id)) {
            return redirect()->to('/visits')
                ->with('error', 'No valid Visit ID provided.');
        }

        // 2) Guard against IDs that don’t exist
        if (! $this->visitModel->find($id)) {
            return redirect()->to('/visits')
                ->with('error', 'Visit not found.');
        }

        // 3) Attempt deletion
        if (! $this->visitModel->delete($id)) {
            return redirect()->to('/visits')
                ->with('error', 'Visit deletion failed.');
        }

        // 4) Success
        return redirect()->to('/visits')
            ->with('success', 'Visit deleted successfully.');
    }

    private function validationRules(bool $update = false): array
    {
        return [
            'patient_id'         => 'required|is_natural_no_zero',
            'doctor_id'          => 'required|is_natural_no_zero',
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

    private function collectVisitData(): array
    {
        return [
            'patient_id'         => $this->request->getPost('patient_id'),
            'doctor_id'          => $this->request->getPost('doctor_id'),
            'visit_date'         => $this->request->getPost('visit_date'),
            'visit_category'     => $this->request->getPost('visit_category'),
            'admission_time'     => $this->request->getPost('admission_time'),
            'blood_pressure'     => $this->request->getPost('blood_pressure'),
            'weight'             => $this->request->getPost('weight'),
            'pulse'              => $this->request->getPost('pulse'),
            'temperature'        => $this->request->getPost('temperature'),
            'sp02'               => $this->request->getPost('sp02'),
            'respiration_rate'   => $this->request->getPost('respiration_rate'),
            'patient_complaints' => $this->request->getPost('patient_complaints'),
            'examination_notes'  => $this->request->getPost('examination_notes'),
            'investigations'     => $this->request->getPost('investigations'),
            'diagnosis'          => $this->request->getPost('diagnosis'),
        ];
    }

    public function getVisitDetails($visit_id)
    {
        // Validate that visit_id is a digit
        if (!ctype_digit($visit_id)) {
            return $this->response->setJSON(['error' => 'Invalid visit ID'])->setStatusCode(400);
        }

        // Fetch visit basic info
        $visit = $this->visitModel
            ->select("visits.*, CONCAT(patients.first_name, ' ', patients.last_name) AS patient_name, CONCAT(doctors.first_name, ' ', doctors.last_name) AS doctor_name")
            ->join('patients', 'patients.patient_id = visits.patient_id')
            ->join('doctors', 'doctors.doctor_id = visits.doctor_id')
            ->where('visit_id', $visit_id)
            ->first();


        if (!$visit) {
            return $this->response->setJSON(['error' => 'Visit not found'])->setStatusCode(404);
        }

        // Prescriptions
        $prescriptions = (new \App\Models\VisitPrescriptionModel())
            ->select('visit_prescriptions.*, drugs.name as drug_name')
            ->join('drugs', 'drugs.drug_id = visit_prescriptions.drug_id')
            ->where('visit_id', $visit_id)
            ->findAll();

        // Supplies
        $supplies = (new \App\Models\VisitSupplyModel())
            ->select('visit_supplies.*, supplies.name as supply_name')
            ->join('supplies', 'supplies.supply_id = visit_supplies.supply_id')
            ->where('visit_id', $visit_id)
            ->findAll();

        // Outcome
        $outcome = (new \App\Models\VisitOutcomeModel())
            ->where('visit_id', $visit_id)
            ->first();

        return $this->response->setJSON([
            'visit'         => $visit,
            'prescriptions' => $prescriptions,
            'supplies'      => $supplies,
            'outcome'       => $outcome
        ]);
    }
}
