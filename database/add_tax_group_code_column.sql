-- SQL Script to add tax_group_code column to tax_types table
-- Run this script if your tax_types table already exists

-- Check if column exists first
IF NOT EXISTS (
    SELECT * FROM INFORMATION_SCHEMA.COLUMNS 
    WHERE TABLE_NAME = 'tax_types' 
    AND COLUMN_NAME = 'tax_group_code'
)
BEGIN
    -- Add the column
    ALTER TABLE tax_types
    ADD tax_group_code NVARCHAR(20) NULL;
    
    PRINT 'Column tax_group_code added successfully to tax_types table';
END
ELSE
BEGIN
    PRINT 'Column tax_group_code already exists in tax_types table';
END
GO
