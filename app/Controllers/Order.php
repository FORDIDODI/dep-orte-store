<?php

namespace App\Controllers;

use App\Models\TransactionModel;
use App\Models\ProductModel;
use App\Models\PaymentMethodModel;
use App\Models\PromoCodeModel;
use App\Models\UserModel;
use App\Models\GameModel;

class Order extends BaseController
{
    protected $transactionModel;
    protected $productModel;
    protected $paymentModel;
    protected $promoModel;
    protected $userModel;
    protected $gameModel;

    public function __construct()
    {
        $this->transactionModel = new TransactionModel();
        $this->productModel = new ProductModel();
        $this->paymentModel = new PaymentMethodModel();
        $this->promoModel = new PromoCodeModel();
        $this->userModel = new UserModel();
        $this->gameModel = new GameModel();
    }

    public function create()
    {
        $validation = \Config\Services::validation();
        
        $rules = [
            'product_id' => 'required|numeric',
            'user_game_id' => 'required|min_length[3]',
            'payment_method_id' => 'required|numeric'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $productId = $this->request->getPost('product_id');
        $userGameId = $this->request->getPost('user_game_id');
        $paymentMethodId = $this->request->getPost('payment_method_id');
        $promoCode = $this->request->getPost('promo_code');

        // Get product details
        $product = $this->productModel->getWithGame($productId);
        if (!$product) {
            return redirect()->back()->with('error', 'Produk tidak ditemukan');
        }

        // Calculate amount
        $amount = $product['discount_price'] ?? $product['price'];
        $discount = 0;
        $promoId = null;

        // Apply promo code if provided
        if ($promoCode) {
            $promoValidation = $this->promoModel->validateCode($promoCode, $amount);
            if ($promoValidation['valid']) {
                $discount = $promoValidation['discount'];
                $promoId = $promoValidation['promo_id'];
            }
        }

        // Calculate fee
        $fee = $this->paymentModel->calculateFee($paymentMethodId, $amount - $discount);
        
        // Total payment
        $totalPayment = $amount - $discount + $fee;

        // Calculate points (if user logged in)
        $userId = session()->get('user_id');
        $pointsEarned = 0;
        if ($userId) {
            $settings = model('SettingModel');
            $pointsPerThousand = $settings->where('key_name', 'points_per_transaction')->first()['value'] ?? 1;
            $pointsEarned = floor($totalPayment / 1000) * $pointsPerThousand;
        }

        // Generate invoice
        $invoice = $this->transactionModel->generateInvoice();

        // Expiry time (60 minutes from now)
        $expiryMinutes = model('SettingModel')->where('key_name', 'transaction_expired_minutes')->first()['value'] ?? 60;
        $expiredAt = date('Y-m-d H:i:s', strtotime("+{$expiryMinutes} minutes"));

        // Get payment method
        $paymentMethod = $this->paymentModel->find($paymentMethodId);

        // Generate VA number or QR code based on payment type
        $vaNumber = null;
        $qrCode = null;

        if ($paymentMethod['type'] == 'va') {
            // Generate VA number (this should integrate with actual payment gateway)
            $vaNumber = $this->generateVANumber($paymentMethod['code'], $invoice);
        } elseif (in_array($paymentMethod['type'], ['qr', 'qris'])) {
            // Generate QR code (this should integrate with actual payment gateway)
            $qrCode = $this->generateQRCode($invoice, $totalPayment);
        }

        // Create transaction
        $transactionData = [
            'user_id' => $userId,
            'invoice_number' => $invoice,
            'game_id' => $product['game_id'],
            'product_id' => $productId,
            'user_game_id' => $userGameId,
            'payment_method_id' => $paymentMethodId,
            'promo_code_id' => $promoId,
            'amount' => $amount,
            'discount' => $discount,
            'fee' => $fee,
            'total_payment' => $totalPayment,
            'status' => 'pending',
            'va_number' => $vaNumber,
            'qr_code' => $qrCode,
            'points_earned' => $pointsEarned,
            'expired_at' => $expiredAt
        ];

        $this->transactionModel->insert($transactionData);

        // Increment promo usage
        if ($promoId) {
            $this->promoModel->incrementUsage($promoId);
        }

        return redirect()->to(base_url('order/status/' . $invoice));
    }

    public function status($invoice)
    {
        $transaction = $this->transactionModel->getByInvoice($invoice);

        if (!$transaction) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // Check if expired
        if ($transaction['status'] == 'pending' && strtotime($transaction['expired_at']) < time()) {
            $this->transactionModel->update($transaction['id'], ['status' => 'expired']);
            $transaction['status'] = 'expired';
        }

        $data = [
            'title' => 'Status Transaksi - ' . $invoice,
            'transaction' => $transaction
        ];

        return view('order/status', $data);
    }

    public function checkPromo()
    {
        $code = $this->request->getPost('code');
        $amount = $this->request->getPost('amount');

        if (!$code || !$amount) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Data tidak lengkap'
            ]);
        }

        $result = $this->promoModel->validateCode($code, $amount);

        return $this->response->setJSON([
            'success' => $result['valid'],
            'message' => $result['message'],
            'discount' => $result['discount'] ?? 0
        ]);
    }

    private function generateVANumber($bankCode, $invoice)
    {
        // This is a dummy implementation
        // In production, you should integrate with actual payment gateway API
        $bankPrefix = [
            'bca_va' => '70012',
            'bni_va' => '8808',
            'bri_va' => '26215',
            'mandiri_va' => '88012'
        ];

        $prefix = $bankPrefix[$bankCode] ?? '88888';
        $uniqueNumber = substr(md5($invoice), 0, 11);
        
        return $prefix . $uniqueNumber;
    }

    private function generateQRCode($invoice, $amount)
    {
        // This is a dummy implementation
        // In production, you should integrate with actual payment gateway API
        // This could return a URL to QR image or base64 encoded QR image
        
        helper('text');
        return 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=' . urlencode("Invoice: $invoice, Amount: $amount");
    }
}