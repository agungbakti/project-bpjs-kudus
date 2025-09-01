<?php include('header-hari-libur.php') ?>
<?php include '../layout/header.php' ?>
<?php include('../includes/koneksi.php') ?>

<!-- Notifikasi INSERT -->
<?php if (isset($_SESSION['status'])): ?>
    <?php $splite = explode(', ', $_SESSION['status']);
    //    var_dump($splite);
    $icon = trim($splite[0]);
    $title = trim($splite[1]);
    $text = trim($splite[2]);
    ?>
    <script>
        Swal.fire({
            icon: '<?= $icon ?>',
            title: '<?= $title ?>',
            text: '<?= $text ?>',
        });
    </script>
    <?php unset($_SESSION['status']); ?>
<?php endif; ?>


<div class=" pt-4 mt-4">
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="p-3 bg-light">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                    <h2 class="h5 text-center text-md-start mb-3 mb-md-0 w-100">TABEL HARI LIBUR NASIONAL</h2>
                    <div class="d-flex justify-content-center justify-content-md-end w-100 w-md-auto">
                        <div class="me-2">
                            <input type="text" id="liveSearch" class="form-control form-control-sm" placeholder="Cari hari libur...">
                        </div>
                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModal">
                            <i class="fas fa-plus me-1"></i> Tambah
                        </button>
                    </div>
                </div>
            </div>


            <div class="table-responsive" style="overflow-x: auto; -webkit-overflow-scrolling: touch;">
                <table class="rounded-1 table table-hover table-bordered table-striped mb-0" style="width: 100%; min-width: 800px;">
                    <thead class="table-dark text-center">
                        <tr>
                            <th class="text-center" style="width: 50px;">No</th>
                            <th>Tanggal Libur Nasional</th>
                            <th>Keterangan</th>
                            <th style="width: 100px;">Edit</th>
                            <th style="width: 100px;">delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = "SELECT * FROM hari_libur";
                        $result = mysqli_query($conn, $query);
                        if (!$result) {
                            die("query failed");
                        } else {
                            $counter = 1;
                            while ($row = mysqli_fetch_assoc($result)) {
                                // echo '<pre>'; print_r($row); echo '</pre>';
                        ?>
                                <tr>
                                    <td class="text-center"><?php echo $counter++; ?></td>
                                    <td><?php echo htmlspecialchars($row['tanggal']) ?></td>
                                    <td><?php echo htmlspecialchars($row['keterangan']) ?></td>
                                    </td>
                                    <td class="text-center">
                                        <a href="update-libur.php?id=<?php echo $row['id'] ?>"
                                            class="btn btn-warning btn-sm py-1 px-2"
                                            title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </td>
                                    <td class="text-center">
                                        <a href="delete-libur.php?id=<?php echo $row['id'] ?>"
                                            class="btn btn-danger btn-sm py-1 px-2"
                                            onclick="return confirmDelete(event)"
                                            title="Delete">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                    </td>
                                </tr>
                        <?php
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- Modal -->
<form action="insert_libur.php" id="tambah_user" method="post">
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h1 class="modal-title fs-5 text-light" id="exampleModalLabel">TAMBAH HARI LIBUR</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body ">
                    <div class="w-100 col-md-4">
                        <label for="validationCustomUsername" class="form-label">Tanggal Libur Nasional</label>
                        <div class="input-group has-validation">
                            <span class="input-group-text" id="inputGroupPrepend"><i class="fa-solid fa-calendar"></i></i></span>
                            <input type="date" name="tanggal" class="form-control" id="validationCustomUsername" aria-describedby="inputGroupPrepend" required>
                            <div class="invalid-feedback">
                                Silahkan masukan tanggal.
                            </div>
                        </div>
                    </div>
                    <div class="w-100 col-md-4">
                        <label for="validationCustomUsername" class="form-label">Keterangan</label>
                        <div class="input-group has-validation">
                            <span class="input-group-text" id="inputGroupPrepend"><i class="fa-solid fa-pen"></i></span>
                            <input type="text" name="keterangan" class="form-control" id="validationCustomUsername" aria-describedby="inputGroupPrepend" required>
                            <div class="invalid-feedback">
                                Silahkan masukan Keterangan.
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer col-12">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success" id="submitButton" name="tambahLibur">Tambah</button>
                    </div>
                </div>
            </div>
        </div>
</form>
</div>
<?php include('footer-hari-libur.php') ?>