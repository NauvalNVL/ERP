# Fix: Tabel Component Tidak Menampilkan Data Fit yang Sudah Dikonfigurasi

## Masalah

Tabel di modal "Setup MC Component" tidak menampilkan data PD, PCS/SET, dan PART# untuk Fit1-Fit9 yang sudah dikonfigurasi sebelumnya. Tabel hanya menampilkan row kosong meskipun data sudah tersimpan di database.

### Contoh Masalah:

```
Database memiliki:
MCS_Num | COMP | P_DESIGN | PCS_SET | PART_NO
--------|------|----------|---------|----------
MC001   | Main | B1       | 1       | P001
MC001   | Fit1 | B0       | 2       | P002
MC001   | Fit2 | APR      | 1       | P003

Tapi tabel di modal menampilkan:
NO | C#   | PD | PCS/SET | PART#
---|------|----|---------|---------
01 | Main |    |         |         ← Kosong
02 | Fit1 |    |         |         ← Kosong
03 | Fit2 |    |         |         ← Kosong
```

## Akar Masalah

### 1. Data Tidak Dimuat Saat Modal Dibuka

**File:** `resources/js/Pages/sales-management/system-requirement/master-card/update-mc.vue`

Fungsi `handleNextSetup()` yang membuka modal tidak memuat data full MC untuk existing records:

```javascript
// SEBELUM - SALAH
const handleNextSetup = () => {
    // ... validasi ...
    
    if (recordMode.value === "new") {
        // Reset data untuk new MC
        selectedMcsFull.value = null;
    }
    // ❌ Tidak ada loading data untuk existing MC!
    
    showSetupMcModal.value = true;
};
```

### 2. Modal Tidak Menerima Data Components

Modal `UpdateMcModal