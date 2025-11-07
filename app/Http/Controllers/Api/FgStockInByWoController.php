<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\WorkOrder;
use App\Models\FgStockInByWo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class FgStockInByWoController extends Controller
{
    public function getWorkOrders(Request $request)
    {
        try {
            $query = WorkOrder::query();
            
            // Filter by status if provided
            if ($request->has('status')) {
                $query->where('status', $request->status);
            }
            
            // Search by work order number or customer name
            if ($request->has('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('wo_number', 'like', "%{$search}%")
                      ->orWhere('customer_name', 'like', "%{$search}%")
                      ->orWhere('so_number', 'like', "%{$search}%");
                });
            }
            
            $workOrders = $query->orderBy('due_date', 'asc')
                               ->orderBy('wo_number', 'desc')
                               ->get()
                               ->map(function ($wo) {
                                   return [
                                       'id' => $wo->id,
                                       'wo_number' => $wo->wo_number,
                                       'so_number' => $wo->so_number,
                                       'customer_name' => $wo->customer_name,
                                       'plan_qty' => $wo->plan_qty,
                                       'due_date' => $wo->due_date ? $wo->due_date->format('d/m/Y') : null,
                                       'status' => $wo->status,
                                       'wo_status' => $wo->wo_status,
                                       'so_status' => $wo->so_status,
                                       'acr_number' => $wo->acr_number,
                                       'mcs_number' => $wo->mcs_number,
                                       'model' => $wo->model,
                                       'comp_number' => $wo->comp_number,
                                       'part_number' => $wo->part_number,
                                       'pd' => $wo->pd,
                                       'pcs_per_unit' => $wo->pcs_per_unit,
                                       'ied_x' => $wo->ied_x,
                                       'ied_y' => $wo->ied_y,
                                       'completed_qty' => $wo->completed_qty,
                                       'total_received' => $wo->total_received,
                                       'remaining_qty' => $wo->remaining_qty,
                                   ];
                               });
            
            return response()->json($workOrders);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching work orders: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function getWorkOrderDetails($woNumber)
    {
        try {
            $workOrder = WorkOrder::where('wo_number', $woNumber)->first();
            
            if (!$workOrder) {
                return response()->json([
                    'success' => false,
                    'message' => 'Work order not found'
                ], 404);
            }
            
            $stockInHistory = FgStockInByWo::where('wo_number', $woNumber)
                                          ->orderBy('entry_date', 'desc')
                                          ->get()
                                          ->map(function ($item) {
                                              return [
                                                  'id' => $item->id,
                                                  'entry_ref' => $item->entry_ref,
                                                  'entry_qty' => $item->entry_qty,
                                                  'entry_date' => $item->entry_date->format('d/m/Y'),
                                                  'warehouse' => $item->warehouse,
                                                  'analysis' => $item->analysis,
                                                  'remark' => $item->remark,
                                                  'created_by' => $item->created_by,
                                                  'status' => $item->status,
                                              ];
                                          });
            
            return response()->json([
                'success' => true,
                'work_order' => [
                    'id' => $workOrder->id,
                    'wo_number' => $workOrder->wo_number,
                    'so_number' => $workOrder->so_number,
                    'customer_name' => $workOrder->customer_name,
                    'plan_qty' => $workOrder->plan_qty,
                    'due_date' => $workOrder->due_date ? $workOrder->due_date->format('d/m/Y') : null,
                    'status' => $workOrder->status,
                    'wo_status' => $workOrder->wo_status,
                    'so_status' => $workOrder->so_status,
                    'acr_number' => $workOrder->acr_number,
                    'mcs_number' => $workOrder->mcs_number,
                    'model' => $workOrder->model,
                    'comp_number' => $workOrder->comp_number,
                    'part_number' => $workOrder->part_number,
                    'pd' => $workOrder->pd,
                    'pcs_per_unit' => $workOrder->pcs_per_unit,
                    'ied_x' => $workOrder->ied_x,
                    'ied_y' => $workOrder->ied_y,
                    'completed_qty' => $workOrder->completed_qty,
                    'total_received' => $workOrder->total_received,
                    'remaining_qty' => $workOrder->remaining_qty,
                ],
                'stock_in_history' => $stockInHistory
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching work order details: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'work_order_id' => 'required|exists:work_orders,id',
            'wo_number' => 'required|string',
            'entry_ref' => 'required|string|max:255',
            'entry_qty' => 'required|numeric|min:0.01',
            'entry_date' => 'required|date',
            'warehouse' => 'required|string|max:255',
            'analysis' => 'nullable|string|max:255',
            'remark' => 'nullable|string',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }
        
        try {
            DB::beginTransaction();
            
            $workOrder = WorkOrder::findOrFail($request->work_order_id);
            
            // Check if entry quantity exceeds remaining quantity
            $remainingQty = $workOrder->plan_qty - $workOrder->total_received;
            if ($request->entry_qty > $remainingQty) {
                return response()->json([
                    'success' => false,
                    'message' => "Entry quantity ({$request->entry_qty}) exceeds remaining quantity ({$remainingQty})"
                ], 422);
            }
            
            // Create stock-in record
            $stockIn = FgStockInByWo::create([
                'work_order_id' => $request->work_order_id,
                'wo_number' => $request->wo_number,
                'entry_ref' => $request->entry_ref,
                'entry_qty' => $request->entry_qty,
                'entry_date' => $request->entry_date,
                'warehouse' => $request->warehouse,
                'analysis' => $request->analysis,
                'remark' => $request->remark,
                'period' => $request->period ?? '05/2025',
                'created_by' => Auth::user()->name ?? 'System',
                'status' => 'Processed'
            ]);
            
            // Update work order completed quantity
            $newCompletedQty = $workOrder->completed_qty + $request->entry_qty;
            $workOrder->update(['completed_qty' => $newCompletedQty]);
            
            // Update work order status if completed
            if ($newCompletedQty >= $workOrder->plan_qty) {
                $workOrder->update(['status' => 'Completed', 'wo_status' => 'Completed']);
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Stock-in record saved successfully',
                'data' => $stockIn
            ]);
            
        } catch (\Exception $e) {
            DB::rollback();
            
            return response()->json([
                'success' => false,
                'message' => 'Error saving stock-in record: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function index(Request $request)
    {
        try {
            $query = FgStockInByWo::with('workOrder');
            
            // Filter by warehouse
            if ($request->has('warehouse')) {
                $query->where('warehouse', $request->warehouse);
            }
            
            // Filter by date range
            if ($request->has('start_date') && $request->has('end_date')) {
                $query->whereBetween('entry_date', [$request->start_date, $request->end_date]);
            }
            
            // Filter by work order number
            if ($request->has('wo_number')) {
                $query->where('wo_number', 'like', '%' . $request->wo_number . '%');
            }
            
            $stockIns = $query->orderBy('entry_date', 'desc')
                            ->orderBy('created_at', 'desc')
                            ->paginate($request->per_page ?? 15);
            
            return response()->json($stockIns);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching stock-in records: ' . $e->getMessage()
            ], 500);
        }
    }
}
