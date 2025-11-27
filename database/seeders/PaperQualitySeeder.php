<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PaperQualitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now();
        
        // Helper function to create paper quality entries
        $createEntry = function($paper_quality, $paper_name, $weight_kg_m, $commercial_code = null, $wet_end_code = null, $decc_code = null, $status = 'Act', $flute = null, $db = null, $b = null, $il = null, $a_c_e = null, $two_l = null) use ($now) {
            return [
                'paper_quality' => $paper_quality,
                'paper_name' => $paper_name,
                'weight_kg_m' => $weight_kg_m,
                'commercial_code' => $commercial_code,
                'wet_end_code' => $wet_end_code,
                'decc_code' => $decc_code,
                'status' => $status,
                'flute' => $flute,
                'db' => $db,
                'b' => $b,
                'il' => $il,
                'a_c_e' => $a_c_e,
                '2l' => $two_l,
                'created_by' => 'system',
                'updated_by' => 'system',
                'created_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        };
        
        $paperQualities = [
            // Data based on the image
            $createEntry('AGBK125', 'AGBK125', 0.1250, null, null, '1BL25', 'Act'),
            $createEntry('AGBS125', 'AGBS125', 0.1250, null, null, '1#125', 'Act'),
            $createEntry('AGBS150', 'AGBS150', 0.1500, null, null, '1#150', 'Act'),
            $createEntry('AGBS200', 'AGBS200', 0.2000, null, null, '1#200', 'Act'),
            $createEntry('AGML110', 'AGML110', 0.1100, null, null, '1ML10', 'Act'),
            $createEntry('AGML120', 'AGML120', 0.1200, null, null, '1ML20', 'Act'),
            $createEntry('AGML125', 'AGML125', 0.1250, null, null, '1ML25', 'Act'),
            $createEntry('AGML130', 'AGML130', 0.1300, null, null, '1ML30', 'Act'),
            $createEntry('AGML135', 'AGML135', 0.1350, null, null, '1ML35', 'Act'),
            $createEntry('AGML140', 'AGML140', 0.1400, null, null, '1ML40', 'Act'),
            $createEntry('AGML150', 'AGML150', 0.1500, null, null, '1ML50', 'Act'),
            $createEntry('APBK100', 'APBK100', 0.1000, null, null, '#BL00', 'Act'),
            $createEntry('APBK110', 'APBK110', 0.1100, null, null, '#BL10', 'Act'),
            $createEntry('APBK120', 'APBK120', 0.1200, null, null, '#BL20', 'Act'),
            $createEntry('APML100', 'APML100', 0.1000, null, null, '#ML00', 'Act'),
            $createEntry('APML110', 'APML110', 0.1100, null, null, '#ML10', 'Act'),
            $createEntry('APML120', 'APML120', 0.1200, null, null, '#ML20', 'Act'),
            $createEntry('AHDP250', 'AHDP250', 0.2500, null, null, null, 'Act'),
            $createEntry('APKL125', 'APKL125', 0.1250, null, null, null, 'Obs'),
            $createEntry('APKL150', 'APKL150', 0.1500, null, null, null, 'Obs'),
            $createEntry('APKML125', 'APKML125', 0.1250, 'APKML125', null, null, 'Obs'),
            $createEntry('APKML150', 'APKML150', 0.1500, 'APKML150', null, null, 'Obs'),
            $createEntry('APML150', 'APML150', 0.1500, null, null, null, 'Obs'),
            $createEntry('APKML151', 'APKML150', 0.1500, 'APKML150', null, null, 'Obs'),
            $createEntry('BK100', 'BK100', 0.1000, null, null, 'bk100', 'Act'),
            $createEntry('BK110', 'BK110', 0.1100, null, null, 'bk110', 'Act'),
            $createEntry('BK120', 'BK120', 0.1200, null, null, 'bk120', 'Act'),
            $createEntry('BK125', 'BK125', 0.1250, null, 'BK125', 'bk125', 'Act'),
            $createEntry('BK135', 'BK135', 0.1350, null, null, 'bk135', 'Act'),
            $createEntry('BK140', 'BK140', 0.1400, null, null, 'bk140', 'Act'),
            $createEntry('BK150', 'BK150', 0.1500, null, null, 'bk150', 'Act'),
            $createEntry('BK190', 'BK190', 0.1900, null, null, 'bk190', 'Act'),
            $createEntry('BK200', 'BK200', 0.2000, null, null, 'bk200', 'Act'),
            $createEntry('BK275', 'BCORB275', 0.2750, null, null, 'bk275', 'Act'),
            $createEntry('BMBK125', 'BMBK125', 0.1250, null, null, 'KB125', 'Act'),
            $createEntry('BMBK150', 'BMBK150', 0.1500, null, null, 'KB150', 'Act'),
            $createEntry('BMBK200', 'BMBK200', 0.2000, null, null, 'KB200', 'Act'),
            $createEntry('BMBK275', 'BMBK275', 0.2750, null, null, 'KB275', 'Act'),
            $createEntry('BMKA125', 'BMKA125', 0.1250, null, null, 'KK125', 'Act'),
        ];
        
        // Empty the table first
        DB::table('paper_qualities')->truncate();
        
        // Insert the data
        DB::table('paper_qualities')->insert($paperQualities);
    }
}
