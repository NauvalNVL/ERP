# Cara Menyimpan Multiple Components (Main + Fit1-9)

## Langkah-Langkah

### 1. Buka Update MC dan Pilih Customer

```
1. Klik icon search di sebelah AC#
2. Pilih customer dari list
3. Customer code dan name akan terisi otomatis
```

### 2. Enter Master Card Number

```
1. Ketik MCS# baru (misal: MC001) atau search existing
2. Klik tombol "Proceed"
3. Form akan expand menampilkan detail fields
```

### 3. Isi MC Model dan MC Short Model

```
1. Isi "MC Model" (misal: Box Type A)
2. Isi "MC Short Model" (misal: BTA)
3. Klik tombol "Next Setup"
```

### 4. Setup Main Component (Default)

```
Modal "Setup MC, Component" akan terbuka dengan tabel component:

NO | C#   | PD | PCS/SET | PART#
---|------|----|---------|---------
01 | Main |    |         |         ← Sudah ter-highlight (biru)
02 | Fit1 |    |         |
03 | Fit2 |    |         |
...

1. Main sudah ter-select (background biru)
2. Klik tombol "Setup PD"
3. Isi semua field yang diperlukan:
   - P/Design
   - Flute
   - SO (Paper Quality) - klik button SO1-SO5
   - WO (Paper Quality) - klik button WO1-WO5
   - Dimensions (ID, ED)
   - Colors
   - dll
4. Klik tombol "Save Master Card"
5. Tunggu notifikasi: "Master Card saved successfully for component: Main"
```

**✅ Database sekarang punya 1 row:**
```
MCS_Num | AC_NUM | COMP | MODEL
--------|--------|------|-------
MC001   | C001   | Main | Box Type A
```

### 5. Setup Fit1 Component

```
Masih di modal yang sama:

1. KLIK row "Fit1" di tabel (row nomor 02)
   ⚠️ PENTING: Harus KLIK row-nya!
   
2. Row Fit1 akan ter-highlight (background biru)
   Row Main tidak ter-highlight lagi
   
3. Klik tombol "Setup PD"

4. Isi field untuk Fit1 (bisa berbeda dari Main):
   - P/Design: bisa pilih design berbeda
   - Flute: bisa pilih flute berbeda
   - SO/WO: bisa pilih paper quality berbeda
   - Dimensions: bisa isi dimensions berbeda
   - dll
   
5. Klik tombol "Save Master Card"

6. Tunggu notifikasi: "Master Card saved successfully for component: Fit1"
```

**✅ Database sekarang punya 2 rows:**
```
MCS_Num | AC_NUM | COMP | MODEL
--------|--------|------|-------
MC001   | C001   | Main | Box Type A
MC001   | C001   | Fit1 | Divider Type B  ← Row baru!
```

### 6. Setup Fit2, Fit3, dst (Opsional)

```
Ulangi langkah 5 untuk setiap component yang dibutuhkan:

1. KLIK row "Fit2"
2. Klik "Setup PD"
3. Isi form
4. Klik "Save Master Card"
5. Notifikasi: "Master Card saved successfully for component: Fit2"

1. KLIK row "Fit3"
2. Klik "Setup PD"
3. Isi form
4. Klik "Save Master Card"
5. Notifikasi: "Master Card saved successfully for component: Fit3"

... dan seterusnya sampai Fit9 jika diperlukan
```

**✅ Database sekarang punya multiple rows:**
```
MCS_Num | AC_NUM | COMP | MODEL
--------|--------|------|-------
MC001   | C001   | Main | Box Type A
MC001   | C001   | Fit1 | Divider Type B
MC001   | C001   | Fit2 | Insert Type C
MC001   | C001   | Fit3 | Padding Type D
```

## Visual Guide

```
┌─────────────────────────────────────────────────────┐
│  Setup MC, Component Modal                          │
├─────────────────────────────────────────────────────┤
│  AC#: C001    Customer Name                         │
│  MCS#: MC001  Box Type A                            │
│                                                      │
│  Component Table:                                   │
│  ┌────┬──────┬────┬─────────┬───────┐              │
│  │ NO │  C#  │ PD │ PCS/SET │ PART# │              │
│  ├────┼──────┼────┼─────────┼───────┤              │
│  │ 01 │ Main │ B1 │    1    │  P001 │ ← Klik ini   │
│  │ 02 │ Fit1 │    │         │       │ ← Klik ini   │
│  │ 03 │ Fit2 │    │         │       │ ← Klik ini   │
│  │ 04 │ Fit3 │    │         │       │              │
│  │ .. │ ...  │    │         │       │              │
│  └────┴──────┴────┴─────────┴───────┘              │
│                                                      │
│  [Setup PD]  [Setup Others]                         │
│                                                      │
│  Editing component: Main  ← Akan berubah saat klik  │
└─────────────────────────────────────────────────────┘
```

## Poin Penting

### ⚠️ HARUS KLIK ROW COMPONENT!

Ini adalah langkah PALING PENTING:

```
❌ SALAH:
1. Setup Main
2. Save
3. Langsung klik "Setup PD" lagi (tanpa klik row Fit1)
4. Isi form
5. Save
→ Hasil: Data ter-overwrite di Main, bukan create Fit1 baru

✅ BENAR:
1. Setup Main
2. Save
3. KLIK ROW FIT1 di tabel  ← WAJIB!
4. Klik "Setup PD"
5. Isi form
6. Save
→ Hasil: Data tersimpan di Fit1, Main tidak berubah
```

### Visual Indicator

Saat Anda klik row component yang benar:

1. **Background berubah biru** pada row yang diklik
2. **Text "Editing component:"** berubah sesuai yang diklik
3. **Console browser** menampilkan log (F12 untuk buka console):
   ```
   ✅ Component selected: { component_name: "Fit1", ... }
   ```

### Jika Tidak Yakin

Sebelum klik "Save Master Card", cek:

1. Row yang ter-highlight (background biru) = component yang akan disave
2. Text "Editing component: Fit1" = component yang akan disave
3. Console log menunjukkan component yang benar

## Contoh Use Case

### Use Case 1: Box dengan Divider

```
Main  = Outer box
Fit1  = Inner divider (vertical)
Fit2  = Inner divider (horizontal)

Langkah:
1. Setup Main (outer box) → Save
2. Klik row Fit1 → Setup PD (divider vertical) → Save
3. Klik row Fit2 → Setup PD (divider horizontal) → Save

Result: 3 rows di database (Main, Fit1, Fit2)
```

### Use Case 2: Box dengan Lid dan Insert

```
Main  = Box body
Fit1  = Lid/cover
Fit2  = Insert/padding

Langkah:
1. Setup Main (box body) → Save
2. Klik row Fit1 → Setup PD (lid) → Save
3. Klik row Fit2 → Setup PD (insert) → Save

Result: 3 rows di database (Main, Fit1, Fit2)
```

### Use Case 3: Multi-part Assembly

```
Main  = Base component
Fit1  = Part A
Fit2  = Part B
Fit3  = Part C
Fit4  = Part D

Langkah:
1. Setup Main → Save
2. Klik Fit1 → Setup PD → Save
3. Klik Fit2 → Setup PD → Save
4. Klik Fit3 → Setup PD → Save
5. Klik Fit4 → Setup PD → Save

Result: 5 rows di database (Main, Fit1-4)
```

## Verifikasi di Database

Setelah save semua components, verifikasi dengan query:

```sql
SELECT MCS_Num, AC_NUM, COMP, MODEL, P_DESIGN, created_at
FROM MC
WHERE MCS_Num = 'MC001'  -- Ganti dengan MCS# Anda
ORDER BY COMP;
```

Expected result:
```
MCS_Num | AC_NUM | COMP | MODEL       | P_DESIGN | created_at
--------|--------|------|-------------|----------|-------------------
MC001   | C001   | Fit1 | Divider     | B0       | 2024-01-01 10:05:00
MC001   | C001   | Fit2 | Insert      | APR      | 2024-01-01 10:10:00
MC001   | C001   | Main | Box Type A  | B1       | 2024-01-01 10:00:00
```

Jika hanya ada 1 row atau COMP selalu "Main", berarti Anda lupa klik row component sebelum save.

## Tips

1. **Simpan Main dulu** - Selalu setup dan save Main component terlebih dahulu
2. **Klik row sebelum Setup PD** - Jangan lupa klik row component yang ingin di-setup
3. **Cek highlight** - Pastikan row yang benar ter-highlight sebelum klik Setup PD
4. **Save satu-satu** - Jangan setup semua component sekaligus, save satu per satu
5. **Verifikasi console** - Buka console browser (F12) untuk melihat log
6. **Cek database** - Setelah selesai, verifikasi dengan query SQL

## Troubleshooting

### Masalah: Data selalu tersimpan di Main

**Penyebab:** Lupa klik row component sebelum save

**Solusi:**
1. Pastikan KLIK row Fit1/Fit2/dst sebelum klik "Setup PD"
2. Cek row ter-highlight (background biru)
3. Cek text "Editing component:" menunjukkan component yang benar

### Masalah: Data ter-overwrite

**Penyebab:** Klik row yang sama 2x atau tidak klik row baru

**Solusi:**
1. Setiap kali mau setup component baru, KLIK row-nya dulu
2. Jangan langsung klik "Setup PD" tanpa klik row
3. Verifikasi highlight dan text "Editing component:"

### Masalah: Tidak yakin component mana yang ter-select

**Solusi:**
1. Buka console browser (F12)
2. Klik row component
3. Lihat log: "✅ Component selected: { component_name: ... }"
4. Pastikan component_name sesuai yang Anda klik

## Need Help?

Jika masih ada masalah:

1. Capture screenshot UI saat memilih component
2. Capture console logs (F12)
3. Capture database query result
4. Hubungi developer dengan informasi di atas
