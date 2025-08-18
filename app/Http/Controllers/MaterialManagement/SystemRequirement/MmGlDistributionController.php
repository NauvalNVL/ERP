<?php

namespace App\Http\Controllers\MaterialManagement\SystemRequirement;

use App\Http\Controllers\Controller;
use App\Models\MmGlDistribution;
use App\Models\ChartOfAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class MmGlDistributionController extends Controller
{
    public function index()
    {
        return Inertia::render('material-management/system-requirement/inventory-setup/MMGLDistribution', [
            'glDistributions' => MmGlDistribution::with('chartOfAccount')->get(),
            'chartOfAccounts' => ChartOfAccount::all()
        ]);
    }

    public function getGlDistributions()
    {
        try {
            $glDistributions = MmGlDistribution::with('chartOfAccount')->get();
            return response()->json($glDistributions);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch GL distributions'], 500);
        }
    }

    public function getGlDistributionsForPrint()
    {
        try {
            $glDistributions = MmGlDistribution::with('chartOfAccount')->get();
            return response()->json($glDistributions);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch GL distributions for print'], 500);
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'gl_dist_code' => 'required|string|max:10|unique:mm_gl_distributions,gl_dist_code',
            'gl_dist_name' => 'required|string|max:100',
            'gl_account' => 'required|string|max:20',
            'gl_account_name' => 'nullable|string|max:100',
            'is_linked' => 'boolean',
            'is_active' => 'boolean'
        ]);

        $glDistribution = MmGlDistribution::create($validated);

        return response()->json($glDistribution);
    }

    public function update(Request $request, MmGlDistribution $glDistribution)
    {
        $validated = $request->validate([
            'gl_dist_name' => 'required|string|max:100',
            'gl_account' => 'required|string|max:20',
            'gl_account_name' => 'nullable|string|max:100',
            'is_linked' => 'boolean',
            'is_active' => 'boolean'
        ]);

        $glDistribution->update($validated);

        return response()->json($glDistribution);
    }

    public function destroy(MmGlDistribution $glDistribution)
    {
        $glDistribution->delete();
        return response()->json(['message' => 'GL Distribution deleted successfully']);
    }

    public function getChartOfAccounts()
    {
        try {
            $accounts = ChartOfAccount::all();
            return response()->json($accounts);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch chart of accounts'], 500);
        }
    }

    public function print()
    {
        $glDistributions = MmGlDistribution::with('chartOfAccount')->get();
        return Inertia::render('material-management/system-requirement/inventory-setup/MMGLDistributionPrint', [
            'glDistributions' => $glDistributions
        ]);
    }
} 