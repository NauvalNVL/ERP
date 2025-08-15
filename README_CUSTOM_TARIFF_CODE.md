# Custom Tariff Code Management System

## Overview

The "Define Custom Tariff Code" feature is a comprehensive ERP system module designed to manage and maintain HS Codes (Harmonized System Codes) for import/export customs documentation and duty calculations. This system helps organizations comply with international trade regulations and accurately calculate customs duties and taxes.

## Key Features

### 1. **Tariff Code Management**
- **HS Code Registration**: Register and manage HS Codes with proper formatting
- **Code Validation**: Ensure unique and valid tariff codes
- **Active/Inactive Status**: Toggle tariff codes for current use
- **Category Classification**: Organize codes by product categories

### 2. **Duty and Tax Configuration**
- **Duty Rate Management**: Set and manage import duty rates (0-100%)
- **Tax Rate Configuration**: Configure applicable tax rates
- **Total Rate Calculation**: Automatic calculation of combined rates
- **Rate Validation**: Ensure rates are within acceptable ranges

### 3. **Import/Export Documentation**
- **Country of Origin**: Track source countries for trade agreements
- **Detailed Descriptions**: Comprehensive product descriptions
- **Notes and Comments**: Additional information for customs officials
- **Print Reports**: Generate printable tariff code reports

### 4. **Customs Calculation Engine**
- **Duty Calculation**: Calculate import duties based on value and rate
- **Tax Calculation**: Compute applicable taxes
- **Total Customs**: Sum of duty and tax amounts
- **Real-time Calculations**: Instant calculations for customs planning

## Database Structure

### Custom Tariff Codes Table
```sql
CREATE TABLE custom_tariff_codes (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    code VARCHAR(20) UNIQUE NOT NULL,           -- HS Code
    name VARCHAR(255) NOT NULL,                 -- Product description
    description TEXT NULL,                      -- Detailed description
    duty_rate DECIMAL(5,2) DEFAULT 0,          -- Duty rate percentage
    tax_rate DECIMAL(5,2) DEFAULT 0,           -- Tax rate percentage
    category VARCHAR(100) NULL,                 -- Product category
    country_origin VARCHAR(50) NULL,            -- Country of origin
    is_active BOOLEAN DEFAULT TRUE,             -- Active status
    notes TEXT NULL,                           -- Additional notes
    created_by VARCHAR(50) NULL,               -- Creator username
    updated_by VARCHAR(50) NULL,               -- Updater username
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    
    INDEX idx_code_active (code, is_active),
    INDEX idx_category (category)
);
```

## API Endpoints

### Core CRUD Operations
- `GET /api/material-management/system-requirement/inventory-setup/custom-tariff-codes` - List tariff codes
- `GET /api/material-management/system-requirement/inventory-setup/custom-tariff-codes/{id}` - Get specific tariff code
- `POST /api/material-management/system-requirement/inventory-setup/custom-tariff-codes` - Create new tariff code
- `PUT /api/material-management/system-requirement/inventory-setup/custom-tariff-codes/{id}` - Update tariff code
- `DELETE /api/material-management/system-requirement/inventory-setup/custom-tariff-codes/{id}` - Delete tariff code

### Additional Features
- `GET /api/material-management/system-requirement/inventory-setup/custom-tariff-codes/suggestions` - Search suggestions
- `GET /api/material-management/system-requirement/inventory-setup/custom-tariff-codes/categories` - Get categories
- `POST /api/material-management/system-requirement/inventory-setup/custom-tariff-codes/calculate` - Calculate customs
- `POST /api/material-management/system-requirement/inventory-setup/custom-tariff-codes/bulk-store` - Bulk import
- `PATCH /api/material-management/system-requirement/inventory-setup/custom-tariff-codes/{id}/toggle-active` - Toggle status
- `GET /api/material-management/system-requirement/inventory-setup/custom-tariff-codes/summary` - Get summary
- `GET /api/material-management/system-requirement/inventory-setup/custom-tariff-codes/export` - Export data

## Usage Examples

### 1. Creating a New Tariff Code
```javascript
// Create a new tariff code
const newTariffCode = {
    code: '12345678',
    name: 'Paper and paperboard products',
    description: 'Various types of paper and paperboard for packaging',
    duty_rate: 5.00,
    tax_rate: 10.00,
    category: 'Paper Products',
    country_origin: 'Indonesia',
    is_active: true,
    notes: 'Standard HS code for paper products'
};

await axios.post('/api/material-management/system-requirement/inventory-setup/custom-tariff-codes', newTariffCode);
```

### 2. Calculating Customs Duties
```javascript
// Calculate customs for a specific value
const calculation = {
    tariff_code_id: 1,
    value: 10000.00
};

const result = await axios.post('/api/material-management/system-requirement/inventory-setup/custom-tariff-codes/calculate', calculation);

// Result includes:
// - duty_amount: 500.00 (5% of 10000)
// - tax_amount: 1000.00 (10% of 10000)
// - total_customs: 1500.00
```

### 3. Searching Tariff Codes
```javascript
// Search for tariff codes
const searchResults = await axios.get('/api/material-management/system-requirement/inventory-setup/custom-tariff-codes', {
    params: {
        search: 'paper',
        category: 'Paper Products',
        is_active: true
    }
});
```

## Business Benefits

### 1. **Compliance Management**
- Ensure proper HS Code classification
- Maintain up-to-date duty and tax rates
- Comply with international trade regulations
- Reduce customs clearance delays

### 2. **Cost Optimization**
- Accurate duty and tax calculations
- Identify duty-free trade agreements
- Optimize import/export costs
- Plan customs expenses in advance

### 3. **Documentation Support**
- Generate customs documentation
- Print tariff code reports
- Maintain audit trails
- Support customs declarations

### 4. **Operational Efficiency**
- Centralized tariff code management
- Quick search and retrieval
- Bulk import capabilities
- Real-time calculations

## Integration Points

### 1. **Import/Export Operations**
- Link to import purchase orders
- Connect with export sales orders
- Automate customs calculations
- Generate customs documentation

### 2. **Financial Management**
- Integrate with accounts payable
- Connect to customs duty accounts
- Support tax calculations
- Provide cost allocation data

### 3. **Compliance Reporting**
- Generate customs reports
- Support regulatory compliance
- Maintain audit trails
- Provide trade statistics

## Security and Access Control

### 1. **User Permissions**
- Role-based access control
- Audit trail for changes
- User activity logging
- Secure API endpoints

### 2. **Data Validation**
- Input validation for all fields
- Rate range validation
- Code format validation
- Duplicate prevention

### 3. **Backup and Recovery**
- Regular database backups
- Data export capabilities
- Version control for changes
- Disaster recovery procedures

## Future Enhancements

### 1. **Advanced Features**
- Integration with customs APIs
- Real-time rate updates
- Trade agreement management
- Automated classification

### 2. **Reporting and Analytics**
- Customs cost analysis
- Trade pattern reports
- Compliance dashboards
- Performance metrics

### 3. **Integration Capabilities**
- ERP system integration
- Customs authority APIs
- Third-party logistics
- Trade management systems

## Technical Implementation

### 1. **Frontend Components**
- `CustomTariffCode.vue` - Main component
- Form validation and error handling
- Real-time calculations
- Print functionality

### 2. **Backend Services**
- `CustomTariffCodeController` - API controller
- `CustomTariffCode` model - Data model
- Database migrations and seeders
- Validation rules

### 3. **Database Design**
- Optimized indexes for performance
- Proper relationships and constraints
- Audit trail fields
- Scalable structure

This system provides a comprehensive solution for managing HS Codes and customs calculations, supporting international trade operations and ensuring regulatory compliance. 