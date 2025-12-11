<?php

namespace App\Models;

use CodeIgniter\Model;

class PromoCodeModel extends Model
{
    protected $table = 'promo_codes';
    protected $primaryKey = 'id';
    protected $allowedFields = ['code', 'type', 'value', 'min_transaction', 'max_discount', 'usage_limit', 'used_count', 'valid_from', 'valid_until', 'is_active'];
    protected $useTimestamps = false;

    /**
     * Validasi kode promo dengan pengecekan produk dan limit per user
     * 
     * @param string $code Kode promo
     * @param float $amount Jumlah transaksi
     * @param int|null $productId ID produk (wajib jika promo terikat ke produk)
     * @param int|null $userId ID user (null untuk guest)
     * @return array
     */
    public function validateCode($code, $amount, $productId = null, $userId = null)
    {
        $promo = $this->where('code', $code)
                      ->where('is_active', true)
                      ->first();

        if (!$promo) {
            return ['valid' => false, 'message' => 'Kode promo tidak ditemukan'];
        }

        // Check apakah promo berlaku untuk produk ini (jika tabel promo_products ada)
        if ($productId !== null) {
            try {
                $db = \Config\Database::connect();
                $promoProduct = $db->table('promo_products')
                                  ->where('promo_code_id', $promo['id'])
                                  ->where('product_id', $productId)
                                  ->get()
                                  ->getRowArray();
                
                // Jika ada produk yang terikat di promo_products, maka promo hanya berlaku untuk produk tersebut
                $totalProducts = $db->table('promo_products')
                                   ->where('promo_code_id', $promo['id'])
                                   ->countAllResults();
                
                if ($totalProducts > 0 && !$promoProduct) {
                    return ['valid' => false, 'message' => 'Kode promo tidak berlaku untuk produk ini'];
                }
            } catch (\Exception $e) {
                // Jika tabel promo_products belum ada, skip pengecekan produk spesifik
                log_message('debug', 'Promo products table not found, skipping product validation');
            }
        }

        // Check usage limit global
        if ($promo['usage_limit'] && $promo['used_count'] >= $promo['usage_limit']) {
            return ['valid' => false, 'message' => 'Kode promo sudah mencapai batas penggunaan'];
        }

        // Check limit per user/akun (jika kolom ada)
        if (isset($promo['user_limit_per_account']) && $promo['user_limit_per_account'] !== null) {
            // Cek apakah tabel promo_usage ada
            try {
                $db = \Config\Database::connect();
                $userUsageCount = $db->table('promo_usage')
                                    ->where('promo_code_id', $promo['id'])
                                    ->where('user_id', $userId)
                                    ->countAllResults();
                
                if ($userUsageCount >= $promo['user_limit_per_account']) {
                    return ['valid' => false, 'message' => 'Anda sudah mencapai batas penggunaan kode promo ini'];
                }
            } catch (\Exception $e) {
                // Jika tabel promo_usage belum ada, skip pengecekan ini
                log_message('debug', 'Promo usage table not found, skipping user limit check');
            }
        }

        // Check validity period
        $now = date('Y-m-d H:i:s');
        if ($promo['valid_from'] && $now < $promo['valid_from']) {
            return ['valid' => false, 'message' => 'Kode promo belum berlaku'];
        }
        if ($promo['valid_until'] && $now > $promo['valid_until']) {
            return ['valid' => false, 'message' => 'Kode promo sudah kadaluarsa'];
        }

        // Check minimum transaction
        if ($amount < $promo['min_transaction']) {
            return ['valid' => false, 'message' => 'Minimal transaksi Rp ' . number_format($promo['min_transaction'], 0, ',', '.')];
        }

        // Calculate discount
        $discount = 0;
        if ($promo['type'] == 'percentage') {
            $discount = $amount * $promo['value'] / 100;
            if ($promo['max_discount'] && $discount > $promo['max_discount']) {
                $discount = $promo['max_discount'];
            }
        } else {
            $discount = $promo['value'];
        }

        return [
            'valid' => true,
            'promo_id' => $promo['id'],
            'discount' => round($discount),
            'message' => 'Kode promo berhasil diterapkan'
        ];
    }

    /**
     * Increment usage count dan simpan tracking penggunaan per user
     * 
     * @param int $promoId ID promo
     * @param int|null $userId ID user (null untuk guest)
     * @param int|null $transactionId ID transaksi
     */
    public function incrementUsage($promoId, $userId = null, $transactionId = null)
    {
        // Increment global usage count
        $this->set('used_count', 'used_count + 1', false)
             ->where('id', $promoId)
             ->update();

        // Simpan tracking penggunaan per user (jika tabel promo_usage ada)
        try {
            $db = \Config\Database::connect();
            $db->table('promo_usage')->insert([
                'promo_code_id' => $promoId,
                'user_id' => $userId,
                'transaction_id' => $transactionId,
                'used_at' => date('Y-m-d H:i:s')
            ]);
        } catch (\Exception $e) {
            // Jika tabel promo_usage belum ada, skip tracking
            log_message('debug', 'Promo usage table not found, skipping usage tracking');
        }
    }

    /**
     * Ambil daftar produk yang terikat dengan promo
     * 
     * @param int $promoId ID promo
     * @return array
     */
    public function getPromoProducts($promoId)
    {
        try {
            $db = \Config\Database::connect();
            return $db->table('promo_products')
                      ->select('products.*')
                      ->join('products', 'products.id = promo_products.product_id')
                      ->where('promo_products.promo_code_id', $promoId)
                      ->get()
                      ->getResultArray();
        } catch (\Exception $e) {
            // Jika tabel promo_products belum ada, return empty array
            log_message('debug', 'Promo products table not found');
            return [];
        }
    }

    /**
     * Set produk yang bisa menggunakan promo ini
     * 
     * @param int $promoId ID promo
     * @param array $productIds Array of product IDs
     */
    public function setPromoProducts($promoId, $productIds)
    {
        try {
            $db = \Config\Database::connect();
            
            // Hapus relasi lama
            $db->table('promo_products')
               ->where('promo_code_id', $promoId)
               ->delete();
            
            // Insert relasi baru
            if (!empty($productIds)) {
                $data = [];
                foreach ($productIds as $productId) {
                    $data[] = [
                        'promo_code_id' => $promoId,
                        'product_id' => $productId,
                        'created_at' => date('Y-m-d H:i:s')
                    ];
                }
                $db->table('promo_products')->insertBatch($data);
            }
        } catch (\Exception $e) {
            // Jika tabel promo_products belum ada, skip
            log_message('debug', 'Promo products table not found, skipping product assignment');
        }
    }
}