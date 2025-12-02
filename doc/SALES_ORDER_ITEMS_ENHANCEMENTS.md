# Sales Order Items Screen - CPS Compatible Enhancements

## 📋 Overview
Perbaikan Sales Order Items Screen pada menu "Prepare Invoice by D/Order (Current Period)" agar fetch data real dari database dan display seperti CPS ERP.

---

## ✅ Perubahan yang Telah Dilakukan

### 1. **Enhanced API Controller Method**

**File:** `InvoiceController.php`
**Method:** `getDoItems($request)`

#### **New Features:**
- ✅ Fetch complete DO data from database
- ✅ Calculate totals automatically
- ✅ Build S/O List with unique SO numbers
- ✅ Build Item Details (Main + F#1-F#9)
- ✅ Return comprehensive response

#### **Data Retrieved from DO Table:**
```php
$doRecords = DB::table('DO')
    ->where('DO_Num', $doNumber)
    ->select([
        'DO_Num', 'DO_DMY', 'AC_Num', 'AC_Name',
        'SO_Num', 'MCS_Num', 'Model', 'Product',
        'PD', 'No', 'Unit', 'PCS_PER_SET',
        'DO_Qty', 'SO_Unit_Price', 'DO_Tran_Amt',
        'DO_Base_Amt', 'Curr', 'COMP',
        'Total_DO_Net_KG', 'DO_Remark1', 'DO_Remark2',
    ])
    ->get();
```

#### **Response Structure:**
```json
{
  "do_number": "2025-10-00001",
  "do_date": "29/10/2025",
  "customer_code": "000211-08",
  "customer_name": "ABDULLAH, BPK",
  "so_number": "10-2025-02848",
  "mc_seq": "03-2025-02090",
  "model": "SHIPPING CASE ACOSTA JUMBO",
  "currency": "IDR",
  "control_set_order": 0,
  "s_o_count": 1,
  "total_amount": 4410000,
  "total_qty": 300,
  "so_list": [
    {
      "so_number": "10-2025-02848",
      "mc_seq": "03-2025-02090",
      "do_ref": "2025-10-00001",
      "amount": 4410000
    }
  ],
  "item_details": [
    {
      "item": "Main",
      "p_design": "B1",
      "pcs": 1,
      "unit": "Pcs",
      "u_price": 14700.0000,
      "deliver": 300,
      "reject": 0,
      "unbill": 300,
      "to_bill": 300,
      "to_bill_kg": 0
    },
    {
      "item": "F# 1",
      "p_design": "-",
      "pcs": null,
      // ... all null for finishing items
    }
  ],
  "remarks": {
    "remark1": "test",
    "remark2": "test"
  }
}
```

---

### 2. **Updated Vue Component**

**File:** `SalesOrderItemsModal.vue`

#### **A. Added State Management**
```javascript
const loading = ref(false)
const doData = ref(null)
const soList = ref([])
const itemDetails = ref([])
const controlSetOrder = ref(0)
const soCount = ref(1)
const total = ref(0)
```

#### **B. Fetch Function**
```javascript
const fetchDoItems = async () => {
  loading.value = true
  const response = await fetch(
    `/api/invoices/do-items?do_number=${props.doNumber}`
  )
  
  if (response.ok) {
    const data = await response.json()
    doData.value = data
    soList.value = data.so_list || []
    itemDetails.value = data.item_details || []
    // ... populate other fields
  }
}
```

#### **C. Watchers for Auto-Fetch**
```javascript
// Watch modal open
watch(() => props.open, (isOpen) => {
  if (isOpen && props.doNumber) {
    fetchDoItems()
  }
})

// Watch DO number changes
watch(() => props.doNumber, (newDoNumber) => {
  if (props.open && newDoNumber) {
    fetchDoItems()
  }
})
```

---

### 3. **Template Updates**

#### **A. Loading Indicator**
```vue
<div v-if="loading" class="text-center py-8">
  <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
  <p class="mt-2 text-sm text-gray-600">Loading DO items...</p>
</div>
```

#### **B. S/O List Table (CPS-Compatible)**
```vue
<table>
  <thead>
    <tr>
      <th>No</th>
      <th>S/O Item#</th>
      <th>M/Card Seq#</th>
      <th>D/Order Ref.#</th>
      <th>Amount</th>
    </tr>
  </thead>
  <tbody>
    <tr v-for="(item, index) in soList">
      <td>{{ 1001 + index }}</td>
      <td>{{ item.so_number }}</td>
      <td>{{ item.mc_seq }}</td>
      <td>{{ item.do_ref }}</td>
      <td>{{ formatCurrency(item.amount) }}</td>
    </tr>
  </tbody>
</table>
```

#### **C. Item Details Table**
```vue
<table>
  <thead>
    <tr>
      <th>Item</th>
      <th>P/Design</th>
      <th>Pcs</th>
      <th>Unit</th>
      <th>U/Price</th>
      <th>Deliver</th>
      <th>Reject</th>
      <th>Unbill</th>  <!-- Fixed from "UsBill" -->
      <th>To Bill</th>
      <th>To Bill KG</th>
    </tr>
  </thead>
  <tbody>
    <tr v-for="item in itemDetails">
      <td>{{ item.item }}</td>
      <td>{{ item.p_design }}</td>
      <td>{{ formatNumber(item.pcs) }}</td>
      <!-- ... all fields from API -->
    </tr>
  </tbody>
</table>
```

---

## 🔄 Data Flow

```
User opens Sales Order Items Modal
         ↓
Vue Component mounted
         ↓
Watch triggers: props.open === true
         ↓
fetchDoItems() called
         ↓
API Call: GET /api/invoices/do-items?do_number=2025-10-00001
         ↓
InvoiceController::getDoItems()
         ↓
Query DO table
  • Get all DO records with DO_Num
  • Calculate totals
  • Group by SO_Num for S/O List
  • Build item details (Main + F#1-F#9)
         ↓
Return JSON response
         ↓
Vue component receives data
         ↓
Populate state variables:
  • soList
  • itemDetails
  • total
  • soCount
         ↓
Template re-renders with real data
         ↓
User sees CPS-compatible display
```

---

## 📊 Comparison: Before vs After

### **S/O List Table**

**Before:**
- ❌ Hardcoded single row
- ❌ Static data (1001, props values)
- ❌ No real SO lookup

**After:**
- ✅ Dynamic rows from `soList`
- ✅ Real data from DO table
- ✅ Unique SO numbers grouped
- ✅ Correct amounts calculated

### **Item Details Table**

**Before:**
- ❌ Hardcoded "Main" row
- ❌ Hardcoded "Fit1-Fit8" rows
- ❌ Static values (B1, 1, Pcs, 300)
- ❌ Wrong column name "UsBill"

**After:**
- ✅ Dynamic rows from `itemDetails`
- ✅ Real data from DO table
- ✅ "Main" + "F# 1" through "F# 9"
- ✅ Correct column name "Unbill"
- ✅ Real quantities and prices
- ✅ Proper formatting (currency, numbers)

### **Header Info**

**Before:**
- ❌ Only from props
- ❌ Control Set Order hardcoded to 0

**After:**
- ✅ From API response (doData)
- ✅ Fallback to props if API fails
- ✅ Control Set Order from data

### **Totals**

**Before:**
- ❌ From props.totalAmount
- ❌ Static S/O Count = 1

**After:**
- ✅ Calculated from DO records
- ✅ Dynamic S/O Count from data
- ✅ Auto-sum of all amounts

---

## 🎯 Field Mapping

### **From DO Table to Display:**

| DO Table Field | Display Location | Description |
|----------------|------------------|-------------|
| `DO_Num` | Header: D/Order# | Delivery Order number |
| `DO_DMY` | Header: D/O Date | Date in DD/MM/YYYY |
| `AC_Num` | API only | Customer code |
| `AC_Name` | API only | Customer name |
| `SO_Num` | S/O List: S/O Item# | Sales Order number |
| `MCS_Num` | S/O List: M/Card Seq# | Master Card Sequence |
| `Model` | Model field | Product model name |
| `PD` | Item Details: P/Design | Product Design (e.g., B1) |
| `PCS_PER_SET` | Item Details: Pcs | Pieces per set |
| `Unit` | Item Details: Unit | Unit of measure |
| `SO_Unit_Price` | Item Details: U/Price | Unit price |
| `DO_Qty` | Item Details: Deliver, Unbill, To Bill | Quantity delivered |
| `DO_Tran_Amt` | S/O List: Amount, Total | Transaction amount |
| `Total_DO_Net_KG` | Item Details: To Bill KG | Weight in KG |
| `DO_Remark1` | API: remarks | Remark 1 |
| `DO_Remark2` | API: remarks | Remark 2 |

---

## 🚀 Usage

### **For Users:**

1. **Navigate to:** Prepare Invoice by D/Order (Current Period)
2. **Select a D/O** from Delivery Order Table
3. **Sales Order Items Screen opens**
4. **Automatic Data Load:**
   - ✅ S/O List populated from database
   - ✅ Item details populated from database
   - ✅ Totals calculated automatically
5. **Review data** and click OK to proceed

### **For Developers:**

**Test API Endpoint:**
```bash
curl "http://localhost/api/invoices/do-items?do_number=2025-10-00001"
```

**Expected Response:**
```json
{
  "do_number": "2025-10-00001",
  "do_date": "29/10/2025",
  "s_o_count": 1,
  "total_amount": 4410000,
  "so_list": [...],
  "item_details": [...]
}
```

**Check Laravel Logs:**
```bash
tail -f storage/logs/laravel.log | grep "DO Items"
```

---

## 🔧 Technical Details

### **No Migration Required**
- ✅ Uses existing DO table
- ✅ No new database columns
- ✅ All data from current structure

### **API Route** (Already Exists)
```php
Route::prefix('invoices')->group(function () {
    Route::get('/do-items', [InvoiceController::class, 'getDoItems']);
});
```
**Endpoint:** `GET /api/invoices/do-items?do_number={DO_NUM}`

### **Error Handling**
```php
// Controller
if ($doRecords->isEmpty()) {
    return response()->json(['error' => 'DO not found'], 404);
}

// Vue Component
catch (error) {
    console.error('❌ Error fetching DO items:', error)
}
```

### **Style Preservation**
- ✅ No visual changes
- ✅ Same colors (blue header, yellow highlight)
- ✅ Same fonts and spacing
- ✅ Same table structure
- ✅ Only functional improvements

---

## 📝 Features Implemented

### **✅ Completed:**
1. API method `getDoItems()` enhanced
2. Real data fetch from DO table
3. S/O List table with dynamic rows
4. Item Details table with real data
5. Loading indicator during fetch
6. Auto-fetch on modal open
7. Error handling
8. Proper formatting (currency, numbers)
9. Fixed column name "Unbill"
10. Calculate totals from database

### **🎨 Visual Features:**
- Loading spinner during data fetch
- Yellow highlight on first S/O row (CPS style)
- Gray background on F# rows
- Proper number formatting
- Responsive table layout

---

## 🧪 Testing Checklist

- [x] Modal opens without errors
- [x] Loading indicator shows
- [x] API call successful
- [x] S/O List populated
- [x] Item Details populated
- [x] Totals calculated correctly
- [x] Column name is "Unbill" (not "UsBill")
- [x] Main item shows real data
- [x] F# 1-9 items show as placeholders
- [x] Currency formatted correctly
- [x] Numbers formatted correctly
- [x] No console errors
- [x] No style changes
- [x] CPS-compatible layout

---

## 📊 Example Data Display

### **S/O List Table:**
```
┌──────┬────────────────┬───────────────┬─────────────────┬─────────────┐
│ No   │ S/O Item#      │ M/Card Seq#   │ D/Order Ref.#   │ Amount      │
├──────┼────────────────┼───────────────┼─────────────────┼─────────────┤
│ 1001 │ 10-2025-02848  │ 03-2025-02090 │ 2025-10-00001   │ 4,410,000   │
└──────┴────────────────┴───────────────┴─────────────────┴─────────────┘
```

### **Item Details Table:**
```
┌──────┬──────────┬─────┬──────┬────────────┬─────────┬────────┬────────┬─────────┬────────────┐
│ Item │ P/Design │ Pcs │ Unit │ U/Price    │ Deliver │ Reject │ Unbill │ To Bill │ To Bill KG │
├──────┼──────────┼─────┼──────┼────────────┼─────────┼────────┼────────┼─────────┼────────────┤
│ Main │ B1       │ 1   │ Pcs  │ 14,700,000 │ 300     │ 0      │ 300    │ 300     │ 0.00       │
│ F# 1 │ -        │ -   │ -    │ -          │ -       │ -      │ -      │ -       │ -          │
│ F# 2 │ -        │ -   │ -    │ -          │ -       │ -      │ -      │ -       │ -          │
│ ...  │ ...      │ ... │ ...  │ ...        │ ...     │ ...    │ ...    │ ...     │ ...        │
│ F# 9 │ -        │ -   │ -    │ -          │ -       │ -      │ -      │ -       │ -          │
└──────┴──────────┴─────┴──────┴────────────┴─────────┴────────┴────────┴─────────┴────────────┘
```

---

## ⚠️ Known Limitations

1. **Finishing Items (F# 1-9)**
   - Currently shown as placeholders
   - Data structure in DO table needs enhancement
   - Can be populated if finishing data available

2. **Reject Column**
   - Always 0 (no reject data in DO table)
   - Can be enhanced with reject tracking

3. **Multiple DO Lines**
   - Currently groups by SO_Num
   - Shows first item details
   - Can be enhanced for multi-item DOs

---

## 🔮 Future Enhancements

### **Short Term:**
- [ ] Populate F# 1-9 with actual finishing data
- [ ] Add reject quantity tracking
- [ ] Handle multiple items per DO
- [ ] Add inline editing for To Bill quantity

### **Long Term:**
- [ ] Add item-level calculations
- [ ] Integrate with inventory system
- [ ] Real-time validation
- [ ] Batch processing support

---

## 📞 Support

**API Test:**
```bash
curl -X GET "http://localhost/api/invoices/do-items?do_number=2025-10-00001"
```

**Check Logs:**
```bash
tail -f storage/logs/laravel.log | grep -E "Fetching DO Items|DO Items response"
```

**Browser Console:**
- Open modal → F12 → Console
- Look for: "🔄 Fetching DO items for:"
- Check for: "✅ DO items data received:"

---

## ✅ Summary

**Sales Order Items Screen** sekarang:
- ✅ **Fetch real data** dari DO table
- ✅ **Display CPS-compatible** layout
- ✅ **Auto-populate** S/O List dan Item Details
- ✅ **Calculate totals** from database
- ✅ **No migration** required
- ✅ **Style preserved** - no visual changes
- ✅ **Error handling** implemented
- ✅ **Loading indicators** for UX

Modal sekarang berfungsi **persis seperti CPS ERP** dengan data real dari database! 🎉

---

**Last Updated:** October 29, 2025, 23:34 WIB
**Version:** 2.0 - CPS-Compatible with Real Data
