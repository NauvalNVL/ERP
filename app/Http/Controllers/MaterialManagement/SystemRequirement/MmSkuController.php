<?php

namespace App\Http\Controllers\MaterialManagement\SystemRequirement;

use App\Http\Controllers\Controller;
use App\Models\MmSku;
use App\Models\MmCategory;
use App\Models\MmUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class MmSkuController extends Controller
{
    public function index()
    {
        $skus = MmSku::with('category')->get();
        return response()->json($skus);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'sku' => 'required|string|max:20|unique:mm_skus,sku',
            'sts' => 'nullable|string|max:10',
            'sku_name' => 'required|string|max:150',
            'category_code' => 'required|string|max:20|exists:mm_categories,code',
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

        $sku = MmSku::create($validated);
        return response()->json($sku);
    }

    public function update(Request $request, $sku)
    {
        $validated = $request->validate([
            'sts' => 'nullable|string|max:10',
            'sku_name' => 'required|string|max:150',
            'category_code' => 'required|string|max:20|exists:mm_categories,code',
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

        $skuModel = MmSku::findOrFail($sku);
        $skuModel->update($validated);
        return response()->json($skuModel);
    }

    public function destroy($sku)
    {
        $skuModel = MmSku::findOrFail($sku);
        $skuModel->delete();
        return response()->json(['message' => 'SKU deleted successfully']);
    }

    public function show($sku)
    {
        $skuModel = MmSku::with('category')->findOrFail($sku);
        return response()->json($skuModel);
    }

    public function getCategories()
    {
        $categories = MmCategory::all();
        return response()->json($categories);
    }

    public function getUnits()
    {
        $units = MmUnit::all();
        return response()->json($units);
    }

    public function getTypes()
    {
        $types = MmSku::distinct()->pluck('type')->filter();
        return response()->json($types);
    }

    public function print()
    {
        $skus = MmSku::with('category')->get();
        return view('material-management.system-requirement.inventory-setup.sku.print', compact('skus'));
    }
} 