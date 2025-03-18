<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class ChangePasswordExpiryDateTypeInUsersTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Tambahkan kolom sementara untuk migrasi
            $table->integer('new_password_expiry_date')->nullable();
        });

        // Transfer data dengan konversi yang benar
        DB::statement('
            UPDATE users 
            SET new_password_expiry_date = DATEDIFF(day, GETDATE(), CAST(password_expiry_date AS DATETIME))
        ');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('password_expiry_date');
            $table->renameColumn('new_password_expiry_date', 'password_expiry_date');
            $table->integer('password_expiry_date')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Untuk rollback, konversi kembali ke date
            $table->dateTime('password_expiry_date')->nullable()->change();
        });
    }
}
