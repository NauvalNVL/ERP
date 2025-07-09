<?php

namespace App\Http\Controllers\MaterialManagement\SystemRequirement;

use App\Http\Controllers\Controller;
use App\Models\MmConfig;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MmConfigController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $config = MmConfig::first();
        if (!$config) {
            $config = MmConfig::create([]);
        }

        return Inertia::render('material-management/system-requirement/standard-setup/Configuration', [
            'config' => $config,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $config = MmConfig::first();
        $config->update($request->all());

        return redirect()->back()->with('success', 'Configuration saved successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(MmConfig $mmConfig)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MmConfig $mmConfig)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MmConfig $mmConfig)
    {
        $mmConfig->update($request->all());

        return redirect()->back()->with('success', 'Configuration saved successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MmConfig $mmConfig)
    {
        //
    }

    public function getSourceCodes()
    {
        return response()->json([
            ['code' => 'AP-DO', 'name' => 'AP PEMBEBANAN MANUAL NO PR/PO'],
            ['code' => 'AP-INV', 'name' => 'INVOICE SUPPLIER'],
            ['code' => 'AP-KRTS', 'name' => 'AP PENERIMAAN KERTAS'],
            ['code' => 'AP-NCPS', 'name' => 'AP NON CPS'],
            ['code' => 'AP-RC', 'name' => 'PENERIMAAN BARANG RC'],
            ['code' => 'AP-RT', 'name' => 'RETUR BARANG'],
            ['code' => 'AP-DN', 'name' => 'PENGURANGАN HUTАNG'],
            ['code' => 'AP-CN', 'name' => 'PENAMBAHAN HUTANG']
        ]);
    }

    public function getGlDistributions()
    {
        return response()->json([
            ['code' => 'ADM', 'name' => 'BIAYA BANK', 'account' => '630901-00-00-00'],
            ['code' => 'ALAT PABRIK', 'name' => 'BEBAN PERALATAN PABRIK DIGITAL P', 'account' => '612007-00-00-01'],
            ['code' => 'ALAT PABRIK', 'name' => 'BEBAN PERALATAN PABRIK', 'account' => '612007-00-00-01'],
            ['code' => 'AS BANGUNAN', 'name' => 'ASURANSI BANGUNAN', 'account' => '612016-00-00-01'],
            ['code' => 'AS YMHD', 'name' => 'ASURANSI YMHD', 'account' => '214020-00-00-00'],
            ['code' => 'ASTEK', 'name' => 'BEBAN ASTEK PRODUKSI BULANAN', 'account' => '611001-00-00-08'],
            ['code' => 'ASTEK-YMHD', 'name' => 'ASTEK YMHD', 'account' => '214004-00-00-00'],
            ['code' => 'ASTEK-MKT', 'name' => 'BEBAN ASTEK PENJUALAN', 'account' => '620101-00-00-08'],
            ['code' => 'ASURANSI', 'name' => 'BEBAN ASURANSI KEND', 'account' => '631101-00-00-00'],
            ['code' => 'AYAT SILANG', 'name' => 'POS SILANG', 'account' => '111401-00-00-00'],
            ['code' => 'HUTANG', 'name' => 'PENGURANG HUTANG', 'account' => '210001-00-00-00']
        ]);
    }

    /**
     * API endpoint to get the configuration.
     */
    public function apiGetConfig()
    {
        $config = MmConfig::first();
        if (!$config) {
            $config = MmConfig::create([]);
        }

        return response()->json($config);
    }

    /**
     * API endpoint to update the configuration.
     */
    public function apiUpdateConfig(Request $request)
    {
        $config = MmConfig::first();
        if (!$config) {
            $config = MmConfig::create([]);
        }

        $config->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Configuration updated successfully.',
            'config' => $config
        ]);
    }
}
