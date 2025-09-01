<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Formulir Pendaftaran</title>
  <link rel="stylesheet" href="../css/bootstrap.min.css">
  <link rel="stylesheet" href="../fontawesome/css/all.min.css">
  <script src="../js/bootstrap.bundle.min.js"></script>
  <link rel="icon" type="image/x-icon" href="../assets/favicon.ico">
  <style>
    body {
      height: 100vh;
      display: flex;
      align-items: center;
      /* background-color: #f5f5f5; */
      background-image: url('../assets/gambar1.jpg');
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
      backdrop-filter: blur(3px);
    }

    .card {
      max-width: 400px;
      margin: 20px auto;
      padding: 0px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      border-radius: 10px;
    }

    .overlay-spinner {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      display: flex;
      align-items: center;
      justify-content: center;
      z-index: 9999;
    }

    .form-floating i {
      position: absolute;
      top: 50%;
      right: 15px;
      transform: translateY(-50%);
      opacity: 0.5;
      cursor: default;
    }
  </style>
</head>

<body>

  <div class="container-fluid">
    <div class="card">
      <div class="card-body">
        <form id="waForm">
          <h3 class="card-title fw-normal text-center pb-2">Formulir Pendaftaran</h3>

          <!-- SPINNER -->
          <div id="loading-spinner" style="display: none;" class="overlay-spinner">
            <div class="spinner-border" style="width: 3rem; height: 3rem;" role="status">
              <span class="visually-hidden">Loading...</span>
            </div>
          </div>

          <div class="form-floating mb-3 position-relative">
            <input type="text" class="form-control" id="nama" placeholder="Masukkan nama" autocomplete="off" required>
            <label for="nama">Nama Faskes</label>
            <i class="fa-solid fa-hospital"></i>
          </div>

          <div class="form-floating mb-3 position-relative">
            <input type="email" class="form-control" id="email" placeholder="Masukkan email" autocomplete="off" required>
            <label for="email">Email</label>
            <i class="fa-solid fa-envelope"></i>
          </div>

          <div class="form-floating mb-3 position-relative">
            <input type="text" class="form-control" id="hp" placeholder="Masukkan nomor HP" autocomplete="off" required>
            <label for="hp">Nomor HP (WhatsApp)</label>
            <i class="fa-brands fa-whatsapp"></i>
          </div>

          <div class="form-floating mb-3 position-relative">
            <!-- <input type="text" class="form-control" id="keperluan" placeholder="Keperluan" autocomplete="off" required> -->
            <select class="form-control" name="keperluan" id="keperluan" required>
              <option selected disabled value="">Pilih keperluan</option>
              <option value="Pembuatan akun faskes">Pembuatan akun faskes</option>
              <option value="Ganti Password">Ganti Password</option>
              <option value="Ganti email/nohp">Ganti email/nohp</option>
            </select>
            <label for="keperluan">Keperluan</label>
            <i class="fa-solid fa-clipboard-list"></i>
          </div>
          <button type="submit" id="kirimBtn" class="btn btn-success w-100 mb-0">
            <i class="fab fa-whatsapp"></i> Kirim ke WhatsApp
          </button>

          <p class="text-center mt-2">
            <small>Sudah punya akun? <a href="../login.php">Masuk di sini</a></small>
          </p>
          <p class="text-muted text-center m-0 p-0">&copy; 2025</p>
        </form>
      </div>
    </div>
  </div>

  <script>
    document.getElementById('waForm').addEventListener('submit', function (e) {
      e.preventDefault();

      // Tampilkan spinner & disable tombol
      document.getElementById('loading-spinner').style.display = 'flex';
      document.getElementById('kirimBtn').disabled = true;

      const nama = document.getElementById('nama').value.trim();
      const email = document.getElementById('email').value.trim();
      const hp = document.getElementById('hp').value.trim();
      const keperluan = document.getElementById('keperluan').value.trim();

      const nomorAdmin = '6281226654063';
      const pesan = `Halo Admin, saya ingin mendaftar:\n\nNama: ${nama}\nEmail: ${email}\nNo HP: ${hp}\nKeperluan: ${keperluan}`;
      const url = `https://wa.me/${nomorAdmin}?text=${encodeURIComponent(pesan)}`;

      setTimeout(() => {
        window.open(url, '_blank');
        document.getElementById('loading-spinner').style.display = 'none';
        document.getElementById('kirimBtn').disabled = false;
      }, 2000); // tampilkan spinner selama 2 detik
    });
  </script>

</body>

</html>
