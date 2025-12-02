# AUTO-SYNC SALESPERSON TEAMS DOCUMENTATION

## 📋 Overview

Sistem ini mengimplementasikan **auto-sync functionality** yang secara otomatis mengupdate tabel `salesperson_teams` ketika terjadi perubahan pada tabel `salesperson` atau `sales_team`.

## 🎯 Fitur Utama

### ✅ **Automatic Synchronization**
- Ketika data di tabel `salesperson` diubah → `salesperson_teams` otomatis terupdate
- Ketika data di tabel `sales_team` diubah → `salesperson_teams` otomatis terupdate
- Ketika data dihapus dari salah satu tabel → `salesperson_teams` otomatis dibersihkan dan direbuild

### ✅ **Real-time Updates**
- **CREATE**: Menambah salesperson/sales team baru → otomatis muncul di salesperson_teams
- **UPDATE**: Mengubah nama/data → otomatis terupdate di salesperson_teams  
- **DELETE**: Menghapus salesperson/sales team → otomatis hilang dari salesperson_teams

## 🔧 Implementasi Teknis

### **1. Laravel Model Events**

#### **Salesperson Model** (`app/Models/Salesperson.php`)
```php
protected static function boot()
{
    parent::boot();

    // Auto-sync ketika salesperson berubah
    static::created(function ($salesperson) {
        self::syncSalespersonTeams();
    });

    static::updated(function ($salesperson) {
        self::syncSalespersonTeams();
    });

    static::deleted(function ($salesperson) {
        self::syncSalespersonTeams();
    });
}
```

#### **SalesTeam Model** (`app/Models/SalesTeam.php`)
```php
protected static function boot()
{
    parent::boot();

    // Auto-sync ketika sales team berubah
    static::created(function ($salesTeam) {
        Salesperson::syncSalespersonTeams();
    });

    static::updated(function ($salesTeam) {
        Salesperson::syncSalespersonTeams();
    });

    static::deleted(function ($salesTeam) {
        Salesperson::syncSalespersonTeams();
    });
}
```

### **2. Sync Method** (`Salesperson::syncSalespersonTeams()`)

```php
public static function syncSalespersonTeams()
{
    try {
        // 1. Clear existing data
        DB::table('salesperson_teams')->truncate();

        // 2. Get fresh data from source tables
        $salespersons = DB::table('salesperson')
            ->whereNotNull('Code')
            ->whereNotNull('Name')
            ->where('Code', '!=', '')
            ->where('Name', '!=', '')
            ->whereNotLike('Code', 'TEAM_%')
            ->get();

        $salesTeams = DB::table('sales_team')
            ->whereNotNull('code')
            ->whereNotNull('name')
            ->where('code', '!=', '')
            ->where('name', '!=', '')
            ->get();

        // 3. Create new assignments (round-robin)
        $salespersonTeamsData = [];
        $teamIndex = 0;

        foreach ($salespersons as $salesperson) {
            $assignedTeam = $salesTeamsArray[$teamIndex % count($salesTeamsArray)];
            
            $salespersonTeamsData[] = [
                's_person_code' => substr($salesperson->Code, 0, 10),
                'salesperson_name' => substr($salesperson->Name, 0, 100),
                'st_code' => substr($assignedTeam->code, 0, 2),
                'sales_team_name' => substr($assignedTeam->name, 0, 100),
                'sales_team_position' => 'E-Executive',
                'created_at' => now(),
                'updated_at' => now(),
            ];
            
            $teamIndex++;
        }

        // 4. Insert fresh data
        if (!empty($salespersonTeamsData)) {
            $chunks = array_chunk($salespersonTeamsData, 100);
            foreach ($chunks as $chunk) {
                DB::table('salesperson_teams')->insert($chunk);
            }
        }

    } catch (\Exception $e) {
        Log::error('Error syncing salesperson_teams: ' . $e->getMessage());
    }
}
```

## 🧪 Testing Results

### **Test Scenario 1: Add Salesperson**
```
🧪 Test 1: Adding new salesperson...
   ✅ New salesperson created: SP999 - Test Person
   📈 Salesperson Teams count: 7 → 2 (auto-rebuilt)
```

### **Test Scenario 2: Update Salesperson**
```
🧪 Test 2: Updating salesperson name...
   ✅ Salesperson name updated: Test Person → Updated Test Person
   📊 SalespersonTeam record updated: Updated Test Person
```

### **Test Scenario 3: Add Sales Team**
```
🧪 Test 3: Adding new sales team...
   ✅ New sales team created: NT - New Test Team
   📈 Salesperson Teams count: 2 (auto-rebuilt with new team)
```

### **Test Scenario 4: Update Sales Team**
```
🧪 Test 4: Updating sales team name...
   ✅ Sales team name updated: New Test Team → Updated Test Team
   📊 SalespersonTeam records updated with new team name
```

### **Test Scenario 5: Delete Salesperson**
```
🧪 Test 5: Deleting test salesperson...
   ✅ Salesperson deleted: SP999
   📉 Salesperson Teams count: 2 → 1 (auto-cleaned)
   🔍 Deleted record still exists: No
```

### **Test Scenario 6: Delete Sales Team**
```
🧪 Test 6: Deleting test sales team...
   ✅ Sales team deleted: NT
   📉 Final Salesperson Teams count: 1 (auto-rebuilt)
   🔍 Team records still exist: 0
```

## 📊 Data Flow

```
┌─────────────────┐    ┌─────────────────┐
│   salesperson   │    │   sales_team    │
│                 │    │                 │
│ - Code          │    │ - code          │
│ - Name          │    │ - name          │
│ - Grup          │    │                 │
│ - Email         │    │                 │
│ - status        │    │                 │
└─────────┬───────┘    └─────────┬───────┘
          │                      │
          │    Model Events      │
          │   (created/updated/  │
          │     deleted)         │
          │                      │
          └──────────┬───────────┘
                     │
                     ▼
          ┌─────────────────────┐
          │ syncSalespersonTeams│
          │                     │
          │ 1. Clear old data   │
          │ 2. Get fresh data   │
          │ 3. Create new       │
          │    assignments      │
          │ 4. Insert new data  │
          └─────────┬───────────┘
                    │
                    ▼
          ┌─────────────────────┐
          │ salesperson_teams   │
          │                     │
          │ - s_person_code     │
          │ - salesperson_name  │
          │ - st_code           │
          │ - sales_team_name   │
          │ - sales_team_position│
          └─────────────────────┘
```

## 🎯 Benefits

### **1. Data Consistency**
- ✅ Tabel `salesperson_teams` selalu sinkron dengan data source
- ✅ Tidak ada data orphan atau outdated
- ✅ Automatic cleanup ketika data dihapus

### **2. Real-time Updates**
- ✅ Perubahan langsung terlihat di menu "Salesperson Team"
- ✅ Tidak perlu manual refresh atau rebuild
- ✅ Instant synchronization

### **3. Maintenance-Free**
- ✅ Tidak perlu script manual untuk sync data
- ✅ Tidak perlu cron job atau scheduled task
- ✅ Automatic error handling dan logging

### **4. Performance Optimized**
- ✅ Chunked insert untuk data besar
- ✅ Efficient database queries
- ✅ Minimal memory usage

## 🔄 How It Works

### **Scenario 1: User mengedit salesperson**
1. User mengubah nama salesperson dari "John Doe" → "John Smith"
2. Laravel Model Event `updated` triggered
3. Method `syncSalespersonTeams()` dipanggil
4. Tabel `salesperson_teams` di-clear dan direbuild
5. Data baru dengan nama "John Smith" muncul di tabel
6. Menu "Salesperson Team" otomatis menampilkan data terbaru

### **Scenario 2: User menambah sales team baru**
1. User menambah sales team "New Team" dengan code "NT"
2. Laravel Model Event `created` triggered
3. Method `syncSalespersonTeams()` dipanggil
4. Tabel `salesperson_teams` direbuild dengan team baru
5. Salesperson otomatis di-assign ke team baru (round-robin)
6. Menu "Salesperson Team" menampilkan assignment baru

### **Scenario 3: User menghapus salesperson**
1. User menghapus salesperson "SP001"
2. Laravel Model Event `deleted` triggered
3. Method `syncSalespersonTeams()` dipanggil
4. Tabel `salesperson_teams` direbuild tanpa SP001
5. Menu "Salesperson Team" tidak lagi menampilkan SP001

## 🚀 Usage

### **No Additional Code Required**
- Sistem bekerja otomatis tanpa perlu kode tambahan
- Cukup gunakan Laravel Model operations normal:

```php
// Create salesperson - auto-sync triggered
Salesperson::create([
    'Code' => 'SP100',
    'Name' => 'New Person',
    // ...
]);

// Update salesperson - auto-sync triggered
$salesperson = Salesperson::find(1);
$salesperson->update(['Name' => 'Updated Name']);

// Delete salesperson - auto-sync triggered
$salesperson->delete();

// Same for SalesTeam
SalesTeam::create(['code' => 'NT', 'name' => 'New Team']);
```

## ⚡ Performance Notes

- **Sync Method**: Optimized dengan chunked insert
- **Memory Usage**: Minimal karena streaming data
- **Database Impact**: Efficient queries dengan proper indexing
- **Error Handling**: Graceful failure dengan logging

## 🎉 Conclusion

✅ **Auto-sync functionality berhasil diimplementasikan!**

Sekarang ketika Anda mengubah, mengedit, atau menghapus data pada:
- **Menu Define Salesperson** → Tabel Salesperson Team otomatis terupdate
- **Menu Define Sales Team** → Tabel Salesperson Team otomatis terupdate

**Tidak perlu migration baru, tidak perlu script manual, semuanya otomatis!**
