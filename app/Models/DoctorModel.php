<?php

namespace App\Models;

use CodeIgniter\Model;

class DoctorModel extends Model
{
    protected $table = 'doctors';
    protected $primaryKey = 'doctor_id';
    protected $allowedFields = ['user_id', 'first_name', 'last_name', 'email', 'phone_number'];
    protected $useTimestamps = true;
}
