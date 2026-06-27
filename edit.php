<?php
session_start();
if (!isset($_SESSION['user'])) { header("Location: ../auth/login.php"); exit; }
include '../config/db.php';

$id = intval($_GET['id'] ?? 0);
$row = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM bunga WHERE id=$id"));
if (!$row) { header("Location: daftar.php?error=Data tidak ditemukan"); exit; }

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $kode     = mysqli_real_escape_string($conn, trim($_POST['kode_bunga']));
    $nama     = mysqli_real_escape_string($conn, trim($_POST['nama_bunga']));
    $kategori = mysqli_real_escape_string($conn, $_POST['kategori']);
    $harga    = floatval($_POST['harga']);
    $stok     = intval($_POST['stok']);
    $ket      = mysqli_real_escape_string($conn, trim($_POST['keterangan']));
    $tgl      = $_POST['tanggal_input'];

    // Cek kode unik (kecuali data sendiri)
    $cek = mysqli_query($conn, "SELECT id FROM bunga WHERE kode_bunga='$kode' AND id!=$id");
    if (mysqli_num_rows($cek) > 0) {
        $error = "Kode bunga sudah digunakan oleh data lain!";
    } else {
        $gambar_field = $row['gambar']; // default gambar lama

        // Jika ada upload gambar baru
        if (!empty($_FILES['gambar']['name'])) {
            $allowed = ['jpg','jpeg','png','gif','webp'];
            $ext = strtolower(pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION));
            if (!in_array($ext, $allowed)) {
                $error = "Format gambar tidak valid!";
            } elseif ($_FILES['gambar']['size'] > 2*1024*1024) {
                $error = "Ukuran gambar maksimal 2MB!";
            } else {
                $nama_file = time().'_'.preg_replace('/[^a-zA-Z0-9._]/', '', $_FILES['gambar']['name']);
                if (move_uploaded_file($_FILES['gambar']['tmp_name'], '../uploads/'.$nama_file)) {
                    // Hapus gambar lama jika ada
                    if ($row['gambar'] && file_exists('../uploads/'.$row['gambar'])) {
                        @unlink('../uploads/'.$row['gambar']);
                    }
                    $gambar_field = $nama_file;
                } else {
                    $error = "Gagal mengupload gambar!";
                }
            }
        }

        if (!$error) {
            $gambar_esc = mysqli_real_escape_string($conn, $gambar_field);
            $q = mysqli_query($conn, "UPDATE bunga SET 
                kode_bunga='$kode', nama_bunga='$nama', kategori='$kategori',
                harga='$harga', stok='$stok', keterangan='$ket',
                tanggal_input='$tgl', gambar='$gambar_esc'
                WHERE id=$id");
            if ($q) {
                header("Location: daftar.php?success=Data bunga berhasil diperbarui!");
                exit;
            } else {
                $error = "Gagal memperbarui data: ".mysqli_error($conn);
            }
        }
    }
} else {
    // Isi dari DB
    $_POST = $row;
}
?>
<?php include '../includes/header.php'; ?>
<div class="container-fluid">
  <div class="row">
    <?php include '../includes/navbar.php'; ?>
    <div class="col-md-10 py-4 px-4">
      <div class="mb-3">
        <a href="daftar.php" class="text-decoration-none text-muted"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
        <div class="page-title mt-1"><i class="bi bi-pencil me-2"></i>Edit Data Bunga</div>
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
                <input type="text" name="kode_bunga" class="form-control" required value="<?= htmlspecialchars($_POST['kode_bunga']) ?>">
              </div>
              <div class="col-md-6">
                <label class="form-label fw-semibold">Nama Bunga <span class="text-danger">*</span></label>
                <input type="text" name="nama_bunga" class="form-control" required value="<?= htmlspecialchars($_POST['nama_bunga']) ?>">
              </div>
              <div class="col-md-6">
                <label class="form-label fw-semibold">Kategori <span class="text-danger">*</span></label>
                <select name="kategori" class="form-select" required>
                  <?php
                  $kats = ['Mawar','Tulip','Lily','Anggrek','Matahari','Melati','Krisan','Aster','Lainnya'];
                  foreach($kats as $k):
                    $sel = ($_POST['kategori']==$k) ? 'selected' : '';
                  ?>
                  <option value="<?= $k ?>" <?= $sel ?>><?= $k ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="col-md-6">
                <label class="form-label fw-semibold">Tanggal Input <span class="text-danger">*</span></label>
                <input type="date" name="tanggal_input" class="form-control" required value="<?= $_POST['tanggal_input'] ?>">
              </div>
              <div class="col-md-6">
                <label class="form-label fw-semibold">Harga (Rp) <span class="text-danger">*</span></label>
                <div class="input-group">
                  <span class="input-group-text">Rp</span>
                  <input type="number" name="harga" class="form-control" min="0" required value="<?= $_POST['harga'] ?>">
                </div>
              </div>
              <div class="col-md-6">
                <label class="form-label fw-semibold">Stok <span class="text-danger">*</span></label>
                <input type="number" name="stok" class="form-control" min="0" required value="<?= $_POST['stok'] ?>">
              </div>
              <div class="col-12">
                <label class="form-label fw-semibold">Keterangan</label>
                <textarea name="keterangan" class="form-control" rows="3"><?= htmlspecialchars($_POST['keterangan']) ?></textarea>
              </div>
              <div class="col-12">
                <label class="form-label fw-semibold">Gambar Bunga</label>
                <input type="file" name="gambar" id="gambarInput" class="form-control" accept="image/*">
                <small class="text-muted">Kosongkan jika tidak ingin mengganti gambar. Format: JPG, PNG, GIF, WEBP. Maks 2MB.</small>
                <div class="mt-2">
                  <?php if ($row['gambar'] && file_exists('../uploads/'.$row['gambar'])): ?>
                    <img id="preview" src="../uploads/<?= $row['gambar'] ?>" alt="Preview" style="max-width:200px; max-height:200px; border-radius:10px; object-fit:cover; border:2px solid #dee2e6;">
                  <?php else: ?>
                    <img id="preview" src="#" alt="Preview" style="max-width:200px; max-height:200px; display:none; border-radius:10px; object-fit:cover; border:2px solid #dee2e6;">
                  <?php endif; ?>
                </div>
              </div>
            </div>
            <hr>
            <div class="d-flex gap-2">
              <button type="submit" class="btn btn-warning"><i class="bi bi-save me-1"></i>Update</button>
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
