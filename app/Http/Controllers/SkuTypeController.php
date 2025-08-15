<?php

namespace App\Http\Controllers;

use App\Models\SkuType;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class SkuTypeController extends Controller
{
    public function index()
    {
        $skuTypes = SkuType::all();
        return response()->json($skuTypes);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:20', 'unique:sku_types,code'],
            'name' => ['required', 'string', 'max:100'],
            'is_active' => ['boolean'],
        ]);

        $skuType = SkuType::create($validated);
        return response()->json($skuType, 201);
    }

    public function show($code)
    {
        $skuType = SkuType::findOrFail($code);
        return response()->json($skuType);
    }

    public function update(Request $request, $code)
    {
        $skuType = SkuType::findOrFail($code);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'is_active' => ['boolean'],
        ]);

        $skuType->update($validated);
        return response()->json($skuType);
    }

    public function destroy($code)
    {
        $skuType = SkuType::findOrFail($code);
        $skuType->delete();
        return response()->json(null, 204);
    }

    public function toggleActive($code)
    {
        $skuType = SkuType::findOrFail($code);
        $skuType->is_active = !$skuType->is_active;
        $skuType->save();
        return response()->json($skuType);
    }

    public function seedSampleData()
    {
        $types = [
            ['code' => 'FG', 'name' => 'Finished Goods', 'is_active' => true],
            ['code' => 'RM', 'name' => 'Raw Material', 'is_active' => true],
            ['code' => 'WIP', 'name' => 'Work In Progress', 'is_active' => true],
            ['code' => 'SERV', 'name' => 'Service', 'is_active' => true],
            ['code' => 'OTH', 'name' => 'Others', 'is_active' => true],
        ];

        DB::beginTransaction();
        try {
            foreach ($types as $typeData) {
                SkuType::updateOrCreate(
                    ['code' => $typeData['code']],
                    $typeData
                );
            }
            DB::commit();
            return response()->json(['message' => 'SKU Type sample data seeded successfully.'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to seed SKU Type sample data.', 'error' => $e->getMessage()], 500);
        }
    }
}
