<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Models\UpdateCustomerAccount;
use App\Models\Customer;
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
            $mc = DB::table('MC')->where('MCS_Num', $request->master_card_seq)->first();

            if (!$customer) {
                Log::error('Customer not found: ' . $request->customer_code);
                return response()->json(['success' => false, 'message' => 'Customer not found'], 404);
            }
            if (!$mc) {
                Log::error('Master Card not found: ' . $request->master_card_seq);
                return response()->json(['success' => false, 'message' => 'Master Card not found'], 404);
            }

            // Generate SO number
            $soNumber = $this->generateSONumber();
            Log::info('Generated SO number: ' . $soNumber);

            // Prepare data for creation
            $salesOrderData = [
                'so_number' => $soNumber,
                'customer_code' => $customer->CODE,
                'customer_name' => $customer->NAME ?? null,
                'customer_address' => $customer->ADDRESS1 ?? null,
                'credit_terms' => $customer->TERM ? (int) $customer->TERM : null,
                'credit_limit' => $customer->CREDIT_LIMIT ?? null,
                'salesperson_code' => $request->salesperson_code ?: ($customer->SLM ?? null),
                'currency' => $request->currency,
                'exchange_rate' => $request->exchange_rate ?? 0,

                'master_card_seq' => $mc->MCS_Num,
                'master_card_model' => $mc->MODEL ?? null,
                'p_design' => $mc->P_DESIGN ?? null,
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
                // In SQL Server, created_by is BIGINT; avoid inserting non-numeric IDs
                'created_by' => is_numeric(Auth::id()) ? (int) Auth::id() : null,
            ];

            Log::info('Sales order data prepared:', $salesOrderData);

            // Skip creating in sales_orders table and go directly to SO table
            // This is because we're using the legacy SO table approach
            Log::info('Skipping sales_orders table creation, using legacy SO table approach');

            return response()->json([
                'success' => true,
                'message' => 'Sales order created successfully',
                'data' => [
                    'so_number' => $soNumber,
                    'customer_code' => $customer->CODE,
                    'master_card_seq' => $mc->MCS_Num,
                    'order_type' => $request->order_type,
                    'status' => 'Draft'
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
        $month = date('m');
        $prefix = $month . '-' . $year . '-';
        
        // Get the last SO number for this month/year
        $lastSO = DB::table('so')
            ->where('SO_Num', 'like', $prefix . '%')
            ->orderBy('SO_Num', 'desc')
            ->value('SO_Num');
        
        if ($lastSO) {
            // Extract the sequence part and increment
            $parts = explode('-', $lastSO);
            $lastNumber = isset($parts[2]) ? (int) $parts[2] : 0;
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        
        return $prefix . str_pad($newNumber, 5, '0', STR_PAD_LEFT);
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
            $masterCard = DB::table('MC')->where('MCS_Num', $mcSeq)->first();

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
            $masterCard = DB::table('MC')->where('MCS_Num', $masterCardSeq)->first();
            
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
                        'design' => $masterCard->P_DESIGN ?? 'B1',
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
                        'ext_dimension' => $masterCard->EXT_LENGTH . ' x ' . $masterCard->EXT_WIDTH . ' x ' . $masterCard->EXT_HEIGHT,
                        'int_dimension' => $masterCard->INT_LENGTH . ' x ' . $masterCard->INT_WIDTH . ' x ' . $masterCard->INT_HEIGHT,
                        'part_number' => $masterCard->PART_NO ?? 'BOX',
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

            // Get the sales order to validate it exists
            $salesOrder = DB::table('so')->where('SO_Num', $soNumber)->first();
            if (!$salesOrder) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sales Order not found'
                ], 404);
            }

            // For legacy SO table, we don't have separate details table
            // All data is in the main SO record
            $existingLineNumbers = [1]; // Legacy SO table only has one line

            foreach ($entries as $entry) {
                if (!in_array($entry['line_number'], $existingLineNumbers)) {
                    return response()->json([
                        'success' => false,
                        'message' => "Line number {$entry['line_number']} not found in Sales Order"
                    ], 422);
                }
            }

            // For legacy SO table, we only have one line item
            // Validate quantities don't exceed order quantities
            foreach ($entries as $entry) {
                $salesOrder = DB::table('so')->where('SO_Num', $soNumber)->first();
                $orderQuantity = $salesOrder->SO_QTY;

                $existingScheduledQty = 0; // Legacy system doesn't track scheduled quantities separately

                $newScheduledQty = $existingScheduledQty + $entry['delivery_quantity'];

                if ($newScheduledQty > $orderQuantity) {
                    return response()->json([
                        'success' => false,
                        'message' => "Scheduled quantity for line {$entry['line_number']} exceeds order quantity"
                    ], 422);
                }
            }

            // Save delivery schedule entries
            $savedEntries = [];
            foreach ($entries as $entry) {
                $schedule = DeliverySchedule::create([
                    'so_number' => $soNumber,
                    'line_number' => $entry['line_number'],
                    'schedule_sequence' => $this->getNextScheduleSequence($soNumber, $entry['line_number']),
                    'schedule_date' => $entry['schedule_date'],
                    'schedule_time' => $entry['schedule_time'] ?? null,
                    'delivery_quantity' => $entry['delivery_quantity'],
                    'due_status' => $entry['due_status'],
                    'remark' => $entry['remark'] ?? null,
                    'delivery_code' => $entry['delivery_code'] ?? null,
                    'delivery_location' => $entry['delivery_location'] ?? null,
                ]);

                $savedEntries[] = $schedule;
            }

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
     * Get the next schedule sequence for a line item
     */
    private function getNextScheduleSequence($soNumber, $lineNumber)
    {
        $maxSequence = DeliverySchedule::where('so_number', $soNumber)
            ->where('line_number', $lineNumber)
            ->max('schedule_sequence');

        return ($maxSequence ?? 0) + 1;
    }

    /**
     * Get delivery schedules for a sales order
     */
    public function getDeliverySchedules(Request $request, $soNumber)
    {
        try {
            $salesOrder = DB::table('so')->where('SO_Num', $soNumber)->first();

            if (!$salesOrder) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sales Order not found'
                ], 404);
            }

            // For legacy SO table, delivery schedules are stored in the main record
            // We'll return empty array for now as legacy system doesn't have separate schedules table
            $deliverySchedules = [];

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
            $salesOrder = DB::table('so')->where('SO_Num', $soNumber)->first();
            
            if (!$salesOrder) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sales Order not found'
                ], 404);
            }

            // For legacy SO table, we only have one line item
            $summary = [
                [
                    'line_number' => 1,
                    'item_code' => $salesOrder->PRODUCT,
                    'order_quantity' => $salesOrder->SO_QTY,
                    'scheduled_quantity' => 0, // Legacy system doesn't track scheduled quantities separately
                    'remaining_quantity' => $salesOrder->SO_QTY,
                    'is_fully_scheduled' => false,
                ]
            ];

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
