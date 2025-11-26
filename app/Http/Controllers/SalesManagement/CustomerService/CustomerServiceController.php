<?php

namespace App\Http\Controllers\SalesManagement\CustomerService;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CustomerServiceController extends Controller
{
    public function apiDashboardData()
    {
        try {
            // Safely get counts from core CPS tables. If a table is missing, fall back to 0.

            // Total customers
            try {
                $totalCustomers = DB::table('CUSTOMER')->count();
            } catch (\Exception $e) {
                Log::warning('CUSTOMER table not available for dashboard data', ['error' => $e->getMessage()]);
                $totalCustomers = 0;
            }

            // Sales orders (total and outstanding-like statuses)
            try {
                $totalSalesOrders = DB::table('so')->count();

                $outstandingSalesOrders = DB::table('so')
                    ->whereIn('STS', [
                        'Outstanding', 'OUTSTANDING', 'Outs', 'OUTS',
                        'OPEN', 'O', 'PENDING', 'ACTIVE',
                    ])
                    ->count();
            } catch (\Exception $e) {
                Log::warning('SO table not available for dashboard data', ['error' => $e->getMessage()]);
                $totalSalesOrders = 0;
                $outstandingSalesOrders = 0;
            }

            // Delivery orders (total and active-like statuses)
            try {
                $totalDeliveryOrders = DB::table('DO')->count();

                $openDeliveryOrders = DB::table('DO')
                    ->whereIn('Status', [
                        'Active', 'ACTIVE', 'Outs', 'OUTS', 'Open', 'OPEN',
                    ])
                    ->count();
            } catch (\Exception $e) {
                Log::warning('DO table not available for dashboard data', ['error' => $e->getMessage()]);
                $totalDeliveryOrders = 0;
                $openDeliveryOrders = 0;
            }

            // Invoices (total and unposted)
            try {
                $totalInvoices = DB::table('INV')->count();

                $unpostedInvoices = DB::table('INV')
                    ->where(function ($q) {
                        $q->whereNull('IV_STS')
                          ->orWhere('IV_STS', '!=', 'Posted');
                    })
                    ->count();
            } catch (\Exception $e) {
                Log::warning('INV table not available for dashboard data', ['error' => $e->getMessage()]);
                $totalInvoices = 0;
                $unpostedInvoices = 0;
            }

            return response()->json([
                'total_customers' => $totalCustomers,
                'total_sales_orders' => $totalSalesOrders,
                'outstanding_sales_orders' => $outstandingSalesOrders,
                'total_delivery_orders' => $totalDeliveryOrders,
                'open_delivery_orders' => $openDeliveryOrders,
                'total_invoices' => $totalInvoices,
                'unposted_invoices' => $unpostedInvoices,
            ]);
        } catch (\Exception $e) {
            Log::error('Error building Customer Service dashboard data', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'error' => 'Failed to load dashboard data',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function apiAccountCreditData()
    {
        // Dummy data for Customer Account Credit
        return response()->json([
            'totalCreditLimit' => 5000000,
            'outstandingBalance' => 1200000,
            'usedCreditPercentage' => 24,
        ]);
    }

    public function apiDeliveryScheduleData()
    {
        // Dummy data for Sales Order Delivery Schedule
        return response()->json([
            ['orderId' => 'SO-001', 'customerName' => 'Customer A', 'deliveryDate' => '2025-07-10', 'status' => 'In Transit'],
            ['orderId' => 'SO-002', 'customerName' => 'Customer B', 'deliveryDate' => '2025-07-15', 'status' => 'Pending'],
            ['orderId' => 'SO-003', 'customerName' => 'Customer C', 'deliveryDate' => '2025-07-05', 'status' => 'Delivered'],
        ]);
    }

    public function apiFinishedGoodsData()
    {
        // Dummy data for Customer Finished Goods
        return response()->json([
            ['productId' => 'PROD-001', 'customerName' => 'Customer X', 'quantity' => 150, 'location' => 'WH-A', 'status' => 'Ready for Pickup'],
            ['productId' => 'PROD-002', 'customerName' => 'Customer Y', 'quantity' => 200, 'location' => 'WH-B', 'status' => 'In Warehouse'],
            ['productId' => 'PROD-003', 'customerName' => 'Customer Z', 'quantity' => 50, 'location' => 'WH-A', 'status' => 'Shipped'],
        ]);
    }

    public function apiProductionMonitoringData()
    {
        // Dummy data for Production Monitoring Board [PMB3]
        return response()->json([
            ['machineId' => 'MCH-001', 'currentJob' => 'Order #123', 'progress' => 75, 'status' => 'Running'],
            ['machineId' => 'MCH-002', 'currentJob' => '' , 'progress' => 0, 'status' => 'Idle'],
            ['machineId' => 'MCH-003', 'currentJob' => 'Order #456', 'progress' => 30, 'status' => 'Running'],
            ['machineId' => 'MCH-004', 'currentJob' => 'Maintenance', 'progress' => 0, 'status' => 'Maintenance'],
        ]);
    }
}
