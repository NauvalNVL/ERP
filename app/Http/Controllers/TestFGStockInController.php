<?php

namespace App\Http\Controllers;

use App\Models\FGStockIn;
use Illuminate\Http\Request;

class TestFGStockInController extends Controller
{
    public function test()
    {
        try {
            // Create a test record
            $fgStockIn = new FGStockIn();
            $fgStockIn->AC = 'TEST-AC';
            $fgStockIn->AC_NAME = 'Test Customer';
            $fgStockIn->MCS = 'TEST-MCS';
            $fgStockIn->MODEL = 'TEST-MODEL';
            $fgStockIn->DESIGN = 'Test Design';
            $fgStockIn->C = 1;
            $fgStockIn->WO = 'TEST-WO';
            $fgStockIn->SO = 'TEST-SO';
            $fgStockIn->STOCKIN_REF = 'TEST-REF';
            $fgStockIn->REC_DATE = date('Y-m-d');
            $fgStockIn->TR_TYPE = 'STOCKIN';
            $fgStockIn->WHS_LOC = 'TEST-LOC';
            $fgStockIn->UNIT = 'PCS';
            $fgStockIn->QTY_IN = 100;
            $fgStockIn->QTY_OUT = 0;
            $fgStockIn->AC_CODE = 'TEST-CODE';
            $fgStockIn->ORIGIN_TRAN = 0;
            $fgStockIn->ORIGIN_REF = 0;
            $fgStockIn->UID = 'TEST-USER';
            $fgStockIn->SYSTEM_DATE = date('Y-m-d');
            $fgStockIn->SYSTEM_TIME = date('H:i:s');
            
            $fgStockIn->save();
            
            // Retrieve the record
            $record = FGStockIn::where('SO', 'TEST-SO')->first();
            
            return response()->json([
                'success' => true,
                'message' => 'Test successful',
                'record' => $record
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Test failed: ' . $e->getMessage()
            ], 500);
        }
    }
}