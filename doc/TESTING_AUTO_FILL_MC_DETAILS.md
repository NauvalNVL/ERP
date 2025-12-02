# Testing Guide: Auto-Fill MC Details

## 🎯 Expected Behavior (CPS Style)

### **Step 1: Initial View (Gambar 1 - CPS)**
```
Saat pertama buka menu:
┌────────────────────────┐
│ Selected Master Card   │
│                        │
│ AC#:  [______] 📁      │
│ MCS#: [______] 📁      │
└────────────────────────┘

✓ Hanya 2 fields
✓ Kosong semua
✓ Tidak ada detail lainnya
```

### **Step 2: Select Customer**
```
1. User klik browse (📁) di AC#
2. Modal Customer Account Table terbuka
3. User pilih customer, e.g., "ABDULLAH, BPK." (000211-08)
4. Field terisi otomatis:

┌────────────────────────┐
│ Selected Master Card   │
│                        │
│ AC#:  [000211-08] 📁   │
│ MCS#: [__________] 📁  │
└────────────────────────┘

✓ AC# terisi: "000211-08"
✓ MCS# masih kosong
✓ Masih simple view
```

**Console Log Expected:**
```javascript
Customer selected: {customer_code: "000211-08", customer_name: "ABDULLAH, BPK.", ...}
Customer name filled: ABDULLAH, BPK.
```

### **Step 3: Select MC (Gambar 2 - CPS)**
```
1. User klik browse (📁) di MCS#
2. Modal Master Card Table terbuka (filtered by AC#)
3. User pilih MC, e.g., "1689138"
4. ✨ SEMUA DETAIL AUTO-FILL:

┌─────────────────────────────────────┐
│ Selected Master Card                │
│ ┌──────────┬────────────────────┐  │
│ │ AC#:     │ ABDULLAH, BPK.     │  │ ← AUTO-FILLED
│ │ 000211-08│                    │  │
│ ├──────────┼────────────────────┤  │
│ │ MCS#:    │ Model:             │  │
│ │ 1689138  │ BUX BASU 4.5 KG    │  │ ← AUTO-FILLED
│ ├──────────┼────────────────────┤  │
│ │ Salesperson:  Action:         │  │
│ │ S111 KHOES TJ To Obsolete     │  │ ← AUTO-FILLED & AUTO-DETECTED
│ ├──────────┴────────────────────┤  │
│ │ Reason: [_________________]   │  │
│ └───────────────────────────────┘  │
│                                     │
│ Last Update Log                     │
│ ┌─────────────────────────────┐    │
│ │ Status: Active              │    │ ← AUTO-FILLED
│ │ User ID: mc02               │    │
│ │ Date: 08/08/0000            │    │
│ │ Time:                       │    │
│ │ Reason:                     │    │
│ │ ☑ Number of Updates: 0     │    │
│ └─────────────────────────────┘    │
│                                     │
│ [         Save         ]            │ ← MUNCUL
└─────────────────────────────────────┘
```

**Console Log Expected:**
```javascript
MCS selected: {seq: "1689138", model: "BUX BASU 4.5 KG", ...}
MCS# set to: 1689138
Calling loadMcDetails...
Loading MC details for MCS#: 1689138
MC Details API Response: {
  customer_name: "ABDULLAH, BPK.",
  model: "BUX BASU 4.5 KG",
  salesperson_code: "S111",
  salesperson_name: "KHOES TJ",
  status: "Active",
  last_update: {...}
}
MC Details populated: {
  customer_name: "ABDULLAH, BPK.",
  model: "BUX BASU 4.5 KG",
  salesperson_code: "S111",
  salesperson_name: "KHOES TJ",
  current_status: "Active"
}
Last Update Log populated: {...}
loadMcDetails completed
Detecting action for status: Active
Action detected: To Obsolete
```

---

## 🔍 Debugging Steps

### **1. Open Browser Console**
```
F12 → Console tab
```

### **2. Test Flow**

**A. Test Customer Selection:**
```javascript
// After clicking AC# browse and selecting customer:
// Should see:
✓ Customer selected: {customer_code: "...", customer_name: "..."}
✓ Customer name filled: ...
```

**B. Test MC Selection:**
```javascript
// After clicking MCS# browse and selecting MC:
// Should see:
✓ MCS selected: {seq: "...", model: "..."}
✓ MCS# set to: ...
✓ Calling loadMcDetails...
✓ Loading MC details for MCS#: ...
✓ MC Details API Response: {...}
✓ MC Details populated: {...}
✓ Last Update Log populated: {...}
✓ loadMcDetails completed
✓ Detecting action for status: ...
✓ Action detected: To Obsolete/To Reactivate
```

### **3. Check API Endpoint**

**Test API manually:**
```bash
# In browser or Postman:
GET http://127.0.0.1:8000/api/mc/details/1689138

# Expected Response:
{
  "customer_name": "ABDULLAH, BPK.",
  "model": "BUX BASU 4.5 KG",
  "salesperson_code": "S111",
  "salesperson_name": "KHOES TJ",
  "status": "Active",
  "last_update": {
    "status": "Obsolete",
    "user_id": "mc02",
    "date": "12/10/2018",
    "time": "13:15",
    "reason": "SUDAH GANTI",
    "total_update": 1
  }
}
```

---

## ⚠️ Troubleshooting

### **Issue 1: Fields Not Auto-Filling**

**Check Console for errors:**
```javascript
// Look for:
Error loading MC details: ...
Error details: ...
```

**Possible causes:**
1. API endpoint not working
2. MCS_Num tidak ditemukan di database
3. Data format tidak sesuai

**Solution:**
```bash
# Check if route exists:
php artisan route:list | grep "mc/details"

# Should show:
GET api/mc/details/{mcsNum} .... UpdateMcController@getMcDetails
```

### **Issue 2: Customer Name Not Showing**

**Check console:**
```javascript
Customer selected: {...}
// Look at structure - might be:
customer.customer_name  ← atau
customer.name          ← atau
customer.AC_NAME       ← check field name
```

**Fix in code jika field berbeda:**
```javascript
mcDetails.value.customer_name = customer.customer_name 
                              || customer.name 
                              || customer.AC_NAME 
                              || '';
```

### **Issue 3: Action Not Auto-Detecting**

**Check console:**
```javascript
Detecting action for status: Active  // ← Should be "Active" or "Act"
Action detected: To Obsolete         // ← Should detect correctly
```

**Possible values:**
- `"Active"` → To Obsolete
- `"Act"` → To Obsolete  
- `"Obsolete"` → To Reactivate

### **Issue 4: View Not Switching**

**Check:**
```javascript
// After selecting MC, form.mcs should have value:
console.log('form.mcs:', form.value.mcs);
// Should NOT be empty

// If empty, v-if="!form.mcs" stays true = simple view
// If has value, v-else triggers = detail view
```

---

## ✅ Success Criteria

### **After selecting Customer:**
- ✅ AC# field filled with customer code
- ✅ Customer name stored in `mcDetails.customer_name`
- ✅ MCS# field still empty
- ✅ Still simple view (only AC# and MCS# visible)

### **After selecting MC:**
- ✅ MCS# field filled with MC number
- ✅ View switches to detail view (shows all fields)
- ✅ Customer Name displays in second column
- ✅ Model auto-filled
- ✅ Salesperson code + name auto-filled
- ✅ Action auto-detected (red for Obsolete, green for Reactivate)
- ✅ Last Update Log section appears with data
- ✅ Save button appears
- ✅ Reason textarea appears and ready for input

---

## 📋 Quick Test Checklist

```
□ Open menu: Obsolete & Reactive MC
□ Verify initial view: only AC# and MCS# fields
□ Open browser console (F12)
□ Click AC# browse button
□ Select customer
□ Check console: "Customer selected" log
□ Verify AC# filled
□ Click MCS# browse button
□ Select MC
□ Check console: multiple logs from selectMcs → loadMcDetails
□ Verify view switches to detail view
□ Verify all fields auto-filled:
  □ Customer Name
  □ Model
  □ Salesperson
  □ Action (with correct color)
□ Verify Last Update Log section appears
□ Verify Save button appears
□ Enter reason
□ Click Save
□ Verify confirmation modal
□ Click OK
□ Verify success message
□ Verify data updated
```

---

## 🎨 Visual Indicators

### **Color Coding:**
- **Red Action Field** = "To Obsolete" (MC currently Active)
- **Green Action Field** = "To Reactivate" (MC currently Obsolete)

### **Field States:**
- **White background** = Editable (AC#, Reason)
- **Gray background** = Readonly/Auto-filled (MCS#, Model, Salesperson, Action, Last Update)

---

**Date:** 2025-01-03  
**Status:** Ready for Testing  
**Console Logs:** Enabled for debugging
