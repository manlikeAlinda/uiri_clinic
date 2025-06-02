<?php

namespace App\Models;
use CodeIgniter\Model;

class PatientModel extends Model
{
    protected $table = 'patients';
    protected $primaryKey = 'patient_id';

    protected $allowedFields = [
        'first_name',
        'last_name',
        'date_of_birth',
        'gender',
        'contact_info',
        'weight', // 👈 added here
        'medical_history',
        'next_of_kin_contact',
    ];
    

    protected $useTimestamps = true; // Manages created_at and updated_at automatically
}
