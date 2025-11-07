-- Comprehensive Verification for All MC Automatic Calculations
-- This script verifies M2 and KG calculations for Master Cards

-- ============================================================================
-- 1. COMPLETE OVERVIEW - All Calculated Fields
-- ============================================================================
SELECT 
    MCS_Num,
    AC_NUM,
    MODEL,
    FLUTE,
    -- Input dimensions
    SHEET_LENGTH,
    SHEET_WIDTH,
    PAPER_SIZE,
    CORR_OUT as CON_OUT,
    SLIT_OUT as CONV_OUT_1,
    DIE_OUT as CONV_OUT_2,
    JOIN_ as PCS_TO_JOINT,
    -- Paper qualities
    SO_PQ1,
    SO_PQ2,
    SO_PQ3,
    SO_PQ4,
    SO_PQ5,
    -- Calculated M2 values
    MC_GROSS_M2_PER_PCS,
    MC_NET_M2_PER_PCS,
    -- Calculated KG values
    MC_GROSS_KG_PER_SET,
    MC_NET_KG_PER_PCS,
    -- Waste analysis
    CASE 
        WHEN MC_GROSS_KG_PER_SET > 0 AND MC_NET_KG_PER_PCS > 0
        THEN ((MC_GROSS_KG_PER_SET - MC_NET_KG_PER_PCS) / MC_GROSS_KG_PER_SET) * 100
        ELSE NULL 
    END as WASTE_PERCENT
FROM MC
WHERE MC_GROSS_M2_PER_PCS IS NOT NULL 
   OR MC_NET_M2_PER_PCS IS NOT NULL
   OR MC_GROSS_KG_PER_SET IS NOT NULL
   OR MC_NET_KG_PER_PCS IS NOT NULL
ORDER BY MCS_Num DESC;

-- ============================================================================
-- 2. SUMMARY STATISTICS - All Calculations
-- ============================================================================
SELECT 
    'M2 and KG Calculations Summary' as REPORT_TYPE,
    COUNT(*) as TOTAL_RECORDS,
    -- M2 Statistics
    COUNT(MC_GROSS_M2_PER_PCS) as RECORDS_WITH_GROSS_M2,
    COUNT(MC_NET_M2_PER_PCS) as RECORDS_WITH_NET_M2,
    AVG(MC_GROSS_M2_PER_PCS) as AVG_GROSS_M2,
    AVG(MC_NET_M2_PER_PCS) as AVG_NET_M2,
    -- KG Statistics
    COUNT(MC_GROSS_KG_PER_SET) as RECORDS_WITH_GROSS_KG,
    COUNT(MC_NET_KG_PER_PCS) as RECORDS_WITH_NET_KG,
    AVG(MC_GROSS_KG_PER_SET) as AVG_GROSS_KG,
    AVG(MC_NET_KG_PER_PCS) as AVG_NET_KG,
    -- Waste Statistics
    AVG(
        CASE 
            WHEN MC_GROSS_KG_PER_SET > 0 AND MC_NET_KG_PER_PCS > 0
            THEN ((MC_GROSS_KG_PER_SET - MC_NET_KG_PER_PCS) / MC_GROSS_KG_PER_SET) * 100
            ELSE NULL 
        END
    ) as AVG_WASTE_PERCENT
FROM MC;

-- ============================================================================
-- 3. COMPLETE DATA CHECK - With Flute Ratios
-- ============================================================================
SELECT 
    MC.MCS_Num,
    MC.AC_NUM,
    MC.MODEL,
    MC.FLUTE,
    -- Paper Qualities
    MC.SO_PQ1,
    MC.SO_PQ2,
    MC.SO_PQ3,
    MC.SO_PQ4,
    MC.SO_PQ5,
    -- M2 Values
    MC.MC_GROSS_M2_PER_PCS,
    MC.MC_NET_M2_PER_PCS,
    -- KG Values
    MC.MC_GROSS_KG_PER_SET,
    MC.MC_NET_KG_PER_PCS,
    -- Flute Ratios
    F.DB as RATIO_DB,
    F.B as RATIO_B,
    F._1L as RATIO_1L,
    F.A_C_E as RATIO_ACE,
    F._2L as RATIO_2L,
    -- Waste Analysis
    CASE 
        WHEN MC.MC_GROSS_KG_PER_SET > 0 AND MC.MC_NET_KG_PER_PCS > 0
        THEN ROUND(((MC.MC_GROSS_KG_PER_SET - MC.MC_NET_KG_PER_PCS) / MC.MC_GROSS_KG_PER_SET) * 100, 2)
        ELSE NULL 
    END as WASTE_PERCENT
FROM MC
LEFT JOIN Flute_CPS F ON MC.FLUTE = F.Flute
WHERE MC.MC_GROSS_M2_PER_PCS IS NOT NULL
   OR MC.MC_NET_M2_PER_PCS IS NOT NULL
ORDER BY MC.MCS_Num DESC;

-- ============================================================================
-- 4. MISSING DATA ANALYSIS
-- ============================================================================
SELECT 
    MCS_Num,
    AC_NUM,
    MODEL,
    -- Check M2 inputs
    CASE WHEN SHEET_LENGTH IS NULL THEN 'Missing' ELSE 'OK' END as SHEET_LENGTH_STATUS,
    CASE WHEN SHEET_WIDTH IS NULL THEN 'Missing' ELSE 'OK' END as SHEET_WIDTH_STATUS,
    CASE WHEN PAPER_SIZE IS NULL THEN 'Missing' ELSE 'OK' END as PAPER_SIZE_STATUS,
    CASE WHEN CORR_OUT IS NULL THEN 'Missing' ELSE 'OK' END as CON_OUT_STATUS,
    CASE WHEN SLIT_OUT IS NULL THEN 'Missing' ELSE 'OK' END as CONV_OUT_1_STATUS,
    CASE WHEN DIE_OUT IS NULL THEN 'Missing' ELSE 'OK' END as CONV_OUT_2_STATUS,
    -- Check KG inputs
    CASE WHEN FLUTE IS NULL THEN 'Missing' ELSE 'OK' END as FLUTE_STATUS,
    CASE WHEN SO_PQ1 IS NULL THEN 'Missing' ELSE 'OK' END as SO_PQ1_STATUS,
    -- Check calculated values
    CASE WHEN MC_GROSS_M2_PER_PCS IS NULL THEN 'Missing' ELSE 'OK' END as GROSS_M2_STATUS,
    CASE WHEN MC_NET_M2_PER_PCS IS NULL THEN 'Missing' ELSE 'OK' END as NET_M2_STATUS,
    CASE WHEN MC_GROSS_KG_PER_SET IS NULL THEN 'Missing' ELSE 'OK' END as GROSS_KG_STATUS,
    CASE WHEN MC_NET_KG_PER_PCS IS NULL THEN 'Missing' ELSE 'OK' END as NET_KG_STATUS
FROM MC
WHERE SHEET_LENGTH IS NULL 
   OR SHEET_WIDTH IS NULL 
   OR PAPER_SIZE IS NULL 
   OR FLUTE IS NULL 
   OR SO_PQ1 IS NULL
   OR MC_GROSS_M2_PER_PCS IS NULL
   OR MC_NET_M2_PER_PCS IS NULL
   OR MC_GROSS_KG_PER_SET IS NULL
   OR MC_NET_KG_PER_PCS IS NULL
ORDER BY MCS_Num DESC;

-- ============================================================================
-- 5. WASTE ANALYSIS BY FLUTE TYPE
-- ============================================================================
SELECT 
    MC.FLUTE,
    COUNT(*) as COUNT,
    -- M2 Averages
    AVG(MC.MC_GROSS_M2_PER_PCS) as AVG_GROSS_M2,
    AVG(MC.MC_NET_M2_PER_PCS) as AVG_NET_M2,
    -- KG Averages
    AVG(MC.MC_GROSS_KG_PER_SET) as AVG_GROSS_KG,
    AVG(MC.MC_NET_KG_PER_PCS) as AVG_NET_KG,
    -- Waste Analysis
    AVG(
        CASE 
            WHEN MC.MC_GROSS_KG_PER_SET > 0 AND MC.MC_NET_KG_PER_PCS > 0
            THEN ((MC.MC_GROSS_KG_PER_SET - MC.MC_NET_KG_PER_PCS) / MC.MC_GROSS_KG_PER_SET) * 100
            ELSE NULL 
        END
    ) as AVG_WASTE_PERCENT,
    -- M2 Waste
    AVG(
        CASE 
            WHEN MC.MC_GROSS_M2_PER_PCS > 0 AND MC.MC_NET_M2_PER_PCS > 0
            THEN ((MC.MC_GROSS_M2_PER_PCS - MC.MC_NET_M2_PER_PCS) / MC.MC_GROSS_M2_PER_PCS) * 100
            ELSE NULL 
        END
    ) as AVG_M2_WASTE_PERCENT
FROM MC
WHERE MC.MC_GROSS_KG_PER_SET IS NOT NULL
  AND MC.MC_NET_KG_PER_PCS IS NOT NULL
  AND MC.MC_GROSS_KG_PER_SET > 0
  AND MC.MC_NET_KG_PER_PCS > 0
GROUP BY MC.FLUTE
ORDER BY MC.FLUTE;

-- ============================================================================
-- 6. VALIDATION - Check Calculation Logic
-- ============================================================================
SELECT 
    MCS_Num,
    AC_NUM,
    MODEL,
    -- M2 Values
    MC_GROSS_M2_PER_PCS,
    MC_NET_M2_PER_PCS,
    -- KG Values
    MC_GROSS_KG_PER_SET,
    MC_NET_KG_PER_PCS,
    -- Validation Checks
    CASE 
        WHEN MC_NET_M2_PER_PCS > MC_GROSS_M2_PER_PCS 
        THEN 'ERROR: Net M2 > Gross M2'
        ELSE 'OK'
    END as M2_VALIDATION,
    CASE 
        WHEN MC_NET_KG_PER_PCS > MC_GROSS_KG_PER_SET 
        THEN 'ERROR: Net KG > Gross KG'
        ELSE 'OK'
    END as KG_VALIDATION,
    CASE 
        WHEN MC_GROSS_M2_PER_PCS IS NOT NULL AND MC_GROSS_KG_PER_SET IS NULL
        THEN 'WARNING: M2 exists but Gross KG missing'
        WHEN MC_NET_M2_PER_PCS IS NOT NULL AND MC_NET_KG_PER_PCS IS NULL
        THEN 'WARNING: M2 exists but Net KG missing'
        ELSE 'OK'
    END as COMPLETENESS_CHECK
FROM MC
WHERE MC_GROSS_M2_PER_PCS IS NOT NULL 
   OR MC_NET_M2_PER_PCS IS NOT NULL
ORDER BY MCS_Num DESC;

-- ============================================================================
-- 7. RECENT UPDATES - Last 20 Records
-- ============================================================================
SELECT TOP 20
    MCS_Num,
    AC_NUM,
    MODEL,
    FLUTE,
    MC_GROSS_M2_PER_PCS,
    MC_NET_M2_PER_PCS,
    MC_GROSS_KG_PER_SET,
    MC_NET_KG_PER_PCS,
    CASE 
        WHEN MC_GROSS_KG_PER_SET > 0 AND MC_NET_KG_PER_PCS > 0
        THEN ROUND(((MC_GROSS_KG_PER_SET - MC_NET_KG_PER_PCS) / MC_GROSS_KG_PER_SET) * 100, 2)
        ELSE NULL 
    END as WASTE_PERCENT
FROM MC
WHERE MC_GROSS_M2_PER_PCS IS NOT NULL
   OR MC_NET_M2_PER_PCS IS NOT NULL
ORDER BY MCS_Num DESC;

-- ============================================================================
-- 8. DETAILED BREAKDOWN FOR SPECIFIC MCS
-- Replace 'YOUR_MCS_NUMBER' with actual MCS number
-- ============================================================================
/*
SELECT 
    MC.MCS_Num,
    '=== PAPER QUALITY (SO) ===' as SECTION_1,
    MC.SO_PQ1,
    MC.SO_PQ2,
    MC.SO_PQ3,
    MC.SO_PQ4,
    MC.SO_PQ5,
    '=== FLUTE DATA ===' as SECTION_2,
    MC.FLUTE,
    F.DB as RATIO_DB,
    F.B as RATIO_B,
    F._1L as RATIO_1L,
    F.A_C_E as RATIO_ACE,
    F._2L as RATIO_2L,
    '=== DIMENSIONS ===' as SECTION_3,
    MC.SHEET_LENGTH,
    MC.SHEET_WIDTH,
    MC.PAPER_SIZE,
    MC.CORR_OUT,
    MC.SLIT_OUT,
    MC.DIE_OUT,
    MC.JOIN_,
    '=== CALCULATED M2 ===' as SECTION_4,
    MC.MC_GROSS_M2_PER_PCS,
    MC.MC_NET_M2_PER_PCS,
    '=== CALCULATED KG ===' as SECTION_5,
    MC.MC_GROSS_KG_PER_SET,
    MC.MC_NET_KG_PER_PCS,
    '=== WASTE ANALYSIS ===' as SECTION_6,
    CASE 
        WHEN MC.MC_GROSS_KG_PER_SET > 0 AND MC.MC_NET_KG_PER_PCS > 0
        THEN ROUND(((MC.MC_GROSS_KG_PER_SET - MC.MC_NET_KG_PER_PCS) / MC.MC_GROSS_KG_PER_SET) * 100, 2)
        ELSE NULL 
    END as KG_WASTE_PERCENT,
    CASE 
        WHEN MC.MC_GROSS_M2_PER_PCS > 0 AND MC.MC_NET_M2_PER_PCS > 0
        THEN ROUND(((MC.MC_GROSS_M2_PER_PCS - MC.MC_NET_M2_PER_PCS) / MC.MC_GROSS_M2_PER_PCS) * 100, 2)
        ELSE NULL 
    END as M2_WASTE_PERCENT
FROM MC
LEFT JOIN Flute_CPS F ON MC.FLUTE = F.Flute
WHERE MC.MCS_Num = 'YOUR_MCS_NUMBER';
*/

-- ============================================================================
-- 9. COMPARISON - Gross vs Net
-- ============================================================================
SELECT 
    'Gross vs Net Comparison' as ANALYSIS_TYPE,
    -- M2 Comparison
    AVG(MC_GROSS_M2_PER_PCS) as AVG_GROSS_M2,
    AVG(MC_NET_M2_PER_PCS) as AVG_NET_M2,
    AVG(MC_GROSS_M2_PER_PCS - MC_NET_M2_PER_PCS) as AVG_M2_DIFFERENCE,
    -- KG Comparison
    AVG(MC_GROSS_KG_PER_SET) as AVG_GROSS_KG,
    AVG(MC_NET_KG_PER_PCS) as AVG_NET_KG,
    AVG(MC_GROSS_KG_PER_SET - MC_NET_KG_PER_PCS) as AVG_KG_DIFFERENCE,
    -- Percentage Differences
    AVG(
        CASE 
            WHEN MC_GROSS_M2_PER_PCS > 0 
            THEN ((MC_GROSS_M2_PER_PCS - MC_NET_M2_PER_PCS) / MC_GROSS_M2_PER_PCS) * 100
            ELSE NULL 
        END
    ) as AVG_M2_WASTE_PERCENT,
    AVG(
        CASE 
            WHEN MC_GROSS_KG_PER_SET > 0 
            THEN ((MC_GROSS_KG_PER_SET - MC_NET_KG_PER_PCS) / MC_GROSS_KG_PER_SET) * 100
            ELSE NULL 
        END
    ) as AVG_KG_WASTE_PERCENT
FROM MC
WHERE MC_GROSS_M2_PER_PCS IS NOT NULL
  AND MC_NET_M2_PER_PCS IS NOT NULL
  AND MC_GROSS_KG_PER_SET IS NOT NULL
  AND MC_NET_KG_PER_PCS IS NOT NULL;

-- ============================================================================
-- 10. OUTLIER DETECTION - Unusual Values
-- ============================================================================
SELECT 
    MCS_Num,
    AC_NUM,
    MODEL,
    MC_GROSS_M2_PER_PCS,
    MC_NET_M2_PER_PCS,
    MC_GROSS_KG_PER_SET,
    MC_NET_KG_PER_PCS,
    CASE 
        WHEN MC_NET_M2_PER_PCS > MC_GROSS_M2_PER_PCS THEN 'Net M2 > Gross M2'
        WHEN MC_NET_KG_PER_PCS > MC_GROSS_KG_PER_SET THEN 'Net KG > Gross KG'
        WHEN MC_GROSS_M2_PER_PCS = 0 THEN 'Zero Gross M2'
        WHEN MC_NET_M2_PER_PCS = 0 THEN 'Zero Net M2'
        WHEN MC_GROSS_KG_PER_SET = 0 THEN 'Zero Gross KG'
        WHEN MC_NET_KG_PER_PCS = 0 THEN 'Zero Net KG'
        ELSE 'OK'
    END as ISSUE_TYPE
FROM MC
WHERE (MC_NET_M2_PER_PCS > MC_GROSS_M2_PER_PCS
   OR MC_NET_KG_PER_PCS > MC_GROSS_KG_PER_SET
   OR MC_GROSS_M2_PER_PCS = 0
   OR MC_NET_M2_PER_PCS = 0
   OR MC_GROSS_KG_PER_SET = 0
   OR MC_NET_KG_PER_PCS = 0)
  AND (MC_GROSS_M2_PER_PCS IS NOT NULL OR MC_NET_M2_PER_PCS IS NOT NULL)
ORDER BY MCS_Num DESC;
