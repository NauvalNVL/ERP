<?php

namespace App\Http\Controllers;

use App\Models\Industry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class IndustryController extends Controller
{
    public function index()
    {
        $industries = Industry::orderBy('code')->get();
        
        if (request()->ajax() || request()->wantsJson()) {
            return response()->json($industries);
        }
        
        return view('sales-management.system-requirement.system-requirement.standard-requirement.industry', compact('industries'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:4|unique:industries,code',
            'name' => 'required|string|max:100',
        ]);

        $industry = Industry::create([
            'code' => strtoupper($request->code),
            'name' => strtoupper($request->name),
        ]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Industry added successfully',
                'data' => $industry
            ]);
        }

        return redirect()->route('vue.industry.index')->with('success', 'Industry added successfully');
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

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Industry updated successfully',
                'data' => $industry
            ]);
        }

        return redirect()->route('vue.industry.index')->with('success', 'Industry updated successfully');
    }

    public function destroy($id)
    {
        $industry = Industry::findOrFail($id);
        $industry->delete();
        
        if (request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Industry deleted successfully'
            ]);
        }
        
        return redirect()->route('vue.industry.index')->with('success', 'Industry deleted successfully');
    }

    /**
     * Display a listing of the resource for printing.
     *
     * @return \Illuminate\View\View
     */
    public function viewAndPrint()
    {
        $industries = Industry::orderBy('code')->get();
        return view('sales-management.system-requirement.system-requirement.standard-requirement.viewandprintindustry', compact('industries'));
    }

    /**
     * Search for an industry by code.
     *
     * @param string $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function search($code)
    {
        $exists = Industry::where('code', strtoupper($code))->exists();
        return response()->json(['exists' => $exists]);
    }

    /**
     * Display the Vue index page for industry management
     *
     * @return \Inertia\Response
     */
    public function vueIndex()
    {
        try {
            $industries = Industry::select('id', 'code', 'name')
                ->orderBy('code')
                ->get();
                
            return Inertia::render('sales-management/system-requirement/standard-requirement/industry', [
                'industries' => $industries,
                'header' => 'Industry Management'
            ]);
        } catch (\Exception $e) {
            Log::error('Error in IndustryController@vueIndex: ' . $e->getMessage());
            
            return Inertia::render('sales-management/system-requirement/standard-requirement/industry', [
                'industries' => [],
                'header' => 'Industry Management',
                'error' => 'Error displaying industry data: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Display the Vue version of the view and print page
     *
     * @return \Inertia\Response
     */
    public function vueViewAndPrint()
    {
        try {
            return Inertia::render('sales-management/system-requirement/standard-requirement/view-and-print-industry');
        } catch (\Exception $e) {
            Log::error('Error in IndustryController@vueViewAndPrint: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to load industry data for printing'], 500);
        }
    }
}
