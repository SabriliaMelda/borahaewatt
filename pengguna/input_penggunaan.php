<?php
session_start();
include '../config/db.php';

if (!isset($_SESSION['id'])) {
    header("Location: ../login.php");
    exit;
}

$id_pelanggan = $_SESSION['id'];// otomatis ambil dari session

// ketika insert ke tabel penggunaan:
if (isset($_POST['simpan'])) {
  $bulan = $_POST['bulan'];
  $tahun = $_POST['tahun'];
  $meter_awal = $_POST['meter_awal'];
  $meter_akhir = $_POST['meter_akhir'];

  $query = "INSERT INTO penggunaan (id_pelanggan, bulan, tahun, meter_awal, meter_akhir)
            VALUES ('$id_pelanggan', '$bulan', '$tahun', '$meter_awal', '$meter_akhir')";
  
  if (mysqli_query($conn, $query)) {
    echo "Penggunaan berhasil disimpan.";
  } else {
    echo "Gagal: " . mysqli_error($conn);
  }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Input Penggunaan Listrik</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container py-5">
  <h2 class="mb-4 text-center">Form Input Penggunaan Listrik</h2>
  <form action="simpan_penggunaan.php" method="POST">

    <!-- ID Pelanggan (readonly dan otomatis) -->
    <div class="mb-3">
      <label for="id_pelanggan" class="form-label">ID Pelanggan</label>
      <input type="text" class="form-control" id="id_pelanggan" name="id_pelanggan" value="<?= $id_pelanggan ?>" readonly>
    </div>

    <!-- Tarif -->
<div class="mb-3">
  <label for="tarif" class="form-label">Tarif</label>
  <select class="form-select" id="tarif" name="tarif" required>
    <option value="" disabled selected>Pilih tarif</option>
    <option value="kecil">Rumah Tangga Kecil (R-1/900 VA) – 450–900 kWh</option>
    <option value="sedang">Rumah Tangga Sedang (R-1/1300 VA) – 901–1300 kWh </option>
    <option value="besar">Rumah Tangga Besar (R-2/2200 VA) – 1301–2200 kWh </option>
  </select>
</div>

    <!-- Bulan -->
    <div class="mb-3">
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
    <div class="mb-3">
      <label for="tahun" class="form-label">Tahun</label>
      <input type="number" class="form-control" id="tahun" name="tahun" min="2000" max="2099" required>
    </div>

    <!-- Meter Awal -->
    <div class="mb-3">
      <label for="meter_awal" class="form-label">Meter Awal</label>
      <input type="number" class="form-control" id="meter_awal" name="meter_awal" required>
    </div>

    <!-- Meter Akhir -->
    <div class="mb-3">
      <label for="meter_akhir" class="form-label">Meter Akhir</label>
      <input type="number" class="form-control" id="meter_akhir" name="meter_akhir" required>
    </div>

    <!-- Submit -->
    <button type="submit" class="btn btn-primary">Simpan Penggunaan</button>

  </form>
</div>

</body>
</html>