<?php

namespace App\Models;

use CodeIgniter\Model;

class VisitOutcomeModel extends Model
{
    protected $table      = 'visit_outcomes';
    protected $primaryKey = 'outcome_id';
    protected $useTimestamps = true;
    protected $allowedFields = [
        'visit_id', 'treatment_notes', 'outcome', 'referral_reason',
        'discharge_time', 'discharge_condition', 'return_date', 'follow_up_notes'
    ];
}
