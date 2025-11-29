<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-gray-900 to-gray-800 text-white min-h-screen flex items-center justify-center">
    <div class="w-full max-w-md px-4">
        <div class="bg-gray-800 rounded-2xl p-8 shadow-2xl">
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="w-20 h-20 bg-gradient-to-br from-indigo-500 to-purple-500 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-user-shield text-4xl"></i>
                </div>
                <h2 class="text-3xl font-bold">Admin Login</h2>
                <p class="text-gray-400 mt-2">BayarStore Admin Panel</p>
            </div>

            <!-- Alerts -->
            <?php if (session()->getFlashdata('error')): ?>
            <div class="bg-red-500/20 border border-red-500 rounded-xl p-4 mb-6">
                <i class="fas fa-exclamation-circle mr-2"></i>
                <?= session()->getFlashdata('error') ?>
            </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('success')): ?>
            <div class="bg-green-500/20 border border-green-500 rounded-xl p-4 mb-6">
                <i class="fas fa-check-circle mr-2"></i>
                <?= session()->getFlashdata('success') ?>
            </div>
            <?php endif; ?>

            <!-- Form -->
            <form action="<?= base_url('admin/login') ?>" method="POST">
                <?= csrf_field() ?>

                <div class="mb-6">
                    <label class="block text-gray-300 mb-2">Username</label>
                    <input type="text" 
                           name="username" 
                           value="<?= old('username') ?>"
                           class="w-full bg-gray-900 border-2 border-gray-700 rounded-xl px-4 py-3 focus:border-indigo-500 focus:outline-none transition" 
                           placeholder="Masukkan username" 
                           required>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-300 mb-2">Password</label>
                    <input type="password" 
                           name="password" 
                           class="w-full bg-gray-900 border-2 border-gray-700 rounded-xl px-4 py-3 focus:border-indigo-500 focus:outline-none transition" 
                           placeholder="Masukkan password" 
                           required>
                </div>

                <button type="submit" 
                        class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 py-3 rounded-xl font-semibold transition">
                    <i class="fas fa-sign-in-alt mr-2"></i>
                    Login
                </button>
            </form>

            <!-- Back to Home -->
            <div class="mt-6 text-center">
                <a href="<?= base_url('/') ?>" class="text-gray-400 hover:text-white text-sm">
                    <i class="fas fa-arrow-left mr-1"></i>
                    Kembali ke Homepage
                </a>
            </div>

            <!-- Dev Info -->
            <div class="mt-6 p-4 bg-yellow-500/10 border border-yellow-500/30 rounded-xl">
                <p class="text-yellow-500 text-xs font-semibold mb-1">
                    <i class="fas fa-info-circle mr-1"></i>
                    Default Login:
                </p>
                <p class="text-gray-400 text-xs">Username: <code class="bg-gray-900 px-2 py-1 rounded">admin</code></p>
                <p class="text-gray-400 text-xs">Password: <code class="bg-gray-900 px-2 py-1 rounded">admin123</code></p>
            </div>
        </div>
    </div>
</body>
</html>