<?php

namespace App\Http\Controllers;

use App\Models\Geo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Database\Seeders\GeoSeeder;

class GeoController extends Controller
{
    public function index(Request $request)
    {
        try {
            if ($request->ajax()) {
                $geos = Geo::orderBy('COUNTRY')->orderBy('STATE')->get();

                if ($geos->isEmpty()) {
                    $this->seedData();
                    $geos = Geo::orderBy('COUNTRY')->orderBy('STATE')->get();
                }

                return response()->json($geos);
            }

            $geoData = Geo::all();

            if ($geoData->isEmpty()) {
                $this->seedData();
                $geoData = Geo::all();
            }

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
                'code' => 'required|unique:GEO,CODE',
                'country' => 'required',
                'state' => 'required',
                'town' => 'required',
                'town_section' => 'required',
                'area' => 'required',
            ]);

            $geo = new Geo();
            $geo->CODE = $request->code;
            $geo->COUNTRY = $request->country;
            $geo->STATE = $request->state;
            $geo->TOWN = $request->town;
            $geo->TOWN_SECTION = $request->town_section;
            $geo->AREA = $request->area;
            $geo->save();

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

            $geo = Geo::where('CODE', $code)->firstOrFail();
            $geo->COUNTRY = $request->country;
            $geo->STATE = $request->state;
            $geo->TOWN = $request->town;
            $geo->TOWN_SECTION = $request->town_section;
            $geo->AREA = $request->area;
            $geo->save();

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
            $geo = Geo::where('CODE', $code)->firstOrFail();
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
            $geo = Geo::where('CODE', $code)->firstOrFail();

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
        $geos = Geo::orderBy('COUNTRY')->orderBy('STATE')->get();
        return view('sales-management.system-requirement.system-requirement.standard-requirement.viewandprintgeo', compact('geos'));
    }

    /**
     * Display the Vue index page for geo management
     *
     * @return \Inertia\Response
     */
    public function vueIndex()
    {
        try {
            $geos = Geo::orderBy('country')->orderBy('state')->get();

            Log::info('GeoController@vueIndex: Passing ' . $geos->count() . ' geo records to view');

            return \Inertia\Inertia::render('sales-management/system-requirement/standard-requirement/geo', [
                'geos' => $geos,
                'header' => 'Geo Management'
            ]);
        } catch (\Exception $e) {
            Log::error('Error in GeoController@vueIndex: ' . $e->getMessage());

            return \Inertia\Inertia::render('sales-management/system-requirement/standard-requirement/geo', [
                'geos' => [],
                'header' => 'Geo Management',
                'error' => 'Error displaying geo data: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Display the Vue version of the view and print page
     *
     * @return \Inertia\Response
     */
    public function vueViewAndPrint()
    {
        try {
            return \Inertia\Inertia::render('sales-management/system-requirement/standard-requirement/view-and-print-geo');
        } catch (\Exception $e) {
            Log::error('Error in GeoController@vueViewAndPrint: ' . $e->getMessage());
            return \Inertia\Inertia::render('sales-management/system-requirement/standard-requirement/view-and-print-geo', [
                'error' => 'Failed to load geo data for printing: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * API endpoint to get geos in JSON format.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiIndex()
    {
        try {
            $geos = Geo::orderBy('country')->orderBy('state')->get();

            Log::info('GeoController@apiIndex: Returning ' . $geos->count() . ' geo records');

            return response()->json($geos);
        } catch (\Exception $e) {
            Log::error('Error in GeoController@apiIndex: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to load geo data'], 500);
        }
    }

    private function seedData()
    {
        try {
            $seeder = new GeoSeeder();
            $seeder->run();
        } catch (\Exception $e) {
            Log::error('Error seeding geo data: ' . $e->getMessage());
            throw $e;
        }
    }

    public function seed()
    {
        try {
            $this->seedData();
            return response()->json(['success' => true, 'message' => 'Geo seed data created successfully']);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating geo seed data: ' . $e->getMessage()
            ], 500);
        }
    }
}
