<?php

namespace App\Models;

use CodeIgniter\Model;

class PaymentMethodModel extends Model
{
    protected $table = 'payment_methods';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name', 'type', 'code', 'icon', 'fee_percent', 'fee_fixed', 'is_active'];
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
        
        $fee = $payment['fee_fixed'];
        $fee += ($amount * $payment['fee_percent'] / 100);
        
        return round($fee);
    }
}