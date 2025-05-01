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

class SalespersonController extends Controller
{
    public function index()
    {
        try {
            // Always load from database, ordered by code
            $salespersons = Salesperson::orderBy('code')->get();
            
            // If there are no salespersons in the database, seed them
            if ($salespersons->isEmpty()) {
                $seeder = new SalespersonSeeder();
                $seeder->run();
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
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:10|unique:salespersons',
            'name' => 'required|string|max:100',
            'status' => 'required|in:active,inactive',
            'email' => 'nullable|email|max:100',
            'phone' => 'nullable|string|max:20',
            'mobile' => 'nullable|string|max:20',
            'notes' => 'nullable|string|max:500',
            'sales_team_id' => 'nullable|exists:sales_teams,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            Salesperson::create([
                'code' => $request->code,
                'name' => $request->name,
                'status' => $request->status,
                'email' => $request->email,
                'phone' => $request->phone,
                'mobile' => $request->mobile,
                'notes' => $request->notes,
                'sales_team_id' => $request->sales_team_id,
            ]);

            DB::commit();

            return redirect()->route('salesperson.index')
                ->with('success', 'Salesperson created successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error creating salesperson: ' . $e->getMessage())
                ->withInput();
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

    public function destroy($id)
    {
        try {
            $salesperson = Salesperson::findOrFail($id);
            $salesperson->delete();

            return redirect()->route('salesperson.index')
                ->with('success', 'Salesperson deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error deleting salesperson: ' . $e->getMessage());
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
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function viewAndPrint()
    {
        try {
            $salespersons = Salesperson::with('salesTeam')
                ->orderBy('code')
                ->get();
                
            return view('sales-management.system-requirement.system-requirement.standard-requirement.viewandprintsalesperson', compact('salespersons'));
        } catch (\Exception $e) {
            Log::error('Error viewing salespersons: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error loading data: ' . $e->getMessage());
        }
    }

    public function seed()
    {
        try {
            DB::beginTransaction();
            
            // Run the seeder
            $seeder = new SalespersonSeeder();
            $seeder->run();
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Salesperson data seeded successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error seeding salesperson data: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error seeding salesperson data: ' . $e->getMessage()
            ], 500);
        }
    }
}
