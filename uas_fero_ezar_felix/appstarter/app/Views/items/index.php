<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="card">
    <div class="card-header bg-primary text-white">
        <h3 class="card-title">Daftar Barang</h3>
    </div>
    <div class="card-body">
        <a href="<?= site_url('items/create') ?>" class="btn btn-primary mb-3">Tambah Barang</a>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Kode Buku</th>
                    <th>Judul Buku</th>
                    <th>Harga Satuan</th>
                    <th>Qty</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($items)): ?>
                    <?php foreach($items as $key => $item): ?>
                        <tr>
                            <td><?= $key + 1 ?></td>
                            <td><?= $item['kode_buku'] ?></td>
                            <td><?= $item['judul_buku'] ?></td>
                            <td><?= $item['harga_satuan'] ?></td>
                            <td><?= $item['qty'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center">Tidak ada data</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>
