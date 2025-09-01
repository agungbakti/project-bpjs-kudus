<?php include('header-hari-libur.php') ?>
<?php include('../includes/koneksi.php') ?>

<?php
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $query = "SELECT * FROM `hari_libur` WHERE `id`='$id'";
    $result = mysqli_query($conn, $query);
    if (!$result) {
        die("query failed");
    } else {
        $row = mysqli_fetch_assoc($result);
    }
}

if (isset($_POST['update_libur'])) {
    if (isset($_GET['id_new'])) {
        $idnew = $_GET['id_new'];
    }
    $tanggal = mysqli_real_escape_string($conn, $_POST['tanggal']);
    $keterangan = mysqli_real_escape_string($conn, $_POST['keterangan']);

    $cek_query = "SELECT * FROM hari_libur WHERE (tanggal='$tanggal' OR keterangan='$keterangan') AND id != '$idnew'";
    $cek_result = mysqli_query($conn, $cek_query);

    if (mysqli_num_rows($cek_result) > 0) {
        $_SESSION['status'] = "error, Gagal!, tanggal/keterangan/nomor HP sudah digunakan oleh pengguna lain.";
        header("Location: hari-libur.php");
        exit();
    }

    $query = "UPDATE `hari_libur` SET `tanggal` = '$tanggal', `keterangan` = '$keterangan' WHERE `id` = '$idnew'";
    $result = mysqli_query($conn, $query);
    if (!$result) {
        $_SESSION['status'] = "error, Gagal!, terjadi kesalahan saat menyimpan data!";
    } else {
        $_SESSION['status'] = "success, Berhasil!, user berhasil diperbarui!";
    }
    header('Location: hari-libur.php');
    exit();
}
?>
<?php include '../layout/header.php' ?>
<div class="container py-4">
    <div class="card shadow-lg">
        <div class="card-header bg-primary text-white">
            <div class="d-flex justify-content-between align-items-center">
                <h3 id="judul" class="mb-0"><i class="fas fa-user-edit me-2"></i>Update Data User</h3>
                <a href="hari-libur.php" class="btn btn-light btn-sm">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </div>

        <div class="card-body">
            <form action="update-libur.php?id_new=<?php echo $id ?>" method="post">
                <div class="row g-3">
                    <!-- Username -->
                    <div class="col-md-6">
                        <label for="tanggal" class="form-label">Username</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                            <input type="date" class="form-control" id="tanggal" name="tanggal"
                                value="<?php echo htmlspecialchars($row['tanggal']) ?>" required>
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="col-md-6">
                        <label for="keterangan" class="form-label">Email</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                            <input type="text" class="form-control" id="keterangan" name="keterangan"
                                value="<?php echo htmlspecialchars($row['keterangan']) ?>" required>
                        </div>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="col-12 mt-4">
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="submit" class="btn btn-success px-4" name="update_libur">
                                <i class="fas fa-save me-2"></i> Update Data
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .card {
        border-radius: 0.5rem;
        overflow: hidden;
    }

    .input-group-text {
        min-width: 45px;
        justify-content: center;
    }

    .form-label {
        font-weight: 500;
    }
</style>

<?php include('footer-hari-libur.php') ?>