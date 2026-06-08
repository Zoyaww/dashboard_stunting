# 📊 Alur Rute WebGIS Application

## 🗺️ Diagram Alur Aplikasi

```
┌─────────────────────────────────────────────────────────────────────┐
│                        HALAMAN UTAMA (/)                            │
│                                                                     │
│  ┌─ Belum Login ─────────┐        ┌─ Sudah Login ─────────┐       │
│  │                       │        │                       │       │
│  │  • Map View           │        │  • Map View           │       │
│  │  • Tombol Login       │        │  • User Info (Navbar) │       │
│  │  • Statistik Demo     │        │  • Logout Button      │       │
│  │                       │        │  • Dark Mode Toggle   │       │
│  └───────────────────────┘        └───────────────────────┘       │
│           │                                  │                      │
│           │ Klik "Login"                     │ Akses Terbatas      │
│           ▼                                  ▼                      │
└─────────────────────────────────────────────────────────────────────┘
           │
           │
           ▼
┌─────────────────────────────────────────────────────────────────────┐
│                    HALAMAN LOGIN (/login)                           │
│                                                                     │
│  • Form Username & Password                                        │
│  • Demo Credentials:                                               │
│    - admin / admin123                                              │
│    - user / user123                                                │
│  • Dark Mode Toggle                                                │
│  • Error Message (jika gagal)                                      │
└─────────────────────────────────────────────────────────────────────┘
           │
           │ Submit Form (POST /login)
           ▼
     ┌──────────────┐
     │ Validasi     │
     │ Credentials  │
     └──────────────┘
           │
        ┌──┴──┐
        │     │
    ✓   │     │   ✗
        ▼     ▼
    SUCCESS  ERROR
        │     │
        │     └──► Tampilkan Error Message
        │          (Redirect ke /login)
        │
        ▼
┌─────────────────────────────────────────────────────────────────────┐
│                   HALAMAN DASHBOARD (/)                             │
│                                                                     │
│  ✓ Session Aktif                                                   │
│  ✓ User Info Ditampilkan                                           │
│  ✓ Logout Button Aktif                                             │
│                                                                     │
│  Konten:                                                           │
│  • Interactive Map (Leaflet)                                       │
│  • Statistik Lokasi                                                │
│  • Daftar Lokasi (4 sampel)                                        │
│  • Button "Kelola Lokasi" (ke Admin)                               │
│  • Dark Mode Support                                               │
└─────────────────────────────────────────────────────────────────────┘
           │
       ┌───┴───┬──────────────┐
       │       │              │
    Logout  Kelola Lokasi  Dark Mode
       │       │              │
       ▼       ▼              │
   /logout  /admin            │
       │       │              │
       │       ▼              │
       │  ┌─────────────────────────────────────────────────────────┐
       │  │       ADMIN DASHBOARD (/admin)                         │
       │  │                                                        │
       │  │  • Statistik:                                          │
       │  │    - Total Lokasi                                      │
       │  │    - Lokasi Aktif                                      │
       │  │    - Jumlah Kategori                                   │
       │  │                                                        │
       │  │  • Menu CRUD Manajemen Lokasi                          │
       │  │    └─► Tombol: "+ Tambah Lokasi Baru"                 │
       │  └─────────────────────────────────────────────────────────┘
       │       │
       │       ▼
       │  ┌─────────────────────────────────────────────────────────┐
       │  │    DAFTAR LOKASI (/admin/locations)                   │
       │  │                                                        │
       │  │  • Tabel Lokasi dengan kolom:                          │
       │  │    - No, Nama, Kategori, Koordinat, Status, Aksi      │
       │  │  • Pagination (10 per halaman)                         │
       │  │  • Tombol per baris: [Edit] [Hapus]                    │
       │  │  • Tombol Header: "+ Tambah Lokasi Baru"              │
       │  └─────────────────────────────────────────────────────────┘
       │       │
       │   ┌───┴──────────┬──────────────┐
       │   │              │              │
       │   ▼              ▼              ▼
       │  Tambah        Edit           Hapus
       │   │              │              │
       │   ▼              ▼              ▼
       │  ┌──────────────────────┐   Konfirmasi
       │  │ CREATE FORM          │   Delete
       │  │ (/admin/locations/   │   │
       │  │  create)             │   │
       │  │                      │   ▼
       │  │ • Nama Lokasi*       │  DELETE
       │  │ • Deskripsi          │  Lokasi
       │  │ • Kategori*          │   │
       │  │ • Status*            │   │
       │  │ • Latitude*          │   ▼
       │  │ • Longitude*         │  Redirect ke
       │  │                      │  /admin/
       │  │ [Simpan] [Batal]     │  locations
       │  └──────────────────────┘   │
       │   │                         │
       │   ▼                         │
       │  POST /admin/locations      │
       │   │                         │
       │   ▼                         │
       │  Validasi & Create          │
       │   │                         │
       │   └─────────┬───────────────┘
       │             │
       │             ▼
       │  ┌──────────────────────┐
       │  │ EDIT FORM            │
       │  │ (/admin/locations/   │
       │  │  {id}/edit)          │
       │  │                      │
       │  │ [Sama seperti Create]│
       │  │ tapi pre-filled data │
       │  │                      │
       │  │ [Simpan] [Batal]     │
       │  └──────────────────────┘
       │   │
       │   ▼
       │  PUT /admin/locations/{id}
       │   │
       │   ▼
       │  Validasi & Update
       │   │
       │   └──► Redirect ke /admin/locations
       │        dengan success message
       │
       ▼
    ┌─────────────────────────────────┐
    │  SESSION CLEARED                │
    │  (/logout)                      │
    └─────────────────────────────────┘
           │
           ▼
    Redirect ke /login
```

---

## 📋 Detail Rute

### **1. Halaman Utama**
```
Route: GET /
Controller: DashboardController@index
View: dashboard.blade.php
Features:
  - Map Leaflet (OSM) - Kabupaten Tanah Laut
  - Authentication Check (Session)
  - Redirect ke /login jika belum login
  - Navbar dengan User Info & Logout
  - Dark Mode Toggle
```

### **2. Login**
```
Route: GET /login
Controller: LoginController@showLogin
View: login.blade.php
Features:
  - Form Username & Password
  - Validasi Credentials
  - Session Management
  - Error Messages
```

```
Route: POST /login
Controller: LoginController@login
Logic:
  1. Validate input
  2. Check credentials vs hardcoded list
  3. Set session('user')
  4. Redirect ke / (dashboard)
```

### **3. Logout**
```
Route: GET /logout
Controller: LoginController@logout
Logic:
  1. Clear session('user')
  2. Redirect ke /login
```

### **4. Admin Dashboard**
```
Route: GET /admin
Controller: AdminController@dashboard
View: admin_dashboard.blade.php
Features:
  - Session Check (redirect ke /login jika tidak ada)
  - Statistik: Total Lokasi, Aktif, Kategori
  - Menu CRUD
```

### **5. Admin - Daftar Lokasi**
```
Route: GET /admin/locations
Controller: AdminController@index
View: admin_locations.blade.php
Features:
  - Pagination (10 per halaman)
  - Tabel dengan data lokasi
  - Tombol Edit & Hapus per baris
  - Tombol Tambah Baru
```

### **6. Admin - Tambah Lokasi**
```
Route: GET /admin/locations/create
Controller: AdminController@create
View: admin_create.blade.php
Features:
  - Form kosong dengan default values
  - Validasi client & server side
  - CSRF Protection
```

```
Route: POST /admin/locations
Controller: AdminController@store
Logic:
  1. Validasi data
  2. Create Location record
  3. Redirect ke /admin/locations dengan success message
```

### **7. Admin - Edit Lokasi**
```
Route: GET /admin/locations/{location}/edit
Controller: AdminController@edit
View: admin_edit.blade.php
Model Binding: Location
Features:
  - Form pre-filled dengan data existing
  - Validasi sama seperti Create
```

```
Route: PUT /admin/locations/{location}
Controller: AdminController@update
Logic:
  1. Validasi data
  2. Update Location record
  3. Redirect ke /admin/locations dengan success message
```

### **8. Admin - Hapus Lokasi**
```
Route: DELETE /admin/locations/{location}
Controller: AdminController@destroy
Method: Form submission (DELETE method)
Logic:
  1. Confirm dialog di browser
  2. Delete Location record
  3. Redirect ke /admin/locations dengan success message
```

---

## 🔐 Security Flow

```
Request User
    ▼
Cek Route
    ▼
Route Handler / Controller
    ▼
Validasi Session (untuk route terlindungi)
    │
    ├─ Ada Session ──► Lanjut Proses
    │
    └─ Tidak Ada ────► Redirect /login

Proses Request
    ▼
Validasi Input
    ▼
Database Operation
    ▼
Response (View / Redirect)
```

---

## 🎯 User Journey

### **Scenario 1: New User (Belum Login)**
```
1. Akses http://localhost/
   ↓
2. Redirect ke /login (karena tidak ada session)
   ↓
3. Input Credentials: admin / admin123
   ↓
4. Submit Form (POST /login)
   ↓
5. ✓ Valid → Set session → Redirect ke /
   ↓
6. Dashboard ditampilkan dengan user info
```

### **Scenario 2: Create New Location**
```
1. Login berhasil → Di Dashboard
   ↓
2. Klik "Kelola Lokasi" → /admin
   ↓
3. Lihat statistik & menu
   ↓
4. Klik "+ Tambah Lokasi" → /admin/locations/create
   ↓
5. Isi form:
   - Nama: "Kantor Bupati"
   - Kategori: "Gedung"
   - Status: "Aktif"
   - Latitude: -3.75
   - Longitude: 115.00
   ↓
6. Klik "Simpan" → POST /admin/locations
   ↓
7. ✓ Valid → Create Record → Redirect /admin/locations
   ↓
8. Success message ditampilkan
   ↓
9. Data baru ada di tabel
```

### **Scenario 3: Edit Location**
```
1. Di /admin/locations (daftar lokasi)
   ↓
2. Klik "Edit" pada baris lokasi
   ↓
3. Form pre-filled dengan data existing → /admin/locations/{id}/edit
   ↓
4. Ubah data (misal: Kategori jadi "Landmark")
   ↓
5. Klik "Simpan Perubahan" → PUT /admin/locations/{id}
   ↓
6. ✓ Valid → Update Record → Redirect /admin/locations
   ↓
7. Success message & data terupdate di tabel
```

### **Scenario 4: Delete Location**
```
1. Di /admin/locations (daftar lokasi)
   ↓
2. Klik "Hapus" pada baris lokasi
   ↓
3. Confirm dialog: "Yakin hapus?"
   ↓
4. Klik OK → DELETE /admin/locations/{id}
   ↓
5. Record deleted
   ↓
6. Redirect /admin/locations
   ↓
7. Success message & data hilang dari tabel
```

### **Scenario 5: Logout**
```
1. Logged in → Di Dashboard / Admin Area
   ↓
2. Klik "Logout" button (di navbar)
   ↓
3. GET /logout
   ↓
4. Clear session → Redirect /login
   ↓
5. Akses Dashboard/Admin akan redirect ke /login lagi
```

---

## 💾 Database Flow

```
Locations Table
├── id (Primary Key)
├── name (required)
├── description (optional)
├── latitude (required, -90 to 90)
├── longitude (required, -180 to 180)
├── category (required)
├── status (active / inactive)
├── created_at
└── updated_at

Sample Data:
- Pelaihari (Landmark, Active)
- Tanah Laut (Gedung, Active)
- Taman Wisata Lokasi (Taman, Active)
- Infrastruktur Jalan (Infrastruktur, Active)
```

---

## 🌐 Akses URL Lengkap

| Rute | Method | Controller | Akses |
|------|--------|-----------|-------|
| `/` | GET | DashboardController@index | Public (redirect if not logged) |
| `/login` | GET | LoginController@showLogin | Public |
| `/login` | POST | LoginController@login | Public |
| `/logout` | GET | LoginController@logout | Auth Required |
| `/admin` | GET | AdminController@dashboard | Auth Required |
| `/admin/locations` | GET | AdminController@index | Auth Required |
| `/admin/locations/create` | GET | AdminController@create | Auth Required |
| `/admin/locations` | POST | AdminController@store | Auth Required |
| `/admin/locations/{id}/edit` | GET | AdminController@edit | Auth Required |
| `/admin/locations/{id}` | PUT | AdminController@update | Auth Required |
| `/admin/locations/{id}` | DELETE | AdminController@destroy | Auth Required |

---

## 🔑 Demo Credentials

```
Username: admin
Password: admin123

Username: user
Password: user123
```

---

Semua rute sudah terkonfigurasi dan siap digunakan! 🚀
