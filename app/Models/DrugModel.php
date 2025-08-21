<?php

namespace App\Models;

use CodeIgniter\Model;

class DrugModel extends Model
{
    // Do NOT add scalar types here (string/bool/array), because parent has them untyped
    protected $table      = 'drugs';
    protected $primaryKey = 'drug_id';

    protected $allowedFields = [
        'name', 'dosage', 'quantity_in_stock', 'batch_no',
        'manufacture_date', 'expiration_date', 'status',
        'reorder_level', 'reorder_quantity',
        // add 'is_usable' here only if you created that column
        // 'is_usable',
    ];

    protected $useTimestamps = true;

    // Tip: If you previously added $casts and saw a type error,
    // either remove it entirely OR ensure its type matches the parent exactly.
    // For many CI versions, you can simply omit $casts and inherit defaults.
    // If you *do* need it, add it back as shown in the note below.
}
