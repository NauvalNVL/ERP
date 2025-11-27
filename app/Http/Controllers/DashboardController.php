<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Inertia\Inertia;
use App\Models\SalesTeam;
use App\Models\ProductGroup;
use App\Models\Machine;
use App\Models\PaperQuality;
use App\Models\CustomerGroup;
use App\Models\Industry;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\SalesOrder;
use App\Models\DeliveryOrder;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'salesTeams' => SalesTeam::count(),
            'productGroups' => ProductGroup::count(),
            'machines' => Machine::count(),
            'paperQualities' => PaperQuality::count(),
            'customerGroups' => CustomerGroup::count(),
        ];

        $salesOrdersCount = Schema::hasTable((new SalesOrder())->getTable())
            ? SalesOrder::count()
            : 0;

        $businessStats = [
            'customers' => Customer::count(),
            'invoices' => Invoice::count(),
            'salesOrders' => $salesOrdersCount,
            'deliveryOrders' => DeliveryOrder::count(),
        ];

        $recentIndustries = Industry::orderByDesc('code')
            ->limit(5)
            ->get(['code', 'name', 'status']);

        $chartData = [
            'labels' => ['Sales Teams', 'Product Groups', 'Machines', 'Paper Qualities'],
            'data' => [
                $stats['salesTeams'],
                $stats['productGroups'],
                $stats['machines'],
                $stats['paperQualities'],
            ],
        ];

        $invoiceTrendLabels = [];
        $invoiceTrendValues = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i)->startOfMonth();
            $year = (int) $date->format('Y');
            $month = (int) $date->format('m');

            $invoiceCount = Invoice::forPeriod($year, $month)->count();

            $invoiceTrendLabels[] = $date->format('M Y');
            $invoiceTrendValues[] = $invoiceCount;
        }

        $invoiceTrendData = [
            'labels' => $invoiceTrendLabels,
            'data' => $invoiceTrendValues,
        ];

        return Inertia::render('Dashboard', [
            'dashboardStats' => $stats,
            'businessStats' => $businessStats,
            'kpiChartData' => $chartData,
            'recentIndustries' => $recentIndustries,
            'invoiceTrendData' => $invoiceTrendData,
        ]);
    }
}
