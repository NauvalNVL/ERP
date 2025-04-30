<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Schema;

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
            // Menghapus data salesperson
            DB::table('salesperson')->where('id', $id)->delete();
            
            return redirect()->route('salesperson-team.index')->with('success', 'Salesperson berhasil dihapus dari tim');
        } catch (\Exception $e) {
            return redirect()->route('salesperson-team.index')->with('error', 'Error: ' . $e->getMessage());
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
}
