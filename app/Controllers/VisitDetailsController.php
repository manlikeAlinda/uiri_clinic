<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\VisitPrescriptionModel;
use App\Models\VisitSupplyModel;
use App\Models\VisitOutcomeModel;
use App\Models\DrugModel;
use App\Models\SupplyModel;
use CodeIgniter\API\ResponseTrait;

class VisitDetailsController extends BaseController
{
    use ResponseTrait;

    protected $prescriptionModel;
    protected $supplyModel;
    protected $outcomeModel;
    protected $drugModel;
    protected $stockSupplyModel;

    public function __construct()
    {
        $this->prescriptionModel = new VisitPrescriptionModel();
        $this->supplyModel       = new VisitSupplyModel();
        $this->outcomeModel      = new VisitOutcomeModel();
        $this->drugModel         = new DrugModel();
        $this->stockSupplyModel  = new SupplyModel();
    }

    public function addDetails()
    {
        $type     = $this->request->getPost('type');
        $visit_id = $this->request->getPost('visit_id');

        log_message('debug', 'Received POST data: ' . print_r($this->request->getPost(), true));

        // Validate visit_id
        if (empty($visit_id) || !ctype_digit($visit_id)) {
            log_message('error', 'Invalid or missing visit ID: ' . $visit_id);
            return redirect()->back()->withInput()->with('error', 'Invalid or missing visit ID.');
        }

        // Check if the visit exists
        $visitModel = new \App\Models\VisitModel();
        if (!$visitModel->find($visit_id)) {
            log_message('error', 'Visit not found for ID: ' . $visit_id);
            return redirect()->back()->withInput()->with('error', 'Visit not found.');
        }

        switch ($type) {
            case 'prescription':
                $drug_id = $this->request->getPost('drug_id');
                $qty     = (int)$this->request->getPost('quantity');

                if (empty($drug_id) || empty($qty)) {
                    log_message('error', 'Missing required fields for prescription: drug_id or quantity');
                    return redirect()->back()->withInput()->with('error', 'Drug ID and quantity are required.');
                }

                // Check available drug stock before inserting
                $drug = $this->drugModel->find($drug_id);
                if (!$drug || $drug['quantity_in_stock'] < $qty) {
                    log_message('error', 'Insufficient drug stock for drug_id: ' . $drug_id);
                    return redirect()->back()->with('error', 'Insufficient drug stock available.');
                }

                $inserted = $this->prescriptionModel->insert([
                    'visit_id'     => $visit_id,
                    'drug_id'      => $drug_id,
                    'dosage'       => $this->request->getPost('dosage'),
                    'duration'     => $this->request->getPost('duration'),
                    'instructions' => $this->request->getPost('instructions'),
                    'quantity'     => $qty,
                    'route'        => $this->request->getPost('route'),
                    'other_route'  => $this->request->getPost('other_route'),
                    'created_at'   => date('Y-m-d H:i:s'),
                    'updated_at'   => date('Y-m-d H:i:s'),
                ]);

                if (!$inserted) {
                    log_message('error', 'Failed to insert prescription: ' . json_encode($this->prescriptionModel->errors()));
                    return redirect()->back()->withInput()->with('error', 'Failed to record prescription.');
                }

                // Subtract from drug stock
                $this->drugModel->set('quantity_in_stock', 'quantity_in_stock - ' . $qty, false)
                                ->where('drug_id', $drug_id)
                                ->update();
                break;

            case 'supply':
                $supply_id = $this->request->getPost('supply_id');
                $qty_used  = (int)$this->request->getPost('quantity_used');

                if (empty($supply_id) || empty($qty_used)) {
                    log_message('error', 'Missing required fields for supply: supply_id or quantity_used');
                    return redirect()->back()->withInput()->with('error', 'Supply ID and quantity are required.');
                }

                // Check available supply stock before inserting
                $supply = $this->stockSupplyModel->find($supply_id);
                if (!$supply || $supply['quantity_in_stock'] < $qty_used) {
                    log_message('error', 'Insufficient supply stock for supply_id: ' . $supply_id);
                    return redirect()->back()->with('error', 'Insufficient supply stock available.');
                }

                $inserted = $this->supplyModel->insert([
                    'visit_id'      => $visit_id,
                    'supply_id'     => $supply_id,
                    'quantity_used' => $qty_used,
                    'usage_type'    => $this->request->getPost('usage_type'),
                    'created_at'    => date('Y-m-d H:i:s'),
                    'updated_at'    => date('Y-m-d H:i:s'),
                ]);

                if (!$inserted) {
                    log_message('error', 'Failed to insert supply: ' . json_encode($this->supplyModel->errors()));
                    return redirect()->back()->withInput()->with('error', 'Failed to record supply.');
                }

                // Subtract from supply stock
                $this->stockSupplyModel->set('quantity_in_stock', 'quantity_in_stock - ' . $qty_used, false)
                                       ->where('supply_id', $supply_id)
                                       ->update();
                break;

            case 'outcome':
                $treatment_notes = $this->request->getPost('treatment_notes');
                $outcome         = $this->request->getPost('outcome');

                if (empty($treatment_notes) || empty($outcome)) {
                    log_message('error', 'Missing required fields for outcome: treatment_notes or outcome');
                    return redirect()->back()->withInput()->with('error', 'Treatment notes and outcome are required.');
                }

                $inserted = $this->outcomeModel->insert([
                    'visit_id'            => $visit_id,
                    'treatment_notes'     => $treatment_notes,
                    'outcome'             => $outcome,
                    'referral_reason'     => $this->request->getPost('referral_reason'),
                    'discharge_time'      => $this->request->getPost('discharge_time'),
                    'discharge_condition' => $this->request->getPost('discharge_condition'),
                    'return_date'         => $this->request->getPost('return_date'),
                    'follow_up_notes'     => $this->request->getPost('follow_up_notes'),
                    'created_at'          => date('Y-m-d H:i:s'),
                    'updated_at'          => date('Y-m-d H:i:s'),
                ]);

                if (!$inserted) {
                    log_message('error', 'Failed to insert outcome: ' . json_encode($this->outcomeModel->errors()));
                    return redirect()->back()->withInput()->with('error', 'Failed to record outcome.');
                }
                break;

            default:
                log_message('error', 'Invalid form submission type: ' . $type);
                return redirect()->back()->with('error', 'Invalid form submission type.');
        }

        return redirect()->to('/visits')->with('success', ucfirst($type) . ' saved successfully.')->with('visit_id', $visit_id);
    }
}