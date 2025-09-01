<?php
require_once '../includes/koneksi.php';
session_start();


if ($_SESSION['user']['role'] !== 'admin') {
    header('Location: ../index.php');
    exit;
}

$conn->query("DELETE FROM antrian");
header('Location: antrian.php');
exit;
