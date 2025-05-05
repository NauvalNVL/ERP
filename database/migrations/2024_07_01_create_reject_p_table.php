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
        Schema::create('reject_p', function (Blueprint $table) {
            $table->id();
            $table->string('No', 50);
            $table->string('Reject_Reff', 50);
            $table->string('Reject_Sts', 50);
            $table->string('Entry_Date', 50);
            $table->string('Reject_Code', 50);
            $table->string('Status', 50);
            $table->string('Grouping', 50);
            $table->string('DO_NUM', 50);
            $table->string('SO_NUM', 50);
            $table->string('Customer_Code', 50);
            $table->string('Customer_Name', 50);
            $table->string('MCS', 50);
            $table->string('Model', 50);
            $table->string('Comp', 50);
            $table->string('P_Design', 50);
            $table->float('Reject_Qty');
            $table->float('MT_Per_Pcs');
            $table->float('M2_Per_Pcs');
            $table->float('SO_Up');
            $table->string('SO_Curr', 10);
            $table->string('SO_Ex_Rate', 20);
            $table->string('Reject_Amount', 20);
            $table->float('Reject_MT');
            $table->float('Reject_M2');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reject_p');
    }
}; 