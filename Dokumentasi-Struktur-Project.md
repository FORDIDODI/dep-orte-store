# Dokumentasi Lengkap Struktur Project Deporte Store

## Daftar Isi
1. [Overview Arsitektur](#overview-arsitektur)
2. [Routing System](#routing-system)
3. [Models (Data Layer)](#models-data-layer)
4. [Controllers (Business Logic)](#controllers-business-logic)
5. [Views (Presentation Layer)](#views-presentation-layer)
6. [Filters (Security & Access Control)](#filters-security--access-control)
7. [Database Schema](#database-schema)
8. [Alur Proses Bisnis](#alur-proses-bisnis)

---

## Overview Arsitektur

### Teknologi Stack
- **Framework**: CodeIgniter 4 (MVC Pattern)
- **Frontend**: TailwindCSS (via CDN), Font Awesome Icons
- **Database**: MySQL
- **PHP Version**: 8.0+
- **Session Management**: Native PHP Session

### Struktur Direktori Utama
- `app/Controllers/` - Semua controller untuk menangani request HTTP
- `app/Models/` - Model untuk interaksi dengan database
- `app/Views/` - Template view untuk rendering HTML
- `app/Config/` - File konfigurasi (Routes, Filters, Database, dll)
- `app/Filters/` - Custom filter untuk autentikasi dan authorization
- `public/assets/` - Static assets (CSS, images, JS)
- `writable/` - Folder untuk cache, logs, uploads, session

### Pola Arsitektur
Sistem menggunakan pola **MVC (Model-View-Controller)**:
- **Model**: Menangani logika data dan query database
- **View**: Menampilkan data ke user (HTML dengan TailwindCSS)
- **Controller**: Menghubungkan Model dan View, menangani request HTTP

---

## Routing System

### Lokasi File
`app/Config/Routes.php` - File konfigurasi routing utama

### Kategori Route

#### 1. Frontend Routes (Public Access)
- **GET `/`** → `Home::index` - Halaman landing page utama
- **GET `/game/{slug}`** → `Home::game` - Halaman detail game berdasarkan slug
- **POST `/order/create`** → `Order::create` - Endpoint untuk membuat transaksi baru
- **GET `/order/status/{invoice}`** → `Order::status` - Halaman status transaksi berdasarkan nomor invoice
- **POST `/order/check-promo`** → `Order::checkPromo` - Endpoint AJAX untuk validasi kode promo
- **GET `/cek-transaksi`** → `Home::cekTransaksi` - Halaman form pencarian transaksi
- **POST `/cek-transaksi/search`** → `Home::searchTransaction` - Endpoint pencarian transaksi

#### 2. Auth Routes (Public Access)
Route grup `/auth` untuk autentikasi user:
- **GET `/auth/login`** → `Auth::login` - Halaman form login
- **POST `/auth/login`** → `Auth::attemptLogin` - Proses validasi dan login
- **GET `/auth/register`** → `Auth::register` - Halaman form registrasi
- **POST `/auth/register`** → `Auth::attemptRegister` - Proses registrasi user baru
- **GET `/auth/logout`** → `Auth::logout` - Proses logout dan destroy session

#### 3. User Dashboard Routes (Protected - Filter: `auth`)
Route grup `/dashboard` yang memerlukan login user:
- **GET `/dashboard`** → `Dashboard::index` - Dashboard utama user
- **GET `/dashboard/transactions`** → `Dashboard::transactions` - Halaman riwayat transaksi user
- **GET `/dashboard/profile`** → `Dashboard::profile` - Halaman profil user
- **POST `/dashboard/profile/update`** → `Dashboard::updateProfile` - Update data profil user

#### 4. Admin Routes (Protected - Filter: `adminauth`)
Route grup `/admin` yang memerlukan login admin:

**Auth Admin:**
- **GET `/admin/login`** → `Admin\Auth::login` - Halaman login admin
- **POST `/admin/login`** → `Admin\Auth::attemptLogin` - Proses login admin
- **GET `/admin/logout`** → `Admin\Auth::logout` - Logout admin

**Dashboard Admin:**
- **GET `/admin`** → `Admin\Dashboard::index` - Dashboard admin dengan statistik

**Transaksi Admin:**
- **GET `/admin/transactions`** → `Admin\Transactions::index` - Daftar semua transaksi dengan filter
- **GET `/admin/transactions/detail/{id}`** → `Admin\Transactions::detail` - Detail transaksi spesifik
- **POST `/admin/transactions/update-status`** → `Admin\Transactions::updateStatus` - Update status transaksi

**Games Admin:**
- **GET `/admin/games`** → `Admin\Games::index` - Daftar semua game
- **GET `/admin/games/create`** → `Admin\Games::create` - Form tambah game baru
- **POST `/admin/games/store`** → `Admin\Games::store` - Simpan game baru
- **GET `/admin/games/edit/{id}`** → `Admin\Games::edit` - Form edit game
- **POST `/admin/games/update/{id}`** → `Admin\Games::update` - Update game
- **POST `/admin/games/delete/{id}`** → `Admin\Games::delete` - Hapus game

**Products Admin:**
- **GET `/admin/products`** → `Admin\Products::index` - Daftar semua produk
- **POST `/admin/products/store`** → `Admin\Products::store` - Simpan produk baru
- **POST `/admin/products/update/{id}`** → `Admin\Products::update` - Update produk
- **POST `/admin/products/delete/{id}`** → `Admin\Products::delete` - Hapus produk

**Promos Admin:**
- **GET `/admin/promos`** → `Admin\Promos::index` - Daftar semua kode promo
- **POST `/admin/promos/store`** → `Admin\Promos::store` - Simpan promo baru
- **POST `/admin/promos/update/{id}`** → `Admin\Promos::update` - Update promo
- **POST `/admin/promos/delete/{id}`** → `Admin\Promos::delete` - Hapus promo

### Filter System
- **Filter `auth`**: Memastikan user sudah login sebelum mengakses route dashboard
- **Filter `adminauth`**: Memastikan admin sudah login sebelum mengakses route admin

---

## Models (Data Layer)

### 1. UserModel
**Lokasi**: `app/Models/UserModel.php`  
**Tabel Database**: `users`

**Fungsi Utama:**
- Mengelola data user (username, email, password, phone, points, total_transactions)
- **Auto-hashing password**: Menggunakan event `beforeInsert` dan `beforeUpdate` untuk hash password otomatis menggunakan `password_hash()` dengan `PASSWORD_DEFAULT`
- **Method `addPoints($userId, $points)`**: Menambah poin user dan increment total_transactions secara atomic
- Menggunakan timestamps otomatis (created_at, updated_at)

**Field yang Dapat Diisi:**
- username, email, password, phone, points, total_transactions

### 2. GameModel
**Lokasi**: `app/Models/GameModel.php`  
**Tabel Database**: `games`

**Fungsi Utama:**
- Mengelola data game (name, slug, image, category, is_popular, is_active)
- **Method `getPopularGames($limit)`**: Mengambil game yang ditandai sebagai popular dan aktif, dengan limit default 8
- **Method `getBySlug($slug)`**: Mencari game berdasarkan slug untuk URL-friendly
- Menggunakan timestamps otomatis

**Field yang Dapat Diisi:**
- name, slug, image, category, is_popular, is_active

### 3. ProductModel
**Lokasi**: `app/Models/ProductModel.php`  
**Tabel Database**: `products`

**Fungsi Utama:**
- Mengelola data produk top up (game_id, name, description, price, discount_price, category, is_popular, is_active)
- **Method `getByGameId($gameId)`**: Mengambil semua produk aktif untuk game tertentu, diurutkan berdasarkan harga ascending
- **Method `getPopularByGameId($gameId)`**: Mengambil produk popular untuk game tertentu
- **Method `getWithGame($productId)`**: Mengambil produk dengan informasi game terkait (join query)

**Field yang Dapat Diisi:**
- game_id, name, description, price, discount_price, category, is_popular, is_active

### 4. TransactionModel
**Lokasi**: `app/Models/TransactionModel.php`  
**Tabel Database**: `transactions`

**Fungsi Utama:**
- Mengelola data transaksi top up
- **Method `getWithDetails($transactionId)`**: Mengambil transaksi dengan join ke games, products, dan payment_methods untuk mendapatkan informasi lengkap
- **Method `getByInvoice($invoice)`**: Mencari transaksi berdasarkan nomor invoice dengan join ke games, products, payment_methods
- **Method `getUserTransactions($userId, $limit)`**: Mengambil riwayat transaksi user dengan join ke games dan products, diurutkan DESC, default limit 10
- **Method `generateInvoice()`**: Generate nomor invoice unik dengan format `INV{YYYYMMDD}{6 karakter random uppercase}`
- **Method `getPendingTransactions()`**: Mengambil transaksi dengan status pending atau processing yang belum expired
- **Method `getTodayStats()`**: Menghitung statistik transaksi hari ini (total, success, pending, revenue)

**Field yang Dapat Diisi:**
- user_id, invoice_number, game_id, product_id, user_game_id, payment_method_id, promo_code_id, amount, discount, fee, total_payment, status, payment_proof, qr_code, va_number, points_earned, admin_notes, expired_at, paid_at, completed_at

### 5. PromoCodeModel
**Lokasi**: `app/Models/PromoCodeModel.php`  
**Tabel Database**: `promo_codes`

**Fungsi Utama:**
- Mengelola kode promo dan diskon
- **Method `validateCode($code, $amount)`**: Validasi kode promo dengan logika:
  - Cek apakah kode aktif
  - Cek usage_limit (jika ada) vs used_count
  - Cek periode valid_from dan valid_until
  - Cek minimum transaction amount
  - Hitung diskon berdasarkan type (percentage atau fixed)
  - Untuk percentage: hitung persentase dari amount, jika ada max_discount maka batasi
  - Return array dengan valid (boolean), message, promo_id, discount
- **Method `incrementUsage($promoId)`**: Increment used_count ketika promo digunakan
- Tidak menggunakan timestamps (useTimestamps = false)

**Field yang Dapat Diisi:**
- code, type, value, min_transaction, max_discount, usage_limit, used_count, valid_from, valid_until, is_active

### 6. PaymentMethodModel
**Lokasi**: `app/Models/PaymentMethodModel.php`  
**Tabel Database**: `payment_methods`

**Fungsi Utama:**
- Mengelola metode pembayaran (VA, QRIS, e-wallet)
- **Method `getActive()`**: Mengambil semua metode pembayaran yang aktif
- **Method `getByType($type)`**: Mengambil metode pembayaran berdasarkan tipe (va, qris, ewallet)
- **Method `calculateFee($paymentId, $amount)`**: Menghitung fee pembayaran (saat ini menggunakan fee tetap dari database, bukan persentase)
- Tidak menggunakan timestamps

**Field yang Dapat Diisi:**
- name, type (enum: va/qris/ewallet), code, icon, fee, is_active

### 7. AdminUserModel
**Lokasi**: `app/Models/AdminUserModel.php`  
**Tabel Database**: `admin_users`

**Fungsi Utama:**
- Mengelola data admin user
- **Auto-hashing password**: Sama seperti UserModel, menggunakan event untuk hash password
- Tidak menggunakan timestamps
- **Catatan**: Saat ini admin login menggunakan hardcode di controller, model ini tersedia untuk implementasi database-based admin auth di masa depan

**Field yang Dapat Diisi:**
- username, password

---

## Controllers (Business Logic)

### Frontend Controllers

#### 1. Home Controller
**Lokasi**: `app/Controllers/Home.php`

**Dependencies**: GameModel, ProductModel, TransactionModel

**Methods:**
- **`index()`**: 
  - Mengambil game popular (limit 8), semua game aktif, dan produk flash sale (produk dengan discount_price, limit 3)
  - Render view `home/index` dengan data tersebut
  - Menampilkan landing page dengan hero slider, flash sale section, dan grid game

- **`game($slug)`**: 
  - Mencari game berdasarkan slug
  - Jika tidak ditemukan, throw PageNotFoundException
  - Mengambil semua produk aktif untuk game tersebut
  - Mengambil semua payment method aktif
  - Render view `home/game` dengan data game, products, dan payment_methods
  - Menampilkan halaman detail game dengan form order

- **`cekTransaksi()`**: 
  - Render view `home/cek_transaksi` untuk form pencarian transaksi
  - Halaman sederhana dengan form input nomor invoice

- **`searchTransaction()`**: 
  - Menerima POST request dengan field `invoice`
  - Validasi: invoice tidak boleh kosong
  - Mencari transaksi berdasarkan invoice number
  - Jika tidak ditemukan, redirect back dengan error message
  - Jika ditemukan, redirect ke halaman status transaksi

#### 2. Auth Controller
**Lokasi**: `app/Controllers/Auth.php`

**Dependencies**: UserModel

**Methods:**
- **`login()`**: 
  - Cek jika user sudah login, redirect ke dashboard
  - Render view `auth/login` untuk form login

- **`attemptLogin()`**: 
  - Validasi input: email (required, valid_email), password (required, min 6 karakter)
  - Cari user berdasarkan email
  - Verifikasi password menggunakan `password_verify()`
  - Jika valid, set session: user_id, username, email, logged_in = true
  - Redirect ke dashboard dengan success message
  - Jika invalid, redirect back dengan error message

- **`register()`**: 
  - Cek jika user sudah login, redirect ke dashboard
  - Render view `auth/register` untuk form registrasi

- **`attemptRegister()`**: 
  - Validasi: username (required, min 3, unique), email (required, valid_email, unique), password (required, min 6), password_confirm (required, matches password)
  - Insert data user baru (password akan di-hash otomatis oleh model)
  - Redirect ke halaman login dengan success message
  - Jika validasi gagal, redirect back dengan error messages

- **`logout()`**: 
  - Destroy semua session
  - Redirect ke home dengan success message

#### 3. Order Controller
**Lokasi**: `app/Controllers/Order.php`

**Dependencies**: TransactionModel, ProductModel, PaymentMethodModel, PromoCodeModel, UserModel

**Methods:**
- **`create()`**: 
  - Validasi: product_id (required, numeric), user_game_id (required, min 3 karakter), payment_method_id (required, numeric)
  - Ambil data produk dengan informasi game (getWithGame)
  - Hitung amount: gunakan discount_price jika ada, jika tidak gunakan price
  - Jika ada promo_code di POST, validasi menggunakan PromoCodeModel::validateCode
  - Jika promo valid, terapkan diskon dan simpan promo_id
  - Hitung fee menggunakan PaymentMethodModel::calculateFee (fee tetap, bukan persentase)
  - Hitung total_payment = amount - discount + fee
  - Jika user login, hitung points_earned = floor(total_payment / 1000) (1 point per 1000 rupiah)
  - Generate invoice number menggunakan TransactionModel::generateInvoice
  - Set expired_at = waktu sekarang + 60 menit
  - Generate VA number atau QR code berdasarkan tipe payment method:
    - VA: generate dengan prefix bank (bca_va=70012, bni_va=8808, bri_va=26215, mandiri_va=88012) + hash invoice
    - QRIS/E-wallet: generate QR code string
  - Insert transaksi dengan status 'pending'
  - Jika promo digunakan, increment usage count
  - Redirect ke halaman status transaksi

- **`status($invoice)`**: 
  - Cari transaksi berdasarkan invoice number
  - Jika tidak ditemukan, throw PageNotFoundException
  - Cek jika transaksi pending dan sudah melewati expired_at, update status menjadi 'expired'
  - Render view `order/status` dengan data transaksi lengkap

- **`checkPromo()`**: 
  - Endpoint AJAX untuk validasi promo code real-time
  - Menerima POST: code dan amount
  - Validasi code dan amount tidak boleh kosong
  - Panggil PromoCodeModel::validateCode
  - Return JSON response dengan success (boolean), message, dan discount

- **`generateVANumber($bankCode, $invoice)`** (private): 
  - Helper method untuk generate nomor VA berdasarkan bank code dan invoice
  - Menggunakan prefix bank dan hash dari invoice

#### 4. Dashboard Controller
**Lokasi**: `app/Controllers/Dashboard.php`

**Dependencies**: UserModel, TransactionModel

**Methods:**
- **`index()`**: 
  - Cek user_id dari session
  - Ambil data user
  - Jika user tidak ditemukan, redirect ke login
  - Ambil 5 transaksi terbaru user
  - Render view `dashboard/index` dengan data user dan transactions

- **`transactions()`**: 
  - Cek user_id dari session
  - Ambil data user
  - Jika user tidak ditemukan, redirect ke login
  - Ambil 50 transaksi terbaru user
  - Render view `dashboard/transactions` dengan data user dan transactions

- **`profile()`**: 
  - Cek user_id dari session
  - Ambil data user
  - Jika user tidak ditemukan, redirect ke login
  - Render view `dashboard/profile` dengan data user

- **`updateProfile()`**: 
  - Cek user_id dari session
  - Validasi: username (required, min 3, unique kecuali untuk user yang sama), email (required, valid_email, unique kecuali untuk user yang sama)
  - Update data: username, email, phone
  - Jika password diisi, update password (akan di-hash otomatis)
  - Redirect back dengan success message
  - Jika validasi gagal, redirect back dengan error messages

### Admin Controllers

#### 1. Admin\Auth Controller
**Lokasi**: `app/Controllers/Admin/Auth.php`

**Dependencies**: AdminUserModel (tidak digunakan, hardcode)

**Methods:**
- **`login()`**: 
  - Cek jika admin sudah login, redirect ke admin dashboard
  - Render view `admin/auth/login` untuk form login admin

- **`attemptLogin()`**: 
  - Ambil username dan password dari POST
  - Validasi tidak boleh kosong
  - **Hardcode check**: username === 'admin' && password === 'admin123'
  - Jika valid, set session: admin_id = 1, admin_username = 'admin', admin_logged_in = true
  - Redirect ke admin dashboard
  - Jika invalid, redirect back dengan error message

- **`logout()`**: 
  - Destroy semua session
  - Redirect ke admin login

#### 2. Admin\Dashboard Controller
**Lokasi**: `app/Controllers/Admin/Dashboard.php`

**Dependencies**: TransactionModel, UserModel, GameModel

**Methods:**
- **`index()`**: 
  - Ambil statistik hari ini menggunakan TransactionModel::getTodayStats (total, success, pending, revenue)
  - Hitung total users dan total games
  - Ambil 10 transaksi terbaru dengan join ke games, products, dan users
  - Render view `admin/dashboard` dengan semua data statistik

#### 3. Admin\Games Controller
**Lokasi**: `app/Controllers/Admin/Games.php`

**Dependencies**: GameModel

**Methods:**
- **`index()`**: 
  - Ambil semua game diurutkan DESC
  - Render view `admin/games/index` dengan data games

- **`store()`**: 
  - Validasi: name (required, min 3), slug (required, unique), category (required)
  - Validasi image jika diupload: max 2MB, harus image, ekstensi jpg/jpeg/png/webp
  - Upload image ke folder `public/assets/images/games` dengan random name
  - Insert data game dengan is_popular dan is_active dari checkbox
  - Redirect ke admin/games dengan success message

- **`update($id)`**: 
  - Cari game berdasarkan ID
  - Validasi sama seperti store, tapi slug unique kecuali untuk game yang sama
  - Update data game
  - Jika image baru diupload, hapus image lama (kecuali default.jpg) dan upload yang baru
  - Redirect ke admin/games dengan success message

- **`delete($id)`**: 
  - Cari game berdasarkan ID
  - Hapus image game (kecuali default.jpg)
  - Delete game dari database
  - Return JSON response dengan success

#### 4. Admin\Products Controller
**Lokasi**: `app/Controllers/Admin/Products.php`

**Dependencies**: ProductModel, GameModel

**Methods:**
- **`index()`**: 
  - Ambil semua produk dengan join ke games untuk mendapatkan nama game
  - Ambil semua game aktif untuk dropdown
  - Render view `admin/products/index` dengan data products dan games

- **`store()`**: 
  - Ambil data dari POST: game_id, name, description, price, discount_price (nullable), is_active
  - Insert produk baru
  - Redirect ke admin/products dengan success message

- **`update($id)`**: 
  - Ambil data dari POST sama seperti store
  - Update produk berdasarkan ID
  - Redirect ke admin/products dengan success message

- **`delete($id)`**: 
  - Delete produk berdasarkan ID
  - Return JSON response dengan success

#### 5. Admin\Promos Controller
**Lokasi**: `app/Controllers/Admin/Promos.php`

**Dependencies**: PromoCodeModel

**Methods:**
- **`index()`**: 
  - Ambil semua promo diurutkan DESC
  - Render view `admin/promos/index` dengan data promos

- **`store()`**: 
  - Ambil data dari POST: code (uppercase), type, value, min_transaction, max_discount (nullable), usage_limit (nullable), valid_until (nullable), is_active
  - Insert promo baru
  - Redirect ke admin/promos dengan success message

- **`update($id)`**: 
  - Ambil data dari POST sama seperti store
  - Update promo berdasarkan ID
  - Redirect ke admin/promos dengan success message

- **`delete($id)`**: 
  - Delete promo berdasarkan ID
  - Return JSON response dengan success

#### 6. Admin\Transactions Controller
**Lokasi**: `app/Controllers/Admin/Transactions.php`

**Dependencies**: TransactionModel

**Methods:**
- **`index()`**: 
  - Ambil parameter GET: status (default 'all') dan search
  - Query transaksi dengan join ke games, products, users (left join), payment_methods
  - Filter berdasarkan status jika bukan 'all'
  - Filter berdasarkan search (invoice_number, user_game_id, atau username) jika ada
  - Render view `admin/transactions/index` dengan data transactions, current_status, dan search

- **`detail($id)`**: 
  - Ambil transaksi dengan detail lengkap menggunakan TransactionModel::getWithDetails
  - Jika tidak ditemukan, redirect ke admin/transactions dengan error
  - Render view `admin/transactions/detail` dengan data transaction

- **`updateStatus()`**: 
  - Ambil transaction_id dan status dari POST
  - Update status transaksi
  - Redirect ke halaman detail transaksi dengan success message

---

## Views (Presentation Layer)

### Layout System
**Lokasi**: `app/Views/layouts/main.php`

**Fungsi:**
- Template utama yang digunakan oleh semua view
- Menggunakan TailwindCSS via CDN
- Font Awesome untuk icons
- Header dengan navigation menu (Topup, Cek Transaksi, Login/Register/Dashboard link)
- Footer dengan informasi copyright
- Styling dengan gradient background (dark theme: #1e1b29 ke #2a2738)
- Responsive design dengan mobile menu toggle

### Frontend Views

#### 1. Home Views
**Lokasi**: `app/Views/home/`

- **`index.php`**: 
  - Landing page dengan hero slideshow (3 slide dengan navigasi)
  - Flash Sale section dengan countdown timer (menampilkan produk dengan discount_price)
  - Trending section dengan grid game popular (4 game pertama)
  - All Games section dengan grid semua game aktif
  - Setiap game card memiliki hover effect dan link ke halaman detail game

- **`game.php`**: 
  - Header dengan nama game dan gambar
  - Daftar produk top up untuk game tersebut (grid atau list)
  - Form order dengan field:
    - Pilihan produk (radio button atau select)
    - Input ID game user (required)
    - Pilihan metode pembayaran (radio button dengan icon)
    - Input kode promo (opsional) dengan tombol validasi AJAX
  - Display harga, diskon (jika ada), fee, dan total payment
  - Tombol submit order

- **`cek_transaksi.php`**: 
  - Form sederhana dengan input nomor invoice
  - Tombol submit untuk mencari transaksi
  - Display error message jika transaksi tidak ditemukan

#### 2. Auth Views
**Lokasi**: `app/Views/auth/`

- **`login.php`**: 
  - Form login dengan field email dan password
  - Link ke halaman register
  - Display error messages jika login gagal
  - Styling dengan TailwindCSS, form centered

- **`register.php`**: 
  - Form registrasi dengan field: username, email, password, password_confirm, phone (opsional)
  - Link ke halaman login
  - Display validation error messages
  - Styling dengan TailwindCSS, form centered

#### 3. Dashboard Views
**Lokasi**: `app/Views/dashboard/`

- **`index.php`**: 
  - Welcome message dengan username
  - Statistik ringkas (points, total transactions)
  - Tabel riwayat transaksi terbaru (5 transaksi) dengan kolom: invoice, game, produk, total, status, tanggal
  - Link ke halaman transactions lengkap dan profile

- **`transactions.php`**: 
  - Tabel lengkap riwayat transaksi user (50 transaksi)
  - Kolom: invoice, game, produk, total, status, tanggal
  - Badge status dengan warna berbeda (pending, success, expired, dll)
  - Link ke halaman detail transaksi

- **`profile.php`**: 
  - Form edit profil dengan field: username, email, phone
  - Form ganti password (opsional)
  - Tombol update
  - Display success/error messages

#### 4. Order Views
**Lokasi**: `app/Views/order/`

- **`status.php`**: 
  - Header dengan nomor invoice
  - Informasi transaksi lengkap:
    - Game dan produk yang dibeli
    - ID game user
    - Metode pembayaran
    - Detail harga (amount, discount, fee, total)
    - Status transaksi dengan badge warna
    - Waktu expired (jika pending)
    - VA number atau QR code (jika ada)
  - Catatan admin (jika ada)
  - Tombol kembali ke home atau cek transaksi lagi

### Admin Views

#### 1. Admin Auth Views
**Lokasi**: `app/Views/admin/auth/`

- **`login.php`**: 
  - Form login admin sederhana
  - Field username dan password
  - Styling dengan TailwindCSS

#### 2. Admin Dashboard
**Lokasi**: `app/Views/admin/dashboard.php`

- **Konten:**
  - Statistik cards: Total transaksi hari ini, Success transactions, Pending transactions, Revenue hari ini, Total users, Total games
  - Tabel 10 transaksi terbaru dengan kolom: invoice, user, game, produk, total, status, tanggal
  - Link ke halaman detail transaksi

#### 3. Admin Games Views
**Lokasi**: `app/Views/admin/games/index.php`

- **Konten:**
  - Tabel semua game dengan kolom: ID, Nama, Slug, Category, Popular, Active, Actions
  - Tombol tambah game baru
  - Modal atau form untuk tambah/edit game dengan field: name, slug, category, description, image upload, checkbox is_popular, checkbox is_active
  - Tombol edit dan delete untuk setiap game
  - Preview image game

#### 4. Admin Products Views
**Lokasi**: `app/Views/admin/products/index.php`

- **Konten:**
  - Tabel semua produk dengan kolom: ID, Game, Nama, Price, Discount Price, Active, Actions
  - Tombol tambah produk baru
  - Modal atau form untuk tambah/edit produk dengan field: game (dropdown), name, description, price, discount_price, checkbox is_active
  - Tombol edit dan delete untuk setiap produk

#### 5. Admin Promos Views
**Lokasi**: `app/Views/admin/promos/index.php`

- **Konten:**
  - Tabel semua promo dengan kolom: ID, Code, Type, Value, Min Transaction, Max Discount, Usage Limit, Used Count, Valid Until, Active, Actions
  - Tombol tambah promo baru
  - Modal atau form untuk tambah/edit promo dengan field: code, type (dropdown: percentage/fixed), value, min_transaction, max_discount, usage_limit, valid_from, valid_until, checkbox is_active
  - Tombol edit dan delete untuk setiap promo

#### 6. Admin Transactions Views
**Lokasi**: `app/Views/admin/transactions/`

- **`index.php`**: 
  - Filter dropdown untuk status (all, pending, success, expired, dll)
  - Search box untuk mencari berdasarkan invoice, user_game_id, atau username
  - Tabel semua transaksi dengan kolom: Invoice, User, Game, Produk, Total, Status, Tanggal, Actions
  - Link ke halaman detail untuk setiap transaksi
  - Badge status dengan warna berbeda

- **`detail.php`**: 
  - Informasi lengkap transaksi:
    - Invoice number
    - User information (jika ada)
    - Game dan produk
    - ID game user
    - Metode pembayaran
    - Detail harga (amount, discount, fee, total)
    - Status dengan dropdown untuk update
    - VA number atau QR code
    - Catatan admin (textarea untuk update)
    - Timestamps (created_at, expired_at, paid_at, completed_at)
  - Tombol update status
  - Tombol kembali ke daftar transaksi

---

## Filters (Security & Access Control)

### 1. AuthFilter
**Lokasi**: `app/Filters/AuthFilter.php`

**Fungsi:**
- Filter untuk memastikan user sudah login sebelum mengakses route yang dilindungi
- **Before filter**: Cek session `logged_in`
- Jika tidak login, redirect ke `/auth/login` dengan error message
- Digunakan pada route grup `/dashboard`

**Implementasi:**
- Implement interface `FilterInterface`
- Method `before()`: Cek `session()->get('logged_in')`
- Method `after()`: Kosong (tidak ada action setelah request)

### 2. AdminAuthFilter
**Lokasi**: `app/Filters/AdminAuthFilter.php`

**Fungsi:**
- Filter untuk memastikan admin sudah login sebelum mengakses route admin
- **Before filter**: Cek session `admin_logged_in`
- Jika tidak login, redirect ke `/admin/login` dengan error message
- Digunakan pada route grup `/admin` (kecuali login/logout)

**Implementasi:**
- Implement interface `FilterInterface`
- Method `before()`: Cek `session()->get('admin_logged_in')`
- Method `after()`: Kosong

### Konfigurasi Filter
**Lokasi**: `app/Config/Filters.php`

**Aliases:**
- `auth` → `App\Filters\AuthFilter`
- `adminauth` → `App\Filters\AdminAuthFilter`
- `csrf` → CSRF protection (saat ini dinonaktifkan untuk testing)
- `toolbar` → Debug toolbar (aktif di after filter)

**Global Filters:**
- Before: CSRF dinonaktifkan (dikomentari)
- After: Toolbar aktif untuk development

---

## Database Schema

### Tabel: users
**Deskripsi**: Menyimpan data user yang terdaftar

**Kolom:**
- `id` (INT, PRIMARY KEY, AUTO_INCREMENT)
- `username` (VARCHAR, UNIQUE, NOT NULL)
- `email` (VARCHAR, UNIQUE, NOT NULL)
- `password` (VARCHAR(255), NOT NULL) - Hashed dengan password_hash()
- `phone` (VARCHAR, NULLABLE)
- `points` (INT, DEFAULT 0) - Poin loyalty user
- `total_transactions` (INT, DEFAULT 0) - Total transaksi user
- `created_at` (DATETIME, AUTO)
- `updated_at` (DATETIME, AUTO)

**Index:**
- PRIMARY KEY: id
- UNIQUE: username, email

### Tabel: admin_users
**Deskripsi**: Menyimpan data admin (saat ini belum digunakan, login hardcode)

**Kolom:**
- `id` (INT, PRIMARY KEY, AUTO_INCREMENT)
- `username` (VARCHAR, UNIQUE, NOT NULL)
- `password` (VARCHAR(255), NOT NULL) - Hashed
- `created_at` (DATETIME, AUTO)

**Index:**
- PRIMARY KEY: id
- UNIQUE: username

### Tabel: games
**Deskripsi**: Menyimpan data game yang tersedia untuk top up

**Kolom:**
- `id` (INT, PRIMARY KEY, AUTO_INCREMENT)
- `name` (VARCHAR(100), NOT NULL) - Nama game
- `slug` (VARCHAR(100), UNIQUE, NOT NULL) - URL-friendly identifier
- `image` (VARCHAR(255), NULLABLE) - Nama file gambar
- `category` (VARCHAR(50), NULLABLE) - Kategori game (MOBA, FPS, dll)
- `description` (TEXT, NULLABLE)
- `is_popular` (TINYINT(1), DEFAULT 0) - Flag untuk game popular
- `is_active` (TINYINT(1), DEFAULT 1) - Flag untuk aktif/nonaktif
- `created_at` (DATETIME, AUTO)
- `updated_at` (DATETIME, AUTO)

**Index:**
- PRIMARY KEY: id
- UNIQUE: slug

### Tabel: products
**Deskripsi**: Menyimpan data produk top up untuk setiap game

**Kolom:**
- `id` (INT, PRIMARY KEY, AUTO_INCREMENT)
- `game_id` (INT, NOT NULL, FOREIGN KEY ke games.id)
- `name` (VARCHAR(100), NOT NULL) - Nama produk (contoh: "100 Diamond")
- `description` (TEXT, NULLABLE)
- `price` (DECIMAL(10,2), NOT NULL) - Harga normal
- `discount_price` (DECIMAL(10,2), NULLABLE) - Harga diskon (untuk flash sale)
- `category` (VARCHAR(50), NULLABLE)
- `is_popular` (TINYINT(1), DEFAULT 0)
- `is_active` (TINYINT(1), DEFAULT 1)
- `created_at` (DATETIME, AUTO)
- `updated_at` (DATETIME, AUTO)

**Index:**
- PRIMARY KEY: id
- FOREIGN KEY: game_id → games.id

### Tabel: payment_methods
**Deskripsi**: Menyimpan metode pembayaran yang tersedia

**Kolom:**
- `id` (INT, PRIMARY KEY, AUTO_INCREMENT)
- `name` (VARCHAR(50), NOT NULL) - Nama metode (contoh: "BCA Virtual Account")
- `type` (ENUM: 'va', 'qris', 'ewallet', NOT NULL) - Tipe pembayaran
- `code` (VARCHAR(20), UNIQUE, NOT NULL) - Kode unik (contoh: "bca_va")
- `icon` (VARCHAR(255), NULLABLE) - Nama file icon
- `fee` (DECIMAL(10,2), DEFAULT 0.00) - Biaya admin (fee tetap)
- `is_active` (TINYINT(1), DEFAULT 1)
- `created_at` (DATETIME, AUTO)
- `updated_at` (DATETIME, AUTO)

**Index:**
- PRIMARY KEY: id
- UNIQUE: code

### Tabel: promo_codes
**Deskripsi**: Menyimpan kode promo dan aturan diskon

**Kolom:**
- `id` (INT, PRIMARY KEY, AUTO_INCREMENT)
- `code` (VARCHAR(50), UNIQUE, NOT NULL) - Kode promo (uppercase)
- `type` (ENUM: 'percentage', 'fixed', NOT NULL) - Tipe diskon
- `value` (DECIMAL(10,2), NOT NULL) - Nilai diskon (persen atau nominal)
- `min_transaction` (DECIMAL(10,2), DEFAULT 0) - Minimum transaksi untuk menggunakan promo
- `max_discount` (DECIMAL(10,2), NULLABLE) - Maksimal diskon (untuk type percentage)
- `usage_limit` (INT, NULLABLE) - Batas penggunaan (null = unlimited)
- `used_count` (INT, DEFAULT 0) - Jumlah penggunaan saat ini
- `valid_from` (DATETIME, NULLABLE) - Tanggal mulai berlaku
- `valid_until` (DATETIME, NULLABLE) - Tanggal berakhir
- `is_active` (TINYINT(1), DEFAULT 1)

**Index:**
- PRIMARY KEY: id
- UNIQUE: code

### Tabel: transactions
**Deskripsi**: Menyimpan data transaksi top up

**Kolom:**
- `id` (INT, PRIMARY KEY, AUTO_INCREMENT)
- `user_id` (INT, NULLABLE, FOREIGN KEY ke users.id) - NULL jika guest order
- `invoice_number` (VARCHAR(50), UNIQUE, NOT NULL) - Nomor invoice unik
- `game_id` (INT, NOT NULL, FOREIGN KEY ke games.id)
- `product_id` (INT, NOT NULL, FOREIGN KEY ke products.id)
- `user_game_id` (VARCHAR(100), NOT NULL) - ID game user yang diinput
- `payment_method_id` (INT, NOT NULL, FOREIGN KEY ke payment_methods.id)
- `promo_code_id` (INT, NULLABLE, FOREIGN KEY ke promo_codes.id)
- `amount` (DECIMAL(10,2), NOT NULL) - Harga produk (setelah diskon produk jika ada)
- `discount` (DECIMAL(10,2), DEFAULT 0) - Diskon dari promo code
- `fee` (DECIMAL(10,2), DEFAULT 0) - Biaya admin
- `total_payment` (DECIMAL(10,2), NOT NULL) - Total yang harus dibayar (amount - discount + fee)
- `status` (ENUM: 'pending', 'processing', 'success', 'expired', 'cancelled', DEFAULT 'pending')
- `payment_proof` (VARCHAR(255), NULLABLE) - File bukti pembayaran (jika ada)
- `qr_code` (VARCHAR(255), NULLABLE) - QR code untuk QRIS/e-wallet
- `va_number` (VARCHAR(50), NULLABLE) - Nomor VA untuk Virtual Account
- `points_earned` (INT, DEFAULT 0) - Poin yang didapat dari transaksi ini
- `admin_notes` (TEXT, NULLABLE) - Catatan admin
- `expired_at` (DATETIME, NULLABLE) - Waktu kedaluwarsa (60 menit dari created_at)
- `paid_at` (DATETIME, NULLABLE) - Waktu pembayaran berhasil
- `completed_at` (DATETIME, NULLABLE) - Waktu transaksi selesai
- `created_at` (DATETIME, AUTO)
- `updated_at` (DATETIME, AUTO)

**Index:**
- PRIMARY KEY: id
- UNIQUE: invoice_number
- FOREIGN KEY: user_id → users.id
- FOREIGN KEY: game_id → games.id
- FOREIGN KEY: product_id → products.id
- FOREIGN KEY: payment_method_id → payment_methods.id
- FOREIGN KEY: promo_code_id → promo_codes.id

**Relasi:**
- One user dapat memiliki banyak transactions (1:N)
- One game dapat memiliki banyak transactions (1:N)
- One product dapat memiliki banyak transactions (1:N)
- One payment_method dapat memiliki banyak transactions (1:N)
- One promo_code dapat memiliki banyak transactions (1:N, nullable)

---

## Alur Proses Bisnis

### 1. Alur Registrasi User
1. User mengakses `/auth/register`
2. User mengisi form: username, email, password, password_confirm, phone (opsional)
3. Sistem validasi: username unique & min 3, email unique & valid, password min 6, password_confirm matches
4. Jika valid, insert ke database (password di-hash otomatis oleh model)
5. Redirect ke `/auth/login` dengan success message
6. Jika invalid, redirect back dengan error messages

### 2. Alur Login User
1. User mengakses `/auth/login`
2. User mengisi email dan password
3. Sistem validasi: email valid, password min 6
4. Cari user berdasarkan email
5. Verifikasi password menggunakan `password_verify()`
6. Jika valid, set session: user_id, username, email, logged_in = true
7. Redirect ke `/dashboard` dengan success message
8. Jika invalid, redirect back dengan error message

### 3. Alur Membuat Transaksi (Order)
1. User mengakses halaman game (contoh: `/game/mobile-legends`)
2. Sistem menampilkan daftar produk top up untuk game tersebut
3. User memilih produk, mengisi ID game, memilih metode pembayaran
4. (Opsional) User memasukkan kode promo dan klik validasi (AJAX ke `/order/check-promo`)
5. Sistem validasi promo: aktif, periode valid, usage_limit, min_transaction
6. Jika promo valid, tampilkan diskon yang akan diterapkan
7. User klik tombol "Order" atau "Beli Sekarang"
8. Sistem validasi: product_id, user_game_id (min 3), payment_method_id
9. Sistem hitung:
   - Amount = discount_price (jika ada) atau price
   - Discount = dari promo code (jika valid)
   - Fee = dari payment_method (fee tetap)
   - Total = amount - discount + fee
   - Points = floor(total / 1000) jika user login
10. Generate invoice number: `INV{YYYYMMDD}{6 random chars}`
11. Set expired_at = sekarang + 60 menit
12. Generate VA number atau QR code berdasarkan tipe payment:
    - VA: prefix bank + hash invoice
    - QRIS/E-wallet: string QR code
13. Insert transaksi dengan status 'pending'
14. Jika promo digunakan, increment used_count
15. Redirect ke `/order/status/{invoice}`

### 4. Alur Cek Status Transaksi
1. User mengakses `/cek-transaksi` atau `/order/status/{invoice}`
2. Jika dari form cek, user input nomor invoice dan submit
3. Sistem cari transaksi berdasarkan invoice
4. Jika tidak ditemukan, tampilkan error message
5. Jika ditemukan, cek apakah transaksi pending dan sudah melewati expired_at
6. Jika expired, update status menjadi 'expired'
7. Tampilkan halaman status dengan informasi lengkap:
   - Invoice number
   - Game dan produk
   - ID game user
   - Metode pembayaran
   - Detail harga (amount, discount, fee, total)
   - Status dengan badge warna
   - VA number atau QR code
   - Waktu expired (jika pending)
   - Catatan admin (jika ada)

### 5. Alur Validasi Promo Code (AJAX)
1. User input kode promo di form order
2. User klik tombol validasi (atau auto-validate saat blur)
3. AJAX POST ke `/order/check-promo` dengan code dan amount
4. Sistem validasi:
   - Cek kode aktif
   - Cek usage_limit vs used_count
   - Cek periode valid_from dan valid_until
   - Cek min_transaction
   - Hitung diskon (percentage atau fixed, dengan max_discount jika ada)
5. Return JSON: {success: boolean, message: string, discount: number}
6. Frontend update UI: tampilkan diskon atau error message

### 6. Alur Admin Login
1. Admin mengakses `/admin/login`
2. Admin input username dan password
3. Sistem cek hardcode: username === 'admin' && password === 'admin123'
4. Jika valid, set session: admin_id = 1, admin_username = 'admin', admin_logged_in = true
5. Redirect ke `/admin` (dashboard)
6. Jika invalid, redirect back dengan error message

### 7. Alur Admin Kelola Transaksi
1. Admin mengakses `/admin/transactions`
2. Sistem tampilkan semua transaksi dengan filter status dan search
3. Admin dapat filter berdasarkan status (all, pending, success, expired, dll)
4. Admin dapat search berdasarkan invoice, user_game_id, atau username
5. Admin klik detail transaksi untuk melihat informasi lengkap
6. Di halaman detail, admin dapat:
   - Update status transaksi (dropdown: pending, processing, success, expired, cancelled)
   - Tambah/edit catatan admin
   - Lihat semua informasi transaksi
7. Admin klik update, sistem update status dan/atau admin_notes
8. Redirect ke halaman detail dengan success message

### 8. Alur Admin Kelola Game
1. Admin mengakses `/admin/games`
2. Sistem tampilkan tabel semua game
3. Admin klik "Tambah Game" untuk form baru
4. Admin isi: name, slug, category, description, upload image, checkbox is_popular, checkbox is_active
5. Sistem validasi: name min 3, slug unique, image max 2MB & valid format
6. Upload image ke `public/assets/images/games/` dengan random name
7. Insert game ke database
8. Redirect ke `/admin/games` dengan success message
9. Untuk edit: Admin klik edit, sistem tampilkan form dengan data existing, proses sama seperti tambah
10. Untuk delete: Admin klik delete, sistem hapus image (kecuali default.jpg) dan hapus dari database

### 9. Alur Admin Kelola Produk
1. Admin mengakses `/admin/products`
2. Sistem tampilkan tabel semua produk dengan nama game
3. Admin klik "Tambah Produk" untuk form baru
4. Admin isi: game (dropdown), name, description, price, discount_price (opsional), checkbox is_active
5. Sistem insert produk ke database
6. Redirect ke `/admin/products` dengan success message
7. Untuk edit: Admin klik edit, sistem tampilkan form dengan data existing, proses sama seperti tambah
8. Untuk delete: Admin klik delete, sistem hapus dari database

### 10. Alur Admin Kelola Promo
1. Admin mengakses `/admin/promos`
2. Sistem tampilkan tabel semua promo
3. Admin klik "Tambah Promo" untuk form baru
4. Admin isi: code (auto uppercase), type (percentage/fixed), value, min_transaction, max_discount (opsional), usage_limit (opsional), valid_from, valid_until, checkbox is_active
5. Sistem insert promo ke database
6. Redirect ke `/admin/promos` dengan success message
7. Untuk edit: Admin klik edit, sistem tampilkan form dengan data existing, proses sama seperti tambah
8. Untuk delete: Admin klik delete, sistem hapus dari database

### 11. Alur Update Profil User
1. User login dan mengakses `/dashboard/profile`
2. Sistem tampilkan form dengan data user existing
3. User edit: username, email, phone, password (opsional)
4. User klik update
5. Sistem validasi: username unique (kecuali untuk user yang sama), email unique (kecuali untuk user yang sama), email valid
6. Jika password diisi, password akan di-hash otomatis oleh model
7. Update data user
8. Redirect back dengan success message
9. Jika validasi gagal, redirect back dengan error messages

### 12. Alur Poin Loyalty
1. Ketika transaksi dibuat dan user login, sistem hitung points_earned = floor(total_payment / 1000)
2. Points disimpan di field `points_earned` di tabel transactions
3. (Opsional) Sistem dapat menambahkan points ke user menggunakan UserModel::addPoints() setelah transaksi success
4. User dapat melihat total points di dashboard

### 13. Alur Expiry Transaksi
1. Ketika transaksi dibuat, sistem set expired_at = created_at + 60 menit
2. Ketika user/admin mengakses halaman status transaksi
3. Sistem cek: jika status = 'pending' dan expired_at < sekarang
4. Sistem update status menjadi 'expired'
5. Tampilkan status 'expired' di halaman status

---

## Catatan Teknis Penting

### Keamanan
- Password di-hash menggunakan `password_hash()` dengan `PASSWORD_DEFAULT` (bcrypt)
- Session digunakan untuk autentikasi (tidak ada JWT atau token)
- Filter `auth` dan `adminauth` melindungi route yang memerlukan login
- CSRF protection dinonaktifkan untuk testing (dapat diaktifkan di Filters.php)
- Admin login saat ini hardcode (perlu diubah ke database-based untuk production)

### Performa
- Query menggunakan join untuk mengurangi jumlah query
- Limit digunakan untuk pagination (dashboard user: 5, transactions: 50, admin recent: 10)
- Image upload dengan validasi ukuran dan format
- Index database pada kolom yang sering di-query (id, email, username, slug, invoice_number)

### Validasi
- Server-side validation menggunakan CodeIgniter Validation
- Client-side validation dapat ditambahkan dengan JavaScript
- Unique constraint di database untuk username, email, slug, code promo, invoice_number

### Error Handling
- PageNotFoundException untuk halaman tidak ditemukan
- Redirect back dengan error messages untuk validasi gagal
- Flash messages untuk success/error notification
- Try-catch dapat ditambahkan untuk error handling yang lebih robust

### Extensibility
- Struktur MVC memudahkan penambahan fitur baru
- Model dapat ditambahkan method baru tanpa mengubah controller
- View dapat di-extend menggunakan layout system
- Filter dapat ditambahkan untuk kebutuhan security baru

---

**Dokumen ini mencakup semua aspek teknis dari project Deporte Store. Gunakan sebagai referensi untuk dokumentasi, development, atau maintenance.**
