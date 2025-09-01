<?php
session_start();
// Pengecekan role
if (isset($_SESSION['user'])) {
    if ($_SESSION['user']['role'] === 'admin') {
        header('Location: admin/home.php');
    } else {
        header('Location: faskes3/dashboard/faskes.php');
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk Akun</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="fontawesome/css/all.min.css">
    <script src="js/bootstrap.bundle.min.js"></script>
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico">
    <style>
        body {
            background: url('assets/gambar1.jpg') no-repeat center center/cover;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(3px);
        }

        .login-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
            padding: 30px;
            width: 100%;
            max-width: 400px;
        }

        .form-floating i {
            position: absolute;
            top: 50%;
            right: 15px;
            color: #aaa;
        }

        .overlay-spinner {
            position: fixed;
            top: 0; left: 0;
            width: 100vw; height: 100vh;
            /* background: rgba(255,255,255,0.7); */
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

    </style>
</head>
<body>

<div class="login-card">
    <form id="loginForm" method="post" action="includes/login.php">
        <h4 class="text-center fw-semibold mb-4">Masuk Akun</h4>

        <!-- Notifikasi status login -->
        <?php if (isset($_SESSION['status'])): 
            $splite = explode(', ', $_SESSION['status']); ?>
            <div class="alert alert-<?php echo trim($splite[0]) ?> alert-dismissible fade show" role="alert">
                <?php echo $splite[1] . (count($splite) > 2 ? " " . $splite[2] : ''); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['status']); ?>
        <?php endif; ?>

        <!-- Email / Username -->
        <div class="form-floating mb-3 position-relative">
            <input name="login" type="text" class="form-control" placeholder="Email" autocomplete="off" required>
            <label>Email / Username</label>
            <i class="fa-solid fa-user"></i>
        </div>

        <!-- Password -->
        <div class="form-floating mb-3 position-relative">
            <input name="password" type="password" class="form-control" id="passwordInput" placeholder="Password" required>
            <label for="passwordInput">Password</label>
            <i class="fa-solid fa-eye-slash toggle-password" style="cursor: pointer;"></i>
        </div>

        <!-- Loading Spinner -->
        <div id="loading-spinner" class="overlay-spinner" style="display: none;">
            <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>

        <button type="submit" id="loginButton" class="btn btn-primary w-100 mb-3">
            <i class="fa-solid fa-arrow-right-to-bracket me-1"></i> Masuk
        </button>

        <!--<div class="text-center">-->
        <!--    <small>Belum punya akun? <a href="user/daftar.php">Daftar di sini</a></small>-->
        <!--</div>-->
        <p class="text-muted text-center mt-3 mb-0">&copy; 2025</p>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const toggleIcon = document.querySelector('.toggle-password');
        const passwordInput = document.getElementById('passwordInput');

        toggleIcon.addEventListener('click', function () {
            const isPassword = passwordInput.type === 'password';
            passwordInput.type = isPassword ? 'text' : 'password';
            toggleIcon.classList.toggle('fa-eye');
            toggleIcon.classList.toggle('fa-eye-slash');
        });

        document.getElementById('loginForm').addEventListener('submit', function (e) {
            e.preventDefault();
            document.getElementById('loading-spinner').style.display = 'flex';
            document.getElementById('loginButton').disabled = true;
            setTimeout(() => {
                e.target.submit();
            }, 2000);
        });
    });
</script>

</body>
</html>
