<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SalesTeam;
use Illuminate\Support\Facades\Validator;

class SalesTeamController extends Controller
{
    public function index()
    {
        $salesTeams = SalesTeam::all();
        return view('system-requirement.salesteam', compact('salesTeams'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|unique:sales_teams,code',
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        SalesTeam::create([
            'code' => $request->code,
            'name' => $request->name,
        ]);

        return redirect()->route('sales-team.index')
            ->with('success', 'Sales team created successfully');
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
        return view('system-requirement.salesteam-edit', compact('salesTeam', 'salesTeams'));
    }
    
    public function update(Request $request, $id)
    {
        $salesTeam = SalesTeam::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'code' => 'required|unique:sales_teams,code,' . $id,
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        $salesTeam->update([
            'code' => $request->code,
            'name' => $request->name,
        ]);
        
        return redirect()->route('sales-team.index')
            ->with('success', 'Sales team updated successfully');
    }
    
    public function destroy($id)
    {
        $salesTeam = SalesTeam::findOrFail($id);
        $salesTeam->delete();
        
        return redirect()->route('sales-team.index')
            ->with('success', 'Sales team deleted successfully');
    }

    /**
     * Display a listing of the resource for printing.
     *
     * @return \\Illuminate\\View\\View
     */
    public function viewAndPrint()
    {
        // Ambil semua data sales team, urutkan berdasarkan code
        $salesTeams = SalesTeam::orderBy('code')->get(); 
        return view('system-requirement.viewandprintsalesteam', compact('salesTeams')); 
    }
}