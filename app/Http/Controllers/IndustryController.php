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
            'code' => 'required|string|max:5|unique:industry,code',
            'name' => 'required|string|max:30',
        ]);

        $industry = Industry::create([
            'code' => strtoupper($request->code),
            'name' => $request->name,
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
            'name' => 'nullable|string|max:30',
            'status' => 'nullable|string|max:3',
        ]);

        $updateData = [];
        if ($request->has('name')) {
            $updateData['name'] = $request->name;
        }

        if ($request->has('status')) {
            $updateData['status'] = $request->status;
        }

        $industry->update($updateData);

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

        // Mark as obsolete instead of deleting
        $industry->update(['status' => 'Obs']);

        if (request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Industry marked as obsolete successfully'
            ]);
        }

        return redirect()->route('vue.industry.index')->with('success', 'Industry marked as obsolete successfully');
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
        $industry = Industry::select('status')
            ->where('code', strtoupper($code))
            ->first();

        if (!$industry) {
            return response()->json(['exists' => false]);
        }

        return response()->json([
            'exists' => true,
            'status' => $industry->status ?? 'Act',
        ]);
    }

    /**
     * Display the Vue index page for industry management
     *
     * @return \Inertia\Response
     */
    public function vueIndex()
    {
        try {
            $industries = Industry::select('code', 'name', 'status')
                ->where(function ($q) {
                    $q->whereNull('status')
                        ->orWhere('status', 'Act');
                })
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
     * Display the Vue version of industry status management page
     *
     * @return \Inertia\Response
     */
    public function vueManageStatus()
    {
        try {
            $industries = Industry::orderBy('code', 'asc')->paginate(15);

            return Inertia::render('sales-management/system-requirement/standard-requirement/obsolete-unobsolete-industry', [
                'industries' => $industries->items(),
                'pagination' => [
                    'currentPage' => $industries->currentPage(),
                    'perPage' => $industries->perPage(),
                    'total' => $industries->total()
                ],
                'header' => 'Manage Industry Status'
            ]);
        } catch (\Exception $e) {
            Log::error('Error in IndustryController@vueManageStatus: ' . $e->getMessage());

            return Inertia::render('sales-management/system-requirement/standard-requirement/obsolete-unobsolete-industry', [
                'industries' => [],
                'pagination' => [
                    'currentPage' => 1,
                    'perPage' => 15,
                    'total' => 0
                ],
                'header' => 'Manage Industry Status',
                'error' => 'Error displaying industries: ' . $e->getMessage()
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
    public function apiIndex(Request $request)
    {
        try {
            $query = Industry::orderBy('code');

            if (!$request->has('all_status') || !$request->all_status) {
                $query->where(function ($q) {
                    $q->whereNull('status')
                        ->orWhere('status', 'Act');
                });
            }

            $industries = $query->get()->map(function ($industry) {
                $industry->status = $industry->status ?? 'Act';
                return $industry;
            });

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


}
