<?php
namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class Products extends BaseController
{
    public function index()
    {
        $productModel = model('ProductModel');
        $gameModel = model('GameModel');
        
        $data = [
            'title' => 'Kelola Produk',
            'products' => $productModel->select('products.*, games.name as game_name')
                ->join('games', 'games.id = products.game_id')
                ->orderBy('products.created_at', 'DESC')
                ->findAll(),
            'games' => $gameModel->where('is_active', 1)->findAll()
        ];

        return view('admin/products/index', $data);
    }

    public function store()
    {
        $productModel = model('ProductModel');

        $data = [
            'game_id' => $this->request->getPost('game_id'),
            'name' => $this->request->getPost('name'),
            'price' => $this->request->getPost('price'),
            'discount_price' => $this->request->getPost('discount_price') ?: null,
            'is_active' => 1
        ];

        $productModel->insert($data);
        return redirect()->back()->with('success', 'Produk berhasil ditambahkan');
    }

    public function update($id)
    {
        $productModel = model('ProductModel');

        $data = [
            'game_id' => $this->request->getPost('game_id'),
            'name' => $this->request->getPost('name'),
            'price' => $this->request->getPost('price'),
            'discount_price' => $this->request->getPost('discount_price') ?: null,
            'is_active' => $this->request->getPost('is_active') ? 1 : 0
        ];

        $productModel->update($id, $data);
        return redirect()->back()->with('success', 'Produk berhasil diupdate');
    }

    public function delete($id)
    {
        $productModel = model('ProductModel');
        $productModel->delete($id);
        
        return $this->response->setJSON(['success' => true]);
    }
}
