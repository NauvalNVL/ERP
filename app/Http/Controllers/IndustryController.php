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
        
        // If no data exists, automatically seed the data
        if ($industries->isEmpty()) {
            $this->seedData();
            $industries = Industry::orderBy('code')->get();
        }
        
        if (request()->ajax() || request()->wantsJson()) {
            return response()->json($industries);
        }
        
        return view('sales-management.system-requirement.system-requirement.standard-requirement.industry', compact('industries'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:5|unique:industry,code',
            'name' => 'required|string|max:30',
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

    public function update(Request $request, $code)
    {
        $industry = Industry::where('code', $code)->firstOrFail();
        
        $request->validate([
            'name' => 'required|string|max:30',
        ]);

        $industry->update([
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

    public function destroy($code)
    {
        $industry = Industry::where('code', $code)->firstOrFail();
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
            $industries = Industry::select('code', 'name')
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
     * @return \Inertia\Response|\Illuminate\Http\JsonResponse
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

    /**
     * API endpoint to get industries in JSON format.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiIndex()
    {
        try {
            $industries = Industry::select('code', 'name')
                ->orderBy('code')
                ->get();
            
            return response()->json($industries, 200, [], JSON_PRETTY_PRINT);
        } catch (\Exception $e) {
            Log::error('Error in IndustryController@apiIndex: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json([
                'error' => 'Failed to load industry data',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Seed industry data (private method for internal use).
     *
     * @return void
     */
    private function seedData()
    {
        try {
            // Default industries
            $defaultIndustries = [
                ['code' => 'F', 'name' => 'Food'],
                ['code' => 'E', 'name' => 'Electronics'],
                ['code' => 'P', 'name' => 'Pharmaceuticals'],
                ['code' => 'A', 'name' => 'Automotive'],
                ['code' => 'R', 'name' => 'Retail']
            ];

            foreach ($defaultIndustries as $industry) {
                $exists = Industry::where('code', $industry['code'])->exists();
                if (!$exists) {
                    Industry::create([
                        'code' => $industry['code'],
                        'name' => $industry['name']
                    ]);
                }
            }
        } catch (\Exception $e) {
            Log::error('Error seeding industry data: ' . $e->getMessage());
        }
    }

    /**
     * Seed industry data (public API method).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function seed()
    {
        try {
            $this->seedData();
            
            return response()->json([
                'success' => true,
                'message' => 'Industry seed data created successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error seeding industry data: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error seeding industry data: ' . $e->getMessage()
            ], 500);
        }
    }
}
