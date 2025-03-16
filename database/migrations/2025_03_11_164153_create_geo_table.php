<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('geo', function (Blueprint $table) {
            $table->id();
            $table->string('code', 10)->unique()->comment('Kode geo area');
            $table->string('country', 50)->index();
            $table->string('state', 50)->index();
            $table->string('town', 50);
            $table->string('town_section', 50);
            $table->string('area', 50);
            $table->timestamps();
            
            // Tambahkan indeks gabungan untuk pencarian yang lebih cepat
            $table->index(['country', 'state', 'town']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('geo');
    }
};