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
        // Guard: if CUSTOMER table already exists (legacy DB), skip creation
        if (!Schema::hasTable('CUSTOMER')) {
            Schema::create('CUSTOMER', function (Blueprint $table) {
                $collation = 'SQL_Latin1_General_CP1_CI_AS';

                $table->string('CODE', 50)->collation($collation);
                $table->string('AC_STS', 50)->nullable()->collation($collation);
                $table->string('NAME', 250)->nullable()->collation($collation);
                $table->string('ADDRESS1', 250)->nullable()->collation($collation);
                $table->string('ADDRESS2', 250)->nullable()->collation($collation);
                $table->string('ADDRESS3', 250)->nullable()->collation($collation);
                $table->string('PERSON_CONTACT', 50)->nullable()->collation($collation);
                $table->string('TEL_NO', 100)->nullable()->collation($collation);
                $table->string('FAX_NO', 50)->nullable()->collation($collation);
                $table->string('EMAIL', 50)->nullable()->collation($collation);
                $table->decimal('CREDIT_LIMIT', 15, 2)->nullable();
                $table->decimal('TERM', 15, 2)->nullable();
                $table->string('TYPE', 50)->nullable()->collation($collation);
                $table->string('CURRENCY', 50)->nullable()->collation($collation);
                $table->string('SLM', 50)->nullable()->collation($collation);
                $table->string('AREA', 50)->nullable()->collation($collation);
                $table->string('IND', 50)->nullable()->collation($collation);
                $table->string('GROUP_', 50)->nullable()->collation($collation);
                $table->string('NPWP', 50)->nullable()->collation($collation);
                $table->string('CUST_TYPE', 50)->nullable()->collation($collation);

                // Set primary key
                $table->primary('CODE');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('CUSTOMER');
    }
};