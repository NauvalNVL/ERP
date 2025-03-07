<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
        $validated = $request->validate([
            'business_name' => 'required|max:100',
            'registration_number' => 'required|max:50',
            'cps_version' => 'required|max:20',
            'logo_appear_time' => 'required|integer|min:1|max:60',
            'date_validity_start' => 'required|date',
            'date_validity_end' => 'required|date|after:date_validity_start',
            'financial_system_code' => 'required|max:10',
            'period_end_closing' => 'required|max:50'
        ]);

        try {
            DB::table('system_configurations')->update($validated);
            return redirect()->back()->with('success', 'Konfigurasi berhasil diperbarui');
        } catch (\Exception $e) {
            Log::error('Error updating system config: '.$e->getMessage());
            return back()->with('error', 'Gagal menyimpan konfigurasi');
        }
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