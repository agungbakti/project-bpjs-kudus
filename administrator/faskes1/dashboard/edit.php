<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: ../../index.php');
    exit;
}

require "../database/function_faskes.php";

// ambil data url
$id = $_GET["id"];

// query data tb_faskes berdasarkan id
$faskes = query("SELECT * FROM tb_faskes1 WHERE id_faskes = $id")[0];


// cek submit sudah ditekan atau belum 
if (isset($_POST['submit'])) {

    // cek perubahan data
    if (edit($_POST) > 0) {
        echo "
        <script> alert('Data berhasil diubah')
        document.location.href = 'admin.php';
        </script>
        ";
    } else {
        echo "
        <script>alert('Mohon maaf anda gagal merubah data, silakan coba lagi')
        document.location.href = 'admin.php';
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
    <title>Edit</title>
    <link rel="stylesheet" href="../style/input.css">
    <!-- CSS Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="/assets/favicon.ico">




</head>

<body class="bg-light">
    <div class="container py-5">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0"><i class="bi bi-pencil-square me-2"></i>Edit Data</h4>
            </div>

            <div class="card-body">
                <form action="" method="post">
                    <input type="hidden" name="id_faskes" id="id_faskes" value="<?= $faskes['id_faskes']; ?>">


                    <!-- tanggal -->
                    <div class="mb-3">
                        <label for="tanggal" class="form-label">Tanggal</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-calendar-date"></i></span>
                            <input type="date" class="form-control" name="tanggal" id="tanggal"
                                value="<?= $faskes["tanggal"]; ?>" required>
                        </div>
                    </div>

                    <!-- keperluan -->
                    <div class="mb-3">
                        <label for="keperluan" class="form-label">Keperluan</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-briefcase"></i></span>
                            <select class="form-select" name="keperluan" id="keperluan" required>
                                <option disabled>Keperluan</option>
                                <?php
                                $sql = "SELECT * FROM tb_keperluan";
                                $result = $koneksi->query($sql);
                                while ($row = $result->fetch_assoc()) {
                                    $selected = ($row['id_keperluan'] == $faskes['id_keperluan']) ? 'selected' : '';
                                    echo "<option value='{$row['id_keperluan']}' $selected>{$row['keperluan']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <!-- lokasi -->
                    <div class="mb-3">
                        <label for="lokasi" class="form-label">Lokasi</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
                            <select class="form-select" name="lokasi" id="lokasi" required>
                                <option disabled>Lokasi</option>
                                <?php
                                $sql = "SELECT * FROM tb_lokasi";
                                $result = $koneksi->query($sql);
                                while ($row = $result->fetch_assoc()) {
                                    $selected = ($row['id_lokasi'] == $faskes['id_lokasi']) ? 'selected' : '';
                                    echo "<option value='{$row['id_lokasi']}' $selected > {$row['lokasi']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <!-- rs -->
                    <div class="mb-3">
                        <label for="rs" class="form-label">Rumah Sakit / FKTP</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-hospital"></i></span>
                            <select name="rs" id="rs" class="form-select" required>
                                <option disabled>Pilih Rumah Sakit</option>
                                <?php
                                $sql = "SELECT * FROM tb_rs";
                                $result = $koneksi->query($sql);
                                while ($row = $result->fetch_assoc()) {
                                    $selected = ($row['id_rs'] == $faskes['id_rs']) ? 'selected' : '';
                                    echo "<option value='{$row['id_rs']}' $selected > {$row['fktp_dan_rumahsakit']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <!-- nama peserta -->
                    <div class="mb-3">
                        <label class="form-label" for="nama_peserta">Nama Peserta :</label> <br>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-person"></i></span>
                            <input type="text" class="form-control" name="nama_peserta" id="nama_peserta" required
                                value="<?= $faskes["nama_peserta"] ?>">
                        </div>
                    </div>

                    <!-- nik -->
                    <div class="mb-3">
                        <label class="form-label" for="nik">NIK :</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-credit-card-2-front"></i></span>
                            <input class="form-control" type="text" name="nik" id="nik" required
                                value="<?= $faskes["nik"]; ?>">
                        </div>
                    </div>

                    <!-- no hp -->
                    <div class="mb-3">
                        <label class="form-label" for="nomorhp">Nomor Hp :</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                            <input class="form-control" type="text" name="nomorhp" id="nomorhp" required
                                value="<?= $faskes["nomorhp"]; ?>">
                        </div>
                    </div>

                    <!-- email -->
                    <div class="mb-3">
                        <label class="form-label" for="email">Email :</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                            <input class="form-control" type="email" name="email" id="email" required
                                value="<?= htmlspecialchars($faskes["email"]); ?>">
                        </div>
                    </div>


                    <!-- rujuk -->
                    <div class="mb-3">
                        <label class="form-label" for="rujuk">Rujuk</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-arrow-right-circle"></i></span>
                            <select class="form-select" name="rujuk" id="rujuk" required>
                                <option disabled <?= $faskes['rujuk'] == '' ? 'selected' : '' ?>>Pilih</option>
                                <option value="tidak" <?= $faskes['rujuk'] == 'tidak' ? 'selected' : '' ?>>Tidak</option>
                                <option value="rujuk" <?= $faskes['rujuk'] == 'rujuk' ? 'selected' : '' ?>>Rujuk</option>
                            </select>
                        </div>
                    </div>


                    <!-- submit -->
                    <div class="d-grid">
                        <button type="submit" name="submit" class="btn btn-success"><i
                                class="bi bi-save-me"></i>Submit</button>
                    </div>
                </form>
            </div>

        </div>
    </div>

    <!-- JS Select2 -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#rs').select2({
                placeholder: "Pilih Rumah Sakit",
                allowClear: true
            });
        });
    </script>


</body>

</html>