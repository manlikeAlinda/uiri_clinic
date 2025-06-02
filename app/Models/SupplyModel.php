<?php

namespace App\Models;

use CodeIgniter\Model;

class SupplyModel extends Model
{
    protected $table = 'supplies';
    protected $primaryKey = 'supply_id';

    protected $allowedFields = [
        'name',
        'quantity_in_stock',
        'batch_no',
        'manufacture_date',
        'expiration_date',
    ];

    protected $useTimestamps = true; // to auto-fill created_at and updated_at
}
