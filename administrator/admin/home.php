<?php
session_start();
require_once '../includes/koneksi.php';

// Cek apakah user admin
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: ../index.php');
    exit;
}


function capitalizeAdminPhrase($text)
{
    // Ubah semua kata menjadi kapital di awal
    $text = ucwords(strtolower($text));

    // Gantilah 'Admin' secara eksplisit (agar huruf besar meskipun inputnya aneh)
    return str_ireplace('admin', 'Admin', $text);
}

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Admin</title>
    <link rel="icon" type="image/x-icon" href="../faskes/assets/favicon.ico">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../fontawesome/css/all.min.css">
    <script src="../js/bootstrap.bundle.min.js"></script>
    <script src="../assets/daftar.js"></script>
</head>

<body class="bg-light">

    <!-- Alert -->
    <?php if (isset($_SESSION['status'])):
        $splite = explode(', ', $_SESSION['status']); ?>
        <div class="position-fixed start-50 translate-middle-x z-3" style="top: 80px; min-width: 300px; max-width: 600px;">
            <div class="alert alert-<?= trim($splite[0]) ?> alert-dismissible fade show shadow text-center" role="alert">
                <?= $splite[1] . (count($splite) > 2 ? " " . $splite[2] : ''); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
        <?php unset($_SESSION['status']); ?>
    <?php endif; ?>



    <!-- Header -->
    <div class="container-fluid bg-white shadow-sm mb-4">
        <?php include '../layout/header.php'; ?>
    </div>

    <!-- Main Content -->
    <div class="container pt-5">
        <div class="card mx-auto shadow-lg rounded-4 p-4 mt-4" style="max-width: 600px;">
            <div class="card-body text-center">
                <h1 class="card-title mb-3 text-primary">Dashboard Admin</h1>
                <p class="lead">Halo, <strong><?= capitalizeAdminPhrase(htmlspecialchars($_SESSION['user']['username'])) ?></strong> 👋</p>
                <p class="text-muted">Selamat datang di halaman admin. Silakan kelola data dari menu yang tersedia di atas.</p>
                <i class="fas fa-user-shield fa-3x text-primary mt-3"></i>
            </div>
        </div>
    </div>

    <div class="container-fluid mt-2">
        <div class="text-center">
            <p>&copy; <?= date('Y') ?> Sistem Antrian. All rights reserved.</p>
        </div>
    </div>
</body>

</html>