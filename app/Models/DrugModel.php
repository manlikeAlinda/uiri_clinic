<?php

namespace App\Models;

use CodeIgniter\Model;

class DrugModel extends Model
{
    protected $table = 'drugs';
    protected $primaryKey = 'drug_id';

    protected $allowedFields = [
        'name', 'dosage', 'quantity_in_stock', 'batch_no',
        'manufacture_date', 'expiration_date', 'status'
    ];

    protected $useTimestamps = true;
}
