<?php
usleep(500000);
require "../database/function_faskes.php";

// SETUP PAGINATION
$dataPerHalaman = 30;
$halamanSekarang = isset($_GET['halaman']) ? (int) $_GET['halaman'] : 1;
$halamanSekarang = max(1, $halamanSekarang);

// SETUP VARIABEL UNTUK MAINTAIN STATE
$keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';

// Gunakan pagination dengan atau tanpa keyword
if (!empty($keyword)) {
    $peserta = cariDenganPagination($keyword, $halamanSekarang, $dataPerHalaman);
    $totalData = hitungTotalDataCari($keyword);
    $tipeData = 'cari';
} else {
    // Jika tidak ada keyword, pastikan kita load data yang benar sesuai halaman
    $peserta = ambilnilaiDenganPagination($halamanSekarang, $dataPerHalaman);
    $totalData = hitungTotalData();
    $tipeData = 'all';
}

$totalHalaman = ceil($totalData / $dataPerHalaman);

// Jika halaman yang diminta melebihi total halaman, redirect ke halaman terakhir
if ($halamanSekarang > $totalHalaman && $totalHalaman > 0) {
    $halamanSekarang = $totalHalaman;
    if (!empty($keyword)) {
        $peserta = cariDenganPagination($keyword, $halamanSekarang, $dataPerHalaman);
    } else {
        $peserta = ambilnilaiDenganPagination($halamanSekarang, $dataPerHalaman);
    }
}

// Pastikan $peserta adalah array yang valid
if (!is_array($peserta)) {
    $peserta = [];
}
?>

<div class="table-container">
    <table class="table table-bordered table-striped table-hover">
        <thead>
            <tr class="text-center">
                <th>No</th>
                <th>Tanggal</th>
                <th>Keperluan</th>
                <th>Lokasi</th>
                <th>Rumah Sakit / FKTP</th>
                <th>Kabupaten Rumah Sakit / FKTP</th>
                <th>Nama Peserta</th>
                <th>Status</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($peserta) > 0): ?>
                <?php $i = (($halamanSekarang - 1) * $dataPerHalaman) + 1; ?>
                <?php foreach ($peserta as $index => $row): ?>
                    <tr>
                        <td><?= htmlspecialchars($i) ?></td>
                        <td><?= htmlspecialchars($row['tanggal'] ?? '') ?></td>
                        <td><?= htmlspecialchars($row['keperluan'] ?? '') ?></td>
                        <td><?= htmlspecialchars($row['lokasi'] ?? '') ?></td>
                        <td><?= htmlspecialchars($row['fktp_dan_rumahsakit'] ?? '') ?></td>
                        <td><?= htmlspecialchars($row['kabupaten'] ?? '') ?></td>
                        <td><?= htmlspecialchars($row['nama_peserta'] ?? '') ?></td>
                        <td><?= htmlspecialchars($row['status'] ?? '') ?></td>
                        <td><?= htmlspecialchars($row['keterangan'] ?? '') ?></td>
                    </tr>
                    <?php $i++; ?>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="9" class="text-center py-4">
                        <?php if (!empty($keyword)): ?>
                            <i class="fas fa-search"></i> 
                            Tidak ada data yang ditemukan untuk pencarian "<strong><?= htmlspecialchars($keyword) ?></strong>"
                        <?php else: ?>
                            <i class="fas fa-inbox"></i> 
                            Tidak ada data yang tersedia
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>