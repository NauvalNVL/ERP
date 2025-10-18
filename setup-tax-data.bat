@echo off
echo ========================================
echo Setup Tax Data for Invoice Module
echo ========================================
echo.

echo Running TaxRateSeeder...
php artisan db:seed --class=TaxRateSeeder

echo.
echo ========================================
echo Testing API Endpoint...
echo ========================================
curl http://127.0.0.1:8000/api/invoices/sales-tax-options

echo.
echo.
echo ========================================
echo Setup Complete!
echo ========================================
echo.
echo Next steps:
echo 1. Open browser: http://127.0.0.1:8000
echo 2. Navigate to: Warehouse Management ^> Invoice ^> IV Processing
echo 3. Click "Prepare Invoice by D/Order (Current Period)"
echo 4. Select customer and fill form
echo 5. Click "Continue to Prepare"
echo 6. Check Sales Tax Screen should now display tax options!
echo.
pause
