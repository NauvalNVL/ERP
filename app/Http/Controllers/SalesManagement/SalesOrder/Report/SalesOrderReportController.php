<?php

namespace App\Http\Controllers\SalesManagement\SalesOrder\Report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class SalesOrderReportController extends Controller
{
    /**
     * Generate SO report
     */
    public function apiGenerateSoReport(Request $request): JsonResponse
    {
        // TODO: Implement SO report generation logic
        return response()->json([
            'success' => true,
            'message' => 'SO report generated successfully',
            'data' => []
        ]);
    }
}
