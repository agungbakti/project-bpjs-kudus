<?php
session_start();
require "../database/function_faskes.php";

// cek apakah user faskes apa tidak
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: ../../index.php');
    exit;
}

$peserta = ambilnilai();

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
    <link rel="icon" type="image/x-icon" href="../assets/favicon.ico">
    <title>Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <link rel="icon" type="image/x-icon" href="/assets/favicon.ico">
    <link rel="stylesheet" href="../style/style.css">
</head>

<body>
    <?php include '../header/header_admin.php' ?>
    <main class="mt-5 pt-3">
        <div class="mt-3">
            <h1 style="font-size: 20px;" class="text-center pb-2"> Formulir Pembuatan Akun Mobile JKN Perubahan Data No
                HP
                2 </h1>
            <div class="cari mt-3" style="align-items: center;
  height: 130px;">
                <form action="" method="post">

                    <input type="text" name="keyword" id="keyword" size="30" autofocus placeholder="CARI PESERTA"
                        autocomplete="off" style="text-align: center;" class="form-control">
                    <!-- <button type="submit" name="cari" id="tombol_cari" class="btn btn-primary mt-2">Cari</button> -->

                    <!-- <img src="../assets/loader.gif"
                        style="width: 185px; height:100px; position: absolute; top: 16px; z-index: -1; left: 365px; display: none;"
                        class="loader"> -->
                </form>

                <form action="" method="post" class="mt-3 mb-3">
                    <label for="filter_tanggal" class="form-label">Filter berdasarkan Tanggal:</label>
                    <input type="date" name="filter_tanggal" id="filter_tanggal" class="form-control"
                        style="max-width: 250px;">
                    <button type="submit" name="filter" class="btn btn-success mt-2">Tampilkan</button>
                </form>


                <a id="btn-tambah" href="tambah_data.php" style="text-decoration: none; border: 2px solid black; border-radius: 5px;     display: block;
    width: 180px;
    height: 40px;" class="btn btn-warning text-black">Tambah
                    Data peserta </a>
            </div>
        </div><br>
        <div class="card mx-auto" style="max-width: 95%;">

            <div class="card-header bg-primary text-white">
                Data Peserta JKN
            </div>

            <div class="card-body">
                <div id="container" class="container-fluid mt-4">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">

                            <tr class="text-center table-dark">
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Lokasi</th>
                                <th>Rumah Sakit / FKTP</th>
                                <th>Kabupaten Rumah Sakit / FKTP</th>
                                <th>Nama Peserta</th>
                                <th>Keperluan</th>
                                <th>NIK</th>
                                <th>Nomor HP</th>
                                <th>Email</th>
                                <th>Rujuk</th>
                                <th>Status</th>
                                <th>Keterangan</th>
                                <th>Nama</th>
                                <th>Aksi</th>
                            </tr>

                            <?php $i = 1; ?>
                            <?php foreach ($peserta as $index => $row): ?>
                                <tr>
                                    <td class="text-center"><?= $i ?></td>
                                    <td><?= $row['tanggal']; ?></td>
                                    <td><?= $row['lokasi']; ?></td>
                                    <td><?= $row['fktp_dan_rumahsakit']; ?></td>
                                    <td><?= $row['kabupaten']; ?></td>
                                    <td><?= $row['nama_peserta']; ?></td>
                                    <td><?= $row['keperluan']; ?></td>
                                    <td><?= $row['nik']; ?></td>
                                    <td><?= $row['nomorhp']; ?></td>
                                    <td><?= $row['email']; ?></td>
                                    <td><?= $row['rujuk']; ?></td>

                                    <!-- Kolom Status dengan Select -->
                                    <td>
                                        <form class="form-status" data-id="<?= $row['id_faskes']; ?>">
                                            <select name="status">
                                                <option value=""> Pilih Status </option>
                                                <?php
                                                $status_query = "SELECT * FROM tb_status";
                                                $status_result = mysqli_query($koneksi, $status_query);
                                                while ($status = mysqli_fetch_assoc($status_result)) {
                                                    $selected = ($status['id_status'] == $row['id_status']) ? 'selected' : '';
                                                    echo "<option value='" . $status['id_status'] . "' $selected>" . $status['status'] . "</option>";
                                                }
                                                ?>
                                            </select>
                                            <input type="hidden" name="id_faskes" value="<?= $row['id_faskes']; ?>">
                                            <button type="button" class="btn-update-status">Update</button>
                                        </form>
                                    </td>

                                    <!-- Kolom Keterangan dengan Select -->
                                    <td>
                                        <form class="form-keterangan" data-id="<?= $row['id_faskes']; ?>">

                                            <select name="keterangan">
                                                <option value=""> Pilih Keterangan </option>
                                                <?php
                                                $keterangan_query = "SELECT * FROM tb_keterangan";
                                                $keterangan_result = mysqli_query($koneksi, $keterangan_query);
                                                while ($keterangan = mysqli_fetch_assoc($keterangan_result)) {
                                                    $selected = ($keterangan['id_keterangan'] == $row['id_keterangan']) ? 'selected' : '';
                                                    echo "<option value='" . $keterangan['id_keterangan'] . "' $selected>" . $keterangan['keterangan'] . "</option>";
                                                }
                                                ?>
                                            </select>
                                            <input type="hidden" name="id_faskes" value="<?= $row['id_faskes']; ?>">
                                            <button type="button" class="btn-update-keterangan">Update</button>
                                        </form>
                                    </td>

                                    <!-- update nama -->
                                    <td>
                                        <form class="form-nama" data-id="<?= $row['id_faskes']; ?>">
                                            <input type="text" name="nama" id="nama" value="<?= $row['nama']; ?>"
                                                autocomplete="off"> <!-- Tampilkan nama saat ini -->
                                            <input type="hidden" name="id_faskes" value="<?= $row['id_faskes']; ?>">
                                            <!-- ID Faskes untuk Update -->
                                            <button type="button" name="update_nama" class="btn-update-name">Update</button>
                                        </form>
                                    </td>


                                    <td>
                                        <a href="edit.php?id=<?= $row['id_faskes']; ?>" class="btn btn-primary"
                                            style="text-decoration: none;">Edit</a>

                                        |

                                        <a href="hapus.php?id=<?= $row['id_faskes']; ?>"
                                            onclick="return confirm('Apakah anda yakin ingin menghapus data ?')"
                                            class="btn btn-danger" style="text-decoration: none;">Hapus</a>
                                    </td>
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
    <script src="../js/script_admin.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO"
        crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function loadData() {
            $.ajax({
                url: 'data_peserta.php', // ganti ke file kamu yang sudah ada
                type: 'GET',
                success: function (data) {
                    $('#dataPeserta').html(data);
                }
            });
        }

        // Panggil pertama kali
        loadData();

        // Lalu jalankan terus tiap 5 detik
        setInterval(loadData, 5000);
    </script>

</body>

</html>