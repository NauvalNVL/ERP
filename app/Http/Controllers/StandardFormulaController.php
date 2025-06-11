<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\StandardFormula;
use Illuminate\Support\Facades\Artisan;

class StandardFormulaController extends Controller
{
    public function index()
    {
        return Inertia::render('sales-management/standard-formula/SetupStandardFormulaConfiguration', [
            'configuration' => $this->getConfiguration()
        ]);
    }

    public function scoringFormula()
    {
        return Inertia::render('sales-management/standard-formula/setup-scoring-formula/ScoringFormula');
    }

    // API method to get configuration
    public function apiIndex()
    {
        return response()->json($this->getConfiguration());
    }

    // API method to store configuration
    public function apiStore(Request $request)
    {
        $validated = $request->validate([
            'activateStandardFormula' => 'required|in:yes,no',
            'economicRunSize' => 'required|in:average,highest',
            'checkRunSizeResult' => 'boolean',
            'masterCard' => 'required|in:free,accept',
            'salesOrder' => 'required|in:free,accept',
            'workOrder' => 'required|in:free,accept',
        ]);

        // Map frontend field names to database field names
        $configData = [
            'activate_standard_formula' => $validated['activateStandardFormula'],
            'economic_run_size' => $validated['economicRunSize'],
            'check_run_size_result' => $validated['checkRunSizeResult'],
            'master_card' => $validated['masterCard'],
            'sales_order' => $validated['salesOrder'],
            'work_order' => $validated['workOrder'],
        ];

        // Get the first configuration or create a new one
        $config = StandardFormula::first();
        if ($config) {
            $config->update($configData);
        } else {
            StandardFormula::create($configData);
        }

        return response()->json([
            'message' => 'Standard formula configuration saved successfully.'
        ], 200);
    }

    // API method to seed data
    public function apiSeed()
    {
        try {
            Artisan::call('db:seed', [
                '--class' => 'Database\\Seeders\\StandardFormulaSeeder'
            ]);
            
            return response()->json([
                'message' => 'Standard formula configuration seeded successfully.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error seeding standard formula configuration.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'activateStandardFormula' => 'required|in:yes,no',
            'economicRunSize' => 'required|in:average,highest',
            'checkRunSizeResult' => 'boolean',
            'masterCard' => 'required|in:free,accept',
            'salesOrder' => 'required|in:free,accept',
            'workOrder' => 'required|in:free,accept',
        ]);

        // Map frontend field names to database field names
        $configData = [
            'activate_standard_formula' => $validated['activateStandardFormula'],
            'economic_run_size' => $validated['economicRunSize'],
            'check_run_size_result' => $validated['checkRunSizeResult'],
            'master_card' => $validated['masterCard'],
            'sales_order' => $validated['salesOrder'],
            'work_order' => $validated['workOrder'],
        ];

        // Get the first configuration or create a new one
        $config = StandardFormula::first();
        if ($config) {
            $config->update($configData);
        } else {
            StandardFormula::create($configData);
        }

        return redirect()->back()->with('success', 'Standard formula configuration saved successfully.');
    }

    private function getConfiguration()
    {
        // Get the first configuration or use default values
        $config = StandardFormula::first();
        
        if ($config) {
            return [
                'activateStandardFormula' => $config->activate_standard_formula,
                'economicRunSize' => $config->economic_run_size,
                'checkRunSizeResult' => (bool) $config->check_run_size_result,
                'masterCard' => $config->master_card,
                'salesOrder' => $config->sales_order,
                'workOrder' => $config->work_order,
            ];
        }
        
        // Default values if no configuration exists
        return [
            'activateStandardFormula' => 'yes',
            'economicRunSize' => 'average',
            'checkRunSizeResult' => true,
            'masterCard' => 'free',
            'salesOrder' => 'free',
            'workOrder' => 'free',
        ];
    }
} 