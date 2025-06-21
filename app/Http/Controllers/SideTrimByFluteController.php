<?php

namespace App\Http\Controllers;

use App\Models\PaperFlute;
use App\Models\SideTrimByFlute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

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
            $sideTrims = SideTrimByFlute::with('paperFlute')->get();
            
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

            $sideTrim = SideTrimByFlute::updateOrCreate(
                [
                    'flute_id' => $validated['flute_id'],
                    'compute' => $validated['compute'],
                ],
                [
                    'length_add' => $validated['length_add'],
                    'length_less' => $validated['length_less'],
                ]
            );

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
        try {
            $sideTrim = SideTrimByFlute::findOrFail($id);
            
            $validated = $request->validate([
                'flute_id' => 'required|exists:paper_flutes,id',
                'length_add' => 'required|integer|min:0',
                'length_less' => 'required|integer|min:0',
                'compute' => 'required|boolean',
            ]);

            // Check if there's already a record with the same flute_id and compute value (excluding this record)
            $duplicate = SideTrimByFlute::where('flute_id', $validated['flute_id'])
                ->where('compute', $validated['compute'])
                ->where('id', '!=', $id)
                ->first();
                
            if ($duplicate) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'A record with this flute and compute status already exists',
                ], 422);
            }

            $sideTrim->update($validated);

            // Load the relationship to include in the response
            $sideTrim->load('paperFlute');

            return response()->json([
                'status' => 'success',
                'message' => 'Side trim data updated successfully',
                'data' => $sideTrim
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('Database error updating side trim by flute: ' . $e->getMessage());
            
            // Check for unique constraint violation
            if ($e->getCode() == 23000) { // Integrity constraint violation
                return response()->json([
                    'status' => 'error',
                    'message' => 'A record with this flute and compute status already exists',
                ], 422);
            }
            
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update side trim data: Database error',
                'error' => $e->getMessage()
            ], 500);
        } catch (\Exception $e) {
            Log::error('Error updating side trim by flute: ' . $e->getMessage());
            
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update side trim data',
                'error' => $e->getMessage()
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
     * Update multiple side trim records at once.
     */
    public function apiBatchUpdate(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                '*.id' => 'required|exists:side_trims_by_flute,id',
                '*.flute_id' => 'required|exists:paper_flutes,id',
                '*.length_add' => 'required|integer|min:0',
                '*.length_less' => 'required|integer|min:0',
                '*.compute' => 'required|boolean',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $errors = [];
            $updated = 0;

            foreach ($request->all() as $item) {
                try {
                    $sideTrim = SideTrimByFlute::find($item['id']);
                    if ($sideTrim) {
                        $sideTrim->update([
                            'flute_id' => $item['flute_id'],
                            'length_add' => $item['length_add'],
                            'length_less' => $item['length_less'],
                            'compute' => $item['compute'],
                        ]);
                        $updated++;
                    } else {
                        $errors[] = "Side trim with ID {$item['id']} not found";
                    }
                } catch (\Exception $e) {
                    $errors[] = "Error updating side trim ID {$item['id']}: " . $e->getMessage();
                }
            }

            return response()->json([
                'status' => 'success',
                'message' => "$updated side trim records updated successfully",
                'errors' => $errors
            ]);
        } catch (\Exception $e) {
            Log::error('Error batch updating side trims: ' . $e->getMessage());
            
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update side trims',
                'error' => $e->getMessage()
            ], 500);
        }
    }
} 