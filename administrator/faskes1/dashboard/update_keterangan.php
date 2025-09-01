<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: ../../index.php');
    exit;
}
require "../database/function_faskes.php";

// update_keterangan.php
if (isset($_POST['keterangan'])&& isset($_POST['id_faskes'])) {
    $id_faskes = $_POST['id_faskes'];
    $keterangan_id = $_POST['keterangan'];
    
    // Query untuk mengupdate keterangan
    $query = "UPDATE tb_faskes1 SET id_keterangan = '$keterangan_id' WHERE id_faskes = '$id_faskes'";
    mysqli_query($koneksi, $query);

    // Redirect kembali ke halaman index.php
    if(!isset($_POST['ajax'])){
    header("Location: admin.php");
    echo 'success';
    exit;
}
}

?>
