<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateColorGroupsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('color_groups', function (Blueprint $table) {
            $table->id();
            $table->string('cg', 10)->unique(); // Color Group Code
            $table->string('cg_name', 50); // Color Group Name
            $table->enum('cg_type', ['X-Flex', 'C-Coating', 'S-Offset'])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('color_groups');
    }
}
