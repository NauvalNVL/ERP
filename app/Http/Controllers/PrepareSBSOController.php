<?php

namespace App\Http\Controllers;

use App\Models\SalesBoardSalesOrder;
use App\Models\UpdateCustomerAccount;
use App\Models\Salesperson;
use App\Models\ProductDesign;
use App\Models\PaperFlute;
use App\Models\PaperQuality;
use App\Models\PaperSize;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class PrepareSBSOController extends Controller
{
    /**
     * Display the Prepare SB SO page.
     */
    public function index()
    {
        return inertia('sales-management/sales-order/Transaction/PrepareSBSO');
    }

    /**
     * Get all sales board sales orders for API consumption.
     */
    public function apiIndex(Request $request)
    {
        try {
            $query = SalesBoardSalesOrder::query();

            // Apply filters
            if ($request->has('customer_code') && $request->customer_code) {
                $query->where('customer_code', $request->customer_code);
            }

            if ($request->has('status') && $request->status) {
                $query->where('status', $request->status);
            }

            if ($request->has('period') && $request->period) {
                $query->where('current_period', $request->period);
            }

            if ($request->has('search') && $request->search) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('so_number', 'like', "%{$search}%")
                      ->orWhere('customer_code', 'like', "%{$search}%")
                      ->orWhere('product_design', 'like', "%{$search}%");
                });
            }

            // Sorting
            $sortBy = $request->get('sortBy', 'created_at');
            $sortOrder = $request->get('sortOrder', 'desc');
            $query->orderBy($sortBy, $sortOrder);

            // Pagination
            $perPage = $request->get('per_page', 15);
            $orders = $query->paginate($perPage);

            return response()->json($orders);
        } catch (\Exception $e) {
            Log::error('Error fetching sales board sales orders: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch sales board sales orders'], 500);
        }
    }

    /**
     * Store a new sales board sales order.
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'customer_code' => 'required|string|max:20',
                'salesperson_code' => 'required|string|max:20',
                'current_period' => 'required|integer',
                'update_period' => 'required|integer',
                'order_quantity' => 'required|integer|min:1',
                'currency' => 'required|string|max:3',
                'order_group' => 'required|in:Sales,Non-Sales'
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $data = $request->all();
            
            // Set default values
            $data['status'] = 'Draft';
            $data['forward_period'] = $data['forward_period'] ?? 1;
            $data['backward_period'] = $data['backward_period'] ?? 1;
            $data['exchange_rate'] = $data['exchange_rate'] ?? 0;
            $data['unit_price'] = $data['unit_price'] ?? 0;
            $data['area_per_pcs'] = $data['area_per_pcs'] ?? 0;
            $data['l_meter'] = $data['l_meter'] ?? 0;
            $data['sales_tax'] = $data['sales_tax'] ?? false;

            $order = SalesBoardSalesOrder::create($data);

            return response()->json([
                'success' => true,
                'message' => 'Sales Board Sales Order created successfully',
                'data' => $order->load(['customer', 'salesperson', 'productDesign', 'paperFlute', 'paperQuality'])
            ], 201);

        } catch (\Exception $e) {
            Log::error('Error creating sales board sales order: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to create sales board sales order'], 500);
        }
    }

    /**
     * Update an existing sales board sales order.
     */
    public function update(Request $request, $id)
    {
        try {
            $order = SalesBoardSalesOrder::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'customer_code' => 'required|string|max:20',
                'salesperson_code' => 'required|string|max:20',
                'current_period' => 'required|integer',
                'update_period' => 'required|integer',
                'order_quantity' => 'required|integer|min:1',
                'currency' => 'required|string|max:3',
                'order_group' => 'required|in:Sales,Non-Sales'
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $order->update($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Sales Board Sales Order updated successfully',
                'data' => $order->load(['customer', 'salesperson', 'productDesign', 'paperFlute', 'paperQuality'])
            ]);

        } catch (\Exception $e) {
            Log::error('Error updating sales board sales order: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to update sales board sales order'], 500);
        }
    }

    /**
     * Delete a sales board sales order.
     */
    public function destroy($id)
    {
        try {
            $order = SalesBoardSalesOrder::findOrFail($id);
            $order->delete();

            return response()->json([
                'success' => true,
                'message' => 'Sales Board Sales Order deleted successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Error deleting sales board sales order: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to delete sales board sales order'], 500);
        }
    }

    /**
     * Get customer information by customer code.
     */
    public function getCustomerInfo(Request $request)
    {
        try {
            $customerCode = $request->get('customer_code');
            
            if (!$customerCode) {
                return response()->json(['error' => 'Customer code is required'], 400);
            }

            $customer = UpdateCustomerAccount::where('customer_code', $customerCode)
                ->where('status', 'Active')
                ->first();

            if (!$customer) {
                return response()->json(['error' => 'Customer not found'], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $customer
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching customer info: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch customer information'], 500);
        }
    }

    /**
     * Get salesperson information by salesperson code.
     */
    public function getSalespersonInfo(Request $request)
    {
        try {
            $salespersonCode = $request->get('salesperson_code');
            
            if (!$salespersonCode) {
                return response()->json(['error' => 'Salesperson code is required'], 400);
            }

            $salesperson = Salesperson::where('code', $salespersonCode)
                ->where('is_active', true)
                ->first();

            if (!$salesperson) {
                return response()->json(['error' => 'Salesperson not found'], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $salesperson
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching salesperson info: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch salesperson information'], 500);
        }
    }

    /**
     * Get product design information.
     */
    public function getProductDesigns(Request $request)
    {
        try {
            $query = ProductDesign::where('status', 'Active');

            if ($request->has('search') && $request->search) {
                $query->where(function($q) use ($request) {
                    $q->where('product_design_code', 'like', "%{$request->search}%")
                      ->orWhere('product_design_name', 'like', "%{$request->search}%");
                });
            }

            $productDesigns = $query->orderBy('product_design_code')->get();

            return response()->json([
                'success' => true,
                'data' => $productDesigns
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching product designs: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch product designs'], 500);
        }
    }

    /**
     * Get paper flutes.
     */
    public function getPaperFlutes(Request $request)
    {
        try {
            $query = PaperFlute::where('status', 'Active');

            if ($request->has('search') && $request->search) {
                $query->where(function($q) use ($request) {
                    $q->where('flute_code', 'like', "%{$request->search}%")
                      ->orWhere('flute_description', 'like', "%{$request->search}%");
                });
            }

            $paperFlutes = $query->orderBy('flute_code')->get();

            return response()->json([
                'success' => true,
                'data' => $paperFlutes
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching paper flutes: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch paper flutes'], 500);
        }
    }

    /**
     * Get paper qualities.
     */
    public function getPaperQualities(Request $request)
    {
        try {
            $query = PaperQuality::where('status', 'Active');

            if ($request->has('search') && $request->search) {
                $query->where(function($q) use ($request) {
                    $q->where('quality_code', 'like', "%{$request->search}%")
                      ->orWhere('quality_description', 'like', "%{$request->search}%");
                });
            }

            $paperQualities = $query->orderBy('quality_code')->get();

            return response()->json([
                'success' => true,
                'data' => $paperQualities
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching paper qualities: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch paper qualities'], 500);
        }
    }

    /**
     * Get paper sizes.
     */
    public function getPaperSizes(Request $request)
    {
        try {
            $query = PaperSize::where('status', 'Active');

            if ($request->has('search') && $request->search) {
                $query->where(function($q) use ($request) {
                    $q->where('size_code', 'like', "%{$request->search}%")
                      ->orWhere('size_description', 'like', "%{$request->search}%");
                });
            }

            $paperSizes = $query->orderBy('size_code')->get();

            return response()->json([
                'success' => true,
                'data' => $paperSizes
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching paper sizes: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch paper sizes'], 500);
        }
    }

    /**
     * Calculate area per piece and linear meter.
     */
    public function calculateDimensions(Request $request)
    {
        try {
            $length = $request->get('length', 0);
            $width = $request->get('width', 0);
            $quantity = $request->get('quantity', 1);

            // Calculate area per piece in m²
            $areaPerPcs = ($length * $width) / 1000000; // Convert mm² to m²

            // Calculate linear meter
            $lMeter = ($length * $quantity) / 1000; // Convert mm to m

            return response()->json([
                'success' => true,
                'data' => [
                    'area_per_pcs' => round($areaPerPcs, 4),
                    'l_meter' => round($lMeter, 2)
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error calculating dimensions: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to calculate dimensions'], 500);
        }
    }

    /**
     * Print SO Log.
     */
    public function printSOLog(Request $request)
    {
        try {
            $orderId = $request->get('order_id');
            
            if (!$orderId) {
                return response()->json(['error' => 'Order ID is required'], 400);
            }

            $order = SalesBoardSalesOrder::with(['customer', 'salesperson', 'productDesign', 'paperFlute', 'paperQuality'])
                ->findOrFail($orderId);

            // Here you would typically generate a PDF or return data for printing
            return response()->json([
                'success' => true,
                'message' => 'SO Log generated successfully',
                'data' => $order
            ]);

        } catch (\Exception $e) {
            Log::error('Error printing SO log: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to print SO log'], 500);
        }
    }

    /**
     * Print SO JIT Tracking.
     */
    public function printSOJITTracking(Request $request)
    {
        try {
            $orderId = $request->get('order_id');
            
            if (!$orderId) {
                return response()->json(['error' => 'Order ID is required'], 400);
            }

            $order = SalesBoardSalesOrder::with(['customer', 'salesperson', 'productDesign', 'paperFlute', 'paperQuality'])
                ->findOrFail($orderId);

            // Here you would typically generate a PDF or return data for printing
            return response()->json([
                'success' => true,
                'message' => 'SO JIT Tracking generated successfully',
                'data' => $order
            ]);

        } catch (\Exception $e) {
            Log::error('Error printing SO JIT tracking: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to print SO JIT tracking'], 500);
        }
    }
}
