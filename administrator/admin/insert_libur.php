<?php 
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: ../index.php');
    exit;
}
require '../includes/koneksi.php';

if (isset($_POST['tambahLibur'])) {
    $tanggal = mysqli_real_escape_string($conn, $_POST['tanggal']);
    $keterangan = mysqli_real_escape_string($conn, $_POST['keterangan']);

    // Cek apakah username, email, atau no_hp sudah ada
    $cek_query = "SELECT * FROM hari_libur WHERE tanggal='$tanggal'";
    $cek_result = mysqli_query($conn, $cek_query);

    if (mysqli_num_rows($cek_result) > 0) {
        $_SESSION['status'] = "error, Gagal!, hari libur sudah dimasukan";
        header('Location: hari-libur.php');
        exit();
    }

    // Jika tidak ada yang sama, lanjut insert
    $query = "INSERT INTO hari_libur (tanggal, keterangan) 
              VALUES ('$tanggal', '$keterangan')";

    $result = mysqli_query($conn, $query);

    if (!$result) {
        $_SESSION['status'] = "error, Gagal!, terjadi kesalahan saat menyimpan data!";
    } else {
        $_SESSION['status'] = "success, Berhasil!, hari libur berhasil ditambahkan";
    }
    header('Location: hari-libur.php');
    exit();
}
?>
