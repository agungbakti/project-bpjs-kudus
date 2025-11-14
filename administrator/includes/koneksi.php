<?php 

    $serverName = "localhost";
    $username = "root";
    $password = "";
    $dbName = "bpjs";


    $conn = new mysqli($serverName, $username, $password, $dbName);

    if ($conn -> connect_error) {
        die("Koneksi gagal". $conn ->connect_error);
    } 
    // echo "Koneksi berhasil";
?>
