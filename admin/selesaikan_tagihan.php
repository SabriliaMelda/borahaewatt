<?php
session_start();
include '../config/db.php';

if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id_tagihan = $_GET['id'];

    // Ambil data tagihan
    $query = "SELECT * FROM tagihan WHERE id = $id_tagihan";
    $result = mysqli_query($conn, $query);
    $data = mysqli_fetch_assoc($result);

    if ($data) {
        $id_user = $data['id_user'];
        $bulan = $data['bulan'];
        $tahun = $data['tahun'];

        // Update status jadi LUNAS
        $update = "UPDATE tagihan SET status = 'Lunas' WHERE id = $id_tagihan";

        if (mysqli_query($conn, $update)) {
            // Kirim notifikasi ke user
            $pesan = "Tagihan listrik bulan $bulan $tahun telah diverifikasi. Harap segera melakukan pembayaran.";
            $notif = "INSERT INTO notifikasi (id_user, pesan) VALUES ('$id_user', '$pesan')";
            mysqli_query($conn, $notif);

            header("Location: tagihan.php?msg=Tagihan berhasil diselesaikan & notifikasi dikirim.");
            exit();
        } else {
            echo "Gagal memperbarui status tagihan.";
        }
    } else {
        echo "Data tagihan tidak ditemukan.";
    }
}
?>