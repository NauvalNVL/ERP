<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('customer_groups', function (Blueprint $table) {
            $table->string('group_code', 20)->primary();
            $table->string('description', 100);
            $table->string('created_by', 20)->nullable(); // Reference to USERCPS.ID
            $table->string('updated_by', 20)->nullable(); // Reference to USERCPS.ID
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('customer_groups');
    }
}; 