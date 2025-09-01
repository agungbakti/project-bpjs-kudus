<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'faskes') {
    header('Location: ../../index.php');
    exit;
}
require "../database/function_faskes.php";

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', 'error_log.txt');

// $peserta = ambilnilai();

// if (isset($_POST['cari'])) {
//     $peserta = cari($_POST['keyword']);
// } elseif (isset($_POST['filter'])) {
//     $tanggal = $_POST['filter_tanggal'];
//     $peserta = filterTanggal($tanggal);
// } else {
//     $peserta = ambilnilai();
// }

// SETUP PAGINATION
$dataPerHalaman = 100; // Ganti sesuai kebutuhan
$halamanSekarang = isset($_GET['halaman']) ? (int) $_GET['halaman'] : 1;
$halamanSekarang = max(1, $halamanSekarang); // pastikan minimal halaman 1

// SETUP VARIABEL UNTUK MAINTAIN STATE
$keyword = isset($_POST['keyword']) ? $_POST['keyword'] : (isset($_GET['keyword']) ? $_GET['keyword'] : '');
$filter_tanggal = isset($_POST['filter_tanggal']) ? $_POST['filter_tanggal'] : (isset($_GET['filter_tanggal']) ? $_GET['filter_tanggal'] : '');

if (isset($_POST['cari']) || !empty($keyword)) {
    $peserta = cariDenganPagination($keyword, $halamanSekarang, $dataPerHalaman);
    $totalData = hitungTotalDataCari($keyword);
    $tipeData = 'cari';
} elseif (isset($_POST['filter']) || !empty($filter_tanggal)) {
    $peserta = filterTanggalDenganPagination($filter_tanggal, $halamanSekarang, $dataPerHalaman);
    $totalData = hitungTotalDataFilter($filter_tanggal);
    $tipeData = 'filter';
} else {
    $peserta = ambilnilaiDenganPagination($halamanSekarang, $dataPerHalaman);
    $totalData = hitungTotalData();
    $tipeData = 'all';
}

$totalHalaman = ceil($totalData / $dataPerHalaman);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faskes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <link rel="icon" type="image/x-icon" href="../assets/favicon.ico">
    <link rel="stylesheet" href="../style/style.css">
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
</head>

<body>


    <main>
        <div class="container mt-4">
            <div class="d-flex justify-content-between flex-wrap align-items-center mb-4">
                <div>
                    <h2 class="fw-bold text-primary">Formulir Mobile JKN</h2>
                    <p class="text-muted mb-0">Pembuatan Akun & Perubahan Data Nomor HP</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="input.php" class="btn btn-primary shadow-sm">➕ Tambah Peserta</a>
                    <a href="#" id="logout-btn" class="btn btn-danger shadow-sm">🔒 Logout</a>
                </div>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <form action="" method="post" class="d-flex">
                        <input type="text" name="keyword" id="keyword" placeholder="🔍 Cari peserta"
                            class="form-control me-2" autocomplete="off">
                    </form>
                </div>
                <div class="col-md-6">
                    <form action="" method="post" class="d-flex align-items-center">
                        <label for="filter_tanggal" class="form-label me-2 mb-0">Filter Tanggal:</label>
                        <input type="date" name="filter_tanggal" id="filter_tanggal" class="form-control me-2">
                        <button type="submit" name="filter" class="btn btn-outline-success">Tampilkan</button>
                    </form>
                </div>
            </div>
        </div>


        <div class="card mx-auto" style="max-width: 95%;">

            <div class="card-header bg-danger text-white">
                Data Peserta JKN
            </div>

            <div class="card-body">
                <div id="container" class="container-fluid mt-4">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover ">

                            <tr class="text-center table-dark">
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Keperluan</th>
                                <th>Lokasi</th>
                                <th>Rumah Sakit / FKTP</th>
                                <th>Kabupaten Rumah Sakit / FKTP</th>
                                <th>Nama Peserta</th>
                                <!-- <th>NIK</th>
                            <th>Nomor HP</th>
                            <th>Email</th>
                            <th>Rujuk</th> -->
                                <th>Status</th>
                                <th>Keterangan</th>
                            </tr>

                            <?php $i = 1; ?>
                            <?php foreach ($peserta as $index => $row): ?>
                                <tr>
                                    <td><?= $i ?></td>
                                    <td><?= $row['tanggal']; ?></td>
                                    <td><?= $row['keperluan']; ?></td>
                                    <td><?= $row['lokasi']; ?></td>
                                    <td><?= $row['fktp_dan_rumahsakit']; ?></td>
                                    <td><?= $row['kabupaten']; ?></td>
                                    <td><?= $row['nama_peserta']; ?></td>

                                    <td><?= $row['status']; ?></td>
                                    <td><?= $row['keterangan']; ?></td>

                                </tr>
                                <?php $i++; ?>
                            <?php endforeach; ?>

                        </table>
                    </div>
                </div>
            </div>
        </div>

        <?php if ($totalHalaman > 1): ?>
                <div class="card-footer">
                    <nav aria-label="Pagination">
                        <ul class="pagination justify-content-center mb-0">
                            <!-- Tombol Previous -->
                            <?php if ($halamanSekarang > 1): ?>
                                <li class="page-item">
                                    <a class="page-link"
                                        href="?halaman=<?= $halamanSekarang - 1 ?><?= $tipeData == 'cari' ? '&keyword=' . urlencode($keyword) : '' ?><?= $tipeData == 'filter' ? '&filter_tanggal=' . urlencode($filter_tanggal) : '' ?>"
                                        aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </li>
                            <?php else: ?>
                                <li class="page-item disabled">
                                    <span class="page-link">&laquo;</span>
                                </li>
                            <?php endif; ?>

                            <!-- Nomor Halaman -->
                            <?php
                            $startPage = max(1, $halamanSekarang - 2);
                            $endPage = min($totalHalaman, $halamanSekarang + 2);

                            if ($halamanSekarang <= 3) {
                                $endPage = min($totalHalaman, 5);
                            }

                            if ($halamanSekarang > $totalHalaman - 3) {
                                $startPage = max(1, $totalHalaman - 4);
                            }
                            ?>

                            <?php if ($startPage > 1): ?>
                                <li class="page-item">
                                    <a class="page-link"
                                        href="?halaman=1<?= $tipeData == 'cari' ? '&keyword=' . urlencode($keyword) : '' ?><?= $tipeData == 'filter' ? '&filter_tanggal=' . urlencode($filter_tanggal) : '' ?>">1</a>
                                </li>
                                <?php if ($startPage > 2): ?>
                                    <li class="page-item disabled">
                                        <span class="page-link">...</span>
                                    </li>
                                <?php endif; ?>
                            <?php endif; ?>

                            <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
                                <?php if ($i == $halamanSekarang): ?>
                                    <li class="page-item active">
                                        <span class="page-link"><?= $i ?></span>
                                    </li>
                                <?php else: ?>
                                    <li class="page-item">
                                        <a class="page-link"
                                            href="?halaman=<?= $i ?><?= $tipeData == 'cari' ? '&keyword=' . urlencode($keyword) : '' ?><?= $tipeData == 'filter' ? '&filter_tanggal=' . urlencode($filter_tanggal) : '' ?>"><?= $i ?></a>
                                    </li>
                                <?php endif; ?>
                            <?php endfor; ?>

                            <?php if ($endPage < $totalHalaman): ?>
                                <?php if ($endPage < $totalHalaman - 1): ?>
                                    <li class="page-item disabled">
                                        <span class="page-link">...</span>
                                    </li>
                                <?php endif; ?>
                                <li class="page-item">
                                    <a class="page-link"
                                        href="?halaman=<?= $totalHalaman ?><?= $tipeData == 'cari' ? '&keyword=' . urlencode($keyword) : '' ?><?= $tipeData == 'filter' ? '&filter_tanggal=' . urlencode($filter_tanggal) : '' ?>"><?= $totalHalaman ?></a>
                                </li>
                            <?php endif; ?>

                            <!-- Tombol Next -->
                            <?php if ($halamanSekarang < $totalHalaman): ?>
                                <li class="page-item">
                                    <a class="page-link"
                                        href="?halaman=<?= $halamanSekarang + 1 ?><?= $tipeData == 'cari' ? '&keyword=' . urlencode($keyword) : '' ?><?= $tipeData == 'filter' ? '&filter_tanggal=' . urlencode($filter_tanggal) : '' ?>"
                                        aria-label="Next">
                                        <span aria-hidden="true">&raquo;</span>
                                    </a>
                                </li>
                            <?php else: ?>
                                <li class="page-item disabled">
                                    <span class="page-link">&raquo;</span>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                </div>
            <?php endif; ?>


    </main>

    <script>
        document.getElementById('logout-btn').addEventListener('click', function (e) {
            e.preventDefault(); // mencegah langsung redirect

            Swal.fire({
                title: 'Keluar?',
                text: 'Apakah Anda yakin ingin keluar?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, keluar',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '../../includes/logout.php';
                }
            });
        });
    </script>

    <script src="../js/jquery-3.7.1.js"></script>
    <script src="../js/script_faskes.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO"
        crossorigin="anonymous"></script>
</body>

</html>