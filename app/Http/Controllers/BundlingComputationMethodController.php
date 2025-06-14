<?php

namespace App\Http\Controllers;

use App\Models\BundlingComputationMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class BundlingComputationMethodController extends Controller
{
    /**
     * Display the bundling computation method page.
     */
    public function index()
    {
        return Inertia::render('sales-management/standard-formula/bundling-computation-method/ComputationMethod');
    }

    /**
     * Get all bundling computation methods for API.
     */
    public function apiIndex()
    {
        try {
            $methods = BundlingComputationMethod::orderBy('id', 'asc')->get();
            
            return response()->json([
                'status' => 'success',
                'data' => $methods
            ]);
        } catch (\Exception $e) {
            Log::error('Error retrieving bundling computation methods: ' . $e->getMessage());
            
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve bundling computation methods',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get a specific bundling computation method.
     */
    public function apiShow($id)
    {
        try {
            $method = BundlingComputationMethod::findOrFail($id);
            
            return response()->json([
                'status' => 'success',
                'data' => $method
            ]);
        } catch (\Exception $e) {
            Log::error('Error retrieving bundling computation method: ' . $e->getMessage());
            
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve bundling computation method',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a new bundling computation method.
     */
    public function apiStore(Request $request)
    {
        try {
            $validated = $request->validate([
                'product_name' => 'nullable|string|max:255',
                'product_design' => 'nullable|string|max:255',
                'product' => 'nullable|string|max:255',
                'flute' => 'nullable|string|max:255',
                'formula_divisor' => 'required|integer|min:0',
                'formula_pieces' => 'required|integer|min:0',
                'height_type' => 'required|in:internal,extended',
                'rounding_type' => 'required|in:up,down',
                'is_compute' => 'sometimes|boolean',
            ]);

            // Add user who created the record
            $validated['created_by'] = Auth::user()->name ?? 'System';

            $method = BundlingComputationMethod::create($validated);

            return response()->json([
                'status' => 'success',
                'message' => 'Bundling computation method created successfully',
                'data' => $method
            ]);
        } catch (\Exception $e) {
            Log::error('Error creating bundling computation method: ' . $e->getMessage());
            
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create bundling computation method',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update an existing bundling computation method.
     */
    public function apiUpdate(Request $request, $id)
    {
        try {
            $method = BundlingComputationMethod::findOrFail($id);
            
            $validated = $request->validate([
                'product_name' => 'nullable|string|max:255',
                'product_design' => 'nullable|string|max:255',
                'product' => 'nullable|string|max:255',
                'flute' => 'nullable|string|max:255',
                'formula_divisor' => 'required|integer|min:0',
                'formula_pieces' => 'required|integer|min:0',
                'height_type' => 'required|in:internal,extended',
                'rounding_type' => 'required|in:up,down',
                'is_compute' => 'sometimes|boolean',
            ]);

            // Add user who updated the record
            $validated['updated_by'] = Auth::user()->name ?? 'System';

            $method->update($validated);

            return response()->json([
                'status' => 'success',
                'message' => 'Bundling computation method updated successfully',
                'data' => $method
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating bundling computation method: ' . $e->getMessage());
            
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update bundling computation method',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a bundling computation method.
     */
    public function apiDestroy($id)
    {
        try {
            $method = BundlingComputationMethod::findOrFail($id);
            $method->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Bundling computation method deleted successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting bundling computation method: ' . $e->getMessage());
            
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete bundling computation method',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * API: Seed initial data for bundling computation methods
     */
    public function apiSeed()
    {
        try {
            // Create sample data
            $sampleData = [
                [
                    'product_design' => 'APFI',
                    'product' => 'BKS',
                    'flute' => 'AB',
                    'formula_pieces' => 0,
                    'is_compute' => false,
                    'formula_divisor' => 1,
                    'height_type' => 'internal',
                    'rounding_type' => 'down',
                    'created_by' => 'System',
                ],
                [
                    'product_design' => 'APFI',
                    'product' => 'BKS',
                    'flute' => 'AC',
                    'formula_pieces' => 0,
                    'is_compute' => false,
                    'formula_divisor' => 1,
                    'height_type' => 'internal',
                    'rounding_type' => 'down',
                    'created_by' => 'System',
                ],
                [
                    'product_design' => 'APFI',
                    'product' => 'BKS',
                    'flute' => 'AF',
                    'formula_pieces' => 0,
                    'is_compute' => false,
                    'formula_divisor' => 1,
                    'height_type' => 'internal',
                    'rounding_type' => 'down',
                    'created_by' => 'System',
                ],
                [
                    'product_design' => 'APFI',
                    'product' => 'BKS',
                    'flute' => 'BC',
                    'formula_pieces' => 0,
                    'is_compute' => false,
                    'formula_divisor' => 1,
                    'height_type' => 'internal',
                    'rounding_type' => 'down',
                    'created_by' => 'System',
                ],
            ];

            foreach ($sampleData as $data) {
                BundlingComputationMethod::updateOrCreate(
                    [
                        'product_design' => $data['product_design'],
                        'product' => $data['product'],
                        'flute' => $data['flute'],
                    ],
                    $data
                );
            }
            
            return response()->json([
                'success' => true,
                'message' => "Bundling computation method data seeded successfully"
            ]);
        } catch (\Exception $e) {
            Log::error('Error seeding bundling computation method data: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to seed bundling computation method data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * View and print bundling computation methods
     */
    public function viewPrint()
    {
        return Inertia::render('sales-management/standard-formula/bundling-computation-method/ViewPrintComputationMethod');
    }
} 