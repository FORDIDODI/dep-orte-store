<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class UpdateSchemaFromSQLDump extends Migration
{
    public function up()
    {
        // Add description field to games table
        $fields = [
            'description' => ['type' => 'TEXT', 'null' => true],
        ];
        $this->forge->addColumn('games', $fields);

        // Add updated_at field to products table
        $fields = [
            'updated_at' => ['type' => 'DATETIME', 'default' => new RawSql('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP')],
        ];
        $this->forge->addColumn('products', $fields);

        // Add created_at and updated_at fields to payment_methods table
        $fields = [
            'created_at' => ['type' => 'DATETIME', 'default' => new RawSql('CURRENT_TIMESTAMP')],
            'updated_at' => ['type' => 'DATETIME', 'default' => new RawSql('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP')],
        ];
        $this->forge->addColumn('payment_methods', $fields);
    }

    public function down()
    {
        // Remove added columns in reverse order
        $this->forge->dropColumn('payment_methods', ['created_at', 'updated_at']);
        $this->forge->dropColumn('products', ['updated_at']);
        $this->forge->dropColumn('games', ['description']);
    }
}
