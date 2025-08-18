# SKU Consumption Budget Management System

## Overview

The "Define SKU Consumption Budget" feature is a comprehensive ERP system module designed to manage and plan consumption budgets for Stock Keeping Units (SKUs) across different time periods. This system helps organizations track planned vs. actual consumption, analyze variances, and make informed inventory decisions.

## Key Features

### 1. **SKU Selection & Management**
- **SKU Search**: Advanced search functionality with dropdown suggestions
- **SKU Lookup**: Modal-based SKU selection with detailed information
- **Navigation**: Previous/Next SKU navigation for efficient data entry
- **SKU Details**: Side panel displaying comprehensive SKU information

### 2. **Budget Planning**
- **Effective Month Input**: MM/YYYY format for precise period selection
- **Budget Quantity**: Planned consumption quantities for each period
- **Actual Quantity**: Real consumption data for variance analysis
- **Variance Calculation**: Automatic calculation of budget vs. actual differences

### 3. **Data Management**
- **Individual Save**: Save changes for each budget row independently
- **Bulk Operations**: Save all changes at once for efficiency
- **Delete Functionality**: Remove unwanted budget entries
- **Real-time Updates**: Immediate variance calculations and summary updates

### 4. **Reporting & Analysis**
- **Budget Summary**: Side panel showing total budget, actual, and variance
- **Print Reports**: Generate printable consumption budget reports
- **Variance Analysis**: Color-coded variance indicators (green for positive, red for negative)
- **Data Export**: Export functionality for external analysis

### 5. **User Interface**
- **Modern Design**: Clean, responsive interface matching desktop application
- **Toolbar Actions**: Exit, Save, Print, Refresh, and Options buttons
- **Options Modal**: Advanced filtering and sorting capabilities
- **Toast Notifications**: Real-time feedback for user actions

## Database Schema

### `sku_consumption_budgets` Table
```sql
- id (Primary Key)
- sku_id (Foreign Key to mm_skus.sku)
- effective_month (Format: MM/YYYY)
- budget_quantity (Decimal)
- actual_quantity (Decimal)
- variance (Decimal, calculated)
- notes (Text)
- created_by (String)
- updated_by (String)
- timestamps
```

## API Endpoints

### Core CRUD Operations
- `GET /api/material-management/system-requirement/inventory-setup/sku-consumption-budgets` - List all budgets
- `GET /api/material-management/system-requirement/inventory-setup/sku-consumption-budgets/{id}` - Get specific budget
- `POST /api/material-management/system-requirement/inventory-setup/sku-consumption-budgets` - Create new budget
- `PUT /api/material-management/system-requirement/inventory-setup/sku-consumption-budgets/{id}` - Update budget
- `DELETE /api/material-management/system-requirement/inventory-setup/sku-consumption-budgets/{id}` - Delete budget

### Specialized Endpoints
- `GET /api/material-management/system-requirement/inventory-setup/sku-consumption-budgets/by-sku/{skuId}` - Get budgets by SKU
- `GET /api/material-management/system-requirement/inventory-setup/sku-consumption-budgets/by-month/{effectiveMonth}` - Get budgets by month
- `GET /api/material-management/system-requirement/inventory-setup/sku-consumption-budgets/sku-suggestions` - SKU search suggestions
- `GET /api/material-management/system-requirement/inventory-setup/sku-consumption-budgets/available-months` - Get available months
- `POST /api/material-management/system-requirement/inventory-setup/sku-consumption-budgets/bulk-store` - Bulk create/update
- `GET /api/material-management/system-requirement/inventory-setup/sku-consumption-budgets/summary` - Get budget summary

## Web Routes

### Main Application Route
- `GET /material-management/system-requirement/inventory-setup/sku-consumption-budget` - Main application page

## Components

### Main Component: `SkuConsumptionBudget.vue`
- **Location**: `resources/js/Pages/material-management/system-requirement/inventory-setup/SkuConsumptionBudget.vue`
- **Features**: Complete budget management interface with all functionality

### Supporting Components
- `SkuOptionsModal.vue` - Advanced filtering and sorting options
- `SkuLookupModal.vue` - SKU selection modal
- `ToastContainer.vue` - User notification system

## Models

### `SkuConsumptionBudget` Model
- **Location**: `app/Models/SkuConsumptionBudget.php`
- **Relationships**: 
  - `belongsTo(MmSku::class, 'sku_id', 'sku')`
  - `belongsTo(User::class, 'created_by', 'username')`
  - `belongsTo(User::class, 'updated_by', 'username')`
- **Methods**:
  - `calculateVariance()` - Calculate budget vs actual variance
  - `getVariancePercentage()` - Get variance as percentage

## Controllers

### `SkuConsumptionBudgetController`
- **Location**: `app/Http/Controllers/MaterialManagement/SystemRequirement/SkuConsumptionBudgetController.php`
- **Features**:
  - Full CRUD operations
  - Bulk operations
  - Summary calculations
  - SKU suggestions
  - Month management

## Business Logic

### 1. **Variance Calculation**
```php
$variance = $budget_quantity - $actual_quantity;
```

### 2. **Effective Month Format**
- Input: MM/YYYY (e.g., "12/2024")
- Validation: Ensures proper format and valid dates

### 3. **Budget Summary**
- Total Budget: Sum of all budget quantities
- Total Actual: Sum of all actual quantities
- Total Variance: Sum of all variances
- Budget Count: Number of budget entries

### 4. **Data Validation**
- SKU must exist in `mm_skus` table
- Effective month must be in MM/YYYY format
- Quantities must be non-negative numbers
- Unique constraint on SKU + Effective Month combination

## Usage Workflow

### 1. **Initial Setup**
1. Navigate to the SKU Consumption Budget page
2. Select a SKU using search or lookup
3. Set the effective month (MM/YYYY format)
4. Click "Record: Select" to load existing data

### 2. **Budget Entry**
1. Enter budget quantities for each period
2. Enter actual quantities (if available)
3. Review automatic variance calculations
4. Save individual rows or use bulk save

### 3. **Analysis & Reporting**
1. Review budget summary in side panel
2. Analyze variance patterns
3. Generate print reports
4. Export data for external analysis

## Benefits

### 1. **Inventory Optimization**
- Track consumption patterns over time
- Identify seasonal trends
- Optimize inventory levels based on historical data

### 2. **Cost Control**
- Monitor budget vs. actual consumption
- Identify cost overruns early
- Improve budget accuracy for future periods

### 3. **Planning Support**
- Support Material Requirements Planning (MRP)
- Enable better procurement planning
- Facilitate capacity planning

### 4. **Performance Monitoring**
- Track consumption efficiency
- Identify improvement opportunities
- Support continuous improvement initiatives

## Technical Implementation

### Frontend (Vue.js)
- **Framework**: Vue 3 with Composition API
- **Styling**: Tailwind CSS
- **State Management**: Reactive refs and computed properties
- **HTTP Client**: Axios for API communication

### Backend (Laravel)
- **Framework**: Laravel 10
- **Database**: MySQL with proper indexing
- **API**: RESTful API with JSON responses
- **Validation**: Laravel form request validation

### Database
- **Migration**: Proper foreign key constraints
- **Indexing**: Optimized for SKU and month queries
- **Data Integrity**: Unique constraints and validation

## Security Features

### 1. **Authentication**
- All routes protected by authentication middleware
- User tracking for audit trails

### 2. **Authorization**
- Role-based access control (if implemented)
- User-specific data filtering

### 3. **Data Validation**
- Server-side validation for all inputs
- SQL injection protection
- XSS protection through proper escaping

## Performance Considerations

### 1. **Database Optimization**
- Indexed queries for SKU and month lookups
- Efficient bulk operations
- Pagination for large datasets

### 2. **Frontend Optimization**
- Lazy loading of components
- Efficient state management
- Debounced search inputs

### 3. **Caching Strategy**
- Cache frequently accessed SKU data
- Cache budget summaries
- Implement proper cache invalidation

## Future Enhancements

### 1. **Advanced Analytics**
- Trend analysis and forecasting
- Statistical variance analysis
- Machine learning predictions

### 2. **Integration Features**
- Integration with procurement systems
- Real-time inventory updates
- Automated budget adjustments

### 3. **Reporting Enhancements**
- Advanced charting and graphs
- Custom report builder
- Scheduled report generation

### 4. **Mobile Support**
- Responsive design improvements
- Mobile-specific optimizations
- Offline capability

## Maintenance

### 1. **Regular Tasks**
- Database optimization
- Cache clearing
- Log rotation
- Performance monitoring

### 2. **Updates**
- Framework updates
- Security patches
- Feature enhancements
- Bug fixes

This comprehensive SKU Consumption Budget system provides a robust foundation for inventory planning and cost control in ERP environments, with room for future enhancements and integrations. 