<?php

namespace App\Models;

use CodeIgniter\Model;

class VisitSupplyModel extends Model
{
    protected $table            = 'visit_supplies';
    protected $primaryKey       = 'visit_supplies_id';
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';

    protected $allowedFields = [
        'visit_id',
        'supply_id',
        'quantity_used',
        'usage_type'
    ];
}
