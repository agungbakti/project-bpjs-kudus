<?php 
session_start();

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: ../index.php');
    exit;
}

include('../includes/koneksi.php') 
?>

<?php
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "DELETE FROM `hari_libur` WHERE `id` = '$id'";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        $_SESSION['status'] = "error, Gagal!, terjadi kesalahan saat menyimpan data!";
    } else {
        $_SESSION['status'] = "success, Berhasil!, Hari libur berhasil dihapus!";
    }
    header('Location: hari-libur.php');
    exit();
}


?>