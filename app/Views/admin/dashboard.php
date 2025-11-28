<!-- app/Views/admin/dashboard.php -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Admin Dashboard' ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-900 text-white">
    <!-- Sidebar -->
    <div class="flex h-screen">
        <aside class="w-64 bg-gray-800 p-6">
            <h2 class="text-2xl font-bold mb-8 text-indigo-400">Admin Panel</h2>
            
            <nav class="space-y-2">
                <a href="<?= base_url('admin') ?>" class="flex items-center gap-3 px-4 py-3 rounded-lg bg-indigo-600 text-white">
                    <i class="fas fa-home"></i>
                    <span>Dashboard</span>
                </a>
                <a href="<?= base_url('admin/transactions') ?>" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-gray-700 text-gray-300 hover:text-white transition">
                    <i class="fas fa-exchange-alt"></i>
                    <span>Transaksi</span>
                </a>
                <a href="<?= base_url('admin/games') ?>" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-gray-700 text-gray-300 hover:text-white transition">
                    <i class="fas fa-gamepad"></i>
                    <span>Games</span>
                </a>
                <a href="<?= base_url('admin/products') ?>" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-gray-700 text-gray-300 hover:text-white transition">
                    <i class="fas fa-box"></i>
                    <span>Produk</span>
                </a>
                <a href="<?= base_url('admin/promos') ?>" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-gray-700 text-gray-300 hover:text-white transition">
                    <i class="fas fa-tag"></i>
                    <span>Promo</span>
                </a>
                <hr class="border-gray-700 my-4">
                <a href="<?= base_url('admin/logout') ?>" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-red-600 text-gray-300 hover:text-white transition">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-8 overflow-y-auto">
            <div class="mb-8">
                <h1 class="text-3xl font-bold mb-2">Dashboard</h1>
                <p class="text-gray-400">Selamat datang, <?= session()->get('admin_username') ?></p>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl p-6">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-sm font-semibold opacity-90">Transaksi Hari Ini</h3>
                        <i class="fas fa-shopping-cart text-2xl opacity-80"></i>
                    </div>
                    <p class="text-4xl font-bold"><?= $total_transactions_today ?></p>
                </div>

                <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-2xl p-6">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-sm font-semibold opacity-90">Transaksi Sukses</h3>
                        <i class="fas fa-check-circle text-2xl opacity-80"></i>
                    </div>
                    <p class="text-4xl font-bold"><?= $success_transactions ?></p>
                </div>

                <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-2xl p-6">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-sm font-semibold opacity-90">Pending</h3>
                        <i class="fas fa-clock text-2xl opacity-80"></i>
                    </div>
                    <p class="text-4xl font-bold"><?= $pending_transactions ?></p>
                </div>

                <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl p-6">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-sm font-semibold opacity-90">Revenue Hari Ini</h3>
                        <i class="fas fa-money-bill-wave text-2xl opacity-80"></i>
                    </div>
                    <p class="text-2xl font-bold">Rp <?= number_format($today_revenue, 0, ',', '.') ?></p>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-gray-800 rounded-2xl p-6">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-indigo-600 rounded-xl flex items-center justify-center">
                            <i class="fas fa-users text-xl"></i>
                        </div>
                        <div>
                            <p class="text-gray-400 text-sm">Total Users</p>
                            <p class="text-2xl font-bold"><?= $total_users ?></p>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-800 rounded-2xl p-6">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-pink-600 rounded-xl flex items-center justify-center">
                            <i class="fas fa-gamepad text-xl"></i>
                        </div>
                        <div>
                            <p class="text-gray-400 text-sm">Total Games</p>
                            <p class="text-2xl font-bold"><?= $total_games ?></p>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-800 rounded-2xl p-6">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-green-600 rounded-xl flex items-center justify-center">
                            <i class="fas fa-chart-line text-xl"></i>
                        </div>
                        <div>
                            <p class="text-gray-400 text-sm">Status</p>
                            <p class="text-2xl font-bold text-green-400">Online</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Transactions -->
            <div class="bg-gray-800 rounded-2xl p-6">
                <h2 class="text-xl font-bold mb-4">Transaksi Terbaru</h2>
                
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-gray-700">
                                <th class="text-left py-3 px-4 text-gray-400 font-semibold">Invoice</th>
                                <th class="text-left py-3 px-4 text-gray-400 font-semibold">Game</th>
                                <th class="text-left py-3 px-4 text-gray-400 font-semibold">Produk</th>
                                <th class="text-left py-3 px-4 text-gray-400 font-semibold">User</th>
                                <th class="text-left py-3 px-4 text-gray-400 font-semibold">Total</th>
                                <th class="text-left py-3 px-4 text-gray-400 font-semibold">Status</th>
                                <th class="text-left py-3 px-4 text-gray-400 font-semibold">Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recent_transactions as $trx): ?>
                            <tr class="border-b border-gray-700 hover:bg-gray-700/30 transition">
                                <td class="py-3 px-4">
                                    <a href="<?= base_url('admin/transactions/detail/' . $trx['id']) ?>" class="text-indigo-400 hover:text-indigo-300">
                                        <?= esc($trx['invoice_number']) ?>
                                    </a>
                                </td>
                                <td class="py-3 px-4"><?= esc($trx['game_name']) ?></td>
                                <td class="py-3 px-4"><?= esc($trx['product_name']) ?></td>
                                <td class="py-3 px-4"><?= $trx['username'] ?? 'Guest' ?></td>
                                <td class="py-3 px-4 font-semibold">Rp <?= number_format($trx['total_payment'], 0, ',', '.') ?></td>
                                <td class="py-3 px-4">
                                    <?php if ($trx['status'] == 'success'): ?>
                                        <span class="bg-green-500/20 text-green-400 px-3 py-1 rounded-full text-xs font-semibold">Success</span>
                                    <?php elseif ($trx['status'] == 'pending'): ?>
                                        <span class="bg-yellow-500/20 text-yellow-400 px-3 py-1 rounded-full text-xs font-semibold">Pending</span>
                                    <?php elseif ($trx['status'] == 'processing'): ?>
                                        <span class="bg-blue-500/20 text-blue-400 px-3 py-1 rounded-full text-xs font-semibold">Processing</span>
                                    <?php else: ?>
                                        <span class="bg-red-500/20 text-red-400 px-3 py-1 rounded-full text-xs font-semibold"><?= ucfirst($trx['status']) ?></span>
                                    <?php endif; ?>
                                </td>
                                <td class="py-3 px-4 text-gray-400 text-sm">
                                    <?= date('d M Y H:i', strtotime($trx['created_at'])) ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="mt-4 text-center">
                    <a href="<?= base_url('admin/transactions') ?>" class="text-indigo-400 hover:text-indigo-300 font-semibold">
                        Lihat Semua Transaksi →
                    </a>
                </div>
            </div>
        </main>
    </div>
</body>
</html>