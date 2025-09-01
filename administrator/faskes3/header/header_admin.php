<link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css">
<link rel="stylesheet" href="../fontawesome/css/all.min.css">
<script src="../js/bootstrap.bundle.min.js"></script>
<style>
    .navbar-nav .nav-link:hover {
        background-color: #e0e0e0;
        border-radius: 5px;
        color: black !important;
    }

    .btn-close {
        filter: invert(1);
    }

    @media (min-width: 768px) {
        #offcanvasNavbar {
            width: 300px;
        }

        .logo {
            width: 200px;
        }
    }

    @media (max-width: 767px) {
        #offcanvasNavbar {
            width: 50%;
        }

        .logo {
            width: 200px;
        }
    }
</style>
<div class="z-1">
    <!-- navbar bootstrap -->
    <nav class="navbar fixed-top shadow-sm p-2 mb-4 bg-body-tertiary rounded">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <img src="https://upload.wikimedia.org/wikipedia/commons/b/b4/BPJS_Kesehatan_logo.svg" alt="img-bpjskes" class="logo">
            <div class="offcanvas offcanvas-start text-bg-dark " tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title" id="offcanvasNavbarLabel">Beranda</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                    <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                        <li class="nav-item">
                            <a class="nav-link text-white" aria-current="page" href="../../admin/home.php">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="../../admin/antrian.php">Antrian</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="../../faskes3/dashboard/admin.php">Data Mobile JKN</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="../../admin/uploadExcel.php">Hitung</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="../../admin/index.php">Tambah User</a>
                        </li>
                            <li class="nav-item">
                            <a class="nav-link text-white" href="../../admin/hari-libur.php">Tambah Libur</a>
                        </li>
                        <li class="nav-item">
                            <!-- Trigger modal -->
                            <a class="btn btn-danger mt-2" data-bs-toggle="modal" data-bs-target="#logoutModal">Logout</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
    <!-- end navbar -->
    <!-- Modal Logout -->
    <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="logoutModalLabel">Konfirmasi Logout</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin logout?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <form method="POST" action="../includes/logout.php">
                        <button type="submit" class="btn btn-danger">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>