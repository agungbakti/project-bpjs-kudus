<?php session_start(); ?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>BPJS KESEHATAN KUDUS</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="style/index.css">
  <link rel="icon" type="image/x-icon" href="favicon.ico">
  <link rel="shortcut icon" href="assets/ikon_bpjs.png" type="image/png">

</head>

<body>
  <?php include "dashboard/navbar.php"; ?>

  <!-- home -->
  <section id="home" class="hero-section dashboard py-5 d-flex align-items-center mt-3 ">
    <div class="overlay"></div>
    <div class="container">
      <div class="row align-items-center justify-content-between">

        <div class="teks col-md-6 content-box ">
          <h1 class="text-start mb-4 text-white">Selamat Datang di Website BPJS KESEHATAN KANTOR CABANG KUDUS</h1>
        </div>

        <div class="teks col-md-6 mb-3 content-box text-white" style="text-align: justify;">
          <p style="font-family: Inter, sans-serif" class="lead fw-semibold"><b>Layanan Sistem
              Administrasi, Booking Antrean Online & Manajemen
              Data Fasilitas Kesehatan</b></p>
          <p style="font-family: Inter, sans-serif;" class="lead fw-semibold">Tentukan nomer antrian dan manajemen data fasilitas
            kesehatan anda secara mudah dan cepat yang bisa
            diakses dimana saja</p>
          <button type="button" class="btn btn-primary">Ambil Antrean</button>
        </div>

      </div>
    </div>
  </section>

  <!-- profil -->
  <section id="profil" class="py-5 px-3 px-md-5">
    <div class="container" style="margin-top: 35px;">
      <div class="card">
      <div class="row">

        <div class="col-md-3 col-12 mb-3">
          <div class="nav flex-column nav-pills" id="profil-tab" role="tablist" aria-orientation="vertical">
            <button class="nav-link active" id="visi-tab" data-bs-toggle="pill" data-bs-target="#visi" type="button"
              role="tab">Visi dan Misi</button>

            <button class="nav-link" id="sejarah-tab" data-bs-toggle="pill" data-bs-target="#sejarah" type="button"
              role="tab">Sejarah</button>

            <button class="nav-link" id="struktur-tab" data-bs-toggle="pill" data-bs-target="#tugasfungsi" type="button"
              role="tab">Tugas & Fungsi</button>
          </div>
        </div>

        <div class="col-md-9 col-12">
          <div class="tab-content" id="profil-tabContent">

            <!-- VISI & MISI -->
            <div class="tab-pane fade show active" id="visi" role="tabpanel" aria-labelledby="visi-tab">
              <div class="card p-4">
                <h3><span style="color: blue;">VISI & MISI</span> BPJS KESEHATAN</h3>
                <h5>VISI</h5>
                <p style="text-align: justify;">Menjadi badan penyelenggara yang <b>dinamis</b>, <b>akuntabel</b>, dan
                  <b>tepercaya</b> untuk
                  mewujudkan jaminan kesehatan yang <b>berkualitas</b>, <b>berkelanjutan</b>, <b>berkeadilan</b>, dan
                  <b>inklusif</b>.
                </p>

                <h5>MISI</h5>
                <ol>
                  <li>Meningkatkan kualitas layanan kepada peserta melalui layanan terintegrasi berbasis teknologi
                    informasi.</li>
                  <li>Menjaga keberlanjutan Program JKN-KIS dengan menyeimbangkan antara dana jaminan sosial dan biaya
                    manfaat yang terkendali.</li>
                  <li>Memberikan jaminan kesehatan yang berkeadilan dan inklusif mencakup seluruh penduduk Indonesia.
                  </li>
                  <li>Memperkuat engagement dengan meningkatkan sinergi dan kolaborasi pemangku kepentingan dalam
                    mengimplementasikan program JKN-KIS.</li>
                  <li>Meningkatkan kapabilitas Badan dalam menyelenggarakan Program JKN-KIS secara efisien dan efektif
                    yang akuntabel, berkehati-hatian dengan prinsip tata kelola yang baik, SDM yang produktif,
                    mendorong
                    transformasi digital serta inovasi yang berkelanjutan.</li>
                </ol>
              </div>
            </div>

            <!-- SEJARAH -->
            <div class="tab-pane fade" id="sejarah" role="tabpanel" aria-labelledby="sejarah-tab">
              <div class="card p-4">
                <h3><span style="color: blue;">SEJARAH</span> BPJS KESEHATAN</h3>
                <p style="text-align: justify;">BPJS Kesehatan mulai resmi beroperasi pada 1 Januari 2014. Dasar
                  pendirian beroperasinya BPJS Kesehatan adalah pada tahun 2004 pemerintah mengeluarkan UU Nomor 40
                  Tahun 2004 tentang Sistem Jaminan Sosial Nasional (SJSN) dan kemudian pada tahun 2011 pemerintah
                  menetapkan UU Nomor 24 Tahun 2011 tentang Badan Penyelenggara Jaminan Sosial (BPJS). PT Askes
                  (Persero) ditunjuk sebagai penyelenggara program jaminan sosial di bidang kesehatan, sehingga PT
                  Askes
                  (Persero) pun bertransformasi menjadi BPJS Kesehatan. <br> <br>

                  Namun, cikal bakal jaminan pemeliharaan kesehatan di Indonesia sebenarnya sudah dimulai sejak zaman
                  kolonial Belanda. Setelah kemerdekaan, pada tahun 1949, setelah pengakuan kedaulatan oleh Pemerintah
                  Belanda, upaya untuk menjamin kebutuhan pelayanan kesehatan bagi masyarakat, khususnya pegawai
                  negeri
                  sipil beserta keluarga, tetap dilanjutkan. Prof. G.A. Siwabessy, selaku Menteri Kesehatan yang
                  menjabat pada saat itu. Ia mengajukan sebuah gagasan untuk perlu segera menyelenggarakan program
                  asuransi kesehatan semesta (Universal Health Coverage) yang saat itu mulai diterapkan di banyak
                  negara
                  maju dan tengah berkembang pesat. Kondisi saat itu, kepesertaannya baru mencakup pegawai negeri
                  sipil
                  beserta anggota keluarganya saja. Namun Siwabessy yakin suatu hari nanti, klimaks dari pembangunan
                  derajat kesehatan masyarakat Indonesia akan tercapai melalui suatu sistem yang dapat menjamin
                  kesehatan seluruh warga bangsa ini.<br><br>

                  Pada 1968, pemerintah menerbitkan Peraturan Menteri Kesehatan Nomor 1 Tahun 1968 dengan membentuk
                  Badan Penyelenggara Dana Pemeliharaan Kesehatan (BPDPK) yang mengatur pemeliharaan kesehatan bagi
                  pegawai negara dan penerima pensiun beserta keluarganya. Hal ini sebagai tindak lanjut dari
                  munculnya
                  Keputusan Presiden Republik Indonesia No. 230 Tahun 1968 Tentang Peraturan Pemelihaaraan Kesehatan
                  Pegawai Negeri, Penerima Pensiun serta anggota keluarganya, pada tanggal 15 Juli 1968. Atas dasar
                  tersebut, maka tanggal 15 Juli 1968 dimaknai sebagai hari lahir BPDPK yang merupakan cikal bakal
                  BPJS
                  Kesehatan sebagai penyelenggara Program Jaminan Kesehatan Nasional. <br> <br>

                  Dalam perjalanan BPDPK, Pemerintah saat itu menginginkan cakupan kepesertaan terus diperluas dan
                  tidak
                  berhenti sampai pada pemeliharaan kesehatan pegawai negeri saja. Selain itu skema BPDPK yang masih
                  menganut sistem fee for service dirasa memberatkan dana jaminan kesehatan saat itu. Pemerintah
                  akhirnya menerbitkan Peraturan Pemerintah Nomor 22 dan 23 Tahun 1984. Atas dasar tersebut, BPDPK
                  berubah status dari sebuah badan penyelenggara yang berada di lingkungan Departemen Kesehatan
                  menjadi
                  Badan Usaha Milik Negara (BUMN), yakni Perum Husada Bhakti (PHB). PHB bertugas meningkatkan program
                  jaminan dan pemeliharaan kesehatan bagi para peserta yang terdiri dari PNS, TNI/Polri, pensiunan,
                  veteran, perintis kemerdekaan, dan anggota keluarga mereka. Harapannya PHB sebegai perusahaan dapat
                  dikelola secara lebih profesional mengelola sebuah program asuransi, dalam hal ini asuransi
                  sosial.<br><br>

                  Di era PHB, perusahaan ini terus memperkuat sistem dan program yang berkiblat pada prinsip
                  pengelolaan
                  asuransi sosial. Misalnya diterapkan konsep managed care, dengan sistem ini diharapkan pelayanan
                  kesehatan bermutu diberikan kepada peserta dengan biaya yang efektif dan efisien. Di era ini sistem
                  klaim perserorangan dan fee for service dihapus, dan mulai diterapkan sistem kapitasi di Puskesmas
                  dan
                  sistem paket di rumah sakit. PHB juga memperkuat sistem rujukan, menerapkan konsep dokter keluarga,
                  dan pertama kali menerapkan konsep Daftar Plafon Harga Obat (DPHO) yang menjadi cikal bakal
                  Formularium Nasional yang saat ini digunakan dalam Program JKN. Alhasil di era PHB, perusahaan ini
                  mengalami penghematan dana jaminan kesehatan yang sebelumnya tidak bisa dilakukan saat masih menjadi
                  BPDPK.<br><br>

                  Kinerja PHB yang baik, menginisiasi pemerintah untuk memperluas ruang gerak PHB melalui pelbagai
                  program. Melalui Peraturan Pemerintah Nomor 6 Tahun 1992, PHB berubah menjadi PT Askes (Persero),
                  selain peserta existing, cakupan kepesertaannya mulai menjangkau karyawan BUMN melalui Program Askes
                  Komersial. Pada Januari 2005, PT Askes (Persero) dipercaya pemerintah untuk melaksanakan Program
                  Jaminan Kesehatan bagi Masyarakat Miskin (PJKMM). Program ini kemudian dikenal menjadi Program
                  Askeskin dengan sasaran peserta masyarakat miskin dan tidak mampu sebanyak 60 juta jiwa. PT Askes
                  (Persero) juga menciptakan Program Jaminan Kesehatan Masyarakat Umum (PJKMU) bekerjasama dengan
                  Pemerintah Daerah, yang ditujukan bagi masyarakat yang belum dilayani oleh Jamkesmas, Askes Sosial,
                  maupun asuransi swasta.<br>

                  Sebelum bertransformasi menjadi BPJS Kesehatan, cakupan kepesertaan PT Askes (Persero) sudah
                  mencapai
                  lebih dari 76 juta jiwa serta jumlah fasilitas kesehatan yang bekerja sama terus meningkat, cakupan
                  manfaat pun semakin luas termasuk menjamin penyakit berbiaya katastropik. PT Askes (Persero) juga
                  terus mempersiapkan diri untuk memperkuat SDM, infrastruktur dan sistem informasi manajemen dalam
                  rangka bertransformasi menjadi BPJS Kesehatan sebagai komitmen dalam implementasi UU SJSN dan UU
                  BPJS
                  yang harus diimplementasikan pada 1 Januari 2014. Hal inilah yang menjadi cikal bakal pengelolaan
                  Program Jaminan Kesehatan Nasional bagi seluruh rakyat Indonesia, yang dikelola oleh BPJS Kesehatan.
                  .
                </p>
              </div>
            </div>

            <!-- tugas fungsi -->
            <div class="tab-pane fade" id="tugasfungsi" role="tabpanel" aria-labelledby="struktur-tab">
              <div class="card p-4" style="text-align: justify;">
                <h3><span style="color: blue;">TUGAS & FUNGSI </span>BPJS KESEHATAN</h3>
                <p class="mt-3">Mengacu kepada Undang-Undang Nomor 24 Tahun 2011 tentang Badan Penyelenggara Jaminan
                  Sosial, fungsi, Tugas dan Kewenangan BPJS Kesehatan sebagai berikut:</p>
                <p>Fungsi:</p>
                <p>BPJS Kesehatan berfungsi menyelenggarakan program jaminan kesehatan. Dijelaskan dalam Undang-Undang
                  Nomor 40 Tahun 2004 tentang Sistem Jaminan Sosial Nasional bahwa Jaminan kesehatan diselenggarakan
                  secara nasional berdasarkan prinsip asuransi sosial dan prinsip ekuitas, dengan tujuan menjamin agar
                  peserta memperoleh manfaat pemeliharaan kesehatan dan perlindungan dalam memenuhi kebutuhan dasar
                  kesehatan.</p>

                <ol>
                  <li>Melakukan dan/atau menerima pendaftaran peserta.</li>
                  <li>Memungut dan mengumpulkan iuran dari peserta dan pemberi kerja.</li>
                  <li>Menerima bantuan iuran dari pemerintah.</li>
                  <li>Mengelola Dana Jaminan Sosial untuk kepentingan peserta.</li>
                  <li>Mengumpulkan dan mengelola data peserta program jaminan sosial.</li>
                  <li>Membayarkan manfaat dan/atau membiayai pelayanan kesehatan sesuai dengan ketentuan program
                    jaminan sosial.</li>
                  <li>Memberikan informasi mengenai penyelenggaraan program jaminan sosial kepada peserta dan
                    masyarakat.</li>
                </ol>

                <p>Kewenangan BPJS Kesehatan:</p>
                <ol>
                  <li>menagih pembayaran Iuran;</li>
                  <li>menempatkan Dana Jaminan Sosial untuk investasi jangka pendek dan jangka panjang dengan
                    mempertimbangkan aspek likuiditas, solvabilitas, kehati-hatian, keamanan dana, dan hasil yang
                    memadai; </li>
                  <li>melakukan pengawasan dan pemeriksaan atas kepatuhan Peserta dan Pemberi Kerja dalam memenuhi
                    kewajibannya sesuai dengan ketentuan peraturan perundang-undangan jaminan sosial nasional; </li>
                  <li>membuat kesepakatan dengan fasilitas kesehatan mengenai besar pembayaran fasilitas kesehatan
                    yang mengacu pada standar tarif yang ditetapkan oleh Pemerintah;</li>
                  <li>membuat atau menghentikan kontrak kerja dengan fasilitas kesehatan; </li>
                  <li>mengenakan sanksi administratif kepada Peserta atau Pemberi Kerja yang tidak memenuhi
                    kewajibannya; </li>
                  <li>melaporkan Pemberi Kerja kepada instansi yang berwenang mengenai ketidakpatuhannya dalam
                    membayar Iuran atau dalam memenuhi kewajiban lain sesuai dengan ketentuan peraturan
                    perundangundangan; dan</li>
                  <li>melakukan kerja sama dengan pihak lain dalam rangka penyelenggaraan program Jaminan Sosial.</li>
                </ol>

              </div>
            </div>



          </div>
        </div>
      </div>
      </div>
    </div>
  </section>

  <!-- panduan -->
  <section id="panduan">
    <div class="container" style="padding: 40px;" id="panduan">
      <h2 class="text-center mb-4">Alur Pendaftaran Mobile JKN</h2>
      <div id="carouselExampleDark" class="carousel carousel-dark slide carousel-fade">
        <div class="carousel-indicators">
          <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="0" class="active"
            aria-current="true" aria-label="Slide 1"></button>
          <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="1"
            aria-label="Slide 2"></button>
          <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="2"
            aria-label="Slide 3"></button>
        </div>

        <div class="carousel-inner">
          <div class="carousel-item active" data-bs-interval="10000">
            <img src="assets/input.png" class="d-block w-100 rounded shadow" alt="...">
            <div class="carousel-caption d-block text-dark p-3 rounded shadow  mx-auto text-center">
              <h5 class="fw-bold">Form Input</h5>
              <p>Faskes meng-inputkan data peserta yang akan dibuatkan akun Mobile JKN</p>
            </div>
          </div>
          <div class="carousel-item" data-bs-interval="2000">
            <img src="assets/admin.png" class="d-block w-100 rounded shadow" alt="...">
            <div class="carousel-caption d-block text-dark p-3 rounded shadow  mx-auto">
              <h5 class="fw-bold">Halaman Admin</h5>
              <p>Kemudian data yang diinputkan akan masuk ke halaman admin yang selanjutnya akan diproses</p>
            </div>
          </div>
          <div class="carousel-item">
            <img src="assets/faskes.png" class="d-block w-100 rounded shadow" alt="...">
            <div class="carousel-caption d-block text-dark p-3 rounded shadow  mx-auto">
              <h5 class="fw-bold">Halaman Faskes</h5>
              <p>Faskes bisa melihat status keberhasilan di halaman faskes secara real time</p>
            </div>
          </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleDark" data-bs-slide="prev">
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleDark" data-bs-slide="next">
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Next</span>
        </button>

      </div>
    </div>
  </section>

  <!-- kontak kami -->
  <section id="kontak" class="py-5">

    <div class="container py-5">
      <h2 class="text-center mb-4" style="margin-bottom: 2rem;">Kontak Kami</h2>

      <div class="row g-4">

        <!-- Informasi Kontak -->
        <div class="col-md-6">
          <div class="card shadow-sm rounded-4 p-4">
            <h5 class="text-center mb-3">Hubungi kami di bawah ini!</h5>
            <div class="mb-3">
              <a href="https://www.google.com/maps/place/BPJS+Kesehatan+Kantor+Cabang+Kudus/@-6.814001,110.858418,3123m/data=!3m1!1e3!4m6!3m5!1s0x2e70c4d729d29e01:0xca2a7affb095bab9!8m2!3d-6.8156718!4d110.8601645!16s%2Fg%2F1hm42b72v?hl=en-US&entry=ttu&g_ep=EgoyMDI1MDUxNS4wIKXMDSoASAFQAw%3D%3D"
                target="_blank" class="text-decoration-none text-dark">
                <i class="bi bi-geo-alt-fill me-2 text-primary"></i>
                <strong>Alamat</strong>
                <p class="mb-0 ms-4">Komplek Perkantoran, Jl. Mejobo, RT.01/RW.01, Mlati Kidul, Kec. Kota Kudus,
                  Kabupaten Kudus, Jawa Tengah 59319, Indonesia</p>
            </div>

            <div class="mb-3">
              <a href="https://wa.me/628118165165" target="_blank" class="text-decoration-none text-dark">
                <i class="bi bi-whatsapp me-2 text-success"></i>
                <strong>WhatsApp:</strong>
                <p class="mb-0 ms-4">+62 8118 165 165</p>
              </a>
            </div>

            <div class="mb-3 mt-3 text-center">
              <h5>Jam Operasional</h5>
              <p>Senin - Jumat: 08.00 - 15.00 WIB</p>
            </div>

          </div>
        </div>

        <div class="col-md-6">
          <div class="card shadow-sm rounded-4 p-3 h-100">
            <iframe
              src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d435118.2166714666!2d110.860164!3d-6.815672!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e70c4d729d29e01%3A0xca2a7affb095bab9!2sBPJS%20Kesehatan%20Kantor%20Cabang%20Kudus!5e1!3m2!1sen!2sus!4v1747796974107!5m2!1sen!2sus"
              width="100%" height="250" style="border:0;" allowfullscreen="" loading="lazy"
              referrerpolicy="no-referrer-when-downgrade"></iframe>

            <div class="mb-3 d-flex flex-wrap justify-content-center gap-2 mt-4 fs-6">
              <a href="https://www.tiktok.com/@bpjskesehatan_ri" target="_blank"
                class="text-dark text-decoration-none d-flex align-items-center gap-1" aria-label="TikTok">
                <i class="bi bi-tiktok"></i>
                <strong>Tiktok</strong>
              </a>


              <a href="https://www.facebook.com/@BPJSKesehatanRI" target="_blank"
                class="text-dark text-decoration-none d-flex align-items-center gap-1" aria-label="Facebook">
                <i class="bi bi-facebook"></i>
                <strong>Facebook</strong>
              </a>

              <a href="https://www.twitter.com/@BPJSKesehatanRI" target="_blank"
                class="text-dark text-decoration-none d-flex align-items-center gap-1" aria-label="Twitter">
                <i class="bi bi-twitter"></i>
                <strong>Twitter</strong>
              </a>

              <a href="https://www.instagram.com/@jknkudusbermanfaat" target="_blank"
                class="text-dark text-decoration-none d-flex align-items-center gap-1" aria-label="Instagram">
                <i class="bi bi-instagram"></i>
                <strong>Instagram</strong>
              </a>

              <a href="https://www.youtube.com/@BPJSKesehatan_RI" target="_blank"
                class="text-dark text-decoration-none d-flex align-items-center gap-1" aria-label="Youtube">
                <i class="bi bi-youtube"></i>
                <strong>Youtube</strong>
              </a>
            </div>
          </div>
        </div>



      </div>
    </div>
  </section>




  <footer class="text-center mt-5 mb-2 text-muted" style="font-size: 0.9rem;">
    &copy; 2025 BPJS Kesehatan Kudus | Magang UMKU
  </footer>


  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO"
    crossorigin="anonymous"></script>
</body>

</html>