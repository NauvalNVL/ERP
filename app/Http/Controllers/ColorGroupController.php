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
            if ($request->ajax()) {
                $colorGroups = ColorGroup::select('cg as cg_id', 'cg_name', 'cg_type')
                    ->orderBy('cg')
                    ->get();
                
                return response()->json($colorGroups);
            }

            $colorGroups = ColorGroup::paginate(10);
            return view('sales-management.system-requirement.system-requirement.standard-requirement.color-group', compact('colorGroups'));
        } catch (\Exception $e) {
            Log::error('Error in ColorGroupController@index: ' . $e->getMessage());
            
            if ($request->ajax()) {
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
        $validator = Validator::make($request->all(), [
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
            $colorGroup = ColorGroup::where('cg', $id)->firstOrFail();
            $colorGroup->update([
                'cg_name' => $request->cg_name,
                'cg_type' => $request->cg_type,
            ]);

            return redirect()
                ->route('system-requirement.color-group.index')
                ->with('success', 'Color group updated successfully');
        } catch (\Exception $e) {
            Log::error('Error in ColorGroupController@update: ' . $e->getMessage());
            return redirect()
                ->route('system-requirement.color-group.index')
                ->with('error', 'Failed to update color group');
        }
    }

    public function destroy($id)
    {
        try {
            $colorGroup = ColorGroup::where('cg', $id)->firstOrFail();
            $colorGroup->delete();

            return redirect()
                ->route('system-requirement.color-group.index')
                ->with('success', 'Color group deleted successfully');
        } catch (\Exception $e) {
            Log::error('Error in ColorGroupController@destroy: ' . $e->getMessage());
            return redirect()
                ->route('system-requirement.color-group.index')
                ->with('error', 'Failed to delete color group');
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
            $colorGroups = ColorGroup::all();
            return response()->json($colorGroups);
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
} 