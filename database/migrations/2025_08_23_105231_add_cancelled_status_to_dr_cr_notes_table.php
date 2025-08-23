<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // For SQL Server, we need to drop and recreate the constraint
        DB::statement("ALTER TABLE dr_cr_notes DROP CONSTRAINT IF EXISTS CK_dr_cr_notes_status");
        DB::statement("ALTER TABLE dr_cr_notes ADD CONSTRAINT CK_dr_cr_notes_status CHECK (status IN ('Draft', 'Pending', 'Approved', 'Rejected', 'Posted', 'Cancelled'))");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove 'Cancelled' from the constraint
        DB::statement("ALTER TABLE dr_cr_notes DROP CONSTRAINT IF EXISTS CK_dr_cr_notes_status");
        DB::statement("ALTER TABLE dr_cr_notes ADD CONSTRAINT CK_dr_cr_notes_status CHECK (status IN ('Draft', 'Pending', 'Approved', 'Rejected', 'Posted'))");
    }
};
