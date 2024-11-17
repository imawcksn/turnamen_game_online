<?php include('../includes/header.php'); ?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <!-- Profile Card -->
            <div class="card shadow-lg rounded-3">
                <div class="card-header text-center bg-primary text-white">
                    <h3>Profile</h3>
                </div>
                <div class="card-body">
                    <!-- Profile Picture -->
                    <div class="text-center mb-4">
                        <img src="https://via.placeholder.com/150" alt="Profile Picture" class="rounded-circle img-fluid" style="max-width: 150px;">
                    </div>
                    <!-- User Info -->
                    <div class="text-center">
                        <h4><?php echo $_SESSION['user']['username']; ?></h4>
                        <p class="text-muted"><?php echo $_SESSION['user']['role']; ?></p>
                    </div>
                    <hr>
                    <!-- Profile Details -->
                    <div class="row">
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center">
                                <strong>Email:</strong>
                                <p class="text-muted"><?php echo $_SESSION['user']['email'] ?? 'Not Provided'; ?></p>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <strong>Username:</strong>
                                <p class="text-muted"><?php echo $_SESSION['user']['username']; ?></p>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <strong>Password:</strong>
                                <p class="text-muted"><?php echo $_SESSION['user']['password']; ?></p>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <!-- Buttons -->
                    <div class="text-center">
                        <a href="edit-profile.php" class="btn btn-primary mb-2">Edit Profile</a>
                        <a href="logout.php" class="btn btn-danger">Logout</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('../includes/footer.php'); ?>
