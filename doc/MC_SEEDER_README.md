# MC Table Seeder Documentation

## Overview
Seeder ini dibuat untuk menyimpan data master card ke dalam tabel `MC` (bukan `master_cards`) dengan mengikuti struktur data yang digunakan di menu Update MC.

## Struktur Data
Seeder ini mengisi tabel `MC` dengan data yang mencakup:

### String Fields:
- `AC_NUM` - Customer Account Number
- `AC_NAME` - Customer Account Name  
- `STS` - Status (Active/Obsolete)
- `COMP` - Component (Main, Fit1, Fit2, etc.)
- `P_DESIGN` - Product Design
- `MCS_Num` - Master Card Sequence Number
- `MODEL` - Model Name
- `PART_NO` - Part Number
- `FLUTE` - Paper Flute Type
- `S_TOOL` - Scoring Tool
- `COAT` - Chemical Coat
- `TAPE` - Reinforcement Tape
- `COLOR1-COLOR7` - Print Colors
- `PRINTING_BLOCK_NO` - Printing Block Number
- `DIECUT_MOULD_NO` - Diecut Mould Number
- `FSH` - Finishing (Yes/No)
- `SWIRE` - Stitch Wire (Yes/No)
- `GLUEING` - Glueing (Yes/No)
- `WRAPPING` - Wrapping (Yes/No)
- `HAND_HOLE` - Hand Hole (Yes/No)
- `ROTARY_DC` - Rotary Diecut (Yes/No)
- `FB_PRINTING` - Full Block Printing (Yes/No)
- `STRING_TYPE` - String Type
- `ITEM_REMARK` - Item Remarks
- `UNIT` - Unit of Measurement
- `CURRENCY` - Currency
- `SO_PQ1-SO_PQ5` - SO Paper Quality
- `WO_PQ1-WO_PQ5` - WO Paper Quality
- `MSP1_MCH-MSP12_MCH` - MSP Machine Codes
- `MSP1_UP-MSP12_UP` - MSP Unit Prices
- `MSP1_SPECIAL_INST-MSP12_SPECIAL_INST` - MSP Special Instructions
- `MC_SPECIAL_INST1-MC_SPECIAL_INST4` - MC Special Instructions
- `MC_MORE_DESCRIPTION_1-MC_MORE_DESCRIPTION_5` - MC Additional Descriptions

### Numeric Fields:
- `INT_LENGTH`, `INT_WIDTH`, `INT_HEIGHT` - Internal Dimensions
- `EXT_LENGTH`, `EXT_WIDTH`, `EXT_HEIGHT` - External Dimensions
- `SHEET_LENGTH`, `SHEET_WIDTH` - Sheet Dimensions
- `PAPER_SIZE` - Paper Size
- `CORR_OUT`, `SLIT_OUT`, `DIE_OUT` - Output Values
- `JOIN_` - Join Value
- `NEST_SLOT` - Nest Slot Value
- `CREASE` - Crease Value
- `SL1-SL8` - Score Length Values
- `TOTAL_SL` - Total Score Length
- `SW1-SW8` - Score Width Values
- `TOTAL_SW` - Total Score Width
- `COLOR1_AREA_PERCENT-COLOR7_AREA_PERCENT` - Color Area Percentages
- `DC_SHT_L`, `DC_SHT_W` - Diecut Sheet Dimensions
- `DC_MOULD_L`, `DC_MOULD_W` - Diecut Mould Dimensions
- `SWIRE_PCS` - Stitch Wire Pieces
- `PEEL_OFF_PERCENT` - Peel Off Percentage
- `PCS_PER_BLD` - Pieces Per Bundle
- `BLD_PER_PLD` - Bundles Per Pallet
- `PCS_SET` - Pieces Per Set
- `UNIT_PRICE` - Unit Price
- `MC_GROSS_M2_PER_PCS` - MC Gross M2 Per Piece
- `MC_NET_M2_PER_PCS` - MC Net M2 Per Piece
- `MC_GROSS_KG_PER_SET` - MC Gross KG Per Set
- `MC_NET_KG_PER_PCS` - MC Net KG Per Piece
- `TOTAL_COLOR` - Total Color Count

## Commands Available

### 1. Basic MC Table Seeder
```bash
php artisan seed:mc-table
```
Menjalankan seeder dasar untuk tabel MC.

### 2. Comprehensive MC Table Seeder
```bash
php artisan seed:mc-comprehensive
```
Menjalankan seeder komprehensif dengan data yang lebih lengkap.

### 3. Run All Seeders
```bash
php artisan db:seed
```
Menjalankan semua seeder termasuk MC table seeder.

## Data Sample
Seeder ini menyertakan data sample untuk:
- BOX BASO 4,5 KG (MCS_Num: 1609138)
- BOX IKAN HARIMAU 4.5 KG (MCS_Num: 1609144)
- BOX SRIKAYA 4.5 KG (MCS_Num: 1609145)

## Notes
- Seeder menggunakan `updateOrInsert` untuk mencegah duplikasi data
- Data disimpan dengan format yang sesuai dengan struktur tabel MC
- Semua field numeric menggunakan format decimal dengan 4 digit desimal
- Seeder mengikuti struktur data yang digunakan di menu Update MC

## Troubleshooting
Jika terjadi error saat menjalankan seeder:
1. Pastikan tabel `MC` sudah dibuat melalui migration
2. Pastikan database connection berfungsi dengan baik
3. Check log error untuk detail masalah yang terjadi





