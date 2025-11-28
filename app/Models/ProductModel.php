<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends Model
{
    protected $table = 'products';
    protected $primaryKey = 'id';
    protected $allowedFields = ['game_id', 'name', 'description', 'price', 'discount_price', 'category', 'is_popular', 'is_active'];
    protected $useTimestamps = true;

    public function getByGameId($gameId)
    {
        return $this->where('game_id', $gameId)
                    ->where('is_active', true)
                    ->orderBy('price', 'ASC')
                    ->findAll();
    }

    public function getPopularByGameId($gameId)
    {
        return $this->where('game_id', $gameId)
                    ->where('is_popular', true)
                    ->where('is_active', true)
                    ->findAll();
    }

    public function getWithGame($productId)
    {
        return $this->select('products.*, games.name as game_name, games.slug as game_slug')
                    ->join('games', 'games.id = products.game_id')
                    ->find($productId);
    }
}
