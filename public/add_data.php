<?php
include('../includes/header.php');
include('../public/functions.php');

if ($_SESSION['user']['role'] !== 'admin') {
    header('Location: index.php');
    exit;
}

// Get the table name from the query string
$table = isset($_GET['table']) ? $_GET['table'] : 'tournaments';

// Default fields based on table name (you can extend this for other tables)
$fields = [];
if ($table === 'tournaments') {
    $fields = [
        'name' => 'Tournament Name',
        'schedule' => 'Schedule (YYYY-MM-DD)',
    ];
} elseif ($table === 'users') {
    $fields = [
        'username' => 'Username',
        'email' => 'Email',
        'role' => 'Role',
    ];
} elseif ($table === 'games') {
    $fields = [
        'title' => 'Game Title',
        'genre' => 'Genre',
        'release_date' => 'Release Date (YYYY-MM-DD)',
    ];
} else {
    echo "<div class='alert alert-danger'>Unknown table: $table</div>";
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Prepare the query dynamically based on POST data
    $columns = implode(", ", array_keys($_POST));
    $values = "'" . implode("', '", array_map('mysqli_real_escape_string', array_values($_POST))) . "'";
    $query = "INSERT INTO $table ($columns) VALUES ($values)";
    
    if ($conn->query($query)) {
        header("Location: admin_dashboard.php?table=$table");
        exit;
    } else {
        $error = "Error inserting data: " . $conn->error;
    }
}
?>
<div class="container mt-5">
    <h1 class="text-center">Add Data to <?= ucfirst($table) ?></h1>
    <div class="card shadow-sm p-4">
        <form method="POST" class="needs-validation" novalidate>
            <?php foreach ($fields as $field => $label): ?>
                <div class="mb-3">
                    <label for="<?= $field ?>" class="form-label"><?= $label ?></label>
                    <input 
                        type="<?= $field === 'email' ? 'email' : 'text' ?>" 
                        class="form-control" 
                        id="<?= $field ?>" 
                        name="<?= $field ?>" 
                        placeholder="<?= $label ?>" 
                        required>
                    <div class="invalid-feedback">
                        Please provide a valid <?= strtolower($label) ?>.
                    </div>
                </div>
            <?php endforeach; ?>
            <button type="submit" class="btn btn-primary w-100">Add <?= ucfirst($table) ?></button>
        </form>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger mt-3"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
    </div>
</div>

<script>
// Bootstrap form validation
(() => {
    'use strict';
    const forms = document.querySelectorAll('.needs-validation');
    Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });
})();
</script>

<?php include('../includes/footer.php'); ?>
