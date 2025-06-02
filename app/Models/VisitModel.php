<?php

namespace App\Models;

use CodeIgniter\Model;

class VisitModel extends Model
{
    protected $table = 'visits';
    protected $primaryKey = 'visit_id';

    protected $allowedFields = [
        'patient_id',
        'doctor_id',
        'visit_date',
        'weight',
        'observations',
        'diagnosis',
        'treatment_notes',
        'patient_condition',
        'admission_time',
        'discharge_time',
        'referral_notes',
        'visit_status',          // <- ✅ include this if you're saving status
        'blood_pressure',        // <- ✅ vitals
        'heart_rate',            // <- ✅ vitals
        'temperature'            // <- ✅ vitals
    ];


    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Get all visits with patient and doctor names joined in.
     */
    public function getVisitsWithRelations()
    {
        return $this->select('visits.*, CONCAT(p.first_name, " ", p.last_name) AS patient_name, CONCAT(d.first_name, " ", d.last_name) AS doctor_name')
            ->join('patients p', 'p.patient_id = visits.patient_id')
            ->join('doctors d', 'd.doctor_id = visits.doctor_id')
            ->orderBy('visits.visit_date', 'DESC')
            ->findAll();
    }

    public function addPrescription($data)
    {
        $quantity = isset($data['quantity']) ? intval($data['quantity']) : 1;

        // Fetch current stock
        $drug = $this->db->table('drugs')
            ->select('quantity_in_stock')
            ->where('drug_id', $data['drug_id'])
            ->get()
            ->getRow();

        if (!$drug || $drug->quantity_in_stock < $quantity) {
            return false; // Not enough stock
        }

        // Insert prescription
        $this->db->table('visit_prescriptions')->insert($data);

        // Deduct from stock
        $this->db->table('drugs')
            ->where('drug_id', $data['drug_id'])
            ->set('quantity_in_stock', "quantity_in_stock - {$quantity}", false)
            ->update();

        return true;
    }

    public function deleteVisitAndRollbackStock($visitId)
    {
        $db = \Config\Database::connect();
        $db->transBegin();

        try {
            // 1. Restore drug stock
            $prescriptions = $db->table('visit_prescriptions')->where('visit_id', $visitId)->get()->getResult();
            foreach ($prescriptions as $presc) {
                $db->table('drugs')
                    ->where('drug_id', $presc->drug_id)
                    ->set('quantity_in_stock', "quantity_in_stock + {$presc->quantity}", false)
                    ->update();
            }

            // 2. Restore supplies
            $supplies = $db->table('visit_supplies')->where('visit_id', $visitId)->get()->getResult();
            foreach ($supplies as $supply) {
                $db->table('supplies')
                    ->where('supply_id', $supply->supply_id)
                    ->set('quantity_in_stock', "quantity_in_stock + {$supply->quantity_used}", false)
                    ->update();
            }

            // 3. Delete related records
            $db->table('visit_prescriptions')->where('visit_id', $visitId)->delete();
            $db->table('visit_equipment')->where('visit_id', $visitId)->delete();
            $db->table('visit_supplies')->where('visit_id', $visitId)->delete();

            // 4. Delete the main visit
            $db->table('visits')->where('visit_id', $visitId)->delete();

            $db->transCommit();
            return true;
        } catch (\Exception $e) {
            $db->transRollback();
            log_message('error', 'Visit deletion failed: ' . $e->getMessage());
            return false;
        }
    }


    public function addSupplyUsage($data)
    {
        $supply = $this->db->table('supplies')
            ->select('quantity_in_stock')
            ->where('supply_id', $data['supply_id'])
            ->get()
            ->getRow();

        if (!$supply || $supply->quantity_in_stock < $data['quantity_used']) {
            return false; // Not enough supply
        }

        // Insert usage record including usage_type
        $this->db->table('visit_supplies')->insert([
            'visit_id'      => $data['visit_id'],
            'supply_id'     => $data['supply_id'],
            'quantity_used' => $data['quantity_used'],
            'usage_type'    => $data['usage_type'] ?? 'standard' // fallback if missing
        ]);

        // Deduct supply stock
        $this->db->table('supplies')
            ->where('supply_id', $data['supply_id'])
            ->set('quantity_in_stock', 'quantity_in_stock - ' . intval($data['quantity_used']), false)
            ->update();

        return true;
    }
}
