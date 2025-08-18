# SKU Item Note Analysis Group Feature

## Overview

This feature implements the "Define SKU Item Note Analysis Group" functionality for the ERP system, allowing users to create and manage analysis groups for categorizing SKU item notes and observations.

## Purpose

The SKU Item Note Analysis Group feature serves as a categorization system for organizing various types of notes and observations related to SKU items in the system. This helps in:

- **Organized Documentation**: Categorize different types of notes (quality issues, supplier feedback, storage conditions, etc.)
- **Better Analysis**: Enable filtering and reporting based on note categories
- **Structured Data Management**: Maintain consistent categorization across departments
- **Improved Searchability**: Find relevant notes quickly using category-based filters

## Features Implemented

### Database Layer
- **Migration**: `2025_01_20_000000_create_sku_item_note_analysis_groups_table.php`
- **Model**: `App\Models\SkuItemNoteAnalysisGroup`
- **Seeder**: `SkuItemNoteAnalysisGroupSeeder` with predefined categories

### Backend API
- **Controller**: `App\Http\Controllers\MaterialManagement\SystemRequirement\SkuItemNoteAnalysisGroupController`
- **API Routes**: Full CRUD operations with additional utility endpoints

### Frontend Interface
- **Vue Component**: `resources/js/Pages/material-management/system-requirement/purchase-order-setup/SkuItemNoteAnalysisGroup.vue`
- **Web Route**: `/material-management/system-requirement/purchase-order-setup/sku-item-note-analysis-group`

## Database Schema

The `sku_item_note_analysis_groups` table contains:
- `id`: Primary key
- `group_code`: Unique analysis group code (max 20 chars)
- `group_name`: Analysis group name (max 100 chars)
- `description`: Optional description
- `category`: Optional category/type (max 50 chars)
- `is_active`: Active status (boolean)
- `created_at`, `updated_at`: Timestamps

## Pre-defined Categories

The system comes with several pre-configured analysis groups organized into categories:

### Quality Control
- Quality Issue Notes
- Quality Inspection Results
- Quality Improvement Notes

### Supplier Management
- Supplier Feedback Notes
- Supplier Performance Notes
- Supplier Issue Resolution

### Storage Management
- Storage Condition Notes
- Warehouse Location Notes
- Inventory Adjustment Notes

### Production
- Production Usage Notes
- Production Issue Notes
- Production Efficiency Notes

### Maintenance
- Maintenance Schedule Notes
- Maintenance Issue Notes

### Cost Management
- Cost Analysis Notes
- Price Variance Notes

### Safety & Compliance
- Safety Compliance Notes
- Environmental Notes

### General
- General Observations
- Special Instructions

## API Endpoints

### GET `/api/sku-item-note-analysis-groups`
Retrieve all analysis groups with optional filtering:
- `search`: Search by code, name, category, or description
- `category`: Filter by category
- `status`: Filter by active/inactive status
- `sort_by`: Sort field (default: group_code)
- `sort_direction`: Sort direction (asc/desc)

### POST `/api/sku-item-note-analysis-groups`
Create a new analysis group:
```json
{
    "group_code": "QC-001",
    "group_name": "Quality Issue Notes",
    "category": "Quality Control",
    "description": "Notes related to quality issues...",
    "is_active": true
}
```

### GET `/api/sku-item-note-analysis-groups/{id}`
Retrieve a specific analysis group

### PUT `/api/sku-item-note-analysis-groups/{id}`
Update an existing analysis group

### DELETE `/api/sku-item-note-analysis-groups/{id}`
Delete an analysis group

### PATCH `/api/sku-item-note-analysis-groups/{id}/toggle-active`
Toggle the active status of an analysis group

### GET `/api/sku-item-note-analysis-groups/categories`
Get all unique categories

### GET `/api/sku-item-note-analysis-groups/for-print`
Get filtered data for printing/reporting

## Frontend Features

The Vue.js interface provides:

### Main Interface
- **Search and Filter**: Real-time search with category and status filters
- **Statistics Dashboard**: Overview cards showing totals and breakdowns
- **Data Table**: Sortable table with pagination
- **Details Panel**: Selected group information display

### CRUD Operations
- **Create/Edit Modal**: Form for adding/editing analysis groups
- **Delete Confirmation**: Safe deletion with confirmation
- **Status Toggle**: Quick active/inactive status changes

### Additional Features
- **View & Print Modal**: Filtered reporting with print functionality
- **Responsive Design**: Works on desktop and mobile devices
- **Toast Notifications**: User feedback for all operations
- **Validation**: Client and server-side validation

## Usage Instructions

1. **Access the Feature**: Navigate to Material Management → System Requirement → Purchase Order Setup → SKU Item Note Analysis Group

2. **Create New Group**: Click "New Group" button and fill in the required information

3. **Search and Filter**: Use the search bar and filter dropdowns to find specific groups

4. **Edit Groups**: Click the edit icon on any row to modify the group

5. **Toggle Status**: Use the play/pause icon to activate/deactivate groups

6. **View Details**: Click on any row to see detailed information in the side panel

7. **Print Reports**: Use "View & Print" to generate filtered reports

## Integration Points

This feature is designed to integrate with:
- **SKU Item Note Analysis Note**: The actual notes will reference these groups
- **Reporting Systems**: For categorized analysis and reporting
- **User Permissions**: Respects user access controls
- **Audit Trail**: Maintains creation and update timestamps

## Technical Notes

### Model Features
- **Eloquent Scopes**: Active, inactive, by category, and search scopes
- **Automatic Code Formatting**: Group codes are automatically uppercase
- **Validation**: Unique code validation and required field validation

### Controller Features
- **RESTful API**: Standard REST operations
- **Error Handling**: Proper error responses and validation
- **Filtering**: Multiple filter options for flexible data retrieval

### Frontend Features
- **Vue 3 Composition API**: Modern Vue.js implementation
- **Reactive Data**: Real-time updates and filtering
- **Component Reusability**: Uses shared components and composables

## Installation Steps Completed

1. ✅ Database migration created and run
2. ✅ Model with relationships and scopes created
3. ✅ Controller with full CRUD operations implemented
4. ✅ API routes registered
5. ✅ Seeder with sample data created and run
6. ✅ Vue.js component with full interface created
7. ✅ Web route registered
8. ✅ DatabaseSeeder updated

The feature is now ready for use and can be accessed through the ERP system's navigation menu. 