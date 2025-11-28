<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container mx-auto px-4">
    <div class="max-w-6xl mx-auto">
        <!-- Welcome Section -->
        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-2xl p-8 mb-8">
            <h1 class="text-3xl font-bold mb-2">Selamat Datang, <?= esc($user['username']) ?>! 👋</h1>
            <p class="text-indigo-100">Kelola akun dan lihat riwayat transaksi Anda</p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-gray-800 rounded-2xl p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-gray-400">Total Transaksi</h3>
                    <i class="fas fa-shopping-cart text-2xl text-blue-400"></i>
                </div>
                <p class="text-3xl font-bold"><?= $user['total_transactions'] ?></p>
            </div>

            <div class="bg-gray-800 rounded-2xl p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-gray-400">Points</h3>
                    <i class="fas fa-star text-2xl text-yellow-400"></i>
                </div>
                <p class="text-3xl font-bold text-yellow-400"><?= number_format($user['points']) ?></p>
            </div>

            <div class="bg-gray-800 rounded-2xl p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-gray-400">Member Since</h3>
                    <i class="fas fa-calendar text-2xl text-green-400"></i>
                </div>
                <p class="text-lg font-bold"><?= date('M Y', strtotime($user['created_at'])) ?></p>
            </div>
        </div>

        <!-- Recent Transactions -->
        <div class="bg-gray-800 rounded-2xl p-6 mb-8">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold">Riwayat Transaksi</h2>
                <a href="<?= base_url('dashboard/transactions') ?>" class="text-indigo-400 hover:text-indigo-300">
                    Lihat Semua →
                </a>
            </div>

            <?php if (empty($transactions)): ?>
                <div class="text-center py-12 text-gray-400">
                    <i class="fas fa-inbox text-6xl mb-4"></i>
                    <p class="text-xl">Belum ada transaksi</p>
                    <a href="<?= base_url('/') ?>" class="inline-block mt-4 bg-indigo-600 hover:bg-indigo-700 px-6 py-3 rounded-xl font-semibold transition">
                        Mulai Top Up
                    </a>
                </div>
            <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="border-b border-gray-700">
                            <tr>
                                <th class="text-left py-3 px-4 text-gray-400">Invoice</th>
                                <th class="text-left py-3 px-4 text-gray-400">Game</th>
                                <th class="text-left py-3 px-4 text-gray-400">Produk</th>
                                <th class="text-left py-3 px-4 text-gray-400">Total</th>
                                <th class="text-left py-3 px-4 text-gray-400">Status</th>
                                <th class="text-left py-3 px-4 text-gray-400">Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($transactions as $trx): ?>
                            <tr class="border-b border-gray-700 hover:bg-gray-700/30 transition">
                                <td class="py-3 px-4">
                                    <a href="<?= base_url('order/status/' . $trx['invoice_number']) ?>" class="text-indigo-400 hover:text-indigo-300 font-mono text-sm">
                                        <?= esc($trx['invoice_number']) ?>
                                    </a>
                                </td>
                                <td class="py-3 px-4"><?= esc($trx['game_name']) ?></td>
                                <td class="py-3 px-4 text-sm"><?= esc($trx['product_name']) ?></td>
                                <td class="py-3 px-4 font-semibold">Rp <?= number_format($trx['total_payment'], 0, ',', '.') ?></td>
                                <td class="py-3 px-4">
                                    <?php
                                    $statusColors = [
                                        'success' => 'bg-green-500/20 text-green-400',
                                        'pending' => 'bg-yellow-500/20 text-yellow-400',
                                        'processing' => 'bg-blue-500/20 text-blue-400',
                                        'failed' => 'bg-red-500/20 text-red-400',
                                        'expired' => 'bg-gray-500/20 text-gray-400'
                                    ];
                                    ?>
                                    <span class="<?= $statusColors[$trx['status']] ?> px-3 py-1 rounded-full text-xs font-semibold">
                                        <?= ucfirst($trx['status']) ?>
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-sm text-gray-400">
                                    <?= date('d M Y', strtotime($trx['created_at'])) ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>

        <!-- Profile Info -->
        <div class="bg-gray-800 rounded-2xl p-6">
            <h2 class="text-2xl font-bold mb-6">Informasi Akun</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-gray-400 mb-2">Username</label>
                    <p class="text-lg font-semibold"><?= esc($user['username']) ?></p>
                </div>
                <div>
                    <label class="block text-gray-400 mb-2">Email</label>
                    <p class="text-lg font-semibold"><?= esc($user['email']) ?></p>
                </div>
                <div>
                    <label class="block text-gray-400 mb-2">No. HP</label>
                    <p class="text-lg font-semibold"><?= $user['phone'] ?: '-' ?></p>
                </div>
                <div>
                    <label class="block text-gray-400 mb-2">Bergabung Sejak</label>
                    <p class="text-lg font-semibold"><?= date('d F Y', strtotime($user['created_at'])) ?></p>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>