<?php
// ============================================
// NEW: app/Views/admin/games/index.php
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
                <a href="<?= base_url('admin/games') ?>" class="flex items-center gap-3 px-4 py-3 rounded-lg bg-indigo-600 text-white">
                    <i class="fas fa-gamepad"></i> Games
                </a>
                <a href="<?= base_url('admin/products') ?>" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-gray-700 text-gray-300">
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
                <h1 class="text-3xl font-bold">Kelola Games</h1>
                <button onclick="alert('Feature coming soon!')" class="bg-indigo-600 px-6 py-3 rounded-xl font-semibold">
                    <i class="fas fa-plus mr-2"></i> Tambah Game
                </button>
            </div>

            <!-- Games Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
                <?php foreach ($games as $game): ?>
                <div class="bg-gray-800 rounded-2xl overflow-hidden">
                    <img src="<?= base_url('assets/images/games/' . $game['image']) ?>" 
                         alt="<?= esc($game['name']) ?>"
                         class="w-full h-40 object-cover"
                         onerror="this.src='https://via.placeholder.com/300x200/4a5568/ffffff?text=<?= urlencode($game['name']) ?>'">
                    <div class="p-4">
                        <h3 class="font-bold text-lg mb-2"><?= esc($game['name']) ?></h3>
                        <p class="text-gray-400 text-sm mb-3"><?= esc($game['category']) ?></p>
                        <div class="flex gap-2">
                            <span class="<?= $game['is_popular'] ? 'bg-yellow-500/20 text-yellow-400' : 'bg-gray-700 text-gray-400' ?> px-3 py-1 rounded-full text-xs">
                                <?= $game['is_popular'] ? 'Popular' : 'Regular' ?>
                            </span>
                            <span class="<?= $game['is_active'] ? 'bg-green-500/20 text-green-400' : 'bg-red-500/20 text-red-400' ?> px-3 py-1 rounded-full text-xs">
                                <?= $game['is_active'] ? 'Active' : 'Inactive' ?>
                            </span>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </main>
    </div>
</body>
</html>
