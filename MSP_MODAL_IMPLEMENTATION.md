# Machine Selecting Procedure (MSP) Modal Implementation

## Overview
Implemented a comprehensive Machine Selecting Procedure (MSP) modal for the Update MC menu that allows users to select machines for each production process step. The selected machine data is saved to the MC table fields (msp1_mch, msp1_up, msp1_special_inst, etc.).

## Changes Made

### 1. **New Component Created**
**File:** `resources/js/Components/MachineSelectingProcedureModal.vue`

**Features:**
- Modern, responsive modal design matching the provided image
- Release Mode selection (Run Production / No Production)
- Max FG Level configuration
- Machine selection table with 8 steps (10, 20, 30, 40, 50, 60, 70, 80)
- Integration with Machine Modal for selecting machines from database
- Real-time calculations for accumulated totals
- Support for up to 11 machine selections (MSP1-MSP11)

**Table Columns:**
- Step
- Mch Code (with search button)
- Machine Name
- P/Day
- No /Up (split into two fields)
- Setup Min
- Setup +/-
- Net Min (calculated)
- Target Speed
- Speed +/-
- Net Speed (calculated)
- Special Instruction

**Data Structure:**
```javascript
{
  releaseMode: 'run' | 'no',
  maxFgLevel: number,
  machines: [
    {
      step: number,
      mchCode: string,
      machineName: string,
      noUp: string, // format: "3/1"
      specialInstruction: string
    }
  ]
}
```

### 2. **UpdateMcModal.vue Integration**
**File:** `resources/js/Components/UpdateMcModal.vue`

**Changes:**
- Enabled MSP button (previously disabled)
- Added import for `MachineSelectingProcedureModal`
- Added state management for MSP modal
- Added `openMspModal()` function
- Added `onMspSave()` function to handle MSP data
- Integrated MSP modal component in template
- Added MSP data to the payload sent to backend

**Button Change:**
```vue
<!-- Before -->
<button class="px-3 py-1 bg-gray-200 border border-gray-400 text-xs font-bold cursor-default" disabled>MSP</button>

<!-- After -->
<button class="px-3 py-1 bg-blue-500 hover:bg-blue-600 border border-blue-600 text-xs font-bold text-white transition-colors" @click="openMspModal">MSP</button>
```

### 3. **Backend Controller Updates**
**File:** `app/Http/Controllers/UpdateMcController.php`

**Changes:**
- Added validation for `mspData` field
- Enhanced MSP data processing logic to handle new modal format
- Maintained backward compatibility with old MSP format
- Maps MSP data to MC table fields:
  - `MSP{n}_MCH` - Machine code
  - `MSP{n}_UP` - No/Up value (format: "3/1")
  - `MSP{n}_SPECIAL_INST` - Special instruction

**Processing Logic:**
```php
$mspData = $validated['mspData'] ?? null;
if (is_array($mspData) && isset($mspData['machines'])) {
    foreach ($mspData['machines'] as $index => $machine) {
        $mspNum = $index + 1;
        if ($mspNum <= 12) {
            $legacy["MSP{$mspNum}_MCH"] = $machine['mchCode'] ?? null;
            $legacy["MSP{$mspNum}_UP"] = $machine['noUp'] ?? null;
            $legacy["MSP{$mspNum}_SPECIAL_INST"] = $machine['specialInstruction'] ?? null;
        }
    }
}
```

### 4. **API Routes**
**File:** `routes/api.php`

**Added:**
```php
// Machine API routes
Route::get('/machines', [MachineController::class, 'index']);
```

This route fetches machine data from the `machine` table created in the Define Machine menu.

## Database Structure

### MC Table Fields (Already Existing)
The following fields in the MC table store MSP data:

```sql
MSP1_MCH VARCHAR(50)
MSP1_UP VARCHAR(50)
MSP1_SPECIAL_INST VARCHAR(250)
MSP2_MCH VARCHAR(50)
MSP2_UP VARCHAR(50)
MSP2_SPECIAL_INST VARCHAR(250)
...
MSP11_MCH VARCHAR(50)
MSP11_UP VARCHAR(50)
MSP11_SPECIAL_INST VARCHAR(250)
MSP12_MCH VARCHAR(50)
MSP12_UP VARCHAR(50)
MSP12_SPECIAL_INST VARCHAR(250)
```

### Machine Table (Source Data)
Machine data is sourced from the `machine` table with fields:
- `machine_code` - Machine code
- `machine_name` - Machine name
- `process` - Process type (e.g., "10 - CORRUGATING")
- `sub_process` - Sub-process type
- `resource_type` - Resource type (I-InHouse, E-External)
- `finisher_type` - Finisher type

## Data Flow

1. **User clicks MSP button** in Setup MC modal
2. **MachineSelectingProcedureModal opens**
3. **Modal fetches machines** from `/api/machines` endpoint
4. **User selects machines** for each step using the search button
5. **User fills in** No/Up values and special instructions
6. **User clicks Save**
7. **MSP data is emitted** to parent component (UpdateMcModal)
8. **MSP data is included** in the payload when saving Master Card
9. **Backend processes MSP data** and saves to MC table fields
10. **Data is persisted** to database

## Usage Instructions

### For Users:
1. Open Update MC menu
2. Select customer and MCS number
3. Click "Next Setup" to open Setup MC modal
4. Click the **MSP** button (blue button in the bottom section)
5. In the MSP modal:
   - Select Release Mode (Run Production / No Production)
   - Set Max FG Level if needed
   - For each step, click the search button next to Mch Code
   - Select a machine from the Machine Modal
   - Enter No/Up values (format: number/number)
   - Add special instructions if needed
6. Click **Save** to apply MSP data
7. Click **Save MasterCard** to persist all changes

### For Developers:
- MSP modal component: `resources/js/Components/MachineSelectingProcedureModal.vue`
- Integration point: `resources/js/Components/UpdateMcModal.vue`
- Backend processing: `app/Http/Controllers/UpdateMcController.php`
- API endpoint: `GET /api/machines`
- Database fields: `MC.MSP{1-12}_MCH`, `MC.MSP{1-12}_UP`, `MC.MSP{1-12}_SPECIAL_INST`

## Features

### Modal Features:
- ✅ Responsive design with modern UI
- ✅ Machine selection from database
- ✅ Support for 8 production steps
- ✅ Real-time calculations for totals
- ✅ Special instruction input for each step
- ✅ Release mode configuration
- ✅ Max FG Level setting
- ✅ Data validation
- ✅ Integration with existing MC save flow

### Backend Features:
- ✅ Validation for MSP data
- ✅ Backward compatibility with old format
- ✅ Proper data mapping to MC table
- ✅ Transaction safety
- ✅ Error handling

## Recent Updates (Latest)

### Field Validation & Data Loading
**Date:** Current Session

**Changes:**
1. **No/Up Fields Disabled Until Machine Selected**
   - No/Up input fields are now disabled (grayed out) until a machine is selected
   - Once machine code is populated, the fields become editable
   - Visual feedback with gray background when disabled

2. **Improved Data Loading**
   - Existing MSP data from MC table now loads correctly when editing
   - Machine names are automatically populated from the machines API
   - Data parsing improved for No/Up field (handles "3/1" format)

3. **Data Saving Enhancement**
   - Only machines with selected mchCode are saved to database
   - Empty/unselected rows are filtered out before saving
   - All MSP fields are cleared first, then new selections are saved
   - Prevents orphaned data in database

**Code Changes:**
- `MachineSelectingProcedureModal.vue`: Added `:disabled="!row.mchCode"` to No/Up inputs
- `UpdateMcModal.vue`: Enhanced `openMspModal()` to load existing MSP data from `mcLoaded`
- `UpdateMcController.php`: Clear all MSP fields before saving new data

## Testing Checklist

- [x] MSP button opens the modal correctly
- [x] Machine search button opens Machine Modal
- [x] Selected machine populates Mch Code and Machine Name
- [x] No/Up fields are disabled until machine is selected
- [x] No/Up fields become enabled after machine selection
- [x] No/Up fields accept numeric input
- [x] Special instruction field accepts text
- [x] Save button stores data correctly
- [x] Only selected machines are saved to database
- [x] Data persists to MC table fields
- [x] Existing MSP data loads correctly when editing
- [x] Machine names are populated from API
- [x] Modal closes properly on Cancel
- [x] API endpoint returns machine data

## Notes

- The modal supports up to 11 machines (MSP1-MSP11), though the database has fields for MSP12
- Machine data must be created in the Define Machine menu first
- The No/Up field stores data in "number/number" format (e.g., "3/1")
- Special instructions can be up to 250 characters
- The modal uses the existing MachineModal component for machine selection
- All changes are part of the MC save transaction for data integrity

## Related Files

### Frontend:
- `resources/js/Components/MachineSelectingProcedureModal.vue` (NEW)
- `resources/js/Components/UpdateMcModal.vue` (MODIFIED)
- `resources/js/Components/MachineModal.vue` (EXISTING - Used for selection)
- `resources/js/Pages/sales-management/system-requirement/standard-requirement/machine.vue` (EXISTING - Source data)

### Backend:
- `app/Http/Controllers/UpdateMcController.php` (MODIFIED)
- `app/Http/Controllers/MachineController.php` (EXISTING)
- `routes/api.php` (MODIFIED)

### Database:
- `database/migrations/2025_09_30_000000_create_mc_table.php` (EXISTING)
- Table: `MC` (MSP fields already exist)
- Table: `machine` (Source data)
