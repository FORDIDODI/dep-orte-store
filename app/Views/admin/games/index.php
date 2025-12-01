<!-- ============================================ -->
<!-- app/Views/admin/games/index.php - WITH MODAL -->
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
                <a href="<?= base_url('admin/games') ?>" class="flex items-center gap-3 px-4 py-3 rounded-lg bg-indigo-600 text-white">
                    <i class="fas fa-gamepad"></i> Games
                </a>
                <a href="<?= base_url('admin/products') ?>" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-gray-700 text-gray-300">
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
                <h1 class="text-3xl font-bold">Kelola Games</h1>
                <button onclick="openModal('add')" class="bg-indigo-600 px-6 py-3 rounded-xl font-semibold hover:bg-indigo-700">
                    <i class="fas fa-plus mr-2"></i> Tambah Game
                </button>
            </div>

            <?php if (session()->getFlashdata('success')): ?>
            <div class="bg-green-500/20 border border-green-500 rounded-xl p-4 mb-6">
                <i class="fas fa-check-circle mr-2"></i>
                <?= session()->getFlashdata('success') ?>
            </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
            <div class="bg-red-500/20 border border-red-500 rounded-xl p-4 mb-6">
                <i class="fas fa-exclamation-circle mr-2"></i>
                <?= session()->getFlashdata('error') ?>
            </div>
            <?php endif; ?>

            <!-- Games Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
                <?php foreach ($games as $game): ?>
                <div class="bg-gray-800 rounded-2xl overflow-hidden">
                    <div class="relative">
                        <img src="<?= base_url('assets/images/games/' . $game['image']) ?>" 
                             alt="<?= esc($game['name']) ?>"
                             class="w-full h-40 object-cover bg-gray-700"
                             onerror="this.onerror=null; this.src='data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'300\' height=\'200\'%3E%3Crect fill=\'%234a5568\' width=\'300\' height=\'200\'/%3E%3Ctext fill=\'%23ffffff\' font-family=\'Arial\' font-size=\'16\' x=\'50%25\' y=\'50%25\' text-anchor=\'middle\' dominant-baseline=\'middle\'%3E<?= urlencode($game['name']) ?>%3C/text%3E%3C/svg%3E';">
                        <div class="absolute top-2 right-2 flex gap-2">
                            <button onclick='editGame(<?= json_encode($game) ?>)' 
                                    class="bg-blue-600 hover:bg-blue-700 w-8 h-8 rounded-full flex items-center justify-center">
                                <i class="fas fa-edit text-sm"></i>
                            </button>
                            <button onclick="deleteGame(<?= $game['id'] ?>, '<?= esc($game['name']) ?>')" 
                                    class="bg-red-600 hover:bg-red-700 w-8 h-8 rounded-full flex items-center justify-center">
                                <i class="fas fa-trash text-sm"></i>
                            </button>
                        </div>
                    </div>
                    <div class="p-4">
                        <h3 class="font-bold text-lg mb-2"><?= esc($game['name']) ?></h3>
                        <p class="text-gray-400 text-sm mb-3"><?= esc($game['category']) ?></p>
                        <p class="text-gray-400 text-sm mb-3"><?= esc($game['description'] ?? '') ?></p>
                        <div class="flex gap-2">
                            <span class="<?= $game['is_popular'] ? 'bg-yellow-500/20 text-yellow-400' : 'bg-gray-700 text-gray-400' ?> px-3 py-1 rounded-full text-xs">
                                <?= $game['is_popular'] ? '⭐ Popular' : 'Regular' ?>
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

    <!-- Modal Add/Edit -->
    <div id="gameModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
        <div class="bg-gray-800 rounded-2xl p-8 max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold" id="modalTitle">Tambah Game</h2>
                <button onclick="closeModal()" class="text-gray-400 hover:text-white">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>

            <form action="<?= base_url('admin/games/store') ?>" method="POST" enctype="multipart/form-data" id="gameForm">
                <?= csrf_field() ?>
                <input type="hidden" name="id" id="gameId">
                <input type="hidden" name="_method" id="formMethod" value="POST">

                <!-- Image Preview -->
                <div class="mb-6">
                    <label class="block text-gray-300 mb-2">Preview Gambar</label>
                    <div class="relative w-full h-48 bg-gray-900 rounded-xl overflow-hidden border-2 border-gray-700">
                        <img id="imagePreview" 
                             src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='400' height='200'%3E%3Crect fill='%234a5568' width='400' height='200'/%3E%3Ctext fill='%23ffffff' font-family='Arial' font-size='18' x='50%25' y='50%25' text-anchor='middle' dominant-baseline='middle'%3EUpload Image%3C/text%3E%3C/svg%3E" 
                             class="w-full h-full object-cover"
                             alt="Preview">
                        <div id="imagePreviewOverlay" class="absolute inset-0 bg-black/50 flex items-center justify-center hidden">
                            <span class="text-white text-sm">Loading...</span>
                        </div>
                    </div>
                </div>

                <!-- Upload Image -->
                <div class="mb-6">
                    <label class="block text-gray-300 mb-2">
                        <i class="fas fa-image mr-2"></i>Upload Gambar
                    </label>
                    <div class="relative">
                        <input type="file" 
                               name="image" 
                               id="imageInput"
                               accept="image/jpeg,image/jpg,image/png,image/webp"
                               onchange="previewImage(this)"
                               class="w-full bg-gray-900 border-2 border-gray-700 rounded-xl px-4 py-3 focus:border-indigo-500 focus:outline-none file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-500 file:text-white hover:file:bg-indigo-600">
                    </div>
                    <p class="text-gray-400 text-sm mt-1">Format: JPG, PNG, WEBP. Max: 2MB</p>
                    <p id="imageError" class="text-red-400 text-sm mt-1 hidden"></p>
                </div>

                <!-- Name -->
                <div class="mb-6">
                    <label class="block text-gray-300 mb-2">
                        <i class="fas fa-gamepad mr-2"></i>Nama Game *
                    </label>
                    <input type="text" 
                           name="name" 
                           id="gameName"
                           class="w-full bg-gray-900 border-2 border-gray-700 rounded-xl px-4 py-3 focus:border-indigo-500 focus:outline-none"
                           placeholder="Contoh: Mobile Legends"
                           required>
                </div>

                <!-- Slug -->
                <div class="mb-6">
                    <label class="block text-gray-300 mb-2">
                        <i class="fas fa-link mr-2"></i>Slug (URL) *
                    </label>
                    <input type="text" 
                           name="slug" 
                           id="gameSlug"
                           class="w-full bg-gray-900 border-2 border-gray-700 rounded-xl px-4 py-3 focus:border-indigo-500 focus:outline-none"
                           placeholder="Contoh: mobile-legends"
                           required>
                    <p class="text-gray-400 text-sm mt-1">Gunakan huruf kecil dan strip (-)</p>
                </div>

                <!-- Category -->
                <div class="mb-6">
                    <label class="block text-gray-300 mb-2">
                        <i class="fas fa-tags mr-2"></i>Kategori *
                    </label>
                    <select name="category" 
                            id="gameCategory"
                            class="w-full bg-gray-900 border-2 border-gray-700 rounded-xl px-4 py-3 focus:border-indigo-500 focus:outline-none"
                            required>
                        <option value="">Pilih Kategori</option>
                        <option value="MOBA">MOBA</option>
                        <option value="Battle Royale">Battle Royale</option>
                        <option value="RPG">RPG</option>
                        <option value="FPS">FPS</option>
                        <option value="Strategy">Strategy</option>
                        <option value="Sports">Sports</option>
                        <option value="Racing">Racing</option>
                    </select>
                </div>

                <!-- Description -->
                <div class="mb-6">
                    <label class="block text-gray-300 mb-2">
                        <i class="fas fa-align-left mr-2"></i>Deskripsi
                    </label>
                    <textarea name="description" 
                              id="gameDescription"
                              rows="3"
                              class="w-full bg-gray-900 border-2 border-gray-700 rounded-xl px-4 py-3 focus:border-indigo-500 focus:outline-none"
                              placeholder="Deskripsi singkat tentang game..."></textarea>
                </div>

                <!-- Options -->
                <div class="mb-6 space-y-3">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" 
                               name="is_popular" 
                               id="gamePopular"
                               value="1"
                               class="w-5 h-5 rounded">
                        <span class="text-gray-300">
                            <i class="fas fa-star text-yellow-400 mr-2"></i>Tandai sebagai Popular
                        </span>
                    </label>

                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" 
                               name="is_active" 
                               id="gameActive"
                               value="1"
                               checked
                               class="w-5 h-5 rounded">
                        <span class="text-gray-300">
                            <i class="fas fa-check-circle text-green-400 mr-2"></i>Game Aktif
                        </span>
                    </label>
                </div>

                <!-- Buttons -->
                <div class="flex gap-3">
                    <button type="submit" 
                            class="flex-1 bg-gradient-to-r from-indigo-600 to-purple-600 py-3 rounded-xl font-semibold hover:opacity-90">
                        <i class="fas fa-save mr-2"></i>Simpan
                    </button>
                    <button type="button" 
                            onclick="closeModal()"
                            class="px-6 bg-gray-700 hover:bg-gray-600 py-3 rounded-xl font-semibold">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Auto generate slug from name
        document.getElementById('gameName').addEventListener('input', function(e) {
            const slug = e.target.value
                .toLowerCase()
                .replace(/[^a-z0-9\s-]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-');
            document.getElementById('gameSlug').value = slug;
        });

        // Preview image
        function previewImage(input) {
            const preview = document.getElementById('imagePreview');
            const overlay = document.getElementById('imagePreviewOverlay');
            const errorMsg = document.getElementById('imageError');
            
            // Reset error message
            if (errorMsg) {
                errorMsg.classList.add('hidden');
                errorMsg.textContent = '';
            }
            
            if (input.files && input.files[0]) {
                const file = input.files[0];
                const maxSize = 2 * 1024 * 1024; // 2MB
                const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
                
                // Validate file size
                if (file.size > maxSize) {
                    if (errorMsg) {
                        errorMsg.textContent = 'Ukuran file terlalu besar! Maksimal 2MB.';
                        errorMsg.classList.remove('hidden');
                    }
                    input.value = '';
                    return;
                }
                
                // Validate file type
                if (!allowedTypes.includes(file.type)) {
                    if (errorMsg) {
                        errorMsg.textContent = 'Format file tidak didukung! Gunakan JPG, PNG, atau WEBP.';
                        errorMsg.classList.remove('hidden');
                    }
                    input.value = '';
                    return;
                }
                
                // Show loading overlay
                if (overlay) {
                    overlay.classList.remove('hidden');
                }
                
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    if (overlay) {
                        overlay.classList.add('hidden');
                    }
                };
                reader.onerror = function() {
                    if (errorMsg) {
                        errorMsg.textContent = 'Gagal membaca file!';
                        errorMsg.classList.remove('hidden');
                    }
                    if (overlay) {
                        overlay.classList.add('hidden');
                    }
                };
                reader.readAsDataURL(file);
            } else {
                // Reset to default if no file selected
                preview.src = 'data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'400\' height=\'200\'%3E%3Crect fill=\'%234a5568\' width=\'400\' height=\'200\'/%3E%3Ctext fill=\'%23ffffff\' font-family=\'Arial\' font-size=\'18\' x=\'50%25\' y=\'50%25\' text-anchor=\'middle\' dominant-baseline=\'middle\'%3EUpload Image%3C/text%3E%3C/svg%3E';
            }
        }

        // Open modal
        function openModal(mode) {
            document.getElementById('gameModal').classList.remove('hidden');
            if (mode === 'add') {
                document.getElementById('modalTitle').textContent = 'Tambah Game';
                document.getElementById('gameForm').action = '<?= base_url('admin/games/store') ?>';
                document.getElementById('gameForm').reset();
                document.getElementById('imageInput').value = '';
                document.getElementById('imagePreview').src = 'data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'400\' height=\'200\'%3E%3Crect fill=\'%234a5568\' width=\'400\' height=\'200\'/%3E%3Ctext fill=\'%23ffffff\' font-family=\'Arial\' font-size=\'18\' x=\'50%25\' y=\'50%25\' text-anchor=\'middle\' dominant-baseline=\'middle\'%3EUpload Image%3C/text%3E%3C/svg%3E';
                const errorMsg = document.getElementById('imageError');
                if (errorMsg) {
                    errorMsg.classList.add('hidden');
                    errorMsg.textContent = '';
                }
            }
        }

        // Edit game
        function editGame(game) {
            document.getElementById('gameModal').classList.remove('hidden');
            document.getElementById('modalTitle').textContent = 'Edit Game';
            document.getElementById('gameForm').action = '<?= base_url('admin/games/update/') ?>' + game.id;
            document.getElementById('formMethod').value = 'POST';
            
            document.getElementById('gameId').value = game.id;
            document.getElementById('gameName').value = game.name;
            document.getElementById('gameSlug').value = game.slug;
            document.getElementById('gameCategory').value = game.category;
            document.getElementById('gameDescription').value = game.description || '';
            document.getElementById('gamePopular').checked = game.is_popular == 1;
            document.getElementById('gameActive').checked = game.is_active == 1;
            
            // Set image preview
            const imageUrl = '<?= base_url('assets/images/games/') ?>' + game.image;
            const preview = document.getElementById('imagePreview');
            preview.src = imageUrl;
            preview.onerror = function() {
                this.src = 'data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'400\' height=\'200\'%3E%3Crect fill=\'%234a5568\' width=\'400\' height=\'200\'/%3E%3Ctext fill=\'%23ffffff\' font-family=\'Arial\' font-size=\'18\' x=\'50%25\' y=\'50%25\' text-anchor=\'middle\' dominant-baseline=\'middle\'%3E<?= urlencode('No Image') ?>%3C/text%3E%3C/svg%3E';
            };
        }

        // Close modal
        function closeModal() {
            document.getElementById('gameModal').classList.add('hidden');
        }

        // Delete game
        function deleteGame(id, name) {
            if (confirm(`Hapus game "${name}"?\n\nSemua produk terkait juga akan terhapus!`)) {
                fetch('<?= base_url('admin/games/delete/') ?>' + id, {
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

        // Close modal when clicking outside
        document.getElementById('gameModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });
    </script>
</body>
</html>