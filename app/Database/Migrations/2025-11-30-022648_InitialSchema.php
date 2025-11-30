<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class InitialSchema extends Migration
{
    public function up()
    {
        // 1. Table: users
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true],
            'username' => ['type' => 'VARCHAR', 'constraint' => 50, 'unique' => true],
            'email' => ['type' => 'VARCHAR', 'constraint' => 100, 'unique' => true],
            'password' => ['type' => 'VARCHAR', 'constraint' => 255],
            'phone' => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'points' => ['type' => 'INT', 'default' => 0],
            'total_transactions' => ['type' => 'INT', 'default' => 0],
            'created_at' => ['type' => 'DATETIME', 'default' => new RawSql('CURRENT_TIMESTAMP')],
            'updated_at' => ['type' => 'DATETIME', 'default' => new RawSql('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP')],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('users');

        // 2. Table: games
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true],
            'name' => ['type' => 'VARCHAR', 'constraint' => 100],
            'slug' => ['type' => 'VARCHAR', 'constraint' => 100, 'unique' => true],
            'image' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'category' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'is_popular' => ['type' => 'BOOLEAN', 'default' => false],
            'is_active' => ['type' => 'BOOLEAN', 'default' => true],
            'created_at' => ['type' => 'DATETIME', 'default' => new RawSql('CURRENT_TIMESTAMP')],
            'updated_at' => ['type' => 'DATETIME', 'default' => new RawSql('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP')],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('games');

        // 3. Table: products
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true],
            'game_id' => ['type' => 'INT', 'constraint' => 11],
            'name' => ['type' => 'VARCHAR', 'constraint' => 100],
            'description' => ['type' => 'TEXT', 'null' => true],
            'price' => ['type' => 'DECIMAL', 'constraint' => '10,2'],
            'discount_price' => ['type' => 'DECIMAL', 'constraint' => '10,2', 'null' => true],
            'is_active' => ['type' => 'BOOLEAN', 'default' => true],
            'created_at' => ['type' => 'DATETIME', 'default' => new RawSql('CURRENT_TIMESTAMP')],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('game_id', 'games', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('products');

        // 4. Table: payment_methods
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true],
            'name' => ['type' => 'VARCHAR', 'constraint' => 50],
            'type' => ['type' => 'ENUM', 'constraint' => ['va', 'qris', 'ewallet']],
            'code' => ['type' => 'VARCHAR', 'constraint' => 20, 'unique' => true],
            'icon' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'fee' => ['type' => 'DECIMAL', 'constraint' => '10,2', 'default' => 0],
            'is_active' => ['type' => 'BOOLEAN', 'default' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('payment_methods');

        // 5. Table: promo_codes
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true],
            'code' => ['type' => 'VARCHAR', 'constraint' => 50, 'unique' => true],
            'type' => ['type' => 'ENUM', 'constraint' => ['percentage', 'fixed']],
            'value' => ['type' => 'DECIMAL', 'constraint' => '10,2'],
            'min_transaction' => ['type' => 'DECIMAL', 'constraint' => '10,2', 'default' => 0],
            'max_discount' => ['type' => 'DECIMAL', 'constraint' => '10,2', 'null' => true],
            'usage_limit' => ['type' => 'INT', 'null' => true],
            'used_count' => ['type' => 'INT', 'default' => 0],
            'valid_until' => ['type' => 'DATETIME', 'null' => true],
            'is_active' => ['type' => 'BOOLEAN', 'default' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('promo_codes');

        // 6. Table: transactions
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true],
            'user_id' => ['type' => 'INT', 'constraint' => 11, 'null' => true],
            'invoice_number' => ['type' => 'VARCHAR', 'constraint' => 50, 'unique' => true],
            'game_id' => ['type' => 'INT', 'constraint' => 11],
            'product_id' => ['type' => 'INT', 'constraint' => 11],
            'user_game_id' => ['type' => 'VARCHAR', 'constraint' => 100],
            'payment_method_id' => ['type' => 'INT', 'constraint' => 11],
            'promo_code_id' => ['type' => 'INT', 'constraint' => 11, 'null' => true],
            'amount' => ['type' => 'DECIMAL', 'constraint' => '10,2'],
            'discount' => ['type' => 'DECIMAL', 'constraint' => '10,2', 'default' => 0],
            'fee' => ['type' => 'DECIMAL', 'constraint' => '10,2', 'default' => 0],
            'total_payment' => ['type' => 'DECIMAL', 'constraint' => '10,2'],
            'status' => ['type' => 'ENUM', 'constraint' => ['pending', 'processing', 'success', 'failed', 'expired'], 'default' => 'pending'],
            'qr_code' => ['type' => 'TEXT', 'null' => true],
            'va_number' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'points_earned' => ['type' => 'INT', 'default' => 0],
            'expired_at' => ['type' => 'DATETIME', 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'default' => new RawSql('CURRENT_TIMESTAMP')],
            'updated_at' => ['type' => 'DATETIME', 'default' => new RawSql('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP')],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'SET NULL', 'SET NULL');
        $this->forge->addForeignKey('game_id', 'games', 'id', 'RESTRICT', 'RESTRICT');
        $this->forge->addForeignKey('product_id', 'products', 'id', 'RESTRICT', 'RESTRICT');
        $this->forge->addForeignKey('payment_method_id', 'payment_methods', 'id', 'RESTRICT', 'RESTRICT');
        $this->forge->addForeignKey('promo_code_id', 'promo_codes', 'id', 'SET NULL', 'SET NULL');
        $this->forge->createTable('transactions');

        // 7. Table: admin_users
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true],
            'username' => ['type' => 'VARCHAR', 'constraint' => 50, 'unique' => true],
            'password' => ['type' => 'VARCHAR', 'constraint' => 255],
            'created_at' => ['type' => 'DATETIME', 'default' => new RawSql('CURRENT_TIMESTAMP')],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('admin_users');
    }

    public function down()
    {
        // Hapus tabel dengan urutan terbalik dari pembuatan (untuk constraint FK)
        $this->forge->dropTable('admin_users', true);
        $this->forge->dropTable('transactions', true);
        $this->forge->dropTable('promo_codes', true);
        $this->forge->dropTable('payment_methods', true);
        $this->forge->dropTable('products', true);
        $this->forge->dropTable('games', true);
        $this->forge->dropTable('users', true);
    }
}
