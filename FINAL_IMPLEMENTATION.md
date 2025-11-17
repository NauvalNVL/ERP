# Master Card Component Data Loading - Final Implementation

## Problem Statement
Ketika memilih mastercard yang sudah ada di modal Update MC, data dari Fit1-9 components tidak ditampilkan di modal Setup MC Component. Hanya Main component yang terlihat, dan Fit components tidak muncul di baris yang sesuai.

## Root Cause
1. Data component dari database tidak di-fetch ketika modal dibuka
2. Field `mc_seq` atau `mcs` tidak tersedia di props `mcLoaded` dengan nama yang benar
3. Tidak ada mekanisme untuk memuat data dari database dan menampilkannya di table

## Solution Implemented

### 1. Frontend Changes (UpdateMcModal.vue)

#### a. Enhanced `fetchMcComponentsFromDb()` function
- Fetch data dari endpoint `/api/update-mc/master-cards/{mcSeq}/components`
- Support multiple field names untuk MC sequence: `mc_seq`, `mcs`, `MCS_Num`
- Support multiple field names untuk customer code: `ac`, `AC_NUM`
- Transform data dari database ke format UI
- Tempatkan setiap component di baris yang sesuai dengan index-nya
- Main → index 0, Fit1 → index 1, Fit2 → index 2, dst

**Key Features:**
- Comprehensive logging untuk debugging
- Validasi data sebelum fetch
- Error handling yang robust
- Fallback ke multiple field names

#### b. Watcher untuk modal open event
```javascript
watch(() => props.showSetupMcModal, async (newVal) => {
    if (newVal && props.mcLoaded) {
        await fetchMcComponentsFromDb();
    }
});
```

#### c. Enhanced logging
- Log data yang di-fetch dari database
- Log penempatan setiap component di baris yang benar
- Log jumlah rows dengan data
- Console output untuk debugging

### 2. Backend Changes (UpdateMcController.php)

#### Added `apiShowComponents()` method
```php
public function apiShowComponents($mcSeq, Request $request)
```

**Functionality:**
- Query MC table untuk semua components dengan MCS_Num yang sama
- Filter berdasarkan customer_code (optional)
- Order components: Main first (index 0), kemudian Fit1-9 (index 1-9)
- Transform data dengan field names yang konsisten
- Return JSON array dengan struktur:
  ```json
  [
    {
      "c_num": "Main",
      "comp_no": "Main",
      "pd": "B1",
      "p_design": "B1",
      "pcs_set": "1",
      "pcs": "1",
      "part_num": "PART001",
      "part_no": "PART001",
      "model": "MODEL001",
      "status": "Active"
    },
    {
      "c_num": "Fit1",
      "comp_no": "Fit1",
      "pd": "B0",
      ...
    }
  ]
  ```

**Logging:**
- Log ketika API dipanggil
- Log hasil query dari database
- Log jumlah components yang ditemukan
- Log detail setiap component

### 3. Routes (routes/api.php)

#### Added new route
```php
Route::get('/update-mc/master-cards/{mcSeq}/components', 
    [App\Http\Controllers\UpdateMcController::class, 'apiShowComponents']);
```

## Data Flow

```
1. User membuka Setup MC Modal dengan existing MC
   ↓
2. Watcher mendeteksi showSetupMcModal = true dan mcLoaded ada
   ↓
3. fetchMcComponentsFromDb() dipanggil
   ↓
4. Frontend extract mcSeq dan customerCode dari props
   ↓
5. Frontend fetch ke /api/update-mc/master-cards/{mcSeq}/components?customer_code={ac}
   ↓
6. Backend query MC table untuk semua components dengan MCS_Num yang sama
   ↓
7. Backend order components: Main first, kemudian Fit1-9
   ↓
8. Backend return JSON array dengan semua components
   ↓
9. Frontend transform data dan update localComponents
   ↓
10. UI table menampilkan:
    - Row 0: Main component dengan data dari database
    - Row 1: Fit1 component dengan data dari database
    - Row 2: Fit2 component dengan data dari database
    - ... dst sampai Row 9: Fit9
```

## Component Placement

| Row Index | Component | Database Field | UI Display |
|-----------|-----------|---|---|
| 0 | Main | COMP = 'Main' | Row 1 (NO = 01) |
| 1 | Fit1 | COMP = 'Fit1' | Row 2 (NO = 02) |
| 2 | Fit2 | COMP = 'Fit2' | Row 3 (NO = 03) |
| 3 | Fit3 | COMP = 'Fit3' | Row 4 (NO = 04) |
| 4 | Fit4 | COMP = 'Fit4' | Row 5 (NO = 05) |
| 5 | Fit5 | COMP = 'Fit5' | Row 6 (NO = 06) |
| 6 | Fit6 | COMP = 'Fit6' | Row 7 (NO = 07) |
| 7 | Fit7 | COMP = 'Fit7' | Row 8 (NO = 08) |
| 8 | Fit8 | COMP = 'Fit8' | Row 9 (NO = 09) |
| 9 | Fit9 | COMP = 'Fit9' | Row 10 (NO = 10) |

## Database Query

```sql
SELECT * FROM MC 
WHERE MCS_Num = ? 
AND AC_NUM = ? (optional)
ORDER BY CASE 
  WHEN COMP = 'Main' THEN 0 
  ELSE CAST(SUBSTRING(COMP, 4) AS UNSIGNED) + 1 
END
```

## Testing Steps

1. Buka Update MC page
2. Pilih customer account yang memiliki multiple master cards
3. Cari/pilih existing mastercard yang memiliki Fit components (Fit1, Fit2, dst)
4. Klik "Setup MC" button
5. Modal akan menampilkan:
   - Row 1: Main component dengan data dari database (PD, PCS/SET, PART#)
   - Row 2: Fit1 component dengan data dari database
   - Row 3: Fit2 component dengan data dari database
   - ... dst
6. Verify bahwa setiap component ditampilkan di baris yang benar sesuai dengan comp number-nya

## Debugging

### Console Logs
Frontend akan menampilkan:
```
🔍 fetchMcComponentsFromDb - Data check: {...}
🔄 Fetching MC components from database: {...}
✅ MC components fetched from database: [...]
📊 Processing fetched components: {...}
✓ Found Main at index 0: Main
✓ Found Fit1 at index 1: Fit1
📍 Row 0 (Main): {...}
📍 Row 1 (Fit1): {...}
✅ localComponents updated with fetched data: {...}
```

### Server Logs
Backend akan log:
```
apiShowComponents called: {...}
Components query result: {...}
Components fetched successfully: {...}
```

## Field Mapping

Database columns → UI fields:
- `COMP` → `c_num`, `comp_no`
- `P_DESIGN` → `pd`, `p_design`
- `PCS_SET` → `pcs_set`, `pcs`
- `PART_NO` → `part_num`, `part_no`
- `MODEL` → `model`
- `STS` → `status`

## Error Handling

1. **Missing mc_seq or customer_code**: Log warning dan return (tidak fetch)
2. **API call failed**: Log error dan return (tidak update UI)
3. **No components found**: Return empty array (tidak error)
4. **Customer code mismatch**: Return empty array (tidak error)

## Performance Considerations

- Single API call untuk fetch semua components sekaligus
- Efficient database query dengan proper ordering
- Minimal data transformation
- No N+1 queries

## Future Enhancements

1. Cache component data untuk mengurangi API calls
2. Add pagination jika ada banyak components
3. Add search/filter untuk components
4. Add bulk operations untuk components
5. Add component validation sebelum save
