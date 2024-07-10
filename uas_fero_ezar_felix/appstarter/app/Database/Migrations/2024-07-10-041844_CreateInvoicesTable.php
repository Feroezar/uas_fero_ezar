<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateInvoicesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true
            ],
            'no_faktur' => [
                'type' => 'VARCHAR',
                'constraint' => '20'
            ],
            'tanggal' => [
                'type' => 'DATE'
            ],
            'items' => [
                'type' => 'TEXT'
            ],
            'total' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2'
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true
            ]
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('invoices');
    }

    public function down()
    {
        $this->forge->dropTable('invoices');
    }
}
