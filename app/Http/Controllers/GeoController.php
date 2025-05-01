<?php

namespace App\Http\Controllers;

use App\Models\Geo;
use Illuminate\Http\Request;

class GeoController extends Controller
{
    public function index(Request $request)
    {
        try {
            if ($request->ajax()) {
                $geos = Geo::orderBy('country')->orderBy('state')->get();
                return response()->json($geos);
            }
            
        $geoData = Geo::all();
            return view('sales-management.system-requirement.system-requirement.standard-requirement.geo', compact('geoData'));
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json(['error' => $e->getMessage()], 500);
            }
            return back()->with('error', 'Error loading geo data: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'code' => 'required|unique:geo,code',
                'country' => 'required',
                'state' => 'required',
                'town' => 'required',
                'town_section' => 'required',
                'area' => 'required',
            ]);

            $geo = Geo::create($request->all());
            
            return response()->json([
                'success' => true,
                'message' => 'Geo data added successfully',
                'data' => $geo
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $code)
    {
        try {
        $request->validate([
            'country' => 'required',
            'state' => 'required',
            'town' => 'required',
            'town_section' => 'required',
            'area' => 'required',
        ]);

            $geo = Geo::where('code', $code)->firstOrFail();
            $geo->update($request->all());
            
            return response()->json([
                'success' => true,
                'message' => 'Geo data updated successfully',
                'data' => $geo
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($code)
    {
        try {
            $geo = Geo::where('code', $code)->firstOrFail();
            $geo->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Geo data deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function show($code)
    {
        try {
            $geo = Geo::where('code', $code)->firstOrFail();
            
            return response()->json([
                'success' => true,
                'data' => $geo
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
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
        // Urutkan berdasarkan country, lalu state
        $geos = Geo::orderBy('country')->orderBy('state')->get(); 
        return view('sales-management.system-requirement.system-requirement.standard-requirement.viewandprintgeo', compact('geos')); 
    }
}
