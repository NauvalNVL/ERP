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
        Schema::create('CUSTOMER_TYPE', function (Blueprint $table) {
            $collation = 'SQL_Latin1_General_CP1_CI_AS';

            $table->id(); // DataType 3, Not Nullable, assumed primary key
            $table->string('CODE', 50)->nullable()->collation($collation);
            $table->string('CUST_TYPE', 50)->nullable()->collation($collation);

            // Tambahkan index jika diperlukan
            // $table->index('CODE');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('CUSTOMER_TYPE');
    }
}; 