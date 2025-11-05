# 🔒 CSRF Token URL Security Fix

## ⚠️ MASALAH KEAMANAN

### Issue yang Ditemukan:
CSRF token muncul di URL query string saat navigasi:
```
❌ BAD: http://127.0.0.1:8000/cancel-active-invoice?_token=Qeyxi5pIGvPv1AfxWr5XvJFM9pSYdTOGbqwv9ePq
```

### Dampak Keamanan:
1. **Browser History Exposure** - Token tersimpan di browser history
2. **Referer Header Leaks** - Token bisa ter-expose via HTTP Referer
3. **Server Log Exposure** - Token tercatat di web server access logs
4. **Bookmark Risk** - User bisa bookmark URL dengan token
5. **URL Sharing Risk** - Token ter-share saat copy-paste URL
6. **CSRF Attack Vector** - Membuka celah untuk CSRF attacks

---

## ✅ SOLUSI YANG DIIMPLEMENTASIKAN

### File yang Diperbaiki:
**`resources/js/csrf-handler.js`**

### Perubahan Kode:

#### **BEFORE (Vulnerable):**
```javascript
// ❌ SECURITY ISSUE: Token ditambahkan ke SEMUA requests (termasuk GET)
router.on('before', (event) => {
    const { visit } = event.detail;
    
    // Add fresh CSRF token to all requests
    if (visit.data && typeof visit.data === 'object') {
        const csrfToken = getFreshCsrfToken();
        if (csrfToken) {
            visit.data._token = csrfToken;  // ❌ Masuk ke URL di GET request
        }
    }
});
```

**Problem**: Handler ini menambahkan `_token` ke **semua requests** tanpa memeriksa HTTP method, sehingga GET requests juga mendapat token yang akhirnya muncul di URL query string.

#### **AFTER (Secure):**
```javascript
// ✅ SECURITY FIX: Token HANYA untuk POST/PUT/PATCH/DELETE
router.on('before', (event) => {
    const { visit } = event.detail;
    
    // ✅ SECURITY FIX: Only add CSRF token to POST/PUT/PATCH/DELETE requests
    // GET requests should NOT have CSRF token to prevent token exposure in URL
    const isStateMutatingMethod = ['post', 'put', 'patch', 'delete'].includes(
        (visit.method || 'get').toLowerCase()
    );
    
    if (isStateMutatingMethod && visit.data && typeof visit.data === 'object') {
        const csrfToken = getFreshCsrfToken();
        if (csrfToken) {
            visit.data._token = csrfToken;  // ✅ Hanya untuk state-mutating methods
        }
    }
});
```

**Solution**: Token hanya ditambahkan ke **state-mutating HTTP methods** (POST, PUT, PATCH, DELETE) yang memang membutuhkan CSRF protection. GET requests tidak mendapat token.

---

## 📋 BEST PRACTICES CSRF TOKEN

### ✅ DO (Yang Benar):

#### 1. **POST/PUT/PATCH/DELETE Requests - Token di Body atau Header**
```javascript
// ✅ Option 1: Token di request body
axios.post('/api/invoices/cancel', {
    invoice_number: 'xxx',
    cancel_reason: 'xxx',
    _token: csrfToken
});

// ✅ Option 2: Token di header (BEST PRACTICE)
axios.post('/api/invoices/cancel', {
    invoice_number: 'xxx',
    cancel_reason: 'xxx'
}, {
    headers: {
        'X-CSRF-TOKEN': csrfToken
    }
});

// ✅ Option 3: Inertia POST (token otomatis di body)
router.post('/api/invoices/cancel', {
    invoice_number: 'xxx',
    cancel_reason: 'xxx'
});
```

#### 2. **GET Requests - NO TOKEN**
```javascript
// ✅ BENAR: GET tanpa token
router.get('/cancel-active-invoice');

// ✅ BENAR: GET dengan query params (bukan token)
router.get('/cancel-active-invoice', {
    invoice_no: 'xxx',  // ✅ OK
    period: '10-2025'   // ✅ OK
    // _token: xxx      // ❌ JANGAN!
});

// ✅ BENAR: Axios GET
axios.get('/api/invoices/xxx');
```

#### 3. **Form Submissions**
```html
<!-- ✅ BENAR: POST form dengan CSRF -->
<form method="POST" action="/invoices/cancel">
    @csrf
    <input type="text" name="invoice_number">
    <button type="submit">Cancel</button>
</form>

<!-- ✅ BENAR: GET form TANPA CSRF -->
<form method="GET" action="/invoices/search">
    <!-- NO @csrf here -->
    <input type="text" name="query">
    <button type="submit">Search</button>
</form>
```

### ❌ DON'T (Yang Salah):

```javascript
// ❌ SALAH: Token di URL query string
router.get('/page?_token=xxx');

// ❌ SALAH: Token di GET request data
router.get('/page', { _token: token });

// ❌ SALAH: Form GET dengan CSRF
<form method="GET" action="/search">
    @csrf  <!-- ❌ Token akan muncul di URL -->
</form>

// ❌ SALAH: Manual append token ke URL
const url = `/page?_token=${token}`;
```

---

## 🔍 TESTING

### Test Case 1: GET Navigation (Should NOT have token in URL)
```
Before Fix: /cancel-active-invoice?_token=xxx  ❌
After Fix:  /cancel-active-invoice             ✅
```

### Test Case 2: POST Request (Should have token in body/header)
```javascript
// Request payload
{
    invoice_number: "xxx",
    cancel_reason: "xxx",
    _token: "xxx"  // ✅ OK in POST body
}
```

### Test Case 3: Browser History Check
```
Before Fix: History contains URLs with ?_token=xxx  ❌
After Fix:  History contains clean URLs            ✅
```

---

## 🛡️ SECURITY CHECKLIST

- [x] ✅ Token TIDAK muncul di URL untuk GET requests
- [x] ✅ Token tetap ada di POST/PUT/PATCH/DELETE request bodies
- [x] ✅ Browser history bersih dari sensitive tokens
- [x] ✅ Server logs tidak mencatat token di URL
- [x] ✅ Bookmarks tidak mengandung token
- [x] ✅ URL bisa di-share dengan aman
- [x] ✅ CSRF protection tetap aktif untuk state-mutating operations

---

## 🎯 IMPACT

### Security Improvements:
1. **No Token Exposure in URLs** - Token tidak lagi muncul di URL
2. **Clean Browser History** - History tidak mengandung sensitive data
3. **Safe URL Sharing** - URL bisa di-share tanpa risiko
4. **Reduced Attack Surface** - Mengurangi celah CSRF attack
5. **Compliance Ready** - Sesuai dengan security best practices

### Backward Compatibility:
- ✅ Tidak ada breaking changes
- ✅ POST/PUT/PATCH/DELETE tetap protected
- ✅ GET requests tetap berfungsi normal
- ✅ Existing code tidak perlu diubah

---

## 📚 REFERENCES

### OWASP Guidelines:
- CSRF tokens should NEVER be transmitted in URLs
- CSRF tokens should be in POST body or custom headers
- GET requests should be idempotent and not require CSRF protection

### Laravel Documentation:
- CSRF protection only applies to state-changing operations
- GET, HEAD, OPTIONS requests are excluded from CSRF verification
- Token should be sent via `_token` parameter or `X-CSRF-TOKEN` header

---

## ✅ CONCLUSION

Security fix telah berhasil diimplementasikan untuk mencegah CSRF token exposure di URL. Sistem sekarang lebih aman dan sesuai dengan industry security standards.

**Status**: ✅ **FIXED & VERIFIED**

**Date**: November 2025
**Priority**: 🔴 HIGH (Security Issue)
**Impact**: All GET navigation requests

