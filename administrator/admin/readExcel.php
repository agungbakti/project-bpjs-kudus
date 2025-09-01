<?php
require 'excel/vendor/autoload.php'; // Memuat library PHPSpreadsheet

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date;

session_start();

// Cek admin
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: ../index.php'); // Redirect jika bukan admin
    exit;
}

// Cek apakah file diupload
if (!isset($_FILES['excelFile']) || $_FILES['excelFile']['error'] !== UPLOAD_ERR_OK) {
    die("File tidak valid atau gagal diupload."); // Tampilkan pesan error jika file tidak diupload dengan benar
}

// Simpan file sementara
$tmpFilePath = $_FILES['excelFile']['tmp_name'];
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Hasil Perhitungan Antrian</title>
        <link rel="icon" type="image/x-icon" href="../faskes/assets/favicon.ico">
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <style>
        .scrollable-table {
            max-height: 400px;
            overflow-y: auto;
        }

        .table th,
        .table td {
            white-space: nowrap;
        }
    </style>
</head>

<body>
    <?php include '../layout/header.php'; ?>
    <div class="container mt-5 pt-4">
        <h1 class="mb-2 text-primary">HASIL REKAPITULASI ANTRIAN</h1>
        <?php
        try {
            $spreadsheet = IOFactory::load($tmpFilePath); // Load file Excel
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray(); // Konversi data Excel ke array

            // Inisialisasi array untuk rekap data
            $statusRekap = [];
            $statusByJenisKantor = [];
            $dataByKantor = [];
            $rekapPerJenisPerKantor = [];
            $rekapitulasi = [];

            // Add these new variables for the 30-minute breakdown
            $queueTimeStats = [
                'less_than_30_min' => 0,
                'more_than_30_min' => 0,
                'total_hadir_queue_times' => 0 // Only count queue times for 'hadir' status
            ];

            // Header row
            $headerRow = null;

            foreach ($rows as $i => $row) {
                if ($i === 0) {
                    $headerRow = $row; // Simpan baris header
                    $headerRow[] = 'Waktu Tunggu'; // Tambahkan kolom 'Waktu Tunggu'
                    $headerRow[] = 'Status Kehadiran'; // Tambahkan kolom 'Status Kehadiran'
                    $indexPemberianInformasi = array_search('Fungsi', $headerRow); // Cari index kolom 'Fungsi' (previously Pemberian Informasi)
                    $indexKabupaten = array_search('Kantor', $headerRow); // Cari index kolom 'Kantor' (previously Kabupaten)

                    if ($indexPemberianInformasi === false || $indexKabupaten === false) {
                        die("Kolom 'Fungsi' atau 'Kantor' tidak ditemukan di file Excel. Pastikan nama kolom sesuai."); // Error jika kolom tidak ditemukan
                    }
                    continue;
                }

                $jenisLayanan = trim($row[$indexPemberianInformasi]); // Ambil jenis layanan
                $kabupaten = trim(strtolower($row[$indexKabupaten])); // Ambil kabupaten dan ubah ke lowercase

                if (!isset($rekapitulasi[$jenisLayanan])) {
                    $rekapitulasi[$jenisLayanan] = [
                        'grobogan' => 0,
                        'jepara' => 0,
                        'kudus' => 0,
                        'total' => 0,
                    ];
                }

                if (isset($rekapitulasi[$jenisLayanan][$kabupaten])) {
                    $rekapitulasi[$jenisLayanan][$kabupaten]++; // Hitung jumlah layanan per kabupaten
                    $rekapitulasi[$jenisLayanan]['total']++; // Hitung total layanan
                }

                $kantor = strtolower(trim($row[$indexKabupaten])); // Ambil nama kantor dan ubah ke lowercase
                if (!isset($dataByKantor[$kantor])) {
                    $dataByKantor[$kantor] = [
                        'data' => [],
                        'totalDetik' => 0,
                        'jumlahHadir' => 0
                    ];
                }

                $kodeAntrian = strtoupper(trim($row[1])); // Ambil kode antrian dan ubah ke uppercase
                $jenisAntrian = substr($kodeAntrian, 0, 1); // Ambil jenis antrian (misal: A, B, C)

                $jamAmbil     = $row[4]; // Waktu ambil antrian
                $jamPanggil = $row[5]; // Waktu panggil antrian
                $kolom6     = $row[6];
                $kolom7     = $row[7];

                // Logika status otomatis berdasarkan kondisi kolom
                if (!empty($jamAmbil) && empty($jamPanggil) && empty($kolom6) && empty($kolom7)) {
                    $pesertaHadir = 'layanan so'; // Status: SO (Sedang Dilayani / Sudah Order)
                } elseif (!empty($jamAmbil) && !empty($jamPanggil) && empty($kolom6) && empty($kolom7)) {
                    $pesertaHadir = 'tidak hadir'; // Status: Tidak Hadir
                } elseif (!empty($jamAmbil) && !empty($jamPanggil) && !empty($kolom6) && !empty($kolom7)) {
                    $pesertaHadir = 'hadir'; // Status: Hadir
                } else {
                    $pesertaHadir = 'unknown'; // Status: Tidak diketahui
                }

                // Inisialisasi array jika belum ada
                if (!isset($statusByJenisKantor[$kantor])) {
                    $statusByJenisKantor[$kantor] = [];
                }
                if (!isset($statusByJenisKantor[$kantor][$jenisAntrian])) {
                    $statusByJenisKantor[$kantor][$jenisAntrian] = [
                        'hadir' => 0,
                        'tidak hadir' => 0,
                        'layanan so' => 0,
                        'unknown' => 0
                    ];
                }

                // Tambahkan jumlah berdasarkan status
                if (isset($statusByJenisKantor[$kantor][$jenisAntrian][$pesertaHadir])) {
                    $statusByJenisKantor[$kantor][$jenisAntrian][$pesertaHadir]++;
                } else {
                    $statusByJenisKantor[$kantor][$jenisAntrian]['unknown']++;
                }

                // Hitung waktu tunggu
                $waktuTunggu = '00:00:00';
                $durasiDetik = 0;
                $interval = null;

                try {
                    if (!empty($jamAmbil) && !empty($jamPanggil)) {
                        if (is_numeric($jamAmbil) && is_numeric($jamPanggil)) {
                            $dtAmbil     = Date::excelToDateTimeObject($jamAmbil); // Konversi dari format Excel ke DateTime
                            $dtPanggil = Date::excelToDateTimeObject($jamPanggil);
                        } else {
                            $dtAmbil     = new DateTime($jamAmbil);
                            $dtPanggil = new DateTime($jamPanggil);
                        }

                        $interval = $dtPanggil->diff($dtAmbil); // Hitung selisih waktu
                        $waktuTunggu = $interval->format('%H:%I:%S'); // Format selisih waktu
                        $durasiDetik = ($interval->h * 3600) + ($interval->i * 60) + $interval->s; // Hitung selisih dalam detik

                        if ($pesertaHadir === 'hadir') {
                            $dataByKantor[$kantor]['totalDetik'] += $durasiDetik; // Akumulasi total detik waktu tunggu
                            $dataByKantor[$kantor]['jumlahHadir']++; // Hitung jumlah peserta hadir
                            $queueTimeStats['total_hadir_queue_times']++;

                            // Categorize queue times for the new table
                            if ($durasiDetik <= (30 * 60)) { // 30 minutes in seconds
                                $queueTimeStats['less_than_30_min']++;
                            } else {
                                $queueTimeStats['more_than_30_min']++;
                            }

                            // Tambahkan data untuk rekap per jenis per kantor
                            if (!isset($rekapPerJenisPerKantor[$jenisAntrian])) {
                                $rekapPerJenisPerKantor[$jenisAntrian] = [];
                            }
                            if (!isset($rekapPerJenisPerKantor[$jenisAntrian][$kantor])) {
                                $rekapPerJenisPerKantor[$jenisAntrian][$kantor] = ['total' => 0, 'count' => 0];
                            }

                            $rekapPerJenisPerKantor[$jenisAntrian][$kantor]['total'] += $durasiDetik; // Akumulasi detik
                            $rekapPerJenisPerKantor[$jenisAntrian][$kantor]['count']++; // Hitung jumlah antrian
                        }
                    }
                } catch (Exception $e) {
                    $waktuTunggu = 'Format tidak valid'; // Set waktu tunggu jika format waktu tidak valid
                }

                $row[] = $waktuTunggu; // Tambahkan waktu tunggu ke baris data
                $row[] = $pesertaHadir; // Tambahkan status kehadiran ke baris data
                $dataByKantor[$kantor]['data'][] = $row; // Tambahkan data ke array $dataByKantor
            }

            // Calculate percentages for the new table
            $percentageLessThan30Min = ($queueTimeStats['total_hadir_queue_times'] > 0) ?
                round(($queueTimeStats['less_than_30_min'] / $queueTimeStats['total_hadir_queue_times']) * 100, 2) : 0;
            $percentageMoreThan30Min = ($queueTimeStats['total_hadir_queue_times'] > 0) ?
                round(($queueTimeStats['more_than_30_min'] / $queueTimeStats['total_hadir_queue_times']) * 100, 2) : 0;

            // ---
            // Tampilkan data per kantor
            echo "<h4 class='mt-5 text-success text-uppercase'>DATA ANTRIAN PER KANTOR</h4>";
            foreach ($dataByKantor as $kantor => $info) {
                echo "<h5 class='mt-4 text-primary'>Kantor: " . htmlspecialchars(ucfirst($kantor)) . "</h5>";
                echo '<div class="table-responsive scrollable-table">';
                echo "<table class='table table-bordered table-striped'>";
                echo "<thead class='table-dark'><tr>";
                foreach ($headerRow as $cell) {
                    echo "<th>" . htmlspecialchars($cell) . "</th>"; // Tampilkan header tabel
                }
                echo "</tr></thead><tbody>";

                // Menampilkan tabel perkabupaten
                foreach ($info['data'] as $baris) {
                    echo "<tr>";
                    foreach ($baris as $cell) {
                        echo "<td>" . htmlspecialchars($cell) . "</td>"; // Tampilkan data per baris
                    }
                    echo "</tr>";
                }

                if ($info['jumlahHadir'] > 0) {
                    $avg = intval($info['totalDetik'] / $info['jumlahHadir']); // Hitung rata-rata waktu tunggu dalam detik
                    $jam = str_pad(floor($avg / 3600), 2, '0', STR_PAD_LEFT); // Konversi detik ke format jam:menit:detik
                    $menit = str_pad(floor(($avg % 3600) / 60), 2, '0', STR_PAD_LEFT);
                    $detik = str_pad($avg % 60, 2, '0', STR_PAD_LEFT);
                    $rata = "$jam:$menit:$detik"; // Format rata-rata waktu tunggu
                } else {
                    $rata = "00:00:00"; // Jika tidak ada yang hadir, set rata-rata ke 00:00:00
                }

                echo "<tr class='fw-bold table-info'>";
                echo "<td colspan='7'>Rata-rata Waktu Tunggu (Hadir) - " . htmlspecialchars(ucfirst($kantor)) . "</td>";
                echo "<td>$rata</td>";
                echo "<td colspan='" . (count($headerRow) - 8) . "'></td>";
                echo "</tr>";

                echo "</tbody></table>";
                echo '</div>';
            }

            // ---
            echo "<h4 class='mt-5 text-info'>RATA-RATA WAKTU TUNGGU ANTRIAN PER JENIS DAN KANTOR</h4>";
            echo "<div class='table-responsive'>";
            echo "<table class='table table-bordered text-center'>";
            echo "<thead class='table-primary'><tr>
                <th>Average of Waktu Tunggu</th>
                <th>Grobogan</th>
                <th>Jepara</th>
                <th>Kudus</th>
                <th>Grand Total</th>
            </tr></thead><tbody>";

            $grandTotalDetik = 0;
            $grandTotalCount = 0;

            foreach ($rekapPerJenisPerKantor as $jenis => $kantorData) {
                $totalRowDetik = 0;
                $totalRowCount = 0;

                echo "<tr><td><strong>" . htmlspecialchars($jenis) . "</strong></td>";

                foreach (['grobogan', 'jepara', 'kudus'] as $k) {
                    if (isset($kantorData[$k]) && $kantorData[$k]['count'] > 0) {
                        $avgDetik = intval($kantorData[$k]['total'] / $kantorData[$k]['count']);
                        $jam = str_pad(floor($avgDetik / 3600), 2, '0', STR_PAD_LEFT);
                        $menit = str_pad(floor(($avgDetik % 3600) / 60), 2, '0', STR_PAD_LEFT);
                        $detik = str_pad($avgDetik % 60, 2, '0', STR_PAD_LEFT);
                        echo "<td>$jam:$menit:$detik</td>";

                        $totalRowDetik += $kantorData[$k]['total'];
                        $totalRowCount += $kantorData[$k]['count'];
                    } else {
                        echo "<td>00:00:00</td>";
                    }
                }

                // Hitung Grand Total untuk baris ini
                if ($totalRowCount > 0) {
                    $avgGrand = intval($totalRowDetik / $totalRowCount);
                    $jam = str_pad(floor($avgGrand / 3600), 2, '0', STR_PAD_LEFT);
                    $menit = str_pad(floor(($avgGrand % 3600) / 60), 2, '0', STR_PAD_LEFT);
                    $detik = str_pad($avgGrand % 60, 2, '0', STR_PAD_LEFT);
                    echo "<td><strong>$jam:$menit:$detik</strong></td>";
                } else {
                    echo "<td>00:00:00</td>";
                }

                echo "</tr>";

                // Tambahkan ke Grand Total semua jenis
                $grandTotalDetik += $totalRowDetik;
                $grandTotalCount += $totalRowCount;
            }

            // Baris Grand Total
            echo "<tr class='table-info'><td><strong>Grand Total</strong></td>";

            foreach (['grobogan', 'jepara', 'kudus'] as $k) {
                $totalK = 0;
                $countK = 0;
                foreach ($rekapPerJenisPerKantor as $jenis => $data) {
                    if (isset($data[$k])) {
                        $totalK += $data[$k]['total'];
                        $countK += $data[$k]['count'];
                    }
                }

                if ($countK > 0) {
                    $avgK = intval($totalK / $countK);
                    $jam = str_pad(floor($avgK / 3600), 2, '0', STR_PAD_LEFT);
                    $menit = str_pad(floor(($avgK % 3600) / 60), 2, '0', STR_PAD_LEFT);
                    $detik = str_pad($avgK % 60, 2, '0', STR_PAD_LEFT);
                    echo "<td><strong>$jam:$menit:$detik</strong></td>";
                } else {
                    echo "<td>00:00:00</td>";
                }
            }

            // Grand total all
            if ($grandTotalCount > 0) {
                $avgAll = intval($grandTotalDetik / $grandTotalCount);
                $jam = str_pad(floor($avgAll / 3600), 2, '0', STR_PAD_LEFT);
                $menit = str_pad(floor(($avgAll % 3600) / 60), 2, '0', STR_PAD_LEFT);
                $detik = str_pad($avgAll % 60, 2, '0', STR_PAD_LEFT);
                echo "<td><strong>$jam:$menit:$detik</strong></td>";
            } else {
                echo "<td>00:00:00</td>";
            }

            echo "</tr></tbody></table></div>";

            // ---
            // New table for waiting time percentages
            echo "<h4 class='mt-5 text-dark'>REKAPITULASI WAKTU TUNGGU BERDASARKAN DURASI</h4>";
            echo "<div class='table-responsive'>";
            echo "<table class='table table-bordered table-hover align-middle text-center'>";
            echo "<thead class='table-warning'>
                <tr>
                    <th>Kategori Waktu Tunggu</th>
                    <th>Jumlah Antrian (Hadir)</th>
                    <th>Persentase</th>
                </tr>
            </thead><tbody>";

            echo "<tr>
                <td>Kurang dari sama dengan 30 Menit</td>
                <td>{$queueTimeStats['less_than_30_min']}</td>
                <td>{$percentageLessThan30Min}%</td>
            </tr>";
            echo "<tr>
                <td>lebih dari 30 Menit</td>
                <td>{$queueTimeStats['more_than_30_min']}</td>
                <td>{$percentageMoreThan30Min}%</td>
            </tr>";
            echo "<tr class='table-info fw-bold'>
                <td>Total Antrian Hadir</td>
                <td>{$queueTimeStats['total_hadir_queue_times']}</td>
                <td>100%</td>
            </tr>";

            echo "</tbody></table></div>";

            // ---
            // Tampilkan tabel status kehadiran
            echo "<h4 class='mt-5 text-secondary'>REKAP STATUS KEHADIRAN PER KANTOR & JENIS ANTRIAN</h4>";
            echo "<div class='table-responsive'>";
            echo "<table class='table table-bordered table-hover align-middle text-center'>";
            echo "<thead class='table-dark'>
                <tr>
                    <th>Kantor</th>
                    <th>Jenis Antrian</th>
                    <th>Hadir</th>
                    <th>Tidak Hadir</th>
                    <th>Layanan SO</th>
                    <th>Jumlah Antrian</th>
                    <th>Total Per Kantor</th>
                </tr>
            </thead><tbody>";

            $totalHadir = $totalTidakHadir = $totalSO = $totalSemua = 0;

            // Hitung total per kantor
            $jumlahPerKantor = [];
            foreach ($statusByJenisKantor as $kantor => $jenisData) {
                $jumlahPerKantor[$kantor] = 0;
                foreach ($jenisData as $statusCounts) {
                    $jumlahPerKantor[$kantor] += $statusCounts['hadir'] + $statusCounts['tidak hadir'] + $statusCounts['layanan so'];
                }
            }

            // Cetak tabel
            foreach ($statusByJenisKantor as $kantor => $jenisData) {
                $firstRow = true;
                foreach ($jenisData as $jenis => $statusCounts) {
                    $jumlah = $statusCounts['hadir'] + $statusCounts['tidak hadir'] + $statusCounts['layanan so'];

                    echo "<tr>";
                    $rowClass = '';
                    if ($kantor === "kudus") {
                        $rowClass = 'table-info';
                    } else if ($kantor === "jepara") {
                        $rowClass = 'table-success';
                    } else {
                        $rowClass = 'table-danger';
                    }

                    echo "<td class='{$rowClass}'>" . htmlspecialchars(ucfirst($kantor)) . "</td>";
                    echo "<td class='{$rowClass}'>" . htmlspecialchars($jenis) . "</td>";
                    echo "<td class='{$rowClass}'>{$statusCounts['hadir']}</td>";
                    echo "<td class='{$rowClass}'>{$statusCounts['tidak hadir']}</td>";
                    echo "<td class='{$rowClass}'>{$statusCounts['layanan so']}</td>";
                    echo "<td class='{$rowClass}'>$jumlah</td>";

                    if ($firstRow) {
                        echo "<td rowspan='" . count($jenisData) . "' class='fw-bold table-warning'>{$jumlahPerKantor[$kantor]}</td>";
                        $firstRow = false;
                    }

                    echo "</tr>";

                    $totalHadir += $statusCounts['hadir'];
                    $totalTidakHadir += $statusCounts['tidak hadir'];
                    $totalSO += $statusCounts['layanan so'];
                    $totalSemua += $jumlah;
                }
            }

            // Baris total keseluruhan
            echo "<tr class=' fw-bold'>
                <td colspan='2'>TOTAL</td>
                <td>$totalHadir</td>
                <td>$totalTidakHadir</td>
                <td>$totalSO</td>
                <td colspan='2'>$totalSemua</td>
            </tr>";

            echo "</tbody></table>";
            echo "</div>";

            // ---
            // Tampilkan tabel rekapitulasi
            echo "<h4 class='mt-5 text-info'>REKAPITULASI JENIS LAYANAN PER KABUPATEN</h4>";
            echo "<div class='table-responsive'>";
            echo "<table class='table table-bordered table-hover align-middle'>";
            echo "<thead class='table-primary text-center'>";
            echo "<tr>";
            echo "<th>Jenis Layanan</th>";
            echo "<th>Grobogan</th>";
            echo "<th>Jepara</th>";
            echo "<th>Kudus</th>";
            echo "<th>Grand Total</th>
            </tr>
            </thead>
            <tbody>";

            foreach ($rekapitulasi as $layanan => $data) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($layanan) . "</td>";
                echo "<td class='text-center'>" . (isset($data['grobogan']) ? $data['grobogan'] : 0) . "</td>";
                echo "<td class='text-center'>" . (isset($data['jepara']) ? $data['jepara'] : 0) . "</td>";
                echo "<td class='text-center'>" . (isset($data['kudus']) ? $data['kudus'] : 0) . "</td>";
                echo "<td class='text-center'>" . $data['total'] . "</td>";
                echo "</tr>";
            }

            echo "<tr class='table-info fw-bold'>";
            echo "<td>Grand Total</td>";
            $grandTotalGrobogan = array_sum(array_column($rekapitulasi, 'grobogan'));
            $grandTotalJepara = array_sum(array_column($rekapitulasi, 'jepara'));
            $grandTotalKudus = array_sum(array_column($rekapitulasi, 'kudus'));
            $overallGrandTotal = array_sum(array_column($rekapitulasi, 'total'));
            echo "<td>" . $grandTotalGrobogan . "</td>";
            echo "<td>" . $grandTotalJepara . "</td>";
            echo "<td>" . $grandTotalKudus . "</td>";
            echo "<td>" . $overallGrandTotal . "</td>";
            echo "</tr>";

            echo "</tbody>";
            echo "</table>";
            echo "</div>";
        } catch (Exception $e) {
            echo "<div class='alert alert-danger'>Terjadi kesalahan: " . htmlspecialchars($e->getMessage()) . "</div>"; // Tampilkan pesan error jika terjadi kesalahan
            echo "<a href='uploadExcel.php' class='btn btn-primary'>Kembali Ke-Upload</a>"; // Tampilkan pesan error jika terjadi kesalahan
        }
        ?>
    </div>
    <?php include '../layout/footer.php'; ?>
</body>

</html>