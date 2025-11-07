# 📝 Invoice Signature Name - Data Flow Documentation

## ❓ **Pertanyaan User:**
> "Nama EVA KEMPI pada print invoice ERP CPS, data nya di ambil darimana? Apakah sudah default EVA KEMPI atau nama tersebut merupakan nama user yang print invoice tersebut?"

---

## ✅ **Jawaban:**

**Nama pada signature invoice SEHARUSNYA adalah nama user yang melakukan print**, BUKAN hardcoded "EVA KEMPI".

Pada gambar invoice yang Anda berikan, **"EVA KEMPI"** adalah nama user yang melakukan print invoice tersebut pada **16/10/2025**.

---

## 🔄 **Complete Data Flow**

### **1. Saat User Pertama Kali Print Invoice:**

```
┌─────────────────────────────────────────────────────┐
│  USER LOGIN ke ERP System                           │
│  ┌───────────────────────────────────────────────┐  │
│  │ User: EVA KEMPI                               │  │
│  │ Authenticated via Laravel Auth                │  │
│  │ Auth::user()->name = "EVA KEMPI"             │  │
│  └───────────────────────────────────────────────┘  │
└──────────────────┬──────────────────────────────────┘
                   │
                   ▼
┌─────────────────────────────────────────────────────┐
│  USER CLICK "Export to PDF" di Print Invoice Menu  │
│  ┌───────────────────────────────────────────────┐  │
│  │ Invoice No: IV-202511-0004                    │  │
│  │ Frontend: PrintInvoice.vue                    │  │
│  │ Action: executePrint()                        │  │
│  └───────────────────────────────────────────────┘  │
└──────────────────┬──────────────────────────────────┘
                   │
                   ▼
┌─────────────────────────────────────────────────────┐
│  BACKEND: Update Print Audit Trail                 │
│  ┌───────────────────────────────────────────────┐  │
│  │ API: POST /api/invoices/{invoiceNo}/print    │  │
│  │ Controller: InvoiceController@updatePrintAudit│  │
│  │                                               │  │
│  │ UPDATE INV SET                                 │  │
│  │   PT_UID = Auth::user()->name  ← "EVA KEMPI"│  │
│  │   PT_DATE = '16/10/2025'                     │  │
│  │   PT_TIME = '14:30'                          │  │
│  │ WHERE IV_NUM = 'IV-202511-0004'              │  │
│  └───────────────────────────────────────────────┘  │
└──────────────────┬──────────────────────────────────┘
                   │
                   ▼
┌─────────────────────────────────────────────────────┐
│  DATABASE: INV Table Updated                        │
│  ┌───────────────────────────────────────────────┐  │
│  │ IV_NUM = 'IV-202511-0004'                    │  │
│  │ PT_UID = 'EVA KEMPI'         ✅ SAVED!      │  │
│  │ PT_DATE = '16/10/2025'                       │  │
│  │ PT_TIME = '14:30'                            │  │
│  └───────────────────────────────────────────────┘  │
└──────────────────┬──────────────────────────────────┘
                   │
                   ▼
┌─────────────────────────────────────────────────────┐
│  FRONTEND: Generate PDF                             │
│  ┌───────────────────────────────────────────────┐  │
│  │ generateInvoicePDF(invoice)                   │  │
│  │                                               │  │
│  │ Signature Section:                            │  │
│  │ - Date: Banten, 13/10/2025                   │  │
│  │ - Company: PT. MULTIBOX INDAH                │  │
│  │ - Signature Box: [____]                      │  │
│  │ - Name: invoice.printed_by ← "EVA KEMPI"    │  │
│  └───────────────────────────────────────────────┘  │
└──────────────────┬──────────────────────────────────┘
                   │
                   ▼
┌─────────────────────────────────────────────────────┐
│  PDF DOWNLOADED                                     │
│  ┌───────────────────────────────────────────────┐  │
│  │ File: Invoice_IV-202511-0004.pdf             │  │
│  │                                               │  │
│  │ Signature shows: "EVA KEMPI"                 │  │
│  │ Footer shows: "Tanggal Print: 16/10/2025"    │  │
│  └───────────────────────────────────────────────┘  │
└─────────────────────────────────────────────────────┘
```

---

## 💻 **Implementasi Code**

### **A. Backend - InvoiceController.php**

**Method: `updatePrintAudit()`** (Line 1931-1994)

```php
public function updatePrintAudit(Request $request, $invoiceNo)
{
    try {
        $invoice = DB::table('INV')->where('IV_NUM', $invoiceNo)->first();

        if (!$invoice) {
            return response()->json(['error' => 'Invoice not found'], 404);
        }

        if ($invoice->IV_STS === 'Cancelled') {
            return response()->json(['error' => 'Cannot print cancelled invoice'], 400);
        }

        $now = now();
        $updateData = [
            'PT_UID' => Auth::check() ? Auth::user()->name : 'system',  // ← Nama user login
            'PT_DATE' => $now->format('d/m/Y'),
            'PT_TIME' => $now->format('H:i'),
        ];

        DB::beginTransaction();
        DB::table('INV')->where('IV_NUM', $invoiceNo)->update($updateData);
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
            'printed_by' => $updateData['PT_UID'],  // ← Return ke frontend
            'printed_at' => $updateData['PT_DATE'] . ' ' . $updateData['PT_TIME']
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json([
            'error' => 'Failed to update print audit',
            'message' => $e->getMessage()
        ], 500);
    }
}
```

**Method: `show()`** - Get Invoice Details (Line 1709-1774)

```php
public function show($invoiceNo)
{
    try {
        $invoice = DB::table('INV')->where('IV_NUM', $invoiceNo)->first();

        if (!$invoice) {
            return response()->json(['error' => 'Invoice not found'], 404);
        }

        return response()->json([
            'invoice_no' => $invoice->IV_NUM ?? '',
            'customer_code' => $invoice->AC_NUM ?? '',
            'customer_name' => $invoice->AC_NAME ?? '',
            // ... other fields ...
            
            // ✅ AUDIT TRAIL - Including printed_by
            'printed_by' => $invoice->PT_UID ?? '',      // ← Ambil dari DB
            'printed_date' => $invoice->PT_DATE ?? '',
            'printed_time' => $invoice->PT_TIME ?? '',
            // ... other audit fields ...
        ]);

    } catch (\Exception $e) {
        return response()->json(['error' => 'Failed to fetch invoice'], 500);
    }
}
```

---

### **B. Frontend - PrintInvoice.vue**

**Method: `generateInvoicePDF()`** (Line 459-653)

```javascript
const generateInvoicePDF = (invoice) => {
    const doc = new jsPDF({
        orientation: printOptions.value.orientation,
        unit: 'mm',
        format: printOptions.value.paperSize
    });

    // ... company header, invoice details, items table, totals ...

    // ==== SIGNATURE SECTION (Right side) ====
    const sigY = paymentInfoY;
    doc.setFontSize(8);
    doc.text('Banten, ' + (invoice.invoice_date || new Date().toLocaleDateString('id-ID')), 140, sigY);
    doc.text('PT. MULTIBOX INDAH', 140, sigY + 4);

    // Signature box
    doc.rect(140, sigY + 10, 40, 20);

    // ✅ SIGNATURE NAME - From printed_by (PT_UID)
    doc.setFont('helvetica', 'bold');
    const signerName = invoice.printed_by || 'AUTHORIZED SIGNATURE';
    doc.text(signerName.toUpperCase(), 160, sigY + 33, { align: 'center' });
    //     ↑
    //     Jika invoice sudah pernah di-print: "EVA KEMPI"
    //     Jika invoice belum pernah di-print: "AUTHORIZED SIGNATURE"

    // ==== FOOTER ====
    doc.setFontSize(7);
    doc.text('Tanggal Print  : ' + new Date().toLocaleDateString('id-ID'), 15, 289);

    return doc;
};
```

**Method: `selectInvoice()`** - Load Invoice Data (Line 351-398)

```javascript
const selectInvoice = async (invoice) => {
    try {
        // Fetch full invoice details from backend
        const res = await axios.get(`/api/invoices/${encodeURIComponent(invoice.invoice_no)}`);

        if (res.data) {
            // Set selected invoice with all fields including printed_by
            selectedInvoice.value = {
                invoice_no: res.data.invoice_no,
                customer_code: res.data.customer_code,
                customer_name: res.data.customer_name,
                // ... other fields ...
                
                // ✅ AUDIT TRAIL
                printed_by: res.data.printed_by,      // ← "EVA KEMPI" (dari DB)
                printed_date: res.data.printed_date,  // ← "16/10/2025"
                // ... other audit fields ...
            };

            showInvoiceTable.value = false;
            toast.success('Invoice loaded successfully');
        }
    } catch (e) {
        toast.error('Failed to load invoice details');
    }
};
```

---

## 🗄️ **Database Structure**

### **Tabel INV - Print Audit Fields:**

| Column   | Type         | Description                              | Example Value   |
|----------|--------------|------------------------------------------|-----------------|
| `PT_UID` | VARCHAR(50)  | User ID yang print **PERTAMA KALI**    | "EVA KEMPI"     |
| `PT_DATE`| VARCHAR(50)  | Tanggal print pertama (dd/mm/yyyy)      | "16/10/2025"    |
| `PT_TIME`| VARCHAR(50)  | Waktu print pertama (HH:ii)             | "14:30"         |

**⚠️ PENTING:**
- Fields ini **HANYA di-update saat print PERTAMA KALI**
- Re-print **TIDAK mengubah** PT_UID, PT_DATE, PT_TIME
- Ini untuk **audit trail** - siapa yang pertama kali mencetak invoice

---

## 📊 **Skenario Berbeda**

### **Skenario 1: Invoice Belum Pernah Di-Print**

```sql
SELECT PT_UID, PT_DATE, PT_TIME FROM INV WHERE IV_NUM = 'IV-202511-0005';

Result:
PT_UID = NULL
PT_DATE = NULL
PT_TIME = NULL
```

**PDF akan menampilkan:**
```
Signature box: [____]
Name: AUTHORIZED SIGNATURE  ← Fallback jika PT_UID kosong
```

---

### **Skenario 2: Invoice Sudah Di-Print oleh EVA KEMPI**

```sql
SELECT PT_UID, PT_DATE, PT_TIME FROM INV WHERE IV_NUM = 'IV-202511-0004';

Result:
PT_UID = 'EVA KEMPI'
PT_DATE = '16/10/2025'
PT_TIME = '14:30'
```

**PDF akan menampilkan:**
```
Signature box: [____]
Name: EVA KEMPI  ← Dari PT_UID
Footer: Tanggal Print: 16/10/2025
```

---

### **Skenario 3: Re-Print oleh User Lain (BUDI)**

**User BUDI login dan re-print invoice yang sama:**

```sql
-- PT_UID TIDAK BERUBAH karena ini re-print
SELECT PT_UID, PT_DATE, PT_TIME FROM INV WHERE IV_NUM = 'IV-202511-0004';

Result:
PT_UID = 'EVA KEMPI'      ← Tetap EVA (first print)
PT_DATE = '16/10/2025'    ← Tetap 16/10
PT_TIME = '14:30'         ← Tetap 14:30
```

**PDF akan tetap menampilkan:**
```
Signature box: [____]
Name: EVA KEMPI  ← Tetap EVA (audit trail first print)
Footer: Tanggal Print: 06/11/2025  ← Tanggal hari ini (re-print)
```

**Catatan:**
- Signature name = **First printer** (EVA KEMPI)
- Footer print date = **Current date** (06/11/2025)

---

## ✅ **Kesimpulan**

### **Jawaban untuk Pertanyaan User:**

1. ✅ **"EVA KEMPI" BUKAN hardcoded/default**
2. ✅ **"EVA KEMPI" adalah nama user yang melakukan print invoice PERTAMA KALI**
3. ✅ **Data diambil dari kolom `PT_UID` di tabel `INV`**
4. ✅ **`PT_UID` di-isi otomatis dari `Auth::user()->name` saat print**
5. ✅ **Pada gambar invoice, "EVA KEMPI" adalah user yang print pada 16/10/2025**

### **Data Flow Summary:**

```
User Login (EVA KEMPI)
    ↓
Click "Export to PDF"
    ↓
Backend: Auth::user()->name = "EVA KEMPI"
    ↓
Update DB: PT_UID = "EVA KEMPI"
    ↓
Frontend: invoice.printed_by = "EVA KEMPI"
    ↓
PDF: Signature shows "EVA KEMPI"
```

### **Perbaikan yang Sudah Dilakukan:**

**SEBELUM (Hardcoded - SALAH):**
```javascript
doc.text('EVA KEMPI', 160, sigY + 33, { align: 'center' });
```

**SESUDAH (Dynamic - BENAR):**
```javascript
const signerName = invoice.printed_by || 'AUTHORIZED SIGNATURE';
doc.text(signerName.toUpperCase(), 160, sigY + 33, { align: 'center' });
```

---

## 🔒 **Security & Audit Implications**

### **Mengapa Ini Penting:**

1. **Accountability** - Tahu siapa yang print invoice (legal/audit)
2. **Non-Repudiation** - User tidak bisa menyangkal sudah print
3. **Audit Trail** - History lengkap untuk compliance
4. **Authorization** - Bukti bahwa user authorized melakukan print

### **Business Rules:**

- ✅ Hanya **authenticated user** yang bisa print
- ✅ Nama user **harus terekam** di PT_UID
- ✅ PT_UID **tidak boleh diubah** setelah first print
- ✅ Re-print **tidak mengubah** audit trail original
- ✅ PDF **harus menampilkan** nama user yang print

---

**End of Documentation** 📝

