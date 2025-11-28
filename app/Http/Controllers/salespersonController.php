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
            // Always load from database, ordered by Code, filter by Active status
            $salespersons = Salesperson::where('status', 'Active')->orderBy('Code')->get();

            // If there are no salespersons in the database, seed them
            if ($salespersons->isEmpty()) {
                $this->seedData();
                $salespersons = Salesperson::where('status', 'Active')->orderBy('Code')->get();
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
                'code' => 'required|string|max:50|unique:salesperson,Code',
                'name' => 'required|string|max:50',
                'grup' => 'nullable|string|max:20',
                'code_grup' => 'nullable|string|max:50',
                'target_sales' => 'nullable|numeric|min:0',
                'internal' => 'nullable|string|max:20',
                'email' => 'nullable|email|max:100',
                'status' => 'nullable|string|max:10'
            ]);

            if ($validator->fails()) {
                $errors = $validator->errors();
                $message = $errors->first();

                // If code already exists, suggest next available code
                if ($errors->has('code') && strpos($message, 'already been taken') !== false) {
                    $suggestedCode = $this->getNextAvailableCode($request->code);
                    $message = "Code '{$request->code}' already exists. Suggested code: {$suggestedCode}";
                }

                return response()->json([
                    'success' => false,
                    'message' => $message
                ], 422);
            }

            DB::beginTransaction();

            // Create salesperson using direct column mapping
            $salesperson = new Salesperson();
            $salesperson->Code = $request->code;
            $salesperson->Name = $request->name;
            $salesperson->Grup = $request->grup;
            $salesperson->CodeGrup = $request->code_grup;
            $salesperson->TargetSales = $request->target_sales ?? 0;
            $salesperson->Internal = $request->internal;
            $salesperson->Email = $request->email;
            $salesperson->status = $request->status ?? 'Active';
            $salesperson->save();

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
            $salesperson = Salesperson::where('Code', $code)->firstOrFail();

            $validator = Validator::make($request->all(), [
                'name' => 'nullable|string|max:50',
                'grup' => 'nullable|string|max:20',
                'code_grup' => 'nullable|string|max:50',
                'target_sales' => 'nullable|numeric|min:0',
                'internal' => 'nullable|string|max:20',
                'email' => 'nullable|email|max:100',
                'status' => 'nullable|string|max:10',
                'is_active' => 'nullable|boolean'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }

            // Update using direct column mapping
            if ($request->has('name')) {
                $salesperson->Name = $request->name;
            }
            if ($request->has('grup')) {
                $salesperson->Grup = $request->grup;
            }
            if ($request->has('code_grup')) {
                $salesperson->CodeGrup = $request->code_grup;
            }
            if ($request->has('target_sales')) {
                $salesperson->TargetSales = $request->target_sales ?? 0;
            }
            if ($request->has('internal')) {
                $salesperson->Internal = $request->internal;
            }
            if ($request->has('email')) {
                $salesperson->Email = $request->email;
            }
            if ($request->has('status')) {
                $salesperson->status = $request->status ?? 'Active';
            }
            if ($request->has('is_active')) {
                $salesperson->is_active = $request->is_active;
            }
            
            $salesperson->save();

            // Get the updated data
            $updatedPerson = Salesperson::where('Code', $code)->first();

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
            $salesperson = Salesperson::where('Code', $code)->firstOrFail();
            
            // Soft delete: update status to 'Inactive'
            $salesperson->update([
                'status' => 'Inactive'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Salesperson marked as inactive successfully'
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

        $salespersons = Salesperson::where('Code', 'like', "%{$query}%")
            ->orWhere('Name', 'like', "%{$query}%")
            ->orderBy('Code')
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
        $salesperson = Salesperson::where('Code', $code)->firstOrFail();

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
            // Filter by status 'Active' unless specifically requested otherwise
            $query = Salesperson::orderBy('Name');
            
            if (!request()->has('all_status')) {
                $query->where('status', 'Active');
            }
            
            $salespersons = $query->get();

            // Auto-seed when empty to ensure data is available for the Vue menu
            if ($salespersons->isEmpty()) {
                $this->seedData();
                // Re-fetch with same filters
                $query = Salesperson::orderBy('Name');
                if (!request()->has('all_status')) {
                    $query->where('status', 'Active');
                }
                $salespersons = $query->get();
            }

            // Trim status values to handle CHAR field padding
            $salespersons->transform(function ($person) {
                if ($person->status) {
                    $person->status = trim($person->status);
                }
                return $person;
            });

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

    /**
     * Display the Vue version of salesperson status management page
     *
     * @return \Inertia\Response
     */
    public function vueManageStatus()
    {
        try {
            $salespersons = Salesperson::orderBy('Code', 'asc')
                ->paginate(15);

            // Trim status values to handle CHAR field padding
            $items = collect($salespersons->items())->map(function ($person) {
                if ($person->status) {
                    $person->status = trim($person->status);
                }
                return $person;
            })->toArray();

            return Inertia::render('sales-management/system-requirement/standard-requirement/obsolete-unobsolete-salesperson', [
                'salespersons' => $items,
                'pagination' => [
                    'currentPage' => $salespersons->currentPage(),
                    'perPage' => $salespersons->perPage(),
                    'total' => $salespersons->total()
                ],
                'header' => 'Manage Salesperson Status'
            ]);
        } catch (\Exception $e) {
            Log::error('Error in SalespersonController@vueManageStatus: ' . $e->getMessage());

            return Inertia::render('sales-management/system-requirement/standard-requirement/obsolete-unobsolete-salesperson', [
                'salespersons' => [],
                'pagination' => [
                    'currentPage' => 1,
                    'perPage' => 15,
                    'total' => 0
                ],
                'header' => 'Manage Salesperson Status',
                'error' => 'Error displaying salespersons: ' . $e->getMessage()
            ]);
        }
    }

    public function getAllPurchasers()
    {
        $purchasers = Salesperson::where('position', 'PU')->orWhere('position', 'PU/RQ')->get();
        return response()->json($purchasers);
    }

    // ==================== SALES TEAM METHODS ====================

    /**
     * Get all sales teams (unique Grup values)
     */
    public function getSalesTeams()
    {
        try {
            $teams = Salesperson::select('Grup as name', 'CodeGrup as code')
                ->whereNotNull('Grup')
                ->where('Grup', '!=', '')
                ->distinct()
                ->orderBy('Grup')
                ->get()
                ->map(function($team) {
                    return [
                        'name' => $team->name,
                        'code' => $team->code
                    ];
                });

            return response()->json($teams);
        } catch (\Exception $e) {
            Log::error('Error getting sales teams: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to load sales teams'], 500);
        }
    }

    /**
     * Vue component for Define Sales Team
     */
    public function vueDefineTeam()
    {
        try {
            return Inertia::render('sales-management/system-requirement/standard-requirement/sales-team', [
                'header' => 'Define Sales Team'
            ]);
        } catch (\Exception $e) {
            Log::error('Error in SalespersonController@vueDefineTeam: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to load sales team page'], 500);
        }
    }

    /**
     * Store or update sales team
     */
    public function storeSalesTeam(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'code' => 'required|string|max:50',
                'name' => 'required|string|max:50',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }

            // Check if team already exists
            $existingTeam = Salesperson::where('CodeGrup', $request->code)
                ->orWhere('Grup', $request->name)
                ->first();

            if ($existingTeam) {
                // Update existing team members
                Salesperson::where('Grup', $existingTeam->Grup)
                    ->update([
                        'Grup' => $request->name,
                        'CodeGrup' => $request->code
                    ]);
            } else {
                // Create new team entry
                Salesperson::create([
                    'Code' => 'TEAM_' . $request->code,
                    'Name' => 'Team: ' . $request->name,
                    'Grup' => $request->name,
                    'CodeGrup' => $request->code,
                    'status' => 'Active'
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Sales team saved successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error storing sales team: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error saving sales team: ' . $e->getMessage()
            ], 500);
        }
    }

    // ==================== SALESPERSON TEAM METHODS ====================

    /**
     * Get salesperson teams (salesperson with their team info)
     */
    public function getSalespersonTeams()
    {
        try {
            $teams = Salesperson::select('Code', 'Name', 'Grup', 'CodeGrup', 'TargetSales', 'status')
                ->whereNotNull('Grup')
                ->whereNotLike('Code', 'TEAM_%')
                ->orderBy('Grup')
                ->orderBy('Name')
                ->get();

            return response()->json($teams);
        } catch (\Exception $e) {
            Log::error('Error getting salesperson teams: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to load salesperson teams'], 500);
        }
    }

    /**
     * Vue component for Define Salesperson Team
     */
    public function vueDefineSalespersonTeam()
    {
        try {
            return Inertia::render('sales-management/system-requirement/standard-requirement/salesperson-team', [
                'header' => 'Define Salesperson Team'
            ]);
        } catch (\Exception $e) {
            Log::error('Error in SalespersonController@vueDefineSalespersonTeam: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to load salesperson team page'], 500);
        }
    }

    /**
     * Assign salesperson to team
     */
    public function assignToTeam(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'salesperson_code' => 'required|exists:salesperson,Code',
                'team_name' => 'required|string',
                'team_code' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }

            $salesperson = Salesperson::where('Code', $request->salesperson_code)->first();
            $salesperson->update([
                'Grup' => $request->team_name,
                'CodeGrup' => $request->team_code
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Salesperson assigned to team successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error assigning salesperson to team: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error assigning salesperson to team: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get next available code based on existing code pattern
     */
    private function getNextAvailableCode($baseCode)
    {
        // Extract prefix and number from code (e.g., S106 -> S + 106)
        if (preg_match('/^([A-Z]+)(\d+)$/', $baseCode, $matches)) {
            $prefix = $matches[1];
            $number = (int)$matches[2];

            // Find next available number
            do {
                $number++;
                $newCode = $prefix . str_pad($number, strlen($matches[2]), '0', STR_PAD_LEFT);
                $exists = Salesperson::where('Code', $newCode)->exists();
            } while ($exists && $number < 9999);

            return $newCode;
        }

        // If pattern doesn't match, just append a number
        $counter = 1;
        do {
            $newCode = $baseCode . '_' . $counter;
            $exists = Salesperson::where('Code', $newCode)->exists();
            $counter++;
        } while ($exists && $counter < 100);

        return $newCode;
    }
}
