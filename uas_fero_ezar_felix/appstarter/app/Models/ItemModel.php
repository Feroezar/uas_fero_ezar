<?php

namespace App\Models;

use CodeIgniter\Model;

class ItemModel extends Model
{
    protected $table = 'items';
    protected $primaryKey = 'id';
    protected $allowedFields = ['kode_buku', 'judul_buku', 'harga_satuan', 'qty'];
}
