<?php

// App\Controllers\Admin\Auth.php
namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AdminUserModel;

class Auth extends BaseController
{
    public function login()
    {
        if (session()->get('admin_id')) {
            return redirect()->to(base_url('admin'));
        }

        $data = [
            'title' => 'Admin Login'
        ];

        return view('admin/auth/login', $data);
    }

    public function attemptLogin()
    {
        $validation = \Config\Services::validation();

        $rules = [
            'username' => 'required',
            'password' => 'required'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $adminModel = model('AdminUserModel');
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $admin = $adminModel->where('username', $username)->where('is_active', 1)->first();

        if (!$admin) {
            return redirect()->back()->withInput()->with('error', 'Username atau password salah');
        }

        if (!password_verify($password, $admin['password'])) {
            return redirect()->back()->withInput()->with('error', 'Username atau password salah');
        }

        // Update last login
        $adminModel->update($admin['id'], ['last_login' => date('Y-m-d H:i:s')]);

        session()->set([
            'admin_id' => $admin['id'],
            'admin_username' => $admin['username'],
            'admin_role' => $admin['role'],
            'admin_logged_in' => true
        ]);

        return redirect()->to(base_url('admin'))->with('success', 'Login berhasil');
    }

    public function logout()
    {
        session()->remove(['admin_id', 'admin_username', 'admin_role', 'admin_logged_in']);
        return redirect()->to(base_url('admin/login'))->with('success', 'Logout berhasil');
    }
}
