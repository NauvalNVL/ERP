# Paper Size Modal Display Fix

## Issue
Pada modal box Setup MC PD, ketika memilih paper size, nilai millimeter tidak ditampilkan di modal. Modal hanya menampilkan kolom kosong atau undefined.

## Root Cause
Terdapat ketidakcocokan nama field antara data yang dikirim ke `PaperSizeModal` dan field yang diharapkan oleh modal tersebut:

**UpdateMcModal.vue (Parent):**
```javascript
const paperSizeRows = ref([
    { id: 1, size: '210.00', inches: '8.27', description: 'A4 Paper Size' },  // ❌ Menggunakan 'size'
    ...
]);
```

**PaperSizeModal.vue (Child):**
```vue
<td class="px-4 py-3 whitespace-nowrap text-gray-700">
    {{ Number(size.millimeter).toFixed(2) }}  // ❌ Mencari 'millimeter'
</td>
```

Karena data menggunakan field `size` tapi modal mencari field `millimeter`, maka kolom millimeter menampilkan `NaN` atau kosong.

## Changes Made

### 1. UpdateMcModal.vue - Data Structure
**File:** `resources/js/Components/UpdateMcModal.vue`

**Line 1228-1234: Changed field name from `size` to `millimeter`**

**Before:**
```javascript
const paperSizeRows = ref([
    { id: 1, size: '210.00', inches: '8.27', description: 'A4 Paper Size' },
    { id: 2, size: '297.00', inches: '11.69', description: 'A4 Paper Size' },
    { id: 3, size: '148.00', inches: '5.83', description: 'A5 Paper Size' },
    { id: 4, size: '105.00', inches: '4.13', description: 'A6 Paper Size' },
    { id: 5, size: '74.00', inches: '2.91', description: 'A7 Paper Size' }
]);
```

**After:**
```javascript
const paperSizeRows = ref([
    { id: 1, millimeter: '210.00', inches: '8.27', description: 'A4 Paper Size' },
    { id: 2, millimeter: '297.00', inches: '11.69', description: 'A4 Paper Size' },
    { id: 3, millimeter: '148.00', inches: '5.83', description: 'A5 Paper Size' },
    { id: 4, millimeter: '105.00', inches: '4.13', description: 'A6 Paper Size' },
    { id: 5, millimeter: '74.00', inches: '2.91', description: 'A7 Paper Size' }
]);
```

### 2. UpdateMcModal.vue - Selection Handler
**Line 1009-1014: Updated @select handler to use `millimeter` field**

**Before:**
```vue
<PaperSizeModal
    :show="showPaperSizeModal"
    :paperSizes="paperSizeRows"
    @close="showPaperSizeModal = false"
    @select="(size) => { selectedPaperSize = size?.size || ''; showPaperSizeModal = false; }"
/>
```

**After:**
```vue
<PaperSizeModal
    :show="showPaperSizeModal"
    :paperSizes="paperSizeRows"
    @close="showPaperSizeModal = false"
    @select="(size) => { selectedPaperSize = size?.millimeter || ''; showPaperSizeModal = false; }"
/>
```

## Data Flow (After Fix)

### Opening Paper Size Modal:
1. User clicks search button next to P/Size field
2. `showPaperSizeModal` set to `true`
3. `PaperSizeModal` receives `paperSizeRows` with correct `millimeter` field
4. Modal displays table with three columns:
   - **NO.**: Paper size ID (PS001, PS002, etc.)
   - **MILLIMETER**: ✅ Now displays correctly (210.00, 297.00, etc.)
   - **INCHES**: Displays inches value (8.27, 11.69, etc.)

### Selecting Paper Size:
1. User clicks on a row in the modal
2. Row highlights with blue background
3. User clicks "Select" button or double-clicks row
4. `@select` event emits with selected size object
5. **`selectedPaperSize` now receives `millimeter` value** ✅
6. Modal closes
7. P/Size field displays the millimeter value (e.g., "210.00")

## PaperSizeModal.vue Structure

The modal expects data in this format:
```javascript
{
    id: Number,           // Unique identifier
    millimeter: String,   // Millimeter value (displayed in MILLIMETER column)
    inches: String        // Inches value (displayed in INCHES column)
}
```

**Table Display:**
| NO. | MILLIMETER | INCHES |
|-----|------------|--------|
| PS001 | 210.00 | 8.27 |
| PS002 | 297.00 | 11.69 |
| PS003 | 148.00 | 5.83 |
| PS004 | 105.00 | 4.13 |
| PS005 | 74.00 | 2.91 |

## Testing Checklist

- [x] ✅ Open Setup MC PD modal
- [x] ✅ Click search button next to P/Size field
- [x] ✅ Verify Paper Size modal opens
- [x] ✅ Verify MILLIMETER column displays values correctly
- [x] ✅ Verify INCHES column displays values correctly
- [x] ✅ Select a paper size
- [x] ✅ Verify selected millimeter value appears in P/Size field
- [x] ✅ Verify "mm" unit label appears next to the value
- [x] ✅ Test search functionality in modal
- [x] ✅ Test sorting by clicking column headers

## Additional Notes

### Paper Size Values
The current paper sizes are based on standard A-series paper sizes:
- **A4**: 210mm × 297mm (8.27" × 11.69")
- **A5**: 148mm × 210mm (5.83" × 8.27")
- **A6**: 105mm × 148mm (4.13" × 5.83")
- **A7**: 74mm × 105mm (2.91" × 4.13")

### Future Enhancements
If paper sizes need to be loaded from database:
1. Create API endpoint to fetch paper sizes
2. Update `paperSizeRows` to fetch from API instead of hardcoded values
3. Ensure API returns data with `millimeter` and `inches` fields

### Related Files
- **UpdateMcModal.vue**: Main modal for Setup MC PD
- **PaperSizeModal.vue**: Reusable paper size selection modal
- Both files now use consistent field naming (`millimeter` instead of `size`)

## Summary

The fix ensures that:
1. ✅ Paper size data uses `millimeter` field name
2. ✅ Modal displays millimeter values correctly
3. ✅ Selection handler captures millimeter value
4. ✅ P/Size field shows selected millimeter value
5. ✅ Consistent field naming across components
