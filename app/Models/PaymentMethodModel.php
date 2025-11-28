<?php

namespace App\Models;

use CodeIgniter\Model;

class PaymentMethodModel extends Model
{
    protected $table = 'payment_methods';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name', 'type', 'code', 'icon', 'fee', 'is_active'];
    protected $useTimestamps = false;

    public function getActive()
    {
        return $this->where('is_active', true)->findAll();
    }

    public function getByType($type)
    {
        return $this->where('type', $type)->where('is_active', true)->findAll();
    }

    public function calculateFee($paymentId, $amount)
    {
        $payment = $this->find($paymentId);
        if (!$payment) return 0;
        
        // Fee sudah dalam satu field saja
        $fee = $payment['fee'] ?? 0;
        
        return round($fee);
    }
}
