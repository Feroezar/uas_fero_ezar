<?php

namespace App\Controllers;

use App\Models\ItemModel;
use App\Models\InvoiceModel;

class Order extends BaseController
{
    public function index()
    {
        $itemModel = new ItemModel();
        $data['items'] = $itemModel->findAll();
        
        return view('order_form', $data);
    }

    public function saveInvoice()
    {
        $invoiceModel = new InvoiceModel();

        // Generate invoice number
        $lastInvoice = $invoiceModel->orderBy('id', 'DESC')->first();
        $lastInvoiceNumber = $lastInvoice ? intval(substr($lastInvoice['no_faktur'], -4)) : 0;
        $newInvoiceNumber = sprintf('%04d', $lastInvoiceNumber + 1);
        $no_faktur = date('Ymd') . $newInvoiceNumber;

        // Save invoice
        $data = [
            'no_faktur' => $no_faktur,
            'tanggal' => $this->request->getPost('tanggal'),
            'items' => json_encode($this->request->getPost('items')),
            'total' => $this->request->getPost('total')
        ];

        $invoiceModel->save($data);

        return $this->response->setJSON(['status' => 'success', 'message' => 'Invoice saved successfully']);
    }
}
