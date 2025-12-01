<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UpdateDataFromSQLDump extends Seeder
{
    /**
     * Cache for table field names
     */
    private $tableFieldsCache = [];

    /**
     * Helper method to insert or update records
     */
    private function insertOrUpdate($table, $data, $uniqueKey = 'id')
    {
        // Get table fields to filter out non-existent columns (cached)
        if (!isset($this->tableFieldsCache[$table])) {
            $fields = $this->db->getFieldData($table);
            $this->tableFieldsCache[$table] = array_column($fields, 'name');
        }
        $fieldNames = $this->tableFieldsCache[$table];

        foreach ($data as $row) {
            $builder = $this->db->table($table);

            // Filter row data to only include existing columns
            $filteredRow = array_intersect_key($row, array_flip($fieldNames));

            // Check if record exists based on unique key
            if ($uniqueKey === 'id' && isset($row['id'])) {
                $exists = $builder->where('id', $row['id'])->countAllResults() > 0;
            } elseif ($uniqueKey === 'username' && isset($row['username'])) {
                $exists = $builder->where('username', $row['username'])->countAllResults() > 0;
            } elseif ($uniqueKey === 'slug' && isset($row['slug'])) {
                $exists = $builder->where('slug', $row['slug'])->countAllResults() > 0;
            } elseif ($uniqueKey === 'code' && isset($row['code'])) {
                $exists = $builder->where('code', $row['code'])->countAllResults() > 0;
            } elseif ($uniqueKey === 'invoice_number' && isset($row['invoice_number'])) {
                $exists = $builder->where('invoice_number', $row['invoice_number'])->countAllResults() > 0;
            } elseif ($uniqueKey === 'email' && isset($row['email'])) {
                $exists = $builder->where('email', $row['email'])->countAllResults() > 0;
            } else {
                $exists = false;
            }

            if ($exists) {
                // Remove id and unique key from update data to avoid conflicts
                $updateData = $filteredRow;
                if (isset($updateData['id'])) {
                    unset($updateData['id']);
                }
                if ($uniqueKey !== 'id' && isset($updateData[$uniqueKey])) {
                    // Don't update the unique key field itself
                    unset($updateData[$uniqueKey]);
                }

                // Update existing record
                if ($uniqueKey === 'id' && isset($row['id'])) {
                    $builder->where('id', $row['id'])->update($updateData);
                } elseif ($uniqueKey === 'username' && isset($row['username'])) {
                    $builder->where('username', $row['username'])->update($updateData);
                } elseif ($uniqueKey === 'slug' && isset($row['slug'])) {
                    $builder->where('slug', $row['slug'])->update($updateData);
                } elseif ($uniqueKey === 'code' && isset($row['code'])) {
                    $builder->where('code', $row['code'])->update($updateData);
                } elseif ($uniqueKey === 'invoice_number' && isset($row['invoice_number'])) {
                    $builder->where('invoice_number', $row['invoice_number'])->update($updateData);
                } elseif ($uniqueKey === 'email' && isset($row['email'])) {
                    $builder->where('email', $row['email'])->update($updateData);
                }
            } else {
                // Insert new record - use original row but filter to existing columns
                $insertRow = array_intersect_key($row, array_flip($fieldNames));
                $builder->insert($insertRow);
            }
        }
    }

    public function run()
    {

        // 1. Insert Admin Users
        $adminUsers = [
            [
                'id' => 5,
                'username' => 'admin',
                'password' => 'bebas',
                'created_at' => '2025-11-29 17:32:21'
            ],
            [
                'id' => 6,
                'username' => 'bebas',
                'password' => 'bebas',
                'created_at' => '2025-11-29 18:13:18'
            ],
        ];
        $this->insertOrUpdate('admin_users', $adminUsers, 'username');

        // 2. Insert Games
        $games = [
            [
                'id' => 1,
                'name' => 'Mobile Legends',
                'slug' => 'mobile-legends',
                'image' => 'ml.jpg',
                'category' => 'MOBA',
                'description' => null,
                'is_popular' => 1,
                'is_active' => 1,
                'created_at' => '2025-11-28 16:11:51',
                'updated_at' => '2025-11-28 16:11:51'
            ],
            [
                'id' => 2,
                'name' => 'Free Fire',
                'slug' => 'free-fire',
                'image' => 'ff.jpg',
                'category' => 'Battle Royale',
                'description' => null,
                'is_popular' => 1,
                'is_active' => 1,
                'created_at' => '2025-11-28 16:11:51',
                'updated_at' => '2025-11-28 16:11:51'
            ],
            [
                'id' => 3,
                'name' => 'PUBG Mobile',
                'slug' => 'pubg-mobile',
                'image' => 'pubg.jpg',
                'category' => 'Battle Royale',
                'description' => null,
                'is_popular' => 1,
                'is_active' => 1,
                'created_at' => '2025-11-28 16:11:51',
                'updated_at' => '2025-11-28 16:11:51'
            ],
            [
                'id' => 4,
                'name' => 'Genshin Impact',
                'slug' => 'genshin-impact',
                'image' => 'genshin.jpg',
                'category' => 'RPG',
                'description' => null,
                'is_popular' => 1,
                'is_active' => 1,
                'created_at' => '2025-11-28 16:11:51',
                'updated_at' => '2025-11-28 16:11:51'
            ],
            [
                'id' => 5,
                'name' => 'Call of Duty Mobile',
                'slug' => 'codm',
                'image' => 'codm.jpg',
                'category' => 'FPS',
                'description' => null,
                'is_popular' => 1,
                'is_active' => 1,
                'created_at' => '2025-11-28 16:11:51',
                'updated_at' => '2025-11-28 16:11:51'
            ],
            [
                'id' => 6,
                'name' => 'Valorant',
                'slug' => 'valorant',
                'image' => 'valorant.jpg',
                'category' => 'FPS',
                'description' => null,
                'is_popular' => 0,
                'is_active' => 1,
                'created_at' => '2025-11-28 16:11:51',
                'updated_at' => '2025-11-30 05:20:46'
            ],
            [
                'id' => 7,
                'name' => 'Clash of Clans',
                'slug' => 'coc',
                'image' => 'coc.jpg',
                'category' => 'Strategy',
                'description' => null,
                'is_popular' => 0,
                'is_active' => 1,
                'created_at' => '2025-11-28 16:11:51',
                'updated_at' => '2025-11-30 05:20:26'
            ],
            [
                'id' => 8,
                'name' => 'BEfi',
                'slug' => 'befi',
                'image' => 'default.jpg',
                'category' => 'RPG',
                'description' => null,
                'is_popular' => 0,
                'is_active' => 1,
                'created_at' => '2025-11-30 05:27:17',
                'updated_at' => '2025-11-30 05:39:38'
            ],
        ];
        $this->insertOrUpdate('games', $games, 'slug');

        // 3. Insert Payment Methods
        $paymentMethods = [
            [
                'id' => 1,
                'name' => 'BCA Virtual Account',
                'type' => 'va',
                'code' => 'bca_va',
                'icon' => 'bca.png',
                'fee' => 4000.00,
                'is_active' => 1,
                'created_at' => '2025-11-30 12:37:57',
                'updated_at' => '2025-11-30 12:37:57'
            ],
            [
                'id' => 2,
                'name' => 'BNI Virtual Account',
                'type' => 'va',
                'code' => 'bni_va',
                'icon' => 'bni.png',
                'fee' => 4000.00,
                'is_active' => 1,
                'created_at' => '2025-11-30 12:37:57',
                'updated_at' => '2025-11-30 12:37:57'
            ],
            [
                'id' => 3,
                'name' => 'Mandiri Virtual Account',
                'type' => 'va',
                'code' => 'mandiri_va',
                'icon' => 'mandiri.png',
                'fee' => 4000.00,
                'is_active' => 1,
                'created_at' => '2025-11-30 12:37:57',
                'updated_at' => '2025-11-30 12:37:57'
            ],
            [
                'id' => 4,
                'name' => 'QRIS',
                'type' => 'qris',
                'code' => 'qris',
                'icon' => 'qris.png',
                'fee' => 0.00,
                'is_active' => 1,
                'created_at' => '2025-11-30 12:37:57',
                'updated_at' => '2025-11-30 12:37:57'
            ],
            [
                'id' => 5,
                'name' => 'GoPay',
                'type' => 'ewallet',
                'code' => 'gopay',
                'icon' => 'gopay.png',
                'fee' => 0.00,
                'is_active' => 1,
                'created_at' => '2025-11-30 12:37:57',
                'updated_at' => '2025-11-30 12:37:57'
            ],
            [
                'id' => 6,
                'name' => 'OVO',
                'type' => 'ewallet',
                'code' => 'ovo',
                'icon' => 'ovo.png',
                'fee' => 0.00,
                'is_active' => 1,
                'created_at' => '2025-11-30 12:37:57',
                'updated_at' => '2025-11-30 12:37:57'
            ],
            [
                'id' => 7,
                'name' => 'DANA',
                'type' => 'ewallet',
                'code' => 'dana',
                'icon' => 'dana.png',
                'fee' => 0.00,
                'is_active' => 1,
                'created_at' => '2025-11-30 12:37:57',
                'updated_at' => '2025-11-30 12:37:57'
            ],
        ];
        $this->insertOrUpdate('payment_methods', $paymentMethods, 'code');

        // 4. Insert Products
        $products = [
            [
                'id' => 1,
                'game_id' => 1,
                'name' => '86 Diamonds',
                'description' => null,
                'price' => 20000.00,
                'discount_price' => null,
                'is_active' => 1,
                'created_at' => '2025-11-28 16:11:51',
                'updated_at' => '2025-11-30 12:35:57'
            ],
            [
                'id' => 2,
                'game_id' => 1,
                'name' => '172 Diamonds',
                'description' => null,
                'price' => 40000.00,
                'discount_price' => null,
                'is_active' => 1,
                'created_at' => '2025-11-28 16:11:51',
                'updated_at' => '2025-11-30 12:35:57'
            ],
            [
                'id' => 3,
                'game_id' => 1,
                'name' => '257 Diamonds',
                'description' => null,
                'price' => 60000.00,
                'discount_price' => 45000.00,
                'is_active' => 1,
                'created_at' => '2025-11-28 16:11:51',
                'updated_at' => '2025-11-30 12:35:57'
            ],
            [
                'id' => 4,
                'game_id' => 1,
                'name' => '344 Diamonds',
                'description' => null,
                'price' => 80000.00,
                'discount_price' => null,
                'is_active' => 1,
                'created_at' => '2025-11-28 16:11:51',
                'updated_at' => '2025-11-30 12:35:57'
            ],
            [
                'id' => 5,
                'game_id' => 1,
                'name' => '706 Diamonds',
                'description' => null,
                'price' => 160000.00,
                'discount_price' => 135000.00,
                'is_active' => 1,
                'created_at' => '2025-11-28 16:11:51',
                'updated_at' => '2025-11-30 12:35:57'
            ],
            [
                'id' => 6,
                'game_id' => 2,
                'name' => '70 Diamonds',
                'description' => null,
                'price' => 10000.00,
                'discount_price' => null,
                'is_active' => 1,
                'created_at' => '2025-11-28 16:11:51',
                'updated_at' => '2025-11-30 12:35:57'
            ],
            [
                'id' => 7,
                'game_id' => 2,
                'name' => '140 Diamonds',
                'description' => null,
                'price' => 20000.00,
                'discount_price' => null,
                'is_active' => 1,
                'created_at' => '2025-11-28 16:11:51',
                'updated_at' => '2025-11-30 12:35:57'
            ],
            [
                'id' => 8,
                'game_id' => 2,
                'name' => '355 Diamonds',
                'description' => null,
                'price' => 50000.00,
                'discount_price' => null,
                'is_active' => 1,
                'created_at' => '2025-11-28 16:11:51',
                'updated_at' => '2025-11-30 12:35:57'
            ],
            [
                'id' => 9,
                'game_id' => 2,
                'name' => '721 Diamonds',
                'description' => '',
                'price' => 100000.00,
                'discount_price' => 95000.00,
                'is_active' => 1,
                'created_at' => '2025-11-28 16:11:51',
                'updated_at' => '2025-11-30 05:38:18'
            ],
            [
                'id' => 10,
                'game_id' => 8,
                'name' => '100000 Diamonds',
                'description' => 'bebas',
                'price' => 99999999.99,
                'discount_price' => 99999999.99,
                'is_active' => 1,
                'created_at' => '2025-11-30 05:39:27',
                'updated_at' => '2025-11-30 05:39:27'
            ],
        ];
        $this->insertOrUpdate('products', $products, 'id');

        // 5. Insert Promo Codes
        $promoCodes = [
            [
                'id' => 1,
                'code' => 'WELCOME10',
                'type' => 'percentage',
                'value' => 10.00,
                'min_transaction' => 50000.00,
                'max_discount' => 50000.00,
                'usage_limit' => 100,
                'used_count' => 0,
                'valid_until' => '2025-12-28 16:11:51',
                'is_active' => 1,
                'updated_at' => '2025-11-30 12:37:24'
            ],
            [
                'id' => 2,
                'code' => 'HEMAT5K',
                'type' => 'fixed',
                'value' => 5000.00,
                'min_transaction' => 100000.00,
                'max_discount' => 5000.00,
                'usage_limit' => 50,
                'used_count' => 0,
                'valid_until' => null,
                'is_active' => 1,
                'updated_at' => '2025-11-30 12:37:24'
            ],
            [
                'id' => 3,
                'code' => 'BEBEK5',
                'type' => 'percentage',
                'value' => 10.00,
                'min_transaction' => 50000.00,
                'max_discount' => 50000.00,
                'usage_limit' => 25,
                'used_count' => 0,
                'valid_until' => null,
                'is_active' => 1,
                'updated_at' => '2025-11-30 12:39:05'
            ],
        ];
        $this->insertOrUpdate('promo_codes', $promoCodes, 'code');

        // 6. Insert Users
        $users = [
            [
                'id' => 1,
                'username' => 'RAXIE',
                'email' => 'bobot@gmail.com',
                'password' => '$2y$12$ysd2hUMoTQwmtO7GOHE02uJVlExL3zOH0RT.DSgANTiufMZELiU/u',
                'phone' => '09302930293',
                'points' => 190,
                'total_transactions' => 2,
                'created_at' => '2025-11-28 09:15:33',
                'updated_at' => '2025-11-29 16:18:15'
            ],
        ];
        $this->insertOrUpdate('users', $users, 'email');

        // 7. Insert Transactions
        $transactions = [
            [
                'id' => 1,
                'user_id' => 1,
                'invoice_number' => 'INV202511284F99AA',
                'game_id' => 1,
                'product_id' => 5,
                'user_game_id' => '7364949',
                'payment_method_id' => 7,
                'promo_code_id' => null,
                'amount' => 135000.00,
                'discount' => 0.00,
                'fee' => 0.00,
                'total_payment' => 135000.00,
                'status' => 'success',
                'qr_code' => 'QR-INV202511284F99AA',
                'va_number' => null,
                'points_earned' => 135,
                'expired_at' => '2025-11-28 14:44:34',
                'created_at' => '2025-11-28 13:44:34',
                'updated_at' => '2025-11-29 16:35:59'
            ],
            [
                'id' => 2,
                'user_id' => null,
                'invoice_number' => 'INV20251128B118B0',
                'game_id' => 1,
                'product_id' => 5,
                'user_game_id' => '7364949',
                'payment_method_id' => 5,
                'promo_code_id' => null,
                'amount' => 135000.00,
                'discount' => 0.00,
                'fee' => 0.00,
                'total_payment' => 135000.00,
                'status' => 'processing',
                'qr_code' => 'QR-INV20251128B118B0',
                'va_number' => null,
                'points_earned' => 0,
                'expired_at' => '2025-11-28 14:46:40',
                'created_at' => '2025-11-28 13:46:40',
                'updated_at' => '2025-11-30 05:27:34'
            ],
            [
                'id' => 3,
                'user_id' => null,
                'invoice_number' => 'INV20251129893BCE',
                'game_id' => 2,
                'product_id' => 9,
                'user_game_id' => '7364949',
                'payment_method_id' => 5,
                'promo_code_id' => null,
                'amount' => 95000.00,
                'discount' => 0.00,
                'fee' => 0.00,
                'total_payment' => 95000.00,
                'status' => 'success',
                'qr_code' => 'QR-INV20251129893BCE',
                'va_number' => null,
                'points_earned' => 0,
                'expired_at' => '2025-11-29 08:09:39',
                'created_at' => '2025-11-29 07:09:39',
                'updated_at' => '2025-11-29 16:35:36'
            ],
            [
                'id' => 4,
                'user_id' => 1,
                'invoice_number' => 'INV20251129CCD48A',
                'game_id' => 2,
                'product_id' => 9,
                'user_game_id' => '7364949',
                'payment_method_id' => 6,
                'promo_code_id' => null,
                'amount' => 95000.00,
                'discount' => 0.00,
                'fee' => 0.00,
                'total_payment' => 95000.00,
                'status' => 'success',
                'qr_code' => 'QR-INV20251129CCD48A',
                'va_number' => null,
                'points_earned' => 95,
                'expired_at' => '2025-11-29 08:11:00',
                'created_at' => '2025-11-29 07:11:00',
                'updated_at' => '2025-11-29 16:24:19'
            ],
        ];
        $this->insertOrUpdate('transactions', $transactions, 'invoice_number');
    }
}
