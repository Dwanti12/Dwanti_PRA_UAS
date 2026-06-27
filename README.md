# 🌸 Toko Bunga - Sistem Manajemen Data Bunga
**Project Pemrograman Web 2**

## Teknologi
- PHP Native
- MySQL
- Bootstrap 5
- FPDF (untuk Report PDF)

## Cara Instalasi

### 1. Persiapan
- Install XAMPP di komputer kamu
- Download project ini

### 2. Letakkan Project
- Copy folder `toko-bunga` ke `C:/xampp/htdocs/`

### 3. Setup Database
- Buka `http://localhost/phpmyadmin`
- Klik **Import** → pilih file `database.sql`
- Klik **Go**

### 4. Download FPDF (untuk Report PDF)
- Download dari http://www.fpdf.org
- Ekstrak, copy file `fpdf.php` ke folder `fpdf/`

### 5. Jalankan Aplikasi
- Buka `http://localhost/toko-bunga`
- Login dengan: **admin / admin123**

## Fitur
- ✅ Login & Logout
- ✅ Dashboard dengan statistik
- ✅ Tambah Data Bunga (+ Upload Gambar)
- ✅ Tampil Data dengan Search & Filter
- ✅ Edit Data Bunga
- ✅ Hapus Data Bunga
- ✅ Report PDF (butuh library FPDF)

## Struktur Database
Tabel `bunga`:
| Kolom | Tipe | Keterangan |
|---|---|---|
| kode_bunga | VARCHAR(20) | Kode unik |
| nama_bunga | VARCHAR(100) | Nama bunga |
| kategori | VARCHAR(50) | Jenis bunga |
| harga | DECIMAL(10,2) | Harga per tangkai |
| stok | INT | Jumlah stok |
| keterangan | TEXT | Deskripsi |
| tanggal_input | DATE | Tanggal input |
| gambar | VARCHAR(255) | Path foto bunga |

## Login Default
- Username: `admin`
- Password: `admin123`
