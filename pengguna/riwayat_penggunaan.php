<?php
session_start();
include '../config/db.php';
date_default_timezone_set("Asia/Jakarta");

if (!isset($_SESSION['id'])) {
    header("Location: ../login.php");
    exit();
}

$id_user = $_SESSION['id'];
$username = $_SESSION['username'] ?? 'User';
$loggedIn = true;
$time = date('H:i');

$query = "SELECT * FROM penggunaan WHERE id_user = '$id_user' ORDER BY tahun DESC, bulan DESC";
$result = $conn->query($query);

$total_kwh = 0;
$total_rows = 0;
$data = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
        $total_kwh += $row['pemakaian_kwh'];
        $total_rows++;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Riwayat Penggunaan - BorahaeWatt</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

  <!-- Chart.js -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <style>
    body {
      background-color: #e6e0f8;
      color: #333;
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

    h3 {
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
    }

    .info-box {
      background: transparent;
      padding: 10px 0;
    }

    .info-box h5 {
      font-weight: normal;
      font-size: 16px;
      color: #555;
    }

    .info-box p {
      font-size: 1.8rem;
      font-weight: bold;
      color: #6f42c1;
    }

    footer {
      background-color: #401d80;
      color: white;
      padding: 3rem 0;
    }

    footer a {
      color: white;
    }

    footer a:hover {
      text-decoration: underline;
    }

    .chart-container {
      background-color: #fff;
      border-radius: 10px;
      padding: 20px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.05);
    }
    .btn-primary-custom {
  background-color: #6f42c1;
  border-color: #6f42c1;
}

.btn-primary-custom:hover {
  background-color: #5a35a1;
  border-color: #5a35a1;
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
    <a class="navbar-brand" href="#">
      <i class="bi bi-lightning-charge-fill" style="color:#6f42c1;"></i>
      <span>BorahaeWatt</span>
      <?php if ($loggedIn): ?>
        <span class="ms-3 text-muted">Hai, <strong><?= htmlspecialchars($username) ?></strong> - <?= $time ?></span>
      <?php endif; ?>
    </a>
    <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item"><a class="nav-link" href="beranda.php">Beranda</a></li>
        <li class="nav-item"><a class="nav-link active" href="#">Riwayat</a></li>
      </ul>
      <a href="../index.php" class="btn btn-primary-custom px-3 text-white">Logout</a>
    </div>
  </div>
</nav>

<!-- Konten -->
<main class="container mt-5 mb-5">
  <h3 class="text-center mb-4">Riwayat Penggunaan Listrik</h3>

  <?php if ($total_rows > 0): ?>
    <div class="table-responsive mb-4">
      <table class="table styled-table">
        <thead>
          <tr>
            <th>No</th>
            <th>Bulan</th>
            <th>Tahun</th>
            <th>Meter Awal</th>
            <th>Meter Akhir</th>
            <th>Pemakaian (kWh)</th>
          </tr>
        </thead>
        <tbody>
          <?php 
            $no = 1;
            $labels = [];
            $values = [];
            foreach ($data as $row): 
              $labels[] = $row['bulan'] . ' ' . $row['tahun'];
              $values[] = $row['pemakaian_kwh'];
          ?>
            <tr>
              <td><?= $no++ ?></td>
              <td><?= $row['bulan'] ?></td>
              <td><?= $row['tahun'] ?></td>
              <td><?= $row['meter_awal'] ?></td>
              <td><?= $row['meter_akhir'] ?></td>
              <td><?= $row['pemakaian_kwh'] ?> kWh</td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <!-- Grafik -->
    <div class="chart-container mb-5">
      <h5 class="text-center">Grafik Pemakaian Listrik</h5>
      <canvas id="usageChart" height="100"></canvas>
    </div>

    <!-- Info -->
<div class="row text-center">
  <div class="col-md-4 mb-3">
    <div class="card border-0 shadow-sm" style="background-color:rgb(255, 255, 255);">
      <div class="card-body">
        <h5 class="card-title">Jumlah Baris</h5>
        <p class="card-text fs-4 fw-bold text-dark"><?= $total_rows ?></p>
      </div>
    </div>
  </div>
  <div class="col-md-4 mb-3">
    <div class="card border-0 shadow-sm" style="background-color:rgb(255, 255, 255);">
      <div class="card-body">
        <h5 class="card-title">Jumlah Kolom</h5>
        <p class="card-text fs-4 fw-bold text-dark">6</p>
      </div>
    </div>
  </div>
  <div class="col-md-4 mb-3">
    <div class="card border-0 shadow-sm" style="background-color:rgb(255, 255, 255);">
      <div class="card-body">
        <h5 class="card-title">Total Pemakaian</h5>
        <p class="card-text fs-4 fw-bold text-dark"><?= $total_kwh ?> kWh</p>
      </div>
    </div>
  </div>
</div>
  <?php else: ?>
    <div class="alert alert-warning text-center bg-light text-dark mt-4">Belum ada data penggunaan listrik.</div>
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

<!-- Script Chart -->
<script>
  const ctx = document.getElementById('usageChart').getContext('2d');
  new Chart(ctx, {
    type: 'bar',
    data: {
      labels: <?= json_encode($labels) ?>,
      datasets: [{
        label: 'Pemakaian (kWh)',
        data: <?= json_encode($values) ?>,
        backgroundColor: '#d5c2f9',
        borderRadius: 6
      }]
    },
    options: {
      scales: {
        y: { beginAtZero: true, ticks: { color: '#000' }},
        x: { ticks: { color: '#000' }}
      },
      plugins: {
        legend: {
          labels: { color: '#000' }
        }
      }
    }
  });
</script>

</body>
</html>
