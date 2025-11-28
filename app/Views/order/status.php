<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container mx-auto px-4 max-w-3xl">
    <!-- Status Header -->
    <div class="text-center mb-8">
        <?php if ($transaction['status'] == 'success'): ?>
            <div class="w-32 h-32 bg-gradient-to-br from-green-500 to-green-600 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-check text-6xl"></i>
            </div>
            <h2 class="text-3xl font-bold text-green-400">Transaksi Berhasil!</h2>
        <?php elseif ($transaction['status'] == 'processing'): ?>
            <div class="w-32 h-32 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-clock text-6xl animate-pulse"></i>
            </div>
            <h2 class="text-3xl font-bold text-blue-400">Sedang Diproses</h2>
        <?php elseif ($transaction['status'] == 'expired'): ?>
            <div class="w-32 h-32 bg-gradient-to-br from-red-500 to-red-600 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-times text-6xl"></i>
            </div>
            <h2 class="text-3xl font-bold text-red-400">Transaksi Kedaluwarsa</h2>
        <?php else: ?>
            <div class="w-32 h-32 bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-hourglass-half text-6xl animate-pulse"></i>
            </div>
            <h2 class="text-3xl font-bold text-yellow-400">Menunggu Pembayaran</h2>
        <?php endif; ?>
    </div>

    <!-- Progress Steps -->
    <div class="flex items-center justify-between mb-12 px-4">
        <div class="flex flex-col items-center flex-1">
            <div class="w-16 h-16 rounded-full <?= in_array($transaction['status'], ['pending', 'processing', 'success']) ? 'bg-gradient-to-br from-indigo-500 to-purple-500' : 'bg-gray-700' ?> flex items-center justify-center mb-2">
                <i class="fas fa-shopping-cart text-2xl"></i>
            </div>
            <p class="text-xs text-center text-gray-400">Transaksi<br>Dibuat</p>
        </div>

        <div class="h-1 flex-1 <?= in_array($transaction['status'], ['processing', 'success']) ? 'bg-gradient-to-r from-indigo-500 to-purple-500' : 'bg-gray-700' ?> mx-2"></div>

        <div class="flex flex-col items-center flex-1">
            <div class="w-16 h-16 rounded-full <?= in_array($transaction['status'], ['processing', 'success']) ? 'bg-gradient-to-br from-indigo-500 to-purple-500' : 'bg-gray-700' ?> flex items-center justify-center mb-2">
                <i class="fas fa-credit-card text-2xl"></i>
            </div>
            <p class="text-xs text-center text-gray-400">Pembayaran<br>Diterima</p>
        </div>

        <div class="h-1 flex-1 <?= $transaction['status'] == 'success' ? 'bg-gradient-to-r from-indigo-500 to-purple-500' : 'bg-gray-700' ?> mx-2"></div>

        <div class="flex flex-col items-center flex-1">
            <div class="w-16 h-16 rounded-full <?= $transaction['status'] == 'success' ? 'bg-gradient-to-br from-indigo-500 to-purple-500' : 'bg-gray-700' ?> flex items-center justify-center mb-2">
                <i class="fas fa-check text-2xl"></i>
            </div>
            <p class="text-xs text-center text-gray-400">Selesai</p>
        </div>
    </div>

    <!-- Timer (if pending) -->
    <?php if ($transaction['status'] == 'pending'): ?>
    <div class="bg-gray-800 rounded-2xl p-6 mb-6 text-center">
        <p class="text-gray-400 mb-4">Selesaikan pembayaran dalam</p>
        <div class="flex justify-center gap-4">
            <div class="bg-gray-900 rounded-xl p-4 min-w-[80px]">
                <div id="timer-hours" class="text-3xl font-bold text-red-500">00</div>
                <div class="text-xs text-gray-400 mt-1">Jam</div>
            </div>
            <div class="flex items-center text-3xl font-bold text-gray-600">:</div>
            <div class="bg-gray-900 rounded-xl p-4 min-w-[80px]">
                <div id="timer-minutes" class="text-3xl font-bold text-red-500">00</div>
                <div class="text-xs text-gray-400 mt-1">Menit</div>
            </div>
            <div class="flex items-center text-3xl font-bold text-gray-600">:</div>
            <div class="bg-gray-900 rounded-xl p-4 min-w-[80px]">
                <div id="timer-seconds" class="text-3xl font-bold text-red-500">00</div>
                <div class="text-xs text-gray-400 mt-1">Detik</div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Account Info -->
    <div class="bg-gray-800 rounded-2xl p-6 mb-6">
        <h3 class="text-xl font-bold mb-4">Informasi Akun</h3>
        <div class="space-y-3">
            <div class="flex justify-between py-3 border-b border-gray-700">
                <span class="text-gray-400">Game</span>
                <span class="font-semibold"><?= esc($transaction['game_name']) ?></span>
            </div>
            <div class="flex justify-between py-3 border-b border-gray-700">
                <span class="text-gray-400">ID</span>
                <span class="font-semibold"><?= esc($transaction['user_game_id']) ?></span>
            </div>
            <div class="flex justify-between py-3">
                <span class="text-gray-400">Server</span>
                <span class="font-semibold">87454</span>
            </div>
        </div>
    </div>

    <!-- Payment Info -->
    <?php if ($transaction['status'] == 'pending'): ?>
    <div class="bg-gray-800 rounded-2xl p-6 mb-6">
        <h3 class="text-xl font-bold mb-2">Metode Pembayaran</h3>
        <p class="text-gray-400 mb-6"><?= esc($transaction['payment_name']) ?></p>

        <!-- Invoice Number -->
        <div class="mb-6">
            <label class="text-gray-400 text-sm block mb-2">Nomor Invoice</label>
            <div class="flex items-center gap-3 bg-gray-900 p-4 rounded-xl">
                <span class="flex-1 font-mono text-lg"><?= esc($transaction['invoice_number']) ?></span>
                <button onclick="copyToClipboard('<?= $transaction['invoice_number'] ?>')" 
                        class="bg-indigo-500 hover:bg-indigo-600 px-4 py-2 rounded-lg transition">
                    <i class="fas fa-copy"></i>
                </button>
            </div>
        </div>

        <!-- VA Number or QR Code -->
        <?php if ($transaction['payment_type'] == 'va' && $transaction['va_number']): ?>
        <div class="mb-6">
            <label class="text-gray-400 text-sm block mb-2">Nomor Virtual Account</label>
            <div class="flex items-center gap-3 bg-gray-900 p-4 rounded-xl">
                <span class="flex-1 font-mono text-lg"><?= esc($transaction['va_number']) ?></span>
                <button onclick="copyToClipboard('<?= $transaction['va_number'] ?>')" 
                        class="bg-indigo-500 hover:bg-indigo-600 px-4 py-2 rounded-lg transition">
                    <i class="fas fa-copy"></i>
                </button>
            </div>
        </div>
        <?php endif; ?>

        <?php if (in_array($transaction['payment_type'], ['qris', 'ewallet'])): ?>
        <div class="text-center">
            <label class="text-gray-400 text-sm block mb-4">Scan QR Code untuk Pembayaran</label>
            
            <!-- Mock QR Code -->
            <div class="inline-block bg-white p-6 rounded-2xl mb-4">
                <div class="w-64 h-64 bg-gray-200 relative overflow-hidden">
                    <!-- QR Code Pattern (Mock) -->
                    <svg class="w-full h-full" viewBox="0 0 100 100">
                        <!-- Corner markers -->
                        <rect x="5" y="5" width="20" height="20" fill="black"/>
                        <rect x="8" y="8" width="14" height="14" fill="white"/>
                        <rect x="11" y="11" width="8" height="8" fill="black"/>
                        
                        <rect x="75" y="5" width="20" height="20" fill="black"/>
                        <rect x="78" y="8" width="14" height="14" fill="white"/>
                        <rect x="81" y="11" width="8" height="8" fill="black"/>
                        
                        <rect x="5" y="75" width="20" height="20" fill="black"/>
                        <rect x="8" y="78" width="14" height="14" fill="white"/>
                        <rect x="11" y="81" width="8" height="8" fill="black"/>
                        
                        <!-- Random pattern -->
                        <?php for($i = 0; $i < 200; $i++): ?>
                        <rect x="<?= rand(30, 70) ?>" y="<?= rand(30, 70) ?>" width="<?= rand(2, 4) ?>" height="<?= rand(2, 4) ?>" fill="black"/>
                        <?php endfor; ?>
                    </svg>
                </div>
                <div class="mt-3 text-gray-800 font-semibold text-sm">
                    Rp <?= number_format($transaction['total_payment'], 0, ',', '.') ?>
                </div>
            </div>

            <div class="text-gray-400 text-sm mb-4">
                Scan menggunakan aplikasi <?= esc($transaction['payment_name']) ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <!-- Payment Summary -->
    <div class="bg-gray-800 rounded-2xl p-6 mb-6">
        <h3 class="text-xl font-bold mb-4">Rincian Pembayaran</h3>
        
        <div class="space-y-3">
            <div class="flex justify-between text-gray-400">
                <span>Harga</span>
                <span>Rp <?= number_format($transaction['amount'], 0, ',', '.') ?></span>
            </div>

            <?php if ($transaction['discount'] > 0): ?>
            <div class="flex justify-between text-green-400">
                <span>Diskon</span>
                <span>- Rp <?= number_format($transaction['discount'], 0, ',', '.') ?></span>
            </div>
            <?php endif; ?>

            <?php if ($transaction['fee'] > 0): ?>
            <div class="flex justify-between text-gray-400">
                <span>Biaya Admin</span>
                <span>Rp <?= number_format($transaction['fee'], 0, ',', '.') ?></span>
            </div>
            <?php endif; ?>

            <div class="border-t border-gray-700 pt-3 mt-3">
                <div class="flex justify-between text-xl font-bold">
                    <span>Total Pembayaran</span>
                    <span class="text-green-400">Rp <?= number_format($transaction['total_payment'], 0, ',', '.') ?></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="text-center mb-8">
        <?php if ($transaction['status'] == 'pending'): ?>
        <div class="bg-indigo-500/20 border border-indigo-500 rounded-xl p-4 mb-6">
            <i class="fas fa-info-circle mr-2"></i>
            <span class="text-sm">
                Pembayaran akan dikonfirmasi otomatis. Silahkan tunggu atau hubungi CS jika ada kendala.
            </span>
        </div>
        <?php elseif ($transaction['status'] == 'success'): ?>
        <div class="bg-green-500/20 border border-green-500 rounded-xl p-4 mb-6">
            <i class="fas fa-check-circle mr-2"></i>
            <span class="text-sm">
                Produk telah berhasil dikirim ke akun Anda. Terima kasih!
            </span>
        </div>
        <?php endif; ?>

        <a href="<?= base_url('/') ?>" 
           class="inline-block bg-gray-700 hover:bg-gray-600 px-8 py-3 rounded-xl font-semibold transition">
            Kembali ke Beranda
        </a>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        alert('Berhasil disalin!');
    }).catch(() => {
        // Fallback
        const temp = document.createElement('textarea');
        temp.value = text;
        document.body.appendChild(temp);
        temp.select();
        document.execCommand('copy');
        document.body.removeChild(temp);
        alert('Berhasil disalin!');
    });
}

<?php if ($transaction['status'] == 'pending'): ?>
// Countdown Timer
const expiredAt = new Date('<?= $transaction['expired_at'] ?>').getTime();

function updateTimer() {
    const now = new Date().getTime();
    const distance = expiredAt - now;

    if (distance < 0) {
        document.getElementById('timer-hours').textContent = '00';
        document.getElementById('timer-minutes').textContent = '00';
        document.getElementById('timer-seconds').textContent = '00';
        setTimeout(() => location.reload(), 2000);
        return;
    }

    const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
    const seconds = Math.floor((distance % (1000 * 60)) / 1000);

    document.getElementById('timer-hours').textContent = String(hours).padStart(2, '0');
    document.getElementById('timer-minutes').textContent = String(minutes).padStart(2, '0');
    document.getElementById('timer-seconds').textContent = String(seconds).padStart(2, '0');
}

setInterval(updateTimer, 1000);
updateTimer();

// Auto refresh every 30 seconds
setInterval(() => {
    location.reload();
}, 30000);
<?php endif; ?>
</script>
<?= $this->endSection() ?>