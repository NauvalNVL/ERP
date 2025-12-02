# View & Print Category - Web Version

## 📋 Overview

View & Print Category adalah versi website dari menu desktop ERP yang memungkinkan pengguna untuk melihat dan mencetak daftar kategori material management. Fitur ini dirancang untuk memberikan pengalaman yang mirip dengan aplikasi desktop ERP dengan antarmuka web yang modern dan responsif.

## 🎯 Fitur Utama

### 1. **Tampilan Tabel Kategori**
- Menampilkan daftar kategori dengan kolom: Code, Name, Description, Status
- Tabel responsif dengan scroll horizontal untuk layar kecil
- Highlight baris yang dipilih (seperti di desktop ERP)
- Border antar kolom untuk memudahkan pembacaan

### 2. **Fitur Pencarian**
- Pencarian real-time berdasarkan:
  - Kode kategori
  - Nama kategori  
  - Deskripsi kategori
- Placeholder text yang informatif
- Debouncing untuk performa optimal

### 3. **Filter Status**
- Filter berdasarkan status kategori:
  - All Status (semua)
  - Active Only (aktif saja)
  - Inactive Only (non-aktif saja)
- Dropdown yang mudah digunakan

### 4. **Fitur Print**
- Header print dengan logo perusahaan
- Format yang dioptimalkan untuk pencetakan
- Informasi tanggal dan waktu cetak
- Styling khusus untuk print (CSS @media print)

### 5. **Informasi Statistik**
- Total kategori
- Jumlah kategori aktif
- Jumlah kategori non-aktif
- Jumlah hasil yang ditampilkan

### 6. **Navigasi**
- Tombol Back untuk kembali ke halaman kategori
- Tombol Refresh untuk memperbarui data
- Link yang jelas dan mudah diakses

## 🏗️ Arsitektur Teknis

### Frontend (Vue.js)
- **File**: `resources/js/Pages/material-management/system-requirement/inventory-setup/ViewPrintCategory.vue`
- **Framework**: Vue 3 dengan Composition API
- **Styling**: Tailwind CSS
- **Icons**: Font Awesome
- **State Management**: Vue 3 refs dan computed properties

### Backend (Laravel)
- **Controller**: `app/Http/Controllers/MaterialManagement/SystemRequirement/MmCategoryController.php`
- **Model**: `app/Models/MmCategory.php`
- **API Endpoint**: `/api/material-management/categories/for-print`
- **Database**: SQL Server

### Routes
```php
// API Routes
Route::get('/material-management/categories/for-print', [MmCategoryController::class, 'getCategoriesForPrint']);

// Web Routes  
Route::get('/material-management/system-requirement/inventory-setup/view-print-category', [MmCategoryController::class, 'viewPrint']);
```

## 🎨 UI/UX Features

### 1. **Responsive Design**
- Mobile-friendly dengan scroll horizontal
- Breakpoints yang dioptimalkan
- Touch-friendly untuk perangkat mobile

### 2. **Visual Feedback**
- Loading spinner saat memuat data
- Hover effects pada baris tabel
- Selected state untuk baris yang dipilih
- Toast notifications untuk feedback

### 3. **Accessibility**
- Keyboard navigation support
- Screen reader friendly
- High contrast colors
- Focus indicators

### 4. **Print Optimization**
- Clean print layout
- Proper page breaks
- Company branding
- Date and time stamps

## 📊 Data Structure

### Category Model
```php
{
  "code": "A1.01",
  "name": "BEARING", 
  "description": "Bearing components",
  "is_active": true,
  "created_at": "2025-08-03T09:30:00Z",
  "updated_at": "2025-08-03T09:30:00Z"
}
```

### API Response
```json
[
  {
    "code": "A1.01",
    "name": "BEARING",
    "description": "Bearing components",
    "is_active": true
  },
  {
    "code": "A1.02", 
    "name": "BEARING",
    "description": "Bearing components",
    "is_active": true
  }
]
```

## 🔧 Konfigurasi

### 1. **Environment Variables**
Tidak ada environment variables khusus yang diperlukan.

### 2. **Database**
- Tabel: `mm_categories`
- Migration: `2025_08_03_000000_create_mm_categories_table.php`
- Seeder: `MmCategorySeeder.php`

### 3. **Dependencies**
```json
{
  "vue": "^3.x",
  "inertia": "^1.x", 
  "tailwindcss": "^3.x",
  "@fortawesome/fontawesome-free": "^6.x"
}
```

## 🚀 Cara Penggunaan

### 1. **Akses Halaman**
```
/material-management/system-requirement/inventory-setup/view-print-category
```

### 2. **Pencarian Kategori**
- Ketik di kotak pencarian untuk mencari berdasarkan kode, nama, atau deskripsi
- Hasil pencarian akan ditampilkan secara real-time

### 3. **Filter Status**
- Pilih filter dari dropdown untuk menampilkan kategori berdasarkan status
- Opsi: All Status, Active Only, Inactive Only

### 4. **Print Kategori**
- Klik tombol "Print" untuk mencetak daftar kategori
- Browser akan membuka dialog print
- Pilih printer dan pengaturan yang diinginkan

### 5. **Refresh Data**
- Klik tombol "Refresh" untuk memperbarui data dari server
- Berguna jika ada perubahan data dari sumber lain

## 🔍 Troubleshooting

### 1. **Data Tidak Muncul**
- Periksa koneksi database
- Jalankan seeder: `php artisan db:seed --class=MmCategorySeeder`
- Periksa log Laravel untuk error

### 2. **Print Tidak Berfungsi**
- Pastikan browser mendukung print
- Periksa popup blocker
- Coba browser berbeda

### 3. **Pencarian Lambat**
- Periksa indeks database pada kolom pencarian
- Optimasi query jika diperlukan
- Pertimbangkan implementasi debouncing

## 📈 Performance Optimization

### 1. **Database**
- Indeks pada kolom `code`, `name`, `description`
- Query optimization dengan eager loading jika diperlukan

### 2. **Frontend**
- Debouncing pada pencarian
- Lazy loading untuk data besar
- Memoization untuk computed properties

### 3. **Caching**
- Cache hasil API jika data jarang berubah
- Implementasi Redis untuk performa tinggi

## 🔒 Security

### 1. **Authentication**
- Pastikan user terautentikasi sebelum mengakses halaman
- Implementasi middleware auth jika diperlukan

### 2. **Authorization**
- Periksa permission user untuk akses kategori
- Implementasi role-based access control

### 3. **Data Validation**
- Validasi input pencarian
- Sanitasi output untuk mencegah XSS

## 📝 Changelog

### Version 1.0.0 (2025-08-03)
- ✅ Implementasi dasar View & Print Category
- ✅ Fitur pencarian dan filter
- ✅ Print functionality
- ✅ Responsive design
- ✅ Toast notifications
- ✅ Statistik kategori

## 🎯 Roadmap

### Future Enhancements
- [ ] Export ke Excel/PDF
- [ ] Bulk operations
- [ ] Advanced filtering
- [ ] Real-time updates
- [ ] Dark mode support
- [ ] Keyboard shortcuts

## 📞 Support

Untuk bantuan teknis atau pertanyaan, silakan hubungi:
- **Email**: support@multiboxindah.com
- **Documentation**: [Internal Wiki]
- **Issue Tracker**: [GitHub Issues]

---

**Dibuat oleh**: Development Team  
**Terakhir diperbarui**: 3 Agustus 2025  
**Versi**: 1.0.0 