<?php
session_start();
if (isset($_SESSION['user'])) { header("Location: ../pages/dashboard.php"); exit; }
include '../config/db.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = MD5($_POST['password']);
    $query = mysqli_query($conn, "SELECT * FROM users WHERE username='$username' AND password='$password'");
    if (mysqli_num_rows($query) > 0) {
        $_SESSION['user'] = $username;
        header("Location: ../pages/dashboard.php");
        exit;
    } else {
        $error = "Username atau password salah!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Toko Bunga</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <style>
    body {
      min-height: 100vh;
      background: linear-gradient(135deg, #fce4ec 0%, #f8bbd0 50%, #fce4ec 100%);
      display: flex; align-items: center; justify-content: center;
      font-family: 'Segoe UI', sans-serif;
    }
    .login-card { border: none; border-radius: 20px; box-shadow: 0 10px 40px rgba(233,30,140,0.15); overflow: hidden; width: 100%; max-width: 420px; }
    .login-header { background: linear-gradient(135deg, #e91e8c, #f06292); padding: 35px 20px; text-align: center; color: white; }
    .login-header .icon { font-size: 3rem; }
    .login-body { padding: 30px; background: white; }
    .form-control:focus { border-color: #e91e8c; box-shadow: 0 0 0 0.2rem rgba(233,30,140,0.2); }
    .input-group-text { background-color: #fce4ec; border-color: #f8bbd0; color: #c2185b; }
    .btn-pink { background: linear-gradient(135deg, #e91e8c, #f06292); border: none; border-radius: 10px; padding: 10px; font-weight: 600; color: white; }
    .btn-pink:hover { background: linear-gradient(135deg, #c2185b, #e91e8c); color: white; }
    .link-pink { color: #e91e8c; text-decoration: none; }
    .link-pink:hover { color: #c2185b; text-decoration: underline; }
  </style>
</head>
<body>
  <div class="login-card">
    <div class="login-header">
      <div class="icon">🌸</div>
      <h4 class="fw-bold mt-2 mb-0">Toko Bunga</h4>
      <small class="opacity-75">Sistem Manajemen Data Bunga</small>
    </div>
    <div class="login-body">
      <?php if ($error): ?>
        <div class="alert alert-danger alert-dismissible fade show">
          <i class="bi bi-exclamation-triangle me-2"></i><?= $error ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      <?php endif; ?>
      <form method="POST">
        <div class="mb-3">
          <label class="form-label fw-semibold">Username</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-person"></i></span>
            <input type="text" name="username" class="form-control" placeholder="Masukkan username" required>
          </div>
        </div>
        <div class="mb-4">
          <label class="form-label fw-semibold">Password</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-lock"></i></span>
            <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
          </div>
        </div>
        <button type="submit" class="btn btn-pink w-100">
          <i class="bi bi-box-arrow-in-right me-2"></i>Login
        </button>
      </form>
      <div class="text-center mt-3">
        <small class="text-muted">Belum punya akun? <a href="register.php" class="link-pink fw-semibold">Daftar di sini</a></small>
      </div>
      <div class="text-center mt-2">
        <small class="text-muted">Default: admin / admin123</small>
      </div>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
