# Update MC Component Configuration Fix

## Problem Summary
When configuring Fit1-Fit9 components in the Setup MC Component modal, users encountered:
1. **CSRF Token Mismatch Error** - 419 errors when saving component configurations
2. **Single Row Limitation** - Only one row per MC number was saved, preventing multiple component configurations

## Solution Implemented

### 1. CSRF Token Handling

#### Updated Files:
- `resources/js/bootstrap.js`
- `resources/js/Pages/sales-management/system-requirement/master-card/update-mc.vue`

#### Changes Made:

**bootstrap.js:**
- Added request interceptor to refresh CSRF token before each request
- Added response interceptor to handle 419 errors gracefully
- Implemented `getCsrfToken()` function to fetch fresh token from meta tag

**update-mc.vue:**
- Updated `saveMasterCardFromModal()` to explicitly include CSRF token in headers
- Updated `saveRecord()` to include CSRF token in headers
- Added better error handling for session expiration (419 errors)
- Improved success messages to show which component was saved

### 2. Multiple Component Support

#### Updated Files:
- `app/Http/Controllers/UpdateMcController.php`

#### Changes Made:

**UpdateMcController.php:**
Modified the `updateOrInsert` logic to include `COMP` field in the unique key:

```php
// Before (only 2 fields in unique key):
DB::table('MC')->updateOrInsert(
    [
        'MCS_Num' => $normalized['MCS_Num'],
        'AC_NUM' => $normalized['AC_NUM'],
    ],
    $normalized
);

// After (3 fields in unique key):
DB::table('MC')->updateOrInsert(
    [
        'MCS_Num' => $normalized['MCS_Num'],
        'AC_NUM' => $normalized['AC_NUM'],
        'COMP' => $normalized['COMP'] ?? 'Main',
    ],
    $normalized
);
```

## How It Works Now

### Component Configuration Flow:

1. **Select Customer Account (AC#)**
   - User selects customer from modal
   - Form populates with customer data

2. **Enter/Select Master Card Number (MCS#)**
   - User enters new MCS# or selects existing
   - Click "Proceed" to continue

3. **Configure Main Component**
   - Fill in MC Model, MC Short Model
   - Click "Next Setup" to open Setup MC Component modal

4. **Setup Multiple Components**
   - Select component (Main, Fit1, Fit2, etc.)
   - Click "Setup PD" to configure product design
   - Fill in all required fields
   - Click "Save Master Card"
   - **Result:** Creates/updates row in MC table with:
     - `MCS_Num` = MC number
     - `AC_NUM` = Customer code
     - `COMP` = Component name (Main, Fit1, Fit2, etc.)

5. **Add More Components**
   - Select different component (e.g., Fit1)
   - Repeat configuration
   - Save again
   - **Result:** Creates NEW row in MC table with same MCS_Num but different COMP value

### Database Structure:

```
MC Table:
+----------+--------+------+------------------+
| MCS_Num  | AC_NUM | COMP | Other Fields...  |
+----------+--------+------+------------------+
| MC001    | C001   | Main | ...              |
| MC001    | C001   | Fit1 | ...              |
| MC001    | C001   | Fit2 | ...              |
+----------+--------+------+------------------+
```

Each row represents a different component configuration for the same master card.

## Testing Instructions

### Test Case 1: New Master Card with Multiple Components

1. Open Update MC page
2. Select customer account
3. Enter new MCS# (e.g., "TEST001")
4. Click "Proceed"
5. Fill in MC Model and MC Short Model
6. Click "Next Setup"
7. In Setup MC Component modal:
   - Main component should be selected by default
   - Click "Setup PD"
   - Configure product design
   - Click "Save Master Card"
   - **Expected:** Success message "Master Card saved successfully for component: Main"
8. Select "Fit1" component
9. Click "Setup PD"
10. Configure different product design for Fit1
11. Click "Save Master Card"
    - **Expected:** Success message "Master Card saved successfully for component: Fit1"
12. Verify in database:
    ```sql
    SELECT MCS_Num, AC_NUM, COMP, MODEL, P_DESIGN 
    FROM MC 
    WHERE MCS_Num = 'TEST001'
    ORDER BY COMP;
    ```
    - **Expected:** 2 rows (Main and Fit1)

### Test Case 2: Existing Master Card - Add New Component

1. Open Update MC page
2. Select customer account
3. Search for existing MCS#
4. Select from list
5. Click "Proceed"
6. Click "Next Setup"
7. Select "Fit2" component (new component)
8. Click "Setup PD"
9. Configure product design
10. Click "Save Master Card"
    - **Expected:** Success message "Master Card saved successfully for component: Fit2"
11. Verify in database:
    - **Expected:** New row added with COMP = 'Fit2'

### Test Case 3: Update Existing Component

1. Open Update MC page
2. Select customer account
3. Search for existing MCS#
4. Select from list
5. Click "Proceed"
6. Click "Next Setup"
7. Select "Main" component (existing)
8. Click "Setup PD"
9. Modify product design fields
10. Click "Save Master Card"
    - **Expected:** Success message "Master Card saved successfully for component: Main"
11. Verify in database:
    - **Expected:** Existing Main row updated, not duplicated

### Test Case 4: CSRF Token Handling

1. Open Update MC page
2. Leave page open for extended period (to simulate session timeout)
3. Try to save a component
   - **Expected:** If session expired, show error "Session expired. Please refresh the page and try again."
4. Refresh page
5. Try to save again
   - **Expected:** Success

## Error Handling

### CSRF Token Errors (419)
- **Message:** "Session expired. Please refresh the page and try again."
- **Action:** User should refresh the page to get a new CSRF token

### Validation Errors (422)
- **Message:** Specific field validation errors
- **Action:** User should correct the invalid fields

### Server Errors (500)
- **Message:** "Failed to save Master Card"
- **Action:** Check server logs for details

## Technical Notes

### CSRF Token Flow:
1. Laravel generates CSRF token on page load
2. Token stored in meta tag: `<meta name="csrf-token" content="...">`
3. Axios interceptor reads token before each request
4. Token sent in header: `X-CSRF-TOKEN`
5. Laravel validates token on server side
6. If invalid/expired, returns 419 error

### Component Uniqueness:
- Unique key: `(MCS_Num, AC_NUM, COMP)`
- Same MC can have multiple components
- Each component stored as separate row
- Updates use `updateOrInsert` to prevent duplicates

## Files Modified

1. **app/Http/Controllers/UpdateMcController.php**
   - Modified `store()` method to include COMP in unique key

2. **resources/js/bootstrap.js**
   - Added axios interceptors for CSRF token handling
   - Improved error handling for 419 responses

3. **resources/js/Pages/sales-management/system-requirement/master-card/update-mc.vue**
   - Updated `saveMasterCardFromModal()` with CSRF token
   - Updated `saveRecord()` with CSRF token
   - Improved error messages
   - Better component name display in success messages

## Benefits

1. **Multiple Components:** One master card can now have multiple component configurations (Main + Fit1-Fit9)
2. **Better Error Handling:** Clear messages for CSRF token issues
3. **Automatic Token Refresh:** CSRF token refreshed before each request
4. **Data Integrity:** Unique key prevents duplicate components
5. **User Feedback:** Success messages show which component was saved

## Future Enhancements

1. Add component list view to show all components for a master card
2. Add delete component functionality
3. Add copy component configuration feature
4. Add component comparison view
5. Add bulk component operations

## Troubleshooting

### Issue: Still getting 419 errors
**Solution:** 
- Clear browser cache
- Check if meta tag exists: `document.querySelector('meta[name="csrf-token"]')`
- Verify Laravel session is working
- Check `config/session.php` settings

### Issue: Components not saving separately
**Solution:**
- Verify COMP field is being sent in payload
- Check database query logs
- Ensure `updateOrInsert` includes COMP in unique key

### Issue: Wrong component being updated
**Solution:**
- Verify correct component is selected in UI
- Check `mcComponents.value.find(c => c.selected)`
- Ensure component selection updates `comp_no` in payload
