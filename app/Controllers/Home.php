<?php

namespace App\Controllers;

use App\Models\GameModel;
use App\Models\ProductModel;
use App\Models\TransactionModel;

class Home extends BaseController
{
    protected $gameModel;
    protected $productModel;
    protected $transactionModel;

    public function __construct()
    {
        $this->gameModel = new GameModel();
        $this->productModel = new ProductModel();
        $this->transactionModel = new TransactionModel();
    }

    public function index()
    {
        $data = [
            'title' => 'BayarStore - Top Up Game Murah & Cepat',
            'popular_games' => $this->gameModel->getPopularGames(),
            'all_games' => $this->gameModel->where('is_active', true)->findAll(),
            'flash_sales' => $this->productModel->where('discount_price IS NOT NULL')
                                                 ->where('is_active', true)
                                                 ->limit(3)
                                                 ->findAll()
        ];

        return view('home/index', $data);
    }

    public function game($slug)
    {
        $game = $this->gameModel->getBySlug($slug);
        
        if (!$game) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $paymentModel = model('PaymentMethodModel');

        $data = [
            'title' => 'Top Up ' . $game['name'] . ' - BayarStore',
            'game' => $game,
            'products' => $this->productModel->getByGameId($game['id']),
            'payment_methods' => $paymentModel->getActive()
        ];

        return view('home/game', $data);
    }

    public function cekTransaksi()
    {
        $data = [
            'title' => 'Cek Transaksi - BayarStore'
        ];

        return view('home/cek_transaksi', $data);
    }

    public function searchTransaction()
    {
        $invoice = $this->request->getPost('invoice');
        
        if (!$invoice) {
            return redirect()->back()->with('error', 'Nomor invoice tidak boleh kosong');
        }

        $transaction = $this->transactionModel->getByInvoice($invoice);

        if (!$transaction) {
            return redirect()->back()->with('error', 'Transaksi tidak ditemukan. Pastikan nomor invoice benar.');
        }

        return redirect()->to(base_url('order/status/' . $invoice));
    }
}