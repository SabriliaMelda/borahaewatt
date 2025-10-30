<?php
session_start();
include '../config/db.php';

// Cek login dan role
if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// Validasi ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: kelola_pengguna.php");
    exit();
}

$id = intval($_GET['id']);

// Cek apakah user tersebut ada dan bukan super admin
$userCheck = mysqli_query($conn, "SELECT * FROM users WHERE id = $id");
$user = mysqli_fetch_assoc($userCheck);

if (!$user) {
    echo "<script>alert('User tidak ditemukan.'); window.location='kelola_pengguna.php';</script>";
    exit();
}

// Cegah admin menghapus dirinya sendiri
if ($id == $_SESSION['id']) {
    echo "<script>alert('Tidak dapat menghapus akun sendiri.'); window.location='kelola_pengguna.php';</script>";
    exit();
}

// Eksekusi hapus
$delete = mysqli_query($conn, "DELETE FROM users WHERE id = $id");

if ($delete) {
    echo "<script>alert('Pengguna berhasil dihapus.'); window.location='kelola_pengguna.php';</script>";
} else {
    echo "<script>alert('Gagal menghapus pengguna.'); window.location='kelola_pengguna.php';</script>";
}
?>