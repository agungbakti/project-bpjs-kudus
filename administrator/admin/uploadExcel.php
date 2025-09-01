<?php

session_start();

// Cek apakah user admin
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: ../index.php');
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" type="image/x-icon" href="../faskes/assets/favicon.ico">
    <title>Upload File Excel</title>
    <link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../fontawesome/css/all.min.css">
    <script src="../js/bootstrap.bundle.min.js"></script>
    <style>
        .fa-cloud-arrow-up:hover {
            color: blue;
            cursor: pointer;
        }

        /* Gaya untuk spinner loading di tengah halaman */
        #loading-spinner {
            display: none;
            /* Awalnya disembunyikan */
            position: fixed;
            /* Gunakan posisi fixed agar berada di tengah viewport */
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            /* Pusatkan spinner */
            z-index: 1000;
            /* Pastikan muncul di atas elemen lain jika ada */
            justify-content: center;
            align-items: center;
            /* Tambahkan ini jika Anda ingin konten di dalamnya juga terpusat */
        }

        .spinner-border {
            color: #0d6efd;
            /* Warna primary Bootstrap */
        }
    </style>
</head>

<body>
    <?php include '../layout/header.php'; ?>

    <form action="readExcel.php" method="post" enctype="multipart/form-data" id="uploadForm">
        <div class="container pt-5 mt-5">
            <div class="card mx-auto shadow-lg rounded-4 p-4 mt-4" style="max-width: 600px;">
                <div class="card-body text-center">
                    <h1 class="card-title mb-3 text-primary">Perhitungan Antrian</h1>
                    <p class="text-center">Selamat datang di halaman perhitungan antrian. <br>
                        Silakan input data Excel di bawah ini.</p>

                    <div class="pt-4">
                        <label for="excelFile" class="form-label">
                            <i class="fa-solid fa-cloud-arrow-up fa-3x"></i>
                        </label>
                        <input name="excelFile" id="excelFile" hidden class="form-control" type="file" required>
                        <div id="file-names" class="mt-2 text-muted small"></div>
                    </div>
                    <div id="loading-spinner">
                        <div class="spinner-border" style="width: 3rem; height: 3rem;" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>

                <button class="btn btn-primary" type="submit" id="prosesButton">
                    <i class="fa-solid fa-arrows-rotate"></i> Proses
                </button>
            </div>
        </div>
    </form>
    <div class="container-fluid mt-2">
        <div class="text-center">
            <p>&copy; <?= date('Y') ?> Sistem Antrian. All rights reserved.</p>
        </div>
    </div>

    <script>
        document.getElementById('excelFile').addEventListener('change', function() {
            const fileList = this.files;
            const display = document.getElementById('file-names');
            if (fileList.length > 0) {
                const names = Array.from(fileList).map(file => file.name).join('<br>');
                display.innerHTML = names;
            } else {
                display.innerHTML = '';
            }
        });

        document.getElementById('uploadForm').addEventListener('submit', function() {
            // Tampilkan spinner saat form dikirim
            document.getElementById('loading-spinner').style.display = 'flex';
            document.getElementById('prosesButton').disabled = true; // Menonaktifkan tombol

            // Simulate a 2-second delay using setTimeout
            setTimeout(function() {
                // Sembunyikan spinner setelah penundaan
                document.getElementById('loading-spinner').style.display = 'none';
                document.getElementById('prosesButton').disabled = false; // Mengaktifkan tombol kembali
                // Anda biasanya akan mengarahkan ke halaman hasil di sini, contoh:
                // window.location.href = "hasil_perhitungan.php";
            }, 10000); // 2000 milidetik = 2 detik

            // Mencegah form dari pengiriman yang sebenarnya (hanya untuk demonstrasi)
            // event.preventDefault(); // Biarkan baris ini dikomentari jika Anda ingin form mengirim ke readExcel.php
        });
    </script>
</body>

</html>
