<?php include('header_tambah.php') ?>
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
                    <h2 class="h5 text-center text-md-start mb-3 mb-md-0 w-100">TABEL USER</h2>
                    <div class="d-flex justify-content-center justify-content-md-end w-100 w-md-auto">
                        <div class="me-2">
                            <input type="text" id="liveSearch" class="form-control form-control-sm" placeholder="Cari user...">
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
                            <th>Username</th>
                            <th>Email</th>
                            <th>No. HP</th>
                            <th style="width: 120px;">Password</th>
                            <th style="width: 100px;">Role</th>
                            <th style="width: 80px;">Update</th>
                            <th style="width: 80px;">Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = "SELECT * FROM users";
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
                                    <td><?php echo htmlspecialchars($row['username']) ?></td>
                                    <td><?php echo htmlspecialchars($row['email']) ?></td>
                                    <td><?php echo htmlspecialchars($row['no_hp']) ?></td>
                                    <td class="text-center"><?php echo str_repeat('•', 10); ?></td>
                                    <td class="text-center">
                                        <?php
                                        $role = $row['role'];
                                        $badgeClass = '';
                                        $icon = '';

                                        switch ($role) {
                                            case 'admin':
                                                $badgeClass = 'bg-success';
                                                $icon = 'fa-user-shield';
                                                break;
                                            case 'faskes':
                                                $badgeClass = 'bg-primary';
                                                $icon = 'fa-hospital';
                                                break;
                                            case 'user':
                                                $badgeClass = 'bg-secondary';
                                                $icon = 'fa-user';
                                                break;
                                            default:
                                                $badgeClass = 'bg-warning';
                                                $icon = 'fa-question';
                                        }
                                        ?>
                                        <span class="badge <?php echo $badgeClass ?>">
                                            <i class="fas <?php echo $icon ?> me-1"></i>
                                            <?php echo ucfirst($role) ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <a href="update.php?id=<?php echo $row['id_user'] ?>"
                                            class="btn btn-warning btn-sm py-1 px-2"
                                            title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </td>
                                    <td class="text-center">
                                        <a href="delete.php?id=<?php echo $row['id_user'] ?>"
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
<form action="insert_data.php" id="tambah_user" method="post">
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h1 class="modal-title fs-5 text-light" id="exampleModalLabel">ADD NEW USER</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body ">
                    <div class="w-100 col-md-4">
                        <label for="validationCustomUsername" class="form-label">Username</label>
                        <div class="input-group has-validation">
                            <span class="input-group-text" id="inputGroupPrepend"><i class="fa-solid fa-user"></i></span>
                            <input type="text" name="username" class="form-control" id="validationCustomUsername" aria-describedby="inputGroupPrepend" required>
                            <div class="invalid-feedback">
                                Silahkan masukan username.
                            </div>
                        </div>
                    </div>
                    <div class="w-100 col-md-4">
                        <label for="validationCustomUsername" class="form-label">Email</label>
                        <div class="input-group has-validation">
                            <span class="input-group-text" id="inputGroupPrepend"><i class="fa-solid fa-envelope"></i></span>
                            <input type="email" name="email" class="form-control" id="validationCustomUsername" aria-describedby="inputGroupPrepend" required>
                            <div class="invalid-feedback">
                                Silahkan masukan email.
                            </div>
                        </div>
                    </div>
                    <div class="w-100 col-md-4">
                        <label for="validationCustomUsername" class="form-label">Nomor WhatsApp</label>
                        <div class="input-group has-validation">
                            <span class="input-group-text" id="inputGroupPrepend"><i class="fa-brands fa-whatsapp"></i></span>
                            <input type="tel" name="noHp" class="form-control" id="validationCustomUsername" aria-describedby="inputGroupPrepend" pattern="(08)[0-9]{5,20}" placeholder="contoh: 08xxxxxxxxxxx" required>
                            <div class="invalid-feedback">
                                Silahkan masukan nomor whatsapp.
                            </div>
                        </div>
                    </div>
                    <div class="w-100 col-md-4">
                        <label for="validationCustomUsername" class="form-label">Password</label>
                        <div class="input-group has-validation">
                            <span class="input-group-text" id="inputGroupPrepend"><i class="fa-solid fa-lock"></i></span>
                            <input type="text" name="password" class="form-control" id="validationCustomUsername" aria-describedby="inputGroupPrepend" min="<?= $todayDate ?>" required>
                            <div class="invalid-feedback">
                                Silahkan masukan password.
                            </div>
                        </div>
                    </div>
                    <div class="w-100 col-md-4">
                        <label for="validationCustomUsername" class="form-label">Jenis Role</label>
                        <div class="input-group has-validation">
                            <span class="input-group-text" id="inputGroupPrepend"><i class="fa-solid fa-user-tie"></i></i></span>
                            <select name="role" class="form-select" id="validationCustom04" required>
                                <option selected disabled value="">Choose...</option>
                                <!-- <option value="user">User</option> -->
                                <option value="faskes">Faskes</option>
                                <option value="admin">Admin</option>
                            </select>
                            <div class="invalid-feedback">
                                Silahkan pilih Role.
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer col-12">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success" id="submitButton" name="tambahUser">Tambah</button>
                    </div>
                </div>
            </div>
        </div>
</form>
</div>
<?php include('footer.php') ?>