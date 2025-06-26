<?php

namespace App\Http\Controllers\MaterialManagement\SystemRequirement;

use App\Http\Controllers\Controller;
use App\Models\MmTaxGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class MmTaxGroupController extends Controller
{
    /**
     * Display the tax group management interface.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        return Inertia::render('material-management/system-requirement/standard-setup/TaxGroup');
    }
    
    /**
     * Display a view for printing the tax groups.
     */
    public function viewPrint()
    {
        return Inertia::render('material-management/system-requirement/standard-setup/ViewPrintTaxGroup');
    }
    
    /**
     * Get all tax groups for API
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTaxGroups()
    {
        $taxGroups = MmTaxGroup::with('taxTypes')->orderBy('code')->get();
        return response()->json($taxGroups);
    }
    
    /**
     * Show a specific tax group for API
     *
     * @param string $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($code)
    {
        $taxGroup = MmTaxGroup::with('taxTypes')->findOrFail($code);
        return response()->json($taxGroup);
    }
    
    /**
     * Store a new tax group
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:10|unique:mm_tax_groups,code',
            'name' => 'required|string|max:100',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }
        
        $taxGroup = MmTaxGroup::create([
            'code' => strtoupper($request->code),
            'name' => $request->name,
        ]);
        
        return response()->json($taxGroup, 201);
    }
    
    /**
     * Update an existing tax group
     *
     * @param \Illuminate\Http\Request $request
     * @param string $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $code)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }
        
        $taxGroup = MmTaxGroup::findOrFail($code);
        $taxGroup->update([
            'name' => $request->name,
        ]);
        
        return response()->json($taxGroup, 200);
    }
    
    /**
     * Delete a tax group
     *
     * @param string $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($code)
    {
        $taxGroup = MmTaxGroup::findOrFail($code);
        
        // Check if tax group is being used by any tax types
        if ($taxGroup->taxTypes()->exists()) {
            return response()->json([
                'message' => 'Cannot delete tax group that is in use by tax types',
            ], 422);
        }
        
        $taxGroup->delete();
        
        return response()->json([
            'message' => 'Tax group deleted successfully',
        ], 200);
    }
    
    /**
     * Seed tax groups with sample data
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function seed()
    {
        $sampleTaxGroups = [
            ['code' => 'NON', 'name' => 'NON PPN'],
            ['code' => 'STD', 'name' => 'STANDARD TAX'],
            ['code' => 'VAT10', 'name' => 'VAT 10%'],
            ['code' => 'VAT11', 'name' => 'VAT 11%'],
        ];
        
        foreach ($sampleTaxGroups as $data) {
            MmTaxGroup::updateOrCreate(
                ['code' => $data['code']],
                ['name' => $data['name']]
            );
        }
        
        return response()->json([
            'message' => 'Sample tax groups seeded successfully',
        ], 200);
    }
} 