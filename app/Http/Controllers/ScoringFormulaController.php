<?php

namespace App\Http\Controllers;

use App\Models\ScoringFormula;
use App\Models\ProductDesign;
use App\Models\PaperFlute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ScoringFormulaController extends Controller
{
    /**
     * Display the scoring formula page.
     */
    public function index()
    {
        return Inertia::render('sales-management/standard-formula/setup-scoring-formula/ScoringFormula');
    }

    /**
     * Display the view and print scoring formula page.
     */
    public function viewAndPrint()
    {
        return Inertia::render('sales-management/standard-formula/setup-scoring-formula/ViewAndPrintScoringFormula');
    }

    /**
     * Get all scoring formulas.
     */
    public function apiIndex()
    {
        $formulas = ScoringFormula::with(['productDesign', 'paperFlute'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($formulas);
    }

    /**
     * Get a specific scoring formula.
     */
    public function apiShow($id)
    {
        $formula = ScoringFormula::with(['productDesign', 'paperFlute'])->findOrFail($id);
        return response()->json($formula);
    }

    /**
     * Store a new scoring formula.
     */
    public function apiStore(Request $request)
    {
        $validated = $request->validate([
            'product_design_id' => 'required|exists:product_designs,id',
            'paper_flute_code' => 'required|string|exists:Flute_CPS,Flute',
            'scoring_length_formula' => 'required|array',
            'scoring_width_formula' => 'required|array',
            'length_conversion' => 'required|numeric',
            'width_conversion' => 'required|numeric',
            'height_conversion' => 'required|numeric',
            'is_active' => 'boolean',
            'notes' => 'nullable|string'
        ]);

        // Check for duplicate formula
        $existingFormula = ScoringFormula::where('product_design_id', $validated['product_design_id'])
            ->where('paper_flute_code', $validated['paper_flute_code'])
            ->first();

        if ($existingFormula) {
            return response()->json([
                'success' => false,
                'message' => 'A formula with this product design and paper flute combination already exists.'
            ], 422);
        }

        $formula = ScoringFormula::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Scoring formula created successfully',
            'data' => $formula
        ]);
    }

    /**
     * Update an existing scoring formula.
     */
    public function apiUpdate(Request $request, $id)
    {
        $formula = ScoringFormula::findOrFail($id);

        $validated = $request->validate([
            'product_design_id' => 'required|exists:product_designs,id',
            'paper_flute_id' => 'required|exists:paper_flutes,id',
            'scoring_length_formula' => 'required|array',
            'scoring_width_formula' => 'required|array',
            'length_conversion' => 'required|numeric',
            'width_conversion' => 'required|numeric',
            'height_conversion' => 'required|numeric',
            'is_active' => 'boolean',
            'notes' => 'nullable|string'
        ]);

        // Check for duplicate formula (excluding current record)
        $existingFormula = ScoringFormula::where('product_design_id', $validated['product_design_id'])
            ->where('paper_flute_code', $validated['paper_flute_code'])
            ->where('id', '!=', $id)
            ->first();

        if ($existingFormula) {
            return response()->json([
                'success' => false,
                'message' => 'A formula with this product design and paper flute combination already exists.'
            ], 422);
        }

        $formula->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Scoring formula updated successfully',
            'data' => $formula
        ]);
    }

    /**
     * Delete a scoring formula.
     */
    public function apiDestroy($id)
    {
        $formula = ScoringFormula::findOrFail($id);
        $formula->delete();

        return response()->json([
            'success' => true,
            'message' => 'Scoring formula deleted successfully'
        ]);
    }

    /**
     * Get formulas by product design.
     */
    public function getByProductDesign($productDesignId)
    {
        $formulas = ScoringFormula::with(['productDesign', 'paperFlute'])
            ->where('product_design_id', $productDesignId)
            ->get();

        return response()->json($formulas);
    }

    /**
     * Get formulas by paper flute.
     */
    public function getByPaperFlute($paperFluteCode)
    {
        $formulas = ScoringFormula::with(['productDesign', 'paperFlute'])
            ->where('paper_flute_code', $paperFluteCode)
            ->get();

        return response()->json($formulas);
    }

    /**
     * Seed scoring formula data.
     */
    public function apiSeed()
    {
        try {
            Artisan::call('db:seed', [
                '--class' => 'Database\\Seeders\\ScoringFormulaSeeder'
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Scoring formula data seeded successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error seeding scoring formula data.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
} 