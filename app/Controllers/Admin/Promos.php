<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class Promos extends BaseController
{
    public function index()
    {
        $promoModel = model('PromoCodeModel');
        $productModel = model('ProductModel');
        
        $promos = $promoModel->orderBy('id', 'DESC')->findAll();
        
        // Ambil produk untuk setiap promo dan pastikan semua kolom ada
        foreach ($promos as &$promo) {
            $promo['products'] = $promoModel->getPromoProducts($promo['id']);
            // Pastikan kolom user_limit_per_account ada (default null jika belum ada di DB)
            if (!isset($promo['user_limit_per_account'])) {
                $promo['user_limit_per_account'] = null;
            }
            // Pastikan kolom valid_from ada
            if (!isset($promo['valid_from'])) {
                $promo['valid_from'] = null;
            }
        }
        
        $data = [
            'title' => 'Kelola Promo',
            'promos' => $promos,
            'all_products' => $productModel->select('products.*, games.name as game_name')
                                          ->join('games', 'games.id = products.game_id')
                                          ->where('products.is_active', 1)
                                          ->orderBy('games.name', 'ASC')
                                          ->orderBy('products.price', 'ASC')
                                          ->findAll()
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
            'valid_from' => $this->request->getPost('valid_from') ?: null,
            'valid_until' => $this->request->getPost('valid_until') ?: null,
            'is_active' => $this->request->getPost('is_active') ? 1 : 0
        ];

        // Cek apakah kolom user_limit_per_account ada di database
        $db = \Config\Database::connect();
        $fields = $db->getFieldData('promo_codes');
        $hasUserLimit = false;
        foreach ($fields as $field) {
            if ($field->name === 'user_limit_per_account') {
                $hasUserLimit = true;
                break;
            }
        }
        
        if ($hasUserLimit) {
            $data['user_limit_per_account'] = $this->request->getPost('user_limit_per_account') ?: null;
        }

        $promoId = $promoModel->insert($data);
        
        // Set produk yang terikat dengan promo ini (jika tabel promo_products ada)
        try {
            $productIds = $this->request->getPost('product_ids') ?: [];
            $promoModel->setPromoProducts($promoId, $productIds);
        } catch (\Exception $e) {
            // Jika tabel promo_products belum ada, skip
            log_message('error', 'Promo products table not found: ' . $e->getMessage());
        }
        
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
            'valid_from' => $this->request->getPost('valid_from') ?: null,
            'valid_until' => $this->request->getPost('valid_until') ?: null,
            'is_active' => $this->request->getPost('is_active') ? 1 : 0
        ];

        // Cek apakah kolom user_limit_per_account ada di database
        $db = \Config\Database::connect();
        $fields = $db->getFieldData('promo_codes');
        $hasUserLimit = false;
        foreach ($fields as $field) {
            if ($field->name === 'user_limit_per_account') {
                $hasUserLimit = true;
                break;
            }
        }
        
        if ($hasUserLimit) {
            $data['user_limit_per_account'] = $this->request->getPost('user_limit_per_account') ?: null;
        }

        $promoModel->update($id, $data);
        
        // Update produk yang terikat dengan promo ini (jika tabel promo_products ada)
        try {
            $productIds = $this->request->getPost('product_ids') ?: [];
            $promoModel->setPromoProducts($id, $productIds);
        } catch (\Exception $e) {
            // Jika tabel promo_products belum ada, skip
            log_message('error', 'Promo products table not found: ' . $e->getMessage());
        }
        
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