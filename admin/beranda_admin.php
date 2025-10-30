<?php
session_start();
date_default_timezone_set("Asia/Jakarta");

$loggedIn = isset($_SESSION['username']) && isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
$username = $loggedIn ? $_SESSION['username'] : null;
$time = date("H:i");

if (!$loggedIn) {
  header("Location: login.php");
  exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Admin | BorahaeWatt</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet"/>
  <style>
    body { font-family: 'Segoe UI', sans-serif; }
    .admin-header { background-color: #e9d5ff; padding: 60px 0; }
    .feature-box {
      background: #fff;
      border-radius: 10px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.08);
      padding: 25px;
      transition: 0.3s ease-in-out;
    }
    .feature-box:hover {
      transform: translateY(-5px);
    }
    .feature-icon {
      font-size: 36px;
      color: #6f42c1;
      margin-bottom: 15px;
    }
    footer {
      background-color: #6f42c1;
      color: white;
      padding: 20px 0;
    }
    body { font-family: 'Segoe UI', sans-serif; }
    .hero { background-color: #f3e8ff; padding: 70px 0; }
    .feature-icon {
      font-size: 35px;
      background-color: #ede7f6;
      color: #6f42c1;
      width: 60px;
      height: 60px;
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: 50%;
      margin: 0 auto 15px;
    }
    .feature-box, .step-box {
      border: 1px solid #e0e0e0;
      border-radius: 12px;
      padding: 25px;
      background: #ffffff;
      transition: 0.3s ease-in-out;
    }
    .feature-box:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 16px rgba(0,0,0,0.1);
    }
    .step-box span {
      font-size: 30px;
      font-weight: bold;
      color: #6f42c1;
    }
    .section-title {
      font-weight: 600;
      color: #6f42c1;
    }
    footer {
      background-color: #6f42c1;
      color: white;
      padding: 20px 0;
    }
    .btn-primary-custom {
      background-color: #6f42c1;
      border-color: #6f42c1;
    }
    .btn-primary-custom:hover {
      background-color: #5a379f;
      border-color: #5a379f;
    }
    .nav-link {
  position: relative;
  transition: color 0.3s ease;
}

.nav-link.active {
  color: #6f42c1 !important;
  font-weight: 600;
}

.nav-link.active::after {
  content: "";
  position: absolute;
  bottom: 0;
  left: 0;
  height: 2px;
  width: 100%;
  background-color: #6f42c1;
}
  </style>
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
      <a href="../index.php" class="btn btn-primary-custom px-3 text-white">Logout</a>
    </div>
    </div>
  </nav>

<!-- Hero Section -->
<section id="beranda" class="hero">
  <div class="container">
    <div class="row align-items-center">
      <div class="col-md-6 text-center">
        <img src="\listrik\assets\img\1.png" alt="App" class="img-fluid">
      </div>
      <div class="col-md-6 mt-4 mt-md-0">
        <h1 class="fw-bold text-purple mb-3" style="color:#6f42c1;">Panel Admin - Kelola Tagihan Listrik Anda Lebih Cerdas</h1>
        <p class="mb-3">BorahaeWatt adalah platform digital yang memudahkan Anda dalam memantau dan membayar tagihan listrik pascabayar secara mudah, cepat, dan aman. Dengan tampilan yang menarik dan nuansa khas ARMY, BorahaeWatt hadir sebagai solusi pembayaran listrik yang modern dan menyenangkan.</p>
      </div>
    </div>
  </div>
</section>

<!-- Admin Features -->
<section class="py-5">
  <div class="container">
    <div class="row g-4 justify-content-center">

      <!-- Kelola Pengguna -->
      <div class="col-md-4 col-sm-6">
        <a href="kelola_pengguna.php" style="text-decoration: none; color: inherit;">
          <div class="feature-box h-100 p-4 border rounded shadow-sm d-flex flex-column justify-content-between text-center" style="min-height: 280px;">
            <div>
              <div class="feature-icon mb-3">
                <i class="bi bi-person-badge-fill fs-1 text-primary"></i>
              </div>
              <h5>Kelola Users</h5>
              <p>Tambah, edit, dan hapus data Users listrik.</p>
            </div>
          </div>
        </a>
      </div>

      <!-- Kelola Tagihan -->
      <div class="col-md-4 col-sm-6">
        <a href="tagihan.php" style="text-decoration: none; color: inherit;">
          <div class="feature-box h-100 p-4 border rounded shadow-sm d-flex flex-column justify-content-between text-center" style="min-height: 280px;">
            <div>
              <div class="feature-icon mb-3">
                <i class="bi bi-receipt fs-1 text-danger"></i>
              </div>
              <h5>Kelola Tagihan</h5>
              <p>Tambah dan pantau tagihan listrik pengguna.</p>
            </div>
          </div>
        </a>
      </div>

      <!-- Data Penggunaan -->
      <div class="col-md-4 col-sm-6">
        <a href="penggunaan.php" style="text-decoration: none; color: inherit;">
          <div class="feature-box h-100 p-4 border rounded shadow-sm d-flex flex-column justify-content-between text-center" style="min-height: 280px;">
            <div>
              <div class="feature-icon mb-3">
                <i class="bi bi-people fs-1 text-warning"></i>
              </div>
              <h5>Data Penggunaan</h5>
              <p>Lihat statistik dan aktivitas pengguna sistem.</p>
            </div>
          </div>
        </a>
      </div>

    </div>
  </div>
</section>

<!-- Footer -->
<footer class="text-white py-5" style="background-color:rgb(64, 29, 128);">
  <div class="container">
    <div class="row">

      <!-- Kontak Kami -->
      <div class="col-md-6 mb-4">
        <h3 class="fw-semibold">Kontak Kami</h3>
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

</body>
</html>