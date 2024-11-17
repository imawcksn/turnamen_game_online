<?php
include('../includes/header.php');

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

echo "<h1>Welcome, " . $_SESSION['user']['username'] . "</h1>";
?>


<?php include('../includes/footer.php'); ?>
