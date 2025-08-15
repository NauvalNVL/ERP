<?php

namespace App\Http\Controllers\MaterialManagement\SystemRequirement;

use App\Http\Controllers\Controller;
use App\Models\PurchaseSubControl;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PurchaseSubControlController extends Controller
{
    /**
     * Display a listing of purchase sub controls
     */
    public function index(Request $request)
    {
        $query = PurchaseSubControl::query();

        // Search functionality
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->byCategory($request->category);
        }



        // Sorting
        $sortBy = $request->get('sort_by', 'psc_code');
        $sortDirection = $request->get('sort_direction', 'asc');
        $query->orderBy($sortBy, $sortDirection);

        $purchaseSubControls = $query->get();

        return response()->json($purchaseSubControls);
    }

    /**
     * Store a newly created purchase sub control
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'psc_code' => [
                'required',
                'string',
                'max:20',
                Rule::unique('purchase_sub_controls', 'psc_code')
            ],
            'psc_name' => 'required|string|max:100',
            'category' => 'nullable|string|max:50'
        ]);

        $purchaseSubControl = PurchaseSubControl::create($validated);

        return response()->json([
            'message' => 'Purchase Sub-Control created successfully',
            'data' => $purchaseSubControl
        ], 201);
    }

    /**
     * Display the specified purchase sub control
     */
    public function show($id)
    {
        $purchaseSubControl = PurchaseSubControl::findOrFail($id);

        return response()->json($purchaseSubControl);
    }

    /**
     * Update the specified purchase sub control
     */
    public function update(Request $request, $id)
    {
        $purchaseSubControl = PurchaseSubControl::findOrFail($id);

        $validated = $request->validate([
            'psc_code' => [
                'required',
                'string',
                'max:20',
                Rule::unique('purchase_sub_controls', 'psc_code')->ignore($id)
            ],
            'psc_name' => 'required|string|max:100',
            'category' => 'nullable|string|max:50'
        ]);

        $purchaseSubControl->update($validated);

        return response()->json([
            'message' => 'Purchase Sub-Control updated successfully',
            'data' => $purchaseSubControl
        ]);
    }

    /**
     * Remove the specified purchase sub control
     */
    public function destroy($id)
    {
        $purchaseSubControl = PurchaseSubControl::findOrFail($id);
        $purchaseSubControl->delete();

        return response()->json([
            'message' => 'Purchase Sub-Control deleted successfully'
        ]);
    }



    /**
     * Get all categories
     */
    public function getCategories()
    {
        $categories = PurchaseSubControl::getCategories();

        return response()->json($categories);
    }

    /**
     * Validate PSC code uniqueness
     */
    public function validateCode(Request $request)
    {
        $request->validate([
            'psc_code' => 'required|string|max:20'
        ]);

        $exists = PurchaseSubControl::where('psc_code', $request->psc_code)->exists();

        return response()->json([
            'available' => !$exists,
            'message' => $exists ? 'PSC Code already exists' : 'PSC Code is available'
        ]);
    }
} 