<?php
require_once '../includes/koneksi.php';
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: ../index.php');
    exit;
}

header('Content-Type: application/json');

// Cek apakah user admin
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = $_POST['id'];
    $result = $conn->query("DELETE FROM antrian WHERE id_antrian=$id");
    
    if ($result) {
        echo json_encode(['success' => true, 'message' => 'Antrian berhasil dihapus']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Antrian gagal dihapus']);
    }
    exit;
}

echo json_encode(['success' => false, 'message' => 'Invalid request']);
?>