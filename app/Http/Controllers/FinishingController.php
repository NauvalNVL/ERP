<?php

namespace App\Http\Controllers;

use App\Models\Finishing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class FinishingController extends Controller
{
    public function index()
    {
        try {
            // Always load from database, ordered by code
            $finishings = Finishing::orderBy('code')->get();
            
            // If the request wants JSON, return JSON response
            if (request()->wantsJson() || request()->ajax()) {
                return response()->json($finishings);
            }
            
            return view('sales-management.system-requirement.system-requirement.standard-requirement.finishing', compact('finishings'));
        } catch (\Exception $e) {
            Log::error('Error loading finishings: ' . $e->getMessage());
            
            if (request()->wantsJson() || request()->ajax()) {
                return response()->json([
                    'error' => true,
                    'message' => 'Error loading data from database: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()->with('error', 'Error loading data from database: ' . $e->getMessage());
        }
    }

    public function create()
    {
        return view('sales-management.system-requirement.system-requirement.standard-requirement.finishing-create');
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'code' => 'required|unique:finishings,code',
                'description' => 'required|string|max:255',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }

            $finishing = Finishing::create([
                'code' => $request->code,
                'description' => $request->description,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Finishing created successfully',
                'data' => $finishing
            ]);
        } catch (\Exception $e) {
            Log::error('Error creating finishing: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error creating finishing: ' . $e->getMessage()
            ], 500);
        }
    }

    public function edit(Finishing $finishing)
    {
        return view('sales-management.system-requirement.system-requirement.standard-requirement.finishing-edit', compact('finishing'));
    }

    public function update(Request $request, $code)
    {
        try {
            $finishing = Finishing::where('code', $code)->firstOrFail();
            
            $validator = Validator::make($request->all(), [
                'code' => 'required|string|max:10|unique:finishings,code,' . $finishing->id,
                'description' => 'required|string|max:255',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }

            // Update the finishing
            $finishing->update([
                'code' => $request->code,
                'description' => $request->description,
                'updated_at' => now()
            ]);

            // Get the updated data
            $updatedFinishing = Finishing::where('code', $request->code)->first();

            return response()->json([
                'success' => true,
                'message' => 'Finishing updated successfully',
                'data' => $updatedFinishing
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating finishing: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error updating finishing: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($code)
    {
        try {
            $finishing = Finishing::where('code', $code)->firstOrFail();
            $finishing->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Finishing deleted successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting finishing: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error deleting finishing: ' . $e->getMessage()
            ], 500);
        }
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
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function viewAndPrint()
    {
        try {
            $finishings = Finishing::orderBy('code')->get();
            return view('sales-management.system-requirement.system-requirement.standard-requirement.viewandprintfinishing', compact('finishings'));
        } catch (\Exception $e) {
            Log::error('Error viewing finishings: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error loading data: ' . $e->getMessage());
        }
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
            return \Inertia\Inertia::render('sales-management/system-requirement/standard-requirement/finishing', [
                'header' => 'Finishing Management'
            ]);
        } catch (\Exception $e) {
            Log::error('Error in FinishingController@vueIndex: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to load finishing data'], 500);
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
            Log::error('Error in FinishingController@vueViewAndPrint: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to load finishing data for printing'], 500);
        }
    }

    /**
     * Get all finishings as JSON for API.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiIndex()
    {
        try {
            $finishings = Finishing::orderBy('code')->get();
            return response()->json($finishings);
        } catch (\Exception $e) {
            Log::error('Error in FinishingController@apiIndex: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to load finishing data'], 500);
        }
    }

    /**
     * Seed finishing data.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function seed()
    {
        try {
            // Default finishings
            $defaultFinishings = [
                ['code' => 'G', 'description' => 'Glue Application'],
                ['code' => 'S', 'description' => 'Stitching'],
                ['code' => 'A', 'description' => 'Assembly'],
                ['code' => 'H', 'description' => 'Heat Treatment'],
                ['code' => 'W', 'description' => 'Wrapping']
            ];

            $created = [];

            foreach ($defaultFinishings as $finishing) {
                $exists = Finishing::where('code', $finishing['code'])->exists();
                if (!$exists) {
                    $created[] = Finishing::create([
                        'code' => $finishing['code'],
                        'description' => $finishing['description']
                    ]);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Finishing seed data created successfully',
                'created' => $created
            ]);
        } catch (\Exception $e) {
            Log::error('Error seeding finishing data: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error seeding finishing data: ' . $e->getMessage()
            ], 500);
        }
    }
}
