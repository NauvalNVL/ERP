# Vehicle Class Management System

A modern web-based Vehicle Class management system built for the ERP CPS system, featuring a clean and intuitive interface for managing vehicle class definitions and specifications.

## Features

### 🚛 Vehicle Class Management
- **Add New Vehicle Classes**: Create new vehicle class definitions with all required specifications
- **Edit Existing Classes**: Update vehicle class information including codes, descriptions, and capacity details
- **Delete Classes**: Remove vehicle classes from the system with confirmation
- **Search & Filter**: Real-time search functionality across all vehicle class fields
- **Pagination**: Efficient data pagination for large datasets

### 📊 Data Management
- **Volume Tracking**: Manage vehicle volume in cubic meters (M³)
- **Weight Capacity**: Track vehicle capacity weight in metric tons (MT)
- **Standard Class Codes**: Link to standard vehicle classifications
- **Unique Codes**: Ensure vehicle class codes are unique across the system

### 📋 Reporting & Export
- **View & Print Reports**: Generate comprehensive vehicle class reports
- **Export to CSV**: Export data for external analysis
- **Print Functionality**: Print reports directly from the browser
- **Summary Statistics**: View average volume and capacity metrics

## Database Schema

The system uses the `vehicleclass` table with the following structure:

```sql
CREATE TABLE vehicleclass (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    NO_ VARCHAR(50),
    VEHICLE_CLASS_CODE VARCHAR(50) UNIQUE,
    DESCRIPTION VARCHAR(50),
    STANDART_CLASS_CODE VARCHAR(50),
    VOLUME_M3 FLOAT,
    CAPACITY_WGT_MT FLOAT,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

## API Endpoints

### Vehicle Class Management
- `GET /api/vehicle-classes` - List all vehicle classes
- `POST /api/vehicle-classes` - Create a new vehicle class
- `GET /api/vehicle-classes/{id}` - Get specific vehicle class
- `PUT /api/vehicle-classes/{id}` - Update vehicle class
- `DELETE /api/vehicle-classes/{id}` - Delete vehicle class

### Web Routes
- `GET /warehouse-management/delivery-order/setup/vehicle-class` - Main vehicle class management page
- `GET /warehouse-management/delivery-order/setup/vehicle-class/view-print` - View and print reports

## Sample Data

The system comes pre-loaded with sample vehicle classes:

| Code | Description | Standard Class | Volume (M³) | Capacity (MT) |
|------|-------------|----------------|-------------|---------------|
| BE | T. Box Engkel | #03-1 | 18.50 | 3.00 |
| DB | D.BOX | #05-1 | 22.90 | 5.00 |
| GM | GRAND MAX | #01-1 | 8.57 | 1.00 |
| HT | H.TRAILER | #40-1 | 60.00 | 40.00 |
| LL | LOHAN ENGKEL | #10-1 | 41.95 | 10.00 |
| WB | WING BOX | #24-1 | 40.45 | 24.00 |

## Installation & Setup

1. **Run Migration**:
   ```bash
   php artisan migrate
   ```

2. **Seed Sample Data**:
   ```bash
   php artisan db:seed --class=VehicleClassSeeder
   ```

3. **Access the System**:
   Navigate to `/warehouse-management/delivery-order/setup/vehicle-class` in your browser

## Technical Implementation

### Backend Components
- **Model**: `App\Models\VehicleClass` - Eloquent model with relationships and scopes
- **Controller**: `App\Http\Controllers\VehicleClassController` - Handles CRUD operations and API endpoints
- **Migration**: `2024_07_01_create_vehicleclass_table.php` - Database schema definition
- **Seeder**: `VehicleClassSeeder` - Sample data population

### Frontend Components
- **Main Page**: `VehicleClass.vue` - Modern table interface with CRUD operations
- **Reports**: `ViewPrintVehicleClass.vue` - Report generation and export functionality
- **Layout**: Uses `AppLayout.vue` for consistent UI structure
- **Toast Notifications**: Integrated toast system for user feedback

### Key Features
- **Responsive Design**: Mobile-friendly interface using Tailwind CSS
- **Real-time Search**: Instant filtering without page reloads
- **Form Validation**: Client and server-side validation
- **Error Handling**: Comprehensive error handling with user-friendly messages
- **Export Functionality**: CSV export with proper formatting
- **Print Support**: Browser print functionality with custom styling

## Usage Guide

### Adding a New Vehicle Class
1. Click the "Add Vehicle Class" button
2. Fill in the required fields:
   - Vehicle Class Code (unique identifier)
   - Description (human-readable name)
   - Standard Class Code (reference to standard classification)
   - Volume in cubic meters
   - Capacity weight in metric tons
3. Click "Add Vehicle Class" to save

### Editing a Vehicle Class
1. Click the edit icon (pencil) next to the vehicle class
2. Modify the required fields
3. Click "Update Vehicle Class" to save changes

### Searching Vehicle Classes
1. Use the search box to filter by:
   - Vehicle Class Code
   - Description
   - Standard Class Code
2. Results update in real-time as you type

### Generating Reports
1. Navigate to the "View & Print" page
2. Use the export buttons to:
   - Print the report
   - Export to PDF (placeholder)
   - Export to Excel (CSV format)

## Security Features
- CSRF protection on all forms
- Input validation and sanitization
- Unique constraint enforcement
- Proper error handling without data exposure

## Browser Compatibility
- Modern browsers with ES6+ support
- Responsive design for mobile and desktop
- Print functionality across all major browsers

## Future Enhancements
- PDF export functionality
- Advanced filtering options
- Bulk operations (import/export)
- Audit trail for changes
- Integration with other ERP modules
