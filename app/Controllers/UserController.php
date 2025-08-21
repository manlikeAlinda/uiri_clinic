<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\DoctorModel;
use Config\Database;
class UserController extends BaseController
{
    protected UserModel $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    /**
     * GET /users
     * List users (10 per page), with optional search + date + role filters.
     */
    public function index()
    {
        // 1) Fetch raw filter inputs
        $filters = [
            'q'         => trim((string) $this->request->getGet('q')),
            'role'      => trim((string) $this->request->getGet('role')),
            'date_from' => $this->request->getGet('date_from'),
            'date_to'   => $this->request->getGet('date_to'),
        ];

        // 2) Build the query
        $builder = $this->userModel;
        if ($filters['q'] !== '') {
            $builder = $builder->like('username', $filters['q']);
        }
        if ($filters['role'] !== '') {
            $builder = $builder->where('role', $filters['role']);
        }
        if ($filters['date_from']) {
            $builder = $builder->where('created_at >=', $filters['date_from'] . ' 00:00:00');
        }
        if ($filters['date_to']) {
            $builder = $builder->where('created_at <=', $filters['date_to'] . ' 23:59:59');
        }

        // 3) Fetch available roles from the model
        $roles = $this->userModel->getAvailableRoles();

        // 4) Set the current page based on the URI segment
        $current_page = $this->request->getUri()->getSegment(1) ?: 'dashboard'; // Use getUri()

        // 5) Define breadcrumb data
        $breadcrumb = $this->getBreadcrumb($current_page);

        // 6) Paginate + order
        $data = [
            'users'   => $builder->orderBy('created_at', 'DESC')->paginate(10),
            'pager'   => $this->userModel->pager,
            'filters' => $filters,
            'roles'   => $roles,
            'current_page' => $current_page,
            'breadcrumb' => $breadcrumb, // Pass breadcrumb data
        ];

        return view('manage_users', $data);
    }

    // Helper method to generate breadcrumb
    private function getBreadcrumb($current_page)
    {
        $breadcrumbs = [
            'dashboard' => ['title' => 'Dashboard', 'url' => base_url('dashboard')],
            'users' => ['title' => 'Manage Users', 'url' => base_url('users')],
            'patients' => ['title' => 'Manage Patients', 'url' => base_url('patients')],
            'doctors' => ['title' => 'Manage Doctors', 'url' => base_url('doctors')],
            'reports' => ['title' => 'Reports', 'url' => base_url('reports')],
            'supplies' => ['title' => 'Manage Supplies', 'url' => base_url('supplies')],
            'drugs' => ['title' => 'Manage Drugs', 'url' => base_url('drugs')],
            'visits' => ['title' => 'Manage Visits', 'url' => base_url('visits')],
        ];

        $result = [];
        $result[] = ['title' => 'Dashboard', 'url' => base_url('dashboard'), 'active' => false];
        if (isset($breadcrumbs[$current_page])) {
            $result[] = ['title' => $breadcrumbs[$current_page]['title'], 'url' => $breadcrumbs[$current_page]['url'], 'active' => true];
        }
        return $result;
    }

    /**
     * POST /users/store
     * Create a new user.
     */
    public function store()
    {
        $rules = [
            'username' => 'required|alpha_numeric_space|min_length[3]|is_unique[users.username]',
            'role'     => 'required|in_list[' . implode(',', $this->userModel->getAvailableRoles()) . ']',
            'password' => 'required|min_length[6]',
            // When role=Doctor, validate doctor fields too
            'doc_first_name' => 'permit_empty',
            'doc_last_name'  => 'permit_empty',
            'doc_email'      => 'permit_empty|valid_email',
            'doc_phone'      => 'permit_empty|min_length[10]|max_length[15]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        $db = Database::connect();
        $db->transStart();

        // 1) Create the user
        $userId = $this->userModel->insert([
            'username' => $this->request->getPost('username'),
            'role'     => $this->request->getPost('role'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'status'   => 'active',
        ], true); // returns insert id

        // 2) If role=Doctor, create the doctor profile and link to the user
        if ($this->request->getPost('role') === 'Doctor') {
            $doc = new DoctorModel();
            $doc->insert([
                'user_id'      => $userId,
                'first_name'   => $this->request->getPost('doc_first_name'),
                'last_name'    => $this->request->getPost('doc_last_name'),
                'email'        => $this->request->getPost('doc_email'),
                'phone_number' => $this->request->getPost('doc_phone'),
            ]);
        }

        $db->transComplete();

        if (! $db->transStatus()) {
            return redirect()->back()->withInput()->with('error', 'Failed to create user.');
        }

        return redirect()->to('/users')->with('success', 'User created successfully.');
    }

    /**
     * POST /users/update/{id}
     * Update an existing user. Password is optional.
     */
    public function update($id = null)
    {
        $user = $this->userModel->find($id);
        if (! $user) {
            return redirect()->to('/users')
                ->with('error', 'User not found.');
        }

        $rules = [
            'username' => "required|alpha_numeric_space|min_length[3]|is_unique[users.username,user_id,{$id}]",
            'role'     => 'required|in_list[' . implode(',', $this->userModel->getAvailableRoles()) . ']',
            'password' => 'permit_empty|min_length[6]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('validation', $this->validator);
        }

        $payload = [
            'username' => $this->request->getPost('username'),
            'role'     => $this->request->getPost('role'),
        ];

        if ($pw = $this->request->getPost('password')) {
            $payload['password'] = password_hash($pw, PASSWORD_DEFAULT);
        }

        $this->userModel->update($id, $payload);

        return redirect()->to('/users')
            ->with('success', 'User updated successfully.');
    }

    /**
     * POST /users/delete
     * Delete a user.
     */
    public function delete()
    {
        $id = $this->request->getPost('user_id');
        if (! $id || ! ctype_digit((string)$id) || ! $this->userModel->find($id)) {
            return redirect()->to('/users')
                ->with('error', 'Invalid User ID.');
        }

        $this->userModel->delete($id);

        return redirect()->to('/users')
            ->with('success', 'User deleted successfully.');
    }
}
