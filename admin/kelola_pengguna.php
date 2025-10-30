<?php
session_start();
include '../config/db.php';
date_default_timezone_set("Asia/Jakarta");

// Cek login dan role
if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// Filter pencarian
$search = isset($_GET['cari']) ? mysqli_real_escape_string($conn, $_GET['cari']) : '';
$searchSql = $search ? "AND (username LIKE '%$search%' OR email LIKE '%$search%' OR nomor_kwh LIKE '%$search%')" : "";

// Query pengguna
$adminResult = mysqli_query($conn, "SELECT * FROM users WHERE role = 'admin' $searchSql");
$userResult = mysqli_query($conn, "SELECT * FROM users WHERE role = 'user' $searchSql");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Kelola Pengguna - BorahaeWatt</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body { background-color: #e6e0f8; font-family: 'Segoe UI', sans-serif; }
    .navbar { background-color: #fff; }
    .navbar-brand span { color: #6f42c1; font-weight: bold; }
    .nav-link.active { color: #6f42c1 !important; font-weight: bold; }
    .nav-link.active::after { content: ""; display: block; width: 100%; height: 2px; background-color: #6f42c1; margin-top: 3px; }
    h3, h4 { color: #4c2a85; }
    .styled-table { background-color: #fff; border-radius: 10px; border: 1px solid #ddd; overflow: hidden; }
    .styled-table thead { background-color: #d5c2f9; font-weight: bold; }
    .styled-table th, .styled-table td { text-align: center; padding: 12px; vertical-align: middle; }
    .btn-primary-custom { background-color: #6f42c1; border-color: #6f42c1; }
    .btn-primary-custom:hover { background-color: #5a35a1; border-color: #5a35a1; }
    footer { background-color: #401d80; color: white; padding: 3rem 0; }
    footer a { color: white; }
    footer a:hover { text-decoration: underline; }
  </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg sticky-top shadow-sm">
  <div class="container">
    <a class="navbar-brand" href="#"><i class="bi bi-lightning-charge-fill" style="color:#6f42c1;"></i> <span>BorahaeWatt</span></a>
    <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarNav"><span class="navbar-toggler-icon"></span></button>
    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item"><a class="nav-link" href="beranda_admin.php">Beranda</a></li>
        <li class="nav-item"><a class="nav-link active" href="#">Kelola Pengguna</a></li>
      </ul>
      <a href="../index.php" class="btn btn-primary-custom px-3 text-white">Logout</a>
    </div>
  </div>
</nav>

<!-- Main -->
<main class="container mt-5 mb-5">
  <h3 class="text-center mb-5">Data Pengguna Sistem</h3>

  <!-- Filter Pencarian -->
  <form method="GET" class="mb-4">
    <div class="input-group w-50 mx-auto">
      <input type="text" name="cari" class="form-control" placeholder="Cari username / email / nomor KWH..." value="<?= htmlspecialchars($search) ?>">
      <button type="submit" class="btn btn-primary-custom">Cari</button>
    </div>
  </form>

  <!-- Admin Section -->
  <section class="mb-5">
    <h4>Akun Admin</h4>
    <?php if ($search && mysqli_num_rows($adminResult) == 0): ?>
      <div class="alert alert-warning text-center">Tidak ditemukan admin dengan kata kunci: <strong><?= htmlspecialchars($search) ?></strong></div>
    <?php elseif (mysqli_num_rows($adminResult) > 0): ?>
      <div class="table-responsive">
        <table class="table styled-table">
          <thead>
            <tr>
              <th>ID</th>
              <th>Username</th>
              <th>Nomor KWH</th>
              <th>Alamat</th>
              <th>Email</th>
              <th>Password</th>
              <th>Role</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($admin = mysqli_fetch_assoc($adminResult)): ?>
              <tr>
                <td><?= $admin['id'] ?></td>
                <td><?= htmlspecialchars($admin['username']) ?></td>
                <td><?= htmlspecialchars($admin['nomor_kwh']) ?></td>
                <td><?= htmlspecialchars($admin['alamat']) ?></td>
                <td><?= htmlspecialchars($admin['email']) ?></td>
                <td><code><?= substr($admin['password'], 0, 15) ?>...</code></td>
                <td><span class="badge bg-danger"><?= $admin['role'] ?></span></td>
                <td>
                  <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editModal<?= $admin['id'] ?>"><i class="bi bi-pencil"></i></button>
                  <a href="hapus_user.php?id=<?= $admin['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus akun ini?')"><i class="bi bi-trash"></i></a>
                </td>
              </tr>

              <!-- Modal Edit -->
              <div class="modal fade" id="editModal<?= $admin['id'] ?>" tabindex="-1">
                <div class="modal-dialog">
                  <form method="POST" action="update_user.php">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title">Edit Admin: <?= $admin['username'] ?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                      </div>
                      <div class="modal-body">
                        <input type="hidden" name="id" value="<?= $admin['id'] ?>">
                        <div class="mb-3"><label>Username</label><input type="text" name="username" class="form-control" value="<?= $admin['username'] ?>"></div>
                        <div class="mb-3"><label>Nomor KWH</label><input type="text" name="nomor_kwh" class="form-control" value="<?= $admin['nomor_kwh'] ?>"></div>
                        <div class="mb-3"><label>Alamat</label><input type="text" name="alamat" class="form-control" value="<?= $admin['alamat'] ?>"></div>
                        <div class="mb-3"><label>Email</label><input type="email" name="email" class="form-control" value="<?= $admin['email'] ?>"></div>
                      </div>
                      <div class="modal-footer">
                        <button type="submit" class="btn btn-primary-custom">Simpan</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    <?php else: ?>
      <div class="alert alert-info">Tidak ada admin terdaftar.</div>
    <?php endif; ?>
  </section>

  <!-- User Section -->
  <section>
    <h4>Akun Pelanggan</h4>
    <?php if ($search && mysqli_num_rows($userResult) == 0): ?>
      <div class="alert alert-warning text-center">Tidak ditemukan pengguna dengan kata kunci: <strong><?= htmlspecialchars($search) ?></strong></div>
    <?php elseif (mysqli_num_rows($userResult) > 0): ?>
      <div class="table-responsive">
        <table class="table styled-table">
          <thead>
            <tr>
              <th>ID</th>
              <th>Username</th>
              <th>Nomor KWH</th>
              <th>Alamat</th>
              <th>Email</th>
              <th>Password</th>
              <th>Role</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($user = mysqli_fetch_assoc($userResult)): ?>
              <tr>
                <td><?= $user['id'] ?></td>
                <td><?= htmlspecialchars($user['username']) ?></td>
                <td><?= htmlspecialchars($user['nomor_kwh']) ?></td>
                <td><?= htmlspecialchars($user['alamat']) ?></td>
                <td><?= htmlspecialchars($user['email']) ?></td>
                <td><code><?= substr($user['password'], 0, 15) ?>...</code></td>
                <td><span class="badge bg-secondary"><?= $user['role'] ?></span></td>
                <td>
                  <a href="hapus_user.php?id=<?= $user['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus akun ini?')"><i class="bi bi-trash"></i></a>
                </td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    <?php else: ?>
      <div class="alert alert-info">Tidak ada pelanggan terdaftar.</div>
    <?php endif; ?>
  </section>
</main>

<!-- Footer -->
<footer class="text-white py-5" style="background-color:rgb(64, 29, 128);">
  <div class="container">
    <div class="row">

      <!-- Kontak Kami -->
      <div class="col-md-6 mb-4">
        <h3 class="fw-semibold text-white">Kontak Kami</h3>
        <ul class="list-unstyled mt-3">
          <li class="mb-3"><i class="bi bi-envelope-fill me-2"></i> BorahaeWatt@gmail.com</li>
          <li class="mb-3"><i class="bi bi-whatsapp me-2"></i> +62 889-7596-3262</li>
          <li class="mb-3"><i class="bi bi-instagram me-2"></i> @BorahaeWatt</li>
          <li class="mb-3"><i class="bi bi-geo-alt-fill me-2"></i> Jl. Watt Selatan No.14, Kelurahan Energi, Kecamatan Borahae, Jakarta Timur, DKI Jakarta 13210</li>
        </ul>
      </div>

      <!-- Deskripsi BorahaeWatt -->
      <div class="col-md-6 mb-4">
        <h2 class="fw-bold">BorahaeWatt</h2>
        <p>
          BorahaeWatt adalah platform pembayaran listrik berbasis web yang terinspirasi dari BTS dan ARMY. Nama ini menggabungkan kata borahae, simbol cinta dari V BTS, dan watt, satuan listrik. Dengan desain bertema ungu dan nuansa K-pop, BorahaeWatt menjadikan pembayaran listrik terasa lebih seru, modern, dan dekat dengan generasi muda.
        </p>
        <div class="mt-3 d-flex gap-3">
          <a href="https://facebook.com" target="_blank" class="text-white fs-4"><i class="bi bi-facebook"></i></a>
          <a href="https://instagram.com/BorahaeWatt" target="_blank" class="text-white fs-4"><i class="bi bi-instagram"></i></a>
          <a href="https://wa.me/6288975963262" target="_blank" class="text-white fs-4"><i class="bi bi-whatsapp"></i></a>
          <a href="mailto:BorahaeWatt@gmail.com" target="_blank" class="text-white fs-4"><i class="bi bi-envelope-fill"></i></a>
        </div>
      </div>

    </div>

    <hr class="border-light">

    <!-- Copyright -->
    <div class="text-center mt-3">
      <p class="mb-0">© 2025 Sabrilia Melda Putri Lestari. All rights reserved.</p>
    </div>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
