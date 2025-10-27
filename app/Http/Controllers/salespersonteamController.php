<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Schema;
use Inertia\Inertia;

class SalespersonTeamController extends Controller
{
    public function index()
    {
        // Cek apakah tabel-tabel yang diperlukan ada di database
        if (!Schema::hasTable('salesperson_teams')) {
            return view('sales-management.system-requirement.system-requirement.standard-requirement.salespersonteam', [
                'salespersons' => [],
                'salesTeams' => [],
                'error' => 'Tabel salesperson_teams tidak ditemukan. Silakan jalankan migrasi terlebih dahulu.'
            ]);
        }

        try {
            // Mengambil data salesperson team dari tabel
            $salespersons = DB::table('salesperson_teams')
                ->select(
                    'id',
                    's_person_code',
                    'salesperson_name',
                    'st_code',
                    'sales_team_name',
                    'sales_team_position'
                )
                ->orderBy('s_person_code')
                ->get();

            // If there are no salesperson teams in the database, seed them
            if ($salespersons->isEmpty()) {
                $this->seedData();
                $salespersons = DB::table('salesperson_teams')
                    ->select(
                        'id',
                        's_person_code',
                        'salesperson_name',
                        'st_code',
                        'sales_team_name',
                        'sales_team_position'
                    )
                    ->orderBy('s_person_code')
                    ->get();
            }

            // Mengambil data sales team untuk dropdown dan convert ke collection
            $salesTeams = collect([
                (object)['id' => 1, 'code' => '01', 'name' => 'MBI'],
                (object)['id' => 2, 'code' => '02', 'name' => 'MANAGEMENT LOCAL'],
                (object)['id' => 3, 'code' => '03', 'name' => 'MANAGEMENT MNC']
            ]);

            // Log the query result
            Log::info('Salesperson teams count in index: ' . $salespersons->count());

            // Mengirim data ke view
            return view('sales-management.system-requirement.system-requirement.standard-requirement.salespersonteam', compact('salespersons', 'salesTeams'));
        } catch (\Exception $e) {
            Log::error('Error loading salesperson teams: ' . $e->getMessage());
            return view('sales-management.system-requirement.system-requirement.standard-requirement.salespersonteam', [
                'salespersons' => [],
                'salesTeams' => collect([]),
                'error' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    public function store(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'salesperson_code' => 'required|string|max:10|unique:salesperson_teams,s_person_code',
            'salesperson_name' => 'required|string|max:100',
            'sales_team_id' => 'required',
            'position' => 'required|string|max:50',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            // Get sales team info based on ID
            $salesTeam = collect([
                (object)['id' => 1, 'code' => '01', 'name' => 'MBI'],
                (object)['id' => 2, 'code' => '02', 'name' => 'MANAGEMENT LOCAL'],
                (object)['id' => 3, 'code' => '03', 'name' => 'MANAGEMENT MNC']
            ])->firstWhere('id', $request->sales_team_id);

            if (!$salesTeam) {
                return redirect()->back()->with('error', 'Sales team tidak ditemukan')->withInput();
            }

            // Menyimpan data salesperson baru
            DB::table('salesperson_teams')->insert([
                's_person_code' => strtoupper($request->salesperson_code),
                'salesperson_name' => strtoupper($request->salesperson_name),
                'st_code' => $salesTeam->code,
                'sales_team_name' => $salesTeam->name,
                'sales_team_position' => $request->position,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return redirect()->route('salesperson-team.index')->with('success', 'Salesperson berhasil ditambahkan ke tim');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        try {
            // Mengambil data salesperson berdasarkan ID
            $salesperson = DB::table('salesperson')
                ->join('sales_team', 'salesperson.sales_team_id', '=', 'sales_team.id')
                ->select(
                    'salesperson.*',
                    'sales_team.code as team_code',
                    'sales_team.name as team_name'
                )
                ->where('salesperson.id', $id)
                ->first();

            if (!$salesperson) {
                return redirect()->route('salesperson-team.index')->with('error', 'Salesperson tidak ditemukan');
            }

            // Mengambil data sales team untuk dropdown
            $salesTeams = DB::table('sales_team')->get();

            return view('sales-management.system-requirement.system-requirement.standard-requirement.salespersonteam-edit', compact('salesperson', 'salesTeams'));
        } catch (\Exception $e) {
            return redirect()->route('salesperson-team.index')->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            // Validate request
            $validator = Validator::make($request->all(), [
                's_person_code' => 'required|string|max:255',
                'salesperson_name' => 'required|string|max:255',
                'st_code' => 'required|string|max:255',
                'sales_team_name' => 'required|string|max:255',
                'sales_team_position' => 'required|string|max:255',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Update salesperson team
            $updated = DB::table('salesperson_teams')
                ->where('id', $id)
                ->update([
                    's_person_code' => $request->s_person_code,
                    'salesperson_name' => $request->salesperson_name,
                    'st_code' => $request->st_code,
                    'sales_team_name' => $request->sales_team_name,
                    'sales_team_position' => $request->sales_team_position,
                    'updated_at' => now()
                ]);

            if (!$updated) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update salesperson team'
                ], 500);
            }

            // Get updated data
            $updatedData = DB::table('salesperson_teams')
                ->where('id', $id)
                ->first();

            if (!$updatedData) {
                return response()->json([
                    'success' => false,
                    'message' => 'Updated data not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Salesperson team updated successfully',
                'data' => [
                    'id' => $updatedData->id,
                    's_person_code' => $updatedData->s_person_code,
                    'salesperson_name' => $updatedData->salesperson_name,
                    'st_code' => $updatedData->st_code,
                    'sales_team_name' => $updatedData->sales_team_name,
                    'sales_team_position' => $updatedData->sales_team_position
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error updating salesperson team: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating the salesperson team: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            // Delete salesperson team record from the correct table
            $deleted = DB::table('salesperson_teams')->where('id', $id)->delete();

            if (!$deleted) {
                return response()->json([
                    'success' => false,
                    'message' => 'Salesperson team not found or could not be deleted'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Salesperson team deleted successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting salesperson team: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error deleting salesperson team: ' . $e->getMessage()
            ], 500);
        }
    }

    public function search(Request $request)
    {
        $searchTerm = $request->q;

        try {
            $salespersons = DB::table('salesperson')
                ->join('sales_team', 'salesperson.sales_team_id', '=', 'sales_team.id')
                ->select(
                    'salesperson.id',
                    'salesperson.code as salesperson_code',
                    'salesperson.name as salesperson_name',
                    'sales_team.code as team_code',
                    'sales_team.name as team_name',
                    'salesperson.position'
                )
                ->where('salesperson.code', 'like', "%{$searchTerm}%")
                ->orWhere('salesperson.name', 'like', "%{$searchTerm}%")
                ->orWhere('sales_team.code', 'like', "%{$searchTerm}%")
                ->orWhere('sales_team.name', 'like', "%{$searchTerm}%")
                ->orderBy('salesperson.code')
                ->get();

            return response()->json($salespersons);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Render the Vue component for salesperson team management.
     *
     * @return \Inertia\Response
     */
    public function vueIndex()
    {
        try {
            return Inertia::render('sales-management/system-requirement/standard-requirement/salesperson-team', [
                'header' => 'Salesperson Team Management'
            ]);
        } catch (\Exception $e) {
            Log::error('Error in SalespersonTeamController@vueIndex: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to load salesperson team data'], 500);
        }
    }

    /**
     * API endpoint to get all salesperson teams for Vue component.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiIndex()
    {
        try {
            $salespersonTeams = DB::table('salesperson_teams')
                ->select(
                    'id',
                    's_person_code',
                    'salesperson_name',
                    'st_code',
                    'sales_team_name',
                    'sales_team_position'
                )
                ->orderBy('s_person_code')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $salespersonTeams
            ]);
        } catch (\Exception $e) {
            Log::error('Error in SalespersonTeamController@apiIndex: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to load salesperson team data'
            ], 500);
        }
    }

    /**
     * API endpoint to store a new salesperson team.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiStore(Request $request)
    {
        try {
            // Validate input
            $validator = Validator::make($request->all(), [
                's_person_code' => 'required|string|max:10|unique:salesperson_teams,s_person_code',
                'salesperson_name' => 'required|string|max:100',
                'st_code' => 'required|string|max:10',
                'sales_team_name' => 'required|string|max:100',
                'sales_team_position' => 'required|string|max:50',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Insert new record
            $id = DB::table('salesperson_teams')->insertGetId([
                's_person_code' => strtoupper($request->s_person_code),
                'salesperson_name' => strtoupper($request->salesperson_name),
                'st_code' => $request->st_code,
                'sales_team_name' => $request->sales_team_name,
                'sales_team_position' => $request->sales_team_position,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Get the newly created record
            $salespersonTeam = DB::table('salesperson_teams')->where('id', $id)->first();

            return response()->json([
                'success' => true,
                'message' => 'Salesperson team created successfully',
                'data' => $salespersonTeam
            ]);
        } catch (\Exception $e) {
            Log::error('Error in SalespersonTeamController@apiStore: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to create salesperson team: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * API endpoint to seed sample data for the Vue component.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    private function seedData()
    {
        try {
            $sampleData = [
                [
                    's_person_code' => 'SP001',
                    'salesperson_name' => 'JOHN DOE',
                    'st_code' => '01',
                    'sales_team_name' => 'MBI',
                    'sales_team_position' => 'E-Executive',
                ],
                [
                    's_person_code' => 'SP002',
                    'salesperson_name' => 'JANE SMITH',
                    'st_code' => '02',
                    'sales_team_name' => 'MANAGEMENT LOCAL',
                    'sales_team_position' => 'M-Manager',
                ],
                [
                    's_person_code' => 'SP003',
                    'salesperson_name' => 'ROBERT JOHNSON',
                    'st_code' => '03',
                    'sales_team_name' => 'MANAGEMENT MNC',
                    'sales_team_position' => 'S-Supervisor',
                ]
            ];

            // Insert sample data only if it doesn't exist
            foreach ($sampleData as $data) {
                $exists = DB::table('salesperson_teams')
                    ->where('s_person_code', $data['s_person_code'])
                    ->exists();

                if (!$exists) {
                    DB::table('salesperson_teams')->insert(array_merge($data, [
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]));
                }
            }
        } catch (\Exception $e) {
            Log::error('Error seeding salesperson team data: ' . $e->getMessage());
            throw $e;
        }
    }

    public function apiSeed()
    {
        try {
            $this->seedData();
            return response()->json([
                'success' => true,
                'message' => 'Sample data seeded successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error in SalespersonTeamController@apiSeed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to seed sample data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Blade version of the view and print functionality
     *
     * @return \Illuminate\View\View
     */
    public function viewAndPrint()
    {
        try {
            $salespersonTeams = DB::table('salesperson_teams')
                ->select(
                    's_person_code',
                    'salesperson_name',
                    'st_code',
                    'sales_team_name',
                    'sales_team_position'
                )
                ->orderBy('s_person_code')
                ->get();

            return view('sales-management.system-requirement.system-requirement.viewandprintsalespersonteam', compact('salespersonTeams'));
        } catch (\Exception $e) {
            Log::error('Error in viewAndPrint method: ' . $e->getMessage());
            return view('sales-management.system-requirement.system-requirement.viewandprintsalespersonteam', [
                'salespersonTeams' => collect([]),
                'error' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Vue version of the view and print functionality
     *
     * @return \Inertia\Response
     */
    public function vueViewAndPrint()
    {
        try {
            return Inertia::render('sales-management/system-requirement/standard-requirement/view-and-print-salesperson-team');
        } catch (\Exception $e) {
            Log::error('Error in SalespersonTeamController@vueViewAndPrint: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to load salesperson team data for printing'], 500);
        }
    }
}
