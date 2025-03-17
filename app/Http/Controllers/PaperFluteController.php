<?php

namespace App\Http\Controllers;

use App\Models\PaperFlute;
use Illuminate\Http\Request;

class PaperFluteController extends Controller
{
    public function index()
    {
        $paperFlutes = PaperFlute::all();
        return view('system-requirement.paperflute', compact('paperFlutes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:20|unique:paper_flutes',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        PaperFlute::create($validated);

        return redirect()->route('paper-flute.index')->with('success', 'Paper flute berhasil dibuat.');
    }

    public function edit($id)
    {
        $paperFlute = PaperFlute::findOrFail($id);
        $paperFlutes = PaperFlute::all();
        return view('system-requirement.paperflute', compact('paperFlute', 'paperFlutes'));
    }

    public function update(Request $request, $id)
    {
        $paperFlute = PaperFlute::findOrFail($id);

        $validated = $request->validate([
            'code' => 'required|string|max:20|unique:paper_flutes,code,' . $id,
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $paperFlute->update($validated);

        return redirect()->route('paper-flute.index')->with('success', 'Paper flute berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $paperFlute = PaperFlute::findOrFail($id);
        $paperFlute->delete();

        return redirect()->route('paper-flute.index')->with('success', 'Paper flute berhasil dihapus.');
    }
} 