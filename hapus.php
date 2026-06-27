<?php
session_start();
if (!isset($_SESSION['user'])) { header("Location: ../auth/login.php"); exit; }
include '../config/db.php';

$id = intval($_GET['id'] ?? 0);
$row = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM bunga WHERE id=$id"));

if (!$row) {
    header("Location: daftar.php?error=Data tidak ditemukan");
    exit;
}

// Hapus gambar dari folder uploads
if ($row['gambar'] && file_exists('../uploads/'.$row['gambar'])) {
    @unlink('../uploads/'.$row['gambar']);
}

// Hapus dari database
mysqli_query($conn, "DELETE FROM bunga WHERE id=$id");
header("Location: daftar.php?success=Data bunga berhasil dihapus!");
exit;
?>
