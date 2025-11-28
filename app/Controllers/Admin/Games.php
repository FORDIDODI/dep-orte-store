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
            'title' => 'Kelola Game',
            'games' => $this->gameModel->orderBy('name', 'ASC')->findAll()
        ];

        return view('admin/games/index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Tambah Game Baru'
        ];

        return view('admin/games/create', $data);
    }

    public function store()
    {
        $validation = \Config\Services::validation();

        $rules = [
            'name' => 'required|min_length[3]',
            'slug' => 'required|is_unique[games.slug]',
            'category' => 'required',
            'image' => 'uploaded[image]|max_size[image,2048]|is_image[image]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $image = $this->request->getFile('image');
        $imageName = $image->getRandomName();
        $image->move(WRITEPATH . '../public/assets/images/games', $imageName);

        $data = [
            'name' => $this->request->getPost('name'),
            'slug' => $this->request->getPost('slug'),
            'category' => $this->request->getPost('category'),
            'image' => $imageName,
            'is_popular' => $this->request->getPost('is_popular') ? 1 : 0,
            'is_active' => 1
        ];

        $this->gameModel->insert($data);

        return redirect()->to(base_url('admin/games'))->with('success', 'Game berhasil ditambahkan');
    }

    public function edit($id)
    {
        $game = $this->gameModel->find($id);

        if (!$game) {
            return redirect()->to(base_url('admin/games'))->with('error', 'Game tidak ditemukan');
        }

        $data = [
            'title' => 'Edit Game',
            'game' => $game
        ];

        return view('admin/games/edit', $data);
    }

    public function update($id)
    {
        $game = $this->gameModel->find($id);

        if (!$game) {
            return redirect()->to(base_url('admin/games'))->with('error', 'Game tidak ditemukan');
        }

        $validation = \Config\Services::validation();

        $rules = [
            'name' => 'required|min_length[3]',
            'slug' => "required|is_unique[games.slug,id,$id]",
            'category' => 'required'
        ];

        if ($this->request->getFile('image')->isValid()) {
            $rules['image'] = 'max_size[image,2048]|is_image[image]';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'slug' => $this->request->getPost('slug'),
            'category' => $this->request->getPost('category'),
            'is_popular' => $this->request->getPost('is_popular') ? 1 : 0,
            'is_active' => $this->request->getPost('is_active') ? 1 : 0
        ];

        $image = $this->request->getFile('image');
        if ($image->isValid()) {
            // Delete old image
            if (file_exists(WRITEPATH . '../public/assets/images/games/' . $game['image'])) {
                unlink(WRITEPATH . '../public/assets/images/games/' . $game['image']);
            }

            $imageName = $image->getRandomName();
            $image->move(WRITEPATH . '../public/assets/images/games', $imageName);
            $data['image'] = $imageName;
        }

        $this->gameModel->update($id, $data);

        return redirect()->to(base_url('admin/games'))->with('success', 'Game berhasil diupdate');
    }

    public function delete($id)
    {
        $game = $this->gameModel->find($id);

        if (!$game) {
            return $this->response->setJSON(['success' => false, 'message' => 'Game tidak ditemukan']);
        }

        // Delete image
        if (file_exists(WRITEPATH . '../public/assets/images/games/' . $game['image'])) {
            unlink(WRITEPATH . '../public/assets/images/games/' . $game['image']);
        }

        $this->gameModel->delete($id);

        return $this->response->setJSON(['success' => true, 'message' => 'Game berhasil dihapus']);
    }
}