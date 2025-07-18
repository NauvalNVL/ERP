<?php

namespace App\Http\Controllers\MaterialManagement\SystemRequirement;

use App\Http\Controllers\Controller;
use App\Models\MmReportGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class MmReportGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reportGroups = MmReportGroup::all();
        
        return Inertia::render('material-management/system-requirement/inventory-setup/ReportGroup', [
            'reportGroups' => $reportGroups,
        ]);
    }

    /**
     * Display a view for printing the resource.
     */
    public function viewPrint()
    {
        $reportGroups = MmReportGroup::all();
        
        return Inertia::render('material-management/system-requirement/inventory-setup/ViewPrintReportGroup', [
            'reportGroups' => $reportGroups,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), MmReportGroup::rules());

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $reportGroup = MmReportGroup::create([
                'code' => $request->code,
                'name' => $request->name,
                'is_active' => $request->is_active ?? true,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Report group created successfully',
                'data' => $reportGroup,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create report group',
                'errors' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $reportGroup = MmReportGroup::findOrFail($id);
            
            return response()->json([
                'status' => 'success',
                'data' => $reportGroup,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Report group not found',
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $reportGroup = MmReportGroup::findOrFail($id);
            
            $validator = Validator::make($request->all(), MmReportGroup::rules($id));

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $reportGroup->update([
                'code' => $request->code,
                'name' => $request->name,
                'is_active' => $request->is_active ?? $reportGroup->is_active,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Report group updated successfully',
                'data' => $reportGroup,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update report group',
                'errors' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $reportGroup = MmReportGroup::findOrFail($id);
            $reportGroup->delete();
            
            return response()->json([
                'status' => 'success',
                'message' => 'Report group deleted successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete report group',
                'errors' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get all report groups for dropdown or selection.
     */
    public function getReportGroups()
    {
        $reportGroups = MmReportGroup::all();
        
        return response()->json([
            'status' => 'success',
            'data' => $reportGroups,
        ]);
    }
} 