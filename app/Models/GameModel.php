<?php

namespace App\Models;

use CodeIgniter\Model;

// GameModel.php
class GameModel extends Model
{
    protected $table = 'games';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name', 'slug', 'image', 'category', 'is_popular', 'is_active'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function getPopularGames($limit = 8)
    {
        return $this->where('is_popular', true)
                    ->where('is_active', true)
                    ->findAll($limit);
    }

    public function getBySlug($slug)
    {
        return $this->where('slug', $slug)->first();
    }
}