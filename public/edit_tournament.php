<?php
session_start();
include('../public/functions.php');

// Pengecekan role admin
if ($_SESSION['user']['role'] !== 'admin') {
    header('Location: index.php');
    exit;
}

// Ambil ID turnamen dari URL
if (!isset($_GET['id'])) {
    header('Location: dashboard_admin.php');
    exit;
}

$tournament_id = $_GET['id'];
$tournament = getTournamentById($tournament_id); // Fungsi untuk mengambil data turnamen berdasarkan ID

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Update data turnamen
    updateTournament($tournament_id, $_POST['name'], $_POST['description'], $_POST['schedule'], $_POST['livestream']);
    header('Location: admin-dashboard.php');
    exit;
}

include('../includes/header.php');
?>

<div class="wrapper mt-5">
    <form method="post">
        <input type="text" name="name" value="<?= htmlspecialchars($tournament['name']); ?>" required>
        <textarea name="description"><?= htmlspecialchars($tournament['description']); ?></textarea>
        <input type="date" name="schedule" value="<?= htmlspecialchars($tournament['schedule']); ?>" required>
        <input type="text" name="livestream" value="<?= htmlspecialchars($tournament['livestream']); ?>">
        <button type="submit">Update</button>
    </form>
</div>