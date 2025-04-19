<?php

namespace App\Http\Controllers;

use App\Models\ScoringTool;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ScoringToolController extends Controller
{
    /**
     * Display a listing of the scoring tools.
     */
    public function index(Request $request)
    {
        $query = ScoringTool::query();
        
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%");
            });
        }
        
        $scoringTools = $query->orderBy('code')->paginate(10);
        
        return view('system-requirement.scoringtool', compact('scoringTools'));
    }

    /**
     * Store a newly created scoring tool in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:10|unique:scoring_tools',
            'name' => 'required|string|max:100',
            'specification' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->route('scoring-tool.index')
                ->withErrors($validator)
                ->withInput();
        }

        ScoringTool::create($request->all());

        return redirect()->route('scoring-tool.index')
            ->with('success', 'Scoring Tool berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified scoring tool.
     */
    public function edit($id)
    {
        $scoringTool = ScoringTool::findOrFail($id);
        $scoringTools = ScoringTool::orderBy('code')->paginate(10);
        
        return view('system-requirement.scoringtool', compact('scoringTool', 'scoringTools'));
    }

    /**
     * Update the specified scoring tool in storage.
     */
    public function update(Request $request, $id)
    {
        $scoringTool = ScoringTool::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:10|unique:scoring_tools,code,' . $id,
            'name' => 'required|string|max:100',
            'specification' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->route('scoring-tool.edit', $id)
                ->withErrors($validator)
                ->withInput();
        }

        $scoringTool->update($request->all());

        return redirect()->route('scoring-tool.index')
            ->with('success', 'Scoring Tool berhasil diperbarui.');
    }

    /**
     * Remove the specified scoring tool from storage.
     */
    public function destroy($id)
    {
        $scoringTool = ScoringTool::findOrFail($id);
        $scoringTool->delete();

        return redirect()->route('scoring-tool.index')
            ->with('success', 'Scoring Tool berhasil dihapus.');
    }

    /**
     * Display the specified scoring tool.
     */
    public function show($id)
    {
        $scoringTool = ScoringTool::findOrFail($id);
        return response()->json($scoringTool);
    }

    /**
     * Display a listing of the resource for printing.
     *
     * @return \Illuminate\View\View
     */
    public function viewAndPrint()
    {
        // Ambil semua data scoring tool, urutkan berdasarkan code
        $scoringTools = ScoringTool::orderBy('code')->get(); 
        return view('system-requirement.viewandprintscoringtool', compact('scoringTools')); 
    }
}
