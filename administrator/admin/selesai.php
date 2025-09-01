<?php
require_once '../includes/koneksi.php';
session_start();
date_default_timezone_set('Asia/Jakarta'); //ini

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
    $waktuSekarang = date('H:i:s');
    $result = $conn->query("UPDATE antrian SET status='finished', time_finished='$waktuSekarang' WHERE id_antrian=$id");
    
    if ($result) {
        echo json_encode(['success' => true, 'message' => 'Antrian berhasil diselesaikan']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Gagal menyelesaikan antrian']);
    }
    exit;
}

echo json_encode(['success' => false, 'message' => 'Invalid request']);
?>