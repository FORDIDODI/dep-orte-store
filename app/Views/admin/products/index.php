<?php
// ============================================
// NEW: app/Views/admin/products/index.php
// ============================================
?>
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
                <a href="<?= base_url('admin/products') ?>" class="flex items-center gap-3 px-4 py-3 rounded-lg bg-indigo-600 text-white">
                    <i class="fas fa-box"></i> Produk
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
                <h1 class="text-3xl font-bold">Kelola Produk</h1>
                <button onclick="alert('Feature coming soon!')" class="bg-indigo-600 px-6 py-3 rounded-xl font-semibold">
                    <i class="fas fa-plus mr-2"></i> Tambah Produk
                </button>
            </div>

            <!-- Products Table -->
            <div class="bg-gray-800 rounded-2xl overflow-hidden">
                <table class="w-full">
                    <thead class="bg-gray-900">
                        <tr>
                            <th class="text-left py-4 px-6">Nama Produk</th>
                            <th class="text-left py-4 px-6">Game</th>
                            <th class="text-left py-4 px-6">Harga</th>
                            <th class="text-left py-4 px-6">Diskon</th>
                            <th class="text-left py-4 px-6">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $product): ?>
                        <tr class="border-b border-gray-700 hover:bg-gray-700/30">
                            <td class="py-4 px-6 font-semibold"><?= esc($product['name']) ?></td>
                            <td class="py-4 px-6"><?= esc($product['game_name']) ?></td>
                            <td class="py-4 px-6">Rp <?= number_format($product['price'], 0, ',', '.') ?></td>
                            <td class="py-4 px-6">
                                <?php if ($product['discount_price']): ?>
                                    <span class="text-green-400">Rp <?= number_format($product['discount_price'], 0, ',', '.') ?></span>
                                <?php else: ?>
                                    <span class="text-gray-500">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="py-4 px-6">
                                <span class="<?= $product['is_active'] ? 'bg-green-500/20 text-green-400' : 'bg-red-500/20 text-red-400' ?> px-3 py-1 rounded-full text-xs">
                                    <?= $product['is_active'] ? 'Active' : 'Inactive' ?>
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>
</html>
