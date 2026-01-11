# FIX DATABASE CONNECTION

## Masalah
Error: `SQLSTATE[HY000] [2002] No connection could be made because the target machine actively refused it`

Ini berarti **MySQL/MariaDB di XAMPP tidak running**.

## Solusi

### 1. Start MySQL di XAMPP
1. Buka **XAMPP Control Panel**
2. Klik **Start** pada **MySQL** (harus berubah jadi hijau)
3. Pastikan status MySQL menunjukkan "Running"

### 2. Cek Konfigurasi Database
Pastikan file `.env` memiliki konfigurasi berikut:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sipensi
DB_USERNAME=root
DB_PASSWORD=
```

**Catatan:** 
- Ganti `sipensi` dengan nama database yang benar jika berbeda
- Jika ada password MySQL, isi di `DB_PASSWORD`

### 3. Test Koneksi
Jalankan command berikut untuk test koneksi:

```bash
php artisan tinker
```

Lalu ketik:
```php
DB::connection()->getPdo();
```

Jika berhasil, akan muncul info koneksi. Jika error, berarti MySQL belum running atau konfigurasi salah.

### 4. Refresh Browser
Setelah MySQL running, refresh browser dan semua halaman seharusnya sudah bisa konek ke database.

## Yang Sudah Diperbaiki
✅ Semua controller sudah ditambahkan error handling untuk database
✅ Jika database tidak konek, halaman tetap bisa diakses (dengan data kosong)
✅ Tidak akan muncul error fatal, hanya data kosong

