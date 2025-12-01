<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container mx-auto px-4">
  <div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
      <div class="flex items-center justify-between mb-4">
        <div>
          <h1 class="text-3xl font-bold mb-2">Riwayat Transaksi</h1>
          <p class="text-gray-400">Semua transaksi yang pernah Anda lakukan</p>
        </div>
        <a href="<?= base_url('dashboard') ?>" class="text-indigo-400 hover:text-indigo-300 flex items-center gap-2">
          <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
        </a>
      </div>
    </div>

    <!-- Transactions Table -->
    <div class="bg-gray-800 rounded-2xl p-6">
      <?php if (empty($transactions)): ?>
        <div class="text-center py-16 text-gray-400">
          <i class="fas fa-inbox text-6xl mb-4"></i>
          <p class="text-xl mb-2">Belum ada transaksi</p>
          <p class="text-sm mb-6">Mulai top up game favorit Anda sekarang!</p>
          <a href="<?= base_url('/') ?>" class="inline-block bg-indigo-600 hover:bg-indigo-700 px-6 py-3 rounded-xl font-semibold transition">
            <i class="fas fa-gamepad mr-2"></i> Mulai Top Up
          </a>
        </div>
      <?php else: ?>
        <div class="overflow-x-auto">
          <table class="w-full">
            <thead class="border-b border-gray-700">
              <tr>
                <th class="text-left py-3 px-4 text-gray-400 font-semibold">Invoice</th>
                <th class="text-left py-3 px-4 text-gray-400 font-semibold">Game</th>
                <th class="text-left py-3 px-4 text-gray-400 font-semibold">Produk</th>
                <th class="text-left py-3 px-4 text-gray-400 font-semibold">User ID</th>
                <th class="text-left py-3 px-4 text-gray-400 font-semibold">Total</th>
                <th class="text-left py-3 px-4 text-gray-400 font-semibold">Status</th>
                <th class="text-left py-3 px-4 text-gray-400 font-semibold">Tanggal</th>
                <th class="text-left py-3 px-4 text-gray-400 font-semibold">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($transactions as $trx): ?>
                <tr class="border-b border-gray-700 hover:bg-gray-700/30 transition">
                  <td class="py-4 px-4">
                    <a href="<?= base_url('order/status/' . $trx['invoice_number']) ?>"
                      class="text-indigo-400 hover:text-indigo-300 font-mono text-sm font-semibold">
                      <?= esc($trx['invoice_number']) ?>
                    </a>
                  </td>
                  <td class="py-4 px-4">
                    <div class="flex items-center gap-2">
                      <i class="fas fa-gamepad text-gray-400"></i>
                      <span><?= esc($trx['game_name']) ?></span>
                    </div>
                  </td>
                  <td class="py-4 px-4 text-sm"><?= esc($trx['product_name']) ?></td>
                  <td class="py-4 px-4">
                    <span class="font-mono text-sm bg-gray-700 px-2 py-1 rounded"><?= esc($trx['user_game_id']) ?></span>
                  </td>
                  <td class="py-4 px-4">
                    <span class="font-semibold text-green-400">Rp <?= number_format($trx['total_payment'], 0, ',', '.') ?></span>
                  </td>
                  <td class="py-4 px-4">
                    <?php
                    $statusColors = [
                      'success' => 'bg-green-500/20 text-green-400 border-green-500/30',
                      'pending' => 'bg-yellow-500/20 text-yellow-400 border-yellow-500/30',
                      'processing' => 'bg-blue-500/20 text-blue-400 border-blue-500/30',
                      'failed' => 'bg-red-500/20 text-red-400 border-red-500/30',
                      'expired' => 'bg-gray-500/20 text-gray-400 border-gray-500/30'
                    ];
                    $statusIcons = [
                      'success' => 'fa-check-circle',
                      'pending' => 'fa-clock',
                      'processing' => 'fa-spinner',
                      'failed' => 'fa-times-circle',
                      'expired' => 'fa-exclamation-circle'
                    ];
                    $status = $trx['status'] ?? 'pending';
                    ?>
                    <span class="<?= $statusColors[$status] ?? $statusColors['pending'] ?> border px-3 py-1 rounded-full text-xs font-semibold inline-flex items-center gap-1">
                      <i class="fas <?= $statusIcons[$status] ?? $statusIcons['pending'] ?>"></i>
                      <?= ucfirst($status) ?>
                    </span>
                  </td>
                  <td class="py-4 px-4 text-sm text-gray-400">
                    <div><?= date('d M Y', strtotime($trx['created_at'])) ?></div>
                    <div class="text-xs text-gray-500"><?= date('H:i', strtotime($trx['created_at'])) ?></div>
                  </td>
                  <td class="py-4 px-4">
                    <a href="<?= base_url('order/status/' . $trx['invoice_number']) ?>"
                      class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 px-3 py-1.5 rounded-lg text-sm font-semibold transition">
                      <i class="fas fa-eye"></i>
                      Detail
                    </a>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>

        <!-- Summary -->
        <div class="mt-6 pt-6 border-t border-gray-700">
          <div class="flex items-center justify-between text-sm text-gray-400">
            <span>Total: <strong class="text-white"><?= count($transactions) ?></strong> transaksi</span>
            <span>Ditampilkan: <strong class="text-white"><?= count($transactions) ?></strong> dari <?= count($transactions) ?> transaksi</span>
          </div>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>

<?= $this->endSection() ?>