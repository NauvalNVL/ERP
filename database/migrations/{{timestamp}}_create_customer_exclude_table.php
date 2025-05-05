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
        Schema::create('CUSTOMER_EXCLUDE', function (Blueprint $table) {
            $collation = 'SQL_Latin1_General_CP1_CI_AS';

            $table->string('CUST_CODE', 50)->nullable()->collation($collation);
            $table->string('CUST_NAME', 50)->nullable()->collation($collation);
            $table->string('status', 30)->nullable()->collation($collation);

            // Tambahkan primary key atau index jika diperlukan
            // $table->primary('CUST_CODE');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('CUSTOMER_EXCLUDE');
    }
}; 