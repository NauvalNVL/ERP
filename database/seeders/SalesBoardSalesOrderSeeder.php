<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SalesBoardSalesOrder;
use App\Models\UpdateCustomerAccount;
use App\Models\Salesperson;
use App\Models\ProductDesign;
use App\Models\PaperFlute;
use App\Models\PaperQuality;
use App\Models\PaperSize;
use Carbon\Carbon;

class SalesBoardSalesOrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sample data directly without relying on existing tables

        $sampleOrders = [
            [
                'current_period' => 9,
                'update_period' => 9,
                'forward_period' => 1,
                'backward_period' => 1,
                'customer_p_order_number' => 'PO-001',
                'p_order_date' => Carbon::now()->subDays(5),
                'product_design' => 'CB-P',
                'flute' => 'AB',
                'so_b_quality' => 'AGBK125',
                'wo_b_quality' => 'AGBK125',
                'board_size_length' => '70',
                'board_size_width' => '5',
                'sheet_size_length' => '70',
                'sheet_size_width' => '5',
                'paper_size' => '700',
                's_tool' => '1',
                'corr_out' => '7',
                'conv_out' => '50',
                'area_per_pcs' => 0.0001,
                'l_meter' => 0,
                'order_quantity' => 70,
                'currency' => 'IDR',
                'exchange_rate' => 0.0000,
                'lot_number' => '50',
                'pcs_per_bundle' => 10,
                'sales_tax' => true,
                'order_group' => 'Sales',
                'order_type' => 'S1-Sales (SO-Corr-Conv-FG-DO-IV)',
                'remark' => 'Sample order for testing',
                'instruction_1' => 'Handle with care',
                'instruction_2' => 'Deliver on time',
                'unit' => 'P-Price per Piece - Gross M2',
                'unit_price' => 0.0000,
                'status' => 'Draft'
            ],
            [
                'current_period' => 9,
                'update_period' => 9,
                'forward_period' => 1,
                'backward_period' => 1,
                'customer_p_order_number' => 'PO-002',
                'p_order_date' => Carbon::now()->subDays(3),
                'product_design' => 'CB-F',
                'flute' => 'BC',
                'so_b_quality' => 'AGBS125',
                'wo_b_quality' => 'AGBS125',
                'board_size_length' => '80',
                'board_size_width' => '6',
                'sheet_size_length' => '80',
                'sheet_size_width' => '6',
                'paper_size' => '800',
                's_tool' => '2',
                'corr_out' => '8',
                'conv_out' => '60',
                'area_per_pcs' => 0.0002,
                'l_meter' => 0,
                'order_quantity' => 100,
                'currency' => 'IDR',
                'exchange_rate' => 0.0000,
                'lot_number' => '60',
                'pcs_per_bundle' => 20,
                'sales_tax' => false,
                'order_group' => 'Non-Sales',
                'order_type' => 'N1-NonSales (SO-Corr-Conv-FG-DO)',
                'remark' => 'Internal order',
                'instruction_1' => 'Quality check required',
                'instruction_2' => 'Special packaging',
                'unit' => 'Q-Price per Piece - Trimmed M2',
                'unit_price' => 0.0000,
                'status' => 'Active'
            ],
            [
                'current_period' => 9,
                'update_period' => 9,
                'forward_period' => 2,
                'backward_period' => 1,
                'customer_p_order_number' => 'PO-003',
                'p_order_date' => Carbon::now()->subDays(1),
                'product_design' => 'CB-S',
                'flute' => 'CF',
                'so_b_quality' => 'AGML110',
                'wo_b_quality' => 'AGML110',
                'board_size_length' => '90',
                'board_size_width' => '7',
                'sheet_size_length' => '90',
                'sheet_size_width' => '7',
                'paper_size' => '900',
                's_tool' => '3',
                'corr_out' => '9',
                'conv_out' => '70',
                'area_per_pcs' => 0.0003,
                'l_meter' => 0,
                'order_quantity' => 150,
                'currency' => 'USD',
                'exchange_rate' => 15000.0000,
                'lot_number' => '70',
                'pcs_per_bundle' => 25,
                'sales_tax' => true,
                'order_group' => 'Sales',
                'order_type' => 'S2-Sales (SO-DO-IV Kanban/JIT)',
                'remark' => 'Export order',
                'instruction_1' => 'Export documentation required',
                'instruction_2' => 'International shipping',
                'unit' => 'P-Price per Piece - Gross M2',
                'unit_price' => 0.0000,
                'status' => 'Draft'
            ]
        ];

        foreach ($sampleOrders as $index => $orderData) {
            // Use sample customer and salesperson codes
            $orderData['customer_code'] = '000211-08';
            $orderData['salesperson_code'] = 'S111';
            $orderData['so_number'] = 'SB-SO-' . str_pad($index + 1, 6, '0', STR_PAD_LEFT);

            SalesBoardSalesOrder::create($orderData);
        }

        $this->command->info('Sales Board Sales Order sample data created successfully!');
    }
}
