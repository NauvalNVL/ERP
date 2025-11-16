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
        Schema::create('tax_group_items', function (Blueprint $table) {
            $table->id();
            $table->string('tax_group_code', 20);
            $table->string('tax_type_code', 20);
            $table->unsignedTinyInteger('sequence')->default(1);
            $table->char('apply', 1)->default('Y');
            $table->char('include', 1)->default('N');
            $table->char('status', 1)->default('A');
            $table->timestamps();

            $table->unique(['tax_group_code', 'tax_type_code']);
            $table->index(['tax_group_code', 'sequence']);

            $table->foreign('tax_group_code')
                ->references('code')
                ->on('tax_groups')
                ->onDelete('cascade');

            $table->foreign('tax_type_code')
                ->references('code')
                ->on('tax_types')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tax_group_items');
    }
};
