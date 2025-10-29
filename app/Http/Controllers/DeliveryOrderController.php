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
            'pcs_per_bdl' => 'nullable|string|max:50'
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
            
            $doNumber = $doYear . '-' . $doMonth . '-' . str_pad($seq, 5, '0', STR_PAD_LEFT);

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

            // Normalize DO quantity (To Delivery) from request
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

            $doData = [
                'DOYYYY' => $doYear,
                'DOMM' => $doMonth,
                'DO_Num' => $doNumber,
                'Status' => 'Draft',
                'DO_DMY' => $orderDate->format('d/m/Y'),
                'DO_VHC_Num' => $request->vehicle_number,
                'VHC_Class' => $vehicle->VEHICLE_CLASS ?? '',
                'AC_Num' => $request->customer_code,
                'AC_Name' => $customer->NAME ?? ($customer->AC_Name ?? ''),
                'No' => '1', // Line number
                'MCS_Num' => $request->mcard_seq ?? '',
                'Model' => $request->model ?? ($so ? ($so->MODEL ?? '') : ''),
                'Product' => ($so ? ($so->PRODUCT ?? $so->Product ?? '') : '') ?: ($mc->PRODUCT ?? ''),
                'COMP' => $customer->CODE ?? ($customer->AC_Num ?? ''),
                'PD' => $request->pd ?? ($so ? ($so->P_DESIGN ?? '') : ''),
                'PCS_PER_SET' => is_numeric($request->per_set) ? (float)$request->per_set : (float)($so ? ($so->PER_SET ?? 1) : 1),
                'Unit' => $request->unit ?? ($so ? ($so->UNIT ?? 'PCS') : 'PCS'),
                // Pull from SO first (as created with MC data), fallback to MC raw columns
                'Part_Number' => (string) (($so->PART_NUMBER ?? '') ?: ($mc->PART_NO ?? '')),
                'INT_L' => (float) (($so->INT_L ?? null) !== null ? $so->INT_L : ($mc->INT_LENGTH ?? 0)),
                'INT_W' => (float) (($so->INT_W ?? null) !== null ? $so->INT_W : ($mc->INT_WIDTH ?? 0)),
                'INT_H' => (float) (($so->INT_H ?? null) !== null ? $so->INT_H : ($mc->INT_HEIGHT ?? 0)),
                'EXT_L' => (float) (($so->EXT_L ?? null) !== null ? $so->EXT_L : ($mc->EXT_LENGTH ?? 0)),
                'EXT_W' => (float) (($so->EXT_W ?? null) !== null ? $so->EXT_W : ($mc->EXT_WIDTH ?? 0)),
                'EXT_H' => (float) (($so->EXT_H ?? null) !== null ? $so->EXT_H : ($mc->EXT_HEIGHT ?? 0)),
                'Flute' => (string) (($so->FLUTE ?? '') ?: ($mc->FLUTE ?? '')),
                'SLM' => $customer->SLM ?? ($so ? ($so->SLM ?? '') : ''),
                'IND' => $customer->IND ?? ($customer->INDUSTRY ?? ''),
                'Area1' => $customer->AREA ?? ($customer->AREA1 ?? ''),
                'Del_Code' => '',
                'Group_' => '',
                'SO_Num' => $soNumber ?? '',
                'SO_Type' => $so ? ($so->TYPE ?? '') : '',
                'PO_Num' => $request->po_number ?? ($so ? ($so->PO_Num ?? '') : ''),
                'PO_Date' => $request->po_date ?? ($so ? ($so->PO_DATE ?? '') : ''),
                'LOT_Num' => '',
                'PQ1' => (string) (($so->PQ1 ?? '') ?: ($mc->SO_PQ1 ?? '')),
                'PQ2' => (string) (($so->PQ2 ?? '') ?: ($mc->SO_PQ2 ?? '')),
                'PQ3' => (string) (($so->PQ3 ?? '') ?: ($mc->SO_PQ3 ?? '')),
                'PQ4' => (string) (($so->PQ4 ?? '') ?: ($mc->SO_PQ4 ?? '')),
                'PQ5' => (string) (($so->PQ5 ?? '') ?: ($mc->SO_PQ5 ?? '')),
                'DO_Qty' => $doQty,
                'UNAPPLIED_FG' => $request->unapply_fg ? 'Y' : 'N',
                'DO_M3' => 0.0,
                'SO_Unit_Price' => (float) ($so ? ($so->UNIT_PRICE ?? 0) : 0),
                'Curr' => $so ? ($so->CURR ?? 'IDR') : 'IDR',
                'Ex_Rate' => 1.0,
                'DO_Tran_Amt' => 0.0,
                'DO_Base_Amt' => 0.0,
                'MC_Gross_M2_Per_Pcs' => (float) ($mc->GROSS_M2_PER_PCS ?? 0),
                'MC_Net_M2_Per_Pcs' => (float) ($mc->NET_M2_PER_PCS ?? 0),
                'Total_DO_Gross_M2' => 0.0,
                'Total_DO_Net_M2' => 0.0,
                'MC_Gross_Kg_Per_Pcs' => (float) ($mc->GROSS_KG_PER_PCS ?? 0),
                'MC_Net_Kg_Per_Pcs' => (string) ($mc->NET_KG_PER_PCS ?? ''),
                'Total_DO_Gross_KG' => 0.0,
                'Total_DO_Net_KG' => 0.0,
                'DODateSK' => $orderDate->format('Ymd'),
                'DO_Remark1' => $request->remark1 ?? '',
                'DO_Remark2' => $request->remark2 ?? '',
                'Cancelled_Reason' => ''
            ];

            // Insert into DO table
            DB::table('DO')->insert($doData);

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
     * Get delivery orders for print range
     */
    public function getPrintRange(Request $request)
    {
        try {
            $query = DB::table('DO')
                ->leftJoin('AC', 'DO.AC_Num', '=', 'AC.AC_Num')
                ->select(
                    'DO.DO_Num',
                    'DO.DO_DMY',
                    'DO.AC_Num',
                    'AC.AC_Name',
                    'DO.DO_VHC_Num',
                    'DO.Status',
                    'DO.DO_Remark1',
                    'DO.DO_Remark2'
                );

            // Filter by DO number range
            if ($request->from_month && $request->from_year && $request->from_number) {
                $fromDO = sprintf('%02d-%04d-%05d', 
                    $request->from_month, 
                    $request->from_year, 
                    $request->from_number
                );
                $query->where('DO.DO_Num', '>=', $fromDO);
            }

            if ($request->to_month && $request->to_year && $request->to_number) {
                $toDO = sprintf('%02d-%04d-%05d', 
                    $request->to_month, 
                    $request->to_year, 
                    $request->to_number
                );
                $query->where('DO.DO_Num', '<=', $toDO);
            }

            // Filter by customer if specified
            if ($request->customer_code) {
                $query->where('DO.AC_Num', $request->customer_code);
            }

            // Filter by status for print (only Saved and Completed)
            $query->whereIn('DO.Status', ['Saved', 'Completed']);

            // Filter by new entry mode if specified
            if ($request->new_entry_mode === 'print_only') {
                // Add logic for new entries only if needed
            }

            $deliveryOrders = $query->orderBy('DO.DO_Num', 'asc')->get();

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
