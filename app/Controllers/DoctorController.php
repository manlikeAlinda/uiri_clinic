<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\DoctorModel;

class DoctorController extends BaseController
{
    protected $doctorModel;

    public function __construct()
    {
        $this->doctorModel = new DoctorModel();
    }

    /**
     * Display a list of all doctors
     */
    public function index()
    {
        // per-page comes from query (?per_page=25) with a sensible default
        $perPage = (int) ($this->request->getGet('per_page') ?? 10);
        $group   = 'doctors'; // must match the view’s pager group

        // Optional: simple search (?q=term) across key columns
        $q = trim((string) $this->request->getGet('q'));
        $builder = $this->doctorModel->orderBy('last_name', 'ASC');

        if ($q !== '') {
            $builder = $builder->groupStart()
                ->like('first_name', $q)
                ->orLike('last_name', $q)
                ->orLike('email', $q)
                ->orLike('phone_number', $q)
                ->groupEnd();
        }

        // Use paginate so $pager is created and reads ?page_doctors=#
        $data['doctors'] = $builder->paginate($perPage, $group);
        $data['pager']   = $this->doctorModel->pager; // <<< fix: pass pager
        $data['perPage'] = $perPage;                  // used by the rows dropdown
        $data['q']       = $q;                        // keep search term in the UI if you add a search box

        return view('manage_doctors', $data);
    }

    /**
     * Store a new doctor after form submission
     */
    public function store()
    {
        $rules = [
            'first_name'    => 'required|alpha_space|min_length[2]',
            'last_name'     => 'required|alpha_space|min_length[2]',
            'email'         => 'required|valid_email|is_unique[doctors.email]',
            'phone_number'  => 'required|min_length[10]|max_length[15]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        $this->doctorModel->save([
            'first_name'    => $this->request->getPost('first_name'),
            'last_name'     => $this->request->getPost('last_name'),
            'email'         => $this->request->getPost('email'),
            'phone_number'  => $this->request->getPost('phone_number'),
        ]);

        return redirect()->to(base_url('doctors'))->with('success', 'Doctor added successfully!');
    }

    /**
     * Update an existing doctor
     */
    public function update()
    {
        $id = $this->request->getPost('id');

        $rules = [
            'first_name'    => 'required|alpha_space|min_length[2]',
            'last_name'     => 'required|alpha_space|min_length[2]',
            'email'         => 'required|valid_email',
            'phone_number'  => 'required|min_length[10]|max_length[15]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        $this->doctorModel->update($id, [
            'first_name'    => $this->request->getPost('first_name'),
            'last_name'     => $this->request->getPost('last_name'),
            'email'         => $this->request->getPost('email'),
            'phone_number'  => $this->request->getPost('phone_number'),
        ]);

        return redirect()->to(base_url('doctors'))->with('success', 'Doctor updated successfully!');
    }

    public function delete()
    {
        $id = $this->request->getPost('id');

        if ($this->doctorModel->delete($id)) {
            return redirect()->to(base_url('doctors'))->with('success', 'Doctor deleted successfully!');
        } else {
            return redirect()->to(base_url('doctors'))->with('error', 'Failed to delete doctor.');
        }
    }
}
