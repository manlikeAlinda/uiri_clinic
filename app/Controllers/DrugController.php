<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\DrugModel;

class DrugController extends BaseController
{
    protected $drugModel;

    public function __construct()
    {
        $this->drugModel = new DrugModel();
    }

    public function index()
    {
        $data['drugs'] = $this->drugModel->findAll();
        return view('manage_drugs', $data);
    }

    public function store()
    {
        $this->drugModel->save([
            'name' => $this->request->getPost('name'),
            'dosage' => $this->request->getPost('dosage'),
            'quantity_in_stock' => $this->request->getPost('quantity_in_stock'),
            'batch_no' => $this->request->getPost('batch_no'),
            'manufacture_date' => $this->request->getPost('manufacture_date'),
            'expiration_date' => $this->request->getPost('expiration_date'),
            'status' => $this->request->getPost('status'),
        ]);

        return redirect()->to(base_url('drugs'))->with('success', 'Drug added successfully!');
    }

    public function update()
    {
        $id = $this->request->getPost('id');

        $this->drugModel->update($id, [
            'name' => $this->request->getPost('name'),
            'dosage' => $this->request->getPost('dosage'),
            'quantity_in_stock' => $this->request->getPost('quantity_in_stock'),
            'batch_no' => $this->request->getPost('batch_no'),
            'manufacture_date' => $this->request->getPost('manufacture_date'),
            'expiration_date' => $this->request->getPost('expiration_date'),
            'status' => $this->request->getPost('status'),
        ]);

        return redirect()->to(base_url('drugs'))->with('success', 'Drug updated successfully!');
    }

    public function delete()
    {
        $id = $this->request->getPost('id');
        $this->drugModel->delete($id);

        return redirect()->to(base_url('drugs'))->with('success', 'Drug deleted successfully!');
    }
}
