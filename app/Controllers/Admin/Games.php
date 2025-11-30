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
        if ($this->request->getFile('image')->isValid()) {
            $rules['image'] = 'uploaded[image]|max_size[image,2048]|is_image[image]';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Validasi gagal! ' . implode(', ', $this->validator->getErrors()));
        }

        // Handle image upload
        $imageName = 'default.jpg';
        $image = $this->request->getFile('image');
        
        if ($image->isValid() && !$image->hasMoved()) {
            $imageName = $image->getRandomName();
            $image->move(FCPATH . 'assets/images/games', $imageName);
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
        if ($this->request->getFile('image')->isValid()) {
            $rules['image'] = 'max_size[image,2048]|is_image[image]';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Validasi gagal! ' . implode(', ', $this->validator->getErrors()));
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
        $image = $this->request->getFile('image');
        
        if ($image->isValid() && !$image->hasMoved()) {
            // Delete old image (except default)
            if ($game['image'] != 'default.jpg' && file_exists(FCPATH . 'assets/images/games/' . $game['image'])) {
                unlink(FCPATH . 'assets/images/games/' . $game['image']);
            }

            // Upload new image
            $imageName = $image->getRandomName();
            $image->move(FCPATH . 'assets/images/games', $imageName);
            $data['image'] = $imageName;
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
        if ($game['image'] != 'default.jpg' && file_exists(FCPATH . 'assets/images/games/' . $game['image'])) {
            unlink(FCPATH . 'assets/images/games/' . $game['image']);
        }

        $this->gameModel->delete($id);

        return $this->response->setJSON(['success' => true, 'message' => 'Game berhasil dihapus']);
    }
}

?>