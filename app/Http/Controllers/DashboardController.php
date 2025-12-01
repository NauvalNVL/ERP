<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
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
            'productGroups' => ProductGroup::count(),
            'machines' => Machine::count(),
            'paperQualities' => PaperQuality::count(),
            'customerGroups' => CustomerGroup::count(),
        ];

        // Sales Orders: gunakan tabel legacy CPS 'SO' (bukan tabel Laravel 'sales_orders')
        // Hitung hanya dokumen utama dengan menghitung SO_Num yang unik (Main + Fit share the same SO_Num)
        $salesOrdersCount = Schema::hasTable('SO')
            ? DB::table('SO')->distinct()->count('SO_Num')
            : 0;

        // Delivery Orders: hitung hanya DO_Num yang unik (setiap DO bisa punya beberapa baris komponen)
        $deliveryOrdersCount = Schema::hasTable('DO')
            ? DB::table('DO')->distinct()->count('DO_Num')
            : 0;

        // Invoices: hitung hanya IV_NUM yang unik (satu dokumen invoice, beberapa baris komponen)
        $invoiceCount = Schema::hasTable('INV')
            ? DB::table('INV')->distinct()->count('IV_NUM')
            : 0;

        $businessStats = [
            'customers' => Customer::count(),
            'invoices' => $invoiceCount,
            'salesOrders' => $salesOrdersCount,
            'deliveryOrders' => $deliveryOrdersCount,
        ];

        $recentIndustries = Industry::orderByDesc('code')
            ->limit(5)
            ->get(['code', 'name', 'status']);

        $chartData = [
            'labels' => ['Product Groups', 'Machines', 'Paper Qualities'],
            'data' => [
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

            // Hitung invoice per bulan berdasarkan jumlah dokumen (IV_NUM) yang unik,
            // bukan jumlah baris komponen (Main + Fit) di tabel INV
            $invoiceCount = Invoice::forPeriod($year, $month)
                ->distinct()
                ->count('IV_NUM');

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
