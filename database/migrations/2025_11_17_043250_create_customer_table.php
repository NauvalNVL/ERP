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
        // Guard: if CUSTOMER table already exists (legacy DB), skip creation
        if (!Schema::hasTable('CUSTOMER')) {
            Schema::create('CUSTOMER', function (Blueprint $table) {
                $collation = 'SQL_Latin1_General_CP1_CI_AS';

                $table->string('CODE', 50)->collation($collation);
                $table->string('AC_STS', 50)->nullable()->collation($collation);
                $table->string('NAME', 250)->nullable()->collation($collation);
                $table->string('ADDRESS1', 250)->nullable()->collation($collation);
                $table->string('ADDRESS2', 250)->nullable()->collation($collation);
                $table->string('ADDRESS3', 250)->nullable()->collation($collation);
                $table->string('PERSON_CONTACT', 50)->nullable()->collation($collation);
                $table->string('TEL_NO', 100)->nullable()->collation($collation);
                $table->string('FAX_NO', 50)->nullable()->collation($collation);
                $table->string('EMAIL', 50)->nullable()->collation($collation);
                $table->decimal('CREDIT_LIMIT', 15, 2)->nullable();
                $table->decimal('TERM', 15, 2)->nullable();
                $table->string('TYPE', 50)->nullable()->collation($collation);
                $table->string('CURRENCY', 50)->nullable()->collation($collation);
                $table->string('SLM', 50)->nullable()->collation($collation);
                $table->string('AREA', 10)->nullable()->collation($collation);
                $table->string('IND', 5)->nullable()->collation($collation);
                $table->string('GROUP_', 12)->nullable()->collation($collation);
                $table->string('NPWP', 16)->nullable()->collation($collation);
                $table->string('CUST_TYPE', 50)->nullable()->collation($collation);

                // Set primary key
                $table->primary('CODE');
            });
        }

        if (Schema::hasTable('INV')) {
            Schema::table('INV', function (Blueprint $table) {
                $table->foreign('AC_NUM')
                      ->references('CODE')
                      ->on('CUSTOMER');
            });
        }

        Schema::table('CUSTOMER', function (Blueprint $table) {
            $table->foreign('SLM')
                  ->references('Code')
                  ->on('salesperson');

            $table->foreign('IND')
                  ->references('code')
                  ->on('industry');

            $table->foreign('AREA')
                  ->references('CODE')
                  ->on('GEO');
        });

        Schema::table('customer_sales_tax_indices', function (Blueprint $table) {
            $table->foreign('customer_code')
                  ->references('CODE')
                  ->on('CUSTOMER');
        });

        // Link legacy SO, DO, MC, and INV tables to CUSTOMER by account number
        if (Schema::hasTable('so')) {
            Schema::table('so', function (Blueprint $table) {
                $table->foreign('AC_Num')
                      ->references('CODE')
                      ->on('CUSTOMER');
            });
        }

        if (Schema::hasTable('DO')) {
            Schema::table('DO', function (Blueprint $table) {
                $table->foreign('AC_Num')
                      ->references('CODE')
                      ->on('CUSTOMER');
            });
        }

        if (Schema::hasTable('MC')) {
            Schema::table('MC', function (Blueprint $table) {
                $table->foreign('AC_NUM')
                      ->references('CODE')
                      ->on('CUSTOMER');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('so')) {
            Schema::table('so', function (Blueprint $table) {
                $table->dropForeign(['AC_Num']);
            });
        }

        if (Schema::hasTable('DO')) {
            Schema::table('DO', function (Blueprint $table) {
                $table->dropForeign(['AC_Num']);
            });
        }

        if (Schema::hasTable('MC')) {
            Schema::table('MC', function (Blueprint $table) {
                $table->dropForeign(['AC_NUM']);
            });
        }

        if (Schema::hasTable('INV')) {
            Schema::table('INV', function (Blueprint $table) {
                $table->dropForeign(['AC_NUM']);
            });
        }

        Schema::table('customer_sales_tax_indices', function (Blueprint $table) {
            $table->dropForeign(['customer_code']);
        });

        Schema::table('CUSTOMER', function (Blueprint $table) {
            $table->dropForeign(['SLM']);
            $table->dropForeign(['IND']);
            $table->dropForeign(['AREA']);
        });

        Schema::dropIfExists('CUSTOMER');
    }
};
