<?php

namespace App\Http\Controllers\SalesManagement\SalesOrder\Report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SalesOrderReportController extends Controller
{
    public function apiIndexReportFormats()
    {
        // For demonstration, returning dummy data for report formats
        $dummyReportFormats = [
            ['code' => 'RPT001', 'name' => 'Sales Summary Report'],
            ['code' => 'RPT002', 'name' => 'Customer Activity Report'],
            ['code' => 'RPT003', 'name' => 'Product Performance Report'],
            ['code' => 'RPT004', 'name' => 'Monthly Sales Forecast'],
            ['code' => 'RPT005', 'name' => 'Cancelled Orders Report'],
        ];

        return response()->json($dummyReportFormats);
    }

    public function apiGenerateSoReport(Request $request)
    {
        // For demonstration, just returning the received form data
        // In a real application, you would use this data to query your database
        // and generate the actual sales order report.
        return response()->json([
            'message' => 'Sales Order Report generation request received!',
            'data' => $request->all()
        ]);
    }
} 