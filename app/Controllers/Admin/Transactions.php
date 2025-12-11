<?php
// ============================================
// FIX: app/Controllers/Admin/Transactions.php
// Kirim variable search ke view
// ============================================
namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class Transactions extends BaseController
{
    public function index()
    {
        $transactionModel = model('TransactionModel');
        
        $status = $this->request->getGet('status') ?? 'all';
        $search = $this->request->getGet('search');

        $query = $transactionModel
            ->select('transactions.*, games.name as game_name, products.name as product_name, users.username, payment_methods.name as payment_name')
            ->join('games', 'games.id = transactions.game_id')
            ->join('products', 'products.id = transactions.product_id')
            ->join('users', 'users.id = transactions.user_id', 'left')
            ->join('payment_methods', 'payment_methods.id = transactions.payment_method_id');

        if ($status !== 'all') {
            $query->where('transactions.status', $status);
        }

        if ($search) {
            $query->groupStart()
                  ->like('transactions.invoice_number', $search)
                  ->orLike('transactions.user_game_id', $search)
                  ->orLike('users.username', $search)
                  ->groupEnd();
        }

        $data = [
            'title' => 'Kelola Transaksi',
            'transactions' => $query->orderBy('transactions.id', 'DESC')->findAll(),
            'current_status' => $status,
            'search' => $search // <- TAMBAH INI!
        ];

        return view('admin/transactions/index', $data);
    }

    public function detail($id)
    {
        $transactionModel = model('TransactionModel');
        $transaction = $transactionModel->getWithDetails($id);

        if (!$transaction) {
            return redirect()->to(base_url('admin/transactions'))->with('error', 'Transaksi tidak ditemukan');
        }

        // Check if expired (auto-expire setelah 24 jam)
        if (in_array($transaction['status'], ['pending', 'processing']) && strtotime($transaction['expired_at']) < time()) {
            $transactionModel->update($transaction['id'], ['status' => 'expired']);
            $transaction['status'] = 'expired';
            // Reload transaction data
            $transaction = $transactionModel->getWithDetails($id);
        }

        return view('admin/transactions/detail', ['title' => 'Detail Transaksi', 'transaction' => $transaction]);
    }

    public function updateStatus()
    {
        $transactionModel = model('TransactionModel');
        $id = $this->request->getPost('transaction_id');
        $status = $this->request->getPost('status');

        $transactionModel->update($id, ['status' => $status]);

        return redirect()->to(base_url('admin/transactions/detail/' . $id))->with('success', 'Status updated!');
    }
}