<?php 

    $serverName = "localhost";
    $username = "u465459225_sibaku";
    $password = "Sibaku123";
    $dbName = "u465459225_bpjs";


    $conn = new mysqli($serverName, $username, $password, $dbName);

    if ($conn -> connect_error) {
        die("Koneksi gagal". $conn ->connect_error);
    } 
    // echo "Koneksi berhasil";
?>