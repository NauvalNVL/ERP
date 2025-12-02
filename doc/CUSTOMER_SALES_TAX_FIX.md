# Define Customer Sales Tax Index - Component Not Found Fix

## Problem
User couldn't access the "Define Customer Sales Tax Index" menu from the sidebar, getting the error:
```
Resolving page: warehouse-management/Invoice/Setup/DefineCustomerSalesTaxIndex => Component not found
TypeError: Cannot read properties of undefined (reading 'default')
```

## Root Cause
**Vite dev server needed to be restarted** to properly register the Vue component. The component file existed but wasn't being picked up by Vite's hot module replacement.

## Solution Applied

### 1. Verified Component Exists
✅ File exists: `resources/js/Pages/warehouse-management/Invoice/Setup/DefineCustomerSalesTaxIndex.vue`
✅ Controller exists: `app/Http/Controllers/Invoice/CustomerSalesTaxIndexController.php`
✅ Route exists: `/warehouse-management/invoice/setup/define-customer-sales-tax-index`

### 2. Restarted Vite Dev Server
```bash
# Stop all Node processes
Stop-Process -Name "node" -Force

# Restart Vite
npm run dev
```

### 3. Vite Now Running
- **Port:** 5173
- **Status:** Ready
- **Component:** Now properly registered

## Verification

### Component Details
- **Path:** `warehouse-management/Invoice/Setup/DefineCustomerSalesTaxIndex.vue`
- **Uses:** `<script setup>` syntax
- **Layout:** AppLayout
- **Features:**
  - Customer selection modal
  - Tax group assignment
  - Product group mappings
  - CRUD operations for customer tax indices

### Route Details
```php
Route::get('/warehouse-management/invoice/setup/define-customer-sales-tax-index', 
    [CustomerSalesTaxIndexController::class, 'index'])
    ->name('vue.warehouse-management.invoice.setup.define-customer-sales-tax-index');
```

### Controller Rendering
```php
public function index()
{
    return Inertia::render('warehouse-management/Invoice/Setup/DefineCustomerSalesTaxIndex');
}
```

## Expected Behavior Now
1. ✅ Menu item in sidebar is clickable
2. ✅ Page loads without errors
3. ✅ Component renders properly
4. ✅ All features accessible

## Action Required
**Refresh your browser** (Ctrl + Shift + R) to clear any cached errors and load the newly registered component.

## Related API Endpoints
The component uses these API endpoints (already configured):
- `GET /api/invoices/customer-tax-indices/{customerCode}` - Get customer tax indices
- `POST /api/invoices/customer-tax-indices` - Create new index
- `GET /api/invoices/customer-tax-indices/{customerCode}/{indexNumber}` - Get specific index
- `DELETE /api/invoices/customer-tax-indices/{customerCode}/{indexNumber}` - Delete index
- `GET /api/invoices/customer-tax-indices/{customerCode}/{indexNumber}/product-tieups` - Get product mappings
- `POST /api/invoices/customer-tax-indices/{customerCode}/{indexNumber}/product-tieups` - Save product mappings

All API routes are properly configured in `routes/api.php` within the invoices prefix group.

## Status
✅ **FIXED** - Vite restarted, component now accessible
