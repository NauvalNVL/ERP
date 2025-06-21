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
     * Display a listing of the resource for API.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiIndex()
    {
        $trims = RollTrimByCorrugator::all();
        
        // Transform the data to match the expected format in the frontend
        $transformedTrims = $trims->map(function ($trim) {
            $flute = PaperFlute::where('code', $trim->flute_code)->first();
            
            return [
                'id' => $trim->id,
                'flute_id' => $flute ? $flute->id : null,
                'compute' => (bool)$trim->compute,
                'min_trim' => $trim->trim_value,
                'max_trim' => $trim->trim_value + 10, // Default max is 10 more than min
            ];
        });
        
        return response()->json($transformedTrims);
    }

    /**
     * Get all paper flutes.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPaperFlutes()
    {
        try {
            $flutes = \App\Models\PaperFlute::all();
            return response()->json($flutes);
        } catch (\Exception $e) {
            Log::error('Error fetching paper flutes: ' . $e->getMessage());
            return response()->json(['error' => 'Could not fetch paper flutes'], 500);
        }
    }

    /**
     * Update a batch of roll trim records.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiBatchUpdate(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                '*.flute_id' => 'required|integer|exists:paper_flutes,id',
                '*.compute' => 'required|boolean',
                '*.min_trim' => 'nullable|numeric|min:0',
                '*.max_trim' => 'nullable|numeric',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $results = [];
            $errors = [];

            foreach ($request->all() as $specData) {
                try {
                    // Get the flute code from the flute_id
                    $flute = PaperFlute::find($specData['flute_id']);
                    if (!$flute) {
                        $errors[] = [
                            'flute_id' => $specData['flute_id'],
                            'error' => 'Flute not found'
                        ];
                        continue;
                    }
                    
                    // Set default values for empty fields
                    $min_trim = isset($specData['min_trim']) && $specData['min_trim'] !== '' && $specData['min_trim'] !== null ? $specData['min_trim'] : 0;
                    
                    // Use the default corrugator name
                    $corrugatorName = 'DEFAULT';
                    
                    $spec = RollTrimByCorrugator::updateOrCreate(
                        [
                            'corrugator_name' => $corrugatorName,
                            'flute_code' => $flute->code,
                        ],
                        [
                            'compute' => $specData['compute'],
                            'trim_value' => $min_trim,
                        ]
                    );
                    
                    $results[] = [
                        'id' => $spec->id,
                        'flute_id' => $specData['flute_id'],
                        'compute' => $spec->compute,
                        'min_trim' => $spec->trim_value,
                        'max_trim' => $spec->trim_value + 10, // Default max is 10 more than min
                    ];
                } catch (\Exception $e) {
                    $errors[] = [
                        'flute_id' => $specData['flute_id'],
                        'error' => $e->getMessage()
                    ];
                    Log::error('Error updating roll trim for flute ' . $specData['flute_id'] . ': ' . $e->getMessage());
                }
            }

            if (count($errors) > 0) {
                return response()->json([
                    'message' => 'Some specifications could not be saved.',
                    'results' => $results,
                    'errors' => $errors
                ], 207); // 207 Multi-Status
            }

            return response()->json([
                'message' => 'All specifications saved successfully.',
                'results' => $results
            ]);
        } catch (\Exception $e) {
            Log::error('Error in batch update: ' . $e->getMessage());
            return response()->json([
                'message' => 'An error occurred during batch update.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
} 