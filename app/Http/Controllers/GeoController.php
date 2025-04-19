<?php

namespace App\Http\Controllers;

use App\Models\Geo;
use Illuminate\Http\Request;

class GeoController extends Controller
{
    public function index()
    {
        $geoData = Geo::all();
        return view('system-requirement.geo', compact('geoData'));
    }

    public function store(Request $request)
    {
        // Validate and store geo data
        $request->validate([
            'country' => 'required',
            'state' => 'required',
            'town' => 'required',
            'town_section' => 'required',
            'area' => 'required',
        ]);

        Geo::create($request->all());
        return redirect()->route('geo.index')->with('success', 'Geo data added successfully.');
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
        return view('system-requirement.viewandprintgeo', compact('geos')); 
    }
}
