<?php

namespace App\Http\Controllers\MaterialManagement\SystemRequirement;

use App\Http\Controllers\Controller;
use App\Models\SkuItemNoteAnalysisCode;
use App\Models\SkuItemNoteAnalysisGroup;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SkuItemNoteAnalysisCodeController extends Controller
{
    /**
     * Display a listing of analysis codes
     */
    public function index(Request $request)
    {
        $query = SkuItemNoteAnalysisCode::withGroup();

        // Search functionality
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Filter by analysis group
        if ($request->filled('analysis_group_id')) {
            $query->byGroup($request->analysis_group_id);
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
        $sortField = $request->get('sort_field', 'analysis_code');
        $sortDirection = $request->get('sort_direction', 'asc');
        
        $validSortFields = ['analysis_code', 'code_name', 'created_at'];
        if (in_array($sortField, $validSortFields)) {
            $query->orderBy($sortField, $sortDirection);
        }

        // Pagination
        $perPage = $request->get('per_page', 15);
        $analysisCodes = $query->paginate($perPage);

        return response()->json($analysisCodes);
    }

    /**
     * Store a new analysis code
     */
    public function store(Request $request)
    {
        $request->validate([
            'analysis_group_id' => 'required|exists:sku_item_note_analysis_groups,id',
            'analysis_code' => [
                'required',
                'string',
                'max:20',
                'regex:/^[A-Z0-9-_]+$/',
                Rule::unique('sku_item_note_analysis_codes')->where(function ($query) use ($request) {
                    return $query->where('analysis_group_id', $request->analysis_group_id);
                })
            ],
            'code_name' => 'required|string|max:100',
            'description' => 'nullable|string|max:500',
            'is_active' => 'boolean'
        ], [
            'analysis_code.regex' => 'Analysis code must contain only uppercase letters, numbers, hyphens, and underscores.',
            'analysis_code.unique' => 'This analysis code already exists in the selected group.'
        ]);

        $analysisCode = SkuItemNoteAnalysisCode::create([
            'analysis_group_id' => $request->analysis_group_id,
            'analysis_code' => strtoupper($request->analysis_code),
            'code_name' => $request->code_name,
            'description' => $request->description,
            'is_active' => $request->get('is_active', true)
        ]);

        $analysisCode->load('analysisGroup');

        return response()->json($analysisCode, 201);
    }

    /**
     * Display the specified analysis code
     */
    public function show($id)
    {
        $analysisCode = SkuItemNoteAnalysisCode::withGroup()->findOrFail($id);
        return response()->json($analysisCode);
    }

    /**
     * Update the specified analysis code
     */
    public function update(Request $request, $id)
    {
        $analysisCode = SkuItemNoteAnalysisCode::findOrFail($id);

        $request->validate([
            'analysis_group_id' => 'required|exists:sku_item_note_analysis_groups,id',
            'analysis_code' => [
                'required',
                'string',
                'max:20',
                'regex:/^[A-Z0-9-_]+$/',
                Rule::unique('sku_item_note_analysis_codes')->where(function ($query) use ($request) {
                    return $query->where('analysis_group_id', $request->analysis_group_id);
                })->ignore($analysisCode->id)
            ],
            'code_name' => 'required|string|max:100',
            'description' => 'nullable|string|max:500',
            'is_active' => 'boolean'
        ], [
            'analysis_code.regex' => 'Analysis code must contain only uppercase letters, numbers, hyphens, and underscores.',
            'analysis_code.unique' => 'This analysis code already exists in the selected group.'
        ]);

        $analysisCode->update([
            'analysis_group_id' => $request->analysis_group_id,
            'analysis_code' => strtoupper($request->analysis_code),
            'code_name' => $request->code_name,
            'description' => $request->description,
            'is_active' => $request->get('is_active', $analysisCode->is_active)
        ]);

        $analysisCode->load('analysisGroup');

        return response()->json($analysisCode);
    }

    /**
     * Remove the specified analysis code
     */
    public function destroy($id)
    {
        $analysisCode = SkuItemNoteAnalysisCode::findOrFail($id);
        $analysisCode->delete();

        return response()->json(['message' => 'Analysis code deleted successfully']);
    }

    /**
     * Toggle active status
     */
    public function toggleActive($id)
    {
        $analysisCode = SkuItemNoteAnalysisCode::findOrFail($id);
        $analysisCode->is_active = !$analysisCode->is_active;
        $analysisCode->save();

        $analysisCode->load('analysisGroup');

        return response()->json([
            'message' => 'Status updated successfully',
            'analysis_code' => $analysisCode
        ]);
    }

    /**
     * Get analysis groups for dropdown
     */
    public function getAnalysisGroups()
    {
        $groups = SkuItemNoteAnalysisGroup::active()
            ->orderBy('group_name')
            ->get(['id', 'group_code', 'group_name', 'category']);

        return response()->json($groups);
    }

    /**
     * Validate analysis code uniqueness within group
     */
    public function validateCode(Request $request)
    {
        $request->validate([
            'analysis_group_id' => 'required|exists:sku_item_note_analysis_groups,id',
            'analysis_code' => 'required|string|max:20',
            'id' => 'nullable|exists:sku_item_note_analysis_codes,id'
        ]);

        $query = SkuItemNoteAnalysisCode::where('analysis_group_id', $request->analysis_group_id)
            ->where('analysis_code', strtoupper($request->analysis_code));

        if ($request->filled('id')) {
            $query->where('id', '!=', $request->id);
        }

        $exists = $query->exists();

        return response()->json([
            'exists' => $exists,
            'message' => $exists ? 'Analysis code already exists in this group' : 'Analysis code is available'
        ]);
    }

    /**
     * Get analysis codes for specific group
     */
    public function getByGroup($groupId)
    {
        $analysisCodes = SkuItemNoteAnalysisCode::byGroup($groupId)
            ->active()
            ->orderBy('analysis_code')
            ->get();

        return response()->json($analysisCodes);
    }

    /**
     * Get data for printing
     */
    public function getForPrint(Request $request)
    {
        $query = SkuItemNoteAnalysisCode::withGroup();

        // Apply filters
        if ($request->filled('analysis_group_id')) {
            $query->byGroup($request->analysis_group_id);
        }

        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->active();
            } elseif ($request->status === 'inactive') {
                $query->inactive();
            }
        }

        // Get all records (no pagination for printing)
        $analysisCodes = $query->orderBy('analysis_code')->get();

        return response()->json($analysisCodes);
    }
} 