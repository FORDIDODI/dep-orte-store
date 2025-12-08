<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Deporte Store') ?></title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            background: linear-gradient(135deg, #1e1b29 0%, #2a2738 100%);
            min-height: 100vh;
        }

        .gradient-primary {
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
        }

        .card-hover:hover {
            transform: translateY(-5px);
            transition: all 0.3s ease;
        }
    </style>
</head>

<body class="text-white">
    <!-- Header -->
    <header class="bg-gray-800/80 backdrop-blur-lg sticky top-0 z-50 border-b border-gray-700">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between py-4">
                <!-- Logo -->
                <a href="<?= base_url('/') ?>" class="flex items-center invert">
                    <img src="<?= base_url('assets/images/logo.png') ?>" alt="Deporte Store" class="h-10">
                </a>

                <!-- Navigation -->
                <nav class="hidden md:flex items-center gap-8">
                    <a href="<?= base_url('/') ?>"
                        class="text-gray-300 hover:text-white transition <?= uri_string() == '' ? 'text-white font-semibold' : '' ?>">
                        Topup
                    </a>
                    <a href="<?= base_url('cek-transaksi') ?>"
                        class="text-gray-300 hover:text-white transition <?= uri_string() == 'cek-transaksi' ? 'text-white font-semibold' : '' ?>">
                        Cek Transaksi
                    </a>

                    <?php if (session()->get('logged_in')): ?>
                        <a href="<?= base_url('dashboard') ?>" class="text-gray-300 hover:text-white transition">
                            Dashboard
                        </a>
                        <a href="<?= base_url('auth/logout') ?>" class="text-gray-300 hover:text-white transition">
                            Logout
                        </a>
                    <?php else: ?>
                        <a href="<?= base_url('auth/login') ?>" class="text-gray-300 hover:text-white transition">
                            Masuk
                        </a>
                    <?php endif; ?>
                </nav>

                <!-- Language Selector -->
                <div class="flex items-center gap-2 bg-gray-700 px-4 py-2 rounded-lg cursor-pointer">
                    <img src="https://flagcdn.com/w40/id.png" alt="ID" class="w-6">
                    <span class="text-sm">ID/IDR</span>
                </div>

                <!-- Mobile Menu Button -->
                <button class="md:hidden text-white" onclick="toggleMobileMenu()">
                    <i class="fas fa-bars text-xl"></i>
                </button>
            </div>

            <!-- Mobile Menu -->
            <div id="mobileMenu" class="hidden md:hidden pb-4">
                <div class="flex flex-col gap-3">
                    <a href="<?= base_url('/') ?>" class="text-gray-300 hover:text-white">Topup</a>
                    <a href="<?= base_url('cek-transaksi') ?>" class="text-gray-300 hover:text-white">Cek Transaksi</a>
                    <?php if (session()->get('logged_in')): ?>
                        <a href="<?= base_url('dashboard') ?>" class="text-gray-300 hover:text-white">Dashboard</a>
                        <a href="<?= base_url('auth/logout') ?>" class="text-gray-300 hover:text-white">Logout</a>
                    <?php else: ?>
                        <a href="<?= base_url('auth/login') ?>" class="text-gray-300 hover:text-white">Masuk</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </header>

    <!-- Alerts -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="container mx-auto px-4 mt-4">
            <div class="bg-green-500/20 border border-green-500 text-green-300 px-6 py-4 rounded-lg">
                <i class="fas fa-check-circle mr-2"></i>
                <?= session()->getFlashdata('success') ?>
            </div>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="container mx-auto px-4 mt-4">
            <div class="bg-red-500/20 border border-red-500 text-red-300 px-6 py-4 rounded-lg">
                <i class="fas fa-exclamation-circle mr-2"></i>
                <?= session()->getFlashdata('error') ?>
            </div>
        </div>
    <?php endif; ?>

    <!-- Main Content -->
    <main class="py-8">
        <?= $this->renderSection('content') ?>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 mt-20 border-t border-gray-700">
        <div class="container mx-auto px-4 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <h3 class="font-bold text-lg mb-4">Situs Map</h3>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="<?= base_url('/') ?>" class="hover:text-white">Home</a></li>
                        <li><a href="#" class="hover:text-white">Promo</a></li>
                        <li><a href="#" class="hover:text-white">Tentang</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="font-bold text-lg mb-4">Dukungan</h3>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-white">WhatsApp</a></li>
                        <li><a href="#" class="hover:text-white">Instagram</a></li>
                        <li><a href="<?= base_url('cek-transaksi') ?>" class="hover:text-white">Cek Transaksi</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="font-bold text-lg mb-4">Metode Pembayaran</h3>
                    <div class="flex flex-wrap gap-2">
                        <div class="bg-blue-600 px-3 py-1 rounded text-xs">BCA</div>
                        <div class="bg-orange-600 px-3 py-1 rounded text-xs">BNI</div>
                        <div class="bg-blue-800 px-3 py-1 rounded text-xs">BRI</div>
                        <div class="bg-yellow-500 text-black px-3 py-1 rounded text-xs">QRIS</div>
                    </div>
                </div>

                <div>
                    <h3 class="font-bold text-lg mb-4">Ikuti Kami</h3>
                    <div class="flex gap-3">
                        <a href="#" class="w-10 h-10 bg-gray-700 rounded-full flex items-center justify-center hover:bg-indigo-500 transition">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-gray-700 rounded-full flex items-center justify-center hover:bg-green-500 transition">
                            <i class="fab fa-whatsapp"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-gray-700 rounded-full flex items-center justify-center hover:bg-pink-500 transition">
                            <i class="fab fa-tiktok"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400 text-sm">
                <p>&copy; <?= date('Y') ?> DeporteStore.com All rights reserved</p>
            </div>
        </div>
    </footer>

    <script>
        function toggleMobileMenu() {
            const menu = document.getElementById('mobileMenu');
            menu.classList.toggle('hidden');
        }

        // Auto-hide alerts
        setTimeout(() => {
            const alerts = document.querySelectorAll('.bg-green-500\\/20, .bg-red-500\\/20');
            alerts.forEach(alert => {
                alert.style.opacity = '0';
                alert.style.transition = 'opacity 0.5s';
                setTimeout(() => alert.remove(), 500);
            });
        }, 5000);
    </script>

    <?= $this->renderSection('scripts') ?>
</body>

</html>