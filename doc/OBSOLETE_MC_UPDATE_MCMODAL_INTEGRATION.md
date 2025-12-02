# Obsolete & Reactive MC - UpdateMcModal Integration

## 📋 Overview
Updated menu **Obsolete & Reactive MC** to use the same `UpdateMcModal.vue` component as **Update MC** menu for MCS# browsing functionality.

---

## 🎯 Objective
Memastikan kedua menu (Update MC dan Obsolete & Reactive MC) menggunakan modal box yang sama untuk konsistensi UI/UX dan maintainability.

---

## 🔄 Changes Made

### **1. Vue Component Updates**

#### **File:** `obsolete-reactive-mc.vue`

**A. Import Changes:**
```javascript
// Before
import MasterCardOptionsModal from '@/Components/MasterCardOptionsModal.vue';
import MasterCardSearchSelectModal from '@/Components/MasterCardSearchSelectModal.vue';
import MasterCardZoomModal from '@/Components/MasterCardZoomModal.vue';
import ProductModal from '@/Components/product-modal.vue';

// After
import UpdateMcModal from '@/Components/UpdateMcModal.vue';
import CustomerAccountModal from '@/Components/customer-account-modal.vue';
```

**B. Reactive State Updates:**
```javascript
// Before - Custom modal states
const showMcsSearchSelectModal = ref(false);
const showMcsZoomModal = ref(false);
const masterCards = ref([]);
const zoomedMasterCard = ref(null);

// After - UpdateMcModal states
const showMcsTableModal = ref(false);
const mcsSortOption = ref('mc_seq');
const mcsSortOrder = ref('asc');
const mcsStatusFilter = ref('Act');
const mcsSearchTerm = ref('');
const mcsLoading = ref(false);
const mcsError = ref(null);
const mcsMasterCards = ref([]);
const selectedMcs = ref(null);
const mcsCurrentPage = ref(1);
const mcsLastPage = ref(1);
```

**C. Function Updates:**

1. **openMcsLookup()** - Opens UpdateMcModal
```javascript
const openMcsLookup = (field) => {
    if (!form.value.ac) {
        showToast('Validation Error', 'Please fill AC# field first to browse Master Cards.', 'error');
        return;
    }
    showMcsTableModal.value = true;
    fetchMcsData();
};
```

2. **fetchMcsData()** - Fetch MCS with pagination
```javascript
const fetchMcsData = async () => {
    if (!form.value.ac) {
        mcsMasterCards.value = [];
        return;
    }

    mcsLoading.value = true;
    mcsError.value = null;

    try {
        const params = {
            ac: form.value.ac,
            sort: mcsSortOption.value || 'mc_seq',
            order: mcsSortOrder.value || 'asc',
            status: mcsStatusFilter.value || 'Act',
            search: mcsSearchTerm.value || '',
            page: mcsCurrentPage.value || 1,
        };

        const response = await axios.get('/api/mc/by-customer-paginated', { params });
        
        if (response.data) {
            mcsMasterCards.value = response.data.data || response.data;
            mcsCurrentPage.value = response.data.current_page || 1;
            mcsLastPage.value = response.data.last_page || 1;
        }
    } catch (error) {
        mcsError.value = error.response?.data?.message || 'Failed to load master cards';
        mcsMasterCards.value = [];
    } finally {
        mcsLoading.value = false;
    }
};
```

3. **selectMcs()** - Select MCS from modal
```javascript
const selectMcs = (mcs) => {
    form.value.mcs_from = mcs.seq;
    showMcsTableModal.value = false;
    selectedMcs.value = null;
};
```

4. **goToMcsPage()** - Handle pagination
```javascript
const goToMcsPage = (page) => {
    mcsCurrentPage.value = page;
    fetchMcsData();
};
```

5. **handleSortOptionChange()** - Handle sort changes
```javascript
const handleSortOptionChange = (event) => {
    mcsSortOption.value = event.target.value;
    fetchMcsData();
};
```

**D. Template Changes:**

Replaced old modals with UpdateMcModal:
```vue
<!-- Before -->
<MasterCardOptionsModal ... />
<MasterCardSearchSelectModal ... />
<MasterCardZoomModal ... />
<ProductModal ... />

<!-- After -->
<UpdateMcModal
    :showErrorModal="false"
    :showSetupMcModal="false"
    :showSetupPdModal="false"
    :showMcsTableModal="showMcsTableModal"
    :formData="form"
    :mcComponents="[]"
    :mcLoaded="null"
    :zoomOption="'normal'"
    :mcsSortOption="mcsSortOption"
    :mcsSortOrder="mcsSortOrder"
    :mcsStatusFilter="mcsStatusFilter"
    :mcsSearchTerm="mcsSearchTerm"
    :mcsLoading="mcsLoading"
    :mcsError="mcsError"
    :mcsMasterCards="mcsMasterCards"
    :selectedMcs="selectedMcs"
    :mcsCurrentPage="mcsCurrentPage"
    :mcsLastPage="mcsLastPage"
    :productDesigns="[]"
    :paperFlutes="[]"
    :soValues="[]"
    :woValues="[]"
    @closeErrorModal="() => {}"
    @closeSetupMcModal="() => {}"
    @closeSetupPdModal="() => {}"
    @closeMcsTableModal="showMcsTableModal = false"
    @selectComponent="() => {}"
    @setupPD="() => {}"
    @setupOthers="() => {}"
    @handleZoomChange="() => {}"
    @fetchMcsData="fetchMcsData"
    @selectMcsItem="selectedMcs = $event"
    @selectMcs="selectMcs"
    @goToMcsPage="goToMcsPage"
    @updateSearchTerm="mcsSearchTerm = $event"
    @updateSortOption="handleSortOptionChange"
    @productDesignSelected="() => {}"
    @paperFluteSelected="() => {}"
    @soValueSelected="() => {}"
    @woValueSelected="() => {}"
/>
```

---

### **2. Controller Updates**

#### **File:** `UpdateMcController.php`

**Added Method: `getMcsByCustomerPaginated()`**

```php
/**
 * Get master cards by customer with pagination (for modal browsing).
 *
 * @param  \Illuminate\Http\Request  $request
 * @return \Illuminate\Http\JsonResponse
 */
public function getMcsByCustomerPaginated(Request $request)
{
    try {
        $ac = $request->input('ac');
        $sort = $request->input('sort', 'mc_seq');
        $order = $request->input('order', 'asc');
        $status = $request->input('status', 'Act');
        $search = $request->input('search', '');
        $perPage = $request->input('per_page', 50);

        if (!$ac) {
            return response()->json([
                'data' => [],
                'current_page' => 1,
                'last_page' => 1,
                'total' => 0,
            ]);
        }

        $query = DB::table('MC')->where('AC_NUM', $ac);

        // Apply status filter
        if ($status !== 'all') {
            if ($status === 'Act') {
                $query->where('STS', 'Active');
            } else {
                $query->where('STS', $status);
            }
        }

        // Apply search filter
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('MCS_Num', 'like', "%{$search}%")
                  ->orWhere('MODEL', 'like', "%{$search}%")
                  ->orWhere('PART_NO', 'like', "%{$search}%")
                  ->orWhere('P_DESIGN', 'like', "%{$search}%");
            });
        }

        // Apply sorting
        $sortColumn = match($sort) {
            'mc_model' => 'MODEL',
            'part_no' => 'PART_NO',
            'ext_dim_1' => 'EXT_DIM_1',
            'int_dim_1' => 'INT_DIM_1',
            default => 'MCS_Num',
        };

        $query->orderBy($sortColumn, $order);

        // Get paginated results
        $results = $query->paginate($perPage);

        // Transform data to match expected format
        $transformedData = array_map(function ($mc) {
            return [
                'seq' => $mc->MCS_Num,
                'model' => $mc->MODEL,
                'part' => $mc->PART_NO,
                'comp' => $mc->COMP,
                'p_design' => $mc->P_DESIGN,
                'status' => $mc->STS === 'Active' ? 'Act' : $mc->STS,
                'ext_dim_1' => $mc->EXT_DIM_1 ?? '',
                'ext_dim_2' => $mc->EXT_DIM_2 ?? '',
                'ext_dim_3' => $mc->EXT_DIM_3 ?? '',
                'int_dim_1' => $mc->INT_DIM_1 ?? '',
                'int_dim_2' => $mc->INT_DIM_2 ?? '',
                'int_dim_3' => $mc->INT_DIM_3 ?? '',
            ];
        }, $results->items());

        return response()->json([
            'data' => $transformedData,
            'current_page' => $results->currentPage(),
            'last_page' => $results->lastPage(),
            'total' => $results->total(),
            'per_page' => $results->perPage(),
        ]);

    } catch (\Exception $e) {
        Log::error('Get MCs by Customer Paginated Error', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ]);

        return response()->json([
            'message' => 'Error occurred while fetching master cards.',
            'error' => $e->getMessage(),
        ], 500);
    }
}
```

**Features:**
- ✅ Pagination support (50 items per page default)
- ✅ Sorting by MC Seq#, Model, Part#, External Dim, Internal Dim
- ✅ Status filtering (Active, Obsolete, All)
- ✅ Search functionality (searches across MCS#, Model, Part#, P/Design)
- ✅ Order by ASC/DESC
- ✅ Error handling and logging

---

### **3. Routes Updates**

#### **File:** `routes/web.php`

```php
// Added new route
Route::get('/mc/by-customer-paginated', [UpdateMcController::class, 'getMcsByCustomerPaginated']);
```

**Complete API Routes for Obsolete & Reactive MC:**
```php
Route::post('/mc/bulk-obsolete', [UpdateMcController::class, 'bulkObsolete']);
Route::post('/mc/bulk-reactive', [UpdateMcController::class, 'bulkReactivate']);
Route::get('/mc/by-customer/{customerCode}', [UpdateMcController::class, 'getMcsByCustomer']);
Route::get('/mc/by-customer-paginated', [UpdateMcController::class, 'getMcsByCustomerPaginated']); // NEW
```

---

## 🎨 UI/UX Improvements

### **Before:**
- Different modal designs between Update MC and Obsolete & Reactive MC
- Inconsistent user experience
- Duplicate code for similar functionality

### **After:**
- ✅ **Unified Modal Design** - Both menus use same UpdateMcModal component
- ✅ **Consistent UX** - Same sorting, filtering, search experience
- ✅ **Shared Codebase** - Single modal component for easier maintenance
- ✅ **Professional Look** - Matches Update MC menu exactly

---

## 📊 UpdateMcModal Features Used

### **MCS Table Modal Features:**

1. **Header Controls:**
   - Sort by: MC Seq#, MC Model, MC PD Part#, MC PD ED, MC PD ID
   - Order: Ascending / Descending
   - Status: Active, Obsolete, All
   - Close button

2. **Search Functionality:**
   - Real-time search across MCS#, Model, Part#, P/Design
   - Press Enter to search
   - Search icon indicator

3. **Table Display:**
   - Dynamic column layout based on sort option
   - Color-coded status (Green=Active, Red=Obsolete)
   - Hover effects for row selection
   - Selected row highlighting (blue background)
   - Double-click to select

4. **Pagination:**
   - Page navigation controls
   - Current page indicator
   - Total pages display

5. **Loading States:**
   - Spinner with "Loading data..." text
   - Error display with retry button
   - Empty state message

---

## 🔄 User Workflow

### **Browse MCS# Workflow:**

```
1. User fills AC# field
2. User clicks folder icon (browse button) on MCS# field
3. UpdateMcModal opens with MCS Table
4. User can:
   - Search MCS by keyword
   - Sort by different columns
   - Filter by status
   - Navigate pages
   - Select MCS (double-click)
5. Selected MCS# populates the input field
6. Modal closes automatically
```

### **Validation:**
- AC# must be filled before browsing MCS
- Shows validation error if AC# is empty
- Only displays MCs for selected customer

---

## 🎯 Benefits

### **1. Code Reusability:**
- Single modal component shared by two menus
- Reduces code duplication
- Easier to maintain and update

### **2. Consistency:**
- Same UI/UX across different modules
- Users familiar with Update MC will instantly understand Obsolete & Reactive MC
- Professional, cohesive application design

### **3. Maintainability:**
- Bug fixes in UpdateMcModal benefit both menus
- New features automatically available in both menus
- Centralized styling and behavior

### **4. Performance:**
- Pagination reduces data load
- Efficient search and filtering
- Optimized database queries

---

## 🧪 Testing Guide

### **Test 1: Basic MCS Browsing**
```
1. Open: Obsolete & Reactive MC menu
2. Fill AC#: Enter valid customer code
3. Click: Browse button (folder icon) on MCS# field
4. Verify: Modal opens with MCS table
5. Verify: Data displays for selected customer
6. Double-click: Any MCS row
7. Verify: MCS# populates input field
8. Verify: Modal closes
```

### **Test 2: Validation**
```
1. Leave AC# field empty
2. Click: Browse button on MCS# field
3. Verify: Error toast appears
4. Verify: Message says "Please fill AC# field first"
5. Verify: Modal does not open
```

### **Test 3: Search Functionality**
```
1. Fill AC# and open modal
2. Type search term in search box
3. Press Enter
4. Verify: Table filters to matching records
5. Clear search
6. Verify: All records display again
```

### **Test 4: Sorting**
```
1. Open MCS modal
2. Change "Sort" dropdown to "MC Model"
3. Verify: Table re-sorts by Model column
4. Change "Order" to "Desc"
5. Verify: Table reverses order
```

### **Test 5: Status Filtering**
```
1. Open MCS modal
2. Change "Status" to "Obsolete"
3. Verify: Only obsolete MCs display
4. Change to "All"
5. Verify: Both active and obsolete MCs display
```

### **Test 6: Pagination**
```
1. Open MCS modal for customer with many MCs
2. Verify: Pagination controls display
3. Click "Next" page
4. Verify: Next set of records loads
5. Verify: Page number updates
```

---

## 📝 API Request/Response

### **Request:**
```
GET /api/mc/by-customer-paginated?ac=C001&sort=mc_seq&order=asc&status=Act&search=&page=1
```

### **Response:**
```json
{
    "data": [
        {
            "seq": "MC001",
            "model": "BOX-001",
            "part": "PART001",
            "comp": "C01",
            "p_design": "RSC",
            "status": "Act",
            "ext_dim_1": "100",
            "ext_dim_2": "200",
            "ext_dim_3": "300",
            "int_dim_1": "95",
            "int_dim_2": "195",
            "int_dim_3": "295"
        }
    ],
    "current_page": 1,
    "last_page": 5,
    "total": 245,
    "per_page": 50
}
```

---

## 📂 Files Modified

### **Vue Components:**
1. `resources/js/Pages/sales-management/system-requirement/master-card/obsolete-reactive-mc.vue`
   - Updated imports
   - Updated reactive state
   - Added new functions (fetchMcsData, selectMcs, goToMcsPage, handleSortOptionChange)
   - Updated template to use UpdateMcModal

### **Controllers:**
2. `app/Http/Controllers/UpdateMcController.php`
   - Added `getMcsByCustomerPaginated()` method

### **Routes:**
3. `routes/web.php`
   - Added `/mc/by-customer-paginated` route

---

## ✅ Completion Checklist

- [x] Import UpdateMcModal component
- [x] Remove old modal imports (MasterCardOptionsModal, MasterCardSearchSelectModal, MasterCardZoomModal, ProductModal)
- [x] Update reactive state for UpdateMcModal
- [x] Implement fetchMcsData function
- [x] Implement selectMcs function
- [x] Implement pagination handler
- [x] Implement sort change handler
- [x] Update template to use UpdateMcModal
- [x] Add getMcsByCustomerPaginated method in controller
- [x] Add route for new API endpoint
- [x] Test MCS browsing functionality
- [x] Test validation (AC# required)
- [x] Test search, sort, filter features
- [x] Test pagination
- [x] Create documentation

---

**Date:** 2025-01-03  
**Version:** 4.0 (UpdateMcModal Integration)  
**Status:** ✅ Complete - Using Shared UpdateMcModal Component  
**Next:** Both Update MC and Obsolete & Reactive MC now use identical modal for MCS browsing
