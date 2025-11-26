<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class DeliveryOrderController extends Controller
{
    /**
     * Store a new delivery order
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'customer_code' => 'required|string|max:50',
            'vehicle_number' => 'required|string|max:50',
            'order_date' => 'required|date',
            'cust_remark' => 'nullable|string|max:250',
            'remark1' => 'nullable|string|max:250',
            'remark2' => 'nullable|string|max:250',
            'unapply_fg' => 'boolean',
            // additional optional payload from UI
            'so_number' => 'nullable|string|max:50',
            'mcard_seq' => 'nullable|string|max:50',
            'model' => 'nullable|string|max:250',
            'so_status' => 'nullable|string|max:50',
            'so_date' => 'nullable|string|max:50',
            'po_number' => 'nullable|string|max:250',
            'po_date' => 'nullable|string|max:50',
            'pd' => 'nullable|string|max:50',
            'per_set' => 'nullable|string|max:50',
            'unit' => 'nullable|string|max:50',
            'do_qty' => 'nullable|numeric',
            'pcs_per_bdl' => 'nullable|string|max:50',
            // Optional multi-line DO items (Main + Fit1-9)
            'items' => 'sometimes|array',
            'items.*.name' => 'nullable|string|max:50',
            'items.*.p_design' => 'nullable|string|max:50',
            'items.*.pcs' => 'nullable|string|max:50',
            'items.*.unit' => 'nullable|string|max:50',
            'items.*.to_deliver' => 'nullable|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Generate DO number
            $orderDate = Carbon::parse($request->order_date);
            $doYear = $orderDate->format('Y');
            $doMonth = $orderDate->format('m');

            // Get the last DO number for this month/year
            $lastDO = DB::table('DO')
                ->where('DOYYYY', $doYear)
                ->where('DOMM', $doMonth)
                ->orderBy('DO_Num', 'desc')
                ->value('DO_Num');

            $seq = 1;
            if ($lastDO) {
                $parts = explode('-', $lastDO);
                $seq = isset($parts[2]) ? ((int) $parts[2]) + 1 : 1;
            }

            $doNumber = $doMonth . '-' . $doYear . '-' . str_pad($seq, 5, '0', STR_PAD_LEFT);

            // Get customer information
            $customer = DB::table('CUSTOMER')
                ->where('CODE', $request->customer_code)
                ->first();

            if (!$customer) {
                return response()->json([
                    'success' => false,
                    'message' => 'Customer not found'
                ], 404);
            }

            // Get vehicle information
            $vehicle = DB::table('vehicle')
                ->where('VEHICLE_NO', $request->vehicle_number)
                ->first();

            if (!$vehicle) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vehicle not found'
                ], 404);
            }

            // Optional: Get SO and MC info for enrichment
            $soNumber = $request->so_number;
            $salesOrder = null;
            if (!empty($soNumber)) {
                // Look up by SO_Num only to match schema
                $salesOrder = DB::table('so')->where('SO_Num', $soNumber)->first();
            }

            $masterCard = null;
            if ($request->mcard_seq) {
                // Prefer exact schema used by SO controller
                $masterCard = DB::table('MC')
                    ->where('MCS_Num', $request->mcard_seq)
                    ->where('AC_NUM', $request->customer_code)
                    ->first();
                if (!$masterCard) {
                    // Fallbacks if column case varies
                    $masterCard = DB::table('MC')
                        ->where('MCS_NUM', $request->mcard_seq)
                        ->where('AC_NUM', $request->customer_code)
                        ->first();
                }
            }

            // Normalize DO quantity (To Delivery) from request (Main row)
            $rawQty = $request->do_qty
                ?? $request->input('to_delivery')
                ?? $request->input('to_deliver')
                ?? $request->input('mainToDel')
                ?? $request->input('main_to_del')
                ?? null;
            if (is_string($rawQty)) {
                $rawQty = str_replace([',', ' '], '', $rawQty);
            }
            $doQty = is_numeric($rawQty) ? (float) $rawQty : (float) ($request->do_qty ?? 0);

            // Prepare DO data
            $so = $salesOrder; // alias
            $mc = $masterCard; // alias

            // Get LOT_Num from SO table
            $lotNum = $so ? ($so->LOT_Num ?? '') : '';

            // ===================================================================
            // Del_Code (Delivery Code / Kode Geo) Logic
            // ===================================================================
            // Del_Code now directly taken from SO.D_LOC_Num
            // SO.D_LOC_Num already stores geographical delivery code (same logic as Del_Code)
            // No need to re-query customer_alternate_addresses or update_customer_accounts
            // ===================================================================

            $delCode = $so ? ($so->D_LOC_Num ?? '') : '';

            Log::info('DO Del_Code - From SO.D_LOC_Num', [
                'del_code' => $delCode,
                'so_number' => $soNumber ?? 'N/A',
                'source' => 'SO.D_LOC_Num (geographical code)'
            ]);

            $customerGroup = $customer->GROUP_ ?? ($customer->Group_ ?? '');

            // NOTE: SO_Date, SODateSK, PODateSK are NOT in DO table schema
            // These fields will be retrieved from SO table when creating invoice

            // Base fields shared by all DO lines for this DO number
            $baseDo = [
                'DOYYYY' => $doYear,
                'DOMM' => $doMonth,
                'DO_Num' => $doNumber,
                'Status' => 'Draft',
                'DO_DMY' => $orderDate->format('d/m/Y'),
                'DO_VHC_Num' => $request->vehicle_number,
                'VHC_Class' => $vehicle->VEHICLE_CLASS ?? '',
                'AC_Num' => $request->customer_code,
                'AC_Name' => $customer->NAME ?? ($customer->AC_Name ?? ''),
                'MCS_Num' => $request->mcard_seq ?? '',
                'Model' => $request->model ?? ($so ? ($so->MODEL ?? '') : ''),
                'Product' => ($so ? ($so->PRODUCT ?? $so->Product ?? '') : '') ?: ($mc->PRODUCT ?? ''),
                'SLM' => $customer->SLM ?? ($so ? ($so->SLM ?? '') : ''),
                'IND' => $customer->IND ?? ($customer->INDUSTRY ?? ''),
                'Area1' => $customer->AREA ?? ($customer->AREA1 ?? ''),
                'Del_Code' => $delCode,
                'Group_' => $customerGroup,
                'SO_Num' => $soNumber ?? '',
                'SO_Type' => $so ? ($so->TYPE ?? '') : '',
                'PO_Num' => $request->po_number ?? ($so ? ($so->PO_Num ?? '') : ''),
                'PO_Date' => $request->po_date ?? ($so ? ($so->PO_DATE ?? '') : ''),
                'LOT_Num' => $lotNum,
                'Curr' => $so ? ($so->CURR ?? 'IDR') : 'IDR',
                'DODateSK' => $orderDate->format('Ymd'),
                'DO_Remark1' => $request->remark1 ?? '',
                'DO_Remark2' => $request->remark2 ?? '',
                'Cancelled_Reason' => ''
            ];

            $items = $request->input('items', []);
            $rowsToInsert = [];

            if (!is_array($items) || empty($items)) {
                // Legacy single-line behavior (Main only)
                $extL = (float) (($so->EXT_L ?? null) !== null ? $so->EXT_L : ($mc->EXT_LENGTH ?? 0));
                $extW = (float) (($so->EXT_W ?? null) !== null ? $so->EXT_W : ($mc->EXT_WIDTH ?? 0));
                $extH = (float) (($so->EXT_H ?? null) !== null ? $so->EXT_H : ($mc->EXT_HEIGHT ?? 0));

                $doM3 = 0.0;
                if ($extL > 0 && $extW > 0 && $extH > 0 && $doQty > 0) {
                    $doM3 = ($extL * $extW * $extH * $doQty) / 1000000000;
                }

                $unitPrice = (float) ($so ? ($so->UNIT_PRICE ?? 0) : 0);
                $exRate = (float) ($so ? ($so->EX_RATE ?? 1) : 1);
                $doTranAmt = $doQty > 0 && $unitPrice > 0 ? round($doQty * $unitPrice, 2) : 0.0;
                $doBaseAmt = round($doTranAmt * $exRate, 2);

                $grossM2PerPcs = (float) ($mc ? ($mc->MC_GROSS_M2_PER_PCS ?? 0) : 0);
                $netM2PerPcs = (float) ($mc ? ($mc->MC_NET_M2_PER_PCS ?? 0) : 0);
                $totalGrossM2 = $doQty > 0 && $grossM2PerPcs > 0 ? round($doQty * $grossM2PerPcs, 4) : 0.0;
                $totalNetM2 = $doQty > 0 && $netM2PerPcs > 0 ? round($doQty * $netM2PerPcs, 4) : 0.0;

                $grossKgPerPcs = (float) ($mc ? ($mc->MC_GROSS_KG_PER_SET ?? 0) : 0);
                $netKgPerPcs = (float) ($mc ? ($mc->MC_NET_KG_PER_PCS ?? 0) : 0);
                $totalGrossKg = $doQty > 0 && $grossKgPerPcs > 0 ? round($doQty * $grossKgPerPcs, 4) : 0.0;
                $totalNetKg = $doQty > 0 && $netKgPerPcs > 0 ? round($doQty * $netKgPerPcs, 4) : 0.0;

                $rowsToInsert[] = array_merge($baseDo, [
                    'No' => '1',
                    'COMP' => $customer->CODE ?? ($customer->AC_Num ?? ''),
                    'PD' => $request->pd ?? ($so ? ($so->P_DESIGN ?? '') : ''),
                    'PCS_PER_SET' => is_numeric($request->per_set) ? (float)$request->per_set : (float)($so ? ($so->PER_SET ?? 1) : 1),
                    'Unit' => $request->unit ?? ($so ? ($so->UNIT ?? 'PCS') : 'PCS'),
                    'Part_Number' => (string) (($so->PART_NUMBER ?? '') ?: ($mc->PART_NO ?? '')),
                    'INT_L' => (float) (($so->INT_L ?? null) !== null ? $so->INT_L : ($mc->INT_LENGTH ?? 0)),
                    'INT_W' => (float) (($so->INT_W ?? null) !== null ? $so->INT_W : ($mc->INT_WIDTH ?? 0)),
                    'INT_H' => (float) (($so->INT_H ?? null) !== null ? $so->INT_H : ($mc->INT_HEIGHT ?? 0)),
                    'EXT_L' => $extL,
                    'EXT_W' => $extW,
                    'EXT_H' => $extH,
                    'Flute' => (string) (($so->FLUTE ?? '') ?: ($mc->FLUTE ?? '')),
                    'PQ1' => (string) (($so->PQ1 ?? '') ?: ($mc->SO_PQ1 ?? '')),
                    'PQ2' => (string) (($so->PQ2 ?? '') ?: ($mc->SO_PQ2 ?? '')),
                    'PQ3' => (string) (($so->PQ3 ?? '') ?: ($mc->SO_PQ3 ?? '')),
                    'PQ4' => (string) (($so->PQ4 ?? '') ?: ($mc->SO_PQ4 ?? '')),
                    'PQ5' => (string) (($so->PQ5 ?? '') ?: ($mc->SO_PQ5 ?? '')),
                    'DO_Qty' => $doQty,
                    'UNAPPLIED_FG' => $request->unapply_fg ? 'Y' : 'N',
                    'DO_M3' => round($doM3, 2),
                    'SO_Unit_Price' => $unitPrice,
                    'Ex_Rate' => $exRate,
                    'DO_Tran_Amt' => $doTranAmt,
                    'DO_Base_Amt' => $doBaseAmt,
                    'MC_Gross_M2_Per_Pcs' => $grossM2PerPcs,
                    'MC_Net_M2_Per_Pcs' => $netM2PerPcs,
                    'Total_DO_Gross_M2' => $totalGrossM2,
                    'Total_DO_Net_M2' => $totalNetM2,
                    'MC_Gross_Kg_Per_Pcs' => $grossKgPerPcs,
                    'MC_Net_Kg_Per_Pcs' => $netKgPerPcs,
                    'Total_DO_Gross_KG' => $totalGrossKg,
                    'Total_DO_Net_KG' => $totalNetKg,
                ]);
            } else {
                // Multi-line DO: build one row per MC component (Main + Fit components)
                $mcComponents = [];
                if ($request->mcard_seq && $request->customer_code) {
                    $mcComponents = DB::table('MC')
                        ->where('MCS_Num', $request->mcard_seq)
                        ->where('AC_NUM', $request->customer_code)
                        ->orderBy('COMP')
                        ->get();
                }

                $lineNo = 1;
                foreach ($items as $item) {
                    $compName = isset($item['name']) ? (string) $item['name'] : '';
                    $qty = isset($item['to_deliver']) && is_numeric($item['to_deliver'])
                        ? (float) $item['to_deliver']
                        : 0.0;

                    if ($qty <= 0) {
                        continue;
                    }

                    // Map UI name to MC.COMP value: Main, Fit1-9
                    $targetComp = $compName === 'Main' ? 'Main' : $compName;
                    $compRow = null;
                    foreach ($mcComponents as $row) {
                        if ((string) $row->COMP === (string) $targetComp) {
                            $compRow = $row;
                            break;
                        }
                    }

                    // Jika komponen tidak ditemukan di MC, lewati.
                    // Pengecualian: Main boleh fallback ke master card umum ($mc) jika tersedia.
                    if (!$compRow) {
                        if ($targetComp === 'Main' && $mc) {
                            $compRow = $mc;
                        } else {
                            continue;
                        }
                    }

                    $compModel = $compRow->MODEL ?? ($mc->MODEL ?? '');
                    $compPDesign = $compRow->P_DESIGN ?? ($item['p_design'] ?? ($request->pd ?? ''));
                    $compPartNumber = $compRow->PART_NO ?? ($mc->PART_NO ?? '');

                    $compIntL = (float)($compRow->INT_LENGTH ?? $mc->INT_LENGTH ?? 0);
                    $compIntW = (float)($compRow->INT_WIDTH ?? $mc->INT_WIDTH ?? 0);
                    $compIntH = (float)($compRow->INT_HEIGHT ?? $mc->INT_HEIGHT ?? 0);
                    $compExtL = (float)($compRow->EXT_LENGTH ?? $mc->EXT_LENGTH ?? 0);
                    $compExtW = (float)($compRow->EXT_WIDTH ?? $mc->EXT_WIDTH ?? 0);
                    $compExtH = (float)($compRow->EXT_HEIGHT ?? $mc->EXT_HEIGHT ?? 0);

                    $compFlute = $compRow->FLUTE ?? $mc->FLUTE ?? '';
                    $compPq1 = $compRow->SO_PQ1 ?? $mc->SO_PQ1 ?? '';
                    $compPq2 = $compRow->SO_PQ2 ?? $mc->SO_PQ2 ?? '';
                    $compPq3 = $compRow->SO_PQ3 ?? $mc->SO_PQ3 ?? '';
                    $compPq4 = $compRow->SO_PQ4 ?? $mc->SO_PQ4 ?? '';
                    $compPq5 = $compRow->SO_PQ5 ?? $mc->SO_PQ5 ?? '';

                    $unitPrice = (float) ($so ? ($so->UNIT_PRICE ?? 0) : 0);
                    $exRate = (float) ($so ? ($so->EX_RATE ?? 1) : 1);
                    $doTranAmt = $qty > 0 && $unitPrice > 0 ? round($qty * $unitPrice, 2) : 0.0;
                    $doBaseAmt = round($doTranAmt * $exRate, 2);

                    $grossM2PerPcs = (float)($compRow->MC_GROSS_M2_PER_PCS ?? $mc->MC_GROSS_M2_PER_PCS ?? 0);
                    $netM2PerPcs = (float)($compRow->MC_NET_M2_PER_PCS ?? $mc->MC_NET_M2_PER_PCS ?? 0);
                    $totalGrossM2 = $qty > 0 && $grossM2PerPcs > 0 ? round($qty * $grossM2PerPcs, 4) : 0.0;
                    $totalNetM2 = $qty > 0 && $netM2PerPcs > 0 ? round($qty * $netM2PerPcs, 4) : 0.0;

                    $grossKgPerPcs = (float)($compRow->MC_GROSS_KG_PER_SET ?? $mc->MC_GROSS_KG_PER_SET ?? 0);
                    $netKgPerPcs = (float)($compRow->MC_NET_KG_PER_PCS ?? $mc->MC_NET_KG_PER_PCS ?? 0);
                    $totalGrossKg = $qty > 0 && $grossKgPerPcs > 0 ? round($qty * $grossKgPerPcs, 4) : 0.0;
                    $totalNetKg = $qty > 0 && $netKgPerPcs > 0 ? round($qty * $netKgPerPcs, 4) : 0.0;

                    $doM3 = 0.0;
                    if ($compExtL > 0 && $compExtW > 0 && $compExtH > 0 && $qty > 0) {
                        $doM3 = ($compExtL * $compExtW * $compExtH * $qty) / 1000000000;
                    } elseif ($compIntL > 0 && $compIntW > 0 && $compIntH > 0 && $qty > 0) {
                        $doM3 = ($compIntL * $compIntW * $compIntH * $qty) / 1000000000;
                    }

                    $rowsToInsert[] = array_merge($baseDo, [
                        'No' => (string) $lineNo,
                        'COMP' => $compRow->COMP ?? $targetComp,
                        'Model' => $compModel,
                        'PD' => $compPDesign,
                        'PCS_PER_SET' => is_numeric($item['pcs'] ?? null)
                            ? (float) $item['pcs']
                            : (float)($compRow->PCS_SET ?? $mc->PCS_SET ?? 1),
                        'Unit' => $item['unit'] ?? ($request->unit ?? ($so ? ($so->UNIT ?? 'PCS') : 'PCS')),
                        'Part_Number' => (string) $compPartNumber,
                        'INT_L' => $compIntL,
                        'INT_W' => $compIntW,
                        'INT_H' => $compIntH,
                        'EXT_L' => $compExtL,
                        'EXT_W' => $compExtW,
                        'EXT_H' => $compExtH,
                        'Flute' => $compFlute,
                        'PQ1' => $compPq1,
                        'PQ2' => $compPq2,
                        'PQ3' => $compPq3,
                        'PQ4' => $compPq4,
                        'PQ5' => $compPq5,
                        'DO_Qty' => $qty,
                        'UNAPPLIED_FG' => $request->unapply_fg ? 'Y' : 'N',
                        'DO_M3' => round($doM3, 2),
                        'SO_Unit_Price' => $unitPrice,
                        'Ex_Rate' => $exRate,
                        'DO_Tran_Amt' => $doTranAmt,
                        'DO_Base_Amt' => $doBaseAmt,
                        'MC_Gross_M2_Per_Pcs' => $grossM2PerPcs,
                        'MC_Net_M2_Per_Pcs' => $netM2PerPcs,
                        'Total_DO_Gross_M2' => $totalGrossM2,
                        'Total_DO_Net_M2' => $totalNetM2,
                        'MC_Gross_Kg_Per_Pcs' => $grossKgPerPcs,
                        'MC_Net_Kg_Per_Pcs' => $netKgPerPcs,
                        'Total_DO_Gross_KG' => $totalGrossKg,
                        'Total_DO_Net_KG' => $totalNetKg,
                    ]);

                    $lineNo++;
                }

                if (empty($rowsToInsert)) {
                    // Fallback if no valid items: insert a single legacy row
                    $rowsToInsert[] = array_merge($baseDo, [
                        'No' => '1',
                        'COMP' => $customer->CODE ?? ($customer->AC_Num ?? ''),
                        'PD' => $request->pd ?? ($so ? ($so->P_DESIGN ?? '') : ''),
                        'PCS_PER_SET' => is_numeric($request->per_set) ? (float)$request->per_set : 1,
                        'Unit' => $request->unit ?? ($so ? ($so->UNIT ?? 'PCS') : 'PCS'),
                        'DO_Qty' => $doQty,
                        'UNAPPLIED_FG' => $request->unapply_fg ? 'Y' : 'N',
                    ]);
                }
            }

            // Insert into DO table
            DB::table('DO')->insert($rowsToInsert);

            // Update SO status based on remaining balance
            if (!empty($soNumber) && $so) {
                $soQty = (float) ($so->SO_QTY ?? 0);
                try {
                    $delivered = (float) DB::table('DO')
                        ->where('SO_Num', $soNumber)
                        ->sum('DO_Qty');
                    $epsilon = 0.0001;
                    if ($soQty > 0) {
                        $newStatus = ($delivered + $epsilon) >= $soQty ? 'Complete' : 'Partial Complete';
                        DB::table('so')->where('SO_Num', $soNumber)->update(['STS' => $newStatus]);
                    }
                } catch (\Exception $e) {
                    Log::warning('Failed to update SO status after DO insert', [
                        'so_number' => $soNumber,
                        'error' => $e->getMessage()
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Delivery order saved successfully',
                'data' => [
                    'do_number' => $doNumber,
                    'do_date' => $orderDate->format('d/m/Y'),
                    'customer_name' => $customer->NAME,
                    'vehicle_number' => $request->vehicle_number
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error saving delivery order: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error saving delivery order: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get delivery orders
     */
    public function index(Request $request)
    {
        try {
            $query = DB::table('DO');

            // Filter by date range
            if ($request->has('from_date') && $request->has('to_date')) {
                $query->whereBetween('DODateSK', [
                    Carbon::parse($request->from_date)->format('Ymd'),
                    Carbon::parse($request->to_date)->format('Ymd')
                ]);
            }

            // Filter by status
            if ($request->has('status')) {
                $query->where('Status', $request->status);
            }

            // Filter by customer
            if ($request->has('customer_code')) {
                $query->where('AC_Num', $request->customer_code);
            }

            $deliveryOrders = $query->orderBy('DO_Num', 'desc')->paginate(20);

            return response()->json([
                'success' => true,
                'data' => $deliveryOrders
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching delivery orders: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error fetching delivery orders'
            ], 500);
        }
    }

    /**
     * Get delivery order details
     */
    public function show($doNumber)
    {
        try {
            $deliveryOrder = DB::table('DO')
                ->where('DO_Num', $doNumber)
                ->first();

            if (!$deliveryOrder) {
                return response()->json([
                    'success' => false,
                    'message' => 'Delivery order not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $deliveryOrder
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching delivery order: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error fetching delivery order'
            ], 500);
        }
    }

    /**
     * Update/Amend a delivery order
     */
    public function update(Request $request, $doNumber)
    {
        $validator = Validator::make($request->all(), [
            'vehicle_number' => 'required|string|max:50',
            'order_date' => 'required|date',
            'remark1' => 'nullable|string|max:250',
            'remark2' => 'nullable|string|max:250',
            'unapply_fg' => 'boolean',
            'cancelled_reason' => 'nullable|string|max:250'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Check if delivery order exists
            $existingDO = DB::table('DO')
                ->where('DO_Num', $doNumber)
                ->first();

            if (!$existingDO) {
                return response()->json([
                    'success' => false,
                    'message' => 'Delivery order not found'
                ], 404);
            }

            // Check if delivery order can be amended
            if (!in_array($existingDO->Status, ['Draft', 'Saved'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Only Draft and Saved delivery orders can be amended'
                ], 400);
            }

            // Get vehicle information
            $vehicle = DB::table('vehicle')
                ->where('VEHICLE_NO', $request->vehicle_number)
                ->first();

            if (!$vehicle) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vehicle not found'
                ], 404);
            }

            $orderDate = Carbon::parse($request->order_date);

            // Prepare updated data
            $updateData = [
                'DO_DMY' => $orderDate->format('d/m/Y'),
                'DO_VHC_Num' => $request->vehicle_number,
                'VHC_Class' => $vehicle->VEHICLE_CLASS ?? '',
                'UNAPPLIED_FG' => $request->unapply_fg ? 'Y' : 'N',
                'DO_Remark1' => $request->remark1 ?? '',
                'DO_Remark2' => $request->remark2 ?? '',
                'DODateSK' => $orderDate->format('Ymd'),
            ];

            // Add cancelled reason if provided
            if ($request->has('cancelled_reason') && $request->cancelled_reason) {
                $updateData['Cancelled_Reason'] = $request->cancelled_reason;
            }

            // Update the delivery order
            DB::table('DO')
                ->where('DO_Num', $doNumber)
                ->update($updateData);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Delivery order amended successfully',
                'data' => [
                    'do_number' => $doNumber,
                    'do_date' => $orderDate->format('d/m/Y'),
                    'vehicle_number' => $request->vehicle_number
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error amending delivery order: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error amending delivery order: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get vehicle information
     */
    public function getVehicle($vehicleNumber)
    {
        try {
            $vehicle = DB::table('vehicle')
                ->where('VEHICLE_NO', $vehicleNumber)
                ->first();

            if (!$vehicle) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vehicle not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $vehicle
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching vehicle: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error fetching vehicle'
            ], 500);
        }
    }

    /**
     * Cancel a delivery order
     */
    public function cancel(Request $request, $doNumber)
    {
        $validator = Validator::make($request->all(), [
            'cancellation_reason' => 'required|string|max:250'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Check if delivery order exists
            $existingDO = DB::table('DO')
                ->where('DO_Num', $doNumber)
                ->first();

            if (!$existingDO) {
                return response()->json([
                    'success' => false,
                    'message' => 'Delivery order not found'
                ], 404);
            }

            // Check if delivery order can be cancelled
            if (!in_array($existingDO->Status, ['Draft', 'Saved'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Only Draft and Saved delivery orders can be cancelled'
                ], 400);
            }

            // Update the delivery order status and cancellation reason
            DB::table('DO')
                ->where('DO_Num', $doNumber)
                ->update([
                    'Status' => 'Cancelled',
                    'Cancelled_Reason' => $request->cancellation_reason
                ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Delivery order cancelled successfully',
                'data' => [
                    'do_number' => $doNumber,
                    'status' => 'Cancelled',
                    'cancellation_reason' => $request->cancellation_reason
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error cancelling delivery order: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error cancelling delivery order: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Fix missing data in existing DO records
     */
    public function fixMissingData()
    {
        try {
            DB::beginTransaction();

            $updated = 0;
            $errors = 0;

            // Get all DO records with missing data
            $doRecords = DB::table('DO')
                ->whereRaw('(Del_Code IS NULL OR Del_Code = "")')
                ->orWhereRaw('(Group_ IS NULL OR Group_ = "")')
                ->orWhereRaw('(MC_Gross_M2_Per_Pcs = 0 OR MC_Gross_M2_Per_Pcs IS NULL)')
                ->orWhereRaw('(Total_DO_Gross_M2 = 0 OR Total_DO_Gross_M2 IS NULL)')
                ->orWhereRaw('(Total_DO_Net_M2 = 0 OR Total_DO_Net_M2 IS NULL)')
                ->orWhereRaw('(MC_Gross_Kg_Per_Pcs = 0 OR MC_Gross_Kg_Per_Pcs IS NULL)')
                ->orWhereRaw('(Total_DO_Gross_KG = 0 OR Total_DO_Gross_KG IS NULL)')
                ->orWhereRaw('(Total_DO_Net_KG = 0 OR Total_DO_Net_KG IS NULL)')
                ->get();

            foreach ($doRecords as $do) {
                try {
                    $updateData = [];

                    // Get Customer data for Del_Code and Group_
                    if (empty($do->Del_Code) || empty($do->Group_)) {
                        // Get Del_Code from SO.D_LOC_Num (which stores geographical code)
                        if (empty($do->Del_Code)) {
                            $so = DB::table('so')->where('SO_Num', $do->SO_Num)->first();
                            if ($so && !empty($so->D_LOC_Num)) {
                                $updateData['Del_Code'] = $so->D_LOC_Num;
                            }
                        }

                        // Get Group_ from customer
                        if (empty($do->Group_)) {
                            $customer = DB::table('CUSTOMER')
                                ->where('CODE', $do->AC_Num)
                                ->first();

                            if ($customer) {
                                $updateData['Group_'] = $customer->GROUP_ ?? ($customer->Group_ ?? '');
                            }
                        }
                    }

                    // Get Master Card data for M2 and KG calculations
                    if (!empty($do->MCS_Num) && !empty($do->AC_Num)) {
                        $mc = DB::table('MC')
                            ->where('MCS_Num', $do->MCS_Num)
                            ->where('AC_NUM', $do->AC_Num)
                            ->first();

                        if ($mc) {
                            $doQty = (float) ($do->DO_Qty ?? 0);

                            // M2 calculations - MC table uses MC_GROSS_M2_PER_PCS and MC_NET_M2_PER_PCS
                            $grossM2PerPcs = (float) ($mc->MC_GROSS_M2_PER_PCS ?? 0);
                            $netM2PerPcs = (float) ($mc->MC_NET_M2_PER_PCS ?? 0);

                            if ($grossM2PerPcs > 0) {
                                $updateData['MC_Gross_M2_Per_Pcs'] = $grossM2PerPcs;
                                $updateData['Total_DO_Gross_M2'] = round($doQty * $grossM2PerPcs, 4);
                            }

                            if ($netM2PerPcs > 0) {
                                $updateData['MC_Net_M2_Per_Pcs'] = $netM2PerPcs;
                                $updateData['Total_DO_Net_M2'] = round($doQty * $netM2PerPcs, 4);
                            }

                            // KG calculations - MC table uses MC_GROSS_KG_PER_SET and MC_NET_KG_PER_PCS
                            $grossKgPerPcs = (float) ($mc->MC_GROSS_KG_PER_SET ?? 0);
                            $netKgPerPcs = (float) ($mc->MC_NET_KG_PER_PCS ?? 0);

                            if ($grossKgPerPcs > 0) {
                                $updateData['MC_Gross_Kg_Per_Pcs'] = $grossKgPerPcs;
                                $updateData['Total_DO_Gross_KG'] = round($doQty * $grossKgPerPcs, 4);
                            }

                            if ($netKgPerPcs > 0) {
                                $updateData['MC_Net_Kg_Per_Pcs'] = $netKgPerPcs;
                                $updateData['Total_DO_Net_KG'] = round($doQty * $netKgPerPcs, 4);
                            }
                        }
                    }

                    // Update record if there's data to update
                    if (!empty($updateData)) {
                        DB::table('DO')
                            ->where('DO_Num', $do->DO_Num)
                            ->update($updateData);
                        $updated++;
                    }

                } catch (\Exception $e) {
                    Log::error('Error fixing DO record: ' . $do->DO_Num, [
                        'error' => $e->getMessage()
                    ]);
                    $errors++;
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data fixed successfully',
                'data' => [
                    'total_records' => $doRecords->count(),
                    'updated' => $updated,
                    'errors' => $errors
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error fixing DO data: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error fixing DO data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get delivery orders for print range
     */
    public function getPrintRange(Request $request)
    {
        try {
            Log::info('PrintDO - Request received', $request->all());
            $query = DB::table('DO')
                ->leftJoin('CUSTOMER', 'DO.AC_Num', '=', 'CUSTOMER.CODE')
                ->leftJoin('so', 'DO.SO_Num', '=', 'so.SO_Num')
                ->leftJoin('MC', function($join) {
                    $join->on('DO.MCS_Num', '=', 'MC.MCS_Num')
                         ->on('DO.AC_Num', '=', 'MC.AC_NUM')
                         // Important: also match component so each DO line joins only to its own MC row
                         ->on('DO.COMP', '=', 'MC.COMP');
                })
                // Join products to fetch its product group
                // Important: join ONLY on DO.Product to avoid duplicate rows when
                // DO.Product and DO.PD are different valid product codes.
                ->leftJoin('products as p', function($join) {
                    $join->on('p.product_code', '=', 'DO.Product');
                })
                ->leftJoin('product_groups as pg', 'p.product_group_id', '=', 'pg.product_group_id')
                ->select(
                    'DO.DO_Num',
                    'DO.DO_DMY',
                    'DO.No',
                    'DO.COMP',
                    'DO.AC_Num',
                    'CUSTOMER.NAME as AC_Name',
                    'CUSTOMER.ADDRESS1',
                    'CUSTOMER.ADDRESS2',
                    'CUSTOMER.ADDRESS3',
                    'CUSTOMER.TEL_NO',
                    'CUSTOMER.FAX_NO',
                    'DO.DO_VHC_Num',
                    'DO.Status',
                    'DO.SO_Num',
                    'DO.PO_Num',
                    'DO.Model',
                    'DO.PD',
                    'DO.Product',
                    'DO.DO_Qty',
                    'DO.Unit',
                    'DO.INT_L',
                    'DO.INT_W',
                    'DO.INT_H',
                    'DO.EXT_L',
                    'DO.EXT_W',
                    'DO.EXT_H',
                    'DO.PCS_PER_SET',
                    'MC.PCS_PER_BLD',
                    'MC.P_DESIGN as MC_P_DESIGN',
                    'so.MODEL as SO_Model',
                    'so.PRODUCT as SO_Product',
                    'so.SO_QTY',
                    'so.UNIT as SO_Unit',
                    DB::raw("pg.product_group_name as ProductGroupName"),
                    'DO.DO_Remark1',
                    'DO.DO_Remark2'
                );

            // If exact do_num provided, prefer exact match (supports both formats)
            if ($request->filled('do_num')) {
                $num = (string) $request->do_num;
                // Try both formats: YYYY-MM-SSSSS or MM-YYYY-SSSSS
                $parts = explode('-', $num);
                if (count($parts) === 3) {
                    // Detect which segment represents the year
                    if (strlen($parts[0]) === 4) {
                        // Format: YYYY-MM-SSSSS (canonical)
                        $yyyy = (int) $parts[0];
                        $mm   = (int) $parts[1];
                        $seq  = (int) $parts[2];
                        $canonical = sprintf('%04d-%02d-%05d', $yyyy, $mm, $seq);
                        $alternate = sprintf('%02d-%04d-%05d', $mm, $yyyy, $seq); // MM-YYYY-SSSSS
                    } else {
                        // Format: MM-YYYY-SSSSS
                        $mm   = (int) $parts[0];
                        $yyyy = (int) $parts[1];
                        $seq  = (int) $parts[2];
                        $canonical = sprintf('%04d-%02d-%05d', $yyyy, $mm, $seq); // YYYY-MM-SSSSS
                        $alternate = sprintf('%02d-%04d-%05d', $mm, $yyyy, $seq); // MM-YYYY-SSSSS
                    }

                    $query->where(function ($q) use ($num, $canonical, $alternate) {
                        $q->where('DO.DO_Num', $num)
                          ->orWhere('DO.DO_Num', $canonical)
                          ->orWhere('DO.DO_Num', $alternate);
                    });
                } else {
                    $query->where('DO.DO_Num', $num);
                }
            } else {
            // Filter by DO number range (DO_Num format is MM-YYYY-SSSSS)
            $fromDO = null;
            $toDO = null;
            if ($request->from_month && $request->from_year && $request->from_number) {
                // Auto-correct swapped inputs (e.g., month=2025, year=10)
                $fMonth = (int) $request->from_month;
                $fYear  = (int) $request->from_year;
                if ($fMonth >= 1000 && $fYear > 0 && $fYear <= 12) {
                    [$fMonth, $fYear] = [$fYear, $fMonth];
                }
                $fromDO = sprintf('%02d-%04d-%05d',
                    $fMonth,
                    $fYear,
                    (int)$request->from_number
                );
            }

            if ($request->to_month && $request->to_year && $request->to_number) {
                // Auto-correct swapped inputs (e.g., month=2025, year=10)
                $tMonth = (int) $request->to_month;
                $tYear  = (int) $request->to_year;
                if ($tMonth >= 1000 && $tYear > 0 && $tYear <= 12) {
                    [$tMonth, $tYear] = [$tYear, $tMonth];
                }
                $toDO = sprintf('%02d-%04d-%05d',
                    $tMonth,
                    $tYear,
                    (int)$request->to_number
                );
            }

            if ($fromDO && $toDO) {
                // Build swapped variants to support both formats
                $fromYear = (int)$request->from_year; $fromMonth = (int)$request->from_month; $fromSeq = (int)$request->from_number;
                $toYear = (int)$request->to_year; $toMonth = (int)$request->to_month; $toSeq = (int)$request->to_number;
                $fromAlt = sprintf('%04d-%02d-%05d', $fromYear, $fromMonth, $fromSeq); // YYYY-MM-SSSSS
                $toAlt = sprintf('%04d-%02d-%05d', $toYear, $toMonth, $toSeq);

                if ($fromDO === $toDO) {
                    // Exact single DO match (either format)
                    $query->where(function ($q) use ($fromDO, $fromAlt) {
                        $q->where('DO.DO_Num', $fromDO)
                          ->orWhere('DO.DO_Num', $fromAlt);
                    });
                } else {
                    // Range match on either format
                    $query->where(function ($q) use ($fromDO, $toDO, $fromAlt, $toAlt) {
                        $q->whereBetween('DO.DO_Num', [$fromDO, $toDO])
                          ->orWhereBetween('DO.DO_Num', [$fromAlt, $toAlt]);
                    });
                }
            } elseif ($fromDO && !$toDO) {
                // Only from provided: treat as exact DO (either format)
                $fromYear = (int)$request->from_year; $fromMonth = (int)$request->from_month; $fromSeq = (int)$request->from_number;
                $fromAlt = sprintf('%04d-%02d-%05d', $fromYear, $fromMonth, $fromSeq);
                $query->where(function ($q) use ($fromDO, $fromAlt) {
                    $q->where('DO.DO_Num', $fromDO)
                      ->orWhere('DO.DO_Num', $fromAlt);
                });
            } elseif (!$fromDO && $toDO) {
                // Only to provided: treat as exact DO (either format)
                $toYear = (int)$request->to_year; $toMonth = (int)$request->to_month; $toSeq = (int)$request->to_number;
                $toAlt = sprintf('%04d-%02d-%05d', $toYear, $toMonth, $toSeq);
                $query->where(function ($q) use ($toDO, $toAlt) {
                    $q->where('DO.DO_Num', $toDO)
                      ->orWhere('DO.DO_Num', $toAlt);
                });
            }

            // Close 'else' block for do_num exact matching
            }

            // Filter by customer if specified
            if ($request->customer_code) {
                $query->where('DO.AC_Num', $request->customer_code);
            }

            // Optional: filter by status only if provided; otherwise include all statuses
            if ($request->has('status')) {
                $statuses = is_array($request->status) ? $request->status : [$request->status];
                $query->whereIn('DO.Status', $statuses);
            }

            // Filter by new entry mode if specified
            if ($request->new_entry_mode === 'print_only') {
                // Add logic for new entries only if needed
            }

            $deliveryOrders = $query
                ->orderBy('DO.DO_Num', 'asc')
                ->orderBy('DO.No', 'asc')
                ->orderBy('DO.COMP', 'asc')
                ->get();

            Log::info('PrintDO - Query result', [
                'count' => $deliveryOrders->count(),
                'sample' => $deliveryOrders->take(2)->toArray()
            ]);

            return response()->json([
                'success' => true,
                'data' => $deliveryOrders
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching delivery orders for print: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error fetching delivery orders for print: ' . $e->getMessage()
            ], 500);
        }
    }
}
