<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\VisitPrescriptionModel;
use App\Models\VisitSupplyModel;
use App\Models\VisitOutcomeModel;
use App\Models\DrugModel;
use App\Models\SupplyModel;
use App\Models\VisitModel;
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

        log_message('debug', 'Received POST data for ADD: ' . print_r($this->request->getPost(), true));

        if (empty($visit_id) || !ctype_digit($visit_id)) {
            log_message('error', 'Invalid or missing visit ID: ' . $visit_id);
            return redirect()->back()->withInput()->with('error', 'Invalid or missing visit ID.');
        }

        $visitModel = new VisitModel();
        if (!$visitModel->find($visit_id)) {
            log_message('error', 'Visit not found for ID: ' . $visit_id);
            return redirect()->back()->withInput()->with('error', 'Visit not found.');
        }

        switch ($type) {
            case 'prescription':
                $drug_id = $this->request->getPost('drug_id');
                $qty     = (int)$this->request->getPost('quantity');

                if (empty($drug_id) || empty($qty)) {
                    return redirect()->back()->withInput()->with('error', 'Drug ID and quantity are required.');
                }

                $drug = $this->drugModel->find($drug_id);
                if (!$drug || $drug['quantity_in_stock'] < $qty) {
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
                    return redirect()->back()->withInput()->with('error', 'Failed to record prescription.');
                }

                $this->drugModel->set('quantity_in_stock', 'quantity_in_stock - ' . $qty, false)
                    ->where('drug_id', $drug_id)
                    ->update();
                break;

            case 'supply':
                $supply_id = $this->request->getPost('supply_id');
                $qty_used  = (int)$this->request->getPost('quantity_used');

                if (empty($supply_id) || empty($qty_used)) {
                    return redirect()->back()->withInput()->with('error', 'Supply ID and quantity are required.');
                }

                $supply = $this->stockSupplyModel->find($supply_id);
                if (!$supply || $supply['quantity_in_stock'] < $qty_used) {
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
                    return redirect()->back()->withInput()->with('error', 'Failed to record supply usage.');
                }

                $this->stockSupplyModel->set('quantity_in_stock', 'quantity_in_stock - ' . $qty_used, false)
                    ->where('supply_id', $supply_id)
                    ->update();
                break;

            case 'outcome':
                $treatment_notes = $this->request->getPost('treatment_notes');
                $outcome         = $this->request->getPost('outcome');

                if (empty($treatment_notes) || empty($outcome)) {
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
                    return redirect()->back()->withInput()->with('error', 'Failed to record outcome.');
                }
                break;

            default:
                return redirect()->back()->with('error', 'Invalid form submission type.');
        }

        return redirect()->to('/visits')->with('success', ucfirst($type) . ' saved successfully.');
    }

    public function updateDetail()
    {
        $type     = $this->request->getPost('type');
        $visit_id = $this->request->getPost('visit_id');

        if (empty($type) || empty($visit_id)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Missing data for update.');
        }

        log_message('debug', "Updating [{$type}] for visit ID: {$visit_id}");
        log_message('debug', 'Payload: ' . print_r($this->request->getPost(), true));

        $validation = \Config\Services::validation();

        switch ($type) {
            case 'prescription':
                // 1) Validation rules
                $rules = [
                    'prescription_id' => 'required|is_natural_no_zero',
                    'visit_id'        => 'required|is_natural_no_zero',
                    'drug_id'         => 'required|is_natural_no_zero',
                    'dosage'          => 'required|string|max_length[100]',
                    'duration'        => 'required|string|max_length[100]',
                    'instructions'    => 'permit_empty|string|max_length[255]',
                    'quantity'        => 'required|is_natural',
                    'route'           => 'required|string|max_length[50]',
                    'other_route'     => 'permit_empty|string|max_length[50]',
                ];
                if (! $this->validate($rules)) {
                    return redirect()->back()
                        ->withInput()
                        ->with('errors', $validation->getErrors());
                }

                // 2) Fetch old prescription
                $prescId = $this->request->getPost('prescription_id');
                $old     = $this->prescriptionModel->find($prescId);

                $oldQty  = (int)$old['quantity'];
                $newQty  = (int)$this->request->getPost('quantity');
                $oldDrug = $old['drug_id'];
                $newDrug = $this->request->getPost('drug_id');

                // 3) Adjust stock
                if ($oldDrug !== $newDrug) {
                    // refund old drug entirely
                    $this->drugModel
                        ->set('quantity_in_stock', "quantity_in_stock + {$oldQty}", false)
                        ->where('drug_id', $oldDrug)
                        ->update();

                    // consume new drug
                    $this->drugModel
                        ->set('quantity_in_stock', "quantity_in_stock - {$newQty}", false)
                        ->where('drug_id', $newDrug)
                        ->update();
                } else {
                    $diff = $newQty - $oldQty;
                    if ($diff > 0) {
                        // need more
                        $this->drugModel
                            ->set('quantity_in_stock', "quantity_in_stock - {$diff}", false)
                            ->where('drug_id', $newDrug)
                            ->update();
                    } elseif ($diff < 0) {
                        // refund the difference
                        $this->drugModel
                            ->set('quantity_in_stock', "quantity_in_stock + " . abs($diff), false)
                            ->where('drug_id', $newDrug)
                            ->update();
                    }
                }

                // 4) Update the prescription
                $data = [
                    'drug_id'      => $newDrug,
                    'dosage'       => $this->request->getPost('dosage'),
                    'duration'     => $this->request->getPost('duration'),
                    'instructions' => $this->request->getPost('instructions'),
                    'quantity'     => $newQty,
                    'route'        => $this->request->getPost('route'),
                    'other_route'  => $this->request->getPost('other_route'),
                    'updated_at'   => date('Y-m-d H:i:s'),
                ];
                if (! $this->prescriptionModel->update($prescId, $data)) {
                    return redirect()->back()
                        ->withInput()
                        ->with('error', 'Failed to update prescription.');
                }
                break;

            case 'supply':
                $rules = [
                    'visit_supplies_id' => 'required|is_natural_no_zero',
                    'supply_id'         => 'required|is_natural_no_zero',
                    'quantity_used'     => 'required|is_natural',
                    'usage_type'        => 'required|string|max_length[100]',
                ];
                if (! $this->validate($rules)) {
                    return redirect()->back()
                        ->withInput()
                        ->with('errors', $validation->getErrors());
                }
                $usageId = $this->request->getPost('visit_supplies_id');
                $upd     = [
                    'supply_id'     => $this->request->getPost('supply_id'),
                    'quantity_used' => $this->request->getPost('quantity_used'),
                    'usage_type'    => $this->request->getPost('usage_type'),
                    'updated_at'    => date('Y-m-d H:i:s'),
                ];
                if (! $this->supplyModel->update($usageId, $upd)) {
                    return redirect()->back()
                        ->withInput()
                        ->with('error', 'Failed to update supply usage.');
                }
                break;

            case 'outcome':
                $rules = [
                    'outcome_id'         => 'required|is_natural_no_zero',
                    'outcome'            => 'required|in_list[Discharged,Referred]',
                    'treatment_notes'    => 'required|string|max_length[500]',
                    'referral_reason'    => 'permit_empty|string|max_length[255]',
                    'discharge_time'     => 'permit_empty|valid_date',
                    'discharge_condition' => 'permit_empty|string|max_length[255]',
                    'return_date'        => 'permit_empty|valid_date',
                    'follow_up_notes'    => 'permit_empty|string|max_length[500]',
                ];
                if (! $this->validate($rules)) {
                    return redirect()->back()
                        ->withInput()
                        ->with('errors', $validation->getErrors());
                }
                $ocId = $this->request->getPost('outcome_id');
                $upd  = [
                    'outcome'             => $this->request->getPost('outcome'),
                    'treatment_notes'     => $this->request->getPost('treatment_notes'),
                    'referral_reason'     => $this->request->getPost('referral_reason'),
                    'discharge_time'      => $this->request->getPost('discharge_time'),
                    'discharge_condition' => $this->request->getPost('discharge_condition'),
                    'return_date'         => $this->request->getPost('return_date'),
                    'follow_up_notes'     => $this->request->getPost('follow_up_notes'),
                    'updated_at'          => date('Y-m-d H:i:s'),
                ];
                if (! $this->outcomeModel->update($ocId, $upd)) {
                    return redirect()->back()
                        ->withInput()
                        ->with('error', 'Failed to update outcome.');
                }
                break;

            default:
                return redirect()->back()
                    ->with('error', 'Invalid update type.');
        }

        return redirect()->back()
            ->with('success', ucfirst($type) . ' updated successfully.');
    }

    public function fetchEditDetails($visit_id)
    {
        $prescriptions = $this->prescriptionModel->where('visit_id', $visit_id)->findAll();
        $supplies = $this->supplyModel->where('visit_id', $visit_id)->findAll();
        $outcome = $this->outcomeModel->where('visit_id', $visit_id)->first();

        // 🔁 Prepare drug names (drug_id => name)
        $drugList = $this->drugModel->findAll();
        $drugMap = array_column($drugList, 'name', 'drug_id');

        // 🔁 Inject drug_name into each prescription
        $prescriptionsHtml = '';
        foreach ($prescriptions as $p) {
            $p['drug_name'] = $drugMap[$p['drug_id']] ?? 'Unknown';
            $prescriptionsHtml .= view('partials/cards/prescription_card', [
                'p' => $p,
                'drugs' => $drugList
            ]);
        }

        $supplyList = $this->stockSupplyModel->findAll();
        $supplyMap = array_column($supplyList, 'name', 'supply_id');

        $suppliesHtml = '';
        foreach ($supplies as $s) {
            $s['supply_name'] = $supplyMap[$s['supply_id']] ?? 'Unknown';

            if (!isset($s['supply_usage_id']) && isset($s['id'])) {
                $s['supply_usage_id'] = $s['id'];
            }

            $suppliesHtml .= view('partials/cards/supply_card', [
                'supply' => $s,
                'supplies' => $supplyList
            ]);
        }

        $outcomesHtml = view('partials/cards/outcome_card', [
            'outcome' => $outcome
        ]);

        return $this->response->setJSON([
            'prescriptionsHtml' => $prescriptionsHtml,
            'suppliesHtml' => $suppliesHtml,
            'outcomesHtml' => $outcomesHtml
        ]);
    }
}
