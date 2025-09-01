<?php include('header_tambah.php') ?>
<?php include('../includes/koneksi.php') ?>

<?php
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $query = "SELECT * FROM `users` WHERE `id_user`='$id'";
    $result = mysqli_query($conn, $query);
    if (!$result) {
        die("query failed");
    } else {
        $row = mysqli_fetch_assoc($result);
    }
}

if (isset($_POST['update_student'])) {
    if (isset($_GET['id_new'])) {
        $idnew = $_GET['id_new'];
    }
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $noHp = mysqli_real_escape_string($conn, $_POST['noHp']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = mysqli_real_escape_string($conn, $_POST['role']);

    $cek_query = "SELECT * FROM users WHERE (username='$username' OR email='$email' OR no_hp='$noHp') AND id_user != '$idnew'";
    $cek_result = mysqli_query($conn, $cek_query);

    if (mysqli_num_rows($cek_result) > 0) {
        $_SESSION['status'] = "error, Gagal!, username/email/nomor HP sudah digunakan oleh pengguna lain.";
        header("Location: index.php");
        exit();
    }

    $query = "UPDATE `users` SET `username` = '$username', `email` = '$email', `no_hp` = '$noHp', `password` = '$password', `role` = '$role' WHERE `id_user` = '$idnew'";
    $result = mysqli_query($conn, $query);
    if (!$result) {
        $_SESSION['status'] = "error, Gagal!, terjadi kesalahan saat menyimpan data!";
    } else {
        $_SESSION['status'] = "success, Berhasil!, user berhasil diperbarui!";
    }
    header('Location: index.php');
    exit();
}
?>
<?php include '../layout/header.php' ?>
<div class="container py-4">
    <div class="card shadow-lg">
        <div class="card-header bg-primary text-white">
            <div class="d-flex justify-content-between align-items-center">
                <h3 id="judul" class="mb-0"><i class="fas fa-user-edit me-2"></i>Update Data User</h3>
                <a href="index.php" class="btn btn-light btn-sm">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </div>
        
        <div class="card-body">
            <form action="update.php?id_new=<?php echo $id ?>" method="post">
                <div class="row g-3">
                    <!-- Username -->
                    <div class="col-md-6">
                        <label for="username" class="form-label">Username</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                            <input type="text" class="form-control" id="username" name="username" 
                                   value="<?php echo htmlspecialchars($row['username']) ?>" required>
                        </div>
                        <div class="form-text">Masukkan username yang akan digunakan</div>
                    </div>
                    
                    <!-- Email -->
                    <div class="col-md-6">
                        <label for="email" class="form-label">Email</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                            <input type="email" class="form-control" id="email" name="email" 
                                   value="<?php echo htmlspecialchars($row['email']) ?>" required>
                        </div>
                    </div>
                    
                    <!-- Nomor WhatsApp -->
                    <div class="col-md-6">
                        <label for="noHp" class="form-label">Nomor WhatsApp</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fab fa-whatsapp"></i></span>
                            <input type="tel" class="form-control" id="noHp" name="noHp" 
                                   value="<?php echo htmlspecialchars($row['no_hp']) ?>" 
                                   pattern="(08)[0-9]{5,20}" placeholder="08xxxxxxxxxx" required>
                        </div>
                    </div>
                    
                    <!-- Password -->
                    <div class="col-md-6">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            <input type="password" class="form-control" id="password" name="password" 
                                   placeholder="Masukkan password baru" required>
                            <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Role -->
                    <div class="col-md-6">
                        <label for="role" class="form-label">Jenis Role</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-user-tag"></i></span>
                            <select class="form-select" id="role" name="role" required>
                                <!-- <option value="user" <?= ($row['role'] == 'user') ? 'selected' : '' ?>>User</option> -->
                                <option value="faskes" <?= ($row['role'] == 'faskes') ? 'selected' : '' ?>>Faskes</option>
                                <option value="admin" <?= ($row['role'] == 'admin') ? 'selected' : '' ?>>Admin</option>
                            </select>
                        </div>
                    </div>
                    
                    <!-- Tombol Aksi -->
                    <div class="col-12 mt-4">
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="submit" class="btn btn-success px-4" name="update_student">
                                <i class="fas fa-save me-2"></i> Update Data
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Toggle show/hide password
document.getElementById('togglePassword').addEventListener('click', function() {
    const passwordInput = document.getElementById('password');
    const icon = this.querySelector('i');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        icon.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
        passwordInput.type = 'password';
        icon.classList.replace('fa-eye-slash', 'fa-eye');
    }
});

// Validasi form
document.querySelector('form').addEventListener('submit', function(e) {
    let valid = true;
    
    // Validasi nomor HP
    const noHp = document.getElementById('noHp');
    const hpRegex = /^08\d{5,20}$/;
    if (!hpRegex.test(noHp.value)) {
        valid = false;
        noHp.classList.add('is-invalid');
    } else {
        noHp.classList.remove('is-invalid');
    }
    
    if (!valid) {
        e.preventDefault();
        Swal.fire({
            icon: 'error',
            title: 'Validasi Gagal',
            text: 'Harap periksa kembali data yang dimasukkan',
        });
    }
});
</script>

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

<?php include('footer.php') ?>