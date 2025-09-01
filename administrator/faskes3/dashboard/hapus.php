<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: ../../index.php');
    exit;
}

require "../database/function_faskes.php";
$id = $_GET['id'];

if (hapus($id) > 0) {
    $status = 'success';
    $title = 'Berhasil';
    $message = 'Data berhasil dihapus!';
} else {
    $status = 'error';
    $title = 'Gagal';
    $message = 'Data gagal dihapus!';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Hapus</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<script>
    Swal.fire({
        icon: '<?= $status ?>',
        title: '<?= $title ?>',
        text: '<?= $message ?>',
        showConfirmButton: false,
        timer: 2000
    }).then(() => {
        window.location.href = 'admin.php';
    });
</script>
</body>
</html>
