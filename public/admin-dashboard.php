<?php
include('../includes/header.php');
include('../public/functions.php');


if ($_SESSION['user']['role'] !== 'admin') {
    header('Location: index.php');
    exit;
}

$tournaments = getTournaments();
?>
<h1>Dashboard Admin</h1>
<div class="d-flex justify-content-end w-full">
    <a href="add_tournament.php" class="btn btn-primary me-5">Tambah Turnamen</a>
</div>
<table class="table mx-5 px-5" border="1">
    <th>Nama Turnamen</th>
    <th>Tanggal</th>
    <th>Action</th>
    </tr>
    <?php while ($row = $tournaments->fetch_assoc()): ?>
        <tr>
            <td><?= $row['name'] ?></td>
            <td><?= $row['schedule'] ?></td>
            <td>
                <a href="edit_tournament.php?id=<?= $row['id'] ?>">Edit</a> |
                <a href="delete_tournament.php?id=<?= $row['id'] ?>">Delete</a>
            </td>
        </tr>
    <?php endwhile; ?>
</table>

<?php include('../includes/footer.php'); ?>