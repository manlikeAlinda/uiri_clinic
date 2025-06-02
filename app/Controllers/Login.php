<?php

namespace App\Controllers;
use App\Models\UserModel;

class Login extends BaseController
{
    public function index()
    {
        return view('auth/login', [
            'title' => 'Login | UIRI Clinic'
        ]);
    }

    public function authenticate()
    {
        $session = session();
        $model = new UserModel();

        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $user = $model->where('username', $username)->first();

        if ($user && password_verify($password, $user['password'])) {
            $session->set([
                'user_id'      => $user['user_id'],
                'user_name'    => $user['username'],
                'is_logged_in' => true
            ]);
            return redirect()->to('/');
        } else {
            $session->setFlashdata('error', 'Invalid username or password');
            return redirect()->to('/login');
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}
