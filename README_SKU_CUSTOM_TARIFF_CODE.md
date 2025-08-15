# SKU Custom Tariff Code Management System

## Overview

The "Define SKU Custom Tariff Code" feature is a comprehensive ERP system module designed to link each SKU (Stock Keeping Unit) with appropriate HS Codes (Harmonized System Codes) for accurate import/export customs management. This system ensures legal compliance and accurate customs calculations for international trade operations.

## Key Features

### 1. **SKU-Tariff Code Linking**
- **SKU Assignment**: Link each SKU with appropriate HS Codes
- **Tariff Code Lookup**: Search and select tariff codes from the database
- **Bulk Operations**: Assign tariff codes to multiple SKUs efficiently
- **Validation**: Prevent duplicate assignments and ensure data integrity

### 2. **Customs Rate Management**
- **Duty Rate Configuration**: Set import duty rates (0-100%)
- **VAT/PPN Rate Management**: Configure Value Added Tax rates
- **PPh Import Rate**: Set Pajak Penghasilan import rates
- **Total Rate Calculation**: Automatic calculation of combined rates

### 3. **Import/Export Documentation**
- **Country of Origin**: Track source countries for trade agreements
- **Tariff Descriptions**: Official product descriptions for customs
- **Documentation Support**: Generate customs documentation automatically
- **Compliance Tracking**: Ensure regulatory compliance

### 4. **Customs Calculation Engine**
- **Duty Calculation**: Calculate import duties based on SKU and value
- **Tax Calculation**: Compute VAT and PPh amounts
- **Total Customs**: Sum of all applicable customs charges
- **Real-time Calculations**: Instant calculations for customs planning

## Database Structure

### SKU Custom Tariff Codes Table
```sql
CREATE TABLE sku_custom_tariff_codes (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    sku_id VARCHAR(20) NOT NULL,                    -- References mm_skus.sku
    custom_tariff_code_id BIGINT NOT NULL,          -- References custom_tariff_codes.id
    tariff_description VARCHAR(255) NULL,            -- Official tariff description
    duty_rate DECIMAL(5,2) DEFAULT 0,               -- Duty rate percentage
    vat_rate DECIMAL(5,2) DEFAULT 0,                -- VAT/PPN rate percentage
    pph_import_rate DECIMAL(5,2) DEFAULT 0,         -- PPh import rate percentage
    country_origin VARCHAR(50) NULL,                 -- Country of origin
    is_active BOOLEAN DEFAULT TRUE,                 -- Active status
    notes TEXT NULL,                                -- Additional notes
    created_by VARCHAR(50) NULL,                    -- Creator username
    updated_by VARCHAR(50) NULL,                    -- Updater username
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    
    FOREIGN KEY (sku_id) REFERENCES mm_skus(sku) ON DELETE CASCADE,
    FOREIGN KEY (custom_tariff_code_id) REFERENCES custom_tariff_codes(id) ON DELETE CASCADE,
    UNIQUE KEY unique_sku_tariff (sku_id, custom_tariff_code_id),
    INDEX idx_sku_active (sku_id, is_active),
    INDEX idx_tariff_code (custom_tariff_code_id)
);
```

## API Endpoints

### Core CRUD Operations
- `GET /api/material-management/system-requirement/inventory-setup/sku-custom-tariff-codes` - List SKU tariff codes
- `GET /api/material-management/system-requirement/inventory-setup/sku-custom-tariff-codes/{id}` - Get specific SKU tariff code
- `POST /api/material-management/system-requirement/inventory-setup/sku-custom-tariff-codes` - Create new SKU tariff code
- `PUT /api/material-management/system-requirement/inventory-setup/sku-custom-tariff-codes/{id}` - Update SKU tariff code
- `DELETE /api/material-management/system-requirement/inventory-setup/sku-custom-tariff-codes/{id}` - Delete SKU tariff code

### Additional Features
- `GET /api/material-management/system-requirement/inventory-setup/sku-custom-tariff-codes/sku-suggestions` - SKU search suggestions
- `GET /api/material-management/system-requirement/inventory-setup/sku-custom-tariff-codes/tariff-code-suggestions` - Tariff code search suggestions
- `GET /api/material-management/system-requirement/inventory-setup/sku-custom-tariff-codes/by-sku/{skuId}` - Get tariff code by SKU
- `POST /api/material-management/system-requirement/inventory-setup/sku-custom-tariff-codes/calculate` - Calculate customs for SKU
- `POST /api/material-management/system-requirement/inventory-setup/sku-custom-tariff-codes/bulk-store` - Bulk import
- `PATCH /api/material-management/system-requirement/inventory-setup/sku-custom-tariff-codes/{id}/toggle-active` - Toggle status
- `GET /api/material-management/system-requirement/inventory-setup/sku-custom-tariff-codes/summary` - Get summary
- `GET /api/material-management/system-requirement/inventory-setup/sku-custom-tariff-codes/export` - Export data
- `GET /api/material-management/system-requirement/inventory-setup/sku-custom-tariff-codes/skus-without-tariff-codes` - Get SKUs without tariff codes

## Usage Examples

### 1. Creating a New SKU Tariff Code Assignment
```javascript
// Create a new SKU tariff code assignment
const newAssignment = {
    sku_id: 'SKU001',
    custom_tariff_code_id: 1,
    tariff_description: 'Paper and paperboard products',
    duty_rate: 5.00,
    vat_rate: 10.00,
    pph_import_rate: 2.50,
    country_origin: 'Indonesia',
    is_active: true,
    notes: 'Standard assignment for paper products'
};

await axios.post('/api/material-management/system-requirement/inventory-setup/sku-custom-tariff-codes', newAssignment);
```

### 2. Calculating Customs for a SKU
```javascript
// Calculate customs for a specific SKU and value
const calculation = {
    sku_id: 'SKU001',
    value: 10000.00
};

const result = await axios.post('/api/material-management/system-requirement/inventory-setup/sku-custom-tariff-codes/calculate', calculation);

// Result includes:
// - duty_amount: 500.00 (5% of 10000)
// - vat_amount: 1000.00 (10% of 10000)
// - pph_import_amount: 250.00 (2.5% of 10000)
// - total_customs: 1750.00
```

### 3. Searching SKU Tariff Codes
```javascript
// Search for SKU tariff codes
const searchResults = await axios.get('/api/material-management/system-requirement/inventory-setup/sku-custom-tariff-codes', {
    params: {
        search: 'paper',
        is_active: true
    }
});
```

## Business Benefits

### 1. **🧾 Customs Classification**
- Ensure proper HS Code classification for each SKU
- Maintain up-to-date duty and tax rates
- Comply with international trade regulations
- Reduce customs clearance delays

### 2. **💰 Cost Optimization**
- Accurate duty and tax calculations
- Identify duty-free trade agreements
- Optimize import/export costs
- Plan customs expenses in advance

### 3. **📦 Documentation Automation**
- Generate customs documentation automatically
- Pull HS Code data to invoices and packing lists
- Support PIB/PEB document generation
- Maintain audit trails

### 4. **✅ Legal Compliance**
- Ensure compliance with DJBC regulations
- Meet international trade standards
- Maintain proper documentation
- Support customs declarations

## Field Descriptions

| Field | Description |
|-------|-------------|
| SKU Code | Product code used in ERP system |
| SKU Description | Product name in the system |
| Custom Tariff Code / HS Code | Official classification code for the product |
| Tariff Description | Official description from customs authority |
| Duty Rate (%) | Import duty percentage applied |
| VAT / PPN (%) | Value Added Tax percentage for imports |
| PPh Import (%) | Income tax percentage for imports (if applicable) |
| Country of Origin | Source country (if rates vary by country) |
| Status | Active/Inactive for historical management |

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
- Duplicate prevention
- Foreign key constraints

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
- `SkuCustomTariffCode.vue` - Main component with table layout
- Tariff code lookup modal
- Real-time calculations
- Print functionality

### 2. **Backend Services**
- `SkuCustomTariffCodeController` - API controller
- `SkuCustomTariffCode` model - Data model
- Database migrations and seeders
- Validation rules

### 3. **Database Design**
- Optimized indexes for performance
- Proper relationships and constraints
- Audit trail fields
- Scalable structure

This system provides a comprehensive solution for linking SKUs with HS Codes, ensuring accurate customs calculations and regulatory compliance for international trade operations. 