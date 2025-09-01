<?php 
session_start(); 
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: ../index.php');
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="../faskes/assets/favicon.ico">
    <link href="../../css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="../../fontawesome/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
    <title>Tambah user</title>
</head>
<body class="bg-light">\
    <!-- <h1 id="main_title" class="">TAMBAH USER</h1> -->
    <div class="container-fluid">