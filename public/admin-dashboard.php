<?php
include('../includes/header.php');
include('functions.php');

// check user role
if ($_SESSION['user']['role'] !== 'admin') {
    header('Location: index.php');
    exit;
}

// determine which table to fetch data from
$currentTable = isset($_GET['table']) ? $_GET['table'] : 'tournaments';

// Get data from the selected table
$data = getDataFromTable($currentTable);
?>
<div class="container mt-5">
    <h1>Dashboard Admin</h1>

    <!-- Buttons to switch tables -->
    <div class="d-flex justify-content-start mb-3">
        <a href="?table=tournaments" class="btn btn-secondary me-2 <?= $currentTable === 'tournaments' ? 'active' : '' ?>">Tournaments</a>
        <a href="?table=users" class="btn btn-secondary me-2 <?= $currentTable === 'users' ? 'active' : '' ?>">Users</a>
        <a href="?table=tournament_categories" class="btn btn-secondary me-2 <?= $currentTable === 'tournament_categories' ? 'active' : '' ?>">Games</a>
    </div>

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
                            <a href="edit.php?table=<?= $currentTable ?>&id=<?= $row['id'] ?>">Edit</a> |
                            <a href="delete.php?table=<?= $currentTable ?>&id=<?= $row['id'] ?>">Delete</a>
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

<?php include('../includes/footer.php'); ?>
