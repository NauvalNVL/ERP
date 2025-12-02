# Debug Guide: Component Selection Issue

## Perbaikan yang Sudah Dilakukan

### 1. Menggunakan `form.comp_no` Langsung

**Masalah Sebelumnya:**
```javascript
// Mengambil dari mcComponents.value.find(c => c.selected)
const selectedComponent = mcComponents.value.find(c => c.selected);
const componentName = selectedComponent ? selectedComponent.c_num : 'Main';
// Jika tidak ada yang selected, componentName = 'Main'
```

**Perbaikan:**
```javascript
// Menggunakan form.comp_no yang sudah diupdate oleh selectComponent
const componentName = form.value.comp_no || 'Main';
const selectedComponent = mcComponents.value.find(c => c.c_num === componentName);
```

**Alasan:**
- `form.comp_no` sudah diupdate dengan benar saat user klik component
- Lebih reliable daripada mencari dari `selected: true`
- Menghindari fallback ke 'Main' jika ada masalah dengan selected state

### 2. Menambahkan Logging Lengkap

**Di UpdateMcModal.vue:**
```javascript
const onSelectComponent = (component, index) => {
    console.log('📡 UpdateMcModal - onSelectComponent called:', {
        component: component,
        index: index,
        component_c_num: component?.c_num
    });
    
    console.log('📡 UpdateMcModal - Emitting selectComponent to parent');
    emit('selectComponent', component, index);
    // ...
};
```

**Di update-mc.vue:**
```javascript
const selectComponent = (component, index) => {
    console.log('🔧 selectComponent called:', {
        component: component,
        index: index,
        component_c_num: component?.c_num
    });
    
    // ... update logic ...
    
    console.log('✅ Component selected:', {
        component_name: component.c_num,
        form_comp_no: form.value.comp_no,
        mcComponents_state: mcComponents.value.map(c => ({ 
            name: c.c_num, 
            selected: c.selected 
        }))
    });
};
```

## Expected Console Logs Setelah Perbaikan

### Saat User Klik Row Fit1:

```javascript
// 1. Modal mendeteksi klik
📡 UpdateMcModal - onSelectComponent called: {
    component: { c_num: "Fit1", pd: "", ... },
    index: 1,
    component_c_num: "Fit1"
}

// 2. Modal emit ke parent
📡 UpdateMcModal - Emitting selectComponent to parent

// 3. Parent menerima emit
🔧 selectComponent called: {
    component: { c_num: "Fit1", pd: "", ... },
    index: 1,
    component_c_num: "Fit1"
}

// 4. Parent update state
✅ Component selected: {
    component_name: "Fit1",
    form_comp_no: "Fit1",
    mcComponents_state: [
        { name: "Main", selected: false },
        { name: "Fit1", selected: true },  // ← Fit1 selected!
        ...
    ]
}
```

### Saat User Klik "Save Master Card":

```javascript
// 1. Ambil component name dari form.comp_no
🔍 Save Master Card - Component Info: {
    componentName: "Fit1",  // ← Dari form.comp_no
    form_comp_no: "Fit1",
    selectedComponent: { c_num: "Fit1", ... },  // ← Found by c_num
    all_components: [
        { name: "Main", selected: false },
        { name: "Fit1", selected: true },
        ...
    ]
}

// 2. Payload dengan comp_no yang benar
📤 Payload being sent: {
    comp_no: "Fit1",  // ← Benar!
    customer_code: "000004",
    mc_seq: "897969",
    ...
}
```

## Testing Steps

### Step 1: Clear Browser Cache

```
1. Buka DevTools (F12)
2. Klik kanan pada refresh button
3. Pilih "Empty Cache and Hard Reload"
4. Atau: Ctrl+Shift+Delete → Clear cache
```

### Step 2: Test Component Selection

```
1. Buka Update MC page
2. Pilih customer
3. Enter MCS# dan klik "Proceed"
4. Klik "Next Setup"
5. Buka Console (F12)
6. KLIK row "Fit1" di tabel

Expected Logs:
📡 UpdateMcModal - onSelectComponent called: { component_c_num: "Fit1" }
📡 UpdateMcModal - Emitting selectComponent to parent
🔧 selectComponent called: { component_c_num: "Fit1" }
✅ Component selected: { component_name: "Fit1", form_comp_no: "Fit1" }
```

### Step 3: Test Save

```
1. Klik "Setup PD"
2. Isi form
3. Klik "Save Master Card"
4. Perhatikan console

Expected Logs:
🔍 Save Master Card - Component Info: {
    componentName: "Fit1",  // ← Harus "Fit1"
    form_comp_no: "Fit1"
}
📤 Payload being sent: {
    comp_no: "Fit1"  // ← Harus "Fit1"
}
```

### Step 4: Verify Database

```sql
SELECT MCS_Num, AC_NUM, COMP, MODEL, created_at
FROM MC
WHERE MCS_Num = '897969'  -- Ganti dengan MCS# Anda
ORDER BY COMP;
```

Expected Result:
```
MCS_Num | AC_NUM | COMP | MODEL       | created_at
--------|--------|------|-------------|-------------------
897969  | 000004 | Fit1 | Your Model  | 2024-01-01 10:05:00
```

## Troubleshooting

### Issue 1: Tidak Ada Log "📡 UpdateMcModal - onSelectComponent called"

**Penyebab:** Row tidak ter-klik atau event handler tidak terpasang

**Solusi:**
1. Pastikan klik di area row (bukan di luar)
2. Cek apakah row ter-highlight (background biru)
3. Refresh page dan coba lagi

### Issue 2: Ada Log "📡 Emitting" Tapi Tidak Ada "🔧 selectComponent called"

**Penyebab:** Emit tidak sampai ke parent atau handler tidak terhubung

**Solusi:**
1. Cek template UpdateMcModal di parent:
   ```vue
   <UpdateMcModal
       ...
       @selectComponent="selectComponent"
       ...
   />
   ```
2. Pastikan `selectComponent` function ada di parent
3. Refresh page dan coba lagi

### Issue 3: Log "✅ Component selected" Menunjukkan Component yang Salah

**Penyebab:** Component object yang dikirim salah

**Solusi:**
1. Cek log "📡 onSelectComponent called" - component_c_num harus benar
2. Cek apakah `component` parameter di `selectComponent` benar
3. Cek apakah `component.c_num` ada dan benar

### Issue 4: `componentName` Masih "Main" Saat Save

**Penyebab:** `form.comp_no` tidak terupdate

**Solusi:**
1. Cek log "✅ Component selected" - form_comp_no harus benar
2. Jika form_comp_no benar tapi componentName salah, ada bug di `saveMasterCardFromModal`
3. Pastikan menggunakan kode terbaru:
   ```javascript
   const componentName = form.value.comp_no || 'Main';
   ```

### Issue 5: Payload `comp_no` Masih "Main"

**Penyebab:** `componentName` salah atau payload tidak menggunakan `componentName`

**Solusi:**
1. Cek log "🔍 Save Master Card - Component Info" - componentName harus benar
2. Pastikan payload menggunakan:
   ```javascript
   comp_no: componentName,  // Bukan hardcoded 'Main'
   ```

## Verification Checklist

Sebelum melaporkan masalah, pastikan:

- [ ] Browser cache sudah di-clear
- [ ] Page sudah di-refresh (hard reload)
- [ ] Console sudah dibuka (F12)
- [ ] Row component sudah di-klik (ter-highlight biru)
- [ ] Semua expected logs muncul di console
- [ ] `form.comp_no` menunjukkan component yang benar
- [ ] `componentName` menunjukkan component yang benar
- [ ] Payload `comp_no` menunjukkan component yang benar
- [ ] Database menunjukkan COMP yang benar

## Quick Test Script

Copy paste ini di browser console untuk quick test:

```javascript
// Test 1: Cek form.comp_no
console.log('Current form.comp_no:', form.value?.comp_no);

// Test 2: Cek mcComponents state
console.log('mcComponents state:', 
    mcComponents.value?.map(c => ({ 
        name: c.c_num, 
        selected: c.selected 
    }))
);

// Test 3: Cek selected component
const selected = mcComponents.value?.find(c => c.selected);
console.log('Selected component:', selected?.c_num || 'NONE');

// Test 4: Cek component by form.comp_no
const byCompNo = mcComponents.value?.find(c => c.c_num === form.value?.comp_no);
console.log('Component by form.comp_no:', byCompNo?.c_num || 'NOT FOUND');
```

Expected Output (setelah klik Fit1):
```
Current form.comp_no: "Fit1"
mcComponents state: [
    { name: "Main", selected: false },
    { name: "Fit1", selected: true },
    ...
]
Selected component: "Fit1"
Component by form.comp_no: "Fit1"
```

## Summary

**Perbaikan Utama:**
1. ✅ Menggunakan `form.comp_no` langsung (lebih reliable)
2. ✅ Menambahkan logging lengkap untuk debugging
3. ✅ Emit dari modal ke parent sudah benar
4. ✅ Handler di parent sudah benar

**Yang Perlu Ditest:**
1. Klik row component → Cek console logs
2. Save master card → Cek payload
3. Verify database → Cek COMP column

**Jika Masih Bermasalah:**
1. Capture semua console logs
2. Capture screenshot UI
3. Capture database query result
4. Report dengan informasi lengkap
