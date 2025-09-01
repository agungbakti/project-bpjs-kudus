<?php
// usleep(500000);
session_start();
require "../database/function_faskes.php";

// cek apakah user faskes apa tidak
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: ../../index.php');
    exit;
}

// Ensure $koneksi is available for mysqli_query calls
// If function_faskes.php does not establish $koneksi globally, you might need to add:
// require "../database/koneksi.php"; // Adjust path as needed
global $koneksi; // Assuming $koneksi is set up globally in function_faskes.php or a included file

// SETUP PAGINATION
$dataPerHalaman = 100; // Sesuai dengan admin.php
$halamanSekarang = isset($_GET['halaman']) ? (int) $_GET['halaman'] : 1;
$halamanSekarang = max(1, $halamanSekarang);

// SETUP VARIABEL UNTUK MAINTAIN STATE
$keyword = isset($_POST['keyword']) ? $_POST['keyword'] : (isset($_GET['keyword']) ? $_GET['keyword'] : '');
$filter_tanggal = isset($_POST['filter_tanggal']) ? $_POST['filter_tanggal'] : (isset($_GET['filter_tanggal']) ? $_GET['filter_tanggal'] : '');

// Logika untuk menentukan jenis query
if (isset($_POST['cari']) || (!empty($keyword) && !isset($_POST['filter']))) { // Added !isset($_POST['filter']) to prioritize search
    $peserta = cariDenganPagination($keyword, $halamanSekarang, $dataPerHalaman);
    $totalData = hitungTotalDataCari($keyword);
    $tipeData = 'cari';
} elseif (isset($_POST['filter']) || (!empty($filter_tanggal) && !isset($_POST['cari']))) { // Added !isset($_POST['cari']) to prioritize filter
    $peserta = filterTanggalDenganPagination($filter_tanggal, $halamanSekarang, $dataPerHalaman);
    $totalData = hitungTotalDataFilter($filter_tanggal);
    $tipeData = 'filter';
} else {
    $peserta = ambilnilaiDenganPagination($halamanSekarang, $dataPerHalaman);
    $totalData = hitungTotalData();
    $tipeData = 'all';
}

$totalHalaman = ceil($totalData / $dataPerHalaman);

// Function to check if data is updated (sesuai dengan admin.php)
function isDataUpdated($row)
{
    // Use strict comparison if IDs are strings, or cast to int if they are integers
    return !empty($row['id_status']) && !empty($row['id_keterangan']) && !empty($row['nama']);
}

// Function to check if data is partially updated (sesuai dengan admin.php)
function isDataPartiallyUpdated($row)
{
    $filledFields = 0;
    if (!empty($row['id_status']))
        $filledFields++;
    if (!empty($row['id_keterangan']))
        $filledFields++;
    if (!empty($row['nama']))
        $filledFields++;

    return $filledFields > 0 && $filledFields < 3;
}

// Output hanya bagian tabel dan pagination (tanpa HTML lengkap)
$nomorUrut = ($halamanSekarang - 1) * $dataPerHalaman + 1;
?>


<!--<div class="card-body">-->
<!--    <div id="container" class="container-fluid mt-1">-->
<!--        <div class="table-responsive">-->
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
    <tr class="data-row <?= $rowClass ?>"> <td class="text-center"><?= $nomorUrut ?></td>

        <td class="text-center">
            <span class="status-indicator <?= $statusClass ?>">
                <?= $statusText ?>
            </span>
            <br>
            <small class="text-muted status-count"> <?php
                $filledCount = 0;
                if (!empty($row['id_status']))
                    $filledCount++;
                if (!empty($row['id_keterangan']))
                    $filledCount++;
                if (!empty($row['nama']))
                    $filledCount++;
                echo $filledCount . "/3";
                ?>
            </small>
        </td>

        <td><?= htmlspecialchars($row['tanggal']); ?></td>
        <td><?= htmlspecialchars($row['lokasi']); ?></td>
        <td><?= htmlspecialchars($row['fktp_dan_rumahsakit']); ?></td>
        <td><?= htmlspecialchars($row['kabupaten']); ?></td>
        <td><?= htmlspecialchars($row['nama_peserta']); ?></td>
        <td><?= htmlspecialchars($row['keperluan']); ?></td>
        <td><?= htmlspecialchars($row['nik']); ?></td>
        <td><?= htmlspecialchars($row['nomorhp']); ?></td>
        <td><?= htmlspecialchars($row['email']); ?></td>
        <td><?= htmlspecialchars($row['rujuk']); ?></td>

        <td>
            <select name="status"
                class="form-select form-select-sm status-select <?= empty($row['id_status']) ? 'field-empty' : 'field-filled' ?>"
                data-id="<?= htmlspecialchars($row['id_faskes']); ?>"
                data-original-value="<?= htmlspecialchars($row['id_status'] ?? ''); ?>"> <option value="">Pilih Status</option>
                <?php
                $status_query = "SELECT id_status, status FROM tb_status";
                $status_result = mysqli_query($koneksi, $status_query);
                while ($status = mysqli_fetch_assoc($status_result)) {
                    $selected = ((string)$status['id_status'] === (string)$row['id_status']) ? 'selected' : '';
                    echo "<option value='" . htmlspecialchars($status['id_status']) . "' $selected>" . htmlspecialchars($status['status']) . "</option>";
                }
                ?>
            </select>
        </td>

        <td>
            <select name="keterangan"
                class="form-select form-select-sm keterangan-select <?= empty($row['id_keterangan']) ? 'field-empty' : 'field-filled' ?>"
                data-id="<?= htmlspecialchars($row['id_faskes']); ?>"
                data-original-value="<?= htmlspecialchars($row['id_keterangan'] ?? ''); ?>"> <option value="">Pilih Keterangan</option>
                <?php
                $keterangan_query = "SELECT id_keterangan, keterangan FROM tb_keterangan";
                $keterangan_result = mysqli_query($koneksi, $keterangan_query);
                while ($keterangan = mysqli_fetch_assoc($keterangan_result)) {
                    $selected = ((string)$keterangan['id_keterangan'] === (string)$row['id_keterangan']) ? 'selected' : '';
                    echo "<option value='" . htmlspecialchars($keterangan['id_keterangan']) . "' $selected>" . htmlspecialchars($keterangan['keterangan']) . "</option>";
                }
                ?>
            </select>
        </td>

        <td>
            <input type="text" name="nama"
                class="form-control form-control-sm nama-input <?= empty($row['nama']) ? 'field-empty' : 'field-filled' ?>"
                data-id="<?= htmlspecialchars($row['id_faskes']); ?>" value="<?= htmlspecialchars($row['nama'] ?? ''); ?>"
                data-original-value="<?= htmlspecialchars($row['nama'] ?? ''); ?>" placeholder="......................." autocomplete="off">
        </td>

        <td>
            <a href="edit.php?id=<?= htmlspecialchars($row['id_faskes']); ?>" class="btn btn-primary btn-sm"
                style="text-decoration: none;">Edit</a>
            |
            <a href="hapus.php?id=<?= htmlspecialchars($row['id_faskes']); ?>" class="btn btn-danger btn-sm btn-hapus"
                style="text-decoration: none;">Hapus</a>
        </td>
    </tr>
    <?php $nomorUrut++; ?>
<?php endforeach; ?>

<?php if (empty($peserta)): ?>
    <tr>
        <td colspan="16" class="text-center py-4">
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

            </table>
<!--        </div>-->
<!--    </div>-->
<!--</div>-->

<script>
    // Script untuk real-time update visual (sesuai dengan admin.php)
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
    document.addEventListener('DOMContentLoaded', function () {
        const statusSelects = document.querySelectorAll('.status-select');
        const keteranganSelects = document.querySelectorAll('.keterangan-select');
        const namaInputs = document.querySelectorAll('.nama-input');

        // Add event listeners
        [...statusSelects, ...keteranganSelects].forEach(select => {
            select.addEventListener('change', function () {
                const row = this.closest('tr');
                updateRowStatus(row);
            });
        });

        namaInputs.forEach(input => {
            input.addEventListener('input', function () {
                const row = this.closest('tr');
                updateRowStatus(row);
            });
        });
    });

    // Script untuk tombol hapus dengan SweetAlert
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