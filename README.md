# 🎟️ TicketIn — Laravel 11

Platform tiket event modern. Migrasi dari PHP Native + MySQLi ke **Laravel 11 + Breeze**.

---

## 🚀 Cara Install Cepat

```bash
# 1. Masuk ke folder project
cd ticketin-laravel

# 2. Jalankan installer otomatis
bash install.sh
```

---

## 📋 Install Manual (Step by Step)

### Prasyarat
- PHP 8.2+
- Composer
- MySQL 5.7+ / MariaDB
- Node.js (opsional, untuk Vite)

### Langkah-langkah

```bash
# 1. Install dependencies
composer install

# 2. Salin .env
cp .env.example .env

# 3. Generate app key
php artisan key:generate

# 4. Edit konfigurasi database di .env
#    DB_DATABASE=ticketin_db
#    DB_USERNAME=root
#    DB_PASSWORD=password_kamu

# 5. Buat database di MySQL
#    CREATE DATABASE ticketin_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

# 6. Jalankan migrasi + seeder
php artisan migrate --seed

# 7. Buat symlink storage (untuk upload gambar)
php artisan storage:link

# 8. Jalankan server development
php artisan serve
```

Buka browser: **http://localhost:8000**

---

## 👥 Akun Default (dari Seeder)

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@ticketin.id | password |
| Pengelola EO | eo@ticketin.id | password |
| User Biasa | user@ticketin.id | password |

---

## 📁 Struktur Project

```
ticketin-laravel/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/
│   │   │   │   ├── AuthenticatedSessionController.php  ← Login/Logout
│   │   │   │   └── RegisteredUserController.php        ← Register
│   │   │   ├── DashboardController.php                 ← Beranda publik
│   │   │   ├── EventController.php                     ← Daftar & detail event
│   │   │   ├── CheckoutController.php                  ← Beli tiket
│   │   │   └── PengelolaController.php                 ← CRUD event (EO)
│   │   ├── Middleware/
│   │   │   └── PengelolaMiddleware.php                 ← Guard role pengelola
│   │   └── Requests/Auth/
│   │       └── LoginRequest.php
│   ├── Models/
│   │   ├── User.php
│   │   ├── Event.php
│   │   ├── TicketCategory.php
│   │   ├── EventLineup.php
│   │   ├── EventFaq.php
│   │   ├── Order.php
│   │   └── OrderItem.php
│   └── Providers/
│       └── AppServiceProvider.php
├── database/
│   ├── migrations/
│   │   ├── ..._create_users_table.php
│   │   ├── ..._create_events_table.php
│   │   └── ..._create_ticket_tables.php
│   └── seeders/
│       └── DatabaseSeeder.php                          ← Data contoh 6 event
├── resources/views/
│   ├── layouts/
│   │   ├── app.blade.php                               ← Layout utama
│   │   └── auth.blade.php                              ← Layout login/register
│   ├── auth/
│   │   ├── login.blade.php
│   │   └── register.blade.php
│   ├── dashboard/
│   │   └── index.blade.php                             ← Beranda (slider + events)
│   ├── events/
│   │   ├── index.blade.php                             ← Daftar event
│   │   └── show.blade.php                              ← Detail event
│   ├── checkout/
│   │   ├── pilih-tiket.blade.php
│   │   ├── index.blade.php                             ← Konfirmasi & bayar
│   │   └── sukses.blade.php
│   ├── pengelola/
│   │   ├── dashboard.blade.php                         ← Dashboard EO
│   │   └── form-event.blade.php                        ← Tambah/Edit event
│   ├── pages/
│   │   └── hubungi.blade.php
│   ├── partials/
│   │   └── event-card.blade.php
│   └── errors/
│       ├── 403.blade.php
│       ├── 404.blade.php
│       └── 500.blade.php
├── routes/
│   └── web.php
├── config/
│   ├── app.php
│   ├── auth.php
│   ├── database.php
│   ├── filesystems.php
│   └── session.php
├── lang/id/
│   ├── auth.php
│   └── validation.php
├── .env.example
├── artisan
├── composer.json
├── install.sh                                          ← Installer otomatis
└── README.md
```

---

## 🗺️ Daftar Route

| Method | URL | Name | Keterangan |
|--------|-----|------|------------|
| GET | `/` | — | Redirect ke beranda |
| GET | `/dashboard` | `dashboard` | Beranda publik |
| GET | `/events` | `events.index` | Daftar semua event |
| GET | `/events/{id}` | `events.show` | Detail event |
| GET | `/hubungi` | `hubungi` | Halaman kontak |
| GET | `/register` | `register` | Form daftar |
| POST | `/register` | — | Proses daftar |
| GET | `/login` | `login` | Form login |
| POST | `/login` | — | Proses login |
| POST | `/logout` | `logout` | Logout |
| GET | `/events/{id}/pilih-tiket` | `events.pilih-tiket` | Pilih tiket (auth) |
| POST | `/events/{id}/keranjang` | `checkout.keranjang` | Simpan keranjang (auth) |
| GET | `/checkout/{id}` | `checkout.show` | Halaman checkout (auth) |
| POST | `/checkout/{id}/proses` | `checkout.proses` | Bayar (auth) |
| GET | `/checkout/sukses` | `checkout.sukses` | Sukses beli (auth) |
| GET | `/pengelola` | `pengelola.dashboard` | Dashboard EO |
| GET | `/pengelola/event/tambah` | `pengelola.event.create` | Form tambah event |
| POST | `/pengelola/event` | `pengelola.event.store` | Simpan event baru |
| GET | `/pengelola/event/{id}/edit` | `pengelola.event.edit` | Form edit event |
| PUT | `/pengelola/event/{id}` | `pengelola.event.update` | Update event |
| DELETE | `/pengelola/event/{id}` | `pengelola.event.destroy` | Hapus event |

---

## ✅ Apa yang Sudah Dimigrasi

| PHP Native | Laravel 11 |
|-----------|------------|
| `session_start()` + `$_SESSION` | `Auth::check()`, `auth()->user()` |
| `mysqli_connect()` | Eloquent ORM + Query Builder |
| `mysqli_query()` SQL manual | Model relationships |
| SQL injection (string concat) | Parameterized queries otomatis |
| Manual file upload | `$request->file()->store()` |
| Manual redirect `header()` | `return redirect()->route()` |
| Inline HTML + PHP campur | Blade templating |
| Manual session auth check | Middleware `auth` + `PengelolaMiddleware` |
| Tidak ada validasi | Form Request validation |

---

## 🔧 Konfigurasi Tambahan (Opsional)

### Upload Gambar
Gambar event disimpan di `storage/app/public/events/` dan diakses via `/storage/events/`.
Pastikan sudah menjalankan `php artisan storage:link`.

### Production
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

### Reset Database
```bash
php artisan migrate:fresh --seed
```

---

## 📝 Catatan

- Locale diset ke `id` (Indonesia) otomatis — `translatedFormat()` sudah dalam Bahasa Indonesia
- Semua CSS menggunakan Tailwind CDN (tidak perlu build step)
- Biaya layanan checkout: 5% dari subtotal (dapat diubah di `CheckoutController`)
- Upload gambar: maks 5MB, format JPG/PNG/WebP
