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
        Schema::create('DimDate', function (Blueprint $table) {
            $collation = 'SQL_Latin1_General_CP1_CI_AS';

            $table->integer('DateSK'); // DataType 3, Not Nullable
            $table->date('Date'); // DataType 19, Not Nullable
            $table->tinyInteger('Day'); // DataType 0, Not Nullable
            $table->string('DaySuffix', 4)->collation($collation); // DataType 27, Not Nullable
            $table->string('DayOfWeek', 9)->collation($collation); // DataType 27, Not Nullable
            $table->tinyInteger('DOWInMonth'); // DataType 0, Not Nullable
            $table->smallInteger('DayOfYear'); // DataType 3 (using smallInteger for < 367), Not Nullable
            $table->tinyInteger('WeekOfYear'); // DataType 0, Not Nullable
            $table->tinyInteger('WeekOfMonth'); // DataType 0, Not Nullable
            $table->tinyInteger('WeekOfMonth2'); // DataType 0, Not Nullable
            $table->tinyInteger('Month'); // DataType 0, Not Nullable
            $table->string('MonthName', 9)->collation($collation); // DataType 27, Not Nullable
            $table->tinyInteger('Quarter'); // DataType 0, Not Nullable
            $table->string('QuarterName', 6)->collation($collation); // DataType 27, Not Nullable
            $table->year('Year'); // DataType 25, Not Nullable
            $table->string('StandardDate', 10)->nullable()->collation($collation); // DataType 27, Nullable
            $table->string('HolidayText', 50)->nullable()->collation($collation); // DataType 27, Nullable

            // Set Primary Key
            $table->primary('DateSK');
            
            // Tambahkan index jika diperlukan
            $table->index('Date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('DimDate');
    }
}; 