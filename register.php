<?php
session_start();
if (isset($_SESSION['user'])) { header("Location: ../pages/dashboard.php"); exit; }
include '../config/db.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($conn, trim($_POST['username']));
    $password = $_POST['password'];
    $konfirmasi = $_POST['konfirmasi'];

    if (strlen($username) < 3) {
        $error = "Username minimal 3 karakter!";
    } elseif (strlen($password) < 6) {
        $error = "Password minimal 6 karakter!";
    } elseif ($password !== $konfirmasi) {
        $error = "Konfirmasi password tidak cocok!";
    } else {
        $cek = mysqli_query($conn, "SELECT id FROM users WHERE username='$username'");
        if (mysqli_num_rows($cek) > 0) {
            $error = "Username sudah digunakan, pilih username lain!";
        } else {
            $pass_md5 = MD5($password);
            $q = mysqli_query($conn, "INSERT INTO users (username, password) VALUES ('$username', '$pass_md5')");
            if ($q) {
                $success = "Registrasi berhasil! Silakan login.";
            } else {
                $error = "Gagal mendaftar: " . mysqli_error($conn);
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registrasi - Toko Bunga</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <style>
    body {
      min-height: 100vh;
      background: linear-gradient(135deg, #fce4ec 0%, #f8bbd0 50%, #fce4ec 100%);
      display: flex; align-items: center; justify-content: center;
      font-family: 'Segoe UI', sans-serif;
    }
    .register-card { border: none; border-radius: 20px; box-shadow: 0 10px 40px rgba(233,30,140,0.15); overflow: hidden; width: 100%; max-width: 440px; }
    .register-header { background: linear-gradient(135deg, #e91e8c, #f06292); padding: 30px 20px; text-align: center; color: white; }
    .register-header .icon { font-size: 2.5rem; }
    .register-body { padding: 30px; background: white; }
    .form-control:focus { border-color: #e91e8c; box-shadow: 0 0 0 0.2rem rgba(233,30,140,0.2); }
    .input-group-text { background-color: #fce4ec; border-color: #f8bbd0; color: #c2185b; }
    .btn-pink { background: linear-gradient(135deg, #e91e8c, #f06292); border: none; border-radius: 10px; padding: 10px; font-weight: 600; color: white; }
    .btn-pink:hover { background: linear-gradient(135deg, #c2185b, #e91e8c); color: white; }
    .link-pink { color: #e91e8c; text-decoration: none; }
    .link-pink:hover { color: #c2185b; text-decoration: underline; }
    .password-strength { height: 4px; border-radius: 2px; transition: all 0.3s; margin-top: 6px; }
  </style>
</head>
<body>
  <div class="register-card">
    <div class="register-header">
      <div class="icon">🌸</div>
      <h4 class="fw-bold mt-2 mb-0">Buat Akun Baru</h4>
      <small class="opacity-75">Toko Bunga - Sistem Manajemen</small>
    </div>
    <div class="register-body">
      <?php if ($error): ?>
        <div class="alert alert-danger alert-dismissible fade show">
          <i class="bi bi-x-circle me-2"></i><?= $error ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      <?php endif; ?>
      <?php if ($success): ?>
        <div class="alert alert-success">
          <i class="bi bi-check-circle me-2"></i><?= $success ?>
          <div class="mt-2"><a href="login.php" class="btn btn-pink btn-sm">Login Sekarang</a></div>
        </div>
      <?php else: ?>
      <form method="POST">
        <div class="mb-3">
          <label class="form-label fw-semibold">Username <span class="text-danger">*</span></label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-person"></i></span>
            <input type="text" name="username" class="form-control" placeholder="Minimal 3 karakter" required value="<?= isset($_POST['username']) ? htmlspecialchars($_POST['username']) : '' ?>">
          </div>
        </div>
        <div class="mb-3">
          <label class="form-label fw-semibold">Password <span class="text-danger">*</span></label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-lock"></i></span>
            <input type="password" name="password" id="password" class="form-control" placeholder="Minimal 6 karakter" required>
          </div>
          <div id="strengthBar" class="password-strength bg-secondary opacity-25"></div>
          <small id="strengthText" class="text-muted"></small>
        </div>
        <div class="mb-4">
          <label class="form-label fw-semibold">Konfirmasi Password <span class="text-danger">*</span></label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
            <input type="password" name="konfirmasi" id="konfirmasi" class="form-control" placeholder="Ulangi password" required>
          </div>
          <small id="matchText" class="text-muted"></small>
        </div>
        <button type="submit" class="btn btn-pink w-100">
          <i class="bi bi-person-plus me-2"></i>Daftar Sekarang
        </button>
      </form>
      <?php endif; ?>
      <div class="text-center mt-3">
        <small class="text-muted">Sudah punya akun? <a href="login.php" class="link-pink fw-semibold">Login di sini</a></small>
      </div>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Password strength indicator
    document.getElementById('password').addEventListener('input', function() {
      const val = this.value;
      const bar = document.getElementById('strengthBar');
      const text = document.getElementById('strengthText');
      if (val.length === 0) { bar.style.width='0%'; text.textContent=''; return; }
      let strength = 0;
      if (val.length >= 6) strength++;
      if (val.length >= 10) strength++;
      if (/[A-Z]/.test(val)) strength++;
      if (/[0-9]/.test(val)) strength++;
      if (/[^A-Za-z0-9]/.test(val)) strength++;
      const levels = [
        {w:'20%', c:'bg-danger', t:'Sangat Lemah'},
        {w:'40%', c:'bg-warning', t:'Lemah'},
        {w:'60%', c:'bg-info', t:'Cukup'},
        {w:'80%', c:'bg-primary', t:'Kuat'},
        {w:'100%', c:'bg-success', t:'Sangat Kuat'},
      ];
      const lv = levels[Math.min(strength-1, 4)];
      bar.className = 'password-strength ' + lv.c;
      bar.style.width = lv.w;
      text.textContent = lv.t;
    });

    // Confirm password match
    document.getElementById('konfirmasi').addEventListener('input', function() {
      const pass = document.getElementById('password').value;
      const text = document.getElementById('matchText');
      if (this.value === '') { text.textContent = ''; return; }
      if (this.value === pass) {
        text.innerHTML = '<span class="text-success"><i class="bi bi-check-circle"></i> Password cocok</span>';
      } else {
        text.innerHTML = '<span class="text-danger"><i class="bi bi-x-circle"></i> Password tidak cocok</span>';
      }
    });
  </script>
</body>
</html>
