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
        if (!Schema::hasTable('salesperson')) {
            return view('system-requirement.salespersonteam', [
                'salespersons' => [],
                'salesTeams' => [],
                'error' => 'Tabel salesperson tidak ditemukan. Silakan jalankan migrasi terlebih dahulu.'
            ]);
        }

        if (!Schema::hasTable('sales_team')) {
            return view('system-requirement.salespersonteam', [
                'salespersons' => [],
                'salesTeams' => [],
                'error' => 'Tabel sales_team tidak ditemukan. Silakan jalankan migrasi terlebih dahulu.'
            ]);
        }

        try {
            // Mengambil data salesperson team beserta informasi terkait
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
                ->orderBy('salesperson.code')
                ->get();
    
            // Mengambil data sales team untuk dropdown
            $salesTeams = DB::table('sales_team')->get();
            
            // Log the query result
            Log::info('Salesperson teams count in index: ' . $salespersons->count());
            
            // Mengirim data ke view
            return view('system-requirement.salespersonteam', compact('salespersons', 'salesTeams'));
        } catch (\Exception $e) {
            Log::error('Error loading salesperson teams: ' . $e->getMessage());
            return view('system-requirement.salespersonteam', [
                'salespersons' => [],
                'salesTeams' => [],
                'error' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    public function store(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'salesperson_code' => 'required|string|max:10|unique:salesperson,code',
            'salesperson_name' => 'required|string|max:100',
            'sales_team_id' => 'required|exists:sales_team,id',
            'position' => 'required|string|max:50',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            // Menyimpan data salesperson baru
            DB::table('salesperson')->insert([
                'code' => strtoupper($request->salesperson_code),
                'name' => strtoupper($request->salesperson_name),
                'sales_team_id' => $request->sales_team_id,
                'position' => $request->position,
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
            
            return view('system-requirement.salespersonteam-edit', compact('salesperson', 'salesTeams'));
        } catch (\Exception $e) {
            return redirect()->route('salesperson-team.index')->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'salesperson_name' => 'required|string|max:100',
            'sales_team_id' => 'required|exists:sales_team,id',
            'position' => 'required|string|max:50',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            // Memperbarui data salesperson
            DB::table('salesperson')
                ->where('id', $id)
                ->update([
                    'name' => strtoupper($request->salesperson_name),
                    'sales_team_id' => $request->sales_team_id,
                    'position' => $request->position,
                    'updated_at' => now(),
                ]);
    
            return redirect()->route('salesperson-team.index')->with('success', 'Salesperson team berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage())->withInput();
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
