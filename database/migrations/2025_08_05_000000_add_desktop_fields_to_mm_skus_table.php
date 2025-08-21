<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('mm_skus', function (Blueprint $table) {
            // Additional SKU Name fields (Name 1-5)
            $table->string('additional_name_1', 150)->nullable()->after('additional_name');
            $table->string('additional_name_2', 150)->nullable()->after('additional_name_1');
            $table->string('additional_name_3', 150)->nullable()->after('additional_name_2');
            $table->string('additional_name_4', 150)->nullable()->after('additional_name_3');
            $table->string('additional_name_5', 150)->nullable()->after('additional_name_4');
            
            // Valuation Unit
            $table->string('valuation_unit', 10)->nullable()->after('uom');
            $table->decimal('valuation_per_base_unit', 10, 3)->default(1.000)->after('valuation_unit');
            
            // Shipping fields
            $table->integer('days_to_ship')->default(0)->after('rol');
            $table->decimal('units_shipped', 15, 3)->default(0)->after('days_to_ship');
            
            // Location and Report Group
            $table->string('location_code', 20)->nullable()->after('units_shipped');
            $table->string('report_group_code', 10)->nullable()->after('location_code');
            
            // Reorder levels
            $table->decimal('min_level', 15, 3)->default(0)->after('report_group_code');
            $table->decimal('max_level', 15, 3)->default(0)->after('min_level');
            $table->decimal('reorder_level', 15, 3)->default(0)->after('max_level');
            
            // SKU Picture path
            $table->string('sku_picture_path', 500)->nullable()->after('part_number3');
            
            // Foreign key constraints
            $table->foreign('location_code')->references('code')->on('mm_locations')->onDelete('set null');
            $table->foreign('report_group_code')->references('code')->on('mm_report_groups')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mm_skus', function (Blueprint $table) {
            $table->dropForeign(['location_code']);
            $table->dropForeign(['report_group_code']);
            
            $table->dropColumn([
                'additional_name_1',
                'additional_name_2', 
                'additional_name_3',
                'additional_name_4',
                'additional_name_5',
                'valuation_unit',
                'valuation_per_base_unit',
                'days_to_ship',
                'units_shipped',
                'location_code',
                'report_group_code',
                'min_level',
                'max_level',
                'reorder_level',
                'sku_picture_path'
            ]);
        });
    }
};
