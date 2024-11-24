<?php include('../includes/header.php'); ?>
<body style="background-color: #222">
    

<div class="container my-5" style="">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <!-- Proffile Card -->
            <div class="card shadow-lg border-0 rounded-4" style="background-color: #151314; color: #029afe;">
                <!-- Card header -->
                <div class="card-header text-center" style="background-color: #029afe; color: #000; border-radius: 10px 10px 0 0;">
                    <h3 class="mb-0">Profile</h3>
                </div>
                <!-- Card Body -->
                <div class="card-body p-4">
                    <!-- Profile Picture -->
                    <!-- User Info -->
                    <div class="text-center">
                        <h4 class="mb-1" style="color: #029afe;"><?php echo $_SESSION['user']['username']; ?></h4>
                        <p class="" style="color: #029afe;"><?php echo ucfirst($_SESSION['user']['role']); ?></p>
                    </div>
                    <hr style="border-top: 1px solid #029afe;">
                    <!-- profile Details -->
                    <div class="row mb-3">
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center py-2">
                                <span class="fw-bold" style="color: #029afe;">Email:</span>
                                <span class="" style="color: #029afe;"><?php echo $_SESSION['user']['email'] ?? 'Not Provided'; ?></span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center py-2">
                                <span class="fw-bold" style="color: #029afe;">Username:</span>
                                <span class="" style="color: #029afe;"><?php echo $_SESSION['user']['username']; ?></span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center py-2">
                                <span class="fw-bold" style="color: #029afe;">Password:</span>
                                <span class="" style="color: #029afe;">********</span>
                            </div>
                        </div>
                    </div>
                    <hr style="border-top: 1px solid #029afe;">
                    <!-- Buttons -->
                    <div class="d-flex justify-content-between">
                        <a href="edit-profile.php" class="btn w-48 rounded-pill" 
                            style="background-color: #029afe; color: #000; border: none;">Edit Profile</a>
                        <a href="logout.php" class="btn w-48 rounded-pill" 
                            style="background-color: #000; color: #029afe; border: 2px solid #029afe;">Logout</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
<?php include('../includes/footer.php'); ?>
