<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-md-3">
        <div class="card">
            <div class="card-header">Informasi Pemesanan Buku</div>
            <div class="card-body">
                <form id="order-form">
                    <div class="form-group">
                        <label for="no_faktur">No. Faktur</label>
                        <input type="text" class="form-control" id="no_faktur" value="20180500033" readonly>
                    </div>
                    <div class="form-group">
                        <label for="tanggal">Tanggal</label>
                        <input type="date" class="form-control" id="tanggal" value="2018-05-22">
                    </div>
                    <div class="form-group">
                        <label for="cabang">Cabang</label>
                        <select class="form-control" id="cabang">
                            <option>-- Umum --</option>
                        </select>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-9">
        <div class="card">
            <div class="card-header">Faktur Pesanan</div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Kode Buku</th>
                            <th>Judul Buku</th>
                            <th>Harga Satuan</th>
                            <th>Qty</th>
                            <th>Sub Total</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="order-items">
                        <tr>
                            <td>1</td>
                            <td>
                                <select class="form-control kode-buku" onchange="updateDetails(this)">
                                    <option value="">-- pilih kode buku --</option>
                                    <?php foreach ($items as $item): ?>
                                        <option value="<?= $item['kode_buku'] ?>" data-title="<?= $item['judul_buku'] ?>" data-price="<?= $item['harga_satuan'] ?>"><?= $item['kode_buku'] ?> - <?= $item['judul_buku'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td class="judul-buku"></td>
                            <td class="harga-satuan"></td>
                            <td><input type="number" class="form-control qty" oninput="updateSubtotal(this)"></td>
                            <td class="sub-total"></td>
                            <td><button class="btn btn-success btn-sm" onclick="addItemRow(this)">&plus;</button></td>
                        </tr>
                    </tbody>
                </table>
                <div class="row">
                    <div class="col-md-6">
                        <button class="btn btn-primary" onclick="resetOrderForm()">Transaksi Batal</button>
                        <button class="btn btn-success">Transaksi Baru</button>
                    </div>
                    <div class="col-md-6 text-right">
                        <h5>Total: Rp. <span id="grand-total">0</span></h5>
                        <button class="btn btn-info" onclick="generateInvoice()">Generate Invoice</button>
                        <button class="btn btn-warning" onclick="saveInvoice()">Save Invoice</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="invoice" style="display:none; padding: 20px; border: 1px solid #ccc; margin-top: 20px;">
    <h2>Invoice</h2>
    <p>No. Faktur: <span id="invoice-no-faktur"></span></p>
    <p>Tanggal: <span id="invoice-tanggal"></span></p>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Kode Buku</th>
                <th>Judul Buku</th>
                <th>Harga Satuan</th>
                <th>Qty</th>
                <th>Sub Total</th>
            </tr>
        </thead>
        <tbody id="invoice-items">
        </tbody>
    </table>
    <h4>Total: Rp. <span id="invoice-grand-total"></span></h4>
    <button class="btn btn-primary" onclick="printInvoice()">Print Invoice</button>
</div>

<script>
    function updateDetails(selectElement) {
        const selectedOption = selectElement.options[selectElement.selectedIndex];
        const row = selectElement.closest('tr');
        const titleCell = row.querySelector('.judul-buku');
        const priceCell = row.querySelector('.harga-satuan');

        const title = selectedOption.getAttribute('data-title');
        const price = selectedOption.getAttribute('data-price');

        titleCell.textContent = title;
        priceCell.textContent = price;
        updateSubtotal(row.querySelector('.qty'));
    }

    function updateSubtotal(qtyElement) {
        const row = qtyElement.closest('tr');
        const price = parseFloat(row.querySelector('.harga-satuan').textContent) || 0;
        const qty = parseFloat(qtyElement.value) || 0;
        const subTotal = price * qty;

        row.querySelector('.sub-total').textContent = subTotal.toFixed(2);
        updateGrandTotal();
    }

    function updateGrandTotal() {
        let grandTotal = 0;
        document.querySelectorAll('.sub-total').forEach(subTotalCell => {
            grandTotal += parseFloat(subTotalCell.textContent) || 0;
        });
        document.getElementById('grand-total').textContent = grandTotal.toFixed(2);
    }

    function addItemRow(button) {
        const tableBody = document.getElementById('order-items');
        const rowCount = tableBody.rows.length + 1;
        const newRow = tableBody.rows[0].cloneNode(true);

        newRow.querySelector('td:first-child').textContent = rowCount;
        newRow.querySelectorAll('select, input').forEach(element => element.value = '');
        newRow.querySelectorAll('.judul-buku, .harga-satuan, .sub-total').forEach(cell => cell.textContent = '');
        newRow.querySelector('.kode-buku').addEventListener('change', function() { updateDetails(this); });
        newRow.querySelector('.qty').addEventListener('input', function() { updateSubtotal(this); });

        tableBody.appendChild(newRow);
    }

    function resetOrderForm() {
        const tableBody = document.getElementById('order-items');
        const firstRow = tableBody.rows[0].cloneNode(true);

        while (tableBody.firstChild) {
            tableBody.removeChild(tableBody.firstChild);
        }

        firstRow.querySelectorAll('select, input').forEach(element => element.value = '');
        firstRow.querySelectorAll('.judul-buku, .harga-satuan, .sub-total').forEach(cell => cell.textContent = '');
        tableBody.appendChild(firstRow);

        document.getElementById('grand-total').textContent = '0';
    }

    function generateInvoice() {
        const invoiceNoFaktur = document.getElementById('no_faktur').value;
        const invoiceTanggal = document.getElementById('tanggal').value;
        const grandTotal = document.getElementById('grand-total').textContent;

        document.getElementById('invoice-no-faktur').textContent = invoiceNoFaktur;
        document.getElementById('invoice-tanggal').textContent = invoiceTanggal;
        document.getElementById('invoice-grand-total').textContent = grandTotal;

        const invoiceItems = document.getElementById('invoice-items');
        invoiceItems.innerHTML = '';

        document.querySelectorAll('#order-items tr').forEach((row, index) => {
            const kodeBuku = row.querySelector('.kode-buku').value;
            const judulBuku = row.querySelector('.judul-buku').textContent;
            const hargaSatuan = row.querySelector('.harga-satuan').textContent;
            const qty = row.querySelector('.qty').value;
            const subTotal = row.querySelector('.sub-total').textContent;

            const newRow = document.createElement('tr');
            newRow.innerHTML = `
                <td>${index + 1}</td>
                <td>${kodeBuku}</td>
                <td>${judulBuku}</td>
                <td>${hargaSatuan}</td>
                <td>${qty}</td>
                <td>${subTotal}</td>
            `;
            invoiceItems.appendChild(newRow);
        });

        document.getElementById('invoice').style.display = 'block';
    }

    function saveInvoice() {
        const noFaktur = document.getElementById('no_faktur').value;
        const tanggal = document.getElementById('tanggal').value;
        const items = [];
        document.querySelectorAll('#order-items tr').forEach(row => {
            const kodeBuku = row.querySelector('.kode-buku').value;
            const judulBuku = row.querySelector('.judul-buku').textContent;
            const hargaSatuan = row.querySelector('.harga-satuan').textContent;
            const qty = row.querySelector('.qty').value;
            const subTotal = row.querySelector('.sub-total').textContent;
            if (kodeBuku) {
                items.push({ kodeBuku, judulBuku, hargaSatuan, qty, subTotal });
            }
        });
        const total = document.getElementById('grand-total').textContent;

        fetch('<?= base_url('order/saveInvoice') ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                no_faktur: noFaktur,
                tanggal: tanggal,
                items: items,
                total: total
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                alert('Invoice saved successfully');
                resetOrderForm();
                document.getElementById('invoice').style.display = 'none';
            } else {
                alert('Failed to save invoice');
            }
        });
    }

    function printInvoice() {
        const printContents = document.getElementById('invoice').innerHTML;
        const originalContents = document.body.innerHTML;

        document.body.innerHTML = printContents;

        window.print();

        document.body.innerHTML = originalContents;
        location.reload();
    }
</script>

<?= $this->endSection() ?>
