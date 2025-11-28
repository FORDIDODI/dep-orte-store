<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container">
    <div class="status-container">
        <!-- Transaction Status -->
        <div class="status-header">
            <?php if ($transaction['status'] == 'success'): ?>
                <div class="status-icon success">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h2 class="status-success">Transaksi Berhasil</h2>
            <?php elseif ($transaction['status'] == 'processing'): ?>
                <div class="status-icon processing">
                    <i class="fas fa-clock"></i>
                </div>
                <h2 class="status-processing">Sedang Diproses</h2>
            <?php elseif ($transaction['status'] == 'expired'): ?>
                <div class="status-icon expired">
                    <i class="fas fa-times-circle"></i>
                </div>
                <h2 class="status-expired">Transaksi Kedaluwarsa</h2>
            <?php elseif ($transaction['status'] == 'failed'): ?>
                <div class="status-icon failed">
                    <i class="fas fa-exclamation-circle"></i>
                </div>
                <h2 class="status-failed">Transaksi Gagal</h2>
            <?php else: ?>
                <div class="status-icon pending">
                    <i class="fas fa-hourglass-half"></i>
                </div>
                <h2 class="status-pending">Menunggu Pembayaran</h2>
            <?php endif; ?>
        </div>

        <!-- Progress Steps -->
        <div class="progress-steps">
            <div class="progress-step <?= in_array($transaction['status'], ['pending', 'processing', 'success']) ? 'active' : '' ?>">
                <div class="step-circle">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <p>Transaksi dibuat</p>
            </div>

            <div class="progress-line <?= in_array($transaction['status'], ['processing', 'success']) ? 'active' : '' ?>"></div>

            <div class="progress-step <?= in_array($transaction['status'], ['processing', 'success']) ? 'active' : '' ?>">
                <div class="step-circle">
                    <i class="fas fa-credit-card"></i>
                </div>
                <p>Pembayaran diterima</p>
            </div>

            <div class="progress-line <?= $transaction['status'] == 'success' ? 'active' : '' ?>"></div>

            <div class="progress-step <?= $transaction['status'] == 'success' ? 'active' : '' ?>">
                <div class="step-circle">
                    <i class="fas fa-cog"></i>
                </div>
                <p>Sedang diproses</p>
            </div>

            <div class="progress-line <?= $transaction['status'] == 'success' ? 'active' : '' ?>"></div>

            <div class="progress-step <?= $transaction['status'] == 'success' ? 'active' : '' ?>">
                <div class="step-circle">
                    <i class="fas fa-check"></i>
                </div>
                <p>Transaksi selesai</p>
            </div>
        </div>

        <!-- Timer (if pending) -->
        <?php if ($transaction['status'] == 'pending'): ?>
        <div class="payment-timer">
            <p>Selesaikan pembayaran dalam</p>
            <div class="timer-display">
                <div class="timer-box">
                    <span id="timer-hours">00</span>
                    <small>Jam</small>
                </div>
                <span class="timer-separator">:</span>
                <div class="timer-box">
                    <span id="timer-minutes">00</span>
                    <small>Menit</small>
                </div>
                <span class="timer-separator">:</span>
                <div class="timer-box">
                    <span id="timer-seconds">00</span>
                    <small>Detik</small>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Account Information -->
        <div class="info-card">
            <h3>Informasi Akun</h3>
            <div class="info-row">
                <div class="info-label">Username</div>
                <div class="info-value"><?= esc($transaction['game_name']) ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">ID</div>
                <div class="info-value"><?= esc($transaction['user_game_id']) ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">Server</div>
                <div class="info-value">Global</div>
            </div>
        </div>

        <!-- Payment Information -->
        <?php if ($transaction['status'] == 'pending'): ?>
        <div class="payment-info">
            <h3>Metode Pembayaran<br><small>QRIS (All Payment)</small></h3>

            <div class="payment-details">
                <p><strong>Nomor Invoice:</strong></p>
                <div class="invoice-number">
                    <span><?= esc($transaction['invoice_number']) ?></span>
                    <button onclick="copyInvoice()" class="btn-copy"><i class="fas fa-copy"></i></button>
                </div>

                <?php if ($transaction['payment_type'] == 'va' && $transaction['va_number']): ?>
                    <p><strong>Nomor Virtual Account:</strong></p>
                    <div class="va-number">
                        <span><?= esc($transaction['va_number']) ?></span>
                        <button onclick="copyVA()" class="btn-copy"><i class="fas fa-copy"></i></button>
                    </div>
                <?php endif; ?>

                <?php if (in_array($transaction['payment_type'], ['qr', 'qris']) && $transaction['qr_code']): ?>
                    <div class="qr-container">
                        <img src="<?= esc($transaction['qr_code']) ?>" alt="QR Code" class="qr-image">
                        <button class="btn-download-qr">Unduh Kode QR</button>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Payment Summary -->
        <div class="payment-summary">
            <h3>Rincian Pembayaran</h3>
            
            <div class="summary-row">
                <span>Harga</span>
                <span>Rp <?= number_format($transaction['amount'], 0, ',', '.') ?></span>
            </div>

            <?php if ($transaction['discount'] > 0): ?>
            <div class="summary-row">
                <span>Diskon</span>
                <span class="text-success">- Rp <?= number_format($transaction['discount'], 0, ',', '.') ?></span>
            </div>
            <?php endif; ?>

            <?php if ($transaction['fee'] > 0): ?>
            <div class="summary-row">
                <span>Biaya</span>
                <span>Rp <?= number_format($transaction['fee'], 0, ',', '.') ?></span>
            </div>
            <?php endif; ?>

            <div class="summary-divider"></div>

            <div class="summary-row summary-total">
                <span>Total Pembayaran</span>
                <span>Rp <?= number_format($transaction['total_payment'], 0, ',', '.') ?></span>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="action-buttons">
            <?php if ($transaction['status'] == 'pending'): ?>
                <p class="payment-note">
                    Pembayaran akan dikonfirmasi secara otomatis setelah pembayaran diterima. 
                    Harap tunggu atau hubungi customer service jika ada kendala.
                </p>
            <?php elseif ($transaction['status'] == 'success'): ?>
                <p class="success-note">
                    <i class="fas fa-info-circle"></i>
                    Produk telah berhasil dikirim ke akun Anda. Terima kasih telah berbelanja!
                </p>
            <?php endif; ?>

            <a href="<?= base_url('/') ?>" class="btn-back">Kembali ke Beranda</a>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
// Copy Invoice Number
function copyInvoice() {
    const invoice = '<?= $transaction['invoice_number'] ?>';
    navigator.clipboard.writeText(invoice).then(() => {
        alert('Nomor invoice berhasil disalin!');
    });
}

// Copy VA Number
function copyVA() {
    const va = '<?= $transaction['va_number'] ?? '' ?>';
    navigator.clipboard.writeText(va).then(() => {
        alert('Nomor VA berhasil disalin!');
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
        clearInterval(timerInterval);
        location.reload();
        return;
    }

    const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
    const seconds = Math.floor((distance % (1000 * 60)) / 1000);

    document.getElementById('timer-hours').textContent = String(hours).padStart(2, '0');
    document.getElementById('timer-minutes').textContent = String(minutes).padStart(2, '0');
    document.getElementById('timer-seconds').textContent = String(seconds).padStart(2, '0');
}

const timerInterval = setInterval(updateTimer, 1000);
updateTimer();

// Auto refresh status every 30 seconds
setInterval(() => {
    location.reload();
}, 30000);
<?php endif; ?>
</script>
<?= $this->endSection() ?>