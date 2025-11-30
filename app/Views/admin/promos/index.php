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
                <button onclick="openModal('add')" class="bg-indigo-600 px-6 py-3 rounded-xl font-semibold hover:bg-indigo-700">
                    <i class="fas fa-plus mr-2"></i> Tambah Promo
                </button>
            </div>

            <?php if (session()->getFlashdata('success')): ?>
            <div class="bg-green-500/20 border border-green-500 rounded-xl p-4 mb-6">
                <?= session()->getFlashdata('success') ?>
            </div>
            <?php endif; ?>

            <!-- Promos Table -->
            <div class="bg-gray-800 rounded-2xl overflow-hidden">
                <table class="w-full">
                    <thead class="bg-gray-900">
                        <tr>
                            <th class="text-left py-4 px-6">Kode</th>
                            <th class="text-left py-4 px-6">Tipe</th>
                            <th class="text-left py-4 px-6">Value</th>
                            <th class="text-left py-4 px-6">Min. Trans.</th>
                            <th class="text-left py-4 px-6">Usage</th>
                            <th class="text-left py-4 px-6">Exp. Date</th>
                            <th class="text-left py-4 px-6">Status</th>
                            <th class="text-left py-4 px-6">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($promos as $promo): ?>
                        <tr class="border-b border-gray-700 hover:bg-gray-700/30">
                            <td class="py-4 px-6 font-mono font-bold text-yellow-400"><?= esc($promo['code']) ?></td>
                            <td class="py-4 px-6">
                                <span class="<?= $promo['type'] == 'percentage' ? 'bg-blue-500/20 text-blue-400' : 'bg-green-500/20 text-green-400' ?> px-3 py-1 rounded-full text-xs">
                                    <?= $promo['type'] == 'percentage' ? 'Percentage' : 'Fixed' ?>
                                </span>
                            </td>
                            <td class="py-4 px-6 font-semibold">
                                <?= $promo['type'] == 'percentage' ? $promo['value'] . '%' : 'Rp ' . number_format($promo['value'], 0, ',', '.') ?>
                            </td>
                            <td class="py-4 px-6">Rp <?= number_format($promo['min_transaction'], 0, ',', '.') ?></td>
                            <td class="py-4 px-6"><?= $promo['used_count'] ?> / <?= $promo['usage_limit'] ?: '∞' ?></td>
                            <td class="py-4 px-6 text-sm"><?= $promo['valid_until'] ? date('d M Y', strtotime($promo['valid_until'])) : '-' ?></td>
                            <td class="py-4 px-6">
                                <span class="<?= $promo['is_active'] ? 'bg-green-500/20 text-green-400' : 'bg-red-500/20 text-red-400' ?> px-3 py-1 rounded-full text-xs">
                                    <?= $promo['is_active'] ? 'Active' : 'Inactive' ?>
                                </span>
                            </td>
                            <td class="py-4 px-6">
                                <button onclick='editPromo(<?= json_encode($promo) ?>)' class="text-blue-400 hover:text-blue-300 mr-3">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button onclick="deletePromo(<?= $promo['id'] ?>, '<?= esc($promo['code']) ?>')" class="text-red-400 hover:text-red-300">
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

    <!-- Modal Add/Edit Promo -->
    <div id="promoModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
        <div class="bg-gray-800 rounded-2xl p-8 max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold" id="modalTitle">Tambah Promo</h2>
                <button onclick="closeModal()" class="text-gray-400 hover:text-white">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>

            <form action="<?= base_url('admin/promos/store') ?>" method="POST" id="promoForm">
                <?= csrf_field() ?>
                <input type="hidden" name="id" id="promoId">

                <!-- Promo Code -->
                <div class="mb-6">
                    <label class="block text-gray-300 mb-2">
                        <i class="fas fa-ticket mr-2"></i>Kode Promo *
                    </label>
                    <input type="text" name="code" id="promoCode" class="w-full bg-gray-900 border-2 border-gray-700 rounded-xl px-4 py-3 uppercase" placeholder="WELCOME10" required>
                    <p class="text-gray-400 text-sm mt-1">Huruf kapital, tanpa spasi</p>
                </div>

                <!-- Type -->
                <div class="mb-6">
                    <label class="block text-gray-300 mb-2">
                        <i class="fas fa-cogs mr-2"></i>Tipe Diskon *
                    </label>
                    <select name="type" id="promoType" class="w-full bg-gray-900 border-2 border-gray-700 rounded-xl px-4 py-3" required onchange="updateValueLabel()">
                        <option value="percentage">Percentage (%)</option>
                        <option value="fixed">Fixed Amount (Rp)</option>
                    </select>
                </div>

                <!-- Value -->
                <div class="mb-6">
                    <label class="block text-gray-300 mb-2">
                        <i class="fas fa-calculator mr-2"></i><span id="valueLabel">Nilai Diskon (%)</span> *
                    </label>
                    <input type="number" name="value" id="promoValue" class="w-full bg-gray-900 border-2 border-gray-700 rounded-xl px-4 py-3" placeholder="10" required>
                </div>

                <!-- Min Transaction -->
                <div class="mb-6">
                    <label class="block text-gray-300 mb-2">
                        <i class="fas fa-money-bill mr-2"></i>Minimal Transaksi *
                    </label>
                    <input type="number" name="min_transaction" id="promoMin" class="w-full bg-gray-900 border-2 border-gray-700 rounded-xl px-4 py-3" placeholder="50000" required>
                </div>

                <!-- Max Discount -->
                <div class="mb-6">
                    <label class="block text-gray-300 mb-2">
                        <i class="fas fa-tag mr-2"></i>Maksimal Diskon (Opsional)
                    </label>
                    <input type="number" name="max_discount" id="promoMax" class="w-full bg-gray-900 border-2 border-gray-700 rounded-xl px-4 py-3" placeholder="50000">
                    <p class="text-gray-400 text-sm mt-1">Untuk percentage type</p>
                </div>

                <!-- Usage Limit -->
                <div class="mb-6">
                    <label class="block text-gray-300 mb-2">
                        <i class="fas fa-users mr-2"></i>Batas Penggunaan
                    </label>
                    <input type="number" name="usage_limit" id="promoLimit" class="w-full bg-gray-900 border-2 border-gray-700 rounded-xl px-4 py-3" placeholder="100">
                    <p class="text-gray-400 text-sm mt-1">Kosongkan untuk unlimited</p>
                </div>

                <!-- Valid Until -->
                <div class="mb-6">
                    <label class="block text-gray-300 mb-2">
                        <i class="fas fa-calendar mr-2"></i>Berlaku Hingga
                    </label>
                    <input type="date" name="valid_until" id="promoExpiry" class="w-full bg-gray-900 border-2 border-gray-700 rounded-xl px-4 py-3">
                </div>

                <!-- Active Status -->
                <div class="mb-6">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_active" id="promoActive" value="1" checked class="w-5 h-5 rounded">
                        <span class="text-gray-300">
                            <i class="fas fa-check-circle text-green-400 mr-2"></i>Promo Aktif
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
        // Auto uppercase code
        document.getElementById('promoCode').addEventListener('input', function(e) {
            e.target.value = e.target.value.toUpperCase().replace(/[^A-Z0-9]/g, '');
        });

        function updateValueLabel() {
            const type = document.getElementById('promoType').value;
            document.getElementById('valueLabel').textContent = type === 'percentage' ? 'Nilai Diskon (%)' : 'Nilai Diskon (Rp)';
        }

        function openModal(mode) {
            document.getElementById('promoModal').classList.remove('hidden');
            if (mode === 'add') {
                document.getElementById('modalTitle').textContent = 'Tambah Promo';
                document.getElementById('promoForm').action = '<?= base_url('admin/promos/store') ?>';
                document.getElementById('promoForm').reset();
                document.getElementById('promoActive').checked = true;
            }
        }

        function editPromo(promo) {
            document.getElementById('promoModal').classList.remove('hidden');
            document.getElementById('modalTitle').textContent = 'Edit Promo';
            document.getElementById('promoForm').action = '<?= base_url('admin/promos/update/') ?>' + promo.id;
            
            document.getElementById('promoId').value = promo.id;
            document.getElementById('promoCode').value = promo.code;
            document.getElementById('promoType').value = promo.type;
            document.getElementById('promoValue').value = promo.value;
            document.getElementById('promoMin').value = promo.min_transaction;
            document.getElementById('promoMax').value = promo.max_discount || '';
            document.getElementById('promoLimit').value = promo.usage_limit || '';
            document.getElementById('promoExpiry').value = promo.valid_until || '';
            document.getElementById('promoActive').checked = promo.is_active == 1;
            updateValueLabel();
        }

        function closeModal() {
            document.getElementById('promoModal').classList.add('hidden');
        }

        function deletePromo(id, code) {
            if (confirm(`Hapus promo "${code}"?`)) {
                fetch('<?= base_url('admin/promos/delete/') ?>' + id, {
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
                    }
                });
            }
        }

        document.getElementById('promoModal').addEventListener('click', function(e) {
            if (e.target === this) closeModal();
        });
    </script>
</body>
</html>
