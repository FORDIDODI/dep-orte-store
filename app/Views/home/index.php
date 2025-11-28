<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container mx-auto px-4">
    <!-- Hero Slideshow -->
    <section class="mb-8">
        <div class="relative rounded-2xl overflow-hidden h-64 md:h-80 bg-gray-800">
            <!-- Slideshow Container -->
            <div class="slideshow-container h-full">
                <!-- Slide 1 -->
                <div class="slide fade active">
                    <div class="absolute inset-0 bg-gradient-to-r from-blue-600 to-purple-600 flex items-center justify-center">
                        <div class="text-center px-6">
                            <h2 class="text-4xl md:text-6xl font-bold mb-4">🎮 Welcome to BayarStore</h2>
                            <p class="text-xl md:text-2xl mb-6">Top Up Game Murah & Cepat!</p>
                            <a href="#games" class="inline-block bg-white text-purple-600 px-8 py-3 rounded-xl font-bold hover:bg-gray-100 transition">
                                Mulai Top Up
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Slide 2 -->
                <div class="slide fade">
                    <div class="absolute inset-0 bg-gradient-to-r from-pink-600 to-red-600 flex items-center justify-center">
                        <div class="text-center px-6">
                            <h2 class="text-4xl md:text-6xl font-bold mb-4">🔥 FLASH SALE</h2>
                            <p class="text-xl md:text-2xl mb-6">Diskon hingga 30%!</p>
                            <a href="#flash-sale" class="inline-block bg-white text-red-600 px-8 py-3 rounded-xl font-bold hover:bg-gray-100 transition">
                                Lihat Promo
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Slide 3 -->
                <div class="slide fade">
                    <div class="absolute inset-0 bg-gradient-to-r from-green-600 to-teal-600 flex items-center justify-center">
                        <div class="text-center px-6">
                            <h2 class="text-4xl md:text-6xl font-bold mb-4">⚡ Proses Cepat</h2>
                            <p class="text-xl md:text-2xl mb-6">Instant delivery dalam hitungan menit!</p>
                            <a href="<?= base_url('/') ?>" class="inline-block bg-white text-green-600 px-8 py-3 rounded-xl font-bold hover:bg-gray-100 transition">
                                Order Sekarang
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Navigation Dots -->
            <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex gap-2">
                <button onclick="currentSlide(1)" class="dot w-3 h-3 bg-white/50 rounded-full hover:bg-white transition"></button>
                <button onclick="currentSlide(2)" class="dot w-3 h-3 bg-white/50 rounded-full hover:bg-white transition"></button>
                <button onclick="currentSlide(3)" class="dot w-3 h-3 bg-white/50 rounded-full hover:bg-white transition"></button>
            </div>

            <!-- Navigation Arrows -->
            <button onclick="changeSlide(-1)" class="absolute left-4 top-1/2 transform -translate-y-1/2 bg-black/30 hover:bg-black/50 text-white w-10 h-10 rounded-full transition">
                <i class="fas fa-chevron-left"></i>
            </button>
            <button onclick="changeSlide(1)" class="absolute right-4 top-1/2 transform -translate-y-1/2 bg-black/30 hover:bg-black/50 text-white w-10 h-10 rounded-full transition">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>
    </section>

    <!-- Flash Sale Section -->
    <?php if (!empty($flash_sales)): ?>
    <section id="flash-sale" class="bg-gray-800 rounded-2xl p-6 mb-8 border border-red-500/30">
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
                     class="w-full h-full object-cover"
                     onerror="this.src='https://via.placeholder.com/300x200/4a5568/ffffff?text=<?= urlencode($game['name']) ?>'">
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent flex flex-col justify-end p-4">
                    <h3 class="font-bold text-lg"><?= esc($game['name']) ?></h3>
                    <p class="text-gray-300 text-sm"><?= esc($game['category']) ?></p>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Top Up Section -->
    <section id="games">
        <h2 class="text-3xl font-bold mb-6">TOP UP</h2>

        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4">
            <?php foreach ($all_games as $game): ?>
            <a href="<?= base_url('game/' . $game['slug']) ?>" 
               class="bg-gray-800 rounded-xl overflow-hidden card-hover group">
                <img src="<?= base_url('assets/images/games/' . $game['image']) ?>" 
                     alt="<?= esc($game['name']) ?>" 
                     class="w-full h-40 object-cover"
                     onerror="this.src='https://via.placeholder.com/300x200/4a5568/ffffff?text=<?= urlencode($game['name']) ?>'">
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
// Slideshow functionality
let slideIndex = 1;
showSlide(slideIndex);

// Auto slideshow
setInterval(() => {
    changeSlide(1);
}, 5000);

function changeSlide(n) {
    showSlide(slideIndex += n);
}

function currentSlide(n) {
    showSlide(slideIndex = n);
}

function showSlide(n) {
    const slides = document.querySelectorAll('.slide');
    const dots = document.querySelectorAll('.dot');
    
    if (n > slides.length) slideIndex = 1;
    if (n < 1) slideIndex = slides.length;
    
    slides.forEach(slide => {
        slide.classList.remove('active');
        slide.style.display = 'none';
    });
    
    dots.forEach(dot => dot.classList.remove('bg-white'));
    
    slides[slideIndex - 1].style.display = 'block';
    setTimeout(() => {
        slides[slideIndex - 1].classList.add('active');
    }, 10);
    
    if (dots[slideIndex - 1]) {
        dots[slideIndex - 1].classList.add('bg-white');
    }
}

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

    const hoursEl = document.getElementById('flash-hours');
    const minutesEl = document.getElementById('flash-minutes');
    const secondsEl = document.getElementById('flash-seconds');
    
    if (hoursEl) hoursEl.textContent = String(hours).padStart(2, '0');
    if (minutesEl) minutesEl.textContent = String(minutes).padStart(2, '0');
    if (secondsEl) secondsEl.textContent = String(seconds).padStart(2, '0');
}, 1000);
</script>

<style>
.slide {
    display: none;
    position: absolute;
    width: 100%;
    height: 100%;
    opacity: 0;
    transition: opacity 0.5s ease-in-out;
}

.slide.active {
    display: block;
    opacity: 1;
}

.fade {
    animation-name: fade;
    animation-duration: 0.5s;
}

@keyframes fade {
    from { opacity: 0.4 }
    to { opacity: 1 }
}

.card-hover {
    transition: transform 0.3s ease;
}

.card-hover:hover {
    transform: translateY(-5px);
}
</style>
<?= $this->endSection() ?>
