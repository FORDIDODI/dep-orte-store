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
                <a href="<?= base_url('admin/promos') ?>" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-gray-700 text-gray-300">
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
                <h1 class="text-3xl font-bold">Kelola Produk</h1>
                <button onclick="openModal('add')" class="bg-indigo-600 px-6 py-3 rounded-xl font-semibold hover:bg-indigo-700">
                    <i class="fas fa-plus mr-2"></i> Tambah Produk
                </button>
            </div>

            <?php if (session()->getFlashdata('success')): ?>
            <div class="bg-green-500/20 border border-green-500 rounded-xl p-4 mb-6">
                <?= session()->getFlashdata('success') ?>
            </div>
            <?php endif; ?>

            <!-- Products Table -->
            <div class="bg-gray-800 rounded-2xl overflow-hidden">
                <table class="w-full">
                    <thead class="bg-gray-900">
                        <tr>
                            <th class="text-left py-4 px-6">Game</th>
                            <th class="text-left py-4 px-6">Nama Produk</th>
                            <th class="text-left py-4 px-6">Harga</th>
                            <th class="text-left py-4 px-6">Diskon</th>
                            <th class="text-left py-4 px-6">Status</th>
                            <th class="text-left py-4 px-6">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $product): ?>
                        <tr class="border-b border-gray-700 hover:bg-gray-700/30">
                            <td class="py-4 px-6"><?= esc($product['game_name']) ?></td>
                            <td class="py-4 px-6 font-semibold"><?= esc($product['name']) ?></td>
                            <td class="py-4 px-6">Rp <?= number_format($product['price'], 0, ',', '.') ?></td>
                            <td class="py-4 px-6">
                                <?php if ($product['discount_price']): ?>
                                    <span class="text-green-400 font-semibold">Rp <?= number_format($product['discount_price'], 0, ',', '.') ?></span>
                                <?php else: ?>
                                    <span class="text-gray-500">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="py-4 px-6">
                                <span class="<?= $product['is_active'] ? 'bg-green-500/20 text-green-400' : 'bg-red-500/20 text-red-400' ?> px-3 py-1 rounded-full text-xs">
                                    <?= $product['is_active'] ? 'Active' : 'Inactive' ?>
                                </span>
                            </td>
                            <td class="py-4 px-6">
                                <button onclick='editProduct(<?= json_encode($product) ?>)' class="text-blue-400 hover:text-blue-300 mr-3">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button onclick="deleteProduct(<?= $product['id'] ?>, '<?= esc($product['name']) ?>')" class="text-red-400 hover:text-red-300">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <!-- Modal Add/Edit Product -->
    <div id="productModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
        <div class="bg-gray-800 rounded-2xl p-8 max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold" id="modalTitle">Tambah Produk</h2>
                <button onclick="closeModal()" class="text-gray-400 hover:text-white">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>

            <form action="<?= base_url('admin/products/store') ?>" method="POST" id="productForm">
                <?= csrf_field() ?>
                <input type="hidden" name="id" id="productId">

                <!-- Game Selection -->
                <div class="mb-6">
                    <label class="block text-gray-300 mb-2">
                        <i class="fas fa-gamepad mr-2"></i>Pilih Game *
                    </label>
                    <select name="game_id" id="productGame" class="w-full bg-gray-900 border-2 border-gray-700 rounded-xl px-4 py-3" required>
                        <option value="">-- Pilih Game --</option>
                        <?php foreach ($games as $game): ?>
                        <option value="<?= $game['id'] ?>"><?= esc($game['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Product Name -->
                <div class="mb-6">
                    <label class="block text-gray-300 mb-2">
                        <i class="fas fa-box mr-2"></i>Nama Produk *
                    </label>
                    <input type="text" name="name" id="productName" class="w-full bg-gray-900 border-2 border-gray-700 rounded-xl px-4 py-3" placeholder="Contoh: 100 Diamonds" required>
                </div>

                <!-- Description -->
                <div class="mb-6">
                    <label class="block text-gray-300 mb-2">
                        <i class="fas fa-align-left mr-2"></i>Deskripsi
                    </label>
                    <textarea name="description" id="productDescription" rows="2" class="w-full bg-gray-900 border-2 border-gray-700 rounded-xl px-4 py-3" placeholder="Deskripsi produk..."></textarea>
                </div>

                <!-- Price -->
                <div class="mb-6">
                    <label class="block text-gray-300 mb-2">
                        <i class="fas fa-money-bill mr-2"></i>Harga Normal *
                    </label>
                    <input type="number" name="price" id="productPrice" class="w-full bg-gray-900 border-2 border-gray-700 rounded-xl px-4 py-3" placeholder="50000" required>
                </div>

                <!-- Discount Price -->
                <div class="mb-6">
                    <label class="block text-gray-300 mb-2">
                        <i class="fas fa-tag mr-2"></i>Harga Diskon (Opsional)
                    </label>
                    <input type="number" name="discount_price" id="productDiscount" class="w-full bg-gray-900 border-2 border-gray-700 rounded-xl px-4 py-3" placeholder="45000">
                    <p class="text-gray-400 text-sm mt-1">Kosongkan jika tidak ada diskon</p>
                </div>

                <!-- Active Status -->
                <div class="mb-6">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_active" id="productActive" value="1" checked class="w-5 h-5 rounded">
                        <span class="text-gray-300">
                            <i class="fas fa-check-circle text-green-400 mr-2"></i>Produk Aktif
                        </span>
                    </label>
                </div>

                <!-- Buttons -->
                <div class="flex gap-3">
                    <button type="submit" class="flex-1 bg-gradient-to-r from-indigo-600 to-purple-600 py-3 rounded-xl font-semibold">
                        <i class="fas fa-save mr-2"></i>Simpan
                    </button>
                    <button type="button" onclick="closeModal()" class="px-6 bg-gray-700 py-3 rounded-xl font-semibold">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal(mode) {
            document.getElementById('productModal').classList.remove('hidden');
            if (mode === 'add') {
                document.getElementById('modalTitle').textContent = 'Tambah Produk';
                document.getElementById('productForm').action = '<?= base_url('admin/products/store') ?>';
                document.getElementById('productForm').reset();
                document.getElementById('productActive').checked = true;
            }
        }

        function editProduct(product) {
            document.getElementById('productModal').classList.remove('hidden');
            document.getElementById('modalTitle').textContent = 'Edit Produk';
            document.getElementById('productForm').action = '<?= base_url('admin/products/update/') ?>' + product.id;
            
            document.getElementById('productId').value = product.id;
            document.getElementById('productGame').value = product.game_id;
            document.getElementById('productName').value = product.name;
            document.getElementById('productDescription').value = product.description || '';
            document.getElementById('productPrice').value = product.price;
            document.getElementById('productDiscount').value = product.discount_price || '';
            document.getElementById('productActive').checked = product.is_active == 1;
        }

        function closeModal() {
            document.getElementById('productModal').classList.add('hidden');
        }

        function deleteProduct(id, name) {
            if (confirm(`Hapus produk "${name}"?`)) {
                fetch('<?= base_url('admin/products/delete/') ?>' + id, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Error: ' + data.message);
                    }
                });
            }
        }

        document.getElementById('productModal').addEventListener('click', function(e) {
            if (e.target === this) closeModal();
        });
    </script>
</body>
</html>
