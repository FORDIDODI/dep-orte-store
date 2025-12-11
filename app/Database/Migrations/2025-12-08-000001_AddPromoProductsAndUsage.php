<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPromoProductsAndUsage extends Migration
{
    public function up()
    {
        // Tabel promo_products: relasi many-to-many promo ke products
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'auto_increment' => true,
            ],
            'promo_code_id' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'product_id' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('promo_code_id', 'promo_codes', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('product_id', 'products', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addUniqueKey(['promo_code_id', 'product_id']);
        $this->forge->createTable('promo_products');

        // Tabel promo_usage: tracking penggunaan promo per user
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'auto_increment' => true,
            ],
            'promo_code_id' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'user_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true, // NULL untuk guest user
            ],
            'transaction_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
            ],
            'used_at' => [
                'type' => 'DATETIME',
                'default' => 'CURRENT_TIMESTAMP',
            ],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('promo_code_id', 'promo_codes', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'SET NULL');
        $this->forge->addForeignKey('transaction_id', 'transactions', 'id', 'CASCADE', 'SET NULL');
        $this->forge->addKey(['promo_code_id', 'user_id']);
        $this->forge->createTable('promo_usage');

        // Tambah kolom user_limit_per_account di promo_codes
        $this->forge->addColumn('promo_codes', [
            'user_limit_per_account' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
                'comment'    => 'Limit penggunaan per user/akun (NULL = unlimited)',
                'after'      => 'usage_limit',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropTable('promo_usage', true);
        $this->forge->dropTable('promo_products', true);
        $this->forge->dropColumn('promo_codes', 'user_limit_per_account');
    }
}
