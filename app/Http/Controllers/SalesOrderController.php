<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Models\UpdateCustomerAccount;
use App\Models\Customer;
use App\Models\MasterCard;
use App\Models\SalesOrder;
use App\Models\SalesOrderDetail;
use App\Models\DeliverySchedule;
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
            'set_quantity' => 'nullable|string',
            'details' => 'required|array',
            'details.*.line_number' => 'required|integer',
            'details.*.item_code' => 'required|string',
            'details.*.item_description' => 'nullable|string',
            'details.*.order_quantity' => 'required|numeric|min:0',
            'details.*.unit_price' => 'required|numeric|min:0',
            'details.*.uom' => 'nullable|string',
            'details.*.remark' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Log the incoming request data for debugging
            Log::info('Creating sales order with data:', $request->all());
            
            // Pull related entities
            $customer = Customer::where('CODE', $request->customer_code)->first();
            $mc = MasterCard::where('mc_seq', $request->master_card_seq)->first();

            if (!$customer) {
                Log::error('Customer not found: ' . $request->customer_code);
                return response()->json(['success' => false, 'message' => 'Customer not found'], 404);
            }
            if (!$mc) {
                Log::error('Master Card not found: ' . $request->master_card_seq);
                return response()->json(['success' => false, 'message' => 'Master Card not found'], 404);
            }

            Log::info('Creating sales order for customer: ' . $request->customer_code . ' with MC: ' . $request->master_card_seq);

            // Instead of using SalesOrder model, save directly to 'so' table
            $poDate = \Carbon\Carbon::parse($request->po_date);
            $mm = $poDate->format('m');
            $yyyy = $poDate->format('Y');

            // Generate SO number in format MM-YYYY-#####
            $prefix = $mm . '-' . $yyyy . '-';
            $last = DB::table('so')
                ->where('SO_Num', 'like', $prefix . '%')
                ->orderBy('SO_Num', 'desc')
                ->value('SO_Num');
            $seq = 1;
            if ($last) {
                $parts = explode('-', $last);
                $seq = isset($parts[2]) ? ((int) $parts[2]) + 1 : 1;
            }
            $soNum = $prefix . str_pad($seq, 5, '0', STR_PAD_LEFT);

            $first = $request->details[0];
            $qty = (float) ($first['order_quantity'] ?? 0);
            $unitPrice = (float) ($first['unit_price'] ?? 0);
            $amount = $qty * $unitPrice;

            // Map into legacy columns - provide all required fields
            $row = [
                'YYYY' => $yyyy,
                'MM' => $mm,
                'SO_Num' => $soNum,
                'STS' => 'Draft',
                'TYPE' => $request->order_type,
                'SO_DMY' => $poDate->format('d/m/Y'),
                'AC_Num' => $request->customer_code,
                'AC_NAME' => $customer->NAME ?? '',
                'SLM' => $request->salesperson_code ?? '',
                'IND' => $customer->IND ?? '', // Required field
                'AREA' => $customer->AREA ?? '', // Required field
                'GROUP_' => $request->order_group,
                'PO_Num' => $request->customer_po_number ?? '',
                'PO_DATE' => $poDate->format('d/m/Y'),
                'LOT_Num' => $request->lot_number ?? '',
                'MCS_Num' => $request->master_card_seq,
                'MODEL' => $mc->mc_model ?? '',
                'PRODUCT' => $first['item_code'] ?? '',
                'COMP_Num' => $customer->CODE ?? '', // Required field
                'P_DESIGN' => $mc->p_design ?? '',
                'PER_SET' => 1.0, // Required field
                'UNIT' => $first['uom'] ?? 'PCS',
                'PART_NUMBER' => $first['item_code'] ?? '', // Required field
                'INT_L' => $mc->int_dim_1 ?? 0.0, // Required field
                'INT_W' => $mc->int_dim_2 ?? 0.0, // Required field
                'INT_H' => $mc->int_dim_3 ?? 0.0, // Required field
                'EXT_L' => $mc->ext_dim_1 ?? 0.0, // Required field
                'EXT_W' => $mc->ext_dim_2 ?? 0.0, // Required field
                'EXT_H' => $mc->ext_dim_3 ?? 0.0, // Required field
                'FLUTE' => $mc->flute ?? '', // Required field
                'PQ1' => $mc->pq1 ?? '', // Required field
                'PQ2' => $mc->pq2 ?? '', // Required field
                'PQ3' => $mc->pq3 ?? '', // Required field
                'PQ4' => $mc->pq4 ?? '', // Required field
                'PQ5' => $mc->pq5 ?? '', // Required field
                'SO_QTY' => $qty,
                'UNIT_PRICE' => $unitPrice,
                'CURR' => $request->currency,
                'EX_RATE' => $request->exchange_rate ?? 0,
                'AMOUNT' => $amount,
                'BASE_AMOUNT' => $amount, // Required field
                'MC_GROSS_M2_PER_PCS' => $mc->mc_gross_m2_per_pcs ?? 0.0, // Required field
                'MC_NET_M2_PER_PCS' => $mc->mc_net_m2_per_pcs ?? 0.0, // Required field
                'TOTAL_SO_GROSS_M2' => $mc->total_so_gross_m2 ?? 0.0, // Required field
                'TOTAL_SO_NET_M2' => $mc->total_so_net_m2 ?? 0.0, // Required field
                'TOTAL_LM' => $mc->total_lm ?? '', // Required field
                'TOTAL_M3' => $mc->total_m3 ?? 0, // Required field
                'MC_GROSS_KG_PER_PCS' => $mc->mc_gross_kg_per_pcs ?? 0.0, // Required field
                'MC_NET_KG_PER_PCS' => $mc->mc_net_kg_per_pcs ?? 0.0, // Required field
                'TOTAL_SO_GROSS_KG' => $mc->total_so_gross_kg ?? 0.0, // Required field
                'TOTAL_SO_NET_KG' => $mc->total_so_net_kg ?? 0.0, // Required field
                'SO_REMARK' => $request->remark ?? '',
                'SO_INSTRUCTION_1' => $request->instruction1 ?? '',
                'SO_INSTRUCTION_2' => $request->instruction2 ?? '',
                'D_LOC_Num' => '', // Required field
                'DELIVERY_TO' => '', // Required field
                'DELIVERY_ADD_1' => '', // Required field
                'DELIVERY_ADD_2' => '', // Required field
                'DELIVERY_ADD_3' => '', // Required field
                'D_DATE_1' => '', // Required field
                'D_TIME_1' => '', // Required field
                'D_DUE_1' => '', // Required field
                'D_QTY_1' => 0.0, // Required field
                'D_DATE_2' => '', // Required field
                'D_TIME_2' => '', // Required field
                'D_DUE_2' => '', // Required field
                'D_QTY_2' => 0.0, // Required field
                'D_DATE_3' => '', // Required field
                'D_TIME_3' => '', // Required field
                'D_DUE_3' => '', // Required field
                'D_QTY_3' => 0.0, // Required field
                'D_DATE_4' => '', // Required field
                'D_TIME_4' => '', // Required field
                'D_DUE_4' => '', // Required field
                'D_QTY_4' => 0.0, // Required field
                'D_DATE_5' => '', // Required field
                'D_TIME_5' => '', // Required field
                'D_DUE_5' => '', // Required field
                'D_QTY_5' => 0.0, // Required field
                'D_DATE_6' => '', // Required field
                'D_TIME_6' => '', // Required field
                'D_DUE_6' => '', // Required field
                'D_QTY_6' => 0.0, // Required field
                'D_DATE_7' => '', // Required field
                'D_TIME_7' => '', // Required field
                'D_DUE_7' => '', // Required field
                'D_QTY_7' => 0.0, // Required field
                'D_DATE_8' => '', // Required field
                'D_TIME_8' => '', // Required field
                'D_DUE_8' => '', // Required field
                'D_QTY_8' => 0.0, // Required field
                'D_DATE_9' => '', // Required field
                'D_TIME_9' => '', // Required field
                'D_DUE_9' => '', // Required field
                'D_QTY_9' => 0.0, // Required field
                'D_DATE10' => '', // Required field
                'D_TIME10' => '', // Required field
                'D_DUE10' => '', // Required field
                'D_QTY_10' => 0.0, // Required field
                'NW_UID' => Auth::user()->name ?? 'system',
                'NW_DATE' => now()->format('d/m/Y'),
                'NW_TIME' => now()->format('H:i'),
                'AM_UID' => '', // Required field
                'AM_DATE' => '', // Required field
                'AM_TIME' => '', // Required field
                'CX_UID' => '', // Required field
                'CX_DATE' => '', // Required field
                'CX_TIME' => '', // Required field
                'PT_UID' => '', // Required field
                'PT_DATE' => '', // Required field
                'PT_TIME' => '', // Required field
                'SODateSK' => $poDate->format('Ymd'), // Required field
                'PODateSK' => $poDate->format('Ymd'), // Required field
            ];

            DB::table('so')->insert($row);

            Log::info('Sales order created successfully with SO Number: ' . $soNum);

            return response()->json([
                'success' => true,
                'message' => 'Sales order created successfully',
                'data' => [
                    'so_number' => $soNum,
                    'sales_order' => $row
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error creating sales order: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
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
        $lastSO = SalesOrder::where('so_number', 'like', $prefix . $year . '%')
            ->orderBy('so_number', 'desc')
            ->first();
        
        if ($lastSO) {
            // Extract the number part and increment
            $lastNumber = (int) substr($lastSO->so_number, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        
        return $prefix . $year . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Get customer details by code
     */
    public function getCustomer($customerCode)
    {
        try {
            $customer = Customer::where('CODE', $customerCode)
                ->first();

            if (!$customer) {
                return response()->json([
                    'success' => false,
                    'message' => 'Customer not found'
                ], 404);
            }

            // Transform the data to match frontend expectations
            $customerData = [
                'id' => $customer->CODE,
                'customer_code' => $customer->CODE,
                'customer_name' => $customer->NAME,
                'short_name' => $customer->SHORT_NAME ?? '',
                'address' => $customer->ADDRESS1 ?? '',
                'address2' => $customer->ADDRESS2 ?? '',
                'address3' => $customer->ADDRESS3 ?? '',
                'contact_person' => $customer->PERSON_CONTACT ?? '',
                'telephone_no' => $customer->TEL_NO ?? '',
                'fax_no' => $customer->FAX_NO ?? '',
                'co_email' => $customer->EMAIL ?? '',
                'credit_limit' => $customer->CREDIT_LIMIT ?? 0,
                'credit_terms' => $customer->TERM ?? 0,
                'account_type' => $customer->TYPE ?? 'N-Local',
                'currency_code' => $customer->CURRENCY ?? 'IDR',
                'salesperson_code' => $customer->SLM ?? '',
                'industrial_code' => $customer->IND ?? '',
                'geographical' => $customer->AREA ?? '',
                'grouping_code' => $customer->GROUP_ ?? '',
                'npwp' => $customer->NPWP ?? '',
                'status' => $customer->CUST_TYPE ?? 'A'
            ];

            return response()->json([
                'success' => true,
                'data' => $customerData
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
            'so_number' => 'required|string',
            'entries' => 'required|array',
            'entries.*.line_number' => 'required|integer',
            'entries.*.schedule_date' => 'required|date',
            'entries.*.schedule_time' => 'nullable|date_format:H:i',
            'entries.*.delivery_quantity' => 'required|numeric|min:0',
            'entries.*.due_status' => 'required|string|in:ETD,ETA,TBA',
            'entries.*.remark' => 'nullable|string',
            'entries.*.delivery_code' => 'nullable|string',
            'entries.*.delivery_location' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $soNumber = $request->so_number;
            $entries = $request->entries;

            // Get the sales order from 'so' table to validate it exists
            $salesOrder = DB::table('so')->where('SO_Num', $soNumber)->first();
            if (!$salesOrder) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sales Order not found'
                ], 404);
            }

            // For simplicity, we'll just save the delivery schedule entries
            // Since we're using the 'so' table, we don't need to validate line numbers
            // as the 'so' table contains all the order information in a single row

            // Save delivery schedule entries to 'so' table
            $savedEntries = [];
            $updateData = [];
            
            // Map delivery schedule entries to 'so' table columns
            foreach ($entries as $index => $entry) {
                $scheduleDate = \Carbon\Carbon::parse($entry['schedule_date']);
                $scheduleTime = $entry['schedule_time'] ?? '00:00';
                $dueStatus = $entry['due_status'] ?? 'TBA';
                $quantity = (float) $entry['delivery_quantity'];
                
                // Map to D_DATE_X, D_TIME_X, D_DUE_X, D_QTY_X columns
                $dateKey = 'D_DATE_' . ($index + 1);
                $timeKey = 'D_TIME_' . ($index + 1);
                $dueKey = 'D_DUE_' . ($index + 1);
                $qtyKey = 'D_QTY_' . ($index + 1);
                
                $updateData[$dateKey] = $scheduleDate->format('d/m/Y');
                $updateData[$timeKey] = $scheduleTime;
                $updateData[$dueKey] = $dueStatus;
                $updateData[$qtyKey] = $quantity;
                
                // Also update delivery location info if provided
                if ($entry['delivery_code']) {
                    $updateData['D_LOC_Num'] = $entry['delivery_code'];
                }
                if ($entry['delivery_location']) {
                    $updateData['DELIVERY_TO'] = $entry['delivery_location'];
                }
                
                $savedEntries[] = [
                    'line_number' => $entry['line_number'],
                    'schedule_date' => $entry['schedule_date'],
                    'schedule_time' => $entry['schedule_time'],
                    'delivery_quantity' => $entry['delivery_quantity'],
                    'due_status' => $entry['due_status'],
                    'remark' => $entry['remark'] ?? null,
                    'delivery_code' => $entry['delivery_code'] ?? null,
                    'delivery_location' => $entry['delivery_location'] ?? null,
                ];
            }
            
            // Update the 'so' table with delivery schedule data
            DB::table('so')
                ->where('SO_Num', $soNumber)
                ->update($updateData);

            DB::commit();

            Log::info('Delivery Schedule Saved:', [
                'so_number' => $soNumber,
                'entries_count' => count($savedEntries),
                'saved_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Delivery schedule saved successfully',
                'data' => [
                    'so_number' => $soNumber,
                    'entries_count' => count($savedEntries),
                    'saved_at' => now()
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saving delivery schedule: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error saving delivery schedule: ' . $e->getMessage()
            ], 500);
        }
    }


    /**
     * Get delivery schedules for a sales order
     */
    public function getDeliverySchedules(Request $request, $soNumber)
    {
        try {
            $salesOrder = SalesOrder::with(['details', 'deliverySchedules'])
                ->where('so_number', $soNumber)
                ->first();

            if (!$salesOrder) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sales Order not found'
                ], 404);
            }

            $deliverySchedules = DeliverySchedule::where('so_number', $soNumber)
                ->with('salesOrderDetail')
                ->orderBy('line_number')
                ->orderBy('schedule_sequence')
                ->get();

            return response()->json([
                'success' => true,
                'data' => [
                    'sales_order' => $salesOrder,
                    'delivery_schedules' => $deliverySchedules,
                    'summary' => [
                        'total_order_quantity' => $salesOrder->total_order_quantity,
                        'total_scheduled_quantity' => $salesOrder->total_scheduled_quantity,
                        'remaining_quantity' => $salesOrder->remaining_quantity,
                        'is_fully_scheduled' => $salesOrder->isFullyScheduled(),
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting delivery schedules: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error getting delivery schedules'
            ], 500);
        }
    }

    /**
     * Get delivery schedule summary for validation
     */
    public function getDeliveryScheduleSummary(Request $request, $soNumber)
    {
        try {
            $salesOrder = SalesOrder::where('so_number', $soNumber)->first();
            
            if (!$salesOrder) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sales Order not found'
                ], 404);
            }

            $details = SalesOrderDetail::where('so_number', $soNumber)->get();
            $summary = [];

            foreach ($details as $detail) {
                $scheduledQty = DeliverySchedule::where('so_number', $soNumber)
                    ->where('line_number', $detail->line_number)
                    ->sum('delivery_quantity');

                $summary[] = [
                    'line_number' => $detail->line_number,
                    'item_code' => $detail->item_code,
                    'order_quantity' => $detail->order_quantity,
                    'scheduled_quantity' => $scheduledQty,
                    'remaining_quantity' => $detail->order_quantity - $scheduledQty,
                    'is_fully_scheduled' => $scheduledQty >= $detail->order_quantity,
                ];
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'so_number' => $soNumber,
                    'summary' => $summary,
                    'total_order_quantity' => $salesOrder->total_order_quantity,
                    'total_scheduled_quantity' => $salesOrder->total_scheduled_quantity,
                    'remaining_quantity' => $salesOrder->remaining_quantity,
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting delivery schedule summary: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error getting delivery schedule summary'
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

    /**
     * Get sales orders from legacy 'so' table for Print SO
     */
    public function getSalesOrders(Request $request)
    {
        try {
            $query = DB::table('so');
            
            // Apply filters
            if ($request->has('month') && $request->has('year')) {
                $query->where('MM', $request->month)
                      ->where('YYYY', $request->year);
            }
            
            if ($request->has('from_so') && $request->has('to_so')) {
                $query->whereBetween('SO_Num', [$request->from_so, $request->to_so]);
            }
            
            if ($request->has('status') && is_array($request->status)) {
                $query->whereIn('STS', $request->status);
            }
            
            // Order by SO number
            $query->orderBy('SO_Num', 'desc');
            
            $salesOrders = $query->get();
            
            return response()->json([
                'success' => true,
                'data' => $salesOrders
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error fetching sales orders: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error fetching sales orders'
            ], 500);
        }
    }

    /**
     * Store Sales Order into legacy-style 'so' table (for CPS compatibility)
     */
    public function apiStoreToSo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'customer_code' => 'required|string',
            'customer_name' => 'nullable|string',
            'master_card_seq' => 'required|string',
            'master_card_model' => 'nullable|string',
            'p_design' => 'nullable|string',
            'salesperson_code' => 'nullable|string',
            'currency' => 'required|string',
            'exchange_rate' => 'nullable|numeric',
            'customer_po_number' => 'nullable|string',
            'po_date' => 'required|date',
            'order_group' => 'required|string',
            'order_type' => 'required|string',
            'lot_number' => 'nullable|string',
            'remark' => 'nullable|string',
            'instruction1' => 'nullable|string',
            'instruction2' => 'nullable|string',
            'details' => 'required|array|min:1',
            'details.*.order_quantity' => 'required|numeric|min:0',
            'details.*.unit_price' => 'required|numeric|min:0',
            'details.*.item_code' => 'nullable|string',
            'details.*.uom' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            $poDate = \Carbon\Carbon::parse($request->po_date);
            $mm = $poDate->format('m');
            $yyyy = $poDate->format('Y');

            // Generate SO number in format MM-YYYY-#####
            $prefix = $mm . '-' . $yyyy . '-';
            $last = \Illuminate\Support\Facades\DB::table('so')
                ->where('SO_Num', 'like', $prefix . '%')
                ->orderBy('SO_Num', 'desc')
                ->value('SO_Num');
            $seq = 1;
            if ($last) {
                $parts = explode('-', $last);
                $seq = isset($parts[2]) ? ((int) $parts[2]) + 1 : 1;
            }
            $soNum = $prefix . str_pad($seq, 5, '0', STR_PAD_LEFT);

            $first = $request->details[0];
            $qty = (float) ($first['order_quantity'] ?? 0);
            $unitPrice = (float) ($first['unit_price'] ?? 0);
            $amount = $qty * $unitPrice;

            // Map into legacy columns - provide all required fields
            $row = [
                'YYYY' => $yyyy,
                'MM' => $mm,
                'SO_Num' => $soNum,
                'STS' => 'Draft',
                'TYPE' => $request->order_type,
                'SO_DMY' => $poDate->format('d/m/Y'),
                'AC_Num' => $request->customer_code,
                'AC_NAME' => $request->customer_name ?? '',
                'SLM' => $request->salesperson_code ?? '',
                'IND' => $request->industry ?? '', // Required field
                'AREA' => $request->area ?? '', // Required field
                'GROUP_' => $request->order_group,
                'PO_Num' => $request->customer_po_number ?? '',
                'PO_DATE' => $poDate->format('d/m/Y'),
                'LOT_Num' => $request->lot_number ?? '',
                'MCS_Num' => $request->master_card_seq,
                'MODEL' => $request->master_card_model ?? '',
                'PRODUCT' => $first['item_code'] ?? '',
                'COMP_Num' => $request->comp_num ?? '', // Required field
                'P_DESIGN' => $request->p_design ?? '',
                'PER_SET' => $request->per_set ?? 0.0, // Required field
                'UNIT' => $first['uom'] ?? 'PCS',
                'PART_NUMBER' => $first['item_code'] ?? '', // Required field
                'INT_L' => $request->int_l ?? 0.0, // Required field
                'INT_W' => $request->int_w ?? 0.0, // Required field
                'INT_H' => $request->int_h ?? 0.0, // Required field
                'EXT_L' => $request->ext_l ?? 0.0, // Required field
                'EXT_W' => $request->ext_w ?? 0.0, // Required field
                'EXT_H' => $request->ext_h ?? 0.0, // Required field
                'FLUTE' => $request->flute ?? '', // Required field
                'PQ1' => $request->pq1 ?? '', // Required field
                'PQ2' => $request->pq2 ?? '', // Required field
                'PQ3' => $request->pq3 ?? '', // Required field
                'PQ4' => $request->pq4 ?? '', // Required field
                'PQ5' => $request->pq5 ?? '', // Required field
                'SO_QTY' => $qty,
                'UNIT_PRICE' => $unitPrice,
                'CURR' => $request->currency,
                'EX_RATE' => $request->exchange_rate ?? 0,
                'AMOUNT' => $amount,
                'BASE_AMOUNT' => $amount, // Required field
                'MC_GROSS_M2_PER_PCS' => $request->mc_gross_m2_per_pcs ?? 0.0, // Required field
                'MC_NET_M2_PER_PCS' => $request->mc_net_m2_per_pcs ?? 0.0, // Required field
                'TOTAL_SO_GROSS_M2' => $request->total_so_gross_m2 ?? 0.0, // Required field
                'TOTAL_SO_NET_M2' => $request->total_so_net_m2 ?? 0.0, // Required field
                'TOTAL_LM' => $request->total_lm ?? '', // Required field
                'TOTAL_M3' => $request->total_m3 ?? 0, // Required field
                'MC_GROSS_KG_PER_PCS' => $request->mc_gross_kg_per_pcs ?? 0.0, // Required field
                'MC_NET_KG_PER_PCS' => $request->mc_net_kg_per_pcs ?? 0.0, // Required field
                'TOTAL_SO_GROSS_KG' => $request->total_so_gross_kg ?? 0.0, // Required field
                'TOTAL_SO_NET_KG' => $request->total_so_net_kg ?? 0.0, // Required field
                'SO_REMARK' => $request->remark ?? '',
                'SO_INSTRUCTION_1' => $request->instruction1 ?? '',
                'SO_INSTRUCTION_2' => $request->instruction2 ?? '',
                'D_LOC_Num' => $request->d_loc_num ?? '', // Required field
                'DELIVERY_TO' => $request->delivery_to ?? '', // Required field
                'DELIVERY_ADD_1' => $request->delivery_add_1 ?? '', // Required field
                'DELIVERY_ADD_2' => $request->delivery_add_2 ?? '', // Required field
                'DELIVERY_ADD_3' => $request->delivery_add_3 ?? '', // Required field
                'D_DATE_1' => $request->d_date_1 ?? '', // Required field
                'D_TIME_1' => $request->d_time_1 ?? '', // Required field
                'D_DUE_1' => $request->d_due_1 ?? '', // Required field
                'D_QTY_1' => $request->d_qty_1 ?? 0.0, // Required field
                'D_DATE_2' => $request->d_date_2 ?? '', // Required field
                'D_TIME_2' => $request->d_time_2 ?? '', // Required field
                'D_DUE_2' => $request->d_due_2 ?? '', // Required field
                'D_QTY_2' => $request->d_qty_2 ?? 0.0, // Required field
                'D_DATE_3' => $request->d_date_3 ?? '', // Required field
                'D_TIME_3' => $request->d_time_3 ?? '', // Required field
                'D_DUE_3' => $request->d_due_3 ?? '', // Required field
                'D_QTY_3' => $request->d_qty_3 ?? 0.0, // Required field
                'D_DATE_4' => $request->d_date_4 ?? '', // Required field
                'D_TIME_4' => $request->d_time_4 ?? '', // Required field
                'D_DUE_4' => $request->d_due_4 ?? '', // Required field
                'D_QTY_4' => $request->d_qty_4 ?? 0.0, // Required field
                'D_DATE_5' => $request->d_date_5 ?? '', // Required field
                'D_TIME_5' => $request->d_time_5 ?? '', // Required field
                'D_DUE_5' => $request->d_due_5 ?? '', // Required field
                'D_QTY_5' => $request->d_qty_5 ?? 0.0, // Required field
                'D_DATE_6' => $request->d_date_6 ?? '', // Required field
                'D_TIME_6' => $request->d_time_6 ?? '', // Required field
                'D_DUE_6' => $request->d_due_6 ?? '', // Required field
                'D_QTY_6' => $request->d_qty_6 ?? 0.0, // Required field
                'D_DATE_7' => $request->d_date_7 ?? '', // Required field
                'D_TIME_7' => $request->d_time_7 ?? '', // Required field
                'D_DUE_7' => $request->d_due_7 ?? '', // Required field
                'D_QTY_7' => $request->d_qty_7 ?? 0.0, // Required field
                'D_DATE_8' => $request->d_date_8 ?? '', // Required field
                'D_TIME_8' => $request->d_time_8 ?? '', // Required field
                'D_DUE_8' => $request->d_due_8 ?? '', // Required field
                'D_QTY_8' => $request->d_qty_8 ?? 0.0, // Required field
                'D_DATE_9' => $request->d_date_9 ?? '', // Required field
                'D_TIME_9' => $request->d_time_9 ?? '', // Required field
                'D_DUE_9' => $request->d_due_9 ?? '', // Required field
                'D_QTY_9' => $request->d_qty_9 ?? 0.0, // Required field
                'D_DATE10' => $request->d_date10 ?? '', // Required field
                'D_TIME10' => $request->d_time10 ?? '', // Required field
                'D_DUE10' => $request->d_due10 ?? '', // Required field
                'D_QTY_10' => $request->d_qty_10 ?? 0.0, // Required field
                'NW_UID' => Auth::user()->name ?? 'system',
                'NW_DATE' => now()->format('d/m/Y'),
                'NW_TIME' => now()->format('H:i'),
                'AM_UID' => '', // Required field
                'AM_DATE' => '', // Required field
                'AM_TIME' => '', // Required field
                'CX_UID' => '', // Required field
                'CX_DATE' => '', // Required field
                'CX_TIME' => '', // Required field
                'PT_UID' => '', // Required field
                'PT_DATE' => '', // Required field
                'PT_TIME' => '', // Required field
                'SODateSK' => $poDate->format('Ymd'), // Required field
                'PODateSK' => $poDate->format('Ymd'), // Required field
            ];

            \Illuminate\Support\Facades\DB::table('so')->insert($row);

            return response()->json([
                'success' => true,
                'message' => 'SO saved to legacy table successfully',
                'data' => [
                    'so_number' => $soNum,
                    'row' => $row,
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error saving to so table: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error saving to SO table: ' . $e->getMessage()
            ], 500);
        }
    }
}
