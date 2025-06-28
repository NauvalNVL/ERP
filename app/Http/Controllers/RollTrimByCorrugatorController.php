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
        $validator = Validator::make($request->all(), [
            '*.flute_id' => 'required|integer|exists:paper_flutes,id',
            '*.compute' => 'required|boolean',
            '*.min_trim' => 'nullable|numeric|min:0',
            '*.max_trim' => 'nullable|numeric|gte:*.min_trim',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation failed', 'errors' => $validator->errors()], 422);
        }

        $results = [];
        foreach ($request->all() as $trimData) {
            $updateData = [
                'compute' => $trimData['compute'] ?? false,
                'min_trim' => $trimData['min_trim'] ?? 0,
                'max_trim' => $trimData['max_trim'] ?? null,
            ];

            $trim = RollTrimByCorrugator::updateOrCreate(
                ['flute_id' => $trimData['flute_id']],
                $updateData
            );
            $results[] = $trim;
        }

        return response()->json(['success' => true, 'results' => $results]);
    }
} 