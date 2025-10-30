<?php
session_start();
include 'config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Ambil data dari database
    $query = mysqli_query($conn, "SELECT * FROM users WHERE username = '$username'");
    $data = mysqli_fetch_assoc($query);

    // Verifikasi user ditemukan dan password cocok
    if ($data && password_verify($password, $data['password'])) {
         $_SESSION['id'] = $data['id'];
        $_SESSION['username'] = $data['username'];
        $_SESSION['role'] = $data['role'];

        // Redirect berdasarkan role
        if ($data['role'] === 'admin') {
            header("Location: admin/beranda_admin.php");
        } else {
            header("Location: pengguna/beranda.php");
        }
        exit();
    } else {
        echo "<script>alert('Username atau password salah!'); window.location='login.php';</script>";
    }
}
?>