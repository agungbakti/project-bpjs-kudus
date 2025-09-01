<?php
session_start();
header('Content-Type: application/json'); // set response JSON

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Akses ditolak']);
    exit;
}

require "../database/function_faskes.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_faskes'])) {
    $id_faskes = $_POST['id_faskes'];
    $id_status = $_POST['status'];
    $id_keterangan = $_POST['keterangan'];
    $nama = htmlspecialchars($_POST['nama']);

    // Update status
    if (!empty($id_status)) {
        $query_status = "UPDATE tb_faskes1 SET id_status = '$id_status' WHERE id_faskes = '$id_faskes'";
        mysqli_query($koneksi, $query_status);
    }

    // Update keterangan
    if (!empty($id_keterangan)) {
        $query_keterangan = "UPDATE tb_faskes1 SET id_keterangan = '$id_keterangan' WHERE id_faskes = '$id_faskes'";
        mysqli_query($koneksi, $query_keterangan);
    }

    // Update nama
    if (!empty($nama)) {
        $query_nama = "UPDATE tb_faskes1 SET nama = '$nama' WHERE id_faskes = '$id_faskes'";
        mysqli_query($koneksi, $query_nama);
    }

    echo json_encode(['success' => true, 'message' => 'Data berhasil diupdate']);
    exit;
} else {
    echo json_encode(['success' => false, 'message' => 'Request tidak valid']);
    exit;
}
