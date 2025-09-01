<?php
usleep(500000);
require "../database/function_faskes.php";

$keyword = $_GET["keyword"];

$query = "
        SELECT f.*,
               k.keperluan,
               l.lokasi,
               r.fktp_dan_rumahsakit,
               r.kabupaten,
               s.status,
               kt.keterangan

        FROM tb_faskes f
        JOIN tb_keperluan k ON f.id_keperluan = k.id_keperluan
        JOIN tb_lokasi l ON f.id_lokasi = l.id_lokasi
        JOIN tb_rs r ON f.id_rs = r.id_rs
        LEFT JOIN tb_status s ON f.id_status = s.id_status
        LEFT JOIN tb_keterangan kt ON f.id_keterangan = kt.id_keterangan
        WHERE
        f.nama_peserta LIKE '%$keyword%' OR
        f.email LIKE '%$keyword%' OR
        r.kabupaten LIKE '%$keyword%'
    ";

// Assuming ambilnilai() and query() are correctly defined in function_faskes.php
$peserta = query($query);

// IMPORTANT: Include database connection for the select options
// Assuming $koneksi is your mysqli connection variable
// If $koneksi is not available here, you'll need to pass it or establish it.
// Example: require "../database/koneksi.php"; // Or wherever your connection is
global $koneksi; // Make sure $koneksi is accessible if it's global

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Peserta</title>
    </head>
<body>
    <table class="card-body table-responsive table table-bordered table-striped table-hover " style="max-height: 358px;">

        <tr class="text-center table-dark">
            <th>No</th>
            <th>Tanggal</th>
            <th>Keperluan</th>
            <th>Lokasi</th>
            <th>Rumah Sakit / FKTP</th>
            <th>Kabupaten Rumah Sakit / FKTP</th>
            <th>Nama Peserta</th>
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
            <tr class="data-row"> <td class="text-center"><?= $i ?></td>
                <td><?= htmlspecialchars($row['tanggal']); ?></td>
                <td><?= htmlspecialchars($row['keperluan']); ?></td>
                <td><?= htmlspecialchars($row['lokasi']); ?></td>
                <td><?= htmlspecialchars($row['fktp_dan_rumahsakit']); ?></td>
                <td><?= htmlspecialchars($row['kabupaten']); ?></td>
                <td><?= htmlspecialchars($row['nama_peserta']); ?></td>
                <td><?= htmlspecialchars($row['nik']); ?></td>
                <td><?= htmlspecialchars($row['nomorhp']); ?></td>
                <td><?= htmlspecialchars($row['email']); ?></td>
                <td><?= htmlspecialchars($row['rujuk']); ?></td>

                <td>
                    <select name="status" class="status-select" data-id="<?= htmlspecialchars($row['id_faskes']); ?>" data-original-value="<?= htmlspecialchars($row['id_status']); ?>">
                        <option value=""> Pilih Status </option>
                        <?php
                        $status_query = "SELECT id_status, status FROM tb_status";
                        $status_result = mysqli_query($koneksi, $status_query);
                        while ($status = mysqli_fetch_assoc($status_result)) {
                            $selected = ((string)$status['id_status'] === (string)$row['id_status']) ? 'selected' : '';
                            echo "<option value='" . htmlspecialchars($status['id_status']) . "' " . $selected . ">" . htmlspecialchars($status['status']) . "</option>";
                        }
                        ?>
                    </select>
                </td>

                <td>
                    <select name="keterangan" class="keterangan-select" data-id="<?= htmlspecialchars($row['id_faskes']); ?>" data-original-value="<?= htmlspecialchars($row['id_keterangan']); ?>">
                        <option value=""> Pilih Keterangan </option>
                        <?php
                        $keterangan_query = "SELECT id_keterangan, keterangan FROM tb_keterangan";
                        $keterangan_result = mysqli_query($koneksi, $keterangan_query);
                        while ($keterangan = mysqli_fetch_assoc($keterangan_result)) {
                            $selected = ((string)$keterangan['id_keterangan'] === (string)$row['id_keterangan']) ? 'selected' : '';
                            echo "<option value='" . htmlspecialchars($keterangan['id_keterangan']) . "' " . $selected . ">" . htmlspecialchars($keterangan['keterangan']) . "</option>";
                        }
                        ?>
                    </select>
                </td>

                <td>
                    <input type="text" name="nama" class="nama-input" value="<?= htmlspecialchars($row['nama']); ?>" autocomplete="off" data-id="<?= htmlspecialchars($row['id_faskes']); ?>" data-original-value="<?= htmlspecialchars($row['nama']); ?>">
                </td>

                <td>
                    <a href="edit.php?id=<?= htmlspecialchars($row['id_faskes']); ?>" class="btn btn-primary"
                        style="text-decoration: none;">Edit</a>
                    |
                    <a href="hapus.php?id=<?= htmlspecialchars($row['id_faskes']); ?>"
                        class="btn btn-danger btn-hapus" style="text-decoration: none;">Hapus</a>
                </td>
            </tr>
            <?php $i++; ?>
        <?php endforeach; ?>

    </table>

    </body>
</html>