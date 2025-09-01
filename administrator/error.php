<?php
date_default_timezone_set('Asia/Jakarta');

// === Waktu target nonaktif yang bisa kamu ubah manual ===
$waktuNonaktif = '2025-07-12'; // ubah ini sesuai kebutuhan

// Ambil waktu saat ini
$waktuSekarang = date('Y-m-d');

// Cek apakah sekarang sama dengan waktu nonaktif
if ($waktuSekarang == $waktuNonaktif) {
    // Keluar dari PHP agar bisa menampilkan HTML
    ?>
    <!DOCTYPE html>
    <html lang="id">
    <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title>Oops! Halaman Tidak Ditemukan</title>
      <style>
        body {
          background: #f8f9fa;
          font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
          margin: 0;
          padding: 0;
          display: flex;
          justify-content: center;
          align-items: center;
          height: 100vh;
          text-align: center;
        }

        .error-container {
          max-width: 500px;
          padding: 30px;
          background: #ffffff;
          box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
          border-radius: 20px;
        }

        .error-code {
          font-size: 96px;
          font-weight: bold;
          color: #ff6b6b;
          margin: 0;
        }

        .error-message {
          font-size: 22px;
          margin: 20px 0;
          color: #333;
        }

        .btn-home {
          display: inline-block;
          padding: 12px 24px;
          background-color: #4dabf7;
          color: white;
          border-radius: 8px;
          text-decoration: none;
          font-weight: bold;
          transition: 0.3s;
        }

        .btn-home:hover {
          background-color: #339af0;
        }

        .emoji {
          font-size: 48px;
          margin-bottom: 10px;
        }
      </style>
    </head>
    <body>
      <div class="error-container">
        <div class="emoji">😕</div>
        <h1 class="error-code">404</h1>
        <p class="error-message">Oops! Halaman ini sedang dinonaktifkan sementara.</p>
      </div>
    </body>
    </html>
    <?php
    exit(); // Penting agar sisa kode PHP tidak dieksekusi
}
?>
