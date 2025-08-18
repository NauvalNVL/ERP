<?php

namespace App\Http\Controllers\MaterialManagement\SystemRequirement;

use App\Http\Controllers\Controller;
use App\Models\SkuItemNoteAnalysisGroup;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SkuItemNoteAnalysisGroupController extends Controller
{
    /**
     * Display a listing of SKU item note analysis groups
     */
    public function index(Request $request)
    {
        $query = SkuItemNoteAnalysisGroup::query();

        // Search functionality
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->byCategory($request->category);
        }

        // Filter by status
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->active();
            } elseif ($request->status === 'inactive') {
                $query->inactive();
            }
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'group_code');
        $sortDirection = $request->get('sort_direction', 'asc');
        $query->orderBy($sortBy, $sortDirection);

        $analysisGroups = $query->get();

        return response()->json($analysisGroups);
    }

    /**
     * Store a newly created analysis group
     */
    public function store(Request $request)
    {
        $request->validate([
            'group_code' => 'required|string|max:20|unique:sku_item_note_analysis_groups,group_code',
            'group_name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:50',
            'is_active' => 'boolean'
        ]);

        $analysisGroup = SkuItemNoteAnalysisGroup::create([
            'group_code' => strtoupper($request->group_code),
            'group_name' => $request->group_name,
            'description' => $request->description,
            'category' => $request->category,
            'is_active' => $request->get('is_active', true)
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Analysis group created successfully',
            'data' => $analysisGroup
        ], 201);
    }

    /**
     * Display the specified analysis group
     */
    public function show($id)
    {
        $analysisGroup = SkuItemNoteAnalysisGroup::findOrFail($id);
        return response()->json($analysisGroup);
    }

    /**
     * Update the specified analysis group
     */
    public function update(Request $request, $id)
    {
        $analysisGroup = SkuItemNoteAnalysisGroup::findOrFail($id);

        $request->validate([
            'group_code' => [
                'required',
                'string',
                'max:20',
                Rule::unique('sku_item_note_analysis_groups', 'group_code')->ignore($analysisGroup->id)
            ],
            'group_name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:50',
            'is_active' => 'boolean'
        ]);

        $analysisGroup->update([
            'group_code' => strtoupper($request->group_code),
            'group_name' => $request->group_name,
            'description' => $request->description,
            'category' => $request->category,
            'is_active' => $request->get('is_active', $analysisGroup->is_active)
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Analysis group updated successfully',
            'data' => $analysisGroup
        ]);
    }

    /**
     * Remove the specified analysis group
     */
    public function destroy($id)
    {
        $analysisGroup = SkuItemNoteAnalysisGroup::findOrFail($id);
        $analysisGroup->delete();

        return response()->json([
            'success' => true,
            'message' => 'Analysis group deleted successfully'
        ]);
    }

    /**
     * Toggle active status of analysis group
     */
    public function toggleActive($id)
    {
        $analysisGroup = SkuItemNoteAnalysisGroup::findOrFail($id);
        $analysisGroup->update(['is_active' => !$analysisGroup->is_active]);

        return response()->json([
            'success' => true,
            'message' => 'Analysis group status updated successfully',
            'data' => $analysisGroup
        ]);
    }

    /**
     * Get all categories
     */
    public function getCategories()
    {
        $categories = SkuItemNoteAnalysisGroup::getCategories();
        return response()->json($categories);
    }

    /**
     * Validate unique code
     */
    public function validateCode(Request $request)
    {
        $code = strtoupper($request->code);
        $excludeId = $request->exclude_id;

        $exists = SkuItemNoteAnalysisGroup::where('group_code', $code)
            ->when($excludeId, function ($query, $excludeId) {
                return $query->where('id', '!=', $excludeId);
            })
            ->exists();

        return response()->json(['exists' => $exists]);
    }

    /**
     * Get analysis groups for print/view
     */
    public function getForPrint(Request $request)
    {
        $query = SkuItemNoteAnalysisGroup::query();

        // Apply filters
        if ($request->filled('category')) {
            $query->byCategory($request->category);
        }

        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->active();
            } elseif ($request->status === 'inactive') {
                $query->inactive();
            }
        }

        $analysisGroups = $query->orderBy('group_code')->get();

        return response()->json($analysisGroups);
    }
} 