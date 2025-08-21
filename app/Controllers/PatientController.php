<?php

namespace App\Controllers;

use App\Models\PatientModel;

class PatientController extends BaseController
{
    protected $patientModel;

    public function __construct()
    {
        $this->patientModel = new PatientModel();
    }

    public function index()
    {
        $allowed = [10, 25, 50, 100];
        $perPage = (int)($this->request->getGet('per_page') ?? 10);
        if (! in_array($perPage, $allowed, true)) {
            $perPage = 10;
        }

        $patients = $this->patientModel
            ->orderBy('last_name', 'asc')
            ->paginate($perPage, 'patients');

        return view('manage_patients', [
            'patients' => $patients,
            'pager'    => $this->patientModel->pager,
            'title'    => 'Manage Patients',
            'perPage'  => $perPage,
        ]);
    }

    public function store()
    {
        $validation = \Config\Services::validation();

        $data = [
            'first_name'               => trim((string) $this->request->getPost('first_name')),
            'last_name'                => trim((string) $this->request->getPost('last_name')),
            'date_of_birth'            => $this->request->getPost('date_of_birth'),
            'gender'                   => $this->request->getPost('gender'),
            'contact_info'             => $this->request->getPost('contact_info'),
            'medical_history'          => $this->request->getPost('medical_history'),
            'next_of_kin_contact'      => $this->request->getPost('next_of_kin_contact'),
            'next_of_kin_relationship' => $this->request->getPost('next_of_kin_relationship'),
        ];

        $rules = [
            'first_name'               => 'required',
            'last_name'                => 'required',
            'date_of_birth'            => 'required|valid_date',
            'gender'                   => 'required|in_list[Male,Female]',
            'medical_history'          => 'permit_empty',
            'contact_info'             => 'permit_empty',
            'next_of_kin_contact'      => 'permit_empty',
            'next_of_kin_relationship' => 'required|in_list[Spouse,Child,Parent,Sibling,Guardian]',
        ];

        if (! $validation->setRules($rules)->run($data)) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $this->patientModel->save($data);
        return redirect()->to('/patients')->with('success', 'Patient added successfully.');
    }

    public function update()
    {
        $id = $this->request->getPost('patient_id');

        $data = [
            'first_name'               => trim((string) $this->request->getPost('first_name')),
            'last_name'                => trim((string) $this->request->getPost('last_name')),
            'date_of_birth'            => $this->request->getPost('date_of_birth'),
            'gender'                   => $this->request->getPost('gender'),
            'contact_info'             => $this->request->getPost('contact_info'),
            'medical_history'          => $this->request->getPost('medical_history'),
            'next_of_kin_contact'      => $this->request->getPost('next_of_kin_contact'),
            'next_of_kin_relationship' => $this->request->getPost('next_of_kin_relationship'),
        ];

        $rules = [
            'first_name'               => 'required',
            'last_name'                => 'required',
            'date_of_birth'            => 'required|valid_date',
            'gender'                   => 'required|in_list[Male,Female]',
            'next_of_kin_relationship' => 'required|in_list[Spouse,Child,Parent,Sibling,Guardian]',
        ];

        $validation = \Config\Services::validation();
        if (! $validation->setRules($rules)->run($data)) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $this->patientModel->update($id, $data);
        return redirect()->to('/patients')->with('success', 'Patient updated successfully.');
    }

    public function delete()
    {
        $id = $this->request->getPost('patient_id');
        $this->patientModel->delete($id);
        return redirect()->to('/patients')->with('success', 'Patient deleted successfully.');
    }
}
