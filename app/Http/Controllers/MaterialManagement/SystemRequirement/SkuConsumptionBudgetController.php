<?php
namespace App\Http\Controllers\MaterialManagement\SystemRequirement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SkuConsumptionBudget;
use App\Models\MmSku;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SkuConsumptionBudgetController extends Controller
{
    public function index(Request $request)
    {
        $skuId = $request->query('sku_id');
        $effectiveMonth = $request->query('effective_month');
        
        $query = SkuConsumptionBudget::with('sku');
        
        if ($skuId) {
            $query->where('sku_id', $skuId);
        }
        
        if ($effectiveMonth) {
            $query->where('effective_month', $effectiveMonth);
        }
        
        return response()->json($query->orderBy('effective_month')->get());
    }

    public function show($id)
    {
        return response()->json(SkuConsumptionBudget::with('sku')->findOrFail($id));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'sku_id' => 'required|exists:mm_skus,sku',
            'effective_month' => 'required|string|max:7',
            'budget_quantity' => 'required|numeric|min:0',
            'actual_quantity' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $data['created_by'] = Auth::user()->username ?? 'system';
        $data['actual_quantity'] = $data['actual_quantity'] ?? 0;
        $data['variance'] = $data['budget_quantity'] - $data['actual_quantity'];

        $item = SkuConsumptionBudget::updateOrCreate([
            'sku_id' => $data['sku_id'],
            'effective_month' => $data['effective_month'],
        ], $data);

        return response()->json($item->load('sku'));
    }

    public function update(Request $request, $id)
    {
        $item = SkuConsumptionBudget::findOrFail($id);
        $data = $request->validate([
            'budget_quantity' => 'required|numeric|min:0',
            'actual_quantity' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $data['updated_by'] = Auth::user()->username ?? 'system';
        $data['actual_quantity'] = $data['actual_quantity'] ?? 0;
        $data['variance'] = $data['budget_quantity'] - $data['actual_quantity'];

        $item->update($data);
        return response()->json($item->load('sku'));
    }

    public function destroy($id)
    {
        $item = SkuConsumptionBudget::findOrFail($id);
        $item->delete();
        return response()->json(['success' => true]);
    }

    public function getBySku($skuId)
    {
        $budgets = SkuConsumptionBudget::where('sku_id', $skuId)
            ->orderBy('effective_month')
            ->get();
        return response()->json($budgets);
    }

    public function getByEffectiveMonth($effectiveMonth)
    {
        $budgets = SkuConsumptionBudget::where('effective_month', $effectiveMonth)
            ->with('sku')
            ->orderBy('sku_id')
            ->get();
        return response()->json($budgets);
    }

    // Get SKU suggestions for consumption budget
    public function getSkuSuggestions(Request $request)
    {
        $search = $request->query('search', '');
        
        $skus = MmSku::where('is_active', true)
            ->where(function($query) use ($search) {
                $query->where('sku', 'like', "%{$search}%")
                      ->orWhere('sku_name', 'like', "%{$search}%");
            })
            ->select('sku', 'sku_name', 'category_code', 'type', 'uom')
            ->limit(20)
            ->get();
        
        return response()->json($skus);
    }

    // Get available effective months
    public function getAvailableMonths()
    {
        try {
            $months = SkuConsumptionBudget::select('effective_month')
                ->distinct()
                ->orderBy('effective_month')
                ->pluck('effective_month');
            
            // If no months exist in database, generate default months for the next 12 months
            if ($months->isEmpty()) {
                $months = collect();
                $now = now();
                for ($i = 0; $i < 12; $i++) {
                    $months->push($now->copy()->addMonths($i)->format('m/Y'));
                }
            }
            
            return response()->json($months);
        } catch (\Exception $e) {
            // Fallback: generate default months if database query fails
            $months = collect();
            $now = now();
            for ($i = 0; $i < 12; $i++) {
                $months->push($now->copy()->addMonths($i)->format('m/Y'));
            }
            return response()->json($months);
        }
    }

    // Bulk operations
    public function bulkStore(Request $request)
    {
        $request->validate([
            'budgets' => 'required|array',
            'budgets.*.sku_id' => 'required|exists:mm_skus,sku',
            'budgets.*.effective_month' => 'required|string|max:7',
            'budgets.*.budget_quantity' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();
            
            $created = [];
            foreach ($request->budgets as $budgetData) {
                $budgetData['created_by'] = Auth::user()->username ?? 'system';
                $budgetData['actual_quantity'] = $budgetData['actual_quantity'] ?? 0;
                $budgetData['variance'] = $budgetData['budget_quantity'] - $budgetData['actual_quantity'];

                $budget = SkuConsumptionBudget::updateOrCreate([
                    'sku_id' => $budgetData['sku_id'],
                    'effective_month' => $budgetData['effective_month'],
                ], $budgetData);
                
                $created[] = $budget->load('sku');
            }
            
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Consumption budgets created successfully',
                'data' => $created
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'error' => 'Failed to create consumption budgets: ' . $e->getMessage()
            ], 500);
        }
    }

    // Get consumption budget summary
    public function getSummary(Request $request)
    {
        $skuId = $request->query('sku_id');
        $effectiveMonth = $request->query('effective_month');
        
        $query = SkuConsumptionBudget::with('sku');
        
        if ($skuId) {
            $query->where('sku_id', $skuId);
        }
        
        if ($effectiveMonth) {
            $query->where('effective_month', $effectiveMonth);
        }
        
        $budgets = $query->get();
        
        $summary = [
            'total_budget' => $budgets->sum('budget_quantity'),
            'total_actual' => $budgets->sum('actual_quantity'),
            'total_variance' => $budgets->sum('variance'),
            'budget_count' => $budgets->count(),
            'positive_variance_count' => $budgets->where('variance', '>', 0)->count(),
            'negative_variance_count' => $budgets->where('variance', '<', 0)->count(),
            'zero_variance_count' => $budgets->where('variance', 0)->count(),
        ];
        
        return response()->json($summary);
    }

    // View & Print method for SKU Consumption Budget
    public function getViewPrint(Request $request)
    {
        try {
            // Get all SKU consumption budgets with SKU details
            $query = SkuConsumptionBudget::with('sku');
            
            // Apply filters if provided
            if ($request->filled('sku_id')) {
                $query->where('sku_id', $request->sku_id);
            }
            
            if ($request->filled('effective_month')) {
                $query->where('effective_month', $request->effective_month);
            }
            
            if ($request->filled('search')) {
                $search = $request->search;
                $query->whereHas('sku', function($q) use ($search) {
                    $q->where('sku', 'like', "%{$search}%")
                      ->orWhere('sku_name', 'like', "%{$search}%")
                      ->orWhere('category_code', 'like', "%{$search}%");
                });
            }
            
            $budgets = $query->orderBy('sku_id')->orderBy('effective_month')->get();
            
            // Transform data to match the desktop application format
            $result = $budgets->map(function($budget) {
                return [
                    'sku' => $budget->sku_id,
                    'sku_name' => $budget->sku ? $budget->sku->sku_name : 'N/A',
                    'category_code' => $budget->sku ? $budget->sku->category_code : 'N/A',
                    'type' => $budget->sku ? $budget->sku->type : 'N/A',
                    'uom' => $budget->sku ? $budget->sku->uom : 'N/A',
                    'is_active' => $budget->sku ? $budget->sku->is_active : true,
                    'effective_month' => $budget->effective_month,
                    'budget_quantity' => $budget->budget_quantity,
                    'actual_quantity' => $budget->actual_quantity,
                    'variance' => $budget->variance,
                    'check_flag' => $budget->variance > 0 ? 'OVER' : ($budget->variance < 0 ? 'UNDER' : 'OK'),
                    'notes' => $budget->notes,
                ];
            });
            
            return response()->json($result);
            
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error in getViewPrint: ' . $e->getMessage());
            return response()->json([
                'error' => 'Internal server error: ' . $e->getMessage()
            ], 500);
        }
    }
} 