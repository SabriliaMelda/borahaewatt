<?php
include '../config/db.php'; // koneksi ke database
session_start();
date_default_timezone_set("Asia/Jakarta");

$loggedIn = isset($_SESSION['username']) && isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
$username = $loggedIn ? $_SESSION['username'] : null;
$time = date("H:i");

if (!$loggedIn) {
  header("Location: login.php");
  exit();
}
// Pastikan variabel koneksi terdefinisi
if (!isset($conn)) {
    die("Koneksi ke database gagal.");
}

// Inisialisasi nilai default
$jumlah_user = $jumlah_transaksi = 0;
$total_nominal = 0;

// Ambil data statistik jika koneksi tersedia
if ($conn) {
    $query_user = $conn->query("SELECT COUNT(*) AS total_user FROM users");
    $jumlah_user = $query_user ? $query_user->fetch_assoc()['total_user'] : 0;

    $query_transaksi = $conn->query("SELECT COUNT(*) AS total_transaksi FROM pembayaran");
    $jumlah_transaksi = $query_transaksi ? $query_transaksi->fetch_assoc()['total_transaksi'] : 0;

    $query_nominal = $conn->query("SELECT SUM(jumlah_pembayaran) AS total_pembayaran FROM pembayaran");
    $total_nominal = $query_nominal ? $query_nominal->fetch_assoc()['total_pembayaran'] : 0;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistik Penggunaan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
  <div class="container">
    <a class="navbar-brand fw-semibold text-purple d-flex align-items-center gap-2" href="#">
      <i class="bi bi-lightning-charge-fill" style="color:#6f42c1; font-size: 1.4rem;"></i>
      <span style="color:#6f42c1;">BorahaeWatt</span>
        <?php if ($loggedIn): ?>
          <span class="ms-3 fs-6 text-muted">Selamat datang, <strong><?= htmlspecialchars($username) ?></strong> - <?= $time ?></span>
        <?php endif; ?>
      </a>
      <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
      <ul class="navbar-nav ms-auto">
          <li class="nav-item">
            <a class="nav-link" href="beranda_admin.php">Beranda</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../index.php">Logout</a>
          </li>
        </ul>
    </div>
    </div>
  </nav>

    <div class="container mt-5">
        <h2 class="mb-4 text-center">Statistik Penggunaan Sistem</h2>
        <div class="row text-center">

            <div class="col-md-4 mb-3">
                <div class="card shadow">
                    <div class="card-body">
                        <h5>Total Pengguna</h5>
                        <h3><?= $jumlah_user ?></h3>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-3">
                <div class="card shadow">
                    <div class="card-body">
                        <h5>Total Transaksi</h5>
                        <h3><?= $jumlah_transaksi ?></h3>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-3">
                <div class="card shadow">
                    <div class="card-body">
                        <h5>Total Pembayaran</h5>
                        <h3>Rp <?= number_format($total_nominal, 0, ',', '.') ?></h3>
                    </div>
                </div>
            </div>

        </div>
    </div>
</body>
</html>