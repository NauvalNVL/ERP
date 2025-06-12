<?php

namespace App\Http\Controllers;

use App\Models\CorrugatorConfig;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Validator;

class CorrugatorConfigController extends Controller
{
    /**
     * Display the corrugator configuration page.
     */
    public function index()
    {
        return Inertia::render('sales-management/standard-formula/setup-corrugator-run-size-formula/Corrugator');
    }

    /**
     * API: Get all corrugator configurations
     */
    public function apiIndex()
    {
        $configs = CorrugatorConfig::all();
        return response()->json($configs);
    }

    /**
     * API: Get a specific corrugator configuration
     */
    public function apiShow($id)
    {
        $config = CorrugatorConfig::findOrFail($id);
        return response()->json($config);
    }

    /**
     * API: Store a new corrugator configuration
     */
    public function apiStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'corrugator_id' => 'required|string|max:10',
            'min_sheet_length' => 'required|integer|min:1',
            'max_sheet_length' => 'required|integer|min:1',
            'min_sheet_width' => 'required|integer|min:1',
            'max_sheet_width' => 'required|integer|min:1',
            'is_sheet_length_multiplied' => 'sometimes|boolean',
            'is_min_raw_multiplied' => 'sometimes|boolean',
            'validate_sheet_width' => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $config = CorrugatorConfig::create($request->all());
        return response()->json($config, 201);
    }

    /**
     * API: Update a corrugator configuration
     */
    public function apiUpdate(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'corrugator_id' => 'sometimes|string|max:10',
            'min_sheet_length' => 'sometimes|integer|min:1',
            'max_sheet_length' => 'sometimes|integer|min:1',
            'min_sheet_width' => 'sometimes|integer|min:1',
            'max_sheet_width' => 'sometimes|integer|min:1',
            'is_sheet_length_multiplied' => 'sometimes|boolean',
            'is_min_raw_multiplied' => 'sometimes|boolean',
            'validate_sheet_width' => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $config = CorrugatorConfig::findOrFail($id);
        $config->update($request->all());
        
        return response()->json($config);
    }

    /**
     * API: Delete a corrugator configuration
     */
    public function apiDestroy($id)
    {
        $config = CorrugatorConfig::findOrFail($id);
        $config->delete();
        
        return response()->json(null, 204);
    }

    /**
     * API: Run the seeder to populate default data
     */
    public function apiSeed()
    {
        $seeder = new \Database\Seeders\CorrugatorConfigSeeder();
        $seeder->run();
        
        return response()->json(['message' => 'Corrugator configurations seeded successfully']);
    }
} 