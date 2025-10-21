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
        Schema::create('scoring_tools', function (Blueprint $table) {
            $table->id();
            $table->string('code', 10)->unique()->comment('Kode scoring tool');
            $table->string('name', 100)->index()->comment('Nama scoring tool');
            $table->decimal('scores', 8, 1)->default(0.0)->comment('Skor scoring tool');  
            $table->decimal('gap', 8, 1)->default(0.0)->comment('Gap scoring tool');
            $table->string('specification', 255)->nullable()->comment('Spesifikasi teknis');
            $table->text('description')->nullable()->comment('Deskripsi lengkap');
            $table->boolean('is_active')->default(true)->index()->comment('Status aktif');
            $table->string('created_by', 20)->nullable()->comment('Reference to USERCPS.ID');
            $table->string('updated_by', 20)->nullable()->comment('Reference to USERCPS.ID');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scoring_tools');
    }
}; 