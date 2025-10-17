<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
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

        // Generate a dummy SO number: e.g., SO-YYYYMMDD-XXXX
        $datePart = date('Ymd');
        $randPart = str_pad((string) random_int(1, 9999), 4, '0', STR_PAD_LEFT);
        $soNumber = 'SO-' . $datePart . '-' . $randPart;

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
        return response()->json(['message' => 'getDeliverySchedules not yet implemented', 'soNumber' => $soNumber], 200);
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

        $soNumber = $validated['so_number'] ?? ('SO-' . date('Ymd') . '-' . str_pad((string) random_int(1, 9999), 4, '0', STR_PAD_LEFT));
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
        try {
            $month = $request->input('month');
            $year = $request->input('year');
            $fromSo = $request->input('from_so');
            $toSo = $request->input('to_so');
            $status = $request->input('status', []);

            $query = DB::table('so');

            // Filter by period if provided
            if ($month && $year) {
                $query->where('MM', str_pad($month, 2, '0', STR_PAD_LEFT))
                      ->where('YYYY', $year);
            }

            // Filter by SO number range if provided
            if ($fromSo && $toSo) {
                // Handle different SO number formats
                if ($fromSo === $toSo) {
                    // Single SO number - use LIKE for flexible matching
                    $query->where('SO_Num', 'LIKE', '%' . $fromSo . '%');
                } else {
                    // Range of SO numbers - handle both numeric and string formats
                    if (is_numeric($fromSo) && is_numeric($toSo)) {
                        // If input is numeric, try different approaches
                        $fromNum = (int)$fromSo;
                        $toNum = (int)$toSo;
                        
                        // Limit range to prevent performance issues
                        if (($toNum - $fromNum) <= 100) {
                            // For small ranges, use LIKE pattern matching
                            $query->where(function($q) use ($fromNum, $toNum) {
                                for ($i = $fromNum; $i <= $toNum; $i++) {
                                    $q->orWhere('SO_Num', 'LIKE', '%' . str_pad($i, 4, '0', STR_PAD_LEFT) . '%');
                                }
                            });
                        } else {
                            // For large ranges, use simple BETWEEN on string
                            $query->whereBetween('SO_Num', [$fromSo, $toSo]);
                        }
                    } else {
                        // String comparison for non-numeric SO numbers
                        $query->whereBetween('SO_Num', [$fromSo, $toSo]);
                    }
                }
            }

            // Filter by status if provided
            if (!empty($status)) {
                $statusMap = [
                    'outstanding' => 'OPEN',
                    'partial' => 'PARTIAL',
                    'completed' => 'COMPLETED',
                    'closed' => 'CLOSED',
                    'cancelled' => 'CANCELLED'
                ];
                
                $mappedStatus = array_map(function($s) use ($statusMap) {
                    return $statusMap[$s] ?? strtoupper($s);
                }, $status);
                
                $query->whereIn('STS', $mappedStatus);
            }

            // Order by SO number
            $query->orderBy('SO_Num', 'asc');

            $salesOrders = $query->get();

            return response()->json([
                'success' => true,
                'data' => $salesOrders,
                'count' => $salesOrders->count()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching sales orders: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getSalesOrderReport(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'period' => 'nullable|string',
                'range.from' => 'nullable|string',
                'range.to' => 'nullable|string',
                'copies' => 'nullable|integer|min:1|max:9',
                'status' => 'nullable|array',
                'printer.code' => 'nullable|string',
                'printer.user' => 'nullable|string'
            ]);

            // Parse period (e.g., "8 2025")
            $periodParts = explode(' ', $validated['period'] ?? '');
            $month = $periodParts[0] ?? date('n');
            $year = $periodParts[1] ?? date('Y');

            $query = DB::table('so');

            // Filter by period
            $query->where('MM', str_pad($month, 2, '0', STR_PAD_LEFT))
                  ->where('YYYY', $year);

            // Filter by SO range
            if (!empty($validated['range']['from']) && !empty($validated['range']['to'])) {
                $fromSo = $validated['range']['from'];
                $toSo = $validated['range']['to'];
                
                if ($fromSo === $toSo) {
                    // Single SO number - use LIKE for flexible matching
                    $query->where('SO_Num', 'LIKE', '%' . $fromSo . '%');
                } else {
                    // Range of SO numbers - handle both numeric and string formats
                    if (is_numeric($fromSo) && is_numeric($toSo)) {
                        // If input is numeric, try different approaches
                        $fromNum = (int)$fromSo;
                        $toNum = (int)$toSo;
                        
                        // Limit range to prevent performance issues
                        if (($toNum - $fromNum) <= 100) {
                            // For small ranges, use LIKE pattern matching
                            $query->where(function($q) use ($fromNum, $toNum) {
                                for ($i = $fromNum; $i <= $toNum; $i++) {
                                    $q->orWhere('SO_Num', 'LIKE', '%' . str_pad($i, 4, '0', STR_PAD_LEFT) . '%');
                                }
                            });
                        } else {
                            // For large ranges, use simple BETWEEN on string
                            $query->whereBetween('SO_Num', [$fromSo, $toSo]);
                        }
                    } else {
                        // String comparison for non-numeric SO numbers
                        $query->whereBetween('SO_Num', [$fromSo, $toSo]);
                    }
                }
            }

            // Filter by status
            if (!empty($validated['status'])) {
                $statusMap = [
                    'outstanding' => 'OPEN',
                    'partial' => 'PARTIAL',
                    'completed' => 'COMPLETED',
                    'closed' => 'CLOSED',
                    'cancelled' => 'CANCELLED'
                ];
                
                $mappedStatus = array_map(function($s) use ($statusMap) {
                    return $statusMap[$s] ?? strtoupper($s);
                }, $validated['status']);
                
                $query->whereIn('STS', $mappedStatus);
            }

            $salesOrders = $query->orderBy('SO_Num', 'asc')->get();

            return response()->json([
                'success' => true,
                'data' => [
                    'sales_orders' => $salesOrders,
                    'summary' => [
                        'total' => $salesOrders->count(),
                        'outstanding' => $salesOrders->where('STS', 'OPEN')->count(),
                        'partial' => $salesOrders->where('STS', 'PARTIAL')->count(),
                        'completed' => $salesOrders->where('STS', 'COMPLETED')->count(),
                        'closed' => $salesOrders->where('STS', 'CLOSED')->count(),
                        'cancelled' => $salesOrders->where('STS', 'CANCELLED')->count(),
                    ],
                    'filters' => [
                        'period' => $validated['period'] ?? '',
                        'range' => $validated['range'] ?? [],
                        'status' => $validated['status'] ?? [],
                        'copies' => $validated['copies'] ?? 1,
                        'printer' => $validated['printer'] ?? []
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error generating report: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getSalesOrderWithSchedules(string $soNumber): JsonResponse
    {
        try {
            // Get sales order from SO table
            $salesOrder = DB::table('so')->where('SO_Num', $soNumber)->first();

            if (!$salesOrder) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sales Order not found'
                ], 404);
            }

            // Extract delivery schedules from SO table columns
            $deliverySchedules = [];
            for ($i = 1; $i <= 10; $i++) {
                $dateCol = $i === 10 ? 'D_DATE10' : 'D_DATE_' . $i;
                $timeCol = $i === 10 ? 'D_TIME10' : 'D_TIME_' . $i;
                $dueCol = $i === 10 ? 'D_DUE10' : 'D_DUE_' . $i;
                $qtyCol = $i === 10 ? 'D_QTY_10' : 'D_QTY_' . $i;

                if (!empty($salesOrder->$dateCol)) {
                    $deliverySchedules[] = [
                        'line_number' => $i,
                        'schedule_date' => $salesOrder->$dateCol,
                        'schedule_time' => $salesOrder->$timeCol ?? '',
                        'due_status' => $salesOrder->$dueCol ?? '',
                        'delivery_quantity' => $salesOrder->$qtyCol ?? 0,
                        'remark' => ''
                    ];
                }
            }

            // Get customer address from CUSTOMER table
            $customerAddress = '';
            if (!empty($salesOrder->AC_Num)) {
                $customerData = DB::table('CUSTOMER')->where('CODE', $salesOrder->AC_Num)->first();
                $customerAddress = $customerData ? ($customerData->ADDRESS ?? '') : '';
            }

            // Format sales order data
            $formattedSO = [
                'so_number' => $salesOrder->SO_Num,
                'customer_code' => $salesOrder->AC_Num,
                'customer_name' => $salesOrder->AC_NAME,
                'customer_address' => $customerAddress,
                'delivery_address' => $customerAddress, // Same as customer address for now
                'master_card_seq' => $salesOrder->MCS_Num,
                'master_card_model' => $salesOrder->MODEL,
                'customer_po_number' => $salesOrder->PO_Num,
                'po_date' => $salesOrder->PO_DATE,
                'currency' => $salesOrder->CURR ?? 'IDR',
                'credit_terms' => $salesOrder->CREDIT_TERMS ?? '30 DAYS',
                'status' => $salesOrder->STS,
                'remark' => $salesOrder->SO_REMARK ?? '',
                'instruction1' => $salesOrder->SO_INSTRUCTION_1 ?? '',
                'instruction2' => $salesOrder->SO_INSTRUCTION_2 ?? '',
                'total_amount' => $salesOrder->AMOUNT ?? 0,
                'details' => [
                    [
                        'item_code' => $salesOrder->PRODUCT,
                        'item_description' => $salesOrder->P_DESIGN ?? $salesOrder->MODEL, // Use P_DESIGN if available
                        'order_quantity' => $salesOrder->SO_QTY,
                        'unit_price' => $salesOrder->UNIT_PRICE,
                        'uom' => $salesOrder->UNIT ?? 'PCS'
                    ]
                ]
            ];

            return response()->json([
                'success' => true,
                'data' => [
                    'sales_order' => $formattedSO,
                    'delivery_schedules' => $deliverySchedules
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching sales order: ' . $e->getMessage()
            ], 500);
        }
    }
}
