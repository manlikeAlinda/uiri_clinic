<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\EquipmentModel;

class EquipmentController extends BaseController
{
    protected $equipmentModel;

    public function __construct()
    {
        $this->equipmentModel = new EquipmentModel();
    }

    public function index()
    {
        $perPage = 10; // Show 10 equipment items per page

        $data['equipment'] = $this->equipmentModel->paginate($perPage);
        $data['pager'] = $this->equipmentModel->pager;

        return view('manage_equipment', $data);
    }

    public function store()
    {
        $rules = [
            'name' => 'required|min_length[2]',
            'quantity_in_stock' => 'required|integer',
            'status' => 'required|in_list[Available,Unavailable,Under Repair]',
            'batch_no' => 'required|min_length[2]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        $this->equipmentModel->save([
            'name' => $this->request->getPost('name'),
            'quantity_in_stock' => $this->request->getPost('quantity_in_stock'),
            'status' => $this->request->getPost('status'),
            'batch_no' => $this->request->getPost('batch_no'),
        ]);

        return redirect()->to(base_url('equipment'))->with('success', 'Equipment added successfully!');
    }

    public function update()
    {
        $id = $this->request->getPost('id');

        $rules = [
            'name' => 'required|min_length[2]',
            'quantity_in_stock' => 'required|integer',
            'status' => 'required|in_list[Available,Unavailable,Under Repair]',
            'batch_no' => 'required|min_length[2]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        $this->equipmentModel->update($id, [
            'name' => $this->request->getPost('name'),
            'quantity_in_stock' => $this->request->getPost('quantity_in_stock'),
            'status' => $this->request->getPost('status'),
            'batch_no' => $this->request->getPost('batch_no'),
        ]);

        return redirect()->to(base_url('equipment'))->with('success', 'Equipment updated successfully!');
    }

    public function delete()
    {
        $id = $this->request->getPost('id');
        $this->equipmentModel->delete($id);

        return redirect()->to(base_url('equipment'))->with('success', 'Equipment deleted successfully!');
    }
}
