<?php
namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AdminUserModel;

class Auth extends BaseController
{
    public function login()
    {
        // Kalau sudah login, langsung ke dashboard
        if (session()->get('admin_logged_in')) {
            return redirect()->to(base_url('admin'));
        }

        return view('admin/auth/login');
    }

    public function attemptLogin()
    {
        $validation = \Config\Services::validation();

        $rules = [
            'username' => 'required',
            'password' => 'required|min_length[6]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $adminModel = new AdminUserModel();
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        // Cari admin berdasarkan username
        $admin = $adminModel->where('username', $username)->first();

        if (!$admin) {
            return redirect()->back()->withInput()->with('error', 'Username tidak ditemukan');
        }

        // Verifikasi password - SAMA SEPERTI USER
        if (!password_verify($password, $admin['password'])) {
            return redirect()->back()->withInput()->with('error', 'Password salah');
        }

        // Set session - SAMA SEPERTI USER
        session()->set([
            'admin_id' => $admin['id'],
            'admin_username' => $admin['username'],
            'admin_logged_in' => true
        ]);

        return redirect()->to(base_url('admin'))->with('success', 'Login berhasil!');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to(base_url('admin/login'))->with('success', 'Logout berhasil');
    }
}
