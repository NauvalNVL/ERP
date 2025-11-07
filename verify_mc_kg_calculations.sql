-- Verify MC Gross KG Per Set and Net KG Per Pcs Calculations
-- This script helps verify that the automatic KG calculations are working correctly
-- Includes both GROSS and NET KG calculations

-- 1. Check all MC records with KG values
SELECT 
    MCS_Num,
    AC_NUM,
    MODEL,
    FLUTE,
    SO_PQ1,
    SO_PQ2,
    SO_PQ3,
    SO_PQ4,
    SO_PQ5,
    MC_GROSS_M2_PER_PCS,
    MC_GROSS_KG_PER_SET,
    MC_NET_KG_PER_PCS
FROM MC
WHERE MC_GROSS_KG_PER_SET IS NOT NULL
ORDER BY MCS_Num DESC;

-- 2. Check MC records with flute and paper quality data
SELECT 
    MC.MCS_Num,
    MC.AC_NUM,
    MC.MODEL,
    MC.FLUTE,
    MC.SO_PQ1,
    MC.SO_PQ2,
    MC.SO_PQ3,
    MC.SO_PQ4,
    MC.SO_PQ5,
    MC.MC_GROSS_M2_PER_PCS,
    MC.MC_GROSS_KG_PER_SET,
    -- Get flute ratio layers
    F.DB as RATIO_DB,
    F.B as RATIO_B,
    F._1L as RATIO_1L,
    F.A_C_E as RATIO_ACE,
    F._2L as RATIO_2L
FROM MC
LEFT JOIN Flute_CPS F ON MC.FLUTE = F.Flute
WHERE MC.MC_GROSS_KG_PER_SET IS NOT NULL
ORDER BY MC.MCS_Num DESC;

-- 3. Manual calculation verification
-- This query attempts to recalculate MC_GROSS_KG_PER_SET to verify stored values
SELECT 
    MC.MCS_Num,
    MC.FLUTE,
    MC.MC_GROSS_M2_PER_PCS,
    MC.MC_GROSS_KG_PER_SET as STORED_KG,
    -- Extract GSM from SO_PQ (assuming format like "AGBS200" -> 200)
    -- Note: This is a simplified extraction, actual frontend uses regex
    CASE 
        WHEN MC.SO_PQ1 IS NOT NULL 
        THEN TRY_CAST(SUBSTRING(MC.SO_PQ1, PATINDEX('%[0-9]%', MC.SO_PQ1), 
             PATINDEX('%[^0-9]%', SUBSTRING(MC.SO_PQ1, PATINDEX('%[0-9]%', MC.SO_PQ1), LEN(MC.SO_PQ1)) + 'X') - 1) AS FLOAT)
        ELSE 0 
    END as GSM1,
    -- Calculate estimated KG (simplified - SQL Server doesn't have easy regex)
    -- This is just for verification purposes
    '-- Manual calculation complex in SQL --' as NOTE
FROM MC
WHERE MC.MC_GROSS_KG_PER_SET IS NOT NULL
ORDER BY MC.MCS_Num DESC;

-- 4. Summary statistics
SELECT 
    COUNT(*) as TOTAL_MC_RECORDS,
    COUNT(MC_GROSS_KG_PER_SET) as RECORDS_WITH_GROSS_KG,
    COUNT(MC_NET_KG_PER_PCS) as RECORDS_WITH_NET_KG,
    AVG(MC_GROSS_KG_PER_SET) as AVG_GROSS_KG,
    AVG(MC_NET_KG_PER_PCS) as AVG_NET_KG,
    MIN(MC_GROSS_KG_PER_SET) as MIN_GROSS_KG,
    MAX(MC_GROSS_KG_PER_SET) as MAX_GROSS_KG,
    MIN(MC_NET_KG_PER_PCS) as MIN_NET_KG,
    MAX(MC_NET_KG_PER_PCS) as MAX_NET_KG
FROM MC;

-- 5. Check for missing calculation inputs
SELECT 
    MCS_Num,
    AC_NUM,
    MODEL,
    CASE WHEN FLUTE IS NULL THEN 'Missing' ELSE 'OK' END as FLUTE_STATUS,
    CASE WHEN SO_PQ1 IS NULL THEN 'Missing' ELSE 'OK' END as SO_PQ1_STATUS,
    CASE WHEN SO_PQ2 IS NULL THEN 'Missing' ELSE 'OK' END as SO_PQ2_STATUS,
    CASE WHEN SO_PQ3 IS NULL THEN 'Missing' ELSE 'OK' END as SO_PQ3_STATUS,
    CASE WHEN SO_PQ4 IS NULL THEN 'Missing' ELSE 'OK' END as SO_PQ4_STATUS,
    CASE WHEN SO_PQ5 IS NULL THEN 'Missing' ELSE 'OK' END as SO_PQ5_STATUS,
    CASE WHEN MC_GROSS_M2_PER_PCS IS NULL THEN 'Missing' ELSE 'OK' END as M2_STATUS
FROM MC
WHERE FLUTE IS NULL 
   OR SO_PQ1 IS NULL 
   OR MC_GROSS_M2_PER_PCS IS NULL
ORDER BY MCS_Num DESC;

-- 6. Recent MC updates with KG values
SELECT TOP 20
    MCS_Num,
    AC_NUM,
    MODEL,
    FLUTE,
    SO_PQ1,
    SO_PQ2,
    SO_PQ3,
    SO_PQ4,
    SO_PQ5,
    MC_GROSS_M2_PER_PCS,
    MC_GROSS_KG_PER_SET
FROM MC
WHERE MC_GROSS_KG_PER_SET IS NOT NULL
ORDER BY MCS_Num DESC;

-- 7. Check flute data availability
SELECT 
    Flute,
    DB,
    B,
    _1L as '1L',
    A_C_E as 'A/C/E',
    _2L as '2L',
    Descr as Description
FROM Flute_CPS
ORDER BY Flute;

-- 8. Check paper quality data
SELECT TOP 50
    paper_quality,
    paper_name,
    weight_kg_m,
    flute,
    status
FROM paper_qualities
WHERE status = 'Act'
ORDER BY paper_quality;

-- 9. MC records with complete calculation data
SELECT 
    MC.MCS_Num,
    MC.AC_NUM,
    MC.MODEL,
    MC.FLUTE,
    MC.SO_PQ1,
    MC.SO_PQ2,
    MC.SO_PQ3,
    MC.SO_PQ4,
    MC.SO_PQ5,
    MC.MC_GROSS_M2_PER_PCS,
    MC.MC_GROSS_KG_PER_SET,
    F.DB,
    F.B,
    F._1L,
    F.A_C_E,
    F._2L
FROM MC
INNER JOIN Flute_CPS F ON MC.FLUTE = F.Flute
WHERE MC.MC_GROSS_M2_PER_PCS IS NOT NULL
  AND MC.MC_GROSS_KG_PER_SET IS NOT NULL
  AND MC.SO_PQ1 IS NOT NULL
ORDER BY MC.MCS_Num DESC;

-- 10. Example detailed breakdown for a specific MCS
-- Replace 'YOUR_MCS_NUMBER' with actual MCS number
/*
SELECT 
    MC.MCS_Num,
    '--- Paper Quality (SO) ---' as SECTION,
    MC.SO_PQ1,
    MC.SO_PQ2,
    MC.SO_PQ3,
    MC.SO_PQ4,
    MC.SO_PQ5,
    '--- Flute Data ---' as FLUTE_SECTION,
    MC.FLUTE,
    F.DB as RATIO_DB,
    F.B as RATIO_B,
    F._1L as RATIO_1L,
    F.A_C_E as RATIO_ACE,
    F._2L as RATIO_2L,
    '--- Calculated Values ---' as CALC_SECTION,
    MC.MC_GROSS_M2_PER_PCS,
    MC.MC_GROSS_KG_PER_SET,
    '--- Formula ---' as FORMULA,
    'Layer1: (GSM1/1000) * M2 * DB' as LAYER1_FORMULA,
    'Layer2: (GSM2/1000) * M2 * B' as LAYER2_FORMULA,
    'Layer3: (GSM3/1000) * M2 * 1L' as LAYER3_FORMULA,
    'Layer4: (GSM4/1000) * M2 * ACE' as LAYER4_FORMULA,
    'Layer5: (GSM5/1000) * M2 * 2L' as LAYER5_FORMULA
FROM MC
LEFT JOIN Flute_CPS F ON MC.FLUTE = F.Flute
WHERE MC.MCS_Num = 'YOUR_MCS_NUMBER';
*/

-- 11. Check for zero or null KG values where they should exist
SELECT 
    MCS_Num,
    AC_NUM,
    MODEL,
    FLUTE,
    MC_GROSS_M2_PER_PCS,
    MC_GROSS_KG_PER_SET,
    CASE 
        WHEN MC_GROSS_M2_PER_PCS > 0 AND MC_GROSS_KG_PER_SET IS NULL THEN 'Missing KG'
        WHEN MC_GROSS_M2_PER_PCS > 0 AND MC_GROSS_KG_PER_SET = 0 THEN 'Zero KG'
        ELSE 'OK'
    END as STATUS
FROM MC
WHERE MC_GROSS_M2_PER_PCS IS NOT NULL
  AND MC_GROSS_M2_PER_PCS > 0
  AND (MC_GROSS_KG_PER_SET IS NULL OR MC_GROSS_KG_PER_SET = 0)
ORDER BY MCS_Num DESC;

-- 12. Compare KG values across similar products
SELECT 
    MC.FLUTE,
    COUNT(*) as COUNT,
    AVG(MC.MC_GROSS_KG_PER_SET) as AVG_KG,
    MIN(MC.MC_GROSS_KG_PER_SET) as MIN_KG,
    MAX(MC.MC_GROSS_KG_PER_SET) as MAX_KG
FROM MC
WHERE MC.MC_GROSS_KG_PER_SET IS NOT NULL
  AND MC.MC_GROSS_KG_PER_SET > 0
GROUP BY MC.FLUTE
ORDER BY MC.FLUTE;
