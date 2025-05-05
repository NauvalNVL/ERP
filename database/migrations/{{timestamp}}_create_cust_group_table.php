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
        Schema::create('CUST_GROUP', function (Blueprint $table) {
            $collation = 'SQL_Latin1_General_CP1_CI_AS';

            $table->string('No', 12)->nullable()->collation($collation);
            $table->string('Group_ID', 12)->nullable()->collation($collation);
            $table->string('Group_Name', 50)->nullable()->collation($collation);
            $table->string('Currency', 50)->nullable()->collation($collation);
            $table->string('AC', 50)->nullable()->collation($collation);
            $table->string('Name', 50)->nullable()->collation($collation);

            // Tambahkan primary key atau index jika diperlukan
            // $table->primary('Group_ID');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('CUST_GROUP');
    }
}; 