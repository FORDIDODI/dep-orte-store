<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container mx-auto px-4">
    <div class="max-w-2xl mx-auto">
        <div class="bg-gray-800 rounded-2xl p-8 mt-12">
            <div class="text-center mb-8">
                <i class="fas fa-search text-6xl text-indigo-400 mb-4"></i>
                <h1 class="text-3xl font-bold mb-2">Cek Status Transaksi</h1>
                <p class="text-gray-400">Masukkan nomor invoice untuk melihat status transaksi Anda</p>
            </div>

            <?php if (session()->getFlashdata('error')): ?>
            <div class="bg-red-500/20 border border-red-500 rounded-xl p-4 mb-6">
                <i class="fas fa-exclamation-circle mr-2"></i>
                <?= session()->getFlashdata('error') ?>
            </div>
            <?php endif; ?>

            <form action="<?= base_url('cek-transaksi/search') ?>" method="POST">
                <?= csrf_field() ?>
                
                <div class="mb-6">
                    <label class="block text-gray-300 mb-3 text-lg">Nomor Invoice</label>
                    <input type="text" 
                           name="invoice" 
                           id="invoiceInput"
                           value="<?= esc($invoice ?? '') ?>"
                           class="w-full bg-gray-900 border-2 border-gray-700 rounded-xl px-6 py-4 text-lg font-mono focus:border-indigo-500 focus:outline-none transition" 
                           placeholder="Contoh: INV20240101ABC123"
                           required>
                    <p class="text-gray-400 text-sm mt-2">
                        <i class="fas fa-info-circle mr-1"></i>
                        Invoice dapat dilihat di email konfirmasi atau halaman pembayaran
                    </p>
                </div>

                <button type="submit" 
                        class="w-full gradient-primary py-4 rounded-xl font-bold text-lg hover:opacity-90 transition">
                    <i class="fas fa-search mr-2"></i>
                    Cek Status Transaksi
                </button>
            </form>

            <div class="mt-8 pt-8 border-t border-gray-700">
                <h3 class="font-semibold mb-3">Butuh Bantuan?</h3>
                <div class="flex flex-col gap-3">
                    <a href="#" class="flex items-center gap-3 text-green-400 hover:text-green-300 transition">
                        <i class="fab fa-whatsapp text-xl"></i>
                        <span>Hubungi WhatsApp CS</span>
                    </a>
                    <a href="#" class="flex items-center gap-3 text-pink-400 hover:text-pink-300 transition">
                        <i class="fab fa-instagram text-xl"></i>
                        <span>DM Instagram @DeporteStore</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>