<?php
session_start();
require "../database/function_faskes.php";

// cek apakah user faskes apa tidak
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: ../../index.php');
    exit;
}

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

// Function to check if data is updated
function isDataUpdated($row) {
    // Cek apakah status, keterangan, dan nama sudah diisi
    return !empty($row['id_status']) && !empty($row['id_keterangan']) && !empty($row['nama']);
}

// Function to check if data is partially updated
function isDataPartiallyUpdated($row) {
    $filledFields = 0;
    if (!empty($row['id_status'])) $filledFields++;
    if (!empty($row['id_keterangan'])) $filledFields++;
    if (!empty($row['nama'])) $filledFields++;
    
    return $filledFields > 0 && $filledFields < 3;
}
// HANDLE RESET FILTER
if (isset($_POST['reset']) || isset($_GET['reset'])) {
    $keyword = '';
    $filter_tanggal = '';
    $halamanSekarang = 1;
    
    // Redirect untuk membersihkan URL parameters
    $current_url = strtok($_SERVER["REQUEST_URI"], '?');
    header("Location: $current_url");
    exit;
}
// Function untuk generate URL pagination dengan maintain filter
function generatePaginationURL($page, $keyword = '', $filter_tanggal = '') {
    $params = [];
    
    if (!empty($keyword)) {
        $params['keyword'] = $keyword;
    }
    
    if (!empty($filter_tanggal)) {
        $params['filter_tanggal'] = $filter_tanggal;
    }
    
    if ($page > 1) {
        $params['halaman'] = $page;
    }
    
    if (empty($params)) {
        return '?';
    }
    
    return '?' . http_build_query($params);
}

// Function untuk cek apakah ada filter aktif
function hasActiveFilters($keyword, $filter_tanggal) {
    return !empty($keyword) || !empty($filter_tanggal);
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
    <link rel="stylesheet" href="../style/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        /* CSS untuk membedakan status update */
        .row-updated {
            background-color: #d4edda !important; /* Hijau muda untuk data lengkap */
            border-left: 4px solid #28a745;
        }
        
        .row-partial {
            background-color: #fff3cd !important; /* Kuning muda untuk data sebagian */
            border-left: 4px solid #ffc107;
        }
        
        .row-empty {
            background-color: #f8d7da !important; /* Merah muda untuk data kosong */
            border-left: 4px solid #dc3545;
        }
        
        .status-indicator {
            font-size: 0.8em;
            font-weight: bold;
            padding: 2px 6px;
            border-radius: 3px;
            margin-left: 5px;
        }
        
        .status-complete {
            background-color: #28a745;
            color: white;
        }
        
        .status-partial {
            background-color: #ffc107;
            color: #212529;
        }
        
        .status-empty {
            background-color: #dc3545;
            color: white;
        }
        
        .legend {
            margin-bottom: 20px;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 8px;
            border: 1px solid #dee2e6;
        }
        
        .legend-item {
            display: inline-block;
            margin-right: 20px;
            margin-bottom: 5px;
        }
        
        .legend-color {
            display: inline-block;
            width: 20px;
            height: 15px;
            margin-right: 8px;
            border-radius: 3px;
            vertical-align: middle;
        }
        
        /* Highlight untuk field yang kosong */
        .field-empty {
            border: 2px solid #dc3545 !important;
            background-color: #f8d7da !important;
        }
        
        /* Highlight untuk field yang sudah diisi */
        .field-filled {
            border: 2px solid #28a745 !important;
            background-color: #d4edda !important;
        }
    </style>
</head>

<body>
    <?php include '../header/header_admin.php' ?>
    <main class="mt-5 pt-3">
        <div class="container mt-4">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4 judul">
                <div>
                    <h2 class="fw-bold text-primary mb-1">
                        <i class="bi bi-clipboard2-pulse me-2"></i>Formulir Mobile JKN
                    </h2>
                    <p class="text-muted mb-0">Pembuatan Akun & Perubahan Data Nomor HP</p>
                </div>
                <div>
                    <a href="tambah_data.php" class="btn btn-outline-primary fw-semibold rounded-pill me-2">
                        <i class="bi bi-person-plus-fill me-1"></i>Tambah Peserta
                    </a>
                    <!--<a href="#" id="logout-btn" class="btn btn-outline-danger fw-semibold rounded-pill">-->
                    <!--    <i class="bi bi-box-arrow-right me-1"></i>Logout-->
                    <!--</a>-->
                </div>
            </div>

            <!-- Legend/Keterangan Status -->
            <div class="legend">
                <h6 class="mb-3"><i class="bi bi-info-circle me-2"></i>Status Update Data:</h6>
                <div class="legend-item">
                    <span class="legend-color" style="background-color: #d4edda; border: 1px solid #28a745;"></span>
                    <span>Data Lengkap</span>
                    <span class="status-indicator status-complete">COMPLETE</span>
                </div>
                <div class="legend-item">
                    <span class="legend-color" style="background-color: #fff3cd; border: 1px solid #ffc107;"></span>
                    <span>Data Sebagian</span>
                    <span class="status-indicator status-partial">PARTIAL</span>
                </div>
                <div class="legend-item">
                    <span class="legend-color" style="background-color: #f8d7da; border: 1px solid #dc3545;"></span>
                    <span>Data Kosong</span>
                    <span class="status-indicator status-empty">EMPTY</span>
                </div>
            </div>

            <!-- Card Filter & Download -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <div class="row g-4">
                        <!-- Filter Tanggal -->
                        <div class="col-md-4">
                            <form method="post">
                                <label class="form-label fw-semibold text-secondary">📅 Filter Tanggal</label>
                                <div class="input-group">
                                    <input type="date" name="filter_tanggal" class="form-control"
                                        value="<?= htmlspecialchars($filter_tanggal) ?>" required>
                                    <button type="submit" name="filter" class="btn btn-success">
                                        <i class="bi bi-funnel-fill"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                        
                        <div class="col-md-3">
                            <form method="post">
                                <label class="form-label fw-semibold text-secondary">🔄 Reset</label>
                                <button type="submit" name="reset" class="btn btn-outline-danger w-100" 
                                        <?= !hasActiveFilters($keyword, $filter_tanggal) ? 'disabled' : '' ?>>
                                    <i class="fas fa-times"></i> Reset Filter
                                </button>
                            </form>
                        </div>

                        <!-- Pencarian -->
                        <div class="col-md-4">
                            <form method="post">
                                <label class="form-label fw-semibold text-secondary">🔍 Cari Peserta</label>
                                <input type="text" name="keyword" id="keyword" value="<?= htmlspecialchars($keyword) ?>"
                                    class="form-control rounded-pill text-center shadow-sm"
                                    placeholder="Nama / Kabupaten" autocomplete="off">
                            </form>
                        </div>

                        <!-- Download Excel -->
                        <div class="col-md-4">
                            <form action="download_excel.php" method="get">
                                <label class="form-label fw-semibold text-secondary">📥 Download Excel</label>
                                <div class="input-group">
                                    <input type="date" name="start" class="form-control" required>
                                    <input type="date" name="end" class="form-control" required>
                                    <button type="submit" class="btn btn-outline-success">
                                        <i class="bi bi-download"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        

        <div class="card mx-auto mt-3" style="max-width: 95%;">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <span>Data Peserta JKN</span>
                <!--<small class="text-light">-->
                <!--    <?= min($dataPerHalaman, $totalData - (($halamanSekarang - 1) * $dataPerHalaman)) ?>-->
                <!--    dari <?= $totalData ?> data-->
                <!--</small>-->
            </div>

            <div class="card-body">
                <div id="container" class="container-fluid mt-1">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <tr class="text-center table-dark">
                                <th>No</th>
                                <th>Status Update</th>
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

                            <?php
                            $nomorUrut = ($halamanSekarang - 1) * $dataPerHalaman + 1;
                            ?>
                            <?php foreach ($peserta as $index => $row): ?>
                                <?php
                                $isUpdated = isDataUpdated($row);
                                $isPartial = isDataPartiallyUpdated($row);
                                
                                // Tentukan class untuk baris
                                $rowClass = '';
                                $statusText = '';
                                $statusClass = '';
                                
                                if ($isUpdated) {
                                    $rowClass = 'row-updated';
                                    $statusText = 'COMPLETE';
                                    $statusClass = 'status-complete';
                                } elseif ($isPartial) {
                                    $rowClass = 'row-partial';
                                    $statusText = 'PARTIAL';
                                    $statusClass = 'status-partial';
                                } else {
                                    $rowClass = 'row-empty';
                                    $statusText = 'EMPTY';
                                    $statusClass = 'status-empty';
                                }
                                ?>
                                <tr class="<?= $rowClass ?>">
                                    <td class="text-center"><?= $nomorUrut ?></td>
                                    
                                    <!-- Kolom Status Update -->
                                    <td class="text-center">
                                        <span class="status-indicator <?= $statusClass ?>">
                                            <?= $statusText ?>
                                        </span>
                                        <br>
                                        <small class="text-muted">
                                            <?php
                                            $filledCount = 0;
                                            if (!empty($row['id_status'])) $filledCount++;
                                            if (!empty($row['id_keterangan'])) $filledCount++;
                                            if (!empty($row['nama'])) $filledCount++;
                                            echo $filledCount . "/3";
                                            ?>
                                        </small>
                                    </td>
                                    
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

                                    <!-- Kolom Status -->
                                    <td>
                                        <select name="status" class="form-select form-select-sm status-select <?= empty($row['id_status']) ? 'field-empty' : 'field-filled' ?>"
                                            data-id="<?= $row['id_faskes']; ?>">
                                            <option value="">Pilih Status</option>
                                            <?php
                                            $status_query = "SELECT * FROM tb_status";
                                            $status_result = mysqli_query($koneksi, $status_query);
                                            while ($status = mysqli_fetch_assoc($status_result)) {
                                                $selected = ($status['id_status'] == $row['id_status']) ? 'selected' : '';
                                                echo "<option value='" . $status['id_status'] . "' $selected>" . $status['status'] . "</option>";
                                            }
                                            ?>
                                        </select>
                                    </td>

                                    <!-- Kolom Keterangan -->
                                    <td>
                                        <select name="keterangan" class="form-select form-select-sm keterangan-select <?= empty($row['id_keterangan']) ? 'field-empty' : 'field-filled' ?>"
                                            data-id="<?= $row['id_faskes']; ?>">
                                            <option value="">Pilih Keterangan</option>
                                            <?php
                                            $keterangan_query = "SELECT * FROM tb_keterangan";
                                            $keterangan_result = mysqli_query($koneksi, $keterangan_query);
                                            while ($keterangan = mysqli_fetch_assoc($keterangan_result)) {
                                                $selected = ($keterangan['id_keterangan'] == $row['id_keterangan']) ? 'selected' : '';
                                                echo "<option value='" . $keterangan['id_keterangan'] . "' $selected>" . $keterangan['keterangan'] . "</option>";
                                            }
                                            ?>
                                        </select>
                                    </td>

                                    <!-- Kolom Nama -->
                                    <td>
                                        <input type="text" name="nama" class="form-control form-control-sm nama-input <?= empty($row['nama']) ? 'field-empty' : 'field-filled' ?>"
                                            data-id="<?= $row['id_faskes']; ?>"
                                            value="<?= htmlspecialchars($row['nama'] ?? ''); ?>"
                                            placeholder="......................." autocomplete="off">
                                    </td>

                                    <td>
                                        <a href="edit.php?id=<?= $row['id_faskes']; ?>" class="btn btn-primary btn-sm"
                                            style="text-decoration: none;">Edit</a>
                                        |
                                        <a href="hapus.php?id=<?= $row['id_faskes']; ?>" class="btn btn-danger btn-sm btn-hapus"
                                            style="text-decoration: none;">Hapus</a>
                                    </td>
                                </tr>
                                <?php $nomorUrut++; ?>
                            <?php endforeach; ?>
                        </table>
                    </div>
                </div>
            </div>

            <!-- PAGINATION -->
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
        </div>
    </main>

    <script src="../js/jquery-3.7.1.js"></script>
    <script src="../js/script_admin.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO"
        crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <script>
        // Script untuk real-time update visual
        function updateRowStatus(rowElement) {
            const statusSelect = rowElement.querySelector('.status-select');
            const keteranganSelect = rowElement.querySelector('.keterangan-select');
            const namaInput = rowElement.querySelector('.nama-input');
            const statusIndicator = rowElement.querySelector('.status-indicator');
            const statusCount = rowElement.querySelector('small');
            
            let filledCount = 0;
            
            // Cek dan update class untuk setiap field
            if (statusSelect.value) {
                filledCount++;
                statusSelect.classList.remove('field-empty');
                statusSelect.classList.add('field-filled');
            } else {
                statusSelect.classList.remove('field-filled');
                statusSelect.classList.add('field-empty');
            }
            
            if (keteranganSelect.value) {
                filledCount++;
                keteranganSelect.classList.remove('field-empty');
                keteranganSelect.classList.add('field-filled');
            } else {
                keteranganSelect.classList.remove('field-filled');
                keteranganSelect.classList.add('field-empty');
            }
            
            if (namaInput.value.trim()) {
                filledCount++;
                namaInput.classList.remove('field-empty');
                namaInput.classList.add('field-filled');
            } else {
                namaInput.classList.remove('field-filled');
                namaInput.classList.add('field-empty');
            }
            
            // Update status baris
            rowElement.classList.remove('row-updated', 'row-partial', 'row-empty');
            statusIndicator.classList.remove('status-complete', 'status-partial', 'status-empty');
            
            if (filledCount === 3) {
                rowElement.classList.add('row-updated');
                statusIndicator.classList.add('status-complete');
                statusIndicator.textContent = 'COMPLETE';
            } else if (filledCount > 0) {
                rowElement.classList.add('row-partial');
                statusIndicator.classList.add('status-partial');
                statusIndicator.textContent = 'PARTIAL';
            } else {
                rowElement.classList.add('row-empty');
                statusIndicator.classList.add('status-empty');
                statusIndicator.textContent = 'EMPTY';
            }
            
            // Update counter
            statusCount.textContent = filledCount + '/3';
        }
        
        // Event listeners untuk perubahan real-time
        document.addEventListener('DOMContentLoaded', function() {
            const statusSelects = document.querySelectorAll('.status-select');
            const keteranganSelects = document.querySelectorAll('.keterangan-select');
            const namaInputs = document.querySelectorAll('.nama-input');
            
            // Add event listeners
            [...statusSelects, ...keteranganSelects].forEach(select => {
                select.addEventListener('change', function() {
                    const row = this.closest('tr');
                    updateRowStatus(row);
                });
            });
            
            namaInputs.forEach(input => {
                input.addEventListener('input', function() {
                    const row = this.closest('tr');
                    updateRowStatus(row);
                });
            });
        });
        
        // Existing scripts
        function loadData() {
            $.ajax({
                url: '../ajax/data_peserta.php',
                type: 'GET',
                success: function (data) {
                    $('#dataPeserta').html(data);
                }
            });
        }

        loadData();
        setInterval(loadData);
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const hapusButtons = document.querySelectorAll('.btn-hapus');

            hapusButtons.forEach(button => {
                button.addEventListener('click', function (e) {
                    e.preventDefault();
                    const href = this.getAttribute('href');

                    Swal.fire({
                        title: 'Hapus Data?',
                        text: "Apakah Anda yakin ingin menghapus data ini?",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Ya, hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = href;
                        }
                    });
                });
            });
        });
    </script>
    
    // <script>
    //     document.getElementById('logout-btn').addEventListener('click', function (e) {
    //         e.preventDefault();

    //         Swal.fire({
    //             title: 'Keluar?',
    //             text: 'Apakah Anda yakin ingin keluar?',
    //             icon: 'warning',
    //             showCancelButton: true,
    //             confirmButtonColor: '#d33',
    //             cancelButtonColor: '#6c757d',
    //             confirmButtonText: 'Ya, keluar',
    //             cancelButtonText: 'Batal'
    //         }).then((result) => {
    //             if (result.isConfirmed) {
    //                 window.location.href = '../../includes/logout.php';
    //             }
    //         });
    //     });
    // </script>

</body>
</html>