
<?php
session_start();
include('../includes/header.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

echo "<h1>Welcome, " . $_SESSION['username'] . "</h1>";
?>


<?php include('../includes/footer.php'); ?>
