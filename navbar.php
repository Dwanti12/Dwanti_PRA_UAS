<?php
$current = basename($_SERVER['PHP_SELF']);
?>
<div class="col-md-2 sidebar py-3 px-2 d-none d-md-block">
  <div class="text-center mb-4 pt-2">
    <div style="font-size:2.5rem;">🌸</div>
    <div class="text-white fw-bold fs-5">Toko Bunga</div>
    <small class="text-white-50">Selamat datang,</small><br>
    <strong class="text-white" style="font-size:0.9rem"><?= htmlspecialchars($_SESSION['user']) ?></strong>
  </div>
  <a href="dashboard.php" class="<?= $current=='dashboard.php' ? 'active':'' ?>"><i class="bi bi-speedometer2"></i> Dashboard</a>
  <a href="tambah.php" class="<?= $current=='tambah.php' ? 'active':'' ?>"><i class="bi bi-plus-circle"></i> Tambah Bunga</a>
  <a href="daftar.php" class="<?= $current=='daftar.php' ? 'active':'' ?>"><i class="bi bi-flower1"></i> Data Bunga</a>
  <a href="report_pdf.php" target="_blank"><i class="bi bi-file-earmark-pdf"></i> Report PDF</a>
  <hr style="border-color:rgba(255,255,255,0.2)">
  <a href="../auth/logout.php"><i class="bi bi-box-arrow-left"></i> Logout</a>
</div>
