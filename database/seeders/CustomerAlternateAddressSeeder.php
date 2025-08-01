<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CustomerAlternateAddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing data
        DB::table('customer_alternate_addresses')->truncate();
        
        $alternateAddresses = [
            [
                'customer_code' => '000211-09',
                'delivery_code' => 'B103',
                'country' => 'INDONESIA',
                'state' => 'BANTEN',
                'town' => 'TANGERANG',
                'town_section' => 'JURUMUDI BARU-TANGERANG',
                'bill_to_name' => 'ABDULLAH BPK',
                'bill_to_address' => 'JL. PASAR MINGGU NO. 45, JAKARTA SELATAN',
                'ship_to_name' => 'ABDULLAH, BPK',
                'ship_to_address' => 'JL. YOS SUDARSO NO.61 JURUMUDI BARU-TANGERANG',
                'contact_person' => 'ABDULLAH',
                'tel_no' => '6191875',
                'fax_no' => '5407992',
                'email' => 'abdullah.bpk@example.com',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'customer_code' => '000680-06',
                'delivery_code' => 'C201',
                'country' => 'INDONESIA',
                'state' => 'JAWA BARAT',
                'town' => 'BOGOR',
                'town_section' => 'CIBINONG',
                'bill_to_name' => 'ACEP SUNANDAR, BPK',
                'bill_to_address' => 'JL. RAYA BOGOR KM. 30, BOGOR',
                'ship_to_name' => 'ACEP, BPK',
                'ship_to_address' => 'JL. RAYA BOGOR KM. 30, CIBINONG - BOGOR',
                'contact_person' => 'ACEP',
                'tel_no' => '0251-1234567',
                'fax_no' => '0251-7654321',
                'email' => 'acep.s@example.com',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'customer_code' => '000585-01',
                'delivery_code' => 'D302',
                'country' => 'INDONESIA',
                'state' => 'DKI JAKARTA',
                'town' => 'JAKARTA PUSAT',
                'town_section' => 'TANAH ABANG',
                'bill_to_name' => 'ACHMAD JAMAL',
                'bill_to_address' => 'JL. SUDIRMAN NO. 10, JAKARTA PUSAT',
                'ship_to_name' => 'ACHMAD JAMAL',
                'ship_to_address' => 'JL. KEBON KACANG NO. 5, JAKARTA PUSAT',
                'contact_person' => 'JAMAL',
                'tel_no' => '021-9876543',
                'fax_no' => '021-3456789',
                'email' => 'achmad.j@example.com',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'customer_code' => '000293',
                'delivery_code' => 'E405',
                'country' => 'INDONESIA',
                'state' => 'BANTEN',
                'town' => 'SERPONG',
                'town_section' => 'BSD',
                'bill_to_name' => 'ACOSTA SUPER FOOD, PT',
                'bill_to_address' => 'JL. BSD RAYA NO. 1, SERPONG',
                'ship_to_name' => 'ACOSTA WAREHOUSE',
                'ship_to_address' => 'JL. LINTAS TIMUR NO. 88, BSD - SERPONG',
                'contact_person' => 'BUDI',
                'tel_no' => '021-7890123',
                'fax_no' => '021-6543210',
                'email' => 'acosta.wh@example.com',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'customer_code' => '000502',
                'delivery_code' => 'F506',
                'country' => 'INDONESIA',
                'state' => 'JAWA TENGAH',
                'town' => 'SEMARANG',
                'town_section' => 'TEMBALANG',
                'bill_to_name' => 'ADHITYA SETYABUDIHITA, PT',
                'bill_to_address' => 'JL. DIPONEGORO NO. 15, SEMARANG',
                'ship_to_name' => 'ADHITYA LOGISTICS',
                'ship_to_address' => 'JL. SOEKARNO HATTA KM. 5, SEMARANG',
                'contact_person' => 'FITRI',
                'tel_no' => '024-5678901',
                'fax_no' => '024-1234567',
                'email' => 'adhitya.log@example.com',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'customer_code' => '000851',
                'delivery_code' => 'G607',
                'country' => 'INDONESIA',
                'state' => 'JAWA TIMUR',
                'town' => 'SURABAYA',
                'town_section' => 'BENOWO',
                'bill_to_name' => 'ADIGUNA GEMILANG, PT',
                'bill_to_address' => 'JL. PAHLAWAN NO. 1, SURABAYA',
                'ship_to_name' => 'ADIGUNA WAREHOUSE',
                'ship_to_address' => 'JL. KEPUTIH NO. 99, SURABAYA',
                'contact_person' => 'BAYU',
                'tel_no' => '031-2345678',
                'fax_no' => '031-8765432',
                'email' => 'adiguna.wh@example.com',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'customer_code' => '000004',
                'delivery_code' => 'H708',
                'country' => 'INDONESIA',
                'state' => 'SUMATERA UTARA',
                'town' => 'MEDAN',
                'town_section' => 'AMPAL',
                'bill_to_name' => 'AGILITY INTERNATIONAL, PT',
                'bill_to_address' => 'JL. SISINGAMANGARAJA NO. 12, MEDAN',
                'ship_to_name' => 'AGILITY MEDAN',
                'ship_to_address' => 'JL. PERINTIS KEMERDEKAAN NO. 30, MEDAN',
                'contact_person' => 'DEWI',
                'tel_no' => '061-3456789',
                'fax_no' => '061-9876543',
                'email' => 'agility.medan@example.com',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'customer_code' => '000676',
                'delivery_code' => 'I809',
                'country' => 'INDONESIA',
                'state' => 'SUMATERA SELATAN',
                'town' => 'PALEMBANG',
                'town_section' => 'SEBERANG ULU I',
                'bill_to_name' => 'AGHNDO MAJU LESTARI, PT',
                'bill_to_address' => 'JL. JENDERAL SUDIRMAN NO. 50, PALEMBANG',
                'ship_to_name' => 'AGHNDO PALEMBANG',
                'ship_to_address' => 'JL. KOLONEL ATMO NO. 25, PALEMBANG',
                'contact_person' => 'HENDRA',
                'tel_no' => '0711-456789',
                'fax_no' => '0711-987654',
                'email' => 'aghndo.palembang@example.com',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'customer_code' => '000839',
                'delivery_code' => 'J910',
                'country' => 'INDONESIA',
                'state' => 'KALIMANTAN TIMUR',
                'town' => 'SAMARINDA',
                'town_section' => 'SUNGAI KUNJANG',
                'bill_to_name' => 'AGRO MEGA PERKASA, PT',
                'bill_to_address' => 'JL. M. YAMIN NO. 70, SAMARINDA',
                'ship_to_name' => 'AGRO MEGA SAMARINDA',
                'ship_to_address' => 'JL. K.H. WAHID HASYIM NO. 100, SAMARINDA',
                'contact_person' => 'RATNA',
                'tel_no' => '0541-678901',
                'fax_no' => '0541-234567',
                'email' => 'agromega.smr@example.com',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'customer_code' => '000767',
                'delivery_code' => 'K011',
                'country' => 'INDONESIA',
                'state' => 'SULAWESI SELATAN',
                'town' => 'MAKASSAR',
                'town_section' => 'PANAKKUKANG',
                'bill_to_name' => 'AGUNG KEMUNING WIJAYA, PT',
                'bill_to_address' => 'JL. PERINTIS KEMERDEKAAN NO. 20, MAKASSAR',
                'ship_to_name' => 'AGUNG MAKASSAR',
                'ship_to_address' => 'JL. AP. PETTARANI NO. 40, MAKASSAR',
                'contact_person' => 'ANTO',
                'tel_no' => '0411-789012',
                'fax_no' => '0411-345678',
                'email' => 'agung.mks@example.com',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        DB::table('customer_alternate_addresses')->insert($alternateAddresses);
    }
}
