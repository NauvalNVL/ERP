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
            'unapply_fg' => 'boolean'
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

            // Get master card information (if needed)
            $masterCard = null;

            // Prepare DO data
            $doData = [
                'DOYYYY' => $doYear,
                'DOMM' => $doMonth,
                'DO_Num' => $doNumber,
                'Status' => 'Draft',
                'DO_DMY' => $orderDate->format('d/m/Y'),
                'DO_VHC_Num' => $request->vehicle_number,
                'VHC_Class' => $vehicle->VEHICLE_CLASS ?? '',
                'AC_Num' => $request->customer_code,
                'AC_Name' => $customer->NAME ?? '',
                'No' => '1', // Line number
                'MCS_Num' => '',
                'Model' => '',
                'Product' => '',
                'COMP' => $customer->CODE ?? '',
                'PD' => '',
                'PCS_PER_SET' => 1.0,
                'Unit' => 'PCS',
                'Part_Number' => '',
                'INT_L' => 0.0,
                'INT_W' => 0.0,
                'INT_H' => 0.0,
                'EXT_L' => 0.0,
                'EXT_W' => 0.0,
                'EXT_H' => 0.0,
                'Flute' => '',
                'SLM' => $customer->SLM ?? '',
                'IND' => $customer->IND ?? '',
                'Area1' => $customer->AREA ?? '',
                'Del_Code' => '',
                'Group_' => '',
                'SO_Num' => '',
                'SO_Type' => '',
                'PO_Num' => '',
                'PO_Date' => '',
                'LOT_Num' => '',
                'PQ1' => '',
                'PQ2' => '',
                'PQ3' => '',
                'PQ4' => '',
                'PQ5' => '',
                'DO_Qty' => 0.0,
                'UNAPPLIED_FG' => $request->unapply_fg ? 'Y' : 'N',
                'DO_M3' => 0.0,
                'SO_Unit_Price' => 0.0,
                'Curr' => 'IDR',
                'Ex_Rate' => 1.0,
                'DO_Tran_Amt' => 0.0,
                'DO_Base_Amt' => 0.0,
                'MC_Gross_M2_Per_Pcs' => 0.0,
                'MC_Net_M2_Per_Pcs' => 0.0,
                'Total_DO_Gross_M2' => 0.0,
                'Total_DO_Net_M2' => 0.0,
                'MC_Gross_Kg_Per_Pcs' => 0.0,
                'MC_Net_Kg_Per_Pcs' => '',
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
}
