<?php
// menghubungkan ke database
$koneksi = mysqli_connect("localhost", "u465459225_sibaku", "Sibaku123", "u465459225_bpjs");


// $koneksi = mysqli_connect("sql111.byethost12.com", "b12_38946036", "9JAr?VH7unj#Bf#", "b12_38946036_db_faskes");

function ambilnilai() {
    global $koneksi;

    $query = "
        SELECT f.*, 
               k.keperluan, 
               l.lokasi, 
               r.fktp_dan_rumahsakit,
               r.kabupaten,
               s.status,
               kt.keterangan,
               -- Menentukan status update berdasarkan field yang diisi
               CASE 
                   WHEN f.id_status IS NOT NULL AND f.id_keterangan IS NOT NULL AND f.nama IS NOT NULL AND f.nama != '' THEN 'complete'
                   WHEN f.id_status IS NOT NULL OR f.id_keterangan IS NOT NULL OR (f.nama IS NOT NULL AND f.nama != '') THEN 'partial'
                   ELSE 'not_updated'
               END as update_status
               
        FROM tb_faskes f
        JOIN tb_keperluan k ON f.id_keperluan = k.id_keperluan
        JOIN tb_lokasi l ON f.id_lokasi = l.id_lokasi
        JOIN tb_rs r ON f.id_rs = r.id_rs

        LEFT JOIN tb_status s ON f.id_status = s.id_status
        LEFT JOIN tb_keterangan kt ON f.id_keterangan = kt.id_keterangan
        ORDER BY f.id_faskes DESC
    ";
    
    $result = mysqli_query($koneksi, $query);
    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }

    return $rows;
}

// function query
function query($query){
    global $koneksi;
    $result = mysqli_query($koneksi, $query);
    $rows = [];
    while( $row = mysqli_fetch_assoc($result)){
        $rows[] = $row;
    }
    return $rows;
}

// function input
function input($data){
    global $koneksi;
    //    ambil data dari tiap elemen form  
    $tanggal = htmlspecialchars($data['tanggal']);
    $keperluan = htmlspecialchars($data['keperluan']);
    $lokasi = htmlspecialchars($data['lokasi']);
    $rs = htmlspecialchars($data['rs']);
    $nama_peserta = htmlspecialchars($data['nama_peserta']);
    $nik = htmlspecialchars($data['nik']);
    $nomorhp = htmlspecialchars($data['nomorhp']);
    $email = htmlspecialchars($data['email']);
    $rujuk = htmlspecialchars($data['rujuk']);

    $cek_nomorhp = mysqli_query($koneksi, "SELECT * FROM tb_faskes WHERE nomorhp = '$nomorhp'");
    if(mysqli_num_rows($cek_nomorhp) > 0){
        return -1;
    }

    $cek_email = mysqli_query($koneksi, "SELECT * FROM tb_faskes WHERE email = '$email'");
    if (mysqli_num_rows($cek_email) > 0 ){
        return -2;
    }

    // query input data
    $query = "INSERT INTO 
    tb_faskes (tanggal, id_keperluan, id_lokasi, id_rs, nama_peserta, nik, nomorhp, email, rujuk)
    VALUES 
    ('$tanggal', '$keperluan', '$lokasi', '$rs', '$nama_peserta', '$nik', '$nomorhp', '$email', '$rujuk')";
    
    if (mysqli_query($koneksi, $query)){
        return 1;
    }else{
        return 0;
    }
}

function hapus($id) {
    global $koneksi;
    mysqli_query($koneksi, "DELETE From tb_faskes WHERE id_faskes = $id");
    return mysqli_affected_rows($koneksi);
}

function edit($data) {
    global $koneksi;
    //    ambil data dari tiap elemen form  
    $id = $data['id_faskes'];
    $tanggal = htmlspecialchars($data['tanggal']);
    $keperluan = htmlspecialchars($data['keperluan']);
    $lokasi = htmlspecialchars($data['lokasi']);
    $rs = htmlspecialchars($data['rs']);
    $nama_peserta = htmlspecialchars($data['nama_peserta']);
    $nik = htmlspecialchars($data['nik']);
    $nomorhp = htmlspecialchars($data['nomorhp']);
    $email = htmlspecialchars($data['email']);
    $rujuk = htmlspecialchars($data['rujuk']);

    // query edit data
    $query = "UPDATE 
    tb_faskes SET
    tanggal = '$tanggal',
    id_keperluan = '$keperluan',
    id_lokasi = '$lokasi',
    id_rs ='$rs',
    nama_peserta = '$nama_peserta',
    nik = '$nik',
    nomorhp = '$nomorhp',
    email = '$email',
    rujuk = '$rujuk'
    WHERE id_faskes = $id
    ";

    mysqli_query($koneksi, $query);
    return mysqli_affected_rows($koneksi);
}

// Fungsi baru untuk update status, keterangan, dan nama
function updateStatusKeteranganNama($id_faskes, $id_status = null, $id_keterangan = null, $nama = null) {
    global $koneksi;
    
    $updates = [];
    $params = [];
    
    if ($id_status !== null) {
        $updates[] = "id_status = ?";
        $params[] = $id_status;
    }
    
    if ($id_keterangan !== null) {
        $updates[] = "id_keterangan = ?";
        $params[] = $id_keterangan;
    }
    
    if ($nama !== null) {
        $updates[] = "nama = ?";
        $params[] = htmlspecialchars($nama);
    }
    
    if (empty($updates)) {
        return false;
    }
    
    $params[] = $id_faskes;
    
    $query = "UPDATE tb_faskes SET " . implode(", ", $updates) . " WHERE id_faskes = ?";
    
    $stmt = mysqli_prepare($koneksi, $query);
    if ($stmt) {
        $types = str_repeat('s', count($params) - 1) . 'i'; // semua string kecuali id_faskes (integer)
        mysqli_stmt_bind_param($stmt, $types, ...$params);
        mysqli_stmt_execute($stmt);
        $affected = mysqli_stmt_affected_rows($stmt);
        mysqli_stmt_close($stmt);
        return $affected > 0;
    }
    
    return false;
}

function cari($keyword) {
    global $koneksi;

    $query = "
        SELECT f.*, 
               k.keperluan, 
               l.lokasi, 
               r.fktp_dan_rumahsakit,
               r.kabupaten,
               s.status,
               kt.keterangan,
               -- Menentukan status update berdasarkan field yang diisi
               CASE 
                   WHEN f.id_status IS NOT NULL AND f.id_keterangan IS NOT NULL AND f.nama IS NOT NULL AND f.nama != '' THEN 'complete'
                   WHEN f.id_status IS NOT NULL OR f.id_keterangan IS NOT NULL OR (f.nama IS NOT NULL AND f.nama != '') THEN 'partial'
                   ELSE 'not_updated'
               END as update_status
               
        FROM tb_faskes f
        JOIN tb_keperluan k ON f.id_keperluan = k.id_keperluan
        JOIN tb_lokasi l ON f.id_lokasi = l.id_lokasi
        JOIN tb_rs r ON f.id_rs = r.id_rs
        
        LEFT JOIN tb_status s ON f.id_status = s.id_status
        LEFT JOIN tb_keterangan kt ON f.id_keterangan = kt.id_keterangan
        WHERE 
        f.nama_peserta LIKE '%$keyword%' OR
        f.email LIKE '%$keyword%' OR
        r.kabupaten LIKE '%$keyword%' OR
        r.fktp_dan_rumahsakit LIKE '%$keyword%'
    ";
    
    return query($query);
}

function filterTanggal($tanggal) {
    global $koneksi;
    $tanggal = mysqli_real_escape_string($koneksi, $tanggal); 
    $query = "
        SELECT f.*, 
               k.keperluan, 
               l.lokasi, 
               r.fktp_dan_rumahsakit,
               r.kabupaten,
               s.status,
               kt.keterangan,
               -- Menentukan status update berdasarkan field yang diisi
               CASE 
                   WHEN f.id_status IS NOT NULL AND f.id_keterangan IS NOT NULL AND f.nama IS NOT NULL AND f.nama != '' THEN 'complete'
                   WHEN f.id_status IS NOT NULL OR f.id_keterangan IS NOT NULL OR (f.nama IS NOT NULL AND f.nama != '') THEN 'partial'
                   ELSE 'not_updated'
               END as update_status
               
        FROM tb_faskes f
        JOIN tb_keperluan k ON f.id_keperluan = k.id_keperluan
        JOIN tb_lokasi l ON f.id_lokasi = l.id_lokasi
        JOIN tb_rs r ON f.id_rs = r.id_rs
        LEFT JOIN tb_status s ON f.id_status = s.id_status
        LEFT JOIN tb_keterangan kt ON f.id_keterangan = kt.id_keterangan
        WHERE f.tanggal = '$tanggal'
        ORDER BY f.id_faskes DESC ";
    $result = mysqli_query($koneksi, $query);
    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
    return $rows;
}

function ambilnilaiperminggu($startDate, $endDate) {
    global $koneksi;

    $query = "
        SELECT f.*, 
               k.keperluan, 
               l.lokasi, 
               r.fktp_dan_rumahsakit,
               r.kabupaten,
               s.status,
               kt.keterangan,
               -- Menentukan status update berdasarkan field yang diisi
               CASE 
                   WHEN f.id_status IS NOT NULL AND f.id_keterangan IS NOT NULL AND f.nama IS NOT NULL AND f.nama != '' THEN 'complete'
                   WHEN f.id_status IS NOT NULL OR f.id_keterangan IS NOT NULL OR (f.nama IS NOT NULL AND f.nama != '') THEN 'partial'
                   ELSE 'not_updated'
               END as update_status
        FROM tb_faskes f
        JOIN tb_keperluan k ON f.id_keperluan = k.id_keperluan
        JOIN tb_lokasi l ON f.id_lokasi = l.id_lokasi
        JOIN tb_rs r ON f.id_rs = r.id_rs
        LEFT JOIN tb_status s ON f.id_status = s.id_status
        LEFT JOIN tb_keterangan kt ON f.id_keterangan = kt.id_keterangan
        WHERE f.tanggal BETWEEN '$startDate' AND '$endDate'
        ORDER BY f.id_faskes DESC
    ";

    $result = mysqli_query($koneksi, $query);
    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }

    return $rows;
}

// FUNGSI PAGINATION BARU
function ambilnilaiDenganPagination($halaman = 1, $dataPerHalaman = 30) {
    global $koneksi;
    
    $mulai = ($halaman - 1) * $dataPerHalaman;
    
    $query = "
        SELECT f.*, 
               k.keperluan, 
               l.lokasi, 
               r.fktp_dan_rumahsakit,
               r.kabupaten,
               s.status,
               kt.keterangan,
               -- Menentukan status update berdasarkan field yang diisi
               CASE 
                   WHEN f.id_status IS NOT NULL AND f.id_keterangan IS NOT NULL AND f.nama IS NOT NULL AND f.nama != '' THEN 'complete'
                   WHEN f.id_status IS NOT NULL OR f.id_keterangan IS NOT NULL OR (f.nama IS NOT NULL AND f.nama != '') THEN 'partial'
                   ELSE 'not_updated'
               END as update_status
               
        FROM tb_faskes f
        JOIN tb_keperluan k ON f.id_keperluan = k.id_keperluan
        JOIN tb_lokasi l ON f.id_lokasi = l.id_lokasi
        JOIN tb_rs r ON f.id_rs = r.id_rs

        LEFT JOIN tb_status s ON f.id_status = s.id_status
        LEFT JOIN tb_keterangan kt ON f.id_keterangan = kt.id_keterangan
        ORDER BY f.id_faskes DESC
        LIMIT $mulai, $dataPerHalaman
    ";
    
    $result = mysqli_query($koneksi, $query);
    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
    
    return $rows;
}

function hitungTotalData() {
    global $koneksi;
    
    $query = "SELECT COUNT(*) as total FROM tb_faskes";
    $result = mysqli_query($koneksi, $query);
    $row = mysqli_fetch_assoc($result);
    
    return $row['total'];
}

function cariDenganPagination($keyword, $halaman = 1, $dataPerHalaman = 30) {
    global $koneksi;
    
    $mulai = ($halaman - 1) * $dataPerHalaman;
    $keyword = mysqli_real_escape_string($koneksi, $keyword);
    
    $query = "
        SELECT f.*, 
               k.keperluan, 
               l.lokasi, 
               r.fktp_dan_rumahsakit,
               r.kabupaten,
               s.status,
               kt.keterangan,
               -- Menentukan status update berdasarkan field yang diisi
               CASE 
                   WHEN f.id_status IS NOT NULL AND f.id_keterangan IS NOT NULL AND f.nama IS NOT NULL AND f.nama != '' THEN 'complete'
                   WHEN f.id_status IS NOT NULL OR f.id_keterangan IS NOT NULL OR (f.nama IS NOT NULL AND f.nama != '') THEN 'partial'
                   ELSE 'not_updated'
               END as update_status
               
        FROM tb_faskes f
        JOIN tb_keperluan k ON f.id_keperluan = k.id_keperluan
        JOIN tb_lokasi l ON f.id_lokasi = l.id_lokasi
        JOIN tb_rs r ON f.id_rs = r.id_rs
        
        LEFT JOIN tb_status s ON f.id_status = s.id_status
        LEFT JOIN tb_keterangan kt ON f.id_keterangan = kt.id_keterangan
        WHERE 
        f.nama_peserta LIKE '%$keyword%' OR
        f.email LIKE '%$keyword%' OR
        r.kabupaten LIKE '%$keyword%' OR
        r.fktp_dan_rumahsakit LIKE '%$keyword%'
        ORDER BY f.id_faskes DESC
        LIMIT $mulai, $dataPerHalaman
    ";
    
    $result = mysqli_query($koneksi, $query);
    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
    
    return $rows;
}

// Fungsi khusus untuk cari.php tanpa pagination (jika masih dibutuhkan)
function cariTanpaPagination($keyword) {
    global $koneksi;
    
    $query = "
        SELECT f.*, 
               k.keperluan, 
               l.lokasi, 
               r.fktp_dan_rumahsakit,
               r.kabupaten,
               s.status,
               kt.keterangan,
               -- Menentukan status update berdasarkan field yang diisi
               CASE 
                   WHEN f.id_status IS NOT NULL AND f.id_keterangan IS NOT NULL AND f.nama IS NOT NULL AND f.nama != '' THEN 'complete'
                   WHEN f.id_status IS NOT NULL OR f.id_keterangan IS NOT NULL OR (f.nama IS NOT NULL AND f.nama != '') THEN 'partial'
                   ELSE 'not_updated'
               END as update_status
        FROM tb_faskes f
        JOIN tb_keperluan k ON f.id_keperluan = k.id_keperluan
        JOIN tb_lokasi l ON f.id_lokasi = l.id_lokasi
        JOIN tb_rs r ON f.id_rs = r.id_rs
        LEFT JOIN tb_status s ON f.id_status = s.id_status
        LEFT JOIN tb_keterangan kt ON f.id_keterangan = kt.id_keterangan
        WHERE 
        f.nama_peserta LIKE '%$keyword%' OR
        f.email LIKE '%$keyword%' OR
        r.kabupaten LIKE '%$keyword%' OR
        r.fktp_dan_rumahsakit LIKE '%$keyword%'
        ORDER BY f.id_faskes DESC
    ";
    
    return query($query);
}

function hitungTotalDataCari($keyword) {
    global $koneksi;
    
    $keyword = mysqli_real_escape_string($koneksi, $keyword);
    
    $query = "
        SELECT COUNT(*) as total
        FROM tb_faskes f
        JOIN tb_keperluan k ON f.id_keperluan = k.id_keperluan
        JOIN tb_lokasi l ON f.id_lokasi = l.id_lokasi
        JOIN tb_rs r ON f.id_rs = r.id_rs
        WHERE 
        f.nama_peserta LIKE '%$keyword%' OR
        f.email LIKE '%$keyword%' OR
        r.kabupaten LIKE '%$keyword%' OR
        r.fktp_dan_rumahsakit LIKE '%$keyword%'
    ";
    
    $result = mysqli_query($koneksi, $query);
    $row = mysqli_fetch_assoc($result);
    
    return $row['total'];
}

function filterTanggalDenganPagination($tanggal, $halaman = 1, $dataPerHalaman = 30) {
    global $koneksi;
    
    $mulai = ($halaman - 1) * $dataPerHalaman;
    $tanggal = mysqli_real_escape_string($koneksi, $tanggal);
    
    $query = "
        SELECT f.*, 
               k.keperluan, 
               l.lokasi, 
               r.fktp_dan_rumahsakit,
               r.kabupaten,
               s.status,
               kt.keterangan,
               -- Menentukan status update berdasarkan field yang diisi
               CASE 
                   WHEN f.id_status IS NOT NULL AND f.id_keterangan IS NOT NULL AND f.nama IS NOT NULL AND f.nama != '' THEN 'complete'
                   WHEN f.id_status IS NOT NULL OR f.id_keterangan IS NOT NULL OR (f.nama IS NOT NULL AND f.nama != '') THEN 'partial'
                   ELSE 'not_updated'
               END as update_status
               
        FROM tb_faskes f
        JOIN tb_keperluan k ON f.id_keperluan = k.id_keperluan
        JOIN tb_lokasi l ON f.id_lokasi = l.id_lokasi
        JOIN tb_rs r ON f.id_rs = r.id_rs
        LEFT JOIN tb_status s ON f.id_status = s.id_status
        LEFT JOIN tb_keterangan kt ON f.id_keterangan = kt.id_keterangan
        WHERE f.tanggal = '$tanggal'
        ORDER BY f.id_faskes DESC
        LIMIT $mulai, $dataPerHalaman
    ";
    
    $result = mysqli_query($koneksi, $query);
    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
    
    return $rows;
}

function hitungTotalDataFilter($tanggal) {
    global $koneksi;
    
    $tanggal = mysqli_real_escape_string($koneksi, $tanggal);
    
    $query = "SELECT COUNT(*) as total FROM tb_faskes WHERE tanggal = '$tanggal'";
    $result = mysqli_query($koneksi, $query);
    $row = mysqli_fetch_assoc($result);
    
    return $row['total'];
}

// Fungsi untuk menghitung statistik update
function getUpdateStatistics() {
    global $koneksi;
    
    $query = "
        SELECT 
            COUNT(*) as total_records,
            SUM(CASE 
                WHEN id_status IS NOT NULL AND id_keterangan IS NOT NULL AND nama IS NOT NULL AND nama != '' 
                THEN 1 ELSE 0 
            END) as complete_updates,
            SUM(CASE 
                WHEN (id_status IS NOT NULL OR id_keterangan IS NOT NULL OR (nama IS NOT NULL AND nama != '')) 
                AND NOT (id_status IS NOT NULL AND id_keterangan IS NOT NULL AND nama IS NOT NULL AND nama != '')
                THEN 1 ELSE 0 
            END) as partial_updates,
            SUM(CASE 
                WHEN id_status IS NULL AND id_keterangan IS NULL AND (nama IS NULL OR nama = '') 
                THEN 1 ELSE 0 
            END) as not_updated
        FROM tb_faskes
    ";
    
    $result = mysqli_query($koneksi, $query);
    return mysqli_fetch_assoc($result);
}


?>
