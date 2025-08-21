<?php
namespace App\Models;

use CodeIgniter\Model;

class SupplyModel extends Model
{
    protected $table         = 'supplies';
    protected $primaryKey    = 'supply_id';
    protected $useTimestamps = true;

    protected $allowedFields = [
        'name', 'quantity_in_stock', 'batch_no',
        'manufacture_date', 'expiration_date',
        'reorder_level', 'reorder_quantity',
    ];
}

