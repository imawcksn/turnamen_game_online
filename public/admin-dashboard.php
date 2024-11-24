<?php
include('../includes/header.php');
include('functions.php');

if ($_SESSION['user']['role'] !== 'admin') {
    header('Location: index.php');
    exit;
}
// ambil variabel dari url, misal table?=
$currentTable = isset($_GET['table']) ? $_GET['table'] : 'tournaments';

$data = getDataFromTable($currentTable);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">

        <!-- memakai campuran bootstrap dan custom css untuk fleksibilitas -->
         
    <style>
        body {
            background-color: #333;
            color: #029afe;
            font-family: Arial, sans-serif;
        }
        .container {
            max-width: 1400px;
            margin: auto;
            padding: 20px;
        }
        .btn-secondary.active {
            background-color: #029afe;
            color: #fff;
        }
        .btn-primary {
            background-color: #029afe;
            border-color: #029afe;
            color: #fff;
            font-weight: bold;
        }
        .btn-primary:hover {
            background-color: #029afe;
            border-color: #029afe;
        }
        .btn-secondary {
            background-color: #fff;
            color: #029afe;
        }
        .btn-secondary:hover {
            background-color: #fff;
            color: #029afe;
        }
        .table {
            background-color: #222;
            color: #fff;
        }
        .table thead {
            background-color: #444;
        }
        .table tbody tr:hover {
            background-color: #555;
        }
        h1 {
            color: #029afe;
            font-weight: bold;
        }
        th, td {
            text-align: center;
            vertical-align: middle;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <h1 class="text-left mb-4">Admin Dashboard</h1>

    <!-- Buttons utk switch tables, jika table?=tournaments maka button tournaments nyala -->
    <div class="d-flex justify-content-start mb-5">
        <a href="?table=tournaments" class="btn btn-secondary me-2 <?= $currentTable === 'tournaments' ? 'active' : '' ?>">Tournaments</a>
        <a href="?table=users" class="btn btn-secondary me-2 <?= $currentTable === 'users' ? 'active' : '' ?>">Users</a>
        <a href="?table=tournament_categories" class="btn btn-secondary me-2 <?= $currentTable === 'tournament_categories' ? 'active' : '' ?>">Games</a>
    </div>

    <!-- Add Data Button sesuai table ygg di select -->
    <div class="d-flex justify-content-start mb-3">
        <a href="add_data.php?table=<?= $currentTable ?>" class="btn btn-primary">Add <?= ucfirst($currentTable) ?></a>
    </div>

    <!-- Table Display sesuai table yg di select -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <?php if ($data->num_rows > 0): ?>
                    <?php foreach ($data->fetch_fields() as $field): ?>
                        <th><?= htmlspecialchars($field->name) ?></th>
                    <?php endforeach; ?>
                    <th>Action</th>
                <?php else: ?>
                    <th colspan="3">No data available</th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
        <!-- Get data dari table yg di select dan show di sini -->
        <!--Check apakah data tidak kosong -->
            <?php if ($data->num_rows > 0): ?>
                <!-- ambil data -->
                <?php while ($row = $data->fetch_assoc()): ?>
                    <tr>
                        <!-- setiap row, create table content-->
                        <?php foreach ($row as $value): ?>
                            <td><?= htmlspecialchars($value) ?></td>
                        <?php endforeach; ?>
                        <td>
                            <!-- action button sesuai row yang ditekan dan table yg dipilih -->
                            <a href="edit.php?table=<?= $currentTable ?>&id=<?= $row['id'] ?>" class="btn btn-sm btn-primary">Edit</a>
                            <a href="delete.php?table=<?= $currentTable ?>&id=<?= $row['id'] ?>" class="btn btn-sm btn-danger">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
            <!--bila data kosong, show ini -->
                <tr>
                    <td colspan="3">No records found in <?= htmlspecialchars($currentTable) ?></td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php include('../includes/footer.php'); ?>
