-- Jalankan file ini di phpMyAdmin
CREATE DATABASE IF NOT EXISTS toko_bunga;
USE toko_bunga;

CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL,
  password VARCHAR(255) NOT NULL
);

INSERT INTO users (username, password) VALUES ('admin', MD5('admin123'));

CREATE TABLE IF NOT EXISTS bunga (
  id INT AUTO_INCREMENT PRIMARY KEY,
  kode_bunga VARCHAR(20) NOT NULL UNIQUE,
  nama_bunga VARCHAR(100) NOT NULL,
  kategori VARCHAR(50) NOT NULL,
  harga DECIMAL(10,2) NOT NULL,
  stok INT NOT NULL DEFAULT 0,
  keterangan TEXT,
  tanggal_input DATE NOT NULL,
  gambar VARCHAR(255),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO bunga (kode_bunga, nama_bunga, kategori, harga, stok, keterangan, tanggal_input, gambar) VALUES
('BNG001', 'Mawar Merah', 'Mawar', 25000, 100, 'Mawar merah segar pilihan', '2026-06-01', 'default.jpg'),
('BNG002', 'Tulip Kuning', 'Tulip', 35000, 50, 'Tulip kuning cerah dari Belanda', '2026-06-05', 'default.jpg'),
('BNG003', 'Lily Putih', 'Lily', 30000, 75, 'Lily putih elegan untuk dekorasi', '2026-06-10', 'default.jpg');
