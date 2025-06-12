<?php

namespace App\Http\Controllers;

use App\Models\RollTrimByCorrugator;
use App\Models\PaperFlute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class RollTrimByCorrugatorController extends Controller
{
    /**
     * Display the roll trim by corrugator page.
     */
    public function index()
    {
        return Inertia::render('sales-management/standard-formula/setup-corrugator-run-size-formula/RollTrimByCorrugator');
    }

    /**
     * Display the view and print page for roll trim by corrugator.
     */
    public function viewPrint()
    {
        return Inertia::render('sales-management/standard-formula/setup-corrugator-run-size-formula/ViewPrintRollTrimByCorrugator');
    }

    /**
     * Get all roll trim data for API.
     */
    public function apiIndex()
    {
        try {
            $rollTrims = RollTrimByCorrugator::with('paperFlute')->get();
            
            return response()->json([
                'status' => 'success',
                'data' => $rollTrims
            ]);
        } catch (\Exception $e) {
            Log::error('Error retrieving roll trims: ' . $e->getMessage());
            
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve roll trims data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a new roll trim record.
     */
    public function apiStore(Request $request)
    {
        try {
            $validated = $request->validate([
                'corrugator_name' => 'required|string|max:255',
                'flute_code' => 'required|string|exists:paper_flutes,code',
                'trim_value' => 'required|integer|min:0',
            ]);

            $rollTrim = RollTrimByCorrugator::updateOrCreate(
                [
                    'corrugator_name' => $validated['corrugator_name'],
                    'flute_code' => $validated['flute_code'],
                ],
                [
                    'trim_value' => $validated['trim_value'],
                ]
            );

            return response()->json([
                'status' => 'success',
                'message' => 'Roll trim data saved successfully',
                'data' => $rollTrim
            ]);
        } catch (\Exception $e) {
            Log::error('Error saving roll trim: ' . $e->getMessage());
            
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to save roll trim data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update an existing roll trim record.
     */
    public function apiUpdate(Request $request, $id)
    {
        try {
            $rollTrim = RollTrimByCorrugator::findOrFail($id);
            
            $validated = $request->validate([
                'corrugator_name' => 'required|string|max:255',
                'flute_code' => 'required|string|exists:paper_flutes,code',
                'trim_value' => 'required|integer|min:0',
            ]);

            $rollTrim->update($validated);

            return response()->json([
                'status' => 'success',
                'message' => 'Roll trim data updated successfully',
                'data' => $rollTrim
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating roll trim: ' . $e->getMessage());
            
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update roll trim data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a roll trim record.
     */
    public function apiDestroy($id)
    {
        try {
            $rollTrim = RollTrimByCorrugator::findOrFail($id);
            $rollTrim->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Roll trim data deleted successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting roll trim: ' . $e->getMessage());
            
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete roll trim data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all paper flutes for dropdown.
     */
    public function getPaperFlutes()
    {
        try {
            $flutes = PaperFlute::select('code', 'name')->get();
            
            return response()->json([
                'status' => 'success',
                'data' => $flutes
            ]);
        } catch (\Exception $e) {
            Log::error('Error retrieving paper flutes: ' . $e->getMessage());
            
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve paper flutes data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export roll trim data to Excel.
     */
    public function apiExport()
    {
        try {
            $rollTrims = RollTrimByCorrugator::with('paperFlute')->get();
            
            // Transform data for export
            $exportData = $rollTrims->map(function ($trim) {
                return [
                    'Corrugator' => $trim->corrugator_name,
                    'Flute Code' => $trim->flute_code,
                    'Flute Name' => $trim->paperFlute ? $trim->paperFlute->name : 'N/A',
                    'Trim Value (cm)' => $trim->trim_value,
                    'Created At' => $trim->created_at->format('Y-m-d H:i:s'),
                    'Updated At' => $trim->updated_at->format('Y-m-d H:i:s'),
                ];
            });
            
            // Return JSON data for now
            return response()->json([
                'status' => 'success',
                'data' => $exportData,
                'filename' => 'roll_trim_by_corrugator_' . date('Y-m-d') . '.xlsx'
            ]);
        } catch (\Exception $e) {
            Log::error('Error exporting roll trim data: ' . $e->getMessage());
            
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to export roll trim data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * API: Seed initial data for roll trim by corrugator
     */
    public function apiSeed()
    {
        try {
            // Get all paper flutes
            $flutes = PaperFlute::all();
            
            // Create default trim specifications for each flute
            foreach ($flutes as $flute) {
                RollTrimByCorrugator::updateOrCreate(
                    ['flute_id' => $flute->id],
                    [
                        'min_trim' => 20,
                        'max_trim' => 65
                    ]
                );
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Roll trim by corrugator data seeded successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error seeding roll trim by corrugator data: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to seed data: ' . $e->getMessage()
            ], 500);
        }
    }
} 