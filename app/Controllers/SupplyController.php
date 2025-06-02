<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\SupplyModel;

class SupplyController extends BaseController
{
    protected $supplyModel;

    public function __construct()
    {
        $this->supplyModel = new SupplyModel();
    }

    public function index()
    {
        $data['supplies'] = $this->supplyModel->paginate(10);
        $data['pager'] = $this->supplyModel->pager;

        return view('manage_supplies', $data);
    }

    public function store()
    {
        $this->supplyModel->save([
            'name'              => $this->request->getPost('name'),
            'quantity_in_stock' => $this->request->getPost('quantity_in_stock'),
            'batch_no'          => $this->request->getPost('batch_no'),
            'manufacture_date'  => $this->request->getPost('manufacture_date'),
            'expiration_date'   => $this->request->getPost('expiration_date'),
        ]);

        return redirect()->to(base_url('supplies'))->with('success', 'Supply added successfully!');
    }

    public function update()
    {
        $id = $this->request->getPost('id');

        $this->supplyModel->update($id, [
            'name'              => $this->request->getPost('name'),
            'quantity_in_stock' => $this->request->getPost('quantity_in_stock'),
            'batch_no'          => $this->request->getPost('batch_no'),
            'manufacture_date'  => $this->request->getPost('manufacture_date'),
            'expiration_date'   => $this->request->getPost('expiration_date'),
        ]);

        return redirect()->to(base_url('supplies'))->with('success', 'Supply updated successfully!');
    }

    public function delete()
    {
        $id = $this->request->getPost('id');

        $this->supplyModel->delete($id);

        return redirect()->to(base_url('supplies'))->with('success', 'Supply deleted successfully!');
    }
}
    