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
        Schema::create('Customer_Group_Karton', function (Blueprint $table) {
            $collation = 'SQL_Latin1_General_CP1_CI_AS';

            $table->string('Sales', 50)->nullable()->collation($collation);
            $table->string('Entity', 5)->nullable()->collation($collation);
            $table->string('AC_Num', 50)->nullable()->collation($collation);
            $table->string('AC_Name', 250)->nullable()->collation($collation);
            $table->string('Groups', 250)->nullable()->collation($collation);

            // Tambahkan index jika diperlukan
            // $table->index(['AC_Num', 'Groups']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Customer_Group_Karton');
    }
}; 