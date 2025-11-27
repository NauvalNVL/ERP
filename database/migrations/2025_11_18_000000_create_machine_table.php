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
        Schema::create('machine', function (Blueprint $table) {
            $table->id();
            $table->string('machine_code', 20)->unique()->comment('Machine Code - Primary identifier');
            $table->string('machine_name', 100)->comment('Machine Name');
            $table->string('process', 100)->nullable()->comment('Process (e.g., 10 - CORRUGATING, 20 - CONVERTING, 30 - WAREHOUSE)');
            $table->string('sub_process', 100)->nullable()->comment('Sub-Process (e.g., 10 - PRINTER, 20 - DIECUTTER, 30 - FINISHER)');
            $table->string('resource_type', 50)->nullable()->comment('Resource Type (I-InHouse, E-External)');
            $table->string('finisher_type', 50)->nullable()->comment('Finisher Type (X-N/Applicable, Standard, Premium)');
            $table->string('status', 3)->default('Act')->comment('Status: Act (Active), Obs (Obsolete)');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('machine');
    }
};
