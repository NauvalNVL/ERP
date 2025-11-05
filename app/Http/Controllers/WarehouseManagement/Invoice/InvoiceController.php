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
     * Safely get property from object with default value
     * Handles undefined properties in stdClass objects
     */
    private function getProperty($object, $property, $default = null)
    {
        if (is_object($object) && property_exists($object, $property)) {
            return $object->$property;
        }
        return $default;
    }

    /**
     * Safely convert value to decimal/float or null
     * Handles empty strings, null, and numeric strings
     */
    private function toDecimalOrNull($value, $default = null)
    {
        if ($value === null || $value === '' || $value === '.00' || $value === '0.00' && $default !== null) {
            return $default;
        }
        
        // Remove any non-numeric characters except decimal point and minus
        $cleaned = preg_replace('/[^0-9.-]/', '', (string)$value);
        
        if ($cleaned === '' || $cleaned === '.' || $cleaned === '-') {
            return $default;
        }
        
        return floatval($cleaned);
    }

    /**
     * Safely convert value to integer or null
     */
    private function toIntegerOrNull($value, $default = null)
    {
        if ($value === null || $value === '') {
            return $default;
        }
        
        return intval($value);
    }

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
                    'DO_Qty as do_qty',
                    'Status as status',
                    'DO_VHC_Num as vehicle',
                    'No as item',
                ])
                ->where('DOYYYY', $yyyy)
                ->where('DOMM', $mm)
                ->where(function ($q) {
                    $q->whereNull('Status')
                      ->orWhere('Status', '!=', 'Invoiced')
                      ->orWhere('Status', '!=', 'Completed');
                });

            // Filter by customer if provided
            if ($customerCode) {
                $query->where('AC_Num', $customerCode);
            }

            $dos = $query->orderBy('DO_Num')->get();

            // Calculate invoiced quantity and remaining quantity for each DO
            $dos = $dos->map(function ($do) {
                // Get total invoiced quantity from INV table
                $invoicedQty = DB::table('INV')
                    ->where('SO_NUM', $do->so_number)
                    ->where('IV_STS', '!=', 'Cancelled')
                    ->sum('IV_QTY');

                $do->invoiced_qty = $invoicedQty ?? 0;
                $do->remaining_qty = ($do->do_qty ?? 0) - $do->invoiced_qty;
                
                // Update status based on invoiced quantity
                if ($do->remaining_qty <= 0) {
                    $do->invoice_status = 'Completed';
                } elseif ($do->invoiced_qty > 0) {
                    $do->invoice_status = 'Partial';
                } else {
                    $do->invoice_status = 'Open';
                }
                
                // Extract currency codes only (remove full names)
                $do->currency = $this->extractCurrencyCode($do->currency);
                
                return $do;
            });

            // Filter out completed DOs (remaining_qty <= 0)
            $dos = $dos->filter(function ($do) {
                return $do->remaining_qty > 0;
            })->values();

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
     * Compatible with CPS Enterprise 2020 workflow
     */
    public function prepare(Request $request)
    {
        try {
            $payload = $request->validate([
                'do_numbers' => 'required|array|min:1',
                'do_numbers.*' => 'required|string|max:50',
                'customer_code' => 'nullable|string|max:50',
                'tax_index_no' => 'nullable|string|max:50',
                'tax_code' => 'nullable|string|max:50', // ✅ ADDED: Accept tax_code from frontend
                'tax_percent' => 'nullable|numeric|min:0|max:100', // ✅ ADDED: Accept tax_percent from frontend
                'invoice_date' => 'nullable|date',
                'second_ref' => 'nullable|string|max:50',
                'remark' => 'nullable|string|max:250',
                'invoice_number_mode' => 'nullable|string|in:auto,manual',
                'manual_invoice_number' => 'nullable|string|max:50',
                'year' => 'nullable|string|max:4',
                'month' => 'nullable|string|max:2',
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
            
            // ✅ ADDED: Get tax_code and tax_percent from request (already confirmed in Final Screen)
            $taxCodeFromFrontend = $payload['tax_code'] ?? null;
            $taxPercentFromFrontend = $payload['tax_percent'] ?? null;

            Log::info('Invoice Preparation Started', [
                'do_count' => count($payload['do_numbers']),
                'mode' => $invoiceNumberMode,
                'manual_number' => $manualInvoiceNumber,
                'period' => "{$yyyy}/{$mm}",
                'tax_from_frontend' => [
                    'tax_code' => $taxCodeFromFrontend,
                    'tax_percent' => $taxPercentFromFrontend
                ]
            ]);

            // ✅ PRIORITY 1: Use tax data from frontend (already confirmed by user in Final Screen)
            $taxCode = $taxCodeFromFrontend;
            $taxPercent = $taxPercentFromFrontend;
            
            if ($taxCode && $taxPercent) {
                Log::info('✅ Using tax data from Final Screen (user confirmed)', [
                    'tax_code' => $taxCode,
                    'tax_percent' => $taxPercent,
                    'source' => 'frontend_final_screen'
                ]);
            }
            // ✅ PRIORITY 2: Fallback to database lookup if not provided from frontend
            elseif ($taxIndexNo) {
                Log::info('⚠️ Tax not provided from frontend, looking up in database', [
                    'tax_index_no' => $taxIndexNo
                ]);

                try {
                    // Try taxrate table first (standard table)
                    $tax = DB::table('taxrate')
                        ->where('TAXCODE', $taxIndexNo)
                        ->first();

                    if ($tax) {
                        $taxCode = $tax->TAXCODE;
                        $taxPercent = $tax->RATEPPN;
                        Log::info('Tax found in taxrate table', [
                            'code' => $taxCode,
                            'rate' => $taxPercent,
                            'source' => 'taxrate_table'
                        ]);
                    }
                } catch (\Exception $e) {
                    Log::warning('taxrate table not accessible: ' . $e->getMessage());
                }

                // If not found in taxrate, try Sales_Tax table (fallback)
                if (!$taxCode) {
                    try {
                        $tax = DB::table('Sales_Tax')
                            ->where('tax_code', $taxIndexNo)
                            ->first();

                        if ($tax) {
                            $taxCode = $tax->tax_code;
                            $taxPercent = $tax->tax_rate;
                            Log::info('Tax found in Sales_Tax table', [
                                'code' => $taxCode,
                                'rate' => $taxPercent,
                                'source' => 'sales_tax_table'
                            ]);
                        }
                    } catch (\Exception $e) {
                        Log::warning('Sales_Tax table not accessible: ' . $e->getMessage());
                    }
                }

                // Final warning if tax not found in database
                if (!$taxCode) {
                    Log::warning('❌ Tax code not found in any table', [
                        'tax_index_no' => $taxIndexNo,
                        'searched_tables' => ['taxrate', 'Sales_Tax']
                    ]);
                }
            } else {
                Log::info('ℹ️ No tax information provided (tax-free invoice)');
            }

            // Get existing invoice count for auto-numbering
            $existingCount = (int) DB::table('INV')
                ->where('YYYY', $yyyy)
                ->where('MM', $mm)
                ->count();

            $seq = $existingCount + 1;

            // Invoice number generator function (CPS format)
            $generateNumber = function (int $seq) use ($yyyy, $mm) {
                return sprintf('IV-%s%s-%04d', $yyyy, $mm, $seq);
            };

            $created = [];

            DB::beginTransaction();

            foreach ($payload['do_numbers'] as $doNumber) {
                $do = DB::table('DO')->where('DO_Num', $doNumber)->first();

                if (!$do) {
                    throw new \RuntimeException("Delivery Order not found: {$doNumber}");
                }

                // CPS-Compatible: Check if DO is already fully invoiced by calculating from INV table
                $invoicedQty = DB::table('INV')
                    ->where('SO_NUM', $this->getProperty($do, 'SO_Num'))
                    ->where('IV_STS', '!=', 'Cancelled')
                    ->sum('IV_QTY');
                
                $doQty = $this->toDecimalOrNull($this->getProperty($do, 'DO_Qty'), 0);
                $remainingQty = $doQty - ($invoicedQty ?? 0);
                
                Log::info('DO Invoice Status Check', [
                    'do_number' => $doNumber,
                    'do_qty' => $doQty,
                    'invoiced_qty' => $invoicedQty,
                    'remaining_qty' => $remainingQty
                ]);
                
                // Prevent invoicing if already fully invoiced
                if ($remainingQty <= 0) {
                    throw new \RuntimeException(
                        "Delivery Order {$doNumber} is already fully invoiced. "
                        . "DO Qty: {$doQty}, Already Invoiced: {$invoicedQty}"
                    );
                }
                
                // Check if DO status is Cancelled
                if ($do->Status === 'Cancelled') {
                    throw new \RuntimeException("Delivery Order {$doNumber} is cancelled and cannot be invoiced");
                }

                // Fetch customer payment term from Customer Account table
                $customerCode = $this->getProperty($do, 'AC_Num');
                $paymentTerm = 30; // Default: 30 days (initialize first!)
                
                if ($customerCode) {
                    // Try CUSTOMER table first (has TERM column as decimal - days)
                    try {
                        $customer = DB::table('CUSTOMER')
                            ->where('CODE', $customerCode)
                            ->first();
                        
                        if ($customer && isset($customer->TERM) && $customer->TERM > 0) {
                            // TERM is decimal (number of days), cast to INT for INV table
                            $paymentTerm = (int) round($customer->TERM); // Explicit INT cast
                            
                            Log::info('✅ Payment term found in CUSTOMER table', [
                                'customer' => $customerCode,
                                'term_days' => $paymentTerm,
                                'original_value' => $customer->TERM
                            ]);
                        } else {
                            Log::warning('⚠️ Customer found but TERM is NULL or 0', [
                                'customer' => $customerCode,
                                'term_value' => $customer->TERM ?? 'NULL'
                            ]);
                        }
                    } catch (\Exception $e) {
                        Log::error('❌ CUSTOMER table access failed', [
                            'error' => $e->getMessage(),
                            'customer' => $customerCode
                        ]);
                    }
                    
                    // Log final payment term value
                    Log::info('💰 Final payment term for invoice', [
                        'customer' => $customerCode,
                        'payment_term' => $paymentTerm,
                        'type' => gettype($paymentTerm)
                    ]);
                }

                // Generate or use manual invoice number
                if ($invoiceNumberMode === 'manual' && $manualInvoiceNumber) {
                    $ivNum = $manualInvoiceNumber;

                    // Validate manual number uniqueness
                    $exists = DB::table('INV')->where('IV_NUM', $ivNum)->exists();
                    if ($exists) {
                        throw new \RuntimeException("Invoice number '{$ivNum}' already exists. Please choose a different number.");
                    }

                    Log::info('Using manual invoice number', ['number' => $ivNum]);
                } else {
                    // Auto-generate invoice number (CPS format)
                    $ivNum = $generateNumber($seq++);

                    // Double-check uniqueness (safety)
                    while (DB::table('INV')->where('IV_NUM', $ivNum)->exists()) {
                        $ivNum = $generateNumber($seq++);
                    }

                    Log::info('Generated auto invoice number', ['number' => $ivNum]);
                }

                // Calculate amounts (with proper decimal handling)
                $tranAmount = $this->toDecimalOrNull($this->getProperty($do, 'DO_Tran_Amt'), 0);
                $baseAmount = $this->toDecimalOrNull($this->getProperty($do, 'DO_Base_Amt'), 0);
                
                // If DO_Tran_Amt is 0, calculate from DO_Qty * SO_Unit_Price
                if ($tranAmount == 0) {
                    $doQty = $this->toDecimalOrNull($this->getProperty($do, 'DO_Qty'), 0);
                    $unitPrice = $this->toDecimalOrNull($this->getProperty($do, 'SO_Unit_Price'), 0);
                    $exRate = $this->toDecimalOrNull($this->getProperty($do, 'Ex_Rate'), 1);
                    
                    if ($doQty > 0 && $unitPrice > 0) {
                        $tranAmount = round($doQty * $unitPrice, 2);
                        $baseAmount = round($tranAmount * $exRate, 2);
                        
                        Log::info('Calculated amounts from DO_Qty × Unit_Price', [
                            'do_qty' => $doQty,
                            'unit_price' => $unitPrice,
                            'ex_rate' => $exRate,
                            'calculated_tran_amt' => $tranAmount,
                            'calculated_base_amt' => $baseAmount
                        ]);
                    }
                }

                // Calculate tax amount if applicable
                $taxAmount = 0;
                if ($taxPercent && $tranAmount > 0) {
                    $taxAmount = round($tranAmount * ($taxPercent / 100), 2);
                }

                $netAmount = $tranAmount + $taxAmount;

                Log::info('Inserting invoice with amounts', [
                    'tranAmount' => $tranAmount,
                    'baseAmount' => $baseAmount,
                    'taxAmount' => $taxAmount,
                    'netAmount' => $netAmount,
                    'taxCode' => $taxCode,
                    'taxPercent' => $taxPercent
                ]);

                // Log critical values before INSERT
                Log::info('🔍 Critical values before INSERT', [
                    'invoice_num' => $ivNum,
                    'payment_term' => [
                        'value' => $paymentTerm,
                        'type' => gettype($paymentTerm),
                        'is_null' => $paymentTerm === null ? 'YES' : 'NO'
                    ],
                    'tax' => [
                        'IV_TAX_CODE' => $taxCode,
                        'IV_TAX_PERCENT' => $taxPercent,
                        'tax_code_is_null' => $taxCode === null ? 'YES' : 'NO',
                        'tax_percent_is_null' => $taxPercent === null ? 'YES' : 'NO'
                    ],
                    'amounts' => [
                        'IV_TRAN_AMT' => $tranAmount,
                        'IV_BASE_AMT' => $baseAmount,
                        'tax_amount' => $taxAmount,
                        'net_amount' => $netAmount
                    ]
                ]);

                // Get SO_DMY from SO table (DO table doesn't have SO_Date column)
                $soDmy = null;
                $soNum = $this->getProperty($do, 'SO_Num');
                if ($soNum) {
                    try {
                        $soData = DB::table('so')->where('SO_Num', $soNum)->first();
                        $soDmy = $soData ? ($soData->SO_DMY ?? null) : null;
                        
                        Log::info('✅ Retrieved SO_DMY from SO table', [
                            'so_num' => $soNum,
                            'so_dmy' => $soDmy
                        ]);
                    } catch (\Exception $e) {
                        Log::warning('❌ Cannot fetch SO_DMY from SO table', [
                            'so_num' => $soNum,
                            'error' => $e->getMessage()
                        ]);
                    }
                }
                
                // Calculate TOTAL_IV_NET_KG (KG calculation)
                $totalNetKg = $this->toDecimalOrNull($this->getProperty($do, 'Total_DO_Net_KG'));
                if (!$totalNetKg || $totalNetKg == 0) {
                    // Try to calculate from MC_Net_Kg_Per_Pcs × DO_Qty
                    $kgPerPcs = $this->toDecimalOrNull($this->getProperty($do, 'MC_Net_Kg_Per_Pcs'));
                    $doQty = $this->toDecimalOrNull($this->getProperty($do, 'DO_Qty'), 0);
                    if ($kgPerPcs && $doQty > 0) {
                        $totalNetKg = round($kgPerPcs * $doQty, 4);
                    }
                }

                // Insert invoice record into INV table
                DB::table('INV')->insert([
                    // Period and identification
                    'YYYY' => $yyyy,
                    'MM' => $mm,
                    'IV_NUM' => $ivNum,
                    'IV_STS' => 'Prepared',
                    'IV_DMY' => $invoiceDate,

                    // Payment terms and references
                    'AR_TERM' => $paymentTerm, // From customer account table (COD, 30D, 60D, etc.)
                    'IV_SECOND_REF' => $secondRef ?? $this->getProperty($do, 'DO_Num'),

                    // Customer information
                    'AC_NUM' => $this->getProperty($do, 'AC_Num'),
                    'AC_NAME' => $this->getProperty($do, 'AC_Name'),

                    // Item details
                    'ITEM' => $this->getProperty($do, 'No'),
                    'MCS_NUM' => $this->getProperty($do, 'MCS_Num'),
                    'MODEL' => $this->getProperty($do, 'Model'),
                    'PRODUCT' => $this->getProperty($do, 'Product'),
                    'COMP' => $this->getProperty($do, 'COMP'),
                    'P_DESIGN' => $this->getProperty($do, 'PD'),
                    'PCS_PER_SET' => $this->toDecimalOrNull($this->getProperty($do, 'PCS_PER_SET')),
                    'UNIT' => $this->getProperty($do, 'Unit'),
                    'PART' => $this->getProperty($do, 'Part_Number'),

                    // Dimensions (all decimal fields)
                    'INT_L' => $this->toDecimalOrNull($this->getProperty($do, 'INT_L')),
                    'INT_W' => $this->toDecimalOrNull($this->getProperty($do, 'INT_W')),
                    'INT_H' => $this->toDecimalOrNull($this->getProperty($do, 'INT_H')),
                    'EXT_L' => $this->toDecimalOrNull($this->getProperty($do, 'EXT_L')),
                    'EXT_W' => $this->toDecimalOrNull($this->getProperty($do, 'EXT_W')),
                    'EXT_H' => $this->toDecimalOrNull($this->getProperty($do, 'EXT_H')),
                    'FLUTE' => $this->getProperty($do, 'Flute'),

                    // Sales and organizational info
                    'SLM' => $this->getProperty($do, 'SLM'),
                    'IND' => $this->getProperty($do, 'IND'),
                    'AREA' => $this->getProperty($do, 'Area1'),
                    'GROUP_' => $this->getProperty($do, 'Group_'),

                    // Order references (FIXED: SO_DMY and LOT_NUM)
                    'SO_NUM' => $this->getProperty($do, 'SO_Num'),
                    'SO_TYPE' => $this->getProperty($do, 'SO_Type'),
                    'SO_DMY' => $soDmy, // ✅ FIXED: Now properly populated
                    'PO_NUM' => $this->getProperty($do, 'PO_Num'),
                    'PO_DMY' => $this->getProperty($do, 'PO_Date'),
                    'LOT_NUM' => $this->getProperty($do, 'LOT_Num'), // ✅ FIXED: Now properly populated

                    // Quantities (string fields, not numeric)
                    'SO_PQ1' => $this->getProperty($do, 'PQ1'),
                    'SO_PQ2' => $this->getProperty($do, 'PQ2'),
                    'SO_PQ3' => $this->getProperty($do, 'PQ3'),
                    'SO_PQ4' => $this->getProperty($do, 'PQ4'),
                    'SO_PQ5' => $this->getProperty($do, 'PQ5'),
                    'IV_QTY' => $this->toDecimalOrNull($this->getProperty($do, 'DO_Qty')),
                    'IV_UNIT_PRICE' => $this->toDecimalOrNull($this->getProperty($do, 'SO_Unit_Price')),

                    // Currency and amounts (FIXED: IV_TRAN_AMT, IV_BASE_AMT)
                    'CURR' => $this->getProperty($do, 'Curr', 'IDR'),
                    'EX_RATE' => $this->toDecimalOrNull($this->getProperty($do, 'Ex_Rate'), 1.0),
                    'IV_TRAN_AMT' => $tranAmount, // ✅ FIXED: Calculated above
                    'IV_BASE_AMT' => $baseAmount, // ✅ FIXED: Calculated above

                    // Measurements (M2 and KG) - all decimal fields (FIXED: TOTAL_IV_NET_KG)
                    'MC_GROSS_M2_PER__PCS' => $this->toDecimalOrNull($this->getProperty($do, 'MC_Gross_M2_Per_Pcs')),
                    'MC_NET_M2_PER_PCS' => $this->toDecimalOrNull($this->getProperty($do, 'MC_Net_M2_Per_Pcs')),
                    'TOTAL_IV_GROSS_M2' => $this->toDecimalOrNull($this->getProperty($do, 'Total_DO_Gross_M2')),
                    'TOTAL_IV_NET_M2' => $this->toDecimalOrNull($this->getProperty($do, 'Total_DO_Net_M2')),
                    'MC_GROSS_KG_PER_PCS' => $this->toDecimalOrNull($this->getProperty($do, 'MC_Gross_Kg_Per_Pcs')),
                    'MC_NET_KG_PER_PCS' => $this->toDecimalOrNull($this->getProperty($do, 'MC_Net_Kg_Per_Pcs')),
                    'TOTAL_IV_GROSS_KG' => $this->toDecimalOrNull($this->getProperty($do, 'Total_DO_Gross_KG')),
                    'TOTAL_IV_NET_KG' => $totalNetKg, // ✅ FIXED: Calculated above

                    // Tax information (FIXED: IV_TAX_CODE, IV_TAX_PERCENT)
                    'IV_TAX_CODE' => $taxCode, // ✅ FIXED: From tax lookup
                    'IV_TAX_PERCENT' => $this->toDecimalOrNull($taxPercent), // ✅ FIXED: From tax lookup

                    // Remarks
                    'IV_REMARK' => $remark,

                    // Audit trail - New
                    'NW_UID' => Auth::check() ? Auth::user()->name : 'system',
                    'NW_DATE' => $now->format('d/m/Y'),
                    'NW_TIME' => $now->format('H:i'),

                    // Date surrogate keys (integers)
                    'IVDateSK' => $this->toIntegerOrNull($this->getProperty($do, 'DODateSK'), 0),
                    'SODateSK' => $this->toIntegerOrNull($this->getProperty($do, 'SODateSK'), 0),
                    'PODateSK' => $this->toIntegerOrNull($this->getProperty($do, 'PODateSK'), 0),
                ]);

                // CPS-Compatible: Update DO status based on remaining quantity
                try {
                    // Calculate new invoiced quantity after this invoice
                    $newInvoicedQty = DB::table('INV')
                        ->where('SO_NUM', $this->getProperty($do, 'SO_Num'))
                        ->where('IV_STS', '!=', 'Cancelled')
                        ->sum('IV_QTY');
                    
                    $doQty = $this->toDecimalOrNull($this->getProperty($do, 'DO_Qty'), 0);
                    $newRemainingQty = $doQty - ($newInvoicedQty ?? 0);
                    
                    // Determine new status based on CPS logic
                    $newStatus = 'Open';
                    if ($newRemainingQty <= 0) {
                        $newStatus = 'Invoiced'; // Fully invoiced (CPS uses 'Invoiced' for completed)
                    } elseif ($newInvoicedQty > 0) {
                        $newStatus = 'Partial'; // Partially invoiced
                    }
                    
                    DB::table('DO')
                        ->where('DO_Num', $doNumber)
                        ->update(['Status' => $newStatus]);
                        
                    Log::info('DO status updated successfully (CPS-compatible)', [
                        'do_number' => $doNumber,
                        'do_qty' => $doQty,
                        'invoiced_qty' => $newInvoicedQty,
                        'remaining_qty' => $newRemainingQty,
                        'new_status' => $newStatus,
                        'invoice_num' => $ivNum
                    ]);
                } catch (\Exception $e) {
                    // Log but don't fail - invoice already created
                    Log::warning('Failed to update DO status, but invoice created successfully', [
                        'do_number' => $doNumber,
                        'invoice_num' => $ivNum,
                        'error' => $e->getMessage()
                    ]);
                }

                $created[] = [
                    'invoice_number' => $ivNum,
                    'do_number' => $doNumber,
                    'amount' => $tranAmount,
                    'tax_code' => $taxCode,
                    'tax_percent' => $taxPercent,
                    'tax_amount' => $taxAmount,
                    'net_amount' => $netAmount,
                ];

                Log::info('Invoice created successfully', [
                    'invoice_number' => $ivNum,
                    'do_number' => $doNumber,
                    'amount' => $tranAmount,
                    'tax_amount' => $taxAmount
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Invoice(s) prepared successfully',
                'data' => $created,
                'summary' => [
                    'total_invoices' => count($created),
                    'mode' => $invoiceNumberMode,
                    'period' => "{$mm}/{$yyyy}",
                ]
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Invoice Preparation Failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Failed to prepare invoice(s): ' . $e->getMessage(),
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
     * Calculate total amount from selected DOs or SO number
     * Used by both Prepare Invoice and Amend Invoice
     */
    public function calculateTotal(Request $request)
    {
        $doNumbers = $request->input('do_numbers', []);
        $soNumber = $request->input('so_number');

        // If SO number provided, get DOs from SO
        if ($soNumber) {
            $total = DB::table('DO')
                ->where('SO_Num', $soNumber)
                ->sum('DO_Tran_Amt');

            return response()->json([
                'total_amount' => floatval($total),
                'count' => 1,
                'source' => 'so_number'
            ]);
        }

        // If DO numbers provided
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
            'count' => count($doNumbers),
            'source' => 'do_numbers'
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

    /**
     * Get list of invoices for Amend Invoice (CPS-compatible)
     */
    public function index(Request $request)
    {
        try {
            $query = DB::table('INV');
            
            // Filter by MM (month)
            if ($request->has('mm') && !empty($request->mm)) {
                $query->where('MM', str_pad($request->mm, 2, '0', STR_PAD_LEFT));
            }
            
            // Filter by YYYY (year)
            if ($request->has('yyyy') && !empty($request->yyyy)) {
                $query->where('YYYY', $request->yyyy);
            }
            
            // Filter by invoice number (full or partial)
            if ($request->has('seq') && !empty($request->seq)) {
                $query->where('IV_NUM', 'like', '%' . $request->seq . '%');
            }
            
            // Get column list to check which columns exist
            $columns = DB::getSchemaBuilder()->getColumnListing('INV');
            
            // Build select array with only existing columns
            $selectColumns = [
                'IV_NUM as invoice_no',
                'IV_DMY as invoice_date',
                'AC_NUM as customer_code',
                'IV_STS as status',
            ];
            
            // Add optional columns if they exist
            if (in_array('AC_NAME', $columns)) {
                $selectColumns[] = 'AC_NAME as customer_name';
            } else {
                $selectColumns[] = DB::raw("'' as customer_name");
            }
            
            if (in_array('IV_TAX_CODE', $columns)) {
                $selectColumns[] = 'IV_TAX_CODE as tax_code';
            } else {
                $selectColumns[] = DB::raw("'' as tax_code");
            }
            
            // Mode - default to Manual
            $selectColumns[] = DB::raw("'Manual' as mode");
            
            // PC Status - check if printed
            if (in_array('PT_UID', $columns)) {
                $selectColumns[] = DB::raw("CASE WHEN PT_UID IS NOT NULL AND PT_UID != '' THEN '1' ELSE '0' END as pc_status");
            } else {
                $selectColumns[] = DB::raw("'0' as pc_status");
            }
            
            // Post Status
            $selectColumns[] = DB::raw("CASE WHEN IV_STS = 'Posted' THEN 'Posted' ELSE 'UnPost' END as post_status");
            
            // Audit trail columns (optional)
            $auditColumns = [
                'ORDER_MODE' => 'order_mode',
                'NW_UID' => 'issued_by',
                'NW_DATE' => 'issued_date',
                'AM_UID' => 'amended_by',
                'AM_DATE' => 'amended_date',
                'PT_UID' => 'printed_by',
                'PT_DATE' => 'printed_date',
                'PO_UID' => 'posted_by',
                'PO_DATE' => 'posted_date',
            ];
            
            foreach ($auditColumns as $dbColumn => $alias) {
                if (in_array($dbColumn, $columns)) {
                    $selectColumns[] = "$dbColumn as $alias";
                } else {
                    $selectColumns[] = DB::raw("'' as $alias");
                }
            }
            
            // Get invoices with dynamic column selection
            $invoices = $query->select($selectColumns)
                ->orderBy('IV_NUM', 'desc')
                ->limit(100)
                ->get();
            
            return response()->json([
                'success' => true,
                'data' => $invoices
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error fetching invoices: ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to fetch invoices',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get single invoice details for editing (CPS-compatible)
     */
    public function show($invoiceNo)
    {
        try {
            $invoice = DB::table('INV')
                ->where('IV_NUM', $invoiceNo)
                ->first();
            
            if (!$invoice) {
                return response()->json([
                    'error' => 'Invoice not found'
                ], 404);
            }
            
            // Convert IV_DMY to YYYY-MM-DD for input[type=date]
            $invoiceDate = '';
            if (!empty($invoice->IV_DMY)) {
                // Try DD/MM/YYYY format first
                if (strpos($invoice->IV_DMY, '/') !== false) {
                    $parts = explode('/', $invoice->IV_DMY);
                    if (count($parts) === 3) {
                        $invoiceDate = sprintf('%s-%s-%s', $parts[2], $parts[1], $parts[0]);
                    }
                }
                // Try YYYY-MM-DD format (already correct)
                elseif (strpos($invoice->IV_DMY, '-') !== false && strlen($invoice->IV_DMY) === 10) {
                    $invoiceDate = $invoice->IV_DMY;
                }
                // Fallback: use as-is
                else {
                    $invoiceDate = $invoice->IV_DMY;
                }
            }
            
            return response()->json([
                'invoice_no' => $invoice->IV_NUM ?? '',
                'customer_code' => $invoice->AC_NUM ?? '',
                'customer_name' => $invoice->AC_NAME ?? '',
                'order_mode' => $invoice->ORDER_MODE ?? '',
                'salesperson' => $invoice->SLM ?? '',
                'currency' => $invoice->CURR ?? 'IDR',
                'exchange_rate' => (float)($invoice->EX_RATE ?? 0),
                'exchange_method' => $invoice->EXCHANGE_METHOD ?? '1',
                'tax_index_no' => $invoice->TAX_INDEX_NO ?? '',
                'tax_code' => $invoice->IV_TAX_CODE ?? '',
                'tax_percent' => (float)($invoice->IV_TAX_PERCENT ?? 0),
                'invoice_date' => $invoiceDate,
                'ref2' => $invoice->REF2 ?? '',
                'second_ref' => $invoice->IV_SECOND_REF ?? '',
                'remark' => $invoice->IV_REMARK ?? '',
                'status' => $invoice->IV_STS ?? 'Prepared',
                'total_amount' => (float)($invoice->IV_TRAN_AMT ?? 0),
                'tax_amount' => (float)($invoice->IV_TAX_AMT ?? 0),
                'net_amount' => (float)($invoice->IV_NET_AMT ?? 0),
                // Related documents (for calculating total if needed)
                'so_number' => $invoice->SO_NUM ?? '',
                'do_number' => $invoice->IV_SECOND_REF ?? '',
                // Audit trail
                'issued_by' => $invoice->NW_UID ?? '',
                'issued_date' => $invoice->NW_DATE ?? '',
                'amended_by' => $invoice->AM_UID ?? '',
                'amended_date' => $invoice->AM_DATE ?? '',
                'printed_by' => $invoice->PT_UID ?? '',
                'printed_date' => $invoice->PT_DATE ?? '',
                'posted_by' => $invoice->PO_UID ?? '',
                'posted_date' => $invoice->PO_DATE ?? ''
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error fetching invoice details: ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to fetch invoice details',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get single invoice details WITH items for Final Screen calculation
     */
    public function showWithItems($invoiceNo)
    {
        try {
            // Get invoice header
            $invoice = DB::table('INV')->where('IV_NUM', $invoiceNo)->first();
            
            if (!$invoice) {
                return response()->json([
                    'error' => 'Invoice not found'
                ], 404);
            }
            
            // Try to get invoice items from INVDET table (may not exist)
            $items = collect([]);
            $totalAmount = (float)($invoice->IV_TRAN_AMT ?? 0);
            
            try {
                // Check if INVDET table exists
                $items = DB::table('INVDET')
                    ->where('IV_NUM', $invoiceNo)
                    ->select([
                        'ITEM_CODE',
                        'ITEM_DESC',
                        'QTY',
                        'UNIT_PRICE',
                        'AMOUNT',
                        'LINE_NO'
                    ])
                    ->orderBy('LINE_NO')
                    ->get();
                
                // Calculate total from items if header total is 0
                if ($totalAmount == 0 && $items->count() > 0) {
                    $totalAmount = $items->sum(function($item) {
                        return (float)($item->AMOUNT ?? 0);
                    });
                }
            } catch (\Exception $e) {
                // INVDET table doesn't exist or error querying, use header total only
                Log::info('Cannot fetch items for invoice ' . $invoiceNo . ': ' . $e->getMessage());
            }
            
            // If total still 0, try to use net amount
            if ($totalAmount == 0) {
                $totalAmount = (float)($invoice->IV_NET_AMT ?? 0);
            }
            
            // Return invoice header + items (empty if table doesn't exist)
            return response()->json([
                'invoice_no' => $invoice->IV_NUM ?? '',
                'customer_code' => $invoice->AC_NUM ?? '',
                'customer_name' => $invoice->AC_NAME ?? '',
                'total_amount' => $totalAmount,
                'tax_amount' => (float)($invoice->IV_TAX_AMT ?? 0),
                'net_amount' => (float)($invoice->IV_NET_AMT ?? 0),
                'tax_code' => $invoice->IV_TAX_CODE ?? '',
                'items' => $items->map(function($item) {
                    return [
                        'item_code' => $item->ITEM_CODE,
                        'item_desc' => $item->ITEM_DESC,
                        'qty' => (float)($item->QTY ?? 0),
                        'unit_price' => (float)($item->UNIT_PRICE ?? 0),
                        'amount' => (float)($item->AMOUNT ?? 0),
                        'line_no' => $item->LINE_NO
                    ];
                })
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error fetching invoice with items: ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to fetch invoice with items',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get DO status with invoice tracking (CPS-compatible)
     * Returns DO quantity, invoiced quantity, remaining quantity, and status
     */
    public function getDoStatus(Request $request)
    {
        try {
            $doNumber = $request->input('do_number');
            
            if (!$doNumber) {
                return response()->json([
                    'error' => 'DO number is required'
                ], 400);
            }
            
            // Get DO data
            $do = DB::table('DO')
                ->where('DO_Num', $doNumber)
                ->first();
            
            if (!$do) {
                return response()->json([
                    'error' => 'Delivery Order not found'
                ], 404);
            }
            
            // Calculate invoiced quantity from INV table
            $invoicedQty = DB::table('INV')
                ->where('SO_NUM', $do->SO_Num)
                ->where('IV_STS', '!=', 'Cancelled')
                ->sum('IV_QTY');
            
            $doQty = $do->DO_Qty ?? 0;
            $remainingQty = $doQty - ($invoicedQty ?? 0);
            
            // Determine invoice status
            $invoiceStatus = 'Open';
            if ($remainingQty <= 0) {
                $invoiceStatus = 'Completed';
            } elseif ($invoicedQty > 0) {
                $invoiceStatus = 'Partial';
            }
            
            return response()->json([
                'do_number' => $do->DO_Num,
                'so_number' => $do->SO_Num,
                'do_qty' => $doQty,
                'invoiced_qty' => $invoicedQty ?? 0,
                'remaining_qty' => $remainingQty,
                'invoice_status' => $invoiceStatus,
                'do_status' => $do->Status,
                'can_invoice' => $remainingQty > 0 && $do->Status !== 'Cancelled'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error getting DO status: ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to get DO status',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update invoice details (Amend Invoice - CPS Compatible)
     */
    public function update(Request $request, $invoiceNo)
    {
        try {
            // Validate request - ONLY editable fields per CPS rules
            $validated = $request->validate([
                'exchange_method' => 'nullable|string|in:1,2',
                'tax_index_no' => 'nullable|string',
                'tax_code' => 'nullable|string',
                'tax_percent' => 'nullable|numeric',
                'invoice_date' => 'nullable|string',
                'ref2' => 'nullable|string',
                'remark' => 'nullable|string',
                'total_amount' => 'nullable|numeric',
                'tax_amount' => 'nullable|numeric',
                'net_amount' => 'nullable|numeric'
            ]);
            
            // Get existing invoice
            $invoice = DB::table('INV')
                ->where('IV_NUM', $invoiceNo)
                ->first();
            
            if (!$invoice) {
                return response()->json([
                    'error' => 'Invoice not found'
                ], 404);
            }
            
            // CPS Business Rules: Check if invoice can be amended
            if (!empty($invoice->PT_UID)) {
                return response()->json([
                    'error' => 'Cannot amend invoice that has been printed',
                    'message' => 'Invoice was printed by ' . $invoice->PT_UID . ' on ' . $invoice->PT_DATE
                ], 400);
            }
            
            if ($invoice->IV_STS === 'Cancelled') {
                return response()->json([
                    'error' => 'Cannot amend cancelled invoice'
                ], 400);
            }
            
            if ($invoice->IV_STS === 'Posted') {
                return response()->json([
                    'error' => 'Cannot amend invoice that has been posted to GL'
                ], 400);
            }
            
            // Prepare update data with audit trail
            $now = now();
            $updateData = [
                'AM_UID' => Auth::check() ? Auth::user()->name : 'system',
                'AM_DATE' => $now->format('d/m/Y'),
                'AM_TIME' => $now->format('H:i'),
            ];
            
            // Update ONLY editable fields (CPS-compatible)
            // Readonly fields: Current Period, Invoice#, Customer, Order Mode, Salesperson, Currency, Exchange Rate, Status
            // Editable fields: Exchange Method, Tax Index No, Invoice Date, 2nd Reference#, Remark
            
            if (isset($validated['exchange_method'])) {
                $updateData['EXCHANGE_METHOD'] = $validated['exchange_method'];
            }
            if (isset($validated['tax_index_no'])) {
                $updateData['TAX_INDEX_NO'] = $validated['tax_index_no'];
            }
            if (isset($validated['tax_code'])) {
                $updateData['IV_TAX_CODE'] = $validated['tax_code'];
            }
            if (isset($validated['tax_percent'])) {
                $updateData['IV_TAX_PERCENT'] = $validated['tax_percent'];
            }
            if (isset($validated['invoice_date'])) {
                // Convert to CPS date format if needed
                $date = $validated['invoice_date'];
                if (strpos($date, '-') !== false) {
                    // Format YYYY-MM-DD to DD/MM/YYYY
                    $dateParts = explode('-', $date);
                    $updateData['IV_DMY'] = sprintf('%s/%s/%s', $dateParts[2], $dateParts[1], $dateParts[0]);
                } else {
                    $updateData['IV_DMY'] = $date;
                }
            }
            if (isset($validated['ref2'])) {
                $updateData['REF2'] = $validated['ref2'];
            }
            if (isset($validated['remark'])) {
                $updateData['IV_REMARK'] = $validated['remark'];
            }
            
            // Update amounts if provided
            if (isset($validated['total_amount'])) {
                $updateData['IV_TRAN_AMT'] = $validated['total_amount'];
            }
            if (isset($validated['tax_amount'])) {
                $updateData['IV_TAX_AMT'] = $validated['tax_amount'];
            }
            if (isset($validated['net_amount'])) {
                $updateData['IV_NET_AMT'] = $validated['net_amount'];
            }
            
            // Perform update
            DB::beginTransaction();
            
            $updated = DB::table('INV')
                ->where('IV_NUM', $invoiceNo)
                ->update($updateData);
            
            DB::commit();
            
            Log::info('Invoice amended successfully', [
                'invoice_no' => $invoiceNo,
                'amended_by' => $updateData['AM_UID'],
                'amended_at' => $updateData['AM_DATE'] . ' ' . $updateData['AM_TIME'],
                'fields_updated' => array_keys($updateData)
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Invoice amended successfully',
                'invoice_no' => $invoiceNo,
                'amended_by' => $updateData['AM_UID'],
                'amended_at' => $updateData['AM_DATE'] . ' ' . $updateData['AM_TIME']
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error amending invoice: ' . $e->getMessage(), [
                'invoice_no' => $invoiceNo,
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'error' => 'Failed to amend invoice',
                'message' => $e->getMessage()
            ], 500);
        }
    }

}
