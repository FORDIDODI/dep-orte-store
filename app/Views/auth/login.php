<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container mx-auto px-4 flex items-center justify-center min-h-[80vh]">
    <div class="w-full max-w-md">
        <div class="bg-gray-800 rounded-2xl p-8 shadow-2xl">
            <h2 class="text-3xl font-bold text-center mb-8">Masuk ke Akun</h2>

            <?php if (session()->getFlashdata('errors')): ?>
            <div class="bg-red-500/20 border border-red-500 rounded-xl p-4 mb-6">
                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                <p class="text-red-300 text-sm"><?= esc($error) ?></p>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>

            <form action="<?= base_url('auth/login') ?>" method="POST">
                <?= csrf_field() ?>

                <div class="mb-6">
                    <label class="block text-gray-300 mb-2">Email</label>
                    <input type="email" 
                           name="email" 
                           value="<?= old('email') ?>"
                           class="w-full bg-gray-900 border-2 border-gray-700 rounded-xl px-4 py-3 focus:border-indigo-500 focus:outline-none transition" 
                           placeholder="nama@email.com" 
                           required>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-300 mb-2">Password</label>
                    <input type="password" 
                           name="password" 
                           class="w-full bg-gray-900 border-2 border-gray-700 rounded-xl px-4 py-3 focus:border-indigo-500 focus:outline-none transition" 
                           placeholder="••••••••" 
                           required>
                </div>

                <button type="submit" 
                        class="w-full gradient-primary py-3 rounded-xl font-semibold hover:opacity-90 transition">
                    Masuk
                </button>

                <p class="text-center text-gray-400 mt-6">
                    Belum punya akun? 
                    <a href="<?= base_url('auth/register') ?>" class="text-indigo-400 hover:text-indigo-300 font-semibold">
                        Daftar Sekarang
                    </a>
                </p>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>