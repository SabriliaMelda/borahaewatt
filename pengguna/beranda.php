<?php
session_start();
include '../config/db.php';
date_default_timezone_set("Asia/Jakarta");

$loggedIn = isset($_SESSION['username']);
$username = $loggedIn ? $_SESSION['username'] : null;
$time = date("H:i");

$id_user = $_SESSION['id'];
$notifQuery = "SELECT * FROM notifikasi WHERE id_user = '$id_user' ORDER BY waktu DESC LIMIT 5";
$notifResult = mysqli_query($conn, $notifQuery);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>BorahaeWatt</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet"/>
  <style>
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
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const navLinks = document.querySelectorAll('.navbar-nav .nav-link');
    const sections = [];

    navLinks.forEach(link => {
      const targetId = link.getAttribute('href').replace('#', '');
      const section = document.getElementById(targetId);
      if (section) {
        sections.push({
          id: targetId,
          section: section,
          link: link
        });
      }

      // Klik langsung juga aktif
      link.addEventListener('click', function () {
        navLinks.forEach(l => l.classList.remove('active'));
        this.classList.add('active');
      });
    });

    window.addEventListener('scroll', function () {
      const scrollPosition = window.scrollY + 100;

      sections.forEach(s => {
        const offsetTop = s.section.offsetTop;
        const offsetBottom = offsetTop + s.section.offsetHeight;

        if (scrollPosition >= offsetTop && scrollPosition < offsetBottom) {
          navLinks.forEach(l => l.classList.remove('active'));
          s.link.classList.add('active');
        }
      });
    });
  });
  </script>

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
      <ul class="navbar-nav me-4">
        <li class="nav-item"><a class="nav-link active" href="#beranda">Beranda</a></li>
        <li class="nav-item"><a class="nav-link" href="#fitur">Fitur</a></li>
        <li class="nav-item"><a class="nav-link" href="#about">Tentang</a></li>
        <li class="nav-item"><a class="nav-link" href="#kontak">Kontak</a></li>
      </ul>
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
        <h1 class="fw-bold text-purple mb-3" style="color:#6f42c1;">Kelola Tagihan Listrik Anda Lebih Cerdas</h1>
        <p class="mb-3">BorahaeWatt adalah platform digital yang memudahkan Anda dalam memantau dan membayar tagihan listrik pascabayar secara mudah, cepat, dan aman. Dengan tampilan yang menarik dan nuansa khas ARMY, BorahaeWatt hadir sebagai solusi pembayaran listrik yang modern dan menyenangkan.</p>
      </div>
    </div>
  </div>
</section>

<!-- Fitur -->
<section id="fitur" class="py-5">
  <div class="container text-center">
    <h2 class="section-title mb-5">Fitur BorahaeWatt</h2>
    <div class="row g-4">
      
      <!-- Input Penggunaan -->
      <div class="col-md-6 col-lg-6">
        <div style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#inputPenggunaanModal">
          <div class="feature-box h-100 p-4 border rounded shadow-sm d-flex flex-column">
            <div class="feature-icon mb-3"><i class="bi bi-pencil-square fs-2 text-primary"></i></div>
            <h5 class="mb-2">Input Penggunaan</h5>
            <p class="mb-0">Masukkan data penggunaan listrik berdasarkan ID pelanggan, tarif, meteran, bulan dan tahun.</p>
          </div>
        </div>
      </div>
<!-- Modal Input Penggunaan -->
<div class="modal fade" id="inputPenggunaanModal" tabindex="-1" aria-labelledby="inputPenggunaanModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="inputPenggunaanModalLabel">Form Input Penggunaan Listrik</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="simpan_penggunaan.php" method="POST">

          <div class="row">
            <!-- ID Pelanggan -->
            <div class="col-md-6 mb-3">
              <label for="id_pelanggan" class="form-label">ID Pelanggan</label>
              <input type="text" class="form-control" id="id_pelanggan" name="id_pelanggan" value="<?= $_SESSION['id'] ?>" readonly>
            </div>

            <!-- Tarif -->
            <div class="col-md-6 mb-3">
              <label for="tarif" class="form-label">Tarif</label>
              <select class="form-select" id="tarif" name="tarif" required>
                <option value="" disabled selected>Pilih tarif</option>
                <option value="kecil">Rumah Tangga Kecil (R-1/900 VA) kwh</option>
                <option value="sedang">Rumah Tangga Sedang (R-1/1300 VA) kwh</option>
                <option value="besar">Rumah Tangga Besar (R-2/2200 VA) kwh</option>
              </select>
            </div>

            <!-- Bulan -->
            <div class="col-md-6 mb-3">
              <label for="bulan" class="form-label">Bulan</label>
              <select class="form-select" id="bulan" name="bulan" required>
                <?php
                  $bulan = [
                    1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                    5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                    9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                  ];
                  foreach ($bulan as $key => $nama) {
                    echo "<option value='$key'>$nama</option>";
                  }
                ?>
              </select>
            </div>

            <!-- Tahun -->
            <div class="col-md-6 mb-3">
              <label for="tahun" class="form-label">Tahun</label>
              <input type="number" class="form-control" id="tahun" name="tahun" min="2000" max="2099" required>
            </div>

            <!-- Meter Awal -->
            <div class="col-md-6 mb-3">
              <label for="meter_awal" class="form-label">Meter Awal</label>
              <input type="number" class="form-control" id="meter_awal" name="meter_awal" required>
            </div>

            <!-- Meter Akhir -->
            <div class="col-md-6 mb-3">
              <label for="meter_akhir" class="form-label">Meter Akhir</label>
              <input type="number" class="form-control" id="meter_akhir" name="meter_akhir" required>
            </div>
          </div>

          <div class="text-end">
            <button type="submit" class="btn btn-primary">Simpan Penggunaan</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

      <!-- Riwayat Penggunaan -->
      <div class="col-md-6 col-lg-6">
        <a href="riwayat_penggunaan.php" style="text-decoration: none; color: inherit;">
          <div class="feature-box h-100 p-4 border rounded shadow-sm d-flex flex-column">
            <div class="feature-icon mb-3"><i class="bi bi-clock-history fs-2 text-primary"></i></div>
            <h5 class="mb-2">Riwayat Penggunaan</h5>
            <p class="mb-0">Lihat riwayat penggunaan listrik Anda secara lengkap berdasarkan periode waktu tertentu.</p>
          </div>
        </a>
      </div>

      <!-- Daftar Tagihan -->
      <div class="col-md-6 col-lg-6">
        <a href="daftar_tagihan.php" style="text-decoration: none; color: inherit;">
          <div class="feature-box h-100 p-4 border rounded shadow-sm d-flex flex-column">
            <div class="feature-icon mb-3"><i class="bi bi-file-earmark-text fs-2 text-primary"></i></div>
            <h5 class="mb-2">Daftar Tagihan</h5>
            <p class="mb-0">Cek daftar tagihan listrik Anda secara terperinci dan sesuai dengan penggunaan yang telah dimasukkan.</p>
          </div>
        </a>
      </div>

      <!-- Notifikasi -->
      <div class="col-md-6 col-lg-6">
        <div style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#notifikasiModal">
          <div class="feature-box h-100 p-4 border rounded shadow-sm d-flex flex-column">
            <div class="feature-icon mb-3"><i class="bi bi-bell fs-2 text-primary"></i></div>
            <h5 class="mb-2">Notifikasi</h5>
            <p class="mb-0">Lihat rincian tagihan listrik Anda berdasarkan penggunaan setiap bulan, lengkap dengan perhitungan tarif yang sesuai.</p>
          </div>
        </a>
      </div>


      <!-- Modal Notifikasi -->
<div class="modal fade" id="notifikasiModal" tabindex="-1" aria-labelledby="notifikasiModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="notifikasiModalLabel">Notifikasi Anda</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        <?php if (mysqli_num_rows($notifResult) > 0): ?>
          <ul class="list-group">
            <?php while ($notif = mysqli_fetch_assoc($notifResult)): ?>
              <li class="list-group-item">
                <?= htmlspecialchars($notif['pesan']) ?><br>
                <small class="text-muted"><?= date('d M Y, H:i', strtotime($notif['waktu'])) ?></small>
              </li>
            <?php endwhile; ?>
          </ul>
        <?php else: ?>
          <div class="alert alert-info mb-0">
            Tidak ada notifikasi.
          </div>
        <?php endif; ?>
      </div>

    </div>
  </div>
</div>

    </div>
  </div>
</section>

  <!-- About Section -->
<section id="about" class="py-5">
  <div class="container text-center">
    <h2 class="section-title mb-3">Tentang Kami</h2>
    <p>BorahaeWatt adalah platform digital inovatif yang hadir untuk memberikan solusi mudah, cepat, dan aman dalam pembayaran tagihan listrik pascabayar. Terinspirasi dari semangat positif dan penuh cinta antara BTS dan para ARMY di seluruh dunia, BorahaeWatt mengusung konsep yang unik dan penuh warna, menghadirkan pengalaman digital yang berbeda dari layanan konvensional.</p>
    <p>Nama "BorahaeWatt" berasal dari kombinasi kata "borahae", yang berarti "aku akan mencintaimu sampai akhir"—ungkapan ikonik dari Kim Taehyung (V) BTS untuk para ARMY—dan "watt", satuan daya listrik. Gabungan ini merepresentasikan komitmen kami untuk menghadirkan layanan yang penuh kasih, terang, dan dapat diandalkan seperti energi listrik yang tak terpisahkan dari kehidupan sehari-hari.</p>
    <p>BorahaeWatt tidak hanya sekadar aplikasi pembayaran, tetapi juga media untuk mempererat hubungan antara teknologi dan komunitas muda. Dengan desain yang segar, antarmuka bertema ungu khas ARMY, serta berbagai fitur yang user-friendly, kami berupaya menjadikan setiap interaksi pengguna sebagai pengalaman yang menyenangkan dan inspiratif.</p>
    <p>Kami percaya bahwa hal-hal teknis seperti membayar tagihan listrik pun bisa terasa lebih personal dan menyenangkan, terutama ketika dikemas dengan sentuhan kreativitas, fandom, dan cinta. BorahaeWatt berkomitmen untuk terus berkembang dan menjadi platform andalan dalam manajemen energi rumah tangga yang praktis dan penuh gaya.</p>
  </div>
</section>

<!-- Contact Section -->
<section id="kontak" class="py-5 bg-light">
  <div class="container">
    <h2 class="section-title text-center mb-4">Hubungi Kami</h2>
    <div class="row align-items-center justify-content-center">
      
      <!-- Form Kontak -->
      <div class="col-md-6 mb-4 mb-md-0">
        <form>
          <div class="mb-3">
            <label for="nama" class="form-label">Nama</label>
            <input type="text" class="form-control" id="nama" required>
          </div>
          <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" required>
          </div>
          <div class="mb-3">
            <label for="pesan" class="form-label">Pesan</label>
            <textarea class="form-control" id="pesan" rows="4" required></textarea>
          </div>
          <button type="submit" class="btn btn-primary-custom text-white">Kirim</button>
        </form>
      </div>

      <!-- Gambar Kontak -->
      <div class="col-md-6 ps-md-5 text-center">
        <img src="\listrik\assets\img\contact.png" alt="Ilustrasi Kontak" class="img-fluid" style="max-height: 400px;">
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
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
