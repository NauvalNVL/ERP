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
        Schema::create('tax_types', function (Blueprint $table) {
            $table->string('code', 20)->primary();
            $table->string('name', 100);
            $table->char('apply', 1)->default('Y'); // Y or N
            $table->decimal('rate', 8, 2)->default(0.00);
            $table->string('custom_type', 50)->default('N-NIL');
            $table->string('tax_group_code', 20)->nullable(); // Relation to tax_groups
            $table->char('status', 1)->default('A');
            $table->timestamps();
        });

		// Link invoice tax code to tax_types
		if (Schema::hasTable('INV')) {
			Schema::table('INV', function (Blueprint $table) {
				$table->foreign('IV_TAX_CODE')
				      ->references('code')
				      ->on('tax_types');
			});
		}
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
		if (Schema::hasTable('INV')) {
			Schema::table('INV', function (Blueprint $table) {
				$table->dropForeign(['IV_TAX_CODE']);
			});
		}

        Schema::dropIfExists('tax_types');
    }
};
