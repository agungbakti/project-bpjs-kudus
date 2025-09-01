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

if (isset($_POST['cari'])) {
    $peserta = cari($_POST['keyword']);
} elseif (isset($_POST['filter'])) {
    $tanggal = $_POST['filter_tanggal'];
    $peserta = filterTanggal($tanggal);
} else {
    $peserta = ambilnilai();
}

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
</head>

<body>


    <main>
        <h1 style="font-size: 20px;" class="text-center fs-5"> Formulir Pembuatan Akun Mobile JKN _ Perubahan Data
                No HP
                2 </h1>
        <div class="container mt-4" style="display: flex;
    flex-direction: row;
    flex-wrap: nowrap;
    justify-content: space-around;
    align-items: center;">
            
            <div class="row justify-content-center mt-4">
                <div class="col-md-6 col-sm-10" style="width: 100%;">
                    <form action="" method="post" class="text-center">

                        <input type="text" name="keyword" id="keyword" size="30" autofocus placeholder="CARI PESERTA"
                            autocomplete="off" style="text-align: center;" class="form-control mb-2">

                        <button type="submit" name="cari" id="tombol_cari"
                            class="btn btn-primary w-100 mb-3">Cari</button>

                    </form>

                    <div class=" d-flex justify-content-beetwen mt-3">
                        

                        <a href="input.php" style="text-decoration: none; border: 2px solid black; border-radius: 5px;"
                            class="btn btn-primary  text-white w-50 me-2">Tambah Peserta</a>

                        <a href="../../includes/logout.php"
                            style="text-decoration: none; border: 2px solid black; border-radius: 5px;"
                            class="btn btn-danger text-white w-50 me-2"
                            onclick="return confirm('Apakah anda yakin ingin keluar ?')">Logout</a>
                    </div>
                </div>
            </div>
            <div>
                <form action="" method="post" class="mt-3 mb-3">
                            <label for="filter_tanggal" class="form-label">Filter berdasarkan Tanggal:</label>
                            <input type="date" name="filter_tanggal" id="filter_tanggal" class="form-control"
                                style="max-width: 250px;">
                            <button type="submit" name="filter" class="btn btn-success mt-2">Tampilkan</button>
                        </form>
            </div>
            
        </div><br>

        <div class="card mx-auto" style="max-width: 95%;">

            <div class="card-header bg-danger text-white">
                Data Peserta JKN
            </div>

            <div class="card-body">
                <div id="container" class="container-fluid mt-4">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover ">

                            <tr class="text-center">
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


    </main>




    <script src="../js/jquery-3.7.1.js"></script>
    <script src="../js/script_faskes.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO"
        crossorigin="anonymous"></script>
</body>

</html>