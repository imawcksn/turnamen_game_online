<?php
session_start();
include('../config/db.php');
include('../public/functions.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (login($username, $password)) {
        // Redirect sesuai peran pengguna
        var_dump($_SESSION['user']['role']);
        if ($_SESSION['user']['role'] === 'admin') {
            header('Location: admin-dashboard.php');
        } else {
            header('Location: home.php');
        }
        exit;
    } else {
        // Jika login gagal, kembali ke halaman login dengan pesan error
        header('Location: home.php');
        exit;
    }
}
