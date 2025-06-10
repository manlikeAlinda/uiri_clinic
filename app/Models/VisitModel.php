<?php
namespace App\Models;

use CodeIgniter\Model;

class VisitModel extends Model
{
    protected $table         = 'visits';
    protected $primaryKey    = 'visit_id';
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $allowedFields = [
        'patient_id',
        'doctor_id',
        'visit_date',
        'visit_category',
        'admission_time',
        'blood_pressure',
        'weight',
        'pulse',
        'temperature',
        'sp02',
        'respiration_rate',
        'patient_complaints',
        'examination_notes',
        'investigations',
        'diagnosis',
    ];

    /**
     * Fetch visits with patient and doctor names
     * Ordered by most recent visit_date first
     *
     * @return array
     */
    public function getVisitsWithRelations(): array
    {
        return $this->select(
                    'visits.*, ' .
                    'CONCAT(p.first_name, " ", p.last_name) AS patient_name, ' .
                    'CONCAT(d.first_name, " ", d.last_name) AS doctor_name'
                )
                ->join('patients p', 'p.patient_id = visits.patient_id')
                ->join('doctors d',  'd.doctor_id = visits.doctor_id')
                ->orderBy('visits.visit_date', 'DESC')
                ->findAll();
    }
}
