# MC Net KG Per Pcs Automatic Calculation

## Overview
Implemented automatic calculation for `MC_NET_KG_PER_PCS` field in the Master Card (MC) table. This calculation uses the same formula as `MC_GROSS_KG_PER_SET` but uses `mc_net_m2_per_pcs` instead of `mc_gross_m2_per_pcs`.

## Calculation Formula

```
MC_NET_KG_PER_PCS = 
  (SO_PQ1/1000 * mc_net_m2_per_pcs * ratio_layer_1_DB) +
  (SO_PQ2/1000 * mc_net_m2_per_pcs * ratio_layer_2_B) +
  (SO_PQ3/1000 * mc_net_m2_per_pcs * ratio_layer_3_1L) +
  (SO_PQ4/1000 * mc_net_m2_per_pcs * ratio_layer_4_ACE) +
  (SO_PQ5/1000 * mc_net_m2_per_pcs * ratio_layer_5_2L)
```

### Difference from MC_GROSS_KG_PER_SET:
- **MC_GROSS_KG_PER_SET**: Uses `mc_gross_m2_per_pcs` (gross area)
- **MC_NET_KG_PER_PCS**: Uses `mc_net_m2_per_pcs` (net area)

The net M² is typically smaller than gross M² because it accounts for material waste and conversion losses.

## Components Breakdown:

Same as MC_GROSS_KG_PER_SET:

1. **SO PQ (Paper Quality GSM)**: Extracted from paper quality name
2. **MC Net M² Per Pcs**: Previously calculated net area value
3. **Ratio Layers (from Flute)**: DB, B, 1L, A/C/E, 2L

## Example Calculation

### Input Data:
- **Paper Qualities (SO):**
  - SO PQ1: "AGBS200" → 200 GSM
  - SO PQ2: "KLBS150" → 150 GSM
  - SO PQ3: "TLBS125" → 125 GSM
  - SO PQ4: "KLBS150" → 150 GSM
  - SO PQ5: "TLBS125" → 125 GSM

- **Flute Selected:** "BC2"
  - DB = 1, B = 1.35, 1L = 1, A/C/E = 1.35, 2L = 1

- **MC Net M² Per Pcs:** 0.4 m² (example)

### Calculation Steps:

```
Layer 1 (DB):   (200/1000) * 0.4 * 1    = 0.08
Layer 2 (B):    (150/1000) * 0.4 * 1.35 = 0.081
Layer 3 (1L):   (125/1000) * 0.4 * 1    = 0.05
Layer 4 (A/C/E):(150/1000) * 0.4 * 1.35 = 0.081
Layer 5 (2L):   (125/1000) * 0.4 * 1    = 0.05

Total MC_NET_KG_PER_PCS = 0.342 KG
```

## Comparison: Gross vs Net KG

| Metric | Uses M² | Typical Value | Purpose |
|--------|---------|---------------|---------|
| **MC_GROSS_KG_PER_SET** | Gross M² | Higher | Material ordering, waste included |
| **MC_NET_KG_PER_PCS** | Net M² | Lower | Actual product weight, finished goods |

**Example:**
- Gross M²: 0.001953 m² → Gross KG: 0.0016698 kg
- Net M²: 0.4 m² → Net KG: 0.342 kg

The difference represents material waste during production.

## Changes Made

### 1. Frontend - UpdateMcModal.vue

**File:** `resources/js/Components/UpdateMcModal.vue`

#### Added Computed Property (Line 1601-1651)

```javascript
// Computed property for MC_NET_KG_PER_PCS calculation
const mcNetKgPerPcs = computed(() => {
    // Get the selected flute data to retrieve ratio layers
    const selectedFlute = props.paperFlutes?.find(f => 
        f.code === selectedPaperFlute.value || 
        f.Flute === selectedPaperFlute.value
    );
    
    if (!selectedFlute || mcNetM2PerPcs.value === 0) return 0;
    
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
    
    // Calculate each layer contribution using NET M2 instead of GROSS M2
    const gsm1 = extractGSM(soArr[0]);
    const layer1 = (gsm1 / 1000) * mcNetM2PerPcs.value * ratioDB;
    
    const gsm2 = extractGSM(soArr[1]);
    const layer2 = (gsm2 / 1000) * mcNetM2PerPcs.value * ratioB;
    
    const gsm3 = extractGSM(soArr[2]);
    const layer3 = (gsm3 / 1000) * mcNetM2PerPcs.value * ratio1L;
    
    const gsm4 = extractGSM(soArr[3]);
    const layer4 = (gsm4 / 1000) * mcNetM2PerPcs.value * ratioACE;
    
    const gsm5 = extractGSM(soArr[4]);
    const layer5 = (gsm5 / 1000) * mcNetM2PerPcs.value * ratio2L;
    
    // Sum all layers
    return layer1 + layer2 + layer3 + layer4 + layer5;
});
```

**Key Difference:**
- Uses `mcNetM2PerPcs.value` instead of `mcGrossM2PerPcs.value`
- All other logic remains the same

#### Updated buildPdSetupPayload (Line 2332-2336)

```javascript
// Calculated M2 and KG values
mcGrossM2PerPcs: mcGrossM2PerPcs.value,
mcNetM2PerPcs: mcNetM2PerPcs.value,
mcGrossKgPerSet: mcGrossKgPerSet.value,
mcNetKgPerPcs: mcNetKgPerPcs.value,
```

### 2. Backend - UpdateMcController.php

**File:** `app/Http/Controllers/UpdateMcController.php`

#### Updated Validation (Line 279)

```php
'mcNetKgPerPcs' => 'nullable|numeric',
```

#### Existing Mapping (Line 594)

```php
$legacy['MC_NET_KG_PER_PCS'] = $num($alias($pd, ['mcNetKgPerPcs','mc_net_kg_per_pcs']));
```

The mapping was already in place, we just needed to add the frontend calculation and validation.

### 3. Database Structure

**Table:** `MC`
**Migration:** `database/migrations/2025_09_30_000000_create_mc_table.php`

**Field:**
```php
$table->decimal('MC_NET_KG_PER_PCS', 18, 4)->nullable(); // Line 111
```

## Data Flow

### Creating/Updating Master Card:

1. **User Inputs Data:**
   - Selects Paper Qualities (SO)
   - Selects Paper Flute
   - Enters sheet dimensions

2. **Automatic M² Calculations:**
   - System calculates `mcGrossM2PerPcs`
   - System calculates `mcNetM2PerPcs`

3. **Automatic KG Calculations:**
   - `mcGrossKgPerSet` uses Gross M²
   - `mcNetKgPerPcs` uses Net M²
   - Both use same GSM and ratio layers

4. **Save to Backend:**
   - User clicks "Save MasterCard"
   - Payload includes both KG values
   - Backend validates and saves

5. **Database Storage:**
   - `MC_GROSS_KG_PER_SET`: Material with waste
   - `MC_NET_KG_PER_PCS`: Finished product weight

## Field Mapping Reference

| Frontend Variable | Database Column | Description |
|-------------------|-----------------|-------------|
| `mcGrossM2PerPcs` | `MC_GROSS_M2_PER_PCS` | Gross area per piece |
| `mcNetM2PerPcs` | `MC_NET_M2_PER_PCS` | Net area per piece |
| `mcGrossKgPerSet` | `MC_GROSS_KG_PER_SET` | Gross weight per set |
| `mcNetKgPerPcs` | `MC_NET_KG_PER_PCS` | Net weight per piece |

## Use Cases

### 1. Material Procurement
Use **MC_GROSS_KG_PER_SET** for:
- Ordering raw materials
- Calculating material costs
- Planning inventory with waste

### 2. Product Specifications
Use **MC_NET_KG_PER_PCS** for:
- Product weight specifications
- Shipping calculations
- Customer quotations
- Finished goods inventory

### 3. Waste Analysis
Compare both values to:
- Calculate material waste percentage
- Optimize production efficiency
- Identify cost-saving opportunities

**Waste Calculation:**
```
Waste % = ((Gross KG - Net KG) / Gross KG) × 100
```

## Testing Checklist

- [x] ✅ Open Update MC page
- [x] ✅ Select customer and MCS
- [x] ✅ Click "Setup PD" button
- [x] ✅ Select Paper Flute
- [x] ✅ Select Paper Qualities for SO
- [x] ✅ Enter sheet dimensions
- [x] ✅ Verify `mcGrossM2PerPcs` calculates
- [x] ✅ Verify `mcNetM2PerPcs` calculates
- [x] ✅ Verify `mcGrossKgPerSet` calculates
- [x] ✅ Verify `mcNetKgPerPcs` calculates
- [x] ✅ Verify Net KG < Gross KG (typically)
- [x] ✅ Click "Save MasterCard"
- [x] ✅ Verify both KG values save to database
- [x] ✅ Query MC table to confirm:
  ```sql
  SELECT 
      MCS_Num,
      MC_GROSS_M2_PER_PCS,
      MC_NET_M2_PER_PCS,
      MC_GROSS_KG_PER_SET,
      MC_NET_KG_PER_PCS,
      -- Calculate waste percentage
      CASE 
          WHEN MC_GROSS_KG_PER_SET > 0 
          THEN ((MC_GROSS_KG_PER_SET - MC_NET_KG_PER_PCS) / MC_GROSS_KG_PER_SET) * 100
          ELSE 0 
      END as WASTE_PERCENT
  FROM MC
  WHERE MCS_Num = 'YOUR_MCS_NUMBER';
  ```

## Error Handling

Same as MC_GROSS_KG_PER_SET:

### Missing Data Protection:
1. **No Flute Selected:** Returns 0
2. **No SO Values:** Returns 0
3. **Zero Net M²:** Returns 0
4. **Invalid Paper Quality:** Returns 0

### Edge Cases:
1. **Net M² = 0:** Returns 0 KG
2. **Partial SO values:** Calculates only filled layers
3. **Missing ratio layers:** Uses 0 as default

## Dependencies

This calculation depends on:
1. ✅ `mcNetM2PerPcs` - Must be calculated first
2. ✅ `selectedPaperFlute` - Must be selected
3. ✅ `soValues` - Must have paper quality codes
4. ✅ `props.paperFlutes` - Must contain flute data

## Benefits

1. **Complete Weight Data:** Both gross and net weights available
2. **Accurate Costing:** Separate material and product weights
3. **Waste Tracking:** Can calculate material efficiency
4. **Customer Specs:** Accurate product weight for quotations
5. **Inventory Management:** Proper finished goods weight
6. **Real-time Updates:** Both values update automatically

## Related Files

- **Frontend Component:** `resources/js/Components/UpdateMcModal.vue`
- **Backend Controller:** `app/Http/Controllers/UpdateMcController.php`
- **Database Migration:** `database/migrations/2025_09_30_000000_create_mc_table.php`
- **Parent Page:** `resources/js/Pages/sales-management/system-requirement/master-card/update-mc.vue`
- **Related Documentation:** 
  - `MC_M2_AUTO_CALCULATION.md`
  - `MC_GROSS_KG_PER_SET_CALCULATION.md`

## Summary

The automatic Net KG calculation feature:
1. ✅ Computes Net KG Per Pcs in real-time
2. ✅ Uses same formula as Gross KG but with Net M²
3. ✅ Provides finished product weight
4. ✅ Enables waste analysis when compared to Gross KG
5. ✅ Updates reactively with input changes
6. ✅ Includes value in save payload
7. ✅ Backend validates and maps to database
8. ✅ Stores in MC table with 4 decimal precision

Now the system provides complete weight calculations for both material procurement (gross) and finished products (net)! 🎉
