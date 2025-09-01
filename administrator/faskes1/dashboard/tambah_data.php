<?php
$todayDate = date('Y-m-d');
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: ../../index.php');
    exit;
}
require "../database/function_faskes.php";

// cek submit sudah ditekan atau belum 
if (isset($_POST['submit'])) {

    $result = input($_POST);
    if ($result > 0) {
        echo " 
        <script> alert('Anda Berhasil Input')
        document.location.href = 'admin.php';
        </script>
        ";
    } elseif ($result == -1) {
        echo "
        <script>alert('Nomor sudah terdaftar, silakan gunakan nomor yang lain')
        document.location.href = 'tambah_data.php';
        </script>
        ";
    } elseif ($result == -2) {
        echo "
        <script>alert('Email sudah terdaftar, silakan gunakan email yang lain')
        document.location.href = 'tambah_data.php';
        </script>
        ";
    }

}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Input</title>
    <link rel="stylesheet" href="../style/input.css">
    <!-- CSS Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <link rel="icon" type="image/x-icon" href="/assets/favicon.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">




</head>

<body class="bg-light">
    <div class="container py-5">
        <div class="card shadow">
            <div class="card-header bg-warning text-white">
                <h4 class="text-center fs-5 mb-0"><i class="bi bi-pencil-square me-2"></i>Formulir Pembuatan Akun Mobile
                    JKN / Perubahan Data Nomor HP</h4>
            </div>

            <div class="card-body">
                <form action="" method="post" style=" max-height: 465px; overflow-x: auto; overflow-y: auto;">

                    <!-- tanggal -->
                    <div class="mb-3">
                        <label for="tanggal" class="form-label">Tanggal :</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-calendar-date"></i></span>
                            <input type="date" name="tanggal" id="tanggal" class="form-control" min="<?= $todayDate ?>"
                                required>
                        </div>
                    </div>

                    <!-- keperluan -->
                    <div class="mb-3">
                        <label for="keperluan" class="form-label ">Keperluan:</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-briefcase"></i></span>
                            <select name="keperluan" id="keperluan" class="form-select" required>
                                <option disabled selected>Keperluan</option>
                                <?php
                                $sql = "SELECT * FROM tb_keperluan";
                                $result = $koneksi->query($sql);
                                while ($row = $result->fetch_assoc()) {
                                    echo "<option value='{$row['id_keperluan']}'>{$row['keperluan']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="rs" class="form-label">FKTP / Rumah Sakit:</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
                            <select name="lokasi" id="lokasi" class="form-select" required>
                                <option disabled selected>Lokasi</option>
                                <?php
                                $sql = "SELECT * FROM tb_lokasi";
                                $result = $koneksi->query($sql);
                                while ($row = $result->fetch_assoc()) {
                                    echo "<option value='{$row['id_lokasi']}' > {$row['lokasi']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="fktp_dan_rumahsakit" class="form-label">FKTP / Rumah Sakit :</label>
                        <div class="input-group nowrap">
                            <span class="input-group-text"><i class="bi bi-hospital"></i></span>
                            <select name="rs" id="rs" class="select2" required>
                                <option disabled selected>Pilih FKTP / Rumah Sakit</option>
                                <?php
                                $sql = "SELECT * FROM tb_rs";
                                $result = $koneksi->query($sql);
                                while ($row = $result->fetch_assoc()) {
                                    echo "<option value='{$row['id_rs']}' > {$row['fktp_dan_rumahsakit']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="nama_peserta" class="form-label">Nama Peserta :</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-person"></i></span>
                            <input type="text" name="nama_peserta" id="nama_peserta" required autocomplete="off"
                                placeholder="masukan nama" class="form-control">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="nik" class="form-label">NIK :</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-credit-card-2-front"></i></span>
                            <input type="text" name="nik" id="nik" required autocomplete="off" placeholder="masukan nik"
                                class="form-control">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="nomorhp" class="form-label">No Hp :</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                            <input type="tel" pattern="08[0-9]{8,11}" name="nomorhp" id="nomorhp" required
                                autocomplete="off" placeholder="masukan nomor hp" class="form-control">
                            <small id="hpHelp" class="text-danger d-none">Nomor HP terlalu panjang. Maksimal 13
                                digit.</small>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email :</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                            <input type="email" name="email" id="email" required autocomplete="off"
                                placeholder="masukan Email" class=" form-control">
                        </div>

                        <div class="mb-3">
                            <label for="rujuk" class="form-label">Rujuk :</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-arrow-right-circle"></i></span>
                                <select name="rujuk" id="rujuk" class="form-select" required>
                                    <option disabled selected>Pilih</option>
                                    <option value="tidak">Tidak</option>
                                    <option value="rujuk">Rujuk</option>
                                </select>
                            </div>
                        </div>


                        <div class="mb-3">
                            <button name="submit" type="submit" class="btn btn-success w-100">Submit</button>
                        </div>
                </form>
            </div>
        </div>

    </div>
    <script>
        document.getElementById('nomorhp').addEventListener('input', function () {
            const hp = this.value;
            const helpText = document.getElementById('hpHelp');

            if (!hp.startsWith('08')) {
                helpText.textContent = 'Nomor HP harus diawali dengan 08';
                helpText.classList.remove('d-none');
            } else if (hp.length > 13) {
                helpText.textContent = 'Nomor HP terlalu panjang. Maksimal 13 digit. Pastikan tanpa spasi';
                helpText.classList.remove('d-none');
            } else {
                helpText.classList.add('d-none');
            }
        });
    </script>


    <!-- JS Select2 -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function () {
            $('#rs').select2({
                placeholder: "Pilih Rumah Sakit",
                allowClear: true,
            });
        });
    </script>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO"
        crossorigin="anonymous"></script>

</body>

</html>