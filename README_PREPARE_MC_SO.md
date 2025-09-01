# Prepare MC SO - Modern Web Implementation

## Overview
This is a modern web implementation of the "Prepare MC SO" (Master Card Sales Order) functionality from ERP CPS, built with Vue 3, Tailwind CSS, and Laravel backend.

## Features Implemented

### 1. Main Sales Order Form
- **Period Information Management**: Current period, update period, forward/backward periods
- **Customer Information**: Customer code lookup with validation
- **Master Card Information**: Master card sequence lookup with validation
- **Order Details**: Complete order configuration including order mode, product, salesperson, currency, etc.

### 2. Customer Account Lookup Modal
- **Search Functionality**: Search customers by code or name
- **Sorting Options**: Sort by customer code or name
- **Status Filtering**: Filter active/obsolete customers
- **Integration**: Uses existing `UpdateCustomerAccount` model and API

### 3. Master Card Lookup Modal
- **Customer-Specific Search**: Shows master cards for selected customer only
- **Sorting Options**: Sort by MC sequence, model, part#, etc.
- **Status Filtering**: Filter active/obsolete master cards
- **Integration**: Uses existing `MasterCard` model and `ApproveMC` API

### 4. Product Design Screen Modal
- **Item Details Table**: Main item and Fit 1-9 with quantity, unit price, amount calculations
- **Dimensions Table**: External/internal dimensions, part numbers, gross weight
- **Real-time Calculations**: Automatic amount calculation when quantity/price changes
- **Customer Service Integration**: Direct link to customer service dashboard

### 5. Delivery Location Modal
- **Order By/Bill To/Ship To**: Complete delivery address management
- **Delivery Code Lookup**: Integration with customer alternate addresses
- **Address Validation**: Ensures proper delivery information
- **Same Address Option**: Convenience feature for common scenarios

### 6. Delivery Schedule Modal
- **Schedule Grid**: Visual grid showing delivery schedule entries
- **Date/Time Management**: Schedule date and time selection with calendar picker
- **Due Status Options**: ETD/ETA/TBA options
- **Entry Management**: Add/edit/remove schedule entries

## Technical Implementation

### Frontend (Vue 3 + Tailwind CSS)
```
resources/js/Pages/sales-management/sales-order/Transaction/PrepareMCSO.vue
resources/js/Components/DeliveryLocationModal.vue
resources/js/Components/DeliveryScheduleModal.vue
```

### Backend (Laravel)
```
app/Http/Controllers/SalesOrderController.php
routes/web.php (Added sales order routes)
```

### Database Integration
- Uses existing `UpdateCustomerAccount` model for customer data
- Uses existing `MasterCard` and `ApproveMC` models for master card data
- Uses existing `Salesperson` model for salesperson data
- Uses existing `CustomerAlternateAddress` model for delivery locations

## API Endpoints

### Sales Order Management
- `POST /api/sales-order` - Create new sales order
- `GET /api/sales-order/customer/{customerCode}` - Get customer details
- `GET /api/sales-order/master-card/{mcSeq}` - Get master card details
- `GET /api/sales-order/salesperson/{salespersonCode}` - Get salesperson details

### Product Design & Delivery
- `GET /api/sales-order/product-design/{masterCardSeq}` - Get product design data
- `POST /api/sales-order/product-design` - Save product design
- `POST /api/sales-order/delivery-schedule` - Save delivery schedule

### Reports
- `GET /api/sales-order/print-log` - Generate SO log report
- `GET /api/sales-order/print-jit-tracking` - Generate JIT tracking report

## Page Access
Access the Prepare MC SO page at:
```
/sales-order/transaction/prepare-mc-so
```

## Usage Flow

1. **Set Period Information**: Configure current and update periods
2. **Select Customer**: Use customer lookup or enter customer code manually
3. **Select Master Card**: Use master card lookup or enter MC sequence manually
4. **Configure Order Details**: Fill in order mode, product, salesperson, currency, etc.
5. **Product Design**: Click "Continue to Product Design" to configure item details and calculations
6. **Delivery Location**: Set up delivery addresses and locations
7. **Delivery Schedule**: Configure delivery timing and schedule entries
8. **Save**: Complete the sales order creation process

## Modern UI Features

- **Responsive Design**: Works on desktop, tablet, and mobile devices
- **Clean Interface**: Modern card-based layout with proper spacing
- **Interactive Elements**: Hover effects, focus states, loading indicators
- **Toast Notifications**: Success/error feedback for user actions
- **Modal System**: Clean modal dialogs for lookup and configuration
- **Form Validation**: Real-time validation with error messages
- **Accessibility**: Proper ARIA labels and keyboard navigation

## Integration with Existing System

This implementation leverages the existing ERP database structure and APIs:
- Customer accounts from `update_customer_accounts` table
- Master cards from `master_cards` and `approve_mcs` tables
- Salesperson data from `salespersons` table
- Alternate addresses from `customer_alternate_addresses` table

## Screenshots Reference
The implementation is based on the ERP CPS screenshots provided, maintaining the same functionality while modernizing the user interface and user experience.

## Future Enhancements

1. **Real-time Updates**: WebSocket integration for real-time order status updates
2. **Advanced Validation**: Business rule validation (e.g., credit limits, inventory checks)
3. **Batch Operations**: Bulk order creation and management
4. **Mobile App**: Native mobile application for field sales
5. **Integration**: Connect with production planning and inventory systems
