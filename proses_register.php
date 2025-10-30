<?php
include 'config/db.php'; // Pastikan file ini menghubungkan ke $conn

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username   = htmlspecialchars($_POST['username']);
    $nomor_kwh  = htmlspecialchars($_POST['nomor_kwh']);
    $alamat     = htmlspecialchars($_POST['alamat']);
    $email      = htmlspecialchars($_POST['email']);
    $password   = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Tentukan role berdasarkan username
    $role = (substr($username, -4) === '_adm') ? 'admin' : 'user';

    // Cek duplikasi username
    $cek = mysqli_query($conn, "SELECT * FROM users WHERE username = '$username'");
    if (mysqli_num_rows($cek) > 0) {
        echo "<script>alert('Username sudah digunakan!'); window.history.back();</script>";
    } else {
        $query = mysqli_query($conn, "INSERT INTO users 
            (username, nomor_kwh, alamat, email, password, role) 
            VALUES 
            ('$username', '$nomor_kwh', '$alamat', '$email', '$password', '$role')");

        if ($query) {
            echo "<script>alert('Registrasi berhasil! Silakan login.'); window.location='login.php';</script>";
        } else {
            echo "<script>alert('Registrasi gagal!'); window.history.back();</script>";
        }
    }
}
?>