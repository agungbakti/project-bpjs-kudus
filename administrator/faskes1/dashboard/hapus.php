<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: ../../index.php');
    exit;
}
require "../database/function_faskes.php";

$id = $_GET['id'];

if( hapus ($id) > 0 ){
    echo "
    <script> alert('Data Berhasil Dihapus')
    document.location.href = 'admin.php';
    </script>
    ";
}else{
    echo "
    <script>alert('Mohon maaf data gagal dihapus')
    document.location.href = 'admin.php';
    </script>
    ";
}

?>