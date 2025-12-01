<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UpdateGameImagesSeeder extends Seeder
{
    /**
     * Seeder khusus untuk memperbarui gambar game yang sudah ada
     * Gunakan seeder ini jika sudah ada data game di database dan ingin update gambarnya
     */
    public function run()
    {
        // Mapping slug game dengan nama file gambar yang sudah diupload
        $gameImages = [
            'mobile-legends' => '1764556145_1a79696c188e85b80c5c.jpg',
            'free-fire' => '1764556137_f56a07582c5aa76e7019.jpg',
            'pubg-mobile' => '1764556127_18996e333f4dde6de30a.webp',
            'genshin-impact' => '1764556089_247c1982be4da206ca7c.webp',
            'codm' => '1764556019_bd216a282167ee0244db.webp',
            'valorant' => '1764556011_802812b7ae2098b9c8fd.jpg',
            'coc' => '1764555974_c73a4e19623eaf4fcec5.webp',
        ];

        $builder = $this->db->table('games');
        
        foreach ($gameImages as $slug => $imageName) {
            // Update gambar berdasarkan slug
            $builder->where('slug', $slug)
                   ->update(['image' => $imageName]);
            
            echo "Updated image for game: {$slug} -> {$imageName}\n";
        }
        
        echo "Game images updated successfully!\n";
    }
}

