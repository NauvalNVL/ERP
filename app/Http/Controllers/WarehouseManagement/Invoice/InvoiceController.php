<?php

namespace App\Http\Controllers\WarehouseManagement\Invoice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Invoice;

class InvoiceController extends Controller
{
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

            return response()->json($dos);
        } catch (\Exception $e) {
            \Log::error('Error fetching current period DOs: ' . $e->getMessage());
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
        $customerCode = $request->input('customer_code');
        
        if (!$customerCode) {
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
            $customer = null;
            
            // Try different table names (SQL Server uses dbo.CUSTOMER)
            $tables = ['CUSTOMER', 'Customer_Account', 'customer_account', 'update_customer_accounts'];
            
            foreach ($tables as $table) {
                try {
                    $customer = DB::table($table)
                        ->where(function($query) use ($customerCode) {
                            $query->where('CODE', $customerCode)
                                  ->orWhere('customer_code', $customerCode)
                                  ->orWhere('Customer_Code', $customerCode);
                        })
                        ->first();
                    
                    if ($customer) {
                        break;
                    }
                } catch (\Exception $e) {
                    // Table doesn't exist, try next
                    continue;
                }
            }

            // If customer not found, return minimal data without overriding currency
            if (!$customer) {
                \Log::info("Customer not found in database: {$customerCode}, returning minimal data");
                return response()->json([
                    'customer_code' => $customerCode,
                    'customer_name' => '',
                    'currency' => null, // Don't override with IDR default
                    'tax_index_no' => '',
                    'salesperson' => '',
                    'area' => '',
                ]);
            }

            // Get customer's currency and tax information with multiple fallbacks
            // SQL Server columns: CODE, NAME, CURRENCY, SLM (salesperson), AREA
            $currency = $customer->CURRENCY ?? $customer->currency ?? $customer->Currency ?? $customer->currency_code ?? 'IDR';
            
            return response()->json([
                'customer_code' => $customer->CODE ?? $customer->customer_code ?? $customer->Customer_Code ?? '',
                'customer_name' => $customer->NAME ?? $customer->customer_name ?? $customer->Customer_Name ?? '',
                'currency' => $currency,
                'tax_index_no' => $customer->tax_index_no ?? $customer->Tax_Index_No ?? '',
                'salesperson' => $customer->SLM ?? $customer->salesperson ?? $customer->Salesperson ?? $customer->salesperson_code ?? '',
                'area' => $customer->AREA ?? $customer->area ?? $customer->Area ?? $customer->geographical ?? '',
            ]);
        } catch (\Exception $e) {
            \Log::error('Error fetching customer details: ' . $e->getMessage());
            
            // Return minimal values on error without overriding currency
            return response()->json([
                'customer_code' => $customerCode,
                'customer_name' => '',
                'currency' => null, // Don't override with IDR default
                'tax_index_no' => '',
                'salesperson' => '',
                'area' => '',
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
     * Get DO items details (for Sales Order Items Screen)
     */
    public function getDoItems(Request $request)
    {
        $doNumber = $request->input('do_number');
        
        if (!$doNumber) {
            return response()->json(['error' => 'DO number is required'], 400);
        }

        $items = DB::table('DO')
            ->where('DO_Num', $doNumber)
            ->select([
                'No as item_no',
                'Product as product',
                'Model as model',
                'MCS_Num as mcs_num',
                'DO_Qty as quantity',
                'SO_Unit_Price as unit_price',
                'DO_Tran_Amt as amount',
                'Unit as unit',
            ])
            ->get();

        return response()->json($items);
    }

}
