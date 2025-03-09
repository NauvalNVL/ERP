<?php

namespace App\Http\Controllers;

use App\Models\Industry;
use Illuminate\Http\Request;

class IndustryController extends Controller
{
    public function index()
    {
        $industries = Industry::orderBy('code')->get();
        return view('system-requirement.industry', compact('industries'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:4|unique:industries,code',
            'name' => 'required|string|max:100',
        ]);

        Industry::create([
            'code' => strtoupper($request->code),
            'name' => strtoupper($request->name),
        ]);

        return redirect()->route('industry.index')->with('success', 'Industry added successfully');
    }

    public function edit($id)
    {
        $industry = Industry::findOrFail($id);
        return response()->json($industry);
    }

    public function update(Request $request, $id)
    {
        $industry = Industry::findOrFail($id);
        
        $request->validate([
            'code' => 'required|string|max:4|unique:industries,code,' . $id,
            'name' => 'required|string|max:100',
        ]);

        $industry->update([
            'code' => strtoupper($request->code),
            'name' => strtoupper($request->name),
        ]);

        return redirect()->route('industry.index')->with('success', 'Industry updated successfully');
    }

    public function destroy($id)
    {
        $industry = Industry::findOrFail($id);
        $industry->delete();
        
        return redirect()->route('industry.index')->with('success', 'Industry deleted successfully');
    }
}
