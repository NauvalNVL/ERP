<?php

namespace App\Http\Controllers;

use App\Models\ColorGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class ColorGroupController extends Controller
{
    /**
     * Display a listing of color groups
     */
    public function index(Request $request)
    {
        try {
            $colorGroups = ColorGroup::orderBy('CG', 'asc')->get();
            
            // If no data exists, seed sample data
            if ($colorGroups->isEmpty()) {
                $this->seedData();
                $colorGroups = ColorGroup::orderBy('CG', 'asc')->get();
            }
            
            // Transform data to match Vue component expected format
            $colorGroupsTransformed = $colorGroups->map(function($group) {
                return [
                    'cg' => $group->CG,
                    'cg_name' => $group->CG_Name,
                    'cg_type' => $group->CG_Type
                ];
            });
            
            // Only return JSON for explicit API requests
            if ($request->is('api/*') || ($request->header('X-Requested-With') === 'XMLHttpRequest' && !$request->header('X-Inertia'))) {
                return response()->json($colorGroupsTransformed);
            }
            
            return Inertia::render('sales-management/system-requirement/standard-requirement/color-group', [
                'colorGroups' => $colorGroupsTransformed,
                'header' => 'Define Color Group'
            ]);
        } catch (\Exception $e) {
            Log::error('Error in ColorGroupController@index: ' . $e->getMessage());
            
            if ($request->is('api/*') || ($request->header('X-Requested-With') === 'XMLHttpRequest' && !$request->header('X-Inertia'))) {
                return response()->json([
                    'error' => true,
                    'message' => 'Error loading color groups: ' . $e->getMessage()
                ], 500);
            }
            
            return Inertia::render('sales-management/system-requirement/standard-requirement/color-group', [
                'colorGroups' => [],
                'header' => 'Define Color Group',
                'error' => 'Error loading color groups'
            ]);
        }
    }

    /**
     * Store a new color group
     */
    public function store(Request $request)
    {
        try {
            Log::info('Creating new color group with data:', $request->all());

            $validator = Validator::make($request->all(), [
                'cg' => 'required|unique:COLOR_GROUP,CG|max:15',
                'cg_name' => 'required|max:150',
                'cg_type' => 'required|max:50'
            ]);

            if ($validator->fails()) {
                Log::warning('Validation failed:', $validator->errors()->toArray());
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }

            // Create color group
            $colorGroup = ColorGroup::create([
                'CG' => trim($request->cg),
                'CG_Name' => trim($request->cg_name),
                'CG_Type' => trim($request->cg_type),
                'status' => 'Act'
            ]);

            Log::info('Color group created successfully:', ['CG' => $colorGroup->CG]);
            
            // Transform back to Vue format for response
            $colorGroupResponse = [
                'cg' => $colorGroup->CG,
                'cg_name' => $colorGroup->CG_Name,
                'cg_type' => $colorGroup->CG_Type,
                'status' => $colorGroup->status
            ];

            return response()->json([
                'success' => true,
                'message' => 'Color group berhasil ditambahkan',
                'data' => $colorGroupResponse
            ]);
        } catch (\Exception $e) {
            Log::error('Error creating color group: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Error creating color group: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update a color group
     */
    /**
     * Update a color group
     */
    public function update(Request $request, $code)
    {
        try {
            Log::info('Updating color group with CG: ' . $code);
            Log::info('Request data:', $request->all());

            $validator = Validator::make($request->all(), [
                'cg_name' => 'nullable|max:150',
                'cg_type' => 'nullable|max:50',
                'is_active' => 'nullable|boolean'
            ]);

            if ($validator->fails()) {
                Log::warning('Validation failed:', $validator->errors()->toArray());
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }

            $colorGroup = ColorGroup::where('CG', $code)->first();
            
            if (!$colorGroup) {
                Log::warning('Color group not found with CG: ' . $code);
                return response()->json([
                    'success' => false,
                    'message' => 'Color group tidak ditemukan'
                ], 404);
            }

            // Update color group
            $updateData = [];
            if ($request->has('cg_name')) $updateData['CG_Name'] = trim($request->cg_name);
            if ($request->has('cg_type')) $updateData['CG_Type'] = trim($request->cg_type);
            // Status update should be done via toggleStatus
            // if ($request->has('status')) $updateData['status'] = $request->status;
            
            $colorGroup->update($updateData);

            Log::info('Color group updated successfully:', ['CG' => $code]);
            
            // Transform back to Vue format for response
            $colorGroupResponse = [
                'cg' => $colorGroup->CG,
                'cg_name' => $colorGroup->CG_Name,
                'cg_type' => $colorGroup->CG_Type,
                'status' => $colorGroup->status
            ];

            return response()->json([
                'success' => true,
                'message' => 'Color group berhasil diperbarui',
                'data' => $colorGroupResponse
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating color group: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Error updating color group: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a color group
     */
    public function destroy($code)
    {
        try {
            $colorGroup = ColorGroup::where('CG', $code)->first();
            
            if (!$colorGroup) {
                return response()->json([
                    'success' => false,
                    'message' => 'Color group tidak ditemukan'
                ], 404);
            }

            // Check if any colors use this group (from COLOR table)
            $colorsUsingGroup = \App\Models\Color::where('GroupCode', $code)->count();

            if ($colorsUsingGroup > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak dapat menghapus color group. Masih digunakan oleh ' . $colorsUsingGroup . ' color(s).'
                ], 422);
            }

            $colorGroup->delete();

            return response()->json([
                'success' => true,
                'message' => 'Color group berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting color group: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error deleting color group: ' . $e->getMessage()
            ], 500);
        }
    }

    public function toggleStatus($code)
    {
        try {
            $colorGroup = ColorGroup::where('CG', $code)->first();
            
            if (!$colorGroup) {
                return response()->json([
                    'success' => false,
                    'message' => 'Color group not found'
                ], 404);
            }

            $colorGroup->status = ($colorGroup->status === 'Act') ? 'Obs' : 'Act';
            $colorGroup->save();

            return response()->json([
                'success' => true,
                'message' => 'Color group status updated successfully',
                'data' => [
                    'cg' => $colorGroup->CG,
                    'cg_name' => $colorGroup->CG_Name,
                    'cg_type' => $colorGroup->CG_Type,
                    'status' => $colorGroup->status
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error in ColorGroupController@toggleStatus: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error toggling color group status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the Vue version for printing
     */
    public function vueViewAndPrint()
    {
        try {
            return Inertia::render('sales-management/system-requirement/standard-requirement/view-and-print-color-group', [
                'header' => 'View & Print Color Groups'
            ]);
        } catch (\Exception $e) {
            Log::error('Error in ColorGroupController@vueViewAndPrint: ' . $e->getMessage());
            return Inertia::render('sales-management/system-requirement/standard-requirement/view-and-print-color-group', [
                'header' => 'View & Print Color Groups',
                'error' => 'Failed to load color group data for printing'
            ]);
        }
    }

    /**
     * Display the Vue version of color group status management page
     *
     * @return \Inertia\Response
     */
    public function vueManageStatus()
    {
        try {
            $colorGroups = ColorGroup::orderBy('CG', 'asc')->get();

            // Transform data
            $colorGroupsTransformed = $colorGroups->map(function($group) {
                return [
                    'cg' => $group->CG,
                    'cg_name' => $group->CG_Name,
                    'cg_type' => $group->CG_Type,
                    'status' => $group->status
                ];
            });

            return Inertia::render('sales-management/system-requirement/standard-requirement/obsolete-unobsolete-color-group', [
                'colorGroups' => $colorGroupsTransformed,
                'pagination' => [
                    'currentPage' => 1,
                    'perPage' => $colorGroupsTransformed->count(),
                    'total' => $colorGroupsTransformed->count()
                ],
                'header' => 'Manage Color Group Status'
            ]);
        } catch (\Exception $e) {
            Log::error('Error in ColorGroupController@vueManageStatus: ' . $e->getMessage());

            return Inertia::render('sales-management/system-requirement/standard-requirement/obsolete-unobsolete-color-group', [
                'colorGroups' => [],
                'pagination' => [
                    'currentPage' => 1,
                    'perPage' => 0,
                    'total' => 0
                ],
                'header' => 'Manage Color Group Status',
                'error' => 'Error displaying color groups: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * API endpoint to get color groups
     * By default returns only active groups (status = 'Act').
     * Use ?all_status=1 to retrieve all groups including obsolete ones.
     */
    public function apiIndex(Request $request)
    {
        try {
            $query = ColorGroup::orderBy('CG', 'asc');

            // Only return active color groups unless explicitly asked for all statuses
            if (!$request->has('all_status') || !$request->all_status) {
                $query->where('status', 'Act');
            }

            $colorGroups = $query->get();
            
            // Transform data
            $colorGroupsTransformed = $colorGroups->map(function($group) {
                return [
                    'cg' => $group->CG,
                    'cg_name' => $group->CG_Name,
                    'cg_type' => $group->CG_Type,
                    'status' => $group->status
                ];
            });
            
            return response()->json($colorGroupsTransformed);
        } catch (\Exception $e) {
            Log::error('Error in ColorGroupController@apiIndex: ' . $e->getMessage());
            return response()->json([
                'error' => true,
                'message' => 'Error loading color groups'
            ], 500);
        }
    }

    /**
     * Seed color group data (private method)
     */
    private function seedData()
    {
        try {
            $colorGroups = [
                ['CG' => '01', 'CG_Name' => 'BLACK', 'CG_Type' => 'X-Flexo'],
                ['CG' => '02', 'CG_Name' => 'WHITE', 'CG_Type' => 'X-Flexo'],
                ['CG' => '03', 'CG_Name' => 'RED', 'CG_Type' => 'X-Flexo'],
                ['CG' => '04', 'CG_Name' => 'BLUE', 'CG_Type' => 'X-Flexo'],
                ['CG' => '05', 'CG_Name' => 'GREEN', 'CG_Type' => 'X-Flexo'],
                ['CG' => '06', 'CG_Name' => 'CYAN', 'CG_Type' => 'C-Coating'],
                ['CG' => '07', 'CG_Name' => 'MAGENTA', 'CG_Type' => 'C-Coating'],
                ['CG' => '08', 'CG_Name' => 'YELLOW', 'CG_Type' => 'S-Offset'],
                ['CG' => '09', 'CG_Name' => 'ORANGE', 'CG_Type' => 'X-Flexo'],
                ['CG' => '10', 'CG_Name' => 'YELLOW', 'CG_Type' => 'X-Flexo'],
                ['CG' => '11', 'CG_Name' => 'PINK', 'CG_Type' => 'X-Flexo'],
                ['CG' => '12', 'CG_Name' => 'GOLD', 'CG_Type' => 'X-Flexo'],
                ['CG' => '13', 'CG_Name' => 'GRAY', 'CG_Type' => 'X-Flexo'],
                ['CG' => '14', 'CG_Name' => 'BROWN', 'CG_Type' => 'X-Flexo'],
                ['CG' => '15', 'CG_Name' => 'VARNISH', 'CG_Type' => 'C-Coating'],
                ['CG' => '16', 'CG_Name' => 'VIOLET', 'CG_Type' => 'X-Flexo'],
                ['CG' => '17', 'CG_Name' => 'PURPLE', 'CG_Type' => 'X-Flexo'],
                ['CG' => '18', 'CG_Name' => 'SILVER', 'CG_Type' => 'S-Offset'],
                ['CG' => 'PANTONE', 'CG_Name' => 'PANTONE', 'CG_Type' => 'S-Offset'],
            ];

            foreach ($colorGroups as $group) {
                if (!ColorGroup::where('CG', $group['CG'])->exists()) {
                    ColorGroup::create($group);
                }
            }
        } catch (\Exception $e) {
            Log::error('Error seeding color group data: ' . $e->getMessage());
        }
    }

    /**
     * Seed the database with sample color group data (public API method)
     */
    public function seed()
    {
        try {
            $this->seedData();
            
            return response()->json([
                'success' => true,
                'message' => 'Color group seed data created successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error in ColorGroupController@seed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to seed color group data: ' . $e->getMessage()
            ], 500);
        }
    }
}
