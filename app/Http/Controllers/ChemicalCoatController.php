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
     * Display the Obsolete/Unobsolete Chemical Coat status management page (Vue)
     */
    public function vueManageStatus()
    {
        try {
            $chemicalCoats = ChemicalCoat::orderBy('code')->get();

            return Inertia::render('sales-management/system-requirement/standard-requirement/ObsoleteUnobsoleteChemicalCoat', [
                'chemicalCoats' => $chemicalCoats,
                'header' => 'Manage Chemical Coat Status',
            ]);
        } catch (\Exception $e) {
            return Inertia::render('sales-management/system-requirement/standard-requirement/ObsoleteUnobsoleteChemicalCoat', [
                'chemicalCoats' => [],
                'header' => 'Manage Chemical Coat Status',
            ]);
        }
    }

    /**
     * Get all chemical coats (API)
     */
    public function apiIndex(Request $request)
    {
        $query = ChemicalCoat::query()->orderBy('code');

        $allStatus = $request->boolean('all_status') || $request->boolean('include_obsolete');
        if (!$allStatus) {
            $query->where(function ($q) {
                $q->where('status', 'Act')
                    ->orWhere('is_active', true);
            });
        }

        $chemicalCoats = $query->get();
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
            'is_active' => 'boolean',
            'status' => 'nullable|string|max:3',
        ]);

        $status = $request->input('status');
        if ($status === null || $status === '') {
            $status = 'Act';
        }
        $validated['status'] = $status;

        if (!array_key_exists('is_active', $validated)) {
            $validated['is_active'] = $status === 'Act';
        }

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
            'is_active' => 'boolean',
            'status' => 'nullable|string|max:3',
        ]);

        $status = $request->input('status');
        if ($status === null || $status === '') {
            $status = $chemicalCoat->status ?? ($chemicalCoat->is_active ? 'Act' : 'Obs');
        }
        $validated['status'] = $status;

        if (!array_key_exists('is_active', $validated)) {
            $validated['is_active'] = $status === 'Act';
        }

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
        $chemicalCoat->status = 'Obs';
        $chemicalCoat->is_active = false;
        $chemicalCoat->save();

        return response()->json([
            'success' => true,
            'message' => 'Chemical coat marked as obsolete successfully'
        ]);
    }

    /**
     * Toggle chemical coat status (Act/Obs) via API
     */
    public function toggleStatus(Request $request, $code)
    {
        $chemicalCoat = ChemicalCoat::where('code', $code)->first();

        if (!$chemicalCoat) {
            return response()->json([
                'success' => false,
                'message' => 'Chemical coat not found',
            ], 404);
        }

        $status = $request->input('status');

        if (!in_array($status, ['Act', 'Obs'], true)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid status value',
            ], 422);
        }

        $chemicalCoat->status = $status;
        $chemicalCoat->is_active = $status === 'Act';
        $chemicalCoat->save();

        return response()->json([
            'success' => true,
            'message' => 'Chemical coat status updated successfully',
            'data' => $chemicalCoat,
        ]);
    }

    /**
     * Seed default chemical coats (API)
     */
    public function seed()
    {
        $defaultCoats = [
            ['code' => '001', 'name' => 'VERNISH', 'dry_end_code' => '1', 'status' => 'Act', 'is_active' => true],
            ['code' => '002', 'name' => 'WATER BASE COATING', 'dry_end_code' => '2', 'status' => 'Act', 'is_active' => true],
            ['code' => '003', 'name' => 'GLOSS COAT', 'dry_end_code' => '3', 'status' => 'Act', 'is_active' => true],
            ['code' => '004', 'name' => 'MATTE COAT', 'dry_end_code' => '4', 'status' => 'Act', 'is_active' => true],
            ['code' => '005', 'name' => 'UV COATING', 'dry_end_code' => '5', 'status' => 'Act', 'is_active' => true],
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
