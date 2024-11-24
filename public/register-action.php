<?php
session_start();
include('../config/db.php');
include('../public/functions.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $register = register($_POST['username'], $_POST['password'], $_POST['email']);
        if ($register == true) {
            login($_POST['username'], $_POST['password']);
        }
        header('Location: home.php');
        exit;
    }
}
