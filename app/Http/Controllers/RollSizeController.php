<?php

namespace App\Http\Controllers;

use App\Models\PaperFlute;
use App\Models\RollSize;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class RollSizeController extends Controller
{
    /**
     * Display the roll size page.
     */
    public function index()
    {
        return Inertia::render('sales-management/standard-formula/setup-corrugator-run-size-formula/RollSize');
    }

    /**
     * Display the view and print page for roll sizes.
     */
    public function viewPrint()
    {
        return Inertia::render('sales-management/standard-formula/setup-corrugator-run-size-formula/ViewPrintRollSize');
    }

    /**
     * Get all roll size data for API.
     */
    public function apiIndex()
    {
        try {
            $rollSizes = RollSize::with('paperFlute')->get();
            
            return response()->json([
                'status' => 'success',
                'data' => $rollSizes
            ]);
        } catch (\Exception $e) {
            Log::error('Error retrieving roll sizes: ' . $e->getMessage());
            
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve roll sizes data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a new roll size record or update if exists.
     */
    public function apiStore(Request $request)
    {
        try {
            $validated = $request->validate([
                'flute_id' => 'required|exists:paper_flutes,id',
                'roll_length' => 'required|numeric|min:0',
                'is_composite' => 'required|boolean',
            ]);

            $rollSize = RollSize::updateOrCreate(
                [
                    'flute_id' => $validated['flute_id'],
                ],
                [
                    'roll_length' => $validated['roll_length'],
                    'is_composite' => $validated['is_composite'],
                ]
            );

            return response()->json([
                'status' => 'success',
                'message' => 'Roll size data saved successfully',
                'data' => $rollSize
            ]);
        } catch (\Exception $e) {
            Log::error('Error saving roll size: ' . $e->getMessage());
            
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to save roll size data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update an existing roll size record.
     */
    public function apiUpdate(Request $request, $id)
    {
        try {
            $rollSize = RollSize::findOrFail($id);
            
            $validated = $request->validate([
                'flute_id' => 'required|exists:paper_flutes,id',
                'roll_length' => 'required|numeric|min:0',
                'is_composite' => 'required|boolean',
            ]);

            $rollSize->update($validated);

            return response()->json([
                'status' => 'success',
                'message' => 'Roll size data updated successfully',
                'data' => $rollSize
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating roll size: ' . $e->getMessage());
            
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update roll size data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Batch update multiple roll size records.
     */
    public function apiBatchUpdate(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                '*.id' => 'required|exists:roll_sizes,id',
                '*.flute_id' => 'required|exists:paper_flutes,id',
                '*.roll_length' => 'required|numeric|min:0',
                '*.is_composite' => 'required|boolean',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $results = [];
            $errors = [];

            foreach ($request->all() as $item) {
                try {
                    $rollSize = RollSize::findOrFail($item['id']);
                    $rollSize->update([
                        'flute_id' => $item['flute_id'],
                        'roll_length' => $item['roll_length'],
                        'is_composite' => $item['is_composite'],
                    ]);
                    $results[] = $rollSize;
                } catch (\Exception $e) {
                    $errors[] = [
                        'id' => $item['id'] ?? null,
                        'error' => $e->getMessage()
                    ];
                    Log::error('Error updating roll size in batch: ' . $e->getMessage());
                }
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Roll sizes updated successfully',
                'results' => $results,
                'errors' => $errors
            ]);
        } catch (\Exception $e) {
            Log::error('Error in batch update of roll sizes: ' . $e->getMessage());
            
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update roll sizes',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a roll size record.
     */
    public function apiDestroy($id)
    {
        try {
            $rollSize = RollSize::findOrFail($id);
            $rollSize->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Roll size data deleted successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting roll size: ' . $e->getMessage());
            
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete roll size data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export roll size data to Excel.
     */
    public function apiExport()
    {
        try {
            $rollSizes = RollSize::with('paperFlute')->get();
            
            // Transform data for export
            $exportData = $rollSizes->map(function ($size) {
                return [
                    'Flute Code' => $size->paperFlute ? $size->paperFlute->code : 'N/A',
                    'Roll Length (mm)' => $size->roll_length,
                    'Composite' => $size->is_composite ? 'Yes' : 'No',
                    'Created At' => $size->created_at->format('Y-m-d H:i:s'),
                    'Updated At' => $size->updated_at->format('Y-m-d H:i:s'),
                ];
            });
            
            // Return JSON data for now
            return response()->json([
                'status' => 'success',
                'data' => $exportData,
                'filename' => 'roll_sizes_' . date('Y-m-d') . '.xlsx'
            ]);
        } catch (\Exception $e) {
            Log::error('Error exporting roll size data: ' . $e->getMessage());
            
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to export roll size data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * API: Seed initial data for roll sizes
     */
    public function apiSeed()
    {
        try {
            // Get all flutes
            $flutes = PaperFlute::all();
            
            $count = 0;
            $rollLengths = [
                '740' => false,
                '860' => true,
                '905' => true,
                '965' => true,
                '1040' => true,
                '1180' => true,
                '1290' => true,
                '1340' => true,
                '1450' => true,
                '1750' => true,
                '1950' => true,
                '1480' => true,
                '1540' => true,
                '1580' => true,
                '1900' => true,
            ];
            
            // Create sample data for each flute
            foreach ($flutes as $flute) {
                // Use the flute code to determine which roll lengths to use
                $fluteCode = strtoupper($flute->code);
                
                // For demonstration, we'll create entries for all flutes with predefined roll lengths
                foreach ($rollLengths as $length => $isComposite) {
                    RollSize::updateOrCreate(
                        [
                            'flute_id' => $flute->id,
                            'roll_length' => $length,
                        ],
                        [
                            'is_composite' => $isComposite,
                        ]
                    );
                    $count++;
                }
            }
            
            return response()->json([
                'success' => true,
                'message' => "Roll size data seeded successfully ($count records created)"
            ]);
        } catch (\Exception $e) {
            Log::error('Error seeding roll size data: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to seed data: ' . $e->getMessage()
            ], 500);
        }
    }
} 