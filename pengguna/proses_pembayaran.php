<?php
session_start();
include '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_tagihan = $_POST['id_tagihan'] ?? null;

    if (!$id_tagihan) {
        $_SESSION['msg'] = "❌ ID tagihan tidak ditemukan.";
        header("Location: daftar_tagihan.php");
        exit();
    }

    // Cek apakah file bukti pembayaran diupload
    if (isset($_FILES['bukti_pembayaran']) && $_FILES['bukti_pembayaran']['error'] === 0) {
        $file_tmp = $_FILES['bukti_pembayaran']['tmp_name'];
        $file_name = $_FILES['bukti_pembayaran']['name'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $allowed_ext = ['jpg', 'jpeg', 'png', 'pdf'];

        if (in_array($file_ext, $allowed_ext)) {
            $new_filename = uniqid('bukti_') . '.' . $file_ext;
            $upload_path = '../uploads/' . $new_filename;

            if (move_uploaded_file($file_tmp, $upload_path)) {
                // Simpan ke database
                $query = "UPDATE tagihan 
          SET bukti_pembayaran = '$new_filename', status = 'Belum Lunas' 
          WHERE id = '$id_tagihan'";


                if (mysqli_query($conn, $query)) {
                    $_SESSION['msg'] = "✅ Bukti pembayaran berhasil diunggah.";
                } else {
                    $_SESSION['msg'] = "❌ Gagal menyimpan ke database.";
                }
            } else {
                $_SESSION['msg'] = "❌ Gagal upload file ke folder.";
            }
        } else {
            $_SESSION['msg'] = "❌ Format file tidak didukung (hanya jpg, jpeg, png, pdf).";
        }
    } else {
        $_SESSION['msg'] = "❌ File bukti pembayaran belum dipilih.";
    }

    header("Location: daftar_tagihan.php");
    exit();
}
?>
