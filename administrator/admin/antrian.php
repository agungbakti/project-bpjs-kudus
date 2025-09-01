<?php
session_start();
require_once '../includes/koneksi.php';
date_default_timezone_set("Asia/Jakarta");

// Cek apakah user admin
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: ../index.php');
    exit;
}
// Di bagian atas file (setelah session_start())
$filterDate = isset($_GET['filterDate']) ? $_GET['filterDate'] : date('Y-m-d');

// Modifikasi query antrian untuk include filter tanggal
$antrian = $conn->query("SELECT * FROM antrian WHERE DATE(date) = '$filterDate' ORDER BY time_taken ASC")->fetch_all(MYSQLI_ASSOC);

// Modifikasi query statistik untuk include filter tanggal
$jumlah_a = $conn->query("SELECT COUNT(*) as total FROM antrian WHERE jenis='A' AND DATE(date) = '$filterDate'")->fetch_assoc()['total'];
$jumlah_b = $conn->query("SELECT COUNT(*) as total FROM antrian WHERE jenis='B' AND DATE(date) = '$filterDate'")->fetch_assoc()['total'];

$dipanggil_a = $conn->query("SELECT nomor FROM antrian WHERE jenis='A' AND status='called' AND DATE(date) = '$filterDate' ORDER BY time_called DESC LIMIT 1")->fetch_assoc()['nomor'] ?? '-';
$dipanggil_b = $conn->query("SELECT nomor FROM antrian WHERE jenis='B' AND status='called' AND DATE(date) = '$filterDate' ORDER BY time_called DESC LIMIT 1")->fetch_assoc()['nomor'] ?? '-';

$sisa_a = $conn->query("SELECT COUNT(*) as total FROM antrian WHERE jenis='A' AND status='waiting' AND DATE(date) = '$filterDate'")->fetch_assoc()['total'];
$sisa_b = $conn->query("SELECT COUNT(*) as total FROM antrian WHERE jenis='B' AND status='waiting' AND DATE(date) = '$filterDate'")->fetch_assoc()['total'];
$total_wait = 0;
$jumlah_called = 0;

foreach ($antrian as $row) {
    if ($row['time_called']) {
        $wait = strtotime($row['time_called']) - strtotime($row['time_taken']);
        $total_wait += $wait;
        $jumlah_called++;
    }
}

$rata_rata_wait = $jumlah_called > 0 ? round($total_wait / $jumlah_called) : 0;
?>

<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Admin</title>
    <link rel="icon" type="image/x-icon" href="../faskes/assets/favicon.ico">
    <link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../fontawesome/css/all.min.css">
    <script src="../js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
            padding: 8px 12px;
            border: 1px solid #ccc;
            text-align: center;
        }

        .actions form {
            display: inline-block;
        }

        .no-wrap {
            white-space: nowrap;
        }

        @media screen and (max-width: 500px) {
            .judul {
                font-size: 12px;
            }
        }
    </style>
</head>


<body class="bg-light">
    <?php include '../layout/header.php'; ?>

    <div class="container-fluid pt-5 mt-4">
        <div class="row">
            <div class="col-12 col-md-12 col-lg-12 mx-auto">
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h2 class="card-title text-primary">Statistik Antrian</h2>
                        <!-- CARD ANTRIAN A-->
                        <div class="mb-4">
                            <h5>Antrian A</h5>
                            <div class="row row-cols-3 row-cols-sm-4 row-cols-md-4 row-cols-lg-6 g-3">
                                <div class="col">
                                    <div class="card text-bg-primary h-80">
                                        <!-- <div class="card-header">Header</div> -->
                                        <div class="card-body">
                                            <h5 class="card-title judul">Total</h5>
                                            <p class="card-text"><?= $jumlah_a ?></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="card text-bg-success h-80">
                                        <!-- <div class="card-header">Header</div> -->
                                        <div class="card-body">
                                            <h5 class="card-title judul">Dipanggil</h5>
                                            <p class="card-text"><?= $dipanggil_a ?></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="card text-bg-warning h-80">
                                        <!-- <div class="card-header">Header</div> -->
                                        <div class="card-body">
                                            <h5 class="card-title judul">Sisa</h5>
                                            <p class="card-text"><?= $sisa_a ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- END CARD -->
                        <!-- CARD ANTRIAN B -->
                        <h5>Antrian B</h5>
                        <div class="row row-cols-3 row-cols-sm-4 row-cols-md-4 row-cols-lg-6 g-3">
                            <div class="col">
                                <div class="card text-bg-primary h-80">
                                    <!-- <div class="card-header">Header</div> -->
                                    <div class="card-body">
                                        <h5 class="card-title judul">Total</h5>
                                        <p class="card-text"><?= $jumlah_b ?></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="card text-bg-success h-80">
                                    <!-- <div class="card-header">Header</div> -->
                                    <div class="card-body">
                                        <h5 class="card-title judul">Dipanggil</h5>
                                        <p class="card-text"><?= $dipanggil_b ?></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="card text-bg-warning h-80">
                                    <!-- <div class="card-header">Header</div> -->
                                    <div class="card-body">
                                        <h5 class="card-title judul">Sisa</h5>
                                        <p class="card-text"><?= $sisa_b ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- END CARD -->
                        <div class="mt-3">
                            <h5 class="text-muted">⏱ Rata-rata Waktu Tunggu:</h5>
                            <p class="fs-5"><?= gmdate("H:i:s", $rata_rata_wait) ?></p>
                        </div>

                        <form method="POST" action="reset.php" onsubmit="return confirm('Yakin hapus semua antrian?');" class="mt-3">
                            <!-- Tombol trigger modal -->
                            <button type="button" class="btn btn-danger mt-3" data-bs-toggle="modal" data-bs-target="#confirmResetModal">
                                🗑 Hapus Semua Antrian
                            </button>

                        <!--<h1><?php echo "Waktu sekarang: " . date("Y-m-d H:i:s"); ?></h1>-->
                        
                        </form>
                        <!-- Modal Konfirmasi -->
                        <div class="modal fade" id="confirmResetModal" tabindex="-1" aria-labelledby="confirmResetLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header bg-danger text-white">
                                        <h5 class="modal-title" id="confirmResetLabel">Konfirmasi Hapus</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
                                    </div>
                                    <div class="modal-body">
                                        Apakah Anda yakin ingin menghapus <strong>semua antrian</strong>?
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                        <form method="POST" action="reset.php">
                                            <button type="submit" class="btn btn-danger">Ya, Hapus Semua</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <form method="GET" class="row g-3 align-items-center" id="filterForm">
                                <div class="col-auto">
                                    <label for="filterDate" class="col-form-label">Filter Tanggal:</label>
                                </div>
                                <div class="col-auto">
                                    <input type="date" class="form-control" id="filterDate" name="filterDate"
                                        value="<?= isset($_GET['filterDate']) ? $_GET['filterDate'] : date('Y-m-d') ?>">
                                </div>
                                <div class="col-auto">
                                    <button type="submit" class="btn btn-primary">Filter</button>
                                </div>
                                <div class="col-auto">
                                    <button type="button" class="btn btn-secondary" onclick="resetFilter()">Reset</button>
                                </div>
                            </form>
                        </div>
                        <h4 class="card-title text-secondary mb-4">Daftar Antrian</h4>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover text-center align-middle">
                                <thead class="table-dark">
                                    <tr>
                                        <th>No</th>
                                        <th>Nomor</th>
                                        <th>Jenis</th>
                                        <th>Tanggal</th>
                                        <th class="no-wrap" >Peserta</th>
                                        <th>No.Wa</th>
                                        <th>Status</th>
                                        <th>Booking</th>
                                        <th>Panggil</th>
                                        <th>Selesai</th>
                                        <th class="no-wrap">Waktu Tunggu</th>
                                        <th>Aksi</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include '../layout/footer.php'; ?>

    <script>
        function formatWaitTime(totalSeconds) {
            if (!totalSeconds || totalSeconds <= 0) return '-';

            const hours = Math.floor(totalSeconds / 3600);
            const minutes = Math.floor((totalSeconds % 3600) / 60);
            const seconds = totalSeconds % 60;

            // Format HH:MM:SS jika lebih dari 1 jam, MM:SS jika kurang
            if (hours > 0) {
                return `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')} menit`;
            } else {
                return `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')} menit`;
            }
        }

        // Fungsi untuk reset filter
        function resetFilter() {
            document.getElementById('filterDate').value = '';
            document.getElementById('filterForm').submit();
        }

        // Fungsi untuk menghitung selisih waktu dalam format HH:MM:SS
        function calculateTimeDifference(startTime, endTime) {
            if (!startTime || !endTime) return 0;

            try {
                // Parse waktu dalam format HH:MM:SS
                const parseTime = (timeStr) => {
                    const [hours, minutes, seconds] = timeStr.split(':').map(Number);
                    return (hours * 3600) + (minutes * 60) + seconds;
                };

                const startSeconds = parseTime(startTime);
                const endSeconds = parseTime(endTime);

                // Hitung selisih (handle kasus melewati tengah malam)
                return endSeconds >= startSeconds ?
                    endSeconds - startSeconds :
                    (86400 - startSeconds) + endSeconds;
            } catch (e) {
                console.error('Error parsing time:', e);
                return 0;
            }
        }

        // Fungsi untuk menangani aksi panggil
        async function handlePanggil(event) {
            event.preventDefault();

            const form = event.target;
            const formData = new FormData(form);

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    body: formData
                });

                const data = await response.json();

                if (data.success) {
                    // Refresh data antrian
                    await fetchAntrianData();

                    // Tampilkan notifikasi sukses
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: data.message,
                        timer: 2000,
                        showConfirmButton: false
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: data.message
                    });
                }
            } catch (error) {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Terjadi kesalahan saat memproses permintaan'
                });
            }
        }

        // Fungsi untuk menangani aksi selesai
        async function handleSelesai(event) {
            event.preventDefault();

            const form = event.target;
            const formData = new FormData(form);

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    body: formData
                });

                const data = await response.json();

                if (data.success) {
                    // Refresh data antrian
                    await fetchAntrianData();

                    // Tampilkan notifikasi sukses
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: data.message,
                        timer: 2000,
                        showConfirmButton: false
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: data.message
                    });
                }
            } catch (error) {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Terjadi kesalahan saat memproses permintaan'
                });
            }
        }

        // fungsi untuk menghapus antrian
        // menambahkan handle delete
        async function handleDelete(event) {
            event.preventDefault();

            const form = event.target;
            const formData = new FormData(form);

            // Konfirmasi sebelum menghapus
            const result = await Swal.fire({
                title: 'Apakah Anda yakin?',
                text: 'Data yang dihapus tidak dapat dikembalikan!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            });

            if (!result.isConfirmed) {
                return; // Batalkan proses jika user menekan "Batal"
            }

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    body: formData
                });

                const data = await response.json();

                if (data.success) {
                    await fetchAntrianData(); // Refresh data

                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: data.message,
                        timer: 1000,
                        showConfirmButton: false
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: data.message
                    });
                }
            } catch (error) {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Terjadi kesalahan saat memproses permintaan'
                });
            }
        }

        // Fungsi untuk memperbarui tampilan
        function updateDisplay(data) {
            console.log('Data received:', data); // Tambahkan ini untuk debugging

            // Update statistik
            document.querySelectorAll('.card.text-bg-primary .card-text')[0].textContent = data.stats.jumlah_a;
            document.querySelectorAll('.card.text-bg-primary .card-text')[1].textContent = data.stats.jumlah_b;
            document.querySelectorAll('.card.text-bg-success .card-text')[0].textContent = data.stats.dipanggil_a;
            document.querySelectorAll('.card.text-bg-success .card-text')[1].textContent = data.stats.dipanggil_b;
            document.querySelectorAll('.card.text-bg-warning .card-text')[0].textContent = data.stats.sisa_a;
            document.querySelectorAll('.card.text-bg-warning .card-text')[1].textContent = data.stats.sisa_b;
            document.querySelector('.fs-5').textContent = data.stats.rata_rata_wait;

            // Update tabel
            const tbody = document.querySelector('tbody');
            tbody.innerHTML = '';

            data.antrian.forEach((row, i) => {
                const statusBadge = {
                    'waiting': 'warning',
                    'called': 'info',
                    'finished': 'success'
                } [row.status] || 'secondary';

                const statusText = row.status.charAt(0).toUpperCase() + row.status.slice(1);

                const waitSeconds = row.time_called ?
                    calculateTimeDifference(row.time_taken, row.time_called) :
                    0;
                const waitTime = row.time_called ? formatWaitTime(waitSeconds) : '-';

                // menambahkan delete antrian
                const deleteBtn = `<form method="POST" action="delete_antrian.php" class="d-inline" onsubmit="handleDelete(event)">
                <input type="hidden" name="id" value="${row.id_antrian}">
                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                </form>`

                const actionBtn = row.status === 'waiting' ?
                    `<form method="POST" action="panggil.php" class="d-inline" onsubmit="handlePanggil(event)">
                <input type="hidden" name="id" value="${row.id_antrian}">
                <button type="submit" class="btn btn-sm btn-primary">Panggil</button>
                </form>` :
                    row.status === 'called' ?
                    `<form method="POST" action="selesai.php" class="d-inline" onsubmit="handleSelesai(event)">
                <input type="hidden" name="id" value="${row.id_antrian}">
                <button type="submit" class="btn btn-sm btn-success">Selesai</button>
                </form>` :
                    '<span class="text-muted">✔</span>';


                const tr = document.createElement('tr');
                tr.innerHTML = `
            <td>${i + 1}</td>
            <td><span class="fw-bold">ONL_${row.jenis}${row.nomor}</span></td>
            <td>${row.jenis}</td>
            <td class="no-wrap">${row.date}</td>
            <td class="no-wrap">${row.username}</td>
            <td class="no-wrap">${row.no_hp}</td>
            <td><span class="badge bg-${statusBadge}">${statusText}</span></td>
            <td class="no-wrap">${row.time_taken}</td>
            <td class="no-wrap">${row.time_called || '-'}</td>
            <td class="no-wrap">${row.time_finished || '-'}</td>
            <td>${waitTime}</td>
            <td>${actionBtn}</td>
            <td>${deleteBtn}</td>
        `;


                tbody.appendChild(tr);
            });
        }

        // Fungsi untuk mengambil data antrian
        function fetchAntrianData() {
            const filterDate = document.getElementById('filterDate').value;
            let url = '../../peserta/booking.php?action=get_antrian';

            if (filterDate) {
                url += `&filterDate=${filterDate}`;
            }

            fetch(url)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data && data.antrian) {
                        updateDisplay(data);
                    } else {
                        console.error('Invalid data format:', data);
                    }
                })
                .catch(error => console.error('Error fetching data:', error));
        }

        // Polling setiap 3 detik
        setInterval(fetchAntrianData, 3000);

        // Ambil data pertama kali saat halaman dimuat
        document.addEventListener('DOMContentLoaded', fetchAntrianData);
    </script>
</body>

</html>