<?php

namespace App\Controllers;
use App\Models\UserModel;

class Register extends BaseController
{
    public function index()
    {
        return view('auth/register', ['title' => 'Register']);
    }

    public function save()
    {
        $session = session();
        $validation = \Config\Services::validation();
    
        $rules = [
            'username' => 'required|min_length[3]|max_length[50]|is_unique[users.username]',
            'password' => 'required|min_length[6]',
            'confirm_password' => 'required|matches[password]'
        ];
    
        if (!$this->validate($rules)) {
            return redirect()->back()
                             ->withInput()
                             ->with('error', implode('<br>', $validation->getErrors()));
        }
    
        $model = new UserModel();
        $model->save([
            'username' => $this->request->getPost('username'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT)
        ]);
    
        return redirect()->to('/login')->with('success', 'Account created successfully! Please log in.');
    }
    
}
