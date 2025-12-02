# Update MC Database Connection Fix

## Masalah
Tidak semua kolom pada menu **Update MC** terhubung ke database table MC, menyebabkan beberapa data tidak tersimpan.

## Solusi yang Diterapkan

### 1. ✅ Model Mc - Lengkap Semua Kolom Fillable
**File:** `app/Models/Mc.php`

**Perubahan:**
- Menambahkan **SEMUA 158 kolom** dari migration ke `$fillable` array
- Sebelumnya hanya ~50 kolom, sekarang lengkap semua

**Kolom yang Ditambahkan:**
```php
// MSP (Machine Special Process) 1-12
'MSP1_MCH', 'MSP1_UP', 'MSP1_SPECIAL_INST',
'MSP2_MCH', 'MSP2_UP', 'MSP2_SPECIAL_INST',
... (sampai MSP12)

// MC Special Instructions (1-4)
'MC_SPECIAL_INST1', 'MC_SPECIAL_INST2', 
'MC_SPECIAL_INST3', 'MC_SPECIAL_INST4',

// MC More Descriptions (1-5)
'MC_MORE_DESCRIPTION_1', 'MC_MORE_DESCRIPTION_2', 
'MC_MORE_DESCRIPTION_3', 'MC_MORE_DESCRIPTION_4', 
'MC_MORE_DESCRIPTION_5',
```

### 2. ✅ Controller - Mapping Field Baru
**File:** `app/Http/Controllers/UpdateMcController.php`

**Perubahan:**

#### A. Validation Rules (lines 297-307)
Menambahkan validation untuk:
```php
'specialInstructions' => 'nullable|array',
'moreDescriptions' => 'nullable|array',
'mcSpecialInst1' => 'nullable|string',
'mcSpecialInst2' => 'nullable|string',
'mcSpecialInst3' => 'nullable|string',
'mcSpecialInst4' => 'nullable|string',
'mcMoreDescription1' => 'nullable|string',
'mcMoreDescription2' => 'nullable|string',
'mcMoreDescription3' => 'nullable|string',
'mcMoreDescription4' => 'nullable|string',
'mcMoreDescription5' => 'nullable|string',
```

#### B. PD Setup Array (lines 420-430)
Menambahkan field ke pd_setup:
```php
'specialInstructions' => $validated['specialInstructions'] ?? [],
'moreDescriptions' => $validated['moreDescriptions'] ?? [],
'mcSpecialInst1' => $validated['mcSpecialInst1'] ?? null,
// ... dan seterusnya
```

#### C. Database Mapping (lines 615-639)
Menambahkan mapping loop untuk:
```php
// MC Special Instructions (1-4)
for ($i = 1; $i <= 4; $i++) {
    $key = 'MC_SPECIAL_INST' . $i;
    $incoming = $alias($pd, [
        'mcSpecialInst' . $i,
        'mc_special_inst_' . $i,
        'MC_SPECIAL_INST' . $i,
        'specialInstructions.' . ($i - 1),
        'special_instructions.' . ($i - 1)
    ]);
    $legacy[$key] = $keep($key, $incoming);
}

// MC More Descriptions (1-5)
for ($i = 1; $i <= 5; $i++) {
    $key = 'MC_MORE_DESCRIPTION_' . $i;
    $incoming = $alias($pd, [
        'mcMoreDescription' . $i,
        'mc_more_description_' . $i,
        'MC_MORE_DESCRIPTION_' . $i,
        'moreDescriptions.' . ($i - 1),
        'more_descriptions.' . ($i - 1)
    ]);
    $legacy[$key] = $keep($key, $incoming);
}
```

## Kolom yang Sekarang Terhubung ke Database

### ✅ Semua 158 Kolom MC Table
Berdasarkan screenshot database yang diberikan, berikut mapping kolom:

#### 1. Basic Information
- ✅ AC_NUM, AC_NAME, STS, COMP, P_DESIGN, MCS_Num, MODEL, FLUTE

#### 2. Packing Quantities (SO/WO)
- ✅ SO_PQ1, SO_PQ2, SO_PQ3, SO_PQ4, SO_PQ5
- ✅ WO_PQ1, WO_PQ2, WO_PQ3, WO_PQ4, WO_PQ5

#### 3. Dimensions
- ✅ INT_LENGTH, INT_WIDTH, INT_HEIGHT
- ✅ EXT_LENGTH, EXT_WIDTH, EXT_HEIGHT

#### 4. Sheet & Cut Metrics
- ✅ SHEET_LENGTH, SHEET_WIDTH, PAPER_SIZE
- ✅ CORR_OUT, SLIT_OUT, DIE_OUT, JOIN_
- ✅ S_TOOL, NEST_SLOT, CREASE

#### 5. Scoring (SL1-SL8, SW1-SW8)
- ✅ SL1, SL2, SL3, SL4, SL5, SL6, SL7, SL8, TOTAL_SL
- ✅ SW1, SW2, SW3, SW4, SW5, SW6, SW7, SW8, TOTAL_SW

#### 6. Colors & Area
- ✅ COLOR1-COLOR7
- ✅ COLOR1_AREA_PERCENT - COLOR7_AREA_PERCENT
- ✅ TOTAL_COLOR

#### 7. Printing & Diecut
- ✅ PRINTING_BLOCK_NO, DIECUT_MOULD_NO
- ✅ DC_SHT_L, DC_SHT_W, DC_MOULD_L, DC_MOULD_W

#### 8. Process Flags
- ✅ FSH, SWIRE, GLUEING, WRAPPING
- ✅ HAND_HOLE, ROTARY_DC, FB_PRINTING

#### 9. Additional Process
- ✅ SWIRE_PCS, PEEL_OFF_PERCENT
- ✅ PCS_PER_BLD, BLD_PER_PLD
- ✅ STRING_TYPE, ITEM_REMARK

#### 10. Pricing & Units
- ✅ PCS_SET, UNIT, CURRENCY, UNIT_PRICE, PART_NO

#### 11. Weight & Area Calculations
- ✅ MC_GROSS_M2_PER_PCS, MC_NET_M2_PER_PCS
- ✅ MC_GROSS_KG_PER_SET, MC_NET_KG_PER_PCS

#### 12. MSP (Machine Special Process) 1-12
- ✅ MSP1_MCH, MSP1_UP, MSP1_SPECIAL_INST
- ✅ MSP2_MCH, MSP2_UP, MSP2_SPECIAL_INST
- ... (sampai MSP12)

#### 13. Special Instructions (BARU!)
- ✅ MC_SPECIAL_INST1
- ✅ MC_SPECIAL_INST2
- ✅ MC_SPECIAL_INST3
- ✅ MC_SPECIAL_INST4

#### 14. More Descriptions (BARU!)
- ✅ MC_MORE_DESCRIPTION_1
- ✅ MC_MORE_DESCRIPTION_2
- ✅ MC_MORE_DESCRIPTION_3
- ✅ MC_MORE_DESCRIPTION_4
- ✅ MC_MORE_DESCRIPTION_5

## Cara Penggunaan

### Frontend (Vue Component)
Untuk mengirim data special instructions dan more descriptions dari Vue:

```javascript
// Example payload
const payload = {
    mc_seq: '1609144',
    customer_code: '000211-08',
    mc_model: 'BOX IKAN MARINIR 4.5 KG',
    status: 'Active',
    
    // PD Setup with special instructions
    pd_setup: {
        // ... other pd_setup fields
        
        // Method 1: Array format
        specialInstructions: [
            'Fish grade',
            'Odor resistant',
            'Additional instruction 3',
            'Additional instruction 4'
        ],
        
        // Method 2: Individual fields
        mcSpecialInst1: 'Fish grade',
        mcSpecialInst2: 'Odor resistant',
        
        // More descriptions
        moreDescriptions: [
            'Liner',
            'Flute',
            'Coating',
            'Special coating',
            'Food grade'
        ],
        
        // Or individual
        mcMoreDescription1: 'Liner',
        mcMoreDescription2: 'Flute',
    }
}
```

### Backend Processing
Controller akan otomatis mapping:
```php
// Array access
$pd['specialInstructions'][0] → MC_SPECIAL_INST1
$pd['specialInstructions'][1] → MC_SPECIAL_INST2

// Direct field access
$pd['mcSpecialInst1'] → MC_SPECIAL_INST1
$pd['mcSpecialInst2'] → MC_SPECIAL_INST2
```

## Testing

### 1. Test Create New MC
```bash
# Buat MC baru dengan semua field
POST /api/update-mc/master-cards
{
    "mc_seq": "TEST001",
    "customer_code": "000004",
    "mc_model": "Test Model",
    "status": "Active",
    "mcSpecialInst1": "Instruction 1",
    "mcMoreDescription1": "Description 1"
}
```

### 2. Test Update Existing MC
```bash
# Update MC yang sudah ada
POST /api/update-mc/master-cards
{
    "mc_seq": "1609144",
    "customer_code": "000211-08",
    "mcSpecialInst1": "Updated instruction"
}
```

### 3. Verify Database
```sql
SELECT 
    MCS_Num,
    MODEL,
    MC_SPECIAL_INST1,
    MC_SPECIAL_INST2,
    MC_SPECIAL_INST3,
    MC_SPECIAL_INST4,
    MC_MORE_DESCRIPTION_1,
    MC_MORE_DESCRIPTION_2,
    MC_MORE_DESCRIPTION_3,
    MC_MORE_DESCRIPTION_4,
    MC_MORE_DESCRIPTION_5
FROM MC
WHERE MCS_Num = '1609144';
```

## Summary

### ✅ Files Changed
1. `app/Models/Mc.php` - Added all 158 columns to fillable
2. `app/Http/Controllers/UpdateMcController.php` - Added mapping for special instructions and more descriptions

### ✅ Kolom yang Sekarang Terhubung
- **Total: 158 kolom** dari table MC sekarang terhubung
- **Baru ditambahkan:**
  - 4 MC Special Instructions
  - 5 MC More Descriptions
  - 36 MSP (Machine Special Process) fields

### ✅ Benefits
- ✅ Semua data dari form Update MC sekarang tersimpan ke database
- ✅ Data tidak hilang saat save
- ✅ Support untuk special instructions dan descriptions
- ✅ Support untuk MSP (Machine Special Process) data
- ✅ Backward compatible dengan data yang sudah ada

## Migration Required
**TIDAK ADA MIGRATION DIPERLUKAN** - Table MC sudah memiliki semua kolom yang diperlukan dari migration file `2025_09_30_000000_create_mc_table.php`.

## Notes
- Model menggunakan `updateOrInsert` dengan composite key `(MCS_Num, AC_NUM)`
- Semua field nullable, jadi tidak wajib diisi
- Empty strings otomatis di-convert ke NULL
- Field preservation: Jika field kosong saat update, nilai existing akan tetap

---
**Date:** October 21, 2025
**Status:** ✅ COMPLETED
**Version:** 1.0.0
