<?php
require "../database/function_faskes.php";

if ($_POST['action'] && $_POST['id_faskes'] && isset($_POST['value'])) {
    $action = $_POST['action'];
    $id_faskes = $_POST['id_faskes'];
    $value = $_POST['value'];
    
    try {
        switch ($action) {
            case 'status':
                $query = "UPDATE tb_faskes SET id_status = ? WHERE id_faskes = ?";
                $stmt = mysqli_prepare($koneksi, $query);
                mysqli_stmt_bind_param($stmt, "ii", $value, $id_faskes);
                break;
                
            case 'keterangan':
                $query = "UPDATE tb_faskes SET id_keterangan = ? WHERE id_faskes = ?";
                $stmt = mysqli_prepare($koneksi, $query);
                mysqli_stmt_bind_param($stmt, "ii", $value, $id_faskes);
                break;
                
            case 'nama':
                $query = "UPDATE tb_faskes SET nama = ? WHERE id_faskes = ?";
                $stmt = mysqli_prepare($koneksi, $query);
                mysqli_stmt_bind_param($stmt, "si", $value, $id_faskes);
                break;
                
            default:
                echo 'error';
                exit;
        }
        
        if (mysqli_stmt_execute($stmt)) {
            echo 'success';
        } else {
            echo 'error';
        }
        
        mysqli_stmt_close($stmt);
        
    } catch (Exception $e) {
        echo 'error';
    }
} else {
    echo 'error';
}
?>