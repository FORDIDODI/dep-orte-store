# Laporan Rekayasa Perangkat Lunak  
Proyek: **Deporte Store – Platform Top Up Game**  
Teknologi utama: CodeIgniter 4, PHP 8+, TailwindCSS, MySQL  

---

## Daftar Isi
1. Pendahuluan  
   1.1 Latar Belakang  
   1.2 Rumusan Masalah  
   1.3 Tujuan dan Manfaat  
   1.4 Batasan Sistem  
   1.5 Metodologi Pengembangan  
2. Analisis Kebutuhan  
   2.1 Aktor dan Deskripsi Singkat  
   2.2 Kebutuhan Fungsional  
   2.3 Kebutuhan Non-Fungsional  
   2.4 Kebutuhan Data  
3. Desain Sistem  
   3.1 Arsitektur & Pola  
   3.2 Use Case  
   3.3 Activity / Alur Proses Utama  
   3.4 ERD (Narasi Hubungan Data)  
   3.5 Desain Antarmuka (Ringkasan UI/UX)  
4. Implementasi  
   4.1 Lingkungan & Teknologi  
   4.2 Struktur Direktori Kode  
   4.3 Implementasi Backend (Controller, Model, Routing)  
   4.4 Implementasi Frontend (Views & Tailwind)  
   4.5 Keamanan & Session  
   4.6 Konfigurasi Database  
5. Pengujian  
   5.1 Strategi Pengujian  
   5.2 Skenario Uji Fungsional  
   5.3 UAT (User Acceptance Test)  
   5.4 Catatan Temuan & Risiko Sisa  
6. Penutup  
   6.1 Kesimpulan  
   6.2 Saran Pengembangan Lanjutan  
7. Lampiran  
   7.1 Panduan Deploy Singkat  
   7.2 Rincian Peran Tim  
   7.3 Struktur Tabel Inti (Ringkasan)  
   7.4 Risiko & Mitigasi (Ringkas)  

---

## 1. Pendahuluan
### 1.1 Latar Belakang
Permintaan top up game cepat, murah, dan terpercaya meningkat seiring penetrasi game mobile. Pengguna membutuhkan platform yang:
- Menyediakan katalog game dan nominal top up jelas.  
- Mendukung berbagai metode pembayaran (VA bank, QRIS, e-wallet).  
- Menawarkan promo/loyalty yang mudah diterapkan.  
- Memiliki transparansi status transaksi dan bukti pembayaran.  

Deporte Store dikembangkan sebagai web app CI4 + Tailwind untuk memenuhi kebutuhan tersebut dengan alur pemesanan ringkas dan panel admin pengelolaan produk/promo/transaksi.

### 1.2 Rumusan Masalah
1) Bagaimana menyediakan alur top up dari pemilihan produk hingga pembayaran secara ringkas dan terukur?  
2) Bagaimana menerapkan promo/loyalty dengan aturan periode, batas pakai, dan minimal transaksi?  
3) Bagaimana memastikan status transaksi transparan bagi pengguna dan admin?

### 1.3 Tujuan dan Manfaat
- Membangun platform top up dengan autentikasi, katalog produk, perhitungan diskon & fee, invoice, serta status transaksi.  
- Memberi panel admin untuk CRUD game/produk/promo/transaksi.  
- Memberi pengalaman UI/UX responsif dengan TailwindCSS.  

### 1.4 Batasan Sistem
- Pembayaran masih **mock** (VA/QR/ewallet digenerate lokal, belum gateway).  
- Tidak ada OTP/2FA; keamanan dasar mengandalkan session dan hashing.  
- Pengiriman item game belum terintegrasi dengan API publisher; alur fulfillment bersifat simulasi.  

### 1.5 Metodologi Pengembangan
Pendekatan waterfall ringan: penggalian kebutuhan → SRS → desain (use case, ERD, activity) → implementasi CI4 + Tailwind → pengujian fungsional & UAT → panduan deployment.

---

## 2. Analisis Kebutuhan
### 2.1 Aktor
- **Pengunjung**: melihat katalog, detail game/produk.  
- **User terdaftar**: registrasi/login, membuat order, cek status, melihat riwayat, kelola profil.  
- **Admin**: login panel admin, kelola game/produk/promo, memonitor & mengubah status transaksi.  

### 2.2 Kebutuhan Fungsional (Ringkas)
- Registrasi/Login/Logout user (`Auth`).  
- Lihat katalog & detail game (`Home::index`, `Home::game`).  
- Buat transaksi top up: pilih produk, isi ID game, pilih metode bayar, input promo (`Order::create`).  
- Validasi promo & hitung diskon (`Order::checkPromo`, `PromoCodeModel::validateCode`).  
- Hitung fee pembayaran (`PaymentMethodModel::calculateFee`).  
- Generate invoice + VA/QR mock, set status pending + expired_at.  
- Cek status transaksi via invoice (`Order::status`, `Home::searchTransaction`).  
- Dashboard user: profil, riwayat transaksi, update profil/password (`Dashboard::*`).  
- Admin: login sederhana, CRUD game/produk/promo, update status transaksi (`Admin/*`).  

### 2.3 Kebutuhan Non-Fungsional
- Keamanan dasar: hashing password (model event), session, filter `auth`/`adminauth`.  
- Kinerja: query sederhana, limit di dashboard admin, expiry 60 menit untuk pending.  
- UI/UX: responsif TailwindCSS, CTA jelas, form validasi sisi server.  
- Portabilitas: PHP 8+/CI4, MySQL; konfigurasi via `.env`.  

### 2.4 Kebutuhan Data
Tabel utama: `users`, `games`, `products`, `payment_methods`, `promo_codes`, `transactions`, `admin_users`. Dump tersedia di `dep_orte_store.sql`.

---

## 3. Desain Sistem
### 3.1 Arsitektur & Pola
- **MVC CI4**: Controller mengelola alur, Model mengakses DB, View menggunakan Tailwind.  
- Routing terpusat di `app/Config/Routes.php`, grup auth dan admin memakai filter.  
- Session untuk autentikasi user dan admin.  

### 3.2 Use Case (narasi)
- UC1 Registrasi/Login User.  
- UC2 Melihat Katalog & Detail Game.  
- UC3 Membuat Transaksi Top Up (isi ID game, pilih bayar, promo).  
- UC4 Mengecek Status Transaksi via invoice.  
- UC5 Mengelola Profil & Poin.  
- UC6 Admin Kelola Game/Produk/Promo.  
- UC7 Admin Kelola Transaksi (ubah status/catatan).  

### 3.3 Activity / Alur Proses Utama
- **Order**: pilih produk → isi ID game → pilih metode bayar → input promo (opsional, validasi) → hitung total (harga - diskon + fee) → generate invoice & VA/QR → simpan transaksi status pending + expiry 60 menit → tampil halaman status.  
- **Cek Transaksi**: input invoice → redirect ke halaman status → jika pending dan lewat expiry, status diubah ke expired.  

### 3.4 ERD (narasi hubungan)
- `users` 1..* `transactions`  
- `games` 1..* `products` 1..* `transactions`  
- `payment_methods` 1..* `transactions`  
- `promo_codes` 0..* `transactions`  
- `admin_users` terpisah untuk panel admin  

### 3.5 Desain Antarmuka (ringkas)
- Landing: hero slider, flash sale, trending grid, kartu game.  
- Halaman game: daftar nominal, metode bayar, form ID game, input kode promo.  
- Halaman status: invoice, total bayar, VA/QR mock, status, waktu kedaluwarsa.  
- Dashboard user: ringkasan user & riwayat transaksi.  
- Admin: tabel transaksi, game, produk, promo; form tambah/ubah; aksi ubah status.  

---

## 4. Implementasi
### 4.1 Lingkungan & Teknologi
- PHP 8+, CodeIgniter 4, Composer.  
- TailwindCSS (asset di `public/assets/css`).  
- MySQL dengan dump `dep_orte_store.sql`.  
- Session native untuk auth.  

### 4.2 Struktur Direktori (ringkas)
- `app/Controllers`: `Home`, `Auth`, `Order`, `Dashboard`, `Admin/*`.  
- `app/Models`: `UserModel`, `GameModel`, `ProductModel`, `PaymentMethodModel`, `PromoCodeModel`, `TransactionModel`, `AdminUserModel`.  
- `app/Views`: `home`, `auth`, `dashboard`, `order`, `admin`, layout `layouts/main.php`.  
- `app/Config/Routes.php`: definisi routing & grup filter.  
- `public/assets`: CSS (Tailwind output), images (logo, game).  

### 4.3 Implementasi Backend (inti)
- **Routing** (`app/Config/Routes.php`): grup frontend, auth, dashboard (filter `auth`), admin (filter `adminauth`).  
- **Auth User** (`Auth`): validasi, hash password via model event, session set `user_id/username/email/logged_in`.  
- **Dashboard User** (`Dashboard`): ambil user + transaksi terbaru, update profil dengan validasi unik.  
- **Order & Pembayaran** (`Order`):  
  - Validasi input produk, user_game_id, payment_method.  
  - Ambil produk + harga (diskon jika ada), terapkan promo (`PromoCodeModel::validateCode`).  
  - Hitung fee tunggal (`PaymentMethodModel::calculateFee`), total = harga - diskon + fee.  
  - Poin: `floor(total/1000)` jika login.  
  - Generate invoice `INVyyyymmddXXXXXX`, set expired_at +60 menit.  
  - Generate VA number mock atau QR code mock tergantung tipe pembayaran.  
  - Simpan transaksi status `pending`; jika promo dipakai, increment usage.  
  - Halaman status auto-set expired jika melewati waktu.  
- **Promo** (`PromoCodeModel`): cek aktif, periode, usage_limit, min_transaction; tipe percentage/fixed dengan max_discount.  
- **Pembayaran** (`PaymentMethodModel`): fee satu kolom; tipe `va|qris|ewallet`.  
- **Admin** (`Admin\Auth`, `Admin\Dashboard`, `Admin\Games`, `Admin\Products`, `Admin\Promos`, `Admin\Transactions`): login sederhana, CRUD data, ubah status transaksi, statistik harian.  

### 4.4 Implementasi Frontend (Tailwind Views)
- **Landing** (`home/index.php`): hero slider, flash sale, trending, kartu game, CTA.  
- **Detail Game** (`home/game.php`): daftar produk, metode bayar, form ID game, input promo, tombol order.  
- **Auth** (`auth/login.php`, `auth/register.php`): form Tailwind, validasi server-side.  
- **Dashboard** (`dashboard/index.php`, `dashboard/transactions.php`, `dashboard/profile.php`): tabel riwayat, form profil.  
- **Order Status** (`order/status.php`): tampil invoice, total, VA/QR, status, waktu kedaluwarsa.  
- **Admin Views** (`admin/*`): tabel transaksi/game/produk/promo, form tambah/ubah.  

### 4.5 Keamanan & Session
- Password di-hash otomatis di `UserModel` sebelum insert/update.  
- Filter `auth` untuk dashboard user, `adminauth` untuk admin.  
- Session key user: `user_id/username/email/logged_in`; admin: `admin_logged_in`.  
- Belum ada CSRF token di form Ajax promo; dapat ditambah di tahap lanjut.  

### 4.6 Konfigurasi Database
- Gunakan `.env` untuk koneksi DB.  
- Import `dep_orte_store.sql` agar tabel dan seed contoh tersedia (games, products, payment_methods, promo_codes, admin_users contoh).  

---

## 5. Pengujian
### 5.1 Strategi
- Uji fungsional manual untuk alur utama.  
- Skeleton test CI ada di `tests/`, bisa diperluas.  

### 5.2 Skenario Uji Fungsional (ringkas)
1. **Registrasi & Login**: input valid/invalid, verifikasi hashing & session.  
2. **Lihat Katalog & Detail Game**: pastikan hanya produk aktif tampil, harga & diskon benar.  
3. **Order Tanpa Promo**: isi ID, pilih bayar, submit; cek invoice, total = price + fee.  
4. **Order Dengan Promo Valid**: diskon diterapkan (percent/fixed), tidak melebihi max_discount; total = price - discount + fee.  
5. **Promo Invalid**: periode habis, usage_limit, atau min_transaction tidak terpenuhi menolak dengan pesan.  
6. **Expiry**: set waktu >60 menit, status berubah ke expired saat buka halaman status.  
7. **Dashboard User**: riwayat menampilkan transaksi terbaru.  
8. **Admin Ubah Status**: status berubah dan tercermin di halaman status user.  
9. **CRUD Game/Produk/Promo**: tambah/ubah/hapus, cek efek di katalog dan validasi aktif/nonaktif.  

### 5.3 UAT (User Acceptance Test)
- Lakukan 2–3 game berbeda, variasi metode bayar (VA, QRIS, e-wallet), dengan/ tanpa promo.  
- Verifikasi pesan error input kosong/salah muncul jelas.  
- Pastikan total dan fee konsisten dengan skenario.  

### 5.4 Catatan Temuan & Risiko Sisa
- Pembayaran masih mock; risiko mismatch dengan gateway nyata.  
- Admin login hardcoded; perlu hardening (DB + hash + 2FA).  
- CSRF di form Ajax promo perlu ditambahkan.  

---

## 6. Penutup
### 6.1 Kesimpulan
Sistem memenuhi alur utama top up: katalog, promo, perhitungan fee, invoice, poin loyalti, serta panel admin. Struktur CI4 memudahkan perluasan ke gateway pembayaran nyata dan integrasi publisher.

### 6.2 Saran Pengembangan
- Integrasi payment gateway + webhook untuk auto-update status.  
- Integrasi API publisher game untuk fulfillment otomatis.  
- Pengamanan: 2FA admin, rate limiting, CSRF di Ajax, audit log.  
- Automasi test: unit/feature untuk promo, fee, expiry, auth.  

---

## 7. Lampiran
### 7.1 Panduan Deploy Singkat
1. `composer install`  
2. Salin `.env` contoh, isi DB (MySQL).  
3. Import `dep_orte_store.sql`.  
4. Set `baseURL` dan kredensial DB di `.env`.  
5. Jalankan `php spark serve`.  

### 7.2 Rincian Peran Tim
- **Farid – Project Manager & Backend**: scope, timeline/Kanban, arsitektur backend CI4, auth, payment flow, promo logic, integrasi checkout, deployment & dokumentasi teknis backend.  
- **Shiddiq – Frontend & Backend**: Tailwind UI → CI4 views, login/register UI, kartu promo/loyalty, routing frontend, integrasi checkout + form input, bantu modul produk & order backend.  
- **Yusuf – System Analyst & Frontend**: kebutuhan fungsional/non-fungsional, SRS, use case/ERD/flow, komponen UI & halaman detail produk.  
- **Richard – Dokumentasi & System Analyst**: SRS, Use Case, Activity/DFD (jika perlu), laporan akhir, user/admin manual, validasi konsistensi dokumen vs implementasi.  

### 7.3 Struktur Tabel Inti (Ringkas)
- `users(id, username, email, password, phone, points, total_transactions, created_at, updated_at)`  
- `admin_users(id, username, password, created_at)`  
- `games(id, name, slug, image, category, is_popular, is_active, created_at, updated_at)`  
- `products(id, game_id, name, description, price, discount_price, category, is_popular, is_active, created_at, updated_at)`  
- `payment_methods(id, name, type[va/qris/ewallet], code, icon, fee, is_active, created_at, updated_at)`  
- `promo_codes(id, code, type[percentage/fixed], value, min_transaction, max_discount, usage_limit, used_count, valid_from, valid_until, is_active)`  
- `transactions(id, user_id?, invoice_number, game_id, product_id, user_game_id, payment_method_id, promo_code_id?, amount, discount, fee, total_payment, status, payment_proof, qr_code, va_number, points_earned, admin_notes, expired_at, paid_at, completed_at, created_at, updated_at)`  

### 7.4 Risiko & Mitigasi (Ringkas)
- Pembayaran mock → Integrasi gateway + webhook, sandbox dulu.  
- Admin auth hardcoded → Pindah ke tabel admin_users, hash, role/permission, 2FA.  
- CSRF & rate limiting → Aktifkan CSRF CI4, middleware rate limit untuk endpoint form/Ajax.  
- Audit trail minim → Tambah log aksi admin & perubahan status transaksi.  

---

**Catatan**: Dokumen ini disusun spesifik berdasarkan kode pada repositori `dep-orte-store` (CI4 + Tailwind). Format dapat disesuaikan dengan template laporan RPL Anda.  
