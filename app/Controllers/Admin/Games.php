<?php
namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\GameModel;

class Games extends BaseController
{
    protected $gameModel;

    public function __construct()
    {
        $this->gameModel = new GameModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Kelola Games',
            'games' => $this->gameModel->orderBy('id', 'DESC')->findAll()
        ];

        return view('admin/games/index', $data);
    }

    public function store()
    {
        // Validation
        $rules = [
            'name' => 'required|min_length[3]',
            'slug' => 'required|is_unique[games.slug]',
            'category' => 'required',
        ];

        // Validasi image jika diupload
        $image = $this->request->getFile('image');
        if ($image && $image->isValid() && !$image->hasMoved()) {
            $rules['image'] = 'uploaded[image]|max_size[image,2048]|is_image[image]|ext_in[image,jpg,jpeg,png,webp]';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Validasi gagal! ' . implode(', ', $this->validator->getErrors()));
        }

        // Ensure directory exists
        $uploadPath = FCPATH . 'assets/images/games';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        // Handle image upload
        $imageName = 'default.jpg';
        if ($image && $image->isValid() && !$image->hasMoved()) {
            $imageName = $image->getRandomName();
            if (!$image->move($uploadPath, $imageName)) {
                return redirect()->back()->withInput()->with('error', 'Gagal mengupload gambar!');
            }
        }

        // Insert data
        $data = [
            'name' => $this->request->getPost('name'),
            'slug' => $this->request->getPost('slug'),
            'category' => $this->request->getPost('category'),
            'description' => $this->request->getPost('description'),
            'image' => $imageName,
            'is_popular' => $this->request->getPost('is_popular') ? 1 : 0,
            'is_active' => $this->request->getPost('is_active') ? 1 : 0
        ];

        $this->gameModel->insert($data);

        return redirect()->to(base_url('admin/games'))->with('success', 'Game berhasil ditambahkan!');
    }

    public function update($id)
    {
        $game = $this->gameModel->find($id);

        if (!$game) {
            return redirect()->to(base_url('admin/games'))->with('error', 'Game tidak ditemukan');
        }

        // Validation
        $rules = [
            'name' => 'required|min_length[3]',
            'slug' => "required|is_unique[games.slug,id,$id]",
            'category' => 'required'
        ];

        // Validasi image jika diupload
        $image = $this->request->getFile('image');
        if ($image && $image->isValid() && !$image->hasMoved()) {
            $rules['image'] = 'max_size[image,2048]|is_image[image]|ext_in[image,jpg,jpeg,png,webp]';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Validasi gagal! ' . implode(', ', $this->validator->getErrors()));
        }

        // Ensure directory exists
        $uploadPath = FCPATH . 'assets/images/games';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        // Update data
        $data = [
            'name' => $this->request->getPost('name'),
            'slug' => $this->request->getPost('slug'),
            'category' => $this->request->getPost('category'),
            'description' => $this->request->getPost('description'),
            'is_popular' => $this->request->getPost('is_popular') ? 1 : 0,
            'is_active' => $this->request->getPost('is_active') ? 1 : 0
        ];

        // Handle image upload
        if ($image && $image->isValid() && !$image->hasMoved()) {
            // Delete old image (except default)
            $oldImagePath = FCPATH . 'assets/images/games/' . $game['image'];
            if ($game['image'] != 'default.jpg' && file_exists($oldImagePath)) {
                @unlink($oldImagePath);
            }

            // Upload new image
            $imageName = $image->getRandomName();
            if ($image->move($uploadPath, $imageName)) {
                $data['image'] = $imageName;
            } else {
                return redirect()->back()->withInput()->with('error', 'Gagal mengupload gambar!');
            }
        }

        $this->gameModel->update($id, $data);

        return redirect()->to(base_url('admin/games'))->with('success', 'Game berhasil diupdate!');
    }

    public function delete($id)
    {
        $game = $this->gameModel->find($id);

        if (!$game) {
            return $this->response->setJSON(['success' => false, 'message' => 'Game tidak ditemukan']);
        }

        // Delete image (except default)
        $imagePath = FCPATH . 'assets/images/games/' . $game['image'];
        if ($game['image'] != 'default.jpg' && file_exists($imagePath)) {
            @unlink($imagePath);
        }

        $this->gameModel->delete($id);

        return $this->response->setJSON(['success' => true, 'message' => 'Game berhasil dihapus']);
    }
}

?>