<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Salesperson;
use App\Models\SalesTeam;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Database\Seeders\SalespersonSeeder;
use Database\Seeders\SalesTeamSeeder;
use Inertia\Inertia;

class SalespersonController extends Controller
{
    public function index()
    {
        try {
            // Always load from database, ordered by code
            $salespersons = Salesperson::orderBy('code')->get();
            
            // If there are no salespersons in the database, seed them
            if ($salespersons->isEmpty()) {
                $this->seedData();
                $salespersons = Salesperson::orderBy('code')->get();
            }
            
            // If the request wants JSON, return JSON response
            if (request()->wantsJson() || request()->ajax()) {
                return response()->json($salespersons);
            }
            
            return view('sales-management.system-requirement.system-requirement.standard-requirement.salesperson', compact('salespersons'));
        } catch (\Exception $e) {
            Log::error('Error loading salespersons: ' . $e->getMessage());
            
            if (request()->wantsJson() || request()->ajax()) {
                return response()->json([
                    'error' => true,
                    'message' => 'Error loading data from database: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()->with('error', 'Error loading data from database: ' . $e->getMessage());
        }
    }

    public function list()
    {
        $salespersons = Salesperson::orderBy('code')->paginate(15);
        return view('sales-management.system-requirement.system-requirement.standard-requirement.salesperson.list', compact('salespersons'));
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'code' => 'required|string|max:10|unique:salespersons,code',
                'name' => 'required|string|max:100',
                'sales_team_id' => 'required|exists:sales_team,id',
                'position' => 'required|string|max:50',
                'user_id' => 'nullable|string|max:20',
                'is_active' => 'required|boolean'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }

            DB::beginTransaction();

            $salesperson = Salesperson::create([
                'code' => $request->code,
                'name' => $request->name,
                'sales_team_id' => $request->sales_team_id,
                'position' => $request->position,
                'user_id' => $request->user_id,
                'is_active' => $request->is_active
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Salesperson created successfully',
                'data' => $salesperson
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating salesperson: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error creating salesperson: ' . $e->getMessage()
            ], 500);
        }
    }

    public function edit($id)
    {
        $salesperson = Salesperson::with('salesTeam')->findOrFail($id);
        return view('sales-management.system-requirement.system-requirement.standard-requirement.salesperson', compact('salesperson'));
    }

    public function update(Request $request, $code)
    {
        try {
            $salesperson = Salesperson::where('code', $code)->firstOrFail();
            
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
                'sales_team_id' => 'required|exists:sales_team,id',
                'position' => 'required|string|max:50',
                'user_id' => 'nullable|string|max:20',
                'is_active' => 'required|boolean'
        ]);

        if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }

            // Update the salesperson
            $salesperson->update([
                'name' => $request->name,
                'sales_team_id' => $request->sales_team_id,
                'position' => $request->position,
                'user_id' => $request->user_id,
                'is_active' => $request->is_active
            ]);

            // Get the updated data
            $updatedPerson = Salesperson::where('code', $code)->first();

            return response()->json([
                'success' => true,
                'message' => 'Salesperson updated successfully',
                'data' => $updatedPerson
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating salesperson: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error updating salesperson: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($code)
    {
        try {
            $salesperson = Salesperson::where('code', $code)->firstOrFail();
            $salesperson->delete();

            return response()->json([
                'success' => true,
                'message' => 'Salesperson deleted successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting salesperson: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error deleting salesperson: ' . $e->getMessage()
            ], 500);
        }
    }

    public function search(Request $request)
    {
        try {
        $query = $request->get('query', '');
            
        $salespersons = Salesperson::where('code', 'like', "%{$query}%")
            ->orWhere('name', 'like', "%{$query}%")
            ->orderBy('code')
            ->get();

        return response()->json($salespersons);
        } catch (\Exception $e) {
            Log::error('Error searching salespersons: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error searching salespersons: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getDetails($code)
    {
        try {
        $salesperson = Salesperson::with('salesTeam')
            ->where('code', $code)
                ->firstOrFail();

            return response()->json([
                'success' => true,
                'data' => $salesperson
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting salesperson details: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error getting salesperson details: ' . $e->getMessage()
            ], 404);
        }
    }

    /**
     * Display a listing of the resource for printing.
     *
     * @return \Illuminate\View\View
     */
    public function viewAndPrint()
    {
        $salespersons = Salesperson::with('salesTeam')->orderBy('name')->get();
        return view('sales-management.system-requirement.system-requirement.standard-requirement.viewandprintsalesperson', compact('salespersons'));
    }

    /**
     * Display a listing of the resource for printing in Vue.
     *
     * @return \Inertia\Response
     */
    public function vueViewAndPrint()
    {
        try {
            return Inertia::render('sales-management/system-requirement/standard-requirement/view-and-print-salesperson');
        } catch (\Exception $e) {
            Log::error('Error in SalespersonController@vueViewAndPrint: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to load salesperson data for printing'], 500);
        }
    }

    /**
     * API endpoint to get salespersons in JSON format.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiIndex()
    {
        try {
            $salespersons = Salesperson::with('salesTeam')
                ->orderBy('name')
                ->get();

            // Auto-seed when empty to ensure data is available for the Vue menu
            if ($salespersons->isEmpty()) {
                $this->seedData();
                $salespersons = Salesperson::with('salesTeam')
                    ->orderBy('name')
                    ->get();
            }
            
            return response()->json($salespersons);
        } catch (\Exception $e) {
            Log::error('Error in SalespersonController@apiIndex: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to load salesperson data'], 500);
        }
    }

    private function seedData()
    {
        try {
            DB::beginTransaction();
            
            // Ensure Sales Teams exist first (FK dependency)
            $teamSeeder = new SalesTeamSeeder();
            $teamSeeder->run();

            // Run the salesperson seeder
            $seeder = new SalespersonSeeder();
            $seeder->run();
            
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error seeding salesperson data: ' . $e->getMessage());
            throw $e;
        }
    }

    public function seed()
    {
        try {
            $this->seedData();
            return response()->json(['success' => true, 'message' => 'Salesperson seed data created successfully']);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating salesperson seed data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Render the Vue component for salesperson management.
     * 
     * @return \Inertia\Response
     */
    public function vueIndex()
    {
        try {
            return Inertia::render('sales-management/system-requirement/standard-requirement/salesperson', [
                'header' => 'Salesperson Management'
            ]);
        } catch (\Exception $e) {
            Log::error('Error in SalespersonController@vueIndex: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to load salesperson data'], 500);
        }
    }

    public function getAllPurchasers()
    {
        $purchasers = Salesperson::where('position', 'PU')->orWhere('position', 'PU/RQ')->get();
        return response()->json($purchasers);
    }
}
