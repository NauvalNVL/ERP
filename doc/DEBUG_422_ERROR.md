# Debugging 422 Error - Update MasterCard

## Changes Made for Better Error Visibility

### Backend Changes (`UpdateMcController.php`)

1. **Added Request Logging:**
```php
Log::info('UpdateMC Store Request', [
    'has_mspData' => $request->has('mspData'),
    'mspData' => $request->input('mspData')
]);
```

2. **Enhanced Error Response:**
```php
return response()->json([
    'message' => 'Validation failed',
    'errors' => $e->errors(),
    'debug' => [
        'received_fields' => array_keys($request->all()),
        'missing_validations' => array_diff(
            array_keys($request->all()), 
            array_keys($e->validator->getRules())
        )
    ]
], 422);
```

### Frontend Changes (`update-mc.vue`)

**Enhanced Error Logging:**
```javascript
catch (e) {
    console.error('Save MasterCard Error:', e);
    console.error('Error Response:', e.response?.data);
    
    if (e.response?.data?.errors) {
        console.error('Validation Errors:', e.response.data.errors);
    }
    if (e.response?.data?.debug) {
        console.error('Debug Info:', e.response.data.debug);
        console.error('Received Fields:', e.response.data.debug.received_fields);
        console.error('Missing Validations:', e.response.data.debug.missing_validations);
    }
}
```

## How to Debug

### Step 1: Open Browser Console
1. Press F12 to open Developer Tools
2. Go to "Console" tab
3. Clear console (click trash icon)

### Step 2: Try to Save MasterCard
1. Fill in the form
2. Click "Save MasterCard"
3. Wait for error

### Step 3: Check Console Output

You should now see detailed error information:

```
Save MasterCard Error: [Error object]
Error Response: {
    message: "Validation failed",
    errors: {
        field_name: ["error message"]
    },
    debug: {
        received_fields: ["field1", "field2", ...],
        missing_validations: ["field_that_needs_validation"]
    }
}
Validation Errors: {...}
Debug Info: {...}
Received Fields: [...]
Missing Validations: [...]
```

### Step 4: Identify the Problem

**Look for:**

1. **Validation Errors** - Which fields failed validation?
   - Example: `{"mc_seq": ["The mc seq field is required."]}`
   - Solution: Make sure the field is filled in

2. **Missing Validations** - Which fields don't have validation rules?
   - Example: `["someNewField"]`
   - Solution: Add validation rule in `UpdateMcController.php`

3. **Received Fields** - What fields are being sent?
   - Check if all required fields are present
   - Check if any unexpected fields are being sent

### Step 5: Check Laravel Logs

```bash
# Windows (PowerShell)
Get-Content storage/logs/laravel.log -Tail 50 -Wait

# Or open the file directly
notepad storage/logs/laravel.log
```

Look for:
- `UpdateMC Store Request` - Shows what data was received
- `Validation Error in UpdateMC Store` - Shows validation errors

## Common Issues and Solutions

### Issue 1: Required Field Missing
**Error:** `{"mc_seq": ["The mc seq field is required."]}`

**Solution:** 
- Check that `form.value.mcs` has a value
- Make sure the field is not empty before saving

### Issue 2: Invalid Enum Value
**Error:** `{"status": ["The selected status is invalid."]}`

**Solution:**
- Status must be exactly "Active" or "Obsolete" (case-sensitive)
- mc_approval must be exactly "Yes" or "No" (case-sensitive)

### Issue 3: Field Not Validated
**Error in Debug:** `missing_validations: ["newField"]`

**Solution:**
Add to `UpdateMcController.php` validation:
```php
'newField' => 'nullable|string', // or appropriate rule
```

### Issue 4: Wrong Data Type
**Error:** `{"mcGrossM2PerPcs": ["The mc gross m2 per pcs must be a number."]}`

**Solution:**
- Make sure numeric fields contain numbers, not strings
- Check that arrays are actually arrays, not objects

## Current Validation Rules

All fields in `UpdateMcController.php` validation:

```php
'mc_seq' => 'required|string',
'customer_code' => 'required|string|max:20',
'mc_model' => 'nullable|string',
'mc_short_model' => 'nullable|string',
'status' => 'required|string|in:Active,Obsolete',
'mc_approval' => 'required|string|in:Yes,No',
'part_no' => 'nullable|string',
'comp_no' => 'nullable|string',
'p_design' => 'nullable|string',
'ext_dim_1' => 'nullable|string',
'ext_dim_2' => 'nullable|string',
'ext_dim_3' => 'nullable|string',
'int_dim_1' => 'nullable|string',
'int_dim_2' => 'nullable|string',
'int_dim_3' => 'nullable|string',
'detailed_master_card' => 'nullable|array',
'pd_setup' => 'nullable|array',
'selectedProductDesign' => 'nullable|string',
'selectedPaperFlute' => 'nullable|string',
'selectedChemicalCoat' => 'nullable|string',
'selectedReinforcementTape' => 'nullable|string',
'selectedPaperSize' => 'nullable|string',
'selectedScoringToolCode' => 'nullable|string',
'printColorCodes' => 'nullable|array',
'scoreL' => 'nullable|array',
'scoreW' => 'nullable|array',
'sheetLength' => 'nullable|string',
'sheetWidth' => 'nullable|string',
'conOut' => 'nullable|string',
'convDuctX2' => 'nullable|string',
'slitOut' => 'nullable|string',
'dieOut' => 'nullable|string',
'pcsToJoint' => 'nullable|string',
'mcGrossM2PerPcs' => 'nullable|numeric',
'mcNetM2PerPcs' => 'nullable|numeric',
'mcGrossKgPerSet' => 'nullable|numeric',
'mcNetKgPerPcs' => 'nullable|numeric',
'id' => 'nullable|array',
'ed' => 'nullable|array',
'pcsPerSet' => 'nullable|string',
'creaseValue' => 'nullable|string',
'nestSlot' => 'nullable|string',
'dcutSheet' => 'nullable|array',
'dcutMould' => 'nullable|array',
'dcutBlockNo' => 'nullable|string',
'pitBlockNo' => 'nullable|string',
'stitchWirePieces' => 'nullable|string',
'bdlPerPallet' => 'nullable|string',
'peelOffPercent' => 'nullable|string',
'itemRemark' => 'nullable|string',
'handHole' => 'nullable|boolean',
'rotaryDCut' => 'nullable|boolean',
'fullBlockPrint' => 'nullable|boolean',
'selectedFinishingCode' => 'nullable|string',
'selectedStitchWireCode' => 'nullable|string',
'selectedBundlingStringCode' => 'nullable|string',
'bundlingStringQty' => 'nullable|string',
'selectedGlueingCode' => 'nullable|string',
'selectedWrappingCode' => 'nullable|string',
'soValues' => 'nullable|array',
'woValues' => 'nullable|array',
'specialInstructions' => 'nullable|array',
'moreDescriptions' => 'nullable|array',
'mcSpecialInst1' => 'nullable|string',
'mcSpecialInst2' => 'nullable|string',
'mcSpecialInst3' => 'nullable|string',
'mcSpecialInst4' => 'nullable|string',
'mcMoreDescription1' => 'nullable|string',
'mcMoreDescription2' => 'nullable|string',
'mcMoreDescription3' => 'nullable|string',
'mcMoreDescription4' => 'nullable|string',
'mcMoreDescription5' => 'nullable|string',
'mspData' => 'nullable|array',
'components' => 'nullable|array',
'subMaterials' => 'nullable|array',
'colorAreaPercents' => 'nullable|array',
'partNo' => 'nullable|string',
```

## Next Steps

1. **Try saving again** - Check browser console for detailed error
2. **Copy the error output** - Share it if you need help
3. **Check Laravel logs** - Look for more details
4. **Add missing validations** - If any fields are missing

## Files Modified

1. ✅ `app/Http/Controllers/UpdateMcController.php`
   - Added logging
   - Enhanced error response
   - Added validation for components, subMaterials, etc.

2. ✅ `resources/js/Pages/sales-management/system-requirement/master-card/update-mc.vue`
   - Enhanced error logging in console
   - Display debug information

## Testing

After making these changes:
1. Clear browser cache (Ctrl+Shift+Delete)
2. Refresh the page (Ctrl+F5)
3. Try saving MasterCard again
4. Check console for detailed error information
5. Share the console output if you need help debugging
