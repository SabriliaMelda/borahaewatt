<?php
session_start();
include '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $username = $_POST['username'];
    $nomor_kwh = $_POST['nomor_kwh'];
    $alamat = $_POST['alamat'];
    $email = $_POST['email'];

    $update = "UPDATE users SET 
                username = '$username',
                nomor_kwh = '$nomor_kwh',
                alamat = '$alamat',
                email = '$email'
                WHERE id = $id";

    if (mysqli_query($conn, $update)) {
        header("Location: kelola_pengguna.php?status=success");
    } else {
        echo "Gagal mengupdate data: " . mysqli_error($conn);
    }
} else {
    header("Location: kelola_pengguna.php");
}
?>