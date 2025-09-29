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
            $customer = UpdateCustomerAccount::where('customer_code', $request->customer_code)->first();
            $mc = MasterCard::where('mc_seq', $request->master_card_seq)->first();

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
                // In SQL Server, created_by is BIGINT; avoid inserting non-numeric IDs
                'created_by' => is_numeric(Auth::id()) ? (int) Auth::id() : null,
            ];

            Log::info('Sales order data prepared:', $salesOrderData);

            // Create the sales order
            $salesOrder = SalesOrder::create($salesOrderData);

            // Create sales order details
            if ($request->has('details') && is_array($request->details)) {
                foreach ($request->details as $detail) {
                    SalesOrderDetail::create([
                        'so_number' => $soNumber,
                        'line_number' => $detail['line_number'],
                        'item_code' => $detail['item_code'],
                        'item_description' => $detail['item_description'] ?? null,
                        'order_quantity' => $detail['order_quantity'],
                        'unit_price' => $detail['unit_price'],
                        'line_total' => $detail['order_quantity'] * $detail['unit_price'],
                        'uom' => $detail['uom'] ?? 'PCS',
                        'remark' => $detail['remark'] ?? null,
                    ]);
                }
            }

            Log::info('Sales order created successfully with ID: ' . $salesOrder->id);

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
            $salesOrder = SalesOrder::where('so_number', $soNumber)->first();
            if (!$salesOrder) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sales Order not found'
                ], 404);
            }

            // Validate that all line numbers exist in sales order details
            $existingLineNumbers = SalesOrderDetail::where('so_number', $soNumber)
                ->pluck('line_number')
                ->toArray();

            foreach ($entries as $entry) {
                if (!in_array($entry['line_number'], $existingLineNumbers)) {
                    return response()->json([
                        'success' => false,
                        'message' => "Line number {$entry['line_number']} not found in Sales Order"
                    ], 422);
                }
            }

            // Validate quantities don't exceed order quantities
            foreach ($entries as $entry) {
                $lineDetail = SalesOrderDetail::where('so_number', $soNumber)
                    ->where('line_number', $entry['line_number'])
                    ->first();

                $existingScheduledQty = DeliverySchedule::where('so_number', $soNumber)
                    ->where('line_number', $entry['line_number'])
                    ->sum('delivery_quantity');

                $newScheduledQty = $existingScheduledQty + $entry['delivery_quantity'];

                if ($newScheduledQty > $lineDetail->order_quantity) {
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
}
