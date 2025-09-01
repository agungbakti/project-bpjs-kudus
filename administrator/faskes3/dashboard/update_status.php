<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: ../../index.php');
    exit;
}
require "../database/function_faskes.php";
// update_status.php
if (isset($_POST['status']) && isset($_POST['id_faskes'])) {
    $id_faskes = $_POST['id_faskes'];
    $status_id = $_POST['status'];
    
    // Query untuk mengupdate status
    $query = "UPDATE tb_faskes SET id_status = '$status_id' WHERE id_faskes = '$id_faskes'";
    mysqli_query($koneksi, $query);

    // Redirect kembali ke halaman index.php
    if(!isset($_POST['ajax'])){
        header("Location: admin.php");
        echo 'success';
        exit;
    }
}
?>