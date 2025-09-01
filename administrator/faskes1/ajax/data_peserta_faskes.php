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
                                <td><?=$row['tanggal']; ?></td>
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