<?php
include '../config/db.php';
session_start();

// Cek login
if (!isset($_SESSION['id'])) {
    header("Location: ../login.php");
    exit;
}

$id_user = $_SESSION['id'];
$bulan = $_POST['bulan'];
$tahun = $_POST['tahun'];
$meter_awal = $_POST['meter_awal'];
$meter_akhir = $_POST['meter_akhir'];
$tarif = $_POST['tarif'];

if ($meter_akhir < $meter_awal) {
    echo "Meter akhir tidak boleh lebih kecil dari meter awal.";
    exit;
}

$pemakaian = $meter_akhir - $meter_awal;

// Simpan ke tabel penggunaan
$sql_penggunaan = "INSERT INTO penggunaan (id_user, bulan, tahun, meter_awal, meter_akhir, pemakaian_kwh)
                   VALUES ('$id_user', '$bulan', '$tahun', '$meter_awal', '$meter_akhir', '$pemakaian')";

if (!mysqli_query($conn, $sql_penggunaan)) {
    echo "Gagal menyimpan data penggunaan: " . mysqli_error($conn);
    exit;
}

// Hitung total tagihan berdasarkan tarif
switch ($tarif) {
    case 'kecil':
        $harga_per_kwh = 1300;
        break;
    case 'sedang':
        $harga_per_kwh = 1500;
        break;
    case 'besar':
        $harga_per_kwh = 2000;
        break;
    default:
        $harga_per_kwh = 0;
        break;
}

$total_tagihan = $pemakaian * $harga_per_kwh;

// Ambil nama user untuk disimpan ke tagihan
$query_user = mysqli_query($conn, "SELECT username FROM users WHERE id = '$id_user'");
$user_data = mysqli_fetch_assoc($query_user);
$nama_pelanggan = $user_data['username'];

// Simpan ke tabel tagihan
$sql_tagihan = "INSERT INTO tagihan (id_user, nama_pelanggan, bulan, tahun, pemakaian_kwh, total_tagihan, status)
                VALUES ('$id_user', '$nama_pelanggan', '$bulan', '$tahun', '$pemakaian', '$total_tagihan', 'Belum Lunas')";

if (!mysqli_query($conn, $sql_tagihan)) {
    echo "Gagal menyimpan tagihan: " . mysqli_error($conn);
    exit;
}

// Berhasil
header("Location: beranda.php?msg=sukses");
exit;
?>