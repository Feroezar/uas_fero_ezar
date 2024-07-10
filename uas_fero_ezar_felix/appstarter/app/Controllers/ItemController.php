<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ItemModel;

class ItemController extends BaseController
{
    public function index()
    {
        $itemModel = new ItemModel();
        $data['items'] = $itemModel->findAll();
        
        return view('items/index', $data);
    }

    public function create()
    {
        return view('items/create');
    }

    public function store()
    {
        $itemModel = new ItemModel();

        $data = [
            'kode_buku' => $this->request->getPost('kode_buku'),
            'judul_buku' => $this->request->getPost('judul_buku'),
            'harga_satuan' => $this->request->getPost('harga_satuan'),
            'qty' => $this->request->getPost('qty'),
        ];

        $itemModel->save($data);

        return redirect()->to('/items');
    }

    public function invoice()
    {
        $itemModel = new ItemModel();
        $data['items'] = $itemModel->findAll();

        return view('items/invoice', $data);
    }
}
