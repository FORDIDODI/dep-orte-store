<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MasterSeeder extends Seeder
{
    public function run()
    {
        // 1. Insert Games
        $games = [
            ['name' => 'Mobile Legends', 'slug' => 'mobile-legends', 'image' => 'ml.jpg', 'category' => 'MOBA', 'is_popular' => 1],
            ['name' => 'Free Fire', 'slug' => 'free-fire', 'image' => 'ff.jpg', 'category' => 'Battle Royale', 'is_popular' => 1],
            ['name' => 'PUBG Mobile', 'slug' => 'pubg-mobile', 'image' => 'pubg.jpg', 'category' => 'Battle Royale', 'is_popular' => 1],
            ['name' => 'Genshin Impact', 'slug' => 'genshin-impact', 'image' => 'genshin.jpg', 'category' => 'RPG', 'is_popular' => 1],
            ['name' => 'Call of Duty Mobile', 'slug' => 'codm', 'image' => 'codm.jpg', 'category' => 'FPS', 'is_popular' => 1],
            ['name' => 'Valorant', 'slug' => 'valorant', 'image' => 'valorant.jpg', 'category' => 'FPS', 'is_popular' => 0],
            ['name' => 'Clash of Clans', 'slug' => 'coc', 'image' => 'coc.jpg', 'category' => 'Strategy', 'is_popular' => 0],
        ];
        $this->db->table('games')->insertBatch($games);

        // 2. Insert Products
        $products = [
            // Mobile Legends (ID 1)
            ['game_id' => 1, 'name' => '86 Diamonds', 'price' => 20000, 'discount_price' => null],
            ['game_id' => 1, 'name' => '172 Diamonds', 'price' => 40000, 'discount_price' => null],
            ['game_id' => 1, 'name' => '257 Diamonds', 'price' => 60000, 'discount_price' => 45000],
            ['game_id' => 1, 'name' => '344 Diamonds', 'price' => 80000, 'discount_price' => null],
            ['game_id' => 1, 'name' => '706 Diamonds', 'price' => 160000, 'discount_price' => 135000],
            // Free Fire (ID 2)
            ['game_id' => 2, 'name' => '70 Diamonds', 'price' => 10000, 'discount_price' => null],
            ['game_id' => 2, 'name' => '140 Diamonds', 'price' => 20000, 'discount_price' => null],
            ['game_id' => 2, 'name' => '355 Diamonds', 'price' => 50000, 'discount_price' => null],
            ['game_id' => 2, 'name' => '720 Diamonds', 'price' => 100000, 'discount_price' => 95000],
        ];
        $this->db->table('products')->insertBatch($products);

        // 3. Insert Payment Methods
        $payments = [
            ['name' => 'BCA Virtual Account', 'type' => 'va', 'code' => 'bca_va', 'icon' => 'bca.png', 'fee' => 4000],
            ['name' => 'BNI Virtual Account', 'type' => 'va', 'code' => 'bni_va', 'icon' => 'bni.png', 'fee' => 4000],
            ['name' => 'Mandiri Virtual Account', 'type' => 'va', 'code' => 'mandiri_va', 'icon' => 'mandiri.png', 'fee' => 4000],
            ['name' => 'QRIS', 'type' => 'qris', 'code' => 'qris', 'icon' => 'qris.png', 'fee' => 0],
            ['name' => 'GoPay', 'type' => 'ewallet', 'code' => 'gopay', 'icon' => 'gopay.png', 'fee' => 0],
            ['name' => 'OVO', 'type' => 'ewallet', 'code' => 'ovo', 'icon' => 'ovo.png', 'fee' => 0],
            ['name' => 'DANA', 'type' => 'ewallet', 'code' => 'dana', 'icon' => 'dana.png', 'fee' => 0],
        ];
        $this->db->table('payment_methods')->insertBatch($payments);

        // 4. Insert Promo Codes
        // Kita gunakan date('Y-m-d H:i:s') untuk valid_until agar dinamis +30 hari dari sekarang
        $promos = [
            [
                'code' => 'WELCOME10',
                'type' => 'percentage',
                'value' => 10,
                'min_transaction' => 50000,
                'max_discount' => 50000,
                'usage_limit' => 100,
                'valid_until' => date('Y-m-d H:i:s', strtotime('+30 days'))
            ],
            [
                'code' => 'HEMAT5K',
                'type' => 'fixed',
                'value' => 5000,
                'min_transaction' => 100000,
                'max_discount' => 5000,
                'usage_limit' => 50,
                'valid_until' => date('Y-m-d H:i:s', strtotime('+30 days'))
            ],
        ];
        $this->db->table('promo_codes')->insertBatch($promos);

        // 5. Insert Admin User
        $admins = [
            [
                'username' => 'admin',
                // Password: admin123 (Sesuai hash yang kamu berikan)
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'
            ]
        ];
        $this->db->table('admin_users')->insertBatch($admins);
    }
}
