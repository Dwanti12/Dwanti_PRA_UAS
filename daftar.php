<?php
session_start();
if (!isset($_SESSION['user'])) { header("Location: ../auth/login.php"); exit; }
include '../config/db.php';

// Search & Filter
$where = "WHERE 1=1";
$search = '';
$filter_kat = '';

if (!empty($_GET['search'])) {
    $search = mysqli_real_escape_string($conn, $_GET['search']);
    $where .= " AND (nama_bunga LIKE '%$search%' OR kode_bunga LIKE '%$search%')";
}
if (!empty($_GET['kategori'])) {
    $filter_kat = mysqli_real_escape_string($conn, $_GET['kategori']);
    $where .= " AND kategori='$filter_kat'";
}

$data = mysqli_query($conn, "SELECT * FROM bunga $where ORDER BY created_at DESC");
$categories = mysqli_query($conn, "SELECT DISTINCT kategori FROM bunga ORDER BY kategori");

// Alert
$alert = '';
if (isset($_GET['success'])) $alert = '<div class="alert alert-success alert-dismissible fade show"><i class="bi bi-check-circle me-2"></i>'.$_GET['success'].'<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
if (isset($_GET['error']))   $alert = '<div class="alert alert-danger alert-dismissible fade show"><i class="bi bi-x-circle me-2"></i>'.$_GET['error'].'<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
?>
<?php include '../includes/header.php'; ?>
<div class="container-fluid">
  <div class="row">
    <?php include '../includes/navbar.php'; ?>
    <div class="col-md-10 py-4 px-4">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="page-title"><i class="bi bi-flower1 me-2"></i>Data Bunga</div>
        <div>
          <a href="report_pdf.php" target="_blank" class="btn btn-warning btn-sm me-2"><i class="bi bi-file-earmark-pdf me-1"></i>Export PDF</a>
          <a href="tambah.php" class="btn btn-success btn-sm"><i class="bi bi-plus-circle me-1"></i>Tambah</a>
        </div>
      </div>

      <?= $alert ?>

      <!-- Filter -->
      <div class="card mb-3">
        <div class="card-body py-2">
          <form method="GET" class="row g-2 align-items-center">
            <div class="col-md-5">
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-search"></i></span>
                <input type="text" name="search" class="form-control" placeholder="Cari nama/kode bunga..." value="<?= htmlspecialchars($search) ?>">
              </div>
            </div>
            <div class="col-md-3">
              <select name="kategori" class="form-select">
                <option value="">Semua Kategori</option>
                <?php while($kat = mysqli_fetch_assoc($categories)): ?>
                  <option value="<?= $kat['kategori'] ?>" <?= $filter_kat==$kat['kategori']?'selected':'' ?>><?= $kat['kategori'] ?></option>
                <?php endwhile; ?>
              </select>
            </div>
            <div class="col-auto">
              <button type="submit" class="btn btn-success">Filter</button>
              <a href="daftar.php" class="btn btn-outline-secondary ms-1">Reset</a>
            </div>
          </form>
        </div>
      </div>

      <!-- Tabel -->
      <div class="card">
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-hover mb-0">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Gambar</th>
                  <th>Kode</th>
                  <th>Nama Bunga</th>
                  <th>Kategori</th>
                  <th>Harga</th>
                  <th>Stok</th>
                  <th>Tgl Input</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                <?php $no=1; $count = mysqli_num_rows($data);
                if ($count == 0): ?>
                <tr><td colspan="9" class="text-center py-4 text-muted"><i class="bi bi-inbox fs-3 d-block mb-2"></i>Tidak ada data ditemukan</td></tr>
                <?php else: while($row = mysqli_fetch_assoc($data)): ?>
                <tr>
                  <td><?= $no++ ?></td>
                  <td>
                    <?php if($row['gambar'] && file_exists('../uploads/'.$row['gambar'])): ?>
                      <img src="../uploads/<?= $row['gambar'] ?>" class="thumb" alt="foto">
                    <?php else: ?>
                      <div class="thumb d-flex align-items-center justify-content-center bg-light text-muted" style="border-radius:8px">🌸</div>
                    <?php endif; ?>
                  </td>
                  <td><code><?= $row['kode_bunga'] ?></code></td>
                  <td><?= htmlspecialchars($row['nama_bunga']) ?></td>
                  <td><span class="badge-kategori"><?= $row['kategori'] ?></span></td>
                  <td>Rp <?= number_format($row['harga'],0,',','.') ?></td>
                  <td>
                    <?php
                      $stok = $row['stok'];
                      $badge = $stok > 20 ? 'success' : ($stok > 5 ? 'warning' : 'danger');
                    ?>
                    <span class="badge bg-<?= $badge ?>"><?= $stok ?></span>
                  </td>
                  <td><?= date('d/m/Y', strtotime($row['tanggal_input'])) ?></td>
                  <td>
                    <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm"><i class="bi bi-pencil"></i></a>
                    <a href="hapus.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus data <?= addslashes($row['nama_bunga']) ?>?')"><i class="bi bi-trash"></i></a>
                  </td>
                </tr>
                <?php endwhile; endif; ?>
              </tbody>
            </table>
          </div>
        </div>
        <div class="card-footer text-muted small">
          Total: <?= $count ?> data
        </div>
      </div>
    </div>
  </div>
</div>
<?php include '../includes/footer.php'; ?>
