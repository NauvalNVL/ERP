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

        $finishing = Finishing::create($request->all());
        
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Finishing berhasil ditambahkan',
                'data' => $finishing
            ]);
        }
        
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
        
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Finishing berhasil diupdate'
            ]);
        }
        
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
     * Display the specified finishing.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $finishing = Finishing::findOrFail($id);
        return response()->json($finishing);
    }

    /**
     * Display a listing of the resource for printing.
     *
     * @return \Illuminate\View\View
     */
    public function viewAndPrint()
    {
        // Ambil semua data finishing, urutkan berdasarkan code
        $finishings = Finishing::orderBy('code')->get(); 
        return view('sales-management.system-requirement.system-requirement.standard-requirement.viewandprintfinishing', compact('finishings')); 
    }

    /**
     * Get finishings for JSON API - add row numbers
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getFinishingsJson()
    {
        $finishings = Finishing::orderBy('code')->get();
        $numberedFinishings = [];
        
        foreach ($finishings as $index => $finishing) {
            $item = $finishing->toArray();
            $item['row_number'] = $index + 1;
            $numberedFinishings[] = $item;
        }
        
        return response()->json($numberedFinishings);
    }

    /**
     * Search for a finishing by code.
     *
     * @param string $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function search($code)
    {
        $exists = Finishing::where('code', strtoupper($code))->exists();
        return response()->json(['exists' => $exists]);
    }
    
    /**
     * Display the Vue index page for finishing management
     *
     * @return \Inertia\Response
     */
    public function vueIndex()
    {
        try {
            $finishings = Finishing::select('code', 'description')
                ->orderBy('code')
                ->get();
                
            return \Inertia\Inertia::render('sales-management/system-requirement/standard-requirement/finishing', [
                'finishings' => $finishings,
                'header' => 'Finishing Management'
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error in FinishingController@vueIndex: ' . $e->getMessage());
            
            return \Inertia\Inertia::render('sales-management/system-requirement/standard-requirement/finishing', [
                'finishings' => [],
                'header' => 'Finishing Management',
                'error' => 'Error displaying finishing data: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * Display the Vue view and print page for finishing
     *
     * @return \Inertia\Response
     */
    public function vueViewAndPrint()
    {
        try {
            return \Inertia\Inertia::render('sales-management/system-requirement/standard-requirement/view-and-print-finishing', [
                'header' => 'View & Print Finishings'
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error in FinishingController@vueViewAndPrint: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to load finishing data for printing'], 500);
        }
    }
}
