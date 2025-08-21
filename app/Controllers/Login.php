<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;

class Login extends BaseController
{
    public function index()
    {
        return view('auth/login', ['title' => 'Login | UIRI Clinic']);
    }

    public function authenticate()
    {
        $session = session();
        $model   = new UserModel();

        $username = trim((string) $this->request->getPost('username'));
        $password = (string) $this->request->getPost('password');

        // Fetch auth profile: user_id, username, password, role, status, doctor_id
        $auth = $model->getAuthProfileByUsername($username);

        if (!$auth || !password_verify($password, $auth['password'])) {
            $session->setFlashdata('error', 'Invalid username or password');
            return redirect()->to('/login');
        }

        // Normalize role safely: 'admin' -> Admin, 'doctor' -> Doctor, else -> User
        // (normalizeRole returns title-cased canonical name)
        $role = UserModel::normalizeRole($auth['role'] ?? null) ?? 'User';

        // Optional: block inactive users
        if (!UserModel::isActive($auth)) {
            $session->setFlashdata('error', 'Your account is inactive. Contact the administrator.');
            return redirect()->to('/login');
        }

        // If Doctor, enforce mapping to a doctors row
        if ($role === 'Doctor' && empty($auth['doctor_id'])) {
            $session->setFlashdata('error', 'Your account is not linked to a doctor profile. Contact admin.');
            return redirect()->to('/login');
        }

        // Security best practice: new session ID on privilege change
        $session->regenerate();

        // Clear any stale per-user state that could leak across logins
        $session->remove(['visit_id', 'filters']);

        // Set session
        $session->set([
            'user_id'      => (int) $auth['user_id'],
            'user_name'    => $auth['username'],
            'role'         => $role,                                   // 'Admin' | 'Doctor' | 'User'
            'doctor_id'    => $role === 'Doctor' ? (int) $auth['doctor_id'] : null,
            'is_logged_in' => true,
        ]);

        // Route by role
        if ($role === 'Admin' || $role === 'Doctor') {
            return redirect()->to('/visits');  // aligns with VisitController RBAC
        }

        // Default landing for non-clinical roles
        return redirect()->to('/');            // or '/dashboard' if you have one
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}
