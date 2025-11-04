<?php

namespace App\Http\Controllers;

use App\Models\ChemicalCoat;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ChemicalCoatController extends Controller
{
    /**
     * Display the main Chemical Coat page (Vue)
     */
    public function vueIndex()
    {
        return Inertia::render('sales-management/system-requirement/standard-requirement/chemical-coat');
    }

    /**
     * Display the View & Print Chemical Coat page (Vue)
     */
    public function vueViewAndPrint()
    {
        return Inertia::render('sales-management/system-requirement/standard-requirement/view-and-print-chemical-coat');
    }

    /**
     * Get all chemical coats (API)
     */
    public function apiIndex()
    {
        $chemicalCoats = ChemicalCoat::orderBy('code')->get();
        return response()->json($chemicalCoats);
    }

    /**
     * Store a new chemical coat (API)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:chemical_coats,code',
            'name' => 'required|string|max:255',
            'dry_end_code' => 'nullable|string|max:1',
            'is_active' => 'boolean'
        ]);

        $chemicalCoat = ChemicalCoat::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Chemical coat created successfully',
            'data' => $chemicalCoat
        ], 201);
    }

    /**
     * Update an existing chemical coat (API)
     */
    public function update(Request $request, $code)
    {
        $chemicalCoat = ChemicalCoat::where('code', $code)->firstOrFail();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'dry_end_code' => 'nullable|string|max:1',
            'is_active' => 'boolean'
        ]);

        $chemicalCoat->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Chemical coat updated successfully',
            'data' => $chemicalCoat
        ]);
    }

    /**
     * Delete a chemical coat (API)
     */
    public function destroy($code)
    {
        $chemicalCoat = ChemicalCoat::where('code', $code)->firstOrFail();
        $chemicalCoat->delete();

        return response()->json([
            'success' => true,
            'message' => 'Chemical coat deleted successfully'
        ]);
    }

    /**
     * Seed default chemical coats (API)
     */
    public function seed()
    {
        $defaultCoats = [
            ['code' => '001', 'name' => 'VERNISH', 'dry_end_code' => '1'],
            ['code' => '002', 'name' => 'WATER BASE COATING', 'dry_end_code' => '2'],
            ['code' => '003', 'name' => 'GLOSS COAT', 'dry_end_code' => '3'],
            ['code' => '004', 'name' => 'MATTE COAT', 'dry_end_code' => '4'],
            ['code' => '005', 'name' => 'UV COATING', 'dry_end_code' => '5'],
        ];

        foreach ($defaultCoats as $coat) {
            ChemicalCoat::updateOrCreate(
                ['code' => $coat['code']],
                $coat
            );
        }

        return response()->json([
            'success' => true,
            'message' => 'Chemical coats seeded successfully'
        ]);
    }
}
