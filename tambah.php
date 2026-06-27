<?php
session_start();
if (!isset($_SESSION['user'])) { header("Location: ../auth/login.php"); exit; }
include '../config/db.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $kode     = mysqli_real_escape_string($conn, trim($_POST['kode_bunga']));
    $nama     = mysqli_real_escape_string($conn, trim($_POST['nama_bunga']));
    $kategori = mysqli_real_escape_string($conn, $_POST['kategori']);
    $harga    = floatval($_POST['harga']);
    $stok     = intval($_POST['stok']);
    $ket      = mysqli_real_escape_string($conn, trim($_POST['keterangan']));
    $tgl      = $_POST['tanggal_input'];

    // Cek kode unik
    $cek = mysqli_query($conn, "SELECT id FROM bunga WHERE kode_bunga='$kode'");
    if (mysqli_num_rows($cek) > 0) {
        $error = "Kode bunga sudah digunakan!";
    } elseif (empty($_FILES['gambar']['name'])) {
        $error = "Gambar wajib diupload!";
    } else {
        // Upload gambar
        $allowed = ['jpg','jpeg','png','gif','webp'];
        $ext = strtolower(pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $allowed)) {
            $error = "Format gambar tidak valid! Gunakan JPG, PNG, atau GIF.";
        } elseif ($_FILES['gambar']['size'] > 2*1024*1024) {
            $error = "Ukuran gambar maksimal 2MB!";
        } else {
            $nama_file = time().'_'.preg_replace('/[^a-zA-Z0-9._]/', '', $_FILES['gambar']['name']);
            $upload_path = '../uploads/'.$nama_file;
            if (move_uploaded_file($_FILES['gambar']['tmp_name'], $upload_path)) {
                $q = mysqli_query($conn, "INSERT INTO bunga (kode_bunga, nama_bunga, kategori, harga, stok, keterangan, tanggal_input, gambar)
                    VALUES ('$kode','$nama','$kategori','$harga','$stok','$ket','$tgl','$nama_file')");
                if ($q) {
                    header("Location: daftar.php?success=Data bunga berhasil ditambahkan!");
                    exit;
                } else {
                    $error = "Gagal menyimpan data: ".mysqli_error($conn);
                }
            } else {
                $error = "Gagal mengupload gambar!";
            }
        }
    }
}
?>
<?php include '../includes/header.php'; ?>
<div class="container-fluid">
  <div class="row">
    <?php include '../includes/navbar.php'; ?>
    <div class="col-md-10 py-4 px-4">
      <div class="mb-3">
        <a href="daftar.php" class="text-decoration-none text-muted"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
        <div class="page-title mt-1"><i class="bi bi-plus-circle me-2"></i>Tambah Data Bunga</div>
      </div>

      <?php if ($error): ?>
        <div class="alert alert-danger"><i class="bi bi-x-circle me-2"></i><?= $error ?></div>
      <?php endif; ?>

      <div class="card">
        <div class="card-body">
          <form method="POST" enctype="multipart/form-data">
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label fw-semibold">Kode Bunga <span class="text-danger">*</span></label>
                <input type="text" name="kode_bunga" class="form-control" placeholder="Contoh: BNG001" required value="<?= isset($_POST['kode_bunga']) ? htmlspecialchars($_POST['kode_bunga']) : '' ?>">
              </div>
              <div class="col-md-6">
                <label class="form-label fw-semibold">Nama Bunga <span class="text-danger">*</span></label>
                <input type="text" name="nama_bunga" class="form-control" placeholder="Contoh: Mawar Merah" required value="<?= isset($_POST['nama_bunga']) ? htmlspecialchars($_POST['nama_bunga']) : '' ?>">
              </div>
              <div class="col-md-6">
                <label class="form-label fw-semibold">Kategori <span class="text-danger">*</span></label>
                <select name="kategori" class="form-select" required>
                  <option value="">-- Pilih Kategori --</option>
                  <?php
                  $kats = ['Mawar','Tulip','Lily','Anggrek','Matahari','Melati','Krisan','Aster','Lainnya'];
                  foreach($kats as $k):
                    $sel = (isset($_POST['kategori']) && $_POST['kategori']==$k) ? 'selected' : '';
                  ?>
                  <option value="<?= $k ?>" <?= $sel ?>><?= $k ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="col-md-6">
                <label class="form-label fw-semibold">Tanggal Input <span class="text-danger">*</span></label>
                <input type="date" name="tanggal_input" class="form-control" required value="<?= isset($_POST['tanggal_input']) ? $_POST['tanggal_input'] : date('Y-m-d') ?>">
              </div>
              <div class="col-md-6">
                <label class="form-label fw-semibold">Harga (Rp) <span class="text-danger">*</span></label>
                <div class="input-group">
                  <span class="input-group-text">Rp</span>
                  <input type="number" name="harga" class="form-control" placeholder="0" min="0" required value="<?= isset($_POST['harga']) ? $_POST['harga'] : '' ?>">
                </div>
              </div>
              <div class="col-md-6">
                <label class="form-label fw-semibold">Stok <span class="text-danger">*</span></label>
                <input type="number" name="stok" class="form-control" placeholder="0" min="0" required value="<?= isset($_POST['stok']) ? $_POST['stok'] : '' ?>">
              </div>
              <div class="col-12">
                <label class="form-label fw-semibold">Keterangan</label>
                <textarea name="keterangan" class="form-control" rows="3" placeholder="Deskripsi bunga..."><?= isset($_POST['keterangan']) ? htmlspecialchars($_POST['keterangan']) : '' ?></textarea>
              </div>
              <div class="col-12">
                <label class="form-label fw-semibold">Gambar Bunga <span class="text-danger">*</span></label>
                <input type="file" name="gambar" id="gambarInput" class="form-control" accept="image/*" required>
                <small class="text-muted">Format: JPG, PNG, GIF, WEBP. Maks 2MB.</small>
                <div class="mt-2">
                  <img id="preview" src="#" alt="Preview" style="max-width:200px; max-height:200px; display:none; border-radius:10px; object-fit:cover; border:2px solid #dee2e6;">
                </div>
              </div>
            </div>
            <hr>
            <div class="d-flex gap-2">
              <button type="submit" class="btn btn-success"><i class="bi bi-save me-1"></i>Simpan</button>
              <a href="daftar.php" class="btn btn-outline-secondary">Batal</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
document.getElementById('gambarInput').addEventListener('change', function(e) {
  const file = e.target.files[0];
  if (file) {
    const reader = new FileReader();
    reader.onload = function(ev) {
      const preview = document.getElementById('preview');
      preview.src = ev.target.result;
      preview.style.display = 'block';
    };
    reader.readAsDataURL(file);
  }
});
</script>
<?php include '../includes/footer.php'; ?>
