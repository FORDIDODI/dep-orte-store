<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container mx-auto px-4">
    <!-- Flash Sale Section -->
    <?php if (!empty($flash_sales)): ?>
    <section class="bg-gray-800 rounded-2xl p-6 mb-8 border border-red-500/30">
        <div class="flex justify-between items-center mb-6">
            <div class="flex items-center gap-3">
                <i class="fas fa-bolt text-yellow-400 text-2xl"></i>
                <span class="text-2xl font-bold">FLASH SALE</span>
            </div>
            <div class="flex items-center gap-2 text-red-500 text-2xl font-mono font-bold">
                <span id="flash-hours">10</span>:
                <span id="flash-minutes">15</span>:
                <span id="flash-seconds">30</span>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <?php foreach ($flash_sales as $flash): ?>
            <div class="bg-gray-900 rounded-xl p-5 relative card-hover cursor-pointer">
                <div class="absolute top-3 right-3 bg-red-500 text-white px-3 py-1 rounded-full text-xs font-bold">
                    SALE
                </div>
                <div class="text-center">
                    <h4 class="font-semibold text-lg mb-3"><?= esc($flash['name']) ?></h4>
                    <div class="flex items-center justify-center gap-3 mb-4">
                        <span class="text-gray-400 line-through">
                            Rp <?= number_format($flash['price'], 0, ',', '.') ?>
                        </span>
                        <span class="text-green-400 text-xl font-bold">
                            Rp <?= number_format($flash['discount_price'], 0, ',', '.') ?>
                        </span>
                    </div>
                    <button class="gradient-primary w-full py-2 rounded-lg font-semibold hover:opacity-90 transition">
                        BELI SEKARANG
                    </button>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>

    <!-- Trending Section -->
    <section class="mb-12">
        <div class="flex items-center gap-3 mb-6">
            <i class="fas fa-fire text-orange-500 text-2xl"></i>
            <h2 class="text-3xl font-bold">TRENDING</h2>
        </div>
        <p class="text-gray-400 mb-6">Berikut adalah beberapa produk yang paling popular saat ini.</p>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <?php foreach (array_slice($popular_games, 0, 4) as $game): ?>
            <a href="<?= base_url('game/' . $game['slug']) ?>" 
               class="relative h-48 rounded-xl overflow-hidden card-hover group">
                <img src="<?= base_url('assets/images/games/' . $game['image']) ?>" 
                     alt="<?= esc($game['name']) ?>" 
                     class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent flex flex-col justify-end p-4">
                    <h3 class="font-bold text-lg"><?= esc($game['name']) ?></h3>
                    <p class="text-gray-300 text-sm"><?= esc($game['category']) ?></p>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Top Up Section -->
    <section>
        <h2 class="text-3xl font-bold mb-6">TOP UP</h2>

        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4">
            <?php foreach ($all_games as $game): ?>
            <a href="<?= base_url('game/' . $game['slug']) ?>" 
               class="bg-gray-800 rounded-xl overflow-hidden card-hover group">
                <img src="<?= base_url('assets/images/games/' . $game['image']) ?>" 
                     alt="<?= esc($game['name']) ?>" 
                     class="w-full h-40 object-cover">
                <div class="p-4">
                    <h4 class="font-semibold group-hover:text-indigo-400 transition">
                        <?= esc($game['name']) ?>
                    </h4>
                    <p class="text-gray-400 text-sm"><?= esc($game['category']) ?></p>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    </section>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
// Flash Sale Countdown
let hours = 10, minutes = 15, seconds = 30;

setInterval(() => {
    seconds--;
    if (seconds < 0) {
        seconds = 59;
        minutes--;
    }
    if (minutes < 0) {
        minutes = 59;
        hours--;
    }
    if (hours < 0) {
        hours = 10;
        minutes = 15;
        seconds = 30;
    }

    document.getElementById('flash-hours').textContent = String(hours).padStart(2, '0');
    document.getElementById('flash-minutes').textContent = String(minutes).padStart(2, '0');
    document.getElementById('flash-seconds').textContent = String(seconds).padStart(2, '0');
}, 1000);
</script>
<?= $this->endSection() ?>