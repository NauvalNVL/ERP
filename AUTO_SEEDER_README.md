# Auto-Seeder for Standard Requirements

This implementation provides automatic seeding functionality for all standard requirement menu items in the ERP system. The seeders run automatically when users access the pages, eliminating the need to manually click "Run Seeder" buttons.

## Features

- **Automatic Seeding**: All standard requirement seeders run automatically when pages are loaded
- **Session-based**: Seeders only run once per browser session to avoid unnecessary database operations
- **Error Handling**: Comprehensive error handling with detailed feedback
- **Toast Notifications**: User-friendly notifications for seeding status
- **Comprehensive Coverage**: Covers all 15 standard requirement modules

## Covered Modules

The auto-seeder covers the following standard requirement modules:

1. **Sales Team** (`SalesTeamSeeder`)
2. **Salesperson** (`SalespersonSeeder`)
3. **Salesperson Team** (`SalespersonTeamSeeder`)
4. **Industry** (`IndustrySeeder`)
5. **Geo** (`GeoSeeder`)
6. **Product Group** (`ProductGroupSeeder`)
7. **Product** (`ProductSeeder`)
8. **Product Design** (`ProductDesignSeeder`)
9. **Scoring Tool** (`ScoringToolSeeder`)
10. **Paper Quality** (`PaperQualitySeeder`)
11. **Paper Flute** (`PaperFluteSeeder`)
12. **Paper Size** (`PaperSizeSeeder`)
13. **Color Group** (`ColorGroupSeeder`)
14. **Color** (`ColorSeeder`)
15. **Finishing** (`FinishingSeeder`)

## Implementation Details

### 1. Database Seeder Updates

The `DatabaseSeeder.php` has been updated to include all standard requirement seeders in the proper order:

```php
$this->call([
    // Standard Requirement Seeders - Sales Management
    SalesTeamSeeder::class,
    SalespersonSeeder::class,
    SalespersonTeamSeeder::class,
    // ... other seeders
]);
```

### 2. API Route

A new API route `/api/auto-seed-standard-requirements` has been added that:
- Runs all standard requirement seeders
- Provides detailed feedback for each seeder
- Handles errors gracefully
- Returns comprehensive results

### 3. Vue Composable

The `useAutoSeeder.js` composable provides:
- `checkAndRunAutoSeed()`: Checks session storage and runs seeders if needed
- `runAutoSeed()`: Manually triggers the seeding process
- Session-based tracking to prevent duplicate runs
- Toast notifications for user feedback

### 4. Component Integration

All standard requirement Vue components have been updated to:
- Import the `useAutoSeeder` composable
- Call `checkAndRunAutoSeed()` in their `onMounted` lifecycle
- Run seeders before fetching data

## Usage

### Automatic Usage
The auto-seeder runs automatically when users visit any standard requirement page. No manual intervention is required.

### Manual Testing
You can test the auto-seeder functionality by visiting:
```
/test-auto-seeder
```

This page provides a manual trigger and detailed results display.

### Programmatic Usage
```javascript
import { useAutoSeeder } from '@/Composables/useAutoSeeder'

const { checkAndRunAutoSeed, runAutoSeed } = useAutoSeeder()

// Check and run if needed (used in components)
await checkAndRunAutoSeed()

// Force run (for testing)
await runAutoSeed()
```

## Session Management

The auto-seeder uses `sessionStorage` to track whether seeders have been run in the current browser session:
- Key: `standard_requirements_auto_seeded`
- Value: `'true'` when seeders have been run
- Resets when browser session ends

## Error Handling

The implementation includes comprehensive error handling:
- Individual seeder failures don't stop the entire process
- Detailed error messages for each failed seeder
- Toast notifications for user feedback
- Console logging for debugging

## Benefits

1. **Improved User Experience**: No need to manually run seeders
2. **Consistent Data**: Ensures all standard requirements have proper seed data
3. **Reduced Support**: Eliminates common user confusion about missing data
4. **Development Efficiency**: Faster setup for new environments
5. **Data Integrity**: Ensures all related data is properly seeded

## Technical Notes

- Seeders use `updateOrCreate()` methods to prevent duplicate data
- CSRF protection is maintained for all API calls
- All operations are asynchronous and non-blocking
- Session storage prevents unnecessary database operations
- Toast notifications provide immediate user feedback

## Maintenance

To add new standard requirement modules:
1. Create the seeder class
2. Add it to the `DatabaseSeeder.php`
3. Add it to the auto-seeder API route
4. Update the Vue component to use `useAutoSeeder`

The system is designed to be easily extensible for future standard requirement modules.
