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
    private function normalizeCustomerStatus(?string $status): array
    {
        $raw = trim((string) $status);
        $lower = strtolower($raw);

        if ($raw === '' || in_array($lower, ['a', 'active', 'y'], true)) {
            return ['label' => $raw ?: 'Active', 'category' => 'active'];
        }

        if (in_array($lower, ['i', 'inactive', 'n'], true)) {
            return ['label' => 'Inactive', 'category' => 'inactive'];
        }

        if (in_array($lower, ['obsolete', 'obs', 'o'], true)) {
            return ['label' => 'Obsolete', 'category' => 'inactive'];
        }

        return ['label' => $raw ?: 'Active', 'category' => 'other'];
    }

    private function isCustomerActive(?string $status): bool
    {
        return $this->normalizeCustomerStatus($status)['category'] === 'active';
    }

    /**
     * Get current date/time in WIB timezone (UTC+7)
     *
     * @return \Carbon\Carbon
     */
    private function getNowWib()
    {
        return now()->timezone('Asia/Jakarta'); // WIB = UTC+7
    }

    /**
     * Calculate tax amount from invoice data (on-the-fly calculation)
     *
     * @param object $invoice
     * @return float
     */
    private function calculateTaxAmount($invoice)
    {
        $tranAmount = (float)($invoice->IV_TRAN_AMT ?? 0);
        $taxPercent = (float)($invoice->IV_TAX_PERCENT ?? 0);

        if ($tranAmount > 0 && $taxPercent > 0) {
            return round($tranAmount * ($taxPercent / 100), 2);
        }

        return 0;
    }

    /**
     * Calculate net amount from invoice data (on-the-fly calculation)
     *
     * @param object $invoice
     * @return float
     */
    private function calculateNetAmount($invoice)
    {
        $tranAmount = (float)($invoice->IV_TRAN_AMT ?? 0);
        $taxAmount = $this->calculateTaxAmount($invoice);

        return $tranAmount + $taxAmount;
    }

    private function isTaxIncludedForCode(?string $taxCode, ?string $customerCode = null, ?string $taxIndexNo = null): bool
    {
        $taxCode = trim((string) $taxCode);
        if ($taxCode === '') {
            return false;
        }

        $taxGroupCode = null;

        if (!empty($customerCode) && !empty($taxIndexNo)) {
            try {
                $indexNumber = (int) ltrim((string) $taxIndexNo, '0');
                if ($indexNumber === 0 && trim((string) $taxIndexNo) !== '0') {
                    $indexNumber = (int) $taxIndexNo;
                }

                $row = DB::table('customer_sales_tax_indices')
                    ->where('customer_code', $customerCode)
                    ->where('index_number', $indexNumber)
                    ->first();

                if ($row && !empty($row->tax_group_code)) {
                    $taxGroupCode = (string) $row->tax_group_code;
                }
            } catch (\Throwable $e) {
                $taxGroupCode = null;
            }
        }

        try {
            $query = DB::table('tax_group_items')
                ->where('tax_type_code', $taxCode)
                ->where(function ($q) {
                    $q->whereNull('status')->orWhere('status', 'A');
                });

            if (!empty($taxGroupCode)) {
                $query->where('tax_group_code', $taxGroupCode);
            }

            $item = $query->orderBy('sequence')->first();
            if ($item && isset($item->include)) {
                return strtoupper((string) $item->include) === 'Y';
            }
        } catch (\Throwable $e) {
            return false;
        }

        return false;
    }

    /**
     * Resolve DO number for printing (CPS-like behaviour)
     *
     * Priority:
     * 1) If IV_SECOND_REF matches a DO record, use that DO_Num
     * 2) Else, find DO by SO_Num and AC_Num (latest DO_Num)
     * 3) Fallback to raw IV_SECOND_REF (may be empty or free text)
     *
     * @param object $invoice
     * @return string
     */
    private function resolveDoNumberForPrint($invoice): string
    {
        $doNumber = $invoice->IV_SECOND_REF ?? '';

        try {
            // 1) Direct match on DO_Num
            if (!empty($doNumber)) {
                $doByRef = DB::table('DO')
                    ->where('DO_Num', $doNumber)
                    ->first();
                if ($doByRef) {
                    return (string) $doByRef->DO_Num;
                }
            }

            // 2) Find DO by SO number + customer (latest DO)
            if (!empty($invoice->SO_NUM) && !empty($invoice->AC_NUM)) {
                $doCandidate = DB::table('DO')
                    ->where('SO_Num', $invoice->SO_NUM)
                    ->where('AC_Num', $invoice->AC_NUM)
                    ->orderByDesc('DO_Num')
                    ->first();

                if ($doCandidate) {
                    return (string) $doCandidate->DO_Num;
                }
            }

            // 3) Fallback: only by SO number
            if (!empty($invoice->SO_NUM)) {
                $doCandidate = DB::table('DO')
                    ->where('SO_Num', $invoice->SO_NUM)
                    ->orderByDesc('DO_Num')
                    ->first();

                if ($doCandidate) {
                    return (string) $doCandidate->DO_Num;
                }
            }
        } catch (\Exception $e) {
            Log::info('Failed to resolve DO number for invoice ' . ($invoice->IV_NUM ?? '') . ': ' . $e->getMessage());
        }

        // Fallback: raw IV_SECOND_REF (may be empty or free text)
        return (string) ($doNumber ?? '');
    }

    /**
     * Get current authenticated user ID for audit trail
     * Returns userID from UserCps model or null
     *
     * @return string|null
     */
    private function getCurrentUserId()
    {
        try {
            Log::info('🔍 getCurrentUserId() called - Starting user ID retrieval');

            if (!Auth::check()) {
                Log::warning('❌ User not authenticated for audit trail');
                return null;
            }

            Log::info('✅ Auth::check() passed');

            $user = Auth::user();

            if (!$user) {
                Log::warning('❌ Auth::user() returned null');
                return null;
            }

            Log::info('✅ Auth::user() returned user object', [
                'class' => get_class($user)
            ]);

            // Try to get userID property from UserCps model
            $userId = null;
            $method = null;

            // Priority 1: Direct property access
            if (isset($user->userID) && !empty($user->userID)) {
                $userId = $user->userID;
                $method = 'userID property';
                Log::info('✅ Found via userID property', ['value' => $userId]);
            }
            // Priority 2: Alternative naming
            elseif (isset($user->user_id) && !empty($user->user_id)) {
                $userId = $user->user_id;
                $method = 'user_id property';
                Log::info('✅ Found via user_id property', ['value' => $userId]);
            }
            // Priority 3: Check NO_ as fallback
            elseif (isset($user->NO_) && !empty($user->NO_)) {
                $userId = $user->NO_;
                $method = 'NO_ property (fallback)';
                Log::info('⚠️ Found via NO_ fallback', ['value' => $userId]);
            }

            // ✅ Fallback: Try Auth::id() if userID property not found
            if (empty($userId)) {
                $authId = Auth::id();
                if ($authId) {
                    $userId = (string) $authId;
                    $method = 'Auth::id() fallback';
                    Log::info('✅ Using Auth::id() as fallback', ['value' => $userId]);
                } else {
                    Log::error('❌ Could not retrieve userID from authenticated user', [
                        'user_class' => get_class($user),
                        'has_userID' => property_exists($user, 'userID'),
                        'has_user_id' => property_exists($user, 'user_id'),
                        'has_NO_' => property_exists($user, 'NO_'),
                        'userID_value' => $user->userID ?? 'NOT SET',
                        'user_id_value' => $user->user_id ?? 'NOT SET',
                        'NO__value' => $user->NO_ ?? 'NOT SET',
                        'Auth::id()' => $authId,
                    ]);
                    return null;
                }
            }

            Log::info('✅ User ID successfully retrieved for audit trail', [
                'userId' => $userId,
                'method' => $method,
                'user_class' => get_class($user)
            ]);

            return $userId;

        } catch (\Exception $e) {
            Log::error('❌ EXCEPTION in getCurrentUserId()', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            return null;
        }
    }

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

    private function normalizeInvoiceStatus($ivSts, $amUid = null): string
    {
        $raw = strtoupper(trim((string) ($ivSts ?? '')));

        if ($raw === 'CANCEL' || $raw === 'CANCELLED') {
            return 'Cancel';
        }

        if ($raw === 'AMEND') {
            return 'Amend';
        }

        if (!empty($amUid)) {
            return 'Amend';
        }

        // Treat legacy states (Active/Posted/blank) as New
        return 'New';
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
     * Normalize various date representations into DD/MM/YYYY string for INV.*_DMY columns
     */
    private function normalizeDateToDmy($value): ?string
    {
        if (empty($value)) {
            return null;
        }

        try {
            return \Carbon\Carbon::parse($value)->format('d/m/Y');
        } catch (\Exception $e) {
            Log::warning('Failed to normalize date to DMY format', [
                'raw_value' => $value,
                'error' => $e->getMessage(),
            ]);

            // Fallback: return original string if it looks like a date, otherwise null
            return is_string($value) ? $value : null;
        }
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
                })
                ->where(function ($q) {
                    // Exclude SO_Type containing 'NonSales' AND 'N1'/'N2'/'N3'/'N4'
                    // Pattern examples: 'N1-NonSales', 'N2-NonSales', etc.
                    $q->where(function($subQ) {
                        // NOT (NonSales AND (N1 OR N2 OR N3 OR N4))
                        $subQ->where('SO_Type', 'NOT LIKE', '%NonSales%')
                             ->orWhere(function($innerQ) {
                                 $innerQ->where('SO_Type', 'LIKE', '%NonSales%')
                                        ->where('SO_Type', 'NOT LIKE', '%N1%')
                                        ->where('SO_Type', 'NOT LIKE', '%N2%')
                                        ->where('SO_Type', 'NOT LIKE', '%N3%')
                                        ->where('SO_Type', 'NOT LIKE', '%N4%');
                             });
                    });
                });

            // Debug logging
            Log::info('🔍 Invoice currentPeriodDo - Filter applied for NonSales N1-N4', [
                'year' => $yyyy,
                'month' => $mm,
                'customer_code' => $customerCode,
                'sql' => $query->toSql(),
                'bindings' => $query->getBindings()
            ]);

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
                    ->where(function ($q) {
                        $q->whereNull('IV_STS')
                          ->orWhereNotIn('IV_STS', ['Cancel', 'Cancelled']);
                    })
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

                $statusMeta = $this->normalizeCustomerStatus($customer->AC_STS ?? '');
                if ($statusMeta['category'] !== 'active') {
                    return response()->json([
                        'success' => false,
                        'message' => 'Cannot prepare invoice because customer status is ' . $statusMeta['label'],
                        'status_raw' => $customer->AC_STS ?? '',
                        'status_label' => $statusMeta['label'],
                        'status_category' => $statusMeta['category'],
                    ], 422);
                }

                return response()->json([
                    'customer_code' => $customer->CODE,
                    'customer_name' => $customer->NAME,
                    'currency' => $this->extractCurrencyCode($customer->CURRENCY),
                    'tax_index_no' => '',
                    'salesperson' => $customer->SLM ?? '',
                    'area' => $customer->AREA ?? '',
                    'status_raw' => $customer->AC_STS ?? '',
                    'status_label' => $statusMeta['label'],
                    'status_category' => $statusMeta['category'],
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
                    'status_raw' => '',
                    'status_label' => 'Active',
                    'status_category' => 'active',
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
                $statusValue = $customer->AC_STS ?? $customer->status ?? $customer->status_raw ?? '';
                $statusMeta = $this->normalizeCustomerStatus($statusValue);
                if ($statusMeta['category'] !== 'active') {
                    return response()->json([
                        'success' => false,
                        'message' => 'Cannot prepare invoice because customer status is ' . $statusMeta['label'],
                        'status_raw' => $statusValue,
                        'status_label' => $statusMeta['label'],
                        'status_category' => $statusMeta['category'],
                    ], 422);
                }

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
                    'status_raw' => $statusValue,
                    'status_label' => $statusMeta['label'],
                    'status_category' => $statusMeta['category'],
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
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function prepare(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            /** @var array<string, mixed> $payload */
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
                'billed_items' => 'nullable|array', // ✅ FIX: Accept billed items with to_bill quantities
            ]);

            $now = now();
            /** @var string $yyyy */
            $yyyy = $request->input('year', $now->format('Y'));
            /** @var string $mm */
            $mm = $request->input('month', $now->format('m'));

            // Normalize invoice date so IV_DMY is always stored as DD/MM/YYYY
            /** @var string $invoiceDateInput */
            $invoiceDateInput = $payload['invoice_date'] ?? $now->format('Y-m-d');
            /** @var string $invoiceDate */
            try {
                $invoiceDate = \Carbon\Carbon::parse($invoiceDateInput)->format('d/m/Y');
            } catch (\Exception $e) {
                // Fallback to current date if parsing fails
                $invoiceDate = $now->format('d/m/Y');
            }
            /** @var string|null $taxIndexNo */
            $taxIndexNo = $payload['tax_index_no'] ?? null;
            /** @var string|null $secondRef */
            $secondRef = $payload['second_ref'] ?? null;
            /** @var string|null $remark */
            $remark = $payload['remark'] ?? null;
            /** @var string $invoiceNumberMode */
            $invoiceNumberMode = $payload['invoice_number_mode'] ?? 'auto';
            /** @var string|null $manualInvoiceNumber */
            $manualInvoiceNumber = $payload['manual_invoice_number'] ?? null;

            // ✅ ADDED: Get tax_code and tax_percent from request (already confirmed in Final Screen)
            /** @var string|null $taxCodeFromFrontend */
            $taxCodeFromFrontend = $payload['tax_code'] ?? null;
            /** @var float|null $taxPercentFromFrontend */
            $taxPercentFromFrontend = $payload['tax_percent'] ?? null;

            // ✅ FIX: Get billed_items with to_bill quantities from frontend
            /** @var array $billedItems */
            $billedItems = $payload['billed_items'] ?? [];

            Log::info('Invoice Preparation Started', [
                'do_count' => count($payload['do_numbers']),
                'mode' => $invoiceNumberMode,
                'manual_number' => $manualInvoiceNumber,
                'period' => "{$yyyy}/{$mm}",
                'tax_from_frontend' => [
                    'tax_code' => $taxCodeFromFrontend,
                    'tax_percent' => $taxPercentFromFrontend
                ],
                'has_billed_items' => !empty($billedItems),
                'billed_items_count' => count($billedItems)
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

            // Get last used invoice sequence for auto-numbering (per period)
            // IMPORTANT: Do NOT use row count because 1 invoice can create multiple INV rows (Main + Fit)
            /** @var string|null $lastIvNum */
            $lastIvNum = DB::table('INV')
                ->where('YYYY', $yyyy)
                ->where('MM', $mm)
                ->where('IV_NUM', 'like', sprintf('%s-%s-%%', $mm, $yyyy))
                ->max('IV_NUM');

            /** @var int $lastSeq */
            $lastSeq = 0;
            if ($lastIvNum) {
                $lastSeq = (int) substr((string) $lastIvNum, -5);
            }

            /** @var int $seq */
            $seq = $lastSeq + 1;

            // Invoice number generator function (CPS-like format: MM-YYYY-SEQ)
            /** @var \Closure(int): string $generateNumber */
            $generateNumber = function (int $seq) use ($yyyy, $mm): string {
                // Example: 11-2025-00001
                return sprintf('%s-%s-%05d', $mm, $yyyy, $seq);
            };

            /** @var array<int, array<string, mixed>> $created */
            $created = [];

            // DEBUG: Log authentication state BEFORE transaction
            Log::info('🔍 DEBUG: Authentication state before invoice creation', [
                'Auth::check()' => Auth::check(),
                'Auth::id()' => Auth::id(),
                'Auth::user() exists' => Auth::user() !== null,
                'User class' => Auth::user() ? get_class(Auth::user()) : null,
            ]);

            // ✅ VALIDATION: Check if there are any items with to_bill > 0
            $hasItemsToBill = false;
            foreach ($billedItems as $doNum => $billedData) {
                $items = $billedData['item_details'] ?? [];
                foreach ($items as $item) {
                    $toBill = floatval($item['to_bill'] ?? 0);
                    $toBillKg = floatval($item['to_bill_kg'] ?? 0);
                    if ($toBill > 0 || $toBillKg > 0) {
                        $hasItemsToBill = true;
                        break 2; // Break both loops
                    }
                }
            }

            if (!$hasItemsToBill) {
                throw new \RuntimeException(
                    'No items to bill. All items have zero unbilled quantity or no To Bill amount specified. ' .
                    'Please ensure items have remaining quantity to bill.'
                );
            }

            DB::beginTransaction();

            foreach ($payload['do_numbers'] as $doNumber) {
                // Fetch all DO lines for this DO number (Main + Fit components)
                $doRecords = DB::table('DO')
                    ->where('DO_Num', $doNumber)
                    ->orderBy('No')
                    ->get();

                /** @var \stdClass|null $do */
                $do = $doRecords->first();

                if (!$do) {
                    throw new \RuntimeException("Delivery Order not found: {$doNumber}");
                }

                // CPS-Compatible: Check if DO is already fully invoiced by calculating from INV table
                // IMPORTANT: Only count Main component lines to avoid double-counting Fit components
                /** @var float $invoicedQty */
                $invoicedQty = DB::table('INV')
                    ->where('IV_SECOND_REF', $doNumber) // ✅ Track invoicing per DO number to avoid cross-DO mixing under the same SO
                    ->where(function ($q) {
                        $q->whereNull('IV_STS')
                          ->orWhereNotIn('IV_STS', ['Cancel', 'Cancelled']);
                    })
                    ->where(function ($q) {
                        $q->whereNull('COMP')
                          ->orWhere('COMP', '')
                          ->orWhere('COMP', 'Main');
                    })
                    ->sum('IV_QTY');

                // Backward compatibility: legacy data without IV_SECOND_REF uses SO_NUM. Only use this if DO-level sum is zero.
                if ($invoicedQty == 0) {
                    $invoicedQty = DB::table('INV')
                        ->whereNull('IV_SECOND_REF')
                        ->where('SO_NUM', $this->getProperty($do, 'SO_Num'))
                        ->where(function ($q) {
                            $q->whereNull('IV_STS')
                              ->orWhereNotIn('IV_STS', ['Cancel', 'Cancelled']);
                        })
                        ->where(function ($q) {
                            $q->whereNull('COMP')
                              ->orWhere('COMP', '')
                              ->orWhere('COMP', 'Main');
                        })
                        ->sum('IV_QTY');
                }

                /** @var float $doQty */
                $doQty = $this->toDecimalOrNull($this->getProperty($do, 'DO_Qty'), 0);
                /** @var float $remainingQty */
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

                // Fetch customer payment term using helper method
                /** @var string|null $customerCode */
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

                $taxIsIncluded = false;
                $taxDivisor = null;
                if (!empty($taxCode) && !empty($taxPercent)) {
                    $taxIsIncluded = $this->isTaxIncludedForCode(
                        (string) $taxCode,
                        (string) ($customerCode ?? ''),
                        (string) ($taxIndexNo ?? '')
                    );
                    if ($taxIsIncluded) {
                        $taxDivisor = 1 + ((float) $taxPercent / 100);
                        if ($taxDivisor <= 0) {
                            $taxDivisor = null;
                            $taxIsIncluded = false;
                        }
                    }
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

                // Calculate amounts for the whole DO (sum of all component lines)
                $tranAmount = $this->toDecimalOrNull($doRecords->sum('DO_Tran_Amt'), 0);
                $baseAmount = $this->toDecimalOrNull($doRecords->sum('DO_Base_Amt'), 0);
                $exRate = $this->toDecimalOrNull($this->getProperty($do, 'Ex_Rate'), 1);

                // If DO_Tran_Amt is 0, calculate from DO_Qty * SO_Unit_Price
                if ($tranAmount == 0) {
                    $doQty = $this->toDecimalOrNull($this->getProperty($do, 'DO_Qty'), 0);
                    $unitPrice = $this->toDecimalOrNull($this->getProperty($do, 'SO_Unit_Price'), 0);

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

                // ✅ CRITICAL FIX: If baseAmount is still 0 but tranAmount exists, calculate it
                // This handles cases where DO_Base_Amt is NULL/0 in DO table
                if ($baseAmount == 0 && $tranAmount > 0) {
                    $baseAmount = round($tranAmount * $exRate, 2);

                    Log::info('✅ Calculated IV_BASE_AMT from IV_TRAN_AMT × EX_RATE', [
                        'tran_amount' => $tranAmount,
                        'ex_rate' => $exRate,
                        'calculated_base_amt' => $baseAmount,
                        'reason' => 'DO_Base_Amt was 0 or NULL'
                    ]);
                }

                // Calculate tax amount if applicable
                if ($taxIsIncluded && $taxDivisor && $tranAmount > 0) {
                    $tranAmount = round($tranAmount / $taxDivisor, 2);
                    $baseAmount = round($tranAmount * $exRate, 2);
                }

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

                // Get User ID for audit trail with detailed logging
                $currentUserId = $this->getCurrentUserId();

                Log::info('🔍 DEBUG: User ID for NW_UID', [
                    'returned_value' => $currentUserId,
                    'type' => gettype($currentUserId),
                    'is_null' => $currentUserId === null ? 'YES' : 'NO',
                    'is_empty' => empty($currentUserId) ? 'YES' : 'NO',
                ]);

                // Log critical values before INSERT
                Log::info('🔍 Critical values before INSERT', [
                    'invoice_num' => $ivNum,
                    'NW_UID' => $currentUserId, // ADDED: Log the actual value that will be inserted
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
                        $rawSoDmy = $soData ? ($soData->SO_DMY ?? null) : null;
                        $soDmy = $this->normalizeDateToDmy($rawSoDmy);

                        Log::info('✅ Retrieved SO_DMY from SO table', [
                            'so_num' => $soNum,
                            'raw_so_dmy' => $rawSoDmy,
                            'normalized_so_dmy' => $soDmy,
                        ]);
                    } catch (\Exception $e) {
                        Log::warning('❌ Cannot fetch SO_DMY from SO table', [
                            'so_num' => $soNum,
                            'error' => $e->getMessage()
                        ]);
                    }
                }

                // =====================================================================
                // Insert invoice rows: one INV line per DO component (Main + Fit)
                // =====================================================================
                $itemNo = 1;

                // ✅ FIX: Get billed item details for this DO from frontend
                $billedItemData = $billedItems[$doNumber] ?? null;
                $itemDetailsFromFrontend = $billedItemData['item_details'] ?? [];
                
                Log::info('📦 Processing DO with to_bill quantities', [
                    'do_number' => $doNumber,
                    'has_item_details' => !empty($itemDetailsFromFrontend),
                    'item_count' => count($itemDetailsFromFrontend)
                ]);

                foreach ($doRecords as $index => $line) {
                    // ✅ FIX: Get user-specified to_bill quantity from frontend
                    $comp = trim((string) ($line->COMP ?? ''));
                    $isMainComp = ($index === 0) || strcasecmp($comp, 'Main') === 0;
                    
                    // ✅ IMPROVED: Match frontend items by index (most reliable) or label
                    $frontendItem = null;
                    if (!empty($itemDetailsFromFrontend)) {
                        // Try to match by index first (most reliable)
                        if (isset($itemDetailsFromFrontend[$index])) {
                            $frontendItem = $itemDetailsFromFrontend[$index];
                            Log::info("✅ Matched by index", [
                                'index' => $index,
                                'frontend_item' => $frontendItem['item'] ?? 'unknown',
                                'do_comp' => $comp ?: 'empty'
                            ]);
                        } else {
                            // Fallback: Try to match by label
                            foreach ($itemDetailsFromFrontend as $item) {
                                $itemLabel = $item['item'] ?? '';
                                $itemIsMain = ($itemLabel === 'Main');
                                
                                if ($isMainComp && $itemIsMain) {
                                    $frontendItem = $item;
                                    break;
                                } elseif (!$isMainComp && $comp && $itemLabel === $comp) {
                                    $frontendItem = $item;
                                    break;
                                }
                            }
                        }
                    }
                    
                    // Base quantity per line (from DO)
                    $lineQty = $this->toDecimalOrNull($this->getProperty($line, 'DO_Qty'), 0);
                    
                    // ✅ FIX: Use to_bill quantity if provided, otherwise skip this item
                    $toBillQty = 0;
                    $toBillKg = 0;
                    
                    if ($frontendItem) {
                        // Check unit type - KG vs PCS
                        $unit = strtoupper(trim($frontendItem['unit'] ?? ''));
                        
                        if ($unit === 'KG') {
                            // For KG unit, use to_bill_kg
                            $toBillKg = floatval($frontendItem['to_bill_kg'] ?? 0);
                            $toBillQty = $toBillKg; // Invoice quantity is in KG
                        } else {
                            // For other units (PCS, etc.), use to_bill
                            $toBillQty = floatval($frontendItem['to_bill'] ?? 0);
                        }
                        
                        Log::info("✅ Using to_bill quantity for {$comp}", [
                            'do_number' => $doNumber,
                            'component' => $comp ?: 'Main',
                            'unit' => $unit,
                            'deliver_qty' => $lineQty,
                            'to_bill' => $toBillQty,
                            'to_bill_kg' => $toBillKg
                        ]);
                    }
                    
                    // ✅ CRITICAL: Skip items with zero to_bill quantity (user didn't bill them)
                    if ($toBillQty <= 0) {
                        Log::info("⏭️ Skipping item with zero to_bill", [
                            'do_number' => $doNumber,
                            'component' => $comp ?: 'Main',
                            'reason' => 'to_bill = 0'
                        ]);
                        continue; // Skip this item - don't create invoice line
                    }
                    
                    // ✅ VALIDATION: Ensure to_bill doesn't exceed available quantity
                    $invoicedQty = DB::table('INV')
                        ->where('IV_SECOND_REF', $doNumber)
                        ->where('SO_NUM', $this->getProperty($line, 'SO_Num'))
                        ->where(function($q) use ($comp, $isMainComp) {
                            if ($isMainComp) {
                                $q->whereNull('COMP')->orWhere('COMP', '')->orWhere('COMP', 'Main');
                            } else {
                                $q->where('COMP', $comp);
                            }
                        })
                        ->where(function ($q) {
                            $q->whereNull('IV_STS')->orWhereNotIn('IV_STS', ['Cancel', 'Cancelled']);
                        })
                        ->sum('IV_QTY');
                    
                    $remainingQty = $lineQty - floatval($invoicedQty);
                    
                    if ($toBillQty > $remainingQty) {
                        throw new \RuntimeException(
                            "Cannot bill {$toBillQty} for {$comp} in DO {$doNumber}. " .
                            "Only {$remainingQty} remaining (Delivered: {$lineQty}, Already Invoiced: {$invoicedQty})"
                        );
                    }
                    
                    // Use to_bill quantity for invoice (not full DO quantity)
                    $lineQty = $toBillQty;

                    // Normalize PO date for INV.PO_DMY
                    $poDateRaw = $this->getProperty($line, 'PO_Date');
                    $poDmy = $this->normalizeDateToDmy($poDateRaw);

                    // Raw values from DO line for M2 & KG
                    $grossM2PerPcs = $this->toDecimalOrNull($this->getProperty($line, 'MC_Gross_M2_Per_Pcs'));
                    $netM2PerPcs   = $this->toDecimalOrNull($this->getProperty($line, 'MC_Net_M2_Per_Pcs'));
                    $totalGrossM2  = $this->toDecimalOrNull($this->getProperty($line, 'Total_DO_Gross_M2'));
                    $totalNetM2    = $this->toDecimalOrNull($this->getProperty($line, 'Total_DO_Net_M2'));

                    $grossKgPerPcs = $this->toDecimalOrNull($this->getProperty($line, 'MC_Gross_Kg_Per_Pcs'));
                    $netKgPerPcs   = $this->toDecimalOrNull($this->getProperty($line, 'MC_Net_Kg_Per_Pcs'));
                    $totalGrossKg  = $this->toDecimalOrNull($this->getProperty($line, 'Total_DO_Gross_KG'));
                    $totalNetKg    = $this->toDecimalOrNull($this->getProperty($line, 'Total_DO_Net_KG'));

                    // ✅ FIX: Store original DO quantity before overriding with to_bill
                    $originalDoQty = $this->toDecimalOrNull($this->getProperty($line, 'DO_Qty'), 0);
                    
                    // --- Derive per-PCS values from totals when missing (use original DO qty) ---
                    if ((!$grossM2PerPcs || $grossM2PerPcs == 0) && $totalGrossM2 && $originalDoQty > 0) {
                        $grossM2PerPcs = round($totalGrossM2 / $originalDoQty, 4);
                    }
                    if ((!$netM2PerPcs || $netM2PerPcs == 0) && $totalNetM2 && $originalDoQty > 0) {
                        $netM2PerPcs = round($totalNetM2 / $originalDoQty, 4);
                    }
                    if ((!$grossKgPerPcs || $grossKgPerPcs == 0) && $totalGrossKg && $originalDoQty > 0) {
                        $grossKgPerPcs = round($totalGrossKg / $originalDoQty, 4);
                    }
                    if ((!$netKgPerPcs || $netKgPerPcs == 0) && $totalNetKg && $originalDoQty > 0) {
                        $netKgPerPcs = round($totalNetKg / $originalDoQty, 4);
                    }

                    // ✅ FIX: Calculate invoice totals based on to_bill quantity (not original DO qty)
                    // Proportionally scale M2/KG values based on to_bill vs original
                    if ($grossM2PerPcs && $lineQty > 0) {
                        $totalGrossM2 = round($grossM2PerPcs * $lineQty, 4);
                    } else {
                        $totalGrossM2 = 0;
                    }
                    
                    if ($netM2PerPcs && $lineQty > 0) {
                        $totalNetM2 = round($netM2PerPcs * $lineQty, 4);
                    } else {
                        $totalNetM2 = 0;
                    }
                    
                    if ($grossKgPerPcs && $lineQty > 0) {
                        $totalGrossKg = round($grossKgPerPcs * $lineQty, 4);
                    } else {
                        $totalGrossKg = 0;
                    }
                    
                    if ($netKgPerPcs && $lineQty > 0) {
                        $totalNetKg = round($netKgPerPcs * $lineQty, 4);
                    } else {
                        $totalNetKg = 0;
                    }

                    // Determine Main vs Fit component (still used for informational flags/printing)
                    $isMain = ($itemNo === 1) || strcasecmp($comp, 'Main') === 0;

                    // ✅ Invoice quantity is the to_bill quantity (already set in $lineQty)
                    $ivQty = $lineQty;

                    // ✅ FIX: Calculate amounts based on to_bill quantity
                    $unitPrice = $this->toDecimalOrNull($this->getProperty($line, 'SO_Unit_Price'), 0);
                    $unitPriceToStore = $unitPrice;
                    if ($taxIsIncluded && $taxDivisor && $unitPriceToStore > 0) {
                        $unitPriceToStore = round($unitPriceToStore / $taxDivisor, 4);
                    }
                    
                    // Always recalculate amounts based on to_bill quantity
                    if ($unitPriceToStore > 0 && $lineQty > 0) {
                        $lineTranAmt = round($lineQty * $unitPriceToStore, 2);
                        $lineBaseAmt = round($lineTranAmt * $exRate, 2);
                        
                        Log::info("💰 Calculated amounts for to_bill", [
                            'to_bill_qty' => $lineQty,
                            'unit_price' => $unitPriceToStore,
                            'tran_amt' => $lineTranAmt,
                            'base_amt' => $lineBaseAmt
                        ]);
                    } else {
                        // Fallback to proportional calculation from DO amounts
                        $doTranAmt = $this->toDecimalOrNull($this->getProperty($line, 'DO_Tran_Amt'), 0);
                        $doBaseAmt = $this->toDecimalOrNull($this->getProperty($line, 'DO_Base_Amt'), 0);
                        
                        if ($doTranAmt > 0 && $originalDoQty > 0) {
                            // Proportionally scale DO amount based on to_bill vs original
                            $lineTranAmt = round(($doTranAmt / $originalDoQty) * $lineQty, 2);
                            $lineBaseAmt = round(($doBaseAmt / $originalDoQty) * $lineQty, 2);
                        } else {
                            $lineTranAmt = 0;
                            $lineBaseAmt = 0;
                        }

                        if ($taxIsIncluded && $taxDivisor && $lineTranAmt > 0) {
                            $lineTranAmt = round($lineTranAmt / $taxDivisor, 2);
                            $lineBaseAmt = round($lineTranAmt * $exRate, 2);
                        }
                    }
                    
                    if ($lineBaseAmt == 0 && $lineTranAmt > 0) {
                        $lineBaseAmt = round($lineTranAmt * $exRate, 2);
                    }

                    // ✅ CRITICAL: Determine proper COMP value for storage
                    // Use frontend item label if available, otherwise use DO.COMP
                    $compToStore = null;
                    if ($frontendItem) {
                        $frontendLabel = $frontendItem['item'] ?? '';
                        // Store component label from frontend (Main, Fit1, etc.)
                        $compToStore = $frontendLabel === 'Main' ? 'Main' : $frontendLabel;
                    } else {
                        // Fallback to DO.COMP
                        $compToStore = $comp ?: null;
                    }
                    
                    // 🔍 DEBUG: Log what we're about to insert
                    Log::info("💾 DEBUG: Inserting invoice record", [
                        'do_number' => $doNumber,
                        'iv_num' => $ivNum,
                        'comp_from_do' => $comp ?: 'NULL',
                        'comp_to_store' => $compToStore ?: 'NULL',
                        'is_main' => $isMain,
                        'iv_qty' => $ivQty,
                        'iv_second_ref' => $this->getProperty($line, 'DO_Num'),
                        'frontend_item' => $frontendItem['item'] ?? 'not_found'
                    ]);
                    
                    // Insert invoice record for this DO component line
                    DB::table('INV')->insert([
                        // Period and identification
                        'YYYY' => $yyyy,
                        'MM' => $mm,
                        'IV_NUM' => $ivNum,
                        'IV_STS' => 'New',
                        'IV_DMY' => $invoiceDate,

                        // Payment terms and references
                        'AR_TERM' => $paymentTerm,
                        'IV_SECOND_REF' => $this->getProperty($line, 'DO_Num'), // ✅ CRITICAL: Always use DO_Num for tracking unbill

                        // Customer information
                        'AC_NUM' => $this->getProperty($line, 'AC_Num'),
                        'AC_NAME' => $this->getProperty($line, 'AC_Name'),

                        // Item details
                        'ITEM' => $itemNo,
                        'MCS_NUM' => $this->getProperty($line, 'MCS_Num'),
                        'MODEL' => $this->getProperty($line, 'Model'),
                        'PRODUCT' => $this->getProperty($line, 'Product'),
                        'COMP' => $compToStore, // ✅ CRITICAL: Store proper component label for unbill tracking
                        'P_DESIGN' => $this->getProperty($line, 'PD'),
                        'PCS_PER_SET' => $this->toDecimalOrNull($this->getProperty($line, 'PCS_PER_SET')),
                        'UNIT' => $this->getProperty($line, 'Unit'),
                        'PART' => $this->getProperty($line, 'Part_Number'),

                        // Dimensions
                        'INT_L' => $this->toDecimalOrNull($this->getProperty($line, 'INT_L')),
                        'INT_W' => $this->toDecimalOrNull($this->getProperty($line, 'INT_W')),
                        'INT_H' => $this->toDecimalOrNull($this->getProperty($line, 'INT_H')),
                        'EXT_L' => $this->toDecimalOrNull($this->getProperty($line, 'EXT_L')),
                        'EXT_W' => $this->toDecimalOrNull($this->getProperty($line, 'EXT_W')),
                        'EXT_H' => $this->toDecimalOrNull($this->getProperty($line, 'EXT_H')),
                        'FLUTE' => $this->getProperty($line, 'Flute'),

                        // Sales and organizational info
                        'SLM' => $this->getProperty($line, 'SLM'),
                        'IND' => $this->getProperty($line, 'IND'),
                        'AREA' => $this->getProperty($line, 'Area1'),
                        'GROUP_' => $this->getProperty($line, 'Group_'),

                        // Order references
                        'SO_NUM' => $this->getProperty($line, 'SO_Num'),
                        'SO_TYPE' => $this->getProperty($line, 'SO_Type'),
                        'SO_DMY' => $soDmy,
                        'PO_NUM' => $this->getProperty($line, 'PO_Num'),
                        'PO_DMY' => $poDmy,
                        'LOT_NUM' => $this->getProperty($line, 'LOT_Num'),

                        // Quantities
                        'SO_PQ1' => $this->getProperty($line, 'PQ1'),
                        'SO_PQ2' => $this->getProperty($line, 'PQ2'),
                        'SO_PQ3' => $this->getProperty($line, 'PQ3'),
                        'SO_PQ4' => $this->getProperty($line, 'PQ4'),
                        'SO_PQ5' => $this->getProperty($line, 'PQ5'),
                        'IV_QTY' => $ivQty,
                        'IV_UNIT_PRICE' => $this->toDecimalOrNull($unitPriceToStore),

                        // Currency and amounts
                        'CURR' => $this->getProperty($line, 'Curr', 'IDR'),
                        'EX_RATE' => $this->toDecimalOrNull($this->getProperty($line, 'Ex_Rate'), 1.0),
                        'IV_TRAN_AMT' => $lineTranAmt,
                        'IV_BASE_AMT' => $lineBaseAmt,

                        // Measurements
                        'MC_GROSS_M2_PER__PCS' => $grossM2PerPcs,
                        'MC_NET_M2_PER_PCS' => $netM2PerPcs,
                        'TOTAL_IV_GROSS_M2' => $totalGrossM2,
                        'TOTAL_IV_NET_M2' => $totalNetM2,
                        'MC_GROSS_KG_PER_PCS' => $grossKgPerPcs,
                        'MC_NET_KG_PER_PCS' => $netKgPerPcs,
                        'TOTAL_IV_GROSS_KG' => $totalGrossKg,
                        'TOTAL_IV_NET_KG' => $totalNetKg,

                        // Tax information
                        'IV_TAX_CODE' => $taxCode,
                        'IV_TAX_PERCENT' => $this->toDecimalOrNull($taxPercent),

                        // Remarks (combine user's secondRef and remark)
                        'IV_REMARK' => $secondRef ? ($remark ? "{$secondRef} - {$remark}" : $secondRef) : $remark,

                        // Audit trail
                        'NW_UID' => $currentUserId,
                        'NW_DATE' => $this->getNowWib()->format('d/m/Y'),
                        'NW_TIME' => $this->getNowWib()->format('H:i'),

                        // Date surrogate keys
                        'IVDateSK' => $this->toIntegerOrNull($this->getProperty($line, 'DODateSK'), 0),
                        'SODateSK' => $this->toIntegerOrNull($this->getProperty($line, 'SODateSK'), 0),
                        'PODateSK' => $this->toIntegerOrNull($this->getProperty($line, 'PODateSK'), 0),
                    ]);

                    $itemNo++;
                }

                // CPS-Compatible: Update DO status based on remaining quantity
                try {
                    // Calculate new invoiced quantity after this invoice
                    $newInvoicedQty = DB::table('INV')
                        ->where('IV_SECOND_REF', $doNumber)
                        ->where(function ($q) {
                            $q->whereNull('IV_STS')
                              ->orWhereNotIn('IV_STS', ['Cancel', 'Cancelled']);
                        })
                        ->sum('IV_QTY');

                    // Backward compatibility for legacy records without IV_SECOND_REF
                    if ($newInvoicedQty == 0) {
                        $newInvoicedQty = DB::table('INV')
                            ->whereNull('IV_SECOND_REF')
                            ->where('SO_NUM', $this->getProperty($do, 'SO_Num'))
                            ->where(function ($q) {
                                $q->whereNull('IV_STS')
                                  ->orWhereNotIn('IV_STS', ['Cancel', 'Cancelled']);
                            })
                            ->sum('IV_QTY');
                    }

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
     * CPS-Compatible: Supports two cancellation reasons
     */
    public function cancelInvoice(Request $request)
    {
        try {
            $payload = $request->validate([
                'invoice_number' => 'required|string|max:50',
                'cancel_reason' => 'required|string|max:250',
                'cancel_reason_2' => 'nullable|string|max:250',
            ]);

            DB::beginTransaction();

            $invoice = DB::table('INV')->where('IV_NUM', $payload['invoice_number'])->first();

            if (!$invoice) {
                throw new \RuntimeException("Invoice not found");
            }

            if (in_array($invoice->IV_STS, ['Cancel', 'Cancelled'], true)) {
                throw new \RuntimeException("Invoice is already cancelled");
            }

            // CPS Business Rule: Cannot cancel printed invoices
            if (!empty($invoice->PT_UID)) {
                throw new \RuntimeException("Cannot cancel invoice that has been printed");
            }

            // CPS Business Rule: Cannot cancel invoice that already has Faktur Number
            try {
                $hasFaktur = DB::table('TAX')
                    ->where('INV_Num', $payload['invoice_number'])
                    ->whereNotNull('No_Faktur')
                    ->where('No_Faktur', '!=', '')
                    ->exists();

                if ($hasFaktur) {
                    $fakturRow = DB::table('TAX')
                        ->where('INV_Num', $payload['invoice_number'])
                        ->whereNotNull('No_Faktur')
                        ->where('No_Faktur', '!=', '')
                        ->orderByDesc('Tgl_Faktur')
                        ->first();

                    throw new \RuntimeException(
                        "Cannot cancel invoice that already has Faktur Number: " . ($fakturRow->No_Faktur ?? '-')
                    );
                }
            } catch (\RuntimeException $e) {
                throw $e;
            } catch (\Exception $e) {
                Log::warning('Failed to check TAX faktur number (cancel)', [
                    'invoice_number' => $payload['invoice_number'],
                    'error' => $e->getMessage(),
                ]);
            }

            // Prepare update data (WIB timezone)
            $nowWib = $this->getNowWib();
            $updateData = [
                'IV_STS' => 'Cancel',
                'CANCELLED_REASON_1' => $payload['cancel_reason'],
                'CX_UID' => $this->getCurrentUserId(),
                'CX_DATE' => $nowWib->format('d/m/Y'),
                'CX_TIME' => $nowWib->format('H:i'),
            ];

            // Add second reason if provided
            if (!empty($payload['cancel_reason_2'])) {
                $updateData['CANCELLED_REASON_2'] = $payload['cancel_reason_2']; // ✅ Fixed column name
            }

            // Update invoice status
            DB::table('INV')
                ->where('IV_NUM', $payload['invoice_number'])
                ->update($updateData);

            // CPS-Compatible: Revert DO status if linked
            if ($invoice->IV_SECOND_REF) {
                // Recalculate invoiced quantity after this cancellation
                $invoicedQty = DB::table('INV')
                    ->where('SO_NUM', $invoice->SO_NUM)
                    ->where(function ($q) {
                        $q->whereNull('IV_STS')
                          ->orWhereNotIn('IV_STS', ['Cancel', 'Cancelled']);
                    })
                    ->where('IV_NUM', '!=', $payload['invoice_number']) // Exclude current invoice
                    ->sum('IV_QTY');

                // Get DO quantity
                $do = DB::table('DO')->where('DO_Num', $invoice->IV_SECOND_REF)->first();

                if ($do) {
                    $doQty = floatval($do->DO_Qty ?? 0);
                    $remainingQty = $doQty - ($invoicedQty ?? 0);

                    // Determine new DO status
                    $newStatus = null; // Open (no status)
                    if ($remainingQty <= 0) {
                        $newStatus = 'Invoiced'; // Fully invoiced
                    } elseif ($invoicedQty > 0) {
                        $newStatus = 'Partial'; // Partially invoiced
                    }

                    DB::table('DO')
                        ->where('DO_Num', $invoice->IV_SECOND_REF)
                        ->update([
                            'Status' => $newStatus,
                        ]);

                    Log::info('DO status reverted after invoice cancellation', [
                        'invoice_num' => $payload['invoice_number'],
                        'do_number' => $invoice->IV_SECOND_REF,
                        'new_status' => $newStatus,
                        'remaining_qty' => $remainingQty
                    ]);
                }
            }

            DB::commit();

            Log::info('Invoice cancelled successfully', [
                'invoice_number' => $payload['invoice_number'],
                'cancelled_by' => $updateData['CX_UID'],
                'cancelled_at' => $updateData['CX_DATE'] . ' ' . $updateData['CX_TIME'],
                'reason' => $payload['cancel_reason']
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Invoice cancelled successfully',
                'invoice_number' => $payload['invoice_number'],
                'cancelled_by' => $updateData['CX_UID'],
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Failed to cancel invoice', [
                'invoice_number' => $request->input('invoice_number'),
                'error' => $e->getMessage()
            ]);

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

        $invoiceFrom = trim((string) $request->input('invoice_from'));
        $invoiceTo = trim((string) $request->input('invoice_to'));

        $query = DB::table('INV')
            ->where('ITEM', 1)
            ->where('YYYY', $year)
            ->where('MM', str_pad($month, 2, '0', STR_PAD_LEFT));

        if ($invoiceFrom !== '' && $invoiceTo !== '') {
            $query->whereBetween('IV_NUM', [$invoiceFrom, $invoiceTo]);
        } elseif ($invoiceFrom !== '') {
            $query->where('IV_NUM', '>=', $invoiceFrom);
        } elseif ($invoiceTo !== '') {
            $query->where('IV_NUM', '<=', $invoiceTo);
        }

        $select = [
            'IV_NUM as invoice_number',
            'IV_DMY as invoice_date',
            'AC_NUM as customer_code',
            'AC_NAME as customer_name',
            'IV_TRAN_AMT as amount',
            'IV_TAX_CODE as tax_code',
            'IV_TAX_PERCENT as tax_percent',
            DB::raw("CASE WHEN IV_STS IN ('Cancel','Cancelled') THEN 'Cancel' WHEN IV_STS = 'Amend' OR (AM_UID IS NOT NULL AND AM_UID != '') THEN 'Amend' ELSE 'New' END as status"),
            'NW_UID as created_by',
            'NW_DATE as created_date',
        ];

        $invoices = $query->select($select)
            ->orderBy('IV_NUM', 'desc')
            ->get();

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

            // Build item details from actual DO component lines (Main + Fit)
            $itemDetails = [];

            // Calculate total Net KG from all DO records (sum if multiple lines)
            $totalNetKg = floatval($doRecords->sum('Total_DO_Net_KG'));
            $doQty = floatval($doRecords->first()->DO_Qty ?: 0);
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
            elseif ($doRecords->first()->MC_Net_Kg_Per_Pcs && floatval($doRecords->first()->MC_Net_Kg_Per_Pcs) > 0) {
                $kgPerUnit = floatval($doRecords->first()->MC_Net_Kg_Per_Pcs);
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

            Log::info("=== Final KG Data ===", [
                'kg_per_unit' => $kgPerUnit,
                'total_kg_available' => $totalNetKg,
                'ready_for_calculation' => $kgPerUnit > 0
            ]);

            foreach ($doRecords as $index => $record) {
                $comp = trim((string) ($record->COMP ?? ''));
                $isMain = ($index === 0) || strcasecmp($comp, 'Main') === 0;

                // ✅ CRITICAL: Use consistent label that matches what's stored in INV.COMP
                // Frontend displays as "Main", "Fit1", "Fit2", etc.
                // We need to match this in our query
                $label = $isMain ? 'Main' : ($comp !== '' ? $comp : 'Fit' . $index);

                $qty = floatval($record->DO_Qty ?: 0);

                // ✅ FIX: Calculate already invoiced quantity for this specific DO and component
                // Query INV table to get total invoiced for this DO_Num, SO_Num, and Component
                
                // 🔍 DEBUG: Log search parameters
                Log::info("🔍 Searching invoices for unbill calculation", [
                    'do_number' => $doNumber,
                    'so_number' => $record->SO_Num,
                    'label' => $label,
                    'is_main' => $isMain,
                    'index' => $index
                ]);
                
                $invoicedQty = DB::table('INV')
                    ->where('IV_SECOND_REF', $doNumber) // Track by DO number (most accurate)
                    ->where('SO_NUM', $record->SO_Num) // Also match SO number
                    ->where(function($q) use ($label, $isMain) {
                        // Match component type using consistent label
                        if ($isMain) {
                            // For Main component: COMP is null or 'Main'
                            $q->whereNull('COMP')
                              ->orWhere('COMP', '')
                              ->orWhere('COMP', 'Main');
                        } else {
                            // For Fit components: match label (Fit1, Fit2, etc.)
                            $q->where('COMP', $label);
                        }
                    })
                    ->where(function ($q) {
                        // Only count non-cancelled invoices
                        $q->whereNull('IV_STS')
                          ->orWhereNotIn('IV_STS', ['Cancel', 'Cancelled']);
                    })
                    ->sum('IV_QTY');
                
                // 🔍 DEBUG: Check what invoices exist for this DO
                $debugInvoices = DB::table('INV')
                    ->where('IV_SECOND_REF', $doNumber)
                    ->select(['IV_NUM', 'COMP', 'IV_QTY', 'SO_NUM'])
                    ->get();
                    
                Log::info("🔍 Found invoices", [
                    'do_number' => $doNumber,
                    'invoices' => $debugInvoices->toArray(),
                    'calculated_invoiced_qty' => $invoicedQty
                ]);

                $invoicedQty = floatval($invoicedQty ?: 0);
                
                // Calculate remaining unbilled quantity
                $unbillQty = $qty - $invoicedQty;
                
                // Ensure unbill is not negative
                if ($unbillQty < 0) {
                    $unbillQty = 0;
                }

                Log::info("📊 Unbill calculation for {$label}", [
                    'do_number' => $doNumber,
                    'component' => $comp ?: 'Main',
                    'deliver_qty' => $qty,
                    'invoiced_qty' => $invoicedQty,
                    'unbill_qty' => $unbillQty,
                ]);

                $detail = [
                    'item' => $label,
                    'p_design' => $record->PD ?: ($isMain ? 'B1' : '-'),
                    'pcs' => $record->PCS_PER_SET ?: null,
                    'unit' => $record->Unit ?: null,
                    'u_price' => floatval($record->SO_Unit_Price ?: 0),
                    'deliver' => $qty,
                    'reject' => 0,
                    'unbill' => $unbillQty,  // ✅ FIXED: Now reflects actual remaining quantity
                    'to_bill' => 0,      // User will input for Main row only in UI
                    'to_bill_kg' => 0,   // Calculated client-side for Main row
                    'kg_per_unit' => $isMain ? $kgPerUnit : null,
                ];

                $itemDetails[] = $detail;
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

            // Exclude SO_Type containing 'NonSales' AND 'N1'/'N2'/'N3'/'N4'
            // Pattern examples: 'N1-NonSales', 'N2-NonSales', etc.
            $query->where(function ($q) {
                // NOT (NonSales AND (N1 OR N2 OR N3 OR N4))
                $q->where(function($subQ) {
                    $subQ->where('SO_Type', 'NOT LIKE', '%NonSales%')
                         ->orWhere(function($innerQ) {
                             $innerQ->where('SO_Type', 'LIKE', '%NonSales%')
                                    ->where('SO_Type', 'NOT LIKE', '%N1%')
                                    ->where('SO_Type', 'NOT LIKE', '%N2%')
                                    ->where('SO_Type', 'NOT LIKE', '%N3%')
                                    ->where('SO_Type', 'NOT LIKE', '%N4%');
                         });
                });
            });

            // Debug logging
            Log::info('🔍 Invoice getDeliveryOrders - Filter applied for NonSales N1-N4', [
                'customer_code' => $customerCode,
                'period_month' => $periodMonth,
                'period_year' => $periodYear,
                'sql' => $query->toSql(),
                'bindings' => $query->getBindings()
            ]);

            // Get delivery orders with aggregated item count and additional fields
            // IMPORTANT: Group by DO header only (one row per DO_Num), not per component line
            $deliveryOrders = $query
                ->select([
                    'DO_Num',
                    'DO_DMY',
                    'AC_Num',
                    'AC_Name',
                    'DO_VHC_Num',
                    'VHC_Class',
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
                    DB::raw('MAX(DOMM) as month'),
                    DB::raw('MAX(PCS_PER_SET) as pcs_per_set')
                ])
                ->groupBy([
                    'DO_Num',
                    'DO_DMY',
                    'AC_Num',
                    'AC_Name',
                    'DO_VHC_Num',
                    'VHC_Class',
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
                    'vehicle_class' => $order->VHC_Class,
                    'item_count' => intval($order->item_count),
                    'pc' => $order->pcs_per_set ?? 1,
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
            // Only show one row per invoice number: use ITEM = 1 (Main component line)
            $query = DB::table('INV')->where('ITEM', 1);

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

            // Exclude cancelled invoices if requested (for Amend/Cancel Invoice pages)
            if ($request->boolean('exclude_cancelled', false)) {
                $query->where(function ($q) {
                    $q->whereNull('IV_STS')
                      ->orWhereNotIn('IV_STS', ['Cancel', 'Cancelled']);
                });
            }

            // Get column list to check which columns exist
            $columns = DB::getSchemaBuilder()->getColumnListing('INV');

            // Build select array with only existing columns
            $selectColumns = [
                'IV_NUM as invoice_no',
                'IV_DMY as invoice_date',
                'AC_NUM as customer_code',
            ];

            $statusExpr = "CASE";
            $statusExpr .= " WHEN IV_STS IN ('Cancel','Cancelled') THEN 'Cancel'";
            $statusExpr .= " WHEN IV_STS = 'Amend' THEN 'Amend'";
            $statusExpr .= " WHEN IV_STS = 'New' THEN 'New'";
            if (in_array('AM_UID', $columns)) {
                $statusExpr .= " WHEN AM_UID IS NOT NULL AND AM_UID != '' THEN 'Amend'";
            }
            $statusExpr .= " ELSE 'New' END";
            $selectColumns[] = DB::raw("{$statusExpr} as status");

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

            // ✅ Audit trail columns with TIME fields (CPS-compatible)
            $auditColumns = [
                'ORDER_MODE' => 'order_mode',
                'NW_UID' => 'issued_by',
                'NW_DATE' => 'issued_date',
                'NW_TIME' => 'issued_time',
                'AM_UID' => 'amended_by',
                'AM_DATE' => 'amended_date',
                'AM_TIME' => 'amended_time',
                'CX_UID' => 'cancelled_by',
                'CX_DATE' => 'cancelled_date',
                'CX_TIME' => 'cancelled_time',
                'PT_UID' => 'printed_by',
                'PT_DATE' => 'printed_date',
                'PT_TIME' => 'printed_time',
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

            $fakturNo = '';
            try {
                $fakturRow = DB::table('TAX')
                    ->where('INV_Num', $invoiceNo)
                    ->whereNotNull('No_Faktur')
                    ->where('No_Faktur', '!=', '')
                    ->orderByDesc('Tgl_Faktur')
                    ->first();

                $fakturNo = $fakturRow ? (string) ($fakturRow->No_Faktur ?? '') : '';
            } catch (\Exception $e) {
                Log::warning('Failed to check TAX faktur number (show)', [
                    'invoice_no' => $invoiceNo,
                    'error' => $e->getMessage(),
                ]);
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

            // Calculate payment term and due date (invoice date + AR_TERM days)
            $paymentTerm = (int) ($invoice->AR_TERM ?? 0);
            $dueDate = '';
            if (!empty($invoiceDate)) {
                try {
                    $due = \Carbon\Carbon::parse($invoiceDate);
                    if ($paymentTerm > 0) {
                        $due->addDays($paymentTerm);
                    }
                    $dueDate = $due->format('Y-m-d');
                } catch (\Exception $e) {
                    $dueDate = $invoiceDate;
                }
            }

            // Resolve DO number for printing (independent from second_ref text)
            $doNumberForPrint = $this->resolveDoNumberForPrint($invoice);

            $totalAmount = (float) DB::table('INV')
                ->where('IV_NUM', $invoiceNo)
                ->sum('IV_TRAN_AMT');
            if ($totalAmount == 0) {
                $totalAmount = (float)($invoice->IV_TRAN_AMT ?? 0);
            }

            $displayInvoice = (object) [
                'IV_TRAN_AMT' => $totalAmount,
                'IV_TAX_PERCENT' => $invoice->IV_TAX_PERCENT ?? 0,
                'IV_TAX_CODE' => $invoice->IV_TAX_CODE ?? '',
                'AC_NUM' => $invoice->AC_NUM ?? '',
            ];

            $taxAmount = $this->calculateTaxAmount($displayInvoice);
            $netAmount = $this->calculateNetAmount($displayInvoice);

            return response()->json([
                'invoice_no' => $invoice->IV_NUM ?? '',
                'customer_code' => $invoice->AC_NUM ?? '',
                'customer_name' => $invoice->AC_NAME ?? '',
                'order_mode' => $invoice->ORDER_MODE ?? '',
                'salesperson' => $invoice->SLM ?? '',
                'currency' => $invoice->CURR ?? 'IDR',
                'exchange_rate' => (float)($invoice->EX_RATE ?? 0),
                'payment_term' => $paymentTerm,
                'due_date' => $dueDate,
                'tax_invoice_no' => $invoice->TAX_INVOICE_NO ?? ($invoice->TaxInvoiceNo ?? ''),
                'faktur_no' => $fakturNo,
                'has_faktur' => !empty($fakturNo),
                // ✅ UI-only fields (not stored in DB)
                'exchange_method' => '1', // Default: Multiply (UI display only)
                'tax_index_no' => '', // UI display only, actual tax stored in IV_TAX_CODE
                // ✅ Actual tax data from database
                'tax_code' => $invoice->IV_TAX_CODE ?? '',
                'tax_percent' => (float)($invoice->IV_TAX_PERCENT ?? 0),
                'invoice_date' => $invoiceDate,
                // ✅ Map IV_SECOND_REF to ref2/second_ref (raw value from CPS)
                'ref2' => $invoice->IV_SECOND_REF ?? '',
                'second_ref' => $invoice->IV_SECOND_REF ?? '',
                'remark' => $invoice->IV_REMARK ?? '',
                'status' => $this->normalizeInvoiceStatus($invoice->IV_STS ?? null, $invoice->AM_UID ?? null),
                'total_amount' => $totalAmount,
                'tax_amount' => $taxAmount,
                'net_amount' => $netAmount,
                'quantity' => (float)($invoice->IV_QTY ?? 0),
                'unit_price' => (float)($invoice->IV_UNIT_PRICE ?? 0),
                'po_number' => $invoice->PO_NUM ?? '',
                'model' => $invoice->MODEL ?? '',
                // Related documents (for calculating total if needed)
                'so_number' => $invoice->SO_NUM ?? '',
                // DO number used for printing (resolved from DO table when possible)
                'do_number' => $doNumberForPrint,
                // ✅ Complete Audit trail (CPS-compatible with TIME fields)
                'issued_by' => $invoice->NW_UID ?? '',
                'issued_date' => $invoice->NW_DATE ?? '',
                'issued_time' => $invoice->NW_TIME ?? '',
                'amended_by' => $invoice->AM_UID ?? '',
                'amended_date' => $invoice->AM_DATE ?? '',
                'amended_time' => $invoice->AM_TIME ?? '',
                'cancelled_by' => $invoice->CX_UID ?? '',
                'cancelled_date' => $invoice->CX_DATE ?? '',
                'cancelled_time' => $invoice->CX_TIME ?? '',
                'printed_by' => $invoice->PT_UID ?? '',
                'printed_date' => $invoice->PT_DATE ?? '',
                'printed_time' => $invoice->PT_TIME ?? '',
                // Cancellation reasons (if applicable)
                'cancelled_reason_1' => $invoice->CANCELLED_REASON_1 ?? '',
                'cancelled_reason_2' => $invoice->CANCELLED_REASON_2 ?? '',
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
                    $totalAmount = $items->sum(function ($item) {
                        return (float)($item->AMOUNT ?? 0);
                    });
                }
            } catch (\Exception $e) {
                // INVDET table doesn't exist or error querying, use header total only
                Log::info('Cannot fetch items for invoice ' . $invoiceNo . ': ' . $e->getMessage());
            }

            // Fallback: if INVDET has no rows, build items from INV lines (Main + Fit)
            if ($items->isEmpty()) {
                $invLines = DB::table('INV')
                    ->where('IV_NUM', $invoiceNo)
                    ->orderBy('ITEM')
                    ->get();

                if ($invLines->count() > 0) {
                    $items = $invLines->map(function ($line) {
                        return (object) [
                            'ITEM_CODE' => $line->PRODUCT ?? $line->MCS_NUM ?? '',
                            'ITEM_DESC' => trim(($line->MODEL ?? '') . ' ' . ($line->P_DESIGN ?? '')),
                            'QTY' => $line->IV_QTY ?? 0,
                            'UNIT_PRICE' => $line->IV_UNIT_PRICE ?? 0,
                            'AMOUNT' => $line->IV_TRAN_AMT ?? 0,
                            'LINE_NO' => $line->ITEM ?? 0,
                            'COMP' => $line->COMP ?? '',
                            // PART: nama/part number seperti "BOX", "Box B", dll.
                            'PART' => $line->PART ?? '',
                        ];
                    });

                    // If header total is still 0, calculate from INV lines
                    if ($totalAmount == 0) {
                        $totalAmount = $items->sum(function ($item) {
                            return (float)($item->AMOUNT ?? 0);
                        });
                    }
                }
            }

            // If total still 0, try to use net amount
            if ($totalAmount == 0) {
                $totalAmount = (float)($invoice->IV_NET_AMT ?? 0);
            }

            $displayInvoice = (object) [
                'IV_TRAN_AMT' => $totalAmount,
                'IV_TAX_PERCENT' => $invoice->IV_TAX_PERCENT ?? 0,
                'IV_TAX_CODE' => $invoice->IV_TAX_CODE ?? '',
                'AC_NUM' => $invoice->AC_NUM ?? '',
            ];

            $taxAmount = $this->calculateTaxAmount($displayInvoice);
            $netAmount = $this->calculateNetAmount($displayInvoice);

            // Return invoice header + items (empty if table doesn't exist)
            return response()->json([
                'invoice_no' => $invoice->IV_NUM ?? '',
                'customer_code' => $invoice->AC_NUM ?? '',
                'customer_name' => $invoice->AC_NAME ?? '',
                'total_amount' => $totalAmount,
                'tax_amount' => $taxAmount,
                'net_amount' => $netAmount,
                'tax_code' => $invoice->IV_TAX_CODE ?? '',
                'items' => $items->map(function ($item) {
                    return [
                        'item_code' => $item->ITEM_CODE ?? '',
                        'item_desc' => $item->ITEM_DESC ?? '',
                        'qty' => (float)($item->QTY ?? 0),
                        'unit_price' => (float)($item->UNIT_PRICE ?? 0),
                        'amount' => (float)($item->AMOUNT ?? 0),
                        'line_no' => $item->LINE_NO ?? 0,
                        // Component type (Main / Fit) when built from INV lines
                        'comp' => isset($item->COMP) ? ($item->COMP ?? '') : '',
                        // Part name/number (BOX, Box B, etc.) when available
                        'part' => isset($item->PART) ? ($item->PART ?? '') : '',
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
                ->where(function ($q) {
                    $q->whereNull('IV_STS')
                      ->orWhereNotIn('IV_STS', ['Cancel', 'Cancelled']);
                })
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
     * Update print audit trail (Print Invoice - CPS Compatible)
     */
    public function updatePrintAudit(Request $request, $invoiceNo)
    {
        try {
            // Get existing invoice
            $invoice = DB::table('INV')
                ->where('IV_NUM', $invoiceNo)
                ->first();

            if (!$invoice) {
                return response()->json([
                    'error' => 'Invoice not found'
                ], 404);
            }

            // CPS Business Rules: Cannot update print audit for cancelled invoices
            if (in_array($invoice->IV_STS, ['Cancel', 'Cancelled'], true)) {
                return response()->json([
                    'error' => 'Cannot print cancelled invoice'
                ], 400);
            }

            // Prepare update data with print audit trail (WIB timezone)
            $nowWib = $this->getNowWib();
            $updateData = [
                'PT_UID' => $this->getCurrentUserId(),
                'PT_DATE' => $nowWib->format('d/m/Y'),
                'PT_TIME' => $nowWib->format('H:i'),
            ];

            // Perform update
            DB::beginTransaction();

            $updated = DB::table('INV')
                ->where('IV_NUM', $invoiceNo)
                ->update($updateData);

            DB::commit();

            Log::info('Invoice print audit updated successfully', [
                'invoice_no' => $invoiceNo,
                'printed_by' => $updateData['PT_UID'],
                'printed_at' => $updateData['PT_DATE'] . ' ' . $updateData['PT_TIME']
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Print audit updated successfully',
                'invoice_no' => $invoiceNo,
                'printed_by' => $updateData['PT_UID'],
                'printed_at' => $updateData['PT_DATE'] . ' ' . $updateData['PT_TIME']
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating print audit: ' . $e->getMessage(), [
                'invoice_no' => $invoiceNo,
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'error' => 'Failed to update print audit',
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
            // ✅ Log authentication state for debugging
            Log::info('🔍 Amend Invoice - Authentication check', [
                'invoice_no' => $invoiceNo,
                'Auth::check()' => Auth::check(),
                'Auth::id()' => Auth::id(),
                'Auth::user() exists' => Auth::user() !== null,
                'user_class' => Auth::user() ? get_class(Auth::user()) : null,
            ]);

            // ✅ Validate request - ONLY editable fields that are stored in DB (CPS rules)
            $validated = $request->validate([
                // Note: exchange_method and tax_index_no are UI-only fields, not validated/stored
                // Note: tax_amount and net_amount are calculated fields, not stored in table
                'tax_code' => 'nullable|string|max:50',
                'tax_percent' => 'nullable|numeric|min:0|max:100',
                'invoice_date' => 'nullable|string',
                'ref2' => 'nullable|string|max:50', // Will map to IV_SECOND_REF
                'remark' => 'nullable|string|max:250',
                'total_amount' => 'nullable|numeric|min:0',
                // tax_amount and net_amount are accepted but not stored (calculated on-the-fly)
                'tax_amount' => 'nullable|numeric|min:0',
                'net_amount' => 'nullable|numeric|min:0'
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

            try {
                $hasFaktur = DB::table('TAX')
                    ->where('INV_Num', $invoiceNo)
                    ->whereNotNull('No_Faktur')
                    ->where('No_Faktur', '!=', '')
                    ->exists();

                if ($hasFaktur) {
                    $fakturRow = DB::table('TAX')
                        ->where('INV_Num', $invoiceNo)
                        ->whereNotNull('No_Faktur')
                        ->where('No_Faktur', '!=', '')
                        ->orderByDesc('Tgl_Faktur')
                        ->first();

                    return response()->json([
                        'error' => 'Cannot amend invoice that already has Faktur Number',
                        'message' => 'Invoice already has Faktur Number: ' . ($fakturRow->No_Faktur ?? '-'),
                        'faktur_no' => $fakturRow->No_Faktur ?? null,
                    ], 400);
                }
            } catch (\Exception $e) {
                Log::warning('Failed to check TAX faktur number (update)', [
                    'invoice_no' => $invoiceNo,
                    'error' => $e->getMessage(),
                ]);
            }

            // CPS Business Rules: Check if invoice can be amended
            if (!empty($invoice->PT_UID)) {
                return response()->json([
                    'error' => 'Cannot amend invoice that has been printed',
                    'message' => 'Invoice was printed by ' . $invoice->PT_UID . ' on ' . $invoice->PT_DATE
                ], 400);
            }

            if (in_array($invoice->IV_STS, ['Cancel', 'Cancelled'], true)) {
                return response()->json([
                    'error' => 'Cannot amend cancelled invoice'
                ], 400);
            }

            // ✅ Get current user ID for audit trail with fallback
            $currentUserId = $this->getCurrentUserId();

            // ✅ Fallback: If getCurrentUserId() returns null, try Auth::id()
            if (empty($currentUserId)) {
                Log::warning('⚠️ getCurrentUserId() returned null, trying Auth::id() fallback', [
                    'invoice_no' => $invoiceNo,
                    'Auth::check()' => Auth::check(),
                    'Auth::id()' => Auth::id(),
                ]);

                if (Auth::check() && Auth::id()) {
                    $currentUserId = (string) Auth::id();
                    Log::info('✅ Using Auth::id() as fallback for AM_UID', ['user_id' => $currentUserId]);
                } else {
                    // Last resort: Use 'SYSTEM' if no user found (should not happen in production)
                    $currentUserId = 'SYSTEM';
                    Log::error('❌ No authenticated user found, using SYSTEM as AM_UID', [
                        'invoice_no' => $invoiceNo,
                        'warning' => 'This should not happen in production!'
                    ]);
                }
            }

            // Prepare update data with audit trail (WIB timezone)
            $nowWib = $this->getNowWib();

            Log::info('✅ AM_UID will be set to', [
                'invoice_no' => $invoiceNo,
                'AM_UID' => $currentUserId,
                'AM_DATE' => $nowWib->format('d/m/Y'),
                'AM_TIME' => $nowWib->format('H:i'),
            ]);

            $updateData = [
                'IV_STS' => 'Amend',
                'AM_UID' => $currentUserId, // ✅ Guaranteed to be non-null
                'AM_DATE' => $nowWib->format('d/m/Y'),
                'AM_TIME' => $nowWib->format('H:i'),
            ];

            // ✅ Update ONLY editable fields that exist in table (CPS-compatible)
            // Readonly fields: Current Period, Invoice#, Customer, Order Mode, Salesperson, Currency, Exchange Rate, Status
            // Editable fields: Invoice Date, 2nd Reference#, Remark, Tax Code, Tax Percent

            // Note: EXCHANGE_METHOD and TAX_INDEX_NO don't exist in table schema
            // These are UI-only fields, not stored in database

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
                // ✅ Correct column name is IV_SECOND_REF, not REF2
                $updateData['IV_SECOND_REF'] = $validated['ref2'];
            }
            if (isset($validated['remark'])) {
                $updateData['IV_REMARK'] = $validated['remark'];
            }

            // ✅ Update amounts - ONLY fields that exist in table
            // Note: IV_TAX_AMT and IV_NET_AMT don't exist in table schema
            // These are calculated fields, not stored in database

            if (isset($validated['total_amount'])) {
                $updateData['IV_TRAN_AMT'] = $validated['total_amount'];
            }

            // ✅ CPS Logic: If tax code/percent changed, log calculation (but don't store)
            // Tax amount and net amount are calculated on-the-fly, not stored
            if (isset($validated['tax_code']) || isset($validated['tax_percent'])) {
                $tranAmount = $invoice->IV_TRAN_AMT ?? ($validated['total_amount'] ?? 0);
                $taxPercent = $validated['tax_percent'] ?? $invoice->IV_TAX_PERCENT ?? 0;

                if ($tranAmount > 0 && $taxPercent > 0) {
                    $taxAmount = round($tranAmount * ($taxPercent / 100), 2);
                    $netAmount = $tranAmount + $taxAmount;

                    // ✅ Log calculation for debugging (not stored in DB)
                    Log::info('✅ Recalculated tax amounts on amend (not stored)', [
                        'invoice_no' => $invoiceNo,
                        'tran_amount' => $tranAmount,
                        'tax_percent' => $taxPercent,
                        'calculated_tax_amount' => $taxAmount,
                        'calculated_net_amount' => $netAmount,
                        'note' => 'IV_TAX_AMT and IV_NET_AMT are calculated fields, not stored in table'
                    ]);
                }
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

    /**
     * Resolve tax information from frontend or database
     *
     * @param string|null $taxCodeFromFrontend
     * @param float|null $taxPercentFromFrontend
     * @param string|null $taxIndexNo
     * @return array{taxCode: string|null, taxPercent: float|null}
     */
    private function resolveTaxInformation(?string $taxCodeFromFrontend, ?float $taxPercentFromFrontend, ?string $taxIndexNo): array
    {
        /** @var string|null $taxCode */
        $taxCode = $taxCodeFromFrontend;
        /** @var float|null $taxPercent */
        $taxPercent = $taxPercentFromFrontend;

        if ($taxCode && $taxPercent) {
            Log::info('✅ Using tax data from Final Screen (user confirmed)', [
                'tax_code' => $taxCode,
                'tax_percent' => $taxPercent,
                'source' => 'frontend_final_screen'
            ]);
            return ['taxCode' => $taxCode, 'taxPercent' => $taxPercent];
        }

        if ($taxIndexNo) {
            Log::info('⚠️ Tax not provided from frontend, looking up in database', [
                'tax_index_no' => $taxIndexNo
            ]);

            // Try taxrate table first
            try {
                $tax = DB::table('taxrate')->where('TAXCODE', $taxIndexNo)->first();
                if ($tax) {
                    $taxCode = $tax->TAXCODE;
                    $taxPercent = $tax->RATEPPN;
                    Log::info('Tax found in taxrate table', [
                        'code' => $taxCode,
                        'rate' => $taxPercent,
                        'source' => 'taxrate_table'
                    ]);
                    return ['taxCode' => $taxCode, 'taxPercent' => $taxPercent];
                }
            } catch (\Exception $e) {
                Log::warning('taxrate table not accessible: ' . $e->getMessage());
            }

            // Fallback to Sales_Tax table
            try {
                $tax = DB::table('Sales_Tax')->where('tax_code', $taxIndexNo)->first();
                if ($tax) {
                    $taxCode = $tax->tax_code;
                    $taxPercent = $tax->tax_rate;
                    Log::info('Tax found in Sales_Tax table', [
                        'code' => $taxCode,
                        'rate' => $taxPercent,
                        'source' => 'sales_tax_table'
                    ]);
                    return ['taxCode' => $taxCode, 'taxPercent' => $taxPercent];
                }
            } catch (\Exception $e) {
                Log::warning('Sales_Tax table not accessible: ' . $e->getMessage());
            }

            Log::warning('❌ Tax code not found in any table', [
                'tax_index_no' => $taxIndexNo,
                'searched_tables' => ['taxrate', 'Sales_Tax']
            ]);
        } else {
            Log::info('ℹ️ No tax information provided (tax-free invoice)');
        }

        return ['taxCode' => null, 'taxPercent' => null];
    }

    /**
     * Get customer payment term from database
     *
     * @param string|null $customerCode
     * @return int
     */
    private function getCustomerPaymentTerm(?string $customerCode): int
    {
        /** @var int $paymentTerm */
        $paymentTerm = 30; // Default: 30 days

        if (!$customerCode) {
            return $paymentTerm;
        }

        try {
            $customer = DB::table('CUSTOMER')->where('CODE', $customerCode)->first();

            if ($customer && isset($customer->TERM) && $customer->TERM > 0) {
                $paymentTerm = (int) round($customer->TERM);

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

        Log::info('💰 Final payment term for invoice', [
            'customer' => $customerCode,
            'payment_term' => $paymentTerm,
            'type' => gettype($paymentTerm)
        ]);

        return $paymentTerm;
    }

    /**
     * Generate or validate invoice number
     *
     * @param string $mode
     * @param string|null $manualNumber
     * @param callable $generator
     * @param int $seq
     * @return string
     */
    private function generateInvoiceNumber(string $mode, ?string $manualNumber, callable $generator, int &$seq): string
    {
        if ($mode === 'manual' && $manualNumber) {
            $exists = DB::table('INV')->where('IV_NUM', $manualNumber)->exists();
            if ($exists) {
                throw new \RuntimeException("Invoice number '{$manualNumber}' already exists. Please choose a different number.");
            }
            Log::info('Using manual invoice number', ['number' => $manualNumber]);
            return $manualNumber;
        }

        // Auto-generate
        /** @var string $ivNum */
        $ivNum = $generator($seq++);

        while (DB::table('INV')->where('IV_NUM', $ivNum)->exists()) {
            $ivNum = $generator($seq++);
        }

        Log::info('Generated auto invoice number', ['number' => $ivNum]);
        return $ivNum;
    }

    /**
     * Calculate invoice amounts
     *
     * @param \stdClass $do
     * @param float|null $taxPercent
     * @return array{tranAmount: float, baseAmount: float, taxAmount: float, netAmount: float}
     */
    private function calculateInvoiceAmounts(\stdClass $do, ?float $taxPercent): array
    {
        /** @var float $tranAmount */
        $tranAmount = $this->toDecimalOrNull($this->getProperty($do, 'DO_Tran_Amt'), 0);
        /** @var float $baseAmount */
        $baseAmount = $this->toDecimalOrNull($this->getProperty($do, 'DO_Base_Amt'), 0);

        // Calculate from DO_Qty * Unit_Price if DO_Tran_Amt is 0
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

        /** @var float $taxAmount */
        $taxAmount = 0;
        if ($taxPercent && $tranAmount > 0) {
            $taxAmount = round($tranAmount * ($taxPercent / 100), 2);
        }

        /** @var float $netAmount */
        $netAmount = $tranAmount + $taxAmount;

        return [
            'tranAmount' => $tranAmount,
            'baseAmount' => $baseAmount,
            'taxAmount' => $taxAmount,
            'netAmount' => $netAmount
        ];
    }

    /**
     * Get invoices for tax export (Coretax XML generation)
     * Fetches invoices with customer details including NPWP and address
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getInvoicesForTaxExport(Request $request)
    {
        try {
            $month = $request->input('month');
            $year = $request->input('year');
            $customerCode = $request->input('customer_code');
            $status = $request->input('status');

            Log::info('Tax Export Invoice Request', [
                'month' => $month,
                'year' => $year,
                'customer_code' => $customerCode,
                'status' => $status
            ]);

            // Build query with customer join
            $query = DB::table('INV')
                ->leftJoin('CUSTOMER', 'INV.AC_NUM', '=', 'CUSTOMER.CODE')
                ->select([
                    'INV.*',
                    'CUSTOMER.NPWP',
                    'CUSTOMER.ADDRESS1',
                    'CUSTOMER.ADDRESS2',
                    'CUSTOMER.ADDRESS3',
                    'CUSTOMER.EMAIL',
                    'CUSTOMER.TEL_NO',
                    'CUSTOMER.FAX_NO'
                ]);

            // Apply filters
            if ($month) {
                $query->where('INV.MM', str_pad($month, 2, '0', STR_PAD_LEFT));
            }

            if ($year) {
                $query->where('INV.YYYY', $year);
            }

            if ($customerCode) {
                $query->where('INV.AC_NUM', $customerCode);
            }

            if ($status) {
                if ($status === 'Cancel') {
                    $query->whereIn('INV.IV_STS', ['Cancel', 'Cancelled']);
                } elseif ($status === 'Amend') {
                    $query->where(function ($q) {
                        $q->where('INV.IV_STS', 'Amend')
                          ->orWhere(function ($q2) {
                              $q2->whereNull('INV.IV_STS')
                                 ->whereNotNull('INV.AM_UID')
                                 ->where('INV.AM_UID', '!=', '');
                          });
                    });
                } elseif ($status === 'New') {
                    $query->where(function ($q) {
                        $q->whereNull('INV.IV_STS')
                          ->orWhereNotIn('INV.IV_STS', ['Cancel', 'Cancelled', 'Amend']);
                    });
                    $query->where(function ($q) {
                        $q->whereNull('INV.AM_UID')
                          ->orWhere('INV.AM_UID', '=', '');
                    });
                } else {
                    $query->where('INV.IV_STS', $status);
                }
            }

            // Get invoices
            $invoices = $query->orderBy('INV.IV_NUM', 'DESC')->get();

            // Transform data for frontend
            $invoices = $invoices->map(function ($invoice) {
                return [
                    'IV_NUM' => $invoice->IV_NUM,
                    'YYYY' => $invoice->YYYY,
                    'MM' => $invoice->MM,
                    'IV_DMY' => $invoice->IV_DMY,
                    'IV_STS' => $invoice->IV_STS,
                    'AC_NUM' => $invoice->AC_NUM,
                    'AC_NAME' => $invoice->AC_NAME,
                    'IV_SECOND_REF' => $invoice->IV_SECOND_REF,
                    'SO_NUM' => $invoice->SO_NUM,
                    'PO_NUM' => $invoice->PO_NUM,
                    'MODEL' => $invoice->MODEL,
                    'PRODUCT' => $invoice->PRODUCT,
                    'UNIT' => $invoice->UNIT,
                    'IV_QTY' => $invoice->IV_QTY,
                    'IV_UNIT_PRICE' => $invoice->IV_UNIT_PRICE,
                    'IV_TRAN_AMT' => $invoice->IV_TRAN_AMT,
                    'IV_BASE_AMT' => $invoice->IV_BASE_AMT,
                    'IV_TAX_CODE' => $invoice->IV_TAX_CODE,
                    'IV_TAX_PERCENT' => $invoice->IV_TAX_PERCENT,
                    'IV_REMARK' => $invoice->IV_REMARK,
                    'CURR' => $invoice->CURR,
                    'EX_RATE' => $invoice->EX_RATE,
                    'customer' => [
                        'NPWP' => $invoice->NPWP,
                        'ADDRESS1' => $invoice->ADDRESS1,
                        'ADDRESS2' => $invoice->ADDRESS2,
                        'ADDRESS3' => $invoice->ADDRESS3,
                        'EMAIL' => $invoice->EMAIL,
                        'TEL_NO' => $invoice->TEL_NO,
                        'FAX_NO' => $invoice->FAX_NO,
                    ]
                ];
            });

            Log::info('Tax Export Invoices Retrieved', [
                'count' => $invoices->count()
            ]);

            return response()->json([
                'success' => true,
                'data' => $invoices
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching invoices for tax export: ' . $e->getMessage(), [
                'exception' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Failed to fetch invoices for tax export',
                'message' => $e->getMessage()
            ], 500);
        }
    }

}
