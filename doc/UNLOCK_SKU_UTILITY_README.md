# Unlock SKU Utility - ERP System

## Overview

The Unlock SKU Utility is a web-based tool designed to manage and unlock SKUs (Stock Keeping Units) that have been locked due to various reasons in the ERP system. This utility provides administrators and authorized users with the ability to safely unlock SKUs that are preventing normal business operations.

## Features

### 🔓 Core Functionality
- **Single SKU Unlock**: Unlock individual SKUs with detailed reason tracking
- **Bulk Unlock**: Unlock multiple SKUs simultaneously
- **Stale Lock Management**: Automatically identify and unlock locks older than 30 minutes
- **Real-time Statistics**: View lock statistics and trends
- **Search & Filter**: Find specific locked SKUs quickly
- **Audit Trail**: Complete logging of all unlock operations

### 📊 Dashboard Features
- **Lock Statistics**: Total locked, stale locks, recent locks (24h)
- **Top Lock Reasons**: Most common reasons for locks
- **Duration Tracking**: How long each SKU has been locked
- **Status Indicators**: Visual indicators for stale vs active locks

### 🔍 Search & Filter Capabilities
- Search by SKU code
- Search by SKU name
- Search by locked by user
- Search by lock reason
- Sort by any column
- Pagination support

## Why SKUs Get Locked

SKUs can become locked due to several reasons:

### 1. **Transaction Processing** 🔄
- **Sales Orders (SO)**: SKU is being processed in an active sales order
- **Purchase Orders (PO)**: SKU is part of an active purchase order
- **Inventory Transactions**: SKU is being updated in inventory operations
- **Production Orders**: SKU is being used in manufacturing processes

### 2. **System Failures** ⚠️
- **Network Timeouts**: Connection issues during data updates
- **Application Crashes**: Unexpected system shutdowns
- **Database Errors**: Transaction rollbacks or deadlocks
- **Session Timeouts**: User sessions expiring during edits

### 3. **User Session Issues** 👤
- **Incomplete Logouts**: Users closing browsers without proper logout
- **Multiple Sessions**: Same user logged in from multiple devices
- **Session Conflicts**: Concurrent access attempts

### 4. **Business Rules** 📋
- **Posted Documents**: SKU locked because it's part of a posted transaction
- **Approval Processes**: SKU awaiting approval workflow
- **Data Validation**: Failed validation preventing updates
- **Batch Processing**: SKU locked during bulk operations

## API Endpoints

### Web Routes
```
GET /material-management/system-requirement/inventory-setup/unlock-sku-utility
```

### API Routes
```
GET /api/material-management/unlock-sku-utility/locked-skus
POST /api/material-management/unlock-sku-utility/unlock/{sku}
POST /api/material-management/unlock-sku-utility/bulk-unlock
POST /api/material-management/unlock-sku-utility/unlock-stale
GET /api/material-management/unlock-sku-utility/statistics
```

## Database Schema

### Lock Fields in `mm_skus` Table
```sql
ALTER TABLE mm_skus ADD COLUMN (
    is_locked BOOLEAN DEFAULT FALSE,
    locked_by VARCHAR(50) NULL,
    locked_at TIMESTAMP NULL,
    lock_reason VARCHAR(255) NULL,
    lock_session_id VARCHAR(100) NULL
);
```

### Field Descriptions
- `is_locked`: Boolean flag indicating if SKU is locked
- `locked_by`: Username of the user who locked the SKU
- `locked_at`: Timestamp when the SKU was locked
- `lock_reason`: Description of why the SKU was locked
- `lock_session_id`: Session identifier for tracking

## Usage Instructions

### Accessing the Utility
1. Navigate to: `/material-management/system-requirement/inventory-setup/unlock-sku-utility`
2. Ensure you have appropriate permissions
3. The dashboard will load with current lock statistics

### Unlocking a Single SKU
1. **Search** for the specific SKU using the search bar
2. **Click** the "Unlock" button next to the SKU
3. **Enter** a reason for unlocking in the modal
4. **Confirm** the unlock operation
5. **Verify** the SKU is now unlocked

### Bulk Unlock Operations
1. **Select** multiple SKUs using checkboxes
2. **Click** "Bulk Unlock" button
3. **Enter** a reason for the bulk operation
4. **Confirm** to unlock all selected SKUs
5. **Review** the results summary

### Unlocking Stale Locks
1. **Check** the "Stale Locks" count in statistics
2. **Click** "Unlock Stale" button
3. **Enter** a reason for unlocking stale locks
4. **Confirm** to unlock all stale locks automatically

### Viewing SKU Details
1. **Click** "View" button next to any SKU
2. **Opens** the SKU details page in a new tab
3. **Review** the SKU information and current status

## Security & Permissions

### Access Control
- **Administrators**: Full access to all unlock operations
- **System Managers**: Access to unlock operations with logging
- **Regular Users**: Read-only access to view locked SKUs

### Audit Requirements
- **Reason Required**: All unlock operations require a reason
- **User Tracking**: All operations are logged with user information
- **Timestamp Recording**: Exact time of unlock operations
- **Session Tracking**: Session IDs for debugging

## Best Practices

### Before Unlocking
1. **Verify** the lock is truly stale or unnecessary
2. **Check** if any active transactions are using the SKU
3. **Communicate** with the user who locked the SKU if possible
4. **Document** the reason for unlocking

### After Unlocking
1. **Monitor** the SKU for any issues
2. **Verify** the unlock operation was successful
3. **Check** if the original issue is resolved
4. **Update** any related documentation

### Prevention
1. **Implement** proper session management
2. **Use** transaction timeouts
3. **Monitor** for long-running operations
4. **Train** users on proper logout procedures

## Troubleshooting

### Common Issues

#### "SKU is not locked" Error
- **Cause**: SKU was already unlocked by another user
- **Solution**: Refresh the page and check current status

#### "Permission denied" Error
- **Cause**: User lacks unlock permissions
- **Solution**: Contact administrator for access

#### "Network error" During Unlock
- **Cause**: Connection issues or server problems
- **Solution**: Retry the operation, check network connectivity

#### Stale Locks Not Showing
- **Cause**: Lock duration calculation issues
- **Solution**: Check server time settings, refresh statistics

### Debug Information
- **Lock Duration**: Shows how long SKU has been locked
- **Session ID**: Helps identify specific user sessions
- **Lock Reason**: Provides context for the lock
- **User Information**: Shows who locked the SKU

## Monitoring & Reporting

### Key Metrics to Monitor
- **Total Locked SKUs**: Overall system health
- **Stale Lock Percentage**: System efficiency
- **Unlock Frequency**: Usage patterns
- **Common Lock Reasons**: System improvement opportunities

### Reports Available
- **Daily Lock Summary**: Lock/unlock activity
- **User Lock Activity**: Per-user lock patterns
- **Lock Duration Analysis**: Performance metrics
- **Unlock Reason Analysis**: Process improvement data

## Integration Points

### Related Systems
- **SKU Management**: Direct integration with SKU data
- **User Management**: Authentication and authorization
- **Transaction Systems**: SO, PO, Inventory modules
- **Audit Systems**: Logging and compliance

### Event Triggers
- **Lock Events**: When SKUs become locked
- **Unlock Events**: When SKUs are unlocked
- **Stale Lock Detection**: Automatic identification
- **Permission Changes**: Access control updates

## Development & Maintenance

### Code Structure
```
app/Http/Controllers/MaterialManagement/SystemRequirement/UnlockSkuUtilityController.php
resources/js/Pages/material-management/system-requirement/inventory-setup/UnlockSkuUtility.vue
database/migrations/2025_01_15_000000_add_lock_fields_to_mm_skus_table.php
database/seeders/UnlockSkuUtilitySeeder.php
```

### Testing
- **Unit Tests**: Controller method testing
- **Integration Tests**: API endpoint testing
- **UI Tests**: Frontend functionality testing
- **Performance Tests**: Load testing for bulk operations

### Maintenance Tasks
- **Regular Monitoring**: Check for unusual lock patterns
- **Log Analysis**: Review unlock operation logs
- **Performance Optimization**: Monitor response times
- **Security Audits**: Review access patterns

## Support & Documentation

### Getting Help
- **System Administrators**: For technical issues
- **Business Analysts**: For process questions
- **IT Support**: For access and permission issues
- **User Training**: For usage guidance

### Documentation Updates
- **Process Changes**: When unlock procedures change
- **New Features**: When new functionality is added
- **Security Updates**: When access controls change
- **Best Practices**: When recommendations are updated

---

**Version**: 1.0  
**Last Updated**: January 2025  
**Maintained By**: ERP Development Team 