<?php
require 'includes/koneksi.php';
// menampung variabel
$showModal = false;
$todayDate = date('Y-m-d');
$currentTime = date('H:i');
$modalContent = "";

// Tangkap parameter filter jika ada
$filterDate = isset($_GET['filterDate']) ? $_GET['filterDate'] : $todayDate;

// Endpoint khusus untuk admin
if (isset($_GET['action'])) {
    if ($_GET['action'] == 'get_antrian') {
        // Gunakan filter date dalam query
        $antrian = $conn->query("SELECT * FROM antrian WHERE DATE(date) = '$filterDate' ORDER BY time_taken ASC")->fetch_all(MYSQLI_ASSOC);

        // Hitung statistik dengan filter
        $jumlah_a = $conn->query("SELECT COUNT(*) as total FROM antrian WHERE jenis='A' AND DATE(date) = '$filterDate'")->fetch_assoc()['total'];
        $jumlah_b = $conn->query("SELECT COUNT(*) as total FROM antrian WHERE jenis='B' AND DATE(date) = '$filterDate'")->fetch_assoc()['total'];

        $dipanggil_a = $conn->query("SELECT nomor FROM antrian WHERE jenis='A' AND status='called' AND DATE(date) = '$filterDate' ORDER BY time_called DESC LIMIT 1")->fetch_assoc()['nomor'] ?? '-';
        $dipanggil_b = $conn->query("SELECT nomor FROM antrian WHERE jenis='B' AND status='called' AND DATE(date) = '$filterDate' ORDER BY time_called DESC LIMIT 1")->fetch_assoc()['nomor'] ?? '-';

        $sisa_a = $conn->query("SELECT COUNT(*) as total FROM antrian WHERE jenis='A' AND status='waiting' AND DATE(date) = '$filterDate'")->fetch_assoc()['total'];
        $sisa_b = $conn->query("SELECT COUNT(*) as total FROM antrian WHERE jenis='B' AND status='waiting' AND DATE(date) = '$filterDate'")->fetch_assoc()['total'];

        $total_wait = 0;
        $jumlah_called = 0;

        // melakukan perulangan untuk mengetahui jumlad dipanggil dan jumlah menunggu
        foreach ($antrian as $row) {
            if ($row['time_called']) {
                $wait = strtotime($row['time_called']) - strtotime($row['time_taken']);
                $total_wait += $wait;
                $jumlah_called++;
            }
        }

        // menghitung rata2 waktu tunggu
        $rata_rata_wait = $jumlah_called > 0 ? round($total_wait / $jumlah_called) : 0;

        // memasukan data ke dalam json yang kemudian diambil oleh admin
        header('Content-Type: application/json');
        echo json_encode([
            'antrian' => $antrian,
            'stats' => [
                'jumlah_a' => $jumlah_a,
                'jumlah_b' => $jumlah_b,
                'dipanggil_a' => $dipanggil_a,
                'dipanggil_b' => $dipanggil_b,
                'sisa_a' => $sisa_a,
                'sisa_b' => $sisa_b,
                'rata_rata_wait' => gmdate("H:i:s", $rata_rata_wait)
            ]
        ]);
        exit;
    }
}

// menangkap pengisian form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars(trim($_POST['name']));
    $date = $_POST['date'];
    $time = $_POST['time'];
    $jenis = $_POST['jenis'];
    $phone = htmlspecialchars(trim($_POST['phone']));
    $showModal = true; // Tetapkan ini di awal, agar selalu true saat post

    date_default_timezone_set("Asia/Jakarta");

    $hariLiburNasional = [];

    $sql = "SELECT tanggal FROM hari_libur";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $hariLiburNasional[] = $row['tanggal'];
        }
    }

    // $hariLiburNasional = [
    //     '2025-01-01',
    //     '2025-03-31',
    //     '2025-04-18',
    // ];

    $canBook = true;
    $dayOfWeek = date('N', strtotime($date));

    if (in_array($date, $hariLiburNasional) || $dayOfWeek >= 6) {
        $modalContent = "Tidak bisa booking pada hari libur atau akhir pekan.";
        $canBook = false;
    }

    if ($date < $todayDate) {
        $modalContent = "Tanggal booking tidak boleh sebelum hari ini.";
        $canBook = false;
    } elseif ($date == $todayDate) {
        $inputDateTime = DateTime::createFromFormat('Y-m-d H:i', "$date $time");
        $nowDateTime = new DateTime();

        if ($inputDateTime <= $nowDateTime) {
            $modalContent = "Jam booking sudah lewat. Silakan pilih jam lain.";
            $canBook = false;
        }
    }

    if ($canBook) {
        $stmt = $conn->prepare("SELECT COUNT(*) as total FROM antrian WHERE date = ? AND time_taken = ?");
        $stmt->bind_param("ss", $date, $time);
        $stmt->execute();
        $checkTime = $stmt->get_result()->fetch_assoc();

        if ($checkTime['total'] > 0) {
            $modalContent = "Jam tersebut sudah dibooking. Silakan pilih jam lain.";
        } else {
            $stmt = $conn->prepare("SELECT COUNT(*) as total FROM antrian WHERE date = ? AND jenis = ?");
            $stmt->bind_param("ss", $date, $jenis);
            $stmt->execute();
            $result = $stmt->get_result()->fetch_assoc();
            $nomorAntrian = $result['total'] + 1;
            // jumlah antrian
            $limit = $jenis == 'A' ? 70 : 14;

            if ($result['total'] >= $limit) {
                $modalContent = "Kuota antrian $jenis pada tanggal $date sudah penuh. Silakan pilih hari atau jam lain.";
            } else {
                $stmt = $conn->prepare("INSERT INTO antrian (username, date, time_taken, jenis, nomor, no_hp) VALUES (?, ?, ?, ?, ?,?)");
                $stmt->bind_param("ssssss", $name, $date, $time, $jenis, $nomorAntrian, $phone);
                $stmt->execute();

                $nomorLabel = $jenis . $nomorAntrian;
                $modalContent = "Booking Anda berhasil! Nomor antrian Anda: $nomorLabel";


                $token = "PJ9ETjVr4rBf3c1EvzbW";
                $target = $phone;
                $message = "Bapak/Ibu $name,\nBooking Antrian Online Sudah Berhasil!\n\nTanggal: $date\nJam: $time\nJenis Antrian: $jenis\nNomor Antrian: $nomorLabel\n\nTerima kasih.\n Salam,\n BPJS Kesehatan Kudus\n Komplek Perkantoran Jl. Mejobo Mlati Kidul Kudus \nTelp (0291) 435587 Fax (0291) 431506 \n www.bpjs-kesehatan.go.id ";

                $curl = curl_init();
                curl_setopt_array($curl, [
                    CURLOPT_URL => "https://api.fonnte.com/send",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_POST => true,
                    CURLOPT_POSTFIELDS => [
                        'target' => $target,
                        'message' => $message,
                        'countryCode' => '62',
                    ],
                    CURLOPT_HTTPHEADER => [
                        "Authorization: $token"
                    ],
                ]);
                $response = curl_exec($curl);
                curl_close($curl);
                $data = json_decode($response, true);

                if (isset($data['status']) && $data['status'] == true) {
                    echo "<script>console.log('Pesan WhatsApp berhasil dikirim.');</script>";
                } else {
                    echo "<script>console.log('Gagal kirim WA. Pesan error: " . ($data['reason'] ?? 'Tidak diketahui') . "');</script>";
                }
                // $showModal = true; // Pastikan ini true jika ingin modal muncul setelah sukses
            }
        }
    }
}


$monday = date('Y-m-d', strtotime('monday this week'));
$sunday = date('Y-m-d', strtotime('sunday this week'));
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Antrian</title>
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.min.js" integrity="sha384-RuyvpeZCxMJCqVUGFI0Do1mQrods/hhxYlcVfGPOfQtPJh0JCw12tUAZ/Mv10S7D" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" xintegrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>

    <header>
        <?php include 'layout/header_user.php'; ?>
    </header>

    <div class="container-fluid mt-5 pt-4">
        <div class="card shadow-sm mb-3">
            <div class="card-body">
                <h2>SELAMAT DATANG DIWEBSITE BOOKING ANTRIAN BPJS KANTOR CABANG KUDUS</h2>
                <ol style="text-align: justify;">
                    <li>Silahkan melakukan pengisian form terlebih dahulu untuk melakukan booking antrian</li>
                    <li>Silahkan cek berapa sisa booking antrian yang tersisa dan cek tabel daftar booking</li>
                    <li>Setelah anda melakukan booking antrian pastikan menerima pesan whatsapp tentang detail antrian</li>
                    <li>Silahkan datang ke BPJS Kesehatan KC Kudus sesuai jam booking antriannya</li>
                    <li>Silahkan tunjukan pesan whatsapp tersebut kepada pihak bpjs</li>
                </ol>
            </div>
        </div>
    </div>
    <div style="scroll-margin-top: 70px;" id="form-booking" class="container-fluid">
        <div class="card shadow-sm mb-3">
            <div class="card-body">
                <h2 class="card-title">Form Booking Antrian</h2>
                <form method="post" class="row g-3 needs-validation" novalidate>
                    <div class="col-md-4">
                        <label for="validationCustomUsername" class="form-label">Nama Lengkap</label>
                        <div class="input-group has-validation">
                            <span class="input-group-text" id="inputGroupPrepend"><i class="fa-regular fa-user"></i></span>
                            <input type="text" name="name" class="form-control" id="validationCustomUsername" aria-describedby="inputGroupPrepend" autocomplete="off" required>
                            <div class="invalid-feedback">
                                Silahkan masukan nama lengkap.
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label for="validationCustomUsername" class="form-label">Nomor WhatsApp</label>
                        <div class="input-group has-validation">
                            <span class="input-group-text" id="inputGroupPrepend"><i class="fa-brands fa-whatsapp"></i></span>
                            <input type="tel" name="phone" class="form-control" id="validationCustomUsername" aria-describedby="inputGroupPrepend" pattern="(628)[0-9]{5,20}" placeholder="contoh: 628xxxxxxxxxxx" autocomplete="off" required>
                            <div class="invalid-feedback">
                                Silahkan masukan nomor whatsapp sesuai format.
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label for="validationCustomUsername" class="form-label">Tanggal</label>
                        <div class="input-group has-validation">
                            <span class="input-group-text" id="inputGroupPrepend"><i class="fa-regular fa-calendar"></i></span>
                            <input type="date" name="date" class="form-control" id="validationCustomUsername" aria-describedby="inputGroupPrepend" min="<?= $todayDate ?>" required>
                            <div class="invalid-feedback">
                                Silahkan masukan tanggal.
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label for="validationCustomUsername" class="form-label">Jam</label>
                        <div class="input-group has-validation">
                            <span class="input-group-text" id="inputGroupPrepend"><i class="fa-regular fa-clock"></i></span>
                            <select name="time" class="form-select" id="validationCustom04" required>
                                <option selected disabled value="">Choose...</option>
                                <?php
                                $start = strtotime('08:00');
                                $end = strtotime('14:55');

                                for ($time = $start; $time <= $end; $time += 300) { // 600 detik = 10 menit
                                    $formattedTime = date('H:i', $time);
                                    echo "<option value='$formattedTime'>$formattedTime</option>";
                                }
                                ?>
                            </select>
                            <div class="invalid-feedback">
                                Silahkan pilih jam.
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label for="validationCustomUsername" class="form-label">Jenis Antrian</label>
                        <div class="input-group has-validation">
                            <span class="input-group-text" id="inputGroupPrepend"><i class="fa-regular fa-bell"></i></span>
                            <select name="jenis" class="form-select" id="validationCustom04" required>
                                <option selected disabled value="">Choose...</option>
                                <option value="A">A - Pelayanan administrasi</option>
                                <option value="B">B - Pelayanan informasi dan pengaduan</option>
                            </select>
                            <div class="invalid-feedback">
                                Silahkan pilih jenis.
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <button class="btn btn-primary" type="submit" id="submitButton">Booking Antrian</button>
                    </div>
                </form>
                <hr>
                <div>
                    <p class="m-0 p-0 fs-6 fst-italic"><i class="fa-solid fa-circle-info"></i> Antrian A untuk pelayanan administrasi</p>
                    <p class="m-0 p-0 ps-3 fs-6 fst-italic">Antrian B untuk pelayanan informasi dan pengaduan</p>
                </div>
            </div>
        </div>
    </div>

    <div style="scroll-margin-top: 70px;" id="sisa-antrian" class="container-fluid">
        <div class="card shadow-sm mb-3">
            <div class="card-body">
                <h3>Sisa Antrian Hari Ini</h3>
                <?php
                $stmt = $conn->prepare("SELECT jenis, COUNT(*) as total FROM antrian WHERE date = ? GROUP BY jenis");
                $stmt->bind_param("s", $todayDate);
                $stmt->execute();
                $result = $stmt->get_result();

                $kuota = ['A' => 0, 'B' => 0];
                while ($row = $result->fetch_assoc()) {
                    $kuota[$row['jenis']] = $row['total'];
                }

                $sisaA = 70 - $kuota['A'];
                $sisaB = 14 - $kuota['B'];

                echo "<ul>";
                echo "<li>Antrian A: sisa $sisaA dari 70</li>";
                echo "<li>Antrian B: sisa $sisaB dari 14</li>";
                echo "</ul>";
                ?>

            </div>
        </div>
    </div>
    <?php
    $jamList = [];

    for ($h = 8; $h <= 14; $h++) {
        for ($m = 0; $m < 60; $m += 5) {
            $jamList[] = sprintf('%02d:%02d', $h, $m);
        }
    }

    $stmt = $conn->prepare("SELECT username, date, time_taken, jenis, nomor FROM antrian WHERE date BETWEEN ? AND ? ORDER BY date, time_taken");
    $stmt->bind_param("ss", $monday, $sunday);
    $stmt->execute();
    $result = $stmt->get_result();

    $jadwal = [];
    while ($row = $result->fetch_assoc()) {
        $jam = substr($row['time_taken'], 0, 5);
        $tanggal = $row['date'];
        $nomor = $row['jenis'] . $row['nomor'];
        $jadwal[$jam][$tanggal] = "{$row['username']} ($nomor)";
    }

    $tanggalMingguIni = [];
    for ($i = 0; $i < 7; $i++) {
        $tanggal = date('Y-m-d', strtotime("$monday +$i days"));
        $tanggalMingguIni[] = $tanggal;
    }
    ?>
    <div style="scroll-margin-top: 70px;" id="daftar-booking" class="container-fluid">
        <div class="card shadow-sm mb-3">
            <div class="card-body">
                <div class="table-responsive">
                    <h2 class="card-title text-wrap text-break pb-3">Daftar Booking Antrian Minggu Ini (<?= date('d M', strtotime($monday)) ?> - <?= date('d M Y', strtotime($sunday)) ?>)</h2>
                    <table class="table table-bordered table-striped text-center align-middle text-nowrap mb-0">
                        <thead class="table-primary">
                            <tr>
                                <th>Jam</th>
                                <?php foreach ($tanggalMingguIni as $tanggal) : ?>
                                    <th><?= date('d M y', strtotime($tanggal)) ?></th>
                                <?php endforeach; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Ambil data hari libur dari database
                            $hariLiburNasional = [];
                            $sql = "SELECT tanggal FROM hari_libur";
                            $result = $conn->query($sql);
                            if ($result && $result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    $hariLiburNasional[] = $row['tanggal']; // pastikan formatnya Y-m-d
                                }
                            }
                            ?>

                            <?php foreach ($jamList as $jam) : ?>
                                <tr>
                                    <td><?= $jam ?></td>
                                    <?php foreach ($tanggalMingguIni as $tanggal) : ?>
                                        <td>
                                            <?php
                                            // Format tanggal menjadi Y-m-d
                                            $tanggalFormat = date('Y-m-d', strtotime($tanggal));
                                            // Cari tahu hari dalam angka (1 = Senin, ..., 7 = Minggu)
                                            $hariKe = date('N', strtotime($tanggal));

                                            if (in_array($tanggalFormat, $hariLiburNasional) || $hariKe == 6 || $hariKe == 7) {
                                                echo '<span style="color:red;">Libur</span>';
                                            } else {
                                                echo isset($jadwal[$jam][$tanggal]) ? $jadwal[$jam][$tanggal] : '';
                                            }
                                            ?>
                                        </td>
                                    <?php endforeach; ?>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    </div>
    <div class="modal fade" id="infoModal" tabindex="-1" aria-labelledby="infoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="infoModalLabel">Informasi</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modalBodyContent">
                    <?php echo $modalContent; ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <?php if ($showModal && !empty($modalContent)) : ?>
        <script>
            var infoModal = new bootstrap.Modal(document.getElementById('infoModal'));
            infoModal.show();
        </script>
    <?php endif; ?>

    <script>
        (() => {
            'use strict'

            const forms = document.querySelectorAll('.needs-validation')
            const submitButton = document.getElementById('submitButton');

            Array.from(forms).forEach(form => {
                form.addEventListener('submit', event => {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    } else {
                        // Jika form valid, biarkan form di-submit untuk diproses PHP
                        // event.preventDefault(); // Jangan preventDefault disini, biar ke PHP
                        // console.log("Formulir valid, siap untuk diproses server.");
                    }
                    form.classList.add('was-validated')
                }, false)
            })
        })()
    </script>
        <header>
        <?php include 'layout/footer.php'; ?>
    </header>
</body>

</html>