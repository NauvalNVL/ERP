<?php

namespace App\Http\Controllers\MaterialManagement\SystemRequirement;

use App\Http\Controllers\Controller;
use App\Models\MmGlDistribution;
use App\Models\ChartOfAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class MmGlDistributionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $glDistributions = MmGlDistribution::all();
        $glAccounts = ChartOfAccount::all();
        
        return Inertia::render('material-management/system-requirement/inventory-setup/MMGLDistribution', [
            'glDistributions' => $glDistributions,
            'glAccounts' => $glAccounts,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), MmGlDistribution::rules());

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $glDistribution = MmGlDistribution::create($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'GL Distribution created successfully',
            'data' => $glDistribution,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $glDistribution = MmGlDistribution::findOrFail($id);
        
        return response()->json([
            'status' => 'success',
            'data' => $glDistribution,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $glDistribution = MmGlDistribution::findOrFail($id);
        
        $validator = Validator::make($request->all(), MmGlDistribution::rules($id));

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $glDistribution->update($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'GL Distribution updated successfully',
            'data' => $glDistribution,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $glDistribution = MmGlDistribution::findOrFail($id);
        
        $glDistribution->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'GL Distribution deleted successfully',
        ]);
    }

    /**
     * Get a list of all GL distributions.
     */
    public function getGlDistributions()
    {
        $glDistributions = MmGlDistribution::all();
        
        return response()->json([
            'status' => 'success',
            'data' => $glDistributions,
        ]);
    }

    /**
     * Display the view & print page for GL distributions.
     */
    public function viewPrint()
    {
        $glDistributions = MmGlDistribution::all();
        
        return Inertia::render('material-management/system-requirement/inventory-setup/ViewPrintGLDistribution', [
            'glDistributions' => $glDistributions,
        ]);
    }
} 