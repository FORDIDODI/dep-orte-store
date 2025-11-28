<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\TransactionModel;
use App\Models\UserModel;
use App\Models\GameModel;

class Dashboard extends BaseController
{
    protected $transactionModel;
    protected $userModel;
    protected $gameModel;

    public function __construct()
    {
        $this->transactionModel = new TransactionModel();
        $this->userModel = new UserModel();
        $this->gameModel = new GameModel();
    }

    public function index()
    {
        // Get statistics
        $todayStats = $this->transactionModel->getTodayStats();
        
        $data = [
            'title' => 'Dashboard Admin',
            'total_transactions_today' => $todayStats['total'],
            'success_transactions' => $todayStats['success'],
            'pending_transactions' => $todayStats['pending'],
            'today_revenue' => $todayStats['revenue']['total_payment'] ?? 0,
            'total_users' => $this->userModel->countAll(),
            'total_games' => $this->gameModel->countAll(),
            'recent_transactions' => $this->transactionModel
                ->select('transactions.*, games.name as game_name, products.name as product_name, users.username')
                ->join('games', 'games.id = transactions.game_id')
                ->join('products', 'products.id = transactions.product_id')
                ->join('users', 'users.id = transactions.user_id', 'left')
                ->orderBy('transactions.created_at', 'DESC')
                ->limit(10)
                ->find()
        ];

        return view('admin/dashboard', $data);
    }
}