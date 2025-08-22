<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Vendor;

class VendorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vendors = [
            [
                'vendor_name' => 'ABADI KARYA MAKMUR',
                'ap_ac_number' => '2100201421',
                'status' => 'A-Act',
                'ac_type' => 'Local',
                'currency' => 'IDR',
                'gl_ap_control' => 'HUTANG',
                'gl_bank_control' => 'RP PERMATA',
                'contact_person' => 'John Doe',
                'phone' => '021-1234567',
                'email' => 'contact@abadikarya.com',
                'address' => 'Jl. Sudirman No. 123, Jakarta Pusat',
                'is_active' => true
            ],
            [
                'vendor_name' => 'ABADI PRATAMA INDONESIA PT.',
                'ap_ac_number' => '2100201514',
                'status' => 'A-Act',
                'ac_type' => 'Local',
                'currency' => 'IDR',
                'gl_ap_control' => 'HUTANG',
                'gl_bank_control' => 'RP PERMATA',
                'contact_person' => 'Jane Smith',
                'phone' => '021-2345678',
                'email' => 'info@abadipratama.com',
                'address' => 'Jl. Thamrin No. 456, Jakarta Pusat',
                'is_active' => true
            ],
            [
                'vendor_name' => 'ACCURA SOLIDTECH ADISEJAHTERA, PT.',
                'ap_ac_number' => '2100201198',
                'status' => 'A-Act',
                'ac_type' => 'Local',
                'currency' => 'IDR',
                'gl_ap_control' => 'HUTANG B',
                'gl_bank_control' => 'RP PERMATA',
                'contact_person' => 'Bob Johnson',
                'phone' => '021-3456789',
                'email' => 'sales@accura.com',
                'address' => 'Jl. Gatot Subroto No. 789, Jakarta Selatan',
                'is_active' => true
            ],
            [
                'vendor_name' => 'ACEN JAYA ELEKTRIK, UD.',
                'ap_ac_number' => '2100201199',
                'status' => 'A-Act',
                'ac_type' => 'Local',
                'currency' => 'IDR',
                'gl_ap_control' => 'HUTANG',
                'gl_bank_control' => 'RP PERMATA',
                'contact_person' => 'Alice Brown',
                'phone' => '021-4567890',
                'email' => 'info@acenjaya.com',
                'address' => 'Jl. Hayam Wuruk No. 321, Jakarta Barat',
                'is_active' => true
            ],
            [
                'vendor_name' => 'ADHIJAYA SUKSES MAKMUR',
                'ap_ac_number' => '2100201200',
                'status' => 'A-Act',
                'ac_type' => 'Local',
                'currency' => 'IDR',
                'gl_ap_control' => 'HUTANG',
                'gl_bank_control' => 'RP PERMATA',
                'contact_person' => 'Charlie Wilson',
                'phone' => '021-5678901',
                'email' => 'contact@adhijaya.com',
                'address' => 'Jl. Mangga Dua No. 654, Jakarta Utara',
                'is_active' => true
            ],
            [
                'vendor_name' => 'ADI NUSA GLORY, PT.',
                'ap_ac_number' => '2100201201',
                'status' => 'A-Act',
                'ac_type' => 'Local',
                'currency' => 'IDR',
                'gl_ap_control' => 'HUTANG',
                'gl_bank_control' => 'RP PERMATA',
                'contact_person' => 'Diana Davis',
                'phone' => '021-6789012',
                'email' => 'info@adinusa.com',
                'address' => 'Jl. Senayan No. 987, Jakarta Pusat',
                'is_active' => true
            ],
            [
                'vendor_name' => 'ADIJAYA PRIMA PRESISI',
                'ap_ac_number' => '2100201202',
                'status' => 'A-Act',
                'ac_type' => 'Local',
                'currency' => 'IDR',
                'gl_ap_control' => 'HUTANG',
                'gl_bank_control' => 'RP PERMATA',
                'contact_person' => 'Edward Miller',
                'phone' => '021-7890123',
                'email' => 'sales@adijaya.com',
                'address' => 'Jl. Kuningan No. 147, Jakarta Selatan',
                'is_active' => true
            ],
            [
                'vendor_name' => 'ADIL CITRA RAYA',
                'ap_ac_number' => '2100201203',
                'status' => 'A-Act',
                'ac_type' => 'Local',
                'currency' => 'IDR',
                'gl_ap_control' => 'HUTANG',
                'gl_bank_control' => 'RP PERMATA',
                'contact_person' => 'Fiona Garcia',
                'phone' => '021-8901234',
                'email' => 'info@adilcitra.com',
                'address' => 'Jl. Kebayoran Baru No. 258, Jakarta Selatan',
                'is_active' => true
            ],
            [
                'vendor_name' => 'ADINA MULTI WAHANA, PT.',
                'ap_ac_number' => '2100201204',
                'status' => 'A-Act',
                'ac_type' => 'Local',
                'currency' => 'IDR',
                'gl_ap_control' => 'HUTANG',
                'gl_bank_control' => 'RP PERMATA',
                'contact_person' => 'George Martinez',
                'phone' => '021-9012345',
                'email' => 'contact@adinamulti.com',
                'address' => 'Jl. Blok M No. 369, Jakarta Selatan',
                'is_active' => true
            ],
            [
                'vendor_name' => 'AFLI JAYA MANDIRI',
                'ap_ac_number' => '2100201205',
                'status' => 'A-Act',
                'ac_type' => 'Local',
                'currency' => 'IDR',
                'gl_ap_control' => 'HUTANG',
                'gl_bank_control' => 'RP PERMATA',
                'contact_person' => 'Helen Rodriguez',
                'phone' => '021-0123456',
                'email' => 'info@aflijaya.com',
                'address' => 'Jl. Cilandak No. 741, Jakarta Selatan',
                'is_active' => true
            ],
            [
                'vendor_name' => 'AGROMAS GEMILANG',
                'ap_ac_number' => '2100201206',
                'status' => 'A-Act',
                'ac_type' => 'Local',
                'currency' => 'IDR',
                'gl_ap_control' => 'HUTANG',
                'gl_bank_control' => 'RP PERMATA',
                'contact_person' => 'Ian Thompson',
                'phone' => '021-1234567',
                'email' => 'sales@agromas.com',
                'address' => 'Jl. Pondok Indah No. 852, Jakarta Selatan',
                'is_active' => true
            ],
            [
                'vendor_name' => 'AIRINDO TEKNOLOGI PERKASA',
                'ap_ac_number' => '2100201207',
                'status' => 'A-Act',
                'ac_type' => 'Local',
                'currency' => 'IDR',
                'gl_ap_control' => 'HUTANG',
                'gl_bank_control' => 'RP PERMATA',
                'contact_person' => 'Julia Anderson',
                'phone' => '021-2345678',
                'email' => 'info@airindo.com',
                'address' => 'Jl. Sudirman No. 963, Jakarta Pusat',
                'is_active' => true
            ],
            [
                'vendor_name' => 'ALFA SURYA MANDIRI, PT.(BB)',
                'ap_ac_number' => '2100201208',
                'status' => 'A-Act',
                'ac_type' => 'Local',
                'currency' => 'IDR',
                'gl_ap_control' => 'HUTANG',
                'gl_bank_control' => 'RP PERMATA',
                'contact_person' => 'Kevin Taylor',
                'phone' => '021-3456789',
                'email' => 'contact@alfasurya.com',
                'address' => 'Jl. Thamrin No. 159, Jakarta Pusat',
                'is_active' => true
            ],
            [
                'vendor_name' => 'ALFIN ADVERTISING',
                'ap_ac_number' => '2100201209',
                'status' => 'A-Act',
                'ac_type' => 'Local',
                'currency' => 'IDR',
                'gl_ap_control' => 'HUTANG',
                'gl_bank_control' => 'RP PERMATA',
                'contact_person' => 'Laura White',
                'phone' => '021-4567890',
                'email' => 'info@alfinadvertising.com',
                'address' => 'Jl. Gatot Subroto No. 753, Jakarta Selatan',
                'is_active' => true
            ],
            [
                'vendor_name' => 'ALKAGRA SENI GLASS PT.',
                'ap_ac_number' => '2100201210',
                'status' => 'A-Act',
                'ac_type' => 'Local',
                'currency' => 'IDR',
                'gl_ap_control' => 'HUTANG',
                'gl_bank_control' => 'RP PERMATA',
                'contact_person' => 'Michael Clark',
                'phone' => '021-5678901',
                'email' => 'sales@alkagra.com',
                'address' => 'Jl. Hayam Wuruk No. 951, Jakarta Barat',
                'is_active' => true
            ],
            [
                'vendor_name' => 'ALPHA AUSTENITE',
                'ap_ac_number' => '2100201211',
                'status' => 'A-Act',
                'ac_type' => 'Local',
                'currency' => 'IDR',
                'gl_ap_control' => 'HUTANG',
                'gl_bank_control' => 'RP PERMATA',
                'contact_person' => 'Nancy Lewis',
                'phone' => '021-6789012',
                'email' => 'info@alphaaustenite.com',
                'address' => 'Jl. Mangga Dua No. 357, Jakarta Utara',
                'is_active' => true
            ],
            [
                'vendor_name' => 'ALWI S.',
                'ap_ac_number' => '2100201212',
                'status' => 'A-Act',
                'ac_type' => 'Local',
                'currency' => 'IDR',
                'gl_ap_control' => 'HUTANG',
                'gl_bank_control' => 'RP PERMATA',
                'contact_person' => 'Alwi S.',
                'phone' => '021-7890123',
                'email' => 'alwi@example.com',
                'address' => 'Jl. Senayan No. 468, Jakarta Pusat',
                'is_active' => true
            ],
            [
                'vendor_name' => 'AMERTA NIAGATAMA, PT. (BB)',
                'ap_ac_number' => '2100201213',
                'status' => 'A-Act',
                'ac_type' => 'Local',
                'currency' => 'IDR',
                'gl_ap_control' => 'HUTANG',
                'gl_bank_control' => 'RP PERMATA',
                'contact_person' => 'Oliver Hall',
                'phone' => '021-8901234',
                'email' => 'contact@amertaniaga.com',
                'address' => 'Jl. Kuningan No. 579, Jakarta Selatan',
                'is_active' => true
            ],
            [
                'vendor_name' => 'AMERTA NIAGATAMA, PT. (DUPLEX)',
                'ap_ac_number' => '2100201214',
                'status' => 'A-Act',
                'ac_type' => 'Local',
                'currency' => 'IDR',
                'gl_ap_control' => 'HUTANG',
                'gl_bank_control' => 'RP PERMATA',
                'contact_person' => 'Patricia Young',
                'phone' => '021-9012345',
                'email' => 'info@amertaduplex.com',
                'address' => 'Jl. Kebayoran Baru No. 680, Jakarta Selatan',
                'is_active' => true
            ],
            [
                'vendor_name' => 'AMRINDO MEDIATAMA GRUP',
                'ap_ac_number' => '2100201215',
                'status' => 'A-Act',
                'ac_type' => 'Local',
                'currency' => 'IDR',
                'gl_ap_control' => 'HUTANG',
                'gl_bank_control' => 'RP PERMATA',
                'contact_person' => 'Quentin King',
                'phone' => '021-0123456',
                'email' => 'sales@amrindo.com',
                'address' => 'Jl. Blok M No. 791, Jakarta Selatan',
                'is_active' => true
            ]
        ];

        foreach ($vendors as $vendor) {
            Vendor::updateOrCreate(
                ['ap_ac_number' => $vendor['ap_ac_number']],
                $vendor
            );
        }
    }
}
