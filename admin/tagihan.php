<?php
session_start();
include '../config/db.php';
date_default_timezone_set("Asia/Jakarta");

if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$query = "SELECT * FROM tagihan ORDER BY tahun DESC, bulan DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Data Tagihan - BorahaeWatt</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
        background-color: #e6e0f8;
        font-family: 'Segoe UI', sans-serif;
    }
    .navbar {
        background-color: #fff;
    }
    .navbar-brand span {
        color: #6f42c1; font-weight: bold;
    }
    .nav-link.active {
        color: #6f42c1 !important; font-weight: bold;
    }
    .nav-link.active::after {
        content: "";
        display: block;
        width: 100%;
        height: 2px;
        background-color: #6f42c1;
        margin-top: 3px;
    }
    h3, h4 {
        color: #4c2a85;
    }
    .styled-table {
        background-color: #fff;
        border-radius: 10px; 
        border: 1px solid #ddd; 
        overflow: hidden; 
    }
    .styled-table thead {
        background-color: #d5c2f9;
        font-weight: bold;
    }
    .styled-table th, .styled-table td {
        text-align: center; 
        padding: 12px; 
        vertical-align: middle; 
    }
    .badge-success { 
        background-color: #28a745; 
    }
    .badge-danger { 
        background-color: #dc3545; 
    }
    .btn-primary-custom { 
        background-color: #6f42c1; 
        border-color: #6f42c1; 
    }
    .btn-primary-custom:hover { 
        background-color: #5a35a1; 
        border-color: #5a35a1; 
    }
    footer { 
        background-color: #401d80; 
        color: white; padding: 3rem 0; 
    }
    footer a { 
        color: white; 
    }
    html, body {
  height: 100%;
  margin: 0;
  display: flex;
  flex-direction: column;
}

main {
  flex: 1;
}
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
        <li class="nav-item"><a class="nav-link active" href="#">Tagihan</a></li>
      </ul>
      <a href="../index.php" class="btn btn-primary-custom px-3 text-white">Logout</a>
    </div>
  </div>
</nav>

<!-- Main -->
<main class="container mt-5 mb-5">
  <h3 class="text-center mb-5">Data Tagihan Pelanggan</h3>

  <?php if (mysqli_num_rows($result) > 0): ?>
    <div class="table-responsive">
      <table class="table styled-table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Nama</th>
            <th>Bulan</th>
            <th>Tahun</th>
            <th>Pemakaian (kWh)</th>
            <th>Total Tagihan</th>
            <th>Status</th>
            <th>Bukti</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
              <td><?= $row['id'] ?></td>
              <td><?= htmlspecialchars($row['nama_pelanggan']) ?></td>
              <td><?= $row['bulan'] ?></td>
              <td><?= $row['tahun'] ?></td>
              <td><?= $row['pemakaian_kwh'] ?> kWh</td>
              <td>Rp<?= number_format($row['total_tagihan'], 0, ',', '.') ?></td>
              <td>
                <?php if ($row['status'] === 'Lunas'): ?>
                  <span class="badge bg-success">Lunas</span>
                <?php else: ?>
                  <span class="badge bg-danger">Belum Lunas</span>
                <?php endif; ?>
              </td>
              <td>
                <?php if (!empty($row['bukti_pembayaran'])): ?>
                    <a href="../uploads/<?= $row['bukti_pembayaran'] ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                    Lihat Bukti
                    </a>
                <?php else: ?>
                    <span class="text-muted">Belum Upload</span>
                <?php endif; ?>
                </td>
                <td>
                <?php if ($row['status'] === 'Belum Lunas'): ?>
                    <a href="selesaikan_tagihan.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-success" onclick="return confirm('Selesaikan tagihan ini?')">Selesaikan</a>
                <?php else: ?>
                    <button class="btn btn-sm btn-secondary" disabled>Selesai</button>
                <?php endif; ?>
                </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  <?php else: ?>
    <div class="alert alert-info text-center">Belum ada data tagihan yang tersedia.</div>
  <?php endif; ?>
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
