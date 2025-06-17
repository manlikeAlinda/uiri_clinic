<?php

namespace App\Models;

use CodeIgniter\Model;

class VisitPrescriptionModel extends Model
{
    protected $table            = 'visit_prescriptions';
    protected $primaryKey       = 'prescription_id';
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';

    protected $allowedFields = [
        'visit_id',
        'drug_id',
        'dosage',
        'duration',
        'instructions',
        'quantity',
        'route',
        'other_route'
    ];
}
