# Unified Color and Color Group System Documentation

## Overview
Define Color dan Define Color Group menggunakan **TABLE COLOR yang sama**, dengan **ColorController** dan **Model Color** yang sama, tanpa membuat controller/model/table baru.

## Database Structure

### Table: COLOR
```
Columns:
- Color_Code (VARCHAR 15) - Primary Key  
- Color_Name (VARCHAR 150)
- GroupCode (VARCHAR 15) - Reference to parent Color Group
- Group (VARCHAR 50) - Type/Category name
```

### Logic untuk membedakan Color dan Color Group:

#### **Color Group**:
- `GroupCode` = `Color_Code` (self-reference) ATAU
- `GroupCode` = `NULL`

Contoh:
```
Color_Code | Color_Name | GroupCode | Group
-----------|------------|-----------|--------
BK         | Black      | BK        | X-Flexo
R          | Red        | R         | X-Flexo
```

#### **Color** (Individual Colors):
- `GroupCode` != `Color_Code` (references parent group)

Contoh:
```
Color_Code | Color_Name    | GroupCode | Group
-----------|---------------|-----------|--------
BK01       | Black Matte   | BK        | X-Flexo
BK02       | Black Glossy  | BK        | X-Flexo
RD01       | Red Standard  | R         | X-Flexo
```

## Model: Color.php

**Location**: `app/Models/Color.php`

### Properties:
```php
protected $table = 'COLOR';
protected $primaryKey = 'Color_Code';
public $timestamps = false;

protected $fillable = [
    'Color_Code',
    'Color_Name',
    'GroupCode',
    'Group'
];
```

### Scopes:

```php
// Get only Color Groups
Color::colorGroups()->get();
// WHERE GroupCode IS NULL OR GroupCode = Color_Code

// Get only Colors  
Color::colors()->get();
// WHERE GroupCode IS NOT NULL AND GroupCode != Color_Code
```

### Relationships:

```php
// Get parent color group for a color
$color->colorGroup();

// Get all colors belonging to a color group
$colorGroup->colors();
```

### Helper Methods:

```php
// Check if record is a color group
$record->isColorGroup(); // returns boolean

// Check if record is a color
$record->isColor(); // returns boolean
```

## Controller: ColorController.php

**Location**: `app/Http/Controllers/ColorController.php`

### Methods untuk Colors:

| Method | Route | Description |
|--------|-------|-------------|
| `index()` | GET /api/colors | Get all colors |
| `store()` | POST /api/colors | Create new color |
| `update($color_id)` | PUT /api/colors/{color_id} | Update color |
| `destroy($color_id)` | DELETE /api/colors/{color_id} | Delete color |

### Methods untuk Color Groups:

| Method | Route | Description |
|--------|-------|-------------|
| `indexColorGroups()` | GET /api/color-groups | Get all color groups |
| `storeColorGroup()` | POST /api/color-groups | Create new color group |
| `updateColorGroup($code)` | PUT /api/color-groups/{code} | Update color group |
| `destroyColorGroup($code)` | DELETE /api/color-groups/{code} | Delete color group |

### Seed Data Method:

```php
private function seedData()
{
    // Seeds both Color Groups and Colors
    // Color Groups: GroupCode = Color_Code
    // Colors: GroupCode references parent group
}
```

## Routes

### Web Routes (`routes/web.php`):

```php
// Define Color page
Route::get('/color', [ColorController::class, 'vueIndex'])
    ->name('vue.color.index');

// Define Color Group page  
Route::get('/color-group', [ColorController::class, 'indexColorGroups'])
    ->name('vue.color-group.index');
```

### API Routes (in `routes/web.php` prefix /api):

```php
// Colors API
Route::get('/colors', [ColorController::class, 'index']);
Route::post('/colors', [ColorController::class, 'store']);
Route::put('/colors/{color_id}', [ColorController::class, 'update']);
Route::delete('/colors/{color_id}', [ColorController::class, 'destroy']);

// Color Groups API
Route::get('/color-groups', [ColorController::class, 'indexColorGroups']);
Route::post('/color-groups', [ColorController::class, 'storeColorGroup']);
Route::put('/color-groups/{id}', [ColorController::class, 'updateColorGroup']);
Route::delete('/color-groups/{id}', [ColorController::class, 'destroyColorGroup']);
```

## Vue Components

### Define Color
**File**: `resources/js/Pages/sales-management/system-requirement/standard-requirement/color.vue`

**API Endpoints**:
- GET `/api/colors` - List all colors
- GET `/api/color-groups` - List color groups for dropdown
- POST `/api/colors` - Create color
- PUT `/api/colors/{color_id}` - Update color
- DELETE `/api/colors/{color_id}` - Delete color

### Define Color Group
**File**: `resources/js/Pages/sales-management/system-requirement/standard-requirement/color-group.vue`

**API Endpoints**:
- GET `/api/color-groups` - List all color groups
- POST `/api/color-groups` - Create color group
- PUT `/api/color-groups/{code}` - Update color group
- DELETE `/api/color-groups/{code}` - Delete color group

## Sample Data

### Color Groups:
```json
[
  {"Color_Code": "BK", "Color_Name": "Black", "GroupCode": "BK", "Group": "X-Flexo"},
  {"Color_Code": "R", "Color_Name": "Red", "GroupCode": "R", "Group": "X-Flexo"},
  {"Color_Code": "B", "Color_Name": "Blue", "GroupCode": "B", "Group": "X-Flexo"}
]
```

### Colors:
```json
[
  {"Color_Code": "BK01", "Color_Name": "Black Matte", "GroupCode": "BK", "Group": "X-Flexo"},
  {"Color_Code": "BK02", "Color_Name": "Black Glossy", "GroupCode": "BK", "Group": "X-Flexo"},
  {"Color_Code": "RD01", "Color_Name": "Red Standard", "GroupCode": "R", "Group": "X-Flexo"}
]
```

## Benefits

### ✅ Single Source of Truth
- Satu table (COLOR) untuk semua data color
- Tidak ada duplikasi data
- Consistency terjaga

### ✅ Reusable Controller & Model
- Satu ColorController untuk Colors dan Color Groups
- Satu Color Model dengan scopes dan relationships
- Easier maintenance

### ✅ Simple Logic
- GroupCode logic yang jelas:
  - Self-reference = Color Group
  - Reference ke parent = Color
- Easy to query dan understand

### ✅ Scalability
- Mudah extend dengan field baru
- Mudah tambah relationships
- Flexible untuk future requirements

## Migration Status

**Table COLOR** sudah ada dan TIDAK PERLU DIUBAH:
- ✅ Structure sudah support untuk Color dan Color Group
- ✅ Tidak ada perubahan schema
- ✅ Data existing tetap aman

## Files Modified

1. ✅ **app/Models/Color.php** - Updated untuk handle Colors dan Color Groups
2. ✅ **app/Http/Controllers/ColorController.php** - Added Color Group methods
3. ✅ **routes/web.php** - Updated routes untuk Color Groups
4. ⚠️ **color.vue** - Perlu update API calls jika diperlukan
5. ⚠️ **color-group.vue** - Perlu update API calls jika diperlukan

## Request Detection Logic

⚠️ **Properly distinguishing API vs Inertia requests**:

ColorController menggunakan logic berikut untuk detect request type:
```php
// Return JSON only for explicit API requests
if ($request->is('api/*') || 
    ($request->header('X-Requested-With') === 'XMLHttpRequest' && !$request->header('X-Inertia'))) {
    return response()->json($data);
}

// Otherwise return Inertia response
return Inertia::render('component', $data);
```

**Why this approach?**
- Inertia requests include `X-Inertia: true` header
- Pure API requests use `api/*` route prefix
- AJAX requests without Inertia header are API requests
- Prevents Inertia error "plain JSON response received"

## Next Steps

1. ✅ Model sudah updated
2. ✅ Controller sudah ada methods untuk Color Groups
3. ✅ Routes sudah configured
4. ✅ Request detection logic fixed
5. ✅ Field transformation implemented
6. ⏳ Test all CRUD operations

## Field Name Transformation

⚠️ **Vue Component vs Database Field Names**:

Vue component menggunakan field names:
- `color_id` → Database: `Color_Code`
- `color_name` → Database: `Color_Name`
- `color_group_id` → Database: `GroupCode`
- `cg_type` → Database: `Group`

ColorController automatically transforms:
- **Request to Database**: `color_id` → `Color_Code`
- **Database to Response**: `Color_Code` → `color_id`

Ini dilakukan di methods:
- `index()` - transform saat return data
- `vueIndex()` - transform saat return Inertia response
- `store()` - transform saat save
- `update()` - transform saat update

## Important Notes

⚠️ **TIDAK ADA TABLE, CONTROLLER, ATAU MODEL BARU YANG DIBUAT**
- Semua menggunakan yang sudah ada (COLOR table, ColorController, Color model)
- Hanya menambahkan methods di ColorController yang sudah ada
- Table COLOR tidak berubah sama sekali

✅ **Color Groups dapat dihapus** hanya jika tidak ada Colors yang menggunakannya (referential integrity check di `destroyColorGroup()`)

✅ **Automatic Field Transformation** - Controller menangani transformasi field names antara Vue component dan database
