<?php

namespace App\Http\Controllers;

use App\Models\StitchWire;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class StitchWireController extends Controller
{
    /**
     * Display the Vue index page.
     */
    public function vueIndex()
    {
        return Inertia::render('sales-management/system-requirement/standard-requirement/stitch-wire');
    }

    /**
     * Display the Vue view and print page.
     */
    public function vueViewAndPrint()
    {
        return Inertia::render('sales-management/system-requirement/standard-requirement/view-and-print-stitch-wire');
    }

    /**
     * Get all stitch wires for API.
     */
    public function apiIndex()
    {
        try {
            $stitchWires = StitchWire::orderBy('code')->get();
            return response()->json($stitchWires);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch stitch wires',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created stitch wire.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:50|unique:stitch_wires,code',
            'name' => 'required|string|max:255',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Validation failed',
                'messages' => $validator->errors()
            ], 422);
        }

        try {
            $stitchWire = StitchWire::create([
                'code' => $request->code,
                'name' => $request->name,
                'is_active' => $request->is_active ?? true
            ]);

            return response()->json([
                'message' => 'Stitch wire created successfully',
                'data' => $stitchWire
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to create stitch wire',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified stitch wire.
     */
    public function update(Request $request, $code)
    {
        $stitchWire = StitchWire::where('code', $code)->first();

        if (!$stitchWire) {
            return response()->json([
                'error' => 'Stitch wire not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Validation failed',
                'messages' => $validator->errors()
            ], 422);
        }

        try {
            $stitchWire->update([
                'name' => $request->name,
                'is_active' => $request->is_active ?? $stitchWire->is_active
            ]);

            return response()->json([
                'message' => 'Stitch wire updated successfully',
                'data' => $stitchWire
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to update stitch wire',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified stitch wire.
     */
    public function destroy($code)
    {
        try {
            $stitchWire = StitchWire::where('code', $code)->first();

            if (!$stitchWire) {
                return response()->json([
                    'error' => 'Stitch wire not found'
                ], 404);
            }

            $stitchWire->delete();

            return response()->json([
                'message' => 'Stitch wire deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to delete stitch wire',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Seed sample data.
     */
    public function seed()
    {
        try {
            $stitchWires = [
                ['code' => '001', 'name' => 'TIPE 1+1+1+1+1', 'is_active' => true],
                ['code' => '002', 'name' => 'TIPE 2+1+1+1+2', 'is_active' => true],
                ['code' => '003', 'name' => 'TIPE 2+2+2+2+2', 'is_active' => true],
            ];

            foreach ($stitchWires as $data) {
                StitchWire::updateOrCreate(
                    ['code' => $data['code']],
                    $data
                );
            }

            return response()->json([
                'message' => 'Stitch wires seeded successfully',
                'count' => count($stitchWires)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to seed stitch wires',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
