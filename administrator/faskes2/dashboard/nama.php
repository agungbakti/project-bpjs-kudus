<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: ../../index.php');
    exit;
}
require "../database/function_faskes.php";

if (isset($_POST['nama']) && isset($_POST['id_faskes'])) {
    $nama = htmlspecialchars($_POST['nama']);
    $id_faskes = $_POST['id_faskes']; 
    
    // Query untuk mengupdate nama 
    $query = "UPDATE tb_faskes SET nama = '$nama' WHERE id_faskes = '$id_faskes'";

    if (mysqli_query($koneksi, $query)) {
        if(!isset($_POST['ajax'])){
            header("Location: admin.php");
            echo 'success';
            exit;
        }
    }
}
?>
