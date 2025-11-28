<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\TransactionModel;

class Transactions extends BaseController
{
    protected $transactionModel;

    public function __construct()
    {
        $this->transactionModel = new TransactionModel();
    }

    public function index()
    {
        $status = $this->request->getGet('status') ?? 'all';
        $search = $this->request->getGet('search');

        $query = $this->transactionModel
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
            'transactions' => $query->orderBy('transactions.created_at', 'DESC')->paginate(20),
            'pager' => $this->transactionModel->pager,
            'current_status' => $status
        ];

        return view('admin/transactions/index', $data);
    }

    public function detail($id)
    {
        $transaction = $this->transactionModel->getWithDetails($id);

        if (!$transaction) {
            return redirect()->to(base_url('admin/transactions'))->with('error', 'Transaksi tidak ditemukan');
        }

        $data = [
            'title' => 'Detail Transaksi',
            'transaction' => $transaction
        ];

        return view('admin/transactions/detail', $data);
    }

    public function updateStatus()
    {
        $id = $this->request->getPost('transaction_id');
        $status = $this->request->getPost('status');
        $notes = $this->request->getPost('admin_notes');

        $updateData = [
            'status' => $status,
            'admin_notes' => $notes
        ];

        if ($status == 'success') {
            $updateData['completed_at'] = date('Y-m-d H:i:s');
            
            // Add points to user if logged in
            $transaction = $this->transactionModel->find($id);
            if ($transaction && $transaction['user_id'] && $transaction['points_earned'] > 0) {
                $userModel = new \App\Models\UserModel();
                $userModel->addPoints($transaction['user_id'], $transaction['points_earned']);
            }
        } elseif ($status == 'processing') {
            $updateData['paid_at'] = date('Y-m-d H:i:s');
        }

        $this->transactionModel->update($id, $updateData);

        return redirect()->to(base_url('admin/transactions/detail/' . $id))->with('success', 'Status transaksi berhasil diupdate');
    }
}