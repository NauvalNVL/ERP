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
        
        // Get the last SO number for this period
        $lastSO = DB::table('so')
            ->where('MM', $month)
            ->where('YYYY', $year)
            ->orderBy('SO_Num', 'desc')
            ->first();
        
        $sequence = 1;
        if ($lastSO && $lastSO->SO_Num) {
            // Extract sequence from format MM-YYYY-XXXXX
            $parts = explode('-', $lastSO->SO_Num);
            if (count($parts) === 3) {
                $sequence = intval($parts[2]) + 1;
            }
        }
        
        $seqPart = str_pad((string) $sequence, 5, '0', STR_PAD_LEFT);
        $soNumber = $month . '-' . $year . '-' . $seqPart;

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
            'STS' => 'OPEN',
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
            'INT_L' => 0,
            'INT_W' => 0,
            'INT_H' => 0,
            'EXT_L' => 0,
            'EXT_W' => 0,
            'EXT_H' => 0,
            'FLUTE' => '',
            'PQ1' => '',
            'PQ2' => '',
            'PQ3' => '',
            'PQ4' => '',
            'PQ5' => '',
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

        // Upsert by SO_Num
        $exists = DB::table('so')->where('SO_Num', $soNumber)->exists();
        if ($exists) {
            DB::table('so')->where('SO_Num', $soNumber)->update($base);
        } else {
            DB::table('so')->insert($base);
        }

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
            
            // Get the last SO number for this period
            $lastSO = DB::table('so')
                ->where('MM', $month)
                ->where('YYYY', $year)
                ->orderBy('SO_Num', 'desc')
                ->first();
            
            $sequence = 1;
            if ($lastSO && $lastSO->SO_Num) {
                // Extract sequence from format MM-YYYY-XXXXX
                $parts = explode('-', $lastSO->SO_Num);
                if (count($parts) === 3) {
                    $sequence = intval($parts[2]) + 1;
                }
            }
            
            $seqPart = str_pad((string) $sequence, 5, '0', STR_PAD_LEFT);
            $soNumber = $month . '-' . $year . '-' . $seqPart;
        } else {
            $soNumber = $validated['so_number'];
        }
        
        $qty = (float) ($validated['details'][0]['order_quantity'] ?? 0);
        $price = (float) ($validated['details'][0]['unit_price'] ?? 0);
        $amount = $qty * $price;
        $partNumber = (string) ($request->input('part_number') ?? $request->input('partNo') ?? $request->input('master_card_model') ?? '');

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
            'INT_L' => 0,
            'INT_W' => 0,
            'INT_H' => 0,
            'EXT_L' => 0,
            'EXT_W' => 0,
            'EXT_H' => 0,
            'FLUTE' => '',
            'PQ1' => '',
            'PQ2' => '',
            'PQ3' => '',
            'PQ4' => '',
            'PQ5' => '',
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

        // Upsert by SO_Num in case it's already created
        $exists = DB::table('so')->where('SO_Num', $soNumber)->exists();
        if ($exists) {
            DB::table('so')->where('SO_Num', $soNumber)->update($base);
        } else {
            DB::table('so')->insert($base);
        }

        return response()->json([
            'success' => true,
            'message' => 'Legacy SO record saved',
            'data' => ['so_number' => $soNumber]
        ]);
    }

    public function getSalesOrders(Request $request): JsonResponse
    {
        Log::info('getSalesOrders called with params:', $request->all());
        
        $query = DB::table('so');

        // Filter by month and year
        if ($request->has('month') && $request->has('year')) {
            $month = str_pad($request->input('month'), 2, '0', STR_PAD_LEFT);
            $year = $request->input('year');
            Log::info('Filtering by month/year:', ['month' => $month, 'year' => $year]);
            $query->where('MM', $month)
                  ->where('YYYY', $year);
        }

        // Filter by SO number range - Support both old and new format
        if ($request->has('from_so') && $request->has('to_so')) {
            $fromSO = $request->input('from_so');
            $toSO = $request->input('to_so');
            
            Log::info('Filtering SO range:', ['from_so' => $fromSO, 'to_so' => $toSO]);
            
            // Check if we need to support old format (SO-YYYYMMDD-XXXX)
            // For backward compatibility, also search old format
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
                $samples = DB::table('so')->limit(5)->get(['SO_Num', 'MM', 'YYYY', 'STS']);
                Log::info('Sample SO records:', ['samples' => $samples]);
            }
        }

        return response()->json([
            'success' => true,
            'data' => $salesOrders,
            'debug' => [
                'sql' => $sql,
                'bindings' => $bindings,
                'count' => $salesOrders->count()
            ]
        ]);
    }
}
