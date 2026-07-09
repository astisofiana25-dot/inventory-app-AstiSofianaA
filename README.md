# Inventory App Telkom

Aplikasi manajemen inventaris berbasis web yang dibangun menggunakan framework Laravel dan Vite.

---

## Prasyarat Sistem

Sebelum memulai instalasi, pastikan sistem Anda sudah terinstal:
- **PHP** (minimal versi 8.1 atau 8.2)
- **Composer** (untuk manajemen dependensi PHP)
- **MySQL / MariaDB** (melalui XAMPP)
- **Node.js & NPM** (untuk kompilasi asset frontend/Vite)

---

## Langkah Instalasi

1. **Buka folder proyek** Anda menggunakan Terminal/Command Prompt/PowerShell.

2. **Salin file konfigurasi lingkungan (.env)**:
   ```bash
   cp .env.example .env
   ```
   *Di Windows PowerShell/CMD, jika perintah di atas tidak berfungsi:*
   ```powershell
   copy .env.example .env
   ```

3. **Sesuaikan konfigurasi database** di file `.env` yang baru dibuat:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=sim_inventaris
   DB_USERNAME=root
   DB_PASSWORD=
   ```

4. **Instal Dependensi PHP (Composer)**:
   ```bash
   composer install
   ```

5. **Generate Application Key**:
   ```bash
   php artisan key:generate
   ```

6. **Buat Database Baru**:
   Buat database kosong bernama `inventory_telkom` (atau sesuai konfigurasi `.env` Anda) melalui **phpMyAdmin** (`http://localhost/phpmyadmin`).

7. **Jalankan Migrasi & Seeder Database**:
   Jalankan perintah berikut untuk membuat tabel dan mengisi data awal (seperti roles, kategori, dan akun uji coba):
   ```bash
   php artisan migrate --seed
   ```

8. **Instal & Jalankan Asset Frontend (Vite)**:
   ```bash
   npm install
   npm run dev
   ```

---

## Cara Menjalankan Project

1. Pastikan **Apache** dan **MySQL** di panel kontrol **XAMPP** sudah menyala (Start).
2. Jalankan server lokal Laravel dengan perintah:
   ```bash
   php artisan serve
   ```
3. Buka browser dan akses aplikasi melalui URL:
   [http://127.0.0.1:8000](http://127.0.0.1:8000)

---

## Akun Login untuk Testing

Berikut adalah akun yang telah disediakan secara otomatis dari proses seeder untuk uji coba aplikasi:

### 1. Akun Demo (`UserSeeder`)

| Nama | Email / Username | Role | Password |
|---|---|---|---|
| **Admin** | `admintelkomreg3@gmail.com` | Admin | `passadmin123` |
| **Staff ** | `astisofas123@gmail.com` | Staff | `12345678` |
| **Manager Kantor** | `astisofiana25@gmail.com` | Manager | `12345678` |

---

## Cara Register Akun Baru

Jika Anda ingin mencoba alur pendaftaran user baru, ikuti langkah berikut:

1. Buka halaman register melalui link **Daftar** di halaman login.
2. Isi data berikut:
   - **Nama Lengkap**: isi nama Anda.
   - **Email**: gunakan email Gmail yang belum terdaftar.
   - **ID Karyawan**: masukkan salah satu ID karyawan yang tersedia dan belum dipakai.
   - **Password**: buat password yang aman.
   - **Konfirmasi Password**: ulangi password.
3. Klik tombol **Daftar**.
4. Setelah berhasil, sistem akan otomatis login ke akun Anda dan mengarahkan ke dashboard.

### ID Karyawan yang Bisa Dicoba

Berikut contoh ID karyawan yang tersedia di sistem dan masih bisa dipakai untuk testing register pada kondisi awal (belum ada akun user yang memakai ID tersebut):

| Role | ID Karyawan yang Bisa Dicoba |
|---|---|
| **Staff** | `EMP007`, `EMP009`, `EMP010`, `EMP011`, `EMP012`, `EMP013` |

| **Manager** | `EMP006`, `EMP008`, `EMP014`, `EMP015`, `EMP016`, `EMP017`, `EMP018` |

> Tip: setelah salah satu ID dipakai untuk register, ID tersebut tidak bisa dipakai lagi oleh user lain karena sistem akan menolak ID yang sudah terdaftar.



