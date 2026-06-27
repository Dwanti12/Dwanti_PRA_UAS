<?php
session_start();
if (!isset($_SESSION['user'])) { header("Location: ../auth/login.php"); exit; }
include '../config/db.php';

$total   = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM bunga"))[0];
$stok    = mysqli_fetch_row(mysqli_query($conn, "SELECT SUM(stok) FROM bunga"))[0];
$nilai   = mysqli_fetch_row(mysqli_query($conn, "SELECT SUM(harga*stok) FROM bunga"))[0];
$kategori= mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(DISTINCT kategori) FROM bunga"))[0];
$recent  = mysqli_query($conn, "SELECT * FROM bunga ORDER BY created_at DESC LIMIT 5");
?>
<?php include '../includes/header.php'; ?>
<div class="container-fluid">
  <div class="row">
    <?php include '../includes/navbar.php'; ?>
    <div class="col-md-10 py-4 px-4">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
          <div class="page-title"><i class="bi bi-speedometer2 me-2"></i>Dashboard</div>
          <small class="text-muted">Selamat datang kembali! Berikut ringkasan data toko bunga.</small>
        </div>
        <a href="tambah.php" class="btn btn-success"><i class="bi bi-plus-circle me-1"></i> Tambah Bunga</a>
      </div>

      <!-- Statistik -->
      <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
          <div class="stat-card" style="background:linear-gradient(135deg,#43a047,#66bb6a)">
            <div style="font-size:2rem;">🌸</div>
            <div class="fw-bold fs-4"><?= $total ?></div>
            <div style="font-size:0.85rem;opacity:0.9">Total Jenis Bunga</div>
          </div>
        </div>
        <div class="col-6 col-md-3">
          <div class="stat-card" style="background:linear-gradient(135deg,#0288d1,#4fc3f7)">
            <div style="font-size:2rem;">📦</div>
            <div class="fw-bold fs-4"><?= number_format($stok) ?></div>
            <div style="font-size:0.85rem;opacity:0.9">Total Stok</div>
          </div>
        </div>
        <div class="col-6 col-md-3">
          <div class="stat-card" style="background:linear-gradient(135deg,#f57c00,#ffb74d)">
            <div style="font-size:2rem;">💰</div>
            <div class="fw-bold fs-4">Rp <?= number_format($nilai,0,',','.') ?></div>
            <div style="font-size:0.85rem;opacity:0.9">Nilai Total Stok</div>
          </div>
        </div>
        <div class="col-6 col-md-3">
          <div class="stat-card" style="background:linear-gradient(135deg,#7b1fa2,#ba68c8)">
            <div style="font-size:2rem;">🏷️</div>
            <div class="fw-bold fs-4"><?= $kategori ?></div>
            <div style="font-size:0.85rem;opacity:0.9">Kategori</div>
          </div>
        </div>
      </div>

      <!-- Tabel Data Terbaru -->
      <div class="card">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
          <strong><i class="bi bi-clock-history me-2"></i>Data Bunga Terbaru</strong>
          <a href="daftar.php" class="btn btn-outline-success btn-sm">Lihat Semua</a>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-hover mb-0">
              <thead>
                <tr>
                  <th>Kode</th><th>Nama Bunga</th><th>Kategori</th><th>Harga</th><th>Stok</th><th>Gambar</th>
                </tr>
              </thead>
              <tbody>
                <?php while($row = mysqli_fetch_assoc($recent)): ?>
                <tr>
                  <td><code><?= $row['kode_bunga'] ?></code></td>
                  <td><?= htmlspecialchars($row['nama_bunga']) ?></td>
                  <td><span class="badge-kategori"><?= $row['kategori'] ?></span></td>
                  <td>Rp <?= number_format($row['harga'],0,',','.') ?></td>
                  <td><?= $row['stok'] ?></td>
                  <td>
                    <?php if($row['gambar'] && file_exists('../uploads/'.$row['gambar'])): ?>
                      <img src="../uploads/<?= $row['gambar'] ?>" class="thumb" alt="foto">
                    <?php else: ?>
                      <span class="text-muted">-</span>
                    <?php endif; ?>
                  </td>
                </tr>
                <?php endwhile; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php include '../includes/footer.php'; ?>
