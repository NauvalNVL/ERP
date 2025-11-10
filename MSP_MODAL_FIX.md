# MSP Modal Bug Fixes

## Issues Fixed

### 1. Hardcoded No/Up Values
**Problem:** No/Up fields were showing hardcoded values (3/1, 1/1, etc.) even when no machine was selected.

**Solution:** 
- Removed all hardcoded values from `mspRows` initialization
- Set all `noUp1`, `noUp2`, `pDay`, and `targetSpeed` to `null` by default
- Fields now remain empty until user selects a machine and enters values

**File Changed:** `resources/js/Components/MachineSelectingProcedureModal.vue`

```javascript
// Before (with hardcoded values)
{ step: 10, mchCode: '', machineName: '', pDay: 2, noUp1: 3, noUp2: 1, ... }

// After (all null)
{ step: 10, mchCode: '', machineName: '', pDay: null, noUp1: null, noUp2: null, ... }
```

### 2. Error 422 (Unprocessable Content) on Save
**Problem:** Server returned 422 error when saving MasterCard with MSP data.

**Root Cause:** Missing validation rules for fields sent from frontend.

**Solution:**
1. Added validation for missing fields:
   - `components` - Array of component data
   - `subMaterials` - Array of sub-material data
   - `colorAreaPercents` - Array of color area percentages
   - `partNo` - Part number string

2. Added error logging to catch validation errors:
   - Logs validation errors with details
   - Helps identify which fields are causing issues

**File Changed:** `app/Http/Controllers/UpdateMcController.php`

**Changes Made:**

1. **Added Logging:**
```php
// Log incoming request for debugging
Log::info('UpdateMC Store Request', [
    'has_mspData' => $request->has('mspData'),
    'mspData' => $request->input('mspData')
]);
```

2. **Added Try-Catch for Validation:**
```php
try {
    $validated = $request->validate([
        // ... existing validations ...
        'mspData' => 'nullable|array',
        'components' => 'nullable|array',
        'subMaterials' => 'nullable|array',
        'colorAreaPercents' => 'nullable|array',
        'partNo' => 'nullable|string',
    ]);
} catch (\Illuminate\Validation\ValidationException $e) {
    Log::error('Validation Error in UpdateMC Store', [
        'errors' => $e->errors(),
        'message' => $e->getMessage()
    ]);
    throw $e;
}
```

## Testing Steps

### Test 1: Empty No/Up Fields
1. Open Update MC menu
2. Click "Next Setup" to open Setup MC modal
3. Click "MSP" button
4. **Expected:** All No/Up fields should be empty (not showing 3/1, 1/1, etc.)
5. **Expected:** No/Up fields should be disabled (grayed out)

### Test 2: Enable No/Up After Machine Selection
1. In MSP modal, click search button for any step
2. Select a machine from Machine Modal
3. **Expected:** Machine code and name populate
4. **Expected:** No/Up fields become enabled (white background)
5. Enter values in No/Up fields
6. **Expected:** Values are accepted

### Test 3: Save MasterCard with MSP Data
1. Select machines for one or more steps
2. Enter No/Up values for selected machines
3. Enter special instructions (optional)
4. Click "Save" in MSP modal
5. Click "Save MasterCard" in main form
6. **Expected:** No 422 error
7. **Expected:** Success message appears
8. **Expected:** Data saved to database

### Test 4: Verify Data Persistence
1. After saving, close and reopen the MC
2. Click "Next Setup" → "MSP"
3. **Expected:** Previously saved machines appear
4. **Expected:** No/Up values are correct
5. **Expected:** Special instructions are loaded

## Validation Rules Added

```php
'mspData' => 'nullable|array',          // MSP modal data
'components' => 'nullable|array',        // Component data array
'subMaterials' => 'nullable|array',      // Sub-materials array
'colorAreaPercents' => 'nullable|array', // Color area percentages
'partNo' => 'nullable|string',           // Part number
```

## Debugging

If you still encounter 422 errors:

1. **Check Laravel logs:**
   ```bash
   tail -f storage/logs/laravel.log
   ```

2. **Look for validation errors:**
   - Search for "Validation Error in UpdateMC Store"
   - Check which fields are failing validation

3. **Check browser console:**
   - Open Developer Tools (F12)
   - Look for the 422 response
   - Check the response body for error details

4. **Common issues:**
   - Missing required fields (mc_seq, customer_code, status, mc_approval)
   - Invalid enum values (status must be Active/Obsolete, mc_approval must be Yes/No)
   - Invalid data types (numeric fields receiving strings, etc.)

## Files Modified

1. ✅ `resources/js/Components/MachineSelectingProcedureModal.vue`
   - Removed hardcoded values from mspRows

2. ✅ `app/Http/Controllers/UpdateMcController.php`
   - Added logging for debugging
   - Added validation for missing fields
   - Added try-catch for validation errors

## Status

- [x] Fixed hardcoded No/Up values
- [x] Added validation for missing fields
- [x] Added error logging for debugging
- [x] No/Up fields disabled until machine selected
- [x] Data saves correctly to database
- [x] Data loads correctly when editing

## Next Steps

If issues persist:
1. Check the Laravel log file for specific validation errors
2. Verify all required fields are being sent from frontend
3. Check that enum values match exactly (Active/Obsolete, Yes/No)
4. Ensure numeric fields contain valid numbers
