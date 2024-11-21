<?php
include('../includes/header.php');
include('functions.php');

// Check user role
if ($_SESSION['user']['role'] !== 'admin') {
    header('Location: index.php');
    exit;
}

// Determine which table to fetch data from
$currentTable = isset($_GET['table']) ? $_GET['table'] : 'tournaments';

// Get data from the selected table
$data = getDataFromTable($currentTable);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #333;
            color: #f1c40f;
            font-family: Arial, sans-serif;
        }
        .container {
            max-width: 1200px;
            margin: auto;
            padding: 20px;
        }
        .btn-secondary.active {
            background-color: #f1c40f !important;
            color: #333 !important;
        }
        .btn-primary {
            background-color: #f1c40f;
            border-color: #f1c40f;
            color: #333;
            font-weight: bold;
        }
        .btn-primary:hover {
            background-color: #e1b707;
            border-color: #e1b707;
        }
        .btn-secondary {
            background-color: #444;
            color: #f1c40f;
        }
        .btn-secondary:hover {
            background-color: #555;
            color: #f1c40f;
        }
        .table {
            background-color: #222;
            color: #f1c40f;
        }
        .table thead {
            background-color: #444;
        }
        .table tbody tr:hover {
            background-color: #555;
        }
        h1 {
            color: #f1c40f;
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
    <h1 class="text-center mb-4">Admin Dashboard</h1>

    <!-- Buttons to switch tables -->
    <div class="d-flex justify-content-start mb-3">
        <a href="?table=tournaments" class="btn btn-secondary me-2 <?= $currentTable === 'tournaments' ? 'active' : '' ?>">Tournaments</a>
        <a href="?table=users" class="btn btn-secondary me-2 <?= $currentTable === 'users' ? 'active' : '' ?>">Users</a>
        <a href="?table=tournament_categories" class="btn btn-secondary me-2 <?= $currentTable === 'tournament_categories' ? 'active' : '' ?>">Games</a>
    </div>

    <!-- Add Data Button -->
    <div class="d-flex justify-content-end mb-3">
        <a href="add_data.php?table=<?= $currentTable ?>" class="btn btn-primary">Add <?= ucfirst($currentTable) ?></a>
    </div>

    <!-- Table Display -->
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
            <?php if ($data->num_rows > 0): ?>
                <?php while ($row = $data->fetch_assoc()): ?>
                    <tr>
                        <?php foreach ($row as $value): ?>
                            <td><?= htmlspecialchars($value) ?></td>
                        <?php endforeach; ?>
                        <td>
                            <a href="edit.php?table=<?= $currentTable ?>&id=<?= $row['id'] ?>" class="btn btn-sm btn-primary">Edit</a>
                            <a href="delete.php?table=<?= $currentTable ?>&id=<?= $row['id'] ?>" class="btn btn-sm btn-danger">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
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
