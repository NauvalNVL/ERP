<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SalesTeam;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class SalesTeamController extends Controller
{
    public function index()
    {
        try {
            // Always load from database, ordered by code
            $salesTeams = SalesTeam::orderBy('code')->get();
            
            // If no data exists, automatically seed the data
            if ($salesTeams->isEmpty()) {
                $this->seedData();
                $salesTeams = SalesTeam::orderBy('code')->get();
            }
            
            // If the request wants JSON, return JSON response
            if (request()->wantsJson() || request()->ajax()) {
                return response()->json($salesTeams);
            }
            
            return view('sales-management.system-requirement.system-requirement.standard-requirement.salesteam', compact('salesTeams'));
        } catch (\Exception $e) {
            Log::error('Error loading sales teams: ' . $e->getMessage());
            
            if (request()->wantsJson() || request()->ajax()) {
                return response()->json([
                    'error' => true,
                    'message' => 'Error loading data from database: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()->with('error', 'Error loading data from database: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'code' => 'required|unique:sales_team,code',
                'name' => 'required|string|max:100',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }

            $salesTeam = SalesTeam::create([
                'code' => $request->code,
                'name' => $request->name,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Sales team created successfully',
                'data' => $salesTeam
            ]);
        } catch (\Exception $e) {
            Log::error('Error creating sales team: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error creating sales team: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function search(Request $request)
    {
        $query = $request->input('query');
        
        $salesTeams = SalesTeam::where('code', 'LIKE', "%{$query}%")
            ->orWhere('name', 'LIKE', "%{$query}%")
            ->get();
            
        return response()->json($salesTeams);
    }
    
    public function edit($id)
    {
        $salesTeam = SalesTeam::findOrFail($id);
        $salesTeams = SalesTeam::all();
        return view('sales-management.system-requirement.system-requirement.standard-requirement.salesteam-edit', compact('salesTeam', 'salesTeams'));
    }
    
    public function update(Request $request, $code)
    {
        try {
            $salesTeam = SalesTeam::where('code', $code)->firstOrFail();
            
            $validator = Validator::make($request->all(), [
                'code' => 'required|string|max:10|unique:sales_team,code,' . $salesTeam->id,
                'name' => 'required|string|max:100',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }

            // Update the sales team
            $salesTeam->update([
                'code' => $request->code,
                'name' => $request->name,
                'updated_at' => now()
            ]);

            // Get the updated data
            $updatedTeam = SalesTeam::where('code', $request->code)->first();

            return response()->json([
                'success' => true,
                'message' => 'Sales team updated successfully',
                'data' => $updatedTeam
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating sales team: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error updating sales team: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function destroy($id)
    {
        try {
            $salesTeam = SalesTeam::findOrFail($id);
            $salesTeam->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Sales team deleted successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting sales team: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error deleting sales team: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display a listing of the resource for printing.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function viewAndPrint()
    {
        try {
            $salesTeams = SalesTeam::orderBy('code')->get();
            return view('sales-management.system-requirement.system-requirement.standard-requirement.viewandprintsalesteam', compact('salesTeams'));
        } catch (\Exception $e) {
            Log::error('Error viewing sales teams: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error loading data: ' . $e->getMessage());
        }
    }

    /**
     * Display a listing of the resource for printing in Vue.
     *
     * @return \Inertia\Response
     */
    public function vueViewAndPrint()
    {
        try {
            return \Inertia\Inertia::render('sales-management/system-requirement/standard-requirement/view-and-print-sales-team');
        } catch (\Exception $e) {
            Log::error('Error in SalesTeamController@vueViewAndPrint: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to load sales team data for printing'], 500);
        }
    }

    /**
     * Display the Vue component for sales team management.
     *
     * @return \Inertia\Response
     */
    public function vueIndex()
    {
        try {
            return \Inertia\Inertia::render('sales-management/system-requirement/standard-requirement/sales-team', [
                'header' => 'Sales Team Management'
            ]);
        } catch (\Exception $e) {
            Log::error('Error in SalesTeamController@vueIndex: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to load sales team data'], 500);
        }
    }

    /**
     * Get all sales teams as JSON for API.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiIndex()
    {
        try {
            $salesTeams = SalesTeam::orderBy('code')->get();
            return response()->json($salesTeams);
        } catch (\Exception $e) {
            Log::error('Error in SalesTeamController@apiIndex: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to load sales team data'], 500);
        }
    }

    /**
     * Seed sales team data (private method for internal use).
     *
     * @return void
     */
    private function seedData()
    {
        try {
            // Default sales teams
            $defaultTeams = [
                ['code' => '01', 'name' => 'MBI'],
                ['code' => '02', 'name' => 'MANAGEMENT LOCAL'],
                ['code' => '03', 'name' => 'MANAGEMENT MNC']
            ];

            foreach ($defaultTeams as $team) {
                $exists = SalesTeam::where('code', $team['code'])->exists();
                if (!$exists) {
                    SalesTeam::create([
                        'code' => $team['code'],
                        'name' => $team['name']
                    ]);
                }
            }
        } catch (\Exception $e) {
            Log::error('Error seeding sales team data: ' . $e->getMessage());
        }
    }

    /**
     * Seed sales team data (public API method).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function seed()
    {
        try {
            $this->seedData();
            
            return response()->json([
                'success' => true,
                'message' => 'Sales team seed data created successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error seeding sales team data: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error seeding sales team data: ' . $e->getMessage()
            ], 500);
        }
    }
}