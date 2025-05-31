<?php

namespace App\Http\Controllers;

use App\Models\ColorGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class ColorGroupController extends Controller
{
    public function index(Request $request)
    {
        try {
            if ($request->ajax() || $request->wantsJson()) {
                $colorGroups = ColorGroup::select('cg', 'cg_name', 'cg_type')
                    ->orderBy('cg')
                    ->get();
                
                return response()->json($colorGroups);
            }

            $colorGroups = ColorGroup::paginate(10);
            return view('sales-management.system-requirement.system-requirement.standard-requirement.color-group', compact('colorGroups'));
        } catch (\Exception $e) {
            Log::error('Error in ColorGroupController@index: ' . $e->getMessage());
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['error' => 'Failed to load color groups data'], 500);
            }
            
            return view('sales-management.system-requirement.system-requirement.standard-requirement.color-group')
                ->with('error', 'Failed to load color groups data');
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cg' => 'required|string|max:10|unique:color_groups,cg',
            'cg_name' => 'required|string|max:255',
            'cg_type' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->route('system-requirement.color-group.index')
                ->withErrors($validator)
                ->withInput();
        }

        try {
            ColorGroup::create([
                'cg' => $request->cg,
                'cg_name' => $request->cg_name,
                'cg_type' => $request->cg_type,
            ]);

            return redirect()
                ->route('system-requirement.color-group.index')
                ->with('success', 'Color group created successfully');
        } catch (\Exception $e) {
            Log::error('Error in ColorGroupController@store: ' . $e->getMessage());
            return redirect()
                ->route('system-requirement.color-group.index')
                ->with('error', 'Failed to create color group');
        }
    }

    public function update(Request $request, $id)
    {
        try {
            // Find the color group by cg field instead of id
            $colorGroup = ColorGroup::where('cg', $id)->first();
            
            if (!$colorGroup) {
                return response()->json([
                    'success' => false,
                    'message' => 'Color group not found'
                ], 404);
            }
            
        $validator = Validator::make($request->all(), [
            'cg_name' => 'required|string|max:255',
            'cg_type' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }

            // Update the color group
            $colorGroup->update([
                'cg_name' => $request->cg_name,
                'cg_type' => $request->cg_type,
            ]);

            // Get the updated data
            $updatedGroup = ColorGroup::where('cg', $id)->first();

            return response()->json([
                'success' => true,
                'message' => 'Color group updated successfully',
                'data' => $updatedGroup
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating color group: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error updating color group: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $colorGroup = ColorGroup::where('cg', $id)->first();
            
            if (!$colorGroup) {
                return response()->json([
                    'success' => false,
                    'message' => 'Color group not found'
                ], 404);
            }
            
            $colorGroup->delete();

            return response()->json([
                'success' => true,
                'message' => 'Color group deleted successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error in ColorGroupController@destroy: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete color group'
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \\App\\Models\\ColorGroup  $colorGroup
     * @return \\Illuminate\\Http\\RedirectResponse
     */
    public function show(ColorGroup $colorGroup)
    {
        return Redirect::route('color-group.index');
    }

    public function vueIndex()
    {
        try {
            return Inertia::render('sales-management/system-requirement/standard-requirement/color-group', [
                'header' => 'Color Group Management'
            ]);
        } catch (\Exception $e) {
            Log::error('Error in ColorGroupController@vueIndex: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to load color groups data'], 500);
        }
    }

    /**
     * Display a listing of the resource for printing.
     *
     * @return \\Illuminate\\View\\View
     */
    public function viewAndPrint()
    {
        $colorGroups = ColorGroup::orderBy('cg_name')->get();
        return view('sales-management.system-requirement.system-requirement.standard-requirement.viewandprintcolorgroup', compact('colorGroups'));
    }

    /**
     * Display a listing of the resource for printing in Vue.
     *
     * @return \Inertia\Response
     */
    public function vueViewAndPrint()
    {
        try {
            return Inertia::render('sales-management/system-requirement/standard-requirement/view-and-print-color-group');
        } catch (\Exception $e) {
            Log::error('Error in ColorGroupController@vueViewAndPrint: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to load color groups data for printing'], 500);
        }
    }

    /**
     * Seed the database with sample color group data.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function seed()
    {
        try {
            // Sample color group data
            $sampleData = [
                ['cg' => 'R', 'cg_name' => 'Red', 'cg_type' => 'Standard'],
                ['cg' => 'B', 'cg_name' => 'Blue', 'cg_type' => 'Standard'],
                ['cg' => 'G', 'cg_name' => 'Green', 'cg_type' => 'Standard'],
                ['cg' => 'Y', 'cg_name' => 'Yellow', 'cg_type' => 'Standard'],
                ['cg' => 'BK', 'cg_name' => 'Black', 'cg_type' => 'Standard'],
                ['cg' => 'W', 'cg_name' => 'White', 'cg_type' => 'Standard'],
                ['cg' => 'GD', 'cg_name' => 'Gold', 'cg_type' => 'Metallic'],
                ['cg' => 'SL', 'cg_name' => 'Silver', 'cg_type' => 'Metallic'],
                ['cg' => 'PM', 'cg_name' => 'Premium Mix', 'cg_type' => 'Custom'],
                ['cg' => 'PNT', 'cg_name' => 'Pantone', 'cg_type' => 'Pantone'],
            ];

            $createdCount = 0;

            foreach ($sampleData as $data) {
                // Skip if color group already exists
                if (ColorGroup::where('cg', $data['cg'])->exists()) {
                    continue;
                }

                ColorGroup::create($data);
                $createdCount++;
            }

            return response()->json([
                'success' => true,
                'message' => "Successfully seeded $createdCount color groups"
            ]);
        } catch (\Exception $e) {
            Log::error('Error in ColorGroupController@seed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to seed color groups data: ' . $e->getMessage()
            ], 500);
        }
    }
} 