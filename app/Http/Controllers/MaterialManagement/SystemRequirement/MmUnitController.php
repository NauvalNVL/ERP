<?php

namespace App\Http\Controllers\MaterialManagement\SystemRequirement;

use App\Http\Controllers\Controller;
use App\Models\MmUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use Database\Seeders\MmUnitSeeder;

class MmUnitController extends Controller
{
    /**
     * Display the unit management interface.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        return Inertia::render('material-management/system-requirement/inventory-setup/Unit');
    }
    
    /**
     * Display a view for printing the units.
     */
    public function viewPrint()
    {
        return Inertia::render('material-management/system-requirement/inventory-setup/ViewPrintUnit');
    }
    
    /**
     * Get all units for API
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUnits()
    {
        $units = MmUnit::orderBy('code')->get();
        return response()->json($units);
    }
    
    /**
     * Show a specific unit for API
     *
     * @param string $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($code)
    {
        $unit = MmUnit::findOrFail($code);
        return response()->json($unit);
    }
    
    /**
     * Store a new unit
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:20|unique:mm_units,code',
            'short_name' => 'required|string|max:50',
            'full_name' => 'required|string|max:150',
            'is_active' => 'boolean',
        ]);
        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        
        $unit = MmUnit::create($request->all());
        
        return response()->json($unit, 201);
    }
    
    /**
     * Update an existing unit
     *
     * @param \Illuminate\Http\Request $request
     * @param string $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $code)
    {
        $unit = MmUnit::findOrFail($code);
        
        $validator = Validator::make($request->all(), [
            'short_name' => 'required|string|max:50',
            'full_name' => 'required|string|max:150',
            'is_active' => 'boolean',
        ]);
        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        
        $unit->update($request->all());
        
        return response()->json($unit);
    }
    
    /**
     * Delete a unit
     *
     * @param string $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($code)
    {
        $unit = MmUnit::findOrFail($code);
        $unit->delete();
        
        return response()->json(null, 204);
    }
    
    /**
     * Toggle unit active status
     *
     * @param string $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function toggleActive($code)
    {
        $unit = MmUnit::findOrFail($code);
        $unit->is_active = !$unit->is_active;
        $unit->save();
        
        return response()->json($unit);
    }
    
    /**
     * Get units for the ViewPrint page
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUnitsForPrint()
    {
        $units = MmUnit::orderBy('code')->get();
        return response()->json($units);
    }
    
    /**
     * Seed sample unit data for testing purposes
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function seedSampleData()
    {
        $seeder = new MmUnitSeeder();
        $seeder->run();
        
        return response()->json(['message' => 'Sample units seeded successfully']);
    }
} 