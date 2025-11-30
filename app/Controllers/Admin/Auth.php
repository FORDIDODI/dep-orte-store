<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AdminUserModel;

class Auth extends BaseController
{
    public function login()
    {
        if (session()->get('admin_logged_in')) {
            return redirect()->to(base_url('admin'));
        }
        return view('admin/auth/login');
    }

    public function attemptLogin()
    {
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        if (empty($username) || empty($password)) {
            return redirect()->to(base_url('admin/login'))
                           ->with('error', 'Username dan password harus diisi!');
        }

        // HARDCODE CHECK - SIMPLE!
        if ($username === 'admin' && $password === 'admin123') {
            session()->set([
                'admin_id' => 1,
                'admin_username' => 'admin',
                'admin_logged_in' => true
            ]);
            return redirect()->to(base_url('admin'));
        }

        return redirect()->to(base_url('admin/login'))
                       ->with('error', 'Username atau password salah!');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to(base_url('admin/login'));
    }
}