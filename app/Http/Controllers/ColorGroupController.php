<?php

namespace App\Http\Controllers;

use App\Models\ColorGroup;
use Illuminate\Http\Request;

class ColorGroupController extends Controller
{
    public function index()
    {
        $colorGroups = ColorGroup::all();
        return view('system-requirement.color-group', compact('colorGroups'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'cg' => 'required|unique:color_groups|max:10',
            'cg_name' => 'required|max:50',
            'cg_type' => 'nullable|in:X-Flex,C-Coating,S-Offset'
        ]);

        ColorGroup::create($validatedData);

        return redirect()->route('color-group.index')->with('success', 'Color Group created successfully');
    }

    public function update(Request $request, $id)
    {
        $colorGroup = ColorGroup::findOrFail($id);

        $validatedData = $request->validate([
            'name' => 'required|max:50',
            'cg_type' => 'nullable|in:X-Flex,C-Coating,S-Offset',
            'status' => 'in:active,inactive'
        ]);

        $colorGroup->update($validatedData);

        return redirect()->route('color-group.index')->with('success', 'Color Group updated successfully');
    }

    public function destroy($id)
    {
        $colorGroup = ColorGroup::findOrFail($id);
        $colorGroup->delete();

        return redirect()->route('color-group.index')->with('success', 'Color Group deleted successfully');
    }
} 