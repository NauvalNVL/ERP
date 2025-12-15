<?php

namespace App\Http\Controllers;

use App\Models\Color;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ColorController extends Controller
{
    /**
     * Display a listing of the colors.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        try {
            // Get only colors (not color groups) from COLOR table
            $colorsQuery = Color::whereNotNull('GroupCode')
                ->whereRaw('GroupCode != Color_Code')
                ->orderBy('Color_Code', 'asc');

            // Get color groups for dropdown
            $colorGroupsQuery = Color::whereNull('GroupCode')
                ->orWhereRaw('GroupCode = Color_Code')
                ->orderBy('Color_Code', 'asc');

            // By default only return active records unless explicitly asked for all statuses
            if (!$request->has('all_status') || !$request->all_status) {
                $colorsQuery->where(function ($q) {
                    $q->whereNull('status')
                      ->orWhere('status', 'Act');
                });

                $colorGroupsQuery->where(function ($q) {
                    $q->whereNull('status')
                      ->orWhere('status', 'Act');
                });
            }

            $colors = $colorsQuery->get();
            $colorGroups = $colorGroupsQuery->get();

            // Transform data to match Vue component expected format
            $colorsTransformed = $colors->map(function($color) {
                return [
                    'color_id' => $color->Color_Code,
                    'color_name' => $color->Color_Name,
                    'color_group_id' => $color->GroupCode,
                    'cg_type' => $color->Group,
                    'status' => $color->status,
                ];
            });

            $colorGroupsTransformed = $colorGroups->map(function($group) {
                return [
                    'cg' => $group->Color_Code,
                    'cg_name' => $group->Color_Name,
                    'cg_type' => $group->Group,
                    'status' => $group->status
                ];
            });

            // For debugging
            if ($colors->isEmpty()) {
                Log::info('No colors found in the database');
            } else {
                Log::info('Found ' . $colors->count() . ' colors in the database');
            }

            // Only return JSON for explicit API requests
            if ($request->is('api/*') || ($request->header('X-Requested-With') === 'XMLHttpRequest' && !$request->header('X-Inertia'))) {
                return response()->json([
                    'colors' => $colorsTransformed,
                    'color_groups' => $colorGroupsTransformed
                ]);
            }

            return view('sales-management.system-requirement.system-requirement.standard-requirement.color', [
                'colors' => $colors,
                'colorGroups' => $colorGroups
            ]);
        } catch (\Exception $e) {
            Log::error('Error in ColorController@index: ' . $e->getMessage());

            // Only return JSON for explicit API requests
            if ($request->is('api/*') || ($request->header('X-Requested-With') === 'XMLHttpRequest' && !$request->header('X-Inertia'))) {
                return response()->json([
                    'error' => true,
                    'message' => 'Error displaying color data: ' . $e->getMessage()
                ], 500);
            }

            return view('sales-management.system-requirement.system-requirement.standard-requirement.color', [
                'colors' => [],
                'colorGroups' => [],
                'error' => 'Error displaying color data'
            ]);
        }
    }

    /**
     * Show the form for creating a new color.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        $colorGroups = Color::whereNull('GroupCode')
            ->orWhereRaw('GroupCode = Color_Code')
            ->get();
        return view('sales-management.system-requirement.system-requirement.standard-requirement.color', compact('colorGroups'));
    }

    /**
     * Store a newly created color in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        try {
            Log::info('Creating new color with data:', $request->all());

            $validator = Validator::make($request->all(), [
                'color_id' => 'required|unique:COLOR,Color_Code|max:15',
                'color_name' => 'required|max:150',
                'color_group_id' => 'required|max:15',
                'cg_type' => 'required|max:50'
            ]);

            if ($validator->fails()) {
                Log::warning('Validation failed:', $validator->errors()->toArray());

                if ($request->wantsJson() || $request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => $validator->errors()->first()
                    ], 422);
                }

                return back()->withErrors($validator)->withInput();
            }

            // Transform Vue field names to database column names
            $color = Color::create([
                'Color_Code' => trim($request->color_id),
                'Color_Name' => trim($request->color_name),
                'GroupCode' => trim($request->color_group_id),
                'Group' => trim($request->cg_type),
                'status' => 'Act'
            ]);

            Log::info('Color created successfully:', ['Color_Code' => $color->Color_Code]);

            // Transform back to Vue format for response
            $colorResponse = [
                'color_id' => $color->Color_Code,
                'color_name' => $color->Color_Name,
                'color_group_id' => $color->GroupCode,
                'cg_type' => $color->Group,
            ];

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Color berhasil ditambahkan',
                    'data' => $colorResponse
                ]);
            }

            return redirect()->route('colors.index')->with('success', 'Color created successfully');
        } catch (\Exception $e) {
            Log::error('Error in ColorController@store: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error creating color: ' . $e->getMessage()
                ], 500);
            }

            return back()->withInput()->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified color.
     *
     * @param  string  $id
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function edit($id)
    {
        try {
            $color = DB::table('colors')->where('color_id', $id)->first();

            if (!$color) {
                return redirect()->route('color.index')->with('error', 'Warna tidak ditemukan');
            }

            return view('sales-management.system-requirement.system-requirement.standard-requirement.color-edit', ['color' => $color]);
        } catch (\Exception $e) {
            Log::error('Error in ColorController@edit: ' . $e->getMessage());
            return redirect()->route('color.index')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified color in storage.
     */
    public function update(Request $request, $color_id)
    {
        try {
            Log::info('Updating color with ID: ' . $color_id);
            Log::info('Request data:', $request->all());

            $validator = Validator::make($request->all(), [
                'color_name' => 'nullable|string|max:150',
                'color_group_id' => 'nullable|string|max:15',
                'cg_type' => 'nullable|string|max:50',
                'is_active' => 'nullable|boolean'
            ]);

            if ($validator->fails()) {
                Log::warning('Validation failed:', $validator->errors()->toArray());
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }

            // Find color using Color model with correct primary key
            $color = Color::where('Color_Code', $color_id)->first();

            if (!$color) {
                Log::warning('Color not found with ID: ' . $color_id);
                return response()->json([
                    'success' => false,
                    'message' => 'Color tidak ditemukan'
                ], 404);
            }

            // Update the color with transformed field names
            $updateData = [];
            if ($request->has('color_name')) $updateData['Color_Name'] = trim($request->color_name);
            if ($request->has('color_group_id')) $updateData['GroupCode'] = trim($request->color_group_id);
            if ($request->has('cg_type')) $updateData['Group'] = trim($request->cg_type);
            // Status update should be done via toggleStatus
            // if ($request->has('status')) $updateData['status'] = $request->status;

            $color->update($updateData);

            Log::info('Color updated successfully:', ['Color_Code' => $color_id]);

            // Transform back to Vue format for response
            $colorResponse = [
                'color_id' => $color->Color_Code,
                'color_name' => $color->Color_Name,
                'color_group_id' => $color->GroupCode,
                'cg_type' => $color->Group,
                'status' => $color->status
            ];

            return response()->json([
                'success' => true,
                'message' => 'Color berhasil diperbarui',
                'data' => $colorResponse
            ]);

        } catch (\Exception $e) {
            Log::error('Error updating color: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());

            return response()->json([
                'success' => false,
                'message' => 'Error updating color: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the seeder file with new data.
     */
    protected function updateSeederFile($color)
    {
        try {
            $seederPath = database_path('seeders/ColorSeeder.php');

            if (!file_exists($seederPath)) {
                Log::error('Seeder file not found at: ' . $seederPath);
                return false;
            }

            $seederContent = file_get_contents($seederPath);

            // Create the new color data string (without origin)
            $newColorData = "            [
                'color_id' => '{$color->color_id}',
                'color_name' => '{$color->color_name}',
                'color_group_id' => '{$color->color_group_id}',
                'cg_type' => '{$color->cg_type}',
                'created_at' => now(),
                'updated_at' => now(),
            ],";

            // Find the existing color data in the seeder file
            $pattern = "/\[\s*'color_id'\s*=>\s*'{$color->color_id}'.*?\]/s";

            if (preg_match($pattern, $seederContent)) {
                // Replace existing color data
                $newContent = preg_replace($pattern, $newColorData, $seederContent);
            } else {
                // Add new color data before the closing bracket of the array
                $newContent = str_replace(
                    "    protected \$colors = [\n",
                    "    protected \$colors = [\n" . $newColorData . "\n",
                    $seederContent
                );
            }

            // Write the updated content back to the file
            if (file_put_contents($seederPath, $newContent) === false) {
                Log::error('Failed to write to seeder file');
                return false;
            }

            Log::info('Seeder file updated successfully for color: ' . $color->color_id);
            return true;
        } catch (\Exception $e) {
            Log::error('Error updating seeder file: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return false;
        }
    }

    /**
     * Remove the specified color from storage.
     *
     * @param  string  $color_id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function destroy($color_id)
    {
        try {
            $color = Color::where('Color_Code', $color_id)->first();

            if ($color) {
                $color->delete();

                if (request()->wantsJson() || request()->ajax()) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Warna berhasil dihapus'
                    ]);
                }

                return redirect()->route('color.index')->with('success', 'Warna berhasil dihapus');
            } else {
                if (request()->wantsJson() || request()->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Warna tidak ditemukan'
                    ], 404);
                }

                return back()->with('error', 'Warna tidak ditemukan');
            }
        } catch (\Exception $e) {
            Log::error('Error in ColorController@destroy: ' . $e->getMessage());

            if (request()->wantsJson() || request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error deleting color: ' . $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Display a listing of the colors for Vue component.
     *
     * @return \Inertia\Response
     */
    public function vueIndex()
    {
        try {
            // Get only active colors (not color groups) from COLOR table
            $colors = Color::whereNotNull('GroupCode')
                ->whereRaw('GroupCode != Color_Code')
                ->where('status', 'Act')
                ->orderBy('Color_Code', 'asc')
                ->get();

            // Get active color groups for dropdown
            $colorGroups = Color::whereNull('GroupCode')
                ->orWhereRaw('GroupCode = Color_Code')
                ->where('status', 'Act')
                ->orderBy('Color_Code', 'asc')
                ->get();

            // Transform data to match Vue component expected format
            $colorsTransformed = $colors->map(function($color) {
                return [
                    'color_id' => $color->Color_Code,
                    'color_name' => $color->Color_Name,
                    'color_group_id' => $color->GroupCode,
                    'cg_type' => $color->Group,
                    'status' => $color->status
                ];
            });

            $colorGroupsTransformed = $colorGroups->map(function($group) {
                return [
                    'cg' => $group->Color_Code,
                    'cg_name' => $group->Color_Name,
                    'cg_type' => $group->Group
                ];
            });

            return Inertia::render('sales-management/system-requirement/standard-requirement/color', [
                'colors' => $colorsTransformed,
                'colorGroups' => $colorGroupsTransformed,
                'header' => 'Color Management'
            ]);
        } catch (\Exception $e) {
            Log::error('Error in ColorController@vueIndex: ' . $e->getMessage());
            return Inertia::render('sales-management/system-requirement/standard-requirement/color', [
                'colors' => [],
                'colorGroups' => [],
                'header' => 'Color Management',
                'error' => 'Error displaying color data: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Display a listing of the colors for API consumers (JSON only).
     * By default returns only active colors (status = 'Act').
     * Use ?all_status=1 to retrieve all colors including obsolete ones.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiIndex(Request $request)
    {
        try {
            // Base query: only colors (not color groups) from COLOR table
            $query = Color::whereNotNull('GroupCode')
                ->whereRaw('GroupCode != Color_Code')
                ->orderBy('Color_Code', 'asc');

            // Only return active colors unless explicitly asked for all statuses
            if (!$request->has('all_status') || !$request->all_status) {
                $query->where('status', 'Act');
            }

            $colors = $query->get();

            // Transform data to match Vue component expected format
            $colorsTransformed = $colors->map(function($color) {
                return [
                    'color_id' => $color->Color_Code,
                    'color_name' => $color->Color_Name,
                    'color_group_id' => $color->GroupCode,
                    'group_name' => $color->colorGroup ? $color->colorGroup->Color_Name : '',
                    'cg_type' => $color->Group,
                    'status' => $color->status
                ];
            });

            return response()->json($colorsTransformed);
        } catch (\Exception $e) {
            Log::error('Error in ColorController@apiIndex: ' . $e->getMessage());
            return response()->json([
                'error' => true,
                'message' => 'Error displaying color data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display a listing of the resource for printing.
     *
     * @return \Illuminate\View\View
     */
    public function viewAndPrint()
    {
        // Ambil semua data warna, urutkan berdasarkan nama
        // Eager load Color Group relationship
        $colors = Color::with('colorGroup')->orderBy('Color_Name')->get();
        return view('sales-management.system-requirement.system-requirement.standard-requirement.viewandprintcolor', compact('colors'));
    }

    /**
     * Display a listing of the resource for printing with Vue.
     *
     * @return \Inertia\Response
     */
    /**
     * Display the Vue version for printing
     */
    public function vueViewAndPrint()
    {
        try {
            return Inertia::render('sales-management/system-requirement/standard-requirement/view-and-print-color', [
                'header' => 'View & Print Colors'
            ]);
        } catch (\Exception $e) {
            Log::error('Error in ColorController@vueViewAndPrint: ' . $e->getMessage());
            return Inertia::render('sales-management/system-requirement/standard-requirement/view-and-print-color', [
                'header' => 'View & Print Colors',
                'error' => 'Failed to load color data for printing'
            ]);
        }
    }

    /**
     * Display the Vue version of color status management page
     *
     * @return \Inertia\Response
     */
    public function vueManageStatus()
    {
        try {
            // Get all colors (not color groups) from COLOR table for status management
            $colors = Color::whereNotNull('GroupCode')
                ->whereRaw('GroupCode != Color_Code')
                ->orderBy('Color_Code', 'asc')
                ->get();

            // Transform data
            $colorsTransformed = $colors->map(function($color) {
                return [
                    'color_id' => $color->Color_Code,
                    'color_name' => $color->Color_Name,
                    'group_code' => $color->GroupCode,
                    'group_name' => $color->Group, // Use the Group column which contains the group type/name
                    'status' => $color->status
                ];
            });

            return Inertia::render('sales-management/system-requirement/standard-requirement/obsolete-unobsolete-color', [
                'colors' => $colorsTransformed,
                'pagination' => [
                    'currentPage' => 1,
                    'perPage' => $colorsTransformed->count(),
                    'total' => $colorsTransformed->count()
                ],
                'header' => 'Manage Color Status'
            ]);
        } catch (\Exception $e) {
            Log::error('Error in ColorController@vueManageStatus: ' . $e->getMessage());

            return Inertia::render('sales-management/system-requirement/standard-requirement/obsolete-unobsolete-color', [
                'colors' => [],
                'pagination' => [
                    'currentPage' => 1,
                    'perPage' => 0,
                    'total' => 0
                ],
                'header' => 'Manage Color Status',
                'error' => 'Error displaying colors: ' . $e->getMessage()
            ]);
        }
    }

    public function toggleStatus($color_code)
    {
        try {
            $color = Color::where('Color_Code', $color_code)->first();

            if (!$color) {
                return response()->json([
                    'success' => false,
                    'message' => 'Color not found'
                ], 404);
            }

            $color->status = ($color->status === 'Act') ? 'Obs' : 'Act';
            $color->save();

            return response()->json([
                'success' => true,
                'message' => 'Color status updated successfully',
                'data' => [
                    'color_id' => $color->Color_Code,
                    'color_name' => $color->Color_Name,
                    'group_code' => $color->GroupCode,
                    'group_name' => $color->Group,
                    'status' => $color->status
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error in ColorController@toggleStatus: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error toggling color status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display View & Print page for Color Groups.
     *
     * @return \Inertia\Response
     */
    public function vueViewAndPrintColorGroups()
    {
        try {
            return Inertia::render('sales-management/system-requirement/standard-requirement/view-and-print-color-group', [
                'header' => 'View & Print Color Groups'
            ]);
        } catch (\Exception $e) {
            Log::error('Error in ColorController@vueViewAndPrintColorGroups: ' . $e->getMessage());
            return Inertia::render('sales-management/system-requirement/standard-requirement/view-and-print-color-group', [
                'header' => 'View & Print Color Groups',
                'error' => 'Failed to load color group data for printing'
            ]);
        }
    }

    /**
     * Seed color data (private method for internal use).
     *
     * @return void
     */
    private function seedData()
    {
        return;
    }

    /**
     * Seed the database with sample color data (public API method).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function seed()
    {
        return response()->json([
            'success' => false,
            'message' => 'Color seeding is disabled'
        ], 410);
    }

    // ==================== COLOR GROUP METHODS ====================

    /**
     * Display a listing of color groups
     */
    public function indexColorGroups(Request $request)
    {
        try {
            // Get only color groups from COLOR table
            $colorGroups = Color::whereNull('GroupCode')
                ->orWhereRaw('GroupCode = Color_Code')
                ->orderBy('Color_Code', 'asc')
                ->get();

            // Transform data to match Vue component expected format
            $colorGroupsTransformed = $colorGroups->map(function($group) {
                return [
                    'cg' => $group->Color_Code,
                    'cg_name' => $group->Color_Name,
                    'cg_type' => $group->Group
                ];
            });

            // Only return JSON for explicit API requests (with /api/ prefix or X-Requested-With header)
            // Inertia requests should always get Inertia response
            if ($request->is('api/*') || ($request->header('X-Requested-With') === 'XMLHttpRequest' && !$request->header('X-Inertia'))) {
                return response()->json($colorGroupsTransformed);
            }

            return Inertia::render('sales-management/system-requirement/standard-requirement/color-group', [
                'colorGroups' => $colorGroupsTransformed,
                'header' => 'Color Group Management'
            ]);
        } catch (\Exception $e) {
            Log::error('Error in ColorController@indexColorGroups: ' . $e->getMessage());

            // Only return JSON for explicit API requests
            if ($request->is('api/*') || ($request->header('X-Requested-With') === 'XMLHttpRequest' && !$request->header('X-Inertia'))) {
                return response()->json([
                    'error' => true,
                    'message' => 'Error loading color groups: ' . $e->getMessage()
                ], 500);
            }

            return Inertia::render('sales-management/system-requirement/standard-requirement/color-group', [
                'colorGroups' => [],
                'error' => 'Error loading color groups'
            ]);
        }
    }

    /**
     * Store a new color group
     */
    public function storeColorGroup(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'cg' => 'required|unique:COLOR,Color_Code|max:15',
                'cg_name' => 'required|max:150',
                'cg_type' => 'required|max:50'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }

            // Create color group with GroupCode = Color_Code (self-reference)
            $colorGroup = Color::create([
                'Color_Code' => $request->cg,
                'Color_Name' => $request->cg_name,
                'GroupCode' => $request->cg, // Self-reference indicates color group
                'Group' => $request->cg_type
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Color group created successfully',
                'data' => $colorGroup
            ]);
        } catch (\Exception $e) {
            Log::error('Error creating color group: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error creating color group: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update a color group
     */
    public function updateColorGroup(Request $request, $code)
    {
        try {
            $validator = Validator::make($request->all(), [
                'cg_name' => 'required|max:150',
                'cg_type' => 'required|max:50'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }

            $colorGroup = Color::where('Color_Code', $code)->first();

            if (!$colorGroup) {
                return response()->json([
                    'success' => false,
                    'message' => 'Color group not found'
                ], 404);
            }

            // Update color group
            $colorGroup->update([
                'Color_Name' => $request->cg_name,
                'Group' => $request->cg_type
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Color group updated successfully',
                'data' => $colorGroup
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating color group: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error updating color group: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a color group
     */
    public function destroyColorGroup($code)
    {
        try {
            $colorGroup = Color::where('Color_Code', $code)->first();

            if (!$colorGroup) {
                return response()->json([
                    'success' => false,
                    'message' => 'Color group not found'
                ], 404);
            }

            // Check if any colors use this group
            $colorsUsingGroup = Color::where('GroupCode', $code)
                ->where('Color_Code', '!=', $code)
                ->count();

            if ($colorsUsingGroup > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete color group. It is being used by ' . $colorsUsingGroup . ' color(s).'
                ], 422);
            }

            $colorGroup->delete();

            return response()->json([
                'success' => true,
                'message' => 'Color group deleted successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting color group: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error deleting color group: ' . $e->getMessage()
            ], 500);
        }
    }
}
