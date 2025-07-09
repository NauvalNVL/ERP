<?php

namespace App\Http\Controllers\MaterialManagement\SystemRequirement;

use App\Http\Controllers\Controller;
use App\Models\MmLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class MmLocationController extends Controller
{
    /**
     * Display the location management interface.
     *
     * @return \Inertia\Response
     */
    public function indexView()
    {
        return Inertia::render('material-management/system-requirement/inventory-setup/Location');
    }
    
    /**
     * Display a view for printing the locations.
     */
    public function viewPrint()
    {
        return Inertia::render('material-management/system-requirement/inventory-setup/ViewPrintLocation');
    }
    
    /**
     * Get all locations for API
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $locations = MmLocation::orderBy('code')->get();
        return response()->json($locations);
    }
    
    /**
     * Show a specific location for API
     *
     * @param string $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($code)
    {
        $location = MmLocation::findOrFail($code);
        return response()->json($location);
    }
    
    /**
     * Store a new location
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:20|unique:mm_locations,code',
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);
        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        
        $location = MmLocation::create($request->all());
        
        return response()->json($location, 201);
    }
    
    /**
     * Update an existing location
     *
     * @param \Illuminate\Http\Request $request
     * @param string $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $code)
    {
        $location = MmLocation::findOrFail($code);
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);
        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        
        $location->update($request->all());
        
        return response()->json($location);
    }
    
    /**
     * Delete a location
     *
     * @param string $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($code)
    {
        $location = MmLocation::findOrFail($code);
        $location->delete();
        
        return response()->json(null, 204);
    }
    
    /**
     * Toggle location active status
     *
     * @param string $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function toggleActive($code)
    {
        $location = MmLocation::findOrFail($code);
        $location->is_active = !$location->is_active;
        $location->save();
        
        return response()->json($location);
    }
    
    /**
     * Get locations for the ViewPrint page
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLocationsForPrint()
    {
        $locations = MmLocation::orderBy('code')->get();
        return response()->json($locations);
    }
    
    /**
     * Seed sample location data for testing purposes
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function seed()
    {
        $sampleLocations = [
            [
                'code' => 'WH01', 
                'name' => 'MAIN WAREHOUSE',
                'description' => 'Main storage warehouse for finished goods',
                'is_active' => true
            ],
            [
                'code' => 'OFFICE', 
                'name' => 'OFFICE',
                'description' => 'Office area',
                'is_active' => true
            ],
            [
                'code' => 'FACTORY', 
                'name' => 'FACTORY',
                'description' => 'Production facility',
                'is_active' => true
            ],
            [
                'code' => 'MESIN', 
                'name' => 'MESIN',
                'description' => 'Machine area',
                'is_active' => true
            ],
            [
                'code' => 'PRODUKSI', 
                'name' => 'PP. MESIN PRODUKSI',
                'description' => 'Production machine area',
                'is_active' => true
            ],
            [
                'code' => 'FORKLIFT', 
                'name' => 'FORKLIFT',
                'description' => 'Forklift storage area',
                'is_active' => true
            ],
            [
                'code' => 'GBAHAN', 
                'name' => 'GUDANG BAHAN PEMBANTU',
                'description' => 'Auxiliary materials warehouse',
                'is_active' => true
            ],
            [
                'code' => 'GSPARE', 
                'name' => 'GUDANG SPAREPART',
                'description' => 'Spare parts storage',
                'is_active' => true
            ],
            [
                'code' => 'GKEND', 
                'name' => 'GUDANG KENDARAAN',
                'description' => 'Vehicle storage',
                'is_active' => true
            ],
            [
                'code' => 'OFFSET', 
                'name' => 'OFFSET',
                'description' => 'Offset printing area',
                'is_active' => true
            ],
            [
                'code' => 'IMPORT', 
                'name' => 'SPAREPART IMPORT',
                'description' => 'Imported spare parts storage',
                'is_active' => true
            ],
        ];
        
        foreach ($sampleLocations as $locationData) {
            MmLocation::updateOrCreate(
                ['code' => $locationData['code']],
                $locationData
            );
        }
        
        return response()->json(['message' => 'Sample locations seeded successfully']);
    }

    public function getAll()
    {
        return response()->json(MmLocation::all());
    }
} 