# Permission System Testing Guide

## Test Users Available

### 1. ADMIN001 (Full Access)
- **Username**: admin
- **Password**: admin123
- **Permissions**: All 152 menu items
- **Purpose**: Full system access for testing all features

### 2. USER001 (Full Access)
- **Username**: user
- **Password**: user123
- **Permissions**: All 152 menu items
- **Purpose**: Secondary admin account

### 3. TEST002 (Limited Sales Access)
- **Username**: testuser2
- **Password**: test123
- **Permissions**: Limited to:
  - Dashboard
  - Sales Management (parent)
  - Update MC
  - Approve MC
  - Prepare MC SO
- **Purpose**: Test hierarchical permission filtering for Sales Management

### 4. TEST003 (Limited Warehouse Access)
- **Username**: testuser3
- **Password**: test123
- **Permissions**: Limited to:
  - Dashboard
  - Warehouse Management (parent)
  - DO Processing: Prepare (Single), Amend, Cancel
  - Invoice Processing: Prepare, Amend, Cancel
  - DORN Processing: Issue, Amend, Cancel
  - Invoice Setup: Define Tax Type, Define Tax Group
- **Purpose**: Test comprehensive warehouse management sub-sub menu filtering

### 5. CSRF001 (CSRF Testing)
- **Username**: csrftest
- **Password**: test123
- **Permissions**: Basic permissions for testing CSRF functionality
- **Purpose**: Test CSRF token handling and session management

### 6. LOGOUT001 (Logout Testing)
- **Username**: logouttest
- **Password**: test123
- **Permissions**: Basic permissions for testing logout functionality
- **Purpose**: Test logout button functionality without console errors

### 7. MENU001 (Comprehensive Menu Testing)
- **Username**: menutest
- **Password**: test123
- **Permissions**: Selected permissions from all major Sales Management sections:
  - Dashboard
  - Sales Management (parent)
  - Standard Requirement: Define Sales Team, Define Product Group, View & Print items
  - Customer Account: Define Customer Group, Update Customer Account, View & Print items
  - Master Card: Update MC, Approve MC, View & Print MC, View & Print MC by Color
  - Sales Order: Prepare MC SO, Print SO, Define SO Period, Print SO Report
  - Customer Service Dashboard
- **Purpose**: Test comprehensive sub-sub menu filtering and display

### 8. MISSING001 (Previously Missing Menu Testing)
- **Username**: missingtest
- **Password**: test123
- **Permissions**: Focus on previously missing menu items:
  - Dashboard
  - Sales Management & Warehouse Management (parents)
  - Customer Account: Obsolete/Reactive Customer A/C (was missing)
  - Delivery Order Setup: Define DORN Code, Define Greeting Message, Define Alternate Unit
  - Delivery Order Setup: Define D/Order Group, Define User's D/Order Group
  - Delivery Order Setup: View & Print Analysis Code, View & Print Vehicle Class/Vehicle
- **Purpose**: Test that previously missing sub-menu items now appear correctly

## Testing Steps

### 1. Test Full Access (ADMIN001)
1. Login with admin/admin123
2. Verify all menu items appear in sidebar
3. Check Sales Management shows all submenus
4. Check Master Card shows all options

### 2. Test Limited Sales Access (TEST002)
1. Login with testuser2/test123
2. Verify only Dashboard and Sales Management appear
3. Check Sales Management dropdown shows only:
   - Master Card section with Update MC and Approve MC
   - Sales Order section with Prepare MC SO
4. Verify other submenus are hidden

### 3. Test Limited Warehouse Access (TEST003)
1. Login with testuser3/test123
2. Verify only Dashboard and Warehouse Management appear
3. Check Warehouse Management dropdown shows only:
   - Delivery Order > DO Processing: Prepare (Single), Amend, Cancel
   - Delivery Order > DORN Processing: Issue, Amend, Cancel
   - Invoice > Setup: Define Tax Type, Define Tax Group
   - Invoice > IV Processing: Prepare, Amend, Cancel
4. Verify other warehouse submenus are hidden
5. Verify nested structure works (Setup and DO Processing show filtered items)

### 4. Test Permission Filtering
1. Create new user via form
2. Select only specific permissions
3. Login with new user
4. Verify sidebar matches selected permissions

### 5. Test CSRF Token Handling (CSRF001)
1. Login with csrftest/test123
2. Try to logout immediately (should work without refresh)
3. Login again and navigate to different pages
4. Try form submissions without refreshing page
5. Verify no 419 "Page Expired" errors occur
6. Test that all actions work seamlessly after login

### 6. Test Logout Functionality (LOGOUT001)
1. Login with logouttest/test123
2. Open browser developer console (F12)
3. Click logout button immediately after login
4. Verify no console errors appear (especially no "$page is not defined")
5. Verify logout redirects to login page successfully
6. Test multiple login/logout cycles

### 7. Test Comprehensive Menu Display (MENU001)
1. Login with menutest/test123
2. Verify Sales Management menu appears with sub-sections
3. Check Standard Requirement sub-menus:
   - Define Sales Team ✓
   - Define Product Group ✓
   - View & Print Sales Team ✓
   - View & Print Product Group ✓
4. Check Customer Account sub-menus:
   - Define Customer Group ✓
   - Update Customer Account ✓
   - View & Print Customer Group ✓
5. Check Master Card sub-menus:
   - Update MC ✓
   - Approve MC ✓
   - View & Print MC ✓
   - View & Print MC by Color ✓
6. Check Sales Order sub-menus:
   - Prepare MC SO ✓
   - Print SO ✓
   - Define SO Period ✓
   - Print SO Report ✓
7. Verify Customer Service Dashboard appears
8. Verify other sections are hidden (no access)

### 8. Test Admin Full Access (ADMIN001)
1. Login with admin/admin123
2. Verify all 152 menu items are accessible (up from 142)
3. Check all major sections appear:
   - Dashboard
   - System Manager (with all sub-menus)
   - Sales Management (with all 4 sub-sections)
   - Warehouse Management (with all sub-sections)
4. Verify all View & Print options are available
5. Verify all Setup and Transaction menus appear
6. Check that Print SO, Define SO Period, and other previously missing items now appear

### 9. Test Previously Missing Menus (MISSING001)
1. Login with missingtest/test123
2. Check Customer Account section contains:
   - Obsolete/Reactive Customer A/C ✓ (was missing)
   - Define Customer Group ✓
   - Update Customer Account ✓
3. Check Delivery Order Setup section contains:
   - Define DORN Code ✓ (was missing)
   - Define Greeting Message ✓ (was missing)
   - Define Alternate Unit ✓ (was missing)
   - Define D/Order Group ✓ (was missing)
   - Define User's D/Order Group ✓ (was missing)
   - View & Print Analysis Code ✓ (was missing)
   - View & Print Vehicle Class ✓ (was missing)
   - View & Print Vehicle ✓ (was missing)
4. Verify these menus now appear correctly in the sidebar
5. Verify other sections are hidden (no access)

## Expected Behavior

### Hierarchical Permission System
- **Parent Permission**: User with `sales_management` sees Sales Management menu
- **Child Filtering**: Only selected child permissions appear in dropdown
- **Nested Filtering**: Sub-sub menus filtered based on individual permissions

### Menu Structure
```
Sales Management (parent: sales_management)
├── System Requirement
│   ├── Sales Configuration
│   │   └── Define Sales Configuration (define_sales_configuration)
│   ├── Standard Requirement
│   │   ├── Define Sales Team (define_sales_team)
│   │   ├── Define Salesperson (define_salesperson)
│   │   └── ... (other standard requirements)
│   ├── Customer Account
│   │   ├── Define Customer Group (define_customer_group)
│   │   ├── Update Customer Account (update_customer_account)
│   │   └── ... (other customer account items)
│   └── Master Card
│       ├── Update MC (update_mc)
│       ├── Approve MC (approve_mc)
│       ├── Release Approved MC (release_approved_mc)
│       └── ... (other master card items)
├── Sales Order
│   ├── Setup
│   │   ├── Define SO Config (define_so_config)
│   │   └── ... (other setup items)
│   ├── Transaction
│   │   ├── Prepare MC SO (prepare_mc_so)
│   │   ├── Prepare SB SO (prepare_sb_so)
│   │   └── ... (other transaction items)
│   └── Report
│       └── ... (report items)
└── Customer Service
    └── Customer Service Dashboard (customer_service_dashboard)
```

## Troubleshooting

### Sub-menus Not Appearing
1. Check user has parent permission (e.g., `sales_management`)
2. Verify child permissions are granted (e.g., `update_mc`)
3. Check permission key mapping in `getPermissionKeyFromTitle()`
4. Verify permission exists in UserPermission model

### Permission Not Working
1. Clear browser cache
2. Check user permissions in database
3. Verify middleware is sharing permissions
4. Check console for JavaScript errors

### Database Issues
1. Clear permissions: `UserPermission::truncate()`
2. Re-seed: `php artisan db:seed --class=UserCpsSeeder`
3. Verify counts match between model and database

## Commands for Debugging

```bash
# Check permission counts
php artisan tinker --execute="echo App\Models\UserPermission::count();"

# Check user permissions
php artisan tinker --execute="echo App\Models\UserPermission::where('user_id', 'TEST002')->count();"

# Clear and reseed
php artisan tinker --execute="App\Models\UserPermission::truncate();"
php artisan db:seed --class=UserCpsSeeder
```
