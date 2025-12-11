<?php
// ============================================
// app/Controllers/Order.php (FIXED FEE CALCULATION)
// ============================================
namespace App\Controllers;

use App\Models\TransactionModel;
use App\Models\ProductModel;
use App\Models\PaymentMethodModel;
use App\Models\PromoCodeModel;
use App\Models\UserModel;

class Order extends BaseController
{
    protected $transactionModel;
    protected $productModel;
    protected $paymentModel;
    protected $promoModel;

    public function __construct()
    {
        $this->transactionModel = new TransactionModel();
        $this->productModel = new ProductModel();
        $this->paymentModel = new PaymentMethodModel();
        $this->promoModel = new PromoCodeModel();
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

        // Get user ID (bisa null untuk guest)
        $userId = session()->get('user_id');

        // Apply promo code if provided
        if ($promoCode) {
            // Pass product_id dan user_id ke validasi
            $promoValidation = $this->promoModel->validateCode($promoCode, $amount, $productId, $userId);
            if ($promoValidation['valid']) {
                $discount = $promoValidation['discount'];
                $promoId = $promoValidation['promo_id'];
            } else {
                // Jika promo tidak valid, redirect back dengan error
                return redirect()->back()->withInput()->with('error', $promoValidation['message']);
            }
        }

        // Calculate fee - FIXED: hanya satu field fee
        $fee = $this->paymentModel->calculateFee($paymentMethodId, $amount - $discount);
        
        // Total payment
        $totalPayment = $amount - $discount + $fee;

        // Calculate points (if user logged in)
        $pointsEarned = 0;
        if ($userId) {
            $pointsEarned = floor($totalPayment / 1000); // 1 point per 1000 rupiah
        }

        // Generate invoice
        $invoice = $this->transactionModel->generateInvoice();

        // Expiry time (24 hours from now)
        $expiredAt = date('Y-m-d H:i:s', strtotime('+24 hours'));

        // Get payment method
        $paymentMethod = $this->paymentModel->find($paymentMethodId);

        // Generate VA number or QR code based on payment type
        $vaNumber = null;
        $qrCode = null;

        if ($paymentMethod['type'] == 'va') {
            // Generate VA number (mockup)
            $vaNumber = $this->generateVANumber($paymentMethod['code'], $invoice);
        } elseif (in_array($paymentMethod['type'], ['qris', 'ewallet'])) {
            // Generate QR code URL (mockup)
            $qrCode = 'QR-' . $invoice;
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

        $transactionId =         $this->transactionModel->insert($transactionData);
        $transactionId = $this->transactionModel->getInsertID();

        // Increment promo usage dengan tracking per user
        if ($promoId) {
            $this->promoModel->incrementUsage($promoId, $userId, $transactionId);
        }

        return redirect()->to(base_url('order/status/' . $invoice));
    }

    public function status($invoice)
    {
        $transaction = $this->transactionModel->getByInvoice($invoice);

        if (!$transaction) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // Check if expired (auto-expire setelah 24 jam)
        if (in_array($transaction['status'], ['pending', 'processing']) && strtotime($transaction['expired_at']) < time()) {
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
        $productId = $this->request->getPost('product_id');

        if (!$code || !$amount) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Data tidak lengkap'
            ]);
        }

        // Get user ID (bisa null untuk guest)
        $userId = session()->get('user_id');

        // Pass product_id dan user_id ke validasi
        $result = $this->promoModel->validateCode($code, $amount, $productId, $userId);

        return $this->response->setJSON([
            'success' => $result['valid'],
            'message' => $result['message'],
            'discount' => $result['discount'] ?? 0
        ]);
    }

    private function generateVANumber($bankCode, $invoice)
    {
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

    /**
     * Upload bukti pembayaran
     */
    public function uploadPaymentProof($invoice)
    {
        $transaction = $this->transactionModel->getByInvoice($invoice);

        if (!$transaction) {
            return redirect()->back()->with('error', 'Transaksi tidak ditemukan');
        }

        // Hanya bisa upload jika status pending
        if ($transaction['status'] !== 'pending') {
            return redirect()->back()->with('error', 'Tidak dapat mengupload bukti pembayaran untuk transaksi ini');
        }

        // Validasi file
        $validation = \Config\Services::validation();
        $rules = [
            'payment_proof' => [
                'rules' => 'uploaded[payment_proof]|max_size[payment_proof,2048]|is_image[payment_proof]|mime_in[payment_proof,image/jpg,image/jpeg,image/png,image/webp]',
                'errors' => [
                    'uploaded' => 'Silahkan pilih file bukti pembayaran',
                    'max_size' => 'Ukuran file maksimal 2MB',
                    'is_image' => 'File harus berupa gambar',
                    'mime_in' => 'Format file harus JPG, JPEG, PNG, atau WEBP'
                ]
            ]
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->with('error', $validation->getError('payment_proof'));
        }

        $file = $this->request->getFile('payment_proof');

        if ($file->isValid() && !$file->hasMoved()) {
            // Generate nama file unik
            $newName = $invoice . '_' . date('YmdHis') . '.' . $file->getExtension();
            
            // Pindahkan file ke folder uploads/payment_proofs
            $file->move(WRITEPATH . 'uploads/payment_proofs', $newName);

            // Update transaksi
            $this->transactionModel->update($transaction['id'], [
                'payment_proof' => $newName,
                'status' => 'processing', // Ubah status ke processing setelah upload bukti
                'paid_at' => date('Y-m-d H:i:s')
            ]);

            return redirect()->to(base_url('order/status/' . $invoice))->with('success', 'Bukti pembayaran berhasil diupload. Transaksi sedang diverifikasi.');
        }

        return redirect()->back()->with('error', 'Gagal mengupload file. Silahkan coba lagi.');
    }

    /**
     * Tampilkan gambar bukti pembayaran
     */
    public function viewPaymentProof($filename)
    {
        // Sanitize filename untuk keamanan
        $filename = basename($filename);
        $filepath = WRITEPATH . 'uploads/payment_proofs/' . $filename;

        if (!file_exists($filepath)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('File tidak ditemukan');
        }

        // Determine MIME type
        $extension = pathinfo($filepath, PATHINFO_EXTENSION);
        $mimeTypes = [
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'webp' => 'image/webp',
            'gif' => 'image/gif'
        ];
        $mimeType = $mimeTypes[strtolower($extension)] ?? 'application/octet-stream';

        // Return file sebagai response
        return $this->response
            ->setHeader('Content-Type', $mimeType)
            ->setBody(file_get_contents($filepath));
    }
}