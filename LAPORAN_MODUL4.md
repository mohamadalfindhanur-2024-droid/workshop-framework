# LAPORAN PRAKTIKUM — MODUL 4
**Mata Kuliah :** Workshop Framework  
**Modul       :** 4 — Interaktivitas UI dengan JavaScript  
**Tanggal     :** 9 Maret 2026  
**Framework   :** Laravel 11 · Bootstrap 5 · Purple Admin Template  

---

## DAFTAR ISI
1. [Proses Spinner / Loader pada Tombol Submit](#1-proses-spinner--loader-pada-tombol-submit)
2. [Proses Tambah Barang ke Tabel (Tanpa Database)](#2-proses-tambah-barang-ke-tabel-tanpa-database)
3. [Proses Update & Hapus Barang via Modal](#3-proses-update--hapus-barang-via-modal)
4. [Proses Select Kota Dinamis](#4-proses-select-kota-dinamis)
5. [Daftar File yang Digunakan](#5-daftar-file-yang-digunakan)
6. [Library Eksternal yang Digunakan](#6-library-eksternal-yang-digunakan)
7. [Struktur Alur Data Keseluruhan](#7-struktur-alur-data-keseluruhan)

---

## 1. Proses Spinner / Loader pada Tombol Submit

### Tujuan
Memberikan umpan balik visual kepada pengguna bahwa formulir sedang diproses, sekaligus memastikan semua field wajib telah diisi sebelum data dikirim.

### Alur Proses

```
① Pengguna mengisi form
         │
         ▼
② Pengguna klik tombol Submit / Simpan / Update
         │
         ▼
③ JavaScript menjalankan checkValidity()
         │
    ┌────┴────┐
  GAGAL      VALID
    │           │
    ▼           ▼
④ reportValidity()    ④ Teks tombol disembunyikan
  (muncul tooltip         Animasi spinner ditampilkan
   error di field          Tombol di-disable
   yang kosong)            │
                           ▼
                      ⑤ form.submit() dipanggil
                           │
                           ▼
                      ⑥ Data dikirim ke server (Laravel)
```

### Halaman yang Menggunakan Proses Ini

| Halaman | Tombol | Aksi Setelah Submit |
|---------|--------|---------------------|
| Tambah Buku | Simpan | Data buku tersimpan ke database |
| Edit Buku | Update | Data buku diperbarui di database |
| Tambah Kategori | Simpan | Data kategori tersimpan ke database |
| Edit Kategori | Update | Data kategori diperbarui di database |
| Login | SIGN IN | Proses autentikasi pengguna |
| Verifikasi OTP | VERIFIKASI | Validasi kode OTP 6 digit |
| Form Barang HTML Table | submit | Data tampil di tabel (tanpa database) |
| Form Barang DataTables | submit | Data tampil di DataTables (tanpa database) |
| Modal Ubah Barang | Ubah | Data baris tabel diperbarui |

---

## 2. Proses Tambah Barang ke Tabel (Tanpa Database)

### Tujuan
Menampilkan data yang diinput pengguna langsung ke tabel di halaman yang sama, tanpa menyimpan ke database. Tersedia dalam dua versi: tabel HTML biasa dan DataTables.

### Alur Proses

```
① Pengguna mengisi "Nama barang" dan "Harga barang"
         │
         ▼
② Klik tombol Submit
         │
         ▼
③ Validasi wajib: checkValidity() → reportValidity() jika gagal
         │
         ▼
④ Spinner ditampilkan (simulasi proses 500ms)
         │
         ▼
⑤ ID barang di-generate otomatis (BRG-001, BRG-002, ...)
         │
         ▼
⑥ Data ditambahkan ke tabel sesuai versi halaman
   ┌──────────────────┬──────────────────────────┐
   │   HTML Table     │       DataTables         │
   │   Buat elemen    │   dt.row.add([...])      │
   │   <tr> baru lalu │   .draw()                │
   │   append ke tbody│                          │
   └──────────────────┴──────────────────────────┘
         │
         ▼
⑦ Input dikosongkan, fokus kembali ke field Nama
⑧ Spinner disembunyikan, tombol aktif kembali
```

### Perbedaan HTML Table vs DataTables

| Fitur | HTML Table | DataTables |
|-------|-----------|------------|
| Pencarian data | Tidak ada | Ada (otomatis) |
| Pengurutan kolom | Tidak ada | Ada (klik header) |
| Paginasi | Tidak ada | Ada (otomatis) |
| Bahasa antarmuka | — | Bahasa Indonesia |
| Tambah baris | Manipulasi DOM langsung | Via DataTables API |
| Hapus baris | Manipulasi DOM langsung | Via DataTables API |
| Update baris | Manipulasi DOM langsung | Via DataTables API |

---

## 3. Proses Update & Hapus Barang via Modal

### Tujuan
Setiap baris data pada tabel dapat diedit atau dihapus melalui popup modal tanpa berpindah halaman.

### Alur Proses — Membuka Modal

```
① Pengguna mengarahkan kursor ke baris tabel
   → Kursor berubah menjadi pointer (CSS cursor: pointer)
         │
         ▼
② Pengguna klik salah satu baris
         │
         ▼
③ JavaScript membaca data tersimpan di atribut
   data-id, data-nama, data-harga milik elemen <tr>
         │
         ▼
④ Nilai tersebut dimasukkan ke field-field di dalam modal:
   - ID Barang   → readonly (tidak dapat diubah)
   - Nama Barang → dapat diedit, wajib diisi
   - Harga Barang→ dapat diedit, wajib diisi
         │
         ▼
⑤ Modal ditampilkan
```

### Alur Proses — Tombol Hapus

```
① Pengguna klik tombol "Hapus"
         │
         ▼
② Spinner ditampilkan pada tombol Hapus, tombol di-disable
         │
         ▼
③ Baris yang dipilih dihapus dari tabel
   (HTML Table: tr.remove() | DataTables: dtRow.remove().draw())
         │
         ▼
④ Modal ditutup otomatis
⑤ Spinner disembunyikan, tombol aktif kembali
```

### Alur Proses — Tombol Ubah

```
① Pengguna mengubah Nama / Harga di dalam modal
         │
         ▼
② Pengguna klik tombol "Ubah"
         │
         ▼
③ Validasi: checkValidity() → reportValidity() jika gagal
         │
         ▼
④ Spinner ditampilkan pada tombol Ubah, tombol di-disable
         │
         ▼
⑤ Data baris diperbarui sesuai input pengguna
   HTML Table  : isi <td> diperbarui langsung
   DataTables  : dtRow.data([...]).draw()
         │
         ▼
⑥ Atribut data-nama dan data-harga pada <tr> ikut diperbarui
   agar klik berikutnya tetap menampilkan data terbaru
         │
         ▼
⑦ Modal ditutup otomatis
⑧ Spinner disembunyikan, tombol aktif kembali
```

---

## 4. Proses Select Kota Dinamis

### Tujuan
Mendemonstrasikan cara menambah opsi pada elemen `<select>` secara dinamis menggunakan JavaScript, dalam dua versi: select HTML biasa dan Select2.

### Alur Proses — Menambah Kota

```
① Pengguna mengetik nama kota di field "Kota"
         │
         ▼
② Pengguna klik tombol "Tambahkan"
         │
         ▼
③ Nama kota dijadikan opsi baru pada elemen select
   Value opsi  = nama kota yang diinput
   Teks tampil = nama kota yang diinput
   ┌──────────────────┬────────────────────────────────┐
   │   Select Biasa   │          Select2               │
   │   Buat option    │   new Option(nama, nama)       │
   │   lewat DOM lalu │   → append ke select           │
   │   append         │   → trigger('change') Select2  │
   └──────────────────┴────────────────────────────────┘
         │
         ▼
④ Field input dikosongkan, fokus kembali ke input
```

### Alur Proses — Memilih Kota

```
① Pengguna memilih salah satu opsi dari dropdown
         │
         ▼
② Event "change" terpancar dari elemen select
         │
         ▼
③ JavaScript membaca nilai yang sedang dipilih
         │
         ▼
④ Teks "Kota Terpilih" diperbarui secara real-time
```

### Perbedaan Select Biasa vs Select2

| Fitur | Select Biasa | Select2 |
|-------|-------------|---------|
| Tampilan | Default browser | Modern dan kustom |
| Pencarian opsi | Tidak ada | Ada (ketik untuk filter) |
| Tombol hapus pilihan | Tidak ada | Ada (ikon × di kanan) |
| Placeholder | Terbatas | Fleksibel |

---

## 5. Daftar File yang Digunakan

### File Routes
| File | Keterangan |
|------|-----------|
| `routes/web.php` | Mendaftarkan semua URL dan mengarahkan ke controller |

### File Controllers
| File | Method yang Digunakan | Keterangan |
|------|-----------------------|-----------|
| `app/Http/Controllers/BukuController.php` | `create()`, `store()`, `edit()`, `update()` | Menangani CRUD buku |
| `app/Http/Controllers/KategoriController.php` | `create()`, `store()`, `edit()`, `update()` | Menangani CRUD kategori |
| `app/Http/Controllers/Auth/GoogleController.php` | `showOTPForm()`, `verifyOTP()` | Menangani verifikasi OTP |
| `app/Http/Controllers/BarangController.php` | `formHtml()`, `formDatatable()` | Menampilkan halaman form barang |

### File Views — Layout
| File | Keterangan |
|------|-----------|
| `resources/views/layouts/admin.blade.php` | Template utama: sidebar navigasi, navbar, area konten, yield CSS & JS per halaman |

### File Views — Buku
| File | URL Akses | Keterangan |
|------|-----------|-----------|
| `resources/views/buku/create.blade.php` | `/buku/create` | Form tambah buku dengan spinner |
| `resources/views/buku/edit.blade.php` | `/buku/{id}/edit` | Form edit buku dengan spinner |

### File Views — Kategori
| File | URL Akses | Keterangan |
|------|-----------|-----------|
| `resources/views/kategori/create.blade.php` | `/kategori/create` | Form tambah kategori dengan spinner |
| `resources/views/kategori/edit.blade.php` | `/kategori/{id}/edit` | Form edit kategori dengan spinner |

### File Views — Autentikasi
| File | URL Akses | Keterangan |
|------|-----------|-----------|
| `resources/views/auth/login.blade.php` | `/login` | Halaman login dengan spinner |
| `resources/views/auth/verify-otp.blade.php` | `/auth/verify-otp` | Halaman verifikasi OTP dengan spinner |

### File Views — Barang
| File | URL Akses | Keterangan |
|------|-----------|-----------|
| `resources/views/barang/index.blade.php` | `/barang` | Data barang dari DB & cetak label PDF |
| `resources/views/barang/form-html.blade.php` | `/barang/form-html` | Form input + HTML table + modal edit/hapus |
| `resources/views/barang/form-datatable.blade.php` | `/barang/form-datatable` | Form input + DataTables + modal edit/hapus |

### File Views — Kota
| File | URL Akses | Keterangan |
|------|-----------|-----------|
| `resources/views/kota/index.blade.php` | `/kota` | Halaman select kota (select biasa & Select2) |

---

## 6. Library Eksternal yang Digunakan

| Library | Versi | Digunakan di Halaman | Cara Load |
|---------|-------|---------------------|-----------|
| Bootstrap Spinner | Bawaan Bootstrap 5 | Semua form | Purple Admin Template |
| Bootstrap Modal | Bawaan Bootstrap 5 | Form Barang (HTML & DT) | Purple Admin Template |
| DataTables | 1.13.6 | Form Barang DataTables | CDN |
| DataTables Bootstrap4 | 1.13.6 | Form Barang DataTables | CDN |
| Select2 | 4.1.0-rc.0 | Select Kota | CDN |

---

## 7. Struktur Alur Data Keseluruhan

```
Pengguna (Browser)
       │
       │  HTTP Request (GET/POST)
       ▼
routes/web.php
       │
       │  Diteruskan ke Controller
       ▼
Controller
       │
       │  return view(...)
       ▼
View (.blade.php)
  ├── extends layouts/admin.blade.php
  │     └── sidebar, navbar, footer, JS global
  │
  ├── @section('content')      → konten utama halaman
  ├── @section('style_page')   → CSS khusus halaman
  └── @section('javascript_page') → JS khusus halaman
       │
       │  HTML dikirim ke browser
       ▼
Browser menampilkan halaman
       │
       ├── [Form CRUD & Login & OTP]
       │   JavaScript berjalan:
       │   checkValidity → reportValidity → spinner → form.submit()
       │                                                    │
       │                                              Kembali ke server
       │                                              Data disimpan ke
       │                                              PostgreSQL
       │
       └── [Form Barang & Kota]
           JavaScript berjalan sepenuhnya di browser:
           - Tambah / ubah / hapus baris tabel
           - Buka / tutup modal
           - Tambah opsi select dinamis
           TIDAK ada komunikasi ke server
           TIDAK ada data tersimpan ke database
```

---

## DAFTAR ISI
1. [Spinner / Loader pada Tombol Submit](#1-spinner--loader-pada-tombol-submit)
2. [Halaman Form Barang — HTML Table](#2-halaman-form-barang--html-table)
3. [Halaman Form Barang — DataTables](#3-halaman-form-barang--datatables)
4. [Operasi Update & Hapus via Modal](#4-operasi-update--hapus-via-modal)
5. [Halaman Select Kota](#5-halaman-select-kota)
6. [Navigasi Sidebar](#6-navigasi-sidebar)

---

## 1. Spinner / Loader pada Tombol Submit

### Deskripsi
Setiap tombol submit pada form CRUD diubah agar menampilkan animasi spinner ketika diklik, sekaligus memvalidasi input sebelum data dikirim ke server.

### Alur Kerja

```
User klik tombol
      │
      ▼
checkValidity()  ──── GAGAL ───▶  reportValidity()  (tampil pesan error HTML5)
      │
    VALID
      │
      ▼
Tampilkan spinner · Disable tombol
      │
      ▼
form.submit()  ──▶  Server (Laravel)
```

### Perubahan pada Kode

Sebelum (original):
```html
<button type="submit" class="btn btn-gradient-primary me-2">
    <i class="mdi mdi-content-save"></i> Simpan
</button>
```

Sesudah (modul 4):
```html
<!-- Tombol dipindah ke LUAR tag <form> -->
<button type="button" id="btn-simpan" class="btn btn-gradient-primary me-2">
    <span id="btn-simpan-text">
        <i class="mdi mdi-content-save"></i> Simpan
    </span>
    <span id="btn-simpan-spinner" class="d-none">
        <span class="spinner-border spinner-border-sm" role="status"></span>
        Menyimpan...
    </span>
</button>
```

JavaScript yang ditambahkan:
```javascript
document.getElementById('btn-simpan').addEventListener('click', function () {
    const form = document.getElementById('form-buku-create');

    // Validasi HTML5 native
    if (!form.checkValidity()) {
        form.reportValidity();   // tampil tooltip error di field yang kosong
        return;
    }

    // Tampilkan spinner, nonaktifkan tombol
    document.getElementById('btn-simpan-text').classList.add('d-none');
    document.getElementById('btn-simpan-spinner').classList.remove('d-none');
    this.disabled = true;

    form.submit();   // kirim ke server
});
```

### File yang Diubah

| No | File | Tombol |
|----|------|--------|
| 1 | `resources/views/buku/create.blade.php` | Simpan |
| 2 | `resources/views/buku/edit.blade.php` | Update |
| 3 | `resources/views/kategori/create.blade.php` | Simpan |
| 4 | `resources/views/kategori/edit.blade.php` | Update |
| 5 | `resources/views/auth/login.blade.php` | SIGN IN |
| 6 | `resources/views/auth/verify-otp.blade.php` | VERIFIKASI |

> **Catatan:** Form `buku` dan `kategori` menggunakan `@section('javascript_page')` yang di-yield oleh layout admin. Form `login` dan `verify-otp` menggunakan tag `<script>` inline karena merupakan halaman standalone (tidak extends layout admin).

---

## 2. Halaman Form Barang — HTML Table

### Deskripsi
Halaman baru yang menampilkan form input barang dan tabel HTML biasa. Data **tidak tersimpan ke database** — semua diproses di sisi client (JavaScript).

### URL & File

| Keterangan | Detail |
|------------|--------|
| URL | `/barang/form-html` |
| Route name | `barang.form-html` |
| View | `resources/views/barang/form-html.blade.php` |
| Controller method | `BarangController::formHtml()` |

### Fitur

| Fitur | Implementasi |
|-------|-------------|
| Input Nama Barang | `required`, dikosongkan setelah submit |
| Input Harga Barang | `required`, `type="number"`, `min="0"` |
| Tombol Submit | Spinner (ketentuan nomor 1) |
| ID Barang | Auto-generate berurutan: `BRG-001`, `BRG-002`, ... |
| Tambah ke tabel | `document.createElement('tr')` + `tbody.appendChild(tr)` |
| Data persistence | ❌ Tidak disimpan ke DB |

### Tampilan Tabel

| ID Barang | Nama | Harga |
|-----------|------|-------|
| BRG-001 | (input user) | Rp (input user) |
| BRG-002 | ... | ... |

---

## 3. Halaman Form Barang — DataTables

### Deskripsi
Halaman identik dengan halaman HTML Table, namun tabel menggunakan library **DataTables** sehingga memiliki fitur pencarian, pengurutan kolom, dan paginasi otomatis.

### URL & File

| Keterangan | Detail |
|------------|--------|
| URL | `/barang/form-datatable` |
| Route name | `barang.form-datatable` |
| View | `resources/views/barang/form-datatable.blade.php` |
| Controller method | `BarangController::formDatatable()` |
| Library | DataTables 1.13.6 (CDN) |

### Perbedaan dengan HTML Table

| Aspek | HTML Table | DataTables |
|-------|-----------|------------|
| Tambah row | `tbody.appendChild(tr)` | `dt.row.add([...]).draw()` |
| Hapus row | `tr.remove()` | `dtRow.remove().draw()` |
| Update row | Update `td` langsung | `dtRow.data([...]).draw()` |
| Search | ❌ Tidak ada | ✅ Otomatis |
| Sort kolom | ❌ Tidak ada | ✅ Otomatis |
| Paginasi | ❌ Tidak ada | ✅ Otomatis |
| Bahasa | — | Indonesian (CDN i18n) |

---

## 4. Operasi Update & Hapus via Modal

### Deskripsi
Setiap row pada tabel (di kedua halaman) dapat diklik untuk membuka modal yang berisi form edit dan tombol hapus.

### Alur Kerja

```
Hover row  →  cursor berubah jadi pointer  (CSS)
     │
Klik row   →  Modal muncul dengan data row terisi otomatis
                    ├── ID Barang  : readonly (tidak bisa diubah)
                    ├── Nama       : required
                    └── Harga      : required
                              │
              ┌───────────────┴───────────────┐
         Klik HAPUS                       Klik UBAH
              │                               │
         Spinner                          checkValidity()
              │                               │
         row.remove()                    Spinner
              │                               │
         Modal tutup                     Update data row
                                              │
                                         Modal tutup
```

### Struktur Modal

```html
<div class="modal fade" id="modal-barang-html">
  <div class="modal-body">
    <form id="form-modal-html">
      <input id="modal-id-html"    readonly>   <!-- ID: tidak bisa diubah -->
      <input id="modal-nama-html"  required>   <!-- Nama: wajib diisi     -->
      <input id="modal-harga-html" required>   <!-- Harga: wajib diisi    -->
    </form>
  </div>
  <div class="modal-footer d-flex justify-content-between">
    <button id="btn-hapus-html" class="btn btn-gradient-danger">  Hapus  </button>
    <button id="btn-ubah-html"  class="btn btn-gradient-success"> Ubah   </button>
  </div>
</div>
```

### Teknik Penyimpanan Data pada Row

Setiap row `<tr>` menyimpan data asli menggunakan `data-*` attributes:
```html
<tr data-id="BRG-001" data-nama="Kopi" data-harga="15000">
    <td>BRG-001</td>
    <td>Kopi</td>
    <td>Rp 15.000</td>
</tr>
```
Saat row diklik, nilai `data-*` dibaca dan dimasukkan ke input modal. Setelah ubah berhasil, `data-*` diperbarui agar klik berikutnya tetap menampilkan data terbaru.

---

## 5. Halaman Select Kota

### Deskripsi
Halaman baru dengan 2 card yang mendemonstrasikan penggunaan elemen `<select>` HTML biasa vs library **Select2**.

### URL & File

| Keterangan | Detail |
|------------|--------|
| URL | `/kota` |
| Route name | `kota.index` |
| View | `resources/views/kota/index.blade.php` |
| Library Select2 | select2 4.1.0 (CDN) |

### Card 1 — "Select" (HTML Biasa)

```
[Input: Kota]          [Tambahkan]
[<select> ───────────────────────]
Kota Terpilih: Jakarta
```

- Klik **Tambahkan** → `document.createElement('option')` ditambahkan ke `<select>`
- Pilih opsi → `select.addEventListener('change', ...)` → update teks "Kota Terpilih"

### Card 2 — "select 2" (Select2)

```
[Input: Kota]          [Tambahkan]
[Select2: Pilih kota... ▼ ×      ]
Kota Terpilih: Surabaya
```

- Klik **Tambahkan** → `new Option(nama, nama)` → `$('#select').append(option).trigger('change')`
- Select2 aktif via `$('#select-kota-2').select2({...})`
- Fitur tambahan: **search** (filter opsi) dan **clear** (hapus pilihan)

### Perbandingan Select Biasa vs Select2

| Fitur | Select Biasa | Select2 |
|-------|-------------|---------|
| Tampilan | Default browser | Kustom, lebih modern |
| Search opsi | ❌ | ✅ |
| Clear pilihan | ❌ | ✅ |
| Placeholder | Terbatas | ✅ `placeholder` option |
| Customizable | Minim | Sangat fleksibel |

---

## 6. Navigasi Sidebar

### Perubahan pada `resources/views/layouts/admin.blade.php`

Menu **Barang** diubah dari link tunggal menjadi **dropdown** dengan 3 sub-menu:

```
▼ Barang
    ├── Cetak Label        →  /barang
    ├── Form HTML Table    →  /barang/form-html
    └── Form DataTables    →  /barang/form-datatable
```

Menu **Kota** ditambahkan sebagai item baru di sidebar:
```
  Kota   🗺️   →  /kota
```

---

## RINGKASAN FILE BARU & YANG DIUBAH

### File Baru
| File | Keterangan |
|------|-----------|
| `resources/views/barang/form-html.blade.php` | Halaman form barang + HTML table |
| `resources/views/barang/form-datatable.blade.php` | Halaman form barang + DataTables |
| `resources/views/kota/index.blade.php` | Halaman select kota (select biasa + select2) |

### File yang Diubah
| File | Perubahan |
|------|-----------|
| `resources/views/buku/create.blade.php` | Spinner pada tombol Simpan |
| `resources/views/buku/edit.blade.php` | Spinner pada tombol Update |
| `resources/views/kategori/create.blade.php` | Spinner pada tombol Simpan |
| `resources/views/kategori/edit.blade.php` | Spinner pada tombol Update |
| `resources/views/auth/login.blade.php` | Spinner pada tombol SIGN IN |
| `resources/views/auth/verify-otp.blade.php` | Spinner pada tombol VERIFIKASI |
| `resources/views/layouts/admin.blade.php` | Dropdown Barang + menu Kota di sidebar |
| `app/Http/Controllers/BarangController.php` | Tambah method `formHtml()` & `formDatatable()` |
| `routes/web.php` | Tambah 3 route baru (form-html, form-datatable, kota) |

---

## TEKNOLOGI YANG DIGUNAKAN

| Teknologi | Versi | Kegunaan |
|-----------|-------|---------|
| Laravel | 11.x | Backend framework |
| Bootstrap | 5.x | Komponen UI (spinner, modal, card) |
| DataTables | 1.13.6 | Tabel interaktif |
| Select2 | 4.1.0 | Dropdown kustom |
| HTML5 Constraint Validation API | — | `checkValidity()` & `reportValidity()` |
| Vanilla JavaScript | ES6+ | Logika interaktivitas |
| jQuery | (bundled) | DataTables & Select2 dependency |
