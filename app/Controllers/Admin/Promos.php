<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class Promos extends BaseController
{
    public function index()
    {
        $promoModel = model('PromoCodeModel');
        
        $data = [
            'title' => 'Kelola Promo',
            'promos' => $promoModel->orderBy('id', 'DESC')->findAll()
        ];

        return view('admin/promos/index', $data);
    }

    public function store()
    {
        $promoModel = model('PromoCodeModel');

        $data = [
            'code' => strtoupper($this->request->getPost('code')),
            'type' => $this->request->getPost('type'),
            'value' => $this->request->getPost('value'),
            'min_transaction' => $this->request->getPost('min_transaction'),
            'max_discount' => $this->request->getPost('max_discount') ?: null,
            'usage_limit' => $this->request->getPost('usage_limit') ?: null,
            'valid_until' => $this->request->getPost('valid_until') ?: null,
            'is_active' => $this->request->getPost('is_active') ? 1 : 0
        ];

        $promoModel->insert($data);
        return redirect()->to(base_url('admin/promos'))->with('success', 'Promo berhasil ditambahkan!');
    }

    public function update($id)
    {
        $promoModel = model('PromoCodeModel');

        $data = [
            'code' => strtoupper($this->request->getPost('code')),
            'type' => $this->request->getPost('type'),
            'value' => $this->request->getPost('value'),
            'min_transaction' => $this->request->getPost('min_transaction'),
            'max_discount' => $this->request->getPost('max_discount') ?: null,
            'usage_limit' => $this->request->getPost('usage_limit') ?: null,
            'valid_until' => $this->request->getPost('valid_until') ?: null,
            'is_active' => $this->request->getPost('is_active') ? 1 : 0
        ];

        $promoModel->update($id, $data);
        return redirect()->to(base_url('admin/promos'))->with('success', 'Promo berhasil diupdate!');
    }

    public function delete($id)
    {
        $promoModel = model('PromoCodeModel');
        $promoModel->delete($id);
        
        return $this->response->setJSON(['success' => true]);
    }
}
?>