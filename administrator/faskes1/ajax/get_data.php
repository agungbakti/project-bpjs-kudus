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

$peserta = ambilnilai();
$peserta = query($query);



?>

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
        <tr>
            <td class="text-center"><?= $i ?></td>
            <td><?= $row['tanggal']; ?></td>
            <td><?= $row['keperluan']; ?></td>
            <td><?= $row['lokasi']; ?></td>
            <td><?= $row['fktp_dan_rumahsakit']; ?></td>
            <td><?= $row['kabupaten']; ?></td>
            <td><?= $row['nama_peserta']; ?></td>
            <td><?= $row['nik']; ?></td>
            <td><?= $row['nomorhp']; ?></td>
            <td><?= $row['email']; ?></td>
            <td><?= $row['rujuk']; ?></td>
            <!-- Kolom Status dengan Select -->
            <td>
                <form action="update_status.php" method="post">
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
                    <button type="submit">Update</button>
                </form>
            </td>

            <!-- Kolom Keterangan dengan Select -->
            <td>
                <form action="update_keterangan.php" method="post">

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
                    <button type="submit">Update</button>
                </form>
            </td>

            <!-- update nama -->
            <td>
                <form action="nama.php" method="post">
                    <input type="text" name="nama" id="nama" value="<?= $row['nama']; ?>" autocomplete="off">
                    <!-- Tampilkan nama saat ini -->
                    <input type="hidden" name="id_faskes" value="<?= $row['id_faskes']; ?>">
                    <!-- ID Faskes untuk Update -->
                    <button type="submit" name="update_nama">Update</button>
                </form>
            </td>


            <td>
                <a href="edit.php?id=<?= $row['id_faskes']; ?>" class="btn btn-primary"
                    style="text-decoration: none;">Edit</a>

                |

                <a href="hapus.php?id=<?= $row['id_faskes']; ?>"
                    onclick="return confirm('Apakah anda yakin ingin menghapus data ?')" class="btn btn-danger"
                    style="text-decoration: none;">Hapus</a>
            </td>
        </tr>
        <?php $i++; ?>
    <?php endforeach; ?>

</table>