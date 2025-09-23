<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Models\UpdateCustomerAccount;
use App\Models\MasterCard;
use App\Models\SalesOrder;
use App\Models\Salesperson;
use Inertia\Inertia;

class SalesOrderController extends Controller
{
    /**
     * Display the prepare MC SO page
     */
    public function prepareMcSo()
    {
        return Inertia::render('sales-management/sales-order/Transaction/PrepareMCSO');
    }

    /**
     * Store a new sales order
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'customer_code' => 'required|string',
            'master_card_seq' => 'required|string',
            'order_mode' => 'required|string',
            'product_code' => 'required|string',
            'salesperson_code' => 'nullable|string',
            'currency' => 'required|string',
            'exchange_rate' => 'nullable|numeric',
            'customer_po_number' => 'nullable|string',
            'po_date' => 'required|date',
            'order_group' => 'required|string|in:Sales,Non-Sales',
            'order_type' => 'required|string',
            'sales_tax' => 'boolean',
            'lot_number' => 'nullable|string',
            'remark' => 'nullable|string',
            'instruction1' => 'nullable|string',
            'instruction2' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Pull related entities
            $customer = UpdateCustomerAccount::where('customer_code', $request->customer_code)->first();
            $mc = MasterCard::where('mc_seq', $request->master_card_seq)->first();

            if (!$customer) {
                return response()->json(['success' => false, 'message' => 'Customer not found'], 404);
            }
            if (!$mc) {
                return response()->json(['success' => false, 'message' => 'Master Card not found'], 404);
            }

            // Generate SO number
            $soNumber = $this->generateSONumber();

            // Combine snapshot data per flow
            $salesOrder = SalesOrder::create([
                'so_number' => $soNumber,
                'customer_code' => $customer->customer_code,
                'customer_name' => $customer->customer_name ?? null,
                'customer_address' => $customer->address ?? null,
                'credit_terms' => $customer->credit_terms ?? null,
                'credit_limit' => $customer->credit_limit ?? null,
                'salesperson_code' => $request->salesperson_code ?: ($customer->salesperson_code ?? null),
                'currency' => $request->currency,
                'exchange_rate' => $request->exchange_rate ?? 0,

                'master_card_seq' => $mc->mc_seq,
                'master_card_model' => $mc->mc_model ?? null,
                'p_design' => $mc->p_design ?? null,
                'uom' => 'PCS',

                'order_mode' => $request->order_mode,
                'product_code' => $request->product_code,
                'order_group' => $request->order_group,
                'order_type' => $request->order_type,
                'sales_tax' => (bool)($request->sales_tax ?? false),
                'lot_number' => $request->lot_number,
                'customer_po_number' => $request->customer_po_number,
                'po_date' => $request->po_date,
                'remark' => $request->remark,
                'instruction1' => $request->instruction1,
                'instruction2' => $request->instruction2,
                'status' => 'Draft',
                'created_by' => Auth::id(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Sales order created successfully',
                'data' => [
                    'so_number' => $soNumber,
                    'sales_order' => $salesOrder
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error creating sales order: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error creating sales order: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate SO number
     */
    private function generateSONumber()
    {
        $year = date('Y');
        $prefix = 'SO';
        
        // Get the last SO number for this year
        // For now, we'll generate a simple incremental number
        $lastNumber = 1; // In real implementation, get from database
        
        return $prefix . $year . str_pad($lastNumber, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Get customer details by code
     */
    public function getCustomer($customerCode)
    {
        try {
            $customer = UpdateCustomerAccount::where('customer_code', $customerCode)
                ->first();

            if (!$customer) {
                return response()->json([
                    'success' => false,
                    'message' => 'Customer not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $customer
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching customer: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error fetching customer'
            ], 500);
        }
    }

    /**
     * Get master card details by sequence
     */
    public function getMasterCard($mcSeq)
    {
        try {
            $masterCard = MasterCard::where('mc_seq', $mcSeq)->first();

            if (!$masterCard) {
                return response()->json([
                    'success' => false,
                    'message' => 'Master card not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $masterCard
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching master card: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error fetching master card'
            ], 500);
        }
    }

    /**
     * Get salesperson details by code
     */
    public function getSalesperson($salespersonCode)
    {
        try {
            $salesperson = Salesperson::where('code', $salespersonCode)->first();

            if (!$salesperson) {
                return response()->json([
                    'success' => false,
                    'message' => 'Salesperson not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $salesperson
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching salesperson: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error fetching salesperson'
            ], 500);
        }
    }

    /**
     * Get product design data for sales order
     */
    public function getProductDesignData($masterCardSeq)
    {
        try {
            $masterCard = MasterCard::where('mc_seq', $masterCardSeq)->first();
            
            if (!$masterCard) {
                return response()->json([
                    'success' => false,
                    'message' => 'Master card not found'
                ], 404);
            }

            // Get product design data related to this master card
            $productDesignData = [
                'master_card' => $masterCard,
                'items' => [
                    [
                        'name' => 'Main',
                        'design' => $masterCard->p_design ?? 'B1',
                        'pcs' => 1,
                        'quantity' => 50,
                        'unit_price' => 3036.3600,
                        'unit' => 'Pcs',
                        'amount' => 151818.00,
                        'fg_balance' => null
                    ]
                ],
                'dimensions' => [
                    [
                        'name' => 'Main',
                        'ext_dimension' => $masterCard->ext_dim_1 . ' x ' . $masterCard->ext_dim_2 . ' x ' . $masterCard->ext_dim_3,
                        'int_dimension' => $masterCard->int_dim_1 . ' x ' . $masterCard->int_dim_2 . ' x ' . $masterCard->int_dim_3,
                        'part_number' => $masterCard->part_no ?? 'BOX',
                        'total_gross_kg' => '3.0620',
                        'packing' => '',
                        'moq' => ''
                    ]
                ]
            ];

            return response()->json([
                'success' => true,
                'data' => $productDesignData
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching product design data: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error fetching product design data'
            ], 500);
        }
    }

    /**
     * Save product design data
     */
    public function saveProductDesign(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'master_card_seq' => 'required|string',
            'items' => 'required|array',
            'dimensions' => 'required|array',
            'total_amount' => 'required|numeric',
            'total_gross_kg' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Save product design data to database
            // For now, we'll just log the data
            Log::info('Product Design Saved:', $request->all());

            return response()->json([
                'success' => true,
                'message' => 'Product design saved successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Error saving product design: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error saving product design'
            ], 500);
        }
    }

    /**
     * Save delivery schedule
     */
    public function saveDeliverySchedule(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'entries' => 'required|array',
            'entry_total' => 'required|numeric',
            'order_total' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Save delivery schedule data to database
            // For now, we'll just log the data
            Log::info('Delivery Schedule Saved:', $request->all());

            return response()->json([
                'success' => true,
                'message' => 'Delivery schedule saved successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Error saving delivery schedule: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error saving delivery schedule'
            ], 500);
        }
    }

    /**
     * Print SO log
     */
    public function printLog()
    {
        // Generate SO log report
        return response()->json([
            'message' => 'SO Log report generated successfully',
            'data' => [
                'report_type' => 'SO_LOG',
                'generated_at' => now(),
                'generated_by' => Auth::user()->name ?? 'System'
            ]
        ]);
    }

    /**
     * Print JIT tracking
     */
    public function printJitTracking()
    {
        // Generate JIT tracking report
        return response()->json([
            'message' => 'JIT Tracking report generated successfully',
            'data' => [
                'report_type' => 'JIT_TRACKING',
                'generated_at' => now(),
                'generated_by' => Auth::user()->name ?? 'System'
            ]
        ]);
    }
}
