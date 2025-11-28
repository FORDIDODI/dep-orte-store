<?php

namespace App\Models;

use CodeIgniter\Model;

class TransactionModel extends Model
{
    protected $table = 'transactions';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'user_id', 'invoice_number', 'game_id', 'product_id', 'user_game_id',
        'payment_method_id', 'promo_code_id', 'amount', 'discount', 'fee',
        'total_payment', 'status', 'payment_proof', 'qr_code', 'va_number',
        'points_earned', 'admin_notes', 'expired_at', 'paid_at', 'completed_at'
    ];
    protected $useTimestamps = true;

    public function getWithDetails($transactionId)
    {
        return $this->select('transactions.*, games.name as game_name, products.name as product_name, payment_methods.name as payment_name, payment_methods.type as payment_type')
                    ->join('games', 'games.id = transactions.game_id')
                    ->join('products', 'products.id = transactions.product_id')
                    ->join('payment_methods', 'payment_methods.id = transactions.payment_method_id')
                    ->find($transactionId);
    }

    public function getByInvoice($invoice)
    {
        return $this->select('transactions.*, games.name as game_name, games.slug as game_slug, products.name as product_name, payment_methods.name as payment_name, payment_methods.type as payment_type')
                    ->join('games', 'games.id = transactions.game_id')
                    ->join('products', 'products.id = transactions.product_id')
                    ->join('payment_methods', 'payment_methods.id = transactions.payment_method_id')
                    ->where('transactions.invoice_number', $invoice)
                    ->first();
    }

    public function getUserTransactions($userId, $limit = 10)
    {
        return $this->select('transactions.*, games.name as game_name, products.name as product_name')
                    ->join('games', 'games.id = transactions.game_id')
                    ->join('products', 'products.id = transactions.product_id')
                    ->where('transactions.user_id', $userId)
                    ->orderBy('transactions.created_at', 'DESC')
                    ->findAll($limit);
    }

    public function generateInvoice()
    {
        $date = date('Ymd');
        $random = strtoupper(substr(md5(uniqid(rand(), true)), 0, 6));
        return 'INV' . $date . $random;
    }

    public function getPendingTransactions()
    {
        return $this->whereIn('status', ['pending', 'processing'])
                    ->where('expired_at >', date('Y-m-d H:i:s'))
                    ->findAll();
    }

    public function getTodayStats()
    {
        $today = date('Y-m-d');
        
        return [
            'total' => $this->where('DATE(created_at)', $today)->countAllResults(),
            'success' => $this->where('DATE(created_at)', $today)->where('status', 'success')->countAllResults(),
            'pending' => $this->where('DATE(created_at)', $today)->where('status', 'pending')->countAllResults(),
            'revenue' => $this->selectSum('total_payment')->where('DATE(created_at)', $today)->where('status', 'success')->first()
        ];
    }
}
