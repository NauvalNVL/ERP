<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SalespersonSeeder extends Seeder
{
    /**
     * The salespersons data.
     */
    protected $salespersons = [
        [
            'code' => 'S101',
            'name' => 'ABENG',
            'sales_team_id' => 1, // MBI
            'position' => 'E - Executive',
            'user_id' => 'root',
            'is_active' => true,
        ],
        [
            'code' => 'S102',
            'name' => 'AGUNG',
            'sales_team_id' => 1, // MBI
            'position' => 'E - Executive',
            'user_id' => 'SLS',
            'is_active' => true,
        ],
        [
            'code' => 'S103',
            'name' => 'EKO',
            'sales_team_id' => 1, // MBI
            'position' => 'E - Executive',
            'user_id' => 'SLS',
            'is_active' => true,
        ],
        [
            'code' => 'S104',
            'name' => 'ELIAS',
            'sales_team_id' => 1, // MBI
            'position' => 'E - Executive',
            'user_id' => 'SLS',
            'is_active' => true,
        ],
        [
            'code' => 'S105',
            'name' => 'FEBBY',
            'sales_team_id' => 1, // MBI
            'position' => 'E - Executive',
            'user_id' => 'SLS',
            'is_active' => true,
        ],
        [
            'code' => 'S106',
            'name' => 'HASAN',
            'sales_team_id' => 1, // MBI
            'position' => 'E - Executive',
            'user_id' => 'SLS',
            'is_active' => true,
        ],
        [
            'code' => 'S107',
            'name' => 'HENGKI',
            'sales_team_id' => 1, // MBI
            'position' => 'E - Executive',
            'user_id' => 'SLS',
            'is_active' => true,
            'created_at' => null,
            'updated_at' => null,
        ],
        [
            'code' => 'S108',
            'name' => 'IN HOUSE',
            'sales_team_id' => 2, // MANAGEMENT LOCAL
            'position' => 'E - Executive',
            'user_id' => 'MKT',
            'is_active' => true,
        ],
        [
            'code' => 'S109',
            'name' => 'IN HOUSE FREELANCE JONO',
            'sales_team_id' => 1, // MBI
            'position' => 'E - Executive',
            'user_id' => 'SLS',
            'is_active' => true,
        ],
        [
            'code' => 'S110',
            'name' => 'INDAH SIE',
            'sales_team_id' => 1, // MBI
            'position' => 'E - Executive',
            'user_id' => 'SLS',
            'is_active' => true,
        ],
        [
            'code' => 'S111',
            'name' => 'KHOES TJ',
            'sales_team_id' => 1, // MBI
            'position' => 'E - Executive',
            'user_id' => 'SLS',
            'is_active' => true,
        ],
        [
            'code' => 'S112',
            'name' => 'KURNIAWAN',
            'sales_team_id' => 1, // MBI
            'position' => 'E - Executive',
            'user_id' => 'SLS',
            'is_active' => true,
        ],
        [
            'code' => 'S113',
            'name' => 'MARTIN',
            'sales_team_id' => 1, // MBI
            'position' => 'E - Executive',
            'user_id' => 'SLS',
            'is_active' => true,
        ],
        [
            'code' => 'S114',
            'name' => 'MEGA',
            'sales_team_id' => 1, // MBI
            'position' => 'E - Executive',
            'user_id' => 'SLS',
            'is_active' => true,
            'created_at' => null,
            'updated_at' => null,
        ],
        [
            'code' => 'S115',
            'name' => 'MELINA',
            'sales_team_id' => 1, // MBI
            'position' => 'E - Executive',
            'user_id' => 'SLS',
            'is_active' => true,
            'created_at' => null,
            'updated_at' => null,
        ],
        [
            'code' => 'S116',
            'name' => 'MULTI NATIONAL COMPANY',
            'sales_team_id' => 3, // MANAGEMENT MNC
            'position' => 'E - Executive',
            'user_id' => 'MKT',
            'is_active' => true,
        ],
        [
            'code' => 'S117',
            'name' => 'ROBERT',
            'sales_team_id' => 1, // MBI
            'position' => 'E - Executive',
            'user_id' => 'SLS',
            'is_active' => true,
        ],
        [
            'code' => 'S118',
            'name' => 'ROBERT PURBA',
            'sales_team_id' => 1, // MBI
            'position' => 'E - Executive',
            'user_id' => 'SLS',
            'is_active' => true,
        ],
        [
            'code' => 'S119',
            'name' => 'SUSAN',
            'sales_team_id' => 1, // MBI
            'position' => 'E - Executive',
            'user_id' => 'SLS',
            'is_active' => true,
        ],
        [
            'code' => 'S120',
            'name' => 'TEDDY',
            'sales_team_id' => 1, // MBI
            'position' => 'E - Executive',
            'user_id' => 'SLS',
            'is_active' => true,
        ],
        [
            'code' => 'S121',
            'name' => 'TORRY',
            'sales_team_id' => 1, // MBI
            'position' => 'E - Executive',
            'user_id' => 'SLS',
            'is_active' => true,
            'created_at' => null,
            'updated_at' => null,
        ],
        [
            'code' => 'S122',
            'name' => 'TUN WIE',
            'sales_team_id' => 1, // MBI
            'position' => 'E - Executive',
            'user_id' => 'SLS',
            'is_active' => true,
            'created_at' => null,
            'updated_at' => null,
        ],
        [
            'code' => 'S123',
            'name' => 'YONAS',
            'sales_team_id' => 1, // MBI
            'position' => 'E - Executive',
            'user_id' => 'SLS',
            'is_active' => true,
            'created_at' => null,
            'updated_at' => null,
        ],
        [
            'code' => 'S124',
            'name' => 'FEIGE SIE',
            'sales_team_id' => 2, // MANAGEMENT LOCAL
            'position' => 'E - Executive',
            'user_id' => 'MKT',
            'is_active' => true,
        ],
        [
            'code' => 'S125',
            'name' => 'INHOUSE FREELANCE SUFATO',
            'sales_team_id' => 1, // MBI
            'position' => 'E - Executive',
            'user_id' => 'SLS',
            'is_active' => true,
            'created_at' => null,
            'updated_at' => null,
        ],
        [
            'code' => 'S126',
            'name' => 'INHOUSE FREELANCE ROBBY',
            'sales_team_id' => 1, // MBI
            'position' => 'E - Executive',
            'user_id' => 'SLS',
            'is_active' => true,
            'created_at' => null,
            'updated_at' => null,
        ],
        [
            'code' => 'S127',
            'name' => 'KIRBY',
            'sales_team_id' => 1, // MBI
            'position' => 'E - Executive',
            'user_id' => 'SLS',
            'is_active' => true,
            'created_at' => null,
            'updated_at' => null,
        ],
        [
            'code' => 'S128',
            'name' => '-',
            'sales_team_id' => 2, // MANAGEMENT LOCAL
            'position' => 'E - Executive',
            'user_id' => 'MKT',
            'is_active' => true,
        ],
        [
            'code' => 'S129',
            'name' => 'MULTI NATIONAL COMPANY OIA',
            'sales_team_id' => 3, // MANAGEMENT MNC
            'position' => 'E - Executive',
            'user_id' => 'MKT',
            'is_active' => true,
        ],
        [
            'code' => 'S130',
            'name' => 'MULTI NATIONAL COMPANY PACCCESS',
            'sales_team_id' => 3, // MANAGEMENT MNC
            'position' => 'E - Executive',
            'user_id' => 'MKT',
            'is_active' => true,
        ],
        [
            'code' => 'S131',
            'name' => 'MULTI NATIONAL COMPANY PILAR',
            'sales_team_id' => 3, // MANAGEMENT MNC
            'position' => 'E - Executive',
            'user_id' => 'MKT',
            'is_active' => true,
        ],
        [
            'code' => 'S132',
            'name' => 'MULTI NATIONAL COMPANY AVERY',
            'sales_team_id' => 3, // MANAGEMENT MNC
            'position' => 'E - Executive',
            'user_id' => 'MKT',
            'is_active' => true,
        ],
        [
            'code' => 'S133',
            'name' => 'INHOUSE ROLL',
            'sales_team_id' => 2, // MANAGEMENT LOCAL
            'position' => 'E - Executive',
            'user_id' => 'MKT',
            'is_active' => true,
        ],
        [
            'code' => 'S134',
            'name' => '-',
            'sales_team_id' => 2, // MANAGEMENT LOCAL
            'position' => 'E - Executive',
            'user_id' => 'MKT',
            'is_active' => true,
            'created_at' => null,
            'updated_at' => null,
        ],
        [
            'code' => 'S135',
            'name' => '-',
            'sales_team_id' => 2, // MANAGEMENT LOCAL
            'position' => 'E - Executive',
            'user_id' => 'MKT',
            'is_active' => true,
            'created_at' => null,
            'updated_at' => null,
        ],
        [
            'code' => 'S136',
            'name' => 'INHOUSE FREELANCE BOBBY',
            'sales_team_id' => 1, // MBI
            'position' => 'E - Executive',
            'user_id' => 'SLS',
            'is_active' => true,
            'created_at' => null,
            'updated_at' => null,
        ],
        [
            'code' => 'S137',
            'name' => 'INHOUSE FREELANCE YULI',
            'sales_team_id' => 1, // MBI
            'position' => 'E - Executive',
            'user_id' => 'SLS',
            'is_active' => true,
            'created_at' => null,
            'updated_at' => null,
        ],
        [
            'code' => 'S138',
            'name' => 'INHOUSE MARWAN',
            'sales_team_id' => 1, // MBI
            'position' => 'E - Executive',
            'user_id' => 'SLS',
            'is_active' => true,
            'created_at' => null,
            'updated_at' => null,
        ],
        [
            'code' => 'S139',
            'name' => 'LATH LATN',
            'sales_team_id' => 2, // MANAGEMENT LOCAL
            'position' => 'E - Executive',
            'user_id' => 'MKT',
            'is_active' => true,
            'created_at' => null,
            'updated_at' => null,
        ],
        [
            'code' => 'S140',
            'name' => 'FEBRIAN',
            'sales_team_id' => 1, // MBI
            'position' => 'E - Executive',
            'user_id' => 'SLS',
            'is_active' => true,
            'created_at' => null,
            'updated_at' => null,
        ],
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear the table before seeding (handle FK constraints for MySQL)
        try {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        } catch (\Throwable $e) {
            // Ignore for non-MySQL drivers
        }

        DB::table('salesperson')->truncate();

        $now = now();
        $rows = array_map(function ($row) use ($now) {
            // Ensure timestamps exist
            $row['created_at'] = $row['created_at'] ?? $now;
            $row['updated_at'] = $row['updated_at'] ?? $now;
            return $row;
        }, $this->salespersons);

        DB::table('salesperson')->insert($rows);

        try {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        } catch (\Throwable $e) {
            // Ignore for non-MySQL drivers
        }
    }

    /**
     * Get the seeder data.
     */
    public function getSeederData()
    {
        return $this->salespersons;
    }
} 