<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="card">
    <div class="card-header bg-primary text-white">
        <h3 class="card-title">Tambah Data Barang</h3>
    </div>
    <div class="card-body">
        <form action="<?= site_url('items/store') ?>" method="post">
            <div class="form-group">
                <label for="kode_buku">Kode Buku</label>
                <input type="text" class="form-control" id="kode_buku" name="kode_buku" required>
            </div>
            <div class="form-group">
                <label for="judul_buku">Judul Buku</label>
                <input type="text" class="form-control" id="judul_buku" name="judul_buku" required>
            </div>
            <div class="form-group">
                <label for="harga_satuan">Harga Satuan</label>
                <input type="number" class="form-control" id="harga_satuan" name="harga_satuan" required>
            </div>
            <div class="form-group">
                <label for="qty">Qty</label>
                <input type="number" class="form-control" id="qty" name="qty" required>
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
