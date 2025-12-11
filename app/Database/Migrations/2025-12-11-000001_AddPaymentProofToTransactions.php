<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPaymentProofToTransactions extends Migration
{
    public function up()
    {
        // Cek apakah kolom payment_proof sudah ada
        if (!$this->db->fieldExists('payment_proof', 'transactions')) {
            $this->forge->addColumn('transactions', [
                'payment_proof' => [
                    'type' => 'VARCHAR',
                    'constraint' => 255,
                    'null' => true,
                    'after' => 'status'
                ]
            ]);
        }

        // Tambah kolom admin_notes jika belum ada
        if (!$this->db->fieldExists('admin_notes', 'transactions')) {
            $this->forge->addColumn('transactions', [
                'admin_notes' => [
                    'type' => 'TEXT',
                    'null' => true,
                    'after' => 'payment_proof'
                ]
            ]);
        }

        // Tambah kolom paid_at jika belum ada
        if (!$this->db->fieldExists('paid_at', 'transactions')) {
            $this->forge->addColumn('transactions', [
                'paid_at' => [
                    'type' => 'DATETIME',
                    'null' => true,
                    'after' => 'admin_notes'
                ]
            ]);
        }

        // Tambah kolom completed_at jika belum ada
        if (!$this->db->fieldExists('completed_at', 'transactions')) {
            $this->forge->addColumn('transactions', [
                'completed_at' => [
                    'type' => 'DATETIME',
                    'null' => true,
                    'after' => 'paid_at'
                ]
            ]);
        }
    }

    public function down()
    {
        if ($this->db->fieldExists('payment_proof', 'transactions')) {
            $this->forge->dropColumn('transactions', 'payment_proof');
        }
        if ($this->db->fieldExists('admin_notes', 'transactions')) {
            $this->forge->dropColumn('transactions', 'admin_notes');
        }
        if ($this->db->fieldExists('paid_at', 'transactions')) {
            $this->forge->dropColumn('transactions', 'paid_at');
        }
        if ($this->db->fieldExists('completed_at', 'transactions')) {
            $this->forge->dropColumn('transactions', 'completed_at');
        }
    }
}

