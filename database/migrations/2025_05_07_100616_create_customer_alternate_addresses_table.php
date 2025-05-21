<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('customer_alternate_addresses', function (Blueprint $table) {
            $table->id();
            $table->string('customer_code', 20);
            $table->text('address')->nullable();
            $table->string('contact_person', 100)->nullable();
            $table->string('telephone_no', 30)->nullable();
            $table->string('fax_no', 30)->nullable();
            $table->string('email', 100)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('customer_alternate_addresses');
    }
}; 