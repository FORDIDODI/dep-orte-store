<!-- ============================================ -->
<!-- app/Views/admin/promos/index.php -->
<!-- ============================================ -->
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
                <a href="<?= base_url('admin/transactions') ?>" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-gray-700 text-gray-300">
                    <i class="fas fa-exchange-alt"></i> Transaksi
                </a>
                <a href="<?= base_url('admin/games') ?>" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-gray-700 text-gray-300">
                    <i class="fas fa-gamepad"></i> Games
                </a>
                <a href="<?= base_url('admin/products') ?>" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-gray-700 text-gray-300">
                    <i class="fas fa-box"></i> Produk
                </a>
                <a href="<?= base_url('admin/promos') ?>" class="flex items-center gap-3 px-4 py-3 rounded-lg bg-indigo-600 text-white">
                    <i class="fas fa-tag"></i> Promo
                </a>
                <hr class="border-gray-700 my-4">
                <a href="<?= base_url('admin/logout') ?>" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-red-600 text-gray-300">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-8 overflow-y-auto">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-bold">Kelola Promo</h1>
                <button onclick="alert('Feature coming soon!')" class="bg-indigo-600 px-6 py-3 rounded-xl font-semibold hover:bg-indigo-700">
                    <i class="fas fa-plus mr-2"></i> Tambah Promo
                </button>
            </div>

            <?php if (session()->getFlashdata('success')): ?>
            <div class="bg-green-500/20 border border-green-500 rounded-xl p-4 mb-6">
                <i class="fas fa-check-circle mr-2"></i>
                <?= session()->getFlashdata('success') ?>
            </div>
            <?php endif; ?>

            <!-- Promos Table -->
            <div class="bg-gray-800 rounded-2xl overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-900">
                            <tr>
                                <th class="text-left py-4 px-6">Kode Promo</th>
                                <th class="text-left py-4 px-6">Tipe</th>
                                <th class="text-left py-4 px-6">Value</th>
                                <th class="text-left py-4 px-6">Min. Transaksi</th>
                                <th class="text-left py-4 px-6">Usage</th>
                                <th class="text-left py-4 px-6">Valid Until</th>
                                <th class="text-left py-4 px-6">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($promos)): ?>
                            <tr>
                                <td colspan="7" class="text-center py-8 text-gray-400">
                                    <i class="fas fa-inbox text-4xl mb-3"></i>
                                    <p>Belum ada promo</p>
                                </td>
                            </tr>
                            <?php else: ?>
                                <?php foreach ($promos as $promo): ?>
                                <tr class="border-b border-gray-700 hover:bg-gray-700/30 transition">
                                    <td class="py-4 px-6">
                                        <span class="font-mono font-bold text-yellow-400"><?= esc($promo['code']) ?></span>
                                    </td>
                                    <td class="py-4 px-6">
                                        <span class="<?= $promo['type'] == 'percentage' ? 'bg-blue-500/20 text-blue-400' : 'bg-green-500/20 text-green-400' ?> px-3 py-1 rounded-full text-xs">
                                            <?= $promo['type'] == 'percentage' ? 'Percentage' : 'Fixed' ?>
                                        </span>
                                    </td>
                                    <td class="py-4 px-6 font-semibold">
                                        <?php if ($promo['type'] == 'percentage'): ?>
                                            <?= $promo['value'] ?>%
                                        <?php else: ?>
                                            Rp <?= number_format($promo['value'], 0, ',', '.') ?>
                                        <?php endif; ?>
                                    </td>
                                    <td class="py-4 px-6">
                                        Rp <?= number_format($promo['min_transaction'], 0, ',', '.') ?>
                                    </td>
                                    <td class="py-4 px-6">
                                        <span class="text-gray-400">
                                            <?= $promo['used_count'] ?> / <?= $promo['usage_limit'] ?: '∞' ?>
                                        </span>
                                    </td>
                                    <td class="py-4 px-6 text-sm">
                                        <?php if ($promo['valid_until']): ?>
                                            <?= date('d M Y', strtotime($promo['valid_until'])) ?>
                                        <?php else: ?>
                                            <span class="text-gray-500">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="py-4 px-6">
                                        <?php
                                        $isExpired = $promo['valid_until'] && strtotime($promo['valid_until']) < time();
                                        $isLimitReached = $promo['usage_limit'] && $promo['used_count'] >= $promo['usage_limit'];
                                        ?>
                                        
                                        <?php if ($isExpired): ?>
                                            <span class="bg-red-500/20 text-red-400 px-3 py-1 rounded-full text-xs">
                                                Expired
                                            </span>
                                        <?php elseif ($isLimitReached): ?>
                                            <span class="bg-orange-500/20 text-orange-400 px-3 py-1 rounded-full text-xs">
                                                Limit Reached
                                            </span>
                                        <?php elseif ($promo['is_active']): ?>
                                            <span class="bg-green-500/20 text-green-400 px-3 py-1 rounded-full text-xs">
                                                Active
                                            </span>
                                        <?php else: ?>
                                            <span class="bg-gray-500/20 text-gray-400 px-3 py-1 rounded-full text-xs">
                                                Inactive
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Info Card -->
            <div class="mt-6 bg-indigo-500/10 border border-indigo-500/30 rounded-xl p-6">
                <h3 class="font-bold mb-2 text-indigo-400">
                    <i class="fas fa-info-circle mr-2"></i>
                    Informasi Promo
                </h3>
                <ul class="text-sm text-gray-300 space-y-1">
                    <li>• <strong>Percentage:</strong> Diskon persentase dari total (contoh: 10%)</li>
                    <li>• <strong>Fixed:</strong> Potongan harga tetap (contoh: Rp 5.000)</li>
                    <li>• <strong>Min. Transaksi:</strong> Minimal pembelian untuk pakai promo</li>
                    <li>• <strong>Usage:</strong> Berapa kali promo sudah dipakai</li>
                </ul>
            </div>
        </main>
    </div>
</body>
</html>