<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SystemConfigurationController extends Controller
{
    public function index()
    {
        $config = DB::table('system_configurations')->first();
        
        if (!$config) {
            DB::table('system_configurations')->insert([
                'business_name' => 'Default Business',
                'registration_number' => '123456789',
                'cps_version' => '1.0.0',
                'display_registration' => true,
                'logo_appear_time' => 5,
                'date_validity_start' => '2024-01-01',
                'date_validity_end' => '2024-12-31',
                'turbo_loop_engine' => 'Enabled',
                'financial_system_code' => 'FS001',
                'period_end_closing' => 'X-Check & Block',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $config = DB::table('system_configurations')->first();
        }

        $configModules = [
            ['title' => 'Tax Data', 'icon' => 'percent', 'color' => 'bg-purple-100 text-purple-800'],
            ['title' => 'Base Currency', 'icon' => 'dollar-sign', 'color' => 'bg-green-100 text-green-800'],
            ['title' => 'Password Policy', 'icon' => 'lock', 'color' => 'bg-red-100 text-red-800'],
            ['title' => 'Security Policy', 'icon' => 'shield-alt', 'color' => 'bg-blue-100 text-blue-800'],
            ['title' => 'Email', 'icon' => 'envelope', 'color' => 'bg-cyan-100 text-cyan-800'],
            ['title' => '3rd Party Software', 'icon' => 'cubes', 'color' => 'bg-orange-100 text-orange-800']
        ];
        
        return view('system-setup.system-configuration', compact('config', 'configModules'));
    }

    public function update(Request $request)
    {
        DB::table('system_configurations')->update([
            'business_name' => $request->business_name,
            'registration_number' => $request->registration_number,
            'cps_version' => $request->cps_version,
            'display_registration' => $request->has('display_registration'),
            'logo_appear_time' => $request->logo_appear_time,
            'date_validity_start' => $request->date_validity_start,
            'date_validity_end' => $request->date_validity_end,
            'turbo_loop_engine' => $request->turbo_loop_engine,
            'financial_system_code' => $request->financial_system_code,
            'period_end_closing' => $request->period_end_closing,
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Configuration updated successfully!');
    }

    public function showTaxData()
    {
        return view('system-setup/system-configuration.tax-data');
    }

    public function updateTaxData(Request $request)
    {
        // Logika untuk update tax data
        // ...

        return redirect()->back()->with('success', 'Tax data updated successfully!');
    }
} 