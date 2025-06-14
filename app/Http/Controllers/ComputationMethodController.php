<?php

namespace App\Http\Controllers;

use App\Models\ComputationMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ComputationMethodController extends Controller
{
    /**
     * Display the computation method page.
     */
    public function index()
    {
        return Inertia::render('sales-management/standard-formula/stitching-computation-method/ComputationMethod');
    }

    /**
     * Get all computation methods for API.
     */
    public function apiIndex()
    {
        try {
            $methods = ComputationMethod::orderBy('id', 'asc')->get();
            
            return response()->json([
                'status' => 'success',
                'data' => $methods
            ]);
        } catch (\Exception $e) {
            Log::error('Error retrieving computation methods: ' . $e->getMessage());
            
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve computation methods',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get a specific computation method.
     */
    public function apiShow($id)
    {
        try {
            $method = ComputationMethod::findOrFail($id);
            
            return response()->json([
                'status' => 'success',
                'data' => $method
            ]);
        } catch (\Exception $e) {
            Log::error('Error retrieving computation method: ' . $e->getMessage());
            
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve computation method',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a new computation method.
     */
    public function apiStore(Request $request)
    {
        try {
            $validated = $request->validate([
                'formula_divisor' => 'required|integer|min:0',
                'formula_pieces' => 'required|integer|min:0',
                'height_type' => 'required|in:internal,extended',
                'rounding_type' => 'required|in:up,down',
                'is_active' => 'sometimes|boolean',
            ]);

            // Add user who created the record
            $validated['created_by'] = Auth::user()->name ?? 'System';

            $method = ComputationMethod::create($validated);

            return response()->json([
                'status' => 'success',
                'message' => 'Computation method created successfully',
                'data' => $method
            ]);
        } catch (\Exception $e) {
            Log::error('Error creating computation method: ' . $e->getMessage());
            
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create computation method',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update an existing computation method.
     */
    public function apiUpdate(Request $request, $id)
    {
        try {
            $method = ComputationMethod::findOrFail($id);
            
            $validated = $request->validate([
                'formula_divisor' => 'required|integer|min:0',
                'formula_pieces' => 'required|integer|min:0',
                'height_type' => 'required|in:internal,extended',
                'rounding_type' => 'required|in:up,down',
                'is_active' => 'sometimes|boolean',
            ]);

            // Add user who updated the record
            $validated['updated_by'] = Auth::user()->name ?? 'System';

            $method->update($validated);

            return response()->json([
                'status' => 'success',
                'message' => 'Computation method updated successfully',
                'data' => $method
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating computation method: ' . $e->getMessage());
            
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update computation method',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a computation method.
     */
    public function apiDestroy($id)
    {
        try {
            $method = ComputationMethod::findOrFail($id);
            $method->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Computation method deleted successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting computation method: ' . $e->getMessage());
            
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete computation method',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export computation methods to CSV.
     */
    public function apiExport()
    {
        try {
            $methods = ComputationMethod::orderBy('id', 'asc')->get();
            
            // Set the filename
            $filename = 'computation_methods_' . date('Y-m-d') . '.csv';
            
            // Create CSV content with headers
            $headers = [
                'ID', 
                'Formula Divisor', 
                'Formula Pieces', 
                'Height Type', 
                'Rounding Type', 
                'Active', 
                'Created By', 
                'Updated By',
                'Created At', 
                'Updated At'
            ];
            
            $callback = function() use ($methods, $headers) {
                $file = fopen('php://output', 'w');
                
                // Add headers
                fputcsv($file, $headers);
                
                // Add data rows
                foreach ($methods as $method) {
                    fputcsv($file, [
                        $method->id,
                        $method->formula_divisor,
                        $method->formula_pieces,
                        $method->height_type,
                        $method->rounding_type,
                        $method->is_active ? 'Yes' : 'No',
                        $method->created_by,
                        $method->updated_by,
                        $method->created_at->format('Y-m-d H:i:s'),
                        $method->updated_at->format('Y-m-d H:i:s'),
                    ]);
                }
                
                fclose($file);
            };
            
            // Return a downloadable response
            return response()->stream($callback, 200, [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => "attachment; filename=\"$filename\"",
            ]);
        } catch (\Exception $e) {
            Log::error('Error exporting computation methods: ' . $e->getMessage());
            
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to export computation methods',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * API: Seed initial data for computation methods
     */
    public function apiSeed()
    {
        try {
            // Create a default computation method if none exists
            if (ComputationMethod::count() === 0) {
                ComputationMethod::create([
                    'formula_divisor' => 1,
                    'formula_pieces' => 0,
                    'height_type' => 'internal',
                    'rounding_type' => 'down',
                    'is_active' => true,
                    'created_by' => 'System',
                ]);
            }
            
            return response()->json([
                'success' => true,
                'message' => "Computation method data seeded successfully"
            ]);
        } catch (\Exception $e) {
            Log::error('Error seeding computation method data: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to seed data: ' . $e->getMessage()
            ], 500);
        }
    }
} 