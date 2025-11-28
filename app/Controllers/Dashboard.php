<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\TransactionModel;

class Dashboard extends BaseController
{
    protected $userModel;
    protected $transactionModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->transactionModel = new TransactionModel();
    }

    public function index()
    {
        $userId = session()->get('user_id');
        $user = $this->userModel->find($userId);

        if (!$user) {
            return redirect()->to(base_url('auth/login'));
        }

        $data = [
            'title' => 'Dashboard - ' . $user['username'],
            'user' => $user,
            'transactions' => $this->transactionModel->getUserTransactions($userId, 5)
        ];

        return view('dashboard/index', $data);
    }

    public function transactions()
    {
        $userId = session()->get('user_id');
        $user = $this->userModel->find($userId);

        if (!$user) {
            return redirect()->to(base_url('auth/login'));
        }

        $data = [
            'title' => 'Riwayat Transaksi',
            'user' => $user,
            'transactions' => $this->transactionModel->getUserTransactions($userId, 50)
        ];

        return view('dashboard/transactions', $data);
    }

    public function profile()
    {
        $userId = session()->get('user_id');
        $user = $this->userModel->find($userId);

        if (!$user) {
            return redirect()->to(base_url('auth/login'));
        }

        $data = [
            'title' => 'Profile',
            'user' => $user
        ];

        return view('dashboard/profile', $data);
    }

    public function updateProfile()
    {
        $userId = session()->get('user_id');
        
        $rules = [
            'username' => "required|min_length[3]|is_unique[users.username,id,$userId]",
            'email' => "required|valid_email|is_unique[users.email,id,$userId]",
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'username' => $this->request->getPost('username'),
            'email' => $this->request->getPost('email'),
            'phone' => $this->request->getPost('phone')
        ];

        // Update password if provided
        if ($this->request->getPost('password')) {
            $data['password'] = $this->request->getPost('password');
        }

        $this->userModel->update($userId, $data);

        return redirect()->back()->with('success', 'Profile berhasil diupdate');
    }
}
