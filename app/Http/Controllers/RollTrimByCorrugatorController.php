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
        $flutes = PaperFlute::all();
        $transformedFlutes = $flutes->map(function ($flute) {
            $trim = RollTrimByCorrugator::where('flute_id', $flute->id)->first();
            return [
                'id' => $flute->id,
                'flute_id' => $flute->id,
                'flute_code' => $flute->code,
                'flute_name' => $flute->name,
                'compute' => $trim ? (bool)$trim->compute : false,
                'min_trim' => $trim ? $trim->min_trim : null,
                'max_trim' => $trim ? $trim->max_trim : null,
                'trim_id' => $trim ? $trim->id : null,
            ];
        });
        return response()->json($transformedFlutes);
    }

    /**
     * Get all paper flutes.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPaperFlutes()
    {
        try {
            $flutes = PaperFlute::all();
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
            Log::info('apiBatchUpdate called with data:', $request->all());
            
            $validator = Validator::make($request->all(), [
                '*.flute_id' => 'required|integer|exists:paper_flutes,id',
                '*.compute' => 'required|boolean',
                '*.min_trim' => 'nullable|numeric|min:0',
                '*.max_trim' => 'nullable|numeric|min:0',
            ]);

            if ($validator->fails()) {
                Log::error('Validation failed:', $validator->errors()->toArray());
                return response()->json(['message' => 'Validation failed', 'errors' => $validator->errors()], 422);
            }

            $results = [];
            foreach ($request->all() as $trimData) {
                Log::info('Processing trim data:', $trimData);
                
                // Additional validation for max_trim >= min_trim
                if (isset($trimData['max_trim']) && isset($trimData['min_trim']) && 
                    $trimData['max_trim'] !== null && $trimData['min_trim'] !== null &&
                    $trimData['max_trim'] < $trimData['min_trim']) {
                    Log::error('Max trim validation failed for flute_id: ' . $trimData['flute_id']);
                    return response()->json([
                        'message' => 'Validation failed', 
                        'errors' => ['max_trim' => ['Max trim must be greater than or equal to min trim']]
                    ], 422);
                }

                $updateData = [
                    'compute' => $trimData['compute'] ?? false,
                    'min_trim' => $trimData['min_trim'] ?? 0,
                    'max_trim' => $trimData['max_trim'] ?? null,
                ];

                Log::info('Update data:', $updateData);

                $trim = RollTrimByCorrugator::updateOrCreate(
                    ['flute_id' => $trimData['flute_id']],
                    $updateData
                );
                $results[] = $trim;
            }

            Log::info('Batch update completed successfully');
            return response()->json(['success' => true, 'results' => $results]);
        } catch (\Exception $e) {
            Log::error('Error in apiBatchUpdate: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json(['message' => 'Internal server error: ' . $e->getMessage()], 500);
        }
    }
} 