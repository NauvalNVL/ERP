<?php

namespace App\Http\Controllers\MaterialManagement\SystemRequirement;

use App\Http\Controllers\Controller;
use App\Models\MmSku;
use App\Models\MmCategory;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MmSkuPriceController extends Controller
{
    public function index(Request $request)
    {
        $query = MmSku::query()
            ->when($request->search, function ($query, $search) {
                $query->where('sku', 'like', "%{$search}%")
                    ->orWhere('sku_name', 'like', "%{$search}%");
            })
            ->when($request->category, function ($query, $category) {
                $query->whereHas('category', function ($q) use ($category) {
                    $q->where('name', 'like', "%{$category}%");
                });
            })
            ->with('category');

        $skus = $query->paginate(10)
            ->through(function ($sku) {
                return [
                    'sku' => $sku->sku,
                    'sts' => $sku->sts ?? '',
                    'sku_name' => $sku->sku_name,
                    'category_code' => $sku->category_code,
                    'type' => $sku->type,
                    'uom' => $sku->uom,
                    'boh' => $sku->boh,
                    'fpo' => $sku->fpo,
                    'rol' => $sku->rol,
                    'price' => $sku->price,
                    'total_part' => $sku->total_part,
                    'min_qty' => $sku->min_qty,
                    'max_qty' => $sku->max_qty,
                    'additional_name' => $sku->additional_name,
                    'part_number1' => $sku->part_number1,
                    'part_number2' => $sku->part_number2,
                    'part_number3' => $sku->part_number3,
                    'is_active' => $sku->is_active,
                ];
            });

        return Inertia::render('material-management/system-requirement/inventory-setup/SkuPrice', [
            'skus' => $skus,
        ]);
    }

    public function update(Request $request, $sku)
    {
        $request->validate([
            'price' => 'required|numeric|min:0',
            'min_qty' => 'required|numeric|min:0',
            'max_qty' => 'required|numeric|min:0',
            'additional_name' => 'nullable|string|max:200',
            'part_number1' => 'nullable|string|max:100',
            'part_number2' => 'nullable|string|max:100',
            'part_number3' => 'nullable|string|max:100',
        ]);

        $skuModel = MmSku::findOrFail($sku);
        $skuModel->update([
            'price' => $request->price,
            'min_qty' => $request->min_qty,
            'max_qty' => $request->max_qty,
            'additional_name' => $request->additional_name,
            'part_number1' => $request->part_number1,
            'part_number2' => $request->part_number2,
            'part_number3' => $request->part_number3,
        ]);

        return redirect()->back()->with('success', 'SKU price and details updated successfully.');
    }

    public function viewPrint()
    {
        $skus = MmSku::with('category')
            ->get()
            ->map(function ($sku) {
                return [
                    'sku' => $sku->sku,
                    'sts' => $sku->sts ?? '',
                    'sku_name' => $sku->sku_name,
                    'category_code' => $sku->category_code,
                    'category_name' => $sku->category->name ?? '',
                    'type' => $sku->type,
                    'uom' => $sku->uom,
                    'price' => $sku->price,
                    'boh' => $sku->boh,
                    'fpo' => $sku->fpo,
                    'rol' => $sku->rol,
                ];
            });

        return Inertia::render('material-management/system-requirement/inventory-setup/ViewPrintSkuPrice', [
            'skus' => $skus,
            'title' => 'SKU Price List',
            'date' => now()->format('Y-m-d'),
        ]);
    }

    public function search(Request $request)
    {
        $term = $request->input('term');
        $results = MmSku::where('sku', 'LIKE', "%$term%")
            ->orWhere('sku_name', 'LIKE', "%$term%")
            ->with('category')
            ->get(['sku', 'sku_name', 'category_code', 'price']);
        
        return response()->json($results);
    }
}
