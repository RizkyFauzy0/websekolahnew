# Website Sekolah - Modern School Website

Website Sekolah berbasis PHP dengan Tailwind CSS yang modern, responsive, dan mobile-friendly.

## Fitur Utama

### Halaman Public
- **Dashboard/Landing Page** - Dengan slider carousel, berita terbaru, statistik siswa, daftar guru, dan kontak
- **Profil Sekolah** - Visi Misi, Sejarah, Struktur Organisasi, dan Keunggulan
- **Berita Sekolah** - Sistem berita lengkap dengan detail dan pagination
- **Galeri** - Foto dan Video sekolah
- **Prestasi** - Prestasi Siswa, Guru, dan Sekolah
- **Download** - File dan dokumen yang bisa diunduh
- **Link Aplikasi** - Kumpulan link aplikasi terkait sekolah
- **Kontak** - Informasi kontak dan Google Maps

### Panel Admin
- Dashboard dengan statistik
- CRUD lengkap untuk semua konten:
  - Pengaturan Sekolah (Logo, Nama, Alamat, Maps, dll)
  - Slider/Carousel
  - Berita
  - Data Guru
  - Profil Sekolah
  - Galeri (Foto & Video)
  - Prestasi
  - Download
  - Link Aplikasi
- Upload gambar dan file
- Authentication system (Login/Logout)

## Teknologi yang Digunakan

- **Backend**: PHP 7.4+ (Native/Vanilla PHP)
- **Frontend**: HTML5, Tailwind CSS (via CDN), JavaScript
- **Database**: MySQL 5.7+
- **Icons**: Font Awesome 6.4.0
- **Design**: Responsive, Mobile-first approach

## Instalasi

### Persyaratan Sistem
- PHP 7.4 atau lebih tinggi
- MySQL 5.7 atau lebih tinggi
- Web Server (Apache/Nginx)
- PHP Extensions: mysqli, gd (untuk image upload)

### Langkah Instalasi

1. **Clone atau Download Repository**
   ```bash
   git clone https://github.com/RizkyFauzy0/websekolahnew.git
   cd websekolahnew
   ```

2. **Setup Database**
   - Buat database baru di MySQL:
     ```sql
     CREATE DATABASE school_website;
     ```
   - Import file `database.sql`:
     ```bash
     mysql -u root -p school_website < database.sql
     ```

3. **Konfigurasi Database**
   - Copy file `config.example.php` menjadi `config.php`:
     ```bash
     cp config.example.php config.php
     ```
   - Edit file `config.php` dan sesuaikan dengan konfigurasi database Anda:
     ```php
     define('DB_HOST', 'localhost');
     define('DB_USER', 'root');          // sesuaikan dengan username MySQL
     define('DB_PASS', '');              // sesuaikan dengan password MySQL
     define('DB_NAME', 'school_website');
     
     define('BASE_URL', 'http://localhost/websekolahnew'); // sesuaikan dengan URL
     ```

4. **Set Permission untuk Upload Directory**
   ```bash
   chmod -R 755 uploads/
   ```

5. **Akses Website**
   - Website Public: `http://localhost/websekolahnew/`
   - Admin Panel: `http://localhost/websekolahnew/admin/`

## Login Admin

Default credentials:
- **Username**: `admin`
- **Password**: `admin123`

⚠️ **PENTING**: Segera ubah password default setelah login pertama kali!

## Struktur Direktori

```
websekolahnew/
├── admin/                  # Panel admin
│   ├── includes/          # Admin layout templates
│   ├── index.php          # Admin dashboard
│   ├── login.php          # Admin login
│   ├── settings.php       # Pengaturan sekolah
│   ├── sliders.php        # Kelola slider
│   ├── news.php           # Kelola berita
│   ├── teachers.php       # Kelola guru
│   ├── profile.php        # Kelola profil
│   ├── gallery.php        # Kelola galeri
│   ├── achievements.php   # Kelola prestasi
│   ├── downloads.php      # Kelola download
│   └── links.php          # Kelola link aplikasi
├── includes/              # Core PHP files
│   ├── db.php            # Database functions
│   ├── auth.php          # Authentication functions
│   ├── header.php        # Public header
│   └── footer.php        # Public footer
├── uploads/               # Upload directory (images, files)
│   ├── sliders/
│   ├── news/
│   ├── teachers/
│   ├── gallery/
│   └── downloads/
├── index.php              # Homepage
├── news.php               # News listing
├── news-detail.php        # News detail
├── profile.php            # Profile pages
├── gallery.php            # Gallery pages
├── achievements.php       # Achievements pages
├── downloads.php          # Downloads page
├── links.php              # Links page
├── contact.php            # Contact page
├── database.sql           # Database schema
├── config.example.php     # Example config file
└── README.md              # This file
```

## Penggunaan

### Mengelola Konten

1. **Login ke Admin Panel**
   - Buka `/admin/login.php`
   - Login dengan credentials default

2. **Mengatur Informasi Sekolah**
   - Menu: Pengaturan Sekolah
   - Upload logo, isi nama sekolah, alamat, kontak, dan embed Google Maps

3. **Menambah Slider**
   - Menu: Slider → Tambah Slider
   - Upload gambar (optimal: 1920x1080px)
   - Isi judul dan deskripsi

4. **Menambah Berita**
   - Menu: Berita → Tambah Berita
   - Isi judul, konten, excerpt, upload gambar
   - Set tanggal publikasi dan status

5. **Menambah Guru**
   - Menu: Guru → Tambah Guru
   - Upload foto, isi nama dan mata pelajaran

6. **Mengelola Galeri**
   - Menu: Galeri
   - Pilih tab Foto atau Video
   - Upload foto atau masukkan URL YouTube untuk video

### Google Maps Integration

Untuk menampilkan peta lokasi sekolah:

1. Buka [Google Maps](https://maps.google.com)
2. Cari lokasi sekolah Anda
3. Klik "Share" → "Embed a map"
4. Copy kode iframe
5. Paste ke field "Google Maps Embed Code" di Pengaturan Sekolah

## Keamanan

- Password di-hash menggunakan PHP `password_hash()` dengan bcrypt
- Input validation dan sanitization
- SQL injection protection dengan prepared statements dan escaping
- Session management untuk authentication
- File upload validation

## Customization

### Mengubah Warna Tema

Website ini menggunakan Tailwind CSS. Untuk mengubah warna utama:

1. Buka file yang ingin diubah (contoh: `index.php`)
2. Cari class warna Tailwind seperti `bg-blue-600`, `text-blue-600`
3. Ganti dengan warna lain: `bg-green-600`, `bg-purple-600`, dll.

### Menambah Menu Navigation

Edit file `includes/header.php` untuk menambah menu baru di navigation bar.

## Troubleshooting

### Error "Connection failed"
- Pastikan MySQL service running
- Cek konfigurasi database di `config.php`
- Pastikan database sudah dibuat dan di-import

### Upload File Gagal
- Cek permission folder `uploads/` (chmod 755 atau 777)
- Cek setting PHP `upload_max_filesize` dan `post_max_size` di `php.ini`

### Halaman Admin tidak bisa diakses
- Pastikan sudah login
- Clear browser cache dan cookies
- Cek session configuration di PHP

### Gambar tidak muncul
- Pastikan path BASE_URL di `config.php` sudah benar
- Cek apakah file gambar ada di folder `uploads/`
- Cek permission folder uploads

## Browser Support

- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)
- Mobile browsers (iOS Safari, Chrome Mobile)

## Responsive Design

Website ini fully responsive dengan breakpoints:
- Mobile: < 768px (Hamburger menu)
- Tablet: 768px - 1024px
- Desktop: > 1024px

## Credits

- **Tailwind CSS**: https://tailwindcss.com
- **Font Awesome**: https://fontawesome.com

## License

This project is open source. Feel free to use and modify.

## Support

Untuk pertanyaan atau bantuan, silakan buka issue di GitHub repository.

---

**Developed with ❤️ for Indonesian Schools**
