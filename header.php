<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>🌸 Toko Bunga</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <style>
    :root {
      --pink-primary: #e91e8c;
      --pink-dark: #c2185b;
      --pink-light: #fce4ec;
      --pink-medium: #f48fb1;
      --pink-soft: #fff0f5;
    }
    body { background-color: var(--pink-soft); font-family: 'Segoe UI', sans-serif; }
    .card { border: none; box-shadow: 0 2px 10px rgba(233,30,140,0.08); border-radius: 12px; }
    .btn-success { background-color: var(--pink-primary) !important; border-color: var(--pink-primary) !important; }
    .btn-success:hover { background-color: var(--pink-dark) !important; border-color: var(--pink-dark) !important; }
    .btn-warning { background-color: #ff80ab !important; border-color: #ff80ab !important; color: white !important; }
    .btn-warning:hover { background-color: #f06292 !important; }
    .btn-danger { background-color: #c62828 !important; border-color: #c62828 !important; }
    .table th { background-color: var(--pink-light); color: var(--pink-dark); }
    .table-hover tbody tr:hover { background-color: #fff0f5; }
    .badge-kategori { background-color: #fce4ec; color: var(--pink-dark); padding: 4px 10px; border-radius: 20px; font-size: 0.8rem; font-weight: 600; }
    .sidebar { min-height: 100vh; background: linear-gradient(180deg, #e91e8c 0%, #c2185b 100%); }
    .sidebar a { color: rgba(255,255,255,0.85); text-decoration: none; display: block; padding: 10px 16px; border-radius: 8px; margin-bottom: 4px; transition: all 0.2s; }
    .sidebar a:hover, .sidebar a.active { background: rgba(255,255,255,0.2); color: #fff; }
    .sidebar a i { margin-right: 8px; }
    .page-title { font-size: 1.4rem; font-weight: 700; color: var(--pink-primary); }
    .stat-card { border-radius: 12px; padding: 20px; color: white; }
    img.thumb { width: 60px; height: 60px; object-fit: cover; border-radius: 8px; }
    .top-navbar { background: #fff; border-bottom: 2px solid var(--pink-light); padding: 10px 24px; display: flex; justify-content: flex-end; align-items: center; box-shadow: 0 1px 6px rgba(233,30,140,0.08); }
    .top-navbar .profile-img { width: 40px; height: 40px; border-radius: 50%; object-fit: cover; border: 2px solid var(--pink-primary); }
    .top-navbar .profile-placeholder { width: 40px; height: 40px; border-radius: 50%; background: var(--pink-primary); display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 1rem; }
    .top-navbar .profile-name { font-weight: 600; color: var(--pink-primary); font-size: 0.95rem; }
    .top-navbar .profile-role { font-size: 0.78rem; color: #aaa; }
    .dropdown-menu { border: none; box-shadow: 0 4px 20px rgba(233,30,140,0.12); border-radius: 10px; }
    .form-control:focus, .form-select:focus { border-color: var(--pink-primary); box-shadow: 0 0 0 0.2rem rgba(233,30,140,0.15); }
    .input-group-text { background-color: var(--pink-light); border-color: #f8bbd0; color: var(--pink-dark); }
    .card-header { border-bottom: 1px solid var(--pink-light); }
    code { color: var(--pink-dark); background: var(--pink-light); padding: 2px 6px; border-radius: 4px; }
    .badge.bg-success { background-color: var(--pink-primary) !important; }
    .badge.bg-warning { background-color: #ff80ab !important; }
    .alert-success { background-color: #fce4ec; border-color: #f48fb1; color: var(--pink-dark); }
  </style>
</head>
<body>
<?php
$foto = file_exists('../uploads/profil.jpg') ? '../uploads/profil.jpg' : null;
$initial = strtoupper(substr($_SESSION['user'] ?? 'A', 0, 1));
?>
<div class="top-navbar">
  <div class="dropdown">
    <div class="d-flex align-items-center gap-2" role="button" data-bs-toggle="dropdown" style="cursor:pointer">
      <?php if ($foto): ?>
        <img src="<?= $foto ?>" class="profile-img" alt="Foto Profil">
      <?php else: ?>
        <div class="profile-placeholder"><?= $initial ?></div>
      <?php endif; ?>
      <div>
        <div class="profile-name"><?= htmlspecialchars($_SESSION['user'] ?? '') ?></div>
        <div class="profile-role">Dewanti Syafitiri</div>
      </div>
      <i class="bi bi-chevron-down text-muted ms-1" style="font-size:0.8rem"></i>
    </div>
    <ul class="dropdown-menu dropdown-menu-end mt-2">
      <li><span class="dropdown-item-text text-muted small px-3">Login sebagai <strong><?= htmlspecialchars($_SESSION['user'] ?? '') ?></strong></span></li>
      <li><hr class="dropdown-divider"></li>
      <li><a class="dropdown-item text-danger" href="../auth/logout.php"><i class="bi bi-box-arrow-left me-2"></i>Logout</a></li>
    </ul>
  </div>
</div>
