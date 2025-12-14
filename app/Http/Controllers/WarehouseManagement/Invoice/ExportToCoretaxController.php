<?php

namespace App\Http\Controllers\WarehouseManagement\Invoice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ExportToCoretaxController extends Controller
{
    /**
     * Get invoices for tax export based on filters
     * Data structure matches the SQL query provided
     */
    public function getInvoicesForExport(Request $request)
    {
        try {
            $month = $request->input('month');
            $year = $request->input('year');
            $customerCode = $request->input('customer_code');
            $status = $request->input('status');

            $query = DB::table('INV')
                ->leftJoin('CUSTOMER', 'INV.AC_NUM', '=', 'CUSTOMER.CODE')
                ->select(
                    // 1. Tanggal Faktur
                    'INV.IV_DMY as FK_TANGGAL_FAKTUR',
                    
                    // 2. Kode Transaksi - Default '10' untuk Kawasan Berikat/KITE
                    // MBI memiliki fasilitas KITE, jadi semua penyerahan menggunakan kode 10
                    DB::raw("'10' AS FK_KD_JENIS_TRANSAKSI"),
                    
                    // 3. Referensi Faktur (Menambah Prefix 'IV/')
                    DB::raw("'IV/' + INV.IV_NUM AS FK_REFERENSI"),
                    
                    // 4. Dokumen Pendukung (Mapping ke XML Coretax)
                    'INV.IV_SECOND_REF',
                    
                    // 5. Nama Customer
                    'CUSTOMER.NAME AS FK_NAMA',
                    
                    // 6. Logika Penyusunan Alamat (Membersihkan '00000')
                    DB::raw("CUSTOMER.ADDRESS1 + ' ' + CUSTOMER.ADDRESS2 + ' ' + 
                        CASE 
                            WHEN CUSTOMER.ADDRESS3 = '00000' THEN '' 
                            ELSE CUSTOMER.ADDRESS3 
                        END AS FK_ALAMAT_LENGKAP"),
                    
                    // 7. Data Identitas (NPWP & Tipe)
                    'CUSTOMER.NPWP AS FK_NPWP',
                    'CUSTOMER.CUST_TYPE',
                    
                    // Additional fields for XML generation
                    'INV.IV_NUM',
                    'INV.AC_NUM',
                    'INV.AC_NAME',
                    'INV.IV_TRAN_AMT',
                    'INV.IV_TAX_CODE',
                    'INV.IV_TAX_PERCENT',
                    'INV.IV_QTY',
                    'INV.IV_UNIT_PRICE',
                    'INV.UNIT',
                    'INV.MODEL',
                    'INV.PRODUCT',
                    'INV.IV_STS',
                    'INV.YYYY',
                    'INV.MM',
                    'INV.COMP',
                    'CUSTOMER.EMAIL'
                )
                ->whereNotNull('INV.IV_NUM')
                ->where('INV.IV_STS', '<>', 'CX') // Abaikan faktur yang Cancel (CX)
                ->where(function($q) {
                    // Hanya tampilkan Main component di list
                    $q->where('INV.COMP', '=', 'Main')
                      ->orWhereNull('INV.COMP')
                      ->orWhere('INV.COMP', '=', '');
                });

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
                $query->where('INV.IV_STS', $status);
            }

            $invoices = $query->orderBy('FK_REFERENSI')->get();

            // Add row numbers
            $invoices = $invoices->map(function ($invoice, $index) {
                $invoice->id = $index + 1;
                return $invoice;
            });

            return response()->json([
                'success' => true,
                'data' => $invoices
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching invoices for export', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch invoices: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate XML for selected invoices
     * Follows the PHP logic provided by user
     */
    public function generateXml(Request $request)
    {
        try {
            $invoiceNums = $request->input('invoice_numbers', []);
            
            if (empty($invoiceNums)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No invoices selected'
                ], 400);
            }

            // Fetch invoices with customer data (Main + Fit components)
            $invoices = DB::table('INV')
                ->leftJoin('CUSTOMER', 'INV.AC_NUM', '=', 'CUSTOMER.CODE')
                ->select(
                    'INV.*',
                    'CUSTOMER.NAME AS FK_NAMA',
                    'CUSTOMER.NPWP AS FK_NPWP',
                    'CUSTOMER.CUST_TYPE',
                    'CUSTOMER.EMAIL',
                    DB::raw("CUSTOMER.ADDRESS1 + ' ' + CUSTOMER.ADDRESS2 + ' ' + 
                        CASE 
                            WHEN CUSTOMER.ADDRESS3 = '00000' THEN '' 
                            ELSE CUSTOMER.ADDRESS3 
                        END AS FK_ALAMAT_LENGKAP"),
                    DB::raw("'10' AS FK_KD_JENIS_TRANSAKSI"),
                    DB::raw("'IV/' + INV.IV_NUM AS FK_REFERENSI")
                )
                ->whereIn('INV.IV_NUM', $invoiceNums)
                ->where('INV.IV_STS', '<>', 'CX')
                ->orderBy('INV.IV_DMY')
                ->orderBy('INV.IV_NUM')
                ->orderByRaw("CASE WHEN INV.COMP = 'Main' OR INV.COMP IS NULL OR INV.COMP = '' THEN 0 ELSE 1 END") // Main dulu, Fit kemudian
                ->get();

            if ($invoices->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No valid invoices found'
                ], 404);
            }

            // Group invoices by IV_NUM (untuk handle Main + Fit)
            $groupedInvoices = $invoices->groupBy('IV_NUM');
            
            // Generate XML
            $xml = $this->buildXml($groupedInvoices);

            return response()->json([
                'success' => true,
                'data' => [
                    'xml' => $xml,
                    'filename' => 'TaxInvoice_' . date('YmdHis') . '.xml',
                    'invoice_count' => $groupedInvoices->count()
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error generating XML', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to generate XML: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Convert unit name to UM code
     */
    private function getUnitCode($unitName)
    {
        $unitName = strtoupper(trim($unitName));
        
        // Extract first part if contains multiple units (e.g., "P-Piece,S-Set" -> "P-Piece")
        if (strpos($unitName, ',') !== false) {
            $parts = explode(',', $unitName);
            $unitName = trim($parts[0]);
        }
        
        // Remove prefix (e.g., "P-Piece" -> "Piece")
        if (strpos($unitName, '-') !== false) {
            $parts = explode('-', $unitName, 2);
            $unitName = trim($parts[1]);
        }
        
        // Mapping unit to UM code
        $unitMap = [
            'KG' => 'UM.0003',
            'KILOGRAM' => 'UM.0003',
            'SET' => 'UM.0019',
            'ROLL' => 'UM.0039',
            'SHEET' => 'UM.0020',
            'PCS' => 'UM.0021',
            'PIECE' => 'UM.0021',
            'PC' => 'UM.0021',
        ];
        
        return $unitMap[$unitName] ?? 'UM.0021'; // Default to Piece
    }
    
    /**
     * Build XML structure based on PHP logic provided
     * $groupedInvoices is grouped by IV_NUM (Main + Fit per invoice)
     */
    private function buildXml($groupedInvoices)
    {
        $xml = new \XMLWriter();
        $xml->openMemory();
        $xml->setIndent(true);
        $xml->setIndentString('  ');
        
        $xml->startDocument('1.0', 'UTF-8');
        
        // Root element
        $xml->startElement('TaxInvoiceBulk');
        
        // TIN - NPWP MBI
        $xml->writeElement('TIN', '0014952246415000');
        
        // List of Tax Invoices
        $xml->startElement('ListOfTaxInvoice');
        
        foreach ($groupedInvoices as $invoiceNum => $invoiceLines) {
            // Ambil data utama dari baris pertama (biasanya Main)
            $row = $invoiceLines->first();
            $baris = $row->id ?? 1;
            $cust_type = $row->CUST_TYPE;
            $tanggalfaktur = $row->IV_DMY;
            $jenisfaktur = 'Normal';
            
            // Kawasan Berikat/KITE - Fasilitas Tidak Dipungut PPN
            // MBI memiliki fasilitas KITE untuk semua penyerahan
            $kodetransaksi = '10'; // Penyerahan dari Kawasan Berikat
            $keterangantambahan = 'TD.00502'; // Kode fasilitas tidak dipungut
            $capfasilitas = 'TD.01101'; // Cap fasilitas
            $dokumenpendukung = $row->IV_SECOND_REF ?? ''; // Dokumen pendukung (jika ada)
            
            // Referensi
            $referensi = '';
            if (!empty($row->FK_REFERENSI)) {
                $referensi = substr($row->FK_REFERENSI, 0, 16);
            }
            
            // Seller & Buyer IDs
            $idtkupenjual = '0014952246415000000000'; //NPWP MBI + 000000
            $npwppembeli = $row->FK_NPWP ?? '';
            
            // Jenis ID Pembeli (Logika PT vs Perorangan)
            if ($cust_type == 'PT' || empty($cust_type)) {
                // Badan Usaha (PT)
                $jenisidpembeli = 'TIN';
                $nodokpembeli = '-';
                $idtkupembeli = ($row->FK_NPWP ?? '') . '000000';
                // $npwppembeli tetap dari FK_NPWP (sudah diset di atas)
            } else {
                // Orang Pribadi (Perorangan)
                $jenisidpembeli = 'National ID';
                $nodokpembeli = $npwppembeli; // NPWP asli (NIK disimpan di kolom NPWP)
                $npwppembeli = '0000000000000000'; // NPWP di-nol-kan jika pakai NIK
                $idtkupembeli = '000000'; // IDTKU di-nol-kan untuk perorangan
            }
            
            $namapembeli = str_replace(
                ["PT ", "PT. ", ", PT"], 
                "", 
                $row->FK_NAMA ?? $row->AC_NAME ?? ''
            );
            $alamatpembeli = $row->FK_ALAMAT_LENGKAP ?? '';
            
            // Start Tax Invoice
            $xml->startElement('TaxInvoice');
            
            // Invoice Header
            // Format tanggal ke yyyy-mm-dd
            $formattedDate = $this->formatDateToYmd($tanggalfaktur);
            $xml->writeElement('TaxInvoiceDate', $formattedDate);
            $xml->writeElement('TaxInvoiceOpt', 'Normal'); // Harus string "Normal"
            $xml->writeElement('TrxCode', $kodetransaksi);
            $xml->writeElement('AddInfo', $keterangantambahan);
            $xml->writeElement('CustomDoc', $dokumenpendukung);
            $xml->writeElement('CustomDocMonthYear', '');
            $xml->writeElement('RefDesc', $referensi);
            $xml->writeElement('FacilityStamp', $capfasilitas);
            
            // Seller Info
            $xml->writeElement('SellerIDTKU', $idtkupenjual);
            
            // Buyer Info
            $xml->writeElement('BuyerTin', $npwppembeli);
            $xml->writeElement('BuyerDocument', $jenisidpembeli);
            $xml->writeElement('BuyerCountry', 'IDN'); // Indonesia
            $xml->writeElement('BuyerDocumentNumber', $nodokpembeli);
            $xml->writeElement('BuyerName', $namapembeli);
            $xml->writeElement('BuyerAdress', $alamatpembeli);
            $xml->writeElement('BuyerEmail', $row->EMAIL ?? '');
            $xml->writeElement('BuyerIDTKU', $idtkupembeli);
            
            // List of Goods/Services (Main + Fit)
            $xml->startElement('ListOfGoodService');
            
            // Loop semua line items (Main + Fit)
            foreach ($invoiceLines as $line) {
                $amount = (float)($line->IV_TRAN_AMT ?? 0);
                $qty = (float)($line->IV_QTY ?? 1);
                $price = (float)($line->IV_UNIT_PRICE ?? 0);
                
                if ($price == 0 && $qty > 0 && $amount > 0) {
                    $price = $amount / $qty;
                }
                
                $vatRate = (float)($line->IV_TAX_PERCENT ?? 0);
                $vat = $amount * ($vatRate / 100);
                
                // OtherTaxBase = 11/12 * TaxBase
                $otherTaxBase = $amount * (11 / 12);
                
                // Unit code mapping
                $unitCode = $this->getUnitCode($line->UNIT ?? 'PCS');
                
                $xml->startElement('GoodService');
                $xml->writeElement('Opt', 'A'); // A = Barang, B = Jasa
                $xml->writeElement('Code', $line->MCS_NUM ?? ''); // Dari MCS_NUM bukan PRODUCT
                $xml->writeElement('Name', $line->MODEL ?? '');
                $xml->writeElement('Unit', $unitCode); // Kode UM.xxxx
                $xml->writeElement('Price', number_format($price, 0, '.', '')); // Tanpa desimal
                $xml->writeElement('Qty', (int)$qty);
                $xml->writeElement('TotalDiscount', '0');
                $xml->writeElement('TaxBase', number_format($amount, 2, '.', ''));
                $xml->writeElement('OtherTaxBase', number_format($otherTaxBase, 2, '.', '')); // 11/12 * TaxBase
                $xml->writeElement('VATRate', (int)$vatRate);
                $xml->writeElement('VAT', number_format($vat, 2, '.', ''));
                $xml->writeElement('STLGRate', '0');
                $xml->writeElement('STLG', '0');
                $xml->endElement(); // GoodService
            }
            
            $xml->endElement(); // ListOfGoodService
            $xml->endElement(); // TaxInvoice
        }
        
        $xml->endElement(); // ListOfTaxInvoice
        $xml->endElement(); // TaxInvoiceBulk
        
        return $xml->outputMemory();
    }
    
    /**
     * Format date to yyyy-mm-dd
     */
    private function formatDateToYmd($date)
    {
        if (empty($date)) {
            return date('Y-m-d');
        }
        
        // Jika sudah format yyyy-mm-dd
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
            return $date;
        }
        
        // Jika format dd/mm/yyyy (dari CPS)
        if (preg_match('/^(\d{2})\/(\d{2})\/(\d{4})$/', $date, $matches)) {
            return $matches[3] . '-' . $matches[2] . '-' . $matches[1];
        }
        
        // Try to parse using strtotime
        $timestamp = strtotime($date);
        if ($timestamp !== false) {
            return date('Y-m-d', $timestamp);
        }
        
        return date('Y-m-d');
    }
}
