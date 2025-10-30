<?php
session_start();
include '../config/db.php';
date_default_timezone_set("Asia/Jakarta");

if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// Proses simpan penggunaan
if (isset($_POST['simpan'])) {
    $id_user = $_POST['id_user'];
    $tarif = $_POST['tarif'];
    $bulan = $_POST['bulan'];
    $tahun = $_POST['tahun'];
    $meter_awal = $_POST['meter_awal'];
    $meter_akhir = $_POST['meter_akhir'];
    $pemakaian = $meter_akhir - $meter_awal;

    // Simpan ke tabel penggunaan
    $queryInsert = "INSERT INTO penggunaan (id_user, bulan, tahun, meter_awal, meter_akhir, pemakaian_kwh)
                    VALUES ('$id_user', '$bulan', '$tahun', '$meter_awal', '$meter_akhir', '$pemakaian')";
    if (mysqli_query($conn, $queryInsert)) {
        // Ambil nama pengguna
        $getUser = mysqli_query($conn, "SELECT username FROM users WHERE id = '$id_user'");
        $user = mysqli_fetch_assoc($getUser);
        $nama_pelanggan = $user['username'];

        // Hitung tarif per kWh berdasarkan jenis
        if ($tarif == 'kecil') {
            $tarif_per_kwh = 1300;
        } elseif ($tarif == 'sedang') {
            $tarif_per_kwh = 1500;
        } elseif ($tarif == 'besar') {
            $tarif_per_kwh = 2000;
        } else {
            $tarif_per_kwh = 1500; // default jika tidak sesuai
        }

        // Hitung total tagihan
        $total_tagihan = $pemakaian * $tarif_per_kwh;

        // Simpan ke tabel tagihan
        $insertTagihan = "INSERT INTO tagihan (id_user, nama_pelanggan, bulan, tahun, pemakaian_kwh, total_tagihan, status)
                          VALUES ('$id_user', '$nama_pelanggan', '$bulan', '$tahun', '$pemakaian', '$total_tagihan', 'Belum Lunas')";
        mysqli_query($conn, $insertTagihan);
    }
}

// Ambil semua data penggunaan
$query = "SELECT penggunaan.*, users.username 
          FROM penggunaan 
          JOIN users ON penggunaan.id_user = users.id 
          ORDER BY tahun DESC, bulan DESC";
$result = mysqli_query($conn, $query);

// Ambil data user
$dataUsers = mysqli_query($conn, "SELECT id, username FROM users WHERE role = 'user' ORDER BY username ASC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Data Penggunaan - BorahaeWatt</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body { background-color: #e6e0f8; font-family: 'Segoe UI', sans-serif; }
    .navbar { background-color: #fff; }
    .navbar-brand span { color: #6f42c1; font-weight: bold; }
    .nav-link.active { color: #6f42c1 !important; font-weight: bold; }
    .nav-link.active::after {
        content: ""; display: block; width: 100%;
        height: 2px; background-color: #6f42c1; margin-top: 3px;
    }
    h3 { color: #4c2a85; }
    .styled-table {
        background-color: #fff; border-radius: 10px;
        border: 1px solid #ddd; overflow: hidden;
    }
    .styled-table thead {
        background-color: #d5c2f9; font-weight: bold;
    }
    .styled-table th, .styled-table td {
        text-align: center; padding: 12px; vertical-align: middle;
    }
    .btn-primary-custom {
        background-color: #6f42c1; border-color: #6f42c1;
    }
    .btn-primary-custom:hover {
        background-color: #5a35a1; border-color: #5a35a1;
    }
    footer {
        background-color: #401d80; color: white; padding: 3rem 0;
    }
    footer a { color: white; }
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
        <li class="nav-item"><a class="nav-link active" href="#">Penggunaan</a></li>
      </ul>
      <a href="../index.php" class="btn btn-primary-custom px-3 text-white">Logout</a>
    </div>
  </div>
</nav>

<!-- Main -->
<main class="container mt-5 mb-5">
  <h3 class="text-center mb-4">Input Penggunaan Listrik</h3>
  <form method="POST" class="bg-white p-4 rounded shadow-sm mb-5">
    <div class="row mb-3">
      <div class="col-md-6">
        <label for="id_user" class="form-label">Pilih Pengguna</label>
        <select class="form-select" name="id_user" id="id_user" required>
          <option value="" disabled selected>-- Pilih Pengguna --</option>
          <?php while ($user = mysqli_fetch_assoc($dataUsers)): ?>
            <option value="<?= $user['id'] ?>"><?= htmlspecialchars($user['username']) ?></option>
          <?php endwhile; ?>
        </select>
      </div>
      <div class="col-md-6">
        <label for="tarif" class="form-label">Tarif</label>
        <select class="form-select" name="tarif" id="tarif" required>
          <option disabled selected>Pilih Tarif</option>
          <option value="kecil">Rumah Tangga Kecil (R-1/900 VA) kwh</option>
          <option value="sedang">Rumah Tangga Sedang (R-1/1300 VA) kwh</option>
          <option value="besar">Rumah Tangga Besar (R-2/2200 VA) kwh</option>
        </select>
      </div>
    </div>

    <div class="row mb-3">
      <div class="col-md-6">
        <label for="bulan" class="form-label">Bulan</label>
        <select class="form-select" name="bulan" id="bulan" required>
          <?php
            $bulanArr = [1=>"Januari",2=>"Februari",3=>"Maret",4=>"April",5=>"Mei",6=>"Juni",7=>"Juli",8=>"Agustus",9=>"September",10=>"Oktober",11=>"November",12=>"Desember"];
            foreach ($bulanArr as $key => $val) {
              echo "<option value='$key'>$val</option>";
            }
          ?>
        </select>
      </div>
      <div class="col-md-6">
        <label for="tahun" class="form-label">Tahun</label>
        <input type="number" class="form-control" name="tahun" id="tahun" min="2020" max="2099" required>
      </div>
    </div>

    <div class="row mb-4">
      <div class="col-md-6">
        <label for="meter_awal" class="form-label">Meter Awal</label>
        <input type="number" class="form-control" name="meter_awal" id="meter_awal" required>
      </div>
      <div class="col-md-6">
        <label for="meter_akhir" class="form-label">Meter Akhir</label>
        <input type="number" class="form-control" name="meter_akhir" id="meter_akhir" required>
      </div>
    </div>

    <div class="text-end">
      <button type="submit" name="simpan" class="btn btn-primary-custom px-4">Simpan</button>
    </div>
  </form>

  <h3 class="text-center mb-4">Data Penggunaan Listrik</h3>
  <?php if (mysqli_num_rows($result) > 0): ?>
    <div class="table-responsive">
      <table class="table styled-table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Bulan</th>
            <th>Tahun</th>
            <th>Meter Awal</th>
            <th>Meter Akhir</th>
            <th>Pemakaian (kWh)</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
              <td><?= $row['id_penggunaan'] ?></td>
              <td><?= htmlspecialchars($row['username']) ?></td>
              <td><?= $bulanArr[(int)$row['bulan']] ?></td>
              <td><?= $row['tahun'] ?></td>
              <td><?= $row['meter_awal'] ?></td>
              <td><?= $row['meter_akhir'] ?></td>
              <td><?= $row['pemakaian_kwh'] ?></td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  <?php else: ?>
    <div class="alert alert-info text-center">Belum ada data penggunaan yang tersedia.</div>
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
