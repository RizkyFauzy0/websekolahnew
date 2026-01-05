# Quick Installation Guide - Website Sekolah

## Persiapan

1. **Install XAMPP/WAMP/LAMP**
   - Download XAMPP: https://www.apachefriends.org/
   - Install dan jalankan Apache dan MySQL

2. **Ekstrak Project**
   - Ekstrak project ke folder `htdocs` (XAMPP) atau `www` (WAMP)
   - Path: `C:\xampp\htdocs\websekolahnew`

## Setup Database (5 Menit)

1. **Buka phpMyAdmin**
   - URL: http://localhost/phpmyadmin
   
2. **Buat Database Baru**
   - Klik "New" di sidebar
   - Nama database: `school_website`
   - Collation: `utf8mb4_unicode_ci`
   - Klik "Create"

3. **Import Database**
   - Pilih database `school_website`
   - Klik tab "Import"
   - Choose file: pilih `database.sql`
   - Klik "Go"

## Konfigurasi (2 Menit)

1. **Copy Config File**
   - Copy `config.example.php` → `config.php`
   
2. **Edit Config**
   - Buka `config.php`
   - Sesuaikan:
     ```php
     define('DB_HOST', 'localhost');
     define('DB_USER', 'root');
     define('DB_PASS', '');  // Kosong untuk XAMPP default
     define('DB_NAME', 'school_website');
     define('BASE_URL', 'http://localhost/websekolahnew');
     ```

## Akses Website

### Website Public
- URL: http://localhost/websekolahnew/
- Halaman akan muncul dengan data sample

### Admin Panel
- URL: http://localhost/websekolahnew/admin/
- Username: `admin`
- Password: `admin123`

## Selesai! 🎉

Sekarang Anda bisa:
1. Login ke admin panel
2. Ubah pengaturan sekolah
3. Upload logo dan slider
4. Tambah berita, guru, galeri, dll.

## Troubleshooting

**Error "Configuration file not found"**
- Pastikan file `config.php` sudah dibuat (copy dari `config.example.php`)

**Error "Connection failed"**
- Pastikan MySQL sudah running di XAMPP
- Cek username/password di `config.php`

**Gambar tidak muncul**
- Pastikan folder `uploads/` ada
- Cek BASE_URL di `config.php` sudah benar

**Upload error**
- Pastikan folder `uploads/` writable (chmod 755)
- Windows: klik kanan folder → Properties → Security → Edit permissions

## Tips

1. **Ganti Password Admin**
   - Buka database `users` table
   - Update password dengan:
     ```sql
     UPDATE users SET password = '$2y$10$[hash_baru]' WHERE id = 1;
     ```

2. **Upload Logo Sekolah**
   - Login admin → Pengaturan Sekolah
   - Upload logo (format PNG/JPG, max 2MB)

3. **Setup Google Maps**
   - Buka Google Maps → cari lokasi
   - Klik Share → Embed → Copy iframe
   - Paste ke Pengaturan Sekolah

---

**Butuh bantuan?** 
Buka file README.md untuk dokumentasi lengkap.
