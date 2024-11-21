<?php include('../includes/header.php'); ?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <!-- Profile Card -->
            <div class="card shadow-lg border-0 rounded-4" style="background-color: #000; color: #FFD700;">
                <!-- Card Header -->
                <div class="card-header text-center" style="background-color: #FFD700; color: #000; border-radius: 10px 10px 0 0;">
                    <h3 class="mb-0">Profile</h3>
                </div>
                <!-- Card Body -->
                <div class="card-body p-4">
                    <!-- Profile Picture -->
                    <div class="text-center mb-4">
                        <img src="https://via.placeholder.com/150" alt="Profile Picture" 
                            class="rounded-circle img-fluid border border-3" 
                            style="width: 150px; height: 150px; border-color: #FFD700;">
                    </div>
                    <!-- User Info -->
                    <div class="text-center">
                        <h4 class="mb-1" style="color: #FFD700;"><?php echo $_SESSION['user']['username']; ?></h4>
                        <p class="text-muted" style="color: #CCCCCC;"><?php echo ucfirst($_SESSION['user']['role']); ?></p>
                    </div>
                    <hr style="border-top: 1px solid #FFD700;">
                    <!-- Profile Details -->
                    <div class="row mb-3">
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center py-2">
                                <span class="fw-bold" style="color: #FFD700;">Email:</span>
                                <span class="text-muted" style="color: #CCCCCC;"><?php echo $_SESSION['user']['email'] ?? 'Not Provided'; ?></span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center py-2">
                                <span class="fw-bold" style="color: #FFD700;">Username:</span>
                                <span class="text-muted" style="color: #CCCCCC;"><?php echo $_SESSION['user']['username']; ?></span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center py-2">
                                <span class="fw-bold" style="color: #FFD700;">Password:</span>
                                <span class="text-muted" style="color: #CCCCCC;">********</span>
                            </div>
                        </div>
                    </div>
                    <hr style="border-top: 1px solid #FFD700;">
                    <!-- Buttons -->
                    <div class="d-flex justify-content-between">
                        <a href="edit-profile.php" class="btn w-48 rounded-pill" 
                            style="background-color: #FFD700; color: #000; border: none;">Edit Profile</a>
                        <a href="logout.php" class="btn w-48 rounded-pill" 
                            style="background-color: #000; color: #FFD700; border: 2px solid #FFD700;">Logout</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('../includes/footer.php'); ?>
