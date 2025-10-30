<?php
session_start();
include '../config/db.php';
date_default_timezone_set("Asia/Jakarta");

if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'user') {
    header("Location: ../login.php");
    exit();
}

$id_user = $_SESSION['id'];
$username = $_SESSION['username'];

$query = "SELECT * FROM tagihan WHERE id_user = '$id_user' ORDER BY tahun DESC, bulan DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Daftar Tagihan - BorahaeWatt</title>
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
      color: #6f42c1;
      font-weight: bold;
    }
    .nav-link.active {
      color: #6f42c1 !important;
      font-weight: bold;
    }
    .nav-link.active::after {
      content: "";
      display: block;
      width: 100%;
      height: 2px;
      background-color: #6f42c1;
      margin-top: 3px;
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
    .btn-primary-custom {
      background-color: #6f42c1;
      border-color: #6f42c1;
      color: white;
    }
    .btn-primary-custom:hover {
      background-color: #5a35a1;
      border-color: #5a35a1;
      color: white;
    }
    footer {
      background-color: #401d80;
      color: white;
      padding: 3rem 0;
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
        <li class="nav-item"><a class="nav-link" href="beranda.php">Beranda</a></li>
        <li class="nav-item"><a class="nav-link active" href="#">Daftar Tagihan</a></li>
      </ul>
      <a href="../logout.php" class="btn btn-primary-custom px-3 ms-2">Logout</a>
    </div>
  </div>
</nav>

<!-- Main Content -->
<main class="container mt-5 mb-5">
  <h3 class="text-center mb-5">Daftar Tagihan Anda</h3>

  <?php if (mysqli_num_rows($result) > 0): ?>
    <div class="table-responsive">
      <table class="table styled-table">
        <thead>
          <tr>
            <th>No</th>
            <th>Bulan</th>
            <th>Tahun</th>
            <th>Pemakaian (kWh)</th>
            <th>Total Tagihan</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <?php $no = 1; while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
              <td><?= $no++ ?></td>
              <td><?= $row['bulan'] ?></td>
              <td><?= $row['tahun'] ?></td>
              <td><?= $row['pemakaian_kwh'] ?> kWh</td>
              <td>Rp<?= number_format($row['total_tagihan'], 0, ',', '.') ?></td>
              <td>
                <?php if ($row['status'] === 'Lunas'): ?>
                  <span class="badge bg-success">Lunas</span>
                <?php else: ?>
                  <div class="d-flex justify-content-center align-items-center gap-2">
                    <span class="badge bg-danger">Belum Lunas</span>
                    <button class="btn btn-sm btn-primary-custom" 
                            data-bs-toggle="modal" 
                            data-bs-target="#bayarModal<?= $row['id'] ?>">
                        <i class="bi bi-credit-card"></i> Bayar
                    </button>
                  </div>
                <?php endif; ?>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  <?php else: ?>
    <div class="alert alert-info text-center">Belum ada tagihan yang tersedia untuk Anda.</div>
  <?php endif; ?>

  <!-- Modal Detail Tagihan untuk Tiap Baris -->
  <?php
    mysqli_data_seek($result, 0); // ulangi pointer ke awal
    while ($row = mysqli_fetch_assoc($result)):
      if ($row['status'] !== 'Lunas'):
  ?>
  <div class="modal fade" id="bayarModal<?= $row['id'] ?>" tabindex="-1" aria-labelledby="modalLabel<?= $row['id'] ?>" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="modalLabel<?= $row['id'] ?>">Detail Tagihan</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>

      <!-- FORM dimulai di sini -->
      <form action="proses_pembayaran.php" method="POST" enctype="multipart/form-data">
        <div class="modal-body">
          <p><strong>Bulan:</strong> <?= $row['bulan'] ?></p>
          <p><strong>Tahun:</strong> <?= $row['tahun'] ?></p>
          <p><strong>Pemakaian:</strong> <?= $row['pemakaian_kwh'] ?> kWh</p>
          <p><strong>Total Tagihan:</strong> Rp<?= number_format($row['total_tagihan'], 0, ',', '.') ?></p>
          <p><strong>Status:</strong> <?= $row['status'] ?></p>
          <hr>

          <!-- QRIS -->
          <p class="fw-bold text-center">Silakan transfer ke QRIS berikut:</p>
          <div class="text-center mb-3">
            <img src="../assets/img/qrish.jpg" alt="QRIS BorahaeWatt" width="200">
            <p class="mt-2 small">Scan QR untuk membayar, harap memasukkan nominal sesuai dengan tagihan!</p>
          </div>

          <!-- Upload Bukti Transfer -->
          <div class="mb-3">
            <label for="bukti<?= $row['id'] ?>" class="form-label">Upload Bukti Pembayaran</label>
            <input class="form-control" type="file" id="bukti<?= $row['id'] ?>" name="bukti_pembayaran" accept=".jpg,.jpeg,.png,.pdf" required>
            <small class="text-muted">Format: JPG, PNG, atau PDF</small>
          </div>

          <input type="hidden" name="id_tagihan" value="<?= $row['id'] ?>">
        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-primary-custom">Konfirmasi Pembayaran</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        </div>
      </form>
      <!-- FORM selesai -->
    </div>
  </div>
</div>
  <?php endif; endwhile; ?>
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