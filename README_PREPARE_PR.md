# Purchase Requisition (Prepare PR) - Website Version

## Overview

This is the website version of the **Prepare PR** (Purchase Requisition) module from the ERP desktop application. The module allows users to create, manage, and track purchase requisitions for procurement of materials/services within the organization.

## Features Implemented

### 1. Main PR Form Interface
- **PR Header Information**: Period, Year, Department, Requestor, Priority, Budget Code
- **Date Management**: Request Date and Required Date with validation
- **Auto-generated PR Numbers**: Format: `PRYYYYMMnnnn` (e.g., PR202501001)
- **Priority Levels**: Low, Medium, High, Urgent

### 2. PR Items Management
- **Dynamic Item Addition**: Add/Remove items with line numbers
- **SKU Integration**: Link to Material Management SKU system
- **Stock Status Checking**: Real-time stock availability check
- **Quantity & Pricing**: Estimated pricing and total calculations
- **Item Specifications**: Description, specifications, and notes

### 3. Approval Workflow
- **Multi-level Approvals**: Based on total value and department
- **Approval Rules**:
  - < 10M IDR: Supervisor approval only
  - 10M - 100M IDR: Manager + Finance approval
  - > 100M IDR: Manager + Finance + Director approval
- **Real-time Status Tracking**: Draft → Pending → Approved/Rejected

### 4. PR List & Management
- **Comprehensive Listing**: All PRs with filters and search
- **Status Management**: View, Edit, Approve, Reject, Cancel, Delete
- **Pagination & Sorting**: Efficient data browsing
- **Action-based Permissions**: Role-based action availability

### 5. Integration Features
- **SKU Lookup Modal**: Search and select items from inventory
- **User Lookup Modal**: Select requestors and approvers
- **Stock Validation**: Real-time inventory checks
- **Budget Tracking**: Integration with financial planning

## File Structure

### Vue Components
```
resources/js/Pages/material-management/Purchase-Order/PR-PO/
├── PreparePR.vue          # Main PR creation/editing interface
└── PrList.vue             # PR listing with management actions

resources/js/Components/
├── SkuLookupModal.vue     # SKU selection modal
└── UserLookupModal.vue    # User selection modal
```

### Backend Models
```
app/Models/
├── PurchaseRequisition.php    # Main PR model with business logic
├── PrItem.php                 # PR line items model
└── PrApproval.php             # Approval workflow model
```

### Controllers
```
app/Http/Controllers/MaterialManagement/PurchaseOrder/
└── PurchaseRequisitionController.php    # API endpoints for PR operations
```

### Database Migrations
```
database/migrations/
├── *_create_purchase_requisitions_table.php
├── *_create_pr_items_table.php
└── *_create_pr_approvals_table.php
```

## API Endpoints

### PR CRUD Operations
- `GET /api/purchase-requisitions` - List PRs with filters
- `POST /api/purchase-requisitions` - Create new PR
- `GET /api/purchase-requisitions/{id}` - Get specific PR
- `PUT /api/purchase-requisitions/{id}` - Update PR
- `DELETE /api/purchase-requisitions/{id}` - Delete PR

### PR Workflow Actions
- `POST /api/purchase-requisitions/{id}/submit` - Submit for approval
- `POST /api/purchase-requisitions/{id}/approve` - Approve PR
- `POST /api/purchase-requisitions/{id}/reject` - Reject PR
- `POST /api/purchase-requisitions/{id}/cancel` - Cancel PR

### Utility Endpoints
- `GET /api/purchase-requisitions/statistics/dashboard` - Get PR statistics
- `GET /api/purchase-requisitions/approvals/my-pending` - Get pending approvals

## Business Logic

### PR Statuses
1. **DRAFT** - Initial state, can be edited
2. **PENDING_APPROVAL** - Submitted for approval
3. **APPROVED** - All approvals completed
4. **REJECTED** - Rejected by any approver
5. **CANCELLED** - Cancelled by requestor/admin
6. **PARTIALLY_CONVERTED** - Some items converted to PO
7. **FULLY_CONVERTED** - All items converted to PO

### Approval Workflow
The system automatically determines approval requirements based on:
- **Total Estimated Value**: Higher amounts require more approvals
- **Department**: Department-specific approval chains
- **Special Items**: Critical items may require additional approvals

### Stock Integration
- **Real-time Stock Checking**: Validates availability upon SKU entry
- **Stock Status Indicators**:
  - Available: Green (sufficient stock)
  - Low Stock: Yellow (below required quantity)
  - Out of Stock: Red (zero stock)
  - Not Found: Gray (SKU not in system)

## Usage Instructions

### Creating a New PR

1. **Access the Module**: Navigate to Material Management → Purchase Order → Prepare PR
2. **Fill Header Information**:
   - Set period and year (defaults to current)
   - Enter department information
   - Select requestor (use lookup modal)
   - Set priority level
   - Choose request and required dates
   - Add budget code if applicable

3. **Add Items**:
   - Click "Add Item" to create new line
   - Enter SKU code or use lookup modal
   - Fill description and specifications
   - Set quantity and estimated price
   - Review stock status indicators

4. **Submit for Approval**:
   - Save as draft for later editing
   - Submit for approval to start workflow

### Managing Existing PRs

1. **View PR List**: Toggle "Show PR List" to see existing PRs
2. **Filter & Search**: Use filters to find specific PRs
3. **Take Actions**: 
   - Edit draft/rejected PRs
   - Approve/reject pending PRs (if authorized)
   - Cancel active PRs
   - Delete draft PRs

### Approval Process

1. **Automatic Routing**: System routes to appropriate approvers
2. **Email Notifications**: Approvers receive notification emails
3. **Comments**: Add approval/rejection comments
4. **Delegation**: Forward to other approvers if needed

## Configuration

### Approval Rules
Modify approval workflows in `PurchaseRequisitionController::createApprovalWorkflow()`:

```php
// Example: Modify value thresholds
if ($totalValue >= 100000000) { // 100M IDR
    // High value approvals
} elseif ($totalValue >= 10000000) { // 10M IDR
    // Medium value approvals
} else {
    // Low value approvals
}
```

### User Roles
Ensure users have appropriate roles in the system:
- `supervisor` - Department supervisors
- `manager` - Department managers  
- `finance_manager` - Finance team
- `director` - Executive level

## Technical Details

### Frontend Technology
- **Vue 3 Composition API**: Modern reactive components
- **Tailwind CSS**: Utility-first styling
- **Inertia.js**: SPA-like experience with server routing

### Backend Technology
- **Laravel 11**: Modern PHP framework
- **Eloquent ORM**: Database interactions
- **Soft Deletes**: Data preservation
- **Model Events**: Automated workflows

### Database Design
- **Normalized Structure**: Separate tables for PR header, items, approvals
- **Audit Trail**: Created/updated by tracking
- **Soft Deletes**: Data recovery capability
- **Indexing**: Optimized for performance

## Security Features

### Access Control
- **Authentication Required**: All operations require login
- **Role-based Permissions**: Actions based on user roles
- **CSRF Protection**: Form security
- **SQL Injection Prevention**: Parameterized queries

### Data Validation
- **Frontend Validation**: Immediate user feedback
- **Backend Validation**: Server-side security
- **Business Rules**: Workflow constraints
- **Data Integrity**: Foreign key constraints

## Performance Optimizations

### Frontend
- **Lazy Loading**: Components loaded on demand
- **Debounced Search**: Reduced API calls
- **Pagination**: Efficient data loading
- **Caching**: Static data caching

### Backend
- **Eager Loading**: Optimized database queries
- **Database Indexing**: Fast lookups
- **Query Optimization**: Efficient data retrieval
- **Batch Operations**: Bulk data processing

## Testing Recommendations

### Unit Tests
- Model validation and business logic
- Controller endpoint functionality
- Approval workflow scenarios

### Integration Tests
- End-to-end PR creation flow
- Approval process testing
- Stock integration validation

### Manual Testing Scenarios
1. Create PR with various item types
2. Test approval workflow with different values
3. Verify stock status updates
4. Test rejection and resubmission
5. Validate permissions and security

## Troubleshooting

### Common Issues
1. **SKU Not Found**: Ensure SKU exists in mm_skus table
2. **Approval Not Working**: Check user roles and approval configuration
3. **Stock Status Not Updating**: Verify API endpoint accessibility
4. **Permission Denied**: Confirm user authentication and roles

### Debugging
- Check browser console for JavaScript errors
- Review Laravel logs for backend errors
- Verify database connections and data
- Test API endpoints independently

## Future Enhancements

### Planned Features
1. **Email Notifications**: Automated approval notifications
2. **Mobile Responsive**: Enhanced mobile experience
3. **Reporting**: PR analytics and reports
4. **Integration**: ERP module connections
5. **Workflow Customization**: User-defined approval rules

### Scalability Considerations
- **Microservices**: Separate approval service
- **Queue System**: Background processing
- **Caching Layer**: Redis/Memcached integration
- **Load Balancing**: Multiple server support

---

## Support

For technical support or feature requests, please contact the development team or refer to the main ERP documentation.

**Version**: 1.0  
**Last Updated**: January 2025  
**Author**: ERP Development Team
