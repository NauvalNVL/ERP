<?php

namespace App\Http\Controllers;

use App\Models\FGStockIn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class FGStockInController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'current_period' => 'required|string',
            'so_number' => 'required|string',
            'customer' => 'required|string',
            'mcard_seq' => 'required|string',
            'product' => 'required|string',
            'product_desc' => 'required|string',
            'model' => 'required|string',
            'order_status' => 'required|string',
            'analysis_code' => 'nullable|string',
            'entry_date' => 'nullable|date',
            'entry_ref' => 'nullable|string',
            'set_qty' => 'nullable|numeric',
            'whse_location' => 'nullable|string',
            'remark' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Create a new FGStockIn record
            $fgStockIn = new FGStockIn();
            $fgStockIn->AC = $request->mcard_seq; // Customer Account Code
            $fgStockIn->AC_NAME = $request->customer; // Customer Name
            $fgStockIn->MCS = $request->mcard_seq; // Master Card Sequence
            $fgStockIn->MODEL = $request->model; // Model
            $fgStockIn->DESIGN = $request->product_desc; // Design/Description
            $fgStockIn->C = 1; // Counter (default to 1)
            $fgStockIn->WO = ''; // Work Order (empty for now)
            $fgStockIn->SO = $request->so_number; // Sales Order
            $fgStockIn->STOCKIN_REF = $request->entry_ref; // Entry Reference
            $fgStockIn->REC_DATE = $request->entry_date ? date('Y-m-d', strtotime($request->entry_date)) : null; // Receive Date
            $fgStockIn->TR_TYPE = 'STOCKIN'; // Transaction Type
            $fgStockIn->WHS_LOC = $request->whse_location; // Warehouse Location
            $fgStockIn->UNIT = 'PCS'; // Unit (default to PCS)
            $fgStockIn->QTY_IN = $request->set_qty ?: 0; // Quantity In
            $fgStockIn->QTY_OUT = 0; // Quantity Out (default to 0)
            $fgStockIn->AC_CODE = ''; // Account Code (empty for now)
            $fgStockIn->ORIGIN_TRAN = 0; // Origin Transaction (default to 0)
            $fgStockIn->ORIGIN_REF = 0; // Origin Reference (default to 0)
            $fgStockIn->UID = Auth::check() ? Auth::user()->name : 'SYSTEM'; // User ID
            $fgStockIn->SYSTEM_DATE = date('Y-m-d'); // System Date
            $fgStockIn->SYSTEM_TIME = date('H:i:s'); // System Time
            
            $fgStockIn->save();

            return response()->json([
                'success' => true,
                'message' => 'FG Stock In record saved successfully',
                'data' => $fgStockIn
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to save FG Stock In record',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}