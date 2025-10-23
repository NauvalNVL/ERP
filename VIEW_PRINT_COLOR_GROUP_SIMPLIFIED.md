# View & Print Color Group - Simplified to Match CPS Enterprise 2020

## Changes Made

Updated `view-and-print-color-group.vue` to match CPS Enterprise 2020 design with simple 3-column layout.

## Table Structure

### ✅ 3 Columns Only:
1. **CG#** - Color Group Code
2. **CG Name** - Color Group Name  
3. **CG Type** - Color Group Type

## Visual Changes

### Before (Modern Styling):
- Gray background header
- Rounded badge pills for CG Type with different colors
- No visible borders between cells
- Modern gradient effects

### After (CPS Enterprise Style):
- **Blue header** (`bg-blue-600`) with white text
- **Plain text** CG Type (no colored badges)
- **Visible borders** on all cells (`border border-gray-300`)
- **Alternating row colors**: 
  - Even rows: Light blue (`bg-blue-100`)
  - Odd rows: White (`bg-white`)
- **Hover effect**: Blue highlight (`hover:bg-blue-200`)

## Technical Changes

### 1. Table Header
```html
<!-- Before -->
<thead class="bg-gray-50">
    <th class="px-6 py-3 text-xs uppercase tracking-wider">

<!-- After -->
<thead>
    <tr class="bg-blue-600 text-white">
        <th class="px-4 py-2 font-semibold border border-gray-300">
```

### 2. Table Rows
```html
<!-- Before -->
<tr :class="{'bg-blue-50': index % 2 === 0}" class="hover:bg-blue-100">
    <td class="px-6 py-4 whitespace-nowrap">

<!-- After -->
<tr :class="index % 2 === 0 ? 'bg-blue-100' : 'bg-white'" class="hover:bg-blue-200">
    <td class="px-4 py-2 border border-gray-300">
```

### 3. CG Type Column
```html
<!-- Before - Colored Badge -->
<span class="px-2 py-1 rounded-full bg-blue-100 text-blue-800">
    {{ group.cg_type }}
</span>

<!-- After - Plain Text -->
<div class="text-sm text-gray-900">
    {{ group.cg_type }}
</div>
```

### 4. Removed Functions
- ❌ `getCGTypeClass()` - No longer needed (was used for colored badges)

## File Modified
- `resources/js/Pages/sales-management/system-requirement/standard-requirement/view-and-print-color-group.vue`

## Result

✅ **Simple, clean table** matching CPS Enterprise 2020  
✅ **3 columns only**: CG#, CG Name, CG Type  
✅ **Bordered cells** for better data visibility  
✅ **Alternating row colors** for easier reading  
✅ **Sortable columns** maintained (click header to sort)  
✅ **Search functionality** maintained  
✅ **Print functionality** maintained  

## Sample Data Display

```
┌─────────┬──────────────┬────────────┐
│ CG#     │ CG Name      │ CG Type    │
├─────────┼──────────────┼────────────┤
│ 01      │ WHITE        │ X-Flexo    │ (blue background)
│ 02      │ BLACK        │ X-Flexo    │ (white background)
│ 03      │ RED          │ C-Coating  │ (blue background)
│ 04      │ BLUE         │ C-Coating  │ (white background)
│ ...     │ ...          │ ...        │
└─────────┴──────────────┴────────────┘
```

## Testing

1. ✅ Navigate to "View & Print Color Group"
2. ✅ Verify table shows 3 columns only
3. ✅ Verify alternating blue/white rows
4. ✅ Verify bordered cells
5. ✅ Test sorting by clicking headers
6. ✅ Test search functionality
7. ✅ Test print button
