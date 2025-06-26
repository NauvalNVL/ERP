<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MmAnalysisCode;

class MmAnalysisCodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing data
        MmAnalysisCode::query()->delete();
        
        // Sample data
        $analysisCodes = [
            // PO (Purchase Order) codes
            ['code' => 'AMP001', 'name' => 'EDIT PO', 'group' => 'PO', 'group2' => 'AM'],
            ['code' => 'AMPR01', 'name' => 'EDIT PR', 'group' => 'PR', 'group2' => 'AM'],
            ['code' => 'AMRC', 'name' => 'EDIT RC', 'group' => 'RC', 'group2' => 'AM'],
            ['code' => 'AMRT', 'name' => 'EDIT RT', 'group' => 'RT', 'group2' => 'AM'],
            ['code' => 'CLPO', 'name' => 'CLOSE PO', 'group' => 'PO', 'group2' => 'CM'],
            ['code' => 'CLPR', 'name' => 'CLOSE PR', 'group' => 'PR', 'group2' => 'CM'],
            ['code' => 'CX', 'name' => 'ADJUST COSTING', 'group' => 'CX', 'group2' => 'CX'],
            ['code' => 'CXIS', 'name' => 'CANCEL PEMAKAIAN', 'group' => 'IS', 'group2' => 'CX'],
            ['code' => 'CXLT', 'name' => 'CANCEL LT', 'group' => 'LT', 'group2' => 'CX'],
            ['code' => 'CXMI', 'name' => 'CANCEL MI', 'group' => 'MI', 'group2' => 'CX'],
            ['code' => 'CXMO', 'name' => 'CANCEL MO', 'group' => 'MO', 'group2' => 'CX'],
            ['code' => 'CXPO', 'name' => 'CANCEL PO', 'group' => 'PO', 'group2' => 'CX'],
            ['code' => 'CXPR', 'name' => 'CANCEL PR', 'group' => 'PR', 'group2' => 'CX'],
            ['code' => 'MI01', 'name' => 'STOK IN', 'group' => 'MI', 'group2' => 'NW'],
            ['code' => 'NWIS', 'name' => 'NEW IS', 'group' => 'IS', 'group2' => 'NW'],
            ['code' => 'NWLT', 'name' => 'NEW LT', 'group' => 'LT', 'group2' => 'NW'],
            ['code' => 'NWMO', 'name' => 'NEW MO', 'group' => 'MO', 'group2' => 'NW'],

            // Sales Order related codes
            ['code' => 'AM01', 'name' => 'EDIT SO', 'group' => 'SO', 'group2' => 'AM'],
            ['code' => 'AM02', 'name' => 'EDIT HARGA SO MISS TRIM', 'group' => 'SO', 'group2' => 'AM'],
            ['code' => 'AM03', 'name' => 'EDIT HARGA', 'group' => 'SO', 'group2' => 'AM'],
            ['code' => 'CL01', 'name' => 'SALAH INPUT', 'group' => 'SO', 'group2' => 'CL'],
            ['code' => 'CL02', 'name' => 'PO BATAL (PERMINTAAN CUSTOMER)', 'group' => 'SO', 'group2' => 'CL'],
            ['code' => 'CM01', 'name' => 'GANTI HARGA BARU', 'group' => 'SO', 'group2' => 'CM'],
            ['code' => 'CM02', 'name' => 'DISCONTINUE', 'group' => 'SO', 'group2' => 'CM'],
            ['code' => 'CM03', 'name' => 'PO TIDAK LUNAS/KURANG PRODUKSI', 'group' => 'SO', 'group2' => 'CM'],
            ['code' => 'CM04', 'name' => 'PO SELESAI', 'group' => 'SO', 'group2' => 'CM'],

            // Other group codes
            ['code' => 'NW01', 'name' => 'NEW PO', 'group' => 'PO', 'group2' => 'NW'],
            ['code' => 'RC01', 'name' => 'RECEIVE NOTE', 'group' => 'RC', 'group2' => 'NW'],
            ['code' => 'RT01', 'name' => 'RETURN NOTE', 'group' => 'RT', 'group2' => 'NW'],
            ['code' => 'UI01', 'name' => 'UNCOMPLETE ITEM', 'group' => 'PO', 'group2' => 'UI'],
            ['code' => 'UM01', 'name' => 'UNCLOSE MANUAL SO', 'group' => 'SO', 'group2' => 'UM'],
            ['code' => 'UX01', 'name' => 'UNCANCEL SO', 'group' => 'SO', 'group2' => 'UX'],
            ['code' => 'NA01', 'name' => 'NOT APPLICABLE', 'group' => 'SO', 'group2' => 'NA'],
        ];
        
        foreach ($analysisCodes as $code) {
            MmAnalysisCode::create($code);
        }
    }
} 