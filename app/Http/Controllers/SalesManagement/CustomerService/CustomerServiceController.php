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

    public function apiSalesOrderDetail($soNumber)
    {
        try {
            Log::info('Fetching Sales Order detail', ['so_number' => $soNumber]);

            // Check SO table structure first
            try {
                $soColumns = DB::select("DESCRIBE so");
                Log::info('SO table columns', ['columns' => array_map(function($col) { return $col->Field; }, $soColumns)]);
            } catch (\Exception $e) {
                Log::warning('Could not describe SO table', ['error' => $e->getMessage()]);
            }

            // Ambil baris SO utama (COMP_Num = 'Main') terlebih dahulu untuk
            // memastikan field seperti UNIT_PRICE, CURR, EX_RATE, AMOUNT,
            // BASE_AMOUNT sesuai dengan komponen Main.
            $soRow = DB::table('so')
                ->where('SO_Num', $soNumber)
                ->where(function ($q) {
                    $q->where('COMP_Num', 'Main')
                      ->orWhereNull('COMP_Num');
                })
                ->first();

            // Jika tidak ditemukan baris Main (data lama), fallback ke baris pertama SO_Num tsb
            if (!$soRow) {
                $soRow = DB::table('so')
                    ->where('SO_Num', $soNumber)
                    ->first();
            }

            if (!$soRow) {
                Log::warning('Sales Order not found', ['so_number' => $soNumber]);
                return response()->json([
                    'success' => false,
                    'message' => 'Sales Order not found'
                ], 404);
            }

            // Ubah ke array supaya akses kolom aman (tidak memicu undefined property)
            $so = (array) $soRow;

            // Bangun objek soInfo terpadu dengan fallback beberapa nama kolom yang umum
            $soInfo = (object) [
                'so_number' => $so['SO_Num'] ?? $soNumber,
                'so_date' => $so['SO_DMY'] ?? null,
                'so_status' => $so['STS'] ?? null,
                'customer_code' => $so['AC_Num'] ?? $so['ACNUM'] ?? $so['Customer_Code'] ?? null,
                'customer_name' => $so['AC_NAME'] ?? $so['CUST_NAME'] ?? $so['Customer_Name'] ?? null,
                'customer_name_alt' => $so['CUST_NAME'] ?? null,
                'customer_name_from_table' => null,
                'customer_address1' => $so['ADDRESS1'] ?? null,
                'customer_address2' => $so['ADDRESS2'] ?? null,
                'customer_address3' => $so['ADDRESS3'] ?? null,
                'customer_address' => $so['Customer_Address'] ?? null,
                'customer_po_number' => $so['PO_Num'] ?? null,
                'po_date' => $so['PO_DATE'] ?? null,
                'model' => $so['MODEL'] ?? null,
                'set_quantity' => $so['SO_QTY'] ?? null,
                'set_quantity_alt' => $so['Set_Qty'] ?? null,
                'master_card_seq' => $so['MCS_Num'] ?? null,
                'salesperson_code' => $so['Salesperson'] ?? $so['SLM'] ?? null,
                'salesperson_code_alt' => $so['SLM'] ?? null,
                'salesperson_name' => null,
                'salesperson_name_alt' => null,
                'remark' => $so['SO_REMARK'] ?? $so['Remark'] ?? null,
                'remark_alt' => $so['Remark'] ?? null,
                'instruction1' => $so['SO_INSTRUCTION_1'] ?? $so['Instruction1'] ?? null,
                'instruction1_alt' => $so['Instruction1'] ?? null,
                'instruction2' => $so['SO_INSTRUCTION_2'] ?? $so['Instruction2'] ?? null,
                'instruction2_alt' => $so['Instruction2'] ?? null,
                'delivery_location' => $so['DELIVERY_TO'] ?? $so['Delivery_Location'] ?? null,
                'delivery_location_alt' => $so['Delivery_Location'] ?? null,
                'delivery_terms' => $so['DELIVERY_TERMS'] ?? $so['Delivery_Terms'] ?? null,
                'delivery_terms_alt' => $so['Delivery_Terms'] ?? null,
                'payment_terms' => $so['PAYMENT_TERMS'] ?? $so['Payment_Terms'] ?? null,
                'payment_terms_alt' => $so['Payment_Terms'] ?? null,
                'currency' => $so['CURR'] ?? $so['Curr'] ?? null,
                'currency_alt' => $so['Curr'] ?? null,
                'exchange_rate' => $so['EX_RATE'] ?? null,
                'order_mode' => $so['Order_Mode'] ?? null,
                'order_group' => $so['GROUP_'] ?? null,
                'order_type' => $so['TYPE'] ?? null,
                'delivery_code' => $so['D_LOC_Num'] ?? null,
                'delivery_address_1' => $so['DELIVERY_ADD_1'] ?? null,
                'delivery_address_2' => $so['DELIVERY_ADD_2'] ?? null,
                'lot_number' => $so['LOT_NUM'] ?? null,
                // harga & nilai
                'unit_price' => $so['UNIT_PRICE'] ?? null,
                'amount' => $so['AMOUNT'] ?? null,
                'base_amount' => $so['BASE_AMOUNT'] ?? null,
            ];

            Log::info('SO Info unified object', ['data' => $soInfo]);

            // Get item details from SO table directly
            $itemDetails = [];
            try {
                // Get main item from SO table
                $mainItem = [
                    'item_code' => $soInfo->model ?? '',
                    'description' => $soInfo->model ?? '',
                    'pd' => 'PD001', // Default PD
                    'pcs' => $soInfo->set_quantity ?? $soInfo->set_quantity_alt ?? 0,
                    'unit' => 'PCS', // Default unit
                    'order_qty' => $soInfo->set_quantity ?? $soInfo->set_quantity_alt ?? 0,
                    'net_delivery' => $soInfo->set_quantity ?? $soInfo->set_quantity_alt ?? 0,
                    'balance' => $soInfo->set_quantity ?? $soInfo->set_quantity_alt ?? 0,
                    'max_do' => $soInfo->set_quantity ?? $soInfo->set_quantity_alt ?? 0
                ];
                $itemDetails[] = (object) $mainItem;
                Log::info('Main item created', ['item' => $mainItem]);
            } catch (\Exception $e) {
                Log::warning('Error getting main item from SO table', ['error' => $e->getMessage()]);
            }

            // Try to get item details from so_item table if exists
            try {
                $soItemDetails = DB::table('so_item')
                    ->where('SO_Num', $soNumber)
                    ->select([
                        'Item_Code as item_code',
                        'Description as description',
                        'PD as pd',
                        'PCS as pcs',
                        'Unit as unit',
                        'Order_Qty as order_qty',
                        'Net_Delivery as net_delivery',
                        'Balance as balance',
                        'Max_DO as max_do'
                    ])
                    ->get();

                if ($soItemDetails->count() > 0) {
                    $itemDetails = $soItemDetails->toArray();
                    Log::info('Item details from so_item table', ['count' => $soItemDetails->count()]);
                } else {
                    Log::info('No items found in so_item table');
                }
            } catch (\Exception $e) {
                Log::warning('so_item table not available', ['error' => $e->getMessage()]);
            }

            // Get fittings (Fit1..Fit9) dari tabel SO berdasarkan COMP_Num selain 'Main'
            $fittings = [];
            try {
                $componentRows = DB::table('so')
                    ->where('SO_Num', $soNumber)
                    ->where('COMP_Num', '<>', 'Main')
                    ->orderBy('COMP_Num')
                    ->get();

                if ($componentRows->count() > 0) {
                    foreach ($componentRows as $componentRow) {
                        $compName = trim((string) ($componentRow->COMP_Num ?? ''));
                        if ($compName === '') {
                            continue;
                        }

                        // Order quantity & unit dari baris SO komponen tsb
                        $orderQty = (float) ($componentRow->SO_QTY ?? 0);
                        $unit = $componentRow->UNIT ?? '';

                        // Hitung net delivery dari tabel DO untuk komponen ini
                        $netDelivery = 0.0;
                        try {
                            $netDelivery = (float) DB::table('DO')
                                ->where('SO_Num', $soNumber)
                                ->where('COMP', $compName)
                                ->sum('DO_Qty');
                        } catch (\Exception $e) {
                            Log::warning('Error computing DO net delivery for component in CustomerServiceController', [
                                'so_number' => $soNumber,
                                'component' => $compName,
                                'error' => $e->getMessage(),
                            ]);
                            $netDelivery = 0.0;
                        }

                        $balance = $orderQty - $netDelivery;

                        $fittings[] = [
                            'component' => $compName,
                            'design' => $componentRow->P_DESIGN ?? '',
                            'pcs' => $componentRow->PER_SET ?? '',
                            'unit' => $unit,
                            'part_number' => $componentRow->PART_NUMBER ?? '',
                            'order_qty' => $orderQty,
                            'net_delivery' => $netDelivery > 0 ? $netDelivery : '',
                            'balance' => $balance,
                        ];

                        // Batasi maksimal 9 fitting (Fit1..Fit9)
                        if (count($fittings) >= 9) {
                            break;
                        }
                    }

                    Log::info('Fittings from SO table prepared', [
                        'so_number' => $soNumber,
                        'count' => count($fittings),
                    ]);
                } else {
                    Log::info('No fitting components found in SO table for this SO', [
                        'so_number' => $soNumber,
                    ]);
                }
            } catch (\Exception $e) {
                Log::warning('Error fetching fittings from SO table in CustomerServiceController', [
                    'so_number' => $soNumber,
                    'error' => $e->getMessage(),
                ]);
                $fittings = [];
            }

            // Get Delivery Orders aggregated by component
            $deliveryOrders = [];
            $deliveryOrderSummary = [];
            try {
                $doRecords = DB::table('DO')
                    ->where('SO_Num', $soNumber)
                    ->select([
                        'DO_Num',
                        'COMP as component',
                        'DO_Qty as do_qty',
                        'DO_DMY as do_date',
                        'Status as status'
                    ])
                    ->get();

                if ($doRecords->count() > 0) {
                    $deliveryOrders = $doRecords->toArray();

                    // Aggregate by component
                    foreach ($doRecords as $do) {
                        $comp = $do->component ?? 'Main';
                        if (!isset($deliveryOrderSummary[$comp])) {
                            $deliveryOrderSummary[$comp] = 0;
                        }
                        $deliveryOrderSummary[$comp] += floatval($do->do_qty ?? 0);
                    }

                    Log::info('Delivery Orders found', [
                        'count' => $doRecords->count(),
                        'summary' => $deliveryOrderSummary
                    ]);
                } else {
                    Log::info('No delivery orders found for SO', ['so_number' => $soNumber]);
                }
            } catch (\Exception $e) {
                Log::warning('Error fetching delivery orders', ['error' => $e->getMessage()]);
            }

            // Get Invoices related to this SO
            $invoices = [];
            $invoiceSummary = [];
            try {
                $invRecords = DB::table('INV')
                    ->where('SO_NUM', $soNumber)
                    ->select([
                        'IV_NUM as invoice_number',
                        'IV_QTY as invoice_qty',
                        'IV_DMY as invoice_date',
                        'IV_SECOND_REF as do_reference',
                        'IV_STS as invoice_status'
                    ])
                    ->get();

                if ($invRecords->count() > 0) {
                    $invoices = $invRecords->toArray();

                    // Calculate total invoiced
                    foreach ($invRecords as $inv) {
                        $invoiceSummary['total_invoiced'] = ($invoiceSummary['total_invoiced'] ?? 0) + floatval($inv->invoice_qty ?? 0);
                        $invoiceSummary['invoice_list'][] = $inv->invoice_number;
                    }

                    Log::info('Invoices found', [
                        'count' => $invRecords->count(),
                        'summary' => $invoiceSummary
                    ]);
                } else {
                    Log::info('No invoices found for SO', ['so_number' => $soNumber]);
                }
            } catch (\Exception $e) {
                Log::warning('Error fetching invoices', ['error' => $e->getMessage()]);
            }

            // Calculate Net Delivered from DO
            $netDelivered = 0;
            try {
                $netDelivered = DB::table('DO')
                    ->where('SO_Num', $soNumber)
                    ->sum('DO_Qty');
                Log::info('Net Delivered calculated', ['net_delivered' => $netDelivered]);
            } catch (\Exception $e) {
                Log::warning('Error calculating net delivered', ['error' => $e->getMessage()]);
            }


            // Process and clean order info data with better logging
            $processedOrderInfo = [
                'so_number' => $soInfo->so_number ?? '',
                'so_date' => $soInfo->so_date ?? date('Y-m-d'),
                'so_status' => $soInfo->so_status ?? 'Open',
                'customer_code' => $soInfo->customer_code ?? '',
                'customer_name' => $soInfo->customer_name ?? $soInfo->customer_name_alt ?? $soInfo->customer_name_from_table ?? 'Unknown Customer',
                'customer_address' => trim(implode("\n", array_filter([
                    $soInfo->customer_address ?? '',
                    $soInfo->customer_address1 ?? '',
                    $soInfo->customer_address2 ?? '',
                    $soInfo->customer_address3 ?? ''
                ]))) ?: 'No Address',
                'customer_po_number' => $soInfo->customer_po_number ?? '',
                'po_date' => $soInfo->po_date ?? '',
                'model' => $soInfo->model ?? 'Standard Model',
                'set_quantity' => $soInfo->set_quantity ?? $soInfo->set_quantity_alt ?? 1,
                'master_card_seq' => $soInfo->master_card_seq ?? '',
                'salesperson_code' => $soInfo->salesperson_code ?? $soInfo->salesperson_code_alt ?? '',
                'salesperson_name' => $soInfo->salesperson_name ?? $soInfo->salesperson_name_alt ?? 'Not Assigned',
                'remark' => $soInfo->remark ?? $soInfo->remark_alt ?? '',
                'instruction1' => $soInfo->instruction1 ?? $soInfo->instruction1_alt ?? '',
                'instruction2' => $soInfo->instruction2 ?? $soInfo->instruction2_alt ?? '',
                'delivery_location' => $soInfo->delivery_location ?? $soInfo->delivery_location_alt ?? 'Main Warehouse',
                'delivery_terms' => $soInfo->delivery_terms ?? $soInfo->delivery_terms_alt ?? 'Standard Delivery',
                'payment_terms' => $soInfo->payment_terms ?? $soInfo->payment_terms_alt ?? '30 Days',
                'currency' => $soInfo->currency ?? $soInfo->currency_alt ?? 'IDR',
                'exchange_rate' => $soInfo->exchange_rate ?? 1,
                'order_mode' => $soInfo->order_mode ?? 'D-Order by Customer + Deliver & Invoice to Customer',
                'order_group' => $soInfo->order_group ?? 'Sales',
                'order_type' => $soInfo->order_type ?? 'S1',
                'delivery_code' => $soInfo->delivery_code ?? '',
                'delivery_address_1' => $soInfo->delivery_address_1 ?? '',
                'delivery_address_2' => $soInfo->delivery_address_2 ?? '',
                'lot_number' => $soInfo->lot_number ?? '',
                'unit_price' => $soInfo->unit_price ?? 0,
                'amount' => $soInfo->amount ?? 0,
                'base_amount' => $soInfo->base_amount ?? 0,
            ];

            Log::info('Processed order info', ['processed_data' => $processedOrderInfo]);

            // Calculate balance for main item
            $soQty = floatval($processedOrderInfo['set_quantity'] ?? 0);
            $balance = $soQty - $netDelivered;

            return response()->json([
                'success' => true,
                'data' => [
                    'order_info' => $processedOrderInfo,
                    'item_details' => $itemDetails,
                    'fittings' => $fittings,
                    'delivery_orders' => $deliveryOrders,
                    'delivery_order_summary' => $deliveryOrderSummary,
                    'invoices' => $invoices,
                    'invoice_summary' => $invoiceSummary,
                    'net_delivered' => $netDelivered,
                    'balance' => $balance
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching Sales Order detail', [
                'so_number' => $soNumber,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to load Sales Order details: ' . $e->getMessage()
            ], 500);
        }
    }

    public function apiAccountCreditData()
    {
        // Dummy data for Customer Account Credit
        return response()->json([
            'totalCreditLimit' => 0,
            'outstandingBalance' => 0,
            'usedCreditPercentage' => 0,
        ]);
    }

    public function apiDeliveryScheduleData()
    {
        // Dummy data for Sales Order Delivery Schedule
        return response()->json([]);
    }

    public function apiFinishedGoodsData()
    {
        // Dummy data for Customer Finished Goods
        return response()->json([]);
    }

    public function apiProductionMonitoringData()
    {
        // Dummy data for Production Monitoring Board [PMB3]
        return response()->json([]);
    }
}
