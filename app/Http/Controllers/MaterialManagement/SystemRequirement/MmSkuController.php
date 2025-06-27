<?php

namespace App\Http\Controllers\MaterialManagement\SystemRequirement;

use App\Http\Controllers\Controller;
use App\Models\MmSku;
use App\Models\MmCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class MmSkuController extends Controller
{
    /**
     * Display the SKU management interface.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        return Inertia::render('material-management/system-requirement/inventory-setup/Sku');
    }
    
    /**
     * Display a view for printing the SKUs.
     */
    public function viewPrint()
    {
        return Inertia::render('material-management/system-requirement/inventory-setup/ViewPrintSku');
    }
    
    /**
     * Get all SKUs for API
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSkus(Request $request)
    {
        $query = MmSku::with('category')->orderBy('sku');
        
        // Apply search filter if provided
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('sku', 'like', "%{$search}%")
                  ->orWhere('sku_name', 'like', "%{$search}%");
            });
        }
        
        // Apply category filter if provided
        if ($request->has('category_code') && !empty($request->category_code)) {
            $query->where('category_code', $request->category_code);
        }
        
        $skus = $query->paginate(10);
        return response()->json($skus);
    }
    
    /**
     * Show a specific SKU for API
     *
     * @param string $sku
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($sku)
    {
        $sku = MmSku::with('category')->findOrFail($sku);
        return response()->json($sku);
    }
    
    /**
     * Store a new SKU
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'sku' => 'required|string|max:20|unique:mm_skus,sku',
            'sts' => 'nullable|string|max:10',
            'sku_name' => 'required|string|max:150',
            'category_code' => 'required|exists:mm_categories,code',
            'type' => 'nullable|string|max:10',
            'uom' => 'nullable|string|max:10',
            'boh' => 'nullable|numeric',
            'fpo' => 'nullable|numeric',
            'rol' => 'nullable|numeric',
            'total_part' => 'nullable|integer',
            'min_qty' => 'nullable|numeric',
            'max_qty' => 'nullable|numeric',
            'additional_name' => 'nullable|string|max:200',
            'part_number1' => 'nullable|string|max:100',
            'part_number2' => 'nullable|string|max:100',
            'part_number3' => 'nullable|string|max:100',
            'is_active' => 'boolean',
        ]);
        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        
        $sku = MmSku::create($request->all());
        
        return response()->json($sku, 201);
    }
    
    /**
     * Update an existing SKU
     *
     * @param \Illuminate\Http\Request $request
     * @param string $sku
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $sku)
    {
        $skuItem = MmSku::findOrFail($sku);
        
        $validator = Validator::make($request->all(), [
            'sts' => 'nullable|string|max:10',
            'sku_name' => 'required|string|max:150',
            'category_code' => 'required|exists:mm_categories,code',
            'type' => 'nullable|string|max:10',
            'uom' => 'nullable|string|max:10',
            'boh' => 'nullable|numeric',
            'fpo' => 'nullable|numeric',
            'rol' => 'nullable|numeric',
            'total_part' => 'nullable|integer',
            'min_qty' => 'nullable|numeric',
            'max_qty' => 'nullable|numeric',
            'additional_name' => 'nullable|string|max:200',
            'part_number1' => 'nullable|string|max:100',
            'part_number2' => 'nullable|string|max:100',
            'part_number3' => 'nullable|string|max:100',
            'is_active' => 'boolean',
        ]);
        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        
        $skuItem->update($request->all());
        
        return response()->json($skuItem);
    }
    
    /**
     * Delete an SKU
     *
     * @param string $sku
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($sku)
    {
        $skuItem = MmSku::findOrFail($sku);
        $skuItem->delete();
        
        return response()->json(null, 204);
    }
    
    /**
     * Toggle SKU active status
     *
     * @param string $sku
     * @return \Illuminate\Http\JsonResponse
     */
    public function toggleActive($sku)
    {
        $skuItem = MmSku::findOrFail($sku);
        $skuItem->is_active = !$skuItem->is_active;
        $skuItem->save();
        
        return response()->json($skuItem);
    }
    
    /**
     * Get SKUs for the ViewPrint page
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSkusForPrint(Request $request)
    {
        $query = MmSku::with('category');
        
        if ($request->has('category_code') && !empty($request->category_code)) {
            $query->where('category_code', $request->category_code);
        }
        
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('sku', 'like', "%{$search}%")
                  ->orWhere('sku_name', 'like', "%{$search}%");
            });
        }
        
        if ($request->has('sort_by') && !empty($request->sort_by)) {
            $direction = $request->has('sort_direction') && $request->sort_direction === 'desc' ? 'desc' : 'asc';
            $query->orderBy($request->sort_by, $direction);
        } else {
            $query->orderBy('sku');
        }
        
        $skus = $query->get();
        return response()->json($skus);
    }
    
    /**
     * Seed sample SKU data for testing purposes
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function seedSampleData()
    {
        // Get existing categories
        $categories = MmCategory::pluck('code')->toArray();
        
        if (empty($categories)) {
            return response()->json(['message' => 'No categories found. Please add categories first.'], 400);
        }
        
        $sampleSkus = [
            [
                'sku' => '007-SRV-C27',
                'sts' => 'ACT',
                'sku_name' => 'SERVICE CAL 347NO DRIVER TIPE DASA-560 SPEA',
                'category_code' => $categories[0],
                'type' => 'NS',
                'uom' => 'UNIT',
                'boh' => 0.000,
                'fpo' => 0.000,
                'rol' => 0.000,
                'total_part' => 0,
                'is_active' => true,
                'min_qty' => 0.00,
                'max_qty' => 0.00
            ],
            [
                'sku' => '000055',
                'sts' => 'ACT',
                'sku_name' => 'BOX SLEEVE DAJAN PT.DELTAPACK',
                'category_code' => $categories[0],
                'type' => 'NS',
                'uom' => 'PCS',
                'boh' => 0.000,
                'fpo' => 0.000,
                'rol' => 0.000,
                'total_part' => 0,
                'is_active' => true,
                'min_qty' => 0.00,
                'max_qty' => 0.00
            ],
            [
                'sku' => '000055A',
                'sts' => 'ACT',
                'sku_name' => 'BOX SLEEVE LUAR PT.DELTAPACK',
                'category_code' => $categories[0],
                'type' => 'NS',
                'uom' => 'PCS',
                'boh' => 0.000,
                'fpo' => 0.000,
                'rol' => 0.000,
                'total_part' => 0,
                'is_active' => true,
                'min_qty' => 0.00,
                'max_qty' => 0.00
            ],
            [
                'sku' => '000055B',
                'sts' => 'ACT',
                'sku_name' => 'BOX SLEEVE DALAM PT.DELTAPACK',
                'category_code' => $categories[0],
                'type' => 'NS',
                'uom' => 'PCS',
                'boh' => 0.000,
                'fpo' => 0.000,
                'rol' => 0.000,
                'total_part' => 0,
                'is_active' => true,
                'min_qty' => 0.00,
                'max_qty' => 0.00
            ],
            [
                'sku' => '000202',
                'sts' => 'ACT',
                'sku_name' => 'BOX OUTER 2X30CM NEW',
                'category_code' => $categories[0],
                'type' => 'NS',
                'uom' => 'PCS',
                'boh' => 0.000,
                'fpo' => 0.000,
                'rol' => 0.000,
                'total_part' => 0,
                'is_active' => true,
                'min_qty' => 0.00,
                'max_qty' => 0.00
            ],
            [
                'sku' => '001-A01001',
                'sts' => 'ACT',
                'sku_name' => 'ANNELING WIRE 2 MM (KAWAT PRESS BALLER)',
                'category_code' => $categories[0],
                'type' => 'SI',
                'uom' => 'KG',
                'boh' => 2675.000,
                'fpo' => 0.000,
                'rol' => 0.000,
                'total_part' => 0,
                'is_active' => true,
                'min_qty' => 0.00,
                'max_qty' => 0.00
            ],
            [
                'sku' => '001-A02001',
                'sts' => 'OBS',
                'sku_name' => 'ARMEX BAKING SODA POWDER (25KG/BAG)',
                'category_code' => $categories[0],
                'type' => 'SI',
                'uom' => 'BAG',
                'boh' => 0.000,
                'fpo' => 0.000,
                'rol' => 0.000,
                'total_part' => 0,
                'is_active' => false,
                'min_qty' => 0.00,
                'max_qty' => 0.00
            ],
            [
                'sku' => '001-A02002',
                'sts' => 'ACT',
                'sku_name' => 'ARMEX BAKING SODA POWDER (25KG/BAG)',
                'category_code' => $categories[0],
                'type' => 'SI',
                'uom' => 'KG',
                'boh' => 0.000,
                'fpo' => 0.000,
                'rol' => 0.000,
                'total_part' => 0,
                'is_active' => true,
                'min_qty' => 0.00,
                'max_qty' => 0.00
            ],
            [
                'sku' => '001-A02003',
                'sts' => 'ACT',
                'sku_name' => 'ARMEX BAKING SODA POWDER (25KG/BAG)',
                'category_code' => $categories[0],
                'type' => 'SI',
                'uom' => 'KG',
                'boh' => 100.000,
                'fpo' => 0.000,
                'rol' => 0.000,
                'total_part' => 0,
                'is_active' => true,
                'min_qty' => 0.00,
                'max_qty' => 0.00
            ],
            [
                'sku' => '001-A03001',
                'sts' => 'ACT',
                'sku_name' => 'ALUMINIUM CHLOROHYDRANT/BETAGARD 4040 (ACH)',
                'category_code' => $categories[0],
                'type' => 'SI',
                'uom' => 'KG',
                'boh' => 3514.000,
                'fpo' => 0.000,
                'rol' => 0.000,
                'total_part' => 0,
                'is_active' => true,
                'min_qty' => 0.00,
                'max_qty' => 0.00
            ]
        ];
        
        foreach ($sampleSkus as $skuData) {
            MmSku::updateOrCreate(
                ['sku' => $skuData['sku']],
                $skuData
            );
        }
        
        return response()->json(['message' => 'Sample SKUs seeded successfully']);
    }

    /**
     * Get distinct SKU types
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTypes()
    {
        $types = MmSku::select('type')->distinct()->whereNotNull('type')->get()->pluck('type');
        return response()->json($types);
    }

    /**
     * Get distinct UOMs (Units of Measure)
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUoms()
    {
        $uoms = MmSku::select('uom')->distinct()->whereNotNull('uom')->get()->pluck('uom');
        return response()->json($uoms);
    }
} 