<?php

namespace App\Http\Controllers;

use App\Models\AnalysisCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class AnalysisCodeController extends Controller
{
    /**
     * Display the analysis code management page
     */
    public function index()
    {
        $analysisCodes = AnalysisCode::orderBy('analysis_code')->get();
        
        return Inertia::render('sales-management/system-requirement/standard-requirement/AnalysisCode', [
            'analysisCodes' => $analysisCodes
        ]);
    }

    /**
     * Get all analysis codes (API)
     */
    public function apiIndex()
    {
        try {
            $analysisCodes = AnalysisCode::orderBy('analysis_code')->get();
            return response()->json($analysisCodes);
        } catch (\Exception $e) {
            Log::error('Error fetching analysis codes', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Failed to fetch analysis codes'], 500);
        }
    }

    /**
     * Get a specific analysis code (API)
     */
    public function show($code)
    {
        try {
            $analysisCode = AnalysisCode::find($code);
            
            if (!$analysisCode) {
                return response()->json(['message' => 'Analysis code not found'], 404);
            }
            
            return response()->json($analysisCode);
        } catch (\Exception $e) {
            Log::error('Error fetching analysis code', ['code' => $code, 'error' => $e->getMessage()]);
            return response()->json(['error' => 'Failed to fetch analysis code'], 500);
        }
    }

    /**
     * Store a new analysis code (API)
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'analysis_code' => 'required|string|max:50|unique:analysis_codes,analysis_code',
                'analysis_name' => 'required|string|max:255',
                'analysis_group' => 'required|string|max:50',
                'analysis_group2' => 'required|string|max:50',
            ]);

            // Validate business rules
            $this->validateAnalysisGroupRules($validated['analysis_group'], $validated['analysis_group2']);

            $analysisCode = AnalysisCode::create($validated);

            Log::info('Analysis code created', ['code' => $analysisCode->analysis_code]);

            return response()->json([
                'success' => true,
                'message' => 'Analysis code created successfully',
                'data' => $analysisCode
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error creating analysis code', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update an existing analysis code (API)
     */
    public function update(Request $request, $code)
    {
        try {
            $analysisCode = AnalysisCode::find($code);
            
            if (!$analysisCode) {
                return response()->json([
                    'success' => false,
                    'message' => 'Analysis code not found'
                ], 404);
            }

            $validated = $request->validate([
                'analysis_name' => 'required|string|max:255',
                'analysis_group' => 'required|string|max:50',
                'analysis_group2' => 'required|string|max:50',
            ]);

            // Validate business rules
            $this->validateAnalysisGroupRules($validated['analysis_group'], $validated['analysis_group2']);

            $analysisCode->update($validated);

            Log::info('Analysis code updated', ['code' => $code]);

            return response()->json([
                'success' => true,
                'message' => 'Analysis code updated successfully',
                'data' => $analysisCode
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error updating analysis code', ['code' => $code, 'error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete an analysis code (API)
     */
    public function destroy($code)
    {
        try {
            $analysisCode = AnalysisCode::find($code);
            
            if (!$analysisCode) {
                return response()->json([
                    'success' => false,
                    'message' => 'Analysis code not found'
                ], 404);
            }

            $analysisCode->delete();

            Log::info('Analysis code deleted', ['code' => $code]);

            return response()->json([
                'success' => true,
                'message' => 'Analysis code deleted successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting analysis code', ['code' => $code, 'error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete analysis code'
            ], 500);
        }
    }

    /**
     * Validate business rules for Analysis Group 2
     *
     * 'AM', 'CM', 'CL' & 'UN' of Analysis Group 2 are only applied to Analysis Group = 'SO'
     */
    private function validateAnalysisGroupRules($group, $group2)
    {
        $soOnlyGroups = ['AM', 'CM', 'CL', 'UN'];
        $mcOnlyGroups = ['CS', 'RS'];

        if (in_array($group2, $soOnlyGroups) && $group !== 'SO') {
            throw new \Exception("Analysis Group2 '{$group2}' can only be used with Analysis Group 'SO-Sales Order'");
        }

        if (in_array($group2, $mcOnlyGroups) && $group !== 'MC') {
            throw new \Exception("Analysis Group2 '{$group2}' can only be used with Analysis Group 'MC-Master Card'");
        }
    }
}