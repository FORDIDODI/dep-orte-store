<?php

namespace App\Models;

use CodeIgniter\Model;

class PromoCodeModel extends Model
{
    protected $table = 'promo_codes';
    protected $primaryKey = 'id';
    protected $allowedFields = ['code', 'type', 'value', 'min_transaction', 'max_discount', 'usage_limit', 'used_count', 'valid_from', 'valid_until', 'is_active'];
    protected $useTimestamps = false;

    public function validateCode($code, $amount)
    {
        $promo = $this->where('code', $code)
                      ->where('is_active', true)
                      ->first();

        if (!$promo) {
            return ['valid' => false, 'message' => 'Kode promo tidak ditemukan'];
        }

        // Check usage limit
        if ($promo['usage_limit'] && $promo['used_count'] >= $promo['usage_limit']) {
            return ['valid' => false, 'message' => 'Kode promo sudah mencapai batas penggunaan'];
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

    public function incrementUsage($promoId)
    {
        $this->set('used_count', 'used_count + 1', false)
             ->where('id', $promoId)
             ->update();
    }
}