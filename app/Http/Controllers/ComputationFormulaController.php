<?php

namespace App\Http\Controllers;

use App\Models\ComputationFormula;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ComputationFormulaController extends Controller
{
    /**
     * Display the computation formula page.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        return Inertia::render('sales-management/standard-formula/diecut-computation-method/ComputationFormula');
    }

    /**
     * Display the view & print page.
     *
     * @return \Inertia\Response
     */
    public function viewPrint()
    {
        return Inertia::render('sales-management/standard-formula/diecut-computation-method/ViewPrintComputationFormula');
    }

    /**
     * Get all computation formulas.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiIndex()
    {
        $formulas = ComputationFormula::orderBy('id', 'asc')->get();
        return response()->json(['data' => $formulas]);
    }

    /**
     * Get a specific computation formula.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiShow($id)
    {
        $formula = ComputationFormula::findOrFail($id);
        return response()->json(['data' => $formula]);
    }

    /**
     * Store a new computation formula.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiStore(Request $request)
    {
        $validatedData = $request->validate([
            'board_length_min' => 'required|numeric|min:0',
            'board_length_max' => 'required|numeric|min:0',
            'board_width_min' => 'required|numeric|min:0',
            'board_width_max' => 'required|numeric|min:0',
            'dc_sheet_length_min' => 'required|numeric|min:1',
            'dc_sheet_length_max' => 'required|numeric|min:1',
            'dc_sheet_width_min' => 'required|numeric|min:1',
            'dc_sheet_width_max' => 'required|numeric|min:1',
            'no_of_up_min' => 'required|numeric|min:1',
            'no_of_up_max' => 'required|numeric|min:1|max:99',
        ]);

        $formula = ComputationFormula::create($validatedData);
        
        return response()->json([
            'message' => 'Formula created successfully',
            'data' => $formula
        ], 201);
    }

    /**
     * Update an existing computation formula.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiUpdate(Request $request, $id)
    {
        $formula = ComputationFormula::findOrFail($id);
        
        $validatedData = $request->validate([
            'board_length_min' => 'required|numeric|min:0',
            'board_length_max' => 'required|numeric|min:0',
            'board_width_min' => 'required|numeric|min:0',
            'board_width_max' => 'required|numeric|min:0',
            'dc_sheet_length_min' => 'required|numeric|min:1',
            'dc_sheet_length_max' => 'required|numeric|min:1',
            'dc_sheet_width_min' => 'required|numeric|min:1',
            'dc_sheet_width_max' => 'required|numeric|min:1',
            'no_of_up_min' => 'required|numeric|min:1',
            'no_of_up_max' => 'required|numeric|min:1|max:99',
        ]);

        $formula->update($validatedData);
        
        return response()->json([
            'message' => 'Formula updated successfully',
            'data' => $formula
        ]);
    }

    /**
     * Delete a computation formula.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiDestroy($id)
    {
        $formula = ComputationFormula::findOrFail($id);
        $formula->delete();
        
        return response()->json([
            'message' => 'Formula deleted successfully'
        ]);
    }

    /**
     * Seed initial data.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiSeed()
    {
        $created = self::seedData();
        
        return response()->json([
            'message' => 'Seed data created successfully',
            'data' => $created
        ]);
    }
    
    /**
     * Static method to seed data.
     *
     * @return array
     */
    public static function seedData()
    {
        $seedData = [
            [
                'board_length_min' => 0,
                'board_length_max' => 999999,
                'board_width_min' => 0,
                'board_width_max' => 999999,
                'dc_sheet_length_min' => 1,
                'dc_sheet_length_max' => 999999,
                'dc_sheet_width_min' => 1,
                'dc_sheet_width_max' => 999999,
                'no_of_up_min' => 1,
                'no_of_up_max' => 99
            ],
            [
                'board_length_min' => 100,
                'board_length_max' => 5000,
                'board_width_min' => 100,
                'board_width_max' => 5000,
                'dc_sheet_length_min' => 100,
                'dc_sheet_length_max' => 5000,
                'dc_sheet_width_min' => 100,
                'dc_sheet_width_max' => 5000,
                'no_of_up_min' => 1,
                'no_of_up_max' => 10
            ]
        ];

        $created = [];
        foreach ($seedData as $data) {
            $created[] = ComputationFormula::updateOrCreate(
                [
                    'board_length_min' => $data['board_length_min'],
                    'board_width_min' => $data['board_width_min'],
                ],
                $data
            );
        }

        return $created;
    }
} 