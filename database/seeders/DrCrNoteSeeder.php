<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DrCrNote;
use Carbon\Carbon;

class DrCrNoteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $notes = [
            [
                'note_number' => 'DR2025010001',
                'note_type' => 'DR',
                'reference_document' => 'INV-2025-001',
                'reference_type' => 'Invoice',
                'customer_code' => 'CUST001',
                'customer_name' => 'PT. Customer Pertama',
                'amount' => 1500000.00,
                'description' => 'Debit note untuk tambahan biaya pengiriman',
                'reason' => 'Kesalahan perhitungan biaya pengiriman',
                'status' => 'Approved',
                'note_date' => '2025-01-15',
                'due_date' => '2025-02-15',
                'currency' => 'IDR',
                'exchange_rate' => 1.0000,
                'created_by' => 'system',
                'approved_by' => 'system',
                'approved_at' => '2025-01-16 10:30:00',
                'approval_notes' => 'Disetujui setelah verifikasi',
                'is_active' => true,
            ],
            [
                'note_number' => 'CR2025010001',
                'note_type' => 'CR',
                'reference_document' => 'INV-2025-002',
                'reference_type' => 'Invoice',
                'customer_code' => 'CUST002',
                'customer_name' => 'PT. Customer Kedua',
                'amount' => 750000.00,
                'description' => 'Credit note untuk retur barang',
                'reason' => 'Retur barang karena cacat',
                'status' => 'Posted',
                'note_date' => '2025-01-10',
                'due_date' => '2025-02-10',
                'currency' => 'IDR',
                'exchange_rate' => 1.0000,
                'created_by' => 'system',
                'approved_by' => 'system',
                'approved_at' => '2025-01-12 14:20:00',
                'approval_notes' => 'Disetujui sesuai kebijakan retur',
                'is_active' => true,
            ],
            [
                'note_number' => 'DR2025010002',
                'note_type' => 'DR',
                'reference_document' => 'PO-2025-003',
                'reference_type' => 'Purchase Order',
                'vendor_code' => 'VEND001',
                'vendor_name' => 'PT. Vendor Pertama',
                'amount' => 2500000.00,
                'description' => 'Debit note untuk denda keterlambatan',
                'reason' => 'Keterlambatan pengiriman sesuai kontrak',
                'status' => 'Draft',
                'note_date' => '2025-01-20',
                'due_date' => '2025-02-20',
                'currency' => 'IDR',
                'exchange_rate' => 1.0000,
                'created_by' => 'system',
                'is_active' => true,
            ],
            [
                'note_number' => 'CR2025010002',
                'note_type' => 'CR',
                'reference_document' => 'INV-2025-003',
                'reference_type' => 'Invoice',
                'customer_code' => 'CUST001',
                'customer_name' => 'PT. Customer Pertama',
                'amount' => 500000.00,
                'description' => 'Credit note untuk diskon loyalitas',
                'reason' => 'Diskon 10% untuk pelanggan loyal',
                'status' => 'Pending',
                'note_date' => '2025-01-18',
                'due_date' => '2025-02-18',
                'currency' => 'IDR',
                'exchange_rate' => 1.0000,
                'created_by' => 'system',
                'is_active' => true,
            ],
            [
                'note_number' => 'DR2025010003',
                'note_type' => 'DR',
                'reference_document' => 'INV-2025-004',
                'reference_type' => 'Invoice',
                'customer_code' => 'CUST003',
                'customer_name' => 'PT. Customer Ketiga',
                'amount' => 1200000.00,
                'description' => 'Debit note untuk tambahan biaya handling',
                'reason' => 'Biaya handling khusus untuk pengiriman cepat',
                'status' => 'Rejected',
                'note_date' => '2025-01-22',
                'due_date' => '2025-02-22',
                'currency' => 'IDR',
                'exchange_rate' => 1.0000,
                'created_by' => 'system',
                'approved_by' => 'system',
                'approved_at' => '2025-01-23 09:15:00',
                'approval_notes' => 'Ditolak karena tidak sesuai kebijakan',
                'is_active' => true,
            ],
        ];

        foreach ($notes as $noteData) {
            DrCrNote::create($noteData);
        }
    }
} 