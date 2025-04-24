<?php

namespace App\Http\Controllers;

use App\Models\Finishing;
use Illuminate\Http\Request;

class FinishingController extends Controller
{
    public function index()
    {
        $finishings = Finishing::all();
        return view('sales-management.system-requirement.system-requirement.standard-requirement.finishing', compact('finishings'));
    }

    public function create()
    {
        return view('sales-management.system-requirement.system-requirement.standard-requirement.finishing-create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:finishings,code',
            'description' => 'required',
        ]);

        Finishing::create($request->all());
        
        return redirect()->route('finishing.index')
            ->with('success', 'Finishing berhasil ditambahkan');
    }

    public function edit(Finishing $finishing)
    {
        return view('sales-management.system-requirement.system-requirement.standard-requirement.finishing-edit', compact('finishing'));
    }

    public function update(Request $request, Finishing $finishing)
    {
        $request->validate([
            'code' => 'required|unique:finishings,code,' . $finishing->id,
            'description' => 'required',
        ]);

        $finishing->update($request->all());
        
        return redirect()->route('finishing.index')
            ->with('success', 'Finishing berhasil diupdate');
    }

    public function destroy(Finishing $finishing)
    {
        $finishing->delete();
        
        return redirect()->route('finishing.index')
            ->with('success', 'Finishing berhasil dihapus');
    }

    /**
     * Display a listing of the resource for printing.
     *
     * @return \\Illuminate\\View\\View
     */
    public function viewAndPrint()
    {
        // Ambil semua data finishing, urutkan berdasarkan code
        $finishings = Finishing::orderBy('code')->get(); 
        return view('sales-management.system-requirement.system-requirement.standard-requirement.viewandprintfinishing', compact('finishings')); 
    }
}
