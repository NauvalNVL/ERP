<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Customer;
use App\Models\Salesperson;

class SalesOrderController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'customer_code' => 'required|string',
            'master_card_seq' => 'required|string',
            'order_mode' => 'nullable|string',
            'product_code' => 'nullable|string',
            'salesperson_code' => 'nullable|string',
            'currency' => 'nullable|string',
            'exchange_rate' => 'nullable|numeric',
            'customer_po_number' => 'nullable|string',
            'po_date' => 'required|string',
            'order_group' => 'nullable|string',
            'order_type' => 'nullable|string',
            // Accept any type for sales_tax; we'll normalize below
            'sales_tax' => 'nullable',
            'lot_number' => 'nullable|string',
            'remark' => 'nullable|string',
            'instruction1' => 'nullable|string',
            'instruction2' => 'nullable|string',
            'set_quantity' => 'nullable|string',
            'details' => 'required|array|min:1',
            'details.*.line_number' => 'required|integer',
            'details.*.item_code' => 'required|string',
            'details.*.order_quantity' => 'required|numeric',
            'details.*.unit_price' => 'required|numeric',
            'details.*.uom' => 'required|string',
        ]);

        // Normalize sales_tax to 'Y' or 'N'
        $rawSalesTax = $request->input('sales_tax');
        $salesTax = 'N';
        if (is_bool($rawSalesTax)) {
            $salesTax = $rawSalesTax ? 'Y' : 'N';
        } elseif (is_numeric($rawSalesTax)) {
            $salesTax = ((int) $rawSalesTax) === 1 ? 'Y' : 'N';
        } elseif (is_string($rawSalesTax)) {
            $upper = strtoupper(trim($rawSalesTax));
            $salesTax = in_array($upper, ['Y', 'YES', 'Y-YES', 'TRUE'], true) ? 'Y' : 'N';
        }

        // Generate SO number in format: MM-YYYY-XXXXX (matching the filter format)
        $month = date('m');
        $year = date('Y');
        
        // Get the ACTUAL last sequence number by checking the max sequence in SO_Num column
        // SQL Server compatible: Use CHARINDEX and REVERSE for extracting last part
        $lastSO = DB::table('so')
            ->where('MM', $month)
            ->where('YYYY', $year)
            ->orderByRaw('CAST(RIGHT(SO_Num, 5) AS INT) DESC')
            ->first();
        
        $sequence = 1;
        if ($lastSO && $lastSO->SO_Num) {
            // Extract last 5 characters (sequence) from format MM-YYYY-XXXXX
            $lastSequence = substr($lastSO->SO_Num, -5);
            $sequence = intval($lastSequence) + 1;
        }
        
        // Ensure uniqueness by checking if SO_Num already exists
        do {
            $seqPart = str_pad((string) $sequence, 5, '0', STR_PAD_LEFT);
            $soNumber = $month . '-' . $year . '-' . $seqPart;
            $exists = DB::table('so')->where('SO_Num', $soNumber)->exists();
            if ($exists) {
                $sequence++;
            }
        } while ($exists);

        // Fetch customer data from database
        $customerData = DB::table('CUSTOMER')->where('CODE', $validated['customer_code'])->first();
        $customerName = $customerData ? $customerData->NAME : '';

        // Fetch master card data from database
        $masterCardData = DB::table('MC')
            ->where('MCS_Num', $validated['master_card_seq'])
            ->where('AC_NUM', $validated['customer_code'])
            ->first();
        $mcModel = $masterCardData ? $masterCardData->MODEL : '';
        $mcPDesign = $masterCardData ? $masterCardData->P_DESIGN : '';
        $mcPartNumber = $masterCardData ? $masterCardData->PART_NO : '';
        
        // Extract dimensions and flute data from MC table
        $intL = $masterCardData ? (float)($masterCardData->INT_LENGTH ?? 0) : 0;
        $intW = $masterCardData ? (float)($masterCardData->INT_WIDTH ?? 0) : 0;
        $intH = $masterCardData ? (float)($masterCardData->INT_HEIGHT ?? 0) : 0;
        $extL = $masterCardData ? (float)($masterCardData->EXT_LENGTH ?? 0) : 0;
        $extW = $masterCardData ? (float)($masterCardData->EXT_WIDTH ?? 0) : 0;
        $extH = $masterCardData ? (float)($masterCardData->EXT_HEIGHT ?? 0) : 0;
        $flute = $masterCardData ? ($masterCardData->FLUTE ?? '') : '';
        
        // Extract paper quality (PQ1-PQ5) from MC table (SO_PQ1-SO_PQ5)
        $pq1 = $masterCardData ? ($masterCardData->SO_PQ1 ?? '') : '';
        $pq2 = $masterCardData ? ($masterCardData->SO_PQ2 ?? '') : '';
        $pq3 = $masterCardData ? ($masterCardData->SO_PQ3 ?? '') : '';
        $pq4 = $masterCardData ? ($masterCardData->SO_PQ4 ?? '') : '';
        $pq5 = $masterCardData ? ($masterCardData->SO_PQ5 ?? '') : '';

        // Prepare minimal legacy SO row so schedule updates can find it
        $qty = (float) ($validated['details'][0]['order_quantity'] ?? 0);
        $price = (float) ($validated['details'][0]['unit_price'] ?? 0);
        $amount = $qty * $price;
        
        // Use master card part number, fallback to request data
        $partNumber = $mcPartNumber ?: (string) ($request->input('part_number') ?? $request->input('partNo') ?? $request->input('master_card_model') ?? '');

        $nowDate = date('Y-m-d');
        $nowTime = date('H:i');

        $base = [
            'YYYY' => date('Y'),
            'MM' => date('m'),
            'SO_Num' => $soNumber,
            'STS' => 'Outstanding',
            'TYPE' => (string) ($validated['order_type'] ?? 'S1'),
            'SO_DMY' => $nowDate,
            'AC_Num' => (string) $validated['customer_code'],
            'AC_NAME' => $customerName,
            'SLM' => (string) ($validated['salesperson_code'] ?? ''),
            'IND' => '',
            'AREA' => '',
            'GROUP_' => (string) ($validated['order_group'] ?? 'Sales'),
            'PO_Num' => (string) ($validated['customer_po_number'] ?? ''),
            'PO_DATE' => (string) ($validated['po_date'] ?? $nowDate),
            'LOT_Num' => (string) ($validated['lot_number'] ?? ''),
            'MCS_Num' => (string) $validated['master_card_seq'],
            'MODEL' => $mcModel,
            'PRODUCT' => (string) ($validated['details'][0]['item_code'] ?? ''),
            'COMP_Num' => $masterCardData ? (string) ($masterCardData->COMP ?? '') : '',
            'P_DESIGN' => $mcPDesign,
            'PER_SET' => 1,
            'UNIT' => (string) ($validated['details'][0]['uom'] ?? ''),
            'PART_NUMBER' => $partNumber,
            // Populate dimensions from MC table
            'INT_L' => $intL,
            'INT_W' => $intW,
            'INT_H' => $intH,
            'EXT_L' => $extL,
            'EXT_W' => $extW,
            'EXT_H' => $extH,
            // Populate flute and paper quality from MC table
            'FLUTE' => $flute,
            'PQ1' => $pq1,
            'PQ2' => $pq2,
            'PQ3' => $pq3,
            'PQ4' => $pq4,
            'PQ5' => $pq5,
            'SO_QTY' => $qty,
            'UNIT_PRICE' => $price,
            'CURR' => (string) ($validated['currency'] ?? ''),
            'EX_RATE' => (float) ($validated['exchange_rate'] ?? 0),
            'AMOUNT' => $amount,
            'BASE_AMOUNT' => $amount,
            'MC_GROSS_M2_PER_PCS' => 0,
            'MC_NET_M2_PER_PCS' => 0,
            'TOTAL_SO_GROSS_M2' => 0,
            'TOTAL_SO_NET_M2' => 0,
            'TOTAL_LM' => '0',
            'TOTAL_M3' => 0,
            'MC_GROSS_KG_PER_PCS' => 0,
            'MC_NET_KG_PER_PCS' => 0,
            'TOTAL_SO_GROSS_KG' => 0,
            'TOTAL_SO_NET_KG' => 0,
            'SO_REMARK' => (string) ($validated['remark'] ?? ''),
            'SO_INSTRUCTION_1' => (string) ($validated['instruction1'] ?? ''),
            'SO_INSTRUCTION_2' => (string) ($validated['instruction2'] ?? ''),
            'D_LOC_Num' => '',
            'DELIVERY_TO' => '',
            'DELIVERY_ADD_1' => '',
            'DELIVERY_ADD_2' => '',
            'DELIVERY_ADD_3' => '',
            'D_DATE_1' => '', 'D_TIME_1' => '', 'D_DUE_1' => '', 'D_QTY_1' => 0,
            'D_DATE_2' => '', 'D_TIME_2' => '', 'D_DUE_2' => '', 'D_QTY_2' => 0,
            'D_DATE_3' => '', 'D_TIME_3' => '', 'D_DUE_3' => '', 'D_QTY_3' => 0,
            'D_DATE_4' => '', 'D_TIME_4' => '', 'D_DUE_4' => '', 'D_QTY_4' => 0,
            'D_DATE_5' => '', 'D_TIME_5' => '', 'D_DUE_5' => '', 'D_QTY_5' => 0,
            'D_DATE_6' => '', 'D_TIME_6' => '', 'D_DUE_6' => '', 'D_QTY_6' => 0,
            'D_DATE_7' => '', 'D_TIME_7' => '', 'D_DUE_7' => '', 'D_QTY_7' => 0,
            'D_DATE_8' => '', 'D_TIME_8' => '', 'D_DUE_8' => '', 'D_QTY_8' => 0,
            'D_DATE_9' => '', 'D_TIME_9' => '', 'D_DUE_9' => '', 'D_QTY_9' => 0,
            'D_DATE10' => '', 'D_TIME10' => '', 'D_DUE10' => '', 'D_QTY_10' => 0,
            'NW_UID' => 'System',
            'NW_DATE' => $nowDate,
            'NW_TIME' => $nowTime,
            'AM_UID' => '', 'AM_DATE' => '', 'AM_TIME' => '',
            'CX_UID' => '', 'CX_DATE' => '', 'CX_TIME' => '',
            'PT_UID' => '', 'PT_DATE' => '', 'PT_TIME' => '',
            'SODateSK' => 0,
            'PODateSK' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // Always INSERT new record, NEVER update existing
        // This ensures each sales order is unique even for same customer and master card
        DB::table('so')->insert($base);
        
        Log::info('New sales order created', [
            'so_number' => $soNumber,
            'customer_code' => $validated['customer_code'],
            'master_card_seq' => $validated['master_card_seq']
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Sales order created successfully',
            'data' => [
                'so_number' => $soNumber,
                'sales_tax' => $salesTax
            ]
        ]);
    }

    public function getMasterCard(string $mcSeq): JsonResponse
    {
        return response()->json(['message' => 'getMasterCard not yet implemented', 'mcSeq' => $mcSeq], 200);
    }

    public function getProductDesignData(string $masterCardSeq): JsonResponse
    {
        return response()->json(['message' => 'getProductDesignData not yet implemented', 'masterCardSeq' => $masterCardSeq], 200);
    }

    public function getSalesperson(string $salespersonCode): JsonResponse
    {
        $salesperson = Salesperson::where('code', $salespersonCode)->first();
        if (!$salesperson) {
            return response()->json([
                'success' => false,
                'message' => 'Salesperson not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'code' => $salesperson->code,
                'name' => $salesperson->name,
                'is_active' => (bool) $salesperson->is_active
            ]
        ]);
    }

    public function saveProductDesign(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'master_card_seq' => 'required|string',
            'items' => 'array',
            'dimensions' => 'array',
            'total_amount' => 'numeric',
            'total_gross_kg' => 'numeric',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Product design saved successfully',
            'data' => [
                'master_card_seq' => $validated['master_card_seq'],
                'items' => $request->input('items', []),
                'dimensions' => $request->input('dimensions', []),
                'total_amount' => (float) $request->input('total_amount', 0),
                'total_gross_kg' => (float) $request->input('total_gross_kg', 0),
            ]
        ], 200);
    }

    public function saveDeliverySchedule(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'so_number' => 'required|string',
            'entries' => 'required|array|min:1',
            'entries.*.line_number' => 'nullable|integer',
            'entries.*.schedule_date' => 'required|string',
            'entries.*.schedule_time' => 'nullable|string',
            'entries.*.delivery_quantity' => 'required|numeric|min:0.0000001',
            'entries.*.due_status' => 'required|string|in:ETD,ETA,TBA',
            'entries.*.remark' => 'nullable|string',
            'entries.*.delivery_code' => 'nullable|string',
            'entries.*.delivery_location' => 'nullable|string',
        ]);

        // Update existing SO with up to 10 schedule rows
        $soNumber = $validated['so_number'];
        $entries = $validated['entries'];

        $updates = [];
        $max = min(10, count($entries));
        for ($i = 0; $i < $max; $i++) {
            $index = $i + 1;
            $entry = $entries[$i];
            $dateCol = $index === 10 ? 'D_DATE10' : 'D_DATE_' . $index;
            $timeCol = $index === 10 ? 'D_TIME10' : 'D_TIME_' . $index;
            $dueCol  = $index === 10 ? 'D_DUE10'  : 'D_DUE_' . $index;
            $qtyCol  = $index === 10 ? 'D_QTY_10' : 'D_QTY_' . $index;
            $updates[$dateCol] = (string) ($entry['schedule_date'] ?? '');
            $updates[$timeCol] = (string) ($entry['schedule_time'] ?? '');
            $updates[$dueCol]  = (string) ($entry['due_status'] ?? '');
            $updates[$qtyCol]  = (float) ($entry['delivery_quantity'] ?? 0);
        }

        // Also update delivery location if provided on first entry
        if (!empty($entries[0]['delivery_location'])) {
            $updates['DELIVERY_TO'] = (string) $entries[0]['delivery_location'];
        }
        if (!empty($entries[0]['delivery_code'])) {
            $updates['D_LOC_Num'] = (string) $entries[0]['delivery_code'];
        }

        $affected = DB::table('so')->where('SO_Num', $soNumber)->update($updates);

        if ($affected === 0) {
            return response()->json([
                'success' => false,
                'message' => 'Sales Order not found for updating schedule'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Delivery schedule saved successfully',
            'data' => [
                'so_number' => $soNumber,
                'entries' => $entries
            ]
        ]);
    }

    public function getDeliverySchedules(string $soNumber): JsonResponse
    {
        // Clean any output buffers to prevent BOM issues
        while (ob_get_level()) {
            ob_end_clean();
        }
        
        // Get sales order from SO table
        $so = DB::table('so')->where('SO_Num', $soNumber)->first();
        
        if (!$so) {
            return response()->json([
                'success' => false,
                'message' => 'Sales Order not found'
            ], 404);
        }

        // Extract delivery schedules from D_DATE_1..10, D_QTY_1..10, etc.
        $schedules = [];
        for ($i = 1; $i <= 10; $i++) {
            $dateCol = $i === 10 ? 'D_DATE10' : 'D_DATE_' . $i;
            $timeCol = $i === 10 ? 'D_TIME10' : 'D_TIME_' . $i;
            $dueCol  = $i === 10 ? 'D_DUE10'  : 'D_DUE_' . $i;
            $qtyCol  = $i === 10 ? 'D_QTY_10' : 'D_QTY_' . $i;
            
            $date = $so->$dateCol ?? '';
            $time = $so->$timeCol ?? '';
            $due = $so->$dueCol ?? '';
            $qty = $so->$qtyCol ?? 0;
            
            if ($date || $qty > 0) {
                $schedules[] = [
                    'schedule_date' => $date,
                    'schedule_time' => $time,
                    'delivery_quantity' => (float) $qty,
                    'due_status' => $due,
                    'remark' => ''
                ];
            }
        }

        // Get customer address
        $customer = DB::table('CUSTOMER')->where('CODE', $so->AC_Num)->first();
        $customerAddress = '';
        if ($customer) {
            $addressParts = array_filter([
                $customer->ADDRESS1 ?? '',
                $customer->ADDRESS2 ?? '',
                $customer->ADDRESS3 ?? ''
            ]);
            $customerAddress = implode(', ', $addressParts);
        }

        // Get delivery address
        $deliveryAddress = '';
        if ($so->DELIVERY_TO) {
            $addressParts = array_filter([
                $so->DELIVERY_ADD_1 ?? '',
                $so->DELIVERY_ADD_2 ?? '',
                $so->DELIVERY_ADD_3 ?? ''
            ]);
            $deliveryAddress = implode(', ', $addressParts);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'sales_order' => [
                    'so_number' => $so->SO_Num,
                    'customer_code' => $so->AC_Num,
                    'customer_name' => $so->AC_NAME,
                    'customer_address' => $customerAddress,
                    'customer_po_number' => $so->PO_Num,
                    'po_date' => $so->PO_DATE,
                    'master_card_seq' => $so->MCS_Num,
                    'master_card_model' => $so->MODEL,
                    'part_number' => $so->PART_NUMBER,
                    'p_design' => $so->P_DESIGN,
                    'currency' => $so->CURR,
                    'credit_terms' => '',
                    'remark' => $so->SO_REMARK,
                    'instruction1' => $so->SO_INSTRUCTION_1,
                    'instruction2' => $so->SO_INSTRUCTION_2,
                    'status' => $so->STS,
                    'delivery_location' => $so->DELIVERY_TO,
                    'delivery_address' => $deliveryAddress,
                    'details' => [[
                        'item_code' => $so->PRODUCT,
                        'item_description' => $so->MODEL . ' - ' . $so->P_DESIGN,
                        'order_quantity' => (float) $so->SO_QTY,
                        'unit_price' => (float) $so->UNIT_PRICE,
                        'uom' => $so->UNIT,
                        'flute' => $so->FLUTE,
                        'paper_quality_1' => $so->PQ1,
                        'paper_quality_2' => $so->PQ2,
                        'paper_quality_3' => $so->PQ3,
                        'dimensions' => [
                            'int_l' => (float) $so->INT_L,
                            'int_w' => (float) $so->INT_W,
                            'int_h' => (float) $so->INT_H,
                            'ext_l' => (float) $so->EXT_L,
                            'ext_w' => (float) $so->EXT_W,
                            'ext_h' => (float) $so->EXT_H,
                        ],
                        'measurement' => $so->P_DESIGN,
                        'roll_size' => '',
                        'gross_kg' => (float) $so->MC_GROSS_KG_PER_PCS,
                        'price_per_m2' => 0,
                        'ppn_percentage' => 0,
                        'line_total' => (float) $so->AMOUNT
                    ]]
                ],
                'delivery_schedules' => $schedules
            ]
        ], 200, ['Content-Type' => 'application/json; charset=utf-8']);
    }

    public function getDeliveryScheduleSummary(string $soNumber): JsonResponse
    {
        return response()->json(['message' => 'getDeliveryScheduleSummary not yet implemented', 'soNumber' => $soNumber], 200);
    }

    public function printLog(Request $request): JsonResponse
    {
        return response()->json(['message' => 'printLog not yet implemented'], 200);
    }

    public function printJitTracking(Request $request): JsonResponse
    {
        return response()->json(['message' => 'printJitTracking not yet implemented'], 200);
    }

    public function getCustomer(string $code): JsonResponse
    {
        $customer = Customer::where('CODE', $code)->first();
        if (!$customer) {
            return response()->json([
                'success' => false,
                'message' => 'Customer not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'customer_code' => $customer->CODE,
                'customer_name' => $customer->NAME,
                'address' => $customer->ADDRESS1 ?? '',
                'address2' => $customer->ADDRESS2 ?? '',
                'address3' => $customer->ADDRESS3 ?? '',
                'salesperson_code' => $customer->SLM ?? '',
                'currency_code' => $customer->CURRENCY ?? ''
            ]
        ]);
    }

    public function apiStoreToSo(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'customer_code' => 'required|string',
            'customer_name' => 'required|string',
            'master_card_seq' => 'required|string',
            'master_card_model' => 'nullable|string',
            'p_design' => 'nullable|string',
            'salesperson_code' => 'nullable|string',
            'currency' => 'nullable|string',
            'exchange_rate' => 'nullable|numeric',
            'customer_po_number' => 'nullable|string',
            'po_date' => 'required|string',
            'order_group' => 'nullable|string',
            'order_type' => 'nullable|string',
            'lot_number' => 'nullable|string',
            'remark' => 'nullable|string',
            'instruction1' => 'nullable|string',
            'instruction2' => 'nullable|string',
            'details' => 'required|array|min:1',
            'details.*.order_quantity' => 'required|numeric',
            'details.*.unit_price' => 'required|numeric',
            'details.*.uom' => 'required|string',
            'details.*.item_code' => 'nullable|string',
            // Optional linkage to created SO number
            'so_number' => 'nullable|string'
        ]);

        // Generate SO number in format: MM-YYYY-XXXXX (if not provided)
        if (!isset($validated['so_number'])) {
            $month = date('m');
            $year = date('Y');
            
            // Get the ACTUAL last sequence number by checking the max sequence in SO_Num column
            // SQL Server compatible: Use RIGHT() function
            $lastSO = DB::table('so')
                ->where('MM', $month)
                ->where('YYYY', $year)
                ->orderByRaw('CAST(RIGHT(SO_Num, 5) AS INT) DESC')
                ->first();
            
            $sequence = 1;
            if ($lastSO && $lastSO->SO_Num) {
                // Extract last 5 characters (sequence) from format MM-YYYY-XXXXX
                $lastSequence = substr($lastSO->SO_Num, -5);
                $sequence = intval($lastSequence) + 1;
            }
            
            // Ensure uniqueness by checking if SO_Num already exists
            do {
                $seqPart = str_pad((string) $sequence, 5, '0', STR_PAD_LEFT);
                $soNumber = $month . '-' . $year . '-' . $seqPart;
                $exists = DB::table('so')->where('SO_Num', $soNumber)->exists();
                if ($exists) {
                    $sequence++;
                }
            } while ($exists);
        } else {
            $soNumber = $validated['so_number'];
        }
        
        $qty = (float) ($validated['details'][0]['order_quantity'] ?? 0);
        $price = (float) ($validated['details'][0]['unit_price'] ?? 0);
        $amount = $qty * $price;
        $partNumber = (string) ($request->input('part_number') ?? $request->input('partNo') ?? $request->input('master_card_model') ?? '');
        
        // Fetch master card data from MC table to populate dimensions and paper quality
        $masterCardData = DB::table('MC')
            ->where('MCS_Num', $validated['master_card_seq'])
            ->where('AC_NUM', $validated['customer_code'])
            ->first();
        
        // Extract dimensions and flute data from MC table
        $intL = $masterCardData ? (float)($masterCardData->INT_LENGTH ?? 0) : 0;
        $intW = $masterCardData ? (float)($masterCardData->INT_WIDTH ?? 0) : 0;
        $intH = $masterCardData ? (float)($masterCardData->INT_HEIGHT ?? 0) : 0;
        $extL = $masterCardData ? (float)($masterCardData->EXT_LENGTH ?? 0) : 0;
        $extW = $masterCardData ? (float)($masterCardData->EXT_WIDTH ?? 0) : 0;
        $extH = $masterCardData ? (float)($masterCardData->EXT_HEIGHT ?? 0) : 0;
        $flute = $masterCardData ? ($masterCardData->FLUTE ?? '') : '';
        
        // Extract paper quality (PQ1-PQ5) from MC table (SO_PQ1-SO_PQ5)
        $pq1 = $masterCardData ? ($masterCardData->SO_PQ1 ?? '') : '';
        $pq2 = $masterCardData ? ($masterCardData->SO_PQ2 ?? '') : '';
        $pq3 = $masterCardData ? ($masterCardData->SO_PQ3 ?? '') : '';
        $pq4 = $masterCardData ? ($masterCardData->SO_PQ4 ?? '') : '';
        $pq5 = $masterCardData ? ($masterCardData->SO_PQ5 ?? '') : '';

        $nowDate = date('Y-m-d');
        $nowTime = date('H:i');

        $base = [
            'YYYY' => date('Y'),
            'MM' => date('m'),
            'SO_Num' => $soNumber,
            'STS' => 'OPEN',
            'TYPE' => (string) ($validated['order_type'] ?? 'S1'),
            'SO_DMY' => $nowDate,
            'AC_Num' => (string) $validated['customer_code'],
            'AC_NAME' => (string) $validated['customer_name'],
            'SLM' => (string) ($validated['salesperson_code'] ?? ''),
            'IND' => '',
            'AREA' => '',
            'GROUP_' => (string) ($validated['order_group'] ?? 'Sales'),
            'PO_Num' => (string) ($validated['customer_po_number'] ?? ''),
            'PO_DATE' => (string) ($validated['po_date'] ?? $nowDate),
            'LOT_Num' => (string) ($validated['lot_number'] ?? ''),
            'MCS_Num' => (string) $validated['master_card_seq'],
            'MODEL' => (string) ($validated['master_card_model'] ?? ''),
            'PRODUCT' => (string) ($validated['details'][0]['item_code'] ?? ''),
            'COMP_Num' => '',
            'P_DESIGN' => (string) ($validated['p_design'] ?? ''),
            'PER_SET' => 1,
            'UNIT' => (string) ($validated['details'][0]['uom'] ?? ''),
            'PART_NUMBER' => $partNumber,
            // Populate dimensions from MC table
            'INT_L' => $intL,
            'INT_W' => $intW,
            'INT_H' => $intH,
            'EXT_L' => $extL,
            'EXT_W' => $extW,
            'EXT_H' => $extH,
            // Populate flute and paper quality from MC table
            'FLUTE' => $flute,
            'PQ1' => $pq1,
            'PQ2' => $pq2,
            'PQ3' => $pq3,
            'PQ4' => $pq4,
            'PQ5' => $pq5,
            'SO_QTY' => $qty,
            'UNIT_PRICE' => $price,
            'CURR' => (string) ($validated['currency'] ?? ''),
            'EX_RATE' => (float) ($validated['exchange_rate'] ?? 0),
            'AMOUNT' => $amount,
            'BASE_AMOUNT' => $amount,
            'MC_GROSS_M2_PER_PCS' => 0,
            'MC_NET_M2_PER_PCS' => 0,
            'TOTAL_SO_GROSS_M2' => 0,
            'TOTAL_SO_NET_M2' => 0,
            'TOTAL_LM' => '0',
            'TOTAL_M3' => 0,
            'MC_GROSS_KG_PER_PCS' => 0,
            'MC_NET_KG_PER_PCS' => 0,
            'TOTAL_SO_GROSS_KG' => 0,
            'TOTAL_SO_NET_KG' => 0,
            'SO_REMARK' => (string) ($validated['remark'] ?? ''),
            'SO_INSTRUCTION_1' => (string) ($validated['instruction1'] ?? ''),
            'SO_INSTRUCTION_2' => (string) ($validated['instruction2'] ?? ''),
            'D_LOC_Num' => '',
            'DELIVERY_TO' => '',
            'DELIVERY_ADD_1' => '',
            'DELIVERY_ADD_2' => '',
            'DELIVERY_ADD_3' => '',
            'D_DATE_1' => '', 'D_TIME_1' => '', 'D_DUE_1' => '', 'D_QTY_1' => 0,
            'D_DATE_2' => '', 'D_TIME_2' => '', 'D_DUE_2' => '', 'D_QTY_2' => 0,
            'D_DATE_3' => '', 'D_TIME_3' => '', 'D_DUE_3' => '', 'D_QTY_3' => 0,
            'D_DATE_4' => '', 'D_TIME_4' => '', 'D_DUE_4' => '', 'D_QTY_4' => 0,
            'D_DATE_5' => '', 'D_TIME_5' => '', 'D_DUE_5' => '', 'D_QTY_5' => 0,
            'D_DATE_6' => '', 'D_TIME_6' => '', 'D_DUE_6' => '', 'D_QTY_6' => 0,
            'D_DATE_7' => '', 'D_TIME_7' => '', 'D_DUE_7' => '', 'D_QTY_7' => 0,
            'D_DATE_8' => '', 'D_TIME_8' => '', 'D_DUE_8' => '', 'D_QTY_8' => 0,
            'D_DATE_9' => '', 'D_TIME_9' => '', 'D_DUE_9' => '', 'D_QTY_9' => 0,
            'D_DATE10' => '', 'D_TIME10' => '', 'D_DUE10' => '', 'D_QTY_10' => 0,
            'NW_UID' => 'System',
            'NW_DATE' => $nowDate,
            'NW_TIME' => $nowTime,
            'AM_UID' => '', 'AM_DATE' => '', 'AM_TIME' => '',
            'CX_UID' => '', 'CX_DATE' => '', 'CX_TIME' => '',
            'PT_UID' => '', 'PT_DATE' => '', 'PT_TIME' => '',
            'SODateSK' => 0,
            'PODateSK' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // Always INSERT new record, NEVER update existing
        // This ensures each sales order is unique even for same customer and master card
        DB::table('so')->insert($base);
        
        Log::info('Legacy SO record created', [
            'so_number' => $soNumber,
            'customer_code' => $validated['customer_code'],
            'master_card_seq' => $validated['master_card_seq']
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Legacy SO record saved',
            'data' => ['so_number' => $soNumber]
        ]);
    }

    public function getSalesOrders(Request $request): JsonResponse
    {
        Log::info('getSalesOrders called with params:', $request->all());
        
        // Clean output buffers
        while (ob_get_level()) {
            ob_end_clean();
        }
        
        try {
            $query = DB::table('so');
            
            // Filter by customer code (for Sales Order Table Modal)
            if ($request->has('customer_code') && !empty($request->customer_code)) {
                $query->where('AC_Num', $request->customer_code);
                Log::info('Filtering by customer:', ['customer_code' => $request->customer_code]);
            }

            // Filter by month and year
            if ($request->has('month') && !empty($request->month) && $request->month !== '0') {
                $month = str_pad($request->input('month'), 2, '0', STR_PAD_LEFT);
                $query->where('MM', $month);
                Log::info('Filtering by month:', ['month' => $month]);
            }
            
            if ($request->has('year') && !empty($request->year) && $request->year !== '0') {
                $year = $request->input('year');
                $query->where('YYYY', $year);
                Log::info('Filtering by year:', ['year' => $year]);
            }
            
            // Filter by sequence
            if ($request->has('sequence') && !empty($request->sequence) && $request->sequence !== '0') {
                // Extract sequence from SO_Num format MM-YYYY-XXXXX using SQL Server compatible RIGHT()
                $query->whereRaw("RIGHT(SO_Num, 5) = ?", [str_pad($request->sequence, 5, '0', STR_PAD_LEFT)]);
                Log::info('Filtering by sequence:', ['sequence' => $request->sequence]);
            }
            
            // Filter by SO number (partial match)
            if ($request->has('so_number') && !empty($request->so_number)) {
                $query->where('SO_Num', 'like', '%' . $request->so_number . '%');
                Log::info('Filtering by SO number:', ['so_number' => $request->so_number]);
            }

            // Filter by SO number range - Support both old and new format
            if ($request->has('from_so') && $request->has('to_so')) {
                $fromSO = $request->input('from_so');
                $toSO = $request->input('to_so');
                
                Log::info('Filtering SO range:', ['from_so' => $fromSO, 'to_so' => $toSO]);
                
                $query->where(function($q) use ($fromSO, $toSO) {
                    // New format: MM-YYYY-XXXXX
                    $q->whereBetween('SO_Num', [$fromSO, $toSO]);
                    
                    // Old format: SO-YYYYMMDD-XXXX (always include for backward compatibility)
                    $q->orWhere('SO_Num', 'like', 'SO-%');
                });
            }

            // Filter by status
            if ($request->has('status')) {
                $statuses = $request->input('status');
                Log::info('Filtering by status:', ['status' => $statuses]);
                if (is_array($statuses) && !empty($statuses)) {
                    $statusMap = [
                        'outstanding' => 'OPEN',
                        'partial' => 'PARTIAL',
                        'completed' => 'COMPLETED',
                        'closed' => 'CLOSED',
                        'cancelled' => 'CANCELLED'
                    ];
                    $dbStatuses = [];
                    foreach ($statuses as $status) {
                        if (isset($statusMap[$status])) {
                            $dbStatuses[] = $statusMap[$status];
                        }
                    }
                    if (!empty($dbStatuses)) {
                        Log::info('Mapped DB statuses:', ['dbStatuses' => $dbStatuses]);
                        $query->whereIn('STS', $dbStatuses);
                    }
                }
            }

            // Get SQL query for debugging
            $sql = $query->toSql();
            $bindings = $query->getBindings();
            Log::info('SQL Query:', ['sql' => $sql, 'bindings' => $bindings]);

            $salesOrders = $query->orderBy('SO_Num', 'desc')
                                ->limit(100)
                                ->get();

            Log::info('Query result count:', ['count' => $salesOrders->count()]);
            if ($salesOrders->count() > 0) {
                Log::info('First result:', ['first' => $salesOrders->first()]);
                Log::info('Last result:', ['last' => $salesOrders->last()]);
            } else {
                Log::warning('No sales orders found with current filters');
                
                // Check if there are any records in the table at all
                $totalCount = DB::table('so')->count();
                Log::info('Total SO records in database:', ['total' => $totalCount]);
                
                if ($totalCount > 0) {
                    // Get some sample records
                    $samples = DB::table('so')->limit(5)->get(['SO_Num', 'MM', 'YYYY', 'STS', 'AC_Num']);
                    Log::info('Sample SO records:', ['samples' => $samples]);
                }
            }
            
            // Map to expected format for Sales Order Table Modal
            $formattedOrders = $salesOrders->map(function($order) {
                return [
                    'so_number' => $order->SO_Num,
                    'customer_code' => $order->AC_Num,
                    'customer_name' => $order->AC_NAME,
                    'customer_po_number' => $order->PO_Num,
                    'master_card_seq' => $order->MCS_Num,
                    'master_card_model' => $order->MODEL,
                    'p_design' => $order->P_DESIGN,
                    'salesperson_code' => $order->SLM,
                    'order_group' => $order->GROUP_,
                    'order_type' => $order->TYPE,
                    'status' => $order->STS,
                    'order_quantity' => $order->SO_QTY,
                    'uom' => $order->UNIT,
                    'unit_price' => $order->UNIT_PRICE,
                    'amount' => $order->AMOUNT,
                    'remark' => $order->SO_REMARK,
                    'instruction1' => $order->SO_INSTRUCTION_1,
                    'instruction2' => $order->SO_INSTRUCTION_2,
                    'delivery_location' => $order->D_LOC_Num,
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $formattedOrders,
                'count' => $formattedOrders->count(),
                'debug' => [
                    'sql' => $sql,
                    'bindings' => $bindings,
                    'count' => $salesOrders->count()
                ]
            ], 200, ['Content-Type' => 'application/json; charset=utf-8']);
            
        } catch (\Exception $e) {
            Log::error('Error fetching sales orders:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error fetching sales orders: ' . $e->getMessage()
            ], 500, ['Content-Type' => 'application/json; charset=utf-8']);
        }
    }
    
    /**
     * Get sales order detail by SO number
     */
    public function getSalesOrderDetail(Request $request, $soNumber): JsonResponse
    {
        // Clean output buffers
        while (ob_get_level()) {
            ob_end_clean();
        }
        
        try {
            Log::info('Fetching sales order detail', ['so_number' => $soNumber]);
            
            // Fetch sales order from SO table
            $salesOrder = DB::table('so')
                ->where('SO_Num', $soNumber)
                ->first();
            
            if (!$salesOrder) {
                Log::warning('Sales order not found', ['so_number' => $soNumber]);
                return response()->json([
                    'success' => false,
                    'message' => 'Sales order not found'
                ], 404, ['Content-Type' => 'application/json; charset=utf-8']);
            }
            
            Log::info('Sales order found', ['so_data' => $salesOrder]);
            
            // Get salesperson data from SALESPERSON table
            $salesperson = null;
            if (!empty($salesOrder->SLM)) {
                try {
                    // Try uppercase table name first (legacy)
                    $salesperson = DB::table('SALESPERSON')
                        ->where('CODE', $salesOrder->SLM)
                        ->first();
                    
                    // If not found, try lowercase table name
                    if (!$salesperson) {
                        $salesperson = DB::table('salesperson')
                            ->where('code', $salesOrder->SLM)
                            ->first();
                    }
                    
                    if ($salesperson) {
                        Log::info('Salesperson found', [
                            'code' => $salesOrder->SLM,
                            'salesperson' => $salesperson
                        ]);
                    } else {
                        Log::warning('Salesperson not found in both tables', [
                            'code' => $salesOrder->SLM
                        ]);
                    }
                } catch (\Exception $e) {
                    Log::warning('Error fetching salesperson', [
                        'code' => $salesOrder->SLM,
                        'error' => $e->getMessage()
                    ]);
                }
            }
            
            // Get master card data for additional details
            $masterCard = null;
            if (!empty($salesOrder->MCS_Num) && !empty($salesOrder->AC_Num)) {
                try {
                    $masterCard = DB::table('MC')
                        ->where('MCS_Num', $salesOrder->MCS_Num)
                        ->where('AC_NUM', $salesOrder->AC_Num)
                        ->first();
                    
                    if ($masterCard) {
                        Log::info('Master card found', ['mc_seq' => $salesOrder->MCS_Num]);
                    } else {
                        Log::warning('Master card not found', [
                            'mc_seq' => $salesOrder->MCS_Num,
                            'ac_num' => $salesOrder->AC_Num
                        ]);
                    }
                } catch (\Exception $e) {
                    Log::warning('Error fetching master card', [
                        'mc_seq' => $salesOrder->MCS_Num,
                        'error' => $e->getMessage()
                    ]);
                }
            }
            
            // Format order information
            $salespersonName = '';
            if ($salesperson) {
                // Try uppercase NAME field first (legacy)
                $salespersonName = $salesperson->NAME ?? $salesperson->name ?? $salesOrder->SLM ?? '';
            } else {
                $salespersonName = $salesOrder->SLM ?? '';
            }
            
            $orderInfo = [
                'customer_name' => $salesOrder->AC_NAME ?? '',
                'model' => $salesOrder->MODEL ?? '',
                'order_mode' => '0-Order by Customer + Deliver & Invoice to Customer',
                'salesperson_code' => $salesOrder->SLM ?? '',
                'salesperson_name' => $salespersonName,
                'order_group' => $salesOrder->GROUP_ ?? 'Sales',
                'order_type' => $salesOrder->TYPE ?? 'S1',
                // Added fields for UI bindings
                'so_status' => $salesOrder->STS ?? '',
                'so_date' => $salesOrder->SO_DMY ?? '',
                'po_date' => $salesOrder->PO_DATE ?? '',
                'customer_po_number' => $salesOrder->PO_Num ?? '',
            ];
            
            // Format item details from SO table
            $itemDetails = [
                'pd' => $salesOrder->P_DESIGN ?? '',
                'pcs' => $salesOrder->PER_SET ?? '1',
                'unit' => $salesOrder->UNIT ?? '',
                'order_qty' => $salesOrder->SO_QTY ?? '0',
                'net_delivery' => '0',
                'balance' => $salesOrder->SO_QTY ?? '0',
            ];
            
            // Calculate net delivery from DO table (sum of DO_Qty) for this SO
            $netDelivery = 0;
            $hasDoRows = false;
            try {
                $hasDoRows = DB::table('DO')->where('SO_Num', $soNumber)->exists();
                if ($hasDoRows) {
                    $netDelivery = (float) DB::table('DO')
                        ->where('SO_Num', $soNumber)
                        ->sum('DO_Qty');
                }
            } catch (\Exception $e) {
                Log::warning('Error computing DO net delivery; falling back to SO schedule fields', [
                    'so_number' => $soNumber,
                    'error' => $e->getMessage()
                ]);
                $hasDoRows = false;
            }

            // If no DO rows, show net_delivery as empty string and balance equals order
            if (!$hasDoRows) {
                $itemDetails['net_delivery'] = '';
                $itemDetails['balance'] = $salesOrder->SO_QTY ?? '0';
            } else {
                $itemDetails['net_delivery'] = $netDelivery;
                $itemDetails['balance'] = (float)($salesOrder->SO_QTY ?? 0) - $netDelivery;
            }
            // Include pcs per bundle from MC if available
            if ($masterCard && isset($masterCard->PCS_PER_BLD)) {
                $itemDetails['pcs_per_bdl'] = (float) $masterCard->PCS_PER_BLD;
            }
            
            // Get fittings from master card if available
            $fittings = [];
            if ($masterCard) {
                // Convert object to array for easier access
                $mcArray = (array)$masterCard;
                
                // Check for fitting data in MC table
                for ($i = 1; $i <= 9; $i++) {
                    $designField = "FIT{$i}_DESIGN";
                    $pcsField = "FIT{$i}_PCS";
                    $unitField = "FIT{$i}_UNIT";
                    
                    // Check if design field exists and has value
                    if (isset($mcArray[$designField]) && !empty($mcArray[$designField])) {
                        $fittings[] = [
                            'design' => $mcArray[$designField],
                            'pcs' => $mcArray[$pcsField] ?? '',
                            'unit' => $mcArray[$unitField] ?? '',
                        ];
                    }
                }
            }
            
            Log::info('Successfully prepared response', [
                'fittings_count' => count($fittings),
                'net_delivery' => $netDelivery,
                'salesperson_code' => $orderInfo['salesperson_code'],
                'salesperson_name' => $orderInfo['salesperson_name']
            ]);
            
            $responseData = [
                'success' => true,
                'data' => [
                    'order_info' => $orderInfo,
                    'item_details' => $itemDetails,
                    'fittings' => $fittings,
                    'so_number' => $salesOrder->SO_Num ?? '',
                    'customer_po' => $salesOrder->PO_Num ?? '',
                    'master_card_seq' => $salesOrder->MCS_Num ?? '',
                    'part_number' => $salesOrder->PART_NUMBER ?? '',
                ]
            ];
            
            Log::info('Response data', ['response' => $responseData]);
            
            return response()->json($responseData, 200, ['Content-Type' => 'application/json; charset=utf-8']);
            
        } catch (\Exception $e) {
            Log::error('Error fetching sales order detail:', [
                'so_number' => $soNumber,
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error fetching sales order detail: ' . $e->getMessage(),
                'debug' => [
                    'line' => $e->getLine(),
                    'file' => basename($e->getFile())
                ]
            ], 500, ['Content-Type' => 'application/json; charset=utf-8']);
        }
    }
}
