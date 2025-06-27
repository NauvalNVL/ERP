<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MmTransactionType;

class MmTransactionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Initial transaction types data
        $transactionTypes = [
            [
                'code' => 'PO',
                'name' => 'Purchase Order',
                'group' => 'PO'
            ],
            [
                'code' => 'PR',
                'name' => 'Purchase Requisition',
                'group' => 'PO'
            ],
            [
                'code' => 'RC',
                'name' => 'Receive Note',
                'group' => 'IC'
            ],
            [
                'code' => 'RT',
                'name' => 'Return Note',
                'group' => 'IC'
            ],
            [
                'code' => 'IS',
                'name' => 'Issue Note',
                'group' => 'IC'
            ],
            [
                'code' => 'MI',
                'name' => 'Stock In',
                'group' => 'IC'
            ],
            [
                'code' => 'MO',
                'name' => 'Stock Out',
                'group' => 'IC'
            ],
            [
                'code' => 'LT',
                'name' => 'Location Transfer',
                'group' => 'IC'
            ],
            [
                'code' => 'DN',
                'name' => 'Debit Note',
                'group' => 'CX'
            ],
            [
                'code' => 'CN',
                'name' => 'Credit Note',
                'group' => 'CX'
            ],
        ];

        // Insert or update transaction types
        foreach ($transactionTypes as $data) {
            MmTransactionType::updateOrCreate(
                ['code' => $data['code']],
                $data
            );
        }
    }
} 