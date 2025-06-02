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
        $patients = $this->patientModel->findAll();
        return view('manage_patients', [
            'patients' => $patients,
            'title' => 'Manage Patients'
        ]);
    }

    public function store()
    {
        $validation = \Config\Services::validation();

        $data = [
            'first_name'          => $this->request->getPost('first_name'),
            'last_name'           => $this->request->getPost('last_name'),
            'date_of_birth'       => $this->request->getPost('date_of_birth'),
            'gender'              => $this->request->getPost('gender'),
            'contact_info'        => $this->request->getPost('contact_info'),
            'weight'              => $this->request->getPost('weight'),
            'medical_history'     => $this->request->getPost('medical_history'),
            'next_of_kin_contact' => $this->request->getPost('next_of_kin_contact'),
        ];

        if (!$validation->setRules([
            'first_name'          => 'required',
            'last_name'           => 'required',
            'date_of_birth'       => 'required',
            'gender'              => 'required',
            'contact_info'        => 'permit_empty',
            'weight' => 'permit_empty|decimal',
            'medical_history'     => 'permit_empty',
            'next_of_kin_contact' => 'permit_empty',
        ])->run($data)) {
            return redirect()->to('/patients')->withInput()->with('errors', $validation->getErrors());
        }

        $this->patientModel->save($data);
        return redirect()->to('/patients')->with('success', 'Patient added successfully.');
    }

    public function update()
    {
        $id = $this->request->getPost('patient_id');

        $data = [
            'first_name'          => $this->request->getPost('first_name'),
            'last_name'           => $this->request->getPost('last_name'),
            'date_of_birth'       => $this->request->getPost('date_of_birth'),
            'gender'              => $this->request->getPost('gender'),
            'contact_info'        => $this->request->getPost('contact_info'),
            'medical_history'     => $this->request->getPost('medical_history'),
            'next_of_kin_contact' => $this->request->getPost('next_of_kin_contact'),
        ];

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
