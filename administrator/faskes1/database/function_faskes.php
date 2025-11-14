<?php
// menghubungkan ke database
$koneksi = mysqli_connect("localhost", "root", "", "bpjs");

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
               kt.keterangan
               
        FROM tb_faskes f
        JOIN tb_keperluan k ON f.id_keperluan = k.id_keperluan
        JOIN tb_lokasi l ON f.id_lokasi = l.id_lokasi
        JOIN tb_rs r ON f.id_rs = r.id_rs

        LEFT JOIN tb_status s ON f.id_status = s.id_status
        LEFT JOIN tb_keterangan kt ON f.id_keterangan = kt.id_keterangan
        ORDER BY f.id_faskes ASC
       

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
};

function cari($keyword) {
    global $koneksi;

    $query = "
        SELECT f.*, 
               k.keperluan, 
               l.lokasi, 
               r.fktp_dan_rumahsakit,
               r.kabupaten,
               s.status,
               kt.keterangan
               
        FROM tb_faskes f
        JOIN tb_keperluan k ON f.id_keperluan = k.id_keperluan
        JOIN tb_lokasi l ON f.id_lokasi = l.id_lokasi
        JOIN tb_rs r ON f.id_rs = r.id_rs
        
        LEFT JOIN tb_status s ON f.id_status = s.id_status
        LEFT JOIN tb_keterangan kt ON f.id_keterangan = kt.id_keterangan
        WHERE 
        f.nama_peserta LIKE '%$keyword%' OR
        f.email LIKE '%$keyword%' OR
        r.kabupaten LIKE '%$keyword%'

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
               kt.keterangan
               
        FROM tb_faskes f
        JOIN tb_keperluan k ON f.id_keperluan = k.id_keperluan
        JOIN tb_lokasi l ON f.id_lokasi = l.id_lokasi
        JOIN tb_rs r ON f.id_rs = r.id_rs
        LEFT JOIN tb_status s ON f.id_status = s.id_status
        LEFT JOIN tb_keterangan kt ON f.id_keterangan = kt.id_keterangan
        WHERE f.tanggal = '$tanggal'
        ORDER BY f.id_faskes ASC ";
    $result = mysqli_query($koneksi, $query);
    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
    return $rows;
}

?>
