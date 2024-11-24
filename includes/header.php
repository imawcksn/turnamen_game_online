<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Game Tournament</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <style>
.sidebar {
    text-decoration: none;
    transition: text-shadow 0.3s ease;
    height: 100vh;
    width: 120px;
    position: fixed;
    top: 0;
    left: 0;
    background-color: #151314;
    padding-top: 20px;
    color: #fff;
}

.sidebar a {
    padding: 10px 15px;
    display: block;
    font-weight: bold;
    color: #fff;
    text-decoration: none;
}

.sidebar a:hover {
    background-color: #495057;
    text-shadow: 0 0 10px rgba(255, 255, 255, 1)
    , 0 0 18px rgba(255, 255, 255, 1)
, 0 0 25px rgba(255, 255, 255, 1);
    color: #029afe;
}

.sidebar a:active {
    text-shadow: 0 0 10px rgba(255, 255, 255, 1)
    , 0 0 18px rgba(255, 255, 255, 1)
, 0 0 25px rgba(255, 255, 255, 1);

    color: #029afe;
}

.sidebar a.selected {
    text-shadow: 0 0 10px rgba(255, 255, 255, 1)
    , 0 0 18px rgba(255, 255, 255, 1)
, 0 0 25px rgba(255, 255, 255, 1);

    color: #029afe;
}

.main-content {
    margin-left: 200px; /* Default margin */
    padding: 20px;
}

.sidebar-toggler {
    display: none;
}
.logo-container {
    display: flex;
    justify-content: center;
    align-items: center;
}
@media (max-width: 768px) {

    .sidebar {
        width: 200px; 
        transition: width 0.3s ease;
    }

    .main-content {
        margin-left: 200px; 
    }

    .sidebar a {
        text-align: center;
    }

    .sidebar-toggler {
        display: block;
        position: absolute;
        top: 10px;
        left: 10px;
        background-color: #007bff;
        color: #fff;
        border: none;
        padding: 10px;
        border-radius: 5px;
        z-index: 1000;
    }

    .sidebar.active {
        width: 250px;
    }

    .main-content.active {
        margin-left: 0; 
    }
    .logo-container {
    display: flex;
    justify-content: center;
    align-items: center;
}
}
    </style>
</head>

<body>
    <?php if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
; ?>
    <div class="sidebar" id="sidebar">
        <div class="logo-container">
            <img src="../images/OGT.svg" alt="Home" width="75" height="75">
        </div>
        
        <a href="home.php" class="<?php echo ($_SERVER['PHP_SELF'] == '/home.php') ? 'selected' : ''; ?>">Home</a>
        <?php if (isset($_SESSION['user'])): ?>
            <?php if ($_SESSION['user']['role'] == 'admin'): ?>
                <a href="admin-dashboard.php" class="<?php echo ($_SERVER['PHP_SELF'] == '/admin-dashboard.php') ? 'selected' : ''; ?>">Admin Dashboard</a>
                <a href="profile.php" class="<?php echo ($_SERVER['PHP_SELF'] == '/profile.php') ? 'selected' : ''; ?>">Profile</a>
                <a href="logout.php" class="<?php echo ($_SERVER['PHP_SELF'] == '/logout.php') ? 'selected' : ''; ?>">Logout</a>
            <?php elseif ($_SESSION['user']['role'] == 'user'): ?>
                <a href="profile.php" class="<?php echo ($_SERVER['PHP_SELF'] == '/profile.php') ? 'selected' : ''; ?>">Profile</a>
                <a href="logout.php" class="<?php echo ($_SERVER['PHP_SELF'] == '/logout.php') ? 'selected' : ''; ?>">Logout</a>
            <?php endif; ?>
        <?php else: ?>
            <a href="#" data-bs-toggle="modal" data-bs-target="#authModal" class="<?php echo ($_SERVER['PHP_SELF'] == '/login.php') ? 'selected' : ''; ?>">Login/Register</a>
        <?php endif; ?>
    </div>

    <button class="sidebar-toggler" onclick="toggleSidebar()">☰</button>

    <div class="modal fade" id="authModal" tabindex="-1" aria-labelledby="authModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="authModalLabel">Login / Register</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <ul class="nav nav-tabs" id="authTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="login-tab" data-bs-toggle="tab" href="#login" role="tab" aria-controls="login" aria-selected="true">Login</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="register-tab" data-bs-toggle="tab" href="#register" role="tab" aria-controls="register" aria-selected="false">Register</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="authTabsContent">
                        <div class="tab-pane fade show active" id="login" role="tabpanel" aria-labelledby="login-tab">
                            <form action="login-action.php" method="POST" class="mt-3">
                                <div class="mb-3">
                                    <label for="username" class="form-label">Username</label>
                                    <input type="text" class="form-control" id="username" name="username" required>
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" class="form-control" id="password" name="password" required>
                                </div>
                                <button type="submit" class="btn btn-primary w-100">Login</button>
                            </form>
                        </div>
                        <div class="tab-pane fade" id="register" role="tabpanel" aria-labelledby="register-tab">
                            <form action="register-action.php" method="POST" class="mt-3">
                                <div class="mb-3">
                                    <label for="reg-username" class="form-label">Username</label>
                                    <input type="text" class="form-control" id="reg-username" name="username" required>
                                </div>
                                <div class="mb-3">
                                    <label for="reg-email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="reg-email" name="email" required>
                                </div>
                                <div class="mb-3">
                                    <label for="reg-password" class="form-label">Password</label>
                                    <input type="password" class="form-control" id="reg-password" name="password" required>
                                </div>
                                <button type="submit" class="btn btn-primary w-100">Register</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('active');
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
