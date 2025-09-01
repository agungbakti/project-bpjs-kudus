<?php
session_start();
require "../database/function_faskes.php"; // Pastikan path ini benar
require "../database/koneksi.php"; // Pastikan path ini benar untuk koneksi database

// Pastikan hanya admin yang bisa mengakses
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    http_response_code(403); // Forbidden
    echo json_encode(['success' => false, 'message' => 'Akses ditolak.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_faskes = $_POST['id'];
    $field_name = $_POST['field'];
    $value = $_POST['value'];

    $sql = "";
    if ($field_name === 'status') {
        $sql = "UPDATE tb_faskes SET id_status = ? WHERE id_faskes = ?";
    } elseif ($field_name === 'keterangan') {
        $sql = "UPDATE tb_faskes SET id_keterangan = ? WHERE id_faskes = ?";
    } elseif ($field_name === 'nama') {
        $sql = "UPDATE tb_faskes SET nama = ? WHERE id_faskes = ?";
    } else {
        echo json_encode(['success' => false, 'message' => 'Bidang tidak valid.']);
        exit;
    }

    // Gunakan prepared statement untuk keamanan
    if ($stmt = mysqli_prepare($koneksi, $sql)) {
        // Jika value kosong, set ke NULL di database untuk field yang nullable (status dan keterangan)
        if (empty($value) && ($field_name === 'status' || $field_name === 'keterangan')) {
            $value_to_bind = null;
            mysqli_stmt_bind_param($stmt, "si", $value_to_bind, $id_faskes);
        } else {
             // Pastikan tipe data sesuai. 's' untuk string, 'i' untuk integer
            $param_type = ($field_name === 'status' || $field_name === 'keterangan') ? "ii" : "si";
            if ($field_name === 'nama') {
                 mysqli_stmt_bind_param($stmt, "si", $value, $id_faskes);
            } else {
                 mysqli_stmt_bind_param($stmt, "ii", $value, $id_faskes);
            }
        }
       
        if (mysqli_stmt_execute($stmt)) {
            echo json_encode(['success' => true, 'message' => 'Data berhasil diperbarui.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Gagal memperbarui data: ' . mysqli_stmt_error($stmt)]);
        }
        mysqli_stmt_close($stmt);
    } else {
        echo json_encode(['success' => false, 'message' => 'Prepared statement gagal: ' . mysqli_error($koneksi)]);
    }
} else {
    http_response_code(405); // Method Not Allowed
    echo json_encode(['success' => false, 'message' => 'Metode request tidak diizinkan.']);
}
?>