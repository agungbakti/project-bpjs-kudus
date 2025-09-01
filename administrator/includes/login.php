<?php

require 'koneksi.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $login = mysqli_real_escape_string($conn, $_POST['login']);
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE email = '$login' OR username = '$login'";
    $sql = mysqli_query($conn, $query);

    $result = mysqli_fetch_assoc($sql);

    if ($result) {
        if ($result['verify_status'] == 1) {
            if (password_verify($password, $result['password'])) {
                $_SESSION['status'] = "success, login berhasil";
                $_SESSION['user'] = [
                    'id_user' => $result['id_user'],
                    'username' => $result['username'],
                    'role' => $result['role']
                ];


                // Redirect berdasarkan role
                if ($result['role'] === 'admin') {
                    header('Location: ../admin/home.php');
                } else {
                    header('Location: ../faskes3/dashboard/faskes.php');
                }
                exit;
            } else {
                $_SESSION['status'] = "danger, password tidak sesuai";
                header('Location: ../login.php');
            }
        } else {
            $_SESSION['status'] = "danger, silahkan verifikasi email!!, <a href='user/verify_ulang.php'>verifikasi ulang?</a>";
            header('Location: ../login.php');
        }
    } else {
        $_SESSION['status'] = "danger, email/username tidak sesuai";
        header('Location: ../login.php');
    }
}
