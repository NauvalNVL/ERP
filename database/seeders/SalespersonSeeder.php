<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SalespersonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear the table before seeding
        DB::table('salesperson')->truncate();

        $salespersons = [
            [
                'code' => 'S101',
                'name' => 'ABENG',
                'sales_team_id' => 1, // MBI
                'position' => 'E - Executive',
                'user_id' => 'root',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'S102',
                'name' => 'AGUNG',
                'sales_team_id' => 1, // MBI
                'position' => 'E - Executive',
                'user_id' => 'SLS',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'S103',
                'name' => 'EKO',
                'sales_team_id' => 1, // MBI
                'position' => 'E - Executive',
                'user_id' => 'SLS',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'S104',
                'name' => 'ELIAS',
                'sales_team_id' => 1, // MBI
                'position' => 'E - Executive',
                'user_id' => 'SLS',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'S105',
                'name' => 'FEBBY',
                'sales_team_id' => 1, // MBI
                'position' => 'E - Executive',
                'user_id' => 'SLS',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'S106',
                'name' => 'HASAN',
                'sales_team_id' => 1, // MBI
                'position' => 'E - Executive',
                'user_id' => 'SLS',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'S107',
                'name' => 'HENGKI',
                'sales_team_id' => 1, // MBI
                'position' => 'E - Executive',
                'user_id' => 'SLS',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'S108',
                'name' => 'IN HOUSE',
                'sales_team_id' => 2, // MANAGEMENT LOCAL
                'position' => 'E - Executive',
                'user_id' => 'MKT',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'S109',
                'name' => 'IN HOUSE FREELANCE JONO',
                'sales_team_id' => 1, // MBI
                'position' => 'E - Executive',
                'user_id' => 'SLS',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'S110',
                'name' => 'INDAH SIE',
                'sales_team_id' => 1, // MBI
                'position' => 'E - Executive',
                'user_id' => 'SLS',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'S111',
                'name' => 'KHOES TJ',
                'sales_team_id' => 1, // MBI
                'position' => 'E - Executive',
                'user_id' => 'SLS',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'S112',
                'name' => 'KURNIAWAN',
                'sales_team_id' => 1, // MBI
                'position' => 'E - Executive',
                'user_id' => 'SLS',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'S113',
                'name' => 'MARTIN',
                'sales_team_id' => 1, // MBI
                'position' => 'E - Executive',
                'user_id' => 'SLS',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'S114',
                'name' => 'MEGA',
                'sales_team_id' => 1, // MBI
                'position' => 'E - Executive',
                'user_id' => 'SLS',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'S115',
                'name' => 'MELINA',
                'sales_team_id' => 1, // MBI
                'position' => 'E - Executive',
                'user_id' => 'SLS',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'S116',
                'name' => 'MULTI NATIONAL COMPANY',
                'sales_team_id' => 3, // MANAGEMENT MNC
                'position' => 'E - Executive',
                'user_id' => 'MKT',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'S117',
                'name' => 'ROBERT',
                'sales_team_id' => 1, // MBI
                'position' => 'E - Executive',
                'user_id' => 'SLS',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'S118',
                'name' => 'ROBERT PURBA',
                'sales_team_id' => 1, // MBI
                'position' => 'E - Executive',
                'user_id' => 'SLS',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'S119',
                'name' => 'SUSAN',
                'sales_team_id' => 1, // MBI
                'position' => 'E - Executive',
                'user_id' => 'SLS',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'S120',
                'name' => 'TEDDY',
                'sales_team_id' => 1, // MBI
                'position' => 'E - Executive',
                'user_id' => 'SLS',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'S121',
                'name' => 'TORRY',
                'sales_team_id' => 1, // MBI
                'position' => 'E - Executive',
                'user_id' => 'SLS',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'S122',
                'name' => 'TUN WIE',
                'sales_team_id' => 1, // MBI
                'position' => 'E - Executive',
                'user_id' => 'SLS',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'S123',
                'name' => 'YONAS',
                'sales_team_id' => 1, // MBI
                'position' => 'E - Executive',
                'user_id' => 'SLS',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'S124',
                'name' => 'FEIGE SIE',
                'sales_team_id' => 2, // MANAGEMENT LOCAL
                'position' => 'E - Executive',
                'user_id' => 'MKT',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'S125',
                'name' => 'INHOUSE FREELANCE SUFATO',
                'sales_team_id' => 1, // MBI
                'position' => 'E - Executive',
                'user_id' => 'SLS',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'S126',
                'name' => 'INHOUSE FREELANCE ROBBY',
                'sales_team_id' => 1, // MBI
                'position' => 'E - Executive',
                'user_id' => 'SLS',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'S127',
                'name' => 'KIRBY',
                'sales_team_id' => 1, // MBI
                'position' => 'E - Executive',
                'user_id' => 'SLS',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'S128',
                'name' => '-',
                'sales_team_id' => 2, // MANAGEMENT LOCAL
                'position' => 'E - Executive',
                'user_id' => 'MKT',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'S129',
                'name' => 'MULTI NATIONAL COMPANY OIA',
                'sales_team_id' => 3, // MANAGEMENT MNC
                'position' => 'E - Executive',
                'user_id' => 'MKT',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'S130',
                'name' => 'MULTI NATIONAL COMPANY PACCCESS',
                'sales_team_id' => 3, // MANAGEMENT MNC
                'position' => 'E - Executive',
                'user_id' => 'MKT',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'S131',
                'name' => 'MULTI NATIONAL COMPANY PILAR',
                'sales_team_id' => 3, // MANAGEMENT MNC
                'position' => 'E - Executive',
                'user_id' => 'MKT',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'S132',
                'name' => 'MULTI NATIONAL COMPANY AVERY',
                'sales_team_id' => 3, // MANAGEMENT MNC
                'position' => 'E - Executive',
                'user_id' => 'MKT',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'S133',
                'name' => 'INHOUSE ROLL',
                'sales_team_id' => 2, // MANAGEMENT LOCAL
                'position' => 'E - Executive',
                'user_id' => 'MKT',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'S134',
                'name' => '-',
                'sales_team_id' => 2, // MANAGEMENT LOCAL
                'position' => 'E - Executive',
                'user_id' => 'MKT',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'S135',
                'name' => '-',
                'sales_team_id' => 2, // MANAGEMENT LOCAL
                'position' => 'E - Executive',
                'user_id' => 'MKT',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'S136',
                'name' => 'INHOUSE FREELANCE BOBBY',
                'sales_team_id' => 1, // MBI
                'position' => 'E - Executive',
                'user_id' => 'SLS',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'S137',
                'name' => 'INHOUSE FREELANCE YULI',
                'sales_team_id' => 1, // MBI
                'position' => 'E - Executive',
                'user_id' => 'SLS',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'S138',
                'name' => 'INHOUSE MARWAN',
                'sales_team_id' => 1, // MBI
                'position' => 'E - Executive',
                'user_id' => 'SLS',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'S139',
                'name' => 'LATH LATN',
                'sales_team_id' => 2, // MANAGEMENT LOCAL
                'position' => 'E - Executive',
                'user_id' => 'MKT',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'S140',
                'name' => 'FEBRIAN',
                'sales_team_id' => 1, // MBI
                'position' => 'E - Executive',
                'user_id' => 'SLS',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('salesperson')->insert($salespersons);
    }
} 