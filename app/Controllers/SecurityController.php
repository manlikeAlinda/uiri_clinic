<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class SecurityController extends Controller
{
    public function getToken()
    {
        return $this->response->setJSON([
            'token' => csrf_hash()
        ]);
    }
}
