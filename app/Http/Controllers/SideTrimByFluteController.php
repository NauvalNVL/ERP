<?php

namespace App\Http\Controllers;

use App\Models\PaperFlute;
use App\Models\SideTrimByFlute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class SideTrimByFluteController extends Controller
{
    /**
     * Display the side trim by flute page.
     */
    public function index()
    {
        return Inertia::render('sales-management/standard-formula/setup-corrugator-run-size-formula/SideTrimByFlute');
    }

    /**
     * Display the view and print page for side trims by flute.
     */
    public function viewPrint()
    {
        return Inertia::render('sales-management/standard-formula/setup-corrugator-run-size-formula/ViewPrintSideTrimByFlute');
    }

    /**
     * Get all side trim data for API.
     */
    public function apiIndex()
    {
        try {
            $sideTrims = SideTrimByFlute::with('paperFlute')
                ->join('paper_flutes', 'side_trims_by_flute.flute_id', '=', 'paper_flutes.id')
                ->orderBy('paper_flutes.code', 'asc')
                ->orderBy('side_trims_by_flute.compute', 'asc')
                ->select('side_trims_by_flute.*') // Prevent column conflicts
                ->get();
            
            return response()->json([
                'status' => 'success',
                'data' => $sideTrims
            ]);
        } catch (\Exception $e) {
            Log::error('Error retrieving side trims by flute: ' . $e->getMessage());
            
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve side trims data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a new side trim record or update if exists.
     */
    public function apiStore(Request $request)
    {
        try {
            $validated = $request->validate([
                'flute_id' => 'required|exists:paper_flutes,id',
                'length_add' => 'required|integer|min:0',
                'length_less' => 'required|integer|min:0',
                'compute' => 'required|boolean',
            ]);

            // Check if a record with this flute_id and compute status already exists
            $existing = SideTrimByFlute::where('flute_id', $validated['flute_id'])
                ->where('compute', $validated['compute'])
                ->first();

            if ($existing) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'A record with this flute and compute status already exists.',
                ], 422);
            }

            $sideTrim = SideTrimByFlute::create($validated);

            return response()->json([
                'status' => 'success',
                'message' => 'Side trim data saved successfully',
                'data' => $sideTrim
            ]);
        } catch (\Exception $e) {
            Log::error('Error saving side trim by flute: ' . $e->getMessage());
            
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to save side trim data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update an existing side trim record.
     */
    public function apiUpdate(Request $request, $id)
    {
        $validated = $request->validate([
            'length_add' => 'sometimes|numeric|min:0',
            'length_less' => 'sometimes|numeric|min:0',
            'compute' => 'sometimes|boolean',
        ]);

        try {
            DB::transaction(function () use ($id, $validated) {
                $sideTrimToUpdate = SideTrimByFlute::findOrFail($id);

                // If 'compute' is being set to true, ensure any other record for this flute is set to false.
                if (isset($validated['compute']) && $validated['compute'] === true) {
                    SideTrimByFlute::where('flute_id', $sideTrimToUpdate->flute_id)
                        ->where('id', '!=', $id)
                        ->update(['compute' => false]);
                }
                
                $sideTrimToUpdate->update($validated);
            });

            // Reload the updated record with its relationship to return to the frontend.
            $updatedTrim = SideTrimByFlute::with('paperFlute')->findOrFail($id);

            return response()->json([
                'status' => 'success',
                'message' => 'Side trim data updated successfully.',
                'data' => $updatedTrim
            ]);

        } catch (\Exception $e) {
            Log::error('Error updating side trim by flute: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update side trim data. ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete a side trim record.
     */
    public function apiDestroy($id)
    {
        try {
            $sideTrim = SideTrimByFlute::findOrFail($id);
            $sideTrim->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Side trim data deleted successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting side trim by flute: ' . $e->getMessage());
            
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete side trim data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export side trim data to Excel.
     */
    public function apiExport()
    {
        try {
            $sideTrims = SideTrimByFlute::with('paperFlute')->get();
            
            // Transform data for export
            $exportData = $sideTrims->map(function ($trim) {
                return [
                    'Flute Code' => $trim->paperFlute ? $trim->paperFlute->code : 'N/A',
                    'Flute Name' => $trim->paperFlute ? $trim->paperFlute->name : 'N/A',
                    'Length Add (mm)' => $trim->length_add,
                    'Length Less (mm)' => $trim->length_less,
                    'Composite' => $trim->compute ? 'Yes' : 'No',
                    'Created At' => $trim->created_at->format('Y-m-d H:i:s'),
                    'Updated At' => $trim->updated_at->format('Y-m-d H:i:s'),
                ];
            });
            
            // Return JSON data for now
            return response()->json([
                'status' => 'success',
                'data' => $exportData,
                'filename' => 'side_trims_by_flute_' . date('Y-m-d') . '.xlsx'
            ]);
        } catch (\Exception $e) {
            Log::error('Error exporting side trim data: ' . $e->getMessage());
            
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to export side trim data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * API: Seed initial data for side trims by flute
     */
    public function apiSeed()
    {
        try {
            // Get all flutes
            $flutes = PaperFlute::all();
            
            $count = 0;
            
            // Create sample data for each flute
            foreach ($flutes as $flute) {
                // Create a non-composite entry
                SideTrimByFlute::updateOrCreate(
                    [
                        'flute_id' => $flute->id,
                        'compute' => false,
                    ],
                    [
                        'length_add' => rand(10, 30),
                        'length_less' => rand(5, 20),
                    ]
                );
                $count++;
                
                // Create a composite entry
                SideTrimByFlute::updateOrCreate(
                    [
                        'flute_id' => $flute->id,
                        'compute' => true,
                    ],
                    [
                        'length_add' => rand(20, 40),
                        'length_less' => rand(10, 25),
                    ]
                );
                $count++;
            }
            
            return response()->json([
                'success' => true,
                'message' => "Side trim by flute data seeded successfully ($count records created)"
            ]);
        } catch (\Exception $e) {
            Log::error('Error seeding side trim by flute data: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to seed data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Diagnostic method to help understand compute toggle issues
     */
    public function diagnosticComputeToggle(Request $request)
    {
        try {
            // Get all side trims
            $sideTrims = SideTrimByFlute::with('paperFlute')->get();
            
            // Prepare diagnostic information
            $diagnosticInfo = $sideTrims->map(function ($trim) {
                return [
                    'id' => $trim->id,
                    'flute_code' => $trim->paperFlute->code ?? 'N/A',
                    'compute' => $trim->compute,
                    'length_add' => $trim->length_add,
                    'length_less' => $trim->length_less
                ];
            });
            
            return response()->json([
                'status' => 'success',
                'message' => 'Diagnostic information retrieved',
                'data' => $diagnosticInfo
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Diagnostic Compute Toggle Error: ' . $e->getMessage());
            
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve diagnostic information',
                'error' => $e->getMessage()
            ], 500);
        }
    }
} 