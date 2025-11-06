-- Verify Product Units are saved correctly
SELECT 
    product_code,
    description,
    category,
    unit,
    product_group_id,
    is_active
FROM products
ORDER BY product_code;

-- Count products by category with their units
SELECT 
    category,
    unit,
    COUNT(*) as product_count
FROM products
GROUP BY category, unit
ORDER BY category;
