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
        Schema::create('DimDate2', function (Blueprint $table) {
            $collation = 'SQL_Latin1_General_CP1_CI_AS';

            $table->integer('DateSK1'); // DataType 3, Not Nullable
            $table->date('Date1'); // DataType 19, Not Nullable
            $table->tinyInteger('Day1'); // DataType 0, Not Nullable
            $table->string('DaySuffix1', 4)->collation($collation); // DataType 27, Not Nullable
            $table->string('DayOfWeek1', 9)->collation($collation); // DataType 27, Not Nullable
            $table->tinyInteger('DOWInMonth1'); // DataType 0, Not Nullable
            $table->smallInteger('DayOfYear1'); // DataType 3, Not Nullable
            $table->tinyInteger('WeekOfYear1'); // DataType 0, Not Nullable
            $table->tinyInteger('WeekOfMonth1'); // DataType 0, Not Nullable
            $table->tinyInteger('WeekOfMonth3'); // DataType 0, Not Nullable - Note: name is WeekOfMonth3
            $table->tinyInteger('Month1'); // DataType 0, Not Nullable
            $table->string('MonthName1', 9)->collation($collation); // DataType 27, Not Nullable
            $table->tinyInteger('Quarter1'); // DataType 0, Not Nullable
            $table->string('QuarterName1', 6)->collation($collation); // DataType 27, Not Nullable
            $table->year('Year1'); // DataType 25, Not Nullable
            $table->string('StandardDate1', 10)->nullable()->collation($collation); // DataType 27, Nullable
            $table->string('HolidayText1', 50)->nullable()->collation($collation); // DataType 27, Nullable

            // Set Primary Key
            $table->primary('DateSK1');

            // Tambahkan index jika diperlukan
            $table->index('Date1');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('DimDate2');
    }
}; 