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
        Schema::create('CUST_CR', function (Blueprint $table) {
            $collation = 'SQL_Latin1_General_CP1_CI_AS';

            $table->string('NO_', 50)->nullable()->collation($collation); // Note the trailing underscore
            $table->string('AC_NUM', 50)->nullable()->collation($collation);
            $table->string('AC_NAME', 50)->nullable()->collation($collation);
            $table->decimal('AC_LIMIT', 15, 2)->nullable(); // DataType 12
            $table->string('AC_TERMS', 50)->nullable()->collation($collation);
            $table->decimal('ADD_LIMIT', 15, 2)->nullable(); // DataType 12
            $table->string('VALID_UNTIL1', 50)->nullable()->collation($collation);
            $table->string('ADD_TERM', 50)->nullable()->collation($collation);
            $table->string('VALID_UNTIL2', 50)->nullable()->collation($collation);
            $table->string('ADD_M_TERM', 50)->nullable()->collation($collation);
            $table->string('VALID_UNTIL3', 50)->nullable()->collation($collation);
            $table->string('ADD_MARGIN_', 50)->nullable()->collation($collation); // Note the trailing underscore
            $table->string('VALID_UNTIL4', 50)->nullable()->collation($collation);
            $table->string('CREDIT_LIMIT', 50)->nullable()->collation($collation);
            $table->string('OVERDUE_GRACE', 50)->nullable()->collation($collation);
            $table->string('MAX_TERM', 50)->nullable()->collation($collation);
            $table->string('PROFIT_MARGIN', 50)->nullable()->collation($collation);
            $table->string('PENDING_SO', 50)->nullable()->collation($collation);
            $table->string('SO_NOTES', 50)->nullable()->collation($collation);

            // Tambahkan primary key atau index jika diperlukan
            // $table->primary('AC_NUM');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('CUST_CR');
    }
}; 