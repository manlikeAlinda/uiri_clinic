<?php

namespace App\Models;

use CodeIgniter\Model;

class EquipmentModel extends Model
{
    protected $table = 'equipment';
    protected $primaryKey = 'equipment_id';
    protected $allowedFields = [
        'name', 'quantity_in_stock', 'status', 'batch_no', 'created_at', 'updated_at'
    ];
    protected $useTimestamps = true; // Auto set created_at and updated_at
}
