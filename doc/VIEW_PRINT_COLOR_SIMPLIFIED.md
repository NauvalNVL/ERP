# View & Print Color - Simplified to Match CPS Enterprise 2020

## Changes Made

Updated `view-and-print-color.vue` to match CPS Enterprise 2020 design with simple 5-column layout.

## Table Structure

### ✅ 5 Columns Only (Matching CPS):
1. **Color#** - Color Code/ID
2. **Color Name** - Name of the color
3. **Origin** - Origin code (e.g., ID, CN)
4. **CG#** - Color Group Code
5. **KG Per M2** - Weight per square meter (default: 1.0000)

### ❌ Removed Columns:
- CG Type (removed - not in CPS)
- Created At (removed - not in CPS)
- Updated At (removed - not in CPS)

## Visual Changes

### Before (Modern Complex Styling):
- Gray background header
- 7 columns with timestamps
- Small padding, compact layout
- No visible borders between cells

### After (CPS Enterprise Style):
- **Blue header** (`bg-blue-600`) with white text
- **5 columns only** (matching CPS exactly)
- **Visible borders** on all cells (`border border-gray-300`)
- **Alternating row colors**:
  - Even rows: Light blue (`bg-blue-100`)
  - Odd rows: White (`bg-white`)
- **Hover effect**: Blue highlight (`hover:bg-blue-200`)
- **Simple, clean design** matching CPS aesthetic

## Technical Changes

### 1. Table Header
```html
<!-- After - Blue header with 5 columns -->
<thead>
    <tr class="bg-blue-600 text-white">
        <th class="px-4 py-2 font-semibold border border-gray-300">Color#</th>
        <th class="px-4 py-2 font-semibold border border-gray-300">Color Name</th>
        <th class="px-4 py-2 font-semibold border border-gray-300">Origin</th>
        <th class="px-4 py-2 font-semibold border border-gray-300">CG#</th>
        <th class="px-4 py-2 font-semibold border border-gray-300">KG Per M2</th>
    </tr>
</thead>
```

### 2. Table Rows
```html
<!-- After - Bordered cells with alternating colors -->
<tr :class="index % 2 === 0 ? 'bg-blue-100' : 'bg-white'" class="hover:bg-blue-200">
    <td class="px-4 py-2 border border-gray-300">{{ color.color_id }}</td>
    <td class="px-4 py-2 border border-gray-300">{{ color.color_name }}</td>
    <td class="px-4 py-2 border border-gray-300">{{ color.origin || 'ID' }}</td>
    <td class="px-4 py-2 border border-gray-300">{{ color.color_group_id }}</td>
    <td class="px-4 py-2 border border-gray-300">{{ color.kg_per_m2 || '1.0000' }}</td>
</tr>
```

### 3. Removed Functions
- ❌ `formatDate()` - No longer needed (no date columns)
- ❌ `getCGName()` - Simplified to show code directly instead of looking up name

### 4. Simplified Filtering
```javascript
// Removed CG Type and date handling from filter
filtered = filtered.filter(color => 
    color.color_id.toLowerCase().includes(query) ||
    color.color_name.toLowerCase().includes(query) ||
    color.origin.toLowerCase().includes(query) ||
    color.color_group_id.toLowerCase().includes(query)
);
```

### 5. Simplified Sorting
```javascript
// Removed special date handling and CG name lookup
filtered.sort((a, b) => {
    let valueA = a[sortColumn.value];
    let valueB = b[sortColumn.value];
    // Simple string comparison
});
```

## Files Modified
- `resources/js/Pages/sales-management/system-requirement/standard-requirement/view-and-print-color.vue`

## Data Fields

### Expected Color Object:
```javascript
{
    color_id: "BK01",           // Color code
    color_name: "Black Matte",  // Color name
    origin: "ID",               // Origin code
    color_group_id: "BK",       // Color group code
    kg_per_m2: "1.0000"        // Weight (optional, defaults to 1.0000)
}
```

## Result

✅ **Simple, clean table** matching CPS Enterprise 2020  
✅ **5 columns only**: Color#, Color Name, Origin, CG#, KG Per M2  
✅ **Bordered cells** for better data visibility  
✅ **Alternating row colors** for easier reading  
✅ **Sortable columns** (Color#, Name, Origin, CG#)  
✅ **Search functionality** maintained  
✅ **Print functionality** maintained  

## Sample Data Display

```
┌──────────┬───────────────┬────────┬──────┬───────────┐
│ Color#   │ Color Name    │ Origin │ CG#  │ KG Per M2 │
├──────────┼───────────────┼────────┼──────┼───────────┤
│ BK01     │ Black Matte   │ ID     │ BK   │ 1.0000    │ (blue)
│ BK02     │ Black Glossy  │ ID     │ BK   │ 1.0000    │ (white)
│ RD01     │ Red Standard  │ ID     │ R    │ 1.0000    │ (blue)
│ RD02     │ Red Bright    │ ID     │ R    │ 1.0000    │ (white)
│ ...      │ ...           │ ...    │ ...  │ ...       │
└──────────┴───────────────┴────────┴──────┴───────────┘
```

## Testing

1. ✅ Navigate to "View & Print Color"
2. ✅ Verify table shows 5 columns only
3. ✅ Verify alternating blue/white rows
4. ✅ Verify bordered cells
5. ✅ Test sorting by clicking headers
6. ✅ Test search functionality
7. ✅ Test print button

## Rebuild Required

After changes, run:
```bash
npm run build
```

Then hard refresh browser (Ctrl + Shift + R) to see changes.
