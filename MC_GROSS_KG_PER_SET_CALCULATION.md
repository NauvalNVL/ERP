# MC Gross KG Per Set Automatic Calculation

## Overview
Implemented automatic calculation for `MC_GROSS_KG_PER_SET` field in the Master Card (MC) table. This value is computed based on paper quality GSM values, flute ratio layers, and the previously calculated `MC_GROSS_M2_PER_PCS`.

## Calculation Formula

```
MC_GROSS_KG_PER_SET = 
  (SO_PQ1/1000 * mc_gross_m2_per_pcs * ratio_layer_1_DB) +
  (SO_PQ2/1000 * mc_gross_m2_per_pcs * ratio_layer_2_B) +
  (SO_PQ3/1000 * mc_gross_m2_per_pcs * ratio_layer_3_1L) +
  (SO_PQ4/1000 * mc_gross_m2_per_pcs * ratio_layer_4_ACE) +
  (SO_PQ5/1000 * mc_gross_m2_per_pcs * ratio_layer_5_2L)
```

### Components Breakdown:

#### 1. **SO PQ (Paper Quality GSM)**
- Extracted from paper quality name (e.g., "AGBS200" → 200 GSM)
- SO_PQ1 through SO_PQ5 represent 5 different paper layers
- Values are divided by 1000 to convert GSM to KG/M²

#### 2. **MC Gross M² Per Pcs**
- Previously calculated value from `mcGrossM2PerPcs` computed property
- Represents the gross area in square meters per piece

#### 3. **Ratio Layers (from Flute)**
- Retrieved from selected Paper Flute (e.g., "BC2")
- **DB** (ratio_layer_1): Double board ratio
- **B** (ratio_layer_2): B-flute ratio
- **1L** (ratio_layer_3): Single liner ratio
- **A/C/E** (ratio_layer_4): A, C, or E flute ratio
- **2L** (ratio_layer_5): Double liner ratio

## Example Calculation

### Input Data:
- **Paper Qualities (SO):**
  - SO PQ1: "AGBS200" → 200 GSM
  - SO PQ2: "KLBS150" → 150 GSM
  - SO PQ3: "TLBS125" → 125 GSM
  - SO PQ4: "KLBS150" → 150 GSM
  - SO PQ5: "TLBS125" → 125 GSM

- **Flute Selected:** "BC2"
  - DB = 1
  - B = 1.35
  - 1L = 1
  - A/C/E = 1.35
  - 2L = 1

- **MC Gross M² Per Pcs:** 0.001953 m²

### Calculation Steps:

```
Layer 1 (DB):   (200/1000) * 0.001953 * 1    = 0.0003906
Layer 2 (B):    (150/1000) * 0.001953 * 1.35 = 0.0003955
Layer 3 (1L):   (125/1000) * 0.001953 * 1    = 0.0002441
Layer 4 (A/C/E):(150/1000) * 0.001953 * 1.35 = 0.0003955
Layer 5 (2L):   (125/1000) * 0.001953 * 1    = 0.0002441

Total MC_GROSS_KG_PER_SET = 0.0016698 KG
```

## Changes Made

### 1. Frontend - UpdateMcModal.vue

**File:** `resources/js/Components/UpdateMcModal.vue`

#### Added Computed Property (Line 1549-1599)

```javascript
// Computed property for MC_GROSS_KG_PER_SET calculation
const mcGrossKgPerSet = computed(() => {
    // Get the selected flute data to retrieve ratio layers
    const selectedFlute = props.paperFlutes?.find(f => 
        f.code === selectedPaperFlute.value || 
        f.Flute === selectedPaperFlute.value
    );
    
    if (!selectedFlute || mcGrossM2PerPcs.value === 0) return 0;
    
    // Extract ratio layers from flute data
    const ratioDB = parseFloat(selectedFlute.DB) || 0;
    const ratioB = parseFloat(selectedFlute.B) || 0;
    const ratio1L = parseFloat(selectedFlute._1L) || 0;
    const ratioACE = parseFloat(selectedFlute.A_C_E) || 0;
    const ratio2L = parseFloat(selectedFlute._2L) || 0;
    
    // Get SO values from component forms or props
    const idx = selectedComponentIndex.value ?? 0;
    const soArr = componentForms.value[idx]?.soValues ?? props.soValues ?? [];
    
    // Helper function to extract GSM from paper quality string
    const extractGSM = (paperQuality) => {
        if (!paperQuality) return 0;
        const str = String(paperQuality);
        const match = str.match(/\d+/);
        return match ? parseFloat(match[0]) : 0;
    };
    
    // Calculate each layer contribution
    const gsm1 = extractGSM(soArr[0]);
    const layer1 = (gsm1 / 1000) * mcGrossM2PerPcs.value * ratioDB;
    
    const gsm2 = extractGSM(soArr[1]);
    const layer2 = (gsm2 / 1000) * mcGrossM2PerPcs.value * ratioB;
    
    const gsm3 = extractGSM(soArr[2]);
    const layer3 = (gsm3 / 1000) * mcGrossM2PerPcs.value * ratio1L;
    
    const gsm4 = extractGSM(soArr[3]);
    const layer4 = (gsm4 / 1000) * mcGrossM2PerPcs.value * ratioACE;
    
    const gsm5 = extractGSM(soArr[4]);
    const layer5 = (gsm5 / 1000) * mcGrossM2PerPcs.value * ratio2L;
    
    // Sum all layers
    return layer1 + layer2 + layer3 + layer4 + layer5;
});
```

**Key Features:**
- ✅ Extracts GSM from paper quality names using regex
- ✅ Retrieves flute ratio layers from selected flute
- ✅ Calculates weight contribution for each layer
- ✅ Reactive - updates when SO values, flute, or M² changes
- ✅ Returns 0 if required data is missing

#### Updated buildPdSetupPayload (Line 2280-2283)

```javascript
// Calculated M2 and KG values
mcGrossM2PerPcs: mcGrossM2PerPcs.value,
mcNetM2PerPcs: mcNetM2PerPcs.value,
mcGrossKgPerSet: mcGrossKgPerSet.value,
```

### 2. Backend - UpdateMcController.php

**File:** `app/Http/Controllers/UpdateMcController.php`

#### Updated Validation (Line 278)

```php
'mcGrossKgPerSet' => 'nullable|numeric',
```

#### Existing Mapping (Line 590-593)

```php
// Always update calculated values (don't preserve old values)
$legacy['MC_GROSS_M2_PER_PCS'] = $num($alias($pd, ['mcGrossM2PerPcs','mc_gross_m2_per_pcs']));
$legacy['MC_NET_M2_PER_PCS'] = $num($alias($pd, ['mcNetM2PerPcs','mc_net_m2_per_pcs']));
$legacy['MC_GROSS_KG_PER_SET'] = $num($alias($pd, ['mcGrossKgPerSet','mc_gross_kg_per_set']));
```

### 3. Database Structure

**Table:** `MC`
**Migration:** `database/migrations/2025_09_30_000000_create_mc_table.php`

**Field:**
```php
$table->decimal('MC_GROSS_KG_PER_SET', 18, 4)->nullable(); // Line 110
```

**Related Tables:**
- **Flute_CPS**: Contains ratio layers (DB, B, _1L, A_C_E, _2L)
- **paper_qualities**: Contains paper quality codes and GSM values

## Data Flow

### Creating/Updating Master Card:

1. **User Selects Paper Qualities (SO):**
   - Clicks search button next to SO fields
   - Selects paper quality from modal (e.g., "AGBS200")
   - System stores the paper quality code

2. **User Selects Paper Flute:**
   - Clicks search button next to Flute field
   - Selects flute (e.g., "BC2")
   - System retrieves ratio layers from Flute_CPS table

3. **User Enters Sheet Dimensions:**
   - Sheet Length, Width, Paper Size, etc.
   - System calculates `mcGrossM2PerPcs` automatically

4. **Automatic KG Calculation:**
   - `mcGrossKgPerSet` computed property:
     - Extracts GSM from each SO paper quality
     - Gets ratio layers from selected flute
     - Multiplies: (GSM/1000) × M² × Ratio for each layer
     - Sums all 5 layers

5. **Save to Backend:**
   - User clicks "Save MasterCard"
   - Payload includes `mcGrossKgPerSet` value
   - Backend validates and saves to MC table

6. **Database Storage:**
   - Value stored in `MC_GROSS_KG_PER_SET` column
   - Precision: 18 digits total, 4 decimal places

## Field Mapping Reference

| Frontend Variable | Database Column | Source | Description |
|-------------------|-----------------|--------|-------------|
| `soValues[0-4]` | `SO_PQ1-5` | Paper Quality Modal | Paper quality codes |
| `selectedPaperFlute` | `FLUTE` | Flute Modal | Flute code (e.g., BC2) |
| `mcGrossM2PerPcs` | `MC_GROSS_M2_PER_PCS` | Calculated | Gross M² per piece |
| `mcGrossKgPerSet` | `MC_GROSS_KG_PER_SET` | Calculated | Gross KG per set |

## Flute Ratio Layers

Common flute types and their ratio layers:

| Flute Code | DB | B | 1L | A/C/E | 2L | Description |
|------------|----|----|-------|-------|-----|-------------|
| BC2 | 1 | 1.35 | 1 | 1.35 | 1 | B-flute + C-flute |
| BE2 | 1 | 1.35 | 1 | 1.35 | 1 | B-flute + E-flute |
| EB2 | 1 | 1.35 | 1 | 1.35 | 1 | E-flute + B-flute |
| Single | 1 | 0 | 1 | 0 | 1 | Single wall |

*Note: Actual values are retrieved from Flute_CPS table*

## GSM Extraction Logic

The system extracts GSM (grams per square meter) from paper quality names:

| Paper Quality | Extracted GSM | Logic |
|---------------|---------------|-------|
| AGBS200 | 200 | Finds first number sequence |
| KLBS150 | 150 | Finds first number sequence |
| TLBS125 | 125 | Finds first number sequence |
| TEST300GSM | 300 | Finds first number sequence |
| PAPER250 | 250 | Finds first number sequence |

**Regex Pattern:** `/\d+/` - Matches first sequence of digits

## Testing Checklist

- [x] ✅ Open Update MC page
- [x] ✅ Select customer and MCS
- [x] ✅ Click "Setup PD" button
- [x] ✅ Select Paper Flute (e.g., BC2)
- [x] ✅ Select 5 Paper Qualities for SO
- [x] ✅ Enter sheet dimensions
- [x] ✅ Verify `mcGrossM2PerPcs` calculates
- [x] ✅ Verify `mcGrossKgPerSet` calculates automatically
- [x] ✅ Change SO paper quality and verify recalculation
- [x] ✅ Change flute and verify recalculation
- [x] ✅ Click "Save MasterCard"
- [x] ✅ Verify data saves to database
- [x] ✅ Query MC table to confirm value:
  ```sql
  SELECT 
      MCS_Num,
      FLUTE,
      SO_PQ1, SO_PQ2, SO_PQ3, SO_PQ4, SO_PQ5,
      MC_GROSS_M2_PER_PCS,
      MC_GROSS_KG_PER_SET
  FROM MC
  WHERE MCS_Num = 'YOUR_MCS_NUMBER';
  ```

## Error Handling

### Missing Data Protection:
1. **No Flute Selected:** Returns 0
2. **No SO Values:** Returns 0
3. **Invalid Paper Quality:** GSM extraction returns 0
4. **Zero M² Value:** Returns 0
5. **Missing Ratio Layers:** Uses 0 as default

### Edge Cases:
1. **Paper Quality without numbers:** Returns 0 KG
2. **Partial SO values:** Calculates only filled layers
3. **Empty SO array:** Returns 0 KG
4. **Invalid flute code:** Returns 0 KG

## Dependencies

This calculation depends on:
1. ✅ `mcGrossM2PerPcs` - Must be calculated first
2. ✅ `selectedPaperFlute` - Must be selected
3. ✅ `soValues` - Must have paper quality codes
4. ✅ `props.paperFlutes` - Must contain flute data with ratios

## Benefits

1. **Automation:** No manual weight calculation needed
2. **Accuracy:** Eliminates human calculation errors
3. **Real-time:** Updates instantly when inputs change
4. **Material Planning:** Accurate weight for procurement
5. **Cost Estimation:** Precise material cost calculations
6. **Consistency:** Same formula applied every time

## Related Calculations

This KG calculation has a companion calculation:
- **MC_NET_KG_PER_PCS**: Same formula but uses Net M² instead of Gross M²
- See: `MC_NET_KG_PER_PCS_CALCULATION.md` for details

## Related Files

- **Frontend Component:** `resources/js/Components/UpdateMcModal.vue`
- **Backend Controller:** `app/Http/Controllers/UpdateMcController.php`
- **Database Migration:** `database/migrations/2025_09_30_000000_create_mc_table.php`
- **Flute Table Migration:** `database/migrations/2023_08_01_000000_create_flute_cps_table.php`
- **Paper Quality Migration:** `database/migrations/2025_04_24_061927_create_paper_qualities_table.php`
- **Parent Page:** `resources/js/Pages/sales-management/system-requirement/master-card/update-mc.vue`
- **Related Documentation:** 
  - `MC_M2_AUTO_CALCULATION.md`
  - `MC_NET_KG_PER_PCS_CALCULATION.md`
  - `verify_mc_all_calculations.sql`

## Summary

The automatic KG calculation feature:
1. ✅ Computes Gross KG Per Set in real-time
2. ✅ Extracts GSM from paper quality names
3. ✅ Retrieves ratio layers from selected flute
4. ✅ Uses previously calculated M² value
5. ✅ Calculates weight for all 5 paper layers
6. ✅ Includes value in save payload
7. ✅ Backend validates and maps to database
8. ✅ Stores in MC table with 4 decimal precision
9. ✅ Handles edge cases and missing data
10. ✅ Updates reactively with input changes

This completes the material weight calculation system for Master Cards, providing accurate weight estimates for production planning and cost analysis.
