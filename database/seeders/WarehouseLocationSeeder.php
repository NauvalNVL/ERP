<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\WarehouseLocation;

class WarehouseLocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $locations = [
            ['code' => 'A01', 'description' => 'A01.1', 'type' => '1-Normal', 'to_lock_delivery_order' => false, 'to_lock_stock_out_adjustment' => false, 'to_lock_warehouse_transfer' => false],
            ['code' => 'A02', 'description' => 'A01.2', 'type' => '1-Normal', 'to_lock_delivery_order' => false, 'to_lock_stock_out_adjustment' => false, 'to_lock_warehouse_transfer' => false],
            ['code' => 'A03', 'description' => 'A01.3', 'type' => '1-Normal', 'to_lock_delivery_order' => false, 'to_lock_stock_out_adjustment' => false, 'to_lock_warehouse_transfer' => false],
            ['code' => 'A04', 'description' => 'A02.1', 'type' => '1-Normal', 'to_lock_delivery_order' => false, 'to_lock_stock_out_adjustment' => false, 'to_lock_warehouse_transfer' => false],
            ['code' => 'A05', 'description' => 'A02.2', 'type' => '1-Normal', 'to_lock_delivery_order' => false, 'to_lock_stock_out_adjustment' => false, 'to_lock_warehouse_transfer' => false],
            ['code' => 'A06', 'description' => 'A02.3', 'type' => '1-Normal', 'to_lock_delivery_order' => false, 'to_lock_stock_out_adjustment' => false, 'to_lock_warehouse_transfer' => false],
            ['code' => 'A07', 'description' => 'A03.1', 'type' => '1-Normal', 'to_lock_delivery_order' => false, 'to_lock_stock_out_adjustment' => false, 'to_lock_warehouse_transfer' => false],
            ['code' => 'A08', 'description' => 'A03.2', 'type' => '1-Normal', 'to_lock_delivery_order' => false, 'to_lock_stock_out_adjustment' => false, 'to_lock_warehouse_transfer' => false],
            ['code' => 'A09', 'description' => 'A03.3', 'type' => '1-Normal', 'to_lock_delivery_order' => false, 'to_lock_stock_out_adjustment' => false, 'to_lock_warehouse_transfer' => false],
            ['code' => 'A10', 'description' => 'A04.1', 'type' => '1-Normal', 'to_lock_delivery_order' => false, 'to_lock_stock_out_adjustment' => false, 'to_lock_warehouse_transfer' => false],
            ['code' => 'A11', 'description' => 'A04.2', 'type' => '1-Normal', 'to_lock_delivery_order' => false, 'to_lock_stock_out_adjustment' => false, 'to_lock_warehouse_transfer' => false],
            ['code' => 'A12', 'description' => 'A04.3', 'type' => '1-Normal', 'to_lock_delivery_order' => false, 'to_lock_stock_out_adjustment' => false, 'to_lock_warehouse_transfer' => false],
            ['code' => 'A13', 'description' => 'A05.1', 'type' => '1-Normal', 'to_lock_delivery_order' => false, 'to_lock_stock_out_adjustment' => false, 'to_lock_warehouse_transfer' => false],
            ['code' => 'A14', 'description' => 'A05.2', 'type' => '1-Normal', 'to_lock_delivery_order' => false, 'to_lock_stock_out_adjustment' => false, 'to_lock_warehouse_transfer' => false],
            ['code' => 'A15', 'description' => 'A05.3', 'type' => '1-Normal', 'to_lock_delivery_order' => false, 'to_lock_stock_out_adjustment' => false, 'to_lock_warehouse_transfer' => false],
            ['code' => 'A16', 'description' => 'A06.1', 'type' => '1-Normal', 'to_lock_delivery_order' => false, 'to_lock_stock_out_adjustment' => false, 'to_lock_warehouse_transfer' => false],
            ['code' => 'A17', 'description' => 'A06.2', 'type' => '1-Normal', 'to_lock_delivery_order' => false, 'to_lock_stock_out_adjustment' => false, 'to_lock_warehouse_transfer' => false],
            ['code' => 'A18', 'description' => 'A06.3', 'type' => '1-Normal', 'to_lock_delivery_order' => false, 'to_lock_stock_out_adjustment' => false, 'to_lock_warehouse_transfer' => false],
            ['code' => 'A19', 'description' => 'A07.1', 'type' => '1-Normal', 'to_lock_delivery_order' => false, 'to_lock_stock_out_adjustment' => false, 'to_lock_warehouse_transfer' => false],
            ['code' => 'A20', 'description' => 'A07.2', 'type' => '1-Normal', 'to_lock_delivery_order' => false, 'to_lock_stock_out_adjustment' => false, 'to_lock_warehouse_transfer' => false],
        ];

        foreach ($locations as $location) {
            WarehouseLocation::firstOrCreate(['code' => $location['code']], $location);
        }
    }
} 