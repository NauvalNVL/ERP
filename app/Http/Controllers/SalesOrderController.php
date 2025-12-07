<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\Customer;
use App\Models\Salesperson;

class SalesOrderController extends Controller
{
    /**
     * Get current time in WIB timezone (UTC+7)
     * Same implementation as InvoiceController
     *
     * @return \Carbon\Carbon
     */
    private function getNowWib()
    {
        return now()->timezone('Asia/Jakarta'); // WIB = UTC+7
    }

    /**
     * Get current authenticated user ID for audit trail
     * Same implementation as InvoiceController
     *
     * @return string|null
     */
    private function getCurrentUserId()
    {
        try {
            Log::info('🔍 getCurrentUserId() called - Starting user ID retrieval');

            if (!Auth::check()) {
                Log::warning('❌ User not authenticated for audit trail');
                return 'System'; // Fallback to 'System' if not authenticated
            }

            Log::info('✅ Auth::check() passed');

            $user = Auth::user();

            if (!$user) {
                Log::warning('❌ Auth::user() returned null');
                return 'System';
            }

            Log::info('✅ Auth::user() returned user object', [
                'class' => get_class($user)
            ]);

            // Try to get userID property from UserCps model
            $userId = null;
            $method = null;

            // Priority 1: Direct property access
            if (isset($user->userID) && !empty($user->userID)) {
                $userId = $user->userID;
                $method = 'userID property';
                Log::info('✅ Found via userID property', ['value' => $userId]);
            }
            // Priority 2: Alternative naming
            elseif (isset($user->user_id) && !empty($user->user_id)) {
                $userId = $user->user_id;
                $method = 'user_id property';
                Log::info('✅ Found via user_id property', ['value' => $userId]);
            }
            // Priority 3: Check NO_ as fallback
            elseif (isset($user->NO_) && !empty($user->NO_)) {
                $userId = $user->NO_;
                $method = 'NO_ property (fallback)';
                Log::info('⚠️ Found via NO_ fallback', ['value' => $userId]);
            }

            // ✅ Fallback: Try Auth::id() if userID property not found
            if (empty($userId)) {
                $authId = Auth::id();
                if ($authId) {
                    $userId = (string) $authId;
                    $method = 'Auth::id() fallback';
                    Log::info('✅ Using Auth::id() as fallback', ['value' => $userId]);
                } else {
                    Log::error('❌ Could not retrieve userID from authenticated user', [
                        'user_class' => get_class($user),
                        'has_userID' => property_exists($user, 'userID'),
                        'has_user_id' => property_exists($user, 'user_id'),
                        'has_NO_' => property_exists($user, 'NO_'),
                        'userID_value' => $user->userID ?? 'NOT SET',
                        'user_id_value' => $user->user_id ?? 'NOT SET',
                        'NO__value' => $user->NO_ ?? 'NOT SET',
                        'Auth::id()' => $authId,
                    ]);
                    return 'System'; // Final fallback
                }
            }

            Log::info('✅ User ID successfully retrieved for audit trail', [
                'userId' => $userId,
                'method' => $method,
                'user_class' => get_class($user)
            ]);

            return $userId;

        } catch (\Exception $e) {
            Log::error('❌ EXCEPTION in getCurrentUserId()', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            return 'System'; // Safe fallback on exception
        }
    }


    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'customer_code' => 'required|string|max:50',
            'master_card_seq' => 'required|string|max:50',
            'order_mode' => 'required|string|max:50',
            'product_code' => 'required|string|max:50',
            'salesperson_code' => 'nullable|string|max:50',
            'currency' => 'required|string|max:10',
            'exchange_rate' => 'required|numeric|min:0',
            'customer_po_number' => 'nullable|string|max:50',
            'po_date' => 'required|date',
            'order_group' => 'required|string|max:50',
            'order_type' => 'required|string|max:50',
            'lot_number' => 'nullable|string|max:50',
            'remark' => 'nullable|string|max:250',
            'instruction1' => 'nullable|string|max:250',
            'instruction2' => 'nullable|string|max:250',
            'set_quantity' => 'nullable|numeric|min:0',
            'product_design_quantities' => 'nullable|array',
            'product_design_quantities.*' => 'numeric|min:0',
            'delivery_location.delivery_code' => 'nullable|string|max:50',
            'delivery_location.customer_name' => 'nullable|string|max:250',
            'delivery_location.address' => 'nullable|string|max:250',
            'details' => 'required|array|min:1',
            'details.*.line_number' => 'required|integer|min:1',
            'details.*.item_code' => 'required|string|max:50',
            'details.*.item_description' => 'nullable|string|max:250',
            'details.*.order_quantity' => 'required|numeric|min:0',
            'details.*.unit_price' => 'required|numeric|min:0',
            'details.*.uom' => 'nullable|string|max:10',
            'details.*.remark' => 'nullable|string|max:250',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 422);
        }

        $validated = $request->all();
        // Generate SO number in format: MM-YYYY-XXXXX (matching the filter format)
        $month = date('m');
        $year = date('Y');

        // Get the ACTUAL last sequence number by checking the max sequence in SO_Num column
        // Use TRY_CAST to safely handle non-numeric suffixes like "-Fit1" in SO_Num
        $lastSO = DB::table('so')
            ->where('MM', $month)
            ->where('YYYY', $year)
            ->whereRaw('TRY_CAST(RIGHT(SO_Num, 5) AS INT) IS NOT NULL')
            ->orderByRaw('TRY_CAST(RIGHT(SO_Num, 5) AS INT) DESC')
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
        $customerIndustry = $customerData ? ($customerData->IND ?? $customerData->INDUSTRY ?? '') : '';
        $customerArea = $customerData ? ($customerData->AREA ?? $customerData->REGION ?? '') : '';

        // Fetch master card data from database
        $masterCardData = DB::table('MC')
            ->where('MCS_Num', $validated['master_card_seq'])
            ->where('AC_NUM', $validated['customer_code'])
            ->first();

        // Log MC data fetch result
        Log::info('MC Data Fetch', [
            'customer_code' => $validated['customer_code'],
            'master_card_seq' => $validated['master_card_seq'],
            'mc_found' => $masterCardData ? 'yes' : 'no',
            'mc_model' => $masterCardData ? $masterCardData->MODEL : 'N/A',
            'has_dimensions' => $masterCardData ? [
                'ext_l' => !empty($masterCardData->EXT_LENGTH),
                'ext_w' => !empty($masterCardData->EXT_WIDTH),
                'ext_h' => !empty($masterCardData->EXT_HEIGHT),
            ] : 'N/A'
        ]);

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

        // Extract material calculation data from MC table
        $mcGrossM2 = $masterCardData ? (float)($masterCardData->MC_GROSS_M2_PER_PCS ?? 0) : 0;
        $mcNetM2 = $masterCardData ? (float)($masterCardData->MC_NET_M2_PER_PCS ?? 0) : 0;
        $mcGrossKg = $masterCardData ? (float)($masterCardData->MC_GROSS_KG_PER_SET ?? 0) : 0;
        $mcNetKg = $masterCardData ? (float)($masterCardData->MC_NET_KG_PER_PCS ?? 0) : 0;

        // Extract sheet dimensions for LM calculation (Linear Meter)
        $sheetLength = $masterCardData ? (float)($masterCardData->SHEET_LENGTH ?? 0) : 0;
        $sheetWidth = $masterCardData ? (float)($masterCardData->SHEET_WIDTH ?? 0) : 0;

        // Prepare minimal legacy SO row so schedule updates can find it
        $qty = (float) ($validated['details'][0]['order_quantity'] ?? 0);
        $price = (float) ($validated['details'][0]['unit_price'] ?? 0);
        $mainQty = $productDesignQuantities['Main'] ?? $qty;
        $amount = $mainQty * $price;
        $exRate = isset($validated['exchange_rate']) ? (float) $validated['exchange_rate'] : 1.0;
        if ($exRate <= 0) {
            $exRate = 1.0;
        }

        // Extract product design quantities for each component (Main, Fit1-9)
        $productDesignQuantities = $validated['product_design_quantities'] ?? [];

        // Ensure product design quantities is an associative array of numeric values
        if (!is_array($productDesignQuantities)) {
            $productDesignQuantities = [];
        }

        // Normalize component keys from Product Design to match MC.COMP values
        // Handle variations like "Fit 1" vs "Fit1", case differences, and extra spaces
        $normalizedProductDesignQuantities = [];
        foreach ($productDesignQuantities as $component => $quantity) {
            $qtyFloat = (float) $quantity;
            if ($qtyFloat <= 0) {
                continue;
            }

            $rawKey = (string) $component;
            $noSpaces = preg_replace('/\s+/', '', $rawKey);
            $lower = strtolower($noSpaces);

            if ($lower === 'main') {
                $normalizedKey = 'Main';
            } elseif (preg_match('/^fit(\d+)$/', $lower, $m)) {
                // Map any variation like "fit1", "Fit 1" to canonical "Fit1"
                $normalizedKey = 'Fit' . $m[1];
            } else {
                // Fallback: keep original key
                $normalizedKey = $rawKey;
            }

            if (!isset($normalizedProductDesignQuantities[$normalizedKey])) {
                $normalizedProductDesignQuantities[$normalizedKey] = 0.0;
            }
            $normalizedProductDesignQuantities[$normalizedKey] += $qtyFloat;
        }

        $productDesignQuantities = $normalizedProductDesignQuantities;

        Log::info('Product Design Quantities processed:', [
            'original' => $validated['product_design_quantities'] ?? 'not_set',
            'processed' => $productDesignQuantities,
            'fallback_qty' => $qty
        ]);

        // Use master card part number, fallback to request data
        $partNumber = $mcPartNumber ?: (string) ($request->input('part_number') ?? $request->input('partNo') ?? $request->input('master_card_model') ?? '');

        // Calculate totals based on MC data and Main component quantity
        $totalGrossM2 = $mcGrossM2 * $mainQty;
        $totalNetM2 = $mcNetM2 * $mainQty;
        $totalGrossKg = $mcGrossKg * $mainQty;
        $totalNetKg = $mcNetKg * $mainQty;

        // Calculate TOTAL_LM (Linear Meter) = (SHEET_LENGTH × Quantity) / 1,000 (convert mm to meter)
        $totalLM = 0;
        if ($sheetLength > 0 && $mainQty > 0) {
            $totalLM = ($sheetLength * $mainQty) / 1000;
        }

        // Calculate total M3 (volume) = (L × W × H × Quantity) / 1,000,000,000 (convert mm³ to m³)
        // Use EXT dimensions for outer carton volume
        $totalM3 = 0;

        // Log dimensions for debugging
        Log::info('TOTAL_M3 Calculation Debug', [
            'customer_code' => $validated['customer_code'],
            'master_card_seq' => $validated['master_card_seq'],
            'main_qty' => $mainQty,
            'int_l' => $intL,
            'int_w' => $intW,
            'int_h' => $intH,
            'ext_l' => $extL,
            'ext_w' => $extW,
            'ext_h' => $extH,
        ]);

        // Try EXT dimensions first, fallback to INT dimensions
        if ($extL > 0 && $extW > 0 && $extH > 0 && $mainQty > 0) {
            $totalM3 = ($extL * $extW * $extH * $mainQty) / 1000000000;
            Log::info('TOTAL_M3 calculated from EXT dimensions', ['totalM3' => $totalM3]);
        } elseif ($intL > 0 && $intW > 0 && $intH > 0 && $mainQty > 0) {
            $totalM3 = ($intL * $intW * $intH * $mainQty) / 1000000000;
            Log::info('TOTAL_M3 calculated from INT dimensions', ['totalM3' => $totalM3]);
        } else {
            Log::warning('TOTAL_M3 calculation failed - no valid dimensions', [
                'ext_dimensions' => [$extL, $extW, $extH],
                'int_dimensions' => [$intL, $intW, $intH],
                'main_qty' => $mainQty
            ]);
        }

        // ===================================================================
        // DELIVERY LOCATION LOGIC - Dual Mode System
        // ===================================================================
        // Mode 1: Default - Ship to CUSTOMER main address (when delivery_code is empty)
        // Mode 2: Alternate - Ship to alternate address from customer_alternate_addresses table (when delivery_code is provided)
        // ===================================================================

        $deliveryData = $request->input('delivery_location', []);

        // ===================================================================
        // D_LOC_Num Logic (UPDATED: NOW STORES GEOGRAPHICAL CODE)
        // ===================================================================
        // D_LOC_Num now stores geographical delivery code (same as Del_Code in DO table)
        // Sources:
        // 1. If user selects alternate address: get delivery_code from customer_alternate_addresses
        // 2. If user uses main address: get geographical code from update_customer_accounts or CUSTOMER.AREA
        // ===================================================================

        $dLocNum = (string) ($deliveryData['delivery_code'] ?? '');
        $deliveryTo = '';
        $deliveryAdd1 = '';
        $deliveryAdd2 = '';
        $deliveryAdd3 = '';

        // Check if delivery code is provided
        if (!empty($dLocNum)) {
            // MODE 2: ALTERNATE ADDRESS - Use data from customer_alternate_addresses
            // Frontend should already provide this data from the modal selection
            $deliveryTo = (string) ($deliveryData['customer_name'] ?? '');

            // Split delivery address into 3 lines with intelligent handling
            if (isset($deliveryData['address']) && !empty($deliveryData['address'])) {
                $rawAddress = $deliveryData['address'];

                // Check if address contains newline characters
                if (strpos($rawAddress, "\n") !== false || strpos($rawAddress, "\r\n") !== false) {
                    // Split by newline
                    $addressLines = preg_split('/\r\n|\r|\n/', $rawAddress);
                    $deliveryAdd1 = (string) trim($addressLines[0] ?? '');
                    $deliveryAdd2 = (string) trim($addressLines[1] ?? '');
                    $deliveryAdd3 = (string) trim($addressLines[2] ?? '');
                } else {
                    // Single line address - check length
                    $maxLength = 250; // Based on DB column size
                    if (strlen($rawAddress) <= $maxLength) {
                        $deliveryAdd1 = $rawAddress;
                        $deliveryAdd2 = '';
                        $deliveryAdd3 = '';
                    } else {
                        // Try to split by common delimiters
                        $parts = preg_split('/[,\-]/', $rawAddress, 3);
                        $deliveryAdd1 = (string) trim($parts[0] ?? '');
                        $deliveryAdd2 = (string) trim($parts[1] ?? '');
                        $deliveryAdd3 = (string) trim($parts[2] ?? '');
                    }
                }
            }

            Log::info('SO D_LOC_Num - Mode 2: Alternate Address', [
                'd_loc_num' => $dLocNum,
                'delivery_to' => $deliveryTo,
                'address_1' => $deliveryAdd1,
                'address_2' => $deliveryAdd2,
                'address_3' => $deliveryAdd3,
                'address_raw' => $deliveryData['address'] ?? '',
                'source' => 'customer_alternate_addresses.delivery_code'
            ]);

        } else {
            // MODE 1: DEFAULT - Use main customer address from CUSTOMER table
            $customerData = DB::table('CUSTOMER')->where('CODE', $validated['customer_code'])->first();

            if ($customerData) {
                // D_LOC_Num uses geographical code from CUSTOMER.AREA
                $dLocNum = !empty($customerData->AREA) ? $customerData->AREA : '';

                $deliveryTo = $customerData->NAME ?? '';
                $deliveryAdd1 = $customerData->ADDRESS1 ?? '';
                $deliveryAdd2 = $customerData->ADDRESS2 ?? '';
                $deliveryAdd3 = $customerData->ADDRESS3 ?? '';

                Log::info('SO D_LOC_Num - Mode 1: Customer Main Address', [
                    'd_loc_num' => $dLocNum ?: '(no geographical code found)',
                    'delivery_to' => $deliveryTo,
                    'address_1' => $deliveryAdd1,
                    'address_2' => $deliveryAdd2,
                    'address_3' => $deliveryAdd3,
                    'geographical_source' => 'CUSTOMER.AREA',
                    'source' => 'CUSTOMER table'
                ]);
            } else {
                Log::warning('SO D_LOC_Num - No customer data found', [
                    'customer_code' => $validated['customer_code']
                ]);
            }
        }

        // Extract delivery schedules (up to 10 schedules)
        $deliverySchedules = $request->input('delivery_schedules', []);

        // Ensure delivery schedules is a properly indexed array
        if (!is_array($deliverySchedules)) {
            $deliverySchedules = [];
        }

        // Create empty schedule entries if not provided
        for ($i = count($deliverySchedules); $i < 10; $i++) {
            $deliverySchedules[$i] = [
                'date' => '',
                'time' => '',
                'due' => '',
                'quantity' => 0
            ];
        }

        Log::info('Delivery schedules processed:', [
            'original_schedules' => $request->input('delivery_schedules', []),
            'processed_schedules' => $deliverySchedules,
            'schedule_count' => count($deliverySchedules)
        ]);

        // Get current time in WIB timezone (UTC+7)
        $nowWib = $this->getNowWib();
        $nowDate = $nowWib->format('Y-m-d');
        $nowTime = $nowWib->format('H:i');

        // Get authenticated user ID for audit trail
        $currentUserId = $this->getCurrentUserId();
        Log::info('Creating SO with user audit trail', [
            'user_id' => $currentUserId,
            'so_number' => $soNumber,
            'timestamp_wib' => $nowWib->format('Y-m-d H:i:s')
        ]);

        $base = [
            'YYYY' => $nowWib->format('Y'),
            'MM' => $nowWib->format('m'),
            'SO_Num' => $soNumber,
            'STS' => 'Outstanding',
            'TYPE' => (string) ($validated['order_type'] ?? 'S1'),
            'SO_DMY' => $nowDate,
            'AC_Num' => (string) $validated['customer_code'],
            'AC_NAME' => $customerName,
            'SLM' => (string) ($validated['salesperson_code'] ?? ''),
            'IND' => $customerIndustry,
            'AREA' => $customerArea,
            'GROUP_' => (string) ($validated['order_group'] ?? 'Sales'),
            'PO_Num' => (string) ($validated['customer_po_number'] ?? ''),
            'PO_DATE' => (string) ($validated['po_date'] ?? $nowDate),
            'LOT_Num' => (string) ($validated['lot_number'] ?? ''),
            'MCS_Num' => (string) $validated['master_card_seq'],
            // MODEL / PRODUCT / COMPONENT and MC-related fields will be customized per component row below
            'MODEL' => $mcModel,
            'PRODUCT' => (string) ($validated['details'][0]['item_code'] ?? ''),
            'COMP_Num' => $masterCardData ? (string) ($masterCardData->COMP ?? '') : '',
            'P_DESIGN' => $mcPDesign,
            'PER_SET' => 1,
            'UNIT' => (string) ($validated['details'][0]['uom'] ?? ''),
            'PART_NUMBER' => $partNumber,
            // Populate dimensions from MC table (will be overridden per component if multiple exist)
            'INT_L' => $intL,
            'INT_W' => $intW,
            'INT_H' => $intH,
            'EXT_L' => $extL,
            'EXT_W' => $extW,
            'EXT_H' => $extH,
            // Populate flute and paper quality from MC table (will be overridden per component if needed)
            'FLUTE' => $flute,
            'PQ1' => $pq1,
            'PQ2' => $pq2,
            'PQ3' => $pq3,
            'PQ4' => $pq4,
            'PQ5' => $pq5,
            'SO_QTY' => (float) ($productDesignQuantities['Main'] ?? $qty),
            'UNIT_PRICE' => $price,
            'CURR' => (string) ($validated['currency'] ?? ''),
            'EX_RATE' => $exRate,
            'AMOUNT' => $amount,
            'BASE_AMOUNT' => $amount * $exRate,
            'MC_GROSS_M2_PER_PCS' => $mcGrossM2,
            'MC_NET_M2_PER_PCS' => $mcNetM2,
            'TOTAL_SO_GROSS_M2' => $totalGrossM2,
            'TOTAL_SO_NET_M2' => $totalNetM2,
            'TOTAL_LM' => (string) number_format($totalLM, 2, '.', ''),
            'TOTAL_M3' => (int) round($totalM3),
            'MC_GROSS_KG_PER_PCS' => $mcGrossKg,
            'MC_NET_KG_PER_PCS' => $mcNetKg,
            'TOTAL_SO_GROSS_KG' => $totalGrossKg,
            'TOTAL_SO_NET_KG' => $totalNetKg,
            'SO_REMARK' => (string) ($validated['remark'] ?? ''),
            'SO_INSTRUCTION_1' => (string) ($validated['instruction1'] ?? ''),
            'SO_INSTRUCTION_2' => (string) ($validated['instruction2'] ?? ''),
            'D_LOC_Num' => $dLocNum,
            'DELIVERY_TO' => $deliveryTo,
            'DELIVERY_ADD_1' => $deliveryAdd1,
            'DELIVERY_ADD_2' => $deliveryAdd2,
            'DELIVERY_ADD_3' => $deliveryAdd3,
            'D_DATE_1' => (string) ($deliverySchedules[0]['date'] ?? ''),
            'D_TIME_1' => (string) ($deliverySchedules[0]['time'] ?? ''),
            'D_DUE_1' => (string) ($deliverySchedules[0]['due'] ?? ''),
            'D_QTY_1' => (float) ($deliverySchedules[0]['quantity'] ?? 0),
            'D_DATE_2' => (string) ($deliverySchedules[1]['date'] ?? ''),
            'D_TIME_2' => (string) ($deliverySchedules[1]['time'] ?? ''),
            'D_DUE_2' => (string) ($deliverySchedules[1]['due'] ?? ''),
            'D_QTY_2' => (float) ($deliverySchedules[1]['quantity'] ?? 0),
            'D_DATE_3' => (string) ($deliverySchedules[2]['date'] ?? ''),
            'D_TIME_3' => (string) ($deliverySchedules[2]['time'] ?? ''),
            'D_DUE_3' => (string) ($deliverySchedules[2]['due'] ?? ''),
            'D_QTY_3' => (float) ($deliverySchedules[2]['quantity'] ?? 0),
            'D_DATE_4' => (string) ($deliverySchedules[3]['date'] ?? ''),
            'D_TIME_4' => (string) ($deliverySchedules[3]['time'] ?? ''),
            'D_DUE_4' => (string) ($deliverySchedules[3]['due'] ?? ''),
            'D_QTY_4' => (float) ($deliverySchedules[3]['quantity'] ?? 0),
            'D_DATE_5' => (string) ($deliverySchedules[4]['date'] ?? ''),
            'D_TIME_5' => (string) ($deliverySchedules[4]['time'] ?? ''),
            'D_DUE_5' => (string) ($deliverySchedules[4]['due'] ?? ''),
            'D_QTY_5' => (float) ($deliverySchedules[4]['quantity'] ?? 0),
            'D_DATE_6' => (string) ($deliverySchedules[5]['date'] ?? ''),
            'D_TIME_6' => (string) ($deliverySchedules[5]['time'] ?? ''),
            'D_DUE_6' => (string) ($deliverySchedules[5]['due'] ?? ''),
            'D_QTY_6' => (float) ($deliverySchedules[5]['quantity'] ?? 0),
            'D_DATE_7' => (string) ($deliverySchedules[6]['date'] ?? ''),
            'D_TIME_7' => (string) ($deliverySchedules[6]['time'] ?? ''),
            'D_DUE_7' => (string) ($deliverySchedules[6]['due'] ?? ''),
            'D_QTY_7' => (float) ($deliverySchedules[6]['quantity'] ?? 0),
            'D_DATE_8' => (string) ($deliverySchedules[7]['date'] ?? ''),
            'D_TIME_8' => (string) ($deliverySchedules[7]['time'] ?? ''),
            'D_DUE_8' => (string) ($deliverySchedules[7]['due'] ?? ''),
            'D_QTY_8' => (float) ($deliverySchedules[7]['quantity'] ?? 0),
            'D_DATE_9' => (string) ($deliverySchedules[8]['date'] ?? ''),
            'D_TIME_9' => (string) ($deliverySchedules[8]['time'] ?? ''),
            'D_DUE_9' => (string) ($deliverySchedules[8]['due'] ?? ''),
            'D_QTY_9' => (float) ($deliverySchedules[8]['quantity'] ?? 0),
            'D_DATE10' => (string) ($deliverySchedules[9]['date'] ?? ''),
            'D_TIME10' => (string) ($deliverySchedules[9]['time'] ?? ''),
            'D_DUE10' => (string) ($deliverySchedules[9]['due'] ?? ''),
            'D_QTY_10' => (float) ($deliverySchedules[9]['quantity'] ?? 0),
            // Audit trail - New/Created (WIB timezone)
            'NW_UID' => $currentUserId,
            'NW_DATE' => $nowDate,
            'NW_TIME' => $nowTime,
            // Audit trail - Amended (will be filled on update, WIB timezone)
            'AM_UID' => '',
            'AM_DATE' => '',
            'AM_TIME' => '',
            // Audit trail - Cancelled (will be filled on cancel, WIB timezone)
            'CX_UID' => '',
            'CX_DATE' => '',
            'CX_TIME' => '',
            // Audit trail - Printed (will be filled on print, WIB timezone)
            'PT_UID' => '',
            'PT_DATE' => '',
            'PT_TIME' => '',
            'SODateSK' => 0,
            'PODateSK' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // Build SO rows for Main + Fit components for this master card
        $rowsToInsert = [];

        // Only include the base row (Main component) if it has quantity in product design
        if (isset($productDesignQuantities['Main']) && $productDesignQuantities['Main'] > 0) {
            $rowsToInsert[] = $base;
        } else {
            Log::info('Skipping Main component - no quantity in product design', [
                'product_design_quantities' => $productDesignQuantities
            ]);
        }

        // Fetch all MC components (Main + Fit1-9) for this MC & customer
        $mcComponents = DB::table('MC')
            ->where('MCS_Num', $validated['master_card_seq'])
            ->where('AC_NUM', $validated['customer_code'])
            ->orderBy('COMP')
            ->get();

        foreach ($mcComponents as $componentRow) {
            // Skip if this is the same component as used for the base row to avoid duplicate Main
            if ($masterCardData && $componentRow->COMP === ($masterCardData->COMP ?? null)) {
                continue;
            }

            // Only create SO records for components that have quantities in product design
            // For Fit components, use Main quantity if not specified (backward compatibility)
            $componentName = (string) ($componentRow->COMP ?? '');
            $componentQty = $productDesignQuantities[$componentName] ?? null;

            // If this is a Fit component and no quantity specified, use Main component quantity
            if (($componentQty === null || $componentQty <= 0) &&
                (preg_match('/^fit\d+$/i', $componentName) || str_starts_with($componentName, 'Fit'))) {
                $componentQty = $productDesignQuantities['Main'] ?? $qty;
                Log::info('Fit component using Main quantity', [
                    'component' => $componentName,
                    'using_quantity' => $componentQty,
                    'main_quantity' => $productDesignQuantities['Main'] ?? $qty
                ]);
            }

            // Skip if still no quantity after fallback
            if (!$componentQty || $componentQty <= 0) {
                Log::info('Skipping component - no quantity available', [
                    'component' => $componentName,
                    'available_quantities' => $productDesignQuantities
                ]);
                continue;
            }

            // Extract per-component PD and dimension data
            $compModel = $componentRow->MODEL ?? $mcModel;
            $compPDesign = $componentRow->P_DESIGN ?? $mcPDesign;
            $compPartNumber = $componentRow->PART_NO ?? $mcPartNumber;

            $compIntL = (float)($componentRow->INT_LENGTH ?? 0);
            $compIntW = (float)($componentRow->INT_WIDTH ?? 0);
            $compIntH = (float)($componentRow->INT_HEIGHT ?? 0);
            $compExtL = (float)($componentRow->EXT_LENGTH ?? 0);
            $compExtW = (float)($componentRow->EXT_WIDTH ?? 0);
            $compExtH = (float)($componentRow->EXT_HEIGHT ?? 0);

            $compFlute = $componentRow->FLUTE ?? $flute;
            $compPq1 = $componentRow->SO_PQ1 ?? $pq1;
            $compPq2 = $componentRow->SO_PQ2 ?? $pq2;
            $compPq3 = $componentRow->SO_PQ3 ?? $pq3;
            $compPq4 = $componentRow->SO_PQ4 ?? $pq4;
            $compPq5 = $componentRow->SO_PQ5 ?? $pq5;

            $compMcGrossM2 = (float)($componentRow->MC_GROSS_M2_PER_PCS ?? 0);
            $compMcNetM2 = (float)($componentRow->MC_NET_M2_PER_PCS ?? 0);
            $compMcGrossKg = (float)($componentRow->MC_GROSS_KG_PER_SET ?? 0);
            $compMcNetKg = (float)($componentRow->MC_NET_KG_PER_PCS ?? 0);

            $compTotalGrossM2 = $compMcGrossM2 * $componentQty;
            $compTotalNetM2 = $compMcNetM2 * $componentQty;
            $compTotalGrossKg = $compMcGrossKg * $componentQty;
            $compTotalNetKg = $compMcNetKg * $componentQty;

            // Calculate component-level TOTAL_M3 using same logic as base row
            $compTotalM3 = 0;
            if ($compExtL > 0 && $compExtW > 0 && $compExtH > 0 && $componentQty > 0) {
                $compTotalM3 = ($compExtL * $compExtW * $compExtH * $componentQty) / 1000000000;
            } elseif ($compIntL > 0 && $compIntW > 0 && $compIntH > 0 && $componentQty > 0) {
                $compTotalM3 = ($compIntL * $compIntW * $compIntH * $componentQty) / 1000000000;
            }

            // Clone base row and override MC-related fields for this component
            $row = $base;

            $row['MODEL'] = $compModel;
            $row['PRODUCT'] = (string) ($validated['details'][0]['item_code'] ?? '');
            $row['COMP_Num'] = (string) ($componentRow->COMP ?? '');
            $row['P_DESIGN'] = $compPDesign;
            $row['PART_NUMBER'] = $compPartNumber;

            $row['SO_QTY'] = (float) $componentQty; // Use component-specific quantity

            $row['INT_L'] = $compIntL;
            $row['INT_W'] = $compIntW;
            $row['INT_H'] = $compIntH;
            $row['EXT_L'] = $compExtL;
            $row['EXT_W'] = $compExtW;
            $row['EXT_H'] = $compExtH;

            $row['FLUTE'] = $compFlute;
            $row['PQ1'] = $compPq1;
            $row['PQ2'] = $compPq2;
            $row['PQ3'] = $compPq3;
            $row['PQ4'] = $compPq4;
            $row['PQ5'] = $compPq5;

            $row['MC_GROSS_M2_PER_PCS'] = $compMcGrossM2;
            $row['MC_NET_M2_PER_PCS'] = $compMcNetM2;
            $row['TOTAL_SO_GROSS_M2'] = $compMcGrossM2 * $componentQty;
            $row['TOTAL_SO_NET_M2'] = $compMcNetM2 * $componentQty;
            $row['TOTAL_M3'] = (int) round($compTotalM3);
            $row['MC_GROSS_KG_PER_PCS'] = $compMcGrossKg;
            $row['MC_NET_KG_PER_PCS'] = $compMcNetKg;
            $row['TOTAL_SO_GROSS_KG'] = $compMcGrossKg * $componentQty;
            $row['TOTAL_SO_NET_KG'] = $compMcNetKg * $componentQty;

            $rowsToInsert[] = $row;

            Log::info('Component SO row created', [
                'component' => $componentName,
                'so_number' => $row['SO_Num'],
                'quantity' => $componentQty,
                'comp_num' => $row['COMP_Num']
            ]);
        }

        // Always INSERT new records, NEVER update existing
        // This ensures each sales order is unique even for same customer and master card

        // Debug: Log the data before insertion
        Log::info('About to insert SO records:', [
            'rows_count' => count($rowsToInsert),
            'sample_row' => !empty($rowsToInsert) ? $rowsToInsert[0] : null,
            'all_rows_data_types' => array_map(function($row) {
                return array_map(function($value) {
                    return [
                        'value' => $value,
                        'type' => gettype($value)
                    ];
                }, $row);
            }, $rowsToInsert)
        ]);

        // Validate and sanitize all data before insertion
        $sanitizedRows = [];
        foreach ($rowsToInsert as $row) {
            $sanitizedRow = [];
            foreach ($row as $key => $value) {
                // Handle different field types appropriately
                if (in_array($key, [
                    'YYYY', 'MM', 'SO_DMY', 'SO_Num', 'STS', 'TYPE', 'AC_Num', 'AC_NAME',
                    'SLM', 'IND', 'AREA', 'GROUP_', 'PO_Num', 'PO_DATE', 'MODEL', 'PRODUCT',
                    'COMP_Num', 'P_DESIGN', 'PART_NUMBER', 'UNIT', 'CURR', 'SO_REMARK',
                    'SO_INSTRUCTION_1', 'SO_INSTRUCTION_2', 'D_LOC_Num', 'DELIVERY_TO',
                    'DELIVERY_ADD_1', 'DELIVERY_ADD_2', 'DELIVERY_ADD_3', 'TOTAL_LM',
                    'FLUTE', 'PQ1', 'PQ2', 'PQ3', 'PQ4', 'PQ5', 'INT_L', 'INT_W', 'INT_H',
                    'EXT_L', 'EXT_W', 'EXT_H'
                ])) {
                    // String fields - ensure string type
                    $sanitizedRow[$key] = (string) $value;
                } elseif (in_array($key, [
                    'SO_QTY', 'UNIT_PRICE', 'EX_RATE', 'AMOUNT', 'BASE_AMOUNT',
                    'MC_GROSS_M2_PER_PCS', 'MC_NET_M2_PER_PCS', 'TOTAL_SO_GROSS_M2',
                    'TOTAL_SO_NET_M2', 'TOTAL_M3', 'MC_GROSS_KG_PER_PCS', 'MC_NET_KG_PER_PCS',
                    'TOTAL_SO_GROSS_KG', 'TOTAL_SO_NET_KG', 'D_QTY_1', 'D_QTY_2', 'D_QTY_3',
                    'D_QTY_4', 'D_QTY_5', 'D_QTY_6', 'D_QTY_7', 'D_QTY_8', 'D_QTY_9', 'D_QTY_10'
                ])) {
                    // Numeric fields - ensure numeric type
                    $sanitizedRow[$key] = is_numeric($value) ? (float) $value : 0.0;
                } elseif (in_array($key, [
                    'D_DATE_1', 'D_TIME_1', 'D_DUE_1', 'D_DATE_2', 'D_TIME_2', 'D_DUE_2',
                    'D_DATE_3', 'D_TIME_3', 'D_DUE_3', 'D_DATE_4', 'D_TIME_4', 'D_DUE_4',
                    'D_DATE_5', 'D_TIME_5', 'D_DUE_5', 'D_DATE_6', 'D_TIME_6', 'D_DUE_6',
                    'D_DATE_7', 'D_TIME_7', 'D_DUE_7', 'D_DATE_8', 'D_TIME_8', 'D_DUE_8',
                    'D_DATE_9', 'D_TIME_9', 'D_DUE_9', 'D_DATE_10', 'D_TIME_10', 'D_DUE_10'
                ])) {
                    // Date/Time fields - ensure string type
                    $sanitizedRow[$key] = (string) $value;
                } else {
                    // Unknown field - try to preserve original type
                    $sanitizedRow[$key] = $value;
                }
            }
            $sanitizedRows[] = $sanitizedRow;
        }

        Log::info('Sanitized SO records ready for insertion:', [
            'original_count' => count($rowsToInsert),
            'sanitized_count' => count($sanitizedRows),
            'sample_sanitized_row' => !empty($sanitizedRows) ? $sanitizedRows[0] : null
        ]);

        DB::table('so')->insert($sanitizedRows);

        Log::info('New sales order created', [
            'so_number' => $soNumber,
            'customer_code' => $validated['customer_code'],
            'master_card_seq' => $validated['master_card_seq'],
            'component_row_count' => count($rowsToInsert),
            'product_design_quantities' => $productDesignQuantities
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Sales order created successfully',
            'data' => [
                'so_number' => $soNumber
            ]
        ]);
    }

    /**
     * Get the last sales order number for a given customer (based on SO_Num order, Main component only).
     * Returns the full SO number and its parts: mm, yyyy, and sequence.
     */
    public function getLastSalesOrderForCustomer(string $customerCode): JsonResponse
    {
        // Find the latest SO record for this customer, Main component only
        $lastSo = DB::table('so')
            ->where('AC_Num', $customerCode)
            ->where('COMP_Num', 'Main')
            ->orderBy('SO_Num', 'desc')
            ->first();

        if (!$lastSo || empty($lastSo->SO_Num)) {
            return response()->json([
                'success' => false,
                'message' => 'No sales order found for this customer',
            ], 404);
        }

        $soNumber = (string) $lastSo->SO_Num;

        // Expect format MM-YYYY-XXXXX; fall back safely if format is different
        $parts = explode('-', $soNumber);
        $mm = $parts[0] ?? '';
        $yyyy = $parts[1] ?? '';
        $sequence = isset($parts[2]) ? $parts[2] : substr($soNumber, -5);

        return response()->json([
            'success' => true,
            'data' => [
                'so_number' => $soNumber,
                'mm' => $mm,
                'yyyy' => $yyyy,
                'sequence' => $sequence,
            ],
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
            'entries.*.delivery_quantities' => 'nullable|array', // Make optional for backward compatibility
            'entries.*.delivery_quantities.*.component' => 'required_with:entries.*.delivery_quantities|string',
            'entries.*.delivery_quantities.*.quantity' => 'required_with:entries.*.delivery_quantities|numeric|min:0.0000001',
            // Component field for legacy Product Design mode entries (Main, Fit1-9)
            'entries.*.component' => 'nullable|string',
            'entries.*.delivery_quantity' => 'nullable|numeric|min:0.0000001', // Keep for backward compatibility
            'entries.*.due_status' => 'required|string|in:ETD,ETA,TBA',
            'entries.*.remark' => 'nullable|string',
            'entries.*.delivery_code' => 'nullable|string',
            'entries.*.delivery_location' => 'nullable|string',
        ]);

        // Update existing SO with up to 10 schedule rows
        $soNumber = $validated['so_number'];
        $entries = $validated['entries'];

        // Get product design quantities from SO records to ensure consistency
        $soRecords = DB::table('so')
            ->where('SO_Num', 'like', preg_replace('/-(fit\d+)$/i', '', $soNumber) . '%')
            ->orderBy('SO_Num')
            ->get();

        $componentQuantities = [];
        foreach ($soRecords as $record) {
            $componentQuantities[$record->COMP_Num] = (float) $record->SO_QTY;
        }

        Log::info('Delivery Schedule - Component quantities from SO:', [
            'so_number' => $soNumber,
            'component_quantities' => $componentQuantities,
            'entries' => $entries
        ]);

        // Validate that delivery quantities don't exceed available quantities
        $totalDeliveryQuantities = [];
        foreach ($entries as $entry) {
            // Handle new format (delivery_quantities array) or old formats
            if (isset($entry['delivery_quantities']) && is_array($entry['delivery_quantities'])) {
                // New format: component-wise quantities per entry
                foreach ($entry['delivery_quantities'] as $dq) {
                    $component = $dq['component'] ?? 'Main';
                    $quantity = (float) ($dq['quantity'] ?? 0);

                    if ($quantity <= 0) {
                        continue;
                    }

                    if (!isset($totalDeliveryQuantities[$component])) {
                        $totalDeliveryQuantities[$component] = 0;
                    }
                    $totalDeliveryQuantities[$component] += $quantity;
                }
            } elseif (isset($entry['component']) && isset($entry['delivery_quantity'])) {
                // Legacy Product Design mode: each entry already represents a single component
                $component = (string) $entry['component'];
                $quantity = (float) $entry['delivery_quantity'];

                if ($quantity <= 0) {
                    continue;
                }

                if (!isset($totalDeliveryQuantities[$component])) {
                    $totalDeliveryQuantities[$component] = 0;
                }
                $totalDeliveryQuantities[$component] += $quantity;
            } elseif (isset($entry['delivery_quantity'])) {
                // Old format: single quantity without component info (assume Main component)
                $quantity = (float) $entry['delivery_quantity'];

                if ($quantity <= 0) {
                    continue;
                }

                if (!isset($totalDeliveryQuantities['Main'])) {
                    $totalDeliveryQuantities['Main'] = 0;
                }
                $totalDeliveryQuantities['Main'] += $quantity;
            }
        }

        // Check if any component exceeds available quantity
        $exceededComponents = [];
        foreach ($totalDeliveryQuantities as $component => $totalDelivered) {
            $available = $componentQuantities[$component] ?? 0;
            if ($totalDelivered > $available) {
                $exceededComponents[] = [
                    'component' => $component,
                    'delivered' => $totalDelivered,
                    'available' => $available
                ];
            }
        }

        if (!empty($exceededComponents)) {
            Log::info('Delivery Schedule - Validation failed: quantities exceed available', [
                'so_number' => $soNumber,
                'component_quantities' => $componentQuantities,
                'total_delivery_quantities' => $totalDeliveryQuantities,
                'exceeded_components' => $exceededComponents,
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Delivery quantities exceed available quantities',
                'errors' => $exceededComponents
            ], 422);
        }

        // Build schedule slots grouped by line_number (1..10)
        // All components belonging to the same visual row (line_number)
        // share the same schedule index (D_DATE_i/D_QTY_i).
        $scheduleSlots = [];

        foreach ($entries as $idx => $entry) {
            $lineNumber = isset($entry['line_number']) ? (int) $entry['line_number'] : ($idx + 1);
            if ($lineNumber < 1 || $lineNumber > 10) {
                continue;
            }

            $slotIndex = $lineNumber - 1; // 0-based index for internal array

            $date = (string) ($entry['schedule_date'] ?? '');
            $time = (string) ($entry['schedule_time'] ?? '');
            $due  = (string) ($entry['due_status'] ?? '');

            // Skip completely empty rows
            if ($date === '' && $time === '' && $due === '') {
                continue;
            }

            if (!isset($scheduleSlots[$slotIndex])) {
                $scheduleSlots[$slotIndex] = [
                    'date' => $date,
                    'time' => $time,
                    'due'  => $due,
                    'components' => [] // component => qty for this line
                ];
            } else {
                // Keep existing date/time/due if already set, only override if empty
                if ($scheduleSlots[$slotIndex]['date'] === '' && $date !== '') {
                    $scheduleSlots[$slotIndex]['date'] = $date;
                }
                if ($scheduleSlots[$slotIndex]['time'] === '' && $time !== '') {
                    $scheduleSlots[$slotIndex]['time'] = $time;
                }
                if ($scheduleSlots[$slotIndex]['due'] === '' && $due !== '') {
                    $scheduleSlots[$slotIndex]['due'] = $due;
                }
            }

            // Per-component quantities for this entry
            if (isset($entry['delivery_quantities']) && is_array($entry['delivery_quantities'])) {
                // New format: array of { component, quantity }
                foreach ($entry['delivery_quantities'] as $dq) {
                    $component = (string) ($dq['component'] ?? 'Main');
                    $quantity  = (float) ($dq['quantity'] ?? 0);

                    if ($quantity <= 0) {
                        continue;
                    }

                    if (!isset($scheduleSlots[$slotIndex]['components'][$component])) {
                        $scheduleSlots[$slotIndex]['components'][$component] = 0.0;
                    }
                    $scheduleSlots[$slotIndex]['components'][$component] += $quantity;
                }
            } elseif (isset($entry['component']) && isset($entry['delivery_quantity'])) {
                // Legacy Product Design mode: one component per entry
                $component = (string) $entry['component'];
                $quantity  = (float) $entry['delivery_quantity'];

                if ($quantity <= 0) {
                    continue;
                }

                if (!isset($scheduleSlots[$slotIndex]['components'][$component])) {
                    $scheduleSlots[$slotIndex]['components'][$component] = 0.0;
                }
                $scheduleSlots[$slotIndex]['components'][$component] += $quantity;
            } elseif (isset($entry['delivery_quantity'])) {
                // Old single-quantity format: treat as Main
                $quantity = (float) $entry['delivery_quantity'];

                if ($quantity <= 0) {
                    continue;
                }

                if (!isset($scheduleSlots[$slotIndex]['components']['Main'])) {
                    $scheduleSlots[$slotIndex]['components']['Main'] = 0.0;
                }
                $scheduleSlots[$slotIndex]['components']['Main'] += $quantity;
            }
        }

        Log::info('Delivery Schedule - Line-number grouped schedule slots for SO update', [
            'so_number' => $soNumber,
            'slots' => $scheduleSlots,
        ]);

        // Apply schedule slots to ALL related SO rows (Main + Fit components)
        // Each schedule index (1..n) is shared across components; D_QTY_i per row
        // stores only that component's quantity for that schedule.
        $affectedTotal = 0;

        foreach ($soRecords as $record) {
            $componentName = (string) ($record->COMP_Num ?? 'Main');
            $rowUpdates = [];

            // Reset all 10 schedule columns to avoid stale data from previous saves
            for ($i = 1; $i <= 10; $i++) {
                $dateCol = $i === 10 ? 'D_DATE10' : 'D_DATE_' . $i;
                $timeCol = $i === 10 ? 'D_TIME10' : 'D_TIME_' . $i;
                $dueCol  = $i === 10 ? 'D_DUE10'  : 'D_DUE_' . $i;
                $qtyCol  = $i === 10 ? 'D_QTY_10' : 'D_QTY_' . $i;

                $rowUpdates[$dateCol] = '';
                $rowUpdates[$timeCol] = '';
                $rowUpdates[$dueCol]  = '';
                $rowUpdates[$qtyCol]  = 0.0;
            }

            // Apply up to 10 possible schedule slots mapped by line_number
            for ($i = 0; $i < 10; $i++) {
                $index = $i + 1;

                if (!isset($scheduleSlots[$i])) {
                    continue; // leave defaults (cleared above)
                }

                $slot = $scheduleSlots[$i];

                $dateCol = $index === 10 ? 'D_DATE10' : 'D_DATE_' . $index;
                $timeCol = $index === 10 ? 'D_TIME10' : 'D_TIME_' . $index;
                $dueCol  = $index === 10 ? 'D_DUE10'  : 'D_DUE_' . $index;
                $qtyCol  = $index === 10 ? 'D_QTY_10' : 'D_QTY_' . $index;

                $rowUpdates[$dateCol] = $slot['date'];
                $rowUpdates[$timeCol] = $slot['time'];
                $rowUpdates[$dueCol]  = $slot['due'];

                // Quantity for this component in this schedule slot
                $qty = 0.0;
                if (isset($slot['components'][$componentName])) {
                    $qty = (float) $slot['components'][$componentName];
                } elseif ($componentName === 'Main' && isset($slot['components']['Main'])) {
                    $qty = (float) $slot['components']['Main'];
                }

                $rowUpdates[$qtyCol] = $qty;

                Log::info('Delivery entry calculated per component', [
                    'so_record' => $record->SO_Num,
                    'component' => $componentName,
                    'entry_index' => $index,
                    'slot' => $slot,
                    'component_quantity' => $qty,
                ]);
            }

            // D_LOC_Num is NOT updated here; it was already set during SO creation
            // Use both SO_Num and COMP_Num so each component row (Main, Fit1-9)
            // receives its own D_QTY_i values even when SO_Num is shared.
            $affected = DB::table('so')
                ->where('SO_Num', $record->SO_Num)
                ->where('COMP_Num', $record->COMP_Num)
                ->update($rowUpdates);

            $affectedTotal += $affected;
        }

        if ($affectedTotal === 0) {
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
                'entries' => $entries,
                'component_quantities_used' => $componentQuantities,
                'total_delivery_quantities' => $totalDeliveryQuantities
            ]
        ]);
    }

    public function getDeliverySchedules(string $soNumber): JsonResponse
    {
        // Clean any output buffers to prevent BOM issues
        while (ob_get_level()) {
            ob_end_clean();
        }

        // Get all sales orders with the same base SO number (including main and fits)
        // Extract base SO number by removing any suffix
        $baseSoNumber = preg_replace('/-(fit\d+)$/i', '', $soNumber);

        // Get all related SO records (main + fit1-9)
        $allSoRecords = DB::table('so')
            ->where('SO_Num', 'like', $baseSoNumber . '%')
            ->orderBy('SO_Num')
            ->get();

        if ($allSoRecords->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Sales Order not found'
            ], 404);
        }

        // Find the main record
        $mainSo = $allSoRecords->firstWhere('COMP_Num', 'Main');
        if (!$mainSo) {
            // If no Main found, use the first record as main
            $mainSo = $allSoRecords->first();
        }

        // Extract delivery schedules from D_DATE_1..10, D_QTY_1..10, etc.
        $schedules = [];
        for ($i = 1; $i <= 10; $i++) {
            $dateCol = $i === 10 ? 'D_DATE10' : 'D_DATE_' . $i;
            $timeCol = $i === 10 ? 'D_TIME10' : 'D_TIME_' . $i;
            $dueCol  = $i === 10 ? 'D_DUE10'  : 'D_DUE_' . $i;
            $qtyCol  = $i === 10 ? 'D_QTY_10' : 'D_QTY_' . $i;

            $date = $mainSo->$dateCol ?? '';
            $time = $mainSo->$timeCol ?? '';
            $due = $mainSo->$dueCol ?? '';
            $qty = $mainSo->$qtyCol ?? 0;

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

        // Get customer address and contact info
        $customer = DB::table('CUSTOMER')->where('CODE', $mainSo->AC_Num)->first();
        $customerAddress = '';
        $customerTel = '';
        $customerFax = '';
        if ($customer) {
            $addressParts = array_filter([
                $customer->ADDRESS1 ?? '',
                $customer->ADDRESS2 ?? '',
                $customer->ADDRESS3 ?? ''
            ]);
            $customerAddress = implode(', ', $addressParts);
            $customerTel = $customer->TEL_NO ?? '';
            $customerFax = $customer->FAX_NO ?? '';
        }

        // Get delivery address
        $deliveryAddress = '';
        if ($mainSo->DELIVERY_TO) {
            $addressParts = array_filter([
                $mainSo->DELIVERY_ADD_1 ?? '',
                $mainSo->DELIVERY_ADD_2 ?? '',
                $mainSo->DELIVERY_ADD_3 ?? ''
            ]);
            $deliveryAddress = implode(', ', $addressParts);
        }

        // Build details array with main and all fit components
        $details = [];

        // Add main component first
        $details[] = [
            'item_code' => $mainSo->PRODUCT,
            'item_description' => $mainSo->MODEL . ' - ' . $mainSo->P_DESIGN,
            'order_quantity' => (float) $mainSo->SO_QTY,
            'unit_price' => (float) $mainSo->UNIT_PRICE,
            'uom' => $mainSo->UNIT,
            'flute' => $mainSo->FLUTE,
            'paper_quality_1' => $mainSo->PQ1,
            'paper_quality_2' => $mainSo->PQ2,
            'paper_quality_3' => $mainSo->PQ3,
            'dimensions' => [
                'int_l' => (float) $mainSo->INT_L,
                'int_w' => (float) $mainSo->INT_W,
                'int_h' => (float) $mainSo->INT_H,
                'ext_l' => (float) $mainSo->EXT_L,
                'ext_w' => (float) $mainSo->EXT_W,
                'ext_h' => (float) $mainSo->EXT_H,
            ],
            'gross_kg' => (float) ($mainSo->MC_GROSS_KG_PER_PCS ?? 0),
            'price_per_m2' => (float) ($mainSo->MC_NET_M2_PER_PCS ?? 0),
            'comp_num' => $mainSo->COMP_Num,
            'p_design' => $mainSo->P_DESIGN,
            'per_set' => (float) ($mainSo->PER_SET ?? 1),
            'roll_size' => ''
        ];

        // Add fit components (fit1-9)
        foreach ($allSoRecords as $soRecord) {
            if ($soRecord->COMP_Num !== 'Main' &&
                (preg_match('/^fit\d+$/i', $soRecord->COMP_Num) || str_starts_with($soRecord->COMP_Num, 'Fit'))) {

                $details[] = [
                    'item_code' => $soRecord->PRODUCT,
                    'item_description' => $soRecord->MODEL . ' - ' . $soRecord->P_DESIGN . ' (' . $soRecord->COMP_Num . ')',
                    'order_quantity' => (float) $soRecord->SO_QTY,
                    'unit_price' => (float) $soRecord->UNIT_PRICE,
                    'uom' => $soRecord->UNIT,
                    'flute' => $soRecord->FLUTE,
                    'paper_quality_1' => $soRecord->PQ1,
                    'paper_quality_2' => $soRecord->PQ2,
                    'paper_quality_3' => $soRecord->PQ3,
                    'dimensions' => [
                        'int_l' => (float) $soRecord->INT_L,
                        'int_w' => (float) $soRecord->INT_W,
                        'int_h' => (float) $soRecord->INT_H,
                        'ext_l' => (float) $soRecord->EXT_L,
                        'ext_w' => (float) $soRecord->EXT_W,
                        'ext_h' => (float) $soRecord->EXT_H,
                    ],
                    'gross_kg' => (float) ($soRecord->MC_GROSS_KG_PER_PCS ?? 0),
                    'price_per_m2' => (float) ($soRecord->MC_NET_M2_PER_PCS ?? 0),
                    'comp_num' => $soRecord->COMP_Num,
                    'p_design' => $soRecord->P_DESIGN,
                    'per_set' => (float) ($soRecord->PER_SET ?? 1),
                    'roll_size' => ''
                ];
            }
        }

        return response()->json([
            'success' => true,
            'data' => [
                'sales_order' => [
                    'so_number' => $mainSo->SO_Num,
                    'customer_code' => $mainSo->AC_Num,
                    'customer_name' => $mainSo->AC_NAME,
                    'customer_address' => $customerAddress,
                    'customer_tel' => $customerTel,
                    'customer_fax' => $customerFax,
                    'customer_po_number' => $mainSo->PO_Num,
                    'po_date' => $mainSo->PO_DATE,
                    'master_card_seq' => $mainSo->MCS_Num,
                    'master_card_model' => $mainSo->MODEL,
                    'part_number' => $mainSo->PART_NUMBER,
                    'p_design' => $mainSo->P_DESIGN,
                    'currency' => $mainSo->CURR,
                    'credit_terms' => '',
                    'remark' => $mainSo->SO_REMARK,
                    'instruction1' => $mainSo->SO_INSTRUCTION_1,
                    'instruction2' => $mainSo->SO_INSTRUCTION_2,
                    'status' => $mainSo->STS,
                    'delivery_location' => $mainSo->DELIVERY_TO,
                    'delivery_address' => $deliveryAddress,
                    'details' => $details
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
            // Use TRY_CAST to safely handle non-numeric suffixes like "-Fit1" in SO_Num
            $lastSO = DB::table('so')
                ->where('MM', $month)
                ->where('YYYY', $year)
                ->whereRaw('TRY_CAST(RIGHT(SO_Num, 5) AS INT) IS NOT NULL')
                ->orderByRaw('TRY_CAST(RIGHT(SO_Num, 5) AS INT) DESC')
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
        $exRate = isset($validated['exchange_rate']) ? (float) $validated['exchange_rate'] : 1.0;
        if ($exRate <= 0) {
            $exRate = 1.0;
        }
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

        // Get current time in WIB timezone (UTC+7)
        $nowWib = $this->getNowWib();
        $nowDate = $nowWib->format('Y-m-d');
        $nowTime = $nowWib->format('H:i');

        // Get authenticated user ID for audit trail
        $currentUserId = $this->getCurrentUserId();
        Log::info('Creating SO (API) with user audit trail', [
            'user_id' => $currentUserId,
            'so_number' => $soNumber,
            'timestamp_wib' => $nowWib->format('Y-m-d H:i:s')
        ]);

        $base = [
            'YYYY' => $nowWib->format('Y'),
            'MM' => $nowWib->format('m'),
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
            'SO_QTY' => (float) ($productDesignQuantities['Main'] ?? $qty),
            'UNIT_PRICE' => $price,
            'CURR' => (string) ($validated['currency'] ?? ''),
            'EX_RATE' => $exRate,
            'AMOUNT' => $amount,
            'BASE_AMOUNT' => $amount * $exRate,
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
            // Audit trail - New/Created (WIB timezone)
            'NW_UID' => $currentUserId,
            'NW_DATE' => $nowDate,
            'NW_TIME' => $nowTime,
            // Audit trail - Amended (will be filled on update, WIB timezone)
            'AM_UID' => '',
            'AM_DATE' => '',
            'AM_TIME' => '',
            // Audit trail - Cancelled (will be filled on cancel, WIB timezone)
            'CX_UID' => '',
            'CX_DATE' => '',
            'CX_TIME' => '',
            // Audit trail - Printed (will be filled on print, WIB timezone)
            'PT_UID' => '',
            'PT_DATE' => '',
            'PT_TIME' => '',
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

            // Filter by Master Card sequence (MCS_Num) - used by Search by Master Card flows
            if ($request->has('master_card_seq') && !empty($request->master_card_seq)) {
                $query->where('MCS_Num', $request->master_card_seq);
                Log::info('Filtering by master_card_seq:', ['master_card_seq' => $request->master_card_seq]);
            }

            // Filter by SO number range - Support both old and new format
            if ($request->has('from_so') && $request->has('to_so')) {
                $fromSO = $request->input('from_so');
                $toSO = $request->input('to_so');

                Log::info('Filtering SO range:', ['from_so' => $fromSO, 'to_so' => $toSO]);

                // Check if we're dealing with new format (MM-YYYY-XXXXX) or old format
                if (preg_match('/^\d{2}-\d{4}-\d{5}$/', $fromSO) && preg_match('/^\d{2}-\d{4}-\d{5}$/', $toSO)) {
                    // New format: MM-YYYY-XXXXX - use whereBetween for exact range
                    $query->whereBetween('SO_Num', [$fromSO, $toSO]);
                    Log::info('Using new format SO range filter');
                } else {
                    // Old format: SO-YYYYMMDD-XXXX - extract and compare by date and sequence
                    $query->where(function($q) use ($fromSO, $toSO) {
                        $q->whereBetween('SO_Num', [$fromSO, $toSO]);
                    });
                    Log::info('Using old format SO range filter');
                }
            }

            // Filter by status
            if ($request->has('status')) {
                $statuses = $request->input('status');
                Log::info('Filtering by status:', ['status' => $statuses]);
                if (is_array($statuses) && !empty($statuses)) {
                    $statusMap = [
                        'outstanding' => 'Outstanding',
                        'partial' => 'Partial',
                        'completed' => 'Completed',
                        'closed' => 'Closed',
                        'cancelled' => 'Cancelled'
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

            // Filter to only show main component (exclude fit1-9)
            $query->where('COMP_Num', 'Main');
            Log::info('Filtering by COMP_Num: Main only');

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
     * Get all outstanding sales orders for AmendSO
     */
    public function getOutstandingSalesOrders(Request $request): JsonResponse
    {
        Log::info('getOutstandingSalesOrders called with params:', $request->all());

        // Clean output buffers
        while (ob_get_level()) {
            ob_end_clean();
        }

        try {
            // First, let's check what statuses exist in the database
            $allStatuses = DB::table('so')->select('STS')->distinct()->get();
            Log::info('All statuses in SO table:', ['statuses' => $allStatuses->pluck('STS')->toArray()]);

            // Count total records
            $totalRecords = DB::table('so')->count();
            Log::info('Total SO records:', ['count' => $totalRecords]);

            // Count records by status
            $statusCounts = DB::table('so')
                ->select('STS', DB::raw('COUNT(*) as count'))
                ->groupBy('STS')
                ->get();
            Log::info('Status counts:', ['counts' => $statusCounts->toArray()]);

            $query = DB::table('so')
                ->leftJoin('CUSTOMER', 'so.AC_Num', '=', 'CUSTOMER.CODE');

            $query->where('so.COMP_Num', 'Main');

            // Filter by status: include typical outstanding statuses PLUS cancelled
            // so that cancelled SOs still appear in the table (frontend blocks selection)
            $query->where(function($q) {
                $q->where('so.STS', 'OPEN')
                  ->orWhere('so.STS', 'Outstanding')
                  ->orWhere('so.STS', 'OUTSTANDING')
                  ->orWhere('so.STS', 'Outs')
                  ->orWhere('so.STS', 'OUTS')
                  ->orWhere('so.STS', 'O') // Single letter
                  ->orWhere('so.STS', 'PENDING')
                  ->orWhere('so.STS', 'ACTIVE')
                  ->orWhere('so.STS', 'NEW')
                  ->orWhere('so.STS', '1') // Numeric status
                  // Also include cancelled statuses so users can still see them
                  ->orWhere('so.STS', 'CANCEL')
                  ->orWhere('so.STS', 'Cancelled')
                  ->orWhere('so.STS', 'CANCELLED')
                  ->orWhereNull('so.STS')
                  ->orWhere('so.STS', '');
            });

            Log::info('Outstanding SO query built');

            // Optional filters for search functionality
            if ($request->has('month') && !empty($request->month) && $request->month !== '0') {
                $month = str_pad($request->input('month'), 2, '0', STR_PAD_LEFT);
                $query->where('so.MM', $month);
            }

            if ($request->has('year') && !empty($request->year) && $request->year !== '0') {
                $year = $request->input('year');
                $query->where('so.YYYY', $year);
            }

            if ($request->has('sequence') && !empty($request->sequence) && $request->sequence !== '0') {
                $query->whereRaw("RIGHT(so.SO_Num, 5) = ?", [str_pad($request->sequence, 5, '0', STR_PAD_LEFT)]);
            }

            if ($request->has('so_number') && !empty($request->so_number)) {
                $query->where('so.SO_Num', 'like', '%' . $request->so_number . '%');
            }

            $salesOrders = $query->select([
                    'so.SO_Num',
                    'so.AC_Num',
                    'CUSTOMER.NAME as AC_NAME',
                    'so.PO_Num',
                    'so.MCS_Num',
                    'so.MODEL',
                    'so.P_DESIGN',
                    'so.SLM',
                    'so.GROUP_',
                    'so.TYPE',
                    'so.STS',
                    'so.SO_QTY',
                    'so.UNIT',
                    'so.UNIT_PRICE',
                    'so.AMOUNT',
                    'so.SO_REMARK',
                    'so.SO_INSTRUCTION_1',
                    'so.SO_INSTRUCTION_2',
                    'so.D_LOC_Num'
                ])
                ->orderBy('so.SO_Num', 'desc')
                ->limit(200) // Increased limit for AmendSO
                ->get();

            Log::info('Outstanding SO query result count:', ['count' => $salesOrders->count()]);

            // If no outstanding orders found, get some sample data for debugging
            if ($salesOrders->count() === 0 && $totalRecords > 0) {
                Log::warning('No outstanding orders found, getting sample data for debugging');
                $sampleOrders = DB::table('so')
                    ->leftJoin('CUSTOMER', 'so.AC_Num', '=', 'CUSTOMER.CODE')
                    ->select([
                        'so.SO_Num',
                        'so.AC_Num',
                        'CUSTOMER.NAME as AC_NAME',
                        'so.PO_Num',
                        'so.MCS_Num',
                        'so.MODEL',
                        'so.P_DESIGN',
                        'so.SLM',
                        'so.GROUP_',
                        'so.TYPE',
                        'so.STS',
                        'so.SO_QTY',
                        'so.UNIT',
                        'so.UNIT_PRICE',
                        'so.AMOUNT',
                        'so.SO_REMARK',
                        'so.SO_INSTRUCTION_1',
                        'so.SO_INSTRUCTION_2',
                        'so.D_LOC_Num',
                    ])
                    ->limit(10)
                    ->get();

                Log::info('Sample SO data:', ['sample' => $sampleOrders->toArray()]);

                // For debugging purposes, return sample data if no outstanding found
                $salesOrders = $sampleOrders;
                Log::info('Using sample data for debugging, count:', ['count' => $salesOrders->count()]);
            }

            // Log first few results for debugging
            if ($salesOrders->count() > 0) {
                Log::info('First SO found:', [
                    'so_number' => $salesOrders->first()->SO_Num,
                    'status' => $salesOrders->first()->STS,
                    'customer' => $salesOrders->first()->AC_Num
                ]);
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
                    'status' => $order->STS, // Return actual status from database
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
                    'total_so_records' => $totalRecords,
                    'all_statuses' => $allStatuses->pluck('STS')->toArray(),
                    'status_counts' => $statusCounts->toArray(),
                    'query_result_count' => $salesOrders->count()
                ]
            ], 200, ['Content-Type' => 'application/json; charset=utf-8']);

        } catch (\Exception $e) {
            Log::error('Error fetching outstanding sales orders:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error fetching outstanding sales orders: ' . $e->getMessage()
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

            // Fetch sales order from SO table with customer data
            $salesOrder = DB::table('so')
                ->leftJoin('CUSTOMER', 'so.AC_Num', '=', 'CUSTOMER.CODE')
                ->where('so.SO_Num', $soNumber)
                ->select('so.*',
                    'CUSTOMER.NAME as AC_NAME',
                    'CUSTOMER.ADDRESS1 as CUST_ADDRESS1',
                    'CUSTOMER.ADDRESS2 as CUST_ADDRESS2',
                    'CUSTOMER.ADDRESS3 as CUST_ADDRESS3'
                )
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
                // Beberapa database menggunakan kolom 'Name' (huruf N kapital)
                // sementara lainnya memakai 'NAME' atau 'name'. Coba semua variasi
                // sebelum fallback ke kode SLM.
                $salespersonName = $salesperson->Name
                    ?? $salesperson->NAME
                    ?? $salesperson->name
                    ?? $salesOrder->SLM
                    ?? '';
            } else {
                // Jika tabel salesperson tidak bisa diakses, gunakan kode saja
                $salespersonName = $salesOrder->SLM ?? '';
            }

            $orderInfo = [
                'customer_name' => $salesOrder->AC_NAME ?? '',
                'customer_address' => trim(implode("\n", array_filter([
                    $salesOrder->CUST_ADDRESS1 ?? '',
                    $salesOrder->CUST_ADDRESS2 ?? '',
                    $salesOrder->CUST_ADDRESS3 ?? ''
                ]))),
                'model' => $salesOrder->MODEL ?? '',
                'order_mode' => '0-Order by Customer + Deliver & Invoice to Customer',
                'salesperson_code' => $salesOrder->SLM ?? '',
                'salesperson_name' => $salespersonName,
                'order_group' => $salesOrder->GROUP_ ?? 'Sales',
                'order_type' => $salesOrder->TYPE ?? 'S1',
                // Currency and exchange rate
                'currency' => $salesOrder->CURR ?? 'IDR',
                'exchange_rate' => $salesOrder->EX_RATE ?? 1,
                // Added fields for UI bindings
                'so_status' => $salesOrder->STS ?? '',
                'so_date' => $salesOrder->SO_DMY ?? '',
                'po_date' => $salesOrder->PO_DATE ?? '',
                'customer_po_number' => $salesOrder->PO_Num ?? '',
                // Additional fields for AmendSO
                'lot_number' => $salesOrder->LOT_NUM ?? '',
                // Note: TAX column does not exist in SO table
                // 'sales_tax' => ($salesOrder->TAX ?? 'N') === 'Y',
                'remark' => $salesOrder->SO_REMARK ?? '',
                'instruction1' => $salesOrder->SO_INSTRUCTION_1 ?? '',
                'instruction2' => $salesOrder->SO_INSTRUCTION_2 ?? '',
                'set_quantity' => $salesOrder->SO_QTY ?? '',
                // Delivery location fields
                'delivery_code' => $salesOrder->D_LOC_Num ?? '',
                'delivery_to' => $salesOrder->DELIVERY_TO ?? '',
                'delivery_address_1' => $salesOrder->DELIVERY_ADD_1 ?? '',
                'delivery_address_2' => $salesOrder->DELIVERY_ADD_2 ?? '',
            ];

            // Format item details from SO table - Get Main component unit
            $mainUnit = 'PCS'; // Default fallback
            try {
                $mainSO = DB::table('so')
                    ->where('SO_Num', $soNumber)
                    ->where('COMP_Num', 'Main')
                    ->first();
                if ($mainSO && !empty($mainSO->UNIT)) {
                    $mainUnit = $mainSO->UNIT;
                }
            } catch (\Exception $e) {
                Log::warning('Error fetching Main component unit from SO table', [
                    'so_number' => $soNumber,
                    'error' => $e->getMessage()
                ]);
            }

            $itemDetails = [
                'pd' => $salesOrder->P_DESIGN ?? '',
                'pcs' => $salesOrder->PER_SET ?? '1',
                'unit' => $mainUnit,
                'order_qty' => $salesOrder->SO_QTY ?? '0',
                'net_delivery' => '0',
                'balance' => $salesOrder->SO_QTY ?? '0',
            ];

            // Calculate net delivery from DO table (sum of DO_Qty) for Main component only
            $netDelivery = 0;
            $hasDoRows = false;
            try {
                // Check if DO rows exist for Main component
                $hasDoRows = DB::table('DO')
                    ->where('SO_Num', $soNumber)
                    ->where('COMP', 'Main')
                    ->exists();

                if ($hasDoRows) {
                    // Sum DO_Qty for Main component only
                    $netDelivery = (float) DB::table('DO')
                        ->where('SO_Num', $soNumber)
                        ->where('COMP', 'Main')
                        ->sum('DO_Qty');
                }
            } catch (\Exception $e) {
                Log::warning('Error computing DO net delivery for Main component; falling back to SO schedule fields', [
                    'so_number' => $soNumber,
                    'error' => $e->getMessage()
                ]);
                $hasDoRows = false;
            }

            // If no DO rows for Main, show net_delivery as empty string and balance equals order
            if (!$hasDoRows) {
                $itemDetails['net_delivery'] = '';
                $itemDetails['balance'] = $salesOrder->SO_QTY ?? '0';
            } else {
                $itemDetails['net_delivery'] = $netDelivery;
                // Calculate balance per component: Main.Order - Main.Delivery
                $itemDetails['balance'] = (float)($salesOrder->SO_QTY ?? 0) - $netDelivery;
            }
            // Include pcs per bundle from MC if available
            if ($masterCard && isset($masterCard->PCS_PER_BLD)) {
                $itemDetails['pcs_per_bdl'] = (float) $masterCard->PCS_PER_BLD;
            }

            // Get fittings from master card components if available
            $fittings = [];

            try {
                if (!empty($salesOrder->MCS_Num) && !empty($salesOrder->AC_Num)) {
                    // Fetch all MC rows for this master card & customer
                    $mcComponents = DB::table('MC')
                        ->where('MCS_Num', $salesOrder->MCS_Num)
                        ->where('AC_NUM', $salesOrder->AC_Num)
                        ->orderBy('COMP')
                        ->get();

                    foreach ($mcComponents as $componentRow) {
                        $compName = trim((string) ($componentRow->COMP ?? ''));

                        // Skip Main row; only use Fit components as fittings
                        if ($compName === '' || strcasecmp($compName, 'Main') === 0) {
                            continue;
                        }

                        // Get order_qty and unit from SO table for this component
                        $componentSO = DB::table('so')
                            ->where('SO_Num', $soNumber)
                            ->where('COMP_Num', $compName)
                            ->first();

                        $componentOrderQty = $componentSO ? ($componentSO->SO_QTY ?? 0) : 0;
                        $componentUnit = $componentSO ? ($componentSO->UNIT ?? 'PCS') : 'PCS';

                        // Calculate net delivery from DO table for this component
                        $componentNetDelivery = 0;
                        $hasComponentDoRows = false;
                        try {
                            $hasComponentDoRows = DB::table('DO')
                                ->where('SO_Num', $soNumber)
                                ->where('COMP', $compName)
                                ->exists();

                            if ($hasComponentDoRows) {
                                $componentNetDelivery = (float) DB::table('DO')
                                    ->where('SO_Num', $soNumber)
                                    ->where('COMP', $compName)
                                    ->sum('DO_Qty');
                            }
                        } catch (\Exception $e) {
                            Log::warning('Error computing DO net delivery for component', [
                                'so_number' => $soNumber,
                                'component' => $compName,
                                'error' => $e->getMessage()
                            ]);
                        }

                        // Calculate balance per component: ORDER - DELIVERY
                        $componentBalance = $componentOrderQty - $componentNetDelivery;

                        $fittings[] = [
                            // Optional name for debugging/other UIs; frontend consumers use index-based mapping
                            'name' => $compName,
                            // Use P_DESIGN, PCS_SET from each MC component row, but UNIT from SO table
                            'design' => $componentRow->P_DESIGN ?? '',
                            'pcs' => $componentRow->PCS_SET ?? '',
                            'unit' => $componentUnit, // Use unit from SO table for this component
                            'part_number' => $componentRow->PART_NO ?? '',
                            // Add order, delivery, and balance per component
                            'order_qty' => $componentOrderQty,
                            'net_delivery' => $hasComponentDoRows ? $componentNetDelivery : '',
                            'balance' => $componentBalance,
                        ];

                        // Limit to first 9 components (Fit1..Fit9)
                        if (count($fittings) >= 9) {
                            break;
                        }
                    }
                }
            } catch (\Exception $e) {
                Log::warning('Error fetching fittings from MC components; falling back to empty fittings array', [
                    'so_number' => $soNumber,
                    'mc_seq' => $salesOrder->MCS_Num ?? null,
                    'customer_code' => $salesOrder->AC_Num ?? null,
                    'error' => $e->getMessage(),
                ]);
                $fittings = [];
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

    /**
     * Update sales order (Amend SO)
     */
    public function updateSalesOrder(Request $request, $soNumber): JsonResponse
    {
        // Clean output buffers
        while (ob_get_level()) {
            ob_end_clean();
        }

        try {
            Log::info('Updating sales order', [
                'so_number' => $soNumber,
                'request_data' => $request->all()
            ]);

            // Validate request
            $validated = $request->validate([
                'po_date' => 'nullable|string',
                'set_quantity' => 'nullable|string',
                'order_group' => 'nullable|string',
                'order_type' => 'nullable|string',
                'lot_number' => 'nullable|string',
                'remark' => 'nullable|string',
                'instruction1' => 'nullable|string',
                'instruction2' => 'nullable|string',
                'delivery_location' => 'nullable|array',
            ]);

            // Check if SO exists
            $salesOrder = DB::table('so')->where('SO_Num', $soNumber)->first();

            if (!$salesOrder) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sales order not found'
                ], 404, ['Content-Type' => 'application/json; charset=utf-8']);
            }

            // Prepare update data
            $updateData = [];

            if ($request->has('po_date') && !empty($request->po_date)) {
                $updateData['PO_DATE'] = $request->po_date;
            }

            if ($request->has('set_quantity')) {
                $updateData['SO_QTY'] = $request->set_quantity;
            }

            if ($request->has('order_group')) {
                $updateData['GROUP_'] = $request->order_group;
            }

            if ($request->has('order_type')) {
                $updateData['TYPE'] = $request->order_type;
            }

            if ($request->has('lot_number')) {
                // Only update LOT_NUM when an explicit non-null value is provided.
                // This avoids violating the NOT NULL constraint when frontend sends lot_number: null.
                if ($request->lot_number !== null) {
                    $updateData['LOT_NUM'] = $request->lot_number;
                }
            }

            // Note: TAX column does not exist in SO table, skipping sales_tax update
            // if ($request->has('sales_tax')) {
            //     $updateData['TAX'] = $request->sales_tax;
            // }

            if ($request->has('remark')) {
                $updateData['SO_REMARK'] = $request->remark;
            }

            if ($request->has('instruction1')) {
                $updateData['SO_INSTRUCTION_1'] = $request->instruction1;
            }

            if ($request->has('instruction2')) {
                $updateData['SO_INSTRUCTION_2'] = $request->instruction2;
            }

            // Note: ANALYSIS_CODE column does not exist in SO table
            // if ($request->has('analysis_code')) {
            //     $updateData['ANALYSIS_CODE'] = $request->analysis_code;
            // }

            // ===================================================================
            // DELIVERY LOCATION UPDATE - Dual Mode System
            // ===================================================================
            if ($request->has('delivery_location')) {
                $deliveryData = $request->input('delivery_location', []);
                $dLocNum = (string) ($deliveryData['delivery_code'] ?? '');

                // Check if delivery code is provided
                if (!empty($dLocNum)) {
                    // MODE 2: ALTERNATE ADDRESS
                    // Update D_LOC_Num with geographical code from alternate address
                    $updateData['D_LOC_Num'] = $dLocNum;
                    $updateData['DELIVERY_TO'] = (string) ($deliveryData['customer_name'] ?? '');

                    // Split delivery address into 3 lines with intelligent handling
                    if (isset($deliveryData['address']) && !empty($deliveryData['address'])) {
                        $rawAddress = $deliveryData['address'];

                        // Try to split by newline first
                        if (strpos($rawAddress, "\n") !== false || strpos($rawAddress, "\r\n") !== false) {
                            // Address has line breaks - split normally
                            $addressLines = preg_split('/\r\n|\r|\n/', $rawAddress);
                            $updateData['DELIVERY_ADD_1'] = (string) trim($addressLines[0] ?? '');
                            $updateData['DELIVERY_ADD_2'] = (string) trim($addressLines[1] ?? '');
                            $updateData['DELIVERY_ADD_3'] = (string) trim($addressLines[2] ?? '');
                        } else {
                            // Address is single line - check if it's too long for one field
                            $maxLength = 250; // VARCHAR(250) limit
                            if (strlen($rawAddress) <= $maxLength) {
                                // Fit in one line
                                $updateData['DELIVERY_ADD_1'] = $rawAddress;
                                $updateData['DELIVERY_ADD_2'] = '';
                                $updateData['DELIVERY_ADD_3'] = '';
                            } else {
                                // Too long - split intelligently by commas or hyphens
                                $parts = preg_split('/[,\-]/', $rawAddress, 3);
                                $updateData['DELIVERY_ADD_1'] = (string) trim($parts[0] ?? '');
                                $updateData['DELIVERY_ADD_2'] = (string) trim($parts[1] ?? '');
                                $updateData['DELIVERY_ADD_3'] = (string) trim($parts[2] ?? '');
                            }
                        }
                    } else {
                        $updateData['DELIVERY_ADD_1'] = '';
                        $updateData['DELIVERY_ADD_2'] = '';
                        $updateData['DELIVERY_ADD_3'] = '';
                    }

                    Log::info('SO Update D_LOC_Num - Mode 2: Alternate Address', [
                        'so_number' => $soNumber,
                        'd_loc_num' => $dLocNum,
                        'delivery_to' => $updateData['DELIVERY_TO'],
                        'address_1' => $updateData['DELIVERY_ADD_1'],
                        'address_2' => $updateData['DELIVERY_ADD_2'],
                        'address_3' => $updateData['DELIVERY_ADD_3'],
                        'address_raw' => $rawAddress ?? '',
                        'source' => 'customer_alternate_addresses.delivery_code'
                    ]);

                } else {
                    // MODE 1: DEFAULT - Use main customer address from CUSTOMER table
                    $customerData = DB::table('CUSTOMER')->where('CODE', $salesOrder->AC_Num)->first();

                    if ($customerData) {
                        // Update D_LOC_Num with geographical code from customer
                        $updateCustomerAccount = DB::table('update_customer_accounts')
                            ->where('customer_code', $salesOrder->AC_Num)
                            ->first();

                        $dLocNum = '';
                        if ($updateCustomerAccount && !empty($updateCustomerAccount->geographical)) {
                            $dLocNum = $updateCustomerAccount->geographical;
                        } elseif (!empty($customerData->AREA)) {
                            $dLocNum = $customerData->AREA;
                        }

                        $updateData['D_LOC_Num'] = $dLocNum;
                        $updateData['DELIVERY_TO'] = $customerData->NAME ?? '';
                        $updateData['DELIVERY_ADD_1'] = $customerData->ADDRESS1 ?? '';
                        $updateData['DELIVERY_ADD_2'] = $customerData->ADDRESS2 ?? '';
                        $updateData['DELIVERY_ADD_3'] = $customerData->ADDRESS3 ?? '';

                        Log::info('SO Update D_LOC_Num - Mode 1: Customer Main Address', [
                            'so_number' => $soNumber,
                            'd_loc_num' => $dLocNum ?: '(no geographical code found)',
                            'delivery_to' => $updateData['DELIVERY_TO'],
                            'geographical_source' => $updateCustomerAccount ? 'update_customer_accounts.geographical' : 'CUSTOMER.AREA',
                            'source' => 'CUSTOMER table'
                        ]);
                    }
                }
            }

            // Add amendment tracking with WIB timezone
            $nowWib = $this->getNowWib();
            $updateData['AM_UID'] = $this->getCurrentUserId();
            $updateData['AM_DATE'] = $nowWib->format('Y-m-d');
            $updateData['AM_TIME'] = $nowWib->format('H:i');

            // Update the SO record
            if (!empty($updateData)) {
                DB::table('so')
                    ->where('SO_Num', $soNumber)
                    ->update($updateData);

                Log::info('Sales order updated successfully', [
                    'so_number' => $soNumber,
                    'updated_fields' => array_keys($updateData)
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Sales order updated successfully',
                'data' => [
                    'so_number' => $soNumber,
                    'updated_fields' => array_keys($updateData)
                ]
            ], 200, ['Content-Type' => 'application/json; charset=utf-8']);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error updating sales order:', [
                'so_number' => $soNumber,
                'errors' => $e->errors()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422, ['Content-Type' => 'application/json; charset=utf-8']);

        } catch (\Exception $e) {
            Log::error('Error updating sales order:', [
                'so_number' => $soNumber,
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error updating sales order: ' . $e->getMessage(),
                'debug' => [
                    'line' => $e->getLine(),
                    'file' => basename($e->getFile())
                ]
            ], 500, ['Content-Type' => 'application/json; charset=utf-8']);
        }
    }

    /**
     * Cancel sales order
     */
    public function cancelSalesOrder(Request $request, $soNumber): JsonResponse
    {
        // Clean output buffers
        while (ob_get_level()) {
            ob_end_clean();
        }

        try {
            Log::info('Cancelling sales order', [
                'so_number' => $soNumber,
                'request_data' => $request->all()
            ]);

            // Validate request
            $validated = $request->validate([
                'cancel_reason' => 'nullable|string',
                'cancel_date' => 'nullable|string',
                'cancelled_by' => 'nullable|string',
            ]);

            // Check if SO exists (including all components)
            $salesOrders = DB::table('so')->where('SO_Num', $soNumber)->get();

            if ($salesOrders->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sales order not found'
                ], 404, ['Content-Type' => 'application/json; charset=utf-8']);
            }

            // Check if all components for this SO are already cancelled
            $allCancelled = $salesOrders->every(function ($order) {
                return $order->STS === 'CANCEL' || $order->STS === 'Cancelled';
            });

            if ($allCancelled) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sales order is already cancelled'
                ], 400, ['Content-Type' => 'application/json; charset=utf-8']);
            }

            // Get WIB timezone and user ID
            $nowWib = $this->getNowWib();
            $currentUserId = $this->getCurrentUserId();

            // Prepare cancel reason text with date, user info, and reason only
            $cancelReason = $validated['cancel_reason'] ?? '';
            $cancelInfo = sprintf(
                "CANCELLED on %s by %s\nReason: %s",
                $validated['cancel_date'] ?? $nowWib->format('Y-m-d'),
                $currentUserId,
                $cancelReason
            );

            // Prepare update data - using SO_REMARK to store cancel reason since CANCEL_REASON column doesn't exist
            $updateData = [
                'STS' => 'CANCEL',
                'SO_REMARK' => $cancelInfo,
                // Add cancel tracking with WIB timezone
                'CX_UID' => $currentUserId,
                'CX_DATE' => $nowWib->format('Y-m-d'),
                'CX_TIME' => $nowWib->format('H:i'),
            ];

            // Note: ANALYSIS_CODE column does not exist in SO table
            // Analysis code is stored in SO_REMARK as part of cancel info

            // Update the SO record
            DB::table('so')
                ->where('SO_Num', $soNumber)
                ->update($updateData);

            Log::info('Sales order cancelled successfully', [
                'so_number' => $soNumber,
                'cancelled_by' => $validated['cancelled_by'] ?? 'SYSTEM'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Sales order cancelled successfully',
                'data' => [
                    'so_number' => $soNumber,
                    'status' => 'CANCEL',
                    'cancel_reason' => $cancelReason
                ]
            ], 200, ['Content-Type' => 'application/json; charset=utf-8']);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error cancelling sales order:', [
                'so_number' => $soNumber,
                'errors' => $e->errors()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422, ['Content-Type' => 'application/json; charset=utf-8']);

        } catch (\Exception $e) {
            Log::error('Error cancelling sales order:', [
                'so_number' => $soNumber,
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error cancelling sales order: ' . $e->getMessage(),
                'debug' => [
                    'line' => $e->getLine(),
                    'file' => basename($e->getFile())
                ]
            ], 500, ['Content-Type' => 'application/json; charset=utf-8']);
        }
    }
}
