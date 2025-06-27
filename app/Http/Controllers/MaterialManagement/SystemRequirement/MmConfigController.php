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
