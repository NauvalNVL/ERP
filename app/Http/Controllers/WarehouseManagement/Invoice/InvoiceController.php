<?php

namespace App\Http\Controllers\WarehouseManagement\Invoice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Invoice;
use App\Models\Customer;

class InvoiceController extends Controller
{
    /**
     * Extract currency code only (3-letter uppercase)
     * Handles cases where database stores "IDR" or "IDR - INDONESIA" or "INDONESIA"
     */
    private function extractCurrencyCode($currencyString)
    {
        if (empty($currencyString)) {
            return 'IDR'; // Default
        }

        $str = strtoupper(trim($currencyString));

        // If it's already 3 letters, return it
        if (strlen($str) === 3 && preg_match('/^[A-Z]{3}$/', $str)) {
            return $str;
        }

        // Extract first 3-letter word (currency code)
        if (preg_match('/\b([A-Z]{3})\b/', $str, $matches)) {
            return $matches[1];
        }

        // Map common currency names to codes
        $currencyMap = [
            'INDONESIA' => 'IDR',
            'RUPIAH' => 'IDR',
            'DOLLAR' => 'USD',
            'SINGAPORE' => 'SGD',
            'EURO' => 'EUR',
            'YEN' => 'JPY',
            'YUAN' => 'CNY',
            'RINGGIT' => 'MYR',
        ];

        // Check if string contains any mapped currency name
        foreach ($currencyMap as $name => $code) {
            if (strpos($str, $name) !== false) {
                return $code;
            }
        }

        // If nothing matches, take first 3 characters
        return substr($str, 0, 3) ?: 'IDR';
    }

    /**
     * Get current period delivery orders for invoice preparation
     * Supports customer filtering
     */
    public function currentPeriodDo(Request $request)
    {
        try {
            $now = now();
            $yyyy = $request->input('year', $now->format('Y'));
            $mm = $request->input('month', $now->format('m'));
            $customerCode = $request->input('customer_code');

            $query = DB::table('DO')
                ->select([
                    'DO_Num as do_number',
                    'DO_DMY as do_date',
                    'AC_Num as customer_code',
                    'AC_Name as customer_name',
                    'SO_Num as so_number',
                    'Curr as currency',
                    'DO_Tran_Amt as amount',
                    'Status as status',
                    'DO_VHC_Num as vehicle',
                    'No as item',
                ])
                ->where('DOYYYY', $yyyy)
                ->where('DOMM', $mm)
                ->where(function ($q) {
                    $q->whereNull('Status')
                      ->orWhere('Status', '!=', 'Invoiced');
                });

            // Filter by customer if provided
            if ($customerCode) {
                $query->where('AC_Num', $customerCode);
            }

            $dos = $query->orderBy('DO_Num')->get();

            // Extract currency codes only (remove full names)
            $dos = $dos->map(function ($do) {
                $do->currency = $this->extractCurrencyCode($do->currency);
                return $do;
            });

            return response()->json($dos);
        } catch (\Exception $e) {
            Log::error('Error fetching current period DOs: ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to fetch delivery orders',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get customer details for invoice preparation
     */
    public function getCustomerDetails(Request $request)
    {
        $customerCode = trim((string) $request->input('customer_code'));

        Log::info("Customer lookup request for code: {$customerCode}");

        if (!$customerCode) {
            Log::info("No customer code provided, returning empty data");
            return response()->json([
                'customer_code' => '',
                'customer_name' => '',
                'currency' => 'IDR',
                'tax_index_no' => '',
                'salesperson' => '',
                'area' => '',
            ]);
        }

        try {
            // First try to get customer info from Customer model (most reliable)
            $customer = null;
            try {
                $customer = Customer::where('CODE', $customerCode)->first();
            } catch (\Exception $e) {
                Log::warning('Unable to query Customer model for customer lookup', [
                    'customer_code' => $customerCode,
                    'error' => $e->getMessage(),
                ]);
            }

            if ($customer) {
                Log::info("Customer found in Customer model: {$customerCode}", ['customer' => $customer->toArray()]);

                return response()->json([
                    'customer_code' => $customer->CODE,
                    'customer_name' => $customer->NAME,
                    'currency' => $this->extractCurrencyCode($customer->CURRENCY),
                    'tax_index_no' => '',
                    'salesperson' => $customer->SLM ?? '',
                    'area' => $customer->AREA ?? '',
                    'debug_info' => [
                        'found_in_table' => 'Customer Model',
                        'search_code' => $customerCode
                    ]
                ]);
            }
            
            // Fallback: Try DO table if Customer model doesn't work
            $doCustomer = null;
            try {
                $doCustomer = DB::table('DO')
                    ->where('AC_Num', $customerCode)
                    ->select(['AC_Num as customer_code', 'AC_Name as customer_name', 'Curr as currency'])
                    ->first();
            } catch (\Exception $e) {
                Log::warning('Unable to query DO table for customer lookup', [
                    'customer_code' => $customerCode,
                    'error' => $e->getMessage(),
                ]);
            }

            if ($doCustomer && !empty($doCustomer->customer_name)) {
                Log::info("Customer found in DO table: {$customerCode}", ['customer' => $doCustomer]);

                return response()->json([
                    'customer_code' => $doCustomer->customer_code,
                    'customer_name' => $doCustomer->customer_name,
                    'currency' => $this->extractCurrencyCode($doCustomer->currency),
                    'tax_index_no' => '',
                    'salesperson' => '',
                    'area' => '',
                    'debug_info' => [
                        'found_in_table' => 'DO',
                        'search_code' => $customerCode
                    ]
                ]);
            }

            // Fallback: Try dedicated customer tables
            $customer = null;
            $usedTable = null;
            $tables = ['CUSTOMER', 'Customer_Account', 'customer_account', 'update_customer_accounts'];

            foreach ($tables as $table) {
                try {
                    Log::info("Trying table: {$table} for customer code: {$customerCode}");

                    $customer = DB::table($table)
                        ->where(function($query) use ($customerCode) {
                            $query->where('CODE', $customerCode)
                                  ->orWhere('customer_code', $customerCode)
                                  ->orWhere('Customer_Code', $customerCode);
                        })
                        ->first();

                    if ($customer) {
                        $usedTable = $table;
                        Log::info("Customer found in table: {$table}", ['customer' => $customer]);
                        break;
                    }
                } catch (\Exception $e) {
                    Log::info("Table {$table} doesn't exist or query failed: " . $e->getMessage());
                    continue;
                }
            }

            if ($customer) {
                $currency = $customer->CURRENCY ?? $customer->currency ?? $customer->Currency ?? $customer->currency_code ?? 'IDR';
                $customerName = $customer->NAME ?? $customer->customer_name ?? $customer->Customer_Name ?? '';
                $customerCodeResult = $customer->CODE ?? $customer->customer_code ?? $customer->Customer_Code ?? '';

                return response()->json([
                    'customer_code' => $customerCodeResult,
                    'customer_name' => $customerName,
                    'currency' => $this->extractCurrencyCode($currency),
                    'tax_index_no' => $customer->tax_index_no ?? $customer->Tax_Index_No ?? '',
                    'salesperson' => $customer->SLM ?? $customer->salesperson ?? $customer->Salesperson ?? $customer->salesperson_code ?? '',
                    'area' => $customer->AREA ?? $customer->area ?? $customer->Area ?? $customer->geographical ?? '',
                    'debug_info' => [
                        'found_in_table' => $usedTable,
                        'search_code' => $customerCode
                    ]
                ]);
            }

            // Customer not found anywhere
            Log::warning("Customer not found in any table: {$customerCode}");
            return response()->json([
                'customer_code' => $customerCode,
                'customer_name' => '',
                'currency' => 'IDR',
                'tax_index_no' => '',
                'salesperson' => '',
                'area' => '',
                'debug_info' => [
                    'searched_tables' => array_merge(['DO'], $tables),
                    'found_in_table' => null,
                    'search_code' => $customerCode
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching customer details: ' . $e->getMessage(), [
                'customer_code' => $customerCode,
                'exception' => $e->getTraceAsString()
            ]);

            return response()->json([
                'customer_code' => $customerCode,
                'customer_name' => '',
                'currency' => 'IDR',
                'tax_index_no' => '',
                'salesperson' => '',
                'area' => '',
                'error' => 'Database error occurred',
                'debug_info' => [
                    'error_message' => $e->getMessage(),
                    'search_code' => $customerCode
                ]
            ]);
        }
    }

    /**
     * Test/seed sample customers for development
     */
    public function seedTestCustomers(Request $request)
    {
        try {
            // Insert sample customers directly (table should exist)
            $sampleCustomers = [
                ['code' => '000004', 'name' => 'AGILITY INTERNATIONAL, PT.', 'currency' => 'USD', 'salesperson' => 'S001', 'area' => 'Jakarta'],
                ['code' => '000211-08', 'name' => 'TEST CUSTOMER COMPANY', 'currency' => 'IDR', 'salesperson' => 'S002', 'area' => 'Surabaya'],
                ['code' => '000283', 'name' => 'SAMPLE CORPORATION', 'currency' => 'USD', 'salesperson' => 'S003', 'area' => 'Bandung'],
            ];

            // Table/column mappings to accommodate varied schemas
            $tables = [
                'CUSTOMER' => [
                    'code' => 'CODE',
                    'name' => 'NAME',
                    'currency' => 'CURRENCY',
                    'salesperson' => 'SLM',
                    'area' => 'AREA',
                ],
                'Customer_Account' => [
                    'code' => 'customer_code',
                    'name' => 'customer_name',
                    'currency' => 'currency',
                    'salesperson' => 'salesperson_code',
                    'area' => 'area',
                ],
                'customer_account' => [
                    'code' => 'customer_code',
                    'name' => 'customer_name',
                    'currency' => 'currency',
                    'salesperson' => 'salesperson_code',
                    'area' => 'area',
                ],
                'update_customer_accounts' => [
                    'code' => 'customer_code',
                    'name' => 'customer_name',
                    'currency' => 'currency',
                    'salesperson' => 'salesperson_code',
                    'area' => 'area',
                ],
            ];
            $insertedCount = 0;
            $usedTable = null;

            foreach ($tables as $table => $columns) {
                try {
                    // Test if table exists by trying to select from it
                    DB::table($table)->limit(1)->get();

                    // If no exception, table exists - insert data
                    foreach ($sampleCustomers as $customer) {
                        $payload = [];
                        $payload[$columns['code']] = $customer['code'];
                        $payload[$columns['name']] = $customer['name'];
                        $payload[$columns['currency']] = $customer['currency'];

                        if (!empty($columns['salesperson'])) {
                            $payload[$columns['salesperson']] = $customer['salesperson'];
                        }
                        if (!empty($columns['area'])) {
                            $payload[$columns['area']] = $customer['area'];
                        }

                        DB::table($table)->updateOrInsert(
                            [$columns['code'] => $customer['code']],
                            $payload
                        );
                        $insertedCount++;
                    }

                    // If we've inserted data, we can stop looking for tables
                    $usedTable = $table;
                    Log::info("Sample customers inserted into table: {$table}");
                    break;

                } catch (\Exception $e) {
                    Log::info("Table {$table} doesn't exist or is not accessible: " . $e->getMessage());
                    continue;
                }
            }

            if ($usedTable) {
                return response()->json([
                    'success' => true,
                    'message' => 'Sample customers created successfully',
                    'table_used' => $usedTable,
                    'customers_inserted' => $insertedCount,
                    'customers' => $sampleCustomers
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'No accessible customer table found',
                    'searched_tables' => $tables
                ]);
            }

        } catch (\Exception $e) {
            Log::error('Error creating sample customers: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'message' => 'Failed to create sample customers'
            ]);
        }
    }

    /**
     * Get sales tax options
     */
    public function getSalesTaxOptions(Request $request)
    {
        // Get tax options from taxrate table
        $taxes = DB::table('taxrate')
            ->select([
                'TAXCODE as code',
                'TAXNAME as name',
                'RATEPPN as rate',
                DB::raw("1 as apply"),  // All taxes in table are applicable
                DB::raw("0 as include") // Default: tax not included in price
            ])
            ->where('RATEPPN', '>', 0) // Only get taxes with PPN rate
            ->orderBy('TAXCODE')
            ->get()
            ->map(function ($tax) {
                return [
                    'code' => $tax->code,
                    'name' => $tax->name,
                    'rate' => floatval($tax->rate),
                    'apply' => true,
                    'include' => false,
                    'status' => 'Active'
                ];
            });

        return response()->json($taxes);
    }

    /**
     * Prepare invoices from selected delivery orders
     */
    public function prepare(Request $request)
    {
        try {
            $payload = $request->validate([
                'do_numbers' => 'required|array|min:1',
                'do_numbers.*' => 'required|string|max:50',
                'customer_code' => 'nullable|string|max:50',
                'tax_index_no' => 'nullable|string|max:50',
                'invoice_date' => 'nullable|date',
                'second_ref' => 'nullable|string|max:50',
                'remark' => 'nullable|string|max:250',
                'invoice_number_mode' => 'nullable|string|in:auto,manual',
                'manual_invoice_number' => 'nullable|string|max:50',
            ]);

            $now = now();
            $yyyy = $request->input('year', $now->format('Y'));
            $mm = $request->input('month', $now->format('m'));
            $invoiceDate = $payload['invoice_date'] ?? $now->format('d/m/Y');
            $taxIndexNo = $payload['tax_index_no'] ?? null;
            $secondRef = $payload['second_ref'] ?? null;
            $remark = $payload['remark'] ?? null;
            $invoiceNumberMode = $payload['invoice_number_mode'] ?? 'auto';
            $manualInvoiceNumber = $payload['manual_invoice_number'] ?? null;

            // Get tax details if tax index provided
            $taxCode = null;
            $taxPercent = null;
            if ($taxIndexNo) {
                $tax = DB::table('Sales_Tax')->where('tax_code', $taxIndexNo)->first();
                if ($tax) {
                    $taxCode = $tax->tax_code;
                    $taxPercent = $tax->tax_rate;
                }
            }

            $existingCount = (int) DB::table('INV')->where('YYYY', $yyyy)->where('MM', $mm)->count();
            $seq = $existingCount + 1;

            $generateNumber = function (int $seq) use ($yyyy, $mm) {
                return sprintf('IV-%s%s-%04d', $yyyy, $mm, $seq);
            };

            $created = [];

            DB::beginTransaction();

            foreach ($payload['do_numbers'] as $doNumber) {
                $do = DB::table('DO')->where('DO_Num', $doNumber)->first();
                if (!$do) {
                    throw new \RuntimeException("DO not found: {$doNumber}");
                }

                // Check if DO is already invoiced
                if ($do->Status === 'Invoiced') {
                    throw new \RuntimeException("DO {$doNumber} is already invoiced");
                }

                // Generate or use manual invoice number
                if ($invoiceNumberMode === 'manual' && $manualInvoiceNumber) {
                    $ivNum = $manualInvoiceNumber;
                    // Check if manual number already exists
                    $exists = DB::table('INV')->where('IV_NUM', $ivNum)->exists();
                    if ($exists) {
                        throw new \RuntimeException("Invoice number {$ivNum} already exists");
                    }
                } else {
                    $ivNum = $generateNumber($seq++);
                }

                // Calculate tax amounts if tax is applied
                $tranAmount = $do->DO_Tran_Amt ?? 0;
                $baseAmount = $do->DO_Base_Amt ?? 0;

                DB::table('INV')->insert([
                    'YYYY' => $yyyy,
                    'MM' => $mm,
                    'IV_NUM' => $ivNum,
                    'IV_STS' => 'Prepared',
                    'IV_DMY' => $invoiceDate,
                    'AR_TERM' => $do->AR_Term ?? null,
                    'IV_SECOND_REF' => $secondRef ?? $do->DO_Num,
                    'AC_NUM' => $do->AC_Num ?? null,
                    'AC_NAME' => $do->AC_Name ?? null,
                    'ITEM' => $do->No ?? null,
                    'MCS_NUM' => $do->MCS_Num ?? null,
                    'MODEL' => $do->Model ?? null,
                    'PRODUCT' => $do->Product ?? null,
                    'COMP' => $do->COMP ?? null,
                    'P_DESIGN' => $do->PD ?? null,
                    'PCS_PER_SET' => $do->PCS_PER_SET ?? null,
                    'UNIT' => $do->Unit ?? null,
                    'PART' => $do->Part_Number ?? null,
                    'INT_L' => $do->INT_L ?? null,
                    'INT_W' => $do->INT_W ?? null,
                    'INT_H' => $do->INT_H ?? null,
                    'EXT_L' => $do->EXT_L ?? null,
                    'EXT_W' => $do->EXT_W ?? null,
                    'EXT_H' => $do->EXT_H ?? null,
                    'FLUTE' => $do->Flute ?? null,
                    'SLM' => $do->SLM ?? null,
                    'IND' => $do->IND ?? null,
                    'AREA' => $do->Area1 ?? null,
                    'GROUP_' => $do->Group_ ?? null,
                    'SO_NUM' => $do->SO_Num ?? null,
                    'SO_TYPE' => $do->SO_Type ?? null,
                    'SO_DMY' => $do->SO_Date ?? null,
                    'PO_NUM' => $do->PO_Num ?? null,
                    'PO_DMY' => $do->PO_Date ?? null,
                    'LOT_NUM' => $do->LOT_Num ?? null,
                    'SO_PQ1' => $do->PQ1 ?? null,
                    'SO_PQ2' => $do->PQ2 ?? null,
                    'SO_PQ3' => $do->PQ3 ?? null,
                    'SO_PQ4' => $do->PQ4 ?? null,
                    'SO_PQ5' => $do->PQ5 ?? null,
                    'IV_QTY' => $do->DO_Qty ?? null,
                    'IV_UNIT_PRICE' => $do->SO_Unit_Price ?? null,
                    'CURR' => $do->Curr ?? 'IDR',
                    'EX_RATE' => $do->Ex_Rate ?? 1,
                    'IV_TRAN_AMT' => $tranAmount,
                    'IV_BASE_AMT' => $baseAmount,
                    'MC_GROSS_M2_PER__PCS' => $do->MC_Gross_M2_Per_Pcs ?? null,
                    'MC_NET_M2_PER_PCS' => $do->MC_Net_M2_Per_Pcs ?? null,
                    'TOTAL_IV_GROSS_M2' => $do->Total_DO_Gross_M2 ?? null,
                    'TOTAL_IV_NET_M2' => $do->Total_DO_Net_M2 ?? null,
                    'MC_GROSS_KG_PER_PCS' => $do->MC_Gross_Kg_Per_Pcs ?? null,
                    'MC_NET_KG_PER_PCS' => $do->MC_Net_Kg_Per_Pcs ?? null,
                    'TOTAL_IV_GROSS_KG' => $do->Total_DO_Gross_KG ?? null,
                    'TOTAL_IV_NET_KG' => $do->Total_DO_Net_KG ?? null,
                    'IV_TAX_CODE' => $taxCode,
                    'IV_TAX_PERCENT' => $taxPercent,
                    'IV_REMARK' => $remark,
                    'NW_UID' => Auth::check() ? Auth::user()->name : 'system',
                    'NW_DATE' => $now->format('d/m/Y'),
                    'NW_TIME' => $now->format('H:i'),
                    'IVDateSK' => (int) ($do->DODateSK ?? 0),
                    'SODateSK' => (int) ($do->SODateSK ?? 0),
                    'PODateSK' => (int) ($do->PODateSK ?? 0),
                ]);

                // Update DO status to Invoiced
                DB::table('DO')->where('DO_Num', $doNumber)->update([
                    'Status' => 'Invoiced',
                    'Invoice_Num' => $ivNum,
                ]);

                $created[] = [
                    'invoice_number' => $ivNum,
                    'do_number' => $doNumber,
                    'amount' => $tranAmount,
                    'tax_amount' => $taxPercent ? ($tranAmount * $taxPercent / 100) : 0,
                ];
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Invoices prepared successfully',
                'data' => $created,
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'error' => 'Failed to prepare invoices: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Cancel an active invoice
     */
    public function cancelInvoice(Request $request)
    {
        try {
            $payload = $request->validate([
                'invoice_number' => 'required|string|max:50',
                'cancel_reason' => 'required|string|max:250',
            ]);

            DB::beginTransaction();

            $invoice = DB::table('INV')->where('IV_NUM', $payload['invoice_number'])->first();

            if (!$invoice) {
                throw new \RuntimeException("Invoice not found");
            }

            if ($invoice->IV_STS === 'Cancelled') {
                throw new \RuntimeException("Invoice is already cancelled");
            }

            // Update invoice status
            DB::table('INV')
                ->where('IV_NUM', $payload['invoice_number'])
                ->update([
                    'IV_STS' => 'Cancelled',
                    'CANCELLED_REASON_1' => $payload['cancel_reason'],
                    'CX_UID' => Auth::check() ? Auth::user()->name : 'system',
                    'CX_DATE' => now()->format('d/m/Y'),
                    'CX_TIME' => now()->format('H:i'),
                ]);

            // Revert DO status if linked
            if ($invoice->IV_SECOND_REF) {
                DB::table('DO')
                    ->where('DO_Num', $invoice->IV_SECOND_REF)
                    ->update([
                        'Status' => null,
                        'Invoice_Num' => null,
                    ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Invoice cancelled successfully',
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'error' => 'Failed to cancel invoice: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get invoice log/history
     */
    public function getInvoiceLog(Request $request)
    {
        $year = $request->input('year', now()->format('Y'));
        $month = $request->input('month', now()->format('m'));
        $customerCode = $request->input('customer_code');

        $query = DB::table('INV')
            ->select([
                'IV_NUM as invoice_number',
                'IV_DMY as invoice_date',
                'AC_NUM as customer_code',
                'AC_NAME as customer_name',
                'IV_TRAN_AMT as amount',
                'IV_TAX_CODE as tax_code',
                'IV_TAX_PERCENT as tax_percent',
                'IV_STS as status',
                'NW_UID as created_by',
                'NW_DATE as created_date',
            ])
            ->where('YYYY', $year)
            ->where('MM', $month);

        if ($customerCode) {
            $query->where('AC_NUM', $customerCode);
        }

        $invoices = $query->orderBy('IV_NUM', 'desc')->get();

        return response()->json($invoices);
    }

    /**
     * Calculate total amount from selected DOs
     */
    public function calculateTotal(Request $request)
    {
        $doNumbers = $request->input('do_numbers', []);

        if (empty($doNumbers)) {
            return response()->json([
                'total_amount' => 0,
                'count' => 0
            ]);
        }

        $total = DB::table('DO')
            ->whereIn('DO_Num', $doNumbers)
            ->sum('DO_Tran_Amt');

        return response()->json([
            'total_amount' => floatval($total),
            'count' => count($doNumbers)
        ]);
    }

    /**
     * Get DO items details (for Sales Order Items Screen) - CPS Compatible
     */
    public function getDoItems(Request $request)
    {
        try {
            $doNumber = $request->input('do_number');

            if (!$doNumber) {
                return response()->json(['error' => 'DO number is required'], 400);
            }

            Log::info("=== Fetching DO Items for: {$doNumber} ===");

            // Get all DO records for this DO number (can be multiple lines)
            $doRecords = DB::table('DO')
                ->where('DO_Num', $doNumber)
                ->select([
                    'DO_Num',
                    'DO_DMY',
                    'AC_Num',
                    'AC_Name',
                    'SO_Num',
                    'MCS_Num',
                    'Model',
                    'Product',
                    'PD',
                    'No',
                    'Unit',
                    'PCS_PER_SET',
                    'DO_Qty',
                    'SO_Unit_Price',
                    'DO_Tran_Amt',
                    'DO_Base_Amt',
                    'Curr',
                    'COMP',
                    'Total_DO_Net_KG',
                    'Total_DO_Gross_KG',
                    'MC_Net_Kg_Per_Pcs',
                    'MC_Gross_Kg_Per_Pcs',
                    'DO_Remark1',
                    'DO_Remark2',
                ])
                ->orderBy('No')
                ->get();

            if ($doRecords->isEmpty()) {
                Log::warning("No DO records found for: {$doNumber}");
                return response()->json(['error' => 'DO not found'], 404);
            }

            // Get the first record for header info
            $firstRecord = $doRecords->first();

            // Calculate totals
            $totalAmount = $doRecords->sum('DO_Tran_Amt');
            $totalQty = $doRecords->sum('DO_Qty');

            // Build S/O List (unique SO numbers)
            $soList = $doRecords->groupBy('SO_Num')->map(function ($items, $soNum) {
                $firstItem = $items->first();
                return [
                    'so_number' => $soNum ?: '-',
                    'mc_seq' => $firstItem->MCS_Num ?: '-',
                    'do_ref' => $firstItem->DO_Num,
                    'amount' => $items->sum('DO_Tran_Amt'),
                ];
            })->values();

            // Build item details (Main + Finishing items)
            $itemDetails = [];
            
            // Main item
            $mainItem = $doRecords->first();
            
            // Calculate total Net KG from all DO records (sum if multiple lines)
            $totalNetKg = floatval($doRecords->sum('Total_DO_Net_KG'));
            $doQty = floatval($mainItem->DO_Qty ?: 0);
            $kgPerUnit = 0;
            
            Log::info("=== KG Calculation Start ===", [
                'DO_Num' => $doNumber,
                'Total_DO_Net_KG_sum' => $totalNetKg,
                'DO_Qty' => $doQty,
            ]);
            
            // Priority 1: Use Total_DO_Net_KG if available
            if ($totalNetKg > 0 && $doQty > 0) {
                $kgPerUnit = $totalNetKg / $doQty;
                Log::info("✅ Method 1: Calculated from Total_DO_Net_KG", [
                    'totalNetKg' => $totalNetKg,
                    'doQty' => $doQty,
                    'kg_per_unit' => $kgPerUnit
                ]);
            }
            // Priority 2: Try MC_Net_Kg_Per_Pcs if available
            else if ($mainItem->MC_Net_Kg_Per_Pcs && floatval($mainItem->MC_Net_Kg_Per_Pcs) > 0) {
                $kgPerUnit = floatval($mainItem->MC_Net_Kg_Per_Pcs);
                $totalNetKg = $kgPerUnit * $doQty;
                Log::info("✅ Method 2: Using MC_Net_Kg_Per_Pcs", [
                    'MC_Net_Kg_Per_Pcs' => $kgPerUnit,
                    'doQty' => $doQty,
                    'calculated_total' => $totalNetKg
                ]);
            }
            // Priority 3: Default estimation (very rough, for testing)
            else {
                // Last resort: use a default estimation based on typical box weight
                // This is just to prevent 0.00 display
                $kgPerUnit = 0.05; // 50g per unit as default
                $totalNetKg = $kgPerUnit * $doQty;
                Log::warning("⚠️ Method 3: Using default estimation", [
                    'default_kg_per_unit' => $kgPerUnit,
                    'doQty' => $doQty,
                    'estimated_total' => $totalNetKg,
                    'note' => 'No KG data available, using rough estimate'
                ]);
            }
            
            $itemDetails[] = [
                'item' => 'Main',
                'p_design' => $mainItem->PD ?: 'B1',
                'pcs' => $mainItem->PCS_PER_SET ?: 1,
                'unit' => $mainItem->Unit ?: 'Pcs',
                'u_price' => floatval($mainItem->SO_Unit_Price ?: 0),
                'deliver' => $doQty,
                'reject' => 0,
                'unbill' => $doQty,
                'to_bill' => 0, // Default 0 - User must input manually (CPS-compatible)
                'to_bill_kg' => 0, // Will calculate when user inputs to_bill
                'kg_per_unit' => $kgPerUnit,
            ];
            
            Log::info("=== Final KG Data ===", [
                'kg_per_unit' => $kgPerUnit,
                'total_kg_available' => $totalNetKg,
                'ready_for_calculation' => $kgPerUnit > 0
            ]);

            // Finishing items F#1 - F#9 (placeholders for now)
            for ($i = 1; $i <= 9; $i++) {
                $itemDetails[] = [
                    'item' => "F# {$i}",
                    'p_design' => '-',
                    'pcs' => null,
                    'unit' => null,
                    'u_price' => null,
                    'deliver' => null,
                    'reject' => null,
                    'unbill' => null,
                    'to_bill' => null,
                    'to_bill_kg' => null,
                ];
            }

            $response = [
                'do_number' => $doNumber,
                'do_date' => $firstRecord->DO_DMY,
                'customer_code' => $firstRecord->AC_Num,
                'customer_name' => $firstRecord->AC_Name,
                'so_number' => $firstRecord->SO_Num ?: '-',
                'mc_seq' => $firstRecord->MCS_Num ?: '-',
                'model' => $firstRecord->Model ?: 'N/A',
                'currency' => $this->extractCurrencyCode($firstRecord->Curr),
                'control_set_order' => 0,
                's_o_count' => $soList->count(),
                'total_amount' => floatval($totalAmount),
                'total_qty' => floatval($totalQty),
                'so_list' => $soList,
                'item_details' => $itemDetails,
                'remarks' => [
                    'remark1' => $firstRecord->DO_Remark1,
                    'remark2' => $firstRecord->DO_Remark2,
                ],
            ];

            Log::info("✅ DO Items response prepared", [
                'do_number' => $doNumber,
                'so_count' => $soList->count(),
                'total_amount' => $totalAmount,
                'item_count' => count($itemDetails)
            ]);

            return response()->json($response);

        } catch (\Exception $e) {
            Log::error("Error fetching DO items: " . $e->getMessage(), [
                'do_number' => $request->input('do_number'),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'error' => 'Failed to fetch DO items',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get Delivery Orders from DO table for Delivery Order Table modal
     * Filters by customer code and period
     */
    public function getDeliveryOrders(Request $request)
    {
        try {
            $customerCode = $request->input('customer_code');
            $periodMonth = $request->input('period_month');
            $periodYear = $request->input('period_year');
            $doNumber = $request->input('do_number'); // For specific DO search

            // Build query
            $query = DB::table('DO');

            // Filter by specific DO number if provided (highest priority)
            if ($doNumber) {
                $query->where(function($q) use ($doNumber) {
                    $q->where('DO_Num', $doNumber)
                      ->orWhere('DO_Num', 'LIKE', "%{$doNumber}%");
                });
            }

            // Filter by customer if provided
            if ($customerCode) {
                $query->where('AC_Num', $customerCode);
            }

            // Filter by period if provided
            if ($periodMonth && $periodYear) {
                $query->where('DOMM', str_pad($periodMonth, 2, '0', STR_PAD_LEFT))
                      ->where('DOYYYY', $periodYear);
            }

            // Get delivery orders with aggregated item count and additional fields
            $deliveryOrders = $query
                ->select([
                    'DO_Num',
                    'DO_DMY',
                    'AC_Num',
                    'AC_Name',
                    'DO_VHC_Num',
                    'COMP',
                    'Status',
                    'SO_Num',
                    'SO_Type',
                    'PO_Num',
                    'DO_Remark1',
                    'DO_Remark2',
                    'SLM',  // Salesperson from DO table
                    'Area1', // Area from DO table
                    'IND', // Industry
                    'Group_', // Group
                    DB::raw('COUNT(*) as item_count'),
                    DB::raw('MAX(DOYYYY) as year'),
                    DB::raw('MAX(DOMM) as month')
                ])
                ->groupBy([
                    'DO_Num',
                    'DO_DMY',
                    'AC_Num',
                    'AC_Name',
                    'DO_VHC_Num',
                    'COMP',
                    'Status',
                    'SO_Num',
                    'SO_Type',
                    'PO_Num',
                    'DO_Remark1',
                    'DO_Remark2',
                    'SLM',
                    'Area1',
                    'IND',
                    'Group_'
                ])
                ->orderBy('DO_DMY', 'desc')
                ->orderBy('DO_Num', 'desc')
                ->get();

            // Transform data to match frontend structure and fetch salesperson
            $formattedOrders = $deliveryOrders->map(function ($order) use ($customerCode) {
                // Get salesperson - prioritize DO table SLM, then fallback to CUSTOMER table
                $salesperson = '';
                $salespersonCode = '';
                
                try {
                    Log::info("=== Processing DO {$order->DO_Num} for customer: {$order->AC_Num} ===");
                    
                    // Priority 1: Get salesperson from DO table (most accurate - specific to this DO)
                    if (!empty($order->SLM)) {
                        $salespersonCode = trim($order->SLM);
                        Log::info("✅ Salesperson from DO.SLM: {$salespersonCode}");
                    } else {
                        // Priority 2: Fallback to CUSTOMER table
                        Log::info("⚠️ No SLM in DO, trying CUSTOMER table...");
                        
                        try {
                            $customerData = DB::table('CUSTOMER')
                                ->where('CODE', $order->AC_Num)
                                ->select('SLM')
                                ->first();
                            
                            if ($customerData && !empty($customerData->SLM)) {
                                $salespersonCode = trim($customerData->SLM);
                                Log::info("✅ Salesperson from CUSTOMER.SLM: {$salespersonCode}");
                            } else {
                                Log::warning("⚠️ No SLM in CUSTOMER table for {$order->AC_Num}");
                            }
                        } catch (\Exception $e) {
                            Log::warning("⚠️ Could not query CUSTOMER table: " . $e->getMessage());
                        }
                    }
                    
                    // If we have salesperson code, try to get the name from various tables
                    if (!empty($salespersonCode)) {
                        // Try salesperson_teams table first
                        try {
                            $salespersonTeam = DB::table('salesperson_teams')
                                ->where('s_person_code', $salespersonCode)
                                ->select('s_person_code', 'salesperson_name')
                                ->first();
                            
                            if ($salespersonTeam && !empty($salespersonTeam->salesperson_name)) {
                                $salesperson = $salespersonCode;
                                Log::info("✅ Found in salesperson_teams: {$salesperson}");
                            } else {
                                // Try salesperson table as fallback
                                $salespersonData = DB::table('salesperson')
                                    ->where('Code', $salespersonCode)
                                    ->select('Code', 'Name')
                                    ->first();
                                
                                if ($salespersonData && !empty($salespersonData->Name)) {
                                    $salesperson = $salespersonCode;
                                    Log::info("✅ Found in salesperson: {$salesperson}");
                                } else {
                                    // Just use code if name not found
                                    $salesperson = $salespersonCode;
                                    Log::info("✅ Using salesperson code only: {$salesperson}");
                                }
                            }
                        } catch (\Exception $e) {
                            // If queries fail, just use the code
                            $salesperson = $salespersonCode;
                            Log::warning("⚠️ Could not query salesperson tables, using code: {$salesperson}");
                        }
                    } else {
                        Log::warning("⚠️ No salesperson code found for {$order->AC_Num}");
                    }
                } catch (\Exception $e) {
                    Log::error("❌ Failed to fetch salesperson: " . $e->getMessage(), [
                        'do_number' => $order->DO_Num,
                        'customer' => $order->AC_Num,
                        'error' => $e->getMessage()
                    ]);
                }
                
                // Determine order mode based on SO_Type
                $orderMode = 'customer'; // default
                if (!empty($order->SO_Type)) {
                    $orderMode = strtolower($order->SO_Type) === 'invoice' ? 'invoice' : 'customer';
                }
                
                $result = [
                    'do_number' => $order->DO_Num,
                    'do_date' => $order->DO_DMY,
                    'customer_code' => $order->AC_Num,
                    'customer_name' => $order->AC_Name,
                    'vehicle_no' => $order->DO_VHC_Num,
                    'item_count' => intval($order->item_count),
                    'pc' => $order->COMP ?? 1,
                    'mode' => 'Multiple',
                    'status' => $order->Status ?? 'Draft',
                    'so_number' => $order->SO_Num,
                    'so_type' => $order->SO_Type,
                    'po_number' => $order->PO_Num,
                    'remark1' => $order->DO_Remark1,
                    'remark2' => $order->DO_Remark2,
                    'period_month' => $order->month,
                    'period_year' => $order->year,
                    'salesperson' => $salesperson,
                    'salesperson_code' => $salespersonCode,
                    'area' => $order->Area1,
                    'industry' => $order->IND,
                    'group' => $order->Group_,
                    'order_mode' => $orderMode,
                ];
                
                Log::info("📦 Final DO response for {$order->DO_Num}:", [
                    'salesperson' => $salesperson,
                    'order_mode' => $orderMode,
                    'remark1' => $order->DO_Remark1,
                    'remark2' => $order->DO_Remark2,
                    'is_empty' => empty($salesperson)
                ]);
                
                return $result;
            });

            Log::info("📤 Returning " . count($formattedOrders) . " delivery orders to frontend");
            
            return response()->json($formattedOrders);

        } catch (\Exception $e) {
            Log::error('Error fetching delivery orders: ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to fetch delivery orders',
                'message' => $e->getMessage()
            ], 500);
        }
    }

}
