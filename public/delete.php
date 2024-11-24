<?php
session_start();
include('../public/functions.php');

if ($_SESSION['user']['role'] !== 'admin') {
    header('Location: home.php');
    exit;
}   

if (!isset($_GET['table']) || !isset($_GET['id'])) {
    exit(header('Location: admin-dashboard.php'));

}

$table = htmlspecialchars($_GET['table']);
$id = intval($_GET['id']);

// coba delete record
$result = deleteRecord($table, $id);

if ($result === true) {
    header("Location: admin-dashboard.php?table=$table&message=Record deleted successfully");
    exit;
} else {
    // display error message
    echo "<div class='alert alert-danger'>Failed to delete record: $result</div>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Record</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #333;
            color: #029afe;
            font-family: Arial, sans-serif;
            text-align: center;
            padding: 50px;
        }
        a {
            color: #029afe;
        }
    </style>
</head>
<body>
    <h1>Delete Record</h1>
    <p><?= isset($result) && $result !== true ? $result : 'Invalid operation.' ?></p>
    <a href="admin_dashboard.php?table=<?= $table ?>" class="btn btn-primary">Return to Dashboard</a>
</body>
</html>
