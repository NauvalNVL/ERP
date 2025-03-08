<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Salesperson;
use App\Models\SalesTeam;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SalespersonController extends Controller
{
    public function index()
    {
        return view('system-requirement.salesperson');
    }

    public function list()
    {
        $salespersons = Salesperson::orderBy('code')->paginate(15);
        return view('system-requirement.salesperson.list', compact('salespersons'));
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
        return view('system-requirement.salesperson', compact('salesperson'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
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

            $salesperson = Salesperson::findOrFail($id);
            $salesperson->update([
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
                ->with('success', 'Salesperson updated successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error updating salesperson: ' . $e->getMessage())
                ->withInput();
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
        $query = $request->get('query', '');
        $salespersons = Salesperson::where('code', 'like', "%{$query}%")
            ->orWhere('name', 'like', "%{$query}%")
            ->orderBy('code')
            ->get();

        return response()->json($salespersons);
    }

    public function getDetails($code)
    {
        $salesperson = Salesperson::with('salesTeam')
            ->where('code', $code)
            ->first();

        if (!$salesperson) {
            return response()->json(['error' => 'Salesperson not found'], 404);
        }

        return response()->json($salesperson);
    }
}
