# SPACENEST — CodeIgniter 4

Sistem Pelaporan & Monitoring Fasilitas FTST UNIVERSITAS MUHAMMADIYAH INDONESIA, kini berbasis **CodeIgniter 4.7.6** (MVC).

## Cara menjalankan

1. **Syarat:** PHP 8.2+ dengan ekstensi `intl`, `mbstring`, `mysqli`; MySQL/MariaDB.
   > Pengguna **XAMPP**: buka `php.ini`, hapus tanda `;` pada baris `;extension=intl`, lalu restart Apache.
2. **Database:** import `database.sql` lewat phpMyAdmin (atau `mysql -u root < database.sql`).
3. **Konfigurasi:** edit file `.env`
   - `app.baseURL` → alamat web Anda
   - `database.default.*` → user/password MySQL
   - `admin.registrationCode` → **ganti** dengan kode rahasia Anda sendiri
4. **Jalankan:**
   ```
   php spark serve
   ```
   lalu buka http://localhost:8080
   (XAMPP: letakkan folder di `htdocs`, set `app.baseURL = 'http://localhost/spacenest/public/'`)

Untuk menambahkan 20 laporan contoh beserta riwayat statusnya ke database pengembangan, jalankan:
```
php spark db:seed DummyReportsSeeder
```
Seeder memakai akun pelapor yang sudah ada dan tidak menggandakan data jika laporan dummy sudah pernah dimasukkan.

Akun contoh (password: `password123`):
- Pelapor: `mahasiswa@unisma.ac.id`
- Admin: `admin.ftst@unisma.ac.id`

## Pemetaan file lama → CodeIgniter

| File lama | Sekarang |
|---|---|
| `config/database.php` | `.env` (`database.default.*`) |
| `login.php`, `register.php`, `logout.php`, `admin_register.php` | `app/Controllers/Auth.php` + `app/Views/auth/` |
| `index.php` | `app/Controllers/Dashboard.php` + `app/Views/dashboard/index.php` |
| `laporan_buat.php`, `laporan_detail.php` | `app/Controllers/Laporan.php` + `app/Views/laporan/` |
| `admin_dashboard.php` | `app/Controllers/Admin.php` + `app/Views/admin/dashboard.php` |
| `includes/header.php` + `footer.php` | `app/Views/layouts/main.php` |
| query SQL di tiap halaman | `app/Models/` (User, Report, StatusLog) |
| cek `$_SESSION` di tiap halaman | `app/Filters/` (auth, admin, guest) |
| — | `app/Config/Routes.php` (semua URL) |

## URL baru

`/login` · `/register` · `/logout` · `/` (dashboard) · `/laporan/buat` · `/laporan/{id}` · `/admin` · `/admin/register`

## Peningkatan keamanan yang ikut ditambahkan

- **CSRF protection** aktif untuk semua form.
- **Registrasi admin dikunci** dengan kode rahasia (sebelumnya siapa saja bisa membuat akun admin).
- **Upload foto divalidasi dari isi berkas** (JPG/PNG/WEBP, maks 4 MB); nama & ekstensi ditentukan server; folder `public/uploads` dilarang menjalankan skrip.
- Status laporan divalidasi terhadap daftar yang diizinkan.
- Semua output di-escape (`esc()`), session di-regenerate saat login.
- Hash password pada `database.sql` lama tidak cocok dengan `password123`; sudah diganti hash yang valid.

## Produksi

Ubah `CI_ENVIRONMENT = production` di `.env` dan arahkan document root web server ke folder `public/`.

> Dependensi framework (`laminas-escaper`, `psr/log`) sudah disertakan di `app/ThirdParty/` sehingga **tidak perlu Composer**.
> Jika Anda lebih suka Composer, jalankan `composer install` lalu hapus folder `app/ThirdParty` beserta 2 baris namespace-nya di `app/Config/Autoload.php`.
