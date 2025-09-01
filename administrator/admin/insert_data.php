<?php 
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: ../index.php');
    exit;
}
require '../includes/koneksi.php';

if (isset($_POST['tambahUser'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $noHp = mysqli_real_escape_string($conn, $_POST['noHp']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = mysqli_real_escape_string($conn, $_POST['role']);

    // Cek apakah username, email, atau no_hp sudah ada
    $cek_query = "SELECT * FROM users WHERE username='$username' OR email='$email' OR no_hp='$noHp'";
    $cek_result = mysqli_query($conn, $cek_query);

    if (mysqli_num_rows($cek_result) > 0) {
        $_SESSION['status'] = "error, Gagal!, username email atau no HP sudah digunakan!";
        header('Location: index.php');
        exit();
    }

    // Jika tidak ada yang sama, lanjut insert
    $query = "INSERT INTO users (username, email, no_hp, password, role, verify_status) 
              VALUES ('$username', '$email', '$noHp', '$password', '$role', 1)";

    $result = mysqli_query($conn, $query);

    if (!$result) {
        $_SESSION['status'] = "error, Gagal!, terjadi kesalahan saat menyimpan data!";
    } else {
        $_SESSION['status'] = "success, Berhasil!, user berhasil ditambahkan!";
    }
    header('Location: index.php');
    exit();
}
?>
