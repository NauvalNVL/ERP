<?php
namespace App\Http\Controllers\MaterialManagement\SystemRequirement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SkuReorderLevel;
use App\Models\MmSku;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SkuReorderLevelController extends Controller
{
    public function index(Request $request)
    {
        $skuId = $request->query('sku_id');
        $query = SkuReorderLevel::with('sku');
        if ($skuId) {
            $query->where('sku_id', $skuId);
        }
        return response()->json($query->orderBy('period')->get());
    }

    public function show($id)
    {
        return response()->json(SkuReorderLevel::with('sku')->findOrFail($id));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'sku_id' => 'required|exists:mm_skus,sku', // Fixed to reference 'sku' instead of 'id'
            'period' => 'required|string|max:7',
            'min_level' => 'required|numeric',
            'max_level' => 'required|numeric',
            'reorder_level' => 'required|numeric',
        ]);
        $item = SkuReorderLevel::updateOrCreate([
            'sku_id' => $data['sku_id'],
            'period' => $data['period'],
        ], $data);
        return response()->json($item);
    }

    public function update(Request $request, $id)
    {
        $item = SkuReorderLevel::findOrFail($id);
        $data = $request->validate([
            'min_level' => 'required|numeric',
            'max_level' => 'required|numeric',
            'reorder_level' => 'required|numeric',
        ]);
        $item->update($data);
        return response()->json($item);
    }

    public function destroy($id)
    {
        $item = SkuReorderLevel::findOrFail($id);
        $item->delete();
        return response()->json(['success' => true]);
    }

    public function getBySku($skuId)
    {
        $levels = SkuReorderLevel::where('sku_id', $skuId)->orderBy('period')->get();
        return response()->json($levels);
    }

    // Copy & Paste functionality
    public function copyReorderLevels(Request $request)
    {
        $request->validate([
            'source_sku_id' => 'required|exists:mm_skus,sku',
            'target_sku_id' => 'required|exists:mm_skus,sku',
            'source_period' => 'required|string|max:7',
            'target_period' => 'required|string|max:7',
        ]);

        try {
            DB::beginTransaction();

            // Get source reorder level
            $sourceLevel = SkuReorderLevel::where('sku_id', $request->source_sku_id)
                ->where('period', $request->source_period)
                ->first();

            if (!$sourceLevel) {
                return response()->json(['error' => 'Source reorder level not found'], 404);
            }

            // Create or update target reorder level
            $targetLevel = SkuReorderLevel::updateOrCreate([
                'sku_id' => $request->target_sku_id,
                'period' => $request->target_period,
            ], [
                'min_level' => $sourceLevel->min_level,
                'max_level' => $sourceLevel->max_level,
                'reorder_level' => $sourceLevel->reorder_level,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Reorder levels copied successfully',
                'data' => $targetLevel
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to copy reorder levels: ' . $e->getMessage()], 500);
        }
    }

    // Copy reorder levels to multiple periods
    public function copyToMultiplePeriods(Request $request)
    {
        $request->validate([
            'source_sku_id' => 'required|exists:mm_skus,sku',
            'source_period' => 'required|string|max:7',
            'target_periods' => 'required|array',
            'target_periods.*' => 'string|max:7',
        ]);

        try {
            DB::beginTransaction();

            // Get source reorder level
            $sourceLevel = SkuReorderLevel::where('sku_id', $request->source_sku_id)
                ->where('period', $request->source_period)
                ->first();

            if (!$sourceLevel) {
                return response()->json(['error' => 'Source reorder level not found'], 404);
            }

            $copiedCount = 0;
            foreach ($request->target_periods as $targetPeriod) {
                SkuReorderLevel::updateOrCreate([
                    'sku_id' => $request->source_sku_id,
                    'period' => $targetPeriod,
                ], [
                    'min_level' => $sourceLevel->min_level,
                    'max_level' => $sourceLevel->max_level,
                    'reorder_level' => $sourceLevel->reorder_level,
                ]);
                $copiedCount++;
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Reorder levels copied to {$copiedCount} periods successfully",
                'copied_count' => $copiedCount
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to copy reorder levels: ' . $e->getMessage()], 500);
        }
    }

    // Copy reorder levels to multiple SKUs
    public function copyToMultipleSkus(Request $request)
    {
        $request->validate([
            'source_sku_id' => 'required|exists:mm_skus,sku',
            'target_sku_ids' => 'required|array',
            'target_sku_ids.*' => 'exists:mm_skus,sku',
            'period' => 'required|string|max:7',
        ]);

        try {
            DB::beginTransaction();

            // Get source reorder level
            $sourceLevel = SkuReorderLevel::where('sku_id', $request->source_sku_id)
                ->where('period', $request->period)
                ->first();

            if (!$sourceLevel) {
                return response()->json(['error' => 'Source reorder level not found'], 404);
            }

            $copiedCount = 0;
            foreach ($request->target_sku_ids as $targetSkuId) {
                SkuReorderLevel::updateOrCreate([
                    'sku_id' => $targetSkuId,
                    'period' => $request->period,
                ], [
                    'min_level' => $sourceLevel->min_level,
                    'max_level' => $sourceLevel->max_level,
                    'reorder_level' => $sourceLevel->reorder_level,
                ]);
                $copiedCount++;
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Reorder levels copied to {$copiedCount} SKUs successfully",
                'copied_count' => $copiedCount
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to copy reorder levels: ' . $e->getMessage()], 500);
        }
    }

    // Get available periods for copy/paste
    public function getAvailablePeriods()
    {
        try {
            $periods = SkuReorderLevel::select('period')
                ->distinct()
                ->orderBy('period')
                ->pluck('period');
            
            // If no periods exist in database, generate default periods for the next 12 months
            if ($periods->isEmpty()) {
                $periods = collect();
                $now = now();
                for ($i = 0; $i < 12; $i++) {
                    $periods->push($now->copy()->addMonths($i)->format('m/Y'));
                }
            }
            
            return response()->json($periods);
        } catch (\Exception $e) {
            // Fallback: generate default periods if database query fails
            $periods = collect();
            $now = now();
            for ($i = 0; $i < 12; $i++) {
                $periods->push($now->copy()->addMonths($i)->format('m/Y'));
            }
            return response()->json($periods);
        }
    }

    // Get SKU suggestions for copy/paste
    public function getSkuSuggestions(Request $request)
    {
        $search = $request->query('search', '');
        $skus = MmSku::where('sku', 'like', "%{$search}%")
            ->orWhere('sku_name', 'like', "%{$search}%")
            ->limit(10)
            ->get(['sku', 'sku_name']);
        return response()->json($skus);
    }

    public function getForPrint(Request $request)
    {
        $query = MmSku::select([
            'sku',
            'sku_name',
            'uom',
            'is_active',
            'category_code',
            'type'
        ]);

        // Apply filters
        if ($request->filled('sku_from')) {
            $query->where('sku', '>=', $request->sku_from);
        }
        if ($request->filled('sku_to')) {
            $query->where('sku', '<=', $request->sku_to);
        }
        if ($request->filled('category_from')) {
            $query->where('category_code', '>=', $request->category_from);
        }
        if ($request->filled('category_to')) {
            $query->where('category_code', '<=', $request->category_to);
        }
        if ($request->filled('sku_types')) {
            $query->whereIn('type', $request->sku_types);
        }
        if ($request->filled('sku_status')) {
            $statuses = [];
            foreach ($request->sku_status as $status) {
                if ($status === 'A') {
                    $statuses[] = true;
                } elseif ($status === 'O') {
                    $statuses[] = false;
                }
            }
            if (!empty($statuses)) {
                $query->whereIn('is_active', $statuses);
            }
        }

        // Apply sorting
        if ($request->filled('sort_by')) {
            if ($request->sort_by === 'sku') {
                $query->orderBy('sku');
            } elseif ($request->sort_by === 'category_sku') {
                $query->orderBy('category_code')->orderBy('sku');
            }
        } else {
            $query->orderBy('sku');
        }

        $skus = $query->get();

        return response()->json($skus);
    }

    public function getViewPrint(Request $request)
    {
        try {
            // Return simple test data without using models
            $result = [
                [
                    'sku' => 'TEST001',
                    'sku_name' => 'Test SKU 1',
                    'category_code' => 'CAT001',
                    'type' => 'S',
                    'uom' => 'PCS',
                    'boh' => 100.00,
                    'is_active' => true,
                    'min_level' => 10.00,
                    'max_level' => 50.00,
                    'reorder_level' => 25.00,
                ],
                [
                    'sku' => 'TEST002',
                    'sku_name' => 'Test SKU 2',
                    'category_code' => 'CAT002',
                    'type' => 'NS',
                    'uom' => 'KG',
                    'boh' => 50.00,
                    'is_active' => true,
                    'min_level' => 5.00,
                    'max_level' => 25.00,
                    'reorder_level' => 15.00,
                ]
            ];

            return response()->json($result);
        } catch (\Exception $e) {
            Log::error('Error in getViewPrint: ' . $e->getMessage());
            return response()->json([
                'error' => 'Internal server error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function testConnection()
    {
        try {
            // Test database connection
            DB::connection()->getPdo();
            
            // Test MmSku model
            $skuCount = MmSku::count();
            
            // Test SkuReorderLevel model
            $reorderCount = SkuReorderLevel::count();
            
            return response()->json([
                'success' => true,
                'message' => 'Database connection successful',
                'data' => [
                    'sku_count' => $skuCount,
                    'reorder_level_count' => $reorderCount,
                    'database_connected' => true
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Test connection failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Connection test failed: ' . $e->getMessage()
            ], 500);
        }
    }

    public function testSimple()
    {
        try {
            // Test 1: Check if MmSku model works
            $skuCount = MmSku::count();
            Log::info('MmSku count: ' . $skuCount);

            // Test 2: Get first SKU
            $firstSku = MmSku::first();
            if ($firstSku) {
                Log::info('First SKU: ' . $firstSku->sku);
            }

            // Test 3: Check if SkuReorderLevel model works
            $reorderCount = SkuReorderLevel::count();
            Log::info('SkuReorderLevel count: ' . $reorderCount);

            return response()->json([
                'success' => true,
                'data' => [
                    'sku_count' => $skuCount,
                    'reorder_count' => $reorderCount,
                    'first_sku' => $firstSku ? $firstSku->sku : null
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Test simple failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Test failed: ' . $e->getMessage()
            ], 500);
        }
    }

    public function testBasic()
    {
        try {
            // Test basic database connection
            $pdo = DB::connection()->getPdo();
            
            // Test simple query
            $result = DB::select('SELECT COUNT(*) as count FROM mm_skus');
            $skuCount = $result[0]->count;
            
            return response()->json([
                'success' => true,
                'message' => 'Basic test successful',
                'data' => [
                    'database_connected' => true,
                    'sku_count' => $skuCount
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Basic test failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Basic test failed: ' . $e->getMessage()
            ], 500);
        }
    }

    public function testMinimal()
    {
        try {
            return response()->json([
                'success' => true,
                'message' => 'Minimal test successful',
                'data' => [
                    'controller_working' => true,
                    'timestamp' => now()->toISOString()
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Minimal test failed: ' . $e->getMessage()
            ], 500);
        }
    }
} 