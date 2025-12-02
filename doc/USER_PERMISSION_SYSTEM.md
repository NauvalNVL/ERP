# User Permission System Documentation

## Overview

This document describes the comprehensive user permission system implemented for the ERP application. The system provides granular access control for all menu items and features in the application.

## Database Structure

### User Permissions Table (`user_permissions`)

| Column | Type | Description |
|--------|------|-------------|
| `id` | bigint | Primary key |
| `user_id` | string | References userID from usercps table |
| `menu_key` | string | Unique identifier for each menu item |
| `menu_name` | string | Human readable menu name |
| `menu_route` | string | Route path for the menu (nullable) |
| `menu_category` | string | Category: dashboard, system_manager, sales_management, warehouse_management |
| `menu_parent` | string | Parent menu for nested items (nullable) |
| `can_access` | boolean | Permission to access this menu |
| `created_at` | timestamp | Creation timestamp |
| `updated_at` | timestamp | Update timestamp |

### Indexes
- Composite index on `user_id` and `menu_key`
- Index on `user_id` and `can_access`
- Unique constraint on `user_id` and `menu_key`

## Models

### UserPermission Model

Located at: `app/Models/UserPermission.php`

**Key Methods:**
- `getAllMenuItems()` - Returns complete menu structure
- `createDefaultPermissions($userId)` - Creates full access permissions for a user
- `getUserPermissions($userId)` - Returns array of accessible menu keys

### UserCps Model Updates

Located at: `app/Models/UserCps.php`

**New Methods:**
- `permissions()` - Relationship with UserPermission
- `hasPermission($menuKey)` - Check if user has specific permission
- `getPermissionsArray()` - Get user permissions as array

## Frontend Implementation

### Create User Form

File: `resources/js/Pages/system-manager/system-security/Create.vue`

**Features:**
- Permission checkboxes for all menu categories
- "Select All" toggle functionality
- Hierarchical permission display
- Real-time permission state management

**Permission Categories:**
1. **Dashboard** - Access to main dashboard
2. **System Manager** - User management functions
   - Define User
   - Amend User Password
   - Define User Access Permission
   - Copy & Paste User Access Permission
   - View & Print User
3. **Sales Management** - Sales-related functions
   - Define Sales Team
   - Define Salesperson
   - Define Customer Group
   - Master Card
   - Sales Order
4. **Warehouse Management** - Warehouse operations
   - Delivery Order
   - Delivery Order Setup
   - Invoice

### Sidebar Permission Checking

File: `resources/js/Layouts/Partials/Sidebar.vue`

**Implementation:**
- `hasPermission(menuKey)` function checks user permissions
- Menu items are conditionally rendered based on permissions
- Real-time filtering of accessible menu items

## Backend Implementation

### UserController Updates

File: `app/Http/Controllers/UserController.php`

**Key Changes:**
- Updated `store()` method to handle permission creation
- Database transaction for user and permission creation
- Fallback to default permissions if none specified

### Middleware Integration

File: `app/Http/Middleware/HandleInertiaRequests.php`

**Implementation:**
- Shares user permissions with frontend via Inertia props
- Accessible as `$page.props.auth.permissions`

## Menu Structure

The system covers the following menu hierarchy:

```
Dashboard
├── Dashboard

System Manager
├── Define User
├── Amend User Password
├── Define User Access Permission
├── Copy & Paste User Access Permission
└── View & Print User

Sales Management
├── Define Sales Team
├── Define Salesperson
├── Define Customer Group
├── Master Card
└── Sales Order

Warehouse Management
├── Delivery Order
├── Delivery Order Setup
└── Invoice
```

## Usage

### Creating a User with Permissions

1. Navigate to System Manager > Define User
2. Fill in user details
3. Select desired permissions in the User Permissions section
4. Use "Select All Permissions" toggle for full access
5. Submit the form

### Default Behavior

- New users without specified permissions get full access
- Seeded admin users (ADMIN001, USER001) have full permissions
- Permission checking is automatic in the sidebar

### Permission Checking in Code

```php
// Check if user has permission
if (Auth::user()->hasPermission('dashboard')) {
    // User can access dashboard
}

// Get all user permissions
$permissions = Auth::user()->getPermissionsArray();
```

```javascript
// Frontend permission checking
const hasPermission = (menuKey) => {
  return userPermissions.value.includes(menuKey);
};
```

## Database Seeding

The system includes seeded data:
- **ADMIN001**: Super Administrator with full permissions
- **USER001**: Sample user with full permissions

Run seeder: `php artisan db:seed --class=UserCpsSeeder`

## Migration

Run the migration: `php artisan migrate --path=database/migrations/2024_11_03_100000_create_user_permissions_table.php`

## Key Features

1. **Hierarchical Permission System**: 
   - Parent menus show if user has parent permission
   - Child menus are filtered based on individual permissions
   - Example: User with "system_manager" permission sees System Manager menu, but only child items they have permission for
2. **Granular Control**: Individual permissions for each menu item
3. **Real-time Filtering**: Sidebar dynamically shows/hides menu items based on permissions
4. **Logout Fix**: Improved logout functionality with proper error handling
5. **Default Deny**: Users without permissions are denied access
6. **Database Integrity**: Unique constraints prevent duplicate permissions

## Security Considerations

1. **Hierarchical Filtering**: Parent menus display when user has parent permission, children filtered individually
2. **Default Deny**: Users without permissions are denied access
3. **Real-time Checking**: Permissions are checked on every request
4. **Database Integrity**: Unique constraints prevent duplicate permissions

## Recent Updates (Nov 2024)

### Fixed Issues
1. **419 Page Expired Error**: Fixed CSRF token handling in form submission
2. **Missing Menu Items**: Added comprehensive menu structure including Master Card submenus
3. **Hierarchical Permission System**: Improved parent-child menu filtering
4. **Logout Issues**: Fixed logout functionality with proper error handling

### Menu Structure Updates
- **Sales Management**: Added detailed submenus for Master Card, Customer Account, Standard Requirement
- **Warehouse Management**: Added Delivery Order Setup and DO Processing submenus
- **Permission Granularity**: Now covers 42+ individual menu items

## Troubleshooting

### Common Issues

1. **Migration Fails**: Foreign key constraint issue
   - Solution: Foreign key constraint removed due to usercps table structure

2. **Permissions Not Loading**: Middleware not sharing data
   - Check `HandleInertiaRequests.php` implementation

3. **Sidebar Not Filtering**: Permission checking not working
   - Verify `hasPermission()` function in Sidebar.vue

4. **419 Page Expired Error**: CSRF token issues
   - Solution: CSRF token now explicitly added to form data
   - Check `$page.props.csrf` is available

5. **Permission Count Mismatch**: Old permissions in database
   - Solution: Clear permissions table and re-run seeder
   - Command: `UserPermission::truncate(); php artisan db:seed --class=UserCpsSeeder`

### Verification Commands

```bash
# Check user count
php artisan tinker --execute="echo App\Models\UserCps::count();"

# Check permission count
php artisan tinker --execute="echo App\Models\UserPermission::count();"

# Check admin permissions
php artisan tinker --execute="echo App\Models\UserPermission::where('user_id', 'ADMIN001')->where('can_access', true)->count();"
```

## Future Enhancements

1. **Role-based Permissions**: Implement user roles with predefined permission sets
2. **Permission Groups**: Create permission groups for easier management
3. **Audit Trail**: Log permission changes and access attempts
4. **API Permissions**: Extend to API endpoint access control
5. **Dynamic Menu Loading**: Load menu structure from database

## Support

For issues or questions regarding the permission system, refer to:
- Model implementations in `app/Models/`
- Frontend components in `resources/js/`
- Database migrations in `database/migrations/`
- Seeder files in `database/seeders/`
