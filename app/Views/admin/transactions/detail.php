<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-900 text-white">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-gray-800 p-6">
            <h2 class="text-2xl font-bold mb-8 text-indigo-400">Admin Panel</h2>
            <nav class="space-y-2">
                <a href="<?= base_url('admin') ?>" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-gray-700 text-gray-300">
                    <i class="fas fa-home"></i> Dashboard
                </a>
                <a href="<?= base_url('admin/transactions') ?>" class="flex items-center gap-3 px-4 py-3 rounded-lg bg-indigo-600 text-white">
                    <i class="fas fa-exchange-alt"></i> Transaksi
                </a>
                <a href="<?= base_url('admin/games') ?>" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-gray-700 text-gray-300">
                    <i class="fas fa-gamepad"></i> Games
                </a>
                <a href="<?= base_url('admin/products') ?>" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-gray-700 text-gray-300">
                    <i class="fas fa-box"></i> Produk
                </a>
                <a href="<?= base_url('admin/promos') ?>" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-gray-700 text-gray-300">
                    <i class="fas fa-tag"></i> Promo
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-8 overflow-y-auto">
            <div class="mb-6">
                <a href="<?= base_url('admin/transactions') ?>" class="text-indigo-400 hover:text-indigo-300 mb-4 inline-block">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali
                </a>
                <h1 class="text-3xl font-bold">Detail Transaksi</h1>
            </div>

            <?php if (session()->getFlashdata('success')): ?>
            <div class="bg-green-500/20 border border-green-500 rounded-xl p-4 mb-6">
                <?= session()->getFlashdata('success') ?>
            </div>
            <?php endif; ?>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Transaction Info -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Status Card -->
                    <div class="bg-gray-800 rounded-2xl p-6">
                        <h3 class="text-xl font-bold mb-4">Status Transaksi</h3>
                        <?php
                        $statusInfo = [
                            'pending' => ['color' => 'yellow', 'icon' => 'clock', 'text' => 'Menunggu Pembayaran'],
                            'processing' => ['color' => 'blue', 'icon' => 'spinner', 'text' => 'Sedang Diproses'],
                            'success' => ['color' => 'green', 'icon' => 'check-circle', 'text' => 'Berhasil'],
                            'failed' => ['color' => 'red', 'icon' => 'times-circle', 'text' => 'Gagal'],
                            'expired' => ['color' => 'gray', 'icon' => 'ban', 'text' => 'Kedaluwarsa']
                        ];
                        $status = $statusInfo[$transaction['status']] ?? $statusInfo['pending'];
                        ?>
                        <div class="bg-<?= $status['color'] ?>-500/20 border-2 border-<?= $status['color'] ?>-500 rounded-xl p-6 text-center">
                            <i class="fas fa-<?= $status['icon'] ?> text-<?= $status['color'] ?>-400 text-5xl mb-4"></i>
                            <h4 class="text-2xl font-bold text-<?= $status['color'] ?>-400"><?= $status['text'] ?></h4>
                        </div>

                        <!-- Update Status Form -->
                        <?php if ($transaction['status'] != 'success'): ?>
                        <form action="<?= base_url('admin/transactions/update-status') ?>" method="POST" class="mt-6">
                            <?= csrf_field() ?>
                            <input type="hidden" name="transaction_id" value="<?= $transaction['id'] ?>">
                            
                            <label class="block text-gray-400 mb-2">Update Status</label>
                            <div class="flex gap-3">
                                <select name="status" class="flex-1 bg-gray-900 border-2 border-gray-700 rounded-xl px-4 py-3 focus:border-indigo-500 focus:outline-none">
                                    <option value="pending" <?= $transaction['status'] == 'pending' ? 'selected' : '' ?>>Pending</option>
                                    <option value="processing" <?= $transaction['status'] == 'processing' ? 'selected' : '' ?>>Processing</option>
                                    <option value="success" <?= $transaction['status'] == 'success' ? 'selected' : '' ?>>Success</option>
                                    <option value="failed" <?= $transaction['status'] == 'failed' ? 'selected' : '' ?>>Failed</option>
                                    <option value="expired" <?= $transaction['status'] == 'expired' ? 'selected' : '' ?>>Expired</option>
                                </select>
                                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 px-6 py-3 rounded-xl font-semibold transition">
                                    Update
                                </button>
                            </div>
                        </form>
                        <?php endif; ?>
                    </div>

                    <!-- Transaction Details -->
                    <div class="bg-gray-800 rounded-2xl p-6">
                        <h3 class="text-xl font-bold mb-4">Detail Transaksi</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between py-3 border-b border-gray-700">
                                <span class="text-gray-400">Invoice Number</span>
                                <span class="font-semibold font-mono"><?= esc($transaction['invoice_number']) ?></span>
                            </div>
                            <div class="flex justify-between py-3 border-b border-gray-700">
                                <span class="text-gray-400">Game</span>
                                <span class="font-semibold"><?= esc($transaction['game_name']) ?></span>
                            </div>
                            <div class="flex justify-between py-3 border-b border-gray-700">
                                <span class="text-gray-400">Produk</span>
                                <span class="font-semibold"><?= esc($transaction['product_name']) ?></span>
                            </div>
                            <div class="flex justify-between py-3 border-b border-gray-700">
                                <span class="text-gray-400">User Game ID</span>
                                <span class="font-semibold"><?= esc($transaction['user_game_id']) ?></span>
                            </div>
                            <div class="flex justify-between py-3 border-b border-gray-700">
                                <span class="text-gray-400">Payment Method</span>
                                <span class="font-semibold"><?= esc($transaction['payment_name']) ?></span>
                            </div>
                            <div class="flex justify-between py-3">
                                <span class="text-gray-400">Tanggal Order</span>
                                <span class="font-semibold"><?= date('d M Y H:i', strtotime($transaction['created_at'])) ?></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Summary -->
                <div class="space-y-6">
                    <div class="bg-gray-800 rounded-2xl p-6">
                        <h3 class="text-xl font-bold mb-4">Rincian Pembayaran</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-400">Harga</span>
                                <span>Rp <?= number_format($transaction['amount'], 0, ',', '.') ?></span>
                            </div>
                            <?php if ($transaction['discount'] > 0): ?>
                            <div class="flex justify-between text-green-400">
                                <span>Diskon</span>
                                <span>- Rp <?= number_format($transaction['discount'], 0, ',', '.') ?></span>
                            </div>
                            <?php endif; ?>
                            <?php if ($transaction['fee'] > 0): ?>
                            <div class="flex justify-between">
                                <span class="text-gray-400">Biaya Admin</span>
                                <span>Rp <?= number_format($transaction['fee'], 0, ',', '.') ?></span>
                            </div>
                            <?php endif; ?>
                            <div class="border-t border-gray-700 pt-3 mt-3">
                                <div class="flex justify-between text-xl font-bold">
                                    <span>Total</span>
                                    <span class="text-green-400">Rp <?= number_format($transaction['total_payment'], 0, ',', '.') ?></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php if ($transaction['points_earned'] > 0): ?>
                    <div class="bg-gradient-to-br from-purple-500 to-pink-500 rounded-2xl p-6">
                        <h4 class="font-semibold mb-2">Points Earned</h4>
                        <p class="text-3xl font-bold"><?= number_format($transaction['points_earned']) ?> Pts</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>
</body>
</html>