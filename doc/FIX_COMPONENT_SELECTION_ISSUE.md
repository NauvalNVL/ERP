# Fix: Component Selection Issue - Fit1-9 Tidak Tersimpan

## Masalah yang Ditemukan

Ketika user memilih Fit1-Fit9 di modal "Setup MC Component" dan kemudian save, data masih tersimpan ke component "Main" bukan ke Fit yang dipilih.

### Bukti Masalah dari Console Log:

```javascript
🔍 Save Master Card - Component Info: {
    all_components: [
        { name: "Main", selected: true },   // ← Main masih selected
        { name: "Fit1", selected: false },  // ← Fit1 tidak selected
        { name: "Fit2", selected: false },
        ...
    ],
    componentName: "Main",  // ← Selalu Main
    form_comp_no: "Main"    // ← Selalu Main
}

📤 Payload being sent: {
    comp_no: "Main",  // ← Selalu Main, tidak pernah Fit1/Fit2/dst
    ...
}
```

## Akar Masalah

### Arsitektur Component:

```
update-mc.vue (Parent)
├── mcComponents (array dengan selected state)
└── UpdateMcModal.vue (Child)
    ├── localComponents (copy dari mcComponents)
    ├── selectedComponentIndex (index yang dipilih di modal)
    └── onSelectComponent() (handler saat klik row)
```

### Alur yang Salah:

1. User klik row "Fit1" di modal
2. `onSelectComponent()` dipanggil
3. `selectedComponentIndex` diupdate di modal (lokal)
4. **TIDAK ADA** emit ke parent
5. `mcComponents` di parent **TIDAK BERUBAH**
6. Main tetap `selected: true`
7. Saat save, `componentName` = "Main"

### Kode Sebelum Perbaikan:

```javascript
// UpdateMcModal.vue - SEBELUM
const onSelectComponent = (component, index) => {
    selectedComponentIndex.value = index;
    // ❌ TIDAK ADA emit ke parent!
    // Hanya update state lokal di modal
    // Parent tidak tahu component berubah
};
```

## Solusi yang Diterapkan

### 1. Tambahkan Emit ke Parent

**File:** `resources/js/Components/UpdateMcModal.vue`

```javascript
// UpdateMcModal.vue - SESUDAH
const onSelectComponent = (component, index) => {
    selectedComponentIndex.value = index;
    
    // ✅ BARU: Emit ke parent untuk update mcComponents
    emit('selectComponent', component, index);
    
    // When selecting a component, reflect its SO/WO into parent UI state
    try {
        const idx = selectedComponentIndex.value;
        const cf = componentForms.value[idx] || makeEmptyPdState();
        if ((!cf.soValues?.some(v => v)) && (!cf.woValues?.some(v => v))) {
            emit('requestClearSoWo');
        } else {
            emit('requestSetSoWo', {
                so: Array.isArray(cf.soValues) ? cf.soValues : ['', '', '', '', ''],
                wo: Array.isArray(cf.woValues) ? cf.woValues : ['', '', '', '', ''],
            });
        }
    } catch (e) {}
};
```

### 2. Parent Sudah Siap Menerima Emit

**File:** `resources/js/Pages/sales-management/system-requirement/master-card/update-mc.vue`

Parent component sudah memiliki handler `selectComponent` yang benar:

```javascript
const selectComponent = (component, index) => {
    // Reset all selections
    mcComponents.value.forEach((comp) => (comp.selected = false));
    // Select the clicked component
    component.selected = true;
    
    // Update form comp_no with selected component name
    if (form.value) {
        form.value.comp_no = component.c_num; // Main, Fit1, Fit2, etc.
        console.log('✅ Component selected:', {
            component_name: component.c_num,
            form_comp_no: form.value.comp_no
        });
    }
};
```

Dan sudah terhubung di template:

```vue
<UpdateMcModal
    ...
    @selectComponent="selectComponent"
    ...
/>
```

## Alur Setelah Perbaikan

### Alur yang Benar:

1. User klik row "Fit1" di modal
2. `onSelectComponent()` dipanggil di modal
3. `selectedComponentIndex` diupdate di modal (lokal)
4. **✅ emit('selectComponent')** dipanggil
5. Parent menerima event
6. `selectComponent()` di parent dipanggil
7. `mcComponents` di parent diupdate:
   - Main: `selected: false`
   - Fit1: `selected: true`
8. `form.comp_no` diupdate = "Fit1"
9. Saat save, `componentName` = "Fit1" ✅
10. Database menyimpan row baru dengan `COMP = 'Fit1'` ✅

## Testing

### Test Case 1: Save Main Component

```
1. Buka Update MC
2. Pilih customer
3. Enter MCS# baru
4. Klik "Proceed"
5. Isi MC Model
6. Klik "Next Setup"
7. Main sudah ter-select (default)
8. Klik "Setup PD"
9. Isi form
10. Klik "Save Master Card"

Expected Console Log:
✅ Component selected: { component_name: "Main", form_comp_no: "Main" }
📤 Payload being sent: { comp_no: "Main", ... }

Expected Database:
MCS_Num | AC_NUM | COMP | MODEL
--------|--------|------|-------
MC001   | C001   | Main | Box-A
```

### Test Case 2: Save Fit1 Component

```
1. Masih di modal yang sama
2. KLIK row "Fit1" di tabel component
3. Perhatikan console log
4. Klik "Setup PD"
5. Isi form (bisa berbeda dari Main)
6. Klik "Save Master Card"

Expected Console Log:
✅ Component selected: { component_name: "Fit1", form_comp_no: "Fit1" }
🔍 Save Master Card - Component Info: {
    componentName: "Fit1",
    form_comp_no: "Fit1",
    all_components: [
        { name: "Main", selected: false },
        { name: "Fit1", selected: true },  // ← Fit1 selected!
        ...
    ]
}
📤 Payload being sent: { comp_no: "Fit1", ... }

Expected Database:
MCS_Num | AC_NUM | COMP | MODEL
--------|--------|------|-------
MC001   | C001   | Main | Box-A
MC001   | C001   | Fit1 | Divider  // ← Row baru!
```

### Test Case 3: Save Multiple Components

```
1. Klik row "Fit2"
2. Setup PD
3. Save
4. Klik row "Fit3"
5. Setup PD
6. Save

Expected Database:
MCS_Num | AC_NUM | COMP | MODEL
--------|--------|------|-------
MC001   | C001   | Main | Box-A
MC001   | C001   | Fit1 | Divider
MC001   | C001   | Fit2 | Insert   // ← Row baru!
MC001   | C001   | Fit3 | Padding  // ← Row baru!
```

## Verifikasi Perbaikan

### Checklist Sebelum Test:

- [x] `emit('selectComponent')` ditambahkan di `onSelectComponent()`
- [x] `selectComponent` handler ada di parent
- [x] `@selectComponent="selectComponent"` ada di template
- [x] `form.comp_no` diupdate di handler
- [x] `mcComponents[].selected` diupdate di handler
- [x] Logging ditambahkan untuk debugging

### Checklist Saat Test:

- [ ] Klik row component menampilkan highlight (background biru)
- [ ] Console menunjukkan "✅ Component selected" dengan nama yang benar
- [ ] Console menunjukkan `all_components` dengan selected yang benar
- [ ] Console menunjukkan `comp_no` di payload sesuai component yang dipilih
- [ ] Database menunjukkan row baru dengan COMP yang sesuai
- [ ] Setiap component memiliki row terpisah di database

## Troubleshooting

### Jika Masih Bermasalah:

#### 1. Cek Console Log

Pastikan muncul log ini saat klik row component:

```javascript
✅ Component selected: {
    component_name: "Fit1",  // ← Harus sesuai yang diklik
    form_comp_no: "Fit1"     // ← Harus sesuai yang diklik
}
```

Jika TIDAK muncul:
- Emit tidak terpanggil
- Handler tidak terhubung
- Cek template `@selectComponent="selectComponent"`

#### 2. Cek all_components Array

Pastikan saat save, array menunjukkan selected yang benar:

```javascript
all_components: [
    { name: "Main", selected: false },
    { name: "Fit1", selected: true },  // ← Yang diklik harus true
    ...
]
```

Jika SALAH:
- Handler `selectComponent` tidak jalan
- `mcComponents.value.forEach` tidak jalan
- `component.selected = true` tidak jalan

#### 3. Cek Payload

Pastikan `comp_no` di payload sesuai:

```javascript
📤 Payload being sent: {
    comp_no: "Fit1",  // ← Harus sesuai yang diklik
    ...
}
```

Jika SALAH:
- `form.comp_no` tidak terupdate
- `componentName` tidak ambil dari yang benar
- Cek `const selectedComponent = mcComponents.value.find(c => c.selected)`

#### 4. Cek Database

```sql
SELECT MCS_Num, AC_NUM, COMP, MODEL, created_at
FROM MC
WHERE MCS_Num = 'YOUR_MC_NUMBER'
ORDER BY COMP, created_at DESC;
```

Jika hanya ada 1 row atau COMP selalu "Main":
- Backend tidak menerima `comp_no` dengan benar
- Cek Laravel log untuk "Component Info"
- Cek "Legacy COMP value set to"

## Files Modified

1. **resources/js/Components/UpdateMcModal.vue**
   - Modified `onSelectComponent()` function
   - Added `emit('selectComponent', component, index)`

2. **resources/js/Pages/sales-management/system-requirement/master-card/update-mc.vue**
   - Already has correct `selectComponent()` handler (no changes needed)
   - Already has `@selectComponent="selectComponent"` in template (no changes needed)

3. **app/Http/Controllers/UpdateMcController.php**
   - Already has correct COMP mapping (no changes needed)
   - Already includes COMP in `updateOrInsert` unique key (no changes needed)

## Summary

**Root Cause:** Modal tidak memberitahu parent saat component dipilih

**Solution:** Tambahkan `emit('selectComponent')` di `onSelectComponent()`

**Result:** 
- ✅ Component selection ter-sync antara modal dan parent
- ✅ `form.comp_no` terupdate sesuai pilihan
- ✅ Payload mengirim `comp_no` yang benar
- ✅ Database menyimpan row terpisah untuk setiap component

## Next Steps

1. Test dengan skenario di atas
2. Verifikasi console logs sesuai expected
3. Verifikasi database sesuai expected
4. Jika ada masalah, ikuti troubleshooting guide
5. Report hasil testing

## Contact

Jika masih ada masalah setelah perbaikan ini, capture:
- Full console logs (dari klik component sampai save)
- Database query result
- Screenshot UI saat memilih component
- Laravel logs (storage/logs/laravel.log)
