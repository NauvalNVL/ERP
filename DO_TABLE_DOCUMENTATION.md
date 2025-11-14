# 📦 Dokumentasi Table DO (Delivery Order) - ERP CPS

## 🎯 Overview

Table **DO** menyimpan data pengiriman (Delivery Order) yang berasal dari Sales Order (SO) dan Master Card (MC), ditambah input user dari screen DO.

Migration: `2025_10_07_080137_create_do_table.php`
Controller utama: `DeliveryOrderController@store`

---

## 📊 Ringkasan Sumber Data

**Sumber utama kolom-kolom DO:**

- **Input User (screen DO)**: tanggal DO, vehicle, DO quantity, remark, PO override, unapply flag.
- **SO (Sales Order)**: nomor SO, tipe SO, model, product, LOT, PQ1–PQ5, unit, part number, dimensi, currency, exchange rate.
- **MC (Master Card)**: dimensi backup, flute, PQ1–PQ5, M2/pcs, Kg/pcs.
- **CUSTOMER**: nama, salesman, industry, area, group.
- **Perhitungan Sistem**: DO_M3, DO_Tran_Amt, DO_Base_Amt, Total_DO_M2, Total_DO_KG, DODateSK.

---

## 📋 Mapping Kolom → Sumber Data

| # | Kolom | Type | Source | Keterangan |
|---|-------|------|--------|-----------|
| 1 | `DOYYYY` | string(50) | System Date | Tahun DO (`orderDate->format('Y')`) |
| 2 | `DOMM` | string(50) | System Date | Bulan DO (`orderDate->format('m')`) |
| 3 | `DO_Num` | string(50) | Auto-generated | Nomor DO (format CPS, mis. `2025-11-00001`) |
| 4 | `Status` | string(50) | Hardcoded | Di-set `'Draft'` saat create DO |
| 5 | `DO_DMY` | string(50) | System Date | Tanggal DO `orderDate->format('d/m/Y')` |
| 6 | `DO_VHC_Num` | string(50) | User Input | `request->vehicle_number` |
| 7 | `VHC_Class` | string(50) | VEHICLE table | `vehicle->VEHICLE_CLASS` berdasarkan `vehicle_number` |
| 8 | `AC_Num` | string(50) | User Input | `request->customer_code` |
| 9 | `AC_Name` | string(250) | CUSTOMER table | `customer->NAME` / `customer->AC_Name` |
| 10 | `No` | string(50) | Hardcoded | `'1'` (line number pertama) |
| 11 | `MCS_Num` | string(50) | User Input | `request->mcard_seq` (Master Card Seq) |
| 12 | `Model` | string(250) | Request / SO | `request->model` atau `SO.MODEL` |
| 13 | `Product` | string(50) | SO / MC | `SO.PRODUCT`/`SO.Product` atau `MC.PRODUCT` |
| 14 | `COMP` | string(50) | CUSTOMER | `customer->CODE` atau `customer->AC_Num` |
| 15 | `PD` | string(50) | Request / SO | `request->pd` atau `SO.P_DESIGN` |
| 16 | `PCS_PER_SET` | decimal(15,2) | Request / SO | `request->per_set` atau `SO.PER_SET` atau default 1 |
| 17 | `Unit` | string(50) | Request / SO | `request->unit` atau `SO.UNIT` atau default `'PCS'` |
| 18 | `Part_Number` | string(50) | SO / MC | `SO.PART_NUMBER` atau `MC.PART_NO` |
| 19 | `INT_L` | decimal(15,2) | SO / MC | `SO.INT_L` atau `MC.INT_LENGTH` |
| 20 | `INT_W` | decimal(15,2) | SO / MC | `SO.INT_W` atau `MC.INT_WIDTH` |
| 21 | `INT_H` | decimal(15,2) | SO / MC | `SO.INT_H` atau `MC.INT_HEIGHT` |
| 22 | `EXT_L` | decimal(15,2) | SO / MC | `SO.EXT_L` atau `MC.EXT_LENGTH` (dipakai untuk DO_M3) |
| 23 | `EXT_W` | decimal(15,2) | SO / MC | `SO.EXT_W` atau `MC.EXT_WIDTH` |
| 24 | `EXT_H` | decimal(15,2) | SO / MC | `SO.EXT_H` atau `MC.EXT_HEIGHT` |
| 25 | `Flute` | string(50) | SO / MC | `SO.FLUTE` atau `MC.FLUTE` |
| 26 | `SLM` | string(50) | CUSTOMER / SO | `customer->SLM` atau `SO.SLM` |
| 27 | `IND` | string(50) | CUSTOMER | `customer->IND` atau `customer->INDUSTRY` |
| 28 | `Area1` | string(50) | CUSTOMER | `customer->AREA` atau `customer->AREA1` |
| 29 | `Del_Code` | string(30) | **SO.D_LOC_Num** | **Kode geo, langsung dari `SO.D_LOC_Num`** |
| 30 | `Group_` | string(50) | CUSTOMER | `customer->GROUP_` / `customer->Group_` |
| 31 | `SO_Num` | string(50) | SO | Nomor SO asal DO |
| 32 | `SO_Type` | string(50) | SO | `SO.TYPE` |
| 33 | `PO_Num` | string(250) | Request / SO | `request->po_number` atau `SO.PO_Num` |
| 34 | `PO_Date` | string(50) | Request / SO | `request->po_date` atau `SO.PO_DATE` |
| 35 | `LOT_Num` | string(50) | SO | `SO.LOT_Num` |
| 36 | `PQ1` | string(50) | SO / MC | `SO.PQ1` atau `MC.SO_PQ1` |
| 37 | `PQ2` | string(50) | SO / MC | `SO.PQ2` atau `MC.SO_PQ2` |
| 38 | `PQ3` | string(50) | SO / MC | `SO.PQ3` atau `MC.SO_PQ3` |
| 39 | `PQ4` | string(50) | SO / MC | `SO.PQ4` atau `MC.SO_PQ4` |
| 40 | `PQ5` | string(50) | SO / MC | `SO.PQ5` atau `MC.SO_PQ5` |
| 41 | `DO_Qty` | decimal(15,2) | User Input | Jumlah barang yang dikirim di DO (`$doQty`) |
| 42 | `UNAPPLIED_FG` | string(10) | User Input | Flag: `request->unapply_fg ? 'Y' : 'N'` |
| 43 | `DO_M3` | decimal(15,2) | **Calculated** | Volume m³ dari EXT dimensi & DO_Qty |
| 44 | `SO_Unit_Price` | decimal(15,2) | SO / perhitungan | Harga per unit yang dipakai DO |
| 45 | `Curr` | string(50) | SO / Default | `SO.CURR` atau `'IDR'` |
| 46 | `Ex_Rate` | decimal(15,2) | SO | Nilai kurs yang dipakai (dari SO) |
| 47 | `DO_Tran_Amt` | decimal(15,2) | **Calculated** | `DO_Qty × SO_Unit_Price` |
| 48 | `DO_Base_Amt` | decimal(15,2) | **Calculated** | `DO_Tran_Amt × Ex_Rate` |
| 49 | `MC_Gross_M2_Per_Pcs` | decimal(15,2) | MC | `MC.MC_GROSS_M2_PER_PCS` |
| 50 | `MC_Net_M2_Per_Pcs` | decimal(15,2) | MC | `MC.MC_NET_M2_PER_PCS` |
| 51 | `Total_DO_Gross_M2` | decimal(15,2) | **Calculated** | `DO_Qty × MC_Gross_M2_Per_Pcs` |
| 52 | `Total_DO_Net_M2` | decimal(15,2) | **Calculated** | `DO_Qty × MC_Net_M2_Per_Pcs` |
| 53 | `MC_Gross_Kg_Per_Pcs` | decimal(15,2) | MC | `MC.MC_GROSS_KG_PER_SET` |
| 54 | `MC_Net_Kg_Per_Pcs` | string(30) | MC | `MC.MC_NET_KG_PER_PCS` |
| 55 | `Total_DO_Gross_KG` | decimal(15,2) | **Calculated** | `DO_Qty × MC_Gross_Kg_Per_Pcs` |
| 56 | `Total_DO_Net_KG` | decimal(15,2) | **Calculated** | `DO_Qty × MC_Net_Kg_Per_Pcs` |
| 57 | `DODateSK` | integer | **Calculated** | Key tanggal DO: `orderDate->format('Ymd')` (YYYYMMDD) |
| 58 | `DO_Remark1` | string(250) | User Input | `request->remark1` |
| 59 | `DO_Remark2` | string(250) | User Input | `request->remark2` |
| 60 | `Cancelled_Reason` | string(250) | User Input / System | Kosong saat create, diisi saat cancel DO |

---

## 🔢 Detail Perhitungan Penting

### 1. DO_M3 (Volume DO)

**Formula:**
```text
DO_M3 = (EXT_L × EXT_W × EXT_H × DO_Qty) / 1,000,000,000
```
- EXT_L/W/H dalam **mm**
- DO_Qty = quantity DO
- Hasil disimpan di kolom `DO_M3` dengan 2 decimal (`round($doM3, 2)`).

**Contoh:**
- EXT_L = 500 mm, EXT_W = 400 mm, EXT_H = 300 mm, DO_Qty = 100
- DO_M3 = (500 × 400 × 300 × 100) / 1,000,000,000 = **6.00 m³**

---

### 2. DO_Tran_Amt dan DO_Base_Amt

```text
DO_Tran_Amt = DO_Qty × SO_Unit_Price
DO_Base_Amt = DO_Tran_Amt × Ex_Rate
```

**Contoh:**
- DO_Qty = 100 pcs
- SO_Unit_Price = 5,000
- Ex_Rate = 1

Maka:
- DO_Tran_Amt = 100 × 5,000 = **500,000.00**
- DO_Base_Amt = 500,000 × 1 = **500,000.00**

---

### 3. Total_DO_Gross_M2 & Total_DO_Net_M2

```text
Total_DO_Gross_M2 = DO_Qty × MC_Gross_M2_Per_Pcs
Total_DO_Net_M2   = DO_Qty × MC_Net_M2_Per_Pcs
```

**Contoh:**
- DO_Qty = 100
- MC_Gross_M2_Per_Pcs = 0.45
- MC_Net_M2_Per_Pcs   = 0.40

Hasil:
- Total_DO_Gross_M2 = 100 × 0.45 = **45.0000 m²**
- Total_DO_Net_M2   = 100 × 0.40 = **40.0000 m²**

---

### 4. Total_DO_Gross_KG & Total_DO_Net_KG

```text
Total_DO_Gross_KG = DO_Qty × MC_Gross_Kg_Per_Pcs
Total_DO_Net_KG   = DO_Qty × MC_Net_Kg_Per_Pcs
```

**Contoh:**
- DO_Qty = 100
- MC_Gross_Kg_Per_Pcs = 0.85
- MC_Net_Kg_Per_Pcs   = 0.75

Hasil:
- Total_DO_Gross_KG = 100 × 0.85 = **85.0000 kg**
- Total_DO_Net_KG   = 100 × 0.75 = **75.0000 kg**

---

## 🌍 Del_Code (Kode Geo) – Hubungan dengan SO

- `Del_Code` di DO **tidak lagi dihitung ulang** dari CUSTOMER atau alternate address.
- Sekarang **langsung diambil dari `SO.D_LOC_Num`**, yang sudah menyimpan kode geo (H, BDG, S, dll).

**Keuntungan:**
- Single source of truth: kode geo di SO → dipakai ulang di DO.
- Logic sama persis antara SO dan DO.

---

## 🔗 Relasi DO dengan Tabel Lain

- **DO → SO**
  - Via `SO_Num` dan `SO_Type`.
  - Mengambil model, product, lot, PQ1–PQ5, unit, part number, dimensi, curr, ex_rate.

- **DO → CUSTOMER**
  - Via `AC_Num`.
  - Mengambil `AC_Name`, `SLM`, `IND`, `Area1`, `Group_`.

- **DO → MC**
  - Via `MCS_Num` dan `AC_Num`.
  - Mengambil dimensi, material, M2 per pcs, Kg per pcs.

- **DO → VEHICLE**
  - Via `DO_VHC_Num`.
  - Mengambil `VHC_Class`.

---

## 📝 Summary

- Table **DO** adalah turunan dari **SO + MC + CUSTOMER + input user**.
- Semua kolom diisi konsisten dengan standard CPS:
  - Volume (`DO_M3`) dalam m³.
  - Material usage (`Total_DO_Gross_M2`, `Total_DO_Net_M2`).
  - Weight (`Total_DO_Gross_KG`, `Total_DO_Net_KG`).
  - Kode geo (`Del_Code`) = dari `SO.D_LOC_Num`.
- File ini bisa dipakai sebagai referensi cepat saat debugging atau mapping ke CPS.

**Status:** ✅ COMPLETED  
**Date:** 14 November 2025  
**Controller:** `DeliveryOrderController.php`
