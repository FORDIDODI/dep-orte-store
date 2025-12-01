<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container mx-auto px-4">
    <!-- Game Banner -->
    <div class="relative h-64 md:h-80 rounded-2xl overflow-hidden mb-8">
        <img src="<?= base_url('assets/images/games/' . $game['image']) ?>" 
             alt="<?= esc($game['name']) ?>" 
             class="w-full h-full object-cover bg-gray-700"
             loading="eager"
             onerror="this.onerror=null; this.src='data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'800\' height=\'400\'%3E%3Crect fill=\'%234a5568\' width=\'800\' height=\'400\'/%3E%3Ctext fill=\'%23ffffff\' font-family=\'Arial\' font-size=\'32\' x=\'50%25\' y=\'50%25\' text-anchor=\'middle\' dominant-baseline=\'middle\'%3E<?= urlencode($game['name']) ?>%3C/text%3E%3C/svg%3E';">
        <div class="absolute inset-0 bg-gradient-to-t from-black via-black/60 to-transparent flex items-end">
            <div class="p-6 md:p-8 w-full">
                <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold mb-2"><?= esc($game['name']) ?></h1>
                <p class="text-gray-300 text-lg"><?= esc($game['category']) ?></p>
                <?php if (!empty($game['description'])): ?>
                <p class="text-gray-400 mt-2 text-sm md:text-base"><?= esc($game['description']) ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Order Form -->
    <form action="<?= base_url('order/create') ?>" method="POST" id="orderForm" class="max-w-4xl mx-auto">
        <?= csrf_field() ?>
        
        <!-- Step 1: User ID -->
        <div class="bg-gray-800 rounded-2xl p-6 mb-6">
            <div class="flex items-start gap-4 mb-6">
                <div class="w-12 h-12 gradient-primary rounded-full flex items-center justify-center flex-shrink-0 text-xl font-bold">
                    1
                </div>
                <div class="flex-1">
                    <h3 class="text-xl font-bold mb-2">Masukkan User ID</h3>
                    <p class="text-gray-400 text-sm">
                        Untuk menemukan User ID Anda, klik nama karakter Anda di pojok kiri atas layar.
                    </p>
                </div>
            </div>

            <input type="text" 
                   name="user_game_id" 
                   id="userGameId" 
                   class="w-full bg-gray-900 border-2 border-gray-700 rounded-xl px-4 py-3 focus:border-indigo-500 focus:outline-none transition" 
                   placeholder="Masukkan User ID" 
                   required>
        </div>

        <!-- Step 2: Select Product -->
        <div class="bg-gray-800 rounded-2xl p-6 mb-6">
            <div class="flex items-start gap-4 mb-6">
                <div class="w-12 h-12 gradient-primary rounded-full flex items-center justify-center flex-shrink-0 text-xl font-bold">
                    2
                </div>
                <div class="flex-1">
                    <h3 class="text-xl font-bold">Pilih Nominal</h3>
                </div>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                <?php foreach ($products as $product): ?>
                <label class="cursor-pointer">
                    <input type="radio" 
                           name="product_id" 
                           value="<?= $product['id'] ?>" 
                           data-price="<?= $product['discount_price'] ?? $product['price'] ?>"
                           class="peer hidden" 
                           required>
                    <div class="bg-gray-900 border-2 border-gray-700 peer-checked:border-indigo-500 peer-checked:bg-indigo-500/10 rounded-xl p-4 transition hover:border-gray-600 relative">
                        <?php if ($product['discount_price']): ?>
                        <div class="absolute top-2 right-2 bg-red-500 text-white px-2 py-1 rounded text-xs font-bold">
                            DISKON
                        </div>
                        <?php endif; ?>
                        
                        <h4 class="font-semibold mb-3 text-center"><?= esc($product['name']) ?></h4>
                        
                        <?php if ($product['discount_price']): ?>
                        <div class="text-center">
                            <div class="text-gray-400 line-through text-sm">
                                Rp <?= number_format($product['price'], 0, ',', '.') ?>
                            </div>
                            <div class="text-green-400 font-bold text-lg">
                                Rp <?= number_format($product['discount_price'], 0, ',', '.') ?>
                            </div>
                        </div>
                        <?php else: ?>
                        <div class="text-green-400 font-bold text-center text-lg">
                            Rp <?= number_format($product['price'], 0, ',', '.') ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </label>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Step 3: Promo Code -->
        <div class="bg-gray-800 rounded-2xl p-6 mb-6">
            <div class="flex items-start gap-4 mb-6">
                <div class="w-12 h-12 gradient-primary rounded-full flex items-center justify-center flex-shrink-0 text-xl font-bold">
                    3
                </div>
                <div class="flex-1">
                    <h3 class="text-xl font-bold">Kode Promo (Opsional)</h3>
                </div>
            </div>

            <div class="flex gap-3">
                <input type="text" 
                       name="promo_code" 
                       id="promoCode" 
                       class="flex-1 bg-gray-900 border-2 border-gray-700 rounded-xl px-4 py-3 focus:border-indigo-500 focus:outline-none transition" 
                       placeholder="Masukkan kode promo">
                <button type="button" 
                        id="checkPromo" 
                        class="bg-yellow-500 hover:bg-yellow-600 px-6 py-3 rounded-xl font-semibold text-black transition">
                    Cek
                </button>
            </div>
            <div id="promoMessage" class="mt-3 hidden"></div>
        </div>

        <!-- Step 4: Payment Method -->
        <div class="bg-gray-800 rounded-2xl p-6 mb-6">
            <div class="flex items-start gap-4 mb-6">
                <div class="w-12 h-12 gradient-primary rounded-full flex items-center justify-center flex-shrink-0 text-xl font-bold">
                    4
                </div>
                <div class="flex-1">
                    <h3 class="text-xl font-bold">Pilih Pembayaran</h3>
                </div>
            </div>

            <!-- Payment Tabs -->
            <div class="flex gap-2 mb-6 overflow-x-auto pb-2">
                <button type="button" class="payment-tab active px-6 py-2 bg-indigo-500 rounded-lg font-semibold whitespace-nowrap" data-type="all">
                    Semua
                </button>
                <button type="button" class="payment-tab px-6 py-2 bg-gray-700 rounded-lg font-semibold whitespace-nowrap hover:bg-gray-600 transition" data-type="va">
                    Virtual Account
                </button>
                <button type="button" class="payment-tab px-6 py-2 bg-gray-700 rounded-lg font-semibold whitespace-nowrap hover:bg-gray-600 transition" data-type="qris">
                    QRIS
                </button>
                <button type="button" class="payment-tab px-6 py-2 bg-gray-700 rounded-lg font-semibold whitespace-nowrap hover:bg-gray-600 transition" data-type="ewallet">
                    E-Wallet
                </button>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                <?php foreach ($payment_methods as $method): ?>
                <label class="payment-card cursor-pointer" data-payment-type="<?= $method['type'] ?>">
                    <input type="radio" 
                           name="payment_method_id" 
                           value="<?= $method['id'] ?>" 
                           data-fee="<?= $method['fee'] ?>"
                           class="peer hidden" 
                           required>
                    <div class="bg-gray-900 border-2 border-gray-700 peer-checked:border-indigo-500 peer-checked:bg-indigo-500/10 rounded-xl p-4 transition hover:border-gray-600 flex flex-col items-center justify-center min-h-[100px]">
                        <div class="text-3xl mb-2">
                            <?php if ($method['type'] == 'va'): ?>
                                <i class="fas fa-building-columns"></i>
                            <?php elseif ($method['type'] == 'qris'): ?>
                                <i class="fas fa-qrcode"></i>
                            <?php else: ?>
                                <i class="fas fa-wallet"></i>
                            <?php endif; ?>
                        </div>
                        <span class="text-sm text-center"><?= esc($method['name']) ?></span>
                    </div>
                </label>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="bg-gray-800 rounded-2xl p-6 sticky top-20">
            <h3 class="text-xl font-bold mb-6">Rincian Pembayaran</h3>
            
            <div class="space-y-3 mb-6">
                <div class="flex justify-between text-gray-400">
                    <span>Harga</span>
                    <span id="summaryPrice">Rp 0</span>
                </div>

                <div class="flex justify-between text-green-400 hidden" id="discountRow">
                    <span>Diskon</span>
                    <span id="summaryDiscount">- Rp 0</span>
                </div>

                <div class="flex justify-between text-gray-400 hidden" id="feeRow">
                    <span>Biaya Admin</span>
                    <span id="summaryFee">Rp 0</span>
                </div>
            </div>

            <div class="border-t border-gray-700 pt-4 mb-6">
                <div class="flex justify-between text-xl font-bold">
                    <span>Total Pembayaran</span>
                    <span id="summaryTotal" class="text-green-400">Rp 0</span>
                </div>
            </div>

            <button type="submit" 
                    id="btnSubmit" 
                    class="w-full gradient-primary py-4 rounded-xl font-bold text-lg hover:opacity-90 transition disabled:opacity-50 disabled:cursor-not-allowed" 
                    disabled>
                Beli Sekarang
            </button>
        </div>
    </form>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
const form = document.getElementById('orderForm');
const productRadios = document.querySelectorAll('input[name="product_id"]');
const paymentRadios = document.querySelectorAll('input[name="payment_method_id"]');
const userGameId = document.getElementById('userGameId');
const promoCode = document.getElementById('promoCode');
const checkPromoBtn = document.getElementById('checkPromo');
const btnSubmit = document.getElementById('btnSubmit');

let selectedPrice = 0;
let discount = 0;
let fee = 0;

// Payment Tabs
document.querySelectorAll('.payment-tab').forEach(tab => {
    tab.addEventListener('click', function() {
        document.querySelectorAll('.payment-tab').forEach(t => {
            t.classList.remove('active', 'bg-indigo-500');
            t.classList.add('bg-gray-700');
        });
        this.classList.add('active', 'bg-indigo-500');
        this.classList.remove('bg-gray-700');
        
        const type = this.dataset.type;
        document.querySelectorAll('.payment-card').forEach(card => {
            if (type === 'all' || card.dataset.paymentType === type) {
                card.classList.remove('hidden');
            } else {
                card.classList.add('hidden');
            }
        });
    });
});

// Product Selection
productRadios.forEach(radio => {
    radio.addEventListener('change', function() {
        selectedPrice = parseFloat(this.dataset.price);
        updateSummary();
    });
});

// Payment Method Selection
paymentRadios.forEach(radio => {
    radio.addEventListener('change', function() {
        fee = parseFloat(this.dataset.fee);
        document.getElementById('feeRow').classList.remove('hidden');
        updateSummary();
    });
});

// Check Promo
checkPromoBtn.addEventListener('click', function() {
    const code = promoCode.value.trim();
    
    if (!code) {
        showPromoMessage('Masukkan kode promo', 'error');
        return;
    }

    if (selectedPrice === 0) {
        showPromoMessage('Pilih produk terlebih dahulu', 'error');
        return;
    }

    fetch('<?= base_url('order/check-promo') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: new URLSearchParams({
            code: code,
            amount: selectedPrice,
            '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            discount = data.discount;
            document.getElementById('discountRow').classList.remove('hidden');
            updateSummary();
            showPromoMessage(data.message, 'success');
        } else {
            discount = 0;
            document.getElementById('discountRow').classList.add('hidden');
            updateSummary();
            showPromoMessage(data.message, 'error');
        }
    });
});

function showPromoMessage(message, type) {
    const messageEl = document.getElementById('promoMessage');
    messageEl.textContent = message;
    messageEl.className = `mt-3 p-3 rounded-lg ${type === 'success' ? 'bg-green-500/20 border border-green-500 text-green-300' : 'bg-red-500/20 border border-red-500 text-red-300'}`;
    messageEl.classList.remove('hidden');

    setTimeout(() => {
        messageEl.classList.add('hidden');
    }, 3000);
}

function updateSummary() {
    const total = selectedPrice - discount + fee;

    document.getElementById('summaryPrice').textContent = 'Rp ' + formatNumber(selectedPrice);
    document.getElementById('summaryDiscount').textContent = '- Rp ' + formatNumber(discount);
    document.getElementById('summaryFee').textContent = 'Rp ' + formatNumber(fee);
    document.getElementById('summaryTotal').textContent = 'Rp ' + formatNumber(total);

    validateForm();
}

function validateForm() {
    const productSelected = document.querySelector('input[name="product_id"]:checked');
    const paymentSelected = document.querySelector('input[name="payment_method_id"]:checked');
    const userIdFilled = userGameId.value.trim().length >= 3;

    btnSubmit.disabled = !(productSelected && paymentSelected && userIdFilled);
}

function formatNumber(num) {
    return Math.round(num).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

userGameId.addEventListener('input', validateForm);
</script>
<?= $this->endSection() ?>