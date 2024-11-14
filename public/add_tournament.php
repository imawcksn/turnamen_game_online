<?php
session_start();
include('../public/functions.php');

if ($_SESSION['user']['role'] !== 'admin') {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    create_tournament($_POST['name'], $_POST['description'], $_POST['schedule'], $_POST['livestream']);
    header('Location: admin-dashboard.php');
    exit;
}

include('../includes/header.php');
?>
<div class="wrapper mt-5">
    <form method="post">
        <input type="text" name="name" placeholder="Nama Turnamen" required>
        <textarea name="description" placeholder="Deskripsi"></textarea>
        <input type="date" name="schedule" required>
        <input type="text" name="livestream" placeholder="Livestreaming URL">
        <button type="submit">Simpan</button>
    </form>
</div>