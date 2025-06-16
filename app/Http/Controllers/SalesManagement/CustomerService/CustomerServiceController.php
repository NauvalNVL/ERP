<?php

namespace App\Http\Controllers\SalesManagement\CustomerService;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CustomerServiceController extends Controller
{
    public function apiDashboardData()
    {
        // Dummy data for Customer Service Dashboard
        return response()->json([
            'totalCustomers' => 1250,
            'openTickets' => 85,
            'avgResponseTime' => '4h 15m',
        ]);
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