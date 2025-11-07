# 🔧 Page Expired Error - Fix Documentation

## ❌ **Masalah yang Terjadi**

User mengalami error **"Page Expired"** (419 CSRF Token Mismatch) saat:
1. ✅ **Update Password** di menu "Amend User Password"
2. ✅ **Edit User** di menu "Define User" → Edit

## 🔍 **Penyebab Masalah**

Error **"Page Expired"** terjadi karena **CSRF Token tidak disertakan** dalam request Inertia.js.

### **Detail Penyebab:**

```
Laravel Error 419: CSRF token mismatch
↓
Inertia.js POST/PUT request tanpa _token
↓
Laravel VerifyCsrfToken middleware menolak request
↓
User melihat "Page Expired"
```

### **File yang Bermasalah:**

1. ❌ **AmendPassword.vue** (line 231):
   ```javascript
   this.$inertia.post('/system-security/update-password', this.form, { ... });
   // ❌ Missing CSRF token in form data
   ```

2. ❌ **Edit.vue** (line 332):
   ```javascript
   this.$inertia.put(`/user/${this.user.id}`, this.form);
   // ❌ Missing CSRF token in form data
   ```

---

## ✅ **Solusi yang Diterapkan**

### **1. Fix AmendPassword.vue**

**SEBELUM (SALAH):**
```javascript
updatePassword() {
    if (!this.foundUser) return;
    
    this.$inertia.post('/system-security/update-password', this.form, {
        onSuccess: () => {
            this.form.new_password = '';
            this.form.new_password_confirmation = '';
        },
    });
},
```

**SESUDAH (BENAR):**
```javascript
updatePassword() {
    if (!this.foundUser) return;
    
    // ✅ Add CSRF token to form data
    const formData = {
        ...this.form,
        _token: this.$page.props.csrf_token || document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
    };
    
    this.$inertia.post('/system-security/update-password', formData, {
        onSuccess: () => {
            this.form.new_password = '';
            this.form.new_password_confirmation = '';
        },
        onError: (errors) => {
            console.error('Update password errors:', errors);
            this.setMessage('Failed to update password', 'error');
        }
    });
},
```

---

### **2. Fix Edit.vue**

**SEBELUM (SALAH):**
```javascript
methods: {
    submitForm() {
        this.$inertia.put(`/user/${this.user.id}`, this.form);
    },
}
```

**SESUDAH (BENAR):**
```javascript
methods: {
    submitForm() {
        // ✅ Add CSRF token to form data
        const formData = {
            ...this.form,
            _token: this.$page.props.csrf_token || document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
        };
        
        this.$inertia.put(`/user/${this.user.id}`, formData, {
            preserveState: true,
            preserveScroll: true,
            onError: (errors) => {
                console.error('Update user errors:', errors);
            }
        });
    },
}
```

---

### **3. Update HandleInertiaRequests.php Middleware**

Menambahkan `csrf_token` ke shared props untuk konsistensi:

**File:** `app/Http/Middleware/HandleInertiaRequests.php`

```php
public function share(Request $request): array
{
    return array_merge(parent::share($request), [
        'csrf' => function () {
            return csrf_token();
        },
        'csrf_token' => function () {  // ✅ Added for consistency
            return csrf_token();
        },
        'appName' => config('app.name'),

        'auth' => function () use ($request) {
            return [
                'user' => $request->user(),
                'permissions' => $request->user() ? $request->user()->getPermissionsArray() : [],
            ];
        },
        'flash' => function () {
            return [
                'message' => session('message'),
                'error' => session('error'),
                'success' => session('success'),
            ];
        },
    ]);
}
```

---

## 📊 **Cara Kerja CSRF Protection di Laravel + Inertia.js**

### **Flow Normal (Benar):**

```
1. User mengakses halaman
   ↓
2. Laravel generate CSRF token → disimpan di session
   ↓
3. Inertia.js menerima token via $page.props.csrf_token
   ↓
4. User submit form
   ↓
5. Frontend menambahkan _token ke form data
   ↓
6. Laravel VerifyCsrfToken middleware memvalidasi token
   ↓
7. ✅ Request diterima, proses berhasil
```

### **Flow Error (Sebelum Fix):**

```
1. User mengakses halaman
   ↓
2. Laravel generate CSRF token → disimpan di session
   ↓
3. Inertia.js menerima token (tapi tidak dipakai)
   ↓
4. User submit form
   ↓
5. ❌ Frontend TIDAK menambahkan _token ke form data
   ↓
6. Laravel VerifyCsrfToken middleware menolak request
   ↓
7. ❌ Error 419: Page Expired
```

---

## 🔐 **Best Practices untuk CSRF Token di Inertia.js**

### **✅ Cara 1: Menggunakan $page.props (Recommended)**

```javascript
const formData = {
    ...this.form,
    _token: this.$page.props.csrf_token
};

this.$inertia.post('/endpoint', formData);
```

**Keuntungan:**
- ✅ Konsisten dengan data yang sudah ada di Inertia
- ✅ Lebih predictable
- ✅ Tidak perlu DOM query

---

### **✅ Cara 2: Fallback ke Meta Tag**

```javascript
const formData = {
    ...this.form,
    _token: this.$page.props.csrf_token || 
            document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
};

this.$inertia.post('/endpoint', formData);
```

**Keuntungan:**
- ✅ Fallback jika props tidak tersedia
- ✅ Compatible dengan berbagai setup

---

### **❌ Cara yang SALAH**

```javascript
// ❌ JANGAN seperti ini - Token tidak disertakan
this.$inertia.post('/endpoint', this.form);

// ❌ JANGAN seperti ini - Hardcoded token (tidak aman)
this.$inertia.post('/endpoint', {
    ...this.form,
    _token: 'hardcoded-token-value'
});
```

---

## 🧪 **Testing Setelah Fix**

### **Test Case 1: Amend User Password**

**Steps:**
1. Login sebagai admin
2. Buka menu: **System Manager** → **System Security** → **Amend User Password**
3. Search user ID: `TEST001`
4. Masukkan new password: `NewPass123!`
5. Confirm password: `NewPass123!`
6. Click **"Update Password"**

**Expected Result:**
- ✅ Password berhasil di-update
- ✅ Muncul success message
- ✅ **TIDAK ADA** error "Page Expired"

---

### **Test Case 2: Edit User**

**Steps:**
1. Login sebagai admin
2. Buka menu: **System Manager** → **System Security** → **Define User**
3. Click action menu → **Edit User** untuk user tertentu
4. Ubah data (misal: Official Name, Mobile Number)
5. Click **"Update User"**

**Expected Result:**
- ✅ User data berhasil di-update
- ✅ Redirect ke halaman user list dengan success message
- ✅ **TIDAK ADA** error "Page Expired"

---

### **Test Case 3: Create New User**

**Steps:**
1. Login sebagai admin
2. Buka menu: **System Manager** → **System Security** → **Define User**
3. Click **"Add New User"**
4. Isi semua required fields
5. Click **"Create User"**

**Expected Result:**
- ✅ User baru berhasil dibuat
- ✅ Redirect ke halaman user list dengan success message
- ✅ **TIDAK ADA** error "Page Expired"

---

## 📝 **Checklist Files yang Di-update**

- ✅ `resources/js/Pages/system-manager/system-security/AmendPassword.vue`
  - Added CSRF token to form data
  - Added error handling

- ✅ `resources/js/Pages/system-manager/system-security/Edit.vue`
  - Added CSRF token to form data
  - Added preserveState and preserveScroll options
  - Added error handling

- ✅ `app/Http/Middleware/HandleInertiaRequests.php`
  - Added `csrf_token` to shared props

- ✅ `resources/js/Pages/system-manager/system-security/Create.vue`
  - Already correct (sudah ada CSRF token)

---

## 🚨 **Common Issues & Solutions**

### **Issue 1: Masih Page Expired setelah fix**

**Solution:**
```bash
# Clear Laravel cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Restart development server
php artisan serve
```

---

### **Issue 2: CSRF token undefined di frontend**

**Check:**
1. ✅ Pastikan `HandleInertiaRequests.php` sudah benar
2. ✅ Reload halaman (hard refresh: Ctrl+F5)
3. ✅ Check console: `console.log(this.$page.props)`

**Debug:**
```javascript
// Tambahkan di mounted() atau created()
mounted() {
    console.log('CSRF Token:', this.$page.props.csrf_token);
    console.log('All Props:', this.$page.props);
}
```

---

### **Issue 3: Token expired untuk session lama**

**Solution:**
```php
// config/session.php
'lifetime' => 120, // Increase session lifetime (minutes)

// atau tambahkan middleware untuk refresh session
```

---

## 🔗 **Related Documentation**

- Laravel CSRF Protection: https://laravel.com/docs/10.x/csrf
- Inertia.js Forms: https://inertiajs.com/forms
- Laravel Session Configuration: https://laravel.com/docs/10.x/session

---

## ✅ **Summary**

**Penyebab:**
- CSRF token tidak disertakan dalam Inertia.js POST/PUT requests

**Solusi:**
- Tambahkan `_token` ke form data sebelum submit
- Gunakan `this.$page.props.csrf_token` atau fallback ke meta tag
- Tambahkan error handling untuk better debugging

**Status:**
- ✅ **FIXED** - AmendPassword.vue
- ✅ **FIXED** - Edit.vue
- ✅ **VERIFIED** - Create.vue (already correct)
- ✅ **ENHANCED** - HandleInertiaRequests.php middleware

**Impact:**
- ✅ User dapat update password tanpa error
- ✅ User dapat edit user data tanpa error
- ✅ Semua form submission lebih robust
- ✅ Better error handling untuk debugging

---

**End of Documentation** 📝

